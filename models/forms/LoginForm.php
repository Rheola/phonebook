<?php

namespace models\forms;

use core\forms\Form;
use models\User;

class LoginForm extends Form
{

    public $email;
    public $password;


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


        $user = User::findByEmail($this->email);
        if (!$user) {
            $this->errors['email'][] = 'Пользователь не найден';

            return false;
        }

        if (empty($this->password)) {
            $this->errors['password'][] = 'Укажите пароль';

            return false;
        }


        if (md5($this->password) !== $user->password) {
            $this->errors['password'][] = 'Неверный пароль';

            return false;
        }

        return true;
    }

}