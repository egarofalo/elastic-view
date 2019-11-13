<?php

namespace CoDevelopers\WpTwig\Extension;

use Twig\TwigFunction;

trait GeneralTemplateTag
{
    public function addGeneralTemplateTags()
    {
        $this->addGetLanguageAttributes();
    }

    public function addGetLanguageAttributes()
    {
        if (function_exists('get_language_attributes')) {
            $this->twig->addFunction(new TwigFunction('get_language_attributes', function (string $doctype = 'html') {
                return get_language_attributes($doctype);
            }));
        }
    }
}
