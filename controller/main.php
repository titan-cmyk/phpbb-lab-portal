<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use phpbblab\portal\service\text_codec;

class main
{
    protected $config;
    protected $config_text;
    protected $template;
    protected $user;
    protected $auth;
    protected $helper;
    protected $content;
    protected $root_path;
    protected $php_ext;

    protected $block_keys = array(
        'announcement',
        'support',
        'featured',
        'guides',
        'downloads',
        'recent',
        'links',
        'community',
        'stats',
    );

    public function __construct(
        \phpbb\config\config $config,
        \phpbb\config\db_text $config_text,
        \phpbb\template\template $template,
        \phpbb\user $user,
        \phpbb\auth\auth $auth,
        \phpbb\controller\helper $helper,
        \phpbblab\portal\service\content_repository $content,
        $root_path,
        $php_ext
    )
    {
        $this->config = $config;
        $this->config_text = $config_text;
        $this->template = $template;
        $this->user = $user;
        $this->auth = $auth;
        $this->helper = $helper;
        $this->content = $content;
        $this->root_path = $root_path;
        $this->php_ext = $php_ext;
    }

    public function home()
    {
        $this->user->add_lang_ext('phpbblab/portal', 'common');

        if (empty($this->config['phpbblab_portal_enabled']))
        {
            return new RedirectResponse(append_sid($this->root_path . 'index.' . $this->php_ext));
        }

        if (empty($this->config['phpbblab_portal_as_homepage']))
        {
            $this->template->assign_block_vars('navlinks', array(
                'BREADCRUMB_NAME' => $this->user->lang('PHPBBLAB_PORTAL_NAV'),
                'U_BREADCRUMB'    => $this->helper->route('phpbblab_portal_home'),
            ));
        }

        $support_id = $this->resolve_source_id(
            'phpbblab_portal_support_forum_id',
            array('Support général phpBB', 'Support général', 'Support')
        );
        $docs_id = $this->resolve_source_id(
            'phpbblab_portal_docs_forum_id',
            array('Tutoriels et Documentation phpBB', 'Tutoriels et documentation phpBB', 'Tutoriels et documentation', 'Documentation', 'Tutoriels')
        );
        $guides_id = $this->resolve_source_id(
            'phpbblab_portal_guides_forum_id',
            array('Guides et solutions phpBB', 'Guides et solutions', 'Guides')
        );
        $downloads_id = $this->resolve_source_id(
            'phpbblab_portal_downloads_forum_id',
            array('Centre de téléchargements', 'Téléchargements', 'Downloads')
        );
        $community_id = $this->resolve_source_id(
            'phpbblab_portal_community_forum_id',
            array('Communauté PHPBB Lab', 'Communauté', 'Community')
        );

        $text_keys = array(
            'phpbblab_portal_title',
            'phpbblab_portal_intro',
            'phpbblab_portal_meta_description',
            'phpbblab_portal_featured_topic_ids',
            'phpbblab_portal_announcement_body',
            'phpbblab_portal_announcement_url',
            'phpbblab_portal_announcement_link_label',
            'phpbblab_portal_custom_links',
            'phpbblab_portal_action_forum_label',
            'phpbblab_portal_action_primary_label',
            'phpbblab_portal_action_secondary_label',
            'phpbblab_portal_action_resources_label',
        );
        foreach ($this->block_keys as $key)
        {
            $text_keys[] = 'phpbblab_portal_block_title_' . $key;
        }

        $stored_text = $this->config_text->get_array($text_keys);
        $text = array();
        foreach ($text_keys as $key)
        {
            $text[$key] = text_codec::decode(isset($stored_text[$key]) ? (string) $stored_text[$key] : '');
        }

        $title = trim($text['phpbblab_portal_title']);
        if ($title === '')
        {
            $title = $this->user->lang('PHPBBLAB_PORTAL_DEFAULT_TITLE', isset($this->config['sitename']) ? $this->config['sitename'] : 'phpBB');
        }

        $intro = trim($text['phpbblab_portal_intro']);
        if ($intro === '')
        {
            $intro = $this->user->lang('PHPBBLAB_PORTAL_DEFAULT_INTRO', isset($this->config['sitename']) ? $this->config['sitename'] : 'phpBB');
        }

        $meta_description = trim($text['phpbblab_portal_meta_description']);
        if ($meta_description === '')
        {
            $meta_description = $intro;
        }

        $blocks = array();
        $sequence = 0;

        if ($this->block_enabled('announcement') && $this->can_view_block('announcement'))
        {
            $body = trim($text['phpbblab_portal_announcement_body']);
            if ($body !== '')
            {
                $url = $this->safe_url($text['phpbblab_portal_announcement_url']);
                $label = trim($text['phpbblab_portal_announcement_link_label']);
                if ($label === '')
                {
                    $label = $this->user->lang('PHPBBLAB_PORTAL_ANNOUNCEMENT_MORE');
                }

                $blocks[] = $this->make_block('announcement', $sequence++, array(
                    'S_ANNOUNCEMENT'      => true,
                    'ANNOUNCEMENT_BODY'   => htmlspecialchars($body, ENT_QUOTES, 'UTF-8'),
                    'U_ANNOUNCEMENT'      => $url !== '' ? htmlspecialchars($url, ENT_QUOTES, 'UTF-8') : '',
                    'ANNOUNCEMENT_LINK'   => htmlspecialchars($label, ENT_QUOTES, 'UTF-8'),
                    'S_ANNOUNCEMENT_LINK' => $url !== '',
                ), $text);
            }
        }

        if ($this->block_enabled('support') && $this->can_view_block('support') && $support_id)
        {
            $rows = $this->content->get_child_forums($support_id, 8);
            if (!empty($rows))
            {
                $blocks[] = $this->make_block('support', $sequence++, array('S_SUPPORT' => true), $text, $rows);
            }
        }

        if ($this->block_enabled('featured') && $this->can_view_block('featured'))
        {
            $topic_ids = $this->parse_id_list($text['phpbblab_portal_featured_topic_ids']);
            if (!empty($topic_ids))
            {
                $rows = $this->content->get_topics_by_ids($topic_ids, 6);
                if (!empty($rows))
                {
                    $blocks[] = $this->make_block('featured', $sequence++, array('S_FEATURED' => true), $text, $rows);
                }
            }
        }

        if ($this->block_enabled('guides') && $this->can_view_block('guides') && $guides_id)
        {
            $rows = $this->content->get_topics($guides_id, $this->limit('phpbblab_portal_guides_limit', 4, 1, 10), true, false);
            if (!empty($rows))
            {
                $blocks[] = $this->make_block('guides', $sequence++, array('S_GUIDES' => true), $text, $rows);
            }
        }

        if ($this->block_enabled('downloads') && $this->can_view_block('downloads') && $downloads_id)
        {
            $rows = $this->content->get_leaf_forums($downloads_id, 8);
            if (!empty($rows))
            {
                $blocks[] = $this->make_block('downloads', $sequence++, array('S_DOWNLOADS' => true), $text, $rows);
            }
        }

        if ($this->block_enabled('recent') && $this->can_view_block('recent'))
        {
            $rows = $this->content->get_recent_topics($this->limit('phpbblab_portal_recent_limit', 6, 1, 12));
            if (!empty($rows))
            {
                $blocks[] = $this->make_block('recent', $sequence++, array('S_RECENT' => true), $text, $rows);
            }
        }

        if ($this->block_enabled('links') && $this->can_view_block('links'))
        {
            $rows = $this->parse_custom_links($text['phpbblab_portal_custom_links']);
            if (!empty($rows))
            {
                $blocks[] = $this->make_block('links', $sequence++, array('S_LINKS' => true), $text, $rows);
            }
        }

        if ($this->block_enabled('community') && $this->can_view_block('community') && $community_id)
        {
            $rows = $this->content->get_child_forums($community_id, 8);
            if (!empty($rows))
            {
                $blocks[] = $this->make_block('community', $sequence++, array('S_COMMUNITY' => true), $text, $rows);
            }
        }

        if ($this->block_enabled('stats') && $this->can_view_block('stats'))
        {
            $blocks[] = $this->make_block('stats', $sequence++, array(
                'S_STATS'           => true,
                'PORTAL_STAT_USERS' => isset($this->config['num_users']) ? (int) $this->config['num_users'] : 0,
                'PORTAL_STAT_TOPICS'=> isset($this->config['num_topics']) ? (int) $this->config['num_topics'] : 0,
                'PORTAL_STAT_POSTS' => isset($this->config['num_posts']) ? (int) $this->config['num_posts'] : 0,
            ), $text);
        }

        usort($blocks, function ($a, $b)
        {
            if ($a['_ORDER'] === $b['_ORDER'])
            {
                return $a['_SEQUENCE'] <=> $b['_SEQUENCE'];
            }
            return $a['_ORDER'] <=> $b['_ORDER'];
        });

        foreach ($blocks as $block)
        {
            $rows = isset($block['_ROWS']) ? $block['_ROWS'] : array();
            unset($block['_ROWS'], $block['_ORDER'], $block['_SEQUENCE']);
            $this->template->assign_block_vars('portal_blocks', $block);
            foreach ($rows as $row)
            {
                $this->template->assign_block_vars('portal_blocks.items', $row);
            }
        }

        $support_action_visible = $support_id && $this->can_view_block('support');
        $docs_action_visible = $docs_id && $this->can_view_block('guides');
        $downloads_action_visible = $downloads_id && $this->can_view_block('downloads');
        $forum_index_url = append_sid($this->root_path . 'index.' . $this->php_ext);

        $action_forum_label = $this->action_label($text, 'phpbblab_portal_action_forum_label', 'PHPBBLAB_PORTAL_GO_FORUM');
        $action_primary_label = $this->action_label($text, 'phpbblab_portal_action_primary_label', 'PHPBBLAB_PORTAL_GO_SUPPORT');
        $action_secondary_label = $this->action_label($text, 'phpbblab_portal_action_secondary_label', 'PHPBBLAB_PORTAL_GO_DOCS');
        $action_resources_label = $this->action_label($text, 'phpbblab_portal_action_resources_label', 'PHPBBLAB_PORTAL_GO_DOWNLOADS');

        $this->template->assign_vars(array(
            'PHPBBLAB_PORTAL_TITLE'            => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'PHPBBLAB_PORTAL_INTRO'            => htmlspecialchars($intro, ENT_QUOTES, 'UTF-8'),
            'PHPBBLAB_PORTAL_META_DESCRIPTION' => htmlspecialchars($meta_description, ENT_QUOTES, 'UTF-8'),
            'U_PORTAL_FORUM'                   => $forum_index_url,
            'U_PORTAL_SUPPORT'                 => $support_action_visible ? $this->content->forum_url($support_id) : '',
            'U_PORTAL_DOCS'                    => $docs_action_visible ? $this->content->forum_url($docs_id) : '',
            'U_PORTAL_DOWNLOADS'               => $downloads_action_visible ? $this->content->forum_url($downloads_id) : '',
            'S_PORTAL_ACTION_FORUM'             => true,
            'S_PORTAL_ACTION_SUPPORT'           => (bool) $support_action_visible,
            'S_PORTAL_ACTION_DOCS'              => (bool) $docs_action_visible,
            'S_PORTAL_ACTION_DOWNLOADS'         => (bool) $downloads_action_visible,
            'S_PORTAL_PRIMARY_ACTIONS'          => true,
            'PORTAL_ACTION_FORUM_LABEL'         => htmlspecialchars($action_forum_label, ENT_QUOTES, 'UTF-8'),
            'PORTAL_ACTION_PRIMARY_LABEL'       => htmlspecialchars($action_primary_label, ENT_QUOTES, 'UTF-8'),
            'PORTAL_ACTION_SECONDARY_LABEL'     => htmlspecialchars($action_secondary_label, ENT_QUOTES, 'UTF-8'),
            'PORTAL_ACTION_RESOURCES_LABEL'     => htmlspecialchars($action_resources_label, ENT_QUOTES, 'UTF-8'),
            'S_PHPBBLAB_PORTAL_PAGE'            => true,
            'S_PHPBBLAB_PORTAL_CREDIT'          => !empty($this->config['phpbblab_portal_show_credit']),
            'U_PHPBBLAB_PORTAL_CREDIT'          => 'https://phpbb-lab.com/',
            'PHPBBLAB_PORTAL_VERSION'           => isset($this->config['phpbblab_portal_version']) ? $this->config['phpbblab_portal_version'] : '1.2.0',
        ));

        return $this->helper->render('phpbblab_portal.html', $title);
    }

