<?php

use core\App;
use models\User;

session_start();


function autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    $r = __DIR__ . '/../' . $fileName;
    if (is_file($r)) {

        require $r;

        return;
    }

    var_dump($r);
    debug_print_backtrace();

    throw new Exception();
}

spl_autoload_register('autoload');
date_default_timezone_set('Europe/Moscow');


error_reporting(E_ALL);


$globalPath = __DIR__ . '/../';

$app = App::getInstance();
$app->setRootPath($globalPath);

App::$user = new  User();
App::$user->setGuest(true);


if (isset($_SESSION['is_auth']) && isset($_SESSION['user'])) {
    $array = (array)$_SESSION['user'];
    $user = User::findOneByParam(['email'=>$array['email']]);
    App::$user = $user;
    App::$user->setGuest(false);
}