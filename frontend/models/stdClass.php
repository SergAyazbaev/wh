<?php

namespace frontend\models;

use Yii;
//use yii\base\ExitException;
//use yii\db\ActiveQueryInterface;
//use yii\helpers\ArrayHelper;
//use yii\mongodb\ActiveRecord;

/**
 */
class stdClass extends Crm
{
    public $count_xx; // Иногда использую счетчик в ЗАпросах SQL


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'crm',
        ];

    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'dt_create',
            'dt_update',
            'dt_create_timestamp',
            'dt_update_timestamp',

            'user_id',
            'user_ip',
            "user_name",
            "user_group_id",

            "update_user_id",
            "update_user_name",

            'update_points',// массив всех людей, которые сохраняли эту накладную когда-либо

            'caller_id_hide',//
            'opername',//
            'inputPhone',//
            'needCallBack',//
            'inputName',//
            'inputEmail',//
            'inputLogin',//
            'commentArea',//!!!!!!!!! comment-area
            'optionsRadios',//
            'owner',//
        ];

    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            '_id' => '_ID',
            'id' => '№',

        ];

    }

    /**
     * @return array
     */
    public function rules()
    {

        return [
            ///SAFE
            [[

                'caller_id_hide',//

            ], 'safe'],


            [['id',], 'unique'],

            [['id'], 'integer',
                'min' => 1,
                'message' => 'Id_Crm == 0   ... ERROR!',
            ],
        ];

    }


    /**
     * setNext_max_id()
     * =
     *
     * @return int
     */
    public static function setNextMaxId()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;

    }


}