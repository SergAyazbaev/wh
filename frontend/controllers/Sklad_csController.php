<?php

namespace frontend\controllers;

use frontend\models\Barcode_pool;
use frontend\models\Sklad;
use frontend\models\Sklad_cs_past_inventory;
use frontend\models\Sklad_inventory_cs;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * склад ЦС
 */
class Sklad_csController extends Controller
{
    public $sklad;


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
     * Принять ОСТАТКИ из любого склада ЦС
     * =
     * Форма Выбора АВтобуса в парке
     * @return string
     * @throws HttpException
     */
    public function actionCreate_from_cs()
    {

        //SKLAD My
        $model_sklad = new Sklad();
        $model_sklad->id = Sklad::getSkladIdActive();

        //        ddd($model_sklad);
        /// Получить список всех GROUP складов ЦС
        $list_group = Sprwhtop::get_ListFinalDestination();

        //ddd($list_group);
        /// Получить список всех GROUP складов ЦС
        $list_element = Sprwhelement::get_ListFinalDestination();

        // ddd($list_element);

        $model_top = new Sprwhtop();
        $model_element = new Sprwhelement();


        return $this->render(
            'cs/_form_find', [
                'model_sklad' => $model_sklad,
                'model_top' => $model_top,
                'model_element' => $model_element,
                'list_group' => $list_group,
                'list_element' => $list_element,
            ]
        );

    }


