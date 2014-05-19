<?php

class PasswordController extends \BaseController
{
    public function index()
    {
        return $this->res->render('password/index.php', [
            'pageTitle' => '检测密码是否泄露',
        ]);
    }

    public function md5s()
    {
        $this->res->render('password/md5s', [
            'pageTitle' => 'MD5解密列表',
        ]);
    }
}