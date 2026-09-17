<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\migrations;

class v122 extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['phpbblab_portal_version']) && version_compare($this->config['phpbblab_portal_version'], '1.2.2', '>=');
    }

    public static function depends_on()
    {
        return array('\\phpbblab\\portal\\migrations\\v121');
    }

    public function update_data()
    {
        return array(
            array('config.update', array('phpbblab_portal_version', '1.2.2')),
        );
    }
}
