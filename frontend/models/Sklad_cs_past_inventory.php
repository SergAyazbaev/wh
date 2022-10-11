<?php

namespace frontend\models;

use common\models\User;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use Yii;


class Sklad_cs_past_inventory extends ActiveRecord
{

    public $add_text_to_inventory_am;
    public $add_text_to_inventory;

    public $erase_array;     //// Удаление строк ПАКЕТОМ

    /**
     * NAME COLLECTION
     * -
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'sklad_cs_past_inventory'];
    }

    /**
     * Создаю ТаймШТАМП для всех операций Создания накладных и Редактирования
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt_create_timestamp', 'dt_update_timestamp'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dt_update_timestamp'],
                ]
            ]

        ];

    }

    public function attributes()
    {
        return [
            '_id',
            'id',
            'wh_home_number',   // ид текущего склада

            'dt_create',
            'dt_update',

            'dt_start',
            'dt_start_timestamp',

            'dt_create_timestamp',
            'dt_update_timestamp',

            'itogo_actions', // 'Общее количество деействий',
            'itogo_strings', // Общее количество сторок
            'itogo_things',  // Общее количество штук


            'group_inventory', // Группа ведомостей, причастных к ОДНОЙ Инвентаризации
            'group_inventory_name', //

            'wh_destination',
            'wh_destination_name',
            'wh_destination_element',
            'wh_destination_element_name',
            'wh_destination_gos',
            'wh_destination_bort',

            'array_tk_amort',
            'array_tk',
            'array_casual',

            'array_count_all',

            'prihod_num',
            'rashod_num',

        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['wh_home_number'],
                'integer', 'min' => 1, 'message' => 'Id_Sklad ==0... ERROR!'],

            [['dt_create'], 'date', 'format' => 'php:d.m.Y H:i:s'
            ],

            ['dt_create', 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }
            ],


            [[
                'dt_create',

                'group_inventory',
                'group_inventory_name',

                'wh_home_number',  // ид текущего склада

                'wh_destination',
                'wh_destination_element',
                'wh_destination_gos',
                'wh_destination_bort',


                'array_tk_amort',
                'array_tk',
                'array_casual',
            ],
                'safe'
            ],


            [[
                'dt_create',
                'wh_destination',
                'wh_destination_element',
            ], 'required', 'message' => 'Заполнить...'
            ],

            [[
                'wh_destination',
                'wh_destination_element'
            ], 'integer'],


            [['_id'], 'unique'],
            [['id'], 'unique'],
            [['id'], 'integer'],


            [['array_tk_amort'], 'default', 'value' => []],
            [['array_tk'], 'default', 'value' => []],
            [['array_casual'], 'default', 'value' => []],

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


            'dt_create' => 'Дата создания',

            /// DOP Elements
            'dt_start' => 'Начало периода',
            'dt_create_timestamp' => 'Создан. Дата',

            'wh_home_number' => 'ID',

            'wh_destination' => 'Группа складов',
            'wh_destination_name' => 'Группа складов',
            'wh_destination_element' => 'Склад',
            'wh_destination_element_name' => 'Склад',
            'wh_destination_gos' => 'ГОС',
            'wh_destination_bort' => 'БОРТ',


            'array_tk_amort' => 'Амортизация',

            'itogo_actions' => 'акт.',     // 'Общее количество деействий',
            'itogo_strings' => 'стр.',     // 'Общее количество сторок',
            'itogo_things' => 'шт.',   //'Общее количество штук',

            //'array_tk' => 'Списание',

            'array_casual' => 'Расходные материалы',

            'array_count_all' => 'Строк',  //'Всего строк',

            'comments' => 'Коменты',

            'array_tk_amort.prihod_num' => 'Приход',
            'array_tk_amort.rashod_num' => 'Расход',

            'array_tk.prihod_num' => 'Приход',
            'array_tk.rashod_num' => 'Расход',
        ];

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


//    public function getSprwhelement_wh_destination_element()
//    {
//        return $this->hasOne( Sprwhelement::className(), [ 'id' => 'wh_destination_element' ] );
//    }


    /**
     * @param $id
     * @return Sklad_cs_past_inventory|null
     * @throws ExitException
     */
    public static function findModel($id)
    {
        $model = static::findOne($id);

        if (!isset($model)) {
            throw new ExitException('Sklad_inventory. Find(). ERROR');
        }

        return $model;
    }

