<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\service;

class content_repository
{
    protected $db;
    protected $auth;
    protected $user;
    protected $root_path;
    protected $php_ext;
    protected $forums_table;
    protected $topics_table;
    protected $posts_table;

    public function __construct(
        \phpbb\db\driver\driver_interface $db,
        \phpbb\auth\auth $auth,
        \phpbb\user $user,
        $root_path,
        $php_ext,
        $forums_table,
        $topics_table,
        $posts_table
    )
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->user = $user;
        $this->root_path = $root_path;
        $this->php_ext = $php_ext;
        $this->forums_table = $forums_table;
        $this->topics_table = $topics_table;
        $this->posts_table = $posts_table;

        if (!function_exists('generate_text_for_display'))
        {
            include_once $this->root_path . 'includes/functions_content.' . $this->php_ext;
        }
    }

    public function resolve_forum_id($configured_id, array $fallback_names)
    {
        $configured_id = (int) $configured_id;
        if ($configured_id > 0 && $this->forum_exists($configured_id))
        {
            return $configured_id;
        }

        foreach ($fallback_names as $name)
        {
            $sql = 'SELECT forum_id
                FROM ' . $this->forums_table . '
                WHERE ' . $this->db->sql_build_array('SELECT', array('forum_name' => $name));
            $result = $this->db->sql_query_limit($sql, 1);
            $forum_id = (int) $this->db->sql_fetchfield('forum_id');
            $this->db->sql_freeresult($result);

            if ($forum_id > 0)
            {
                return $forum_id;
            }
        }

        return 0;
    }

    public function forum_url($forum_id)
    {
        return append_sid($this->root_path . 'viewforum.' . $this->php_ext, 'f=' . (int) $forum_id);
    }

    public function get_child_forums($parent_id, $limit)
    {
        $rows = array();
        $parent_id = (int) $parent_id;
        $limit = max(1, min(20, (int) $limit));

        $sql = 'SELECT forum_id, forum_name, forum_desc, forum_desc_uid, forum_desc_bitfield, forum_desc_options, forum_type, forum_status, forum_password
            FROM ' . $this->forums_table . '
            WHERE parent_id = ' . $parent_id . '
            ORDER BY left_id ASC';
        $result = $this->db->sql_query_limit($sql, $limit * 2);

        while ($row = $this->db->sql_fetchrow($result))
        {
            $forum_id = (int) $row['forum_id'];
            if (!$this->can_list_forum($forum_id) || (string) $row['forum_password'] !== '')
            {
                continue;
            }

            $rows[] = array(
                'FORUM_ID'          => $forum_id,
                'FORUM_NAME'        => htmlspecialchars(censor_text($row['forum_name']), ENT_QUOTES, 'UTF-8'),
                'FORUM_DESCRIPTION' => $this->plain_text($row['forum_desc'], $row['forum_desc_uid'], $row['forum_desc_bitfield'], (int) $row['forum_desc_options'], 260),
                'U_FORUM'           => $this->forum_url($forum_id),
                'S_LOCKED'          => ((int) $row['forum_status'] === ITEM_LOCKED),
            );

            if (count($rows) >= $limit)
            {
                break;
            }
        }
        $this->db->sql_freeresult($result);

        return $rows;
    }

    public function get_leaf_forums($parent_id, $limit)
    {
        $parent = $this->get_forum_row((int) $parent_id);
        if (!$parent)
        {
            return array();
        }

        $limit = max(1, min(20, (int) $limit));
        $candidates = array();
        $parents = array();

        $sql = 'SELECT forum_id, parent_id, forum_name, forum_desc, forum_desc_uid, forum_desc_bitfield, forum_desc_options, forum_type, forum_status, forum_password, left_id
            FROM ' . $this->forums_table . '
            WHERE left_id > ' . (int) $parent['left_id'] . '
                AND right_id < ' . (int) $parent['right_id'] . '
            ORDER BY left_id ASC';
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetchrow($result))
        {
            $candidates[] = $row;
            $parents[(int) $row['parent_id']] = true;
        }
        $this->db->sql_freeresult($result);

        $rows = array();
        foreach ($candidates as $row)
        {
            $forum_id = (int) $row['forum_id'];
            if ((int) $row['forum_type'] !== FORUM_POST || isset($parents[$forum_id]) || !$this->can_list_forum($forum_id) || (string) $row['forum_password'] !== '')
            {
                continue;
            }

            $rows[] = array(
                'FORUM_ID'          => $forum_id,
                'FORUM_NAME'        => htmlspecialchars(censor_text($row['forum_name']), ENT_QUOTES, 'UTF-8'),
                'FORUM_DESCRIPTION' => $this->plain_text($row['forum_desc'], $row['forum_desc_uid'], $row['forum_desc_bitfield'], (int) $row['forum_desc_options'], 260),
                'U_FORUM'           => $this->forum_url($forum_id),
                'S_LOCKED'          => ((int) $row['forum_status'] === ITEM_LOCKED),
            );

            if (count($rows) >= $limit)
            {
                break;
            }
        }

        return $rows;
    }

    public function get_topics($forum_id, $limit, $recursive, $order_last_post)
    {
        $forum_ids = $this->readable_forum_ids_under((int) $forum_id, (bool) $recursive);
        if (empty($forum_ids))
        {
            return array();
        }

        return $this->fetch_topics($forum_ids, $limit, $order_last_post ? 't.topic_last_post_time DESC' : 't.topic_time DESC', 210);
    }


    public function get_topics_by_ids(array $topic_ids, $limit)
    {
        $topic_ids = array_values(array_unique(array_filter(array_map('intval', $topic_ids), function ($id)
        {
            return $id > 0;
        })));

        if (empty($topic_ids))
        {
            return array();
        }

        $limit = max(1, min(12, (int) $limit));
        $sql = 'SELECT t.topic_id, t.forum_id, t.topic_title, t.topic_time, t.topic_last_post_time,
                f.forum_name, f.forum_password,
                p.post_text, p.bbcode_uid, p.bbcode_bitfield, p.enable_bbcode, p.enable_smilies, p.enable_magic_url
            FROM ' . $this->topics_table . ' t
            INNER JOIN ' . $this->forums_table . ' f ON f.forum_id = t.forum_id
            INNER JOIN ' . $this->posts_table . ' p ON p.post_id = t.topic_first_post_id
            WHERE ' . $this->db->sql_in_set('t.topic_id', $topic_ids) . '
                AND t.topic_visibility = ' . ITEM_APPROVED . '
                AND p.post_visibility = ' . ITEM_APPROVED . '
                AND t.topic_moved_id = 0';
        $result = $this->db->sql_query($sql);

        $by_id = array();
        while ($row = $this->db->sql_fetchrow($result))
        {
            $forum_id = (int) $row['forum_id'];
            if ((string) $row['forum_password'] !== '' || !$this->can_read_forum($forum_id))
            {
                continue;
            }

            $by_id[(int) $row['topic_id']] = $this->format_topic_row($row, 210);
        }
        $this->db->sql_freeresult($result);

        $rows = array();
        foreach ($topic_ids as $topic_id)
        {
            if (isset($by_id[$topic_id]))
            {
                $rows[] = $by_id[$topic_id];
            }
            if (count($rows) >= $limit)
            {
                break;
            }
        }

        return $rows;
    }

    public function get_recent_topics($limit)
    {
        $forum_ids = $this->all_readable_post_forums();
        if (empty($forum_ids))
        {
            return array();
        }

        return $this->fetch_topics($forum_ids, $limit, 't.topic_last_post_time DESC', 170);
    }

    protected function fetch_topics(array $forum_ids, $limit, $order_by, $excerpt_limit)
    {
        $limit = max(1, min(20, (int) $limit));
        $excerpt_limit = max(120, min(320, (int) $excerpt_limit));
        $sql = 'SELECT t.topic_id, t.forum_id, t.topic_title, t.topic_time, t.topic_last_post_time,
                f.forum_name,
                p.post_text, p.bbcode_uid, p.bbcode_bitfield, p.enable_bbcode, p.enable_smilies, p.enable_magic_url
            FROM ' . $this->topics_table . ' t
            INNER JOIN ' . $this->forums_table . ' f ON f.forum_id = t.forum_id
            INNER JOIN ' . $this->posts_table . ' p ON p.post_id = t.topic_first_post_id
            WHERE ' . $this->db->sql_in_set('t.forum_id', $forum_ids) . '
                AND t.topic_visibility = ' . ITEM_APPROVED . '
                AND p.post_visibility = ' . ITEM_APPROVED . '
                AND t.topic_moved_id = 0
            ORDER BY ' . $order_by;
        $result = $this->db->sql_query_limit($sql, $limit);

        $rows = array();
        while ($row = $this->db->sql_fetchrow($result))
        {
            $rows[] = $this->format_topic_row($row, $excerpt_limit);
        }
        $this->db->sql_freeresult($result);

        return $rows;
    }


    protected function format_topic_row(array $row, $excerpt_limit)
    {
        $parse_flags = (!empty($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0)
            | (!empty($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0)
            | (!empty($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);

        return array(
            'TOPIC_ID'               => (int) $row['topic_id'],
            'TOPIC_TITLE'            => htmlspecialchars(censor_text($row['topic_title']), ENT_QUOTES, 'UTF-8'),
            'TOPIC_EXCERPT'          => $this->topic_excerpt($row['post_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $parse_flags, $row['topic_title'], $excerpt_limit),
            'TOPIC_DATE'             => $this->user->format_date((int) $row['topic_time']),
            'TOPIC_DATE_RFC3339'     => gmdate('Y-m-d\TH:i:s\Z', (int) $row['topic_time']),
            'TOPIC_LASTDATE'         => $this->user->format_date((int) $row['topic_last_post_time']),
            'TOPIC_LASTDATE_RFC3339' => gmdate('Y-m-d\TH:i:s\Z', (int) $row['topic_last_post_time']),
            'FORUM_NAME'             => htmlspecialchars(censor_text($row['forum_name']), ENT_QUOTES, 'UTF-8'),
            'U_TOPIC'                => append_sid($this->root_path . 'viewtopic.' . $this->php_ext, 't=' . (int) $row['topic_id']),
            'U_FORUM'                => $this->forum_url((int) $row['forum_id']),
        );
    }

    protected function readable_forum_ids_under($forum_id, $recursive)
    {
        $forum_id = (int) $forum_id;
        $forum = $this->get_forum_row($forum_id);
        if (!$forum)
        {
            return array();
        }

        $ids = array();
        if ((int) $forum['forum_type'] === FORUM_POST && (string) $forum['forum_password'] === '' && $this->can_read_forum($forum_id))
        {
            $ids[] = $forum_id;
        }

        if (!$recursive)
        {
            return $ids;
        }

        $sql = 'SELECT forum_id, forum_type, forum_password
            FROM ' . $this->forums_table . '
            WHERE left_id > ' . (int) $forum['left_id'] . '
                AND right_id < ' . (int) $forum['right_id'] . '
            ORDER BY left_id ASC';
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetchrow($result))
        {
            $id = (int) $row['forum_id'];
            if ((int) $row['forum_type'] === FORUM_POST && (string) $row['forum_password'] === '' && $this->can_read_forum($id))
            {
                $ids[] = $id;
            }
        }
        $this->db->sql_freeresult($result);

        return array_values(array_unique($ids));
    }

    protected function all_readable_post_forums()
    {
        $ids = array();
        $sql = 'SELECT forum_id
            FROM ' . $this->forums_table . '
            WHERE forum_type = ' . FORUM_POST . " AND forum_password = ''" . '
            ORDER BY left_id ASC';
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetchrow($result))
        {
            $id = (int) $row['forum_id'];
            if ($this->can_read_forum($id))
            {
                $ids[] = $id;
            }
        }
        $this->db->sql_freeresult($result);
        return $ids;
    }

    protected function can_list_forum($forum_id)
    {
        return $this->auth->acl_get('f_list', (int) $forum_id);
    }

    protected function can_read_forum($forum_id)
    {
        return $this->auth->acl_get('f_list', (int) $forum_id) && $this->auth->acl_get('f_read', (int) $forum_id);
    }

    protected function forum_exists($forum_id)
    {
        return (bool) $this->get_forum_row((int) $forum_id);
    }

    protected function get_forum_row($forum_id)
    {
        $sql = 'SELECT forum_id, forum_type, left_id, right_id, forum_password
            FROM ' . $this->forums_table . '
            WHERE forum_id = ' . (int) $forum_id;
        $result = $this->db->sql_query_limit($sql, 1);
        $row = $this->db->sql_fetchrow($result);
        $this->db->sql_freeresult($result);
        return $row ?: false;
    }

    protected function plain_text($text, $uid, $bitfield, $options, $limit)
    {
        return htmlspecialchars($this->truncate_text($this->plain_text_raw($text, $uid, $bitfield, $options), $limit), ENT_QUOTES, 'UTF-8');
    }

    protected function topic_excerpt($text, $uid, $bitfield, $options, $topic_title, $limit)
    {
        $plain = $this->plain_text_raw($text, $uid, $bitfield, $options);
        $title = trim(censor_text((string) $topic_title));

        if ($title !== '' && utf8_strlen($plain) >= utf8_strlen($title))
        {
            $prefix = utf8_substr($plain, 0, utf8_strlen($title));
            if (utf8_strtolower($prefix) === utf8_strtolower($title))
            {
                $remainder = utf8_substr($plain, utf8_strlen($title));
                if ($remainder === '' || preg_match('/^[\s:;,.!?\-–—|]/u', $remainder))
                {
                    $plain = preg_replace('/^[\s:;,.!?\-–—|]+/u', '', $remainder);
                }
            }
        }

        return htmlspecialchars($this->truncate_text($plain, $limit), ENT_QUOTES, 'UTF-8');
    }

    protected function plain_text_raw($text, $uid, $bitfield, $options)
    {
        // Let phpBB render its own BBCode, smileys and magic URLs first.
        // The excerpt is then derived from the rendered result rather than from raw BBCode.
        $text = generate_text_for_display((string) $text, (string) $uid, (string) $bitfield, (int) $options);

        // Preserve natural word boundaries for block/list markup before stripping HTML.
        $text = preg_replace('#<(?:br\s*/?|/p|/div|/li|/ul|/ol|/blockquote|/pre|/h[1-6])\s*>#iu', ' ', $text);
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8');

        // Defensive cleanup for residual BBCode markers from malformed or legacy content.
        // This includes list-item markers and the empty [] fragment observed in excerpts.
        $text = preg_replace('/\[(?:\/)?(?:[a-z][a-z0-9_]*|\*)(?::[a-z0-9]+)?(?:=[^\]]*)?\]/iu', ' ', $text);
        $text = preg_replace('/\[\s*\]/u', ' ', $text);
        $text = preg_replace('/\s+/u', ' ', trim($text));

        return censor_text($text);
    }

    protected function truncate_text($text, $limit)
    {
        $limit = max(40, (int) $limit);
        if (utf8_strlen($text) <= $limit)
        {
            return $text;
        }

        $cut = rtrim(utf8_substr($text, 0, $limit - 1));
        if (preg_match('/^(.{20,})\s+\S*$/us', $cut, $matches))
        {
            $cut = rtrim($matches[1]);
        }

        return rtrim($cut, " \t\n\r\0\x0B,.;:!?-–—") . '…';
    }
}
