<?php

namespace frontend\models;

use yii\base\ExitException;
use yii\helpers\BaseFileHelper;
use \yii\mongodb\ActiveRecord;
use Yii;


//use yii\imagine\Image;
//use Imagine\Image\Box;
//use Imagine\Image\Point;
use yii\web\UploadedFile;


/**
 * PE
 * =
 * Class Typeact
 * @package app\models
 */
class Pe_identification extends ActiveRecord
{

    public $imageName;
    public $image;
    public $crop_info;

    const SCENARIO_PRINT_MTP210 = 'print_mtp210';


    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios[self::SCENARIO_PRINT_MTP210] = [
            'id',
            'id_ap',
            'id_pe',
            'bort',
            'gos',
            'dt_create_timestamp',
            'mts_id'
        ];
        return $scenarios;
    }


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'pe_identification'];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => '№ _id',

            'id' => '№',
//            'sklad_id' => '№ Накладной', /// ИД накладной СКЛАДА

            'id_ap' => 'Автопарк', //ИД
            'id_pe' => 'ПE',       //ИД

            'bort' => "Борт №", // PE
            'gos' => "Гос №", // PE
            'vin' => "VIN №", // PE

            'barcode_pv' => "Помощник водителя GJ-DA04 №", // PE
            'barcode_svb_m' => "CVB24 Мастер №", // Validator CVB24 master Терминал пассажира CVB24 ведущий (Master)
            'barcode_svb_1' => "CVB24 Слейв 1 №", // Validator CVB24 slave Терминал пассажира CVB24 (Slave)
            'barcode_svb_2' => "CVB24 Слейв 2 №", //
            'barcode_svb_3' => "CVB24 Слейв 3 №", //
            'barcode_svb_4' => "CVB24 Слейв 4 №", //
            'barcode_8210' => "Терминал 8210 №", // Терминал NEW8210 (в т.ч. блок питания)
            'barcode_switch_5_port' => "Ethernet-коммутатор IES1005", // Промышленный Ethernet-коммутатор IES1005 (Китай) (Switch ZTE IES1005 5port)
            'barcode_switch_8_port' => "Ethernet-коммутатор IES1008", // Промышленный Ethernet-коммутатор IES1008 (Китай) (Switch ZTE IES1008 8port)
            'barcode_switch_16_port' => "Ethernet-коммутатор IES1016", // Промышленный Ethernet-коммутатор IES1016 (Китай) (Switch ZTE IES1016 16port)
            'barcode_com_5_10_tx' => "NIS-3200", // NIS-3200-005T - промышленный коммутатор 5 10/100Base-TX (Россия)
            'barcode_vsp' => "Стабилизатор VSP", // Vehicle supply protector (Стабилизатор VSP01)

            'check_bort' => "Ok",
            'check_gos' => "Ok",
            'check_vin' => "Ok",

            'aray_check_box' => '',
            'aray_check' => 'Ok',

            'aray_photo_gos' => 'Гос',
            'aray_photo_bort' => 'Борт',
            'aray_photo' => 'Фото',

//            'check_box' => 'Штрихкод верен', //check_box

            'dt_create' => "Дата", // Создано
            'dt_create_timestamp' => "Дата", // Создано
            'mts_id' => 'МТС. Мобильный Сотрудник', //ИД


            'imageName' => 'Фото',//
            'imageFile' => 'Фото',//
            'imageFiles' => 'Фото',//
            'path_hash' => 'hash', //
        ];

        /// Помощник водителя GJ-DA04
        /// Validator CVB24 master Терминал пассажира CVB24 ведущий (Master)
        /// Validator CVB24 slave Терминал пассажира CVB24 (Slave)
        /// Терминал NEW8210 (в т.ч. блок питания)
        /// Промышленный Ethernet-коммутатор IES1005 (Китай) (Switch ZTE IES1005 5port)
        /// Промышленный Ethernet-коммутатор IES1008 (Китай) (Switch ZTE IES1008 8port)
        /// Промышленный Ethernet-коммутатор IES1016 (Китай) (Switch ZTE IES1016 16port)
        /// NIS-3200-005T - промышленный коммутатор 5 10/100Base-TX (Россия)
        /// Vehicle supply protector (Стабилизатор VSP01)
        ///

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
            'gos', //PE
            'vin',//PE


            ///////
            'check_bort', //PE
            'check_gos', //PE
            'check_vin',//PE

            'aray_check_box',
            'aray_check',

            'aray_photo_gos',
            'aray_photo_bort',
            'aray_photo',

