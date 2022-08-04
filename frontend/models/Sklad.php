<?php

namespace frontend\models;


use Exception;
use Yii;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveQuery;
use yii\mongodb\ActiveRecord;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * @property int id
 * @property int dt_create_timestamp
 * @property date dt_create
 * @property date dt_update
 *
 * @property int sklad_vid_oper
 * @property string sklad_vid_oper_name
 *
 * @property int wh_home_number
 * @property int wh_cs_number
 * @property int wh_dalee
 * @property int wh_dalee_element
 * @property int wh_destination
 * @property int wh_destination_element
 *
 * @property int wh_destination_element_cs
 *
 * @property int wh_debet
 * @property int wh_debet_top
 * @property int wh_debet_element
 *
 * @property int user_id
 * @property string user_name
 *
 * @property array array_tk_amort
 * @property array array_tk
 * @property array array_casual
 *
 *
 * @property string wh_dalee_name
 * @property string wh_dalee_element_name
 * @property string wh_destination_name
 * @property string wh_destination_element_name
 * @property string wh_debet_name
 * @property string wh_debet_element_name
 *
 * @property int dc_timestamp
 *
 * @property int array_count_all
 *
 *
 **/
class Sklad extends ActiveRecord
{
    public $count_xx; // Иногда использую счетчик в ЗАпросах SQL

    public $update_user_name; // ANALITICS

    public $add_copypast;
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

    const VID_NAKLADNOY_INVENTORY = '1';
    const VID_NAKLADNOY_PRIHOD = '2';
    const VID_NAKLADNOY_RASHOD = '3';
    const VID_NAKLADNOY_INVENTARIZACIA_ZALIVKA_STR = 'Заливка динамичная';
    const VID_NAKLADNOY_INVENTARIZACIA_STR = 'Инвентаризация';
    const VID_NAKLADNOY_PRIHOD_STR = 'Приходная накладная';
    const VID_NAKLADNOY_RASHOD_STR = 'Расходная накладная';

    const SCENARIO_NEW_CREATE = 'new_vreate';
//    const SCENARIO_UPDATE = 'update';

    const SCENARIO_MODAL_COPYPAST = 'modal_copypast';
    const SCENARIO_NEW_MODEL_FOR_STAT = 'model_stat';///
    const SCENARIO_ADMIN_HIDENREDACTOR = 'model_adm';///


    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios[self::SCENARIO_ADMIN_HIDENREDACTOR] = [
            'wh_home_number',
            'sklad_vid_oper',
            'dt_create',
            'dt_create_timestamp',
        ];
        $scenarios[self::SCENARIO_MODAL_COPYPAST] = [
            'add_copypast',
        ];
        $scenarios[self::SCENARIO_NEW_CREATE] = [
            'id',
            'wh_home_number',
            'sklad_vid_oper',
            'sklad_vid_oper_name',
            'dt_create',
            'dt_create_timestamp',
            'wh_debet_element',
            'wh_debet_element_name',

            'wh_debet_top',
            'wh_debet_name',

            'wh_dalee',
//            'wh_dalee_name',
            'wh_dalee_element',
//            'wh_dalee_element_name',
            'wh_cs_number',
            'wh_destination_element_cs',
        ];
        $scenarios[self::SCENARIO_NEW_MODEL_FOR_STAT] = [
            'id',
            'wh_home_number',

            'sklad_vid_oper',
            'sklad_vid_oper_name',
            'dt_create',
            'dt_create_timestamp',
            'dt_update_timestamp',

            'wh_debet_top',
            'wh_debet_name',

            'wh_debet_element',
            'wh_debet_element_name',

            'wh_destination',
            'wh_destination_name',

            "dt_to_work_signal",
            'array_tk_amort',
            'tx',
        ];
        $scenarios[self::SCENARIO_DEFAULT] = [
            '_id',
            'id',
            'flag',// Для обработки по всей Коллекции
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
//            'wh_destination_element_cs',

            'wh_cs_number',
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
            'update_points',// массив всех людей, которые сохраняли эту накладную когда-либо
        ];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'sklad',
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
            'flag',// Для обработки по всей Коллекции
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
            'update_points',// массив всех людей, которые сохраняли эту накладную когда-либо
        ];

    }

    /**
     * Создаю ТаймШТАМП для всех операций Создания накладных и Редактирования
     */
