<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal;

class ext extends \phpbb\extension\base
{
    public function is_enableable()
    {
        return PHP_VERSION_ID >= 70205
            && phpbb_version_compare(PHPBB_VERSION, '3.3.17', '>=')
            && phpbb_version_compare(PHPBB_VERSION, '3.4.0', '<');
    }
}
