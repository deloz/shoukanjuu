<?php

namespace Shoukanjuu;

use Shoukanjuu\Singleton;
use Shoukanjuu\File;

class Config extends Singleton
{
    protected static $configs = [];

    public function get($key)
    {
        return isset(self::$configs[$key]) ? self::$configs[$key] : null;
    }

    public function set($key, $val)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                self::$configs[$k] = $v;
            }
        } else {
            self::$configs[$key] = $val;
        }
    }

}