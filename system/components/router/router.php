<?php

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;


class Router {
    public $response;
    public $message;
    protected $app;
    protected $routes;
    protected $mode;
    protected $controllers_dir;
    protected $default_controller;

    public function __construct()
    {
        $this->app = App::instance();
        $this->controllers_dir = APPDIR . 'controllers/';
    }
    
    public function run() 
    {
        $this->getRoutes();
        $parameters = $this->match();

        $response = $this->launch($parameters);
        if($response == false)
        {
            $this->showError();
        } else {
            $response->send();
        }
    }

    public function getRoutes()
    {
        $userRoutes = require APPDIR . 'routes.php';
        $this->mode = $userRoutes['mode'];
        $this->default_controller = $userRoutes['default_controller'];

        $routes = new RouteCollection();

        foreach ($userRoutes['routes'] as $routeName => $userRoute)
        {
            if(!isset($userRoute['requirements']))
            {
                $userRoute['requirements'] = array();
            }
            $routes->add($routeName, new Route($userRoute[0], array('controller' => $userRoute[1]), $userRoute['requirements'] ) );
        }
        
        $this->routes = $routes;
    }
    
    public function match()
    {
        $context = new RequestContext();
        $context->fromRequest($this->app->request());
        $matcher = new UrlMatcher($this->routes, $context);
        try {
            $parameters = $matcher->match($this->app->request()->getPathInfo());
        } catch (Exception $e) {
            $parameters = array();
        }
        return $parameters;
    }
    
    public function launch($parameters) 
    {
        // Manual Mode
        if($this->mode == 'manual')
        {
            return $this->_launch_manual($parameters);

        // Auto Mode
        } elseif ($this->mode == 'auto') {

            return $this->_launch_auto($parameters);
        
        // Both Mode
        } elseif ($this->mode == 'both') {
            $manual_response = $this->_launch_manual($parameters);
            if (!$manual_response) {
                $auto_response = $this->_launch_auto($parameters);
                if (!$auto_response) {
                    return false;
                } else {
                    return $auto_response;
                }
            } else {
                return $manual_response;
            }
        // Other
        } else {
            $this->message = 'ERROR_ROUTER_CONFIG';
            return false;
        }
    }

    public function _launch_manual($parameters){
        if(!isset($parameters['controller'])){
            $this->message = 'ERROR_CONTROLLER_NOT_DEFINED';
            return false;
        }
            $controllers_dir = $this->controllers_dir;
            $controller_info = explode('@', $parameters['controller']);
            $controller_path = $controller_info[0];
            $controller_class = end(explode('/', $controller_path));
            $controller_function = $controller_info[1];

            if(file_exists($controllers_dir . $controller_path . '.php'))
            {
                require $controllers_dir . $controller_path . '.php';
                if(class_exists($controller_class))
                {
                    $object = new $controller_class;
                if(method_exists($object, $controller_function))
                {
                    $param_names = $this->routes->get($parameters['_route'])->compile()->getVariables();
                    $params = array();

                    foreach($parameters as $key => $value)
                    {
                        if(in_array($key, $param_names))
                        {
                            $params[$key] = $parameters[$key];
                        }
                    }
                    return call_user_func_array(array(new $controller_class, $controller_function), $params);

                } else {
                    $this->message = 'ERROR_CONTROLLER_METHOD_NOT_FOUND';
                    return false;
                }
                } else {
                    $this->message = 'ERROR_CONTROLLER_CLASS_NOT_FOUND';
                    return false;
                }
                
            } else {
                $this->message = 'ERROR_CONTROLLER_FILE_NOT_FOUND';
                return false;
            }
    }

    public function _launch_auto($parameters)
    {
        $url_path = $this->app->request()->getPathInfo();
        $path_parts = explode('/', $url_path);

        $actual_path = array();
        $actual_parameters = array();

        $controllers_dir = $this->controllers_dir;
        $default_controller = $this->default_controller;
        $call_action = 'index';

        $controller = '';
        foreach ($path_parts as $segment) {
            if (empty($segment)) {
                continue;
            }
            $path = $controllers_dir . implode('/', $actual_path) .'/' . $segment;
            if (is_dir($path)) {
                $actual_path[] = $segment;
            } else if (is_file($path . '.php')) {
                $actual_path[] = $segment . '.php';
                $controller = $segment;
            } else {
                $actual_parameters[] = $segment;
            }
        }

        if (empty($actual_parameters) && $controller == '') {
            $actual_path[] = $default_controller . '.php';
            $controller = $default_controller;
        }
        if (count($actual_parameters) >= 1) {
            $call_action = $actual_parameters[0];
            unset($actual_parameters[0]);
            $actual_parameters = array_values($actual_parameters);
        }

        $launchable = array(
            'controller_class' => $controller,
            'action' => $call_action,
            'parameters' => $actual_parameters,
            'file_path' => $controllers_dir . implode('/', $actual_path),
        );

        if (empty($launchable['controller_class'])) {
            $this->message = 'ERROR_CONTROLLER_NOT_DEFINED';
            return false;
        } else {
            if (file_exists($launchable['file_path'])) {
                require $launchable['file_path'];
                if (class_exists($launchable['controller_class'])) {
                    if (method_exists(new $launchable['controller_class'], $launchable['action'])) {
                        return call_user_func_array(array(new $launchable['controller_class'], $launchable['action']),
                            $launchable['parameters']
                        );
                    } else {
                        $this->message = 'ERROR_CONTROLLER_METHOD_NOT_FOUND';
                        return false;
                    }
                } else {
                    $this->message = 'ERROR_CONTROLLER_CLASS_NOT_FOUND';
                    return false;
                }
            } else {
                $this->message = 'ERROR_CONTROLLER_FILE_NOT_FOUND';
                return false;
            }
        }

    }
    public function showError()
    {
        ob_clean();
        $codes = require SYSDIR . 'framework/error_codes.php';
        $code = $this->message;
        $message = $codes[$code];
        $data = array(
            'ERROR_CODE' => $code,
            'ERROR_MESSAGE' => $message
        );
        $response = View::make('404.twig', $data, 404);
        $response->send();
    }
}

App::register('router', new Router);