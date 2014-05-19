<?php

$app = new \Shoukanjuu\Kernel;

$app->param('site', '[a-z]+');
$app->param('page', '\d*');

$app->get('/', 'SiteController@home');

$app->get('/password/:site', 'PasswordController@index');
$app->get('/password/md5s/:page', 'PasswordController@md5s');

$app->listen();