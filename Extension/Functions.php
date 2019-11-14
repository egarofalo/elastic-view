<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait Functions
{
    public function addFunctions()
    {
        $this->wpNonceField();
    }

    public function wpNonceField()
    {
        if (function_exists('wp_nonce_field')) {
            $this->twig->addFunction(new TwigFunction('wp_nonce_field', function ($action = -1, string $name = '_wpnonce', bool $referer = true) {
                return wp_nonce_field($action, $name, $referer, false);
            }, ['is_safe' => ['html']]));
        }
    }
}
