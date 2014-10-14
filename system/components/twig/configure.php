<?php

class TwigConfiguration {
    public static function setup()
    {
        $app = App::instance();
        $global = (array) require __DIR__ . '/extend/global.php';
        $filters = (array) require __DIR__ . '/extend/filters.php';
        $functions = (array) require __DIR__ . '/extend/functions.php';

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