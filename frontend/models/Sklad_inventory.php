<?php /** @noinspection PhpVariableVariableInspection */

namespace frontend\models;

use common\models\User;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\mongodb\ActiveRecord;
use Yii;


class Sklad_inventory extends ActiveRecord
{
    public $add_text_to_inventory_am1;
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
        return [
            Yii::$app->params['vars'],
            'sklad_inventory',
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
            'group_inventory',
            // Группа ведомостей, причастных к ОДНОЙ Инвентаризации
            'group_inventory_name',
            //

            'key_tabl',
            // величение количества строк в построителе таблиц

            'wh_home_number',
            // ид текущего склада

            'sklad_vid_oper',
            // ид текущего склада

            'prev_doc_number',
            // ид накладной источника

            'sklad_vid_oper',
            'sklad_vid_oper_name',

            'dt_create',
            'dt_update',

            'dt_create_timestamp',
            'dt_update_timestamp',

            //"dt_create_end",


            'wh_debet_top',
            'wh_debet_name',
            'wh_debet_element',
            'wh_debet_element_name',

            'wh_destination',
            'wh_destination_name',
            'wh_destination_element',
            'wh_destination_element_name',

            'user_ip',
            "user_name",
            'user_id',
            "user_group_id",

            "update_user_id",
            "update_user_name",
            'update_user_id',
            "update_user_group_id",


            "tz_id",
            "tz_name",
            "tz_date",

            "dt_to_work_signal",
            "dt_deadline",

            'dt_transfered_date',
            'dt_transfered_user_id',
            'dt_transfered_user_name',


            'array_tk_amort',
            'array_tk_amort.wh_tk_element',


            'array_tk',
            'array_casual',
            'array_bus',
            'array_count_all',

            'bar_code',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt_update_timestamp'],
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
            //                [
            //                    'dt_create_timestamp',
            //                    'integer',
            //                ],
            //                [
            //                    'dt_create_timestamp',
            //                    'default',
            //                    'value' => 1,
            //                ],

            [
                ['wh_home_number'],
                'integer',
                'min' => 1,
                'message' => 'Id_Sklad ==0... ERROR!',
            ],

            [
                ['sklad_vid_oper'],
                'integer',
                'min' => 1,
                'max' => 3,
            ],

            [
                [
                    'dt_create',
                    'dt_update',
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


            [
                [
                    'dt_create',

                    'group_inventory',
                    'group_inventory_name',

                    'wh_home_number',
                    // ид текущего склада

                    'wh_destination',
                    'wh_destination_element',

                    'user_id',
                    'user_ip',
                    "user_name",
                    "user_group_id",

                    'array_tk_amort',
                    'array_tk',
                    'array_casual',
                ],
                'safe',
            ],

            [
                ['wh_destination_element'],
                function ($attribute) {
                    if ((int)$this['wh_debet_element'] == (int)$this['wh_destination_element']) {
                        // Расходная накладная
                        if ((int)$this['sklad_vid_oper'] == 3) {
                            $this->addError(
                                $attribute,
                                'Замените склад Приемник'
                            );
                        }
                    }
                    ///ПРИХОД ТОЛЬКО(!) НА ЭТОТ СКЛАД
                    if ((int)$this['sklad_vid_oper'] == 2 &&
                        $this['wh_destination_element'] != Sklad::getSkladIdActive()) {
                        $this->addError(
                            $attribute,
                            'Склад-получатель'
                        );

                    }

                    return $this['wh_destination_element'];
                },
            ],

            [
                [
                    'dt_create',
                    'wh_destination',
                    //'wh_destination_element',
                ],
                'required',
                'message' => 'Заполнить...',
            ],

            [
                [
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
                'user_group_id',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->group_id;
                },
            ],


            [
                ['id'],
                'unique',
            ],
            [
                ['id'],
                'integer',
            ],

            //            [['group_inventory'],    'integer', 'min'   => 0  ],
            //            [['group_inventory'],    'required','message' => 'Заполнить...' ],

            //            [['group_inventory'],   function ($attribute, $params) {
            //                if (empty($this['id']) || $this['id']===0 ){
            //                    ddd ($this['id']);
            //                }
            //            } ],

            [
                ['array_tk_amort'],
                'default',
                'value' => [],
            ],
            [
                ['array_tk'],
                'default',
                'value' => [],
            ],
            [
                ['array_casual'],
                'default',
                'value' => [],
            ],


            /////
            [
                ['array_tk_amort'],
                function ($attribute) {

                    if (is_array($this->$attribute)) {

                        if (is_array($this['array_tk_amort'])) {

                            //ddd($this);

                            foreach ($this['array_tk_amort'] as $key => $item) {

                                if (isset($item['bar_code']) && !empty($item['bar_code']) &&
                                    (strlen($item['bar_code']) < 5 || strlen($item['bar_code']) > 15)) {
                                    $this->addError(
                                        $attribute,
                                        ' Строка №' . ++$key . ". "
                                        . ' Штрих-код должен содержать цифры длиной '
                                        . ' от 5 до 12'
                                        . ' (сейчас ' . strlen($item['bar_code']) . ')  '
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

                },
            ],


            /////
            /// Запрет на двойники. array_tk
            ///
            [
                ['array_tk'],
                function ($attribute) {

                    if (is_array($this->$attribute) && is_array($this['array_tk'])) {

                        //ddd( $this[ 'array_tk' ] );

                        foreach ($this['array_tk'] as $key1 => $item1) {
                            $array_1[$key1] = [
                                'wh_tk' => $item1['wh_tk'],
                                'wh_tk_element' => $item1['wh_tk_element'],

                                'name_tk' => $item1['name_tk'],
                                'name_tk_element' => $item1['name_tk_element'],
                                'ed_izmer_num' => $item1['ed_izmer_num'],

                                'name_ed_izmer' => $item1['name_ed_izmer'],
                                'ed_izmer' => $item1['ed_izmer'],
                            ];
                        }


                        /// Удаляет двойники из массива
                        //$array_1 = array_map("unserialize", array_unique(array_map("serialize", $array_1)));
                        //$this[ 'array_tk' ] = array_map( "unserialize", array_unique( array_map( "serialize", $array_1 ) ) );

                        $array_err = [];
                        $buf_array = [];

                        foreach ($array_1 as $item1) {
                            ///
                            /// Проверяем
                            ///  Только на номер Элемента
                            if (
                                (int)$buf_array['wh_tk_element'] != (int)$item1['wh_tk_element']
                            ) {

                                $array_2[] = [
                                    'wh_tk' => $item1['wh_tk'],
                                    'wh_tk_element' => $item1['wh_tk_element'],

                                    'name_tk' => $item1['name_tk'],
                                    'name_tk_element' => $item1['name_tk_element'],
                                    'ed_izmer_num' => $item1['ed_izmer_num'],

                                    'name_ed_izmer' => $item1['name_ed_izmer'],
                                    'ed_izmer' => $item1['ed_izmer'],
                                ];
                            } else {

                                //	ddd( $item1 );

                                $array_err[] = [
                                    'wh_tk' => $item1['wh_tk'],
                                    'wh_tk_element' => $item1['wh_tk_element'],

                                    'name_tk' => $item1['name_tk'],
                                    'name_tk_element' => $item1['name_tk_element'],

                                    'ed_izmer_num' => $item1['ed_izmer_num'],
                                    'name_ed_izmer' => $item1['name_ed_izmer'],

                                ];

                            }

                            unset($buf_array);
                            $buf_array = (array)$item1;
                        }


                        $this['array_tk'] = $array_2;


                        if (isset($array_err) && !empty($array_err)) {
                            //ddd($this);

                            $str = [];
                            foreach ($array_err as $item_err) {
                                $str[] = '[ Двойники: '
                                    . ' ' . $item_err['name_tk']
                                    . ' - ' . $item_err['name_tk_element']
                                    . ' = ' . $item_err['ed_izmer_num']
                                    . ' ' . $item_err['name_ed_izmer']
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
            'group_inventory' => '№ группы',
            'group_inventory_name' => 'Название группы',

            'sklad_vid_oper' => 'ВидОп',
            'sklad_vid_oper_name' => 'Вид операции-имя',

            'dt_create' => 'Дата создания',
            'dt_update' => 'Дата исправления',

            'dt_create_timestamp' => 'Дата создания',
            'dt_update_timestamp' => 'Дата исправления',

            /// DOP Elements
            'dt_start' => 'Начало',
            'dt_stop' => 'Окончание',
            'to_print' => 'На Печать',


            'user_id' => 'user_id',
            'user_name' => 'Мат.отв',
            //'Автор',
            'user_ip' => 'IP',
            'user_group_id' => 'user_group_id',

            'update_user_id' => 'update_user_id',
            'update_user_name' => 'update_user_name',
            // Update
            'update_user_ip' => 'ip',
            'update_user_group_id' => 'group_id',


            'wh_home_number' => 'ID',

            'wh_destination' => 'Группа складов',
            'wh_destination_name' => 'Группа складов',
            'wh_destination_element' => 'Склад',
            'wh_destination_element_name' => 'Склад',


            'array_tk_amort' => 'Амортизация',
            'array_tk' => 'Списание',
            'array_casual' => 'Расходные материалы',
            'array_bus' => 'ПЕ',
            'array_count_all' => 'Строк',
            //'Всего строк',

            'comments' => 'Коменты',
        ];

    }

    /**
     * Связка с Таблицей  Sprwhelement
     * -
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_destination_element']);
    }

    /**
     * Поиск по заданной базе
     * static::findOne($id)
     *
     * @param $id
     *
     * @return Sklad_inventory|null
     * @throws ExitException
     */
    public static function findModel($id)
    {
        if (($model = static::findOne($id)) == null) {
            throw new ExitException('Sklad_inventory. Find(). ERROR');
        } else {
            return $model;
        }


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
        if (($model = static::find()->where(['id' => (integer)$id])->one()) !== null) {
            return $model;
        }

        throw new ExitException('Sklad_inventory. findModelDouble(). Errror');
    }

    /**
     * RELATIONS
     * -
     *
     * @return ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(
            User::className(),
            ['group_id' => 'user_group_id']
        );
    }

    public function getTz()
    {
        return $this->hasOne(Tz::className(), ['id' => 'tz_id']);
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
     * Получаем список-массив ИД-задействованых WH-ELEMENT
     *=
     * в РЕЗУЛЬТАТАХ Инвентаризации
     * -
     *
     * @return array
     */
    static function ArrayUniq_WhElements_Ids()
    {
        $xx = self::find()->distinct('wh_destination_element');

        //ddd($xx);

        foreach ($xx as $item) {
            $array[(int)$item] = (int)$item;
        }

        return $array;
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

}
