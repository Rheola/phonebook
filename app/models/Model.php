<?php

namespace models;

use core\Cache;
use core\PDOConnection;
use PDO;

/**
 * @property integer $id
 */
class Model
{

    public $isNewRecord;

    private $fields = [];

    /**
     * TModel constructor.
     */
    public function __construct()
    {
        $this->getAttributes();
        $this->isNewRecord = true;
    }


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'ddd';
    }

    /**
     *
     */
    private function findAttributes()
    {

        $table = static::tableName();
        $sql = sprintf('SHOW COLUMNS FROM %s', $table);

        $pdo = PDOConnection::getConnection();

        $query = $pdo->query($sql);

        $data = $query->fetchAll();

        foreach ($data as $field) {
            $this->fields[] = $field['Field'];
        }
    }

    /**
     * @return array
     */
    private function getAttributes()
    {
        $called = get_called_class();
        $table = $called::tableName();
        $key = $table . '_schema';
        $info = Cache::getValue($key);
        if ($info === false) {
            $this->findAttributes();
            Cache::setValue($key, $this->fields);
        } else {
            $this->fields = $info;
        }

        return $this->fields;
    }


    /**
     *
     */
    public function create()
    {
        $data = (array)$this;

        $columns = [];
        $values = [];


        foreach ($this->fields as $field) {

            $value = $data[$field];

            $key = sprintf('`%s`', $field);
            $columns[] = preg_replace("/^(\(JSON\)\s*|#)/i", '', $key);

            switch (gettype($value)) {
                case 'NULL':
                    $values[] = 'NULL';
                    break;
                case 'boolean':
                    $values[] = ($value ? '1' : '0');
                    break;

                case 'integer':
                case 'double':
                case 'string':
                    $values[] = PDOConnection::getConnection()->quote($value);
                    break;
            }
        }
        $called = get_called_class();
        $table = $called::tableName();

        $valStr = implode(', ', $values);
        $colStr = implode(', ', $columns);

        $query = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $colStr, $valStr);

        $pdo = PDOConnection::getConnection();
        $pdo->exec($query);

        $this->id = $pdo->lastInsertId();
        if ($pdo->errorCode() != 0) {
            //todo crash
        }

        $this->isNewRecord = false;
    }


    /**
     * @param $id
     * @return mixed
     */
    public static function findOne($id)
    {
        $pdo = PDOConnection::getConnection();

        $sql = self::buildFrom();

        $query = $pdo->prepare($sql);

        $query->bindValue('id', $id, PDO::PARAM_INT);
        $query->execute();

        $object = $query->fetchObject(static::class);
        if ($object === false) {
            return null;
        }
        $object->isNewRecord = false;

        return $object;
    }

    /**
     *
     */
    public function save()
    {
        if ($this->isNewRecord) {
            if ($this->id === null) {
                $this->create();

                return;
            }
        }
        $this->update();
    }


    /**
     *
     */
    public function update()
    {
        $data = (array)$this;

        $values = [];

        $this->getAttributes();

        foreach ($this->fields as $field) {
            if ($field === 'id') {
                continue;
            }
            if (!isset($data[$field])) {
                continue;
            }
            $value = $data[$field];

            if (is_array($value)) {
                continue;
            }

            $value = PDOConnection::getConnection()->quote($value);
            $values[] = sprintf('`%s` = %s', $field, $value);
        }

        $called = static::class;

        $valStr = implode(', ', $values);

        $query = sprintf('UPDATE `%s`  SET  %s WHERE id=%d', $called::tableName(), $valStr, $this->id);

        $pdo = PDOConnection::getConnection();
        $pdo->exec($query);

        if ($pdo->errorCode() != 0) {
            // todo
        }
    }

    /**
     * @return string
     */
    private static function buildFrom()
    {
        $table = static::tableName();

        return sprintf('SELECT * FROM `%s` WHERE id = :id', $table);
    }

    /**
     * @param bool $full
     * @return  string
     */
    public static function now($full = true)
    {
        if ($full === false) {
            return date('Y-m-d');
        }

        return date('Y-m-d H:i:s');
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $called = static::class;
        $table = $called::tableName();

        $pdo = PDOConnection::getConnection();

        $sql = sprintf('DELETE FROM `%s` WHERE id = :id', $table);

        $query = $pdo->prepare($sql);
        $query->bindValue('id', $this->id, PDO::PARAM_INT);

        return $query->execute();
    }

    public function findAll($condition = null)
    {
        $called = get_called_class();
        $table = $called::tableName();

        if ($condition == null) {
            $sql = sprintf('SELECT * FROM `%s`', $table);
        } elseif (!is_array($condition)) {
            $condition = ['id' => $condition];
            $sql = sprintf('SELECT * FROM `%s` WHERE id = :id', $table);
        } else {
            $andWhere = '';
            foreach ($condition as $key => $value) {
                if ($andWhere == '') {
                    $andWhere .= sprintf('%s = :%s', $key, $key);
                } else {
                    $andWhere .= sprintf(' AND %s = :%s', $key, $key);
                }
            }
            $sql = sprintf('SELECT * FROM `%s` WHERE %s', $table, $andWhere);
        }

        print_r($sql);

        $pdo = PDOConnection::getConnection();
        $query = $pdo->prepare($sql);

        foreach ($condition as $key => $value) {
            $query->bindValue($key, $value, PDO::PARAM_INT);
        }

        $query->execute();
//
        $object = $query->fetchAll();
//        fetchObject(static::class);
//        if ($object === false) {
//            return null;
//        }
//        $object->isNewRecord = false;
        return $object;
    }


    public static function findOneByParam($params)
    {
        $pdo = PDOConnection::getConnection();

        $sql = sprintf("SELECT * FROM %s  WHERE ", static::tableName());


        $where = [];
        foreach ($params as $param => $val) {
            $where[] = "$param =:$param";
        }
        $sql .= implode(' and ', $where);


        $query = $pdo->prepare($sql);
        $query->setFetchMode(\PDO::FETCH_OBJ);
        foreach ($params as $param => $val) {
            $query->bindValue($param, $val);
        }

        $query->execute();

        $load = $query->fetchObject(get_called_class());

        if ($load === false) {
            return null;
        }

        $model = new static();
        $model->isNewRecord = false;

        foreach ($model as $name => $val) {
            if (isset($load->$name)) {
                $model->$name = $load->$name;
            }
        }

        return $model;
    }
}