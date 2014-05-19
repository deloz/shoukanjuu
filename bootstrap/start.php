<?php

error_reporting(E_ALL);

try {
    if (!is_file($autoload = ($root_dir = dirname(__DIR__)).'/vendor/autoload.php')) {
        throw new \InvalidArgumentException('no loader, run `php composer.phar install` first.');
    }

    if (!is_file($app_file = $root_dir.'/app.php') or !is_readable($app_file)) {
        throw new \InvalidArgumentException('app.php does not exist or is not readable');
    }

    require($autoload);

    $config = \Shoukanjuu\Config::getInstance();
    $config->set('global.root_dir', $root_dir);

    require($app_file);


} catch (\Exception $e) {
    echo $e->getMessage();
}

function dd($var) {
    var_dump($var);
    die;
}