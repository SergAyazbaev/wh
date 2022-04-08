<?php

namespace frontend\models;

use common\models\User;
//use frontend\components\MyHelpers;
use yii\base\Exception;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $role;
    public $group_id;
    public $username;
    public $username_for_signature;
    public $email;
    public $password;

    public $wh_destination;
    public $sklad;
    //public $wh_destination_element;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['sklad', 'username_for_signature'], 'safe'],

            //['sklad', 'integer', 'min' => 1, 'max' => 255],

            [['username', 'username_for_signature', 'password', 'group_id'], 'required', 'message' => 'Заполнить...'],


            [['username_for_signature', 'username', 'email'], 'trim'],

            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Этот логин уже есть в системе.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['username_for_signature', 'string', 'min' => 2, 'max' => 255],

            ['password', 'string', 'min' => 6],

            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => ' Эта почта уже нам встречалась.'],

        ];
    }


    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     * @throws Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $max_value = User::find()->max('id');
        $max_value++;


        $user = new User();
        $user->scenario= User::SCENARIO_CREATE_NEWUSER;



        $user->setUserGroup($this->group_id);
        //$user->setUserRole($this->role);
        $user->setUserSklad($this->sklad);
        $user->setChat();


//        $user->setUserRole($this->role);

        $user->setUserId($max_value);
        $user->setUsername($this->username);
        $user->setUserusername_for_signature($this->username_for_signature);
        $user->setEmail($this->email);
        $user->setPassword($this->password);
        $user->generateAuthKey();

//        ddd($user);
//        ddd($this);

        if (!$user->save()) {
            ddd($user->errors);
        }

        //return $user->save() ? $user : null;
        return $user;
    }


    /**
     * Прописываем начала входа под ЛОГИНОМ
     * =
     * @param $user_oid
     * @return bool
     */
    static function TimeStartLogin($user_oid)
    {
        $user = User::find()
            ->where(['_id' => $user_oid])
            ->one();
        $user->setScenario(User::SCENARIO_CREATE_STARTTIME);
        $user->start_time = strtotime("now");

        if (!$user->save()) {
            return false;
        }

        return true;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     *     //Start
     * @param $start_time
     * @return mixed
     */
//    public function setStartTime($start_time)
//    {
//        $this->start_time = $start_time;
//    }

//    public static function setStartTime($_user_id)
//    {
//        $item = static::find(['_id' => $_user_id])->one();
//        ddd($item);
//
//        return $item->group_id;
//    }

    /**
     *     //Start
     */
    public function getStartTime()
    {
        return $this->start_time;
    }


    public function attributeLabels()
    {
        return [

            '_id' => 'ID',
            'id' => '№#',
            'username' => 'Логин',
            'password' => 'Пароль',

            'role' => 'Функция пользователя',
            'group_id' => 'Уровень доступа',
            'sklad' => 'Склад: пользователь (именной)',

            'wh_destination' => 'Компания',
            'wh_destination_element' => 'Склад',
            'username_for_signature' => 'Ф.И.О.',


        ];

    }


}
