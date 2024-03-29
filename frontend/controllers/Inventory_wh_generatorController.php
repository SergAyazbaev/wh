<?php

namespace frontend\controllers;

use frontend\models\Barcode_pool;
use frontend\models\post_past_inventory_cs;
use frontend\models\Sklad;
use frontend\models\Sklad_inventory_wh;
use frontend\models\Sklad_wh_invent;
use frontend\models\Spr_globam_element;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhelement_change;
use frontend\models\Sprwhtop;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;


class Inventory_wh_generatorController extends Controller
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
     * СТОЛБ - Pillars ДЛЯ ОДНОГО -WH- в ОДИН ДЕНЬ
     * -
     *
     */
    function actionPillars_one_wh()
    {
        ////300 seconds = 5 minutes
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        //ini_set('max_execution_time', '30'); //30 seconds

        //
        $model = new Sklad_wh_invent();

        /// Виксируем моментальную дату и время
        //$model->dt_create = date('d.m.Y H:i:s', strtotime('last day of -2 month 23:59:59 '));
        $model->dt_create = date('d.m.Y H:i:s', strtotime('last day of -1 month 23:59:59 '));
        $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));

        //
        $list_group = ArrayHelper::map(Sprwhtop::find()
            ->where(['<>', 'final_destination', (int)1])
            ->orderBy(['name'])
            ->all(), 'id', 'name');

        //
        $list_element = [];


        ///
        ///
        if ($model->load(Yii::$app->request->post()) &&
            Yii::$app->request->post('contact-button') == 'add_button_park') {

            //            $Sklad_inventory_wh = Yii::$app->request->post();
            //            ddd($Sklad_inventory_wh);

            //ddd(11111111111);
            //////

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

            $Sklad_inventory_wh = Yii::$app->request->post('Sklad_wh_invent');
            $date_timestamp = strtotime($Sklad_inventory_wh['dt_create']); // 1609437601
            $id_PE = (int)$model->wh_destination_element;

            //
            $arr_id_name = (Sprwhelement::findAll_Attrib_PE([(int)$model->wh_destination_element]));

            //
            if ($this->actionGenerate_WH_one($id_PE, $last_time, $arr_id_name) != 0) {
                $err[] = $item;
            };

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
     * ПРИХОД / РАСХОД.
     * =
     * @param $Pe_id
     * @param $last_time
     * @return int
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionGenerate_WH_one($Pe_id, $last_time)
    {
        //        ini_set('max_input_vars','100000');
        //        ini_set('memory_limit','64M');
        //        dd(memory_get_usage());

        ///
        ///  PILLARS
        ///
        $array_items_inventory = Sklad_wh_invent::find()
            ->where(
                [
                    'AND',
                    ['<', 'dt_create_timestamp', (int)$last_time], //time
                    ['OR',
                        ['wh_destination_element' => (int)$Pe_id],
                        ['wh_destination_element' => (string)$Pe_id]
                    ]
                ]
            )
            ->orderBy('dt_create_timestamp DESC')
            ->asArray()
            ->one();

        //
        $arr_inv = $array_items_inventory['array_tk_amort'];

//        ddd($Pe_id);
//        ddd($array_items_inventory);
        //  ddd($arr_inv);

        ///
        /// ТУТ С УЧЕТОМ СТОЛБОВЫХ ОСТАТКОВ
        if (!empty($arr_inv)) {
            foreach ($arr_inv as $item_amort) {

                // ЕСЛИ НОРМАЛЬНЫЙ БАРКОД
                if (isset($item_amort['bar_code']) && !empty($item_amort['bar_code'])) {

                    // Приводим массив к УДОБНОМУ КРАТКОМУ ВИДУ
                    $array_prihod_BarCodes_all[$item_amort['bar_code']][] =
                        [
                            't' => (int)$item_amort['t'],
                            'ts' => date('d.m.Y H:i:s', $item_amort['t']),
                            'id' => (int)$item_amort['id'],
                            'v' => (int)2,//ПРИХОД-ИВЕНТАРИЗАЦИЯ
                            'b' => $item_amort['bar_code'],
                            's' => (int)$item_amort['ed_izmer_num'],
                        ];
                }
                // ЕСЛИ НЕТ БАРКОДА
                if (!isset($item_amort['bar_code']) || empty($item_amort['bar_code'])) {
                    //ddd($item_amort);

                    // Приводим массив к УДОБНОМУ КРАТКОМУ ВИДУ
                    $array_prihod_BarCodes_all[$item_amort['bar_code']][] =
                        [
                            'gr' => (int)$item_amort['wh_tk_amort'],
                            'gr_i' => (int)$item_amort['wh_tk_element'],
                            ///  'wh_tk_amort' => 7
                            //    'wh_tk_element' => 13

                            't' => (int)$item_amort['t'],
                            'ts' => (!empty($item_amort['t']) ? date('d.m.Y H:i:s', $item_amort['t']) : ''),
                            'id' => (int)(!empty($item_amort['id']) ? $item_amort['id'] : 0),
                            'v' => (int)2,//ПРИХОД-ИВЕНТАРИЗАЦИЯ
                            'b' => $item_amort['bar_code'],
                            's' => (int)$item_amort['ed_izmer_num'],
                        ];
                }


            }
            unset($arr_inv);
        } else {
            $array_prihod_BarCodes_all = [];
        }


        //
        // ddd($array_prihod_BarCodes_all);
        //


        ///////////////
        $array_prihod_ID = [];
        //        $array_prihod_BarCodes = [];


        /**
         * Выбрать все накладные по дате и номеру склада
         * ///   ПРИХОД / РАСХОД.
         */
        foreach (Sklad::find()
                     ->select([
                         'id', 'wh_home_number', 'sklad_vid_oper', 'dt_create', 'dt_create_timestamp',
                         'wh_debet_element', 'wh_destination_element',
                         'array_tk_amort.wh_tk_amort', 'array_tk_amort.wh_tk_element',
                         'array_tk_amort.ed_izmer', 'array_tk_amort.ed_izmer_num',
                         //'array_tk_amort.intelligent', // есть не всегда
                         'array_tk_amort.bar_code',
                     ])
                     ->where(
                         ['AND',
                             ['==', 'wh_home_number', (int)$Pe_id],
                             ['<=', 'dt_create_timestamp', (int)$last_time],
                         ]
                     )
                     ->orderBy('dt_create_timestamp ASC')
                     ->each() as $model) {

            ///
            if (empty($model->array_tk_amort)) {
                continue;
            }
            //
            if ((int)$model->dt_create_timestamp <= 10) {
                $model->dt_create_timestamp = strtotime($model->dt_create);
            }


            ///
            ///
            $bbb = $model->array_tk_amort;

            // dd($bbb);

            if (isset($bbb) && is_array($bbb)) {
                //...
                foreach ($bbb as $key_bb => $item_amort) {
                    $buff = [];
                    if (isset($item_amort['bar_code']) && !empty($item_amort['bar_code'])) {

//                        dd($model->id);
//                        dd($item_amort['bar_code']);
//                        dd('-----');

                        if (isset($array_prihod_BarCodes_all[$item_amort['bar_code']])) {

                            // Найден дубликат?
                            $xxxxxxx = $this->Seek_Doublicate($array_prihod_BarCodes_all[$item_amort['bar_code']],
                                $model->dt_create_timestamp, $model->id, $item_amort['bar_code']);

                            if ($xxxxxxx == false) {

                                //Двойник ТУТ НЕ НУЖЕН
                                //bar_code
                                $array_prihod_BarCodes_all[$item_amort['bar_code']][] =
                                    [
                                        't' => (int)$model->dt_create_timestamp,
                                        'ts' => $model->dt_create,
                                        'id' => (int)$model->id,
                                        'v' => (int)$model->sklad_vid_oper,
                                        'b' => $item_amort['bar_code'],
                                        's' => (int)$item_amort['ed_izmer_num'],
                                    ];
                            }

                            //PUSH-PULL...
                            $ccc = $array_prihod_BarCodes_all[$item_amort['bar_code']];
                            $buff = [];
                            foreach ($ccc as $item_bc) {
                                //prihod
                                if ($item_bc['v'] == 2) {
                                    $buff[] = $item_bc;
                                }
                                //RASHOD
                                if ($item_bc['v'] == 3) {
                                    if (!empty($item_bc['b'])) {
                                        array_pop($buff);
                                        //dd($buff);
                                    }
                                }
                            }
                            //
                            $array_prihod_BarCodes_all[$item_amort['bar_code']] = $buff;

                        }


                    }


                    //
                    // ЕСЛИ НЕТ БАРКОДА
                    if (!isset($item_amort['bar_code']) || empty($item_amort['bar_code'])) {
                        /// throw new HttpException(411, 'Сообшите Сергею. Нашелся пример! ', 15);

                        //ddd($item_amort);
                        ///   'wh_tk_amort' => '8'
                        //    'wh_tk_element' => '20'
                        //    'ed_izmer' => '1'
                        //    'ed_izmer_num' => '5'
                        //    'bar_code' => ''

                        if (isset($array_prihod_BarCodes_all[''])) {
                            // Шерстим БЕЗШТРИХКОДОВЫЙ МАССИВ
                            $array_zerro = $array_prihod_BarCodes_all[''];

                            //ddd($array_zerro);

                            foreach ($array_zerro as $key => $item_z) {
                                //
                                $zerro_itog[$key] = $item_z;

                                if ((int)$item_amort['wh_tk_element'] == (int)$item_z['gr_i']) {
                                    //Приход
                                    if ((int)$model->sklad_vid_oper == 2) {
                                        $zerro_itog[$key]['s'] = $item_z['s'] + $item_amort['ed_izmer_num'];
                                    }
                                    //Расход
                                    if ((int)$model->sklad_vid_oper == 3) {
                                        //ddd(111);
                                        $zerro_itog[$key]['s'] = $item_z['s'] - $item_amort['ed_izmer_num'];
                                    }
                                }
                                ///
                                unset($bbb[$key_bb]);
                            }

//ddd($bbb);


                            // Дописываем В КОНЕЦ
                            if (!empty($bbb)) {
                                // Дописываем В КОНЕЦ
                                foreach ($bbb as $item_bbb) {
                                    if ((int)$model->sklad_vid_oper == 2) {
                                        $zerro_itog[] = [
                                            'gr' => (int)$item_bbb['wh_tk_amort'],
                                            'gr_i' => (int)$item_bbb['wh_tk_element'],
                                            't' => 0,
                                            'ts' => '',
                                            'id' => 0,
                                            'v' => (int)$model->sklad_vid_oper,
                                            'b' => $item_bbb['bar_code'], //''
                                            's' => (int)$item_bbb['ed_izmer_num'],
                                        ];
                                    }
                                    if ((int)$model->sklad_vid_oper == 3) {
                                        $zerro_itog[] = [
                                            'gr' => (int)$item_bbb['wh_tk_amort'],
                                            'gr_i' => (int)$item_bbb['wh_tk_element'],
                                            't' => 0,
                                            'ts' => '',
                                            'id' => 0,
                                            'v' => (int)$model->sklad_vid_oper,
                                            'b' => $item_bbb['bar_code'], //''
                                            's' => -(int)$item_bbb['ed_izmer_num'],
                                        ];
                                    }
                                }
                                // БЕЗШТРИХКОДОВЫЙ МАССИВ
                                $array_prihod_BarCodes_all[''] = $zerro_itog;
                            }


                        }

                    }

                }
            }
        }

//        ddd($array_prihod_BarCodes_all);

//        ddd($array_prihod_BarCodes_all);


        //        dd(memory_get_usage());
        //        dd(count($array_prihod_BarCodes_all));
        //        ddd($array_prihod_BarCodes_all); //  5935
        //        ddd('======');


        //
        $array_full = Sprwhelement::findFullArray($Pe_id);


        /**
         * Шапку надстроим
         */
        $model_invenotry = new Sklad_wh_invent();
        $model_invenotry->id = Sklad_wh_invent::setNext_max_id();
        $model_invenotry->dt_create_timestamp = $last_time;
        $model_invenotry->dt_create_day = strtotime(date('d.m.Y H:i:s', $last_time));
        $model_invenotry->dt_create = date('d.m.Y H:i:s', $last_time);
        $model_invenotry->sklad_vid_oper = (int)1;
        $model_invenotry->sklad_vid_oper_name = 'Закрытие по ' . date('d.m.Y', $last_time);
        $model_invenotry->wh_home_number = (int)$Pe_id;
        $model_invenotry->wh_destination = $array_full['top']['id'];
        $model_invenotry->wh_destination_element = (int)$Pe_id;
        $model_invenotry->wh_destination_name = $array_full['top']['name'];
        $model_invenotry->wh_destination_element_name = $array_full['child']['name'];
        //
        $model_invenotry->user_id = (int)Yii::$app->getUser()->identity->id;
        $model_invenotry->user_name = Yii::$app->getUser()->identity->username;

        $array_inventary = [];


        foreach ($array_prihod_BarCodes_all as $code => $item) {

            if (!empty($item)) {
                //ddd($item);

                foreach ($item as $item_save) {
                    if (!empty($item_save['b'])) {
                        $full = Barcode_pool::findFull_array($item_save['b']);
                        //
                        $array_inventary[] = [
                            "wh_tk_amort" => $full['spr_globam_element']['parent_id'],
                            "wh_tk_element" => $full['spr_globam_element']['id'],
                            "ed_izmer" => 1,
                            "ed_izmer_num" => $item_save['s'],
                            "bar_code" => $item_save['b'],
                            "intelligent" => $full['spr_globam_element']['intelligent'],

                            "name_wh_tk_amort" => 'АСУОП',
                            "name_wh_tk_element" => $full['spr_globam_element']['short_name'],
                            "name_ed_izmer" => 'шт.',

                            "t" => $item_save['t'],
                            "id" => $item_save['id'],
                        ];
                    } else {
                        $full = Spr_globam_element::findFullArray($item_save['gr_i']);
                        $array_inventary[] = [
                            "wh_tk_amort" => $full['top']['id'],
                            "wh_tk_element" => $full['child']['id'],
                            "ed_izmer" => 1,
                            "ed_izmer_num" => $item_save['s'],
                            "bar_code" => $item_save['b'],
                            "intelligent" => $full['child']['intelligent'],

                            "name_wh_tk_amort" => $full['top']['name'],
                            "name_wh_tk_element" => $full['child']['short_name'],
                            "name_ed_izmer" => 'шт.',

                            "t" => $item_save['t'],
                            "id" => $item_save['id'],
                        ];

                    }
                    //

                }
            }
        }
        unset($array_prihod_BarCodes_all);

        // ddd($array_inventary);

        //
        $model_invenotry->array_tk_amort = $array_inventary;
        //
        $model_invenotry->count_str = count($array_inventary);
        $model_invenotry->tx = 'Пересчет на дату';
        //
        $model_invenotry->id = (int)Sklad_wh_invent::setNext_max_id();

        ///
        if (!$model_invenotry->save(true)) {
            ddd($model_invenotry->errors);
        }


        return 0;
    }

    /**
     * СПЕЦИАЛЬНЫЙ ПОИСК ВРЕМЕНИ ИД-НАКЛАДНОЙ И ШТРИХОДА в массиве
     * =
     * @param $array_input
     * @param $last_time
     * @param $id
     * @param $barcode
     * @return bool
     */
    public function Seek_Doublicate($array_input, $last_time, $id, $barcode)
    {
        //ddd($item);
        ///  't' => 1611370804
        //    'ts' => '23.01.2021 09:00:04'
        //    'id' => 37338
        //    'v' => 2
        //    'b' => '28000006740'
        //    's' => 1

        ///
        foreach ($array_input as $item) {
            if ($item['t'] == $last_time && $item['id'] == $id && $item['b'] == $barcode) {
                return true;
            }
        }

        return false;
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
        $array_items_inventory = Sklad_wh_invent::find()
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
            if ($array_inventary[$key]['ed_izmer_num'] == 0) {
                unset($array_inventary[$key]);
                continue;
            }

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
        $model_invenotry = new Sklad_wh_invent();
        $model_invenotry->id = Sklad_wh_invent::setNext_max_id();
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
     * @param $last_time // STOP-TIME
     * @return mixed
     */
    public static function ArrayPrihodRashod_array($sklad_id, $last_time)
    {
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
                ['AND',
                    ['<=', 'dt_create_timestamp', (int)$last_time],
                    ['==', 'wh_home_number', (int)$sklad_id]
                ]
            )
            ->orderBy(
                'dt_create_timestamp ASC'
            )
            ->asArray()
            ->all();

        return $array_moves_sklad;

    }

    /**
     * Подсчет количества накладных
     *
     * @param $sklad_id
     * @param $date_start
     * @param $last_time
     * @return int
     */
    public static function ArrayPrihodRashod_count($sklad_id, $date_start, $last_time)
    {
        $models = Sklad::find()->where(
            [
                'AND',
                ['>=', 'dt_create_timestamp', (int)$date_start],
                ['<=', 'dt_create_timestamp', (int)$last_time],
                ['=', 'wh_home_number', (int)$sklad_id],
            ]
        );
        $res = [];
        $count = 0;
        foreach ($models->each() as $model) {
            //$res[(int)$model->sklad_vid_oper][]=$model->array_tk_amort;

            if (is_array($model->array_tk_amort)) {
                $count += count($model->array_tk_amort);
            }

        }

        return $count;
    }

    /**
     * Подсчет количества накладных
     *
     * @param $sklad_id
     * @param $date_start
     * @param $last_time
     * @param int $vid_oper
     * @return array
     */
    public static function ArrayPrihodRashod_ver4($sklad_id, $date_start, $last_time, $vid_oper = 2)
    {
        $models = Sklad::find()
            ->select(
                [
                    'id',
                    'dt_create_timestamp',
                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.bar_code',
                    'array_tk_amort.ed_izmer_num',
                ]
            )
            ->where(
                [
                    'AND',
                    ['>=', 'dt_create_timestamp', (int)$date_start],
                    ['<=', 'dt_create_timestamp', (int)$last_time],
                    ['=', 'wh_home_number', (int)$sklad_id],
                    ['==', 'sklad_vid_oper', (string)$vid_oper],
                ]
            );


        $res = [];
        $count = 0;
        foreach ($models->each() as $key => $model) {
            //ddd($model);

            $res[$key]['nn'] = $model->id;
            $res[$key]['t'] = $model->dt_create_timestamp;
            $res[$key] = $model->array_tk_amort;

        }

        return $res;
    }

    /**
     * Версия 3 WH
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
     * -
     * Возвращает массив с ПРРИХОДОМ и Расходом
     *
     * @param $sklad_id
     * @param $date_start // START-TIME
     * @param $last_time // STOP-TIME
     * @return mixed
     */
    public static function ArrayPrihodRashod_v3($sklad_id, $date_start, $last_time)
    {
//        dd(date('d.m.Y H:i:s', $date_start));
//        dd(date('d.m.Y H:i:s', $last_time));
//        ddd(1111);


        ///
        ///       'date' => 1612752645
        //        'date_2' => '08.02.2021 08:50:45'
//        dd(date('d.m.Y H:i:s', 1612744248)); ///1612744248

//        dd(date('d.m.Y H:i:s', 1612752645));
//        dd(date('d.m.Y H:i:s', 1612775264));

//        ddd(1111);

        ///       'date' => 1612775264
        //        'date_2' => '08.02.2021 15:07:44'

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
                    ['>=', 'dt_create_timestamp', (int)$date_start],
                    ['<=', 'dt_create_timestamp', (int)$last_time],
                    ['==', 'wh_home_number', (int)$sklad_id],

                    //                    ['==', 'array_tk_amort.bar_code', '19600003934'],
                ]
            )
            ->orderBy('dt_create_timestamp ASC')
            ->asArray();


        //        ddd($array_moves_sklad->all());
        //        ddd($array_moves_sklad->limit(10)->all());
        //        ddd($array_moves_sklad->where(['==','id', 37626])->all());


        ///
        /// Переносим движения из Объекта накладных в Массив
        ///
        $array_moves = [];
        //
        foreach ($array_moves_sklad->each() as $key_sklad => $item_sklad) {
            //
            if (isset($item_sklad['array_tk_amort']) && is_array($item_sklad['array_tk_amort'])
                && !empty($item_sklad['array_tk_amort'])) {

                //if ($item_sklad['id'] == 37540) {
                //ddd($item_sklad);
                //ddd($item_sklad['array_tk_amort']);
                //}

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

        // ddd($array_moves);

        return $array_moves;
    }


    /**
     * Версия 2 NEW
     *
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
     * -
     * Возвращает массив с ПРРИХОДОМ и Расходом
     *
     * @param $sklad_id
     * @param $date_start // START-TIME
     * @param $last_time // STOP-TIME
     * @return mixed
     */
    public static function ArrayPrihodRashod_v2($sklad_id, $date_start, $last_time)
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
                    ['>=', 'dt_create_timestamp', (int)$date_start],
                    ['<=', 'dt_create_timestamp', (int)$last_time],
// не включать!!!                    [ '!=', 'array_tk_amort.bar_code', null ],
// не включать!!!                    [ '!=', 'array_tk_amort.bar_code', '' ],
                    ['OR',
                        ['wh_cs_number' => (int)$sklad_id], //CS!!
                        ['=', 'wh_debet_element', (int)$sklad_id], //CS!!
                    ]
                ]
            )
            ->orderBy(
                'dt_create_timestamp ASC'
            )
            ->asArray();

