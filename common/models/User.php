<?php
namespace common\models;

use app\models\User_mod;
use Yii;
use yii\base\Exception;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;


/**
 * Class User
 * @package common\models
 *
 * @property integer $start_time
 */
class User extends User_mod implements IdentityInterface
{

    public $user_group;

    const SKLAD_DEFAULT = 0;

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 10;


    const GROUP_GUEST = 0;            // 'Гость',
    const GROUP_MONTAGE = 1;            // 'Монтажник',

    const GROUP_REMONT = 10;           // 'Ремонтник',
    const GROUP_NIGHT = 20;           // 'Ночной ИНЖЕНЕР',
    const GROUP_AGENT = 30;           // 'Агент ТХА',

    const GROUP_SKLAD = 40;           // 'Пользователь ЗавСКЛАД',

    const GROUP_DISPATCHER = 45;      // 'Пользователь Диспетчер',

    const GROUP_GL_ENG = 50;           // 'Пользователь Гл.ИНЖЕНЕР',


    const GROUP_BUH_2 = 60;            // 'Бухгалтерия 2 этаж',
    const GROUP_BUH_2_NAZIRA = 61;    // 'Бухгалтерия 2 этаж Назира',
    const GROUP_BUH_1 = 65;            // 'Бухгалтерия 1 этаж',

    const GROUP_MODER = 70;           // Модератор

    const GROUP_STAT_86 = 99;            // 'Просмотр остатков на главном складе (86)',

    const GROUP_ADMIN = 100;          // Админ

    const CHAT_SENDER = 1;       // Имеет дотуп к чату

    const ROLE_GUEST = 'guest';       // 'Пользователь Гость',
    const ROLE_REMONT = 'remont';      // 'Пользователь РЕМОНТНИК',
    const ROLE_MONTAGE = 'montage';     // 'Пользователь МОНТАЖНИК',
    const ROLE_ENGINER = 'engineer';    // 'Пользователь ИНЖЕНЕР',
    const ROLE_ZAVSKLAD = 'zavsklad';    // 'Пользователь ЗавСКЛАД',
    const ROLE_GL_ENGINEER = 'gl_engineer'; // 'Пользователь Гл.ИНЖЕНЕР',
    const ROLE_MOD = 'moder';      // Модератор
    const ROLE_ADMIN = 'admin';      // Админ


    const SCENARIO_CREATE_STARTTIME = 'starttime';
    const SCENARIO_CREATE_NEWUSER = 'signup';

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios[self::SCENARIO_CREATE_STARTTIME] = [
            '_id',
            'id',
            'start_time',
        ];



