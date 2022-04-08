<?php

namespace frontend\models;

//use frontend\components\Vars;
use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;


/**
 * Class Tz
 * @package app\models
 */
class Shablon extends ActiveRecord
{
//    const STATE_NULL        = 0;    // Статус не определен
//    const STATE_IN_WORK     = 1;    // Статус "В работе"
//    const STATE_WORKED      = 2;    // Статус "Выполнено.ОК."
//    const STATE_TO_RETURN   = 10;   // Статус "Верните на базу"
//    const STATE_RETURNED    = 11;   // Статус "Возвращено на базу"

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'sklad_shablon'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',

            'shablon_name',

            'intelligent',

            'ed_izmer',
            'ed_izmer_num' ,

            'array_tk_amort',
            'array_tk',

            'user_id',
            'user_name',
            'user_group_id',

            'wh_home_number',

            'comments',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [

            [['id' ],  'unique'],
            [['id' ],  'integer'],
            [['id' ],  'required'],

                //            [['name_tz'], 'string', 'max' => 80],

                //            [['multi_tz' ] , 'integer'],
                //            [['multi_tz' ] , 'integer', 'min' => 1],
                //            [['multi_tz' ], 'default', 'value' => 0],

            [[
//                'wh_deb_top'      ,
//                'wh_deb_top_name'  ,
//                'wh_deb_element'   ,
//
//                'wh_cred_top'      ,
//                'wh_cred_top_name' ,
//                'wh_cred_element'  ,

                'shablon_name',

                'ed_izmer',
                'ed_izmer_num' ,

                'intelligent',
                'array_tk_amort',
                'array_tk'      ,
                'array_bus_gosnum',
                'array_bus_boardnum',

                'user_id',
                'user_name',
                'user_group_id',

                'wh_home_number',

                'comments',


            ], 'safe'],



        ];

    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

            'wh_deb_top'        =>  'Склад-источник',
            'wh_deb_top_name'   =>  '',
            'wh_deb_element'    =>  'Элемент-источник',
            'wh_cred_top'       =>  'Склад-приемник',
            'wh_cred_top_name'  =>  'Автопарк',
            'wh_cred_element'   =>  'Элемент-приемник',

            'shablon_name'  =>  'Название шаблона',
            'tx'            =>  'Tk комментарий',


            'intelligent'   => "Есть штрихкод",

            'ed_izmer'      => "Ед.изм",
            'ed_izmer_num'  => "Значение",

            'array_tk_amort'=>  'Амортизация',
            'array_tk'      =>  'Списание',

            'user_id'       => 'user_id',
            'user_name'     => 'Мат.отв',         //'Автор',
            'user_group_id' => 'user_group_id',

            'wh_home_number'=> 'Склад', // 'ПОРТ ПРИПИСКИ',

            'comments'      => 'Коменты',


        ];
    }


    /**
     * @param $array
     * @param int $sklad
     * @return array|bool
     */
    static function setTransfer_doc($array , $sklad=0 ) {
        //dd($array);

        if (is_object($array) ) {

            $max_value = Sklad::find()->max('id');
            $max_value++;


            $sklad_model = new Sklad();

            $sklad_model->id                 = (integer)$max_value;
//            $sklad_model->dt_transfer_start  =
//                Date(  "d.m.Y H:i:s",strtotime("now")) ;
//            $sklad_model->dt_create          = $array->dt_create;
//            $sklad_model->dt_update          = $array->dt_update;

            $sklad_model->prev_doc_number    = $array->id;

            $sklad_model->user_id            = $array->user_id;
            $sklad_model->user_name          = $array->user_name;

            $sklad_model->sklad_vid_oper     = $array->sklad_vid_oper;
            $sklad_model->sklad_vid_oper_name= $array->sklad_vid_oper_name;

            $sklad_model->wh_debet_top       = $array->wh_debet_top;
            $sklad_model->wh_debet_name      = $array->wh_debet_name;
            $sklad_model->wh_debet_element   = $array->wh_debet_element;
            $sklad_model->wh_debet_element_name    = $array->wh_debet_element_name;

            $sklad_model->wh_destination     = $array->wh_destination;
            $sklad_model->wh_destination_name= $array->wh_destination_name;
            $sklad_model->wh_destination_element    = $array->wh_destination_element;
            $sklad_model->wh_destination_element_name    = $array->wh_destination_element_name;

            if ( isset($array->tz_id) && $array->tz_id > 0 ){
                $sklad_model->tz_id      = $array->tz_id;
                $sklad_model->tz_name    = $array->tz_name;
                $sklad_model->tz_date    = $array->tz_date;
            }

            $sklad_model->array_tk_amort     = $array->array_tk_amort;
            $sklad_model->array_tk           = $array->array_tk;

            //$sklad_model->wh_home_number     = (int) $array->wh_destination_element; /// WH HOME NUMBER
            $sklad_model->wh_home_number     =(int) $sklad; /// WH HOME NUMBER

//
//        dd($sklad_model);


            if( $sklad_model->save(true)) {

                return true;
            }
            else{
                return  ( $sklad_model->errors  );


                //dd($sklad_model->errors);
                //throw new NotFoundHttpException('Доступ только SKLAD');

                //throw new \Exception ( implode ( "<br />" , \yii\helpers\ArrayHelper::getColumn ( $sklad_model->errors , 0 , false ) ) );
            }


        }
        return false;
    }


    /**
     * @param $id
     * @return Shablon|array|null
     * @throws NotFoundHttpException
     */
    static function findModel( $id )
    {
        if (($model = static::findOne($id)) !== null) {
            return $model;
        }

	    throw new NotFoundHttpException( 'Sklad 1 -   9999 . Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад' );
    }


    /**
     * @param $id
     * @return array|null|ActiveRecord
     * @throws NotFoundHttpException
     */
    static function findModelDouble( $id )
    {
        if (($model = static::find()->where(['id'=>(integer) $id])->one() ) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Sklad 2 -  Ответ на запрос. Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад');
    }




}
