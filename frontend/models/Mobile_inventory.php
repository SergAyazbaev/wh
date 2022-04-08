<?php

namespace frontend\models;

use yii\base\ExitException;
use \yii\mongodb\ActiveRecord;
use Yii;



/**
 * PE
 * =
 * Class Typeact
 * @package app\models
 */
class Mobile_inventory extends ActiveRecord
{
    public $array_check;        // галочки чекбокс
    public $array_check_box;    // количество штук
    public $array_barcode_box;  // barcode


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'mobile_inventory'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'id_ap', // Автопарк
            'id_pe', // PE

            'bort', //PE
            'gos',  //PE
            'vin',  //PE

            'check_bort',  //check_bort
            'check_gos',  //check_gos

            'thing_group',  //GROUP
            'thing_element',  //Element
            'thing_count',  //COUNT
            'thing_barcode',  //Bar_code
            'thing_check',  //thing_check

//            'array_check',  //all Checks
//            'array_check_box',  //aray_check_box
//            'array_barcode_box',  //aray_barcode_box

            'dt_create',            // Создано CRM
            'dt_create_timestamp',  // Создано CRM
            'mts_id',   // Ид ИСполнителя МТС

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => '№ _id',
            'id' => '№',

            'id_ap' => 'Автопарк', //ИД
            'id_pe' => 'ПE',       //ИД

            'thing_group' => "Группа", //GROUP
            'thing_element' => "Элемент",  //Element
            'thing_count' => "шт.",  //COUNT
            'thing_barcode' => "Штрихкод",  //Bar_code
            'thing_check' => "OK",  //thing_check


            'bort' => "Борт №", // PE
            'gos' => "Гос №", // PE
            'vin' => "VIN №", // PE


            'check_bort' => "OK", // ОК
            'check_gos' => "OK", // ОК
            'array_check' => "Ок", // ОК

//            'array_check_box' => "111", // ОК
//            'array_barcode_box' => "111", // ОК

            'dt_create' => "Дата", // Создано
            'dt_create_timestamp' => "Дата", // Создано
            'mts_id' => 'Инсп.', //ИД
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                '_id',
                'id',
                'id_ap', // Автопарк
                'id_pe', // PE

                'bort', //PE
                'gos',  //PE
                'vin',  //PE

                'thing_group',  //GROUP
                'thing_element',  //Element
                'thing_count',  //COUNT
                'thing_barcode',  //Bar_code
                'thing_check',  //thing_check

                'check_bort',
                'check_gos',

//                'array_check',
//                'array_check_box',
//                'array_barcode_box',


                'dt_create',            // Создано CRM
                'dt_create_timestamp',  // Создано CRM
                'mts_id',   // Ид ИСполнителя МТС
            ],
                'safe'
            ],


            [['id'], 'unique'],


        ];
    }


    /**
     * {@inheritdoc}
     */
    static function findModelDouble($id)
    {
        if (($model = static::find()
                ->where(['id' => (int)$id])
                ->one()) !== null
        ) {
            return $model;
        }

        throw new ExitException('MTS ID Error');
    }

    /**
     * setNext_max_id()
     * =
     *
     * @return int
     */
    public static function setNext_max_id()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;
    }

    /**
     *
     */
    public function getSprwhelement_ap()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'id_ap']);
    }

    /**
     *
     */
    public function getSprwhelement_pe()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'id_pe']);
    }

    /**
     * thing_group
     */
    public function getSpr_globam()
    {
        return $this->hasOne(Spr_globam::className(), ['id' => 'thing_group']);
    }

    /**
     * thing_group
     */
    public function getSpr_globam_element()
    {
        return $this->hasOne(Spr_globam_element::className(), ['id' => 'thing_element']);
    }




}
