<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait AdvancedCustomField
{
    public function addAdvancedCustomField()
    {
        $this->getField();
    }

    public function getField()
    {
        if (function_exists('get_field')) {
            $this->twig->addFunction(new TwigFunction('get_field', function ($selector, $post_id = false, $format_value = true) {
                return get_field($selector, $post_id = false, $format_value = true);
            }));
        }
    }
}
