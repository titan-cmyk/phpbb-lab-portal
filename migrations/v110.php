<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\migrations;

class v110 extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return isset($this->config['phpbblab_portal_version']) && version_compare($this->config['phpbblab_portal_version'], '1.1.0', '>=');
    }

    public static function depends_on()
    {
        return array('\\phpbblab\\portal\\migrations\\v104');
    }

    public function update_data()
    {
        $data = array(
            array('config.add', array('phpbblab_portal_show_announcement', 0)),
            array('config.add', array('phpbblab_portal_show_featured', 0)),
            array('config.add', array('phpbblab_portal_show_links', 0)),
            array('config.add', array('phpbblab_portal_show_credit', 1)),

            array('config.add', array('phpbblab_portal_order_announcement', 5)),
            array('config.add', array('phpbblab_portal_order_support', 10)),
            array('config.add', array('phpbblab_portal_order_featured', 20)),
            array('config.add', array('phpbblab_portal_order_guides', 30)),
            array('config.add', array('phpbblab_portal_order_downloads', 40)),
            array('config.add', array('phpbblab_portal_order_recent', 50)),
            array('config.add', array('phpbblab_portal_order_links', 60)),
            array('config.add', array('phpbblab_portal_order_community', 70)),
            array('config.add', array('phpbblab_portal_order_stats', 80)),
        );

        foreach (array('announcement', 'support', 'featured', 'guides', 'downloads', 'recent', 'links', 'community', 'stats') as $key)
        {
            $data[] = array('config.add', array('phpbblab_portal_visibility_' . $key, 'all'));
            $data[] = array('config_text.add', array('phpbblab_portal_block_title_' . $key, ''));
        }

        $data[] = array('config_text.add', array('phpbblab_portal_meta_description', ''));
        $data[] = array('config_text.add', array('phpbblab_portal_featured_topic_ids', ''));
        $data[] = array('config_text.add', array('phpbblab_portal_announcement_body', ''));
        $data[] = array('config_text.add', array('phpbblab_portal_announcement_url', ''));
        $data[] = array('config_text.add', array('phpbblab_portal_announcement_link_label', ''));
        $data[] = array('config_text.add', array('phpbblab_portal_custom_links', ''));
        $data[] = array('config.update', array('phpbblab_portal_version', '1.1.0'));

        return $data;
    }
}