//    public function behaviors()
//    {
//        return [
//            'timestamp' => [
//                'class' => 'yii\behaviors\TimestampBehavior',
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt_update_timestamp'],
//                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dt_update_timestamp']
//                ]
//            ],
//        ];
//
//    }

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
            'dt_create_timestamp' => 'Дата созд.',
            'dt_update_timestamp' => 'Дата ред.',
            /// DOP Elements
            'dt_one_day' => 'Один день',
            'dt_start' => 'Начало',
            'dt_stop' => 'Окончание',
            'to_print' => 'На Печать',
            //'wh_top'           => 'Группа складов',
            //'wh_element'       => 'Склад',
            /// DOP Elements END
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
            'wh_dalee_element' => 'ЦС ПЕ',
            'wh_dalee_name' => 'Целевой склад',
            'wh_dalee_element_name' => 'ЦC ПЕ',
            'tz_user_edit_id' => 'tz_user_edit_id',
            'tz_user_edit_name' => 'tz_user_edit_name',
            'dt_to_work_signal' => 'dt_to_work_signal',
            'dt_deadline' => 'Крайний срок',
            'dt_transfered_date' => 'Дата передачи накладной',
            'dt_transfered_user_id' => 'ИД',
            'dt_transfered_user_name' => 'Получил',

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

            'update_user_name' => 'Ред.',

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
     * @return array
     */
    public function rules()
    {

        return [

            [[
                '_id', 'id', 'add_copypast',],
                'safe', 'on' => self::SCENARIO_MODAL_COPYPAST],

            [[
                'sklad_vid_oper',
                'dt_create',
                'dt_create_timestamp',
            ], 'safe', 'on' => self::SCENARIO_ADMIN_HIDENREDACTOR],


            [['sklad_vid_oper'], 'string', 'max' => 1],
            [['sklad_vid_oper'], 'in', 'range' => ['2', '3']],

            [['sklad_vid_oper_name'], 'string', 'max' => 30],

            [['id',], 'unique'],
            [['tx'], 'string', 'max' => 260],
            [['tx'], 'trim'],
            [['id', 'tz_id'], 'integer'],
            [['wh_home_number'], 'integer',
                'min' => 1,
                'message' => 'Id_Sklad == 0   ... ERROR!',
            ],

            // 'array_count_all',
            [
                'array_count_all',
                'default',
                'value' => 0,
            ],

            [
                [
                    'dt_create',
                    'dt_update',
                    'dt_transfered_date',
                ],
                'date',
                'format' => 'php:d.m.Y H:i:s',
            ],
            [
                'dt_create',
                'default',
                'value' => function () {
                    return date('d.m.Y H:i:s', strtotime('now'));
                },
            ],

            [
                'dt_update',
                'default',
                'value' => function () {
                    return date('d.m.Y H:i:s', strtotime('now'));
                },
            ],


            ///
            [
                [
                    'id',

                    'wh_home_number',
                    // ид текущего склада

                    'wh_cs_number',
                    // ид текущего склада

                    'wh_debet_top',
                    'wh_debet_element',
                    'wh_destination',
                    'wh_destination_element',

                    'wh_dalee',
                    'wh_dalee_name',
                    'wh_dalee_element',
                    'wh_dalee_element_name',

                    "tz_id",
                    "tz_name",
                    "tz_date",
                    "dt_to_work_signal",
                    "dt_deadline",
                    "dt_create",
                    "dt_create_timestamp",

                    'update_user_name',
                    'array_tk_amort',
                    'array_tk',
                    'array_casual',
                    'array_bus',
                    'bar_code',
                    'sprwhelement_name',
                    'sprwh',
                    'sprwhelement_wh_cs_number',
                    'sprwhTopName',
                ],
                'safe', 'on' => self::SCENARIO_DEFAULT
            ],


            ///
            [
                ['sklad_vid_oper'],
                //function ($attribute, $params) {
                function () {

                    if (!isset($this['wh_home_number'])) {
                        throw new NotFoundHttpException('SKLAD. wh_home_number. Не указан ИД склада/ Обязательный параметр ');
                    }

                    // ПРИВОДИМ источник, Получатель к единому  = Инвентаризация
                    if ((int)$this['sklad_vid_oper'] == 1) {

                        //ddd($this);


                        $array = Sprwhelement::findFullArray($this['wh_home_number']);
                        //'top' => ['id' => 14,'name' => 'City Bus ТОО']
                        //'child' => [ 'id' => 177, 'name' => '5001' ]

                        $this['wh_debet_top'] = $array['top']['id'];
                        $this['wh_debet_element'] = $array['child']['id'];

                        $this['wh_destination'] = $array['top']['id'];
                        $this['wh_destination_element'] = $array['child']['id'];
                    }


                    if ((int)$this['sklad_vid_oper'] == 2) {
                        //ddd($this);

                        $array = Sprwhelement::findFullArray($this['wh_home_number']);
                        //'top' => ['id' => 14,'name' => 'City Bus ТОО']
                        //'child' => [ 'id' => 177, 'name' => '5001' ]

                        $this['wh_destination'] = $array['top']['id'];
                        $this['wh_destination_element'] = $array['child']['id'];
                    }
                    if ((int)$this['sklad_vid_oper'] == 3) {
                        //ddd($this);

                        $array = Sprwhelement::findFullArray($this['wh_home_number']);
                        //'top' => ['id' => 14,'name' => 'City Bus ТОО']
                        //'child' => [ 'id' => 177, 'name' => '5001' ]

                        $this['wh_debet_top'] = $array['top']['id'];
                        $this['wh_debet_element'] = $array['child']['id'];
                    }
                },
            ],


            ///Замените склад Отправитель
            [
                ['wh_debet_element'],
                function ($attribute) {


                    if ((int)$this['wh_debet_element'] == (int)$this['wh_destination_element']) {
                        // Приходная накладная
                        if ((int)$this['sklad_vid_oper'] == 2) {
                            $this->addError(
                                $attribute,
                                //' Склад № ' . $this['wh_home_number'] . " , " .
                                //' Накладная № ' . $this['id'] . " , " .
                                'Замените склад Отправитель'
                            );
                        }
                    }


                    if (isset($this['wh_cs_number']) && (int)$this['wh_cs_number'] >
                        0)    // Не является ЦС (Целевым Складом)
                    {

                        ///РАСХОД ТОЛЬКО(!) ИЗ ЭТОГО СКЛАДА
                        if (
                            //                        $this[ 'wh_debet_element' ] != self::getSkladIdActive()
                            (int)$this['sklad_vid_oper'] == 3 && (int)$this['wh_home_number'] !=
                            (int)$this['wh_debet_element']
                        ) {

                            // ddd(23432);

                            //ddd($this); //1
                            //ddd($this['wh_debet_element']); //1
                            //ddd( self::getSkladIdActive() ); //1

                            $this->addError(
                                $attribute,
                                ' Склад № ' . $this['wh_home_number'] . " , " .
                                ' Накладная № ' . $this['id'] . " , " .
                                'Возможна ТОЛЬКО Операция РАСХОД. И склад отправитель только наш'
                            );
                        }
                    }


                    return $this['wh_debet_element'];
                },
            ],

            ///Замените склад Получатель
            [
                ['wh_destination_element'],
                function ($attribute) {

                    //            ddd($this);
                    if ((int)$this['wh_debet_element'] == (int)$this['wh_destination_element']) {
                        // Приходная накладная
                        if ((int)$this['sklad_vid_oper'] == 3) {
                            $this->addError(
                                $attribute,
                                //' Склад № ' . $this['wh_home_number'] . " , " .
                                //' Накладная № ' . $this['id'] . " , " .
                                'Замените склад Получатель'
                            );
                        }
                    }

                    ///РАСХОД ТОЛЬКО(!) С ЭТОГО СКЛАДА
                    if (
                        //                        (int)$this[ 'wh_debet_element' ] != self::getSkladIdActive() // Сейчас открыт Активным
                        //(int)$this['wh_debet_element_cs'] != 1  &&    // Не является ЦС (Целевым Складом)

                        // Не является ЦС (Целевым Складом)
                        (int)$this['sklad_vid_oper'] == 3 && (int)$this['wh_cs_number'] > 0 &&
                        (int)$this['wh_home_number'] == (int)$this['wh_destination_element']
                    ) {

                        // Расходная накладная
                        $this->addError(
                            $attribute,
                            ' Склад № ' . $this['wh_home_number'] . " , " .
                            ' Накладная № ' . $this['id'] . " , " .
                            'Замените склад Получатель. РАСХОД ТОЛЬКО(!) С АКТИВНОГО СЕЙЧАС СКЛАДА'
                        );
                    }

                    ///ПРИХОД ТОЛЬКО(!) НА ЭТОТ СКЛАД
                    if (
                        (int)$this['sklad_vid_oper'] == 2 && // Приход
                        (int)$this['wh_destination_element_cs'] != 1 && // Не является ЦС (Целевым Складом)
                        //(int)$this[ 'wh_destination_element' ] != self::getSkladIdActive() // Сейчас открыт Активным
                        (int)$this['wh_destination_element'] != (int)$this['wh_home_number']
                    ) {

                        //ddd($this);

                        $this->addError(
                            $attribute,
                            ' Склад № ' . $this['wh_home_number'] . " , " .
                            ' Накладная № ' . $this['id'] . " , " .
                            'Склад-получатель. ПРИХОД ТОЛЬКО(!) НА ЭТОТ = АКТИВНЫЙ СКЛАД'
                        );
                    }

                    return $this['wh_destination_element'];
                },
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
                'message' => 'Заполнить...', 'on' => self::SCENARIO_DEFAULT
            ],

            /// ЕСЛИ ЕЩЕ НЕ ЗАПОЛНИЛИСЬ !!!!
            [
                [
                    'sklad_vid_oper',
                    'dt_create',
                    'dt_create_timestamp',
                ],
                'required',
                'message' => 'Заполнить...', 'on' => self::SCENARIO_ADMIN_HIDENREDACTOR
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
            [['update_user_id'],
                function () {
                    $this->update_user_id = Yii::$app->user->identity->id;
                },
            ],
            [['update_user_name'],
                function () {
                    $this->update_user_name = Yii::$app->user->identity->username;
                },
            ],
            [['user_group_id'],
                function () {
                    $this->user_group_id = Yii::$app->user->identity->group_id;
                },
            ],


            [
                ['tz_id'],
                'default',
                'value' => 0,
            ],
            [
                ['wh_home_number', 'wh_cs_number'], 'integer',
            ],
            [
                [
                    //'array_tk_amort',
                    //'array_tk',
                    'array_casual',
                    'array_bus',
                ],
                'default',
                'value' => [],
            ],

            // normalize "phone" input
//            ['array_tk_amort', 'filter', 'filter' => function ($value) {
//
//                $array_rez = [];
//                foreach ($value as $key => $item) {
//                    ///
//                    $array_rez[] = $item;
//                    //
//                    if (is_float($item['ed_izmer_num'])) {
//
//                    }
//
//                    $num = $array_rez[$key]['ed_izmer_num']; //ed_izmer
//
//                    $array_rez[$key]['ed_izmer_num'] = 1;
//                }
//
//                ddd($value);
//                // normalize phone input here
//                return $value;
//            }],

//            [
//                ['array_tk_amort'],
//                function ($attribute) {
//                    ddd($this);
//
//                    //type_num ТИП ПОЛЯ
//                    $array_type = ArrayHelper::map(Spr_things::find()->all(), 'id', 'type_num');
//                }
//            ],


            /////
            /// Запрет на двойники. array_tk_amort
            ///
            [
                ['array_tk_amort'],
                function ($attribute) {
                    $err_str = '';
                    if (is_array($this->$attribute)) {
                        if (is_array($this['array_tk_amort'])) {

                            ///////////////////////
                            ///
                            ///  Проверка на двойники в массиве
                            $a = ArrayHelper::getColumn($this['array_tk_amort'], 'bar_code');

                            foreach ($a as $key => $item) {
                                /// Чистка пустышек
                                if (empty($item)) {
                                    unset($a[$key]);
                                }
                            }

                            /// Allsome
                            $unique = array_unique($a); // preserves keys
                            $diffkeys = array_diff_key($a, $unique);
                            $duplicates = array_unique($diffkeys);

                            if (count($duplicates) >= 1) {
                                $this->addError(
                                    $attribute,
                                    ' Склад № ' . $this['wh_home_number'] . " , " .
                                    ' Накладная № ' . $this['id'] . " , " .
                                    'Внимание! Содержит двойники: ' . implode(',', $duplicates)
                                );
                            }


                            foreach ($this['array_tk_amort'] as $key => $item) {


                                if (isset($item['bar_code']) && !empty($item['bar_code'])) {

                                    //if (strlen($item['bar_code']) < 5 || strlen($item['bar_code']) > 12) {
                                    if (strlen($item['bar_code']) < 5 || strlen($item['bar_code']) > 15) {
                                        $this->addError(
                                            $attribute,
                                            ' Склад № ' . $this['wh_home_number'] . " , " .
                                            ' Накладная № ' . $this['id'] . " , " .
                                            ' Строка №' . ++$key . ". "
                                            . ' Штрих-код должен содержать цифры длиной '
                                            . ' от 5 до 15'
                                            . ' (сейчас ' . strlen($item['bar_code']) . ')  '
                                        );
                                    } else {


                                        //  Получить МАССИВ для проверки в ПУЛе ШТРИХКОДОВ
                                        $aray_number_pool = Barcode_pool::getAray_number_pool($item['bar_code']);


                                        ///
                                        ///  СПИСАНИЕ! ЗАПРЕТ НА ПЕРЕМЕЩЕНИЕ!!!
                                        /// Разрешено только Админу для Ремонта
                                        ///
                                        if (Yii::$app->user->identity->group_id !=
                                            100) {
                                            if (isset($aray_number_pool['write_off']) &&
                                                (int)$aray_number_pool['write_off'] ==
                                                1) {
                                                $this->addError(
                                                    $attribute,
                                                    //' Склад № ' . $this['wh_home_number'] . " , " .
                                                    //' Накладная № ' . $this['id'] . " , " .
                                                    ' Строка №' . ++$key . ". "
                                                    . ' Штрих-код ' . $item['bar_code']
                                                    . ' -= С П И С А Н =- на основании: ' . $aray_number_pool['write_off_doc']
                                                    . ', по причине: ' . $aray_number_pool['write_off_note']
                                                );
                                            }
                                        }


                                        //	  'id' => 5024
                                        //    'element_id' => 1
                                        //    'bar_code' => '040062'

                                        if ($aray_number_pool['bar_code'] === $item['bar_code']) {
                                            if ($aray_number_pool['element_id'] !=
                                                $item['wh_tk_element']) {
                                                $this->addError(
                                                    $attribute,
                                                    ' Склад № ' . $this['wh_home_number'] . " , " .
                                                    ' Накладная № ' . $this['id'] . " , " .
                                                    ' Строка №' . ++$key . ". "
                                                    . ' Штрих-код ' . $item['bar_code']
                                                    . ' приписан ДРУГОМУ названию товара  ' . $aray_number_pool['element_id']
                                                    . ' ( в запросе ' . $item['wh_tk_element'] . ')  '
                                                );
                                            }
                                        } else {
                                            $this->addError(
                                                $attribute,
                                                ' Склад № ' . $this['wh_home_number'] . " , " .
                                                ' Накладная № ' . $this['id'] . " , " .
                                                ' Строка №' . ++$key . ". "
                                                . '[ ' . $item['bar_code']
                                                . ' ] Отсутствует в ПУЛе штрихкодов  '
                                            );
                                        }
                                    }
                                    /////////
                                }


                                if (isset($item['msam_code']) && !empty($item['msam_code']) &&
                                    (strlen($item['msam_code']) < 5 || strlen($item['msam_code']) >
                                        12)) {
                                    $this->addError(
                                        $attribute,
                                        ' Склад № ' . $this['wh_home_number'] . " , " .
                                        ' Накладная № ' . $this['id'] . " , " .
                                        ' Строка №' . ++$key . ". "
                                        . ' MSAM должен содержать цифры длиной '
                                        . ' от 5 до 12'
                                        . ' (сейчас ' . strlen($item['msam_code']) . ')  '
                                    );
                                }
                            }
                        }
                    }


                    /////
                    /// Запрет на двойники НАКЛАДНЫХ
                    /// не должно быть повторений как то:
                    /// 1. Штрихкод +
                    /// 2. Операция +
                    /// 3. для Одного ЦС
                    ///

                    $list_barcodes = self::List_barcodes_of_this($this['array_tk_amort']);


                    $list_id_plus = self::find_doubl_nakl_cs(
                        $list_barcodes,
                        self::VID_NAKLADNOY_PRIHOD,
                        $this['wh_debet_element'],
                        $this['dt_create_timestamp']
                    );


                    $list_id_minus = self::find_doubl_nakl_cs(
                        $list_barcodes,
                        self::VID_NAKLADNOY_RASHOD,
                        $this['wh_destination_element'],
                        $this['dt_create_timestamp']
                    );


                    ///2
                    if ((int)$this['sklad_vid_oper'] == 2) {
                        /// Двойники на ОТДАЧУ
                        if (isset($list_id_plus) && !empty($list_id_plus) && count($list_id_plus) > 1) {


                            foreach ($list_id_plus as $item) {
                                $err1['id'][] = $item['id'];
                                $err1['wh_home_number'][$item['wh_home_number']] = $item['wh_home_number'];
                                $err1['wh_debet_element'][] = $item['wh_debet_element'];
                                $err1['wh_destination_element'][] = $item['wh_destination_element'];
                            }

                            $err_str .= "\nДвойники накладных на ДЕМОНТАЖ из ЦС: " .
                                '____ Склад № ' . implode(', ', $err1['wh_home_number']) .
                                "____ Накладная № " . implode(', ', $err1['id']);
                        }
                    }

                    if ((int)$this['sklad_vid_oper'] == 3) {
                        /// Двойники на ПРИЕМ
                        if (isset($list_id_minus) && !empty($list_id_minus) && count($list_id_minus) > 1) {

                            foreach ($list_id_minus as $item) {
                                $err['id'][] = $item['id'];
                                $err['wh_home_number'][$item['wh_home_number']] = $item['wh_home_number'];
                                $err['wh_debet_element'][] = $item['wh_debet_element'];
                                $err['wh_destination_element'][] = $item['wh_destination_element'];
                            }

                            $err_str .= "\nДвойники накладных на УСТАНОВКУ в ЦС: " .
                                '____ Склад № ' . implode(', ', $err['wh_home_number']) .
                                "____ Накладная № " . implode(', ', $err['id']);
                        }
                    }

                    if (!empty($err_str)) {
                        $this->addError(
                            $attribute,
                            $err_str
                        );
                    }
                },
            ],

            /////
            /// Запрет на двойники. array_tk
            ///
            [
                ['array_tk'],
                function ($attribute) {
                    //ddd($attribute);

                    $spr_glob = ArrayHelper::map(Spr_glob::find()->all(), 'id', 'name');
                    $spr_glob_element = ArrayHelper::map(Spr_glob_element::find()->all(), 'id', 'name');

//                    ddd($spr_glob_element);
//                    ddd($spr_glob);


                    if (is_array($this->$attribute) && is_array($this['array_tk'])) {

                        //ddd($this['array_tk']);

                        foreach ($this['array_tk'] as $key1 => $item1) {
                            $array_1[$key1] = [
                                'wh_tk' => $item1['wh_tk'],
                                'wh_tk_element' => $item1['wh_tk_element'],
                                'name_tk' => (isset($item1['name_tk']) ? $item1['name_tk'] : ''),
                                'name_tk_element' => (isset($item1['name_tk_element']) ? $item1['name_tk_element'] : ''),
                                'ed_izmer_num' => $item1['ed_izmer_num'],
                                'name_ed_izmer' => (isset($item1['name_ed_izmer']) ? $item1['name_ed_izmer'] : ''),
                                'ed_izmer' => $item1['ed_izmer'],
                                //'take_it' => $item1[ 'take_it' ],
                            ];
                        }

                        //ddd($array_1);
                        /// Удаляет двойники из массива
                        $array_err = [];
                        $buf_array['wh_tk_element'] = 0;

                        foreach ($array_1 as $item1) {
                            ///
                            /// Проверяем
                            ///  Только на номер Элемента
                            if (
                                isset($item1['wh_tk_element']) && (int)$buf_array['wh_tk_element'] !=
                                (int)$item1['wh_tk_element']
                            ) {

                                $array_2[] = [
                                    'wh_tk' => $item1['wh_tk'],
                                    'wh_tk_element' => $item1['wh_tk_element'],
                                    //'name_tk'         => $spr_glob[ $item1[ 'name_tk' ] ],
                                    //'name_tk_element' => $spr_glob_element[ $item1[ 'name_tk_element' ] ],
                                    //'name_ed_izmer' => 'шт',
                                    'ed_izmer_num' => $item1['ed_izmer_num'],
                                    'ed_izmer' => $item1['ed_izmer'],
                                    //                                    'take_it' => $item1[ 'take_it' ],
                                ];
                            } else {

//                                ddd($item1);
//                                ddd(111);

                                $array_err[] = [
                                    'wh_tk' => $item1['wh_tk'],
                                    'wh_tk_element' => $item1['wh_tk_element'],
                                    'name_tk' => $spr_glob[$item1['wh_tk']],
                                    'name_tk_element' => (isset($spr_glob_element[$item1['wh_tk_element']]) ? $spr_glob_element[$item1['wh_tk_element']] : ''),
                                    'ed_izmer_num' => $item1['ed_izmer_num'],
                                ];
                                $array_2 = [];
                            }


                            //								ddd($array_err);
                            //								ddd($array_2);

                            unset($buf_array);
                            $buf_array = (array)$item1;
                        }


                        $this['array_tk'] = $array_2;


                        //							ddd($array_2);
                        //							ddd($array_err);


                        if (isset($array_err) && !empty($array_err)) {

                            $str = [];
                            foreach ($array_err as $item_err) {
                                //ddd( $item_err );

                                $str[] = '[ Двойники: '
                                    . ' ' . $item_err['name_tk']
                                    . ' - ' . $item_err['name_tk_element']
                                    . ' = ' . $item_err['ed_izmer_num']
                                    //									         . ' ' . $item_err[ 'name_ed_izmer' ]
                                    . ' ]';
                            }

                            $this->addError(
                                $attribute,
                                implode(", ", $str)
                            );
                        }
                    }
                },
            ],


            /// Инициализация update_points
            /// START
            [
                'update_points',
                'default',
                'value' => function () {
                    $xx[] = [
                        'dt_update' => date('d.m.Y H:i:s', strtotime('now')),
                    ];
                    return $xx;
                },
            ],

            /// Запись ИСТОРИИ
            ///  Инициализация update_points
            /// JOI
            [
                ['update_points'], 'record_history'
            ],


            ///
            /// НУЛЕВЫЕ ОСТАТКИ. Подсчет налету. Запрет на создание накладной.
            ///
//            [
//                ['array_tk_amort'],
//                function ($attribute) {
//
//                    //!!! Если это ПРИХОД-ная накладная, то НЕ продолжаем проверку
//                    if ((int)$this->sklad_vid_oper == 2) {
//                        return true;
//                    }
//
//                    $err = [];
//                    $err_string = '';
//
//                    $list_els = self::List_elements_of_this($this->array_tk_amort);
//                    //ddd($list_els);
//
//
//                    /// Две ветки
//                    /// Первая для не имеющих ШТРИХКОД
//                    /// ВТОРАЯ по Штрихкоду
//
//                    ///
//                    /// nobarcode
//                    ///
//                    if (!empty($list_els['nobarcode'])) {
//                        ///
//                        /// ПЕРВАЯ
//                        foreach ($list_els['nobarcode'] as $el => $sum) {
//
//                            //ddd($this['wh_home_number']);
//
//                            /// Остатки в ИНВЕНТАРИЗАЦИИ
//                            $rest_of_inventary = Sklad_wh_invent::Last_inventory_thing($this['wh_home_number'], $el);
//
//                            //
//                            $nn = self::Rest_of_one_id($this->wh_home_number, $el, $sum);
//
//                            // Сводить БАЛЛАНС по приходу и расходу
//                            $array = self::Summ_prihod_rashod($nn);
//
//                            ///Справочная информация
//                            $spr_full = Spr_globam_element::findFullArray($el);
//
//                            // Если Расходы больше Приходов и Инвентаризации
//                            if (isset($rest_of_inventary) && isset($array[2][$el]) && isset($array[3][$el]) &&
//                                (($rest_of_inventary + $array[2][$el]) < $array[3][$el])) {
//                                $err[] = $spr_full['top']['name'] . ' - ' . $spr_full['child']['name'];
//                            }
//
//                            // Если пуст Приход
//                            if (isset($rest_of_inventary) && !isset($array[2][$el]) && isset($array[3][$el]) &&
//                                $rest_of_inventary < $array[3]) {
//                                $err[] = $spr_full['top']['name'] . ' - ' . $spr_full['child']['name'];
//                            }
//
//                        }
//                    }
//                    //ddd($err);
//
//                    ///
//                    /// barcode
//                    ///
//                    if (!empty($list_els['barcode'])) {
//                        ///
//                        /// ВТОРАЯ
//                        foreach ($list_els['barcode'] as $el => $bc) {
//                            //ddd($bc); //'041600'
//                            $nn = self::Rest_of_barcode($this->wh_home_number, $bc);
//
//                            ///  Схлопывание  ///  Один к Одному
//                            $count_prih = (isset($array[2]) ? count($array[2]) : 0);
//                            $count_rash = (isset($array[3]) ? count($array[3]) : 0);
//
//
//                            // Если они пустые ОБА
//                            if ($count_prih >= $count_rash) {
//                                $err[] = $bc;
//                            }
//                        }
//
//                    }
//
//
//                    //
//                    if (!empty($err)) {
//                        $this->addError(
//                            $attribute,
//                            'Внимание! На складе нет:' . "\n\r" . implode(", ", $err)
//                        );
//                    }
//
//                    return true;
//                }],


        ];
    }


    /**
     *  Остаки  на этом складе по ОДНОЙ ПОЗИЦИИ ТОВАРА.  Штрихкод.
     * =
     * @param $array
     * @return array
     */
//    static function Slice_rest($array)
//    {
//        $rez = [];
//        if (!empty($array[2])) {
//
//            $count_prih=count($array[2]);
//            ddd($count_prih);
//
//            //Приходы
//            foreach ($array[2] as $key_prs => $prihods) {
//                foreach ($prihods as $key_pr => $prihod) {
//
//                    if (isset($array[3])) {
//                        ///Расходы
//                        foreach ($array[3] as $key_rashs => $rashods) {
//                            foreach ($rashods as $key_rash => $rashod) {
//
//
//
//
//
////                            dd($rashod);
////                            dd($prihod);
//
////                            if(!isset($rashod['wh_tk_element']) || !isset($prihod['wh_tk_element'])){
////                                dd(111);
////                                dd($array[$key_prs]);
////                                dd($prihods);
////                                dd(21);
////                                dd($array[$key_rashs]);
////                                dd($rashods);
////                                ddd(31);
////
////                            }
//
//                                ///
//                                if ((int)$prihod['wh_tk_element'] === (int)$rashod['wh_tk_element']) {
//
//                                    unset($array[2][$key_prs]);
//                                    unset($array[3][$key_rashs]);
//                                    continue;
//
//                                }
//
////                                ddd($array);
//
//                            }
//                        }
//                    }
//
//
//                }
//            }
//        }
//
//        return $array;
//    }

    /**
     * findModelAsArray
     * =
     * @param int $id
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
     * findModelAsArray (!)только содержимое  массива  array_tk_amort (!)
     * =
     * @param int $id
     * @return array|ActiveRecord|null
     */
    static function findArray_tk_amort($id = 0)
    {
        $arraY = Sklad::find()
            ->select(['id', 'array_tk_amort'])
            ->where(['id' => (int)$id])
            ->asArray()
            ->one();

        $array_rez = $arraY['array_tk_amort'];
        return $array_rez;
    }


    /**
     * findModelAsArray (!)только содержимое  массива  array_tk_amort (!) ArrayHelper:map(code code)
     * =
     * @param int $id
     * @return array|ActiveRecord|null
     */
    static function findArray_amort_double($id = 0)
    {
        $arraY = Sklad::find()
            ->select(['id', 'array_tk_amort'])
            ->where(['id' => (int)$id])
            ->asArray()
            ->one();

        $array_tk_amort = $arraY['array_tk_amort'];
        foreach ($array_tk_amort as $item) {
            $array_rez[$item['bar_code']] = $item['bar_code'];
        }
        return $array_rez;
    }

    /**
     * findModelAsArray (!)только содержимое  массива  array_tk_amort (!) ArrayHelper:map(code code)
     * =
     * @param int $id
     * @return array|ActiveRecord|null
     */
    static function findArray_ap_pe($id = 0)
    {
        $array_rez = Sklad::find()
            ->select(['id', 'wh_dalee', 'wh_dalee_element'
                // 'array_tk_amort'
            ])
            ->where(['id' => (int)$id])
            ->asArray()
            ->one();
        return $array_rez;
    }

    /**
     * Подсчет строк, содержащих Баркод
     * =
     * @param int $id
     * @return int
     */
    static function summPos_of_barcodes($id = 0)
    {
        $arraY = Sklad::find()
            ->select(['id', 'array_tk_amort'])
            ->where(['id' => (int)$id])
            ->asArray()
            ->one();
        $xx = 0;
        $array_tk_amort = $arraY['array_tk_amort'];
        foreach ($array_tk_amort as $item) {
            $array_rez[$item['bar_code']] = $item['bar_code'];
            $xx++;
        }
        return $xx;
    }

    /**
     * Получить список Баркодов из массива-накладной
     * @param $array
     * @return array
     */
    static function List_barcodes_of_this($array)
    {
        $list_array = [];
        foreach ($array as $item) {
            if (!empty($item['bar_code'])) {
                $list_array[] = $item['bar_code'];
            }
        }
        return $list_array;
    }

    /**
     * @param $array
     * @return array
     */
    static function List_barcodes_barcodes_of_this($array)
    {
        $list_array = [];
        foreach ($array as $item) {
            if (!empty($item['bar_code'])) {
                $list_array[(string)$item['bar_code']] = $item['bar_code'];
            }
        }
        return $list_array;
    }

    /**
     * ПОИСК полного Двойника=Накладной
     * =
     *
     * Запрет на двойники НАКЛАДНЫХ
     * не должно быть повторений как то:
     *  1. Штрихкод +
     *  2. Операция +
     *  3. для Одного ЦС
     *  4. именно ЦС (!)
     *
     * @param array $array_barcodes
     * @param $vid_oper
     * @param $cs_id
     * @param $dt_create_timestamp
     * @return mixed
     */
    public static function find_doubl_nakl_cs($array_barcodes, $vid_oper, $cs_id, $dt_create_timestamp)
    {
        if ($vid_oper == self::VID_NAKLADNOY_PRIHOD) {
            return static::find()
                ->select(['id', 'wh_home_number', 'wh_debet_element', 'wh_destination_element', 'dt_create_timestamp'])
                ->where(
                    ['AND',
                        ['IN', 'array_tk_amort.bar_code', $array_barcodes],
                        ['==', 'sklad_vid_oper', (string)$vid_oper],
                        ['==', 'wh_cs_number', $cs_id],
                        ['==', 'wh_debet_element', $cs_id],
                        ['>=', 'dt_create_timestamp', strtotime($dt_create_timestamp . '-1 day')],
                        ['<=', 'dt_create_timestamp', strtotime($dt_create_timestamp . '+1 day')]
                    ])
                ->orderBy('id')
                ->asArray()
                ->all();
        }
        if ($vid_oper == self::VID_NAKLADNOY_RASHOD) {
            return static::find()
                ->select(['id', 'wh_home_number', 'wh_debet_element', 'wh_destination_element', 'dt_create_timestamp'])
                ->where(
                    ['AND',
                        ['IN', 'array_tk_amort.bar_code', $array_barcodes],
                        ['==', 'sklad_vid_oper', (string)$vid_oper],
                        ['==', 'wh_cs_number', $cs_id],
                        ['==', 'wh_destination_element', $cs_id],
                        ['>=', 'dt_create_timestamp', strtotime($dt_create_timestamp . '-1 day')],
                        ['<=', 'dt_create_timestamp', strtotime($dt_create_timestamp . '+1 day')]
                    ])
                ->orderBy('id')
                ->asArray()
                ->all();
        }
        return [];
    }

    /**
     * Отпечаток Пользовательских данных.
     * -
     * Исторические данные в накладных
     * -
     *
     * @return bool
     */
    public function record_history()
    {
        $xx = $this->update_points;
        //        ddd(Yii::$app->user->identity);

        // ddd($xx);

        $xx[] = [
            'dt_update' => date('d.m.Y H:i:s', strtotime('now')),
            'dt_update_timestamp' => strtotime('now'),
            'user_ip' => Yii::$app->request->getUserIP(),
            'user_id' => Yii::$app->user->identity->id,
            'user_name' => Yii::$app->user->identity->username,
            'user_group_id' => Yii::$app->user->identity->group_id,
            'user_role' => Yii::$app->user->identity->role,
        ];

        $this->update_points = $xx;
        return true;
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
     * ПОИСК полного Двойника=Накладной
     * =
     *
     * @param $para_date
     * @param $para_akt
     * @param $wh_home_number
     * @param $para_vid
     * @param $para_barcode
     * @return bool
     */
    public static function findDoubles_many_parameters($para_date, $para_akt, $wh_home_number, $para_vid, $para_barcode)
    {
        // Подготовим две переменные даты dt_start && dt_stop
        $dt_start_timestamp = date('d.m.Y 00:00:00', strtotime($para_date));
        $dt_stop_timestamp = date('d.m.Y 23:59:59', strtotime($para_date));
        // Подготовим две переменные даты dt_start && dt_stop FORMAT = Timestamp
        $dt_start_timestamp = strtotime($dt_start_timestamp);
        $dt_stop_timestamp = strtotime($dt_stop_timestamp);

        ////              Акт демонтажа
        if ($para_vid === 'Акт демонтажа') {
            $xx = static::find()
                ->where(
                    ['AND',
                        ["wh_home_number" => $wh_home_number],
                        ['>=', "dt_create_timestamp", $dt_start_timestamp],
                        ['<=', "dt_create_timestamp", $dt_stop_timestamp],
                        ['like', "tx", $para_akt],//1221
                        ['=', "array_tk_amort.bar_code", $para_barcode],
//                                ['=','wh_debet_element_name',$para_gos_or_bort],
                    ]
                )
                ->asArray()
                ->one();

            // ddd($xx);
        }

        ////
        if ($para_vid === 'Акт монтажа') {
            $xx = static::find()
                ->where(
                    ['AND',
                        ['>=', "dt_create_timestamp", $dt_start_timestamp],
                        ['<=', "dt_create_timestamp", $dt_stop_timestamp],
                        ['like', "tx", $para_akt],
                        ['=', "array_tk_amort.bar_code", $para_barcode],
//                                ['=','wh_destination_element_name',$para_gos_or_bort],
                    ]
                )
                ->asArray()
                ->one();
        }


        if (isset($xx) && !empty($xx)) {
            // Нашел
            return true;
        }
        // No
        return false;

    }

    /**
     * ПОИСК полного Двойника=Накладной ПРИ УСЛОВИИ, ЧТО ЭТО устройство не баркодовое НЕ ИНТЕЛЕГЕНТ
     * =
     *
     * @param $para_date
     * @param $para_akt
     * @param $wh_home_number
     * @param $para_vid
     * @param $para_barcode
     * @return bool
     */
    public static function findDoubles_many_parameters_without_barkode($para_date, $para_akt, $wh_home_number, $para_vid,  $str_name )
    {

        // Подготовим две переменные даты dt_start && dt_stop
        $dt_start_timestamp = date('d.m.Y 00:00:00', strtotime($para_date));
        $dt_stop_timestamp = date('d.m.Y 23:59:59', strtotime($para_date));
        // Подготовим две переменные даты dt_start && dt_stop FORMAT = Timestamp
        $dt_start_timestamp = strtotime($dt_start_timestamp);
        $dt_stop_timestamp = strtotime($dt_stop_timestamp);

        ////              Акт демонтажа
        if ($para_vid === 'Акт демонтажа') {
            $xx = static::find()
                ->where(
                    ['AND',
                        ["wh_home_number" => $wh_home_number],
                        ['>=', "dt_create_timestamp", $dt_start_timestamp],
                        ['<=', "dt_create_timestamp", $dt_stop_timestamp],
                        ['like', "tx", $para_akt],//1221
                        ['==', "array_tk_amort.name", $str_name],
                        ['==', "array_tk_amort.intelligent", "0"],
                    ]
                )
                ->asArray()
                ->one();

            // ddd($xx);
        }

        ////
        if ($para_vid === 'Акт монтажа') {
            $xx = static::find()
                ->where(
                    ['AND',
                        ['>=', "dt_create_timestamp", $dt_start_timestamp],
                        ['<=', "dt_create_timestamp", $dt_stop_timestamp],
                        ['like', "tx", $para_akt],
                        ['==', "array_tk_amort.name", $str_name],
                        ['==', "array_tk_amort.intelligent", "0"],
                    ]
                )
                ->asArray()
                ->one();
        }


        if (isset($xx) && !empty($xx)) {
            // Нашел
            return true;
        }
        // No
        return false;

    }

    /**
     * Связка с Таблицей  Sprwhelement
     * -
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_home_number()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_home_number']);

    }

    /**
     * Связка с Таблицей  Sprwhelement для ПОЛЯ wh_dalee   ТОП
     *=
     * @return ActiveQueryInterface
     */
    public function getSprwhtop_wh_dalee()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'wh_dalee']);

    }

    /**
     * Связка с Таблицей  Sprwhelement для ПОЛЯ wh_dalee_element
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_wh_dalee_element()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_dalee_element']);

    }

    /**
     * Связка с Таблицей  Sprwhelement
     * gj
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_cs_number()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_cs_number']);

    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_wh_cs_number()
    {
        return $this->hasMany(Sprwhelement::className(), ['id' => 'wh_cs_number']);

    }

    public function getSprwhTopName()
    {
        return $this->hasOne(Sprwhtop::className(), ['parent_id' => 'id'])
            ->via('sprwhelement_wh_cs_number'); // Имя связи которая объявлена выше

    }

    /**
     * Связка с Таблицей  Sprwhelement
     * =
     * по полю wh_dalee
     * -
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_dalee()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'wh_dalee']);

    }

    /**
     * Связка с Таблицей  Sprwhelement
     * =
     * по полю wh_dalee_element
     * -
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_dalee_element()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_dalee_element']);

    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_debet()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'wh_debet_top']);

    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_debet_element()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_debet_element']);

    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_destination()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'wh_destination']);

    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhtop_destination()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'wh_destination']);

    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_destination_element()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_destination_element']);

    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSpr_things()
    {
        return $this->hasOne(Spr_things::className(), ['id' => 'array_tk.ed_izmer']);

    }

    /**
     * Пробую создавать псевдо поля с переформатированием
     * /// То самое преобразование ПОЛЯ Милисукунд
     * РАБОТАЕТ!
     * -
     *
     * @return false|string
     */
    public function getDtCreateText()
    {
        return date('d.m.Y H:i:s', $this->dt_create_timestamp);

    }

    /**
     *  Пробую создавать псевдо поля с переформатированием
     * /// То самое преобразование ПОЛЯ Милисукунд
     * РАБОТАЕТ!
     * -
     *
     * @param $value
     */
    public function setDtCreateText($value)
    {
        $this->dt_create_timestamp = strtotime($value);

    }

    /**
     * Поиск по заданной базе
     * static::findOne($id)
     *
     * @param $id
     * @return Sklad|null
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
     * Поиск НАКЛАДНОЙ в своем складе
     * =
     * для слияния ее к существующей в виде Массива
     * -
     *
     * @param $wh_home_number
     * @param $id
     * @return array|ActiveRecord|null
     */
    static function findArray_by_id_into_sklad($wh_home_number, $id)
    {
        return static::find()
            ->where(
                [
                    'AND',
                    [
                        'OR',
                        ['wh_home_number' => (string)$wh_home_number],
                        ['wh_home_number' => (int)$wh_home_number],
                    ],
                    ['id' => (int)$id],
                ]
            )
            ->asArray()
            ->one();

    }

    /**
     * Поиск НАКЛАДНОЙ в своем складе
     * =
     * для слияния ее к существующей в виде Массива
     * -
     *
     * @param $model_base
     * @param $model_next
     * @return mixed
     */
    static function AddNaklad_to_Naklad($model_base, $model_next)
    {
        ///
        ///  На входе два массива = array_tk_amort
        ///  (Таблицы товаров из накладной)

        if (!isset($model_base) || empty($model_base) || !is_array($model_base)) {
            $model_base = [];
        }
        if (!isset($model_next) || empty($model_next) || !is_array($model_next)) {
            $model_next = [];
        }

//        ddd($model_next);
//        ddd($model_base);

        foreach ($model_base as $key => $item) {

            $model_base_parce[] = [
                (isset($item['wh_tk_amort']) ? (int)$item['wh_tk_amort'] : ''),
                (int)$item['wh_tk_element'],
                (isset($item['wh_tk']) ? (int)$item['wh_tk'] : ''),
                (int)$item['wh_tk_element'],
                (int)$item['ed_izmer_num'],
                'para' => $item,
            ];
        }
        // ddd($model_base_parce);

        foreach ($model_next as $key => $item) {
            $model_base_parce[] = [
                (isset($item['wh_tk_amort']) ? (int)$item['wh_tk_amort'] : ''),
                (int)$item['wh_tk_element'],
                (isset($item['wh_tk']) ? (int)$item['wh_tk'] : ''),
                (int)$item['wh_tk_element'],
                (int)$item['ed_izmer_num'],
                'para' => $item,
            ];

            //$model_next_parce[ $item[ 'wh_tk_amort' ] ][ $item[ 'wh_tk_element' ] ][] = $item;
        }

        if (empty($model_base_parce)) {
            return [];
        };


        asort($model_base_parce);

        foreach ($model_base_parce as $key => $item) {
            $array[] = $item['para'];
        }

        return $array;

    }

    /**
     * Жадная ЗАГРУЗКА ОБЪЕКТ-МОДЕЛЬ (универсальная)
     * -
     *
     * @param $sprwhelement
     * @return ActiveQuery
     * @throws ExitException
     */
    static function find_with($sprwhelement)
    {
        if (($model = static::find()->with($sprwhelement)) !== null) {
            if (is_object($model)) {
                return $model;
            }
        }

        throw new ExitException('Sklad 1 -   Ответ на запрос. Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад');

    }

    /**
     * Возвращает массив номеров складов (БАЗ) по накладным
     * -
     *
     * @return array
     */
    static function ArraySklad_Uniq_wh_numbers()
    {
        return self::find()
            ->distinct('wh_home_number');

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
     *
     * @param $dt_create_timestamp
     * @return array
     */
    static function ArrayUniqCS_AfterDate($dt_create_timestamp)
    {
        return self::find()
            ->where(['>=', 'dt_create_timestamp', $dt_create_timestamp])
            ->distinct('wh_cs_number');

    }

    /**
     * Для инвентаризации
     * Создавем список активных ИД (Стартовых Игнвентаризаций)
     *
     * @return array
     */
    static function Array_inventory_ids()
    {
        return Sklad_inventory::find()->distinct('wh_home_number');

    }

    /**
     * $xx2[$item]=$item;
     */
    static function ArraySklad_Uniq_wh_numbers_plus_id()
    {
        $xx = self::find()->distinct('wh_home_number');

        foreach ($xx as $item) {
            $xx2[$item] = $item;
        }

        return $xx2;

    }

    /**
     * Для Админа.
     * Видит все склады фирмы
     * -
     *  Array [id, name]
     *
     * @return array
     */
    static function ArraySklad_Uniq_wh_numbers_all()
    {
        $xx = self::find()->distinct('wh_home_number');

        $xx2 = [0];
        if (isset($xx) && !empty($xx)) {
            foreach ($xx as $item) {
                $xx2[$item] = $item;
            }
        }

        return ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->asArray()
                ->where(['id' => $xx2])
                ->all(),
            'id', 'name'
        );

    }

    /**
     * Запрос в ВШЭ По заданному массиву ИД-шников
     * Для выпадаюшего списка картик Select2  (не мульти)
     * =
     *
     * @param $input_array
     * @return array
     */
    static function ListFromInput_array_sprwhelement($input_array)
    {

        return [0 => 'Выбрать все'] + ArrayHelper::map(
                Sprwhelement::find()
                    ->select(
                        [
                            'id',
                            'name',
                        ]
                    )
                    ->orderBy('name')
                    ->asArray()
                    ->where(['id' => $input_array])
                    ->all(),
                'id', 'name'
            );

    }

    /**
     * УНИВЕРСАЛЬНАЯ ФУНКЦИЯ. Похожа на прошлую, Но получает любую таблицу в параметрах
     * =
     *
     * @param $input_array
     * @return array
     */
    static function ListFromInput_array_sklad($input_array)
    {

        //ddd($input_array);

        return ([
                '' => '...',
                0 => 'Нет выбора',
            ] + ArrayHelper::map(
                Sklad::find()
                    ->select(
                        [
                            'id',
                            'user_name',
                        ]
                    )
                    ->orderBy('user_name')
                    ->asArray()
                    ->where(['user_name' => $input_array])
                    ->all(),
                'user_name', 'user_name'
            )
        );

    }

    /**
     * Для Админа.
     * Видит все склады фирмы, кроме тех, которых нет в этом промежутке дат
     * -
     *  Array [id, name]
     *
     * @param $dt_start
     * @param $dt_stop
     * @return array
     */
    static function ArraySklad_Uniq_time($dt_start, $dt_stop)
    {
        $dt_start = strtotime($dt_start);
        $dt_stop = strtotime($dt_stop);


        $xx = self::find()
            ->where(
                [
                    'AND',
                    [
                        '>=',
                        'dt_create_timestamp',
                        $dt_start,
                    ],
                    [
                        '<=',
                        'dt_create_timestamp',
                        $dt_stop,
                    ],
                ]
            )
            ->distinct('wh_home_number');


        $xx2 = [0];
        if (isset($xx) && !empty($xx)) {
            foreach ($xx as $item) {
                $xx2[$item] = $item;
            }
        }

        return ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->asArray()
                ->where(['id' => $xx2])
                ->orderBy(['name'])
                ->all(),
            'id', 'name'
        );

    }

    /**
     * ЦС. Для Админа.
     * -
     * Видит все склады фирмы, кроме тех, которых нет в этом промежутке дат
     * -
     *  Array [id, name]
     *
     * @param $dt_start
     * @param $dt_stop
     * @return array
     */
    static function ArraySklad_cs_Uniq_time($dt_start, $dt_stop)
    {


        $dt_start = (int)strtotime($dt_start);
        $dt_stop = (int)strtotime($dt_stop);


        $xx = Sklad::find()
            ->where(
                [
                    'AND',
                    [
                        '>=',
                        'dt_create_timestamp',
                        $dt_start,
                    ],
                    [
                        '<=',
                        'dt_create_timestamp',
                        $dt_stop,
                    ],
                    [
                        '>',
                        'wh_cs_number',
                        0,
                    ],
                ]
            )
            ->distinct('wh_cs_number');


        //635


        return ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->asArray()
                ->where(['id' => $xx])
                ->orderBy(['name'])
                ->all(),
            'id', 'name'
        );

    }

    /**
     * Список выборочно по ЦС.
     * ЦС. Для Админа. Видит все  CS склады фирмы
     * Все АйДи ЦелевыхСкладов взяты из рабочих накладных по фирме
     * =
     *  Array [id, name]
     *
     * @return array
     */
    static function ArraySklad_cs_all()
    {
        // По Складу. В накладных ищем любые номера ЦС.
        $xx = self::find()
            ->where(
                [
                    'AND',
                    [
                        '>',
                        'wh_cs_number',
                        0,
                    ],
                ]
            )
            ->distinct('wh_cs_number');

        // Тепер они же - По Справочнику
        return ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->asArray()
                ->where(['id' => $xx])
                ->orderBy(['name'])
                ->all(),
            'id', 'name'
        );

    }

    /**
     *  В построении участвуют все представленные в движении  АйДи ЦелевыхСкладов
     * =
     * Все АйДи ЦелевыхСкладов взяты из рабочих накладных по фирме
     * =
     * только массив Ид - ЦС
     *  Array [id]
     *
     * @return array
     */
    static function ArraySklad_cs_all_id_only()
    {
        return self::find()
            ->where(['>', 'wh_cs_number', 0])
            ->distinct('wh_cs_number');

    }

    /**
     * Для Администратора Склада.
     * Видит только СВОИ склады
     * -
     *  Array [id, name]
     *
     * @return array
     */
    static function ArraySklad_Uniq_wh_numbers_plus_id_name()
    {
        $xx = Yii::$app->getUser()->identity->getUserSklad();

        $xx2 = [0];
        if (isset($xx) && !empty($xx)) {
            foreach ($xx as $item) {
                $xx2[$item] = $item;
            }
        }

        return ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->asArray()
                ->where(['id' => $xx2])
                ->all(),
            'id', 'id'
        );

    }

    /**
     * Для САНЖАРА.
     * Видит все склады ПЕРЕВОЗЧИКА (ЦС)
     * =
     * final_destination=1
     * -
     *  Array [id, name]
     *
     * @return array
     */
    static function ArraySklad_Uniq_wh_numbers_all_final_destination()
    {
        $xx = self::find()
            ->distinct('wh_home_number');

        $xx2 = [0];
        if (isset($xx) && !empty($xx)) {
            foreach ($xx as $item) {
                $xx2[$item] = $item;
            }
        }

        return ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->asArray()
                ->where(
                    [
                        'AND',
                        ['final_destination' => 1],
                        ['id' => $xx2],
                    ]
                )
                ->orderBy(['name'])
                ->all(),
            'id', 'name'
        );

    }

    /**
     * Перевозчик (Униккально-с номером)
     * -
     *
     * @return array
     */
    static function ArraySklad_Uniq_transporter()
    {
        return ArrayHelper::map(
            Sprwhtop::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy('name')
                ->asArray()
                //->where(['id'=>$xx2])
                ->all(),
            'id', 'name'
        );

    }

    /**
     * Список CS. Перевозчики. Для фильтра
     * -
     *
     * @return array
     */
    static function Array_cs()
    {
        return ArrayHelper::map(Sprwhtop::find()
            ->select(['id', 'name',])
            ->where(
                ['OR',
                    ['==', 'final_destination', (string)1],
                    ['==', 'final_destination', (int)1],
                ]
            )
            ->orderBy('name')
            ->asArray()
            ->all(), 'id', 'name');
    }

    /**
     * Список WH.  Для фильтра
     * -
     *
     * @return array
     */
    static function Array_wh()
    {
        return ArrayHelper::map(Sprwhtop::find()
            ->select(['id', 'name',])
            ->where(['<>', 'final_destination', (int)1])
            ->orderBy('name')
            ->asArray()
            ->all(), 'id', 'name');

    }

    /**
     * ELEMENT BY ID
     *
     * @param $parent_id
     * @return array
     */
    static function Array_cs_element_by_id($parent_id)
    {

        return ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->where(
                    ['AND',
                        ['final_destination' => 1],
                        ['==', 'parent_id', (int)$parent_id]
                    ]
                )
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'name'
        );

    }

    /**
     * Список CS. Перевозчики. Для фильтра
     * -
     *
     * @param $group_id
     * @return array
     */
    static function Array_cs_where($group_id)
    {

        return ArrayHelper::map(
            Sprwhtop::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->where(
                    ['AND',
                        ['==', 'parent_id', $group_id],
                        ['OR',
                            ['==', 'final_destination', (string)1],
                            ['==', 'final_destination', (int)1],
                        ]
                    ]
                )
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'name'
        );

    }

    /**
     * @return array
     */
    static function Array_cs_element()
    {

        return ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->where(
                    [
                        '==',
                        'final_destination',
                        1,
                    ]
                )
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'name'
        );

    }

    /**
     * Возвращает массив Дат   по накладным
     * -
     *
     * @return array
     */
    static function ArraySklad_Uniq_dates()
    {
        return self::find()->distinct('dt_create');

    }

    /**
     * Возвращает массив ЗАГОЛОВКОВ накладных
     * -
     *
     * @return array|ActiveRecord
     */
    static function ArraySklad_Uniq_naklad()
    {
        return self::find()->select(
            [
                'id',
                'sklad_vid_oper',
                'wh_home_number',
                'wh_debet_top',
                'wh_debet_name',
                'wh_debet_element',
                'wh_debet_element_name',
                'wh_destination',
                'wh_destination_name',
                'wh_destination_element',
                'wh_destination_element_name',
                'sklad_vid_oper_name',
                'dt_create',
                'user_ip',
                'user_id',
                'user_group_id',
                'array_casual',
                'array_bus',
                'array_count_all',
                'dt_update',
                'update_user_id',
                'dt_transfered_date',
            ]
        )->orderBy(['wh_home_number'])->asArray()->all();

    }

    /**
     * @param int $id
     * @param array $array_select
     * @return array|ActiveRecord
     */
    static function findModelAsArrayToTz($id = 0, $array_select = [])
    {
        return Sklad::find()
            ->select($array_select)
            ->where(['tz_id' => (int)$id])
            ->asArray()
            ->all();

    }

    /**
     * ВЫБОРКА: ВСЕ ЗАПИСИ ПО ДАННОМУ СКЛАДУ (массиву складов)
     * -
     *
     * @param array $array_home_numbers
     * @param       $array_select
     * @return ActiveQuery
     */
    static function findModel_home_number($array_home_numbers, $array_select)
    {
        return Sklad::find()
            ->select($array_select)
            ->where(['wh_home_number' => $array_home_numbers]);

    }

    /**
     * Модель одной накладной
     * =
     * where(['id'=>(integer) $id])->one()
     * -
     *
     * @param $id
     * @return array|null|ActiveRecord
     * @throws ExitException
     */
    static function findModelDouble($id)
    {
        if (($model = static::find()->where(['id' => (int)$id])->one()) !== null) {
            return $model;
        }

        throw new ExitException('Sklad 2 -  Ответ на запрос. Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад');

    }

    /**
     * Список Всех(если не задан ID)
     * складов по списку полей (можно не все поля)
     *
     * @param $id
     * @param $array_select
     * @return array|ActiveRecord
     */
    static function findModelDoubleSelectAll($id, $array_select)
    {
        if (empty($id)) {
            return static::find()
                ->select($array_select)
                ->all();
        } else {
            return static::find()
                ->select($array_select)
                ->where(['id' => (integer)$id])
                ->all();
        }

    }

    /**
     * СВОДНАЯ ВЕДОМОСТЬ (ИТОГОВЫЙ МАССИВ) ( MONGODB Array(Array()) )
     * --
     *
     * @param $element_id - Элемент таргетинга, $bar_code - Искомое значение (строка)
     * @param $array_select - Массив Полей
     * @return array
     */
    //static function Svod_globalam_element($element_id, $bar_code = 0, $array_select)
    static function Svod_globalam_element($element_id, $array_select)
    {

        $cursor = Sklad::find()
            ->select($array_select)
            ->asArray()
            ->all();


        // ddd($cursor);
        /////Сводная ведомость
        $array_svod = []; //Сводная ведомость

        foreach ($cursor as $part) {
            $array = $part['array_tk_amort'];

            if (isset($array) && !empty($array)) {
                foreach ($array as $item) {
                    /////////////
                    if (
                        //$item['wh_tk_amort'] == $element_parent_id &&
                        $item['wh_tk_element'] == $element_id
                    ) {
                        $array_svod[] = [
                            '##' => $element_id,
                            'ed_izmer' => (isset($item['ed_izmer']) && !empty($item['ed_izmer']) ?
                                $item['ed_izmer'] : ''),
                            'ed_izmer_num' => (isset($item['ed_izmer_num']) && !empty($item['ed_izmer_num']) ?
                                $item['ed_izmer_num'] : ''),
                            'bar_code' => (isset($item['bar_code']) ? $item['bar_code'] : ''),
                            'nakladnaya' => (isset($part['id']) ? $part['id'] : 0),
                            'wh_home_number' => (isset($part['wh_home_number']) ?
                                $part['wh_home_number'] : 0),
                            'wh_debet' => (isset($part['wh_debet']) ? $part['wh_debet'] :
                                0),
                            'wh_debet_name' => (isset($part['wh_debet_name']) ?
                                $part['wh_debet_name'] : 0),
                            'wh_debet_element' => (isset($part['wh_debet_element']) ?
                                $part['wh_debet_element'] : 0),
                            'wh_debet_element_name' => (isset($part['wh_debet_element_name']) ?
                                $part['wh_debet_element_name'] : 0),
                            'wh_destination' => (isset($part['wh_destination']) ?
                                $part['wh_destination'] : 0),
                            'wh_destination_name' => (isset($part['wh_destination_name']) ?
                                $part['wh_destination_name'] : 0),
                            'wh_destination_element' => (isset($part['wh_destination_element']) ?
                                $part['wh_destination_element'] : 0),
                            'wh_destination_element_name' => (isset($part['wh_destination_element_name']) ?
                                $part['wh_destination_element_name'] : 0),
                        ];

                        // ddd($item);
                        // ddd($part);
                    }
                    //////////////
                }
            }
        }

        //            ddd($array_svod);

        return $array_svod;

        //        throw new ExitException('findMongoScript_slelect_my_spr_globalam_element []');

    }

    /**
     * СВОДНАЯ ВЕДОМОСТЬ (ИТОГОВЫЙ МАССИВ2)     *
     * собираю по ШТРИХКОДУ.
     * -
     * Чтобы знать максимальное число накладных по нему
     *
     * @param $element_id - Элемент таргетинга, $bar_code - Искомое значение (строка)
     * @return array
     */
    //static function Svod_globalam_element_barcode($element_id, $bar_code = 0, $array_select)
    //static function Svod_globalam_element_barcode($element_id, $array_select)
    static function Svod_globalam_element_barcode($element_id)
    {

        $cursor = Sklad::find()
            ->select(['id', 'array_tk_amort'])
            ->where(['AND',
                ['==', 'array_tk_amort.wh_tk_element', $element_id],
//                ['>', 'wh_cs_number', 0],
//                ['NOT', 'tx', '']
            ])
            ->asArray()
            ->all();

        /////Сводная ведомость
        $array_svod = []; //Сводная ведомость

        foreach ($cursor as $part) {
            $array = $part['array_tk_amort'];

            if (isset($array) && !empty($array)) {
                foreach ($array as $item) {
                    /////////////
                    if (
                        //$item['wh_tk_amort'] == $element_parent_id &&
                        $item['wh_tk_element'] == $element_id
                    ) {
                        //							$xx = 0;

                        if (isset($array_svod[$item['bar_code']]['sum_id'])) {
                            $xx = $array_svod[$item['bar_code']]['sum_id'];
                            $xx++;
                        } else {
                            $xx = 1;
                        }

                        $array_svod[$item['bar_code']] = [

                            'sum_id' => $xx,
                            'bar_code' => (isset($item['bar_code']) ? $item['bar_code'] : ''),

//                            '##' => $element_id,
//                            'ed_izmer' => (isset($item['ed_izmer']) && !empty($item['ed_izmer']) ?
//                                $item['ed_izmer'] : ''),
//                            'ed_izmer_num' => (isset($item['ed_izmer_num']) && !empty($item['ed_izmer_num']) ?
//                                $item['ed_izmer_num'] : ''),

//                            'nakladnaya' => (isset($part['id']) ? $part['id'] : 0),
//                            'wh_home_number' => (isset($part['wh_home_number']) ?
//                                $part['wh_home_number'] : 0),
//                            'wh_debet' => (isset($part['wh_debet']) ? $part['wh_debet'] :
//                                0),
//                            'wh_debet_name' => (isset($part['wh_debet_name']) ?
//                                $part['wh_debet_name'] : 0),
//                            'wh_debet_element' => (isset($part['wh_debet_element']) ?
//                                $part['wh_debet_element'] : 0),
//                            'wh_debet_element_name' => (isset($part['wh_debet_element_name']) ?
//                                $part['wh_debet_element_name'] : 0),
//                            'wh_destination' => (isset($part['wh_destination']) ?
//                                $part['wh_destination'] : 0),
//                            'wh_destination_name' => (isset($part['wh_destination_name']) ?
//                                $part['wh_destination_name'] : 0),
//                            'wh_destination_element' => (isset($part['wh_destination_element']) ?
//                                $part['wh_destination_element'] : 0),
//                            'wh_destination_element_name' => (isset($part['wh_destination_element_name']) ?
//                                $part['wh_destination_element_name'] : 0),
                        ];


//                        $array_svod[] = [
//                            'sum_id' => $xx,
//                            '##' => $element_id,
//                            'bar_code' => (isset($item['bar_code']) ? $item['bar_code'] : ''),
//                        ];


                        //ddd($item);
                        //                            ddd($part);
                    }
                    //////////////
                }
            }
        }

        //ddd($array_svod);

        return $array_svod;

    }

    /**
     * @param $bar_code
     * @return array
     */
    static function Svod_one_barcode($bar_code)
    {

        $cursor = Sklad::find()
            ->select(['id', 'array_tk_amort'])
            ->where(['AND',
                    ['==', 'array_tk_amort.bar_code', $bar_code],
                    ['>', 'wh_cs_number', 0]
                ]
            )
            ->asArray()
            ->all();


        /////Сводная ведомость
        $array_svod = []; //Сводная ведомость

        foreach ($cursor as $part) {


            $array = $part['array_tk_amort'];

            if (isset($array) && !empty($array)) {
                foreach ($array as $item) {
//                    ddd($item);

                    /////////////
                    if (
                        $item['bar_code'] == $bar_code
                    ) {
                        //							$xx = 0;

                        if (isset($array_svod[$item['bar_code']]['sum_id'])) {
                            $xx = $array_svod[$item['bar_code']]['sum_id'];
                            $xx++;
                        } else {
                            $xx = 1;
                        }

                        $array_svod[$item['bar_code']] = [

                            'sum_id' => $xx,
                            'bar_code' => (isset($item['bar_code']) ? $item['bar_code'] : ''),
                        ];

                    }
                    //////////////
                }
            }
        }

//        ddd($array_svod);

        return $array_svod;

    }

    /**
     * СВОДНАЯ ВЕДОМОСТЬ
     * -
     * Скорость оборачиваемости устройств ЦС->Склад Виктора->ЦС->Склад Виктора
     * =
     * @param $element_id - Элемент таргетинга
     * @return array
     */
    static function Fast_turnover($element_id)
    {
        $date_start = strtotime('now -3 month');

        $cursor = Sklad::find()
            ->select(['id', 'array_tk_amort',
                "dt_create", "dt_create_timestamp"
            ])
            ->where(['AND',
                ['>=', 'dt_create_timestamp', $date_start],
                ['==', 'array_tk_amort.wh_tk_element', $element_id],
            ])
            ->asArray()
            ->all();


        /////Сводная ведомость
        $array_svod = []; //Сводная ведомость

        foreach ($cursor as $part) {
            $array = $part['array_tk_amort'];

            if (isset($array) && !empty($array)) {
                foreach ($array as $item) {
                    /////////////
                    if (
                        //$item['wh_tk_amort'] == $element_parent_id &&
                        $item['wh_tk_element'] == $element_id
                    ) {
                        //							$xx = 0;

                        if (isset($array_svod[$item['bar_code']]['sum_id'])) {
                            $xx = $array_svod[$item['bar_code']]['sum_id'];
                            $xx++;
                        } else {
                            $xx = 1;
                        }

                        $array_svod[$item['bar_code']] = [

                            'sum_id' => $xx,
                            'bar_code' => (isset($item['bar_code']) ? $item['bar_code'] : ''),
                        ];

                    }
                    //////////////
                }
            }
        }

//        ddd($array_svod);
        return $array_svod;
    }


    /**
     * ДЛЯ ПЕЧАТИ - АА
     * собираю по ШТРИХКОДУ.
     * -
     *
     * @param $array_select - Массив Полей
     * @return array
     */
    static function Svod_globalam_element_barcode_print($array_select)
    {

        $cursor = static::find()
            ->select($array_select)
            ->asArray()
            ->all();

        //  ddd($cursor);
        /////Сводная ведомость
        $array_svod = []; //Сводная ведомость

        foreach ($cursor as $part) {
            $array = $part['array_tk_amort'];

            if (isset($array) && !empty($array)) {
                foreach ($array as $item) {
                    /////////////
                    {
                        //							$xx = 0;

                        if (isset($array_svod[$item['wh_tk_amort']][$item['wh_tk_element']][(int)$item['bar_code']])) {
                            $xx = $array_svod[$item['wh_tk_amort']][$item['wh_tk_element']][(int)$item['bar_code']]['sum_id'];
                            $xx++;
                        } else {
                            $xx = 1;
                        }


                        $array_svod[$item['wh_tk_amort']][$item['wh_tk_element']]['' . $item['bar_code']] = [
                            'sum_id' => $xx,
                        ];

                        //                            if( $item['wh_tk_element']=='3' && $item['bar_code']=='9' ){
                        //                                ddd($array_svod);
                        //                            }
                    }
                    //////////////
                }
                //array_multisort( $array_svod, SORT_NUMERIC, SORT_DESC );
                //                  array_multisort($ar[0], SORT_ASC, SORT_STRING,
                //                                $ar[1], SORT_NUMERIC, SORT_DESC);
            }
        }

        //        ddd($array_svod);

        return $array_svod;

    }

    /**
     * Сводный отчет по ЦС
     * =
     * На Входе: МОДЕЛЬ;
     *-
     * На выходе: Массив с развернутыми НАКЛАДНЫМИ
     * -
     *
     * @param $model
     * @return array
     */
    static function in_model_out_array($model)
    {
        //         ddd($model);


        //        if(is_object( $model)){
        //            ddd(ArrayHelper::map(  $model,'id',"name"));
        //        }

        $out_array = [];

        $spr_globam_top = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');
        $spr_globam_elem = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');

        $spr_glob_top = ArrayHelper::map(Spr_glob::find()->all(), 'id', 'name');
        $spr_glob_elem = ArrayHelper::map(Spr_glob_element::find()->all(), 'id', 'name');

        //ddd($spr_glob_elem);


        foreach ($model as $item) {
            //            ddd($item['wh_home_number']);
//                        ddd($item);
//            ddd($item->array_tk_amort);

            if (isset($item->array_tk_amort) && !empty($item->array_tk_amort)) {
                foreach ($item->array_tk_amort as $item_amort) {
                    //ddd($item_amort);

                    $out_array[] = [
                        'id' => $item['id'],
                        'sklad_vid_oper' => $item['sklad_vid_oper'],
                        'wh_home_number' => $item['wh_home_number'],
                        'wh_debet_top' => $item['wh_debet_top'],
                        'wh_debet_name' => $item['wh_debet_name'],
                        'wh_debet_element' => $item['wh_debet_element'],
                        'wh_debet_element_name' => $item['wh_debet_element_name'],
                        'wh_destination' => $item['wh_destination'],
                        'wh_destination_name' => $item['wh_destination_name'],
                        'wh_destination_element' => $item['wh_destination_element'],
                        'wh_destination_element_name' => $item['wh_destination_element_name'],
                        'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],
                        'tz_id' => $item['tz_id'],
                        'dt_create' => $item['dt_create'],
                        'dt_update' => $item['dt_update'],
                        //                    'user_ip'=>$item['user_ip'],
                        //                    'user_id'=>$item['user_id'],
                        //                    'user_name'=>$item['user_name'],
                        //                    'update_user_id'=>$item['update_user_id'],
                        //                    'update_user_name'=>$item['update_user_name'],
                        //                    'user_group_id'=>$item['user_group_id'],
                        'array_bus' => $item['array_bus'],
                        'array_count_all' => $item['array_count_all'],
                        'dt_transfered_date' => $item['dt_transfered_date'],
                        //                    'dt_transfered_user_id'=>$item['dt_transfered_user_id'],
                        //                    'dt_transfered_user_name'=>$item['dt_transfered_user_name'],
                        'wh_tk_amort' => (isset($spr_globam_top[$item_amort['wh_tk_amort']]) ?
                            $spr_globam_top[$item_amort['wh_tk_amort']] : ""),
                        'wh_tk_element' => (isset($spr_globam_elem[$item_amort['wh_tk_element']]) ?
                            $spr_globam_elem[$item_amort['wh_tk_element']] : ""),
                        'ed_izmer' => (isset($item_amort['ed_izmer']) ? $item_amort['ed_izmer'] : 0),
                        'ed_izmer_num' => (isset($item_amort['ed_izmer_num']) ?
                            $item_amort['ed_izmer_num'] : 0),
                        'bar_code' => (isset($item_amort['bar_code']) ? $item_amort['bar_code'] : ''),
                        'wh_tk_amort_id' => (isset($item_amort['wh_tk_amort']) ? $item_amort['wh_tk_amort'] : ''),
                        'wh_tk_element_id' => (isset($item_amort['wh_tk_element']) ? $item_amort['wh_tk_element'] : ''),
                        //'name'=>$item_amort['name'],
                        'tx' => (isset($item['tx']) ? $item['tx'] : ''),
                    ];
                }
                /////////////////////
            } else {
                $out_array[] = [
                    'id' => $item['id'],
                    'wh_home_number' => $item['wh_home_number'],
                    'wh_debet_top' => $item['wh_debet_top'],
                    'wh_debet_name' => $item['wh_debet_name'],
                    'wh_debet_element' => $item['wh_debet_element'],
                    'wh_debet_element_name' => $item['wh_debet_element_name'],
                    'wh_destination' => $item['wh_destination'],
                    'wh_destination_name' => $item['wh_destination_name'],
                    'wh_destination_element' => $item['wh_destination_element'],
                    'wh_destination_element_name' => $item['wh_destination_element_name'],
//                    'sklad_vid_oper_name' => (isset($item['sklad_vid_oper_name'])?$item['sklad_vid_oper_name']:''),
                    'dt_create' => $item['dt_create'],
                    'dt_update' => $item['dt_update'],
                    'tx' => (isset($item['tx']) ? $item['tx'] : ''),
                ];
            }

//             ddd($out_array);


            unset($item_amort);


            if (isset($item['array_tk']) && !empty($item['array_tk'])) {
                foreach ($item['array_tk'] as $item_2) {
                    //                ddd($item2);
                    //                'wh_tk' => '9'
                    //                'wh_tk_element' => '114'
                    //                'ed_izmer' => '1'
                    //                'ed_izmer_num' => '6'
                    //                'name' => 'Винт с резьбой, с пресс-шайбой М4*12 мм, тарельчатая головка, оцинкованная'

                    $out_array[] = [
                        'id' => $item['id'],
                        'sklad_vid_oper' => $item['sklad_vid_oper'],
                        'wh_home_number' => $item['wh_home_number'],
                        'wh_debet_top' => $item['wh_debet_top'],
                        'wh_debet_name' => $item['wh_debet_name'],
                        'wh_debet_element' => $item['wh_debet_element'],
                        'wh_debet_element_name' => $item['wh_debet_element_name'],
                        'wh_destination' => $item['wh_destination'],
                        'wh_destination_name' => $item['wh_destination_name'],
                        'wh_destination_element' => $item['wh_destination_element'],
                        'wh_destination_element_name' => $item['wh_destination_element_name'],
                        'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],
                        'tz_id' => $item['tz_id'],
                        //                    'tz_name'=>         $item['tz_name'],
                        //                    'tz_date'=>         $item['tz_date'],
                        //                    'dt_deadline'=>     $item['dt_deadline'],
                        'dt_create' => $item['dt_create'],
                        'dt_update' => $item['dt_update'],
                        //                    'user_ip'=>$item['user_ip'],
                        //                    'user_id'=>$item['user_id'],
                        //                    'user_name'=>$item['user_name'],
                        //                    'update_user_id'=>$item['update_user_id'],
                        //                    'update_user_name'=>$item['update_user_name'],
                        //                    'user_group_id'=>$item['user_group_id'],
                        'array_bus' => $item['array_bus'],
                        'array_count_all' => $item['array_count_all'],
                        'dt_transfered_date' => $item['dt_transfered_date'],
                        //                    'dt_transfered_user_id'=>$item['dt_transfered_user_id'],
                        //                    'dt_transfered_user_name'=>$item['dt_transfered_user_name'],
                        'wh_tk' => (isset($spr_glob_top[$item_2['wh_tk']]) ?
                            $spr_glob_top[$item_2['wh_tk']] : ""),
                        'wh_tk_element' => (isset($spr_glob_elem[$item_2['wh_tk_element']]) ?
                            $spr_glob_elem[$item_2['wh_tk_element']] : ""),
                        'ed_izmer' => (isset($item_2['ed_izmer']) ? $item_2['ed_izmer'] : 0),
                        'ed_izmer_num' => (isset($item_2['ed_izmer_num']) ? $item_2['ed_izmer_num'] : 0),
                        'bar_code' => (isset($item_2['bar_code']) ? $item_2['bar_code'] : ""),
                        //'name'=>$item_amort['name'],
                    ];
                }
            }
            ///////////////
            unset($item_2);


            if (isset($item['array_casual']) && !empty($item['array_casual'])) {
                foreach ($item['array_casual'] as $item_3) {
                    $out_array[] = [
                        'id' => $item['id'],
                        'sklad_vid_oper' => $item['sklad_vid_oper'],
                        'wh_home_number' => $item['wh_home_number'],
                        'wh_debet_top' => $item['wh_debet_top'],
                        'wh_debet_name' => $item['wh_debet_name'],
                        'wh_debet_element' => $item['wh_debet_element'],
                        'wh_debet_element_name' => $item['wh_debet_element_name'],
                        'wh_destination' => $item['wh_destination'],
                        'wh_destination_name' => $item['wh_destination_name'],
                        'wh_destination_element' => $item['wh_destination_element'],
                        'wh_destination_element_name' => $item['wh_destination_element_name'],
                        'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],
                        'tz_id' => $item['tz_id'],
                        //                    'tz_name'=>         $item['tz_name'],
                        //                    'tz_date'=>         $item['tz_date'],
                        //                    'dt_deadline'=>     $item['dt_deadline'],
                        'dt_create' => $item['dt_create'],
                        'dt_update' => $item['dt_update'],
                        //                    'user_ip'=>$item['user_ip'],
                        //                    'user_id'=>$item['user_id'],
                        //                    'user_name'=>$item['user_name'],
                        //                    'update_user_id'=>$item['update_user_id'],
                        //                    'update_user_name'=>$item['update_user_name'],
                        //                    'user_group_id'=>$item['user_group_id'],
                        'array_bus' => $item['array_bus'],
                        'array_count_all' => $item['array_count_all'],
                        'dt_transfered_date' => $item['dt_transfered_date'],
                        //                    'dt_transfered_user_id'=>$item['dt_transfered_user_id'],
                        //                    'dt_transfered_user_name'=>$item['dt_transfered_user_name'],
                        'wh_tk' => (isset($spr_glob_top[$item_3['wh_tk']]) ?
                            $spr_glob_top[$item_3['wh_tk']] : ""),
                        'wh_tk_element' => (isset($spr_glob_elem[$item_3['wh_tk_element']]) ?
                            $spr_glob_elem[$item_3['wh_tk_element']] : ""),
                        'ed_izmer' => (isset($item_3['ed_izmer']) ? $item_3['ed_izmer'] : 0),
                        'ed_izmer_num' => (isset($item_3['ed_izmer_num']) ? $item_3['ed_izmer_num'] : 0),
                        'bar_code' => (isset($item_3['bar_code']) ? $item_3['bar_code'] : ""),
                        //'name'=>$item_amort['name'],
                    ];
                }
            }

            ///////////////
            unset($item_3);
        }

        //ddd($out_array);
        return $out_array;

    }

    /**
     * ТУТ ТОЛЬКО ДЛЯ АСУОП!!!!!!!!!!!!!!
     * =
     * Если надо все - смотри выше!!!
     * =
     * На Входе: МОДЕЛЬ;  На выходе: Массив с развернутыми НАКЛАДНЫМИ
     * -
     *
     * @param $model
     * @return array
     */
    static function in_model_out_array_amort($model)
    {
        $out_array = [];

        $spr_globam_top = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');
        $spr_globam_elem = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');

        //			$spr_glob_top  = ArrayHelper::map( Spr_glob::find()->all(), 'id', 'name' );
        //			$spr_glob_elem = ArrayHelper::map( Spr_glob_element::find()->all(), 'id', 'name' );


        foreach ($model as $item) {
            //ddd($model);

            if (isset($item->array_tk_amort) && !empty($item->array_tk_amort)) {
                foreach ($item->array_tk_amort as $item_amort) {
                    //ddd($item_amort);

                    $out_array[] = [
                        'id' => $item['id'],
                        'sklad_vid_oper' => $item['sklad_vid_oper'],
                        'wh_home_number' => $item['wh_home_number'],
                        'wh_debet_top' => $item['wh_debet_top'],
                        'wh_debet_name' => $item['wh_debet_name'],
                        'wh_debet_element' => $item['wh_debet_element'],
                        'wh_debet_element_name' => $item['wh_debet_element_name'],
                        'wh_destination' => $item['wh_destination'],
                        'wh_destination_name' => $item['wh_destination_name'],
                        'wh_destination_element' => $item['wh_destination_element'],
                        'wh_destination_element_name' => $item['wh_destination_element_name'],
                        'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],
                        'dt_create' => $item['dt_create'],
                        'dt_update' => $item['dt_update'],
                        'array_count_all' => $item['array_count_all'],
                        'dt_transfered_date' => $item['dt_transfered_date'],
                        'wh_tk_amort' => (isset($spr_globam_top[$item_amort['wh_tk_amort']]) ?
                            $spr_globam_top[$item_amort['wh_tk_amort']] : ""),
                        'wh_tk_element' => (isset($spr_globam_elem[$item_amort['wh_tk_element']]) ?
                            $spr_globam_elem[$item_amort['wh_tk_element']] : ""),
                        'ed_izmer' => (isset($item_amort['ed_izmer']) ? $item_amort['ed_izmer'] : 0),
                        'ed_izmer_num' => (isset($item_amort['ed_izmer_num']) ?
                            $item_amort['ed_izmer_num'] : 0),
                        'bar_code' => (isset($item_amort['bar_code']) ? $item_amort['bar_code'] : ''),
                        //'name'=>$item_amort['name'],
                        'tx' => (isset($item['tx']) ? $item['tx'] : ''),
                    ];
                }
                /////////////////////
            } else {
                $out_array[] = [
                    'id' => $item['id'],
                    'wh_home_number' => $item['wh_home_number'],
                    'wh_debet_top' => $item['wh_debet_top'],
                    'wh_debet_name' => $item['wh_debet_name'],
                    'wh_debet_element' => $item['wh_debet_element'],
                    'wh_debet_element_name' => $item['wh_debet_element_name'],
                    'wh_destination' => $item['wh_destination'],
                    'wh_destination_name' => $item['wh_destination_name'],
                    'wh_destination_element' => $item['wh_destination_element'],
                    'wh_destination_element_name' => $item['wh_destination_element_name'],
//                    'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],
                    'dt_create' => $item['dt_create'],
                    'dt_update' => $item['dt_update'],
                ];
            }


            unset($item_amort);
        }

        //ddd($out_array);
        return $out_array;

    }


    /**
     * Поиск по collection-SKLAD
     * ищем любую строку внутри массива "array_tk_amort"
     *
     * @param $bar_code
     * @param $array_select
     * @param $array_ids_sender
     * @param $array_ids_dest
     * @return array|ActiveRecord
     */
    static function BarCode_to_Nakladnye_simpleVariant($bar_code, $array_select)
    {
        return Sklad::find()
            ->select($array_select)
            ->where(
                ['OR',
                    ['array_tk_amort.bar_code' => $bar_code],
                    ['array_tk_amort.bar_code' => (int)$bar_code],
                    ['array_tk_amort.bar_code' => (string)$bar_code],
                ]
            )
            ->asArray()
            ->orderBy('dt_create_timestamp')
            ->all();

    }

    /**
     * Поиск по collection-SKLAD
     * ищем любую строку внутри массива "array_tk_amort"
     *
     * @param $bar_code
     * @param $array_select
     * @param $array_ids_sender
     * @param $array_ids_dest
     * @return array|ActiveRecord
     */
    static function BarCode_to_Nakladnye($bar_code, $array_select, $array_ids_sender, $array_ids_dest)
    {
        if (empty($bar_code)) return [];

        if (empty($array_ids_sender) && empty($array_ids_dest)) {
            return Sklad::find()
                ->select($array_select)
                ->where(
                    ['OR',
                        ['array_tk_amort.bar_code' => $bar_code],
                        ['array_tk_amort.bar_code' => (int)$bar_code],
                        ['array_tk_amort.bar_code' => (string)$bar_code],
                    ]
                )
                ->asArray()
                ->orderBy('dt_create_timestamp')
                ->all();
        }

        //
        $array_ids_sender_int = array_map('intval', $array_ids_sender);
        $dest_filter_int = array_map('intval', $array_ids_dest);

        return Sklad::find()
            ->select($array_select)
            ->where(['AND',

                    ['OR',
                        ['array_tk_amort.bar_code' => $bar_code],
                        ['array_tk_amort.bar_code' => (int)$bar_code],
                        ['array_tk_amort.bar_code' => (string)$bar_code],
                    ],

                    ['OR',
                        ['IN', 'wh_destination_element', $array_ids_dest],
                        ['IN', 'wh_destination_element', $dest_filter_int],

                        ['IN', 'wh_debet_element', $array_ids_sender],
                        ['IN', 'wh_debet_element', $array_ids_sender_int]
                    ]

                ]
            )
            ->asArray()
            ->orderBy('dt_create_timestamp')
            ->all();


    }

    /**
     * Фильтр для выпадающего списка
     *
     * @param $bar_code
     * @return array
     */
    static function BarCode_to_Nakladnye_filtr($bar_code)
    {
        if (empty($bar_code)) return [];

        //return ArrayHelper::map(Sklad::find()
        $ids_elements = Sklad::find()
            ->where(
                ['OR',
                    ['array_tk_amort.bar_code' => (string)$bar_code],
                    ['array_tk_amort.bar_code' => (int)$bar_code]
                ]
            )
            ->distinct('wh_destination_element');

        return Sprwhelement::ArrayNamesWithIds($ids_elements);
    }

    /**
     * Поиск по collection-SKLAD
     * ищем ВСЕ БАРКОДЫ не вошедшие в ПУЛ-справочник
     *
     * @param $bar_code
     * @return bool
     */
    static function Out_barcode_one($bar_code)
    {

        $xx = Sklad::find()
            ->where(['array_tk_amort.bar_code' => ['$eq' => $bar_code]])
            ->one();

        return (isset($xx) && (int)$xx['id'] > 0);

    }

    /**
     * Для ДОК отдела. Поиск по Штрихкоду.
     * =
     * Из него создается потом накладная Демонтаж/Монтаж
     * -
     *
     * @param $bar_code
     * @return array|ActiveRecord|null
     */
    static function Find_barcode_one($bar_code)
    {
        return static::find()
            ->where(['array_tk_amort.bar_code' => ['$eq' => $bar_code]])
            ->orderBy('dt_create_timestamp DESC')
            //->asArray()
            ->one();

    }

    /**
     *        Ищет баркоды, которые:
     * =
     *  1. есть в накладных,
     * -
     *  2. но нет в справочнике
     * -
     *
     * @return array
     */
    static function Out_array_barcode_all()
    {

        $all_barcode = ArrayHelper::map(Barcode_pool::find()->all(), 'bar_code', 'id');

        // Количество разных, найденных в группе
        //			ddd( $all_barcode );
        //			ddd(count( $all_barcode )); //15111
        //	 ddd( count($xx) );

        $xx = Sklad::find()
            ->distinct('array_tk_amort.bar_code');


        $array_out = [];

        foreach ($xx as $key => $item) {
            if (!isset($all_barcode [$item])) {
                $array_out[] = $item;
            }
        }

        //ddd($array_out);

        return $array_out;

    }


    /**
     *   Список ИДС товаров в этой накладной
     * =
     * @param $array
     * @return array
     */
    static function List_elements_of_this($array)
    {
        $res = [];
        if (!empty($array)) {
            foreach ($array as $item) {
                if (empty($item['bar_code'])) {
                    // id = > summ
                    $res['nobarcode'][(int)$item['wh_tk_element']] = (int)$item['ed_izmer_num'];
                } else {
                    $res['barcode'][] = $item['bar_code'];
                }
            }
        }
        return $res;
    }


    /**
     *  Остаки  на этом складе по ОДНОЙ ПОЗИЦИИ ТОВАРА без Штрихкода
     * =
     * @param $array
     * @return array
     */
    static function Summ_prihod_rashod($array)
    {
        $array_rez = [];

        ///
        if (!empty($array[2])) {
            $summ_prihod = 0;
            //Приходы
            foreach ($array[2] as $key_prs => $prihod) {

                if (!isset($array_rez[2][$prihod['wh_tk_element']])) {
                    $array_rez[2][$prihod['wh_tk_element']] = 0;
                } else {
                    $array_rez[2][$prihod['wh_tk_element']] = $array_rez[2][$prihod['wh_tk_element']] + $prihod['ed_izmer_num'];
                }
            }
        }
        ///
        if (!empty($array[3])) {
            $summ_prihod = 0;
            //Приходы
            foreach ($array[3] as $key_prs => $prihod) {

                if (!isset($array_rez[3][$prihod['wh_tk_element']])) {
                    $array_rez[3][$prihod['wh_tk_element']] = 0;
                } else {
                    $array_rez[3][$prihod['wh_tk_element']] = $array_rez[3][$prihod['wh_tk_element']] + $prihod['ed_izmer_num'];
                }
            }
        }
        return $array_rez;
    }


    /**
     *  Остаки  на этом складе по ОДНОЙ ПОЗИЦИИ ТОВАРА без Штрихкода
     * =
     * @param $home_id
     * @param $barcode
     * @return array
     */
    static function Rest_of_barcode($home_id, $barcode)
    {
        //ddd($home_id);
        //ddd($barcode); // 041600

        ///  'wh_destination_element' => 1
        //        'array_tk_amort' => [
        //            0 => [
        //                'wh_tk_amort' => '7'
        //                'wh_tk_element' => '2'
        ///     'ed_izmer' => '1'
        //                'ed_izmer_num' => '1'
        //                'take_it' => '0'
        //                'bar_code' => '19600002545'
        //                'name' => 'Validator CVB24 master Терминал пассажира CVB24 ведущий (Master)'


        $model = Sklad::find()
            ->select([
                'wh_home_number',
                'id',
                'sklad_vid_oper',
                'dt_create_timestamp',
                'array_tk_amort',
            ])
            ->where(
                ['AND',
                    ['=', 'wh_home_number', (int)$home_id],
                    ['=', 'array_tk_amort.bar_code', (string)$barcode] ///!! string
                ]
            )
            ->asArray()
            ->limit(10)
            ->all();

        //ddd($model);

        $rez = [];
        foreach ($model as $nnn) {
            foreach ($nnn['array_tk_amort'] as $key => $item) {
                if ($item['bar_code'] == (string)$barcode) {
                    $rez[(int)$nnn['sklad_vid_oper']][(int)$nnn['dt_create_timestamp']] [] = $item;
                }
            }
        }
        /// OK!!

        return $rez;
    }


    /**
     *  Остаки  на этом складе по ОДНОЙ ПОЗИЦИИ ТОВАРА без Штрихкода
     * =
     * @param $home_id
     * @param $amort_id
     * @param $sum_zapros Запрос на получение количества
     * @return array
     */
    static function Rest_of_one_id($home_id, $amort_id, $sum_zapros)
    {
        //        'array_tk_amort' => [
        //            0 => [
        //                'wh_tk_amort' => '7'
        //                'wh_tk_element' => '2'
        //                'ed_izmer' => '1'
        //                'ed_izmer_num' => '1'
        //                'take_it' => '0'
        //                'bar_code' => '19600002545'
        //                'name' => 'Validator CVB24 master Терминал пассажира CVB24 ведущий (Master)'


        $model = Sklad::find()
            ->select([
                'wh_home_number',
                'id',
                'sklad_vid_oper',
                'dt_create_timestamp',
                'array_tk_amort',
            ])
            ->where(
                ['AND',
                    ['=', 'wh_home_number', (int)$home_id],
                    ['=', 'array_tk_amort.wh_tk_element', (string)$amort_id] ///!! string
                ]
            )
            ->asArray()
            ->all();

        ///
        if (empty($model)) {
            return [];
        }

        $rez = [];
        foreach ($model as $nnn) {
            //ddd($nnn);
            //ddd((int)$nnn['sklad_vid_oper']==2);

            foreach ($nnn['array_tk_amort'] as $key => $item) {
                if ($item['wh_tk_element'] == (string)$amort_id) {
                    //$rez[(int)$nnn['sklad_vid_oper']][(int)$nnn['dt_create_timestamp']] [] = $item;

                    $rez[(int)$nnn['sklad_vid_oper']][] = $item;

                }
            }
        }

        //        dd($home_id);
        //        ddd($model);
        //        ddd($rez);


        return $rez;
    }


    /**
     * МОНГО. Поиск средствами МОНГО.
     * Ищем все накладные, в которых проходил Данный ТОВАР     *
     * -
     * Only AMORT
     *
     * @param $sklad_id
     * @param $group_id
     * @param $element_id
     * @return array|ActiveRecord
     * @throws NotFoundHttpException
     */
    static function Mongo_findHisory_GropElement_am($sklad_id, $group_id, $element_id)
    {

        if (!isset($sklad_id) || !isset($group_id) || !isset($element_id)) {
            throw new NotFoundHttpException('Не достаточно входных данных.');
        }

        if (!isset($group_id) || empty($group_id)) {
            $group_id = ['', 0];
        }
        if (!isset($element_id) || empty($element_id)) {
            $element_id = ['', 0];
        }


        ///
        $xx = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                    'dt_create',
                    'dt_update',
                ]
            )
            ->where(
                [
                    'AND',
                    ['wh_home_number' => $sklad_id],
                    ['array_tk_amort.wh_tk_amort' => ['$eq' => $group_id]],
                    ['array_tk_amort.wh_tk_element' => ['$eq' => $element_id]],
                ]
            )
            ->orderBy('dt_create_timestamp')
            ->asArray()
            ->all();


        ///
        $things_arr = ArrayHelper::map(
            Spr_things::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy(['id'])
                ->asArray()->all(), 'id', 'name'
        );


        ////////////////////
        $out_amort = [];
        $prihod = 0;
        $rashod = 0;
        foreach ($xx as $item) {
            /// Слишком полный массив
            $buf_amort = $item['array_tk_amort'];
            foreach ($buf_amort as $item_array) {
                //                ddd($item_array['wh_tk_amort']==$group_id);

                if (
                    $item_array['wh_tk_amort'] == $group_id && $item_array['wh_tk_element'] ==
                    $element_id
                ) {


                    $item_array = ArrayHelper::merge(['ed_izmer_nn' => $things_arr[$item_array['ed_izmer']]], $item_array);
                    //                    $item_array= ArrayHelper::merge( ['wh_tk_amort_nn'  =>$group_arr [$item_array['wh_tk_amort'] ]] , $item_array);
                    //                    $item_array= ArrayHelper::merge( ['wh_tk_element_nn'=>$element_arr[$item_array['wh_tk_element'] ]],$item_array);

                    $item_array = ArrayHelper::merge(['sklad_vid_oper' => $item['sklad_vid_oper']], $item_array);
                    $item_array = ArrayHelper::merge(['id' => $item['id']], $item_array);

                    $item_array = ArrayHelper::merge(['dt_create' => $item['dt_create']], $item_array);
                    $item_array = ArrayHelper::merge(['dt_update' => $item['dt_update']], $item_array);
                    $item_array = ArrayHelper::merge(['sklad_id' => $sklad_id], $item_array);

                    $out_amort[] = $item_array;

                    //PRIHOD
                    if ((int)$item['sklad_vid_oper'] == 2) {
                        $prihod = $prihod + (int)$item_array['ed_izmer_num'];
                    }
                    //RASHOD
                    if ((int)$item['sklad_vid_oper'] == 3) {
                        $rashod = $rashod + (int)$item_array['ed_izmer_num'];
                    }
                }
            }
        }


        $buf_id = 0;
        $ed_izmer_num = 0;

        foreach ($out_amort as $item) {

            if ($buf_id == (int)$item['id']) {
                $item['ed_izmer_num'] = (int)$item['ed_izmer_num'] + $ed_izmer_num;
            }

            $out[$item['id']] = $item;

            $buf_id = (int)$item['id'];
            $ed_izmer_num = (int)$item['ed_izmer_num'];
        }

        $out_amort = $out;


        //// Итого:
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Приход, всего: ' . $prihod,
        ];
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Расход, всего: ' . $rashod,
        ];


        //        ddd($out_amort);
        //        ddd($xx);

        return $out_amort;

    }




