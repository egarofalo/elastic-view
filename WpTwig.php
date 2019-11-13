<?php

namespace CoDevelopers\WpTwig;

use CoDevelopers\WpTwig\Extension\AdvancedCustomField;
use CoDevelopers\WpTwig\Extension\GeneralTemplate;
use CoDevelopers\WpTwig\Extension\GeneralTemplateTag;
use CoDevelopers\WpTwig\Extension\PostTemplate;
use Twig\TwigFunction;

class WpTwig
{
    use GeneralTemplateTag,
        GeneralTemplate,
        PostTemplate,
        AdvancedCustomField;

    private $viewPath;
    private $cachePath;
    private $loader;
    private $twig;

    public function __construct(array $viewPath, string $cachePath)
    {
        $this->viewPath = $viewPath;
        $this->cachePath = $cachePath;
        $this->loader = new \Twig_Loader_Filesystem($this->viewPath);
        $this->twig = new \Twig_Environment($this->loader, [
            'debug' => WP_DEBUG,
            'cache' => $this->cachePath,
        ]);
        $this->extendTwig();
    }

    private function chooseTemplate(array $templates)
    {
        foreach ($templates as $template) {
            if ($this->loader->exists($template)) {
                return $template;
            }
        }

        return false;
    }

    private function extendTwig()
    {
        $this->addFn();
        $this->addGeneralTemplateTags();
        $this->addGeneralTemplate();
        $this->addPostTemplate();
        $this->addAdvancedCustomField();
        $this->twig = apply_filters('get_twig', $this->twig);
    }

    public function render(string $template, array $vars = []): string
    {
        if (count($this->viewPath) !== 2) {
            return '';
        }

        if (!file_exists($this->viewPath[0] . '/' . $template) && !file_exists($this->viewPath[1] . '/' . $template)) {
            return '';
        }

        ob_start();

        if (is_array($vars)) {
            extract($vars);
        }

        if (file_exists($this->viewPath[0] . '/' . $template)) {
            include $this->viewPath[0] . '/' . $template;
        } else {
            include $this->viewPath[1] . '/' . $template;
        }

        return ob_get_clean();
    }

    public function renderTwig(array $templates, array $vars = []): string
    {
        return $this->twig->render($this->chooseTemplate($templates), $vars);
    }

    private function addFn()
    {
        $this->twig->addFunction(new TwigFunction('fn', function (string $function, ...$args) {
            $fn = trim($function);
            return call_user_func_array($fn, $args);
        }));
    }
}
