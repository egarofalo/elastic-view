<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait GeneralTemplate
{
    public function addGeneralTemplate()
    {
        $this->site();
        $this->wpHead();
        $this->wpFooter();
        $this->getSearchForm();
        $this->getSidebar();
        $this->paginateLinks();
    }

    public function site()
    {
        if (function_exists('get_bloginfo')) {
            $this->twig->addFunction(new TwigFunction('site', function (string $show = '') {
                return get_bloginfo($show);
            }));
        }
    }

    public function wpHead()
    {
        if (function_exists('wp_head')) {
            $this->twig->addFunction(new TwigFunction('wp_head', function () {
                ob_start();
                $head = wp_head();
                ob_get_clean();
                return $head;
            }, ['is_safe' => ['html']]));
        }
    }

    public function wpFooter()
    {
        if (function_exists('wp_footer')) {
            $this->twig->addFunction(new TwigFunction('wp_footer', function () {
                ob_start();
                $head = wp_footer();
                ob_get_clean();
                return $head;
            }, ['is_safe' => ['html']]));
        }
    }

    public function getSearchForm()
    {
        if (function_exists('get_search_form')) {
            $this->twig->addFunction(new TwigFunction('get_search_form', function (array $args = []) {
                $args['echo'] = false;
                return get_search_form($args);
            }, ['is_safe' => ['html']]));
        }
    }

    public function getSidebar()
    {
        if (function_exists('get_sidebar')) {
            $this->twig->addFunction(new TwigFunction('get_sidebar', function (string $name = null) {
                ob_start();
                get_sidebar($name);
                $sidebar = ob_get_contents();
                ob_get_clean();
                return $sidebar;
            }, ['is_safe' => ['html']]));
        }
    }

    public function paginateLinks()
    {
        if (function_exists('paginate_links')) {
            $this->twig->addFunction(new TwigFunction('paginate_links', function ($args = '') {
                return paginate_links($args);
            }, ['is_safe' => ['html']]));
        }
    }
}
