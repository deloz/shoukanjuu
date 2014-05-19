<?php

namespace Shoukanjuu\Template;

class View 
{
    protected $vars = [];
    protected $template;
    public $path;
    public $layout;

    public function __construct($path = '.')
    {
        $this->path = $path;
    }

    public function getVars()
    {
        return $this->vars;
    }

    public function set($key, $val = null)
    {
        if (is_array($key) || is_object($key)) {
            foreach ($key as $k => $v) {
                $this->vars[$k] = $v;
            }
        } else {
            $this->vars[$key] = $val;
        }
    }

    public function setLayout($file, $data = null)
    {
        $this->set('bodyContent', $this->fetch($file, $data));
    }

    public function render($file, $data = null)
    {
        $this->template = $this->getTemplate($file);
        if (!is_file($this->template)) {
            throw new \InvalidArgumentException(sprintf('Template file [%s] not found.', $this->template));
        }

        is_array($data) and $this->vars = array_merge($this->vars, $data);

        extract($this->vars);
        include $this->template;
    }

    public function fetch($file, $data = null)
    {
        ob_start();
        $this->render($file, $data);

        return ob_get_clean();
    }

    public function getTemplate($file, $ext = '.php')
    {
        return $this->path.'/'.('.php' !== strtolower(substr($file, -4)) ? $file .= $ext : $file);
    }
}