///
///
///
    static function arrayVid_oper_all_numered()
    {
        $array = [1, 2, 3];

        $xx_ids = ArrayHelper::getColumn(Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                ]
            )
            ->where(
                ['IN', "sklad_vid_oper", $array]
            )
            ->asArray()
            ->limit(10000)
            ->all(), 'id');

        return $xx_ids;

    }

    /**
     * Все ПУСТЫЕ.
     * =
     * [ "array_tk_amort.wh_tk_amort" => 0 ],
     * [ "array_tk_amort.wh_tk_amort" => "" ],
     * [ "array_tk_amort.wh_tk_element" => 0 ],
     * [ "array_tk_amort.wh_tk_element" => "" ],
     * [ "array_tk_amort.ed_izmer_num" => 0 ],
     * [ "array_tk_amort.ed_izmer_num" =>
     *
     * @return array|ActiveRecord
     */
    static function Mongo_all_empty()
    {
        $xx = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                    'dt_create',
                    'dt_update',
                ]
            )
            ->where(
                ['OR',
                    ["array_tk_amort.wh_tk_amort" => 0],
                    ["array_tk_amort.wh_tk_amort" => ""],
                    ["array_tk_amort.wh_tk_element" => 0],
                    ["array_tk_amort.wh_tk_element" => ""],
                    ["array_tk_amort.ed_izmer_num" => 0],
                    ["array_tk_amort.ed_izmer_num" => ""]
                ]
            )
            ->asArray()
            ->all();


        $things_arr = ArrayHelper::map(
            Spr_things::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy(['id'])
                ->asArray()->all(), 'id', 'name'
        );


        ////////////////////
        $out_amort = [];
        $prihod = 0;
        $rashod = 0;
        foreach ($xx as $item) {
            /// Слишком полный массив
            $buf_amort = $item['array_tk_amort'];
            foreach ($buf_amort as $item_array) {


                $item_array = ArrayHelper::merge(['ed_izmer_nn' => $things_arr[$item_array['ed_izmer']]], $item_array);
                //                    $item_array= ArrayHelper::merge( ['wh_tk_amort_nn'  =>$group_arr [$item_array['wh_tk_amort'] ]] , $item_array);
                //                    $item_array= ArrayHelper::merge( ['wh_tk_element_nn'=>$element_arr[$item_array['wh_tk_element'] ]],$item_array);

                $item_array = ArrayHelper::merge(['sklad_vid_oper' => $item['sklad_vid_oper']], $item_array);
                $item_array = ArrayHelper::merge(['id' => $item['id']], $item_array);

                $item_array = ArrayHelper::merge(['dt_create' => $item['dt_create']], $item_array);
                $item_array = ArrayHelper::merge(['dt_update' => $item['dt_update']], $item_array);
                //                    $item_array = ArrayHelper::merge( [ 'sklad_id' => $sklad_id ], $item_array );

                $out_amort[] = $item_array;

                //PRIHOD
                if ((int)$item['sklad_vid_oper'] == 2) {
                    $prihod = $prihod + (int)$item_array['ed_izmer_num'];
                }
                //RASHOD
                if ((int)$item['sklad_vid_oper'] == 3) {
                    $rashod = $rashod + (int)$item_array['ed_izmer_num'];
                }
            }
        }


        //// Итого:
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Приход, всего: ' . $prihod,
        ];
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Расход, всего: ' . $rashod,
        ];


        //        ddd($out_amort);
        //        ddd($xx);

        return $out_amort;

    }

    /**
     *  РеМОНТ Всех ПУСТЫХ. ОТ Mongo_all_empty()
     * =
     * отработал прекрасно!
     *
     * @return int
     */
    static function Remont_all_empty_am()
    {

        $xx_global = Sklad::find()
            ->select(
                [
                    'id',
                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                ]
            )
            ->where(
                ['AND',
                    ['OR',
                        ['like', "array_tk_amort.wh_tk_element", "2"],
                        ['like', "array_tk_amort.wh_tk_element", "7"]
                    ],
                    ['!=', "array_tk_amort.wh_tk_amort", "2"],
                    ['!=', "array_tk_amort.wh_tk_amort", "7"],
                    ['!=', "array_tk_amort.wh_tk_amort", 2],
                    ['!=', "array_tk_amort.wh_tk_amort", 7],
                ]
            )
            ->asArray()
            ->all();


        //             .............
        $spr_globam_element = Spr_globam_element::find()
            ->select(
                [
                    'id',
                    'parent_id',
                ]
            )
            ->asArray()
            ->all();
        $spr_array = ArrayHelper::map($spr_globam_element, 'id', 'parent_id');

        //        ddd( $spr_array );
        //             .............

        $x = 0;

        foreach ($xx_global as $item) {
            $x++;

            $global_read = Sklad::find()
                ->where(['id' => (int)$item['id']])
                ->one();

            $am = $global_read['array_tk_amort'];

            foreach ($am as $key => $one_am) {
                $am[$key]['wh_tk_amort'] = (string)$spr_array[(int)$am[$key]['wh_tk_element']]; //7
            }

            //ddd($am);
            $global_read['array_tk_amort'] = $am;

            //ddd($global_read);

            if ($global_read->save(true)) {
                echo "<br>" . $global_read->id;
            } else {
                echo "<br>====" . $global_read->id;
                ddd($global_read->errors);
            }
        }


        return $x;

    }

    /**
     *  РеМОНТ Всех ПУСТЫХ.
     * =
     * отработал прекрасно!
     *
     * @return int
     */
    static function Remont_all_empty()
    {

        //        "array_tk" : [
        //        {
        //            "wh_tk" : "",
        //            "wh_tk_element" : "305",
        //            "ed_izmer_num" : "6",
        //            "ed_izmer" : "1"
        //        },

        $xx_global = Sklad::find()
            ->select(
                [
                    'id',
                    'array_tk.wh_tk',
                    //                    'array_tk.wh_tk_element',
                    //                    'array_tk.ed_izmer',
                    //                    'array_tk.ed_izmer_num',
                ]
            )
            ->where(
                [
                    "array_tk.wh_tk" => ""
                ]
            )
            ->asArray()
            ->all();

        //             .............
        $spr_glob_element = Spr_glob_element::find()
            ->select(
                [
                    'id',
                    'parent_id',
                ]
            )
            ->asArray()
            ->all();
        $spr_array = ArrayHelper::map($spr_glob_element, 'id', 'parent_id');

        //                ddd( $spr_array );
        //             .............

        $x = 0;

        //        ddd( $xx_global );


        foreach ($xx_global as $item) {
            $x++;

            $global_read = Sklad::find()
                ->where(['id' => (int)$item['id']])
                ->one();

            //            ddd( $global_read );

            $am = $global_read['array_tk'];

            //            ddd( $am );

            foreach ($am as $key => $one_am) {
                $am[$key]['wh_tk'] = (string)$spr_array[(int)$am[$key]['wh_tk_element']]; //7
            }

            //ddd($am);
            $global_read['array_tk'] = $am;

            //ddd($global_read);

            if ($global_read->save(true)) {
                echo "<br>" . $global_read->id;
            } else {
                echo "<br>====" . $global_read->id;
                ddd($global_read->errors);
            }
        }


        return $x;

    }

    /**
     * МОНГО. Поиск средствами МОНГО.
     * Ищем все накладные, в которых проходил Данный ТОВАР *
     * ПО ШТРИХКОДАМ
     * -
     * Only AMORT
     *
     * @param $sklad_id
     * @param $group_id
     * @param $element_id
     * @return array|ActiveRecord
     * @throws NotFoundHttpException
     */
    static function Mongo_findHisory_GropElement_am_barcode($sklad_id, $group_id, $element_id)
    {
        if (!isset($sklad_id) || !isset($group_id) || !isset($element_id)) {
            throw new NotFoundHttpException('Не достаточно входных данных.');
        }


        if (empty($group_id)) {
            $group_id = '';
        }
        if (empty($element_id)) {
            $element_id = '';
        }

        $xx = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                    //                'array_tk_amort',
                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                    'array_tk_amort.bar_code',
                    'dt_create',
                    'dt_update',
                ]
            )
            ->where(
                [
                    'AND',
                    ['wh_home_number' => $sklad_id],
                    ['array_tk_amort.wh_tk_amort' => ['$eq' => $group_id]],
                    ['array_tk_amort.wh_tk_element' => ['$eq' => $element_id]],
                ]
            )
            ->asArray()
            ->all();

        //        ddd($xx);
        /////////////////
        //        $group_arr= ArrayHelper::map(Spr_globam::find()
        //            ->select(['id','name'])
        //            ->orderBy(['id'])
        //            ->asArray()->all(), 'id', 'name' );
        //
        //        $element_arr= ArrayHelper::map(Spr_globam_element::find()
        //            ->select(['id','name'])
        //            ->orderBy(['id'])
        //            ->asArray()->all(), 'id', 'name' );

        $things_arr = ArrayHelper::map(
            Spr_things::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy(['id'])
                ->asArray()->all(), 'id', 'name'
        );


        ////////////////////
        $out_amort = [];
        $prihod = 0;
        $rashod = 0;
        foreach ($xx as $item) {
            /// Слишком полный массив
            $buf_amort = $item['array_tk_amort'];
            foreach ($buf_amort as $item_array) {
                //ddd($buf_amort);
                //ddd($item_array['wh_tk_amort']==$group_id);


                if (
                    $item_array['wh_tk_amort'] == $group_id && $item_array['wh_tk_element'] ==
                    $element_id
                ) {

                    $item_array = ArrayHelper::merge(['ed_izmer_nn' => $things_arr[$item_array['ed_izmer']]], $item_array);

                    //                    $item_array= ArrayHelper::merge( ['wh_tk_amort_nn'  =>$group_arr [$item_array['wh_tk_amort'] ]] , $item_array);
                    //                    $item_array= ArrayHelper::merge( ['wh_tk_element_nn'=>$element_arr[$item_array['wh_tk_element'] ]],$item_array);

                    $item_array = ArrayHelper::merge(['sklad_vid_oper' => $item['sklad_vid_oper']], $item_array);
                    $item_array = ArrayHelper::merge(['id' => $item['id']], $item_array);

                    $item_array = ArrayHelper::merge(['dt_create' => $item['dt_create']], $item_array);
                    $item_array = ArrayHelper::merge(['dt_update' => $item['dt_update']], $item_array);
                    $item_array = ArrayHelper::merge(['sklad_id' => $sklad_id], $item_array);
                    $out_amort[] = $item_array;

                    //PRIHOD
                    if ((int)$item['sklad_vid_oper'] == 2) {
                        $prihod = $prihod + (int)$item_array['ed_izmer_num'];
                    }
                    //RASHOD
                    if ((int)$item['sklad_vid_oper'] == 3) {
                        $rashod = $rashod + (int)$item_array['ed_izmer_num'];
                    }
                }
            }
        }

        //ddd($item_array);
        //        'id' => 995
        //    'sklad_vid_oper' => '3'
        //    'wh_tk_element_nn' => 'Чехол кожаный для New 8210 (водитель)'
        //    'wh_tk_amort_nn' => 'Карты, чехлы'
        //    'ed_izmer_nn' => 'шт'
        //    'wh_tk_amort' => '6'
        //    'wh_tk_element' => '18'
        //    'ed_izmer' => '1'
        //    'ed_izmer_num' => '4'
        //// Итого:
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Приход, всего: ' . $prihod,
            'bar_code' => '',
        ];
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Расход, всего: ' . $rashod,
            'bar_code' => '',
        ];


        //        ddd($out_amort);
        //        ddd($xx);

        return $out_amort;

    }

    /**
     * МОНГО. Поиск средствами МОНГО.
     * Ищем все накладные, в которых проходил Данный ТОВАР     *
     * -
     * Only SPISANIE
     *
     * @param $sklad_id
     * @param $group_id
     * @param $element_id
     * @return array|ActiveRecord
     * @throws NotFoundHttpException
     */
    static function Mongo_findHisory_GropElement_sp($sklad_id, $group_id, $element_id)
    {
        if (!isset($sklad_id) || !isset($group_id) || !isset($element_id)) {
            throw new NotFoundHttpException('Не достаточно входных данных.');
        }

        //            "wh_tk_amort" : "5",
        //            "wh_tk_element" : "13",
        //            "ed_izmer" : "1",
        //            "ed_izmer_num" : "2",
        //            "bar_code" : ""
        if (empty($group_id)) {
            $group_id = '';
        }
        if (empty($element_id)) {
            $element_id = '';
        }

        //	    ddd(123);


        $xx = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                    'array_tk.wh_tk',
                    'array_tk.wh_tk_element',
                    'array_tk.ed_izmer',
                    'array_tk.ed_izmer_num',
                    'dt_create',
                    'dt_create_timestamp',
                    'dt_update',
                ]
            )
            ->where(
                [
                    'AND',
                    ['wh_home_number' => $sklad_id],
                    ['array_tk.wh_tk' => ['$eq' => $group_id]],
                    ['array_tk.wh_tk_element' => ['$eq' => $element_id]],
                ]
            )
            ->orderBy('dt_create_timestamp')
            ->asArray()
            ->all();

        //        ddd($sklad_id);
        //        ddd($xx);


        $things_arr = ArrayHelper::map(
            Spr_things::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy(['id'])
                ->asArray()
                ->all(), 'id', 'name'
        );


        ////////////////////
        $out_amort = [];
        $prihod = 0;
        $rashod = 0;
        foreach ($xx as $item) {
            /// Слишком полный массив
            $buf_amort = $item['array_tk'];
            foreach ($buf_amort as $item_array) {

                if (
                    $item_array['wh_tk'] == $group_id && $item_array['wh_tk_element'] ==
                    $element_id
                ) {


                    $item_array = ArrayHelper::merge(['ed_izmer_nn' => $things_arr[$item_array['ed_izmer']]], $item_array);

                    $item_array = ArrayHelper::merge(['sklad_vid_oper' => $item['sklad_vid_oper']], $item_array);
                    $item_array = ArrayHelper::merge(['id' => $item['id']], $item_array);

                    $item_array = ArrayHelper::merge(['dt_create' => $item['dt_create']], $item_array);
                    $item_array = ArrayHelper::merge(['dt_update' => $item['dt_update']], $item_array);
                    $item_array = ArrayHelper::merge(['sklad_id' => $sklad_id], $item_array);
                    $out_amort[] = $item_array;

                    //PRIHOD
                    if ((int)$item['sklad_vid_oper'] == 2) {
                        $prihod = $prihod + (int)$item_array['ed_izmer_num'];
                    }
                    //RASHOD
                    if ((int)$item['sklad_vid_oper'] == 3) {
                        $rashod = $rashod + (int)$item_array['ed_izmer_num'];
                    }
                }
            }
        }


        //// Итого:
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Приход, всего: ' . $prihod,
        ];
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Расход, всего: ' . $rashod,
        ];

        //        ddd($out_amort);
        //        ddd($xx);
        return $out_amort;
    }


    /**
     * МОНГО. Поиск средствами МОНГО.
     * Ищем все накладные, в которых проходил Данный ТОВАР     *
     * -
     * Only RASHODNIKI
     *
     * @param $sklad_id
     * @param $group_id
     * @param $element_id
     * @return array|ActiveRecord
     * @throws NotFoundHttpException
     */
    static function Mongo_findHisory_GropElement_rash($sklad_id, $group_id, $element_id)
    {
        if (!isset($sklad_id) || !isset($group_id) || !isset($element_id)) {
            throw new NotFoundHttpException('Не достаточно входных данных.');
        }

        //            "wh_tk_amort" : "5",
        //            "wh_tk_element" : "13",
        //            "ed_izmer" : "1",
        //            "ed_izmer_num" : "2",
        //            "bar_code" : ""

        if (empty($group_id)) {
            $group_id = '';
        }
        if (empty($element_id)) {
            $element_id = '';
        }

        $xx = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                    'array_casual',
                    'dt_create',
                    'dt_update'

                    //                'array_casual.wh_tk',
                    //                'array_casual.wh_tk_element',
                    //                'array_casual.ed_izmer',
                    //                'array_casual.ed_izmer_num'
                ]
            )
            ->where(
                [
                    'AND',
                    ['wh_home_number' => $sklad_id],
                    ['array_casual.wh_tk' => ['$eq' => $group_id]],
                    ['array_casual.wh_tk_element' => ['$eq' => $element_id]],
                ]
            )
            ->asArray()
            ->all();


        //ddd($xx);
        /////////////////
        //        $group_arr= ArrayHelper::map(Spr_globam::find()
        //            ->select(['id','name'])
        //            ->orderBy(['id'])
        //            ->asArray()->all(), 'id', 'name' );
        //
        //        $element_arr= ArrayHelper::map(Spr_globam_element::find()
        //            ->select(['id','name'])
        //            ->orderBy(['id'])
        //            ->asArray()->all(), 'id', 'name' );

        $things_arr = ArrayHelper::map(
            Spr_things::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy(['id'])
                ->asArray()->all(), 'id', 'name'
        );


        //ddd($xx);
        ////////////////////
        $out_amort = [];
        $prihod = 0;
        $rashod = 0;
        foreach ($xx as $item) {
            /// Слишком полный массив
            $buf_amort = $item['array_casual'];
            foreach ($buf_amort as $item_array) {

                if (
                    $item_array['wh_tk'] == $group_id && $item_array['wh_tk_element'] ==
                    $element_id
                ) {


                    $item_array = ArrayHelper::merge(['ed_izmer_nn' => $things_arr[$item_array['ed_izmer']]], $item_array);

                    $item_array = ArrayHelper::merge(['sklad_vid_oper' => $item['sklad_vid_oper']], $item_array);
                    $item_array = ArrayHelper::merge(['id' => $item['id']], $item_array);

                    $item_array = ArrayHelper::merge(['dt_create' => $item['dt_create']], $item_array);
                    $item_array = ArrayHelper::merge(['dt_update' => $item['dt_update']], $item_array);
                    $item_array = ArrayHelper::merge(['sklad_id' => $sklad_id], $item_array);
                    $out_amort[] = $item_array;

                    //PRIHOD
                    if ((int)$item['sklad_vid_oper'] == 2) {
                        $prihod = $prihod + (int)$item_array['ed_izmer_num'];
                    }
                    //RASHOD
                    if ((int)$item['sklad_vid_oper'] == 3) {
                        $rashod = $rashod + (int)$item_array['ed_izmer_num'];
                    }
                }
            }
        }


        //// Итого:
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Приход, всего: ' . $prihod,
        ];
        $out_amort[] = [
            'id' => '',
            'sklad_vid_oper' => '',
            'wh_tk_amort_nn' => '',
            'wh_tk_element_nn' => '',
            'ed_izmer_nn' => '',
            'dt_create' => '',
            'dt_update' => '',
            'ed_izmer_num' => 'Расход, всего: ' . $rashod,
        ];


        //        ddd($out_amort);
        //        ddd($xx);

        return $out_amort;

    }


    /**
     * Добавить нормальный ИНДЕКС с понятной очередью
     * NORMALIZE ARRAY (Serg_M)
     * =
     *
     * @param $array_xx
     * @return array
     */
    static function setArrayToNormal($array_xx)
    {
        $as_normal = [];
        if (is_array($array_xx) && !empty($array_xx)) {
            foreach ($array_xx as $item) {
                if ((double)$item['ed_izmer_num'] > 0.001) {
                    $as_normal[] = $item;
                }
            }

            return $as_normal;
        } else {
            return $array_xx;
        }

    }


    /**
     * Вторая функция Сортировки
     * =
     * (немного меньше строк без ужатия)
     * АМОРТИЗАЦИЯ 1
     *
     * @param $array_xx
     * @return array
     */
    static function setArraySort1($array_xx)
    {
        if (isset($array_xx) && !empty($array_xx)) {

            $sort_names = ArrayHelper::map(
                Spr_globam_element::find()
                    ->select(
                        [
                            'id',
                            'name',
                        ]
                    )
                    ->asArray()
                    ->all(), 'id', 'name'
            );
            //        dd($sort_names);

            if (!empty($sort_names)) {
                foreach ($array_xx as $items) {
                    if (!empty($items['wh_tk_element'])) {
                        $items['name'] = $sort_names[$items['wh_tk_element']];
                        $res_array[] = $items;
                    }
                }
            }
            //dd($res_array);
            ////////GOLDEN FUNC-twice
            if (!isset($res_array)) {
                return $array_xx;
            }


            $keys = array_column($res_array, 'name');
            array_multisort($keys, SORT_ASC, $res_array);

            //dd($res_array);

            return $res_array;
        } else {
            return $array_xx;
        }

    }

    /**
     * Просто очистка от пустых строк
     * =
     * для ТАБЛИЦЫ 1 (Амортизация)
     * -
     *
     * @param $array_xx
     * @return array
     */
    static function setArrayClear($array_xx)
    {
        if (isset($array_xx) && !empty($array_xx)) {

            $res_array = [];
            foreach ($array_xx as $items) {
                if (!empty($items['wh_tk_element'])) {
                    $items['name'] = $items['wh_tk_element'];
                    $res_array[] = $items;
                }
            }

            //dd($res_array);
            return $res_array;
        } else {
            return $array_xx;
        }

    }

    /**
     * Сортировщик СПИСАНИЯ (ТАБЛИЦА 2 )
     * =
     * (УЖАЛ справочник перед работой)
     * СПИСАНИЕ 2
     *
     * @param $array_xx
     * @return array
     */
    static function setArraySort2($array_xx)
    {
        if (isset($array_xx) && !empty($array_xx)) {

            $sort_names = ArrayHelper::map(
                Spr_glob_element::find()
                    ->select(
                        [
                            'id',
                            'name',
                        ]
                    )
                    ->asArray()
                    ->all(),
                'id', 'name'
            );

            //dd($sort_names);


            if (!empty($sort_names)) {
                foreach ($array_xx as $items) {
                    if (!empty($items['wh_tk_element'])) {
                        $items['name'] = $sort_names[$items['wh_tk_element']];
                        $res_array[] = $items;
                    }
                }
            }

            //ddd($res_array);
            ////////GOLDEN FUNC-twice
            if (!isset($res_array)) {
                return $array_xx;
            }


            $keys = array_column($res_array, 'name');
            array_multisort($keys, SORT_ASC, $res_array);


            return $res_array;
        } else {
            return $array_xx;
        }

    }

    /**
     * Интерфейс Асемтай.
     * =
     * Добавить Ед.Изм + Количество в массив
     * -
     *
     * @param $array_xx
     * @return array
     */
    static function setArrayEdIzm_Kolvo($array_xx)
    {
        if (isset($array_xx) && !empty($array_xx)) {

            $res_array = [];
            foreach ($array_xx as $key => $items) {

                //                  ddd($items);

                $res_array[$key]['wh_tk_amort'] = $items['wh_tk_amort'];
                $res_array[$key]['wh_tk_element'] = $items['wh_tk_element'];
                $res_array[$key]['bar_code'] = $items['bar_code'];
                $res_array[$key]['msam_code'] = $items['msam_code'];


                //Ед.Измер
                if (empty($items['ed_izmer'])) {
                    $res_array[$key]['ed_izmer'] = '1';
                }

                //Кол-во
                if (empty($items['ed_izmer_num'])) {
                    $res_array[$key]['ed_izmer_num'] = '1';
                }
            }

            //            dd($res_array);
            return $res_array;
        } else {
            return $array_xx;
        }

    }

    /**
     * Получить номер Активного сейчас Склада
     * =
     *
     * @return mixed
     * @throws HttpException
     */
    static function getSkladIdActive()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        if (empty($session->get('sklad_'))) {
            //Какой склад открывается вместе с накладной. Укакзан в накладной
            $para_sklad_into = (int)Yii::$app->request->get('otbor'); //'4419'

            //Если АДМИН
            if (Yii::$app->user->identity->group_id === 100) {
                return $para_sklad_into;
            }


            //Получить литинг номеров всех доступных складов
            $array_sklad_ids = Yii::$app->user->identity->sklad;

            if (in_array($para_sklad_into, $array_sklad_ids)) {
                return $para_sklad_into;
            } else {
                throw new HttpException(411, 'Этот СКЛАД недоступен Вам. ', 5);
            }
        }
        return (int)$session->get('sklad_');
    }

    /**
     * Прописать номер Активного сейчас Склада
     * =
     *
     * @param $sklad
     * @return bool
     */
    static function setSkladIdActive($sklad)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('sklad_', $sklad);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * Прописать полный ПУТЬ с влюченными адресами страниц.  REFER
     * =
     *
     * @param $refer
     * @return bool
     */
    static function setPathRefer($refer)
    {
        //ddd(Yii::$app->request->referrer);
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('refer_', $refer);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Получить ПУТЬ с влюченными адресами страниц.  REFER
     * =
     *
     * @return mixed
     */
    static function getPathRefer()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        return $session->get('refer_');
    }

    /**
     * Прописать полный ПУТЬ с влюченными адресами страниц.  REFER + NAME
     * =
     *
     * @param string $name
     * @param $refer
     * @return bool
     */
    public static function setPathRefer_ByName($name, $refer)
    {
        //ddd(Yii::$app->request->referrer);
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('refer_' . $name, $refer);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }


    /**
     * Получить ПУТЬ с влюченными адресами страниц. REFER + NAME
     * =
     *
     * @param string $name
     * @return mixed
     */
    public static function getPathReferByName($name)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        return $session->get('refer_' . $name);
    }

    /**
     * Получить FLASH
     * =
     *
     * @param string $name
     * @return mixed
     */
    public static function getFlash($name)
    {
        $session = Yii::$app->session;
//        if (!$session->isActive) {
        $session->open();
//        }

//        ddd($_SESSION);

        return $session->getFlash('flash_' . $name);
    }

    /**
     * Записать  FLASH
     * =
     *
     * @param string $name
     * @param $refer
     * @return mixed
     */
    public static function setFlash($name, $refer)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('flash_' . $name, $refer);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }


    /**
     * Прописать номер Активного сейчас AP
     * =
     *
     * @param $ap
     * @return bool
     */
    static function setApIdActive($ap)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('ap_', $ap);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * Получить номер Активного сейчас AP
     * =
     *
     * @return mixed
     */
    static function getApIdActive()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        return (int)$session->get('ap_');
    }

    /**
     * Прописать номер Активного сейчас AP
     * =
     *
     * @param $ap
     * @return bool
     */
    static function setPeIdActive($ap)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('pe_', $ap);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * Получить номер Активного сейчас AP
     * =
     *
     * @return mixed
     */
    static function getPeIdActive()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        return (int)$session->get('pe_');
    }


    /**
     * Прописать Print PARAM
     * =
     *
     * @param $array
     * @return bool
     */
    static function setPrint_param($array)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('print_param', $array);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * Получить Print PARAM
     * =
     *
     * @return mixed
     */
    static function getPrint_param()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        return $session->get('print_param');
    }

    /**
     * Прописать PARAM SORT
     * =
     *
     * @param $str
     * @return bool
     */
    static function setSort_param($str)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('sort_param', $str);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    static function setUnivers($name, $param)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set($name, $param);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    static function Univers_close()
    {
        $session = Yii::$app->session;
        try {
            if ($session->isActive) {
                $session->closeSession();
            }
            $session->set($name, $param);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * @param $name
     * @return mixed
     */
    static function getUnivers($name)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        return $session->get($name);
    }

    /**
     * Получить  PARAM SORT
     * =
     *
     * @return mixed
     */
    static function getSort_param()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        return $session->get('sort_param');
    }

    /**
     * Прописать PARAM SORT
     * =
     *
     * @param $str
     * @return bool
     */
    static function setBetween_param($str)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('between_param', $str);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * Прописать PARAM SELECT
     * =
     *
     * @param $str
     * @return bool
     */
    static function setSelect_param($str)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set('select_param', $str);
        } catch (Exception $e) {
            return false;
        }

        return true;

    }

    /**
     * Получить  PARAM SORT
     * =
     *
     * @return mixed
     */
    static function getBetween_param()
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        return $session->get('between_param');
    }


    /**
     * Приводим только первую табицу (АМОРТИЗАЦИЯ/АСУОП)
     * к виду : Каждая запись(интелегент) записана в своей, новой строке
     *
     * @param array $array_am
     * @return array
     */
    static function setAmArrayIntelegentToStrings($array_am = [])
    {
        if (is_array($array_am)) {
            foreach ($array_am as $item) {

                //Для ИНТЕЛЕГЕНТ
                if ((int)$item['intelligent'] == 1) {
                    $x = 0;
                    $array_am_buf = $item;
                    $array_am_buf['ed_izmer_num'] = '1';

                    while ($item['ed_izmer_num'] > $x++) {
                        $as_normal[] = $array_am_buf;
                    }
                } // ДЛЯ ОСТАЛЬНЫХ
                else {
                    $as_normal[] = $item;
                }
            }

            return $as_normal;
        } else {
            return $array_am;
        }

    }

    /**
     * Умножить количество строк на количество автобусов и количество единиц Интелигент
     * =
     *
     * @param array $array_am
     * @param int $autos
     * @return array
     */
    static function setAmArrayIntelegentAll($array_am = [], $autos = 1)
    {
        //        if(!isset($autos) || empty($autos)){
        //            $autos=1;
        //        }

        if (is_array($array_am)) {
            foreach ($array_am as $item) {

                //					$mnnn = (int) $autos;
                //Для ИНТЕЛЕГЕНТ
                if ((int)$item['intelligent'] == 1) {
                    $x = 0;
                    $array_am_buf = $item;
                    $array_am_buf['ed_izmer_num'] = '1';
                    $maxxx = $item['ed_izmer_num'] * $autos;
                    while ($maxxx > $x++) {
                        $as_normal[] = $array_am_buf;
                    }
                } // ДЛЯ ОСТАЛЬНЫХ
                else {

                    $as_normal[] = [
                        'wh_tk_amort' => $item['wh_tk_amort'],
                        // Amort
                        'wh_tk' => $item['wh_tk'],
                        // TK
                        'wh_tk_element' => $item['wh_tk_element'],
                        'intelligent' => $item['intelligent'],
                        'ed_izmer' => $item['ed_izmer'],
                        'ed_izmer_num' => $item['ed_izmer_num'] * $autos,
                        'bar_code' => $item['bar_code'],
                    ];

                    //ddd($item);
                }
            }

            return $as_normal;
        }


        return $array_am;

    }


    /**
     * Для Виктора.
     * Номер накладной по Штрихкоду в диапазоне дат.
     * =
     * Далее - поиск самой накладной целиком
     *
     * @param $bar_code
     * @param $sklad
     * @return array|ActiveRecord|null
     */
    static function Find_number_waybill_by_barcode($bar_code, $sklad)
    {
        $model = static::find()
            ->where(
                ['AND',
                    ['array_tk_amort.bar_code' => ['$eq' => $bar_code]],
                    ['wh_home_number' => $sklad]
                ]
            )
            ->orderBy('dt_create_timestamp DESC')
            //->asArray()
            ->one();

        //ddd($model);
        return $model->id;

    }

    /**
     * Версия ПРОСТАЯ. (Далее будет с номером СКЛАДА и диапазоном ДАТ)
     * Для Виктора. Поиск в накладных Этого склада по Штрихкоду в диапазоне дат.
     * =
     * Далее проставляется галочка-КРЫЖ
     *
     * @param $bar_code
     * @return array|ActiveRecord|null
     */
    static function Find_waybill_by_barcode($bar_code)
    {
        return static::find()
            ->where(['array_tk_amort.bar_code' => ['$eq' => $bar_code]])
            ->orderBy('dt_create_timestamp DESC')
            //->asArray()
            ->one();

    }

    /**
     * Поиск по всем накладным. Ищем ИД склада.
     * =
     * Возвращает Массив накладных
     * @param $wh_home_number
     * @return array
     */
    static function findArrayAll_by_idSprWhElement($wh_home_number)
    {
        return ArrayHelper::getColumn(
            static::find()
                ->where(
                    [
                        'OR',
                        ['wh_home_number' => (string)$wh_home_number],
                        ['wh_home_number' => (int)$wh_home_number],
                        ['wh_debet_element' => (int)$wh_home_number],
                        ['wh_destination_element' => (int)$wh_home_number],
                        ['wh_destination_element_cs' => (int)$wh_home_number],
                    ]
                )
                ->asArray()->all(), 'id'
        );
    }

    /**
     * Поиск по всем накладным. Ищем ИД склада.
     * =
     * Возвращает количество накладных
     * =
     * @param $wh_home_number
     * @return int
     * @throws \yii\mongodb\Exception
     */
    static function findCountAll_by_idSprWhElement($wh_home_number)
    {
        return static::find()
            ->where(
                [
                    'OR',
                    ['wh_home_number' => (string)$wh_home_number],
                    ['wh_home_number' => (int)$wh_home_number],
                    ['wh_debet_element' => (int)$wh_home_number],
                    ['wh_destination_element' => (int)$wh_home_number],
                    ['wh_destination_element_cs' => (int)$wh_home_number],
                ]
            )
            ->count();
    }

    /**
     * Поиск по всем накладным. Ищем ИД склада.
     * =
     * Возвращает количество накладных
     * =
     * @param $by_sprwh_id
     * @return array
     */
    static function arraySklad_ids($by_sprwh_id)
    {
        return ArrayHelper::getColumn(static::find()
            ->where(
                [
                    'OR',
                    ['wh_home_number' => (int)$by_sprwh_id],
                    ['wh_debet_element' => (int)$by_sprwh_id],
                    ['wh_destination_element' => (int)$by_sprwh_id],
                ]
            )
            ->all(), 'id');
    }


    /**
     *  РеМОНТ Всех ASUOP GROUP=6. Заменяем на 7
     * =
     *
     * @return int
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    static function Remont_am_6()
    {
        $array_list = ArrayHelper::getColumn(Sklad::find()
            ->where(
                ['=', "array_tk_amort.wh_tk_amort", (int)6]
            )
            ->all(), 'id');

        //
        foreach ($array_list as $item) {
            if (self::Save_model($item)) {
                echo "<br>" . $item;
            }
        }

        return 'OK';
    }


    /**
     * Подпроцедура ЗАПИСИ
     * =
     * @param $id
     * @return bool
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    protected static function Save_model($id)
    {
        ///
        $model = Sklad::find()
            ->where(['id' => (int)$id])
            ->one();
        //
        if (!$model) {
            return false;
        }
        //
        $array_amort = (array)$model->array_tk_amort;
        //
        foreach ($array_amort as $key => $ite_amort) {
            $rez_parent = Spr_globam_element::getParent_id($ite_amort['wh_tk_element']);
            if (!isset($rez_parent) || empty($rez_parent)) {
                return false;
            }
            //Полная копия
            $array_itog[$key] = $ite_amort;
            //Замена группы
            $array_itog[$key]['wh_tk_amort'] = $rez_parent;
        }

//        ddd($array_itog);
        $model->array_tk_amort = $array_itog;

        //ddd($model);
        if (!$model->update(true)) {
            return false;
        }


        return true;

    }


    /**
     * Замена wh_home_number, wh_debet_element, wh_destination_element, wh_cs_number, wh_dalee_element
     * =
     * @param $from_id
     * @param $to_id
     *
     * @return array|bool
     */
    static function update_all_ids_to_id($from_id, $to_id)
    {
        // Ответ будет массивом ответов
        $arr_rez = [];

        if ($from_id === $to_id) {
            return true;
        }


        /**
         * @var \MongoCollection $mongoCommand
         */
        $model = static::updateAll(
            ['wh_home_number' => (int)$to_id],
            ['wh_home_number' => (int)$from_id],   ///            ['id' => (int)34607],
            [
                'upsert' => false,
                'multi' => true
            ]
        );
        $arr_rez[$from_id][$to_id]['wh_home_number'] = $model;

        //
        $model = static::updateAll(
            ['wh_debet_element' => (int)$to_id],
            ['wh_debet_element' => (int)$from_id],
            [
                'upsert' => false,
                'multi' => true
            ]
        );
        $arr_rez[$from_id][$to_id]['wh_debet_element'] = $model;


        //
        $model = static::updateAll(
            ['wh_destination_element' => (int)$to_id],
            ['wh_destination_element' => (int)$from_id],
            [
                'upsert' => false,
                'multi' => true
            ]
        );
        $arr_rez[$from_id][$to_id]['wh_destination_element'] = $model;

        //
        $model = static::updateAll(
            ['wh_cs_number' => (int)$to_id],
            ['wh_cs_number' => (int)$from_id],
            [
                'upsert' => false,
                'multi' => true
            ]
        );
        $arr_rez[$from_id][$to_id]['wh_cs_number'] = $model;

        //
        $model = static::updateAll(
            ['wh_dalee_element' => (int)$to_id],
            ['wh_debet_element' => (int)$from_id],
            [
                'upsert' => false,
                'multi' => true
            ]
        );
        $arr_rez[$from_id][$to_id]['wh_dalee_element'] = $model;

        //
        //
        return $arr_rez;
    }


