<?php
/**
 * PHPBB Lab Portal - English
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

$lang = array_merge($lang, array(
    'PHPBBLAB_PORTAL_NAV'                 => 'Portal',
    'PHPBBLAB_PORTAL_DEFAULT_TITLE'       => 'Welcome to %s',
    'PHPBBLAB_PORTAL_DEFAULT_INTRO'       => 'Discover the content, areas and resources available on %s.',
    'PHPBBLAB_PORTAL_ANNOUNCEMENT'        => 'Announcement',
    'PHPBBLAB_PORTAL_ANNOUNCEMENT_MORE'   => 'Learn more',
    'PHPBBLAB_PORTAL_SUPPORT'             => 'Main areas',
    'PHPBBLAB_PORTAL_SUPPORT_EXPLAIN'     => 'Quickly access the main areas of the site.',
    'PHPBBLAB_PORTAL_FEATURED'            => 'Featured',
    'PHPBBLAB_PORTAL_GUIDES'              => 'Latest content',
    'PHPBBLAB_PORTAL_GUIDES_EXPLAIN'      => 'The latest content published in the selected area.',
    'PHPBBLAB_PORTAL_DOWNLOADS'           => 'Resources',
    'PHPBBLAB_PORTAL_DOWNLOADS_EXPLAIN'   => 'Browse highlighted resources and areas.',
    'PHPBBLAB_PORTAL_RECENT'              => 'Recent activity',
    'PHPBBLAB_PORTAL_RECENT_EXPLAIN'      => 'The latest topics from forum areas you are allowed to read.',
    'PHPBBLAB_PORTAL_LINKS'               => 'Useful links',
    'PHPBBLAB_PORTAL_COMMUNITY'           => 'Other areas',
    'PHPBBLAB_PORTAL_COMMUNITY_EXPLAIN'   => 'Explore other areas of the forum.',
    'PHPBBLAB_PORTAL_STATS'               => 'Site at a glance',
    'PHPBBLAB_PORTAL_USERS'               => 'Members',
    'PHPBBLAB_PORTAL_TOPICS'              => 'Topics',
    'PHPBBLAB_PORTAL_POSTS'               => 'Posts',
    'PHPBBLAB_PORTAL_PRIMARY_ACTIONS'     => 'Main portal links',
    'PHPBBLAB_PORTAL_GO_FORUM'            => 'Go to forum',
    'PHPBBLAB_PORTAL_GO_SUPPORT'          => 'Explore sections',
    'PHPBBLAB_PORTAL_GO_DOCS'             => 'View content',
    'PHPBBLAB_PORTAL_GO_DOWNLOADS'        => 'Explore resources',
    'PHPBBLAB_PORTAL_GO_COMMUNITY'        => 'Explore other areas',
    'PHPBBLAB_PORTAL_READ_GUIDE'          => 'Read content',
    'PHPBBLAB_PORTAL_READ_TOPIC'          => 'Open topic',
    'PHPBBLAB_PORTAL_PUBLISHED'           => 'Published on',
    'PHPBBLAB_PORTAL_LAST_ACTIVITY_LABEL' => 'Last activity:',
    'PHPBBLAB_PORTAL_LAST_ACTIVITY'       => 'Last activity: %s',
    'PHPBBLAB_PORTAL_CREDIT_PREFIX'       => 'Portal powered by',
    'LOG_PHPBBLAB_PORTAL_SETTINGS'        => '<strong>PHPBB Lab Portal settings changed</strong>',
));