    protected function resolve_source_id($config_key, array $fallback_names)
    {
        $configured_id = $this->config_int($config_key, 0);
        if ($configured_id < 0)
        {
            return 0;
        }

        return $this->content->resolve_forum_id($configured_id, $fallback_names);
    }

    protected function action_label(array $text, $key, $default_lang_key)
    {
        $label = isset($text[$key]) ? trim($text[$key]) : '';
        return $label !== '' ? $label : $this->user->lang($default_lang_key);
    }

    protected function make_block($key, $sequence, array $vars, array $text, array $rows = array())
    {
        $default_lang = array(
            'announcement' => 'PHPBBLAB_PORTAL_ANNOUNCEMENT',
            'support'      => 'PHPBBLAB_PORTAL_SUPPORT',
            'featured'     => 'PHPBBLAB_PORTAL_FEATURED',
            'guides'       => 'PHPBBLAB_PORTAL_GUIDES',
            'downloads'    => 'PHPBBLAB_PORTAL_DOWNLOADS',
            'recent'       => 'PHPBBLAB_PORTAL_RECENT',
            'links'        => 'PHPBBLAB_PORTAL_LINKS',
            'community'    => 'PHPBBLAB_PORTAL_COMMUNITY',
            'stats'        => 'PHPBBLAB_PORTAL_STATS',
        );

        $custom_key = 'phpbblab_portal_block_title_' . $key;
        $block_title = isset($text[$custom_key]) ? trim($text[$custom_key]) : '';
        if ($block_title === '')
        {
            $block_title = $this->user->lang($default_lang[$key]);
        }

        return array_merge(array(
            'KEY'            => htmlspecialchars($key, ENT_QUOTES, 'UTF-8'),
            'BLOCK_TITLE'    => htmlspecialchars($block_title, ENT_QUOTES, 'UTF-8'),
            '_ORDER'         => $this->config_int('phpbblab_portal_order_' . $key, 100),
            '_SEQUENCE'      => (int) $sequence,
            '_ROWS'          => $rows,
        ), $vars);
    }

