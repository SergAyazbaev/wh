<?php

namespace frontend\models;

use Yii;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use \yii\mongodb\ActiveRecord;

/**
 * MTS
 * =
 * Class Typeact
 * @package app\models
 */
class mts_crm extends ActiveRecord
{

    public $start_date;


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'mts_crm'];
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

            'id_gos_pe',
            'id_bort_pe',

            'dt_create', // Создано CRM
            'dt_create_timestamp', // Создано CRM
            'dt_update', // Отработал МТС
            'dt_update_timestamp', // Отработал МТС

            'code_bag',
            'code_job',
            'code_rez',

            'crm_txt',   // CRM

            'tz_id',    // TZ
            'tz_txt',   // TZ
            'tx',

            'mts_id',   // Ид ИСполнителя МТС

            'job_fin',
            'job_fin_timestamp',

            'zakaz_fin',
            'zakaz_fin_timestamp',

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№ Id',

            'id_ap' => '№ Автопарк',
            'id_pe' => '№ ПE',

            'id_gos_pe' => '№ ГОС',
            'id_bort_pe' => '№ БОРТ',

            'code_bag' => 'Вид неисправности',      // select id
            'code_job' => 'Вид работы',             // select id
            'code_rez' => 'Результат обслуживания', // text

            'dt_create' => "Создано CRM",
            'dt_create_timestamp' => "Создано CRM",
            'dt_update' => "Отработал МТС",
            'dt_update_timestamp' => "Отработал МТС",


            'crm_txt' => 'Задание из CRM',   // CRM не надо его гонять клиент-сервер

            'tz_id' => 'Номер ТЗ',    // TZ
            'tz_txt' => 'Текст ТЗ',   // TZ

            'tx' => 'Комент',

            'mts_id' => 'МТС. Мобильный Сотрудник',

            'job_fin' => 'Работа выполнена',
            'job_fin_timestamp' => 'Время',

            'zakaz_fin' => 'Заявка закрыта',
            'zakaz_fin_timestamp' => 'Время закрытия',

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

                'id_ap',
                'id_pe',

                'id_gos_pe',
                'id_bort_pe',

                'mts_id',

                'code_bag',
                'code_job',
                'code_rez',

                'tz_id',

                'crm_txt',

                'tx',
            ],
                'safe'
            ],

            [['id',], 'unique'],

            [['job_fin', 'mts_id', 'zakaz_fin'], 'default', 'value' => 0],

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

}
