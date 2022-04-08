<?php

namespace frontend\models;


use Yii;
use yii\base\ExitException;
use yii\mongodb\ActiveRecord;


class Sklad_delete extends ActiveRecord
{

    public $db;

    public $sklad;


    public $dt_start, $dt_stop;
    public $dt_one_day;

    public $add_button;      //// Номер нового вводимого документа (или любое другое применение)
    public $erase_array;     //// Удаление строк ПАКЕТОМ
    ///
    public $am_element;   //// Элемент для выделения крассным цветом
    public $tk_element;   //// Элемент для выделения крассным цветом


    //    public $to_print;  ////PROVERKA

    public $wh_top, $wh_element;
    public $vid_oper;

    public $sprav_wh_element;


    public
        $select2_array_cs_numbers,
        $array_wh_cs_multiple_numbers,
        $array_wh_cs_multiple_users;

    public $wh_cs_parent_number;
    public $wh_cs_parent_name;


    //copypast для заполнения из модальной формы-буфера
    public $pool_copypast_id;
    public $pool_copypast_fufer;


    //const VID_NAKLADNOY_INVENTARIZACIA = 1 ;

    const VID_NAKLADNOY_INVENTORY = 1;
    const VID_NAKLADNOY_PRIHOD = 2;
    const VID_NAKLADNOY_RASHOD = 3;

    const VID_NAKLADNOY_INVENTARIZACIA_ZALIVKA_STR = 'Заливка динамичная';
    const VID_NAKLADNOY_INVENTARIZACIA_STR = 'Инвентаризация';

