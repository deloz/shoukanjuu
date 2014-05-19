<?php

class SiteController extends \BaseController
{
    public function home()
    {
        $this->res->render('site/home', [
            'pageTitle' => '首页'
        ]);
    }
}