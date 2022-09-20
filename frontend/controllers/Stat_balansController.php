<?php

namespace frontend\controllers;


use frontend\models\Sklad;
use frontend\models\Sklad_inventory;
use frontend\models\Sklad_inventory_cs;
use frontend\models\Sklad_past_inventory;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class Stat_balansController extends Controller
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
     * Подсчет остатков по ОДНОМУ(!) складу (ЦС)
     * =
     * ИСХОДНЫЕ ДАННЫЕ = промежуточные ОСТАТКИ
     * =
     * Возвращает Массив-Масивов (AM+TK+CASUAL)
     * -
     *
     * @param $sklad_element
     *
     * @return array|ActiveRecord|null
     */
    public static function get_aray_inventory_from_cs($sklad_element)
    {

        return
            Sklad_inventory_cs::find()
                ->where(
                    [
                        'OR',
                        [
                            'wh_home_number' => (int)$sklad_element],
                        [
                            'wh_home_number' => (string)$sklad_element],
                    ]
                )
                ->orderBy('dt_create_timestamp DESC')
                ->one();

    }

    /**
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
     * (ШТУЧНЫЙ. С учетом ШТРИХКОДОВ )
     * -
     * Возвращает массив с ПРРИХОДОМ и Расходом
     *
     * @param $sc_sklad
     * @param $dt_start_timestamp
     *
     * @return array
     */
    public static function ArrayPrihodRashod_sc($sc_sklad, $dt_start_timestamp)
    {

//              ddd(date('d.m.Y H:i:s',$dt_start_timestamp));
//              ddd($dt_start_timestamp);
//        '27.11.2019 09:35:43'


        $sc_sklad = (int)$sc_sklad;
        $sc_sklad_str = (string)$sc_sklad;
        $dt_start_timestamp = (int)$dt_start_timestamp;

        if (!isset($dt_start_timestamp) || !isset($sc_sklad) || empty($dt_start_timestamp) ||
            empty($sc_sklad)
        ) {
            return [
            ];
        }


        /////////
        /// Все приходные и расходные накладные за период
        /// ВЫБРАТЬ ТОЛЬКО ЦС!!!!
        ///
        $array_items = Sklad::find()
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


        $array_itog_amort1 = [
        ];
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

                    if ((int)$num_id['sklad_vid_oper'] == 3 || (int)$num_id['sklad_vid_oper'] ==
                        5) {
                        $x_plus++;

                        if (!isset($item_pos['intelligent']) || (int)$item_pos['intelligent'] !=
                            1) {

                            $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']] [(int)$item_pos['wh_tk_element']]['ed_izmer_num'] = (isset($array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']] [(int)$item_pos['wh_tk_element']]['ed_izmer_num']) ?
                                    $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']] [(int)$item_pos['wh_tk_element']]['ed_izmer_num'] : 0) +
                                (int)$item_pos['ed_izmer_num'];
                        }


                        if (isset($item_pos['bar_code']) && !empty($item_pos['bar_code'])) {
                            $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['bar_code'][$item_pos['bar_code']] = $item_pos['bar_code'];
                        }

                        $array_itog_amort1['plus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['ed_izmer'] = $item_pos['ed_izmer'];
                    }

                    ////2-4
                    if ((int)$num_id['sklad_vid_oper'] == 2 || (int)$num_id['sklad_vid_oper'] ==
                        4) {

                        if (!empty($item_pos['bar_code'])) {
                            $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['bar_code'][$item_pos['bar_code']] = $item_pos['bar_code'];
                        }

                        $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['intelligent'] = (isset($item_pos['intelligent']) ? $item_pos['intelligent'] : 0);
                        $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['ed_izmer'] = $item_pos['ed_izmer'];
                        $array_itog_amort1['minus'][(int)$item_pos['wh_tk_amort']][(int)$item_pos['wh_tk_element']]['ed_izmer_num'] = $item_pos['ed_izmer_num'];

                        //ddd($array_itog_amort1);
                        //ddd($item_pos);
                    }
                }
                //ddd($array_itog_amort1);
            }
        }


        return $array_itog_amort1;

    }

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
        $array_minus = [
        ];

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
     *
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
        $array_itog_amort = [
        ];
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


        $array_itog_amort = [
        ];
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
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function get_aray_inventory($sklad_element)
    {

        /////////
        //         $array_items_inventory = Sklad_inventory::find() // ПО ИНВЕНТАРИЗАЦИИ!!!!

        $array_items_inventory = Sklad_past_inventory::find()// ПО ПРОМЕЖУТОЧНЫМ ИНВЕНТАРИЗАЦИЯМ
        //            ->select([
        //                'id',
        //                'dt_create',
        //                'dt_create_timestamp',
        //
        //                //                'dt_start_timestamp',
        //
        //                'wh_destination',
        //                'wh_destination_element',
        //
        //                'wh_destination_name',
        //                'wh_destination_element_name',
        //
        //                'array_tk_amort.wh_tk_amort',
        //                'array_tk_amort.wh_tk_element',
        //                'array_tk_amort.ed_izmer',
        //                'array_tk_amort.ed_izmer_num',
        //
        //                'array_tk.wh_tk',
        //                'array_tk.wh_tk_element',
        //                'array_tk.ed_izmer',
        //                'array_tk.ed_izmer_num',
        //                //'array_tk.name_ed_izmer',
        //
        //            ])
        ->where(
            [
                'OR',
                [
                    'wh_destination_element' => (int)$sklad_element],
                [
                    'wh_destination_element' => (string)$sklad_element],
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
    ////////////


    /**
     * Подпроцедура получения ОСТАТКОВ
     * -
     * она вызывается из любых задач
     *
     * @param $sklad_id
     */
    //    public static  function actionPast_inventory_id($sklad_id )



    ////////////
    ////////////


    /**
     * КРАЙНЯЯ ИНВЕНТАРИЗАЦИЯ +приход -расход
     * -
     * временно : по номеру списку активных складов ИНВЕНТАРИЗАЦИИ
     *
     * @return Response
     */
    public function actionPast_inventory_summary()
    {
        //$list_keys=Sklad::ArraySklad_Uniq_wh_numbers();
        ///
        ///  Список всех активных складов
        $list_keys = Sklad::Array_inventory_ids();


        foreach ($list_keys as $item) {
            $this->actionPast_inventory_id($item);
        }

        //        $item = 86;
        //        $this->actionPast_inventory_id($item);
        //        return ;
        return $this->redirect([
            '/past_inventory']);

    }

    /**
     * 2
     * @param $sklad_id
     */
    function actionPast_inventory_id($sklad_id)
    {
        //
        // Получить самую молодую Столбовую ИНВЕНТАРИЗАЦИЮ
        // Одну!
        //
        /////////
        $array_items_inventory = Sklad_inventory::find()
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
                ])
            //->where( ['id'=>(int)$inventory_id] )
            ->where(
                [
                    'OR',
                    [
                        'wh_destination_element' => (int)$sklad_id],
                    [
                        'wh_destination_element' => (string)$sklad_id],
                ]
            )
            ->orderBy('dt_create_timestamp DESC')
            ->asArray()
            ->one();

        //            if($sklad_id==177){
        //                ddd($array_items_inventory);
        //            }


        if (!isset($array_items_inventory) || empty($array_items_inventory)) {
            return;
        }


        /// ИНВЕНТАРИЗАЦИИ
        //                if( !isset($array_items_inventory) || empty($array_items_inventory) ){
        //                    throw new NotFoundHttpException('Нет ИНВЕНТАРИЗАЦИИ по заданному складу');
        //                }

        $model = new Sklad_past_inventory();
        $max_value = Sklad_past_inventory::find()->max('id');
        $max_value++;
        //////////////

        $model->dt_start = $array_items_inventory['dt_create'];
        $model->dt_start_timestamp = $array_items_inventory['dt_create_timestamp'];

        $model->id = (int)$max_value;

        $model->wh_destination = $array_items_inventory['wh_destination'];
        $model->wh_destination_element = $array_items_inventory['wh_destination_element'];
        $model->wh_destination_name = Sklad_past_inventory::ArraySkladGroup_id_name($array_items_inventory['wh_destination']);
        $model->wh_destination_element_name = Sklad_past_inventory::ArraySklad_id_name($array_items_inventory['wh_destination_element']);

        //        ddd($model);
        ////// Полный Массив по Group+Id
        $arrayPrihodRashod = $this->ArrayPrihodRashod(
            $array_items_inventory['wh_destination_element'],
            $array_items_inventory['dt_create_timestamp']
        );


//        ddd($arrayPrihodRashod);
        //        if($sklad_id==86){
        //                        ddd( $array_items_inventory['dt_create_timestamp']);
        //                        ddd($array_items_inventory);
        //            ddd($arrayPrihodRashod);
        //        }
        // Неверный путь!!!!! ПОЛОВИНЧАТЫЙ
        // Надо еще новые приходы и расходы дописать к таблице СНИЗУ
        // Наша ЖАНЕЛЯ Убила гуппы в Амортизации!!!! Внимание!!! Теперь учет в одной группе!!!
        // ddd($array_items_inventory);

        $array_itog_amort = [
        ];
        $array_amort = $array_items_inventory['array_tk_amort'];
        //                ddd($array_amort);

        foreach ($array_amort as $item) {

            //                        if( (int)$item['intelligent']==1 ){
            //
            //                            //Если ВСТРЕТИЛСЯ ИНТЕЛЕГЕНТ (ИМЕЮЩИЙ ШТРИХКОД)
            //                            $xx=(int)$item['ed_izmer_num'];
            //                            do {
            //                                $array_itog_amort[] = [
            //                                    'wh_tk_amort'   => $item['wh_tk_amort'],
            //                                    'wh_tk_element' => $item['wh_tk_element'],
            //                                    'ed_izmer'      => $item['ed_izmer'],
            //                                    'bar_code'      => $item['bar_code'],
            //                                    'intelligent'   => 1,
            //                                    'ed_izmer_num'  => 1,
            //
            //                                    'prihod_num'  => $arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus'],
            //                                    'rashod_num' => $arrayPrihodRashod['amort'] [$item['wh_tk_amort']][(int)$item['wh_tk_element']]['minus'],
            //
            //                                    'itog' =>(
            //                                        $item['ed_izmer_num']
            //                                        +$arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus']
            //                                        -$arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['minus']
            //                                    ),
            //
            //                                ];
            //
            //                                $xx--;
            //                            }while($xx>0);
            //                        }
            //                        else{

            $array_itog_amort[] = [
                'wh_tk_amort' => $item['wh_tk_amort'],
                'wh_tk_element' => $item['wh_tk_element'],
                'ed_izmer' => $item['ed_izmer'],
                'bar_code' => (isset($item['bar_code']) ? $item['bar_code'] : ''),
                'intelligent' => 0,
                'ed_izmer_num' => $item['ed_izmer_num'],
                //'name_ed_izmer' => "шт",
                'prihod_num' => (isset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus']) ?
                    $arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus'] : 0),
                'rashod_num' => (isset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][(int)$item['wh_tk_element']]['minus']) ?
                    $arrayPrihodRashod['amort'][$item['wh_tk_amort']][(int)$item['wh_tk_element']]['minus'] : 0),
                'itog' => (
                    $item['ed_izmer_num']
                    + (isset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus']) ?
                        $arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus'] : 0)
                    - (isset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['minus']) ?
                        $arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['minus'] : 0)
                )
            ];

            //                        }

            unset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus']);
            unset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['minus']);

            if (empty($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']])) {
                unset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]);
            }
            if (empty($arrayPrihodRashod['amort'][$item['wh_tk_amort']])) {
                unset($arrayPrihodRashod['amort'][$item['wh_tk_amort']]);
            }
        }


        //// Вот теперь остались только(!) НОВЫЕ ПОЗИЦИИ в ПРИХОДЕ (и потом в расходе)
        //        $array_tk = $arrayPrihodRashod['amort'];
        //                    ddd($array_tk);
        //        if (isset($array_tk) && !empty($array_tk)) {
        //            foreach ($array_tk as $key_gr => $gr2) {
        //                foreach ($gr2 as $key_id => $id2) {
        //                    //ddd($id);
        //
        //                    $array_itog_amort[] = [
        //                        'wh_tk_amort' => $key_gr,
        //                        'wh_tk_element' => $key_id,
        ////                        'ed_izmer_num' => 0, /// ЕЩЕ НЕ БЫЛО В ИНвентризации
        ////                        'prihod_num' => $id2['plus'], 'rashod_num' => $id2['minus'],
        //
        //                    ];
        //
        //                }
        //            }
        //        }
        //
        //           ddd($array_itog_amort);

        $model->array_tk_amort = $array_itog_amort;


        $array_itog_amort = [
        ];
        foreach ($array_items_inventory['array_tk'] as $item) {

            $array_itog_amort[] = [
                'wh_tk' => $item['wh_tk'],
                'wh_tk_element' => $item['wh_tk_element'],
                'ed_izmer' => $item['ed_izmer'],
                'ed_izmer_num' => $item['ed_izmer_num'],
                //'name_ed_izmer' => $item['name_ed_izmer'],
                'prihod_num' => (isset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus']) ? $arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus'] : 0),
                'rashod_num' => (isset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus']) ?
                    $arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus'] : 0),
                'itog' => (
                    $item['ed_izmer_num']
                    + (isset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus']) ?
                        $arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus'] : 0)
                    - (isset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus']) ?
                        $arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus'] : 0)
                ),
            ];
            unset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus']);
            unset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus']);


            if (empty($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']])) {
                unset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]);
            }
            if (empty($arrayPrihodRashod['tk'][$item['wh_tk']])) {
                unset($arrayPrihodRashod['tk'][$item['wh_tk']]);
            }
        }

        //$model->array_tk  = $array_itog_amort;
        //unset($array_itog_amort);
        // ddd($arrayPrihodRashod['tk']);
        //// Вот теперь остались только(!) НОВЫЕ ПОЗИЦИИ в ПРИХОДЕ (и потом в расходе)

        $array_tk = $arrayPrihodRashod['tk'];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $item_gr => $gr) {
                foreach ($gr as $item_id => $id) {
                    $array_itog_amort[] = [
                        'wh_tk' => $item_gr,
                        'wh_tk_element' => $item_id,
                        'ed_izmer' => 1,
                        'ed_izmer_num' => 0,
                        'prihod_num' => (isset($arrayPrihodRashod['tk'][$item_gr][$item_id]['plus']) ?
                            $arrayPrihodRashod['tk'][$item_gr][$item_id]['plus'] : 0),
                        'rashod_num' => (isset($arrayPrihodRashod['tk'][$item_gr][$item_id]['minus']) ?
                            $arrayPrihodRashod['tk'][$item_gr][$item_id]['minus'] : 0),
                        'itog' => (
                            (isset($id['ed_izmer_num']) ? $id['ed_izmer_num'] : 0)
                            + (isset($arrayPrihodRashod['tk'][$item_gr][$item_id]['plus']) ? $arrayPrihodRashod['tk'][$item_gr][$item_id]['plus'] : 0)
                            - (isset($arrayPrihodRashod['tk'][$item_gr][$item_id]['minus']) ? $arrayPrihodRashod['tk'][$item_gr][$item_id]['minus'] : 0)
                        ),
                    ];
                }
            }
        }

        //        ddd($array_itog_amort);
        // ddd($model)

        $model->array_tk = $array_itog_amort;

        $model->save(true);

        //        /////////////...........
        //                if ($model->save(true)) {
        //                        return $this->redirect(['/past_inventory']);
        //                     }
        //                     else
        //                         ddd($model->errors);
        //                /////////////...........

    }

    /**
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
     * (ТОЛЬКО ШТУЧНЫЙ СКОПОМ. Без разделения на ШТРИХКОДЫ )
     * -
     * Возвращает массив с ПРРИХОДОМ и Расходом
     *
     * @param $sklad_id
     * @param $dt_create_timestamp
     *
     * @return mixed
     */
    public static function ArrayPrihodRashod($sklad_id, $dt_create_timestamp)
    {
        /////////
        $array_items = Sklad::find()
            ->select(
                [
                    'id',
                    'wh_home_number',
                    'dt_create_timestamp',
                    'sklad_vid_oper',
                    'dt_create',
                    'dt_create_timestamp',
                    'wh_debet_element',
                    'wh_destination_element',
                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                    'array_tk.wh_tk',
                    'array_tk.wh_tk_element',
                    'array_tk.ed_izmer',
                    'array_tk.ed_izmer_num',
                    'array_casual.wh_tk',
                    'array_casual.wh_tk_element',
                    'array_casual.ed_izmer',
                    'array_casual.ed_izmer_num',
                ])
            ->where(
                [
                    'AND',
                    [
                        '>',
                        'dt_create_timestamp',
                        $dt_create_timestamp,
                    ],
                    [
                        '=',
                        'wh_home_number',
                        (int)$sklad_id
                    ],
                ]
            )
            ->asArray()
            ->all();

//        ddd($array_items);
        //if($sklad_id==86) {
        //        if($sklad_id==177) {
        //            ddd($array_items);
        //            ddd($sklad_id);
        //        }
        //////////////
        //$array_itog_amort1=[];

        foreach ($array_items as $num_id) {    // $num_id - Это одна накладная целиком
            // ARRAY
            if (isset($num_id['array_tk_amort']) && !empty($num_id['array_tk_amort'])) {
                $array_bufer_amort = $num_id['array_tk_amort'];

                $x_plus = 0;
                $x_minus = 0;

                foreach ($array_bufer_amort as $item_pos) {

                    if ((int)$num_id['sklad_vid_oper'] == 2) {
                        $x_plus++;
                        $array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['plus'] = (isset($array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['plus']) ?
                                $array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['plus'] : 0)
                            + $item_pos['ed_izmer_num'];
                    }
                    if ((int)$num_id['sklad_vid_oper'] == 3) {
                        $x_minus++;
                        $array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['minus'] = (isset($array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['minus']) ?
                                $array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['minus'] : 0)
                            + $item_pos['ed_izmer_num'];
                    }
                }
            }
            // ddd( $array_itog_amort1 );
            // ARRAY array_tk
            if (isset($num_id['array_tk']) && !empty($num_id['array_tk'])) {
                $array_bufer_amort = $num_id['array_tk'];

                $x_plus = 0;
                $x_minus = 0;

                foreach ($array_bufer_amort as $item_pos) {

                    if ((int)$num_id['sklad_vid_oper'] == 2) {
                        $x_plus++;

                        //print_r( $item_pos);

                        $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] = (isset($array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus']) ?
                                $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] : 0)
                            + $item_pos['ed_izmer_num'];
                    }
                    if ((int)$num_id['sklad_vid_oper'] == 3) {
                        $x_minus++;
                        $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] = (isset($array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus']) ?
                                $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] : 0)
                            + $item_pos['ed_izmer_num'];
                    }
                }
            }
            //  ddd( $array_itog_array_tk );
            //  print_r( $array_itog_array_tk);
            // ARRAY array_casual
            if (isset($num_id['array_casual']) && !empty($num_id['array_casual'])) {
                $array_bufer_amort = $num_id['array_casual']; ///!!!!!!!!!!!  array_casual

                $x_plus = 0;
                $x_minus = 0;

                foreach ($array_bufer_amort as $item_pos) {

                    if ((int)$num_id['sklad_vid_oper'] == 2) {
                        $x_plus++;
                        $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] = (isset($array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus']) ?
                                $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] : 0)
                            + $item_pos['ed_izmer_num'];
                    }
                    if ((int)$num_id['sklad_vid_oper'] == 3) {
                        $x_minus++;
                        $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] = (isset($array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus']) ?
                                $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] : 0)
                            + $item_pos['ed_izmer_num'];
                    }
                }
            }
        }

        $array_itog_amort['amort'] = (isset($array_itog_amort1) ? $array_itog_amort1 : '');
        $array_itog_amort['tk'] = (isset($array_itog_array_tk) ? $array_itog_array_tk : '');

        //$array_itog_amort['casual']=$array_itog_array_casual; /// Это - все в одном!!!
        //ddd( $array_itog_amort );

        return $array_itog_amort;

    }

    /**
     *
     * Количественный учет всех товаров
     * (БЕЗ учета по штрихкодам)
     *
     * @param $sklad
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionKolish($sklad)
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
            ]);

        $DataProvider_pihod_spisanie = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_spisanie,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        $DataProvider_pihod_rashod = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_rashod,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);


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
        ]);

    }

    /**
     * ВЕСЬ ПРИХОД
     * -
     * into AMORT
     *
     * @param $sklad_id
     *
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
                ])
            ->where([
                'wh_home_number' => (int)$sklad_id])
            //            ->asArray()
            ->all();

        //ddd($array_items);  // 891
        //////////////
        //  $array_bufer_amort=[];
        $array_itog_amort = [
        ];


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
     *
     * @return array
     */
    function Privod_toarray_2d_amort($array_amort)
    {
        $array_out = [
        ];

        $group_arr = ArrayHelper::map(
            Spr_globam::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->orderBy([
                    'id'])
                ->asArray()->all(), 'id', 'name');

        $element_arr = ArrayHelper::map(
            Spr_globam_element::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->orderBy([
                    'id'])
                ->asArray()->all(), 'id', 'name');

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
                ])
            ->where([
                'wh_home_number' => (int)$sklad_id])
            ->asArray()
            ->all();

        //        ddd($array_items);
        //////////////
        //  $array_bufer_amort=[];
        $array_itog_amort = [
        ];


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
     *
     * @return array
     */
    function Privod_toarray_2d($array_amort)
    {
        $array_out = [
        ];

        $group_arr = ArrayHelper::map(
            Spr_glob::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->orderBy([
                    'id'])
                ->asArray()->all(), 'id', 'name');

        $element_arr = ArrayHelper::map(
            Spr_glob_element::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->orderBy([
                    'id'])
                ->asArray()->all(), 'id', 'name');

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
                    'group_id' => (isset($group_arr[$key_group]) ? $group_arr[$key_group] : 0),
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
     *
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
                ])
            ->where([
                'wh_home_number' => (int)$sklad_id])
            ->asArray()
            ->all();

        //ddd($array_items);
        //////////////
        //  $array_bufer_amort=[];
        $array_itog_amort = [
        ];


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
     *
     * @return array
     */
    function ListItemsOnTheSklad($id_sklad)
    {
        $sklad_one_naklad = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                ])
            //->select(['wh_home_number','id','dt_create_timestamp','sklad_vid_oper'])
            ->where([
                'wh_home_number' => (int)$id_sklad])
            ->orderBy(
                [
                    'dt_create_timestamp',
                    'id',
                ])// Сортируем по времени создания + id
            ->asArray()
            ->all();

        //ddd($sklad_one_naklad);
        return ArrayHelper::map($sklad_one_naklad, 'id', 'sklad_vid_oper');

    }

    /**
     * Вывод списком. Все ПРИХОДЫ и Расходы
     * Only AMORT
     *
     */
    public function actionEnterGrElem_am()
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
            ]);


        return $this->render(
            'index_into',
            [
                'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
                'model_pihod_amprt' => $array_pihod_amprt,
                'id_sklad' => $sklad_id,
                'spr_full_name' => $spr_full_name,
            ]);

    }

    /**
     * OCR - reader txt
     * -
     *
     */
    public function actionOcr()
    {
        //        $ocr = new TesseractOCR("D:\Guidejet\8055.png");
        //        $content = $ocr->run();
        //        echo $content;
        //        echo ( new TesseractOCR('D:\Guidejet\8055.png' ))->run();
        //echo ( new TesseractOCR('assets/reports/8055.png' ))->version();
        //        echo ( new TesseractOCR('assets/reports/8055.png' ))->run();
        //        ->executable('C:\Program Files (x86)\Tesseract-OCR\tesseract.exe')
        //    require_once 'vendor/autoload.php';
        //    use thiagoalessio\TesseractOCR\TesseractOCR;
        //////////// Ура !!!!
        //echo (new TesseractOCR('D:\Guidejet\8055.png'))
        //echo (new TesseractOCR('D:\Guidejet\333.jpg'))
        //    echo (new TesseractOCR('D:\Guidejet\444.jpg'))


        $text = (new TesseractOCR('D:\Guidejet\555.jpg'))
            ->executable('C:\Program Files (x86)\Tesseract-OCR\tesseract.exe')
            ->whitelist(range(0, 9), ' _@', ' ')
            ->run();

        //->whitelist(range('a', 'z'), range(0, 9), '^-._@')
        //->whitelist(range('a', '9'))
        //->lang('eng', 'jpn', 'spa')

        ddd($text);

        return '';

    }


    /**
     * ПроСТО ВСЕ ПУСТЫЕ ГРУППЫ ИЛИ НАИМЕНОВАНИЯ
     * -
     * Only AMORT
     *
     */
    public function actionAll_empty()
    {
        $model = Sklad::find()
            ->where([
                'AND',
                // ['sklad_vid_oper' => '3'], //RASHOD
                // ['=', 'wh_home_number', 4419], /// это по АЛМА-АТЕ
                ['==', 'wh_home_number', 8], /// это по АЛМА-АТЕ
                //array_count_all
                ['==', 'array_count_all', 1], /// это по АЛМА-АТЕ

                 ['==',  'array_tk_amort', [null] ],  //array_tk_amort

                // ['==',  'array_tk_amort', ''],  //array_tk_amort
                // ['==',  'array_tk', ''],        //array_tk
                // ['==',  'array_casual', ''],    //array_casual
                //array_tk_amort
                // ['>=',  'dt_create_timestamp', 1609400000], //!!! 1609441260 1609400000
                // ['like',  'tx', 'Акт'],

                // ['>=', 'id', 39620] ///

            ])   ;

        //
        $x=0;
        foreach ($model->each() as $item) {
          $x++;

          if(  $item->array_tk_amort==[null] ){
            print_r( $item -> id) ;
            echo "  ";
            print_r( $item -> tx) ;
            echo "<br>";
          }


            // if (!isset($item->dt_create_timestamp) || empty($item->dt_create_timestamp)) {
              //  ddd($item);
            // }

            // $item->dt_create_timestamp = $item->dt_create_timestamp + 1;
            // $item->dt_create = date('d.m.Y H:i:s', $item->dt_create_timestamp);

            // dd($item->id);
            // echo "<br>";


            // if ($item->save(true)) {
            //   print_r( $item -> id) ;
            //   echo "<br>";
            // }
            // ddd($item);
        }

        dd($x);
        ddd("end111");


       // return $this->render(
       //     'index_into', [
       //     'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
       //     'model_pihod_amprt' => $array_pihod_amprt,
       //     'spr_full_name' => $spr_full_name,
       //     //            'id_sklad' => $sklad_id,
       // ]);
    }

    /**
     * ПроСТО ВСЕ ПУСТЫЕ ГРУППЫ ИЛИ НАИМЕНОВАНИЯ
     * -
     * Only AMORT
     *
     */