        $scenarios[self::SCENARIO_CREATE_NEWUSER] = [
            '_id',
            'id',
            'username',
            'username_for_signature',
            'username_full',


            'chat',
            'email',
            'password_hash',
            'auth_key',
            'status',

            'role',
            'group_id',
            'sklad',

            'fio',
            'created_at',
            'updated_at',

            'start_time', //session_log_time
        ];
        $scenarios[self::SCENARIO_DEFAULT] = [
            '_id',
            'id',
            'username',
            'username_for_signature',
            'username_full',


            'chat',
            'email',
            'password_hash',
            'auth_key',
            'status',

            'role',
            'group_id',
            'sklad',

            'fio',
            'created_at',
            'updated_at',

            'start_time', //session_log_time
        ];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                '_id',//
                'id',//
                'start_time',//
            ], 'safe', 'on' => self::SCENARIO_CREATE_STARTTIME],


            [[
                '_id',
                'id',
                'username',
                'username_for_signature',
                'username_full',

                'chat',
                'email',
                'role',
                'group_id',
                'sklad',
                'fio',
            ], 'safe', 'on' => self::SCENARIO_CREATE_NEWUSER],

            [['sklad', 'start_time'], 'integer'],

            [['status'], 'default', 'value' => self::STATUS_ACTIVE],

            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            [['chat'], 'default', 'value' => self::CHAT_SENDER],

            [['role'], 'default', 'value' => self::ROLE_GUEST],

            [['group_id'], 'default', 'value' => self::GROUP_GUEST],

            [[
                'username',
                'username_for_signature'
            ], 'required', 'message' => 'Заполнить...'],


        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        //            if (!$id) return null;
        //            if(!Yii::$app->getUser()) return null;

        if ($id === (array)$id && isset($id['$oid'])) {
            $id = $id['$oid'];          //Working for me
        }

        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     *
     */
    public static function findGroup()
    {
        return Yii::$app->getUser()->identity->group_id;
    }

    /**
     * @return mixed
     */
    public static function getGroup()
    {
        return Yii::$app->getUser()->identity->group_id;
    }

    /**
     * @return mixed
     */
    public static function getUsername()
    {
        return Yii::$app->getUser()->identity->username;
    }

    /**
     *
     */
    public static function getChat()
    {
        return Yii::$app->getUser()->identity->chat;
    }

    /**
     * @return mixed
     */
    public static function getUsernameForSignature()
    {
        return Yii::$app->getUser()->identity->username_for_signature;
    }

    /**
     * @param $username
     * @return mixed
     */
    public static function findGroupByUsername($username)
    {
        $item = static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        return $item->group_id;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }


    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }



    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setChat()
    {
        $this->chat = 1;
    }

    /**
     * @param $username_for_signature
     */
    public function setUserusername_for_signature($username_for_signature)
    {
        $this->username_for_signature = $username_for_signature;
    }

    public function getUserusername_for_signature()
    {
        return $this->username_for_signature;
    }

    /**
     * @param $user_id
     */
    public function setUserId($user_id)
    {
        $this->id = $user_id;
    }

    /**
     * @param $user_role
     */
    public function setUserRole($user_role)
    {
        $this->role = $user_role;
    }

    /**
     * @param $user_group
     */
    public function setUserGroup($user_group)
    {
        $this->group_id = (int)$user_group;
    }
    /**
     * {@inheritdoc}
     */
    public function getUserGroup()
    { //group_id
        //user_group
        return $this->group_id;
    }
    /**
     * @param $user_sklad
     */
    public function setUserSklad($user_sklad)
    {
        $this->sklad = (int)$user_sklad;
    }


    /**
     * Массив складов, привязанных к пользователю
     * Возвращаем только МАССИВ
     * Yii::$app->getUser()->identity->getUserSklad();
     * -
     *
     * @return array|mixed|null
     */
    public function getUserSklad()
    {
        if (!isset($this->sklad) || empty($this->sklad)) {
            return null;
        }
        if (is_array($this->sklad))
            return $this->sklad;
        else
            return [$this->sklad];
    }


    /**
     * @param string $email
     */
    public function setEmail($email = 'mail@mail.kz')
    {
        $this->email = $email;
    }


    /**
     * @param $group_id
     */
    public function setGroup($group_id)
    {
        $this->group_id = $group_id;
    }


    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getGroup_id()
    {
        return $this->group_id;
    }

    /**
     * @return bool
     */
    public function getIsGuest()
    {
        return $this->getId() === null;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        //return (string) $this->getPrimaryKey();
        return $this->getPrimaryKey();
    }


    public static function getAll()
    {
        $result = [];

        foreach (self::$users as $user) {
            $result[] = [
                'id' => $user['id'],
                'username' => $user['username'],
            ];
        }

        return $result;
    }


    /**
     *-
     *
     * @return array|ActiveRecord
     */
    static function findAll_username()
    {
        //        username', 'username_for_signature
        return ArrayHelper::map(
            static::find()
                ->orderBy(['username_for_signature ASC'])
                ->all(),
            'id', 'username_for_signature'
        );
    }

    /**
     *-
     *
     * @param $array_ids
     * @return array|ActiveRecord
     */
    static function findAll_username_by_ids($array_ids)
    {
        //        username', 'username_for_signature
        return ArrayHelper::map(
            static::find()
                ->where(['IN', 'id', $array_ids])
                ->orderBy(['username_for_signature ASC'])
                ->all(),
            'id', 'username_for_signature'
        );
    }

}
