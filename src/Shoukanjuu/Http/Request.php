<?php

namespace Shoukanjuu\Http;

use Shoukanjuu\Kernel;

class Request
{
    private $kernel;
    public $originalUrl;
    public $host;
    public $ip;
    public $path;
    public $protocol;
    public $params = [];
    public $cookies = [];
    public $headers = [];
    public $query = [];
    public $xhr = false;
    public $method;
    public $body = '';
    public $route;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->originalUrl = $this->server('REQUEST_URI');

        $parse_urls = parse_url($this->originalUrl);
        $this->path = $parse_urls['path'];
        if (isset($parser_urls['query'])) {
            parse_str($parse_urls['query'], $queries);
            $this->query = $queries;
        }

        $this->ip = $this->getIp();
        $this->body = $this->getBody();
        $this->cookies = $this->getCookies();
        $this->host = $this->server('HTTP_HOST');
        $this->method = $this->getMethod();
        $this->ajax = $this->xhr = $this->server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest';
        $this->protocol = strtolower(preg_replace('#/.*#', '', $this->server('SERVER_PROTOCOL')));
    }

    private function getMethod()
    {
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
        }
        return $this->server('REQUEST_METHOD', 'GET');
    }

    private function getIp()
    {
        return isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
    }

    public function param($key, $val = null)
    {
        return isset($this->params[$key]) ? $this->params[$key] : $this->params[$key] = $val;
    }

    public function setParams($params)
    {
        $this->params = array_merge($this->params, $params, $this->query);
    }

    public function addRoute(Route $route)
    {
        $this->routes[$route->path] = $route;
    }

    public function secure()
    {
        return 'https' === $this->protocol;
    }

    private function server($key = null, $default = null)
    {
        if (is_null($key)) {
            return $_SERVER;
        }

        return isset($_SERVER[$key]) ? $_SERVER[$key] : $_SERVER[$key] = $default;
    }

    public function addHeader($field, $val)
    {
        $this->headers[$field] = $val;
        return $this;
    }

    public function addHeaders(array $headers)
    {
        foreach ($headers as $field => $val) {
            $this->addHeader($field, $val);
        }
        return $this;
    }
        
    public function getHeader($field)
    {
        return isset($this->headers[$field]) ? $this->headers[$field] : null;
    }

    public function getCookies()
    {
    }

    public function getBody()
    {
        return file_get_contents('php://input');
    }

    public function getRawBody()
    {
        return $this->getBody();
    }
}