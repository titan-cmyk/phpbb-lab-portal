<?php
/**
 * PHPBB Lab Portal
 *
 * @copyright (c) 2026 Michel Stassen
 * @license GPL-2.0-only
 */
namespace phpbblab\portal\controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class portal_route
{
    protected $config;
    protected $template;
    protected $helper;
    protected $main;

    public function __construct(
        \phpbb\config\config $config,
        \phpbb\template\template $template,
        \phpbb\controller\helper $helper,
        \phpbblab\portal\controller\main $main
    )
    {
        $this->config = $config;
        $this->template = $template;
        $this->helper = $helper;
        $this->main = $main;
    }

    public function home()
    {
        if (!empty($this->config['phpbblab_portal_enabled']) && !empty($this->config['phpbblab_portal_as_homepage']))
        {
            return new RedirectResponse(rtrim((string) \generate_board_url(), '/') . '/', 301);
        }

        if (!empty($this->config['phpbblab_portal_enabled']))
        {
            $canonical = $this->helper->route(
                'phpbblab_portal_home',
                array(),
                false,
                '',
                UrlGeneratorInterface::ABSOLUTE_URL
            );
            $this->template->assign_var('U_CANONICAL', $canonical);
        }

        return $this->main->home();
    }
}
