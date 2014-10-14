<?php

require __DIR__ . '/redirect.php';
require __DIR__ . '/response.php';

class Controller {
    protected $app;

    function __construct()
    {
        $app = App::instance();
        $this->app = $app;

    }

    public function __get($name)
    {
        return $this->app->container[strtolower($name)];
    }

    public function __call($name, $params)
    {
        if (!method_exists($this, $name)) {
            if (
                !empty($params)
                &&
                in_array($name, array('get', 'post', 'server', 'cookies', 'attributes', 'files', 'headers', 'session', 'flash')))
            {
                return $this->app->$name()->get($params[0]);
            }
           return $this->app->$name();
        }
    }
}

App::register('controller', new Controller);