    /**
     * ДЕМОНТАЖ ЦС
     * =
     *
     * @return string
     * @throws HttpException
     */
    public function actionFrom_cs()
    {
        $para = Yii::$app->request->post();


        /////............
//        $para_get = Yii::$app->request->get();


        $max_value = Sklad::setNext_max_id();

        $model_new = new Sklad();
        $model_new->id = (int)$max_value;

        $model_new->dt_create = date('d.m.Y H:i:s', strtotime('now'));
        $model_new->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
        $model_new->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;


        //  ddd($model_new);
        ///
        ///  Если идет запись в Save()
        ///
//        if (isset($para_get)) {
//            //ddd($para_get);
//
//            if (isset($para_get['Sklad']['wh_destination_element']) && !empty($para_get['Sklad']['wh_destination_element'])) {
//                $sklad = $para_get['Sklad']['wh_destination_element'];
//            }
//
//            if (isset($para_get['Sklad']['wh_debet_element']) && !empty($para_get['Sklad']['wh_debet_element'])) {
//                $model_new->wh_cs_number = (int)$para_get['Sklad']['wh_debet_element'];
//            }
//        }


        //ddd($para);

//        if (isset($para['Sklad']['id']) && !empty($para['Sklad']['id'])) {
//            $sklad = $para['Sklad']['id'];
//        }


        $model_new->wh_home_number = Sklad::getSkladIdActive();
        //ddd($model_new);
        //CS sklad
        if (isset($para['Sprwhelement']['id']) && !empty($para['Sprwhelement']['id'])) {
            $model_new->wh_cs_number = (int)$para['Sprwhelement']['id'];
        }


        // ИСТОЧНИК
        if (isset($para['Sprwhtop']['id']) && !empty($para['Sprwhtop']['id'])) {
            $model_new->wh_debet_top = (int)$para['Sprwhtop']['id'];
        }
        if (isset($para['Sprwhelement']['id']) && !empty($para['Sprwhelement']['id'])) {
            $model_new->wh_debet_element = (int)$para['Sprwhelement']['id'];
        }
        //ddd($para);


        /// ПРИЕМНИК
        $full_sklad = Sprwhelement::findFullArray(Sklad::getSkladIdActive());


        if (isset($para['Sklad']['id']) && !empty($full_sklad['top']['id'])) {
            $model_new->wh_destination = (int)$full_sklad["top"]['id'];
        }
        if (isset($para['Sklad']['id']) && !empty($full_sklad['child']['id'])) {
            $model_new->wh_destination_element = (int)$full_sklad["child"]['id'];
        }


        //        if (isset($para_get['Sklad']['wh_debet_element']) && !empty($para_get['Sklad']['wh_debet_element']) ){
        //            $model_new->wh_cs_number = (int)$para_get['Sklad']['wh_debet_element'];
        //        }
        ///
        ///   ПОДСЧЕТ ПРИХОДОВ-РАСХОДОВ (!!!!!)
        ///
//        if (isset($para['Sprwhelement']['id']) && !empty($para['Sprwhelement']['id'])) {


        ///
        /// ИНВЕНТАРИЗАЦИЯ ЦС
        ///
        ///  ДЛЯ НАЧАЛА ПЕРИОДА
        ///  Возмем... ИНВЕНТАРИЗАЦИЮ ЦС... Последнюю
        /// Вариант1.
        ///
        // $model_inventory = Sklad_inventory_cs::findInventory_by_id($para['Sprwhelement']['id']);
        //
        // Вариант2. Который Сегодня не понравился
        //
        // $model_inventory = Sklad_cs_past_inventory::ArrayRead_LastInventory($para['Sprwhelement']['id']);
        // СТОЛБ
        // Вариант3. Беру остатки Инвентаризации-Столба ЦС
        //

        //        ddd($para);

        $array_items_inventory = Sklad_inventory_cs::ArrayRead_LastInventory((int)$para['Sprwhelement']['id']);

        //            ddd($array_items_inventory);
        //            ddd( $para );
        //            ddd( $array_items_inventory );

        if (!isset($array_items_inventory)) {           // Нет начальных остатков.

            $array_items_inventory = new Sklad_inventory_cs();
            $array_items_inventory->array_tk_amort = [];

            ///
            ///  ПРИХОД / РАСХОД
            //$arrayPrihodRashod = Stat_balans_cs_Controller::ArrayPrihodRashod_without_timestamp($para['Sprwhelement']['id']);
            $arrayPrihodRashod = Stat_balans_cs_Controller::ArrayPrihodRashod_v2( $para['Sprwhelement']['id'] );

        } else {

            ///
            ///  ПРИХОД / РАСХОД
            $arrayPrihodRashod = Stat_balans_cs_Controller::ArrayPrihodRashod_v2(
                (int)$para['Sprwhelement']['id'],
                $array_items_inventory['dt_create_timestamp']
            );
        }


//        ddd( $arrayPrihodRashod );
//        ddd($para);
//        ddd($array_items_inventory);


        ///
        ///  ЛОГИКА 1.
        ///
        ///  Проход- вычитание из массива Инвентаризации
        //$array_itog_amort = [];
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

        //  ddd($arrayPrihodRashod);
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

        //ddd($array_inventary);

        #######################
        //
        // Устанавливаем столбец - Итого, в позицию Штуки
        if (isset($array_inventary)) {
            foreach ($array_inventary as $item) {
                if ($item['itog'] <= 0) {
                    continue;
                }

                $arr_norm[] = [
                    'wh_tk_amort' => $item['wh_tk_amort'],
                    'wh_tk_element' => $item['wh_tk_element'],
                    'ed_izmer' => 1,
                    'bar_code' => $item['bar_code'],
                    'ed_izmer_num' => $item['itog'],
                    'dt_create_timestamp_plus' => (isset($item['dt_create_timestamp_plus']) ? $item['dt_create_timestamp_plus'] : ''),
                    'dt_create_timestamp_minus' => (isset($item['dt_create_timestamp_minus']) ? $item['dt_create_timestamp_minus'] : ''),
                ];
            }
        }

        ///
        $model_new->array_tk_amort = (isset($arr_norm) ? $arr_norm : []);


//        ddd($this->summa_itog($model_new->array_tk_amort));
//        ddd($model_new);


        return $this->render(
            'cs/_form_create', [
                'model_new' => $model_new,
                'sklad' => Sklad::getSkladIdActive(),
                'itogo' => $this->summa_itog($model_new->array_tk_amort),
                'alert_mess' => '',
                'alert_string' => '',
            ]
        );

    }


