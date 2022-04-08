<?php

namespace frontend\models;


use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;


/**
 * Class Tk
 * @package app\models
 */
class Tk extends ActiveRecord
{
//    public $id;
//    public $max_pv= 0 ;


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'tk'];
    }


    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [

            '_id',
            'id',
            'name_tk',
            'dt_create',
            'dt_edit',

            'user_create_group_id',
            'user_create_id',
            'user_create_name',
            'user_edit_group_id',
            'user_edit_id',
            'user_edit_name',

            'user_id',
            'user_name',

            'wh_tk',
            'wh_tk_element',

            'tx',
            'ed_izmer',
            'ed_izmer_num',

            'intelligent',
            'array_tk',
            'array_tk_amort',
            'array_casual' ,

            'captcha'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            [[ 'id', ],   'unique'],

            [[
                'name_tk',
                'dt_create',
                ],
                'required'],

            [[
                'dt_create',
                'dt_edit',
                ],
                'date',
                'format'=>'php:d.m.Y H:i:s'
            ],


            [['dt_create'], 'default', 'value' => function()
                    { return date('d.m.Y H:i:s', strtotime('now')); }
            ],

            [['dt_edit'], 'default', 'value' => function()
                    { return date('d.m.Y H:i:s', strtotime('now')); }
            ],


//            ['user_edit_group_id', 'default','value' => function()
//            { return  \Yii::$app->getUser()->identity->group_id; }
//            ],

//            ['user_edit_group_id', 'default', 'value' => function()
//            { return (integer)\Yii::$app->getUser()->identity->group_id; } ],

            ['user_edit_id', 'default', 'value' => function()
                { return (integer)Yii::$app->user->identity->id; }],

            ['user_edit_name', 'default', 'value' => function()
                { return Yii::$app->user->identity->username; } ],





            [[
                'name_tk',

                'user_create_group_id',
                'user_create_id',
                'user_create_name',
                'user_edit_group_id',
                'user_edit_id',
                'user_edit_name',

                'wh_tk',
                'wh_tk_element',
                'tx',

                'ed_izmer',
                'ed_izmer_num',

                'intelligent',
                'array_tk',
                'array_tk_amort',
                'array_casual',

                'captcha'
            ],
                'safe'],

            ['ed_izmer_num', 'each', 'rule' => ['integer'] ,
                                 'message' => 'Значение цифр....'],

            ['ed_izmer_num', 'each', 'rule' => ['compare', 'compareValue' => 1, 'operator' => '>='],
                                 'message' => 'Значение не равно нулю '],





        ];

    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id'   => 'ID',
            'id'    => '№',
            'dt_create'     =>  'Дата создания',
            'dt_edit'       =>  'Дата правки',

            'user_create_id'=>  'ID Создателя',
            'user_edit_id'  =>  'ID Редактора',
            'user_create_name'=>  'Имя Создателя',
            'user_edit_name'  =>  'Имя Редактора',

            'user_create_group_id'=>  'Group Create',
            'user_edit_group_id'  =>  'Group Edit',

            'user_id'       =>  'User Id',
            'user_name'     =>  'User Name',


            'name_tk'       => 'Наименование Типового Комплекта (ТК)',
            'wh_tk'         =>  'Tk гр.тип',
            'wh_tk_element' =>  'Tk гр.тип элемент',

            'tx'            =>  'Tk комментарий',
            'ed_izmer'      =>  'Ед. изм',
            'ed_izmer_num'  =>  'Кол-во',

            'intelligent'   =>  "Есть штрихкод",
            'array_tk_amort'=>  'Амортизация',
            'array_tk'      =>  'Списание',
            'array_casual'  =>  'Расходные материалы',

            'captcha'       =>  'Капча',

        ];
    }

    /**
     * @param $id
     * @return Tk|array|null
     * @throws NotFoundHttpException
     */
    static function findModel($id)
    {
        if (($model = static::findOne($id)) !== null) {
            return $model;
        }

	    throw new NotFoundHttpException( 'Sklad 1 -   88   на запрос. Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад' );
    }


    /**
     * @param $id
     * @return array|null|\yii\mongodb\ActiveRecord
     */
    static function findModelDouble( $id)
    {
        if (($model = static::find()->where(['id' => (integer)$id])->one()) !== null) {
            return $model;
        }

        return [];
    }


}
