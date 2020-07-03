<?php

namespace models\forms;

use core\App;
use core\Captcha;
use core\forms\Form;
use models\User;

class RegisterForm extends Form
{

    public $email;
    public $login;
    public $captcha;
    public $password;
    public $passwordRepeat;


    public function validate()
    {
        if (empty($this->email)) {
            $this->errors['email'][] = 'Email обязателен';

            return false;
        }

        if (empty($this->login)) {
            $this->errors['login'][] = 'Логин обязателен';

            return false;
        }


        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors['email'][] = 'Неверный формат email';

            return false;
        }

        $user = User::findOneByParam(['email' => $this->email]);
        if ($user) {
            $this->errors['email'][] = 'Этот email занят';

            return false;
        }

        $user = User::findOneByParam(['login' => $this->login]);

        if ($user) {
            $this->errors['login'][] = 'Этот логин занят';

            return false;
        }

        $pattern = '/^[a-zA-Z0-9]*$/';
        preg_match($pattern, $this->login, $matches, PREG_OFFSET_CAPTURE);

        if (count($matches) === 0) {
            $this->errors['login'][] = 'Неверный формат логина';

            return false;

        }

        if ($this->password !== $this->passwordRepeat) {
            $this->errors['password'][] = 'Пароли не совпадают';

            return false;
        }

        if (!preg_match("#[0-9]+#", $this->password)) {
            $this->errors['password'][] = 'Пароль должен содержать хотя бы одну цифру.';
            return false;
        }

        if (!preg_match("#[a-zA-Z]+#", $this->password)) {
            $this->errors['password'][] = 'Пароль должен содержать хотя бы одну букву.';

            return false;
        }

        $captcha = new Captcha();

        if (!$captcha->validateCaptcha($this->captcha)) {

            $this->errors['captcha'][] = 'Неверная капча';

            return false;
        }

        return true;
    }

    public function hashPassword()
    {
        return md5($this->password . App::getInstance()->params['salt']);
    }

}