<?php

namespace Shoukanjuu;

class Singleton
{
    protected static $instance;

    private function __construct() {}
    
    private function __clone() {}

    public static function getInstance()
    {
        return is_null(static::$instance) ? static::$instance = new static : static::$instance;
    }
}