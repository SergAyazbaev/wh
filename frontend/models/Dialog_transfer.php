<?php

namespace frontend\models;

//use common\models\User;
use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "dialog_message".
 *
 * @property integer $id
 * @property integer $dialog_id
 * @property integer $message_id
 *
 * @property integer $sender_id
 * @property integer $reciever_id
 * @property integer dt_create
 * @property integer dt_update
 *
 * @property string $status
 */
class Dialog_transfer extends ActiveRecord
{
    const STATUS_SYSTEM_MESSAGE = 'sysem';
    const STATUS_SYSTEM_ONLINE = 'sysem_inline';
    const STATUS_SYSTEM_OFFLINE = 'sysem_offline';
    const STATUS_SEND = 'send';
    const STATUS_UNREAD = 'unread';
    const STATUS_READ = 'read';

    const SCENARIO_SYSTEM_MESAGE = 'sys';
    const SCENARIO_CREATE = 'new';
    const SCENARIO_READ = 'read';
    const SCENARIO_VIEW = 'view';


    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'dialog_transfer',
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
//        $scenarios['default'] = [
//            '_id',
//            'id',
//            'dialog_id',
//            'sender_id',
//            'reciever_id',
//            'status',  // text
//            'dt_create',
//            'dt_update'
//        ];
        $scenarios[self::SCENARIO_CREATE] = [
            '_id',
            'id',
            'dialog_id',
            'message_id',
            'sender_id',
            'reciever_id',
            'status',  // text
            'dt_create',
            'dt_update'
        ];
        $scenarios[self::SCENARIO_SYSTEM_MESAGE] = [
            '_id',
            'id',
            'dialog_id',
            'message_id',
            'sender_id',
            'status',  // text
            'dt_create',
        ];
        $scenarios[self::SCENARIO_READ] = [
            'id',
            'dialog_id',
            'message_id',
            'reciever_id',
            //            'status',
            'dt_update'
        ];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'dialog_id',
            'message_id',
            'sender_id',
            'reciever_id',
            'status',  // text
            'dt_create',
            'dt_update',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [[
                'message_id',
                'sender_id',
            ], 'safe', 'on' => self::SCENARIO_CREATE],

            [[
                'message_id',
                'sender_id',
            ], 'safe', 'on' => self::SCENARIO_SYSTEM_MESAGE],

            [[
                'id',
                'reciever_id',
            ], 'safe', 'on' => self::SCENARIO_READ],

            [[
                'status'
            ], 'string'],

            [[
                'id',
                'dialog_id',
                'sender_id',
                'reciever_id',
                'dt_create',
                'dt_update',
            ], 'integer'],

//            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
//            [['reciever_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reciever_id' => 'id']],


            [
                'id',
                'default',
                'value' => function () {
                    return self::setNextMaxId();
                },
            ],
            [
                'dialog_id',
                'default',  'on' => self::SCENARIO_CREATE,
                'value' => function () {
                    return (int) 1;
                },
            ],
//            [
//                'sender_id',
//                'default',  'on' => self::SCENARIO_CREATE,
//                'value' => function () {
//                    return Yii::$app->user->identity->id;
//                },
//            ],
            [
                'dt_create',
                'default',  'on' => [self::SCENARIO_CREATE,self::SCENARIO_SYSTEM_MESAGE],
                'value' => function () {
                    return strtotime('now');
                },
            ],
            [
                'dt_update',
                'default',  'on' => self::SCENARIO_READ,
                'value' => function () {
                    return strtotime('now');
                },
            ],


            [
                'status',
                'default',  'on' => self::SCENARIO_CREATE,
                'value' => function () {
                    return self::STATUS_SEND;
                },
            ],
            [
                'status',
                'default',  'on' => self::SCENARIO_READ,
                'value' => function () {
                    return self::STATUS_UNREAD;
                },
            ],

//            [['message_id'], 'required', 'message' => 'Заполнить...'],


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('front', '№'),
            'dialog_id' => 'Диалог',
            'sender_id' => 'Сообщение от кого',
            'reciever_id' => 'Сообщение кому',
            'status' => Yii::t('front', 'Status'),
            'dt_create' => Yii::t('front', 'Create date'),
        ];
    }


    /**
     * setNext_max_id()
     * =
     * Вычисляем следующий новый ИД
     *
     * @return int
     */
    public static function setNextMaxId()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;
    }


}
