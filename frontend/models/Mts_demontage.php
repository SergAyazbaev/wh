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
class Mts_demontage extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'mts_demontage'];
    }
    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'sklad_id',
            'akt',     /// ACT

            'id_ap', // Автопарк
            'id_pe', // PE

            'mts_id',   // Ид ИСполнителя МТС

            'dt_create',            // Создано CRM
            'dt_create_timestamp',  // Создано CRM

            'bar_code',
            'name',  // название устройства

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
            'sklad_id' => '№ накладной', /// ИД накладной СКЛАДА
            'akt' => 'АКТ №', /// ИД накладной СКЛАДА

            'id_ap' => '№ Автопарк',
            'id_pe' => '№ ПE',

            'mts_id' => 'МТС. Мобильный Сотрудник',
            'tz_id' => 'TZ', /// ????

            'bar_code' => "Щтрих код", // Снято,
            'name' => 'Название устройства',

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
                'akt', ///

                'id_ap', // Автопарк
                'id_pe', // PE

                'bar_code',
                'name',  // название устройства

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

//    public $start_date;

//    public $all_summary_barcodes = 0; ///Сколько позиций Баркодов в накладной

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

//    /**
//     * Next AKT
//     * =
//     *
//     * @return int
//     */
//    public static function setNext_max_akt()
//    {
//        $xx = static::find()->asArray()->max('akt');
//        return ++$xx;
//    }

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

    static function arrayBlack_list()
    {
        return ArrayHelper::map(static::find()
            ->where(
                ['<>', 'job_fin', Null]
            )
            ->all(), 'sklad_id', 'sklad_id');
    }

    static function arrayBlack_list_all()
    {
        return ArrayHelper::map(static::find()->all(), 'sklad_id', 'sklad_id');
    }

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
    ///

    /// AP


    ///
    ///
    ///PE

    ///

    /**
     * Связка с Таблицей  Sprwhelement для ПОЛЯ wh_dalee   ТОП
     *=
     * @return ActiveQueryInterface
     */
    public function getSprwhtop_wh_dalee()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'id_ap']);
    }

    ///
    /// findModelDouble
    ///

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
     * Определение списка Ид номеров накладных
     */
    ///
    ///  BLACK LIST FOR OPEN
    ///
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

    /**
     * Определение ПОЛНОГО списка Ид номеров накладных
     */
    ///
    ///  BLACK LIST FOR ALL OPEN
    ///
    public function getSprwhelement_ap()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'id_ap']);
    }


    /**
     * Определение списка БАРКОДОВ в исполненных накладных
     */
    ///
    ///  BLACK LIST FOR OPEN
    ///
    public function getSprwhelement_pe()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'id_pe']);
    }

}
