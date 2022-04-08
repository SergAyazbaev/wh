<?php

namespace frontend\models;

//use common\models\User;

use Yii;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;


/**
 * @property int id
 * @property int wh_home_number
 * @property date dt_create
 * @property date dt_update
 * @property int dt_create_timestamp
 * @property int dt_update_timestamp
 *
 * @property int wh_debet_top
 * @property int wh_debet_element
 * @property int wh_destination
 * @property int wh_destination_element
 *
 * @property string wh_debet_name
 * @property string wh_debet_element_name
 * @property string wh_destination_name
 * @property string wh_destination_element_name
 *
 * @property int sklad_vid_oper
 * @property string sklad_vid_oper_name
 *
 * @property int count_str
 * @property int calc_minus
 * @property int calc_errors
 * @property int array_count_all
 * @property int itogo_actions
 * @property int itogo_strings
 * @property int itogo_things
 *
 * @property int user_id
 * @property string user_name
 *
 * @property array array_tk_amort
 * @property array array_tk
 *
 */
class Sklad_inventory_cs extends ActiveRecord
{
    public $add_text_to_inventory_am1;
    public $add_text_to_inventory_park_park;

    public $add_text_to_inventory_am;

    public $add_text_to_inventory;
    public $add_array_to_delete;

    public $erase_array;     //// Удаление строк ПАКЕТОМ


