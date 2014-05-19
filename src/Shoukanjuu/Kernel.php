<?php

namespace Shoukanjuu;

use Shoukanjuu\Http\Request;
use Shoukanjuu\Http\Response;
use Shoukanjuu\Http\Route;
use Shoukanjuu\Template\View;
use Shoukanjuu\Cookie;
use Shoukanjuu\Config;
use Shoukanjuu\Session;
use Shoukanjuu\Exception\PageNotFound;
use Closure;
use ReflectionFunction;

class Kernel
{
    public $req;
    public $res;
    protected $session;
    protected $router;
    private $root_dir;
    private $is_mvc = false;
    private $regexps = [];
    private $routes = [];
    public $cookie;
    public $view;
    public static $settings = [
        'debug' => false,
        'templates.path' => '.',
    ];

    public function __construct(array $settings = [])
    {
        self::$settings = array_merge(self::$settings, $settings);

        $this->session = new Session;
        $this->req = $this->getReq();
        $this->res = $this->getRes();
        $this->config = Config::getInstance(); 
        $this->root_dir = $this->config->get('global.root_dir');

        $this->init();
    }

    public function getView()
    {
        return is_null($this->view)
            ? $this->view = new View($this->is_mvc ? $this->root_dir.'/app/View' : self::$settings['templates.path'])
            : $this->view;
    }

    private function init()
    {
        $this->config->set('app', File::getRequire($this->root_dir.'/app/config/app.php'));
        $this->config->set('database', File::getRequire($this->root_dir.'/app/config/database.php'));
    }

    public function param($flag, $regexp)
    {
        $this->regexps[$flag] = $regexp;
    }

    public function listen()
    {
        $this->handleRequest();
    }

    private function handleRequest()
    {
        $matched = false;

        foreach ($this->routes as $path => $route) {
            if ($this->req->method !== $route['method']) {
                continue;
            }
            
            $callback = $route['callback'];
            $params = [];

            $path_segments = explode('/', $path);
            $is_found = false;
            foreach ($path_segments as &$path_segment) {
                if (array_key_exists($key = str_replace(':', '', $path_segment), $this->regexps)) {
                    $path_segment = '(?<'.$key.'>'.$this->regexps[$key].')';
                    $is_found = true;
                }
            }

            $path = implode('/?', $path_segments);

            if (preg_match("#^{$path}$#", $this->req->path, $matches)) {
                foreach ($matches as $key => $value) {
                    if (array_key_exists($key, $this->regexps)) {
                        $params[$key] = $value;
                    }
                }
                $matched = true;
                $this->req->setParams($params);
                /*
                $route = new Route($path, $params);
                $this->req->addRoute($route);
                */
                break;
            }
        }

        if (!$matched) {
            $this->pageNotFound();
        }

        if (is_string($callback)) {
            if (!strpos($callback, '@')) {
                $this->pageNotFound();
            }

            $this->is_mvc = true;
            list($controller, $action) = explode('@', $callback);
            $obj = new $controller($this);
            call_user_func_array([$obj, $action], $this->req->params);
        } elseif ($callback instanceof Closure) {
            $reflector = new ReflectionFunction($callback);
            $parameters = $reflector->getParameters();
            $param_arr = [];
            $miss_arguments = [];

            foreach ($parameters as $parameter) {
                $param_name = $parameter->getName();
                if (in_array($param_name, ['res', 'req'])) {
                    $param_arr[] = $this->$param_name;
                } elseif (isset($this->req->params[$param_name])) {
                    $param_arr[] = $this->req->params[$param_name];
                } else {
                    $miss_arguments[] = '$'.$param_name;
                }
            }
            call_user_func_array($callback, $param_arr);
        }        
    }

    private function pageNotFound()
    {
        throw new PageNotFound(sprintf('Page [%s] not found.', $this->req->originalUrl));
    }

    public function getReq()
    {
        if (is_null($this->req)) {
            $this->req = new Request($this);
        }

        return $this->req;
    }

    public function getRes()
    {
        if (is_null($this->res)) {
            $this->res = new Response($this);
        }

        return $this->res;
    }

    public function method($method, $path, $callback = null)
    {
        $method = strtoupper($method);
        $path = empty($path = rtrim($path, '/')) ? '/' : $path;
        $this->routes[$path] = [
            'callback' => $callback,
            'method' => $method,
        ];
    }

    public function get($path, $callback)
    {
        return $this->method('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        return $this->method('POST', $path, $callback);
    }

    public function put($path, $callback)
    {
        return $this->method('PUT', $path, $callback);
    }
}
