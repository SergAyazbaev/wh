<?php
namespace console\controllers;

use frontend\models\Sklad;
use frontend\models\Sklad_cs_past_inventory;
use frontend\models\Sklad_inventory_cs;
use yii\console\Controller;




/**
 * SSS
 */
class Stat_balans_cs_Controller extends Controller
{


    /**
     * 1
     * index
     * Проверка actionIndex
     */
    public function actionIndex()
    {

        $str0 = "\n Ok Stat_balns \n Yes, cron service is running.";
        $str1 = $this->ansiFormat('Alex', Console::FG_RED);
        $str2 = $this->ansiFormat($str0, Console::FG_YELLOW);
        echo "\n=== " . $str1;
        echo "=== " . $str2;
        echo "\n=== \n";

        return 0;
    }

    /**
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
     * (ШТУЧНЫЙ. С учетом ШТРИХКОДОВ )
     *-
     * Возвращает массив с ПРРИХОДОМ и Расходом
     *
     * @param $sc_sklad
     * @param $dt_start_timestamp
     *
     * @return array
     */
    public static function ArrayPrihodRashod_sc($sc_sklad, $dt_start_timestamp)
    {
        $sc_sklad = (int)$sc_sklad;
        $sc_sklad_str = (string)$sc_sklad;
        $dt_start_timestamp = (int)$dt_start_timestamp;

        if (!isset($dt_start_timestamp) || !isset($sc_sklad) || empty($dt_start_timestamp) || empty($sc_sklad)
        ) {
            return [];
        }


        /////////
        /// Все приходные и расходные накладные за период
        /// ВЫБРАТЬ ТОЛЬКО ЦС!!!!
        ///
        $array_items =
            Sklad::find()
                ->where(
                    [
                        'AND',
                        [
                            '>',
                            'dt_create_timestamp',
                            $dt_start_timestamp,
                        ],
                        [
                            'OR',
                            [
                                '==',
                                'wh_cs_number',
                                $sc_sklad,
                            ],
                            [
                                '==',
                                'wh_cs_number',
                                $sc_sklad_str,
                            ],
                        ],

                    ]
                )
                ->orderBy('dt_create_timestamp')
                ->asArray()
                ->all();

//        ddd( $array_items );
//        19600005916


        //////////////


        $array_itog_amort1 = [];
        foreach ($array_items as $num_id) { // Это одна накладная


            //            ddd( $num_id );

            if (isset($num_id['array_tk_amort']) && !empty($num_id['array_tk_amort'])) {////array_tk_amort


                $x_plus = 0;
                //                $x_minus = 0;

                foreach ($num_id['array_tk_amort'] as $item_pos) {


                    ////3-5
                    //
                    // 		'1' => 'Инвентаризация',
                    //		'2' => 'Приходная накладная',
                    //		'3' => 'Расходная накладная',
                    //		'4' => 'Снятие(замена)',
                    //		'5' => 'Установка(замена)'
                    //

                    if ((int)$num_id['sklad_vid_oper'] == 3 || (int)$num_id['sklad_vid_oper'] == 5) {
                        $x_plus++;

                        if (!isset($item_pos['intelligent']) || (int)$item_pos['intelligent'] != 1) {

                            $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']] [(int)$item_pos['wh_tk_element']]['ed_izmer_num'] =

                                (isset($array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']] [(int)$item_pos['wh_tk_element']]['ed_izmer_num']) ?
                                    $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']] [(int)$item_pos['wh_tk_element']]['ed_izmer_num'] : 0) +

                                (int)$item_pos['ed_izmer_num'];
                        }


                        if (isset($item_pos['bar_code']) && !empty($item_pos['bar_code'])) {
                            $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['bar_code'][$item_pos['bar_code']]
                                = $item_pos['bar_code'];
                        }

                        $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['ed_izmer']
                            = $item_pos['ed_izmer'];


                    }

                    ////2-4
                    if ((int)$num_id['sklad_vid_oper'] == 2 || (int)$num_id['sklad_vid_oper'] == 4) {

                        if (!empty($item_pos['bar_code'])) {
                            $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['bar_code'][$item_pos['bar_code']]
                                = $item_pos['bar_code'];
                        }

                        $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['intelligent']
                            = (isset($item_pos['intelligent']) ? $item_pos['intelligent'] : 0);
                        $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['ed_izmer']
                            = $item_pos['ed_izmer'];
                        $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['ed_izmer_num']
                            = $item_pos['ed_izmer_num'];

                        //ddd($array_itog_amort1);
                        //ddd($item_pos);
                    }


                }
                //ddd($array_itog_amort1);
            }
        }


        return $array_itog_amort1;
    }




    ////////////
    ////////////
    ////////////
    ////////////
    ////////////

    /**
     * CS. Iventory REGERERATE
     *
     * КРАЙНЯЯ ИНВЕНТАРИЗАЦИЯ +приход -расход
     * -
     * временно : по номеру списку активных складов ИНВЕНТАРИЗАЦИИ
     *
     * @return int
     */
    function actionCs_past_inventory_summary()
    {
        ///
        /// ОБЩЕЕ УДАЛЕНИЕ ИНВЕНТАРИЗАЦИЙ
        ///   DELETE  -ALL- OLD ROWS
        ///
        Sklad_cs_past_inventory::deleteAll();


        ///
        ///    Список всех активных складов
        ///
        $list_keys = Sklad_inventory_cs::Array_inventory_ids();

        ///
        foreach ($list_keys as $item) {
            if (!$this->actionPast_inventory_id($item)) {
                $err[] = $item;
            };
        }

//        $this->actionPast_inventory_id(4612);
        //$this->actionPast_inventory_id(4714);

        return 0;
    }


    /**
     *  СОЗДАНИЕ НОВОЙ ИНВЕНТАРИЗАЦИИ по Одному Складу
     * =
     * 2
     *
     * @param $sklad_id
     * @return bool
     */
    function actionPast_inventory_id($sklad_id)
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
        $arrayPrihodRashod = $this->ArrayPrihodRashod_v2(
            $array_items_inventory['wh_destination_element'],
            $array_items_inventory['dt_create_timestamp']
        );


        //         ddd($arrayPrihodRashod);


        ///
        ///  ЛОГИКА 1.
        ///
        ///  Проход- вычитание из массива Инвентаризации
//        $array_itog_amort = [];
        $array_inventary = $array_items_inventory['array_tk_amort']; // Ok ИНВЕНТАРИЗАЦИЯ - СТОЛБ

        foreach ($array_inventary as $key_inventary => $item_inventary) {
            //    'wh_tk_amort' => 7
            //    'wh_tk_element' => 13
            //    'ed_izmer' => 1
            //    'ed_izmer_num' => 2
            //    'bar_code' => ''
            //    'intelligent' => 0

            foreach ($arrayPrihodRashod as $key_PR => $item_PR) {

                if ($item_PR['wh_tk_amort'] == $item_inventary['wh_tk_amort'] && $item_PR['wh_tk_element'] == $item_inventary['wh_tk_element']) {

                    // Приход НЕ ТУТ !!!! А уже после СТОЛБОВОГО ВЫЧИТАНИЯ
                    //                    if($item_PR['plus_minus']==2 ){
                    //                        $array_inventary[$key_inventary]['prihod']=$item_PR['ed_izmer_num'];
                    //                        $array_inventary[$key_inventary]['date_prihod']=$item_PR['date'];
                    //                    }

                    // Расход2
                    if ($item_PR['plus_minus'] === 2) {
                        /// With BARCODE
                        if (isset($item_PR['bar_code']) && !empty($item_PR['bar_code']) && $item_PR['bar_code'] === $item_inventary['bar_code']) {
                            $array_inventary[$key_inventary]['rashod_num'] = $item_PR['ed_izmer_num'];
                            $array_inventary[$key_inventary]['dt_create_minus'] = $item_PR['date'];
                            $array_inventary[$key_inventary]['barrr'] = $item_PR['bar_code'];

                            //unset($arrayPrihodRashod[$key_PR]);
                            $array_inventary[$key_inventary]['with'] = ' BARCODE';
                        }

                        /// WithOUT BARCODE
                        if (isset($item_PR['bar_code']) && empty($item_PR['bar_code']) && $item_PR['bar_code'] === $item_inventary['bar_code']) {
                            $array_inventary[$key_inventary]['rashod_num'] = $item_PR['ed_izmer_num'];
                            $array_inventary[$key_inventary]['dt_create_minus'] = $item_PR['date'];
                            $array_inventary[$key_inventary]['withOUT'] = 'withOUT BARCODE';
                            unset($arrayPrihodRashod[$key_PR]);
                        }
                    }


                }

            }


        }

//        ddd($arrayPrihodRashod);
//        ddd($array_inventary);


        ///
        /// Готовим добавку
        ///
        $array_minimal = [];
        foreach ($arrayPrihodRashod as $key_PR => $item_PR) {
            // Приход3
            if ($item_PR['plus_minus'] == 3) {
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
//        ddd($arrayPrihodRashod);

//        'plus_minus' => 2
//        'bar_code' => '19600002564'
//        11111 => 1111
//
//        'plus_minus' => 2
//        'bar_code' => ''

        ///
        /// Готовим добавку
        /// отнимаем расходы
        ///
        $array_minimal2 = $array_minimal;

        foreach ($arrayPrihodRashod as $key_PR => $item_PR) {
            //ddd($item_PR);
            foreach ($array_minimal as $key_minimal => $item_minimal) {

                if ($item_PR['wh_tk_amort'] === $item_minimal['wh_tk_amort'] && $item_PR['wh_tk_element'] === $item_minimal['wh_tk_element']) {

                    // Расход2
                    if (isset($item_PR['plus_minus']) && $item_PR['plus_minus'] == 2) {

                        // BarCode
                        if (isset($item_PR['bar_code']) && !empty($item_PR['bar_code']) && $item_PR['bar_code'] == $item_minimal['bar_code']) {
                            $array_minimal2[$key_minimal] = $item_minimal;
                            $array_minimal2[$key_minimal]['rashod_num'] = $item_PR['ed_izmer_num'];
                            $array_minimal2[$key_minimal]['dt_create_minus'] = $item_PR['date'];
                            $array_minimal2[$key_minimal]['34343'] = 232323;

                            unset($arrayPrihodRashod[$key_PR]);
                        }

                        // BarCode NOT OR STR EMPTY
                        if (isset($item_PR['bar_code']) && empty($item_PR['bar_code']) && $item_PR['bar_code'] == $item_minimal['bar_code']) {
                            $array_minimal2[$key_minimal] = $item_minimal;
                            $array_minimal2[$key_minimal]['rashod_num'] = $item_PR['ed_izmer_num'];
                            $array_minimal2[$key_minimal]['dt_create_minus'] = $item_PR['date'];
                            $array_minimal2[$key_minimal]['empty_str'] = 'empty_str';

                            unset($arrayPrihodRashod[$key_PR]);
                        }
                    }

                }
            }
        }

//        ddd($array_minimal2);

        ///
        unset($array_minimal);
        ///
        $array_inventary = array_merge($array_inventary, $array_minimal2);
        ///
        unset($array_minimal2);


//        ddd($array_minimal2);
//        ddd($array_inventary);
        ///
//        foreach ($array_minimal2 as $arr_m) {
//            $array_inventary[] = $arr_m;
//        }



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
                $array_inventary[$key_a]['itog'] = $array_inventary[$key_a]['ed_izmer_num'] + $array_inventary[$key_a]['prihod_num'] - $array_inventary[$key_a]['rashod_num'];
            }

        }

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
                    //ddd($item);

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
     * Версия 2 NEW
     *
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
     * (ТОЛЬКО ШТУЧНЫЙ СКОПОМ. Без разделения на ШТРИХКОДЫ )
     * -
     * Возвращает массив с ПРРИХОДОМ и Расходом
     *
     * @param $sklad_id
     * @param $dt_create_timestamp
     * @return mixed
     */
    public static function ArrayPrihodRashod_v2($sklad_id, $dt_create_timestamp)
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

            if(isset($item_sklad['array_tk_amort']) && is_array($item_sklad['array_tk_amort'])) {
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

}
