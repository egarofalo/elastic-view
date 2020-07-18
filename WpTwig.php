<?php

namespace CoDevelopers\WpTwig;

use CoDevelopers\WpTwig\Extension\Functions;
use CoDevelopers\WpTwig\Extension\GeneralTemplate;
use CoDevelopers\WpTwig\Extension\GeneralTemplateTag;
use CoDevelopers\WpTwig\Extension\I10n;
use CoDevelopers\WpTwig\Extension\LinkTemplate;
use CoDevelopers\WpTwig\Extension\Media;
use CoDevelopers\WpTwig\Extension\Option;
use CoDevelopers\WpTwig\Extension\Plugin;
use CoDevelopers\WpTwig\Extension\PostTemplate;
use CoDevelopers\WpTwig\Extension\PostThumbnailTemplate;
use CoDevelopers\WpTwig\Extension\Query;
use InvalidArgumentException;
use Twig\TwigFunction;
use TypeError;

class WpTwig
{
    use GeneralTemplateTag,
        GeneralTemplate,
        PostTemplate,
        I10n,
        LinkTemplate,
        Media,
        PostThumbnailTemplate,
        Option,
        Plugin,
        Functions,
        Query;

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
        $this->addI10n();
        $this->addLinkTemplate();
        $this->addMedia();
        $this->addPostThumbnailTemplate();
        $this->addOption();
        $this->addPlugin();
        $this->addFunctions();
        $this->addQuery();
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

    public function renderTwig($templates, array $vars = []): string
    {
        try {
            if (!is_array($templates) and !is_string($templates)) {
                throw new InvalidArgumentException('renderTwig method must have a $templates argument type of array or string, ' . gettype($templates) . ' given.');
            }

            if (is_string($templates)) {
                $templates = [$templates];
            }

            return $this->twig->render($this->chooseTemplate($templates), $vars);
        } catch (InvalidArgumentException $e) {
            echo $e->getMessage();
            die;
        }
    }

    private function addFn()
    {
        $this->twig->addFunction(new TwigFunction('fn', function (string $function, ...$args) {
            $fn = trim($function);
            return call_user_func_array($fn, $args);
        }));
    }
}
