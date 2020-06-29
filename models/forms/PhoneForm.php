<?php


namespace models\forms;


use core\forms\Form;

class PhoneForm extends Form
{
    public $email;
    public $first_name;
    public $last_name;
    public $phone;
    public $file;


    public function validate()
    {
        if (!empty($this->email)) {
            if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
                $this->errors['email'][] = 'Неверный формат email';

                return false;
            }
        }

        if (empty($this->phone)) {
            $this->errors['phone'][] = 'Укажите телефон';
            return false;
        }

        return true;
    }
}