<?php

namespace Shoukanjuu\Http;

use Shoukanjuu\Kernel;

class Response
{
    const DEFAULT_CONTENT_TYPE = 'text/html';
    public $headers = [];
    protected $status = 200;
    protected $body;
    private $kernel;
    public static $codes = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );    

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->setHeader('Content-Type', self::DEFAULT_CONTENT_TYPE);
    }

    public function render($file, $data = null, $key = null)
    {
        $view = $this->kernel->getView();

        if (!is_null($view->layout)) {
            $view->setLayout($file, $data);
            $file = $view->layout;
        } 

        return is_null($key) ? $view->render($file, $data) : $view->set($key, $view->fetch($file, $data));
    }

    public function send($content, $code = 200)
    {
        $this->status($code)
            ->sendHeaders();
        exit($content);
    }

    public function status($code)
    {
        if (array_key_exists($code, self::$codes)) {
            $this->status = $code;
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid status code [%s].', $code));
            
        }
        return $this;
    }

    public function cache($expires)
    {

    }

    public function setHeader($name, $val)
    {
        $this->headers[$name] = $val;
        return $this;
    }

    public function getHeader($name)
    {
        $headers = $this->getHeaders();
        return array_key_exists($name, $headers) ? $headers[$name] : null;
    }

    public function getHeaders()
    {
        return is_null($this->headers) ? [] : $this->headers;
    }

    public function json($content, $code = 200, $jencode = true)
    {
        $this->body = $jencode ? json_encode($content) : $content;
        $this->setHeader('Content-Type', 'application/json')
            ->send($this->body, $code);
    }

    public function jsonp($content, $func = 'jsonp', $code = 200, $jencode = true)
    {
        $this->body = $jencode ? json_encode($content) : $content;
        $this->setHeader('Content-Type', 'application/javascript')
            ->send($func.'('.$this->body.');', $code); 
    }

    public function sendHeaders()
    {
        header(sprintf('Status: %d %s', $this->status, self::$codes[$this->status]));
        header(sprintf('%s %d %s', isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1', $this->status, self::$codes[$this->status]));

        foreach ($this->headers as $name => $val) {
            header($name.': '.$val);
        }

        return $this;
    }
}