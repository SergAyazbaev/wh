<?php

namespace frontend\models;

//use frontend\components\Vars;
use Yii;


/**
 * Class Tz_to_work
 * @package app\models
 */
class Tz_to_work extends \yii\mongodb\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'tz_to_work'];
    }


//    public function getPvMotion()
//    {
//        return $this->hasMany(pvmotion::className(), ['pv_id' => 'id']);
//    }


    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'tz_id',

            'dt_create' ,
            'dt_deadline',
            'dt_deadline1',
            'dt_deadline2',

            'user_create_group_id',
            'user_create_id',
            'user_create_name',

            'user_edit_group_id',

            'name_tz' ,
            'name_tk' ,

            'captcha' ,
            'three' ,

            'status_state',
            'status_create_user',
            'status_create_date',

            'status_return',
            'status_return_create_user',
            'status_return_create_date',


            'datetime',
            'clientIp',
//            'clientHost',
            'referer',

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

    return [
        [[
            'id',
            'tz_id',

            'user_create_group_id',
            'user_create_id',
            'user_create_name',

            ],'required' ],


        ['datetime', 'default', 'value' =>
            Date(  "Y-m-d H:i:s",strtotime("now")) ],

        ['clientIp', 'default', 'value' =>
            function(){ return Yii::$app->request->getUserIP(); }],

        ['referer', 'default', 'value' =>
            function(){ return Yii::$app->request->getReferrer(); }],


            //        ['clientHost', 'default', 'value' => function()
            //        { return Yii::$app->request->getUserHost(); }],

            //        ['clientUserAgent', 'default', 'value' => function()
            //        { return Yii::$app->request->getUserAgent(); }],

        [[
            'id',
            'tz_id',
            ],'unique' ],

        [[
                '_id',
                'id',
                'tz_id',

                'dt_create' ,
                'dt_deadline',
                'dt_deadline1',
                'dt_deadline2',

                'user_create_group_id',
                'user_create_id',
                'user_create_name',

                'name_tz' ,
                'name_tk' ,

                'captcha' ,
                'three' ,

                'status_state',
                'status_create_user',
                'status_create_date',

            'status_return',
            'status_return_create_user',
            'status_return_create_date',


                'datetime',
                'clientIp',
//                'clientHost',
                'referer',

            ],   'safe'  ],

        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id'               => 'ID',
            'id'                => '№',
            'tz_id',

            'dt_create'         =>  'Дата создания',
            'dt_deadline'       =>  'Крайняя Дата',
            'dt_deadline1'      =>  'DL1',
            'dt_deadline2'      =>  'DL2',

            'user_create_group_id'  =>  'Group Create',
            'user_create_id'        =>  'ID Создателя',
            'user_create_name'      =>  'Имя Создателя',

            'name_tz'       =>  'Тех.Задание',
            'name_tk'       =>  'Типовой Комплект (ТК)',

//            'captcha'     =>  'Капча',
//            'three'       =>  'тройная кнопка',

            'status_state'          =>  'Статус',
            'status_create_user'    =>  'Автор',
            'status_create_date'    =>  'Дата',

            'status_return'             => 'Команда "Возврат"',
            'status_return_create_user' => 'Автор',
            'status_return_create_date' => 'Дата',

            'datetime',
            'clientIp',
            'clientHost',
            'referer',

        ];
    }


}
