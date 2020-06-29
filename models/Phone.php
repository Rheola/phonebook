<?php


namespace models;


class Phone extends Model

{

    public $id;
    public $user_id;
    public $email;
    public $first_name;
    public $last_name;
    public $phone;
    public $file;


    /**
     * @inheritDoc
     */
    public function tableName()
    {
        return 'phone';
    }

    public function toArray()
    {
        $array = (array)$this;
        unset($array['user_id']);
        unset($array['isNewRecord']);
        foreach ($array as $key => $val) {
            if ($val === null) {
                $array[$key] = '';
            }
            if (is_array($val)) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}