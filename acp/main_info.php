<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\acp;

class main_info
{
    public function module()
    {
        return array(
            'filename' => '\\phpbblab\\portal\\acp\\main_module',
            'title'    => 'ACP_PHPBBLAB_PORTAL',
            'modes'    => array(
                'settings' => array(
                    'title' => 'ACP_PHPBBLAB_PORTAL_SETTINGS',
                    'auth'  => 'ext_phpbblab/portal && acl_a_board',
                    'cat'   => array('ACP_PHPBBLAB_PORTAL'),
                ),
            ),
        );
    }
}
