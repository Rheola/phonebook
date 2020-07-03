<?php


namespace models;

use core\PDOConnection;

class User extends Model
{

    public $id;
    public $login;
    public $email;
    public $password;
    public $created_at;


    private $guest;


    /**
     * @return bool
     */
    public function isGuest()
    {
        return (bool)$this->guest;
    }

    /**
     * @param bool $guest
     */
    public function setGuest($guest)
    {
        $this->guest = $guest;
    }


    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public function create()
    {
        $this->created_at = date('Y-m-d H:i:s');
        parent::create();
    }
}