//    public function actionAll_empty()
//    {
//        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
//        $array_pihod_amprt = Sklad::Mongo_all_empty();
//
//        $DataProvider_pihod_amprt = new ArrayDataProvider(
//            [
//                'allModels' => $array_pihod_amprt,
//            ]);
//
//
//        //$spr_full_name = [];
//        $spr_full_name = Spr_globam_element::findFullArray(1); // заглушка
//
//        return $this->render(
//            'index_into', [
//            'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
//            'model_pihod_amprt' => $array_pihod_amprt,
//            'spr_full_name' => $spr_full_name,
//            //            'id_sklad' => $sklad_id,
//        ]);
//    }





/**
 * 1. Start Function. CALC FAST - TURNOFER
 *
 *  Пересчет ОБОРАЧИВАЕМОСТИ АСУОП НА РЕМОНТ
 *=
 */
public function actionWrite_zerro()
{


  $models = Sklad::find()
  ->select(
        [
            'id',
            'dt_create',
            'dt_create_timestamp',
            'sklad_vid_oper_name',
            'array_tk_amort',
            'tx',
            'array_count_all'
        ]
      )
  ->where(
        ['AND',
        // wh_home_number
        // ['>=',  'id', 86722], // 86722
        // ['==',  'wh_home_number', 4419], // 4419

        // ['==',  'wh_home_number', 4823], //4823
        // ['>=',  'array_count_all', 1],

        ['==',  'array_tk_amort', ''],  //array_tk_amort
        // ['==',  'array_tk', ''],        //array_tk
        // ['==',  'array_casual', ''],    //array_casual
        //array_tk_amort
        ['>=',  'dt_create_timestamp', 1609400000], //!!! 1609441260 1609400000
        ['like',  'tx', 'Акт'],
              // 86755
      ]
    )

    ->orderBy('id ASC') // ->orderBy(['tx ASC'],['id ASC']) // ->limit(40000)


    // ->asArray()

    //->all();

    ->count();

ddd(  $models);


 $str_x = '';

    //ОТЛОЖЕННАЯ загрузка частями
    foreach ($models->each() as $model) {

      $x1= ( empty( $model->array_tk_amort[0]) || empty($model->array_tk_amort) );
      $x2= ( empty( $model->array_tk[0]) || empty($model->array_tk) );
      $x3= ( empty( $model->array_casual[0]) || empty($model->array_casual) );


          if (  $x1 && $x2 && $x3 ) {
                if (  (int)$model->array_count_all >= 1 ){

                      //
                      if( $str_x == $model->tx ){
                          continue;
                      }
                      else{

                        dd('');
                        echo "top    -----".$model->id;
                        echo "  ".$model->dt_create;
                        echo "  ".$model->dt_create_timestamp;
                        echo "  ".$model->sklad_vid_oper;
                        echo "  ".$model->tx;
                      }


                    if( $str_x <> $model->tx ){
                            //Выборать все Накладные этого АКТА. И Удалить!
                            // 1654023660 - c 1 июня 2022
                          Sklad::Delete_by_akt_date($model->tx , 1); // 1654023600
                         $str_x = $model->tx;
                   }

                              // if ($model->update(true)) {
                              //   echo "   № ".$model->id;
                              // }
                }
          }

    }


    ddd(123);
    echo "ALL is OK.... Расчет окончен. Ок.";
    return 0;
}




    /**
     *
     * -
     * Only AMORT
     *
     */
    public function actionAll_am_6()
    {
        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        Sklad::Remont_am_6();
        return "OK";
    }


    /**
     *
     * -
     * Only AMORT
     *
     */
    public function actionAll_invenory_am_6()
    {
        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        Sklad_inventory_csController::Remont_am_6();
        return "OK";
    }


    /**
     * ПроСТО ВСЕ ПУСТЫЕ ГРУППЫ ИЛИ НАИМЕНОВАНИЯ
     * -
     * Only AMORT
     *
     */
    public function actionVid_oper_remont()
    {
        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        $array_all = Sklad::arrayVid_oper_all_numered();

        foreach ($array_all as $id) {
            $model = Sklad::find()
                ->where(['==', 'id', $id])
                ->one();
            $model->sklad_vid_oper = (string)$model->sklad_vid_oper;

            //ddd($model);
            if (!$model->save(true)) {
                ddd($model->errors);
            }
        }

        return $this->redirect('/');
    }

    /**
     * РЕМОНТИРУЕМ
     * ВСЕ ПУСТЫЕ ГРУППЫ
     * -
     * Only AMORT
     *
     */
    public function actionAll_empty_remont()
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
     *
     */
    public function actionEnterGrElem_am_barcode()
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
            ]);


        //ddd($DataProvider_pihod_amprt->getModels());

        return $this->render(
            'index_into_barcode', [
            'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
            'model_pihod_amprt' => $array_pihod_amprt,
            'id_sklad' => $sklad_id,
        ]);

    }

    /**
     * Вывод списком. Все ПРИХОДЫ и Расходы
     * Only SPISANIE
     *
     */
    public function actionEnterGrElem_sp()
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
            ]);


        return $this->render(
            'index_into', [
            'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
            'model_pihod_amprt' => $array_pihod_amprt,
            'spr_full_name' => $spr_full_name,
            //            'id_sklad' => $id_sklad,
        ]);

    }

    /**
     * Вывод списком. Все ПРИХОДЫ и Расходы
     * Only RASHODNIKI
     *
     */
    public function actionEnterGrElem_rash()
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
        // ddd($spr_full_name);


        $DataProvider_pihod_amprt = new ArrayDataProvider(
            [
                'allModels' => $array_pihod_amprt,
            ]);

        //        ddd($DataProvider_pihod_amprt);

        return $this->render(
            'index_into', [
            'DataProvider_pihod_amprt' => $DataProvider_pihod_amprt,
            'model_pihod_amprt' => $array_pihod_amprt,
            'spr_full_name' => $spr_full_name,
        ]);

    }

}