    /**
     * NAME COLLECTION
     * -
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'sklad_cs_inventory'];
    }

    /**
     * @return array
     */
    public function attributes()
    {

        return [

            '_id',
            'id',

            'empty_cs',   //Накладная -  пустышка, Заготовка под Инвентаризацию

            'wh_home_number',   // ид текущего склада

            'sklad_vid_oper',   // ид текущего склада

            'sklad_vid_oper',
            'sklad_vid_oper_name',

            'dt_create',
            'dt_update',

            'group_inventory',
            'group_inventory_name',
            'user_id',
            'user_name',
            'wh_debet_top',
            'wh_debet_element',
            'wh_debet_name',
            'wh_debet_element_name',
            'array_tk',
            'array_casual',

            'dt_create_timestamp',
            'dt_update_timestamp',

            'wh_destination',
            'wh_destination_name',
            'wh_destination_element',
            'wh_destination_element_name',

            'array_tk_amort',

            'count_str',   //Количество строк в накладной
            'itogo_things', //Количество штук в накладной
            'calc_minus',     //// Разница строк для поиска ошибочных накладных
            'calc_errors',    //// Сумма всех ошибок в накладной
            ///
            ///
        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [

            '_id' => 'ID',
            'id' => '№',

            'empty_cs' => 'Пусто',

            'group_inventory' => '№ группы',
            'group_inventory_name' => 'Название группы',

            'sklad_vid_oper' => 'ВидОп',
            'sklad_vid_oper_name' => 'Вид операции-имя',

            'dt_create' => 'Дата создания',
            'dt_create_timestamp' => 'Дата накладной',
            'dt_update' => 'Дата исправления',
            'dt_update_timestamp' => 'Дата ред.',

            /// DOP Elements
            'dt_start' => 'Начало',
            'dt_stop' => 'Окончание',
            'to_print' => 'На Печать',

            'wh_home_number' => 'ID',

            'wh_destination' => 'Группа складов (АП)',
            'wh_destination_name' => 'Группа складов',
            'wh_destination_element' => 'Склад ',
            'wh_destination_element_name' => 'Склад',

            'user_name' => 'Автор',


            'array_tk_amort' => 'Амортизация',

            'array_count_all' => 'Строк',  //'Всего строк',
            'count_str' => 'Строк',  //'Всего строк',
            'itogo_things' => 'Штук',  //'Всего строк',


            'comments' => 'Коменты',
            'calc_minus' => 'Разность',
            'calc_errors' => 'Ошибки',

            'n_gos',
            'old_gos',
            'do_timestamp',

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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt_update_timestamp'], /// мешало dt_create_timestamp
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dt_update_timestamp'],
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

            ///[['a1', 'a2'], 'unique', 'targetAttribute' => ['a1', 'a2']]

            ///Комбинация "01.05.2020 00:00:01"-"1255" параметров
            ///  Дата создания и Склад уже существует.
            [['dt_create_timestamp'], 'unique', 'targetAttribute' => [
                'dt_create_timestamp', 'wh_home_number',
            ]],

            //
            ['calc_errors', 'default', 'value' => 0],
            //
            ['empty_cs', 'default', 'value' => 1],
            //[['wh_home_number'], 'integer', 'min' => 1, 'message' => 'Id_Sklad ==0... ERROR!'],
            //
            [['sklad_vid_oper'], 'integer', 'min' => 1, 'max' => 3],

            [[
                'dt_create_timestamp',
                'calc_minus'
            ], 'integer'],

            [['dt_create', 'dt_update'], 'date', 'format' => 'php:d.m.Y H:i:s'],
            //
            ['dt_create', 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }],
            //
            ['dt_update', 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }],

            [[
                'dt_create',
                'dt_create_timestamp',

                'group_inventory',
                'group_inventory_name',

                'wh_home_number',  // ид текущего склада

                'wh_destination',
                'wh_destination_element',


                'array_tk_amort',
                'calc_minus',
                'calc_errors',
                'itogo_things',

            ],
                'safe'
            ],

//            [['wh_destination_element'], function ($attribute, $params) {
//                if ((int)$this['wh_debet_element'] == (int)$this['wh_destination_element']) {
//                    // Расходная накладная
//                    if ((int)$this['sklad_vid_oper'] == 3) {
//                        $this->addError(
//                            $attribute,
//                            'Замените склад Приемник'
//                        );
//                    }
//                }
//                ///ПРИХОД ТОЛЬКО(!) НА ЭТОТ СКЛАД
//                if ((int)$this['sklad_vid_oper'] == 2 &&
//                    $this['wh_destination_element'] != self::getSkladIdActive()) {
//                    $this->addError(
//                        $attribute,
//                        'Склад-получатель'
//                    );
//
//                }
//
//                return $this['wh_destination_element'];
//            }],

            [[
                'dt_create',
                'wh_destination',
                //'wh_destination_element',
            ], 'required', 'message' => 'Заполнить...'],

            [[
                'wh_destination',
                'wh_destination_element'
            ], 'integer'],


//            ['user_ip', 'default', 'value' => function () {
//                return Yii::$app->request->getUserIP();
//            }],

//            ['user_id', 'default', 'value' => function () {
//                return Yii::$app->user->identity->id;
//            }],

//            ['user_name', 'default', 'value' => function () {
//                return Yii::$app->user->identity->username;
//            }],

//            ['user_group_id', 'default', 'value' => function () {
//                return Yii::$app->user->identity->group_id;
//            }],


            [['id'], 'unique'],
            [['id'], 'integer'],

            [['sklad_vid_oper_name'], 'string', 'max' => 255],

            [['array_tk_amort'], 'default', 'value' => []],

            /////
            [['array_tk_amort'], function ($attribute, $params) {

                //if (is_array($this->attribute)) {
                if (is_array($this->$attribute)) {

                    if (is_array($this['array_tk_amort'])) {

                        foreach ($this['array_tk_amort'] as $key => $item) {


                            if (isset($item['bar_code']) && !empty($item['bar_code']) &&
                                (strlen($item['bar_code']) < 5 || strlen($item['bar_code']) > 15)) {


                                $this->addError(
                                    $attribute,
                                    ' Строка №' . ++$key . ". "
                                    . ' Штрих-код должен содержать цифры длиной '
                                    . ' от 5 до 12'
                                    . ' (сейчас ' . strlen($item['bar_code']) . ')  <br>' . $item['bar_code']
                                );
                            }

                            if (isset($item['msam_code']) && !empty($item['msam_code']) &&
                                (strlen($item['msam_code']) < 5 || strlen($item['msam_code']) > 12)) {
                                $this->addError(
                                    $attribute,
                                    ' Строка №' . ++$key . ". "
                                    . ' MSAM должен содержать цифры длиной '
                                    . ' от 5 до 12'
                                    . ' (сейчас ' . strlen($item['msam_code']) . ')  '

                                );
                            }


                        }
                    }

                }

            }]


            /////

//            [['wh_destination_element'], function ($attribute, $params) {
//
//                //                if (is_array($this->$attribute)) {
//                //
//                ////                    if (is_array($this['array_tk_amort'])) {
//                ////                        foreach ($this['array_tk_amort'] as $key => $item) {
//                //
//                //
//                ////                            if (isset($item['bar_code']) && !empty($item['bar_code']) &&
//                ////                                (strlen($item['bar_code']) < 5 || strlen($item['bar_code']) > 12))
//                ////                            {
//                ////                                $this->addError(
//                ////                                    $attribute,
//                ////                                    ' Строка №'.++$key.". "
//                ////                                    .' Штрих-код должен содержать цифры длиной '
//                ////                                    .' от 5 до 12'
//                ////                                    .' (сейчас '.strlen($item['bar_code']).')  '
//                ////                                );
//                ////                            }
//                //
//                //
//                ////                        }
//                ////                    }
//                //
//                //
//                //                }
//
//            }],

//            ///
//            [['wh_home_number'], function ($attribute, $params) {
//                ddd($this);
//
//                ddd($params);
//                ddd($attribute);
//            }],

        ];
    }


    /**
     * @param $id
     *
     * @return Sklad_inventory_cs|null
     * @throws ExitException
     */
    public static function findModel($id)
    {
        if (($model = static::findOne($id)) == null) {
            throw new ExitException('Sklad_inventory_cs. findModel(). id=' . $id . ' ERROR');
        } else {
            return $model;
        }
    }

    /**
     * Следующий номер ИД
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
     * Связка с Таблицей  Sprwhelement_change  (Справочник ЗАМЕН ГОС НОМЕРОВ)
     * -
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_change()
    {
        return $this->hasOne(Sprwhelement_change::className(), ['parent_id' => 'wh_home_number']);
    }

    /**
     *
     */
    public function getSprwhelement_name()
    {
        return $this->sprwhelement_home_number['name'];
    }


    /**
     * Поиск по заданной базе
     * where(['id'=>(integer) $id])->one()
     *
     * @param $id
     *
     * @return array|null|ActiveRecord
     * @throws ExitException
     */
    static function findModelDouble($id)
    {
        $xx = static::find()
            ->where(['=', 'id', (int)$id])
            ->one();

        if (!isset($xx)) {
            throw new ExitException('sklad_cs_inventory. findModelDouble(). ERROR iD=' . $id);
        }
        if (empty($xx)) {
            throw new ExitException('sklad_cs_inventory. Empty');
        }
        return $xx;
    }


    /**
     * Для инвентаризации
     *
     * Создавем список активных ИД (Стартовых Игнвентаризаций)
     * =
     * Которые есть в таблице Базовых Инвентаризаций
     *-
     * @return array
     */
    static function Array_inventory_ids()
    {
        return static::find()->distinct('wh_home_number');
    }


    /**
     * Получаем список-массив ИД-задействованых WH-TOP
     *=
     * в РЕЗУЛЬТАТАХ Инвентаризации
     * -
     *
     * @return string
     */
    static function ArrayUniq_Wh_Ids()
    {
        $xx = self::find()->distinct('wh_destination');
        if (empty($xx)) {
            return '';
        }

        foreach ($xx as $item) {
            $array[(int)$item] = (int)$item;
        }

        return $array;
    }

    /**
     * Связка с Таблицей  Sprwhelement
     * -
     * @return ActiveQueryInterface
     */
    public function getSprwhelement()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_destination_element']);
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_wh_destination_element()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_destination_element']);
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwh()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'wh_destination_element']);
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwh_wh_destination()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'wh_destination']);
    }


    /**
     * RELATIONS
     * -
     * @return ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['group_id' => 'user_group_id']);
    }


    /**
     * Получаем НАКЛАДНУЮ ИНВЕНТАРИЗАЦИИ по СКЛАДУ
     * =
     * findInventory_by_id
     * -
     *
     * @param $sklad
     *
     * @return array|ActiveRecord|null
     */
    static function findInventory_by_id($sklad)
    {
        return static::find()
            ->where(
                ['AND',
                    ['wh_home_number' => (int)$sklad],
                    ['!=', 'empty_cs', 1],

                    ['OR',
                        ['sklad_vid_oper' => (int)Sklad::VID_NAKLADNOY_INVENTORY],
                        ['sklad_vid_oper' => (string)Sklad::VID_NAKLADNOY_INVENTORY]
                    ]
                ]
            )
            //->orderBy( [ 'id DESC' ] )
            ->orderBy(['dt_create_timestamp DESC'])
            ->asArray()
            ->one();
    }


    /**
     * На Входе: МОДЕЛЬ;  На выходе: Массив с развернутыми НАКЛАДНЫМИ
     * -
     *
     * @param $model
     *
     * @return array
     */
    static function in_model_out_array($model)
    {
        $out_array = [];
        //ddd($model);

        $spr_globam_top = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');
        $spr_globam_elem = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');

//        $spr_glob_top = ArrayHelper::map(Spr_glob::find()->all(), 'id', 'name');
//        $spr_glob_elem = ArrayHelper::map(Spr_glob_element::find()->all(), 'id', 'name');

        //ddd($spr_glob_elem);


        foreach ($model as $item) {
            // ddd($item);

            if (isset($item['array_tk_amort']) && !empty($item['array_tk_amort']))
                foreach ($item['array_tk_amort'] as $item_amort) {
                    //ddd($item_amort);

                    $out_array[] = [
                        'id' => $item['id'],
                        'sklad_vid_oper' => $item['sklad_vid_oper'],
                        'wh_home_number' => $item['wh_home_number'],
//                        'wh_debet_top' => $item['wh_debet_top'],
//                        'wh_debet_name' => $item['wh_debet_name'],
//                        'wh_debet_element' => $item['wh_debet_element'],
//                        'wh_debet_element_name' => $item['wh_debet_element_name'],
                        'wh_destination' => $item['wh_destination'],
                        'wh_destination_name' => $item['wh_destination_name'],
                        'wh_destination_element' => $item['wh_destination_element'],
                        'wh_destination_element_name' => $item['wh_destination_element_name'],
                        'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],
                        //                        'tz_id'=>           $item['tz_id'],
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
                        //                        'array_count_all'=>$item['array_count_all'],
//                        'dt_transfered_date' => $item['dt_transfered_date'],
                        //                    'dt_transfered_user_id'=>$item['dt_transfered_user_id'],
                        //                    'dt_transfered_user_name'=>$item['dt_transfered_user_name'],

                        'wh_tk_amort' => (isset($spr_globam_top[$item_amort['wh_tk_amort']]) ? $spr_globam_top[$item_amort['wh_tk_amort']] : ""),
                        'wh_tk_element' => (isset($spr_globam_elem[$item_amort['wh_tk_element']]) ? $spr_globam_elem[$item_amort['wh_tk_element']] : ""),
                        'ed_izmer' => (isset($item_amort['ed_izmer']) ? $item_amort['ed_izmer'] : 0),
                        'ed_izmer_num' => (isset($item_amort['ed_izmer_num']) ? $item_amort['ed_izmer_num'] : 0),
                        'bar_code' => (isset($item_amort['bar_code']) ? $item_amort['bar_code'] : ''),
                        //'name'=>$item_amort['name'],
                    ];


                }
            /////////////////////


            unset($item_amort);
//
//            if (isset($item['array_tk']) && !empty($item['array_tk']))
//                foreach ($item['array_tk'] as $item_2) {
//                    //                ddd($item2);
//                    //                'wh_tk' => '9'
//                    //                'wh_tk_element' => '114'
//                    //                'ed_izmer' => '1'
//                    //                'ed_izmer_num' => '6'
//                    //                'name' => 'Винт с резьбой, с пресс-шайбой М4*12 мм, тарельчатая головка, оцинкованная'
//
//                    $out_array[] = [
//                        'id' => $item['id'],
//                        'sklad_vid_oper' => $item['sklad_vid_oper'],
//                        'wh_home_number' => $item['wh_home_number'],
////                        'wh_debet_top' => $item['wh_debet_top'],
////                        'wh_debet_name' => $item['wh_debet_name'],
////                        'wh_debet_element' => $item['wh_debet_element'],
////                        'wh_debet_element_name' => $item['wh_debet_element_name'],
//                        'wh_destination' => $item['wh_destination'],
//                        'wh_destination_name' => $item['wh_destination_name'],
//                        'wh_destination_element' => $item['wh_destination_element'],
//                        'wh_destination_element_name' => $item['wh_destination_element_name'],
//                        'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],
//                        'tz_id' => $item['tz_id'],
//                        //                    'tz_name'=>         $item['tz_name'],
//                        //                    'tz_date'=>         $item['tz_date'],
//                        //                    'dt_deadline'=>     $item['dt_deadline'],
//
//                        'dt_create' => $item['dt_create'],
//                        'dt_update' => $item['dt_update'],
//                        //                    'user_ip'=>$item['user_ip'],
//                        //                    'user_id'=>$item['user_id'],
//                        //                    'user_name'=>$item['user_name'],
//                        //                    'update_user_id'=>$item['update_user_id'],
//                        //                    'update_user_name'=>$item['update_user_name'],
//                        //                    'user_group_id'=>$item['user_group_id'],
//                        'array_bus' => $item['array_bus'],
//                        //                        'array_count_all'=>$item['array_count_all'],
////                        'dt_transfered_date' => $item['dt_transfered_date'],
//                        //                    'dt_transfered_user_id'=>$item['dt_transfered_user_id'],
//                        //                    'dt_transfered_user_name'=>$item['dt_transfered_user_name'],
//
//                        'wh_tk' => (isset($spr_glob_top[$item_2['wh_tk']]) ? $spr_glob_top[$item_2['wh_tk']] : ""),
//                        'wh_tk_element' => (isset($spr_glob_elem[$item_2['wh_tk_element']]) ? $spr_glob_elem[$item_2['wh_tk_element']] : ""),
//                        'ed_izmer' => (isset($item_2['ed_izmer']) ? $item_2['ed_izmer'] : 0),
//                        'ed_izmer_num' => (isset($item_2['ed_izmer_num']) ? $item_2['ed_izmer_num'] : 0),
//                        'bar_code' => (isset($item_2['bar_code']) ? $item_2['bar_code'] : ""),
//                        //'name'=>$item_amort['name'],
//                    ];
//
//                }
            ///////////////
            unset($item_2);


//            if (isset($item['array_casual']) && !empty($item['array_casual']))
//                foreach ($item['array_casual'] as $item_3) {
//                    $out_array[] = [
//                        'id' => $item['id'],
//                        'sklad_vid_oper' => $item['sklad_vid_oper'],
//                        'wh_home_number' => $item['wh_home_number'],
////                        'wh_debet_top' => $item['wh_debet_top'],
////                        'wh_debet_name' => $item['wh_debet_name'],
////                        'wh_debet_element' => $item['wh_debet_element'],
////                        'wh_debet_element_name' => $item['wh_debet_element_name'],
//                        'wh_destination' => $item['wh_destination'],
//                        'wh_destination_name' => $item['wh_destination_name'],
//                        'wh_destination_element' => $item['wh_destination_element'],
//                        'wh_destination_element_name' => $item['wh_destination_element_name'],
//                        'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],
//                        'tz_id' => $item['tz_id'],
//                        //                    'tz_name'=>         $item['tz_name'],
//                        //                    'tz_date'=>         $item['tz_date'],
//                        //                    'dt_deadline'=>     $item['dt_deadline'],
//
//                        'dt_create' => $item['dt_create'],
//                        'dt_update' => $item['dt_update'],
//                        //                    'user_ip'=>$item['user_ip'],
//                        //                    'user_id'=>$item['user_id'],
//                        //                    'user_name'=>$item['user_name'],
//                        //                    'update_user_id'=>$item['update_user_id'],
//                        //                    'update_user_name'=>$item['update_user_name'],
//                        //                    'user_group_id'=>$item['user_group_id'],
//                        'array_bus' => $item['array_bus'],
//                        //                        'array_count_all'=>$item['array_count_all'],
////                        'dt_transfered_date' => $item['dt_transfered_date'],
//                        //                    'dt_transfered_user_id'=>$item['dt_transfered_user_id'],
//                        //                    'dt_transfered_user_name'=>$item['dt_transfered_user_name'],
//
//
//                        'wh_tk' => (isset($spr_glob_top[$item_3['wh_tk']]) ? $spr_glob_top[$item_3['wh_tk']] : ""),
//                        'wh_tk_element' => (isset($spr_glob_elem[$item_3['wh_tk_element']]) ? $spr_glob_elem[$item_3['wh_tk_element']] : ""),
//                        'ed_izmer' => (isset($item_3['ed_izmer']) ? $item_3['ed_izmer'] : 0),
//                        'ed_izmer_num' => (isset($item_3['ed_izmer_num']) ? $item_3['ed_izmer_num'] : 0),
//
//                        'bar_code' => (isset($item_3['bar_code']) ? $item_3['bar_code'] : ""),
//
//                        //'name'=>$item_amort['name'],
//                    ];
//
//                }

            ///////////////
            unset($item_3);


        }

        //ddd($out_array);
        return $out_array;
    }


    /**
     * Получить Список-Дроп только Используемых номеров Складов
     * -
     * @param int $id_AP
     * @return array
     */
    static function ArrayUniq_wh_numbers($id_AP = -1)
    {
        if ($id_AP == -1) {
            return [0 => 'Выбрать АП'];
        }
        $xx = self::find()
            ->where(['wh_destination' => (int)$id_AP])
            ->distinct('wh_home_number');

        //ddd($xx);

        $xx2 = [0];
        if (isset($xx) && !empty($xx)) {
            foreach ($xx as $item) {
                $xx2[$item] = $item;
            }
        }

        $xx3 = ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->asArray()
                ->where(
                    [
                        'AND',
                        ['final_destination' => 1],
                        ['id' => $xx2],
                    ])
                ->orderBy(['name'])
                ->all(),
            'id', 'name');

        //ddd($xx3);
        return [0 => 'Выбрать все...'] + $xx3;

    }

    /**
     * Получить Список-Дроп только Используемых номеров Складов только для данного АП
     * -
     * @param $id_AP
     * @return array
     */
    static function ArrayUniq_wh_numbers_by_id($id_AP)
    {

        $xx3 = ArrayHelper::map(
            Sprwhelement::find()
                ->where(
                    [
                        'AND',
                        ['final_destination' => 1],
                        ['parent_id' => (int)$id_AP],
                    ])
                ->orderBy(['name'])
                ->all(),
            'id', 'name');


        return [0 => 'Выбрать все...'] + $xx3;

    }


    /**
     * Получить Список-Дроп
     * wh_destination
     * -
     * @return array
     */
    static function ArrayUniq_destination()
    {
        $xx = self::find()->distinct('wh_destination');

        $xx3 = ArrayHelper::map(
            Sprwhtop::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->asArray()
                ->where(['id' => $xx])
                ->orderBy(['name'])
                ->all(),
            'id', 'name');

        return [0 => 'Выбрать все...'] + $xx3;

    }


    /**
     * Получить Список-Дроп
     * wh_destination_element
     * -
     * @return array
     */
    static function ArrayUniq_destination_element()
    {
        $xx = self::find()->distinct('wh_destination_element');
        //ddd($xx);

        $xx2 = [0];
        if (isset($xx) && !empty($xx)) {
            foreach ($xx as $item) {
                $xx2[(int)$item] = $item;
            }
        }

        $xx3 = ArrayHelper::map(
            Sprwhelement::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->asArray()
                ->where(['id' => $xx2])
                ->orderBy(['name'])
                ->all(),
            'id', 'name');

        //ddd($xx3);
        return [0 => 'Выбрать все...'] + $xx3;

    }


    /**
     * Получить Список-Дроп
     * -
     * @param int $id_PE
     * @return array
     */
    static function ArrayUniq_dates($id_PE = 0)
    {
        if ($id_PE == -1) {
            return [0 => 'Выберите АП, ПЕ'];
        }
//        ddd($id_PE);

        if ($id_PE == 0) {
            $xx = self::find()
                ->distinct('dt_create_timestamp');
        } else {
            $xx = self::find()
                ->where(['wh_home_number' => (int)$id_PE])
                ->distinct('dt_create_timestamp');
        }

        $xx2 = [0];
        if (isset($xx) && !empty($xx)) {
            foreach ($xx as $item) {
                $xx2[(int)$item] = date('d.m.Y H:i:s', $item);
            }
        }
        //ddd($xx2);

        return [0 => 'Выбрать все...'] + $xx2;

    }

    /**
     * Получить Список-Дроп
     * -
     * @param $id_AP
     * @return array
     */
    static function ArrayUniq_dates_byAP($id_AP)
    {
        if ($id_AP == -1) {
            return [0 => 'Выберите АП, ПЕ'];
        }
        if ($id_AP == 0) {
            $xx = self::find()
                ->distinct('dt_create_timestamp');
        } else {
            $xx = self::find()
                ->where(['wh_destination' => (int)$id_AP])
                ->distinct('dt_create_timestamp');
        }

        $xx2 = [0];
        if (isset($xx) && !empty($xx)) {
            foreach ($xx as $item) {
                $xx2[(int)$item] = date('d.m.Y H:i:s', $item);
            }
        }
        //ddd($xx2);

        return [0 => 'Выбрать все...'] + $xx2;

    }


    /**
     * Получить Список 1000 накладных - ПУСТЫШЕК
     * -
     * @return array
     */
    static function ArrayEmpty_1000_inventory_cs()
    {
        return ArrayHelper::getColumn(self::find()
            ->select(['id', 'empty_cs'])
            ->where(['==', 'empty_cs', 1])
            ->orderBy('id DESC')
            ->asArray()
            ->all(),
            'id');

    }


    /**
     * Получить Список ИНВЕНТАРИЗАЦИЙ - ЦС
     * -
     * @param $id_tagret_cs
     * @return array
     */
    static function ArrayOne_Last_inventory_cs($id_tagret_cs)
    {
        return static::find()
            ->where(
                ['OR',
//                ['==', 'wh_cs_number', (int)$id_tagret_cs],
                    ['==', 'wh_destination_element', (int)$id_tagret_cs],
                ]
            )
            ->orderBy('dt_create_timestamp DESC')
            ->asArray()
            ->one();
    }


    /**
     * Совмещаем Два массива,
     * НО Соблюдаем "Главенство Самого Старого". Это немного не правильно сформулировано, но учитываем(!)
     * =
     * в него доливаем "новые позиции". Но не трогаем, если уже есть.
     * -
     *
     * @param $array_old
     * @param $array_yung
     *
     * @return array
     */
    static function MergeArray_to_array($array_old, $array_yung)
    {

        if (isset($array_yung) && !empty($array_yung)) {
            foreach ($array_yung as $item_yung) {

                if (self::Array_am_find($array_old, $item_yung['wh_tk_element'])) {
                    continue;
                } else {
                    //* Дописать позицию АСУОП.
                    $array_old[] = $item_yung;
                }


            }
        }


        return $array_old;
    }


    /**
     * Поиск
     *
     * @param $array_am
     * @param $wh_tk_element
     * @return bool
     */
    static function Array_am_find($array_am, $wh_tk_element)
    {
        //ddd( $array_am);
        if (isset($array_am) && is_array($array_am)) {
            foreach ($array_am as $item_yung) {
                if ($item_yung['wh_tk_element'] == $wh_tk_element) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Читаем КРАЙНЮЮ Ежедневныю Инвентаризацию по заданному складу.
     * =
     *
     * @param $sklad_id
     * @return array
     */
    static function ArrayRead_LastInventory($sklad_id)
    {
        return self::find()
            ->where(['==', 'wh_destination_element', (int)$sklad_id])
            ->orderBy('dt_create_timestamp DESC')
            ->one();
    }

    /**
     * Удаляем блок Столбовых Инвентаризаций ЦС за один день
     * -
     * @param $day_timestamp
     * @return bool
     * @throws ExitException
     * @throws StaleObjectException
     */
    static function Delete_by_day($day_timestamp)
    {
        ///
        /// Получаем Массив - АЙДИшек (_id !!!!) на эту дату
        ///
        $array_Ids_by_one_day = static::ArrayIds_by_one_day($day_timestamp);

        foreach ($array_Ids_by_one_day as $id) {

            $res = static::findModel($id)->delete();
            if ($res == 1) {
                $arr_res[] = $id;
            } else {
                $err[] = $id;
            }

        }

        if (isset($err)) {
            return false;
        }

        return true;
    }

    /**
     * Возвращает массив номеров CS
     * В указаный период дат
     * =
     * @param $day_timestamp
     * @return array
     */
    static function ArrayIds_by_one_day($day_timestamp)
    {
        $date_start = strtotime(date('d.m.Y 00:00:00', $day_timestamp));
        $date_stop = strtotime(date('d.m.Y 23:59:59', $day_timestamp));

        return ArrayHelper::getColumn(static::find()
            ->where(
                ['AND',
                    ['>=', 'dt_create_timestamp', $date_start],
                    ['<=', 'dt_create_timestamp', $date_stop]
                ]
            )
//            ->limit(10)
            ->asArray()
            ->all(), '_id');
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
    public static function findCountAll_by_idSprWhElement($wh_home_number)
    {
        return static::find()
            ->where(
                [
                    'OR',
                    ['wh_home_number' => (int)$wh_home_number],
                    ['wh_destination_element' => (int)$wh_home_number],
                ]
            )
            ->count();
    }

    /**
     * Поиск по всем INVENTORY. Ищем ИД склада.
     * =
     * Возвращает Массив накладных
     * @param $wh_home_number
     * @return array
     */
    public static function findArrayAll_by_idSprWhElement($wh_home_number)
    {
        return ArrayHelper::getColumn(
            static::find()
                ->where(
                    [
                        'OR',
                        ['wh_home_number' => (int)$wh_home_number],
                        ['wh_destination_element' => (int)$wh_home_number],
                    ]
                )
                ->asArray()->all(), 'id'
        );
    }


    /**
     * Поиск по collection-SKLAD
     * ищем любую строку внутри массива "array_tk_amort"
     *
     * @param $bar_code
     * @param $array_select
     * @return array|ActiveRecord
     */
    static function BarCode_to_Nakladnye($bar_code, $array_select)
    {
        return static::find()
            ->select($array_select)
            ->select(['id', 'dt_create', 'array_tk_amort'])
            ->where(
                ['OR',
                    ['array_tk_amort.bar_code' => $bar_code],
                    ['array_tk_amort.bar_code' => (int)$bar_code],
                    ['array_tk_amort.bar_code' => (string)$bar_code],

//                    [ 'array_tk.bar_code'=> $bar_code],
//                    [ 'array_tk.bar_code'=> (int)$bar_code],
//                    ['like', 'array_tk.bar_code', (string)$bar_code],
//
//                    [ 'array_casual.bar_code'=> $bar_code],
//                    [ 'array_casual.bar_code'=> (int)$bar_code],
//                    ['like', 'array_casual.bar_code', (string)$bar_code],

                ]
            )
            ->asArray()
//            ->orderBy('dt_create_timestamp')
            ->all();


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
                    ['wh_destination_element' => (int)$by_sprwh_id],
                ]
            )
            ->all(), 'id');
    }


//    /**
//     * CS
//     * Замена wh_home_number, wh_destination_element
//     * =
//     * @param $from_id
//     * @param $to_id
//     * @return bool
//     */
//    public static function update_all_ids_to_id($from_id, $to_id)
//    {
//        if ($from_id === $to_id) {
//            return false;
//        }
//
//        /**
//         * @var \MongoCollection $mongoCommand
//         */
//        $model = static::updateAll(
//            ['wh_home_number' => (int)$to_id],
//            ['wh_home_number' => (int)$from_id]
////            ['upsert' => true]
//
//        );
//        //
//        $model = static::updateAll(
//            ['wh_destination_element' => (int)$to_id],
//            ['wh_destination_element' => (int)$from_id]
////            ['upsert' => true]
//        );
//
//        return true;

//    }

    /**
     * CS
     * Замена wh_home_number, wh_destination_element
     * =
     * @param $ids
     * @return bool
     */
    public static function deleteAll_ids($ids)
    {
        //OK!!!! Работает!
        return Sklad_inventory_cs::deleteAll(
            ['wh_home_number' => $ids],
            [
                'upsert' => false,
                'multi' => true
            ]
        );
    }

}
