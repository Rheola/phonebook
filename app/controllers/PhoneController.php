<?php

namespace controllers;

use core\App;
use core\Controller;
use core\PDOConnection;
use models\forms\PhoneForm;
use models\Phone;

class PhoneController extends Controller
{

    public function actionIndex()
    {
        if (App::$user->isGuest()) {
            App::goEnter();
        }
        $user = App::$user;


        $pdo = PDOConnection::getConnection() ;

        $sql = 'SELECT * FROM phone WHERE 
            user_id=:user_id   
            ORDER BY id DESC';

        $query = $pdo->prepare($sql);
        $query->bindValue('user_id', $user->id, \PDO::PARAM_INT);

        $query->execute();

        $phones = $query->fetchAll(\PDO::FETCH_CLASS, Phone::class);

        $this->render('index',
            [
                'user' => $user,
                'phones' => $phones,
            ]
        );
    }

    /**
     *
     */
    public function actionCreate()
    {
        if (App::$user->isGuest()) {
            App::goEnter();
        }
        if (isset($_POST['PhoneForm'])) {
            $phoneForm = new PhoneForm();
            $phoneForm->load($_POST['PhoneForm']);

            if ($phoneForm->validate()) {
                $phone = new Phone();
                $phoneForm->savePhone($phone);

                echo json_encode(
                    [
                        'success' => true,
                        'data' => $phone->toArray(),
                    ]
                );

                return;

            }

            echo json_encode(
                [
                    'success' => false,
                    'errors' => $phoneForm->errors,
                ]
            );

            return;
        }

    }


    /**
     * @param $id
     */
    public function actionDelete($id)
    {

        try {
            $phone = $this->getModel($id);

        } catch (\Exception $exception) {
            echo json_encode(
                [
                    'success' => false,
                    'errors' => $exception->getMessage(),
                ]
            );

            return;
        }

        if ($phone->delete()) {
            echo json_encode(
                [
                    'success' => true,
                ]
            );

            return;
        }
        echo json_encode(
            [
                'success' => false,
                'errors' => 'Internal error',
            ]
        );
    }


    /**
     * @param $id
     */
    public function actionView($id)
    {
        try {
            $phone = $this->getModel($id);

        } catch (\Exception $exception) {
            echo json_encode(
                [
                    'success' => false,
                    'errors' => $exception->getMessage(),
                ]
            );

            return;
        }

        $data = $phone->toArray();
        $data['textPhone'] = $phone->phoneToText();
        echo json_encode(
            [
                'success' => true,
                'data' => $data,
            ]
        );
    }


    public function actionUpdate($id)
    {
        try {
            $phone = $this->getModel($id);

        } catch (\Exception $exception) {
            echo json_encode(
                [
                    'success' => false,
                    'errors' => $exception->getMessage(),
                ]
            );

            return;
        }

        if (isset($_POST['PhoneForm'])) {
            $phoneForm = new PhoneForm();
            $phoneForm->load($_POST['PhoneForm']);

            if ($phoneForm->validate()) {
                $phoneForm->savePhone($phone);

                echo json_encode(
                    [
                        'success' => true,
                        'data' => $phone->toArray(),
                    ]
                );

                return;
            }

            echo json_encode(
                [
                    'success' => false,
                    'errors' => $phoneForm->errors,
                ]
            );

            return;
        }
    }


    /**
     * @param $id
     * @return Phone
     * @throws \Exception
     */
    private function getModel($id)
    {
        if (App::$user->isGuest()) {
            throw new \Exception('Access denied');
        }

        /** @var Phone $phone */
        $phone = Phone::findOne($id);
        if ($phone === null) {
            throw new \Exception('Not found');
        }

        if ($phone->user_id != App::$user->id) {
            throw new \Exception('Access denied');
        }

        return $phone;
    }

    public function actionDeletefile($id)
    {
        try {
            $phone = $this->getModel($id);

        } catch (\Exception $exception) {
            echo json_encode(
                [
                    'success' => false,
                    'errors' => $exception->getMessage(),
                ]
            );

            return;
        }

        if ($phone->file) {
            $phone->deleteFile();
        }
        echo json_encode(
            [
                'success' => true,
            ]
        );

    }


    public function actionSort()
    {

        $attr = $_GET['attr'];
        $order = $_GET['order'];
        $user = App::$user;

        $fields = [
            'email',
            'first_name',
            'last_name',
            'phone',
        ];


        $order = strtoupper($order);
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = 'ASC';
        }
        $attr = strtolower($attr);
        if (!in_array($attr, $fields)) {

            $attr = 'id';
        }

        $orderStr = sprintf(" ORDER by %s %s", $attr, $order);
        $pdo = PDOConnection::getConnection();

        $sql = 'SELECT * FROM phone WHERE 
            user_id=:user_id   
             ' . $orderStr;

        $query = $pdo->prepare($sql);


        $query->bindValue('user_id', $user->id, \PDO::PARAM_INT);

        $query->execute();

        $phones = $query->fetchAll(\PDO::FETCH_CLASS, Phone::class);
        $this->layout = 'empty';
        $orderSettings = [];
        foreach ($fields as $key) {
            $orderSettings[$key] = 'ASC';
            if ($key == $attr) {
                if ($order == 'ASC') {
                    $orderSettings[$key] = 'DESC';
                }
            }
        }
        $this->render('sort', [
            'phones' => $phones,
            'order' => $orderSettings,
            'attr' => $attr,
        ]);
    }
}