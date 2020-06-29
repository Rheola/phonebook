<?php

namespace controllers;

use core\App;
use core\Controller;
use models\forms\LoginForm;
use models\forms\RegisterForm;
use models\User;

class UserController extends Controller
{

    /**
     *
     */
    public function actionLogin()
    {
        if (!App::$user->isGuest()) {
            App::goHome();
        }

        $this->pageTitle = 'Вход';


        $form = new LoginForm();
        if (isset($_POST['LoginForm'])) {
            $form->load($_POST['LoginForm']);


            if ($form->validate() && $form->authenticate()) {
                $this->redirect('/phone/');
            }
        }

        $this->render('login', [
            'loginForm' => $form,
        ]);
    }


    /**
     *
     */
    public function actionRegister()
    {
        if (!App::$user->isGuest()) {
            App::goHome();
        }

        $this->pageTitle = 'Регистрация';

        $form = new RegisterForm();
        if (isset($_POST['RegisterForm'])) {
            $form->load($_POST['RegisterForm']);

            if ($form->validate()) {
                $user = new User();
                $user->login = $form->login;
                $user->email = $form->email;
                $user->password = md5($user->password);

                $user->save();

                $this->redirect('/user/login');
            }

        }

        $this->render('register', [
            'form' => $form,
        ]);
    }

    /**
     *
     */
    public function actionLogout()
    {
        if (App::$user->isGuest()) {
            App::goHome();
        }
        $_SESSION = [];
        session_destroy();
        $this->redirect('/');
    }
}