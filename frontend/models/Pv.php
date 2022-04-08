<?php

namespace frontend\models;



//use frontend\components\Vars;
//use MongoDB\BSON\ObjectID;
//use MongoDB\BSON\UTCDateTime;
use Yii;

/**
 * Class Pv
 * @package app\models
 */
class Pv extends \yii\mongodb\ActiveRecord
{
//    private static $max_pv=0;
    public $id;
    public $max_pv= 0 ;


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'pv'];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getPvMotion()
    {
        return $this->hasMany(Pvmotion::className(), ['pv_id' => 'id']);
    }


    /**
     * @param $number_mongo
     * @return int
     */
    public static function Maxpv($number_mongo)
    {
        global $number_mongo;

        $number_mongo=Pv::find()->max('number_mongo');


        return $number_mongo ;
    }



    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [

            '_id',

            'name_pv',
            //'dt_create_pv',
            'dt_create',
            'dt_create_mongo',
            'dt_create_end',
            'date_mongo',
            'group_pv',
            'type_pv',
            'pv_health','pvaction',
            'group_pv_name',
            'type_pv_name',

            'pv_imei',
            'pv_kcell',
            'pv_bee',

            'bar_code_pv',
            'qr_code_pv',

            'parameters_pv',
            'service_pv',
            'active_pv',
            'active_pv_name',
            'number_mongo'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            [[
                'dt_create','group_pv_name','type_pv_name',
                'pv_health','pvaction',
                'bar_code_pv',
            ], 'required'],

            [['id','qr_code_pv','bar_code_pv'],
                'integer'],

            [['name_pv',
                //'dt_create_pv',
                'dt_create',
                'dt_create_mongo',
                'dt_create_end',
                'date_mongo',

                'group_pv_name','type_pv_name',

                'pv_health','pvaction',

                'pv_imei',
                'pv_kcell',
                'pv_bee',

                'bar_code_pv',
                'qr_code_pv',

                'parameters_pv', 'service_pv',
                'active_pv','active_pv_name',
                'number_mongo'
            ], 'safe'],


            [['pv_kcell','pv_bee'], 'unique', 'message' => 'Этот номер уже есть в базе'],
            [['qr_code_pv','bar_code_pv'], 'unique', 'message' => 'Значение уже занято.'],


                //['dt_create', 'datetime', 'format' => 'Y-m-d h:i:s'],
                //['dt_create', 'datetime', 'format' => 'yyyy-m-d h:i:s'],
                //['dt_create', 'datetime', 'format' => 'yyyy-m-d H:i:s'],

                //OK2 ['dt_create', 'datetime', 'format' => 'd.m.Y H:i:s'],

                //OK3 ['dt_create', 'datetime', 'format' => 'dd.mm.yyyy H:i:s'], //OK

            ['dt_create', 'datetime', 'format' => 'd.m.Y H:i:s'],


//            ['dt_create',       'date', 'timestampAttribute' => 'dt_create'],
//            ['dt_create_end',   'date', 'timestampAttribute' => 'dt_create_end'],
//            ['dt_create', 'compare', 'compareAttribute' => 'dt_create_end',
//                'operator' => '<', 'enableClientValidation' => false],



            // [['dt_create_pv'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/']

        ];

    }


//    public function getrel_group(){
//        return $this->hasMany(SprGroup::className(), ['group_id' => 'id']);
//    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getgroup(){
        return $this->hasOne(Spr_globam::className(), ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function gettype(){
        return $this->hasOne(Sprtype::className(), ['type_id' => 'id']);
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№',
            'name_pv' => 'Наименование Прибора/Устройства',
            //'dt_create_pv' =>  'Дата начала',
            'dt_create' =>  'Дата начала',
            'dt_create_end' => 'Период, конец ',

            'date_mongo' => 'Дата',

//            'group_pv'      => 'Группа ',
            'group_pv_name' => 'Группа ',
//            'type_pv'       => 'Тип объекта',
            'type_pv_name'  => 'Тип объекта',

            'pv_health'  => 'Статус.Здоровье.',
            //'pvaction'  => 'Статус.Действие.',

            'pv_imei' =>'IMEI',
            'pv_kcell'=>'K-Cell',
            'pv_bee' => 'BeeLine',

            'bar_code_pv' => 'BAR Code',
            'qr_code_pv' => 'QR Code',

            'parameters_pv' => 'Технические характеристики Array() ',
            'service_pv' => 'История Обслуживание Array() ',

            'active_pv' => 'Статус устройства',
            'active_pv_name' => 'Статус устройства_h',
            'number_mongo' => 'number_mongo',
        ];
    }




//    public static function to_isoDate($timestamp){
//
//       // $orig_date = new DateTime('2016-06-27 13:03:33');
//        $orig_date = new DateTime($timestamp);
//        $orig_date=$orig_date->getTimestamp();
//        return new UTCDateTime($orig_date*1000);
//    }


//    /**
//     * @param string $stolb
//     * @param int $min_max
//     * @return mixed
//     */
//    public static function Pv_max_value($stolb='number_mongo', $min_max=2 ) {
//        $asc_name=($min_max==1?' ASC':' DESC');
//
//            $query = new Query;
//            $query->select([$stolb])
//                ->from('pv')
//                ->orderBy( $stolb.$asc_name)
//                ->limit(1);
//            $rows = $query->one();
//            $max_value = $rows[$stolb];
//
//        return $max_value;
//    }


    /**
     * @return array
     */
    public function getExportData()
    {
            $files = [];
            //        foreach ($this->goodsImages as $image) {
            //            $files[] = $image->getUrl();
            //        }

        return [
            'Наименование Прибора/Устройства' => $this->name_pv,
            'Дата начала' => $this->dt_create,
            'Период, конец ' => $this->dt_create_end,
            'Группа' => $this->group_pv_name,
            'Тип объекта' => $this->type_pv_name,
            'Период конец' => $this->dt_create_end,
            'Статус.Здоровье.' => $this->pv_health,
            'Статус.Действие.' => $this->pv_action,

            'Изображения' => implode("\n", $files)
        ];
    }

}
