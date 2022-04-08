<?php

namespace frontend\models;



use Yii;
//use frontend\components\Vars;

/**
 * Class Pvrestore
 * @package app\models
 */
class Pvrestore extends \yii\mongodb\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'pvrestore'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id','pv_id',
            'user_id', 'user_name',

            'dt_create',
            //'type_action',
            'type_action_id',
            'type_action_name',
            'detail',
            'list_details',
            'comments',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'id','pv_id',
                    'user_id', 'user_name',
                    'dt_create',

                    'type_action_id',
                    'type_action_name',

                    'detail',
                    'list_details',
                    'comments',

                ], 'safe'
            ],

            //[['id','pv_id','user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№№',
            'pv_id' => 'Инв.№',

            'user_name' => 'Имя мастера',
            'user_id' => 'userid',
            'dt_create' => 'Дата',

//            'type_action' => 'Выполненно...',
            'type_action_id' => 'Выполненная работа ID',
            'type_action_name' => 'Выполненная работа',

            'detail' =>'Компонент',
            'list_details' => 'Замена компонентов',
            'comments' => 'Коментарии',
        ];
    }


}
