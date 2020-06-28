<?php

namespace models\forms;

use core\forms\Form;
use core\forms\FormTrait;

class RegisterForm extends Form
{
    use FormTrait;

    public $email;
    public $login;
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

        $pattern = '/^[a-zA-Z0-9]*$/';
        preg_match($pattern, $this->login, $matches, PREG_OFFSET_CAPTURE);

        if (count($matches) === 0) {
            $this->errors['login'][] = 'Неверный формат логина';

            return false;

        }

        if ($this->password != $this->passwordRepeat) {
            $this->errors['password'][] = 'Пароли не совпадают';

            return false;
        }

        $patternPassword = '/^(?=.*\d).(?=.*\w).{4,50}$/';
        preg_match($patternPassword, $this->password, $matches, PREG_OFFSET_CAPTURE);
        if (count($matches) === 0) {
            $this->errors['password'][] = 'Ненадежный пароль';

            return false;

        }

        return true;
    }

}