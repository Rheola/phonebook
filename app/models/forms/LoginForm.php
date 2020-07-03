<?php

namespace models\forms;

use core\App;
use core\Captcha;
use core\forms\Form;
use models\User;

class LoginForm extends Form
{

    public $email;
    public $password;
    public $captcha;


    private $user;

    public function validate()
    {
        if (empty($this->email)) {
            $this->errors['email'][] = 'Email обязателен';

            return false;
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors['email'][] = 'Неверный формат email';

            return false;
        }


        $user = User::findOneByParam(['email'=>$this->email]);
        if (!$user) {
            $this->errors['email'][] = 'Пользователь не найден';

            return false;
        }
        $this->user = $user;

        if (empty($this->password)) {
            $this->errors['password'][] = 'Укажите пароль';

            return false;
        }

        $captcha = new Captcha();

        if (!$captcha->validateCaptcha($this->captcha)) {

            $this->errors['captcha'][] = 'Неверная капча';

            return false;
        }

        return true;
    }

    public function authenticate()
    {
        if ($this->hashPassword() !== $this->user->password) {
            $this->errors['password'][] = 'Неверный пароль';

            return false;
        }

        $_SESSION['is_auth'] = true;
        $_SESSION['user'] = $this->user;

        return true;
    }

    public function hashPassword()
    {
        return md5($this->password . App::getInstance()->params['salt']);
    }
}