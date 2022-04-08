<?php

namespace frontend\models;


//use frontend\components\Vars;
use Yii;


/**
 * Class Pvmobile
 * @package app\models
 */
class Pvmobile extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'pvmobile'];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getPv()
    {
        return $this->hasOne(Pv::className(), ['id' => 'pv_id']);
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getTypemotion()
    {
        return $this->hasOne(Typemotion::className(), ['id' => 'type_action']);
    }



    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'pv_id',
            'name',
            'user_id',
            'document',
            'wh_top',
            'wh_top_name',
            'wh_element',

            'content',
            'comments',

            'dt_create',
            'dt_create_start',
            'dt_create_stop',

            'dt_time_start',
            'dt_time_stop',


            'type_action',
            'type_action_tx',

            'diagnoz',
            'fact_bag',

        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                'pv_id',
                'name',
                'user_id', 'content', 'comments',
                'document',

                    'type_action_tx',
                    'type_action',

                    'wh_top',
                    'wh_top_name',
                    'wh_element',

                'diagnoz',
                'fact_bag',
                ],
                'safe'
            ],
            [
                [
                    'dt_create',
                    'document',
                    'type_action',
                    'wh_top',
                    'wh_element'
                ],
                'required'
            ],

            [['id','pv_id'], 'integer'],

            //['dt_create', 'datetime', 'format' => 'yyyy-m-d H:i:s'],
            //['dt_create', 'datetime', 'format' => 'dd.M.yy HH:i'],
            //['dt_create', 'datetime', 'format' => 'd.m.Y H:i:s'],

            //OK2 ['dt_create', 'datetime', 'format' => 'd.m.Y H:i:s'],

            //['dt_create', 'datetime', 'format' => 'dd.mm.yyyy H:i:s'],

            ['dt_create', 'datetime', 'format' => 'd.m.Y H:i:s'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№',
            'pv_id' => 'Инв №',
            'name' => 'Автор',
            'user_id' => 'user_id',
            'content' => 'Контент',
            'document' => 'Документ',

            'type_action' => 'Типовая операция',
            'type_action_tx' => 'Тип',

            'wh_top' => '№ Склада #',
            'wh_top_name' => 'Название склада #',
            'wh_element' => 'Склад #.# ',

            'comments' => 'Коменты',

            'dt_create' => 'Дата',
            'dt_create_start'=>'Старт',
            'dt_create_stop'=>'Стоп',
            'dt_time_start'=>'Время',
            'dt_time_stop'=>'Время',
        ];
    }
}
