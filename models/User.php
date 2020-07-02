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
    public function tableName()
    {
        return 'user';
    }

    public function create()
    {
        $this->created_at = date('Y-m-d H:i:s');
        parent::create();
    }

    /**
     * @param $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        $email = mb_strtolower($email);
        $pdo = PDOConnection::getConnection();

        $sql = 'SELECT * FROM user WHERE email = :email';

        $query = $pdo->prepare($sql);
        $query->setFetchMode(\PDO::FETCH_OBJ);
        $query->bindValue('email', $email);
        $query->execute();

        $load = $query->fetchObject(__CLASS__);

        if ($load === false) {
            return null;
        }

        $user = new User();

        foreach ($user as $name => $val) {
            if (isset($load->$name)) {
                $user->$name = $load->$name;
            }
        }

        return $user;
    }
}