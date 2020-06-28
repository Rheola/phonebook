<?php

namespace models\forms;

use core\forms\Form;

class RegisterForm
{
    use Form;

    public $email;
    public $login;
    public $password;
    public $passwordRepeat;


    public function validate()
    {

    }

}