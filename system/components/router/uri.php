<?php

class Uri {
    protected $app;
    protected $segments;

    public function __construct()
    {
        $this->app = App::instance();
        $segments = explode('/', $this->app->request()->getPathInfo());
        $this->segments = $segments;
    }

    public function segment($index)
    {
        if (isset($this->segments[$index])) {
            return $this->segments[$index];
        } else {
            return '';
        }
    }
}

App::register('uri', new Uri());