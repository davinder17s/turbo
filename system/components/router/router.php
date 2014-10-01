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

    public function __construct()
    {
        $this->response = new Response('Not Found', 404);
        $this->app = App::instance();
        $this->controllers_dir = APPDIR . 'controllers/';
    }
    
    public function run() 
    {
        $this->getRoutes();
        $parameters = $this->match();

        $this->launch($parameters);
    }

    public function getRoutes()
    {
        $userRoutes = require APPDIR . 'routes.php';
        $this->mode = $userRoutes['mode'];

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

            if(!$this->_launch_manual && !$this->_launch_auto){
                return false;
            }
        // Other
        } else {
            $this->message = 'Invalid mode: Please correct your router configuration';
        }
    }

    public function _launch_manual($parameters){
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
                    $this->response = call_user_method_array($controller_function, $object, $params);
                    return true;
                } else {
                    $this->message = 'Controller Method Not Found.';
                    return false;
                }
                } else {
                    $this->message = 'Controller class not found';
                    return false;
                }
                
            } else {
                $this->message = 'Controller file not found.';
                return false;
            }
    }
}