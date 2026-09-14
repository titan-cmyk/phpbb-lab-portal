<?php
/**
 * PHPBB Lab Portal - Français
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
    'PHPBBLAB_PORTAL_NAV'                 => 'Portail',
    'PHPBBLAB_PORTAL_DEFAULT_TITLE'       => 'Bienvenue sur %s',
    'PHPBBLAB_PORTAL_DEFAULT_INTRO'       => 'Découvrez les contenus, espaces et ressources proposés sur %s.',
    'PHPBBLAB_PORTAL_ANNOUNCEMENT'        => 'Annonce',
    'PHPBBLAB_PORTAL_ANNOUNCEMENT_MORE'   => 'En savoir plus',
    'PHPBBLAB_PORTAL_SUPPORT'             => 'Sections principales',
    'PHPBBLAB_PORTAL_SUPPORT_EXPLAIN'     => 'Accédez rapidement aux principaux espaces du site.',
    'PHPBBLAB_PORTAL_FEATURED'            => 'À la une',
    'PHPBBLAB_PORTAL_GUIDES'              => 'Derniers contenus',
    'PHPBBLAB_PORTAL_GUIDES_EXPLAIN'      => 'Les derniers contenus publiés dans la section choisie.',
    'PHPBBLAB_PORTAL_DOWNLOADS'           => 'Ressources',
    'PHPBBLAB_PORTAL_DOWNLOADS_EXPLAIN'   => 'Retrouvez les ressources et espaces mis en avant.',
    'PHPBBLAB_PORTAL_RECENT'              => 'Activité récente',
    'PHPBBLAB_PORTAL_RECENT_EXPLAIN'      => 'Les derniers sujets publiés dans les espaces accessibles du forum.',
    'PHPBBLAB_PORTAL_LINKS'               => 'Liens utiles',
    'PHPBBLAB_PORTAL_COMMUNITY'           => 'Autres espaces',
    'PHPBBLAB_PORTAL_COMMUNITY_EXPLAIN'   => 'Explorez d’autres espaces du forum.',
    'PHPBBLAB_PORTAL_STATS'               => 'Le site en quelques chiffres',
    'PHPBBLAB_PORTAL_USERS'               => 'Membres',
    'PHPBBLAB_PORTAL_TOPICS'              => 'Sujets',
    'PHPBBLAB_PORTAL_POSTS'               => 'Messages',
    'PHPBBLAB_PORTAL_PRIMARY_ACTIONS'     => 'Accès principaux du portail',
    'PHPBBLAB_PORTAL_GO_FORUM'            => 'Accéder au forum',
    'PHPBBLAB_PORTAL_GO_SUPPORT'          => 'Découvrir les sections',
    'PHPBBLAB_PORTAL_GO_DOCS'             => 'Voir les contenus',
    'PHPBBLAB_PORTAL_GO_DOWNLOADS'        => 'Explorer les ressources',
    'PHPBBLAB_PORTAL_GO_COMMUNITY'        => 'Découvrir les autres espaces',
    'PHPBBLAB_PORTAL_READ_GUIDE'          => 'Lire le contenu',
    'PHPBBLAB_PORTAL_READ_TOPIC'          => 'Ouvrir le sujet',
    'PHPBBLAB_PORTAL_PUBLISHED'           => 'Publié le',
    'PHPBBLAB_PORTAL_LAST_ACTIVITY_LABEL' => 'Dernière activité :',
    'PHPBBLAB_PORTAL_LAST_ACTIVITY'       => 'Dernière activité : %s',
    'PHPBBLAB_PORTAL_CREDIT_PREFIX'       => 'Portail propulsé par',
    'LOG_PHPBBLAB_PORTAL_SETTINGS'        => '<strong>Réglages PHPBB Lab Portal modifiés</strong>',
));
