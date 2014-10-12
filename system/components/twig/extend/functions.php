<?php

return array(
    'siteUrl' => 'siteUrl',
    'baseUrl' => 'baseUrl',
    'date' => 'date',
    'asset' => function($path = ''){
        return baseUrl('web/' . $path);
    },
    'InputOld' => function ($name, $default = '') {
        $previous = App::instance()->previous();
        if (isset($previous[$name])) {
            return $previous[$name];
        } else {
            return $default;
        }
    },
    'flash' => function ($name) {
        $app = App::instance();
        return $app->flash()->get($name)[0];
    },
    'error' => function ($name) {
        $app = App::instance();
        return $app->errors()->first($name);
    },
    'auth' => function ($type) {
        return Auth::$type();
    }
);