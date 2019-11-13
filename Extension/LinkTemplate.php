<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait LinkTemplate
{
    public function addLinkTemplate()
    {
        $this->getPermalink();
    }
    
    public function getPermalink()
    {
        if (function_exists('get_permalink')) {
            $this->twig->addFunction(new TwigFunction('get_permalink', function ($post = 0, bool $leavename = false) {
                return get_permalink($post, $leavename);
            }));
        }
    }
}