//            ->all();


//        ddd($last_time);//1604167199
//        ddd($date_start);//1604167199
//        ddd($sklad_id);

        //ddd($array_moves_sklad);


        ///
        /// Переносим движения из Объекта накладных в Массив
        ///
        $array_moves = [];
        foreach ($array_moves_sklad->each() as $key_sklad => $item_sklad) {

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
     * @param $date_start // START-TIME
     * @param $last_time // STOP-TIME
     * @return mixed
     */
    public
    static function Query_PrihodRashod_v2($sklad_id, $date_start, $last_time)
    {
        //        ddd($date_start); //1588269340
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
                    ['>', 'dt_create_timestamp', $date_start],
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
    public function actionList_element_wh($id, $actualdate)
    {
        if (!isset($actualdate) || empty($actualdate)) {
            return "Запрос вернул пустой массив";
        }

        if (isset($actualdate) && !empty($actualdate)) {
            $date_timestamp = strtotime($actualdate); //1606759199
        }


        $model_spr = ArrayHelper::map(Sprwhelement::find()
            ->where(
                ['parent_id' => (int)$id]
            )
            ->orderBy('name')
            ->all(), 'id', 'name'
        );


        // MЕНЬШЕ-OLD, Больше-NEW.
        //Теперь они сливаются вместе и не конфликтуют
//        $array_ids_actual = Sprwhelement_change::array_Actual_Ids_and_GOS($date_timestamp);
//        $array_ids_defective = Sprwhelement_change::array_Defective_Ids($date_timestamp);

        //ddd($array_ids_defective);

        ///
        $model = Html::dropDownList('name_id', 0, $model_spr,
            ['prompt' => 'Выбор ...']
        );

        ///
        if (empty($model)) {
            return "Запрос вернул пустой массив";
        }

        return $model;

    }


}