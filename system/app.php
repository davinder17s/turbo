<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class App {
    protected $request;
    protected $autoload = array();

    protected $post;
    protected $get;
    protected $cookies;
    protected $attributes;
    protected $files;
    protected $server;
    protected $headers;
    protected $session;
    protected $flash;

    protected $errors;
    protected $previous;

    public $container = array();

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->post = $request->request;
        $this->get = $request->query;
        $this->cookies = $request->cookies;
        $this->attributes = $request->attributes;
        $this->files = $request->files;
        $this->server = $request->server;
        $this->headers = $request->server;

        $session = new Session();
        $session->start();
        $this->session = $session;
        $this->flash = $this->session->getFlashBag();

        $messages = $this->_cast('Illuminate\Support\MessageBag', (object)$this->flash->get('errors'));
        $this->errors = $messages;
        $this->previous = $this->flash->get('input_old');
    }

    public function start()
    {
        $this->_boot_framework();
        $this->_autoload();
    }

    public function _cast($class, $object)
    {
        return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($class) . ':"' . $class . '"', serialize($object)));
    }

    protected function _boot_framework()
    {
        $components_dir = SYSDIR . 'components/';
        $components = require SYSDIR . 'register.php';
        foreach($components as $component_name)
        {
            if(is_dir($components_dir . $component_name))
            {
                require $components_dir . $component_name . '/' . $component_name . '.php';
            }
        }

        require SYSDIR . 'framework/framework.php';
    }

    public static function register($component_name, $component)
    {
        $app = App::instance();
        $app->container[strtolower($component_name)] = $component;
        return true;
    }

    protected function _autoload()
    {
        require APPDIR . 'config/autoload.php';
        $this->autoload['packages'] = $packages;
        $this->autoload['libraries'] = $libraries;
        $this->autoload['models'] = $models;

        $this->_autoload_packages();
        $this->_autoload_libraries();
        $this->_autoload_models();
    }

    protected function _autoload_packages()
    {
        $package_dir = APPDIR . 'packages/';
        $packages = $this->autoload['packages'];

        foreach ($packages as $package_name)
        {
            if(is_dir( $package_dir . $package_name ) ){
                require $package_dir . $package_name . '/' . $package_name . '.php';
            }
        }
    }

    protected function _autoload_libraries()
    {
        $library_dir = APPDIR . 'libraries/';
        $libraries = $this->autoload['libraries'];

        foreach ($libraries as $library_name)
        {
            if(is_file( $library_dir . $library_name . '.php' ) ){
                require $library_dir . $library_name . '.php';
                $library = new $library_name;
                $this->container['libraries'][strtolower($library_name)] = $library;
            }
        }
    }

    protected function _autoload_models()
    {
        $model_dir = APPDIR . 'models/';
        $models = $this->autoload['models'];

        foreach ($models as $model_name)
        {
            if(is_file( $model_dir . $model_name . '.php' ) ){
                require $model_dir . $model_name . '.php';
                $model = new $model_name;
                $this->container['models'][strtolower($model_name)] = $model;
            }
        }
    }

    public function __get($name)
    {
        return $this->container[strtolower($name)];
    }

    public function  __call($name, $args)
    {
        if(!method_exists($this, $name)){
            return $this->$name;
        }
    }

    public static function instance(){
        global $app;
        return $app;
    }

    public function shutdown()
    {
        // something to do for shutdown
    }

}
