<?php

namespace frontend\controllers;

use frontend\models\post_past_inventory_cs;
use frontend\models\Sklad;
use frontend\models\Sklad_inventory_cs;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhelement_change;
use frontend\models\Sprwhtop;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;


class Inventory_cs_generatorController extends Controller
{

    /**
     * $session
     */
    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $session->open();

        if (!isset(Yii::$app->getUser()->identity)) {
            /// Быстрая переадресация
            throw new HttpException(411, 'Необходима авторизация', 2);
        }

    }

    /**
     *
     */
    public function behaviors()
    {
        if (!isset(Yii::$app->getUser()->identity->id)) {
            throw new HttpException(411, 'Необходима авторизация', 1);
        }

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => [
                        'GET',
                        'POST',
                    ],
//                    'excel' => ['POST'],

                ],
            ],
        ];
    }


    /**
     * СТОЛБЫ - Pillars
     * -
     *
     */
    function actionPillars()
    {
        ////300 seconds = 5 minutes
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

        /// Виксируем моментальную дату и время
        $date_timestamp = strtotime('last day of last month  23:59:59');

        ///  Получить список всех складов по справочнику складов (PE)
        $list_keys = Sprwhelement::getArray_cs_ids();
        $list_keys_names = Sprwhelement::getArray_cs_ids_names();

        //
        //Замены ГОС/БОРТ номеров по разложены ИД ПЕ на конкретную дату
        // $list_keys - для того, чтобы только в рамках Этого Списка!
        //        $cahanges_by_ids = Sprwhelement_change::array_ids_changes_for_TIME_POINT($last_time, $list_keys);

        $err = [];
        ///
        foreach ($list_keys as $item) {
            //
            $id_AP = Sprwhelement::find_parent_id($item);

            /////////////////////////////////////
            //
            // Находим Единственное правильное Значение для данного АП+ПЕ + Дата(секунды)
            $Array_Actual_ids_names = Sprwhelement_change::Actual_Name_One($date_timestamp, (int)$item);

            ///
            $err = [];
            if (!empty($Array_Actual_ids_names)) {
                if ($this->actionGenerate_one($id_AP, $date_timestamp, $Array_Actual_ids_names) != 0) {
                    $err[] = $item;
                };
            } else {
                //
                $arr_id_name = (Sprwhelement::findAll_Attrib_PE([(int)$item]));
                //
                if ($this->actionGenerate_one($id_AP, $date_timestamp, $arr_id_name) != 0) {
                    $err[] = $item;
                };

            }
            /////////////////////////////////////

            //ddd($err);

            $err33[] = [
                'item' => $item,
                'name' => $list_keys_names[$item],
                //'id_inventory' => $xx,
            ];

//            if ($xx == 0) {
//                $err[$item][] = [
//                    'name' => $list_keys_names[$item],
//                  //  'id_inventory' => $xx,
//                ];
//
//            }

        }


        dd($err33);
        ddd($err);


        // Смотрим главную страницу Столбовых Инвентризаций
        return $this->redirect(['/stat_svod/index_svod_cs']);
    }


    /**
     * СТОЛБЫ - Pillars
     * -
     *
     */
    function actionPillars_2month()
    {

//        /// Виксируем моментальную дату и время
//        $last_time = strtotime('last day of -2 month 23:59:59 ');
//
//
//        ///  Получить список всех складов по справочнику складов
//        ///
//        $list_keys = Sprwhelement::getArray_cs_ids();
//
//
//        ///
//        foreach ($list_keys as $item) {
//            if (!$this->actionGenerate_one($item, $last_time)) {
//                $err[] = $item;
//            };
//        }
//
//        // Смотрим главную страницу Столбовых Инвентризаций
//        return $this->redirect(['/stat_svod/index_svod_cs']);
    }


    /**
     * !!!!!!!!!!!!!
     * СТОЛБ - Pillars ДЛЯ ОДНОГО ЦС в ОДИН ДЕНЬ
     * -
     *
     */
    function actionPillars_one_cs()
    {
        ////300 seconds = 5 minutes
        ini_set('max_execution_time', '30'); //30 seconds

        //
        $model = new Sklad_inventory_cs();

        /// Виксируем моментальную дату и время
        $model->dt_create = date('d.m.Y H:i:s', strtotime('last day of -2 month 23:59:59 '));
        $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));

        //
        $list_group = ArrayHelper::map(Sprwhtop::find()
            ->where(['final_destination' => (int)1])
            ->orderBy(['name'])
            ->all(), 'id', 'name');

        //
        $list_element = [];


        ///
        ///
        if ($model->load(Yii::$app->request->post()) &&
            Yii::$app->request->post('contact-button') == 'add_button_park') {

            //
            $last_time = strtotime($model->dt_create);
            $item = (int)$model->wh_destination_element;

            //
            if (!empty($model->wh_destination)) {
                //
                $list_element = ArrayHelper::map(Sprwhelement::find()
                    ->where(['parent_id' => (int)$model->wh_destination])
                    ->orderBy(['name'])
                    ->all(), 'id', 'name');
            }

            ///
            ///  'dt_create' => '17.09.2020 00:00:01'
            //        'dt_update' => '11.01.2021 13:54:34'
            //        'wh_destination' => '27'
            //        'wh_destination_element' => '1757'

            $Sklad_inventory_cs = Yii::$app->request->post('Sklad_inventory_cs');
            $date_timestamp = strtotime($Sklad_inventory_cs['dt_create']); // 1609437601
            $id_AP = (int)$model->wh_destination;


            //
            // Находим Единственное правильное Значение для данного АП+ПЕ + Дата(секунды)
            $Array_Actual_ids_names = Sprwhelement_change::Actual_Name_One($date_timestamp, (int)$model->wh_destination_element);

            // Рекурсия пока не используется
            //    $array_Ids_canges = Sprwhelement_change::recurcia_find_Ids_array($id_AP, $name_gos);

            ///
            $err = [];
            if (!empty($Array_Actual_ids_names)) {
                if ($this->actionGenerate_one($id_AP, $last_time, $Array_Actual_ids_names) != 0) {
                    $err[] = $item;
                };
            } else {
                //
                $arr_id_name = (Sprwhelement::findAll_Attrib_PE([(int)$model->wh_destination_element]));
                //
                if ($this->actionGenerate_one($id_AP, $last_time, $arr_id_name) != 0) {
                    $err[] = $item;
                };

            }


            //
            return $this->render('_form', [
                'model' => $model,
                'list_group' => $list_group,
                'list_element' => $list_element,
                'alert_mess' => ' ' . (!empty($err) ? implode(',', $err) : 'Ошибок НЕТ'),
            ]);

        }


        //
        return $this->render('_form', [
            'model' => $model,
            'list_group' => $list_group,
            'list_element' => $list_element,
        ]);
    }


    /**
     * Генератор СТОЛБОВОЙ НАКЛАДНОЙ
     * =
     * @param $AP_id
     * @param $last_time
     * @param $chage_ids
     * @return int
     * @throws HttpException
     */
    public function actionGenerate_one($AP_id, $last_time, $chage_ids)
    {

        if (empty($chage_ids)) {
            throw new HttpException(411, 'Пустое значение Массива ИДС в запросе - actionGenerate_one()', 15);
        }

        if (count($chage_ids) > 1) {
            throw new HttpException(411, 'Не должно оставаться более ОДНОГО Ид. Удалите лишний из ПАРЫ ', 15);
        }

        //
        $cs_id = array_key_first($chage_ids); // Берем ид из ключа массива

        ///
        $array_items_inventory = Sklad_inventory_cs::find()
            ->where(
                [
                    'AND',
                    ['<', 'dt_create_timestamp', (int)$last_time], //time
                    ['wh_destination' => (int)$AP_id], // AP
                    ['wh_destination_element' => (int)$cs_id]
                ]
            )
            ->orderBy('dt_create_timestamp DESC')
            ->asArray()
            ->one();

        //        dd($AP_id);
        //        dd($chage_ids);
        //        dd($cs_id);

        //        dd($last_time);
        //        dd(date('d.m.Y H:i:s', $last_time));
        //        ddd($array_items_inventory); //041911

        //    dd('--4--------'); ///


        ///
        ///
        if (!isset($array_items_inventory) || empty($array_items_inventory)) {

            /**
             *   ПРИХОД / РАСХОД.
             * если нет начальных остатков
             */
            $arrayPrihodRashod = self::ArrayPrihodRashod_v2(
                $cs_id,
                0,
                $last_time
            );

            /**
             * если нет начальных остатков
             * Нужно заполнить эти поля
             */
            $array_items_inventory['array_tk_amort'] = [];
            $array_items_inventory['wh_destination'] = Sprwhelement::ParentId_from_ChildId($cs_id);
            $array_items_inventory['wh_destination_element'] = $cs_id;
        } else {

            /**
             *   ПРИХОД / РАСХОД    * Полный Массив
             */
            $arrayPrihodRashod = self::ArrayPrihodRashod_v2(
                $cs_id,
                $array_items_inventory['dt_create_timestamp'], //'dt_create_timestamp' => 1588269601
                $last_time // 1609437601
            );
        }

        // 2 - minus
        // 3 - plus

//          dd($arrayPrihodRashod); // +041799 -040526 +040526

//        dd('--5--------'); ///

        ///
        ///  Если не было Движений и НАчальных остатков
        /// ТО - НЕ СОЗДАВАТЬ НАКЛАДНУЮ СТОЛБОВОЙ ИНВЕНТАРИЗАЦИИ
        ///        //if (empty($array_items_inventory['array_tk_amort']) && empty($arrayPrihodRashod)) {
        if (empty($array_items_inventory) && empty($arrayPrihodRashod)) {
            return -1;
        }


        /**
         * Главная ЛОГИКА.
         * Получает два массива:
         * 1.Первый - Остатки на начало.
         * 2.Второй - Массив Приход-расход.
         * На выходе - полный массив ИТОГО.
         */
        /// 1.
        $array_inventary = Stat_balans_cs_Controller::Math_summary(
            $array_items_inventory['array_tk_amort'],
            $arrayPrihodRashod
        );

        // 040032  // 041869
//        ddd($arrayPrihodRashod);
//        ddd($array_inventary);


        /**
         * 1. Перекидываем Итоговое значение в Остаток на начало.
         * 2. Обрезаем ненужные поля. Облегчаем массив
         */
        foreach ($array_inventary as $key => $item) {
            /// Перекидываем Итоговое значение в Остаток на начало
            $array_inventary[$key]['ed_izmer_num'] = $array_inventary[$key]['itog'];

            ///
//            if ($array_inventary[$key]['ed_izmer_num'] == 0) {
//                unset($array_inventary[$key]);
//                continue;
//            }

            unset($array_inventary[$key]['wh_tk_element_name']);
            unset($array_inventary[$key]['itog']);
            unset($array_inventary[$key]['prihod_num']);
            unset($array_inventary[$key]['rashod_num']);
            unset($array_inventary[$key]['dt_create_minus']);
            unset($array_inventary[$key]['dt_create_plus']);

        }

//        ddd($array_inventary);
//        ddd($array_items_inventory);

        /**
         * Сбиваем порядок строк
         */

        $array_inventary2 = [];
        $array_all_sum = [];
        foreach ($array_inventary as $key => $item) {
            $array_inventary2[] = $item;

            ///Если количество в позиции больше нуля
            if ($item['ed_izmer_num'] > 0) {
                ////
                if (isset($array_all_sum[$item['wh_tk_amort']][$item['wh_tk_element']])) {

                    $array_all_sum[$item['wh_tk_amort']][$item['wh_tk_element']] =
                        $array_all_sum[$item['wh_tk_amort']][$item['wh_tk_element']] +
                        $item['ed_izmer_num'];
                } else {

                    $array_all_sum[$item['wh_tk_amort']][$item['wh_tk_element']] = $item['ed_izmer_num'];

                }
            }

        }

//        ddd($array_all_sum);

//  7 => [
//        13 => 3
//        12 => 1
//        3 => 2
//        9 => 1
//        10 => 1
//        14 => 1
//        15 => 1
//        6 => 1
//        2 => 1
//        1 => 1
//    ]

        $array_inventary = $array_inventary2;
        unset($array_inventary2);

        /**
         * Шапку надстроим
         */
        $model_invenotry = new Sklad_inventory_cs();
        $model_invenotry->id = Sklad_inventory_cs::setNext_max_id();
        $model_invenotry->dt_create_timestamp = $last_time;
        $model_invenotry->dt_create = date('d.m.Y H:i:s', $last_time);
        $model_invenotry->sklad_vid_oper = (int)1;
        $model_invenotry->sklad_vid_oper_name = 'Закрытие по ' . date('d.m.Y', $last_time);;
        $model_invenotry->wh_home_number = (int)$cs_id;
        $model_invenotry->wh_destination = $array_items_inventory['wh_destination'];
        $model_invenotry->wh_destination_element = $array_items_inventory['wh_destination_element'];
        $model_invenotry->wh_destination_name = Sprwhtop::Name_from_id($array_items_inventory['wh_destination']);
        $model_invenotry->wh_destination_element_name = Sprwhelement::Name_from_id($array_items_inventory['wh_destination_element']);

        $model_invenotry->array_tk_amort = $array_inventary;


        // Подсчет количества строк и штук в накладной (в массиве)
        $array_res = self::Array_sum_res($array_inventary);
        $model_invenotry->itogo_things = ((isset($array_res['itogo_things']) && $array_res['itogo_things'] >= 0) ? $array_res['itogo_things'] : 0);
        $model_invenotry->count_str = ((isset($array_res['itogo_strings']) && $array_res['itogo_strings'] >= 0) ? $array_res['itogo_strings'] : 0);
        $model_invenotry->calc_minus = $model_invenotry->itogo_things - $model_invenotry->count_str;

        //ddd($model_invenotry);

        unset($array_inventary);


        ///calc_errors
        /// Ошибки !!!!!!!!!!!!!!!!!!!!!!!!!!!!
        /// Помощник водителя GJ-DA04
        if (isset($array_all_sum[7][1])) {
            $model_invenotry->calc_errors = $model_invenotry->calc_errors + ((int)$array_all_sum[7][1] > 1 ? 1 : 0);
            $model_invenotry->calc_errors = $model_invenotry->calc_errors + ((int)$array_all_sum[7][1] < 1 ? 1 : 0);
        }
        //Validator CVB24 master
        if (isset($array_all_sum[7][2]) && !isset($array_all_sum[7][19])) {
            $model_invenotry->calc_errors = $model_invenotry->calc_errors + ((int)$array_all_sum[7][2] > 1 ? 1 : 0);
            $model_invenotry->calc_errors = $model_invenotry->calc_errors + ((int)$array_all_sum[7][2] < 1 ? 1 : 0);
        }
        //Validator CVB44 master
        if (isset($array_all_sum[7][19]) && !isset($array_all_sum[7][2])) {
            $model_invenotry->calc_errors = $model_invenotry->calc_errors + ((int)$array_all_sum[7][19] > 1 ? 1 : 0);
            $model_invenotry->calc_errors = $model_invenotry->calc_errors + ((int)$array_all_sum[7][19] < 1 ? 1 : 0);
        }
        //Validator CVB24 master||CVB44 master + slave
        if ((isset($array_all_sum[7][2]) || isset($array_all_sum[7][19])) && isset($array_all_sum[7][3]) && isset($array_all_sum[7][13])) {
            $master = 0;
            if (isset($array_all_sum[7][2])) {
                $master += $array_all_sum[7][2];
            }
            if (isset($array_all_sum[7][19])) {
                $master += $array_all_sum[7][19];
            }

            $master_slave = $master + $array_all_sum[7][3];
            if (isset($array_all_sum[7][13]) && (int)$array_all_sum[7][13] != $master_slave) {
                $model_invenotry->calc_errors = 44;
            }
        }

        //ddd($array_all_sum);


        // Всего строк в накладной больше 0
        if ($model_invenotry->count_str > 0) {
            // Если Комутаторов много
            //Все СВИЧИ-Коммутаторы
            $commutator =
                (isset($array_all_sum[7][5]) ? $array_all_sum[7][5] : 0) +
                (isset($array_all_sum[7][6]) ? $array_all_sum[7][6] : 0) +
                (isset($array_all_sum[7][7]) ? $array_all_sum[7][7] : 0) +
                (isset($array_all_sum[7][8]) ? $array_all_sum[7][8] : 0);

            if ((int)$commutator > 1) {
                $model_invenotry->calc_errors = 22;
            }
        }

        //        ddd( $model_invenotry->calc_errors);
        //        ddd($commutator);
        //        ddd($array_all_sum);


        //ddd($model_invenotry);

        if (!empty($name_gos_bort)) {
            // Замена номера, если она ЕСТЬ
            $model_invenotry->wh_destination_element_name = $name_gos_bort;
        }

        //
        $model_invenotry->user_id = (int)Yii::$app->getUser()->identity->id;
        $model_invenotry->user_name = Yii::$app->getUser()->identity->username;


        ///
        if (!$model_invenotry->save(true)) {
            ddd($model_invenotry->errors);
        }


        return (int)$model_invenotry->id;
    }


    /**
     * Версия 2 NEW
     *
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
     * -
     * Возвращает массив с ПРРИХОДОМ и Расходом
     *
     * @param $sklad_id
     * @param $dt_create_timestamp // START-TIME
     * @param $last_time // STOP-TIME
     * @return mixed
     */
    public
    static function ArrayPrihodRashod_v2($sklad_id, $dt_create_timestamp, $last_time)
    {

        /////////
        $array_moves_sklad = Sklad::find()
            ->select(
                [
                    'id',
                    'wh_home_number',
                    'sklad_vid_oper',
                    'dt_create',
                    'dt_create_timestamp',
                    'wh_debet_element',
                    'wh_destination_element',
                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                    //'array_tk_amort.intelligent', // есть не всегда
                    'array_tk_amort.bar_code',
                ]
            )
            ->where(
                [
                    'AND',
                    ['OR',
                        ['>=', 'dt_create_timestamp', $dt_create_timestamp],
                        ['>=', 'dt_create_timestamp', (int)$dt_create_timestamp]
                    ],
                    ['OR',
                        ['<=', 'dt_create_timestamp', $last_time],
                        ['<=', 'dt_create_timestamp', (int)$last_time],
                    ],

// не включать!!!                    [ '!=', 'array_tk_amort.bar_code', null ],
// не включать!!!                    [ '!=', 'array_tk_amort.bar_code', '' ],
                    [
                        'OR',
                        // ЦС
                        ['wh_cs_number' => (string)$sklad_id,], //CS!!
                        ['wh_cs_number' => (int)$sklad_id], //CS!!
                        // Можно и так
                        ['=', 'wh_debet_element', (int)$sklad_id], //CS!!
//                        ['=', 'wh_destination_element', (int)$sklad_id], //CS!!
                        // Можно и так
                        ['=', 'wh_debet_element', (string)$sklad_id],//CS!!
//                        ['=', 'wh_destination_element', (string)$sklad_id],//CS!!
                    ],
                ]
            )
            ->orderBy(
                'dt_create_timestamp ASC'
            )
            ->asArray()
            ->all();


//        ddd($last_time);//1604167199
//        ddd($dt_create_timestamp);//1604167199
//        ddd($sklad_id);
//        ddd($array_moves_sklad);


        ///
        /// Переносим движения из Объекта накладных в Массив
        ///
        $array_moves = [];
        foreach ($array_moves_sklad as $key_sklad => $item_sklad) {

            if (isset($item_sklad['array_tk_amort']) && is_array($item_sklad['array_tk_amort'])) {
                ///  заходим в  array_tk_amort этой накладной
                foreach ($item_sklad['array_tk_amort'] as $key_amort => $item_amort) {

                    $array_moves[] = [
                        //$item_sklad
                        'date' => $item_sklad['dt_create_timestamp'],
                        'date_2' => date('d.m.Y H:i:s', $item_sklad['dt_create_timestamp']),

                        'nakl' => $item_sklad['id'],
                        'plus_minus' => (int)$item_sklad['sklad_vid_oper'],

                        //$item_amort
                        'wh_tk_amort' => (int)$item_amort['wh_tk_amort'],
                        'wh_tk_element' => (int)$item_amort['wh_tk_element'],
                        'ed_izmer_num' => (int)$item_amort['ed_izmer_num'],
                        'bar_code' => $item_amort['bar_code'],

                    ];
                }
            }
        }


        //ddd($array_moves);

        return $array_moves;
    }

    /**
     * Версия 2 NEW
     *
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
     * -
     * Возвращает ЗАПРОСНУЮ МОДЕЛЬ с правилами запроса на ПРРИХОД и Расход
     *
     * @param $sklad_id
     * @param $dt_create_timestamp // START-TIME
     * @param $last_time // STOP-TIME
     * @return mixed
     */
    public
    static function Query_PrihodRashod_v2($sklad_id, $dt_create_timestamp, $last_time)
    {
        //        ddd($dt_create_timestamp); //1588269340
        //        ddd($sklad_id); //2861

        /////////
        $array_moves_sklad = Sklad::find()
            ->select(
                [
                    'id',
                    'wh_home_number',
                    'sklad_vid_oper',
                    'dt_create',
                    'dt_create_timestamp',
                    'wh_debet_element',
                    'wh_destination_element',

                    'tx',
                    "user_name",
                    "wh_dalee_element_name",

                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                    //'array_tk_amort.intelligent', // есть не всегда
                    'array_tk_amort.bar_code',
                ]
            )
            ->with(
                'sprwhelement_home_number',
                'sprwhelement_debet_element',
                'sprwhelement_destination_element'
            )
            ->where(
                [
                    'AND',
                    ['>', 'dt_create_timestamp', $dt_create_timestamp],
                    ['<', 'dt_create_timestamp', $last_time],

// не включать!!!                    [ '!=', 'array_tk_amort.bar_code', null ],
// не включать!!!                    [ '!=', 'array_tk_amort.bar_code', '' ],
                    [
                        'OR',
                        // ЦС
                        ['=', 'wh_cs_number', (string)$sklad_id,], //CS!!
                        ['=', 'wh_cs_number', (int)$sklad_id], //CS!!
                        // Можно и так
                        ['=', 'wh_debet_element', (int)$sklad_id], //CS!!
//                        ['=', 'wh_destination_element', (int)$sklad_id], //CS!!
                        // Можно и так
                        ['=', 'wh_debet_element', (string)$sklad_id],//CS!!
//                        ['=', 'wh_destination_element', (string)$sklad_id],//CS!!
                    ],
                ]
            )
            ->asArray();


        return $array_moves_sklad;
    }


    /**
     * Подсчет количества строк и штук в накладной (в массиве)
     * @param $array
     * @return array
     */
    static function Array_sum_res($array)
    {
        $itog_str = 0;
        $itog_act = 0;
        $itogo_things = 0;
        $array_res = [];
        if (is_array($array))
            foreach ($array as $item) {
                $itog_act++;
                if ((int)$item['ed_izmer_num'] > 0) {
                    $itog_str++;
                    $itogo_things += (int)$item['ed_izmer_num'];
                }
            }

        $array_res['itogo_actions'] = $itog_act;     // 'Общее количество деействий',
        $array_res['itogo_strings'] = $itog_str;     //'Общее количество сторок',
        $array_res['itogo_things'] = $itogo_things;     //'Общее количество штук',

        return $array_res;
    }


    /**
     * По вызову Аякс находит
     * ПОДЧИНЕННЫЕ СКЛАДЫ
     *
     * @param int $id
     * @param $actualdate
     * @return string
     */
    public function actionList_element_cs($id, $actualdate)
    {
        if (!isset($actualdate) || empty($actualdate)) {
            return "Запрос вернул пустой массив";
        }

        if (isset($actualdate) && !empty($actualdate)) {
            $date_timestamp = strtotime($actualdate); //1606759199
        }


        $model_spr = ArrayHelper::map(Sprwhelement::find()
            ->where(
                ['AND',
                    ['parent_id' => (int)$id],
                    ['final_destination' => (int)1],
                ]
            )
            ->orderBy('name')
            ->all(), 'id', 'name'
        );

        //        $model_spr_ids = ArrayHelper::getColumn(Sprwhelement::find()
        //            ->where(
        //                ['AND',
        //                    ['parent_id' => (int)$id],
        //                    ['final_destination' => (int)1],
        //                ]
        //            )
        //            ->orderBy('name')
        //            ->all(), 'id');

        // MЕНЬШЕ-OLD, Больше-NEW.
        //Теперь они сливаются вместе и не конфликтуют
        $array_ids_actual = Sprwhelement_change::array_Actual_Ids_and_GOS($date_timestamp);
        $array_ids_defective = Sprwhelement_change::array_Defective_Ids($date_timestamp);

        //ddd($array_ids_defective);

        ///
        $model = Html::dropDownList(
            'name_id', 0,

            $array_ids_defective + $array_ids_actual + $model_spr,

            [
                'prompt' => 'Выбор ...']
        );

        ///
        if (empty($model)) {
            return "Запрос вернул пустой массив";
        }

        return $model;

    }


}