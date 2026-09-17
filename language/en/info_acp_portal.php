<?php
/**
 * PHPBB Lab Portal - ACP English
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
    'ACP_PHPBBLAB_PORTAL'                         => 'PHPBB Lab Portal',
    'ACP_PHPBBLAB_PORTAL_SETTINGS'                => 'Portal settings',
    'ACP_PHPBBLAB_PORTAL_EXPLAIN'                 => 'Configure an accessible, style-independent portal that can be reused by any kind of phpBB community. Public labels, sources and blocks can be adapted to the subject of your board.',
    'ACP_PHPBBLAB_PORTAL_VERSION'                 => 'Version:',
    'ACP_PHPBBLAB_PORTAL_GENERAL'                 => 'General settings',
    'ACP_PHPBBLAB_PORTAL_ENABLED'                 => 'Enable portal',
    'ACP_PHPBBLAB_PORTAL_AS_HOMEPAGE'             => 'Use the portal as the site home page',
    'ACP_PHPBBLAB_PORTAL_AS_HOMEPAGE_EXPLAIN'     => 'Enabled by default. The board root (for example https://example.com/) serves the portal directly without a redirect. The /portal route is permanently redirected to the board root while this option is enabled. index.php remains the normal forum index. Only GET and HEAD requests use the portal on the board root.',
    'ACP_PHPBBLAB_PORTAL_SHOW_NAV'                => 'Show Portal link in navigation',
    'ACP_PHPBBLAB_PORTAL_SHOW_NAV_EXPLAIN'        => 'Adds a link to the portal through a phpBB template event when the active style exposes that location.',
    'ACP_PHPBBLAB_PORTAL_TITLE'                   => 'Portal title',
    'ACP_PHPBBLAB_PORTAL_TITLE_EXPLAIN'           => 'Leave empty to use the default title based on the board name.',
    'ACP_PHPBBLAB_PORTAL_INTRO'                   => 'Introduction',
    'ACP_PHPBBLAB_PORTAL_INTRO_EXPLAIN'           => 'Short text displayed at the top of the portal. Leave empty to use a general introduction based on the board name. Plain text only.',
    'ACP_PHPBBLAB_PORTAL_META_DESCRIPTION'        => 'SEO description',
    'ACP_PHPBBLAB_PORTAL_META_DESCRIPTION_EXPLAIN'=> 'Meta description for the portal page. When empty, the introduction is used.',

    'ACP_PHPBBLAB_PORTAL_ACTIONS'                 => 'Portal access buttons',
    'ACP_PHPBBLAB_PORTAL_ACTIONS_EXPLAIN'         => 'Customise the button labels shown below the introduction. Leave a field empty to use the general default label. A button linked to a missing or disabled source is not displayed.',
    'ACP_PHPBBLAB_PORTAL_ACTION_FORUM'            => 'Forum button label',
    'ACP_PHPBBLAB_PORTAL_ACTION_PRIMARY'          => 'Main section button label',
    'ACP_PHPBBLAB_PORTAL_ACTION_SECONDARY'        => 'Secondary section button label',
    'ACP_PHPBBLAB_PORTAL_ACTION_RESOURCES'        => 'Resources button label',

    'ACP_PHPBBLAB_PORTAL_BLOCKS'                  => 'Block management',
    'ACP_PHPBBLAB_PORTAL_BLOCKS_EXPLAIN'          => 'Enable useful blocks, choose their order and visibility, and optionally give them a custom title matching your board subject. Lower numbers are displayed first.',
    'ACP_PHPBBLAB_PORTAL_DISPLAY_BLOCK'           => 'Display this block',
    'ACP_PHPBBLAB_PORTAL_ORDER'                   => 'Order',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY'              => 'Visibility',
    'ACP_PHPBBLAB_PORTAL_CUSTOM_TITLE'            => 'Custom title',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY_ALL'          => 'Everyone',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY_REGISTERED'   => 'Registered users',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY_MODERATORS'   => 'Moderators and administrators',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY_ADMINS'       => 'Administrators only',

    'ACP_PHPBBLAB_PORTAL_FEATURED'                => 'Featured content',
    'ACP_PHPBBLAB_PORTAL_FEATURED_TOPIC_IDS'      => 'Featured topic IDs',
    'ACP_PHPBBLAB_PORTAL_FEATURED_TOPIC_IDS_EXPLAIN' => 'Enter up to 12 topic IDs separated by commas or spaces. They are displayed in the entered order when the visitor may read them.',

    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT'            => 'Portal announcement',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_BODY'       => 'Announcement text',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_BODY_EXPLAIN'=> 'Plain text, displayed only when the Announcement block is enabled.',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_URL'        => 'Announcement link',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_URL_EXPLAIN'=> 'Optional. HTTP/HTTPS URL or relative internal path.',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_LINK_LABEL' => 'Link label',

    'ACP_PHPBBLAB_PORTAL_CUSTOM_LINKS'            => 'Custom links',
    'ACP_PHPBBLAB_PORTAL_CUSTOM_LINKS_FIELD'      => 'Link list',
    'ACP_PHPBBLAB_PORTAL_CUSTOM_LINKS_EXPLAIN'    => 'One link per line: Label|URL|Optional description. Maximum 12 links. Dangerous URL schemes are rejected.',
    'ACP_PHPBBLAB_PORTAL_INVALID_URL'             => 'The announcement URL is invalid.',
    'ACP_PHPBBLAB_PORTAL_INVALID_LINKS'           => 'The custom link list contains an invalid line or more than 12 links.',

    'ACP_PHPBBLAB_PORTAL_FORUMS'                  => 'Content sources',
    'ACP_PHPBBLAB_PORTAL_FORUMS_EXPLAIN'          => 'Map portal functions to any categories or forums you choose. The labels below describe the technical role of each source and do not assume a specific community topic. Explicit selection is recommended.',
    'ACP_PHPBBLAB_PORTAL_AUTO_DETECT'             => 'Automatic detection',
    'ACP_PHPBBLAB_PORTAL_SOURCE_NONE'             => 'No source — disable this function',
    'ACP_PHPBBLAB_PORTAL_SUPPORT_ID'              => 'Main section',
    'ACP_PHPBBLAB_PORTAL_SUPPORT_ID_EXPLAIN'      => 'Displays the direct child forums of the selected category or forum and feeds the main action button.',
    'ACP_PHPBBLAB_PORTAL_DOCS_ID'                 => 'Secondary section',
    'ACP_PHPBBLAB_PORTAL_DOCS_ID_EXPLAIN'         => 'Used as the destination of the secondary action button. It may point to any category or forum.',
    'ACP_PHPBBLAB_PORTAL_GUIDES_ID'               => 'Content section',
    'ACP_PHPBBLAB_PORTAL_GUIDES_ID_EXPLAIN'       => 'Displays recent topics from the selected forum in the content block.',
    'ACP_PHPBBLAB_PORTAL_DOWNLOADS_ID'             => 'Resources section',
    'ACP_PHPBBLAB_PORTAL_DOWNLOADS_ID_EXPLAIN'     => 'Displays forums contained in the selected section and feeds the Resources action button.',
    'ACP_PHPBBLAB_PORTAL_COMMUNITY_ID'             => 'Additional section',
    'ACP_PHPBBLAB_PORTAL_COMMUNITY_ID_EXPLAIN'     => 'Displays the direct child forums of another section of your choice.',
    'ACP_PHPBBLAB_PORTAL_LIMITS'                  => 'Display limits',
    'ACP_PHPBBLAB_PORTAL_GUIDES_LIMIT'            => 'Number of content items displayed',
    'ACP_PHPBBLAB_PORTAL_RECENT_LIMIT'            => 'Number of recent topics displayed',

    'ACP_PHPBBLAB_PORTAL_CREDIT'                  => 'PHPBB Lab Portal credit',
    'ACP_PHPBBLAB_PORTAL_SHOW_CREDIT'             => 'Display portal credit',
    'ACP_PHPBBLAB_PORTAL_SHOW_CREDIT_EXPLAIN'     => 'Displays only on the portal page “Portal powered by PHPBB Lab Portal” with a link to the phpbb-lab.com home page. The credit can be disabled.',

    'LOG_PHPBBLAB_PORTAL_SETTINGS'                => '<strong>PHPBB Lab Portal settings changed</strong>',
));
