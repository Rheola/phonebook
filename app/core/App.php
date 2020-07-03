<?php

namespace core;

use LogicException;
use models\User;

class App
{


    /**
     * @var User
     */
    public static $user;
    /**
     * singleton instance
     *
     * @var App
     */
    protected static $app;
    /**
     * @var array custom module parameters (name => value).
     */
    public $params = [];


    private $root_path;

    /**
     * Hide constructor, protected so only subclasses and self can use
     */
    protected function __construct()
    {

        $this->params = require __DIR__ . '/../conf/params.php';
    }


    /**
     * @throws \LogicException
     */
    public function __clone()
    {
        throw new LogicException("Can't clone a singleton");
    }

    /**
     * @throws \LogicException
     */
    public function __wakeup()
    {
        throw new LogicException("Can't wakeup a singleton");
    }


    /**
     * Returns singleton instance of App
     *
     * @return App
     */
    public static function getInstance()
    {
        if (self::$app === null) {
            self::$app = new App();
        }

        return self::$app;
    }


    /**
     *
     */
    public static function goEnter()
    {
        header('Location: /user/login/');
        exit();
    }

    /**
     *
     */
    public static function goHome()
    {
        header('Location: /');
        exit();
    }

    public static function goBack()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
        self::goHome();
    }

    /**
     * @return mixed
     */
    public function getRootPath()
    {
        return $this->root_path;
    }

    /**
     * @param mixed $root_path
     */
    public function setRootPath($root_path)
    {
        $this->root_path = $root_path;
    }
}