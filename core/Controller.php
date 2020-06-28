<?php

namespace core;


/**
 * Class Controller
 */
class Controller
{

    public $model;
    public $view;

    public $layout = 'main';
    public $pageTitle = '';

    public $menu = [];

    private $_id;

    public $js = [];
    public $rawJS = [];

    public $user;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $name = get_class($this);

        $data = explode('\\', $name);

        $id = array_pop($data);

        $this->_id = strtolower(substr($id, 0, -strlen('Controller')));
    }

    /**
     *
     */
    public function actionIndex()
    {
        $this->render($this->_id . '/index');
    }

    /**
     * @param $view
     * @param null $data
     */
    public function render($view, $data = null)
    {
        $this->generate($this->_id . '/' . $view, $data);
    }

    /**
     * @param $url
     */
    public function redirect($url)
    {
        header('Location: ' . $url);
        exit();
    }


    /**
     * @param $view
     * @return string
     */
    private function findViewFile($view)
    {

        if (strncmp($view, '//', 2) === 0) {
            // e.g. "//layouts/main"
            $file = __DIR__ . '/../views/' . ltrim($view, '/');
        } elseif (strncmp($view, '/', 1) === 0) {
            // e.g. "/site/index"
            $file = __DIR__ . '/../views/' . ltrim($view, '/');
        } else {
            $file = __DIR__ . '/../views/' . $this->_id . '/' . $view;
        }

        return $file;
    }

    /**
     * @param $view
     * @param null $data
     */
    public function renderPartial($view, $data = null)
    {
        if (is_array($data)) {
            extract($data, EXTR_OVERWRITE);
        }

        $file = $this->findViewFile($view);
        include $file . '.php';
    }

    /**
     * @param $template
     * @param null $data
     */
    private function generate($template, $data = null)
    {

        if (is_array($data)) {
            extract($data, EXTR_OVERWRITE);
        }

        include __DIR__ . '/../views/' . $this->layout . '.php';
        exit();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }


    public static function error404()
    {
        $controller = new self();
        $controller->render('404');
    }

    /**
     *
     */
    public function refresh()
    {
        $url = $_SERVER['REQUEST_URI'];
        header('Location: ' . $url);
        exit();
    }


    /**
     * @param $key
     * @param $value
     */
    public function setFlash($key, $value)
    {
        $dir = __DIR__ . '/../cache/session/';

        $user = App::$user;
        $logFile = $dir . sprintf('%d.json', $user->id);
        $log = fopen($logFile, 'wb+');
        $data = [];
        $data[$key] = $value;
        $text = json_encode($data);
        fwrite($log, $text);
        fclose($log);

        $_SESSION[$key] = $value;
    }


    /**
     * @param $key
     * @return null
     */
    public function getFlash($key)
    {
        $user = App::$user;

        $dir = __DIR__ . '/../cache/session/';
        $sessionFile = $dir . sprintf('%d.json', $user->id);

        if (!is_file($sessionFile)) {
            return null;
        }
        $handle = fopen($sessionFile, 'rb');
        $contents = fread($handle, filesize($sessionFile));

        $data = json_decode($contents, true);
        $data = (array)$data;
        if (isset($data[$key])) {
            unlink($sessionFile);

            return $data[$key];
        }

        return null;
    }
}