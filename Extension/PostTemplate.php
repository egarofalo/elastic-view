<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait PostTemplate
{
    public function addPostTemplate()
    {
        $this->getBodyClass();
    }

    public function getBodyClass()
    {
        if (function_exists('get_body_class')) {
            $this->twig->addFunction(new TwigFunction('get_body_class', function ($class = '') {
                return get_body_class($class);
            }));
        }
    }
}
