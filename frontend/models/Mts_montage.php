<?php

namespace frontend\models;

use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use \yii\mongodb\ActiveRecord;
use Yii;



/**
 * MTS
 * =
 * Class Typeact
 * @package app\models
 */
class Mts_montage extends ActiveRecord
{

//    public $start_date;
//    public $all_summary_barcodes = 0; ///Сколько позиций Баркодов в накладной

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'mts_montage'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'sklad_id',     /// ИД накладной СКЛАДА

            'id_ap', // Автопарк
            'id_pe', // PE

            'mts_id',   // Ид ИСполнителя МТС

            'dt_create',            // Создано CRM
            'dt_create_timestamp',  // Создано CRM

            'barcode_montage',      // BAD

            'tz_id',    // TZ

            'job_fin',              ///Установка Да/Нет  (ДАТА)
            'job_close',            ///Закрытие дня Да/Нет (ДАТА)

            'job_fin_timestamp',
            'job_close_timestamp',

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'sklad_id' => '№ Накладной', /// ИД накладной СКЛАДА

            'id_ap' => '№ Автопарк',
            'id_pe' => '№ ПE',

            'mts_id' => 'МТС. Мобильный Сотрудник',
            'tz_id' => 'TZ',

            'barcode_demontage' => "Демонтаж",// " Снято",
            'barcode_montage' => "Монтаж",//" Установлено",

            'job_fin' => 'Часть работы выполнена',
            'job_fin_timestamp' => 'Время',

            'job_close' => 'Работа выполнена полностью',
            'job_close_timestamp' => 'Время',
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
                'sklad_id', /// ИД накладной СКЛАДА

                'id_ap', // Автопарк
                'id_pe', // PE

                'barcode_montage',      // BAD

                'mts_id',

                'tz_id',

                'job_fin',
                'job_fin_timestamp',

                'job_close',
                'job_close_timestamp',

            ],
                'safe'
            ],

            [['id',], 'unique'],

            /// ELSE Важно! Заполнить
//            [
//                [
//                    'id',
//                    'sklad_id',
//                ],
//                'required',
//                'message' => 'Заполнить, если монтировал',
//            ],

            //            [['job_fin'], 'integer'], [['job_fin'], 'default', 'value' => 0],
            //            [['close_day'], 'integer'], [['close_day'], 'default', 'value' => 0],

        ];
    }


    /**
     * Связка с Таблицей  Sprwhelement для ПОЛЯ wh_dalee   ТОП
     *=
     * @return ActiveQueryInterface
     */
    public function getSprwhtop_wh_dalee()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'id_ap']);
    }

    /**
     * Связка с Таблицей  Sprwhelement для ПОЛЯ wh_dalee_element
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_wh_dalee_element()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'id_pe']);
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
    ///PE
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
     * Определение списка Ид номеров накладных
     */
    ///
    ///  BLACK LIST FOR OPEN
    ///
    static function arrayBlack_list()
    {
        return ArrayHelper::map(static::find()
            ->where(
                ['<>', 'job_fin', Null]
            )
            ->all(), 'sklad_id', 'sklad_id');
    }

    /**
     * Определение ПОЛНОГО списка Ид номеров накладных
     */
    ///
    ///  BLACK LIST FOR ALL OPEN
    ///
    static function arrayBlack_list_all()
    {
        return ArrayHelper::map(static::find()->all(), 'sklad_id', 'sklad_id');
    }


    /**
     * Определение списка БАРКОДОВ в исполненных накладных
     */
    ///
    ///  BLACK LIST FOR OPEN
    ///
    static function arrayBlack_list_barcodes()
    {
        $array_ids_job_close = ArrayHelper::getColumn(static::find()
            ->where(
                ['>', 'job_fin_timestamp', 0]
            )
            ->all(), 'sklad_id');
        //ddd($array_ids_job_close);


        $array_barcode_montage = ArrayHelper::getColumn(static::find()
            ->where(
                ['IN', 'sklad_id', $array_ids_job_close]
            )
            ->all(), 'barcode_montage');

        $array_barcode_montage = array_unique($array_barcode_montage);
        return $array_barcode_montage;
    }

}
