<?php

use Shoukanjuu\Controller;

class BaseController extends Controller
{

    public function init()
    {
        parent::init();

        $this->kernel->view->set([
            'siteName' => '博客',
            'version' => '14.5.18',
        ]);
    }
}