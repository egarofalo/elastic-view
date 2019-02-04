<?php

namespace CoDevelopers\Elastic\Component;

class View
{
    private $viewPath;
    private $cachePath;
    private $twig;

    public function __construct(array $viewPath, string $cachePath, array $functions = [])
    {
        $this->viewPath = $viewPath;
        $this->cachePath = $cachePath;
        $loader = new \Twig_Loader_Filesystem($this->viewPath);
        $this->twig = new \Twig_Environment($loader, [
            'debug' => WP_DEBUG,
            'cache' => $this->cachePath,
        ]);
        $this->extendTwig($functions);
    }

    private function extendTwig(array $functions = [])
    {
        $this->addFaker();
        $this->addAsset();
        $this->addDump();
        $this->addFn();
        $this->addWc();
        $this->addGetField();
        foreach ($functions as $funcName => $func) {
            $this->twig->addFunction(new \Twig_Function($funcName, $func));
        }
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

    public function renderTwig(string $template, array $vars = []): string
    {
        return $this->twig->render($template, $vars);
    }

    private function addFaker()
    {
        if (class_exists('CoDevelopers\\Elastic\\Component\\Faker')) {
            self::$twig->addGlobal('faker', CoDevelopers\Elastic\Component\Faker::get());
        }
    }

    private function addAsset()
    {
        if (class_exists('CoDevelopers\\Elastic\\Component\\Asset')) {
            self::$twig->addFunction(new \Twig_Function('asset', [CoDevelopers\Elastic\Component\Asset::class, 'get']));
        }
    }

    private function addDump()
    {
        if (function_exists('dump')) {
            self::$twig->addFunction(new \Twig_Function('dump', function ($var, ...$moreVars) {
                dump($var, ...$moreVars);
            }));
        }
    }

    private function addFn()
    {
        self::$twig->addFunction(new \Twig_Function('fn', function (string $function, ...$args) {
            $fn = trim($function);
            return call_user_func_array($fn, $args);
        }));
    }

    private function addWc()
    {
        if (function_exists('WC')) {
            self::$twig->addGlobal('WC', WC());
        }
    }

    private function addGetField()
    {
        if (function_exists('get_field')) {
            self::$twig->addFunction(new \Twig_Function('get_field', function (string $selector, $post_id = false, bool $format_value = true) {
                return get_field($selector, $post_id, $format_value);
            }, ['is_safe' => ['html']]));
        }
    }
}