    const VID_NAKLADNOY_PRIHOD_STR = 'Приходная накладная';
    const VID_NAKLADNOY_RASHOD_STR = 'Расходная накладная';


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {


        return [
            Yii::$app->params['vars'],
            'sklad_delete',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {

        return [
            '_id',
            'id',
            'flag', // Для обработки по всей Коллекции

            'key_tabl',
            // величение количества строк в построителе таблиц

            'wh_home_number',
            // ид текущего склада
            'wh_cs_number',
            //				'wh_cs_parent_number',				// ид склада CS

            //				'sprwhtop_destination.id',				// ид склада CS

            'prev_doc_number',
            // ид накладной источника

            'sklad_vid_oper',
            'sklad_vid_oper_name',

            'dt_create',
            'dt_update',
            'dt_start',
            'dt_one_day',

            'dt_create_timestamp',
            'dt_update_timestamp',

            //'dt_update_timestamp',
            //"dt_create_end",

            'wh_debet_top',
            'wh_debet_name',
            'wh_debet_element',
            'wh_debet_element_name',

            //  'wh_debet_element_cs',

            'wh_destination',
            'wh_destination_name',
            'wh_destination_element',
            'wh_destination_element_name',
            'wh_destination_element_cs',


            'wh_dalee',
            'wh_dalee_name',
            'wh_dalee_element',
            'wh_dalee_element_name',


            'user_id',
            'user_ip',
            "user_name",
            "user_group_id",

            "update_user_id",
            "update_user_name",

            "tz_id",
            "tz_name",
            "tz_date",

            "dt_to_work_signal",
            "dt_deadline",

            'dt_transfered_date',
            'dt_transfered_user_id',
            'dt_transfered_user_name',


            'array_tk_amort',
            //'array_tk_amort.wh_tk_element',


            'array_tk',
            'array_casual',
            'array_bus',
            'array_count_all',

            'bar_code',

            'tx',

            'sprwhelement_debet.name',
            'sprwhelement_debet_element.name',
            'sprwhelement_destination.name',

            'sprwhelement_destination_element.name',
            'sprwhelement_destination_element.nomer_borta',
            'sprwhelement_destination_element.nomer_gos_registr',
            'sprwhelement_destination_element.nomer_vin',
            'sprwhelement_destination_element.tx',

            'sprwhelement_wh_cs_number',
            'sprwhTopName',


            'dt_delete_timestamp',

            'update_points',
        ];
    }


    /**
     * Создаю ТаймШТАМП для всех операций Создания накладных и Редактирования
     *
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt_delete_timestamp'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dt_delete_timestamp'],
                ]
            ]

        ];

    }


    /**
     * @return array
     */
    public function rules()
    {

        return [

//            [['id',], 'unique'],

            [['id',], 'default', 'value' => (int)0,],

            [['tx'], 'string', 'max' => 260],

            [['tx'], 'trim'],


            [
                [
                    'id',
                    'twin',

                    'wh_home_number',
                    // ид текущего склада

                    'wh_cs_number',
                    // ид текущего склада CS

                    'wh_debet_top',
                    'wh_debet_element',
                    'wh_destination',
                    'wh_destination_element',

                    'wh_dalee',
                    'wh_dalee_name',
                    'wh_dalee_element',
                    'wh_dalee_element_name',

                    'user_id',
                    'user_ip',
                    "user_name",
                    "user_group_id",

                    "tz_id",
                    "tz_name",
                    "tz_date",

                    "dt_to_work_signal",
                    "dt_deadline",

                    "dt_create",
                    "dt_create_timestamp",


                    'array_tk_amort',
                    'array_tk',
                    'array_casual',
                    'array_bus',

                    'bar_code',

                    'sprwhelement_name',
                    'sprwh',


                    'sprwhelement_wh_cs_number',
                    'sprwhTopName',


                    "sklad_vid_oper",
                    "wh_debet_name",
                    "wh_debet_element_name",
                    "wh_destination_name",
                    "wh_destination_element_name",
                    "array_count_all",
                    "dt_update",
                    "dt_update_timestamp",

                    "update_points",

                ],
                'safe',
            ],

            /// ЕСЛИ ЕЩЕ НЕ ЗАПОЛНИЛИСЬ !!!!
            [
                [
                    'sklad_vid_oper',
                    'dt_create',

                    'wh_debet_top',
                    'wh_debet_element',
                    'wh_destination',
                    'wh_destination_element',
                ],
                'required',
                'message' => 'Заполнить...',
            ],


            [
                [
                    'wh_debet_top',
                    'wh_debet_element',
                    'wh_destination',
                    'wh_destination_element',
                ],
                'integer',
            ],


            [
                'user_ip',
                'default',
                'value' => function () {
                    return Yii::$app->request->getUserIP();
                },
            ],

            [
                'user_id',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->id;
                },
            ],

            [
                'user_name',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->username;
                },
            ],

            [
                'update_user_id',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->id;
                },
            ],

            [
                'update_user_name',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->username;
                },
            ],