//            'check_box',

            'dt_create',            // Создано CRM
            'dt_create_timestamp',  // Создано CRM
            'mts_id',   // Ид ИСполнителя МТС

            'imageFile',   //
            'imageFiles',   //
            'path_hash',   // path_hash
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
                'id_ap', // Автопарк
                'id_pe', // PE

                'bort', //PE
                'gos', //PE

                'dt_create_timestamp',  // Создано CRM
                'mts_id',   // Ид ИСполнителя МТС

            ],
                'safe', 'on' => self::SCENARIO_PRINT_MTP210
            ],

            [[
                '_id',
                'id',
                'id_ap', // Автопарк
                'id_pe', // PE

                'bort', //PE
                'gos', //PE
                'vin',//PE

                ///////
                'check_bort', //PE
                'check_gos', //PE
                'check_vin',//PE

                'aray_check_box',
                'aray_check',

                'aray_photo_gos',
                'aray_photo_bort',
                'aray_photo',

                'dt_create',            // Создано CRM
                'dt_create_timestamp',  // Создано CRM
                'mts_id',   // Ид ИСполнителя МТС

                'imageFile',
                'imageFiles',

                'image',
                'crop_info',
            ],
                'safe'
            ],


            [['id'], 'unique'],


            [['imageFile'], 'file',
                'skipOnEmpty' => true, // !!!!
                'extensions' => 'png, jpg',
                'maxFiles' => 1,
                'mimeTypes' => 'image/jpeg, image/png',
            ],

            [['imageFiles'], 'file',
                'skipOnEmpty' => true, // !!!!
                'extensions' => 'png, jpg',
                'maxFiles' => 4,
                'mimeTypes' => 'image/jpeg, image/png',
            ],

            [
                ['image'], 'image',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'mimeTypes' => ['image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'],
            ],


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
     * upload_crop
     * =
     */
    public function upload_crop()
    {
        /// HASH
        $path_hash = Yii::$app->getSecurity()->generateRandomString();

        $name_model = $this->imageName;
        $name = $name_model['name']['image'];

        if (BaseFileHelper::createDirectory('photo' . DIRECTORY_SEPARATOR . 'ident_pe' . DIRECTORY_SEPARATOR . $path_hash)) {
            $fp = fopen('photo' . DIRECTORY_SEPARATOR . 'ident_pe' . DIRECTORY_SEPARATOR . $path_hash . DIRECTORY_SEPARATOR . $name, "w");
            fwrite($fp, $this->image);
            fclose($fp);

            return $path_hash;
        }

        return false;
    }


    public function upload()
    {
        /// HASH
        $path_hash = Yii::$app->getSecurity()->generateRandomString();

        $model = new UploadForm();
        $model->imageFiles = $this->imageFiles;

        if ($model->validate(false)) {
            ///imageFiles
            foreach ($model->imageFiles as $file) {
                //$file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);

                if (BaseFileHelper::createDirectory('photo/ident_pe/' . $path_hash)) {
                    $file->saveAs('photo/ident_pe/' . $path_hash . '/' . $file->baseName . '.' . $file->extension);
                }
            }

            return $path_hash;
        }

        return false;
    }


    public function uploadFile(UploadedFile $file)
    {
        $this->imageFile = $file;
        $filenmame = mb_strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);
        $file->saveAs(Yii::getAlias('@web') . 'uploads/' . $filenmame);

        //ddd($file);
        //ddd($this);

        return $file->name;
    }


}
