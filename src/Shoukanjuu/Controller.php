<?php

namespace Shoukanjuu;

use Shoukanjuu\Kernel;
use Closure;

class Controller
{
    protected $kernel;
    private $functions = [];
    protected $layout = 'layout';
    protected $view;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->view = $this->kernel->getView();
        $this->view->layout = $this->layout;

        $this->init();
    }

    public function init()
    {
    }

    private function getClosure($name, Closure $closure)
    {
        $this->functions[$name] = $closure->bindTo($this, $this);
    }

    public function __get($name)
    {
        return $this->kernel->{$name};
    }

    public function __call($name, array $args)
    {
        $this->getClosure($name, function() use ($name) {
            return $this->kernel->$name;
        });
        return call_user_func_array($this->functions[$name], $args);
    }
}
