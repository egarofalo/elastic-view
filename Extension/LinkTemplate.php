<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait LinkTemplate
{
    public function addLinkTemplate()
    {
        $this->getPermalink();
        $this->homeUrl();
        $this->siteUrl();
    }

    public function getPermalink()
    {
        if (function_exists('get_permalink')) {
            $this->twig->addFunction(
                new TwigFunction(
                    'get_permalink',
                    function ($post = 0, bool $leavename = false) {
                        return get_permalink($post, $leavename);
                    }
                )
            );
        }
    }

    public function homeUrl()
    {
        if (function_exists('home_url')) {
            $this->twig->addFunction(
                new TwigFunction(
                    'home_url',
                    function (string $path = '', string $scheme = null) {
                        return home_url($path, $scheme);
                    }
                )
            );
        }
    }

    public function siteUrl()
    {
        if (function_exists('site_url')) {
            $this->twig->addFunction(
                new TwigFunction(
                    'site_url',
                    function (string $path = '', string $scheme = null) {
                        return site_url($path, $scheme);
                    }
                )
            );
        }
    }
}
