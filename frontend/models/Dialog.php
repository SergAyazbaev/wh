<?php

namespace frontend\models;

//namespace app\models;
//namespace common\models;
//namespace yii\console\models;

use Exception;
use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "dialog".
 *
 * @property integer $id
 * @property integer $creator_id
 * @property integer $opponent_id
 * @property integer $date_create
 * @property integer $dispute_id
 *
 * @property User $creator
 * @property Dialogmessage[] $dialogMessages
 * @property DialogUser[] $dialogUsers
 */
class Dialog extends ActiveRecord
{

    public $opponent_id;

    /**
     * @inheritdoc
     */
//    public static function tableName()
//    {
//        return 'dialog';
//    }

    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'dialog',
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
                'creator_id',
                'name',
                'ban',
            ], 'safe'],

            [['creator_id'], 'required'],


            //            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'creator_id',
            'name',
            'ban',
            'date_create',
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'creator_id' => Yii::t('app', 'Creator'),
            'date_create' => Yii::t('app', 'Create date'),
            'name' => Yii::t('app', 'name'),
            'ban' => Yii::t('app', 'ban'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getCreator()
//    {
//        return $this->hasOne(User::className(), ['id' => 'creator_id']);
//    }


    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getLastMessage()
//    {
//        return $this->hasOne(Dialogmessage::className(), ['id' => 'last_message_id']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getDialogMessages()
//    {
//        return $this->hasMany(Dialogmessage::className(), ['dialog_id' => 'id']);
//    }

//    public function getDialogUsers()
//    {
//        return $this->hasMany(DialogUser::className(), ['dialog_id' => 'id']);
//    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('dialog_user', ['dialog_id' => 'id']);
    }


    /**
     * @inheritdoc
     */
    public function getReciever()
    {
        if (empty(Yii::$app->user) or empty(Yii::$app->user->identity)) {
            return null;
        }
        $subQuery = DialogUser::find()->select('user_id')
            ->where(['<>', 'user_id', Yii::$app->user->identity->id])
            ->andWhere(['dialog_id' => $this->id]);
        return User::find()->where(['in', 'id', $subQuery])->one();
    }


//    public function getDispute()
//    {
//        return $this->hasOne(Dispute::className(), ['id' => 'dispute_id']);
//    }


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
     * Прописать в сессию. Ид оппонента по переписке
     * =
     *
     * @param $opponent_id
     * @return bool
     */
    static function setOpponentIdActive($opponent_id)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('opponent_id_', $opponent_id);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * Получить из сессии. Ид оппонента по переписке
     * =
     *
     * @return mixed
     */
    static function getOpponentIdActive()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        return (int)$session->get('opponent_id_');
    }


    /**
     * Прописать в сессию. Ид диалога
     * =
     *
     * @param $opponent_id
     * @return bool
     */
    static function setDialogIdActive($opponent_id)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('dialog_id_', $opponent_id);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * Получить из сессии. Ид диалога
     * =
     *
     * @return mixed
     */
    static function getDialogIdActive()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        return (int)$session->get('dialog_id_');
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->date_create = time();
            }
            return true;
        } else {
            return false;
        }
    }
}