    /**
     * Выбор-поиск устройства по БАР-КОДУ
     * -
     * Создать НАКЛАДНУЮ ПО ОСТАТКАМ из ЦС
     * -
     * Сохранить накладную Демонтаж
     * =
     * 1. в ЦС - Расходная
     * 2. в Установщике - Приходная
     * 3. Без БУФЕРНАЯ ПЕРЕДАЧА НАКЛАДНОЙ
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFrom_cs_one_barcode()
    {
        $para = Yii::$app->request->get();
        $alert_str = "";

        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }


        //SKLAD My
        $sklad = Sklad::getSkladIdActive();
        //        ddd($sklad );

        $bar_code = '';
        if (isset($para['Barcode_pool']['find_name'])) {


            /////
            $bar_code = $para['Barcode_pool']['find_name'];


            // Очистка от букв. Оставляем только цифры
            //$bar_code = preg_replace('/[^\d*]/i', '', $bar_code);
            // Очистка от посторонних символов, кроме букв и цифр
            $bar_code = (string)preg_replace('/\W/i', '', $bar_code);

            //19600
            $bar_code = preg_replace('/019600/u', '19600', $bar_code);


            ///
            /// Для ДОК отдела. Поиск по Штрихкоду.
            //
            //  * Из него создается потом накладная Демонтаж/Монтаж
            ///
            /// 1
            ///
            $model_inventory_cs = Sklad_cs_past_inventory::Find_barcode_one($bar_code);

            if (isset($model_inventory_cs)) {
                $array_am = $model_inventory_cs->array_tk_amort;

                foreach ($array_am as $item_array_tk_amort) {
                    if ($item_array_tk_amort['bar_code'] == $bar_code) {
                        $array [] = $item_array_tk_amort;
                    }
                }

                $model_inventory_cs->array_tk_amort = $array;
            } else {
                ///
                /// Для ДОК отдела. Поиск по Штрихкоду.
                //
                //  * Из него создается потом накладная Демонтаж/Монтаж
                ///
                /// 2
                ///

                $model_inventory_cs = Sklad::Find_barcode_one($bar_code);
            }


            ///
            /// Если до сих пор не найден Штрихкод
            ///
            if (!isset($model_inventory_cs)) {
                $alert_str = "Штрихкод не найден.";
            }


            ///........
            ///
            ///  Движение по складу ОТ РАССВЕТА до ЗАКАТА ($ArrayPrihodRashod)
            ///
            ///........
            $ArrayPrihodRashod = Stat_balansController::ArrayPrihodRashod_sc(
                $model_inventory_cs['wh_destination_element'],
                $model_inventory_cs['dt_create_timestamp']
            );

            ///
            ///  MINUSOVKA
            /// Массив минусовых Баркодов.
            /// Работает вместе с ОТ РАССВЕТА до ЗАКАТА ($ArrayPrihodRashod)
            ///
            if (isset($ArrayPrihodRashod['minus'])) {
                $array_minusov = Stat_balansController::ArrayMinusov_or_PlusovBar($ArrayPrihodRashod['minus']);
            }
            //  $array_plusov = Stat_balansController::ArrayMinusov_or_PlusovBar($ArrayPrihodRashod['plus']);
            //$alert_str = '';

            if (isset($array_minusov) && !empty($array_minusov)) {
                $alert_str .= ' Эти номера уже сняты с автобуса: ' . implode(', ', $array_minusov);
            }

            //.....
            if (isset($model_inventory_cs)) {
                // '210100008602'
                //SKLAD My
                $sklad = Sklad::getSkladIdActive();


                $sklad_model = new Sklad();
                $sklad_model->id = Sklad::setNext_max_id();
                $sklad_model->wh_home_number = $sklad;
                $sklad_model->sklad = $sklad;


                // Вид Операции
                $sklad_model->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
                $sklad_model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

                /// ИСТОЧНИК
                $sklad_model->wh_debet_top = $model_inventory_cs->wh_destination;
                $sklad_model->wh_debet_element = $model_inventory_cs->wh_destination_element;

                //
                if (!isset($sklad)) {
                    ddd(123);
                }

                $xx1 = Sprwhelement::findFullArray($sklad);

                $sklad_model->wh_destination = $xx1['top']['id'];
                $sklad_model->wh_destination_element = $xx1['child']['id'];
                $sklad_model->wh_destination_name = $xx1['top']['name'];
                $sklad_model->wh_destination_element_name = $xx1['child']['name'];

                $sklad_model->array_tk_amort = $model_inventory_cs->array_tk_amort;
                ////////////////////

                $sklad_model->tx = "Акт № ";
                $sklad_model->wh_dalee = $sklad_model->wh_debet_top;
                $sklad_model->wh_dalee_element = $sklad_model->wh_debet_element;


//                ddd($sklad_model);
//                 ddd($sklad);
                // ТУТ вход в ТВИН-ФОРМУ
                return $this->render(
                    '_form_twin1', [
                        'model_new' => $sklad_model,
                        'sklad' => $sklad,
                        'alert_str' => '' . $alert_str,
                    ]
                );
            }
        }


        // Поле для формы
        $model_text = Barcode_pool::find()->one();

        // Список-массив. Поиск автопоиск
        $pool = Barcode_pool::Array_for_auttofinder();


        //        ddd($sklad);


        return $this->render(
            'stat_forms/stat_dvizh_barcode', [
                //            'provider' => $provider,
                //            'model' => $model_inventory_cs,

                'sklad' => $sklad,
                'bar_code' => $bar_code,
                'pool' => $pool,
                'model_text' => $model_text,
                'alert_str' => $alert_str,
            ]
        );

    }


    /**
     * Сохранить -TWIN-
     * =
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSave_twin()
    {

        $para_get = Yii::$app->request->queryParams;


        //
        //
        // Создаем Накладную Демонтаж (только ее одну)
        //
        //
        //1
        //

        if ($para_get['contact-button'] == 'create_new1' || $para_get['contact-button'] ==
            'create_new2') {

            $model_new = new Sklad();

            if ($model_new->load(Yii::$app->request->get())) {

                $model_new->id = Sklad::setNext_max_id();
                $model_new->wh_home_number = (int)$para_get['Sklad']['sklad'];
                $model_new->dt_create_timestamp = strtotime($model_new->dt_create);

                if (isset($model_new->wh_debet_element) && !empty($model_new->wh_debet_element)) {
                    ///////
                    /// ИСТОЧНИК
                    $xx1 = Sprwhelement::findFullArray($model_new->wh_debet_element);
                    $model_new->wh_debet_name = $xx1['top']['name'];
                    $model_new->wh_debet_element_name = $xx1['child']['name'];

                    ////  Приводим INTELLIGENT в прядок! Прописываем каждому элементу
                    $model_new->wh_cs_number = $xx1['child']['cs'];
                }


                if (isset($model_new->wh_destination_element) && !empty($model_new->wh_destination_element)) {
                    ///////
                    /// ИСТОЧНИК
                    $xx1 = Sprwhelement::findFullArray($model_new->wh_destination_element);
                    $model_new->wh_destination_name = $xx1['top']['name'];
                    $model_new->wh_destination_element_name = $xx1['child']['name'];
                }

                ///||||||||||||||||||||||||||||||||||
                /// Подсчет СТРОК Всего
                ///
                if (isset($model_new['array_tk_amort']) && !empty($model_new['array_tk_amort'])
                    && is_array($model_new['array_tk_amort'])) {
                    $model_new['array_count_all'] = count($model_new['array_tk_amort']);
                }


//                ddd($model_new);


                if ($model_new->save(true)) {

                    if ($para_get['contact-button'] != 'create_new2') {
                        return $this->redirect('/sklad/in');
                    }
                }
            }
        }


        //
        //
        // Создаем Сразу две Накладные Демонтаж и Монтаж (одновремено)
        //
        //
        //2
        //

        if ($para_get['contact-button'] == 'create_new2') {
            //ddd($para_get);


            $model_new2 = new Sklad();

            $model_new2->id = Sklad::setNext_max_id();
            $model_new2->wh_home_number = (int)$para_get['Sklad']['sklad'];
            $model_new2->dt_create_timestamp = strtotime($model_new2->dt_create) +
                1; //  Следующая секунда

            $model_new2->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;
            $model_new2->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;


            if (isset($model_new->wh_destination_element) && !empty($model_new->wh_destination_element)) {

                $model_new2->wh_debet_top = $model_new->wh_destination;
                $model_new2->wh_debet_element = $model_new->wh_destination_element;

                $model_new2->wh_debet_name = $model_new->wh_destination_name;
                $model_new2->wh_debet_element_name = $model_new->wh_destination_element_name;
            }


            if (isset($model_new->wh_debet_element) && !empty($model_new->wh_debet_element)) {

                $model_new2->wh_destination = $model_new->wh_debet_top;
                $model_new2->wh_destination_element = $model_new->wh_debet_element;

                $model_new2->wh_destination_name = $model_new->wh_debet_name;
                $model_new2->wh_destination_element_name = $model_new->wh_debet_element_name;

                ////  Приводим INTELLIGENT в прядок! Прописываем каждому элементу
                $model_new2->wh_cs_number = $model_new->wh_cs_number;
            }


            ///
            ///   Подсчет СТРОК Всего
            ///
            $array = $model_new->array_tk_amort;
            foreach ($array as $key => $item) {
                $arr[$key] = $item;
                $arr[$key]['bar_code'] = '';
            }
            $model_new2->array_tk_amort = $arr;

            $model_new2['array_count_all'] = $model_new['array_count_all'];


            $model_new2->tx = $model_new->tx;
            $model_new2->wh_dalee = $model_new->wh_debet_top;
            $model_new2->wh_dalee_element = $model_new->wh_debet_element;


            ///
            ///  Подгонка времени Демонтаж раньше чем Монтаж на одну минуту
            ///
            ///

            $model_new2->dt_create_timestamp = $model_new->dt_create_timestamp +
                2;
            $model_new2->dt_create = date("d.m.Y H:i:s");


//ddd($model_new2);
            ////////
            if ($model_new2->save(true)) {
                return $this->redirect('/sklad/in');
            } else {
                ddd($model_new2->errors);
            }

            //....
        }

        return $this->redirect('/sklad/in');

    }


    /**
     *  Создать НАКЛАДНУЮ ПО ОСТАТКАМ из ЦС. Сохранить Демонтаж
     * =
     * 1. в ЦС - Расходная
     * 2. в Установщике - Приходная
     *
     */
    public function actionCreate_new_from_cs()
    {
        $model_new = new Sklad();


        ////////////////
        // LOAD
        ////////////////
        if ($model_new->load(Yii::$app->request->get())) {

            $model_new->user_id = (int)Yii::$app->getUser()->identity->id;
            $model_new->user_name = Yii::$app->getUser()->identity->username;
            $model_new->dt_create_timestamp = (int)strtotime($model_new->dt_create);


            //.........................
            // $model_new === Вот Это НАДО СДЕЛАТЬ!
            //.........................
            // 1. Расходная на стороне ЦС
            // 2. Приходная на стороне Получателя
            //.........................
            ///
            ///     Приводим ключи в порядок!
            ///
            $model_new->array_tk_amort = Sklad::setArrayToNormal($model_new->array_tk_amort);
            $model_new->array_tk = Sklad::setArrayToNormal($model_new->array_tk);


            //CS sklad
            $model_new->wh_cs_number = (int)$model_new->wh_debet_element;
            //$model_new->wh_debet_element_cs= (int) 1 ;

            $sklad_element=Sklad::getSkladIdActive();
//            $sklad_full=Sprwhelement::findFullArray($sklad_element); // Guidejet TI.
//            ddd($sklad_full);

            $model_new->wh_debet_top = (int)$model_new->wh_debet_top;
            $model_new->wh_debet_element = (int)$model_new->wh_debet_element;
            $model_new->wh_destination = (int)$model_new->wh_destination;
            $model_new->wh_destination_element = (int)$model_new->wh_destination_element;

            $model_new->wh_dalee = (int)$model_new->wh_debet_top;
            $model_new->wh_dalee_element = (int)$model_new->wh_debet_element;


            ///////
            /// ИСТОЧНИК
            $xx1 = Sprwhelement::findFullArray($model_new->wh_debet_element);
            /// ПРИЕМНИК
            $xx2 = Sprwhelement::findFullArray($model_new->wh_destination_element);


            $model_new->wh_debet_name = $xx1['top']['name'];
            $model_new->wh_debet_element_name = $xx1['child']['name'];
            $model_new->wh_destination_name = $xx2['top']['name'];
            $model_new->wh_destination_element_name = $xx2['child']['name'];

            $model_new->id = (int)Sklad::setNext_max_id();

            $model_new->wh_home_number=$sklad_element;

            //ddd($model_new);

            if ($model_new->save(true)) {
                $this->redirect('/sklad/in');
            } else {
                ddd($model_new->errors);
            }
        }

        $this->redirect('/sklad/in');
    }


