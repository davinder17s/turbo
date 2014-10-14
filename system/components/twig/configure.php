<?php

class TwigConfiguration {
    public static function setup()
    {
        $app = App::instance();
        $default_global = (array) require __DIR__ . '/extend/global.php';
        $default_filters = (array) require __DIR__ . '/extend/filters.php';
        $default_functions = (array) require __DIR__ . '/extend/functions.php';

        $user_global = (array) require APPDIR . 'twig/extend/global.php';
        $user_filters = (array) require APPDIR . 'twig/extend/filters.php';
        $user_functions = (array) require APPDIR . 'twig/extend/functions.php';

        $global = array_merge($default_global, $user_global);
        $filters = array_merge($default_filters, $user_filters);
        $functions = array_merge($default_functions, $user_functions);

        foreach ($global as $name => $callable)
        {
            $app->twig->addGlobal($name, $callable);
        }

        foreach ($filters as $name => $callable)
        {
            $app->twig->addFilter(new Twig_SimpleFilter( $name, $callable));
        }

        foreach ($functions as $name => $callable)
        {
            $app->twig->addFunction(new Twig_SimpleFunction( $name, $callable));
        }
    }

    public static function addFunction($name, $callable)
    {
        $app = App::instance();
        $app->twig->addFunction(new Twig_SimpleFunction( $name, $callable));
    }

    public static function addFilter($name, $callable)
    {
        $app = App::instance();
        $app->twig->addFilter(new Twig_SimpleFilter( $name, $callable));
    }

    public static function addGlobal($name, $callable)
    {
        $app = App::instance();
        $app->twig->addGlobal($name, $callable);
    }
}