    protected function block_enabled($key)
    {
        return !empty($this->config['phpbblab_portal_show_' . $key]);
    }

    protected function can_view_block($key)
    {
        $visibility = isset($this->config['phpbblab_portal_visibility_' . $key])
            ? (string) $this->config['phpbblab_portal_visibility_' . $key]
            : 'all';

        switch ($visibility)
        {
            case 'registered':
                return !empty($this->user->data['is_registered']);

            case 'moderators':
                return !empty($this->user->data['is_registered'])
                    && ($this->auth->acl_getf_global('m_') || $this->auth->acl_get('a_'));

            case 'admins':
                return !empty($this->user->data['is_registered']) && $this->auth->acl_get('a_');

            case 'all':
            default:
                return true;
        }
    }

    protected function parse_id_list($raw)
    {
        $ids = preg_split('/[\s,;]+/', trim((string) $raw), -1, PREG_SPLIT_NO_EMPTY);
        $result = array();
        foreach ($ids as $id)
        {
            $id = (int) $id;
            if ($id > 0 && !in_array($id, $result, true))
            {
                $result[] = $id;
            }
            if (count($result) >= 12)
            {
                break;
            }
        }
        return $result;
    }

    protected function parse_custom_links($raw)
    {
        $rows = array();
        $lines = preg_split('/\R/u', (string) $raw);
        foreach ($lines as $line)
        {
            $line = trim($line);
            if ($line === '')
            {
                continue;
            }

            $parts = array_map('trim', explode('|', $line, 3));
            $label = isset($parts[0]) ? $parts[0] : '';
            $url = isset($parts[1]) ? $this->safe_url($parts[1]) : '';
            $description = isset($parts[2]) ? $parts[2] : '';

            if ($label === '' || $url === '')
            {
                continue;
            }

            $rows[] = array(
                'LINK_TITLE'       => htmlspecialchars(utf8_substr($label, 0, 100), ENT_QUOTES, 'UTF-8'),
                'LINK_DESCRIPTION' => htmlspecialchars(utf8_substr($description, 0, 240), ENT_QUOTES, 'UTF-8'),
                'U_LINK'           => htmlspecialchars($url, ENT_QUOTES, 'UTF-8'),
            );

            if (count($rows) >= 12)
            {
                break;
            }
        }
        return $rows;
    }

    protected function safe_url($url)
    {
        $url = trim((string) $url);
        if ($url === '' || preg_match('/[\x00-\x1F\x7F]/', $url))
        {
            return '';
        }

        if (strncmp($url, '//', 2) === 0 || strncmp($url, '\\\\', 2) === 0)
        {
            return '';
        }

        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url) && !preg_match('#^https?://#i', $url))
        {
            return '';
        }

        return utf8_substr($url, 0, 500);
    }

    protected function limit($key, $default, $min, $max)
    {
        $value = isset($this->config[$key]) ? (int) $this->config[$key] : (int) $default;
        return max((int) $min, min((int) $max, $value));
    }

    protected function config_int($key, $default = 0)
    {
        return isset($this->config[$key]) ? (int) $this->config[$key] : (int) $default;
    }
}
