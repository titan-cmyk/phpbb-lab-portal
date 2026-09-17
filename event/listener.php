<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class listener implements EventSubscriberInterface
{
    protected $config;
    protected $template;
    protected $user;
    protected $helper;
    protected $request;

    public function __construct(
        \phpbb\config\config $config,
        \phpbb\template\template $template,
        \phpbb\user $user,
        \phpbb\controller\helper $helper,
        \phpbb\request\request_interface $request
    )
    {
        $this->config = $config;
        $this->template = $template;
        $this->user = $user;
        $this->helper = $helper;
        $this->request = $request;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'core.user_setup_after' => 'on_user_setup_after',
            'core.page_header_after' => 'on_page_header_after',
        );
    }

    public function on_user_setup_after($event)
    {
        if (empty($this->config['phpbblab_portal_enabled']) || empty($this->config['phpbblab_portal_as_homepage']))
        {
            return;
        }

        $method = strtoupper((string) $this->request->server('REQUEST_METHOD', 'GET'));
        if ($method !== 'GET' && $method !== 'HEAD')
        {
            return;
        }

        if (!$this->is_board_root_request())
        {
            return;
        }

        // phpBB 3.3.17 provides this helper specifically for permanent redirects
        // from legacy/front-controller entry points to Symfony routes. It generates
        // the route without a session id and sends HTTP 301, preventing anonymous
        // crawlers from discovering /portal?sid=... variants of the home page.
        \phpbb_redirect_to_controller('phpbblab_portal_home', array());
    }

    protected function is_board_root_request()
    {
        $request_uri = html_entity_decode((string) $this->request->server('REQUEST_URI', ''), ENT_COMPAT, 'UTF-8');
        $request_path = parse_url($request_uri, PHP_URL_PATH);

        if (!is_string($request_path) || $request_path === '')
        {
            return false;
        }

        $script_name = str_replace('\\', '/', (string) $this->request->server('SCRIPT_NAME', ''));
        if ($script_name === '')
        {
            return $request_path === '/';
        }

        $base_path = str_replace('\\', '/', dirname($script_name));
        if ($base_path === '.' || $base_path === '/')
        {
            $base_path = '';
        }
        else
        {
            $base_path = '/' . trim($base_path, '/');
        }

        $board_root_path = $base_path . '/';
        $request_path = '/' . ltrim(str_replace('\\', '/', rawurldecode($request_path)), '/');

        return $request_path === $board_root_path;
    }

    public function on_page_header_after($event)
    {
        $this->user->add_lang_ext('phpbblab/portal', 'common');

        $enabled = !empty($this->config['phpbblab_portal_enabled']);
        $show_nav = !empty($this->config['phpbblab_portal_show_nav']);

        $portal_url = $enabled ? $this->helper->route('phpbblab_portal_home') : '';
        // Capture phpBB's native forum-index URL before this listener changes any
        // breadcrumb variables on the portal page. This keeps the Forum navigation
        // link independent from the portal route and from the active style.
        $forum_index_url = (string) $this->template->retrieve_var('U_INDEX');
        $portal_as_homepage = !empty($this->config['phpbblab_portal_as_homepage']);

        $this->template->assign_vars(array(
            'S_PHPBBLAB_PORTAL_NAV' => $enabled && $show_nav,
            'U_PHPBBLAB_PORTAL'     => $portal_url,
            'S_PHPBBLAB_FORUM_NAV'  => $enabled && $portal_as_homepage && $forum_index_url !== '',
            'U_PHPBBLAB_FORUM'      => $forum_index_url,
        ));

        // When the portal is the configured site home page, keep breadcrumbs
        // semantically aligned with the page the visitor is actually viewing.
        //
        // Portal page: Portal
        // Forum pages: Portal > forum index > current hierarchy
        //
        // On the portal itself we reuse phpBB's mandatory index crumb as the single
        // Portal crumb and suppress U_SITE_HOME, because prosilver always renders the
        // index crumb. Everywhere else Portal remains U_SITE_HOME and phpBB keeps the
        // administrator-configured forum-index label in L_INDEX.
        if ($enabled && $portal_as_homepage)
        {
            $is_portal_page = (bool) $this->template->retrieve_var('S_PHPBBLAB_PORTAL_PAGE');

            if ($is_portal_page)
            {
                $canonical_url = $this->helper->route(
                    'phpbblab_portal_home',
                    array(),
                    false,
                    false,
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $this->template->assign_vars(array(
                    'U_SITE_HOME'                => '',
                    'L_SITE_HOME'                => '',
                    'U_INDEX'                    => $portal_url,
                    'L_INDEX'                    => $this->user->lang('PHPBBLAB_PORTAL_NAV'),
                    'PHPBBLAB_PORTAL_CANONICAL'  => $canonical_url,
                ));
            }
            else
            {
                $this->template->assign_vars(array(
                    'U_SITE_HOME' => $portal_url,
                    'L_SITE_HOME' => $this->user->lang('PHPBBLAB_PORTAL_NAV'),
                ));
            }
        }
    }
}
