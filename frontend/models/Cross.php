<?php

namespace frontend\models;



//use frontend\components\Vars;
use yii\mongodb\ActiveRecord;
use Yii;


/**
 * Class Cross
 * @package app\models
 */
class Cross extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'cross'];
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
            'parent_id',
            'local_id',

            'bar_code_aa',
            'bar_code_int',
            'bar_code_cross',

            'dt_print',
            'dt_create',
            'dt_create_end',
            'dt_edit',

            'name',
            'html_text',
            'user_id',
            'user_group_id',

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
//            [['bar_code'],   'required'],
//            [['bar_code'],   'match', 'pattern' => '/^[A-Z]{2,3}[ ]?[\d]{5,15}$/i',
//                'message' => 'Маска не соблюдена (AZ 12345+)'],

//            [ ['bar_code'], 'filter', 'filter' => 'strtoupper'],
//            [ ['bar_code'], 'filter', 'filter' => 'trim'],
//            [ ['bar_code'], 'unique'],


            [ ['dt_print'], 'required'],
            [ ['dt_print'], 'unique'],
            [ ['dt_print'], 'compare', 'compareAttribute' => 'dt_create',
                'operator' => '>=', 'enableClientValidation' => false],

            [ ['user_id'], 'required'],
            [ ['user_id'], 'number', 'min' => 1],
            [ ['user_id'], 'number', 'max' => 10],

            [ ['user_group_id'], 'required'],
            [ ['user_group_id'], 'number', 'min' => 1],
            [ ['user_group_id'], 'number', 'max' => 100],



//            [ ['number_min'], 'number', 'min' => 10],
//            [ ['number_max'], 'number', 'max' => 100],

//            ['dt_create',       'date', 'timestampAttribute' => 'dt_create'],
//            ['dt_create_end',   'date', 'timestampAttribute' => 'dt_create_end'],
//            ['dt_create', 'compare', 'compareAttribute' => 'dt_create_end',
//                'operator' => '<', 'enableClientValidation' => false],


            [[
                '_id',

                'id',
                'parent_id',
                'local_id',

                'bar_code_aa',
                'bar_code_int',
                'bar_code_cross',

                'dt_print',
                'dt_create',        // START DATE - PERIOD
                'dt_create_end',    // END DATE - PERIOD
                'dt_edit',

                'name',
                'html_text',
                'user_id',
                'user_group_id',
            ],
                'safe'],


        ];

    }


//    public function getrel_group(){
//        return $this->hasMany(SprGroup::className(), ['group_id' => 'id']);
//    }





    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => 'Очередь №',

            'parent_id' => 'Оригинал', // Если печать происходит не первый раз,
                                // то повторяем вывод но сохраняем новую дату распечатки
                                // с Признаком "КОПИЯ"

            'dt_print'  =>  'Дата печати',
            'dt_create' =>  'Дата создания',
            'dt_create_end' =>  'До ...',

            'dt_edit'   =>  'Дата редактирования',


            'bar_code_aa'       =>  'AA',
            'bar_code_int'      =>  '12345',
            'bar_code_cross'    =>  'Штрихкод',

            'name_pv'   =>  'Наименование Документа',
            'html_text' =>  'Содержимое документа',

            'user_id'       =>  'Пользователь',
            'user_group_id' =>  'Группа доступа',


        ];
    }


    /**
     * @return false|string
     */
    public function getMinValueId()
    {


        return date('d.m.Y', $this->birthday);
    }




}