    //Подсчет общего количества товара
    function summa_itog($array)
    {
        $x = 0;
        foreach ($array as $item) {
            $x += $item['ed_izmer_num'];
        }
        return $x;

    }


    /**
     * Подсчет/Запись данных в массив
     * =
     * БЕЗ БАРКОДОВ!
     * -
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
        //ddd($array);

        $array_out = [];
        if (is_array($array)) {
            foreach ($array as $key => $item) {


                ///
                if (isset($item['wh_tk_amort']) && isset($item['wh_tk_element'])) {
                    if ($item['wh_tk_amort'] == $group_id && $item['wh_tk_element'] == $element_id) {

                        if ($minus_plus < 0) {

                            $array_out['rashod_num'] = $array[$key]['rashod_num'] + $sum_number;
                            $array_out['itog'] = $array[$key]['itog'] - $sum_number;

                        } else {

                            //if ($minus_plus > 0) {

                            $array_out['prihod_num'] = $array[$key]['prihod_num'] + $sum_number;
                            $array_out['itog'] = $array[$key]['itog'] + $sum_number;

                        }

//                        ddd($array_out);
//
//                        ddd($item);
//
//                        ddd($group_id); //7
//                        ddd($element_id); //10
//                        ddd($key);

                        break;
                    }
                }
            }
        }

        //ddd($array_out);

        return $array_out;
    }


}