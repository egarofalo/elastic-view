<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait Shortcodes
{
    public function addShortcodes()
    {
        $this->doShortcode();
    }

    public function doShortcode()
    {
        if (function_exists('do_shortcode')) {
            $this->twig->addFunction(
                new TwigFunction(
                    'do_shortcode',
                    function (
                        string $content,
                        bool $ignore_html = false
                    ) {
                        return do_shortcode($content, $ignore_html);
                    },
                    ['is_safe' => ['html']]
                )
            );
        }
    }
}