            [
                'user_group_id',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->group_id;
                },
            ],

            [
                ['wh_home_number'],
                'integer',
            ],

            [
                ['wh_cs_number'],
                'integer',
            ],


            [
                ['array_bus'],
                'default',
                'value' => [],
            ],


            [
                [
                    'id',
                    'tz_id',
                ],
                'integer',
            ],


        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [

            '_id' => '_ID',
            'id' => '№',

            'sklad_vid_oper' => 'ВидОп',
            'sklad_vid_oper_name' => 'Вид операции-имя',

            'dt_create' => 'Дата накладной',
            'dt_update' => 'Дата исправления',
            'dt_create_timestamp' => 'Дата создания',
            'dt_update_timestamp' => 'Дата редактир.',
            'dt_delete_timestamp' => 'Дата удаления',

            /// DOP Elements
            'dt_one_day' => 'Один день',
            'dt_start' => 'Начало',
            'dt_stop' => 'Окончание',
            'to_print' => 'На Печать',


            'tz_id' => '№ ТЗ',
            // ТЗ, по которому делали накладную
            'tz_name' => 'Название ТЗ',
            'tz_date' => 'tz_date',

            'user_id' => 'user_id',
            'user_ip' => 'IP',
            'user_name' => 'Автор',
            //'Автор',
            'user_group_id' => 'user_group_id',

            "update_user_id" => '',
            "update_user_name" => '',

            'wh_home_number' => 'База',
            // 'Id cклад',
            'prev_doc_number' => 'ид накладной Отправителя',

            'wh_top' => '№ Склада #',
            'wh_top_name' => 'Название склада #',
            'wh_element' => 'Склад ID',

            'wh_debet_top' => 'Компания-отправитель',
            'wh_debet_name' => 'Компания-отпр ИМЯ',
            'wh_debet_element' => 'Склад-отправитель',
            'wh_debet_element_name' => 'Склад-отпр ИМЯ',


            'wh_destination' => 'Компания-получатель',
            'wh_destination_name' => 'Компания-получатель',
            'wh_destination_element' => 'Склад-получатель',
            'wh_destination_element_name' => 'Склад-получатель',

            'wh_dalee' => 'Целевой парк',
            'wh_dalee_element' => 'Целевой склад',

            'wh_dalee_name' => 'Целевой склад',
            'wh_dalee_element_name' => 'Целевой склад',


            'tz_user_edit_id' => 'tz_user_edit_id',
            'tz_user_edit_name' => 'tz_user_edit_name',

            'dt_to_work_signal' => 'dt_to_work_signal',
            'dt_deadline' => 'Крайний срок',

            'dt_transfered_date' => 'Дата передачи накладной',
            'dt_transfered_user_id' => 'ИД',
            'dt_transfered_user_name' => 'Автор передачи накладной',

            'array_tk_amort' => 'Амортизация',
            'array_tk' => 'Списание',
            'array_casual' => 'Расходные материалы',
            'array_bus' => 'ПЕ',
            'array_count_all' => 'Стр.',
            //'Всего строк',

            'comments' => 'Коменты',
            'bar_code' => 'BarCode',
            //'tx'       => 'Доп.информация',
            'tx' => 'Прим.',
            'username' => 'Автор',

            'sprwhelement_name' => 'sssssss',
            'sprwh' => 'sssssss',

            //                'SprwhName'=> 'НаимГруп',
            //                'SprwhTopName'=> 'НаимТовар',

            'sprwhName' => 'НаимГруп',
            'sprwhTopName' => 'НаимТовар',

            'sprwhelement_wh_cs_number.name' => 'НаимГруп',
            'sprwhTopName.name' => 'НаимГруп',


        ];

    }


    /**
     * setNext_max_id()
     * =
     *
     * @return int
     */
    public static function setNext_max_id()
    {
        $xx = static::find()->max('id');
        return ++$xx;
    }


    /**
     * Поиск по заданной базе
     * static::findOne($id)
     *
     * @param $id
     *
     * @return Sklad_delete
     * @throws ExitException
     */
    static function findModel($id)
    {
        if (($model = static::findOne($id)) !== null) {
            if (is_object($model)) {
                return $model;
            }
        }

        throw new ExitException('Sklad findModel( ' . $id . ' ). Этого нет ');
    }

    /**
     * @param int $id
     *
     * @return array|ActiveRecord|null
     */
    static function findModelAsArray($id = 0)
    {
        return Sklad::find()
            ->where(['id' => (int)$id])
            ->asArray()
            ->one();
    }


    /**
     * Возвращает массив номеров складов (БАЗ) по накладным
     * -
     *
     * @return array
     */
    static function ArraySklad_Uniq_wh_numbers()
    {
        return self::find()->distinct('wh_home_number');
    }


    /**
     * Возвращает массив номеров складов (БАЗ) по накладным
     * -
     *
     * @return array
     */
    static function ArraySklad_Uniq_cs_numbers()
    {
        return self::find()->distinct('wh_cs_number');
    }

    /**
     * Возвращает массив номеров CS после Указанной даты
     * =
     * @param $dt_create_timestamp
     * @return array
     */
    static function ArrayUniqCS_AfterDate($dt_create_timestamp)
    {
        return self::find()
            ->where(['>=', 'dt_create_timestamp', $dt_create_timestamp])
            ->distinct('wh_cs_number');
    }


}
