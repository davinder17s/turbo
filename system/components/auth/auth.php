<?php

class Auth {
    protected $config;
    protected $app;
    protected $type;
    protected $auth = array();

    public function __construct()
    {
        $this->app = App::instance();
        $config = require APPDIR . 'config/auth.php';
        $this->config = $config;
        if ($this->app->session()->has('auth')) {
            $this->auth = $this->app->session()->get('auth');
        }
    }

    public function authenticate($credentials)
    {
        $model = $this->config['types'][$this->type]['model'];
        $results = $model::where($credentials)->first();

        if ($results) {

            if (!$this->app->session()->has('auth')) {
                $this->app->session()->set('auth', array());
            }

            $auth = $this->app->session()->get('auth');

            if (!isset($auth['types'])) {
                $auth['types'] = array();
            }

            if (!isset($auth['types'][$this->type])) {
                $auth['types'][$this->type] = array();
            }

            $auth['types'][$this->type]['id'] = $results->id;
            $auth['types'][$this->type]['data'] = $results;

            $this->app->session()->set('auth', $auth);
            return true;
        } else {
            return false;
        }
    }

    public function validate($credentials)
    {
        $model = $this->config['types'][$this->type]['model'];
        $results = $model::where($credentials)->first();

        if ($results) {
            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        if (!empty($this->auth)) {
            $auth = $this->auth;
            unset($auth['types'][$this->type]);
            $this->app->session()->set('auth', $auth);
            return true;
        } else {
            return false;
        }
    }

    public function get()
    {
        if (!empty($this->auth) && isset($this->auth['types'][$this->type])) {
            $model = $this->config['types'][$this->type]['model'];

            $data =  $this->auth['types'][$this->type]['data'];
            $new_data = $this->app->_cast($model, $data);
            return $new_data;
        } else {
            return false;
        }
    }

    public function id()
    {
        if (!empty($this->auth)) {
            if (isset($this->auth['types'][$this->type])) {
                $id =  $this->auth['types'][$this->type]['id'];
                return $id;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function check()
    {
        if ($this->id() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function __callStatic($name, $params)
    {
        $authClass = new Auth();
        $authClass->type = $name;
        return $authClass;
    }
}

App::register('auth', new Auth);