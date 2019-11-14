<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait Query
{
    public function addQuery()
    {
        $this->isHome();
        $this->isFrontPage();
        $this->isPage();
        $this->isSingular();
        $this->isArchive();
        $this->isPostTypeArchive();
    }

    public function isHome()
    {
        if (function_exists('is_home')) {
            $this->twig->addFunction(new TwigFunction('is_home', function () {
                return is_home();
            }));
        }
    }

    public function isFrontPage()
    {
        if (function_exists('is_front_page')) {
            $this->twig->addFunction(new TwigFunction('is_front_page', function () {
                return is_front_page();
            }));
        }
    }

    public function isPage()
    {
        if (function_exists('is_page')) {
            $this->twig->addFunction(new TwigFunction('is_page', function ($page = '') {
                return is_page($page);
            }));
        }
    }

    public function isSingular()
    {
        if (function_exists('is_singular')) {
            $this->twig->addFunction(new TwigFunction('is_singular', function ($post_types = '') {
                return is_singular($post_types);
            }));
        }
    }

    public function isArchive()
    {
        if (function_exists('is_archive')) {
            $this->twig->addFunction(new TwigFunction('is_archive', function () {
                return is_archive();
            }));
        }
    }

    public function isPostTypeArchive()
    {
        if (function_exists('is_post_type_archive')) {
            $this->twig->addFunction(new TwigFunction('is_post_type_archive', function ($post_types = '') {
                return is_post_type_archive($post_types);
            }));
        }
    }
}
