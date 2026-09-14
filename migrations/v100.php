<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\migrations;

class v100 extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['phpbblab_portal_version']) && version_compare($this->config['phpbblab_portal_version'], '1.0.0', '>=');
    }

    public static function depends_on()
    {
        return array('\\phpbb\\db\\migration\\data\\v33x\\v3317');
    }

    public function update_data()
    {
        return array(
            array('config.add', array('phpbblab_portal_version', '1.0.0')),
            array('config.add', array('phpbblab_portal_enabled', 1)),
            array('config.add', array('phpbblab_portal_show_nav', 1)),
            array('config.add', array('phpbblab_portal_show_support', 1)),
            array('config.add', array('phpbblab_portal_show_guides', 1)),
            array('config.add', array('phpbblab_portal_show_downloads', 1)),
            array('config.add', array('phpbblab_portal_show_recent', 1)),
            array('config.add', array('phpbblab_portal_show_community', 1)),
            array('config.add', array('phpbblab_portal_show_stats', 1)),
            array('config.add', array('phpbblab_portal_support_forum_id', 0)),
            array('config.add', array('phpbblab_portal_docs_forum_id', 0)),
            array('config.add', array('phpbblab_portal_guides_forum_id', 0)),
            array('config.add', array('phpbblab_portal_downloads_forum_id', 0)),
            array('config.add', array('phpbblab_portal_community_forum_id', 0)),
            array('config.add', array('phpbblab_portal_guides_limit', 4)),
            array('config.add', array('phpbblab_portal_recent_limit', 6)),
            array('config_text.add', array('phpbblab_portal_title', '')),
            array('config_text.add', array('phpbblab_portal_intro', '')),
            array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_PHPBBLAB_PORTAL')),
            array('module.add', array('acp', 'ACP_PHPBBLAB_PORTAL', array(
                'module_basename' => '\\phpbblab\\portal\\acp\\main_module',
                'modes' => array('settings'),
            ))),
        );
    }
}
