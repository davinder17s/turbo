<?php

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\ArrayLoader;

class Validator {

    protected static $factory;

    public static function instance()
    {
        if ( ! static::$factory)
        {
            $translator = new Translator('en', new MessageSelector());
            $translator->setFallbackLocales(array('en'));
            $translator->addLoader('array', new ArrayLoader());
            $translator->addResource('array', require APPDIR . 'lang/en/validation.php', 'en');
            static::$factory = new Illuminate\Validation\Factory($translator);
        }

        return static::$factory;
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::instance();
        return call_user_func_array(array($instance, $method), $args);
    }
}
