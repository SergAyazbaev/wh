<?php

namespace app\models;
//namespace common\models;

use Yii;
use yii\mongodb\ActiveRecord;


/**
 * Class User_mod
 * @package app\models
 */
class User_mod extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'user'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
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
    }

    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT=> ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE=> ['updated_at'],            ],
            ]
        ];
    }

}
