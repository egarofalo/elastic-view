<?php

namespace CoDevelopers\Elastic\Component;

class View
{
    private $viewPath;
    private $cachePath;
    private $loader;
    private $twig;

    public function __construct(array $viewPath, string $cachePath, array $functions = [], array $globals = [])
    {
        $this->viewPath = $viewPath;
        $this->cachePath = $cachePath;
        $this->loader = new \Twig_Loader_Filesystem($this->viewPath);
        $this->twig = new \Twig_Environment($this->loader, [
            'debug' => WP_DEBUG,
            'cache' => $this->cachePath,
        ]);
        $this->extendTwig($functions, $globals);
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

    private function extendTwig(array $functions = [], array $globals = [])
    {
        $this->addDump();
        $this->addFn();
        foreach ($functions as $funcName => $func) {
            $this->twig->addFunction(new \Twig_Function($funcName, $func));
        }
        foreach ($globals as $key => $value) {
            $this->twig->addGlobal($key, $value);
        }
        $this->twig = apply_filters('get_twig', $this->twig);
    }

    public function render(string $template, array $vars = []): string
    {
        if (count($this->$viewPath) !== 2) {
            return '';
        }
        if (!file_exists($this->$viewPath[0] . '/' . $template) && !file_exists($this->$viewPath[1] . '/' . $template)) {
            return '';
        }
        ob_start();
        if (is_array($vars)) {
            extract($vars);
        }
        if (file_exists($this->$viewPath[0] . '/' . $template)) {
            include $this->$viewPath[0] . '/' . $template;
        } else {
            include $this->$viewPath[1] . '/' . $template;
        }
        return ob_get_clean();
    }

    public function renderTwig(array $templates, array $vars = []): string
    {
        return $this->twig->render($this->chooseTemplate($templates), $vars);
    }

    private function addDump()
    {
        if (function_exists('dump')) {
            $this->twig->addFunction(new \Twig_Function('dump', function ($var, ...$moreVars) {
                dump($var, ...$moreVars);
            }));
        }
    }

    private function addFn()
    {
        $this->twig->addFunction(new \Twig_Function('fn', function (string $function, ...$args) {
            $fn = trim($function);
            return call_user_func_array($fn, $args);
        }));
    }
}
