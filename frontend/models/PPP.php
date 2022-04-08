<?php

namespace frontend\models;

//use frontend\components\Vars;
//use MongoDB\BSON\UTCDateTime;
use Yii;


/**
 * Class PPP
 * @package app\models
 */
class PPP extends \yii\mongodb\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'Printer_querry'];
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

            'doc_number',

            'dt_create' ,
            'dt_deadline',

            'user_create_id',
            'user_create_name',

            'user_printed_id',
            'user_printed_name',

            'array_amort',
            'array_share',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [

            [[

                'dt_create',
                'dt_deadline',

                ],
                'required'],


//            [['name_tz'], 'string', 'max' => 80],
//            [['id','multi_tz' ] , 'integer'],
//            [['multi_tz' ] ,
//                'integer', 'min' => 1],
//            [['multi_tz' ], 'default', 'value' => 0],


            [[
                '_id',
                'id',

                'doc_number',

                'dt_create' ,
                'dt_deadline',

                'user_create_id',
                'user_create_name',

                'user_printed_id',
                'user_printed_name',

                'array_amort',
                'array_share',

            ], 'safe'],


            // [['pv_kcell','pv_bee'], 'unique', 'message' => 'Этот номер уже есть в базе'],
            // [['qr_code_pv','bar_code_pv'], 'unique', 'message' => 'Значение уже занято.'],

            //['dt_create', 'datetime', 'format' => 'd.m.Y H:i:s'],

        ];

    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№',

            'dt_create'     =>  'Дата создания',
            'dt_deadline'   =>  'Крайняя Дата',

            'doc_number',

            'user_create_id'    =>  'ID Создателя',
            'user_create_name'  =>  'Имя Создателя',

            'user_printed_id'   =>  'User Id',
            'user_printed_name' =>  'User Name',

            'array_amort'       =>  'Амортизация',
            'array_share'       =>  'Простое списание',

            'tk_top'            =>  'Типовой Комплект (ТК)',

        ];
    }




}
