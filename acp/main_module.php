<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\acp;

use phpbblab\portal\service\text_codec;

class main_module
{
    public $u_action;
    public $tpl_name;
    public $page_title;

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

    protected $block_lang = array(
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

    public function main($id, $mode)
    {
        global $config, $phpbb_container, $phpbb_root_path, $phpEx, $request, $template, $user;

        $user->add_lang_ext('phpbblab/portal', 'info_acp_portal');
        $user->add_lang_ext('phpbblab/portal', 'common');

        $this->tpl_name = 'acp_phpbblab_portal';
        $this->page_title = 'ACP_PHPBBLAB_PORTAL_SETTINGS';

        $config_text = $phpbb_container->get('config_text');
        add_form_key('phpbblab_portal_settings');

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

        if ($request->is_set_post('submit'))
        {
            if (!check_form_key('phpbblab_portal_settings'))
            {
                trigger_error($user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
            }

            $announcement_url = trim($request->variable('announcement_url', '', true));
            if ($announcement_url !== '' && !$this->is_safe_url($announcement_url))
            {
                trigger_error($user->lang('ACP_PHPBBLAB_PORTAL_INVALID_URL') . adm_back_link($this->u_action), E_USER_WARNING);
            }

            $custom_links = trim($request->variable('custom_links', '', true));
            if (!$this->validate_custom_links($custom_links))
            {
                trigger_error($user->lang('ACP_PHPBBLAB_PORTAL_INVALID_LINKS') . adm_back_link($this->u_action), E_USER_WARNING);
            }

            $config->set('phpbblab_portal_enabled', $request->variable('portal_enabled', 0) ? 1 : 0);
            $config->set('phpbblab_portal_as_homepage', $request->variable('portal_as_homepage', 0) ? 1 : 0);
            $config->set('phpbblab_portal_show_nav', $request->variable('show_nav', 0) ? 1 : 0);
            $config->set('phpbblab_portal_show_credit', $request->variable('show_credit', 0) ? 1 : 0);

            foreach ($this->block_keys as $key)
            {
                $config->set('phpbblab_portal_show_' . $key, $request->variable('show_' . $key, 0) ? 1 : 0);
                $config->set('phpbblab_portal_order_' . $key, max(1, min(999, $request->variable('order_' . $key, 100))));

                $visibility = $request->variable('visibility_' . $key, 'all');
                if (!in_array($visibility, array('all', 'registered', 'moderators', 'admins'), true))
                {
                    $visibility = 'all';
                }
                $config->set('phpbblab_portal_visibility_' . $key, $visibility);
            }

            foreach (array('support', 'docs', 'guides', 'downloads', 'community') as $name)
            {
                $source_id = (int) $request->variable($name . '_forum_id', 0);
                $config->set('phpbblab_portal_' . $name . '_forum_id', max(-1, $source_id));
            }

            $config->set('phpbblab_portal_guides_limit', max(1, min(10, $request->variable('guides_limit', 4))));
            $config->set('phpbblab_portal_recent_limit', max(1, min(12, $request->variable('recent_limit', 6))));

            $featured_ids = $this->normalize_id_list($request->variable('featured_topic_ids', '', true));

            $text_values = array(
                'phpbblab_portal_title'                   => utf8_substr(trim($request->variable('portal_title', '', true)), 0, 180),
                'phpbblab_portal_intro'                   => utf8_substr(trim($request->variable('portal_intro', '', true)), 0, 800),
                'phpbblab_portal_meta_description'        => utf8_substr(trim($request->variable('meta_description', '', true)), 0, 320),
                'phpbblab_portal_featured_topic_ids'      => $featured_ids,
                'phpbblab_portal_announcement_body'      => utf8_substr(trim($request->variable('announcement_body', '', true)), 0, 1200),
                'phpbblab_portal_announcement_url'       => utf8_substr($announcement_url, 0, 500),
                'phpbblab_portal_announcement_link_label'=> utf8_substr(trim($request->variable('announcement_link_label', '', true)), 0, 100),
                'phpbblab_portal_custom_links'            => utf8_substr($this->normalize_custom_links($custom_links), 0, 6000),
                'phpbblab_portal_action_forum_label'       => utf8_substr(trim($request->variable('action_forum_label', '', true)), 0, 100),
                'phpbblab_portal_action_primary_label'     => utf8_substr(trim($request->variable('action_primary_label', '', true)), 0, 100),
                'phpbblab_portal_action_secondary_label'   => utf8_substr(trim($request->variable('action_secondary_label', '', true)), 0, 100),
                'phpbblab_portal_action_resources_label'   => utf8_substr(trim($request->variable('action_resources_label', '', true)), 0, 100),
            );

            foreach ($this->block_keys as $key)
            {
                $text_values['phpbblab_portal_block_title_' . $key] = utf8_substr(trim($request->variable('title_' . $key, '', true)), 0, 120);
            }

            foreach ($text_values as $key => $value)
            {
                $text_values[$key] = text_codec::encode($value);
            }
            $config_text->set_array($text_values);

            add_log('admin', 'LOG_PHPBBLAB_PORTAL_SETTINGS');
            trigger_error($user->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
        }

        if (!function_exists('make_forum_select'))
        {
            include_once $phpbb_root_path . 'includes/functions_admin.' . $phpEx;
        }

        $auto_label = htmlspecialchars($user->lang('ACP_PHPBBLAB_PORTAL_AUTO_DETECT'), ENT_QUOTES, 'UTF-8');
        $none_label = htmlspecialchars($user->lang('ACP_PHPBBLAB_PORTAL_SOURCE_NONE'), ENT_QUOTES, 'UTF-8');
        $forum_select = function ($selected) use ($auto_label, $none_label)
        {
            $selected = (int) $selected;
            $none_selected = $selected < 0 ? ' selected="selected"' : '';
            $auto_selected = $selected === 0 ? ' selected="selected"' : '';
            $forum_selected = $selected > 0 ? $selected : 0;

            return '<option value="-1"' . $none_selected . '>' . $none_label . '</option>'
                . '<option value="0"' . $auto_selected . '>' . $auto_label . '</option>'
                . make_forum_select($forum_selected, false, true, false, false, false, false);
        };

        $stored_text = $config_text->get_array($text_keys);
        $text = array();
        foreach ($text_keys as $key)
        {
            $text[$key] = text_codec::decode(isset($stored_text[$key]) ? (string) $stored_text[$key] : '');
        }

        foreach ($this->block_keys as $key)
        {
            $template->assign_block_vars('portal_block_settings', array(
                'KEY'                => $key,
                'LABEL'              => $user->lang($this->block_lang[$key]),
                'SHOW'               => !empty($config['phpbblab_portal_show_' . $key]),
                'ORDER'              => isset($config['phpbblab_portal_order_' . $key]) ? (int) $config['phpbblab_portal_order_' . $key] : 100,
                'VISIBILITY_OPTIONS' => $this->visibility_options($user, isset($config['phpbblab_portal_visibility_' . $key]) ? (string) $config['phpbblab_portal_visibility_' . $key] : 'all'),
                'CUSTOM_TITLE'       => htmlspecialchars(isset($text['phpbblab_portal_block_title_' . $key]) ? $text['phpbblab_portal_block_title_' . $key] : '', ENT_QUOTES, 'UTF-8'),
            ));
        }

        $template->assign_vars(array(
            'U_ACTION'                    => $this->u_action,
            'PORTAL_ENABLED'              => !empty($config['phpbblab_portal_enabled']),
            'PORTAL_AS_HOMEPAGE'          => !empty($config['phpbblab_portal_as_homepage']),
            'SHOW_NAV'                    => !empty($config['phpbblab_portal_show_nav']),
            'SHOW_CREDIT'                 => !empty($config['phpbblab_portal_show_credit']),
            'SUPPORT_FORUM_OPTIONS'       => $forum_select(isset($config['phpbblab_portal_support_forum_id']) ? (int) $config['phpbblab_portal_support_forum_id'] : 0),
            'DOCS_FORUM_OPTIONS'          => $forum_select(isset($config['phpbblab_portal_docs_forum_id']) ? (int) $config['phpbblab_portal_docs_forum_id'] : 0),
            'GUIDES_FORUM_OPTIONS'        => $forum_select(isset($config['phpbblab_portal_guides_forum_id']) ? (int) $config['phpbblab_portal_guides_forum_id'] : 0),
            'DOWNLOADS_FORUM_OPTIONS'     => $forum_select(isset($config['phpbblab_portal_downloads_forum_id']) ? (int) $config['phpbblab_portal_downloads_forum_id'] : 0),
            'COMMUNITY_FORUM_OPTIONS'     => $forum_select(isset($config['phpbblab_portal_community_forum_id']) ? (int) $config['phpbblab_portal_community_forum_id'] : 0),
            'GUIDES_LIMIT'                => isset($config['phpbblab_portal_guides_limit']) ? (int) $config['phpbblab_portal_guides_limit'] : 4,
            'RECENT_LIMIT'                => isset($config['phpbblab_portal_recent_limit']) ? (int) $config['phpbblab_portal_recent_limit'] : 6,
            'PORTAL_TITLE'                => htmlspecialchars($text['phpbblab_portal_title'], ENT_QUOTES, 'UTF-8'),
            'PORTAL_INTRO'                => htmlspecialchars($text['phpbblab_portal_intro'], ENT_QUOTES, 'UTF-8'),
            'META_DESCRIPTION'            => htmlspecialchars($text['phpbblab_portal_meta_description'], ENT_QUOTES, 'UTF-8'),
            'FEATURED_TOPIC_IDS'          => htmlspecialchars($text['phpbblab_portal_featured_topic_ids'], ENT_QUOTES, 'UTF-8'),
            'ANNOUNCEMENT_BODY'           => htmlspecialchars($text['phpbblab_portal_announcement_body'], ENT_QUOTES, 'UTF-8'),
            'ANNOUNCEMENT_URL'            => htmlspecialchars($text['phpbblab_portal_announcement_url'], ENT_QUOTES, 'UTF-8'),
            'ANNOUNCEMENT_LINK_LABEL'     => htmlspecialchars($text['phpbblab_portal_announcement_link_label'], ENT_QUOTES, 'UTF-8'),
            'CUSTOM_LINKS'                => htmlspecialchars($text['phpbblab_portal_custom_links'], ENT_QUOTES, 'UTF-8'),
            'ACTION_FORUM_LABEL'          => htmlspecialchars($text['phpbblab_portal_action_forum_label'], ENT_QUOTES, 'UTF-8'),
            'ACTION_PRIMARY_LABEL'        => htmlspecialchars($text['phpbblab_portal_action_primary_label'], ENT_QUOTES, 'UTF-8'),
            'ACTION_SECONDARY_LABEL'      => htmlspecialchars($text['phpbblab_portal_action_secondary_label'], ENT_QUOTES, 'UTF-8'),
            'ACTION_RESOURCES_LABEL'      => htmlspecialchars($text['phpbblab_portal_action_resources_label'], ENT_QUOTES, 'UTF-8'),
            'PHPBBLAB_PORTAL_VERSION'     => isset($config['phpbblab_portal_version']) ? $config['phpbblab_portal_version'] : '1.2.0',
        ));
    }

    protected function visibility_options($user, $selected)
    {
        $options = array(
            'all'        => 'ACP_PHPBBLAB_PORTAL_VISIBILITY_ALL',
            'registered' => 'ACP_PHPBBLAB_PORTAL_VISIBILITY_REGISTERED',
            'moderators' => 'ACP_PHPBBLAB_PORTAL_VISIBILITY_MODERATORS',
            'admins'     => 'ACP_PHPBBLAB_PORTAL_VISIBILITY_ADMINS',
        );

        $html = '';
        foreach ($options as $value => $lang_key)
        {
            $html .= '<option value="' . $value . '"' . ($selected === $value ? ' selected="selected"' : '') . '>'
                . htmlspecialchars($user->lang($lang_key), ENT_QUOTES, 'UTF-8') . '</option>';
        }
        return $html;
    }

    protected function normalize_id_list($raw)
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
        return implode(',', $result);
    }

    protected function validate_custom_links($raw)
    {
        $lines = preg_split('/\R/u', (string) $raw);
        $count = 0;
        foreach ($lines as $line)
        {
            $line = trim($line);
            if ($line === '')
            {
                continue;
            }

            $parts = array_map('trim', explode('|', $line, 3));
            if (count($parts) < 2 || $parts[0] === '' || !$this->is_safe_url($parts[1]))
            {
                return false;
            }

            $count++;
            if ($count > 12)
            {
                return false;
            }
        }
        return true;
    }

    protected function normalize_custom_links($raw)
    {
        $lines = preg_split('/\R/u', (string) $raw);
        $normalized = array();
        foreach ($lines as $line)
        {
            $line = trim($line);
            if ($line === '')
            {
                continue;
            }
            $parts = array_map('trim', explode('|', $line, 3));
            $label = utf8_substr($parts[0], 0, 100);
            $url = utf8_substr($parts[1], 0, 500);
            $description = isset($parts[2]) ? utf8_substr($parts[2], 0, 240) : '';
            $normalized[] = $label . '|' . $url . ($description !== '' ? '|' . $description : '');
        }
        return implode("\n", $normalized);
    }

    protected function is_safe_url($url)
    {
        $url = trim((string) $url);
        if ($url === '' || preg_match('/[\x00-\x1F\x7F]/', $url) || strncmp($url, '//', 2) === 0 || strncmp($url, '\\\\', 2) === 0)
        {
            return false;
        }

        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url) && !preg_match('#^https?://#i', $url))
        {
            return false;
        }

        return true;
    }
}
