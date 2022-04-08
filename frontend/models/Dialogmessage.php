<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "dialog_message".
 *
 * @property integer $id
 * @property integer $dialog_id
 * @property integer $sender_id
 * @property integer $user_id
 * @property integer $user_name
 * @property string $text
 * @property string $status
 * @property User $sender
 * @property Dialog $dialog
 * @property DialogMessageView[] $dialogMessageViews
 */
class Dialogmessage extends ActiveRecord
{
    const STATUS_UNREAD = 'unread';
    const STATUS_READ = 'read';
    const STATUS_DELETED = 'deleted';


    const SCENARIO_CREATE = 'create';
    const SCENARIO_VIEW = 'view';
    const SCENARIO_STATUS_CHANGE = 'change';

    public $dialog_id_menu;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'dialog_message',
        ];
    }

    public function scenarios()
    {

        $scenarios['default'] = [
            '_id',
            'id',
            'dialog_id',
            'text',

            'sender_id',
            'user_id',
            'user_name',

            'date_create',
            'status',
        ];

        $scenarios[self::SCENARIO_CREATE] = [
            '_id',
            'id',
            'dialog_id',
            'text',

//            'sender_id',
//            'user_id',
//            'user_name',

            'date_create',
            'status',
        ];
        $scenarios[self::SCENARIO_STATUS_CHANGE] = [
            'id',
            'status',
        ];
        return $scenarios;
    }


    public function attributes()
    {
        return [
            '_id',
            'id',
            'dialog_id',
            'text',

            'sender_id',
            'user_id',
            'user_name',

            'date_create',
            'status',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [[
                '_id',
                'dialog_id',
//                'sender_id',
//                'user_id',
//                'user_name',
                'text'
            ], 'safe', 'on' => self::SCENARIO_CREATE],

            [[
                'id',
                'status',
            ], 'safe', 'on' => self::SCENARIO_STATUS_CHANGE],

            [[
                'text',
                'status'
            ], 'string'],

            [[
                'dialog_id',
                'sender_id',
                'user_id',
            ], 'integer'],


            [['dialog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dialog::className(), 'targetAttribute' => ['dialog_id' => 'id']],


            //            [['sender_id', 'text'], 'required', 'on' => self::SCENARIO_CREATE_WITHOUT_DIALOG],
            //            [['sender_id', 'text'], 'required', 'on' => self::SCENARIO_CREATE],

            //            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
            //            [['dialog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dialog::className(), 'targetAttribute' => ['dialog_id' => 'id']],

            [[
                'dialog_id',
            ], 'required', 'message' => 'Заполнить...', 'on' => self::SCENARIO_CREATE],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('front', '№'),
            'dialog_id' => Yii::t('front', 'Dialog'),
            'sender_id' => 'Чат Ид',
            'text' => Yii::t('front', 'Text'),
            'status' => Yii::t('front', 'Status'),
            'date_create' => Yii::t('front', 'Create date'),
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

    /**
     * Полный листинг
     *
     * @param $dialog_id
     * @return array|ActiveRecord
     */
    public static function ArrayAll_ByDialogNumber($dialog_id)
    {
        return static::find()
            ->where(['==', 'dialog_id', $dialog_id])
            ->asArray()
            ->all();
    }

    /**
     * Полный листинг
     *
     * @param $message_id
     * @return array|ActiveRecord
     */
    public static function find_one_ByMessageId($message_id)
    {
        return static::find()
            ->where(['==', 'id', $message_id])
            ->one();
    }

    /**
     * МОДЕЛЬ. Полный листинг по диалогу и по номеру пользователя, прочитавшего все сообщения, адесованные ему
     *
     * @param $dialog_id
     * @param $user_id
     * @return array|ActiveRecord
     */
    public static function find_ByDialogId_ByUserId($dialog_id, $user_id)
    {
        return static::find()
            ->where(
                ['AND',
                    ['==', 'dialog_id', $dialog_id],
                    ['==', 'user_id', $user_id]
                ]
            )
            ->all();
    }

    /**
     * Array. Полный листинг по номеру пользователя, прочитавшего все сообщения, адесованные ему
     *
     * @param $user_id
     * @return array|ActiveRecord
     */
    public static function array_ids_ByUserId($user_id)
    {
        return ArrayHelper::getColumn(static::find()
            ->where(
                ['==', 'user_id', $user_id]
            )
            ->all(), 'id');
    }

    /**
     * Array. Полный листинг неПРОЧИТАНЫХ сообщений по номеру пользователя и статусу сообщений
     *
     * @param $user_id
     * @param $status_str
     * @return array|ActiveRecord
     */
    public static function array_ids_ByUserId_ByStatus($user_id, $status_str)
    {
        return ArrayHelper::getColumn(static::find()
            ->where(
                ['AND',
                    ['==', 'user_id', (int)$user_id],
                    ['==', 'status', $status_str]
                ]
            )
            ->all(), 'id');
    }

    /**
     * Array. Листинг последних 20 строк
     * =
     * @param $dialog_id
     * @return array|ActiveRecord
     */
    public static function Array20_ByDialogNumber($dialog_id)
    {
        $array = static::find()
            ->where(['==', 'dialog_id', (int)$dialog_id])
            ->orderBy(['id' => SORT_DESC])
            ->limit(20)
            ->asArray()
            ->all();

        return array_reverse($array);
    }

    /**
     * Array. Листинг ИДС сендеров по признаку Статуса
     * =
     * @param $status_str
     * @return array|ActiveRecord
     */
    public static function ArrayAllUniqUsers_ForStatus_InMessages($status_str)
    {
        return static::find()
            ->where(
                ['AND',
                    ['==', 'status', $status_str],
                    ['chat' => 1]
                ]
            )
            ->distinct('user_id');
    }

    /**
     * Array. CountMessages_forUserId
     * =
     * @param $user_id
     * @return int
     * @throws \yii\mongodb\Exception
     */
    public static function CountMessages_forUserId($user_id)
    {
        return static::find()
            ->select(['dialog_id', 'id', 'user_id', 'status'])
            ->where(['in', 'user_id', $user_id])
            ->orderBy([
                'user_id' => SORT_DESC,
                'id' => SORT_DESC
            ])
            ->count('id');
    }


}
