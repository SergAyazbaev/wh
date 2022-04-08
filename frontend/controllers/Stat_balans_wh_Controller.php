<?php

namespace frontend\controllers;

use frontend\models\Sklad;
use frontend\models\Sklad_cs_past_inventory;
use frontend\models\Sklad_inventory_cs;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Sprwhelement;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class Stat_balans_wh_Controller extends Controller
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
     * Если ГРУППА ниже нормы STATISTIC, то нет доступа
     *
     * @param $event
     * @return bool
     * @throws BadRequestHttpException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function beforeAction($event)
    {
        if (!isset(Yii::$app->getUser()->identity->id)) {
            throw new HttpException(411, 'Необходима авторизация', 1);
        }


        if ((Yii::$app->getUser()->identity->group_id < 40 || Yii::$app->getUser()->identity->group_id >
            100)) {

            throw new NotFoundHttpException('Доступ только STATISTIC-группе');
        }

        return parent::beforeAction($event);

    }

//
//    /**
//     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
//     * (ШТУЧНЫЙ. С учетом ШТРИХКОДОВ )
//     * -
//     * Возвращает массив с ПРРИХОДОМ и Расходом
//     *
//     * @param $sc_sklad
//     * @param $dt_start_timestamp
//     * @return array
//     */
//    public static function ArrayPrihodRashod_sc($sc_sklad, $dt_start_timestamp)
//    {
//        $sc_sklad = (int)$sc_sklad;
//        $sc_sklad_str = (string)$sc_sklad;
//        $dt_start_timestamp = (int)$dt_start_timestamp;
//
//        if (!isset($dt_start_timestamp) || !isset($sc_sklad) || empty($dt_start_timestamp) ||
//            empty($sc_sklad)
//        ) {
//            return [];
//        }
//
//
//        /////////
//        /// Все приходные и расходные накладные за период
//        /// ВЫБРАТЬ ТОЛЬКО ЦС!!!!
//        ///
//        $array_items = Sklad::find()
//            ->where(
//                [
//                    'AND',
//                    [
//                        '>',
//                        'dt_create_timestamp',
//                        $dt_start_timestamp,
//                    ],
//                    [
//                        'OR',
//                        [
//                            '==',
//                            'wh_cs_number',
//                            $sc_sklad,
//                        ],
//                        [
//                            '==',
//                            'wh_cs_number',
//                            $sc_sklad_str,
//                        ],
//                    ],
//                ]
//            )
//            ->orderBy('dt_create_timestamp')
//            ->asArray()
//            ->all();
//
////        ddd( $array_items );
////        19600005916
//        //////////////
//
//
//        $array_itog_amort1 = [];
//        foreach ($array_items as $num_id) { // Это одна накладная
//            //            ddd( $num_id );
//            if (isset($num_id['array_tk_amort']) && !empty($num_id['array_tk_amort'])) {////array_tk_amort
//                $x_plus = 0;
//                //                $x_minus = 0;
//
//                foreach ($num_id['array_tk_amort'] as $item_pos) {
//
//
//                    ////3-5
//                    //
//                    // 		'1' => 'Инвентаризация',
//                    //		'2' => 'Приходная накладная',
//                    //		'3' => 'Расходная накладная',
//                    //		'4' => 'Снятие(замена)',
//                    //		'5' => 'Установка(замена)'
//                    //
//
//                    if ((int)$num_id['sklad_vid_oper'] == 3 || (int)$num_id['sklad_vid_oper'] ==
//                        5) {
//                        $x_plus++;
//
//                        if (!isset($item_pos['intelligent']) || (int)$item_pos['intelligent'] !=
//                            1) {
//
//                            $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']] [(int)$item_pos['wh_tk_element']]['ed_izmer_num'] = (isset($array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']] [(int)$item_pos['wh_tk_element']]['ed_izmer_num']) ?
//                                    $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']] [(int)$item_pos['wh_tk_element']]['ed_izmer_num'] : 0) +
//                                (int)$item_pos['ed_izmer_num'];
//                        }
//
//
//                        if (isset($item_pos['bar_code']) && !empty($item_pos['bar_code'])) {
//                            $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['bar_code'][$item_pos['bar_code']] = $item_pos['bar_code'];
//                        }
//
//                        $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['ed_izmer'] = $item_pos['ed_izmer'];
//                    }
//
//                    ////2-4
//                    if ((int)$num_id['sklad_vid_oper'] == 2 || (int)$num_id['sklad_vid_oper'] ==
//                        4) {
//
//                        if (!empty($item_pos['bar_code'])) {
//                            $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['bar_code'][$item_pos['bar_code']] = $item_pos['bar_code'];
//                        }
//
//                        $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['intelligent'] = (isset($item_pos['intelligent']) ? $item_pos['intelligent'] : 0);
//                        $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['ed_izmer'] = $item_pos['ed_izmer'];
//                        $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['ed_izmer_num'] = $item_pos['ed_izmer_num'];
//
//                        //ddd($array_itog_amort1);
//                        //ddd($item_pos);
//                    }
//                }
//                //ddd($array_itog_amort1);
//            }
//        }
//
//
//        return $array_itog_amort1;
//
//    }

    /**
     * Массив минусовых Баркодов.
     * =
     * на входе делим на минус и плюс
     *   'minus' => [
     *
     * @param $array
     * @return array
     */
    public static function ArrayMinusov_or_PlusovBar($array)
    {
        $array_minus = [];

        ///  MINUSOVKA
        if (isset($array) && is_array($array)) {
            foreach ($array as $group_ids) {
                foreach ($group_ids as $items_ids) {


                    if (isset($items_ids['bar_code']) && is_array($items_ids['bar_code'])) {
                        foreach ($items_ids['bar_code'] as $bc_ids) {

                            $array_minus[$bc_ids] = $bc_ids; // 19600005916
                        }
                    }
                }
            }
        }

        return $array_minus; // массив минусовых Баркодов

    }

    /**
     * Получить ОТЧЕТ ПО ОДНОМУ СКЛАДУ
     * =
     * возвращает массив $model
     * -
     *
     * @param $sklad_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function getNow_one_inventory($sklad_id)
    {

        if (!isset($sklad_id) || empty($sklad_id)) {
            throw new NotFoundHttpException(' sklad_id Нет ИНВЕНТАРИЗАЦИИ по заданному складу');
        }

        ///........
        ///  Инвентаризация для этого Склада
        ///  НА НАЧАЛО ПЕРИОДА
        ///........
        $model_stone_invent = Stat_balansController::get_aray_inventory($sklad_id);

        //ddd($model_stone_invent);
        ///........
        ///  Движение по складу ОТ РАССВЕТА до ЗАКАТА ($ArrayPrihodRashod)
        ///........
        $ArrayPrihodRashod = Stat_balansController::ArrayPrihodRashod(
            $sklad_id, $model_stone_invent['dt_start_timestamp']
        );

        //        ddd($ArrayPrihodRashod);
        ///////////////////
        $array_itog_amort = [];
        $array_amort = $model_stone_invent['array_tk_amort'];
        foreach ($array_amort as $item) {

            //ddd($item['ed_izmer_num']);
            //ddd($item);
            //ddd($ArrayPrihodRashod);


            $x = $item['ed_izmer_num'];
            do {
                $array_itog_amort[] = [
                    'wh_tk_amort' => $item['wh_tk_amort'],
                    'wh_tk_element' => $item['wh_tk_element'],
                    'ed_izmer' => $item['ed_izmer'],
                    'ed_izmer_num' => 1,
                    'bar_code' => $item['bar_code'],
                ];

                $x--;
            } while ($x > 0);


            unset($ArrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus']);
            unset($ArrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['minus']);

            if (empty($ArrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']])) {
                unset($ArrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]);
            }
            if (empty($ArrayPrihodRashod['amort'][$item['wh_tk_amort']])) {
                unset($ArrayPrihodRashod['amort'][$item['wh_tk_amort']]);
            }
        }

        $model_inventory['array_tk_amort'] = $array_itog_amort;

        // ddd($model_inventory);


        $array_itog_amort = [];
        $array_amort = $model_stone_invent['array_tk'];
        foreach ($array_amort as $item) {

            $array_itog_amort[] = [
                'wh_tk' => $item['wh_tk'],
                'wh_tk_element' => $item['wh_tk_element'],
                'ed_izmer' => $item['ed_izmer'],
                'ed_izmer_num' => (
                    $item['ed_izmer_num']
                    + $ArrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus']
                    - $ArrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus']
                ),
            ];
            unset($ArrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus']);
            unset($ArrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus']);


            if (empty($ArrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']])) {
                unset($ArrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]);
            }
            if (empty($ArrayPrihodRashod['tk'][$item['wh_tk']])) {
                unset($ArrayPrihodRashod['tk'][$item['wh_tk']]);
            }
        }

        $model_inventory['array_tk'] = $array_itog_amort;

        unset($array_itog_amort);

        //ddd($model_inventory);

        return $model_inventory;

    }

    ////////////

    /**
     * Подсчет остатков по ОДНОМУ(!) складу
     * =
     * ИСХОДНЫЕ ДАННЫЕ = промежуточные ОСТАТКИ
     * =
     * Возвращает Массив-Масивов (AM+TK+CASUAL)
     * -
     *
     * @param $sklad_element
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function get_aray_inventory($sklad_element)
    {

        /////////
        //         $array_items_inventory = Sklad_inventory::find() // ПО ИНВЕНТАРИЗАЦИИ!!!!

        $array_items_inventory = Sklad_past_inventory::find()// ПО ПРОМЕЖУТОЧНЫМ ИНВЕНТАРИЗАЦИЯМ
        ->where(
            [
                'OR',
                ['wh_destination_element' => (int)$sklad_element],
                ['wh_destination_element' => (string)$sklad_element],
            ]
        )
            ->orderBy('dt_create_timestamp DESC')
            //->asArray()
            ->one();

        //        ddd($array_items_inventory);


        if (!isset($array_items_inventory) || empty($array_items_inventory)) {
            throw new NotFoundHttpException('Нет промежуточных ОСТАТКОВ для данного Склада. Запустите ГЕНЕРАЦИЮ остатков');
        }


        return $array_items_inventory;

    }
    ////////////


    /**
     * !!!!!!!!!!!!!!!!!!!!!! SUPER OK !!!!!!!!!!!!!!!!!!!!!* ОТЛИЧНО РАБОТАЕТ !
     *
     * CS-summary. ЕЖЕДНЕВНО.
     * =
     * КРАЙНЯЯ ИНВЕНТАРИЗАЦИЯ +приход -расход
     * -
     *
     * @return Response
     */
    function actionCs_past_inventory_summary()
    {
        ///
        /// ОБЩЕЕ УДАЛЕНИЕ ИНВЕНТАРИЗАЦИЙ
        /// DELETE_ALL_OLD_ROWS
        Sklad_cs_past_inventory::deleteAll();

        ///
        ///    Список всех активных складов
        ///
        //$list_keys = Sklad_inventory_cs::Array_inventory_ids();

        ///  !!!
        ///  Получить список всех складов по справочнику складов
        ///
        $list_keys = Sprwhelement::getArray_cs_ids();


        ///
        foreach ($list_keys as $item) {
            if (!$this->actionPast_inventory_id($item)) {
                $err[] = $item;
            };
        }


//        $this->actionPast_inventory_id(2861); // 966AZ05
//        $this->actionPast_inventory_id(1393); // 434DS02 big


//        $this->actionPast_inventory_id(5030); // 620DK02
//        $this->actionPast_inventory_id(177); // 5001
//        $this->actionPast_inventory_id(216); // 5040
        //$this->actionPast_inventory_id(2955);
        //$this->actionPast_inventory_id(654);
        //$this->actionPast_inventory_id(813);

        return $this->redirect(['/past_inventory_cs_']);

    }

    /**
     *  СОЗДАНИЕ НОВОЙ ИНВЕНТАРИЗАЦИИ по Одному Складу
     * =
     * 2
     *
     * @param $sklad_id
     * @return bool
     */
    public static function actionPast_inventory_id($sklad_id)
    {
        //4226
        /////////
        $array_items_inventory = Sklad_inventory_cs::find()
            ->select(
                [
                    'id',
                    'dt_create',
                    'dt_create_timestamp',
                    'wh_destination',
                    'wh_destination_element',
                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                    'array_tk_amort.intelligent',
                    'array_tk_amort.bar_code',
                    'array_tk.wh_tk',
                    'array_tk.wh_tk_element',
                    'array_tk.ed_izmer',
                    'array_tk.ed_izmer_num',
                    //'array_tk.name_ed_izmer',
                ]
            )
            //->where( ['id'=>(int)$inventory_id] )
            ->where(
                [
                    'OR',
                    ['wh_destination_element' => $sklad_id],
                    ['wh_destination_element' => (int)$sklad_id],
                    ['wh_destination_element' => (string)$sklad_id],
                ]
            )
            ->orderBy('dt_create_timestamp DESC')
            ->asArray()
            ->one();
        //    253CX02


        if (!isset($array_items_inventory) || empty($array_items_inventory)) {
            return false;
        }

        //        ddd($array_items_inventory);

        /// ИНВЕНТАРИЗАЦИИ
        //                if( !isset($array_items_inventory) || empty($array_items_inventory) ){
        //                    throw new NotFoundHttpException('Нет ИНВЕНТАРИЗАЦИИ по заданному складу');
        //                }


        $model = new Sklad_cs_past_inventory();
        $model->id = (int)Sklad_cs_past_inventory::setNext_max_id();
        $model->dt_start = $array_items_inventory['dt_create'];
        $model->wh_destination = (int)$array_items_inventory['wh_destination'];
        $model->wh_destination_element = (int)$array_items_inventory['wh_destination_element'];
        $model->wh_destination_name = Sklad_cs_past_inventory::ArraySkladGroup_id_name($array_items_inventory['wh_destination']);

        $model->wh_destination_element_name = Sklad_cs_past_inventory::ArraySklad_id_name($model->wh_destination_element);
        $model->wh_destination_gos = Sklad_cs_past_inventory::ArraySklad_id_gos($model->wh_destination_element);
        $model->wh_destination_bort = Sklad_cs_past_inventory::ArraySklad_id_bort($model->wh_destination_element);


        if (empty($model->wh_destination_element_name)) {
            return false;
        }


        ///
        /// ПРИХОД / РАСХОД
        ///   Полный Массив по Group+Id        ///$arrayPrihodRashod = $this->ArrayPrihodRashod(
        //$arrayPrihodRashod = $this->ArrayPrihodRashod_v2(
        $arrayPrihodRashod = self::ArrayPrihodRashod_v2(
            $array_items_inventory['wh_destination_element'],
            $array_items_inventory['dt_create_timestamp']
        );


        //         ddd($arrayPrihodRashod);

        /**
         * Главная ЛОГИКА.
         * Получает два массива:
         * 1.Первый - Остатки на начало.
         * 2.Второй - Массив Приход-расход.
         * На выходе - полный массив ИТОГО.
         */
        $array_inventary = Stat_balans_cs_Controller::Math_summary(
            $array_items_inventory['array_tk_amort'],
            $arrayPrihodRashod
        );


//        ///#############
//        /// Дополняем недостающие поля ВИЗУАЛИЗАЦИИ.
//        /// ПОДСЧЕТ ИТОГОВОГО ПОЛЯ.
//        ///
//        foreach ($array_inventary as $key_a => $item_a) {
//            if (!isset($item_a['prihod_num'])) {
//                $array_inventary[$key_a]['prihod_num'] = 0;
//            }
//            if (!isset($item_a['rashod_num'])) {
//                $array_inventary[$key_a]['rashod_num'] = 0;
//            }
//            if (!isset($item_a['itog'])) {
//                $array_inventary[$key_a]['itog'] = $array_inventary[$key_a]['ed_izmer_num'] + $array_inventary[$key_a]['prihod_num'] - $array_inventary[$key_a]['rashod_num'];
//            }
//
//        }

        ///
        $model->array_tk_amort = $array_inventary;

        // Подсчет количества строк и штук в накладной (в массиве)
        $array_res = self::Array_sum_res($array_inventary);
        $model->itogo_actions = $array_res['itogo_actions'];
        $model->itogo_strings = $array_res['itogo_strings'];
        $model->itogo_things = $array_res['itogo_things'];

        //ddd($model);

        if (!$model->save(true)) {
            return false;
        }


        return true;

    }


    /**
     * Главная ЛОГИКА.
     *-
     * Получает два массива.
     * Первый - Остатки на начало.
     * Второй - Массив Приход-расход.
     * На выходе - полный массив ИТОГО.
     *
     * @param $array_inventary
     * @param $arrayPrihodRashod
     * @return mixed
     */
    public static function Math_summary($array_inventary, $arrayPrihodRashod)
    {
//        ddd($array_inventary); // 1
//        ddd($arrayPrihodRashod); // 33-2


        ///
        ///  Инвентаризация.
        ///  Вытаскиваем и отделяем массив БЕЗ-ШТРИХКОДОВЫХ. БУДЕМ с ним работать отдельно
        ///
        $array_inventory_zerro = [];
        foreach ($array_inventary as $key_inventary => $item_inventary) {
            if ($item_inventary['bar_code'] == '') {

                // '7_14'
                $name_id = ($item_inventary['wh_tk_amort'] . '_' . $item_inventary['wh_tk_element']);
                $array_inventory_zerro[$name_id] = $item_inventary;

                unset($array_inventary[$key_inventary]);
            }
        }

//        dd($arrayPrihodRashod);

//        dd($array_inventary);

        /// Добавляем АДД
        ///
        ///
        foreach ($arrayPrihodRashod as $key_PR => $item_PR) {
            //
            if ($item_PR['bar_code'] == '') {

                // '7_14'
                $name_id = ($item_PR['wh_tk_amort'] . '_' . $item_PR['wh_tk_element']);

                ///
                if (!isset($array_inventory_zerro[$name_id])) {
                    $array_inventory_zerro[$name_id] = $item_PR;
                }

                ///
                if ((int)$item_PR['plus_minus'] == 2) {
                    if (!isset($array_inventory_zerro[$name_id]['prihod_num'])) {
                        $array_inventory_zerro[$name_id]['prihod_num'] = $item_PR['ed_izmer_num'];
                    } else {
                        $array_inventory_zerro[$name_id]['prihod_num'] =
                            $array_inventory_zerro[$name_id]['prihod_num'] + $item_PR['ed_izmer_num'];
                    }
                }

                ///
                if ((int)$item_PR['plus_minus'] == 3) {
                    if (!isset($array_inventory_zerro[$name_id]['rashod_num'])) {
                        $array_inventory_zerro[$name_id]['rashod_num'] = $item_PR['ed_izmer_num'];
                    } else {
                        $array_inventory_zerro[$name_id]['rashod_num'] =
                            $array_inventory_zerro[$name_id]['rashod_num'] + $item_PR['ed_izmer_num'];
                    }

                }


                unset($arrayPrihodRashod[$key_PR]);

            }

        }


//        ddd($array_inventory_zerro);


        ///
        ///  ЛОГИКА БЕЗ-ШТРИХКОДОВЫХ
        ///
        ///  Проход- суммирование приходов
        foreach ($array_inventory_zerro as $key_inventary => $item_inventary) {

            ///
            foreach ($arrayPrihodRashod as $key_PR => $item_PR) {

                if ((int)$item_inventary['wh_tk_element'] === (int)$item_PR['wh_tk_element']) {

                    ///$array_inventory_zerro
                    if (isset($item_PR['plus_minus']) && (int)$item_PR['plus_minus'] == 2) {
                        $array_inventory_zerro[$key_inventary]['ed_izmer_num'] =
                            $array_inventory_zerro[$key_inventary]['ed_izmer_num'] + $item_PR['ed_izmer_num'];
                    }
                    if (isset($item_PR['plus_minus']) && (int)$item_PR['plus_minus'] == 3) {
                        $array_inventory_zerro[$key_inventary]['ed_izmer_num'] =
                            $array_inventory_zerro[$key_inventary]['ed_izmer_num'] - $item_PR['ed_izmer_num'];
                    }

                    unset($arrayPrihodRashod[$key_PR]);

                }
            }
        }

//        ddd($array_inventory_zerro);
//        ddd($arrayPrihodRashod);


//        dd($array_inventory_zerro);
//        ddd($arrayPrihodRashod);


        ///
        ///  ЛОГИКА 1.
        ///
        ///  Проход- суммирование приходов
        foreach ($array_inventary as $key_inventary => $item_inventary) {

            //ddd($arrayPrihodRashod);
            foreach ($arrayPrihodRashod as $key_PR => $item_PR) {

                //Товар одинаковый по Id
                if ((int)$item_PR['wh_tk_element'] == (int)$item_inventary['wh_tk_element']) {

                    // Приход
                    if ($item_PR['plus_minus'] === 2) {

//                        ddd(1111222);

//                        if($item_PR['bar_code'] == '040803'){
//                            if($item_inventary['bar_code'] == '040803') {
////                                ddd($item_inventary); // 'bar_code' => '19600012150'
//                                ddd($item_PR); /// 'bar_code' => '19600012150'
//                                ///
//                                ddd($array_inventary);
//                                ddd(1111);
//                            }
//                        }
                        // ddd($item_PR);

                        /// With BARCODE
                        if (isset($item_PR['bar_code']) && !empty($item_PR['bar_code']) &&
                            $item_PR['bar_code'] == $item_inventary['bar_code']) {
                            //
                            //prihod_num
                            //rashod_num

                            //
                            if (isset($array_inventary[$key_inventary]['ed_izmer_num'])) {

                                $array_inventary[$key_inventary]['ed_izmer_num'] =
                                    $array_inventary[$key_inventary]['ed_izmer_num'] + $item_PR['ed_izmer_num'];

                            }

                            //
                            $array_inventary[$key_inventary]['dt_create_minus'] = $item_PR['date'];

                            unset($arrayPrihodRashod[$key_PR]);
//                            break;
                        }


//                        /// WithOUT BARCODE
//                        if (isset($item_PR['bar_code']) && empty($item_PR['bar_code']) &&
//                            $item_PR['bar_code'] === $item_inventary['bar_code']) {
//
//                            $array_inventary[$key_inventary]['prihod_num'] = $item_PR['ed_izmer_num'];
//                            $array_inventary[$key_inventary]['dt_create_minus'] = $item_PR['date'];
//                            //$array_inventary[$key_inventary]['withOUT'] = 'withOUT BARCODE';
//
////                            break;
//                            unset($arrayPrihodRashod[$key_PR]);
//                        }


                    }


                }

            }
        }


//        ddd($arrayPrihodRashod);
//        ddd($array_inventary);


        ///
        /// Готовим добавку/ Схлопываем ПРИХОД-РАСХОД
        ///
        $array_minimal = [];
        foreach ($arrayPrihodRashod as $key_PR => $item_PR) {

            // Приход2
            if ((int)$item_PR['plus_minus'] == 2) {

//                ddd(11111);
//                ddd($item_PR);

                ///
                if ($item_PR['bar_code'] != '') {
                    ///
                    if (!isset($array_minimal[$item_PR['bar_code']])) {

                        $array_minimal[$item_PR['bar_code']] = [
                            'wh_tk_amort' => $item_PR['wh_tk_amort'],
                            'wh_tk_element' => $item_PR['wh_tk_element'],
                            'bar_code' => (isset($item_PR['bar_code']) ? $item_PR['bar_code'] : ''),

                            'ed_izmer' => 1,
                            'ed_izmer_num' => 0,
                            'prihod_num' => $item_PR['ed_izmer_num'],
                            'dt_create_plus' => $item_PR['date']
                        ];
                    } else {

                        if (!empty($array_minimal[$item_PR['bar_code']]['prihod_num'])) {
                            $array_minimal[$item_PR['bar_code']]['prihod_num'] =
                                $array_minimal[$item_PR['bar_code']]['prihod_num'] + $item_PR['ed_izmer_num'];
                        } else {
                            $array_minimal[$item_PR['bar_code']]['prihod_num'] = $item_PR['ed_izmer_num'];
                        }
                    }


                    unset($arrayPrihodRashod[$key_PR]);
                    ////
                }

//                else {
//                    // ДЛЯ БЕСШТРИХКОДОВЫХ
//                    $array_inventary[] = [
//                        'wh_tk_amort' => $item_PR['wh_tk_amort'],
//                        'wh_tk_element' => $item_PR['wh_tk_element'],
//                        'bar_code' => '',
//                        'ed_izmer' => 1,
//                        'ed_izmer_num' => 0,
//                        'prihod_num' => $item_PR['ed_izmer_num'],
//                        'dt_create_plus' => $item_PR['date']
//                    ];
//                }

            }


//            ddd($arrayPrihodRashod);
//            ddd($array_inventary);


            //
            //            if ((int)$item_PR['plus_minus'] == 3) {
            //                ///
            //                if (!isset($array_minimal[$item_PR['bar_code']]['rashod_num'])) {
            //
            //                    $array_minimal[$item_PR['bar_code']]['rashod_num'] =  $item_PR['ed_izmer_num'];
            //
            //                } else {
            //                    if (!empty($array_minimal[$item_PR['bar_code']]['rashod_num'])) {
            //                        $array_minimal[$item_PR['bar_code']]['rashod_num'] =
            //                            $array_minimal[$item_PR['bar_code']]['rashod_num'] + $item_PR['ed_izmer_num'];
            //                    }
            //                }
            //
            //                //unset($arrayPrihodRashod[$key_PR]);
            //            }


        }


        //Добавляем
        $array_inventary = array_merge($array_inventary, $array_inventory_zerro);
        $array_inventary = array_merge($array_inventary, $array_minimal);

        //ddd($array_inventary);


        //
        unset($array_inventory_zerro);
        unset($array_minimal);

        //ok!!!!!!!!!!!!!!


        ///
        ///  ЛОГИКА 2.
        ///
        ///  Проход- отнимание расходов
        ///
        foreach ($arrayPrihodRashod as $key_PR => $item_PR) {

            ///
            foreach ($array_inventary as $key_inventary => $item_inventary) {

                //Товар одинаковый по Id
                if ($item_PR['wh_tk_element'] == $item_inventary['wh_tk_element']) {


                    // Приход
                    if ((int)$item_PR['plus_minus'] == 3) {


                        /// With BARCODE
                        if (isset($item_PR['bar_code']) && !empty($item_PR['bar_code']) &&
                            (string)$item_PR['bar_code'] == (string)$item_inventary['bar_code']) {
                            //prihod_num
                            //rashod_num

                            //
                            if (isset($array_inventary[$key_inventary]['rashod_num'])) {
                                $array_inventary[$key_inventary]['rashod_num'] =
                                    $array_inventary[$key_inventary]['rashod_num'] + $item_PR['ed_izmer_num'];
                            } else {
                                $array_inventary[$key_inventary]['rashod_num'] = $item_PR['ed_izmer_num'];
                            }

                            //
                            $array_inventary[$key_inventary]['dt_create_minus'] = $item_PR['date'];

                        }


                        ///WithOUT BARCODE
                        if (!isset($item_PR['bar_code']) || empty($item_PR['bar_code'])) {

                            //ddd($item_PR);
                            //ddd($array_inventary[$key_inventary]);

                            $array_inventary[$key_inventary]['ed_izmer_num'] =
                                $array_inventary[$key_inventary]['ed_izmer_num'] - $item_PR['ed_izmer_num'];


                        }
                    }

                    unset($arrayPrihodRashod[$key_PR]);
                }
            }
        }


//         ddd($arrayPrihodRashod);
//        ddd($array_inventary);


        //      ddd($array_minimal); //497   1034
        //      ddd($arrayPrihodRashod);

        ///
        unset($array_minimal);
        ///


        ///#############
        /// Дополняем недостающие поля ВИЗУАЛИЗАЦИИ.
        /// ПОДСЧЕТ ИТОГОВОГО ПОЛЯ.
        ///
        foreach ($array_inventary as $key_a => $item_a) {
            if (!isset($item_a['prihod_num'])) {
                $array_inventary[$key_a]['prihod_num'] = 0;
            }
            if (!isset($item_a['rashod_num'])) {
                $array_inventary[$key_a]['rashod_num'] = 0;
            }
            if (!isset($item_a['itog'])) {
                if (isset($array_inventary[$key_a]['ed_izmer_num'])) {
                    $array_inventary[$key_a]['itog'] =
                        $array_inventary[$key_a]['ed_izmer_num']
                        + $array_inventary[$key_a]['prihod_num']
                        - $array_inventary[$key_a]['rashod_num'];
                } else {
                    $array_inventary[$key_a]['itog'] =
                        $array_inventary[$key_a]['prihod_num'] - $array_inventary[$key_a]['rashod_num'];
                }
            }

            $array_inventary[$key_a]['ed_izmer_num'] = $array_inventary[$key_a]['itog'];
//            unset($array_inventary[$key_a]['itog']);
            unset($array_inventary[$key_a]['prihod_num']);
            unset($array_inventary[$key_a]['rashod_num']);
        }


//        ddd($array_inventary);

        return $array_inventary;
    }


    /**
     * Главная ЛОГИКА.
     *-
     * Получает два массива.
     * Первый - Остатки на начало.
     * Второй - Массив Приход-расход.
     * На выходе - полный массив ИТОГО.
     *
     * @param $array_inventary
     * @param $arrayPrihod
     * @return mixed
     */
    public static function Math_summary_wh_prihod($array_inventary, $arrayPrihod)
    {
        ddd($arrayPrihod);


        // 1. Оптимизация ПРИХОДОВ все плюсуется
        $rez = [];

        ///Инвентаризация
//        foreach ($array_inventary as $key_inventary => $item_inventary) {

        //Приходы
        foreach ($arrayPrihod as $model_pr) {
            foreach ($model_pr as $key_PR => $item_PR) {
                if (is_array($item_PR)) {

                    $rez[$key_PR] = (int)$item_PR['wh_tk_element']; // 1...7
                    //ddd($item_PR);
                    ///  'name_wh_tk_amort' => 'Оборудование АСУОП'
                    //    'name_wh_tk_element' => 'Validator CVB24 master Терминал пассажира CVB24 ведущий (Master)'
                    //    'name_ed_izmer' => 'шт'
                    //    'ed_izmer' => '1'
                    //    'bar_code' => '19600002534'
                    //    'intelligent' => 1
                    //    'wh_tk_amort' => '7'
                    //    'wh_tk_element' => '2'
                    //    'take_it' => '0'
                    //    'ed_izmer_num' => '1'

                    $rez[$key_PR] = [
                        'gr' => (int)$item_PR['wh_tk_amort'],
                        'el' => (int)$item_PR['wh_tk_element'],
                        'bc' => $item_PR['bar_code'],
                        'i' => (int)$item_PR['ed_izmer_num'],
                    ];


                    // Одинаковые id - товаров
//                        if ((int)$item_inventary['wh_tk_element'] == (int)$item_PR['wh_tk_element']) {
//
////                        ddd($item_PR);
////                        ddd($item_inventary);
//
//                            /// With BARCODE
//                            if (isset($item_PR['bar_code']) && !empty($item_PR['bar_code']) &&
//                                $item_PR['bar_code'] === $item_inventary['bar_code']) {
//
//                                //Дата снятия должна быть ПОЗЖЕ даты установки
//                                $array_inventary[$key_inventary]['rashod_num'] = $item_PR['ed_izmer_num'];
//                                $array_inventary[$key_inventary]['dt_create_minus'] = $item_PR['date'];
//                                unset($arrayPrihod[$key_PR]);
//                                break;
//                            }
//                            else{
//                                /// WithOUT BARCODE
//                                $array_inventary[$key_inventary]['rashod_num'] = $item_PR['ed_izmer_num'];
//                                $array_inventary[$key_inventary]['dt_create_minus'] = $item_PR['date'];
//                                //$array_inventary[$key_inventary]['withOUT'] = 'withOUT BARCODE';
//                                unset($arrayPrihod[$key_PR]);
//                                break;
//                            }
//
//
//                        }
                }
            }
        }


//        }

        ddd($rez);
        ddd($array_inventary);
        ddd(111);


        ///
        /// Готовим добавку
        ///
        $array_minimal = [];
        foreach ($arrayPrihodRashod as $key_PR => $item_PR) {
            // Приход3
            if ((int)$item_PR['plus_minus'] === 2) {
                $array_minimal[] = [
                    'wh_tk_amort' => $item_PR['wh_tk_amort'],
                    'wh_tk_element' => $item_PR['wh_tk_element'],
                    'bar_code' => (isset($item_PR['bar_code']) ? $item_PR['bar_code'] : ''),

                    'ed_izmer' => 1,
                    'ed_izmer_num' => 0,
                    'prihod_num' => $item_PR['ed_izmer_num'],
                    'dt_create_plus' => $item_PR['date']
                ];
                unset($arrayPrihodRashod[$key_PR]);
            }

        }
        reset($arrayPrihodRashod);


//        ddd($array_minimal);


        // ddd($array_inventary);

        return $array_inventary;
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
                if ((int)$item['itog'] > 0) {
                    $itog_str++;
                    $itogo_things += (int)$item['itog'];
                }
            }

        $array_res['itogo_actions'] = $itog_act;     // 'Общее количество деействий',
        $array_res['itogo_strings'] = $itog_str;     //'Общее количество сторок',
        $array_res['itogo_things'] = $itogo_things;     //'Общее количество штук',

        return $array_res;
    }

    /**
     * Подсчет/Запись данных в массив
     * =
     * БЕЗ БАРКОДОВ!
     * -
     *
     * @param $array
     * @param $group_id
     * @param $element_id
     * @param $minus_plus
     * @param $sum_number
     * @return
     */
    static function Array_save_by_pos($array, $group_id, $element_id, $minus_plus, $sum_number)
    {

        $sum_number = (int)$sum_number;
        //ddd($array);// 1 = PLUS
        //        ddd($sum_number);// 1 = PLUS
        //        ddd($minus_plus);// 1 = PLUS
        //        ddd($element_id);// 13
        //        ddd($group_id);// 7


        foreach ($array as $key => $item) {
            if (empty($item['bar_code'])) {

                if ($item['wh_tk_amort'] == $group_id && $item['wh_tk_element'] == $element_id) {

                    if ($minus_plus > 0) {
                        $array[$key]['prihod_num'] = $array[$key]['prihod_num'] + $sum_number;
                        $array[$key]['itog'] = $array[$key]['itog'] + $sum_number;
                    } else {
                        $array[$key]['rashod_num'] = $array[$key]['rashod_num'] + $sum_number;
                        $array[$key]['itog'] = $array[$key]['itog'] - $sum_number;
                    }
                }
            }
        }

        return $array;
    }

    /**
     * Версия 2 NEW
     *
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ. По дате движения
     * -
     * Возвращает массив с ПРРИХОДОМ и Расходом
     *
     * @param $sklad_id
     * @param $dt_create_timestamp
     * @return mixed
     */
    public
    static function ArrayPrihodRashod_v2($sklad_id, $dt_create_timestamp = 0)
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
                    ['>=', 'dt_create_timestamp', $dt_create_timestamp],
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
            ->orderBy(['dt_create_timestamp'])
            ->asArray()
            ->all();

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
     * Количественный учет всех товаров
     * (БЕЗ учета по штрихкодам)
     *
     * @param $sklad
     * @return string
     * @throws NotFoundHttpException
     */
    public
    function actionKolish($sklad)
    {
        $sklad_id = $sklad;
        if (!isset($sklad_id)) {
            throw new NotFoundHttpException('Нужен номер склада.');
        }


        /////////////////////
        $array_pihod_amprt = $this->actionKolish_amort($sklad_id);

        //        ddd($array_pihod_amprt);
        /////////////////////
        $array_pihod_spisanie = $this->actionKolish_spisanie($sklad_id);
        //ddd($pihod_spisanie);
        /////////////////////
        $array_pihod_rashod = $this->actionKolish_rashod($sklad_id);
        //ddd($pihod_rashod);


        $DataProvider_pihod_amprt = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_amprt,
            ]
        );

        $DataProvider_pihod_spisanie = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_spisanie,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );
        $DataProvider_pihod_rashod = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_rashod,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]
        );


        return $this->render(
            'index', [
                'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
                //            'model_pihod_amprt' => $array_pihod_amprt,
                'DataProvider_pihod_spisanie' => $DataProvider_pihod_spisanie,
                //            'model_pihod_spisanie' => $array_pihod_spisanie,
                'DataProvider_pihod_rashod' => $DataProvider_pihod_rashod,
                //            'model_pihod_rashod' => $array_pihod_rashod,
                'id_sklad' => $sklad_id,
                'sklad_id' => $sklad_id,
            ]
        );

    }

    /**
     * ВЕСЬ ПРИХОД
     * -
     * into AMORT
     *
     * @param $sklad_id
     * @return array
     */
    function actionKolish_amort($sklad_id)
    {


        /////////
        $array_items = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                    //'dt_create',
                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                ]
            )
            ->where(['wh_home_number' => (int)$sklad_id])
            //            ->asArray()
            ->all();

        //ddd($array_items);  // 891
        //////////////
        //  $array_bufer_amort=[];
        $array_itog_amort = [];


        foreach ($array_items as $num_id) {
            //$array_bufer_amort = [''];
            //                if($num_id['id']==891){
            //                    ddd($num_id);
            //                }
            // ARRAY
            if (isset($num_id['array_tk_amort']) && !empty($num_id['array_tk_amort'])) {
                $array_bufer_amort = $num_id['array_tk_amort'];


                //                    if($num_id['id']==891){
                //                        ddd($array_bufer_amort);
                //ddd($num_id);
                //                    }

                $x_plus = 0;
                $x_minus = 0;


                //                ddd($array_bufer_amort);


                foreach ($array_bufer_amort as $item_pos) {

                    if (!isset($item_pos['wh_tk_amort']) || empty($item_pos['wh_tk_amort'])) {
                        //ddd($array_items);
                        //                                                ddd($num_id);
                        //                        ddd($sklad_id);
                        //                        ddd($array_bufer_amort);
                        //                        ddd($item_pos);

                        $item_pos['wh_tk_amort'] = ''; /// Attension!
                    }

                    $array_itog_amort[(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['sklad'] = $sklad_id;


                    //'wh_tk_amort' => '5'
                    //'wh_tk_element' => '13'
                    //'ed_izmer' => '1'
                    //'ed_izmer_num' => '2'

                    if ((int)$num_id['sklad_vid_oper'] == 2) {
                        $x_plus++;
                        $array_itog_amort[(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['plus'] = (isset($array_itog_amort[(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['plus']) ?
                                $array_itog_amort[(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['plus'] : 0) +
                            $item_pos['ed_izmer_num'];
                    }
                    if ((int)$num_id['sklad_vid_oper'] == 3) {
                        $x_minus++;

                        $array_itog_amort[(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['minus'] = (isset($array_itog_amort[(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['minus']) ?
                                $array_itog_amort[(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['minus'] : 0) +
                            $item_pos['ed_izmer_num'];
                    }
                }
                //      ddd( $array_itog_amort );
            }
            unset($num_id);
        }

        //ddd($array_itog_amort);

        return $this->Privod_toarray_2d_amort($array_itog_amort);

    }

    /**
     * Приводим эти специфичные массивы к нормальному ВИДУ
     * Для чтения в виджете-таблице GridView
     * Только для AMORT
     *
     * @param $array_amort
     * @return array
     */
    function Privod_toarray_2d_amort($array_amort)
    {
        $array_out = [];

        $group_arr = ArrayHelper::map(
            Spr_globam::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy(['id'])
                ->asArray()->all(), 'id', 'name'
        );

        $element_arr = ArrayHelper::map(
            Spr_globam_element::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy(['id'])
                ->asArray()->all(), 'id', 'name'
        );

        //        $things_arr= ArrayHelper::map(Spr_things::find()
        //            ->select(['id','name'])
        //            ->orderBy(['id'])
        //            ->asArray()->all(), 'id', 'name' );
        //ddd($things_arr);


        foreach ($array_amort as $key_group => $item_group) {
            foreach ($item_group as $key_in => $item_in) {

                $array_out[] = [
                    //                        'group_id'=> $key_group ,
                    //                        'item_id' => $key_in ,

                    'sklad_id' => $item_in['sklad'],
                    'group_nn' => $key_group,
                    'item_nn' => $key_in,
                    'group_id' => (isset($group_arr[$key_group]) ? $group_arr[$key_group] : 0),
                    'item_id' => $element_arr[$key_in],
                    'minus' => (isset($item_in['minus']) ? $item_in['minus'] : '0'),
                    'plus' => (isset($item_in['plus']) ? $item_in['plus'] : '0'),
                ];
            }
        }

        //ddd($item_in['sklad']);

        return $array_out;

    }

    /**
     * ВЕСЬ ПРИХОД
     * -
     * into  SPISANIE
     *
     * @param $sklad_id
     * @return array
     */
    function actionKolish_spisanie($sklad_id)
    {

        /////////
        $array_items = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                    //'dt_create',
                    //'array_tk',
                    'array_tk.wh_tk',
                    'array_tk.wh_tk_element',
                    'array_tk.ed_izmer',
                    'array_tk.ed_izmer_num',
                ]
            )
            ->where(['wh_home_number' => (int)$sklad_id])
            ->asArray()
            ->all();

        //        ddd($array_items);
        //////////////
        //  $array_bufer_amort=[];
        $array_itog_amort = [];


        foreach ($array_items as $num_id) {

            //ddd($num_id);
            // ARRAY
            if (isset($num_id['array_tk']) && !empty($num_id['array_tk'])) {
                $array_bufer_amort = $num_id['array_tk'];


                //                    if($num_id['id']==891){
                //                        ddd($array_bufer_amort);
                //ddd($num_id);
                //                    }

                $x_plus = 0;
                $x_minus = 0;

                foreach ($array_bufer_amort as $item_pos) {
                    $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['sklad'] = $sklad_id;


                    //'wh_tk_amort' => '5'
                    //'wh_tk_element' => '13'
                    //'ed_izmer' => '1'
                    //'ed_izmer_num' => '2'

                    if ((int)$num_id['sklad_vid_oper'] == 2) {
                        $x_plus++;

                        $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] = (isset($array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus']) ?
                                $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] : 0) +
                            $item_pos['ed_izmer_num'];
                    }
                    if ((int)$num_id['sklad_vid_oper'] == 3) {
                        $x_minus++;

                        $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] = (isset($array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus']) ?
                                $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] : 0) +
                            $item_pos['ed_izmer_num'];
                    }
                }
                //      ddd( $array_itog_amort );
            }
            unset($num_id);
        }

        // ddd($array_itog_amort);

        return $this->Privod_toarray_2d($array_itog_amort);

    }


    /**
     * Приводим эти специфичные массивы к нормальному ВИДУ
     * Для чтения в виджете-таблице GridView
     * Только для ПИСАНИЕ И РАСХОДНИКИ
     *
     * @param $array_amort
     * @return array
     */
    function Privod_toarray_2d($array_amort)
    {
        $array_out = [];

        $group_arr = ArrayHelper::map(
            Spr_glob::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy(['id'])
                ->asArray()->all(), 'id', 'name'
        );

        $element_arr = ArrayHelper::map(
            Spr_glob_element::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy(['id'])
                ->asArray()->all(), 'id', 'name'
        );

        //        $things_arr= ArrayHelper::map(Spr_things::find()
        //            ->select(['id','name'])
        //            ->orderBy(['id'])
        //            ->asArray()->all(), 'id', 'name' );
        //ddd($things_arr);
        //        6 => 'Инструменты    '
        //        7 => 'Прочие материалы    '
        //        8 => 'Спецодежда    '
        //        9 => 'Сырье и материалы    '
        //        10 => 'Материалы забалансовый'
        //        12 => 'Товары'
        //        13 => 'Карты с чипом'
        //        14 => 'Основные средства'
        //        15 => 'POS терминалы'
        //ddd($group_arr);
        //ddd($array_amort);
        foreach ($array_amort as $key_group => $item_group) {
            foreach ($item_group as $key_in => $item_in) {
                $array_out[] = [
                    'sklad_id' => $item_in['sklad'],
                    'group_nn' => $key_group,
                    'item_nn' => $key_in,
                    'group_id' => $group_arr[$key_group],
                    'item_id' => (isset($element_arr[$key_in]) ? $element_arr[$key_in] : '0'),
                    'minus' => (isset($item_in['minus']) ? $item_in['minus'] : '0'),
                    'plus' => (isset($item_in['plus']) ? $item_in['plus'] : '0'),
                ];
            }
        }

        //        ddd($array_out);

        return $array_out;

    }

    /**
     * ВЕСЬ ПРИХОД
     * -
     * into REASHOD
     *
     * @param $sklad_id
     * @return array
     */
    function actionKolish_rashod($sklad_id)
    {

        /////////
        $array_items = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                    //'dt_create',
                    //                'array_casual',
                    'array_casual.wh_tk',
                    'array_casual.wh_tk_element',
                    'array_casual.ed_izmer',
                    'array_casual.ed_izmer_num',
                ]
            )
            ->where(['wh_home_number' => (int)$sklad_id])
            ->asArray()
            ->all();

        //ddd($array_items);
        //////////////
        //  $array_bufer_amort=[];
        $array_itog_amort = [];


        foreach ($array_items as $num_id) {

            //ddd($num_id);
            // ARRAY
            if (isset($num_id['array_casual']) && !empty($num_id['array_casual'])) {
                $array_bufer_amort = $num_id['array_casual'];


                //                    if($num_id['id']==891){
                //                        ddd($array_bufer_amort);
                //ddd($num_id);
                //                    }

                $x_plus = 0;
                $x_minus = 0;

                foreach ($array_bufer_amort as $item_pos) {

                    $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['sklad'] = $sklad_id;

                    //'wh_tk_amort' => '5'
                    //'wh_tk_element' => '13'
                    //'ed_izmer' => '1'
                    //'ed_izmer_num' => '2'

                    if ((int)$num_id['sklad_vid_oper'] == 2) {
                        $x_plus++;

                        $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] = (isset($array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus']) ?
                                $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] : 0) +
                            $item_pos['ed_izmer_num'];
                    }
                    if ((int)$num_id['sklad_vid_oper'] == 3) {
                        $x_minus++;

                        $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] = (isset($array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus']) ?
                                $array_itog_amort[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] : 0) +
                            $item_pos['ed_izmer_num'];
                    }
                }
                //      ddd( $array_itog_amort );
            }
            unset($num_id);
        }

        //ddd($array_itog_amort);

        return $this->Privod_toarray_2d($array_itog_amort);

    }

    /**
     * Все номера накладных (Id + Vid_oper)
     * -
     * Глобальный список номеров наклданых + тайштамп для Данного Склада
     *
     * @param int $id_sklad
     * @return array
     */
    function ListItemsOnTheSklad($id_sklad)
    {
        $sklad_one_naklad = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                ]
            )
            //->select(['wh_home_number','id','dt_create_timestamp','sklad_vid_oper'])
            ->where(['wh_home_number' => (int)$id_sklad])
            ->orderBy(
                [
                    'dt_create_timestamp',
                    'id',
                ]
            )// Сортируем по времени создания + id
            ->asArray()
            ->all();

        //ddd($sklad_one_naklad);
        return ArrayHelper::map($sklad_one_naklad, 'id', 'sklad_vid_oper');

    }

    /**
     * Вывод списком. Все ПРИХОДЫ и Расходы
     * Only AMORT
     */
    public
    function actionEnterGrElem_am()
    {
        $para = Yii::$app->request->queryParams;


        $sklad_id = $para['sklad_id'];

        if (!isset($sklad_id)) {
            throw new NotFoundHttpException('Нужен номер склада.');
        }


        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        $array_pihod_amprt = Sklad::Mongo_findHisory_GropElement_am((int)$sklad_id, $para['gr'], $para['el']);


        $spr_full_name = Spr_globam_element::findFullArray($para['el']);

        // ddd($spr_full_name);


        $DataProvider_pihod_amprt = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_amprt,
            ]
        );


        return $this->render(
            'index_into',
            [
                'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
                'model_pihod_amprt' => $array_pihod_amprt,
                'id_sklad' => $sklad_id,
                'spr_full_name' => $spr_full_name,
            ]
        );

    }

    /**
     * ПроСТО ВСЕ ПУСТЫЕ ГРУППЫ ИЛИ НАИМЕНОВАНИЯ
     * -
     * Only AMORT
     */
    public
    function actionAll_empty()
    {
        ///ddd(123);
        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        $array_pihod_amprt = Sklad::Mongo_all_empty();

        //         ddd($array_pihod_amprt);


        $DataProvider_pihod_amprt = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_amprt,
            ]
        );


        return $this->render(
            'index_into', [
                'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
                'model_pihod_amprt' => $array_pihod_amprt,
                //            'id_sklad' => $sklad_id,
            ]
        );

    }

    /**
     * РЕМОНТИРУЕМ
     * ВСЕ ПУСТЫЕ ГРУППЫ
     * -
     * Only AMORT
     */
    public
    function actionAll_empty_remont()
    {
        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        //$x = Sklad::Remont_all_empty();

        $x = Sklad::Remont_all_empty_am();

        $y = Sklad::Remont_all_empty();


        ddd('All is OK. Сейчас обработано ошибок = ' . $x . " и = " . $y);

    }

    /**
     * Вывод списком. Все ПРИХОДЫ и Расходы
     * ПО ШТРИХКОДАМ
     * -
     * Only AMORT
     */
    public
    function actionEnterGrElem_am_barcode()
    {
        $para = Yii::$app->request->queryParams;

        //ddd($para);
        //    'sklad_id' => '86'
        //    'gr' => '4'
        //    'el' => '12'

        $sklad_id = $para['sklad_id'];

        if (!isset($sklad_id)) {
            throw new NotFoundHttpException('Нужен номер склада.');
        }


        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        $array_pihod_amprt = Sklad::Mongo_findHisory_GropElement_am_barcode((int)$sklad_id, $para['gr'], $para['el']);


        $DataProvider_pihod_amprt = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_amprt,
            ]
        );


        //ddd($DataProvider_pihod_amprt->getModels());

        return $this->render(
            'index_into_barcode', [
                'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
                'model_pihod_amprt' => $array_pihod_amprt,
                'id_sklad' => $sklad_id,
            ]
        );

    }

    /**
     * Вывод списком. Все ПРИХОДЫ и Расходы
     * Only SPISANIE
     */
    public
    function actionEnterGrElem_sp()
    {
        $para = Yii::$app->request->queryParams;

        // ddd($para);
        //        'gr' => '5'
        //        'el' => '13'
        //        'sklad_id' => '86'

        $sklad_id = $para['sklad_id'];

        if (!isset($sklad_id)) {
            throw new NotFoundHttpException('Нужен номер склада.');
        }

        /// Дата крайней инвентаризации
        //			    $array_date=Sklad_inventory::Array_inventory_lastdate();
        //			    ddd($array_date);
        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        $array_pihod_amprt = Sklad::Mongo_findHisory_GropElement_sp(
            (int)$sklad_id,
            $para['gr'],
            $para['el']
        );


        $spr_full_name = Spr_glob_element::findFullArray($para['el']);


        $DataProvider_pihod_amprt = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_amprt,
            ]
        );


        return $this->render(
            'index_into', [
                'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
                'model_pihod_amprt' => $array_pihod_amprt,
                'spr_full_name' => $spr_full_name,
                //            'id_sklad' => $id_sklad,
            ]
        );

    }

    /**
     * Вывод списком. Все ПРИХОДЫ и Расходы
     * Only RASHODNIKI
     */
    public
    function actionEnterGrElem_rash()
    {
        $para = Yii::$app->request->queryParams;

        //ddd($para);
        //        'gr' => '5'
        //        'el' => '13'
        //        'sklad_id' => '86'

        $sklad_id = $para['sklad_id'];

        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        $array_pihod_amprt = Sklad::Mongo_findHisory_GropElement_rash((int)$sklad_id, $para['gr'], $para['el']);


        $spr_full_name = Spr_glob_element::findFullArray($para['el']);


        $DataProvider_pihod_amprt = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_amprt,
            ]
        );

        //        ddd($DataProvider_pihod_amprt);

        return $this->render(
            'index_into', [
                'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
                'model_pihod_amprt' => $array_pihod_amprt,
                'spr_full_name' => $spr_full_name,
                'id_sklad' => $id_sklad,
            ]
        );

    }

}