    /**
     * Поиск по заданной базе
     * where(['id'=>(integer) $id])->one()
     *
     * @param $id
     * @return array|null|ActiveRecord
     * @throws ExitException
     */
    static function findModelDouble($id)
    {
        if (($model = static::find()->where(['id' => (integer)$id])->one()) !== null) {
            return $model;
        }

        throw new ExitException('Sklad_past_inventory. findModelDouble(). Errror');
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
     * Сортировка Массива по ГРУППАМ ТОВАРОВ
     * Для ОТЧЕТА В ЭКСЕЛ
     * -
     *
     * @param $array
     * @return array
     */
    static function sort_array_pk($array)
    {
        $sort_buf = [];
        foreach ($array as $item) {
            $sort_buf[$item['wh_tk']][$item['wh_tk_element']] = $item;
        }

        $array = [];
        foreach ($sort_buf as $key => $item) {
            foreach ($item as $key2 => $item2) {
                $array[] = $item2;
            }
        }

        unset($sort_buf);
        return $array;
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

    /**
     * Вернуть массив: ид, наименовние склада
     * =
     *
     * @param $sklad_id
     * @return array
     */
    static function ArraySklad_id_name($sklad_id)
    {
        $item = Sprwhelement::find()
            ->select(['name'])
            ->where(
                ['OR',
                    ['id' => $sklad_id],
                    ['id' => (int)$sklad_id]
                ]
            )
            ->asArray()
            ->one();

        return $item['name'];

    }

    static function ArraySklad_id_gos($sklad_id)
    {
        $item = Sprwhelement::find()
            ->select(['nomer_gos_registr'])
            ->asArray()
            ->where(['id' => (int)$sklad_id])
            ->one();

        return $item['nomer_gos_registr'];

    }

    static function ArraySklad_id_bort($sklad_id)
    {
        $item = Sprwhelement::find()
            ->select(['nomer_borta'])
            ->asArray()
            ->where(['id' => (int)$sklad_id])
            ->one();

        return $item['nomer_borta'];

    }

    /**
     * Вернуть массив: ID:ID  только одного парка
     * =
     * OK
     *
     * @param $destination_id
     * @return mixed
     */
    public static function ArrayBy_destination($destination_id)
    {
        $item = static::find()
            ->where(['wh_destination' => (int)$destination_id])
            ->distinct('wh_destination_element');
        if (empty($item)) {
            $item = static::find()
                ->distinct('wh_destination_element');
        }

        foreach ($item as $ed) {
            $array[$ed] = $ed;
        }
        return $array;
    }


    /**
     * Для ИНВЕНТАРИЗАЦИИ в Мобилке. Вернуть только один парк
     * =
     * Содержимое накладных
     *
     * @param $destination_id
     * @return mixed
     */
    public static function ArrayInventory_by_PE($pe)
    {
        return static::find()
            ->where(['wh_destination_element' => (int)$pe])
            ->asArray()
            ->all();
    }

    /**
     * Для ИНВЕНТАРИЗАЦИИ в Мобилке. Вернуть только один парк     *
     * =
     * Только заголовки
     * =
     *
     * @param $ap
     * @return mixed
     */
    public static function ArrayInventory_by_AP($ap)
    {
        return static::find()
            ->select([
                'id',
                'dt_start',
                'wh_destination',
                'wh_destination_element',
                'wh_destination_name',
                'wh_destination_element_name',
                'wh_destination_gos',
                'wh_destination_bort',

                'itogo_actions',
                'itogo_strings',
                'itogo_things',
                // 'dt_create',
                // 'array_tk',
                // 'array_casual',
                'dt_create_timestamp',
                'dt_update_timestamp'
            ])
            ->where(['wh_destination' => (int)$ap])
            ->orderBy('wh_destination_element_name')
            ->asArray()
            ->all();
    }


    /**
     * Вернуть массив: ид, наименовние GROUP склада
     *
     * @param $group_sklad_id
     * @return mixed
     */
    static function ArraySkladGroup_id_name($group_sklad_id)
    {
        $item = Sprwhtop::find()
            ->select(['name'])
            ->asArray()
            ->where(['id' => (int)$group_sklad_id])
            ->one();

        return $item['name'];

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

        $array = [];
        foreach ($xx as $item) {
            $array[(int)$item] = (int)$item;
        }
        return $array;
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
     * На Входе: МОДЕЛЬ;  На выходе: Массив с развернутыми НАКЛАДНЫМИ
     * -
     *
     * @param $model
     * @return array
     */
    static function in_model_out_array($model)
    {
         // ddd($model);

        //        if(is_object( $model)){
        //            ddd(ArrayHelper::map(  $model,'id',"name"));
        //        }

        $out_array = [];

        $spr_globam_top = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');
        $spr_globam_elem = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');
        $spr_glob_top = ArrayHelper::map(Spr_glob::find()->all(), 'id', 'name');
        $spr_glob_elem = ArrayHelper::map(Spr_glob_element::find()->all(), 'id', 'name');

        //
        foreach ($model as $item) {
           //ddd($item); // item -> wh_destination
                       // item -> wh_destination_element

            // Если есть АМОРТ блок
            if (isset($item->array_tk_amort) && !empty($item->array_tk_amort)) {

                foreach ($item->array_tk_amort as $item_amort) {
                      // dd($item_amort);

                    // 'wh_tk_amort' => 8
                    //  'wh_tk_element' => 1
                    //  'wh_tk_element_name' => 'Ruptela ГЛОНАСС/GPS/GSM терминал FM-Eсо4 Light T'
                    //  'ed_izmer' => 1
                    //  'ed_izmer_num' => 1
                    //  'bar_code' => '869943057832173'
                    //  'intelligent' => 1

                    $out_array[] = [
                        'id' => $item['id'],
                        'sklad_vid_oper' => $item['sklad_vid_oper'],
                        'wh_home_number' => $item['wh_home_number'],
                        'wh_debet_top' =>$item['wh_destination'] , // $item['wh_debet_top'],
                        'wh_debet_element' => $item['wh_destination_element'],

                        'wh_debet_name' => $item['wh_debet_name'],
                        'wh_debet_element_name' => $item['wh_debet_element_name'],
                        'wh_destination' => $item['wh_destination'],
                        'wh_destination_name' => $item['wh_destination_name'],
                        'wh_destination_element' => $item['wh_destination_element'],
                        'wh_destination_element_name' => $item['wh_destination_element_name'],
                        'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],

                        'dt_create' => $item['dt_create'],
                        'dt_update' => $item['dt_update'],

                        // 'array_bus' => $item['array_bus'],
                        // 'array_count_all' => $item['array_count_all'],
                        // 'dt_transfered_date' => $item['dt_transfered_date'],

                        'wh_tk_amort' => (isset($spr_globam_top[$item_amort['wh_tk_amort']]) ?
                            $spr_globam_top[$item_amort['wh_tk_amort']] : "NOT FIND"),
                        'wh_tk_element' => (isset($spr_globam_elem[$item_amort['wh_tk_element']]) ?
                            $spr_globam_elem[$item_amort['wh_tk_element']] : "NOT FIND"),

                        // 'wh_tk_amort' => $item_amort['wh_tk_amort'] ,
                        // 'wh_tk_element' => $item_amort['wh_tk_element'] ,

                            'ed_izmer' => (isset($item_amort['ed_izmer']) ? $item_amort['ed_izmer'] : 0),
                        'ed_izmer_num' => (isset($item_amort['ed_izmer_num']) ?
                            $item_amort['ed_izmer_num'] : 0),
                        'bar_code' => (isset($item_amort['bar_code']) ? $item_amort['bar_code'] : ''),

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
                    'sklad_vid_oper_name' => $item['sklad_vid_oper_name'],
                    'dt_create' => $item['dt_create'],
                    'dt_update' => $item['dt_update'],
                ];

            }
 // dd(333);
              // ddd($out_array);


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


}
