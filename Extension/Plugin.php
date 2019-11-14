<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait Plugin
{
    public function addPlugin()
    {
        $this->doAction();
    }

    public function doAction()
    {
        if (function_exists('do_action')) {
            $this->twig->addFunction(new TwigFunction('do_action', function (string $tag, ...$args) {
                ob_start();
                do_action($tag, ...$args);
                $html = ob_get_contents();
                ob_get_clean();
                return $html;
            }, ['is_safe' => ['html']]));
        }
    }
}
