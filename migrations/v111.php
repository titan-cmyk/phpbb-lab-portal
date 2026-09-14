<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\migrations;

class v111 extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['phpbblab_portal_version']) && version_compare($this->config['phpbblab_portal_version'], '1.1.1', '>=');
    }

    public static function depends_on()
    {
        return array('\\phpbblab\\portal\\migrations\\v110');
    }

    public function update_data()
    {
        return array(
            array('config.add', array('phpbblab_portal_as_homepage', 1)),
            array('config.update', array('phpbblab_portal_version', '1.1.1')),
        );
    }
}