////
    public static function Delete_by_akt_date( $akt_str = '', $datetime = 0 )
    {
          // \Yii::$app
          // ->db
          // ->createCommand()
          // ->delete('master_contacts', ['id' => $deletableMasterContacts])
          // ->execute();

          //User::deleteAll(
      //
      $model = Sklad::find()
      ->select(
            [
                'id',
                'dt_create',
                'dt_create_timestamp',
                'array_tk_amort.intelligent',
                'array_tk_amort.bar_code',
                'tx',
                'array_count_all'
            ]
          )
      ->where(
            ['AND',
              ['==',  'tx', $akt_str],
              ['>=',  'dt_create_timestamp', $datetime ],

              ['==',  'wh_home_number', 4419], // 4419
              ['>=',  'dt_create_timestamp', 1609400000], //!!! 1609441260 1609400000

            ]
          )
            ->orderBy('id ASC')
       ->all();



        // $this->findModel($id)->delete();
    dd('');
     //
     foreach ($model  as $mod) {
          echo "".$mod->id;
          echo "  ".$mod->tx;
          echo "  ".$mod->dt_create;
          echo "  ".$mod->dt_create_timestamp;

          // echo "  ".$model->wh_home_number; // 4419

          dd('DELETED ');

          // DELETE OK !!!
          $mod->delete(); //// OK

        }

        return true;
    }


}
