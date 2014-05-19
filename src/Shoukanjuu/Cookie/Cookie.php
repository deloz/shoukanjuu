<?php

namespace Shoukanjuu;

use Shoukanjuu\Kernel;

class Cookie
{
    private $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    } 
}