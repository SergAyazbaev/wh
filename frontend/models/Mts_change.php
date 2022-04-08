<?php

namespace frontend\models;

use Yii;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\helpers\BaseFileHelper;
use \yii\mongodb\ActiveRecord;

/**
 * MTS
 * =
 * Class Typeact
 * @package app\models
 */
class Mts_change extends ActiveRecord
{

    public $start_date;

//    public $imageFiles;


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'mts_change'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',

            'sklad', // Номер склада тов. МТС

            'id_ap', // Автопарк
            'id_pe', // PE

            'dt_create', // Создано CRM
            'dt_create_timestamp', // Создано CRM

            'barcode_bad', // BAD
            'barcode_god', // God

            'imageFiles',//
            'imageFiles_old',//
            'imageFiles_new',//

            'path_hash', //
            'path_hash_old', //
            'path_hash_new', //

            'tz_id',    // TZ
            'tz_txt',   // TZ
            'tx',

            'mts_id',   // Ид ИСполнителя МТС

            'job_fin',
            'job_fin_timestamp',
            'close_day',  // 'День закрыт',
            'transfer_ok',  // Позиция попала в накладную, которая уже передана ДЕЖУРНОМУ на трансфер


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',

            'id_ap' => '№ Автопарк',
            'id_pe' => '№ ПE',

            'sklad' => 'Склад МТС', // Номер склада тов. МТС

            'code_bag' => 'Вид неисправности',      // select id
            'code_job' => 'Вид работы',             // select id
            'code_rez' => 'Результат обслуживания', // text

            'dt_create' => "Дата действия",
            'dt_create_timestamp' => "Дата действия", //"Создано",

            'barcode_bad' => "Демонтаж",// " Снято",
            'barcode_god' => "Монтаж",//" Установлено",

            'imageFiles' => 'Фото',//
            'imageFiles_old' => 'Фото OLD',//
            'imageFiles_new' => 'Фото NEW',//

            'path_hash' => 'hash', //
            'path_hash_old' => 'hash old', //
            'path_hash_new' => 'hash new', //

            'dt_update' => "Отработал МТС",
            'dt_update_timestamp' => "Отработал МТС",


            'crm_txt' => 'Задание из CRM',   // CRM не надо его гонять клиент-сервер
            'tz_id' => 'Номер ТЗ',    // TZ
            'tz_txt' => 'Текст ТЗ',   // TZ

            'tx' => 'Комент',

            'mts_id' => 'МТС. Мобильный Сотрудник',

            'job_fin' => 'Работа выполнена',
            'job_fin_timestamp' => 'Время',

            'close_day' => 'День закрыт',
            'transfer_ok' => 'transfer',

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


                'dt_create', // Создано CRM
                'dt_create_timestamp', // Создано CRM

                'barcode_bad', // BAD
                'barcode_god', // God

                'imageFiles', //
                'imageFiles_old',//
                'imageFiles_new',//

                'path_hash', //
                'path_hash_old', //
                'path_hash_new', //


                'mts_id',

                'tz_id',
            ],
                'safe'
            ],

            [['id',], 'unique'],

            [['job_fin'], 'integer'], [['job_fin'], 'default', 'value' => 0],
            [['close_day', 'transfer_ok'], 'integer'],
            [['close_day', 'transfer_ok'], 'default', 'value' => 0],


        ];
    }

    /**
     * Связка с Таблицей  Sprwhelement
     * -
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_mts()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'mts_id']);

    }


    ///
    /// AP
    ///
    public function getSprwhelement_ap()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'id_ap']);

    }

    ///
    ///
    ///
    public function getSprwhelement_pe()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'id_pe']);

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
     * Find FiRST id_PE
     * =
     * Позиции, которые не закрыты
     * -
     * @return int
     */
    public static function findFirst_id_pe()
    {
        $xx = static::find()
            ->where(['==', 'close_day', (int)0])   // Позиции, которые не закрыты
            ->asArray()
            ->one();
        if (!$xx) return null;

        return $xx['id_pe'];
    }

    ///
    /// findModelDouble
    ///
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
     * upload MTS_CHANGE OLD
     * =
     */
    public function upload_old()
    {
        /// HASH
        $path_hash = Yii::$app->getSecurity()->generateRandomString();

        $model = new UploadForm_mts_change();
//        ddd($model);

        //ddd(1111);
//        ddd($this);

        $model->imageFiles = $this->imageFiles_old;

        if ($model->validate(false)) {
            foreach ($model->imageFiles as $file) {
                if (BaseFileHelper::createDirectory('photo/mts_change_old/' . $path_hash)) {
                    $file->saveAs('photo/mts_change_old/' . $path_hash . '/' . $file->baseName . '.' . $file->extension);
                }
            }
            return $path_hash;
        }
        return false;
    }

    /**
     * upload MTS_CHANGE NEW
     * =
     */
    public function upload_new()
    {
        /// HASH
        $path_hash = Yii::$app->getSecurity()->generateRandomString();

        $model = new UploadForm_mts_change();
        $model->imageFiles = $this->imageFiles_new;

        if ($model->validate(false)) {
            foreach ($model->imageFiles as $file) {
                if (BaseFileHelper::createDirectory('photo/mts_change_new/' . $path_hash)) {

                    $file->saveAs('photo/mts_change_new/' . $path_hash . '/' . $file->baseName . '.' . $file->extension);
                }
            }
            return $path_hash;
        }
        return false;
    }


}
