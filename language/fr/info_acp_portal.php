<?php
/**
 * PHPBB Lab Portal - ACP Français
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
    'ACP_PHPBBLAB_PORTAL_SETTINGS'                => 'Réglages du portail',
    'ACP_PHPBBLAB_PORTAL_EXPLAIN'                 => 'Configurez un portail accessible, indépendant du thème et réutilisable pour tout type de communauté phpBB. Les intitulés publics, les sources et les blocs peuvent être adaptés au sujet de votre forum.',
    'ACP_PHPBBLAB_PORTAL_VERSION'                 => 'Version :',
    'ACP_PHPBBLAB_PORTAL_GENERAL'                 => 'Réglages généraux',
    'ACP_PHPBBLAB_PORTAL_ENABLED'                 => 'Activer le portail',
    'ACP_PHPBBLAB_PORTAL_AS_HOMEPAGE'             => 'Utiliser le portail comme page d’accueil du site',
    'ACP_PHPBBLAB_PORTAL_AS_HOMEPAGE_EXPLAIN'     => 'Activé par défaut. La racine du forum (par exemple https://exemple.com/) affiche directement le portail sans redirection. La route /portal est redirigée de façon permanente vers la racine lorsque cette option est active. L’adresse index.php reste l’index normal du forum. Seules les requêtes GET et HEAD utilisent le portail à la racine.',
    'ACP_PHPBBLAB_PORTAL_SHOW_NAV'                => 'Afficher le lien Portail dans la navigation',
    'ACP_PHPBBLAB_PORTAL_SHOW_NAV_EXPLAIN'        => 'Ajoute un lien vers le portail via un événement de template phpBB lorsque le thème actif prend en charge cet emplacement.',
    'ACP_PHPBBLAB_PORTAL_TITLE'                   => 'Titre du portail',
    'ACP_PHPBBLAB_PORTAL_TITLE_EXPLAIN'           => 'Laissez vide pour utiliser le titre par défaut basé sur le nom du forum.',
    'ACP_PHPBBLAB_PORTAL_INTRO'                   => 'Introduction',
    'ACP_PHPBBLAB_PORTAL_INTRO_EXPLAIN'           => 'Texte court affiché en haut du portail. Laissez vide pour utiliser une introduction générale basée sur le nom du forum. Texte brut uniquement.',
    'ACP_PHPBBLAB_PORTAL_META_DESCRIPTION'        => 'Description SEO',
    'ACP_PHPBBLAB_PORTAL_META_DESCRIPTION_EXPLAIN'=> 'Description meta de la page portail. Si elle est vide, l’introduction est utilisée.',

    'ACP_PHPBBLAB_PORTAL_ACTIONS'                 => 'Boutons d’accès du portail',
    'ACP_PHPBBLAB_PORTAL_ACTIONS_EXPLAIN'         => 'Personnalisez les libellés des boutons affichés sous l’introduction. Laissez un champ vide pour utiliser le libellé général par défaut. Un bouton lié à une source absente ou désactivée n’est pas affiché.',
    'ACP_PHPBBLAB_PORTAL_ACTION_FORUM'            => 'Libellé du bouton vers le forum',
    'ACP_PHPBBLAB_PORTAL_ACTION_PRIMARY'          => 'Libellé du bouton vers la section principale',
    'ACP_PHPBBLAB_PORTAL_ACTION_SECONDARY'        => 'Libellé du bouton vers la section secondaire',
    'ACP_PHPBBLAB_PORTAL_ACTION_RESOURCES'        => 'Libellé du bouton vers les ressources',

    'ACP_PHPBBLAB_PORTAL_BLOCKS'                  => 'Gestion des blocs',
    'ACP_PHPBBLAB_PORTAL_BLOCKS_EXPLAIN'          => 'Activez les blocs utiles, choisissez leur ordre, leur visibilité et éventuellement un titre personnalisé adapté au sujet de votre forum. Les nombres les plus petits apparaissent en premier.',
    'ACP_PHPBBLAB_PORTAL_DISPLAY_BLOCK'           => 'Afficher ce bloc',
    'ACP_PHPBBLAB_PORTAL_ORDER'                   => 'Ordre',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY'              => 'Visibilité',
    'ACP_PHPBBLAB_PORTAL_CUSTOM_TITLE'            => 'Titre personnalisé',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY_ALL'          => 'Tout le monde',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY_REGISTERED'   => 'Membres enregistrés',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY_MODERATORS'   => 'Modérateurs et administrateurs',
    'ACP_PHPBBLAB_PORTAL_VISIBILITY_ADMINS'       => 'Administrateurs uniquement',

    'ACP_PHPBBLAB_PORTAL_FEATURED'                => 'Contenus à la une',
    'ACP_PHPBBLAB_PORTAL_FEATURED_TOPIC_IDS'      => 'Identifiants des sujets à la une',
    'ACP_PHPBBLAB_PORTAL_FEATURED_TOPIC_IDS_EXPLAIN' => 'Indiquez jusqu’à 12 identifiants de sujets, séparés par des virgules ou des espaces. Ils sont affichés dans l’ordre saisi si le visiteur a le droit de les lire.',

    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT'            => 'Annonce du portail',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_BODY'       => 'Texte de l’annonce',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_BODY_EXPLAIN'=> 'Texte brut, affiché uniquement si le bloc Annonce est activé.',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_URL'        => 'Lien de l’annonce',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_URL_EXPLAIN'=> 'Facultatif. URL HTTP/HTTPS ou chemin interne relatif.',
    'ACP_PHPBBLAB_PORTAL_ANNOUNCEMENT_LINK_LABEL' => 'Libellé du lien',

    'ACP_PHPBBLAB_PORTAL_CUSTOM_LINKS'            => 'Liens personnalisés',
    'ACP_PHPBBLAB_PORTAL_CUSTOM_LINKS_FIELD'      => 'Liste des liens',
    'ACP_PHPBBLAB_PORTAL_CUSTOM_LINKS_EXPLAIN'    => 'Un lien par ligne au format : Libellé|URL|Description facultative. Maximum 12 liens. Les protocoles dangereux sont refusés.',
    'ACP_PHPBBLAB_PORTAL_INVALID_URL'             => 'L’URL de l’annonce n’est pas valide.',
    'ACP_PHPBBLAB_PORTAL_INVALID_LINKS'           => 'La liste de liens personnalisés contient une ligne invalide ou dépasse 12 liens.',

    'ACP_PHPBBLAB_PORTAL_FORUMS'                  => 'Sources de contenu',
    'ACP_PHPBBLAB_PORTAL_FORUMS_EXPLAIN'          => 'Associez les fonctions du portail aux catégories ou forums de votre choix. Les intitulés ci-dessous décrivent le rôle technique de chaque source et ne supposent aucun thème particulier pour votre communauté. La sélection explicite est recommandée.',
    'ACP_PHPBBLAB_PORTAL_AUTO_DETECT'             => 'Détection automatique',
    'ACP_PHPBBLAB_PORTAL_SOURCE_NONE'             => 'Aucune source — désactiver cette fonction',
    'ACP_PHPBBLAB_PORTAL_SUPPORT_ID'              => 'Section principale',
    'ACP_PHPBBLAB_PORTAL_SUPPORT_ID_EXPLAIN'      => 'Affiche les sous-forums directs de la catégorie ou du forum sélectionné et alimente le bouton principal.',
    'ACP_PHPBBLAB_PORTAL_DOCS_ID'                 => 'Section secondaire',
    'ACP_PHPBBLAB_PORTAL_DOCS_ID_EXPLAIN'         => 'Utilisée comme destination du bouton secondaire. Elle peut pointer vers n’importe quelle catégorie ou n’importe quel forum.',
    'ACP_PHPBBLAB_PORTAL_GUIDES_ID'               => 'Section de contenus',
    'ACP_PHPBBLAB_PORTAL_GUIDES_ID_EXPLAIN'       => 'Affiche les sujets récents du forum sélectionné dans le bloc de contenus.',
    'ACP_PHPBBLAB_PORTAL_DOWNLOADS_ID'             => 'Section de ressources',
    'ACP_PHPBBLAB_PORTAL_DOWNLOADS_ID_EXPLAIN'     => 'Affiche les forums contenus dans la section sélectionnée et alimente le bouton Ressources.',
    'ACP_PHPBBLAB_PORTAL_COMMUNITY_ID'             => 'Section complémentaire',
    'ACP_PHPBBLAB_PORTAL_COMMUNITY_ID_EXPLAIN'     => 'Affiche les sous-forums directs d’une autre section de votre choix.',
    'ACP_PHPBBLAB_PORTAL_LIMITS'                  => 'Limites d’affichage',
    'ACP_PHPBBLAB_PORTAL_GUIDES_LIMIT'            => 'Nombre de contenus affichés',
    'ACP_PHPBBLAB_PORTAL_RECENT_LIMIT'            => 'Nombre de sujets récents affichés',

    'ACP_PHPBBLAB_PORTAL_CREDIT'                  => 'Crédit PHPBB Lab Portal',
    'ACP_PHPBBLAB_PORTAL_SHOW_CREDIT'             => 'Afficher le crédit du portail',
    'ACP_PHPBBLAB_PORTAL_SHOW_CREDIT_EXPLAIN'     => 'Affiche uniquement sur la page du portail « Portail propulsé par PHPBB Lab Portal » avec un lien vers l’accueil de phpbb-lab.com. Le crédit peut être désactivé.',

    'LOG_PHPBBLAB_PORTAL_SETTINGS'                => '<strong>Réglages PHPBB Lab Portal modifiés</strong>',
));
