<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait I10n
{
    public function addI10n()
    {
        $this->trans();
    }

    public function trans()
    {
        if (function_exists('__')) {
            $this->twig->addFunction(new TwigFunction('__', function (string $text, string $domain = 'default') {
                return __($text, $domain);
            }, ['is_safe' => ['html']]));
        }
    }
}
