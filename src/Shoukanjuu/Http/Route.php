<?php

namespace Shoukanjuu\Http;

class Route
{
    public $path;
    public $params;

    public function __construct($path, $params)
    {
        $this->path = $path;
        $this->params = $params; 
    }
}