<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait I10n
{
    public function addI10n()
    {
        $this->translate();
        $this->translateWithGetTextContext();
        $this->translatePlural();
    }

    public function translate()
    {
        if (function_exists('__')) {
            $this->twig->addFunction(new TwigFunction('__', function (string $text, string $domain = 'default') {
                return __($text, $domain);
            }, ['is_safe' => ['html']]));
        }
    }

    public function translateWithGetTextContext()
    {
        if (function_exists('_x')) {
            $this->twig->addFunction(new TwigFunction('_x', function (string $text, string $context, string $domain = 'default') {
                return _x($text, $context, $domain);
            }, ['is_safe' => ['html']]));
        }
    }

    public function translatePlural()
    {
        if (function_exists('_n')) {
            $this->twig->addFunction(new TwigFunction('_n', function (string $single, string $plural, int $number, string $domain = 'default') {
                return _n($single, $plural, $number, $domain);
            }, ['is_safe' => ['html']]));
        }
    }
}
