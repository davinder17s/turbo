<?php

return array(
    'siteUrl' => 'siteUrl',
    'baseUrl' => 'baseUrl',
    'fullSiteUrl' => 'fullSiteUrl',
    'fullBaseUrl' => 'fullBaseUrl',
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
    },
    'trans' => function ($key = '', $vars = array(), $file = '') {
        $app = App::instance();
        if ($file == '') {
            $template = null;
            foreach (debug_backtrace() as $trace) {
                if (isset($trace['object']) && $trace['object'] instanceof Twig_Template && 'Twig_Template' !== get_class($trace['object'])) {
                    $template = $trace['object'];
                }
            }
            $templatename = $template->getTemplateName();
        } else {
            $templatename = $file . '.twig';
        }
        if (is_object($vars)) {
            $vars = (array)$vars;
        }
        return $app->translator->trans($key, $templatename, $vars);
    }
);