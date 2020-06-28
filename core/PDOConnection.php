<?php

namespace core;

use Exception;
use PDO;
use PDOException;

/**
 * PDOConnection is a singleton implementation.
 * getConnection() returning an instance of PDO connection.
 *
 * <code>
 * Example usage:
 *
 * $pdo = PDOConnection::instance();
 * $conn = $pdo->getConnection( 'dsn', 'username', 'password' );
 *
 * $results = $conn->query("SELECT * FROM Table");
 *
 * </code>
 *
 * @author rmurray
 */
class PDOConnection
{

    /**
     * singleton instance
     *
     * @var PDOConnection
     */
    protected static $instance;

    /**
     * Hide constructor, protected so only subclasses and self can use
     */
    protected function __construct()
    {
    }

    /**
     * Returns singleton instance of PDOConnection
     *
     * @return PDOConnection
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new PDOConnection();
        }

        return self::$instance;
    }

    /**
     * Return a PDO connection using the dsn and credentials provided
     *
     * @return PDO connection to the database
     */
    public function getConnection()
    {
        $conn = null;

        $host = App::getInstance()->params['db']['host'];
        $db = App::getInstance()->params['db']['dbname'];

        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $db);

        $user = App::getInstance()->params['db']['user'];
        $password = App::getInstance()->params['db']['password'];

        try {
            $conn = new PDO($dsn, $user, $password);
            //Set common attributes
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            echo 'Произошла ошибка. Приносим извинения, наши специалисты уже работают над решением проблемы';
            $this->logDbError('PDOException ' . $e->getMessage());
            exit();
        } catch (Exception $e) {
            echo 'Произошла ошибка. Приносим извинения, наши специалисты уже работают над решением проблемы';
            $this->logDbError('Exception');
            exit();
        }
    }


    /**
     * @param $info
     */
    private function logDbError($info)
    {
        $data = date('Y-m-d', time());
        $file = sprintf('%s.log', $data);
        $error_log = fopen(__DIR__ . '/../runtime/db/' . $file, 'ab+');

        $now = date('Y.m.d H:i:s', time());
        $message = sprintf('%s PDO_Error: %s', $now, $info);
        fwrite($error_log, $message . PHP_EOL);
        fclose($error_log);
    }

    /**
     * @throws Exception
     */
    public function __clone()
    {
        throw new Exception("Can't clone a singleton");
    }

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Can't wakeup a singleton");
    }
}