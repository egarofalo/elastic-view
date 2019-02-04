<?php

namespace CoDevelopers\Elastic\Component;

class View
{
    private static $viewPath = null;
    private static $cachePath = null;
    private static $twig = null;

    private static function initViewPath()
    {
        if (is_null(self::$viewPath)) {
            self::$viewPath = [
                rtrim(get_stylesheet_directory(), '/') . '/views',
                rtrim(get_stylesheet_directory(), '/') . '/templates'
            ];
        }
    }

    private static function initCachePath()
    {
        if (is_null(self::$cachePath)) {
            self::$cachePath = rtrim(get_stylesheet_directory(), '/') . '/twig_cache';
        }
    }

    private static function initTwig()
    {
        if (is_null(self::$twig)) {
            self::initViewPath();
            self::initCachePath();
            $loader = new \Twig_Loader_Filesystem(self::$viewPath);
            self::$twig = new \Twig_Environment($loader, [
                'debug' => WP_DEBUG,
                'cache' => self::$cachePath,
            ]);
            self::addFaker();
            self::addAsset();
            self::addDump();
            self::addFn();
            self::addWc();
            self::addGetField();
        }
    }

    public static function render(string $template, array $vars = []): string
    {
        self::initViewPath();
        if (is_array($vars)) {
            extract($vars);
        }
        ob_start();
        include self::$viewPath . '/' . $template;
        return ob_get_clean();
    }

    public static function renderTwig(string $template, array $vars = []): string
    {
        self::initTwig();
        return self::$twig->render($template, $vars);
    }

    private static function addFaker()
    {
        if (class_exists('CoDevelopers\\Elastic\\Component\\Faker')) {
            self::$twig->addGlobal('faker', CoDevelopers\Elastic\Component\Faker::get());
        }
    }

    private static function addAsset()
    {
        if (class_exists('CoDevelopers\\Elastic\\Component\\Asset')) {
            self::$twig->addFunction(new \Twig_Function('asset', [CoDevelopers\Elastic\Component\Asset::class, 'get']));
        }
    }

    private static function addDump()
    {
        if (function_exists('dump')) {
            self::$twig->addFunction(new \Twig_Function('dump', function ($var, ...$moreVars) {
                dump($var, ...$moreVars);
            }));
        }
    }

    private static function addFn()
    {
        self::$twig->addFunction(new \Twig_Function('fn', function (string $function, ...$args) {
            $fn = trim($function);
            return call_user_func_array($fn, $args);
        }));
    }

    private static function addWc()
    {
        if (function_exists('WC')) {
            self::$twig->addGlobal('WC', WC());
        }
    }

    private static function addGetField()
    {
        if (function_exists('get_field')) {
            self::$twig->addFunction(new \Twig_Function('get_field', function (string $selector, $post_id = false, bool $format_value = true) {
                return get_field($selector, $post_id, $format_value);
            }, ['is_safe' => ['html']]));
        }
    }
}
