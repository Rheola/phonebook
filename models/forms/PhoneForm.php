<?php


namespace models\forms;


use core\App;
use core\forms\Form;
use models\Phone;

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
            }
        }

        if (empty($this->phone)) {
            $this->errors['phone'][] = 'Укажите телефон';
        }


        if (!empty($_FILES['PhoneForm']['tmp_name']['file'])) {
            $this->validateFile();
        }

        return !$this->hasErrors();
    }

    public function validateFile()
    {
        $size = $_FILES['PhoneForm']['size']['file'];


        if ($size > 2 * 1024 * 1024) {
            $this->errors['file'][] = 'Слишком большой файл. Максимальный размер - 2 МБ';

            return false;
        }

        $fileType = pathinfo($_FILES['PhoneForm']['name']['file'], PATHINFO_EXTENSION);
        $fileType = strtolower($fileType);
        $allowedTypes = ['jpg', 'png'];

        if (!in_array($fileType, $allowedTypes)) {
            $this->errors['file'][] = 'Разрешенные типы файлов jpg и png';

            return false;
        }

        return true;


    }

    public function uploadFile()
    {
        $ext = pathinfo($_FILES['PhoneForm']['name']['file'], PATHINFO_EXTENSION);

        while (true) {

            $unique = md5(uniqid(mt_rand(), true));
            $fileName = $unique . '.' . $ext;
            $filePath = App::getInstance()->getRootPath() . '/upload/' . $fileName;
            if (!file_exists($filePath)) {
                break;
            }
        }
        if (move_uploaded_file($_FILES['PhoneForm']['tmp_name']['file'], $filePath)) {
            return $fileName;
        }

        return false;
    }


    public function savePhone(Phone $phone)
    {
        $phone->user_id = App::$user->id;
        preg_match_all('/\d+/', $this->phone, $matches);
        $phone->phone = implode('', $matches[0]);
        $phone->email = $this->email;
        $phone->first_name = $this->first_name;
        $phone->last_name = $this->last_name;

        if (!empty($_FILES['PhoneForm']['tmp_name']['file'])) {
            if (!$phone->isNewRecord) {
                if ($phone->file) {
                    $phone->deleteFile();
                }
            }

            $fileName = $this->uploadFile();
            $phone->file = $fileName;
            $phone->makeThumbnails(100, 100);
        }
        $phone->save();

        return $phone;
    }


}