<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait Option
{
    public function addOption()
    {
        $this->getOption();
    }

    public function getOption()
    {
        if (function_exists('get_option')) {
            $this->twig->addFunction(new TwigFunction('get_option', function (string $option, $default = false) {
                return get_option($option, $default);
            }));
        }
    }
}
