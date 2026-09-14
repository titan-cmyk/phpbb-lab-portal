<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\migrations;

class v120 extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['phpbblab_portal_version']) && version_compare($this->config['phpbblab_portal_version'], '1.2.0', '>=');
    }

    public static function depends_on()
    {
        return array('\\phpbblab\\portal\\migrations\\v115');
    }

    public function update_data()
    {
        return array(
            array('config_text.add', array('phpbblab_portal_action_forum_label', '')),
            array('config_text.add', array('phpbblab_portal_action_primary_label', '')),
            array('config_text.add', array('phpbblab_portal_action_secondary_label', '')),
            array('config_text.add', array('phpbblab_portal_action_resources_label', '')),
            array('config.update', array('phpbblab_portal_version', '1.2.0')),
        );
    }
}
