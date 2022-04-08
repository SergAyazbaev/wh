<?php

namespace frontend\controllers;

use frontend\models\post_spr_glob_element;
use frontend\models\postsklad_inventory_cs;
use frontend\models\Sklad;
use frontend\models\Sklad_inventory;
use frontend\models\Sklad_inventory_cs;
use frontend\models\Sklad_past_inventory_cs;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\components\MyHelpers;
use frontend\models\Sprwhelement_change;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;
use yii\base\ExitException;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;


class Sklad_inventory_csController extends Controller
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
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [

                    'index' => ['GET'],
                    'create_new' => ['GET', 'POST'],
                    'create_new_park' => ['GET', 'POST', 'PUT'],
                    'update' => ['POST', 'GET', 'GET'],
                    'delete' => ['GET'],

                    //                   'index'  => ['GET'],
                    // *                 'view'   => ['GET'],
                    // *                 'create' => ['GET', 'POST'],
                    // *                 'update' => ['GET', 'PUT', 'POST'],
                    // *                 'delete' => ['POST', 'DELETE'],
                    //'update' => ['POST', 'GET', 'GET'],
                ],
            ],
        ];
    }


    /**
     * INDEX
     * =
     *
     * @return string
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;
        //ddd($para);

        ///
        /// PARA PRINT ALL
        //$para_print = Yii::$app->request->get('print');
        $para_sort = Yii::$app->request->get('sort'); //
        $para_select = Yii::$app->request->get('select2_array_cs_numbers');//
        $postsklad_inventory_cs = Yii::$app->request->get('postsklad_inventory_cs');//


        ///
        if (!isset($para_print) || (int)$para_print == 0) {
            #PARA PRINT FILTER
            Sklad::setPrint_param($postsklad_inventory_cs);
            #PARA SORT
            Sklad::setSort_param($para_sort);
            #PARA BETWEEN
            Sklad::setSelect_param($para_select);
        }

        ///
        $searchModel = new postsklad_inventory_cs();
        $dataProvider = $searchModel->search($para);

        //ddd($dataProvider->getModels());


        ///
        $dataProvider->setSort(
            [
                'attributes' => [
                    'dt_create_timestamp',
                    'wh_home_number',
                    'wh_destination',

                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC]
                    ],
                    'user_name',
                ],

                'defaultOrder' => ['id' => SORT_DESC]
            ]);


        //ddd($para);

        //* Получить Список-Дроп только Используемых номеров Складов
        if (isset($para['select2_array_destination']) && !empty($para['select2_array_destination'])) {
            $filter_for_element = Sklad_inventory_cs::ArrayUniq_wh_numbers_by_id($para['select2_array_destination']);
        } else {
            $filter_for_element = Sklad_inventory_cs::ArrayUniq_wh_numbers(-1);
            //* Даты Инвентаризаций = all
            $filter_for_date = Sklad_inventory_cs::ArrayUniq_dates(-1);
        }

        //GROUP
        $filter_for_group = Sklad_inventory_cs::ArrayUniq_destination();

        //* Даты Инвентаризаций
        if (isset($para['select2_array_cs_numbers']) && !empty($para['select2_array_cs_numbers'])) {
            $filter_for_date = Sklad_inventory_cs::ArrayUniq_dates($para['select2_array_cs_numbers']);
        } else {
            $filter_for_date = Sklad_inventory_cs::ArrayUniq_dates(0);
        }
        //ddd($filter_for_date);

        //Запомнить РЕФЕР
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);

        //
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

            'filter_for_group' => $filter_for_group,
            'filter_for_element' => $filter_for_element,

            'filter_for_date' => $filter_for_date,
        ]);
    }


    /**
     * Заливка от Назиры (бух, док отд)
     * Создаем новые накладные (Инвентаризации ЦС)
     * =
     * ПО ВСЕМУ АВТОПАРКУ
     * -
     */
    public function actionCreate_new_park()
    {
        $para_post = Yii::$app->request->post();
        //ddd($para_post);

        ///
        $model = new Sklad_inventory_cs();
        if (!is_object($model)) {
            throw new HttpException(411, 'Склад ИНВЕНТАРИЗАЦИИ ЦС не работает', 2);
        }
        ////////
        $model->id = Sklad_inventory_cs::setNext_max_id();
        $model->sklad_vid_oper = 1; // INVENTORY
        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
        $model->dt_create_timestamp = strtotime($model->dt_create);
        $model->dt_update = date('d.m.Y H:i:s', strtotime("now"));

        ///
        /// Заливка одним столбиком. Только Гос номера. Парк взят через параметры
        ///
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'new_bus_column') {

            /// ДРОБИЛКА (Parser)
            $array = explode("\r\n", $para_post['Sklad_inventory_cs']['add_text_to_inventory_am']);

            $id_ap = (int)$para_post['Sklad_inventory_cs']['wh_destination']; //  '52'

//            ddd(111);
//            ddd($array);

            // 0 => '620DK02'
            //    1 => '632DK02'

//            $old = 0;
            $new = 0;
            foreach ($array as $name) {
                //
                $name = trim($name);
                ///
                if ($name == '(пусто)' || empty($name)) {
                    continue;
                }

                // Если ТОЛЬКО ЧИФРЫ БЕЗ БУКВ - это НОМЕР БОРТА
                if (empty(preg_replace('/\d/i', '', $name))) {
                    $array_ids = Sprwhelement::findAll_ids_by_AP_and_BORT($id_ap, $name);
                    if (empty($array_ids)) {
                        /// НЕ НАЙДЕН АТОБУС
                        /// ПЫТАЕМСЯ СОЗДАТЬ ЕГО
                        $model_new_spr = new Sprwhelement();
                        $model_new_spr->id = Sprwhelement::setNext_max_id();
                        $model_new_spr->parent_id = (int)$id_ap;
                        $model_new_spr->name = $name;
                        $model_new_spr->nomer_borta = $name;
                        $model_new_spr->date_create = date('d.m.Y H:i:s', strtotime('now'));
                        $model_new_spr->create_user_id = Yii::$app->user->identity->id;
                        $model_new_spr->tx = 'Доб. с новой заливкой';
                        $model_new_spr->f_first_bort = (int)1;
                        $model_new_spr->final_destination = (int)1;
                        ///
                        if (!$model_new_spr->save(true)) {
                            ddd($model_new_spr->errors);
                        }
                        $new++;
                        ///
                        continue;
                    } else {
                        $err = [];
                        foreach ($array_ids as $item_pe) {
                            if (Sprwhelement::is_f_first_bort_PE($item_pe) == 1) {
                                //Учет по ГОС, а надо - БОРТ
                                $err[] = $item_pe;
                                //ddd($item_pe);
                            }

                        }
                        return $this->render('_form_create_park', [
                            'new_doc' => $model,
                            'alert_mess' => $name . ' Учет не по БОРТУ ' . implode(', ', $err),
                        ]);
                    }
                } // Если это - ГОС НОМЕР
                else {
//                    $array_ids = [];
                    $array_ids = Sprwhelement::findAll_ids_by_AP_and_GOS($id_ap, $name);
                    if (empty($array_ids)) {
                        /// НЕ НАЙДЕН АТОБУС
                        /// ПЫТАЕМСЯ СОЗДАТЬ ЕГО
                        $model_new_spr = new Sprwhelement();
                        $model_new_spr->id = Sprwhelement::setNext_max_id();
                        $model_new_spr->parent_id = (int)$id_ap;
                        $model_new_spr->name = $name;
                        $model_new_spr->date_create = date('d.m.Y H:i:s', strtotime('now'));
                        $model_new_spr->create_user_id = Yii::$app->user->identity->id;
                        $model_new_spr->tx = 'Доб. с новой заливкой';
                        $model_new_spr->f_first_bort = (int)0;
                        $model_new_spr->final_destination = (int)1;

                        ///
                        //if (preg_match("/^[A-Z]{1,4}[0-9]{3,4}[A-Z]{1,4}$/i", $name)) {
                        if (preg_match("/^[A-Z]{0,4}[0-9]{3,4}[A-Z]{1,4}$/", $name)) {
                            $model_new_spr->nomer_traktor = $name;
                        } else {
                            $model_new_spr->nomer_gos_registr = $name;
                        }

                        ///
                        ///
                        if (!$model_new_spr->save(true)) {
                            ddd($model_new_spr->errors);
                        }
                        $new++;
                        ///
                        continue;
                    } else {
                        $err = [];
                        foreach ($array_ids as $item_pe) {
                            //Борт(1) или ГОС(0)
                            if (Sprwhelement::is_f_first_bort_PE($item_pe) == 1) {
                                //Учет по БОРТУ, а надо - ГОС
                                $err[] = $item_pe;
                            }
                        }
                        if (isset($err) && !empty($err)) {
                            return $this->render('_form_create_park', [
                                'new_doc' => $model,
                                'alert_mess' => $name . ' Учет не по ГОС id=' . implode(', ', $err),
                            ]);
                        }
                    }

                    // ddd($name);
                }
                ///
            }


            ///
            return $this->render('_form_create_park', [
                'new_doc' => $model,
                'alert_mess' => 'Справочник. Новых позиций: ' . $new,
            ]);
        }

        //
        // T O D O: SKLAD INVENTORY add_new_bus()
        // Залить копипаст НОВЫЕ АВТОБУСЫ
        //
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_new_bus') {
            //$array = explode("\r\n", trim($para_post['Sklad_inventory']['add_text_to_inventory_am']));

            /// ДРОБИЛКА (Parser)
            $array = explode("\r\n", $para_post['Sklad_inventory_cs']['add_text_to_inventory_am']);

            //Создаем правильный массив
            //            0 => [
            //                0 => 'Каскеленский автопарк'
            //                1 => '018ABB05'
            //            ]
            //ddd($array);

            foreach ($array as $item) {

                $array_item = explode("\t", $item);

                if (!empty($array_item[0])) {
                    $AP_id = $array_item[0];
                }

                if (!empty($array_item[1])) {
                    $array_park_bus[] = [$AP_id, $array_item[1]];
                    //$arr_out[]= ''.$AP_id.' '.$array_item[1];
                }


            }

            //            ddd($array);
            //            ddd($array_park_bus);

            //  ПАКЕТНОЕ Добавление в справочник WH новых автобусов
            $how = SprwhelementController::New_buses_from_array($array_park_bus);

            //ddd($how);
            //'how_math' => 38
            //'find_math' => 178

            return $this->render('_form_create_park', [
                'new_doc' => $model,
                //                'sklad' => $sklad,
                'alert_mess' => 'Заливка. Новых позиций: ' . $how['how_math'] . ' ' . 'Старых позиций: ' . $how['find_math'] . ' ',

            ]);
        }

        //
        // копипаст УДАЛИТЬ СТОЛБЦОМ ПО ИД
        //
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'delete_bus_id') {

            /// ДРОБИЛКА (Parser)
            $array = explode("\r\n", $para_post['Sklad_inventory_cs']['add_array_to_delete']);

            //Создаем правильный массив
            foreach ($array as $item) {
                if (!empty($item)) {
                    $array_park_bus[] = (int)$item; //INT (!!!)
                }
            }


            //  ПАКЕТНОЕ УДАЛЕНИЕ из справочник WH по ИД
            $how = Sprwhelement::Delete_by_id($array_park_bus);


            return $this->render('_form_create_park', [
                'new_doc' => $model,
                //                'sklad' => $sklad,
                'alert_mess' => 'Удаление. Позиций: ' . $how['how_math'],
            ]);

        }

        /// !!!!!!!!!!!
        /// !!       !!
        /// !!!!!!!!!!!
        ///
        /// Заливка ПО КОПИПАСТ ПЕ ПАРК
        /// "add_button_park"
        ///
        //////////////
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button_park') {
            //
            if ($model->load(Yii::$app->request->post())) {
                // ddd(Yii::$app->request->post());
                // ddd($model->dt_create_timestamp);

                //ddd($model->wh_destination);


                /// На какую дату ищем. (DeadLine)
                $date_end = $model->dt_create_timestamp;

                //AP
                $id_AP = (int)$model->wh_destination;


                //$model->wh_home_number = (int)$model->wh_destination_element;
                $model->sklad_vid_oper = 1; // INVENTORY

                ///
                $model->user_id = (int)Yii::$app->user->identity->id;
                $model->user_name = Yii::$app->user->identity->username;

                $model->sklad_vid_oper = (integer)$model->sklad_vid_oper; // Приводим к числу
                if ($model['sklad_vid_oper'] == 2) {
                    $model['sklad_vid_oper_name'] = 'Приходная накладная';
                }

                if ($model['sklad_vid_oper'] == 3) {
                    $model['sklad_vid_oper_name'] = 'Расходная накладная';
                }

                //
                //  Получить справочники
                //
                $spr_elem = Spr_globam_element::name_plus_id();  // Названия
                $spr_elem_parent = Spr_globam_element::id_to_parent();
                $spr_elem_intelligent = Spr_globam_element::id_to_intelligent(); /// intelligent
                //	$spr_elem_am  = Spr_globam::name_am_parent(); // ddd($spr_elem_parent);


//                ddd($para_post);

                //////////////////////
                /// Parser основного куска таблицы
                //////////////////////
                $array = explode("\r\n", trim($para_post['Sklad_inventory_cs']['add_text_to_inventory_am1']));

                //ddd($array);

                global $xx_str;
                $sum_bus = 0;
                $sum_bus_save = 0;
                ///////////////////
                foreach ($array as $key => $item_all) {
                    // Парсим каждую строку в массив
                    $item_str = explode("\t", $item_all);
                    //ddd($item_str);

                    // $item_str
                    //    0 => '5001'
                    //    1 => 'Fastener for CVB24 (Крепление для CVB24)'
                    //    2 => '(пусто)'
                    //    3 => '-3' /// 00000000000000
                    //    4 => '-32 523  '

                    ///  Номер АВТОБУСА в копипасте
                    if (isset($item_str[0]) && !empty($item_str[0])) {
                        $sum_bus++;
                        $number_PE = $item_str[0];

                        // Если ТОЛЬКО ЦИФРЫ БЕЗ БУКВ - это НОМЕР БОРТА
                        if (empty(preg_replace('/\d/i', '', $number_PE))) {
                            // Если это НОМЕР БОРТ

                            // Есть!. Найдена замена.
                            if (Sprwhelement_change::recurcia_findElemBORT($id_AP, $number_PE, $date_end)) {
                                // Замена в этой переменной - $xx_str
                                if (!empty($xx_str)) {
                                    $number_PE = (string)$xx_str;
                                }
                            }

                        } else {
                            // Если это НОМЕР ГОС

                            //    $id_AP = 4242;
                            //    $number_PE = '221HD02'; // 053GXZ05
                            // $date = '01.10.2017 00:00:01'; //ДАТА ПЕРЕХОДА //1506794401
                            //$date_end = 1506794401 + 1000000;
                            //$date_end = 1506794401 + 100000;
                            //    $date_end = 1506794401 - 1;

                            // Есть!. Найдена замена.
                            if (Sprwhelement_change::recurcia_findElemGOS($id_AP, $number_PE, $date_end)) {
                                // Замена в этой переменной - $xx_str
                                if (!empty($xx_str)) {
                                    $number_PE = (string)$xx_str;
                                }
                            }
                        }

                        //ddd($number_PE);

                        if (isset($model->wh_destination) && (int)$model->wh_destination > 0) {
                            /// Получить Номер АВТОПАРКА из ПОЛЯ ФОРМЫ
                            $number_AP = (int)$model->wh_destination;


                        } else {
                            ///
                            return $this->render('_form_create_park', [
                                'new_doc' => $model,
                                'alert_mess' => 'Обязательно выбирайте Автопарк',
                            ]);
                        }
                    }

//                    ddd($number_AP); //52

                    //   ddd($model); //'620DK02'
                    //   ddd($number_PE); //'620DK02'

                    ///
                    /// Если нет АП
                    if (!isset($number_AP) || empty($number_AP)) {
//                        ddd($model);

                        return $this->render('_form_create_park', [
                            'new_doc' => $model,
                            'alert_mess' => 'Заливка.Нет СКЛАДА в Справочнике. Ошибка.PE_SHTUKA=' . $sum_bus,
                        ]);
                    }

                    ///
                    ///  Массив накладной для Каждого автопарка (АП)
                    ///  Если есть цифра в количестве  и она не равна НУЛЮ
                    if (!isset($item_str[3])) {
                        continue;
                    } else {
                        if (!empty($item_str[1])) {
                            $key_key = array_search(trim($item_str[1]), $spr_elem);
                        }
                    }

//                    ddd($key_key); //13
//                    ddd($spr_elem);
//                    ddd($item_str);13 => 'Fastener for CVB24 (Крепление для CVB24)'


                    // ddd($item_str);


                    //// MINUS -1
                    if ((int)$item_str[3] <= 0) {
                        $array_tk[$number_AP][$number_PE][] = [

                            'wh_tk_amort' => (isset($spr_elem_parent[$key]) ? $spr_elem_parent[$key] : 7),
                            //'wh_tk_amort' => $spr_elem_parent[$key],
                            'wh_tk_element' => $key_key,
                            // Номер ИД
                            'wh_tk_element_name' => $item_str[1],

                            'ed_izmer' => 1,
                            // Всегда -ШТУКИ
                            'ed_izmer_num' => ((int)$item_str[3] < 0 ? -(int)$item_str[3] : (int)$item_str[3]),
                            // MODUL INTEGER

                            'bar_code' => MyHelpers::barcode_normalise($item_str[2]),
                            'intelligent' => (int)(isset($spr_elem_intelligent[$key_key]) ? $spr_elem_intelligent[$key_key] : 0),
                        ];
                    }

                    //
                    if (!isset($array_tk[$number_AP][$number_PE])) {
                        $array_tk[$number_AP][$number_PE] = [];
                    }
                }
                //ddd($sum_bus);


                ///

                ///
                /// Пропишем ОФИЦИАЛЬНО ПРИНЯТОЕ РЕШЕНИЕ.
                /// Накладная создается даже ПОЛНОСТЬЮ ПУСТАЯ
                ///
                if (!isset($array_tk)) {
                    $array_tk[$number_AP][$number_PE] = [];
                }
                //ddd($array_tk);     //пусто

                /////////////////////////////
                $x = 1;

                $err = [];
                $err_not_exist_PE = [];
                $err_exist_pillow = [];
                ///
                if (isset($array_tk) && is_array($array_tk)) {

                    // Номер АВТОПАРК
                    foreach ($array_tk as $key_AP => $number_AP) {

                        // Номер АВТОБУСА-СКЛАДА
                        foreach ($number_AP as $key => $item_all) {

                            $model_new = new Sklad_inventory_cs();     // Новая накладная ИНВЕНТАРИЗАЦИИ

                            ////////
                            $model_new->sklad_vid_oper = 1;                 // INVENTORY

                            $dt_sss = date('d.m.Y 00:00:01', strtotime($model->dt_create));
                            $model_new->dt_create = date('d.m.Y H:i:s', strtotime($dt_sss));
                            $model_new->dt_create_timestamp = strtotime($model_new->dt_create);
                            $model_new->dt_update = $model_new->dt_update;
                            //$model_new->tx = 'копипаст '.$model_new->dt_update;

                            /// СКЛАД
                            /// Перевернутый массив (наим+ид)
                            $wh_inverse = Sprwhelement::findChi_as_Array($key_AP);

                            //
                            $model_new->wh_destination = (int)$key_AP;      // Номер АВТОПАРК
                            $model_new->wh_home_number = (int)$key_AP;      // Номер АВТОПАРК


                            //                        if(isset($key) && isset($wh_inverse[ $key ]) &&
                            //                            $key!='(пусто)' && $wh_inverse[ $key ]!='' && $wh_inverse[ $key ]!='(пусто)'&& !empty($wh_inverse[ $key ]) )
                            //                        {

                            if (!isset($wh_inverse[$key]) && !empty($key)) {
                                $err_not_exist_PE[] = $key;
                            } else {
                                if (!isset($wh_inverse[$key])) {
                                    echo 'Отсутствует один из ключей';
                                    ddd($array_tk);
                                    ddd($key);
                                }

                                $model_new->wh_destination_element = $wh_inverse[$key]; // Номер АВТОБУСА-СКЛАДА
                                $model_new->wh_home_number = $wh_inverse[$key];
                                $model_new->array_tk_amort = $array_tk[$key_AP][$key];
                                $model_new->sklad_vid_oper = (int)1;
                                $model_new->sklad_vid_oper_name = 'копипаст ' . date('d.m.Y', strtotime("now"));
                                $model_new->group_inventory = '' . $x++;
//                                $model_new->group_inventory_name = 'копипаст '.$model_new->dt_update;

                                //Есть в саправочние
                                if (isset($wh_inverse[$key])) {
                                    $sum_bus_save++;
                                    /// Прописать СТОЛБ в ОСТАТКИ. Возвращает возможные ошибки
                                    //$err_exist_pillow[$sum_bus_save] = self::actionInventory_in_cs($model_new);
                                    $err_exist_pillow[$key] = self::actionInventory_in_cs($model_new);

                                } //НЕТ в саправочние
                                else {
                                    $err_not_exist_PE[] = $model_new->wh_home_number; // Госномер автобуса
                                }
                            }
                        }
                    }

                }

                if (!empty($err) || !empty($err_not_exist_PE) || !empty($err_exist_pillow)) {
//                    ddd($err_exist_pillow);
//                    ddd($err_not_exist_PE);
//                    ddd($err);
                    ////
                    return $this->render('_form_create_park', [
                        'new_doc' => $model,
                        'sum_bus' => $sum_bus,
                        'sum_bus_save' => $sum_bus_save,
                        'err' => $err,
                        'err_not_exist_PE' => $err_not_exist_PE,
                        'err_exist_pillow' => $err_exist_pillow,
                        'alert_mess' => 'Заливка всех ЦС.По разным причинам не сохранено ( Возможно дубликат. Отсутствие ПЕ в справочнике для данного АП)',
                    ]);
                }


                ///
                return $this->render('_form_create_park', [
                    'new_doc' => $model,
                    'sum_bus' => $sum_bus,
                    'sum_bus_save' => $sum_bus_save,
                    //                'sklad' => $sklad,
                    'alert_mess' => 'Заливка всех ЦС. Сохранение. Ок.',
                ]);

            }
            ///
        }

        /// !!!!!!!!!!!!!!!
        /// !! ПАРК-ПАРК !!
        /// !!!!!!!!!!!!!!!
        ///
        /// Заливка ПО КОПИПАСТ ПАРК-ПАРК
        /// "add_button_park"
        ///
        //////////////
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button_park_park') {
            //
            if ($model->load(Yii::$app->request->post())) {

                //ddd($model);
                //        'id' => 23199
                //        'sklad_vid_oper' => 1
                //        'dt_create' => '26.11.2020 09:57:54'
                //        'dt_create_timestamp' => 1606363115
                //        'dt_update' => '26.11.2020 09:57:54'
                //        'wh_destination' => '12'

                $model->sklad_vid_oper = 1; // INVENTORY
                $model->user_id = (int)Yii::$app->user->identity->id;
                $model->user_name = Yii::$app->user->identity->username;


                //  Получить ИД Парка в парке по соответсвию НАЗВАНИЯ ПАРКА
                //* Поиск ИД ПАРК-ПАРК по ИД ПАРК
                $array_PE_all = Sprwhelement::id_park_by_PARK_NAME($model->wh_destination);


                /// Если нет АП
                if (is_array($array_PE_all) && (int)$array_PE_all['id'] == 0) {
                    //ddd($array_PE['id']);
                    return $this->render('_form_create_park', [
                        'new_doc' => $model,
                        'alert_mess' => 'Заливка ПАРК-ПАРК. Нет такого подразделения в парке. Id = ' . implode(', ', $array_PE_all),
                    ]);
                }

                //
                $number_PE = (int)$array_PE_all['id'];
                $number_AP = (int)$model->wh_destination;
                //ddd($array_PE_all); //
                // 'id' => 6757   //    48
                // 'name' => 'Таксопарк №1'

                //ddd($number_PE); // 6757
                //ddd($number_AP);   // 65

                //ddd($park_park_id); //3965


                //  Получить справочник AM
                $spr_elem = Spr_globam_element::name_plus_id();  // Названия
                $spr_elem_tx = Spr_globam_element::name_plus_id_tx();  // Названия
                //ddd($spr_elem_tx);

                $spr_elem_parent = Spr_globam_element::id_to_parent();
                $spr_elem_intelligent = Spr_globam_element::id_to_intelligent(); /// intelligent


                //////////////////////
                /// Parser основного куска таблицы
                //////////////////////
                $array = explode("\r\n", trim($para_post['Sklad_inventory_cs']['add_text_to_inventory_park_park']));
                //ddd($array);


                $sum_bus = 0;
                $sum_bus_save = 0;
//                $key = 7;
                ///////////////////
                foreach ($array as $item_all) {
                    // Парсим каждую строку в массив
                    $item_str = explode("\t", $item_all);
                    //ddd($item_str);

                    // $item_str
                    //    0 => '5001'
                    //    1 => 'Fastener for CVB24 (Крепление для CVB24)'
                    //    2 => '(пусто)'
                    //    3 => '-3' /// 00000000000000
                    //    4 => '-32 523  '


                    ///
                    ///  Массив накладной для Каждого автопарка (АП)
                    ///  Если есть цифра в количестве  и она не равна НУЛЮ
                    if (!isset($item_str[3])) {
                        continue;
                    } else {
                        if (!empty($item_str[1])) {
                            $key_key = array_search(trim($item_str[1]), $spr_elem);

                            //   if ($item_str[1]=='Терминал New 8210(в т.ч блок питания,SIM карта)') {

                            if (!isset($key_key) || $key_key < 0 || !$key_key) {
                                $key_key = array_search(trim($item_str[1]), $spr_elem_tx);
                            }

                        }
                    }
                    //
                    $key = Spr_globam_element::getParent_id($key_key);
                    //ddd($key);


                    //// MINUS -1
                    if ((int)$item_str[3] <= 0) {
                        $num = preg_replace('/[ ]/i', '', $item_str[3]);
                        //ddd($num);


                        $array_tk[$number_AP][$number_PE][] = [

                            'wh_tk_amort' => (isset($spr_elem_parent[$key]) ? $spr_elem_parent[$key] : 7),
                            //'wh_tk_amort' => $spr_elem_parent[$key],
                            'wh_tk_element' => $key_key,
                            // Номер ИД
                            'wh_tk_element_name' => $item_str[1],

                            'ed_izmer' => 1,
                            // Всегда -ШТУКИ
                            'ed_izmer_num' => ((int)$num < 0 ? -(int)$num : (int)$num),
                            // MODUL INTEGER

                            'bar_code' => MyHelpers::barcode_normalise($item_str[2]),
                            'intelligent' => (int)(isset($spr_elem_intelligent[$key_key]) ? $spr_elem_intelligent[$key_key] : 0),
                        ];
                    }

                    //
                    if (!isset($array_tk[$number_AP][$number_PE])) {
                        $array_tk[$number_AP][$number_PE] = [];
                    }
                }
                //ddd($sum_bus);


                ///

                ///
                /// Пропишем ОФИЦИАЛЬНО ПРИНЯТОЕ РЕШЕНИЕ.
                /// Накладная создается даже ПОЛНОСТЬЮ ПУСТАЯ
                ///
                if (!isset($array_tk)) {
                    $array_tk[$number_AP][$number_PE] = [];
                }
                //ddd($array_tk);     //пусто

                /////////////////////////////
                $x = 1;

                $err = [];
                $err_not_exist_PE = [];
                $err_exist_pillow = [];
                ///
                if (isset($array_tk) && is_array($array_tk)) {

                    // Номер АВТОПАРК
                    foreach ($array_tk as $key_AP => $number_AP) {

                        // Номер АВТОБУСА-СКЛАДА
                        foreach ($number_AP as $key => $item_all) {
                            //ddd($number_AP);

                            $model_new = new Sklad_inventory_cs();     // Новая накладная ИНВЕНТАРИЗАЦИИ

                            ////////
                            $model_new->sklad_vid_oper = 1;                 // INVENTORY

                            $dt_sss = date('d.m.Y 00:00:01', strtotime($model->dt_create));
                            $model_new->dt_create = date('d.m.Y H:i:s', strtotime($dt_sss));
                            $model_new->dt_create_timestamp = strtotime($model_new->dt_create);
                            $model_new->dt_update = $model_new->dt_update;
                            //$model_new->tx = 'копипаст '.$model_new->dt_update;

                            /// СКЛАД
                            /// Перевернутый массив (наим+ид)
//                            $wh_inverse = Sprwhelement::findChi_as_Array($key_AP);

                            //
                            $model_new->wh_destination = (int)$key_AP;      // Номер АВТОПАРК
                            $model_new->wh_home_number = (int)$key_AP;      // Номер АВТОПАРК


                            //                        if(isset($key) && isset($wh_inverse[ $key ]) &&
                            //                            $key!='(пусто)' && $wh_inverse[ $key ]!='' && $wh_inverse[ $key ]!='(пусто)'&& !empty($wh_inverse[ $key ]) )
                            //                        {


                            $model_new->wh_destination_element = $number_PE; // СКЛАД-СКЛАД
                            $model_new->wh_home_number = $number_PE;
                            $model_new->array_tk_amort = $array_tk[$key_AP][$key];
                            $model_new->sklad_vid_oper = (int)1;
                            $model_new->sklad_vid_oper_name = 'копипаст ' . date('d.m.Y', strtotime("now"));
                            $model_new->group_inventory = '' . $x++;
//                                $model_new->group_inventory_name = 'копипаст '.$model_new->dt_update;

                            //ddd($model_new);


                            /// Прописать СТОЛБ в ОСТАТКИ. Возвращает возможные ошибки
                            //$err_exist_pillow[$sum_bus_save] = self::actionInventory_in_cs($model_new);
                            $err_exist_pillow[$key] = self::actionInventory_in_cs($model_new);


                        }
                    }

                }


                if (!empty($err) || !empty($err_not_exist_PE) || !empty($err_exist_pillow)) {
//                    ddd($err_exist_pillow);
//                    ddd($err_not_exist_PE);
//                    ddd($err);
                    ////
                    return $this->render('_form_create_park', [
                        'new_doc' => $model,
                        'sum_bus' => $sum_bus,
                        'sum_bus_save' => $sum_bus_save,
                        'err' => $err,
                        'err_not_exist_PE' => $err_not_exist_PE,
                        'err_exist_pillow' => $err_exist_pillow,
                        'alert_mess' => 'Заливка всех ЦС.По разным причинам не сохранено ( Возможно дубликат. Отсутствие ПЕ в справочнике для данного АП)',
                    ]);
                }


                ///
                return $this->render('_form_create_park', [
                    'new_doc' => $model,
                    'sum_bus' => 1,
                    'sum_bus_save' => 1,
                    //                'sklad' => $sklad,
                    'alert_mess' => 'Заливка всех ЦС. Сохранение. Ок.',
                ]);

            }
            ///
        }

        //ddd($model);
        ///
        return $this->render('_form_create_park', [
            'new_doc' => $model,
        ]);
    }


    /**
     * Создаем новую  накладную  (Инвентаризация)
     * -
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate_new()
    {
        //        $sklad = Sklad::getSkladIdActive();
        //        if (!isset($sklad) || empty($sklad))
        //            throw new UnauthorizedHttpException('Sklad=0');


        $model = new Sklad_inventory_cs();

        if (!is_object($model)) {
            throw new NotFoundHttpException('Склад ИНВЕНТАРИЗАЦИИ-ЦС не работает');
        }

        ////////
        $model->id = (int)Sklad_inventory_cs::setNext_max_id();

        $model->sklad_vid_oper = 1; // INVENTORY

        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
        /// timestamp
        //$model->setDtCreateText("NOW");
        $model->dt_create_timestamp = strtotime($model->dt_create);


        //////////////
        if ($model->load(Yii::$app->request->post())) {
            //            ddd($model);

            $model->wh_home_number = (integer)$model->wh_destination_element;
            $model->sklad_vid_oper = 1; // INVENTORY


            $model->user_id = (int)Yii::$app->getUser()->identity->id;
            $model->user_name = Yii::$app->getUser()->identity->username;


            $model->sklad_vid_oper = (integer)$model->sklad_vid_oper; // Приводим к числу
            if ($model['sklad_vid_oper'] == 2) {
                $model['sklad_vid_oper_name'] = 'Приходная накладная';
            }

            if ($model['sklad_vid_oper'] == 3) {
                $model['sklad_vid_oper_name'] = 'Расходная накладная';
            }


            /// СКЛАД
            $xx2 = Sprwhelement::findFullArray($model->wh_destination_element);

            $model->wh_destination_name = $xx2['top']['name'];
            $model->wh_destination_element_name = $xx2['child']['name'];


            //ddd($model);


            if ($model->save(true)) {

                //if(SkladController::actionInventory_in_cs(++$max_value, $model)) {
                return $this->redirect(['/sklad_inventory_cs/' . $adres_to_return]);
                //                    }

            }

            //                else
            //                    ddd($model->errors);
        }


        return $this->render('_form_create', [
            'new_doc' => $model,
            'sklad' => $sklad,
            //            'alert_mess' => 'Сохранение. Попытка',

        ]);
    }

    /**
     * Редактирование Накладной
     * =
     * @param $id
     * @return string|Response
     * @throws ExitException
     * @throws UnauthorizedHttpException
     */
    public function actionUpdate($id)
    {
        if (!isset($id) || empty($id)) {
            throw new UnauthorizedHttpException('Не подключен ID');
        }

        $para_post = Yii::$app->request->post();

        ////////
        $model = Sklad_inventory_cs::findModel($id);  // it's =  _id

        //ddd($model);

        ////////////////////////////////////
        ////////////////////////////////////
        ///
        /// add_button_am
        ///  КНОПКА - ЗАЛИВКА КОПИПАСТ - АСУОП
        /// из МОДАЛЬНОГО ОКНА
        ///
        ///add_button
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button_am') {


            //  Получить справочник
            $spr_elem = Spr_globam_element::name_plus_id();
            $spr_elem_parent = Spr_globam_element::id_to_parent();
            //$spr_elem_edizm = Spr_globam_element::id_to_ed_izm(); ///ВСЕГДА-ШТУКИ!!!


            /////////
            $array = explode("\r\n", trim($para_post['Sklad_inventory']['add_text_to_inventory_am']));

            //ddd($para_post);
            //ddd($array);


            // Приводим к нормальному массиву
            foreach ($array as $item) {
                $array_sign[] = array_map('trim', explode("\t", $item));   /// "TAB"
            }

            foreach ($array_sign as $key => $item2) {
                $key_key = array_search($item2[0], $spr_elem);   // $item2[1];/// штуки

                if (isset($key_key)) {
                    //ddd($key_key);

                    $array_reason[] = [$spr_elem_parent[$key_key], $key_key, $item2[0], $item2[1]];

                    $array_tk[] = [
                        'wh_tk_amort' => $spr_elem_parent[$key_key],
                        'wh_tk_element' => $key_key,
                        'ed_izmer' => 1, // Всегда -ШТУКИ
                        'ed_izmer_num' => (isset($array_sign[$key][1]) ? $array_sign[$key][1] : 0),
                        'bar_code' => '',
                    ];
                }
            }

            //ddd($array_tk);

            $model->array_tk_amort = array_merge($model->array_tk_amort, $array_tk);
            //   ddd($array_reason);

        }


        //////////////////////////////////
        //////////////////////////////////
        ///
        ///  КНОПКА - ЗАЛИВКА КОПИПАСТ
        ///  - СПИСАНИЕ
        ///
        ///add_button
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button') {

            //  Получить справочник
            $spr_elem = Spr_glob_element::name_plus_id();
            $spr_elem_parent = Spr_glob_element::id_to_parent();
            $spr_elem_edizm = Spr_glob_element::id_to_ed_izm();


            /////////
            $array = explode("\r\n", trim($para_post['Sklad_inventory']['add_text_to_inventory']));


            // Приводим к нормальному массиву
            foreach ($array as $item) {
                $array_sign[] = array_map('trim', explode("\t", $item));   /// "TAB"
            }

            foreach ($array_sign as $key => $item2) {
                $key_key = array_search($item2[0], $spr_elem);   // $item2[1];/// штуки

                if (isset($key_key)) {
                    $array_reason[] = [$spr_elem_parent[$key_key], $key_key, $item2[0], $item2[1]];

                    $array_tk[] = [
                        'wh_tk' => $spr_elem_parent[$key_key],
                        'wh_tk_element' => $key_key,
                        'ed_izmer' => $spr_elem_edizm[$key_key],
                        'ed_izmer_num' => (isset($array_sign[$key][1]) ? floatval(str_replace(",", ".", $array_sign[$key][1])) : 0),
                        'bar_code' => '',
                    ];
                }
            }

            //ddd( floatval( str_replace(",", ".", $array_sign[$key][1] )  ) );

            $model->array_tk = array_merge($model->array_tk, $array_tk);
        }


        //// Подсчет количества строк в массивах
        /// for VIEW
        ///

        $erase_array[0] = count($model->array_tk_amort);

        //ddd($erase_array );


        ///
        ///  КНОПКА УДАЛЕНИЕ СТРОК в массивах
        ///
        /// $para_post['contact-button']=='erase_aray'
        ///
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'erase_button') {
            //ddd($para_post);

            if (is_array($model['array_tk_amort'])) {
                //////////array_tk_amort
                ///
                $start = (int)$para_post['Sklad_inventory']['erase_array'][0][0];
                $stop = (int)$para_post['Sklad_inventory']['erase_array'][0][1] - $start;

                $array = (array)$model['array_tk_amort'];
                array_splice($array, $start, $stop);
                $model['array_tk_amort'] = $array;

            }


            // ddd($model);

        }


        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        //$model['array_tk'] = $this->getTkNames($model['array_tk']);

        //ddd($model);


        $spr_things = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');


        ///
        /// //////// ПРЕД СОХРАНЕНИЕМ
        ///
        if (!isset($para_post['contact-button']) || empty($para_post['contact-button'])) {
            if ($model->load(Yii::$app->request->post())) {
                //  ddd($model);

                //$model->wh_home_number=(integer)$sklad;
                $model->wh_home_number = (integer)$model->wh_destination_element;
                $model->sklad_vid_oper = 1; // INVENTORY

                $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));

                $model->update_user_id = Yii::$app->request->getUserIP();
                $model->update_user_name = Yii::$app->user->identity->username;
                $model->update_user_id = Yii::$app->user->identity->id;
                //            $model->update_user_group_id= Yii::$app->user->identity->group_id ;


                ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
                // $model->array_tk_amort  = Sklad::setArraySort1( $model->array_tk_amort );
                ///  ТАБ 1
                $model->array_tk_amort = Sklad::setArrayClear($model->array_tk_amort);
                ///  ТАБ 2
                $model->array_tk = Sklad::setArraySort2($model->array_tk);


                ////  Приводим ключи в прядок! УСПОКАИВАЕМ ОЧЕРЕДЬ
                $model->array_tk_amort = Sklad::setArrayToNormal($model->array_tk_amort);
                $model->array_tk = Sklad::setArrayToNormal($model->array_tk);
                $model->array_casual = Sklad::setArrayToNormal($model->array_casual);

                ///////
                /// ПРИЕМНИК
                $xx2 = Sprwhelement::findFullArray($model->wh_destination_element);

                $model->wh_destination_name = $xx2['top']['name'];
                $model->wh_destination_element_name = $xx2['child']['name'];


                /// То самое преобразование ПОЛЯ Милисукунд
                //$model->setDtCreateText($model['dt_create']);
                $model->dt_create_timestamp = strtotime($model->dt_create);


                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                $model['array_tk'] = $this->getTkNames($model['array_tk']);


                //ddd($model);


                if ($model->save(true)) {
                    return $this->render('_form', [
                        'new_doc' => $model,
                        'spr_things' => $spr_things,
                        'alert_mess' => 'Сохранение.Успешно.',
                    ]);
                }
            }
        }


        return $this->render('_form', [
            'new_doc' => $model,
            'spr_things' => $spr_things,
            'alert_mess' => '',
        ]);

    }

    /**
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     *-
     *
     * @param $array_tk
     *
     * @return mixed
     */
    public function getTkNames_am($array_tk)
    {
        $spr_globam_model = ArrayHelper::map(Spr_globam::find()->orderBy('name')->all(), 'id', 'name');
        $spr_globam_element_model = ArrayHelper::map(Spr_globam_element::find()->orderBy('name')->all(), 'id', 'name');
        $spr_globam_element_model_tx = ArrayHelper::map(Spr_globam_element::find()
            ->where(['not in', 'tx', ''])
            ->orderBy('name')->all(), 'id', 'tx');


        $spr_globam_element_model_intelligent = ArrayHelper::map(Spr_globam_element::find()->orderBy('name')->all(), 'id', 'intelligent');
        //ddd($spr_globam_element_model_intelligent);

        $buff = [];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                $buff[$key]['name_wh_tk_amort'] = $spr_globam_model[$item['wh_tk_amort']];
                if (isset($spr_globam_element_model[$item['wh_tk_element']])) {
                    $buff[$key]['name_wh_tk_element'] = $spr_globam_element_model[$item['wh_tk_element']];
                }
                if (isset($spr_globam_element_model_tx[$item['wh_tk_element']])) {
                    $buff[$key]['name_wh_tk_element'] = $spr_globam_element_model[$item['wh_tk_element']];
                }
                //$buff[$key]['name_ed_izmer']=$spr_things_model[$item['ed_izmer']];

                $buff[$key]['name_ed_izmer'] = 'шт';
                $buff[$key]['ed_izmer'] = '1';


                if (isset($item['bar_code'])) {
                    $buff[$key]['bar_code'] = ($item['bar_code'] > 0 ? $item['bar_code'] : '');
                }
                if (isset($spr_globam_element_model_intelligent[$item['wh_tk_element']])) {
                    $buff[$key]['intelligent'] = ((int)$spr_globam_element_model_intelligent[$item['wh_tk_element']]);
                } else {
                    $buff[$key]['intelligent'] = 0;
                }

                $buff[$key]['wh_tk_amort'] = $item['wh_tk_amort'];
                $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                $buff[$key]['take_it'] = (isset($item['take_it']) ? $item['take_it'] : '');
                $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];

            }
        }

        return $buff;
    }

    /**
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     *-
     *
     * @param $array_tk
     *
     * @return mixed
     */
    public
    function getTkNames($array_tk)
    {
        $spr_glob_model = ArrayHelper::map(Spr_glob::find()->orderBy('name')->all(), 'id', 'name');
        $spr_glob_element_model = ArrayHelper::map(Spr_glob_element::find()->orderBy('name')->all(), 'id', 'name');
        $spr_things_model = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');


        $buff = [];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                $buff[$key]['name_tk'] = $spr_glob_model[$item['wh_tk']];
                $buff[$key]['name_tk_element'] = $spr_glob_element_model[$item['wh_tk_element']];
                $buff[$key]['name_ed_izmer'] = $spr_things_model[$item['ed_izmer']];


                $buff[$key]['wh_tk'] = $item['wh_tk'];
                $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                $buff[$key]['ed_izmer'] = $item['ed_izmer'];
                $buff[$key]['take_it'] = $item['take_it'];
                $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];

                //$buff[$key]['name']=$item['name'];

            }
        }

        //        ddd($array_tk);
        //        ddd($buff);

        return $buff;
    }

    /**
     * Просто КОПИЯ этой накладной с новым номером ПО НАЖАТИЮ КНОПКИ "Копия с новым номером"
     *
     * @param $id
     *
     * @return Response
     * @throws ExitException
     */
    public function actionCopycard_from_origin($id)
    {
        //        $session = Yii::$app->session;
        //        $sklad = $session->get('sklad_');

        $model = Sklad_inventory::findModelDouble($id);  /// this is  _id !!!!!
        //        dd($model);


        $max_value = Sklad_inventory::find()->max('id');
        $max_value++;

        $new_doc = new Sklad_inventory();

        ///Сливаем в новую накладную старую копию и дописываем новый номер
        //            unset($model->_id);
        //            $new_doc=$model;

        $new_doc->id = (integer)$max_value;
        $new_doc['sklad_vid_oper'] = $model['sklad_vid_oper'];
        $new_doc['wh_home_number'] = $model['wh_home_number'];
        $new_doc['wh_debet_top'] = $model['wh_debet_top'];
        $new_doc['wh_debet_name'] = $model['wh_debet_name'];
        $new_doc['wh_debet_element'] = $model['wh_debet_element'];
        $new_doc['wh_debet_element_name'] = $model['wh_debet_element_name'];
        $new_doc['wh_destination'] = $model['wh_destination'];
        $new_doc['wh_destination_name'] = $model['wh_destination_name'];
        $new_doc['wh_destination_element'] = $model['wh_destination_element'];
        $new_doc['wh_destination_element_name'] = $model['wh_destination_element_name'];

        $new_doc['sklad_vid_oper_name'] = $model['sklad_vid_oper_name'];
        $new_doc['tz_id'] = $model['tz_id'];
        $new_doc['tz_name'] = $model['tz_name'];
        $new_doc['tz_date'] = $model['tz_date'];
        $new_doc['dt_deadline'] = $model['dt_deadline'];


        $new_doc['array_tk_amort'] = $model['array_tk_amort'];
        $new_doc['array_tk'] = $model['array_tk'];
        $new_doc['array_casual'] = $model['array_casual'];
        $new_doc['array_bus'] = $model['array_bus'];


        //       dd($new_doc);


        $new_doc->save(true);

        return $this->redirect('index');
    }

    /**
     * Распечатка. Выходная Форма.
     * Накладная Резервный ФОНД (ПДФ)
     *-
     *
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_reserv_fond()
    {

        $para = Yii::$app->request->queryParams;

        $model = Sklad_inventory::findModelDouble($para['id']);


        ////////////////////
        ///// AMORT!!
        //        $model1 = ArrayHelper::map(Spr_globam::find()
        //            ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(Spr_globam_element::find()->orderBy('id')->all(), 'id', 'name');


        ///// NOT AMORT
        //        $model3 = ArrayHelper::map(Spr_glob::find()
        //            ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(Spr_glob_element::find()->orderBy('id')->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');

        ////////////////////

        ///// BAR-CODE
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        $bar_code_html = MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        ///// BAR-CODE


        //1
        //$html_css = $this->getView()->render('/sklad/html_reserv_fond/_form_css.php');
        $html_css = $this->getView()->render('/sklad_inventory/pdf_form/_form_css.php');

        //ddd($model);

        //2
        //$html = $this->getView()->render('/sklad/html_reserv_fond/_form', [

        $html = $this->getView()->render('/sklad_inventory/pdf_form/_form', [
            //            'bar_code_html' => $bar_code_html,
            'model' => $model,
            //            'model1' => $model1,
            'model2' => $model2,
            //            'model3' => $model3,
            'model4' => $model4,
            'model5' => $model5,
        ]);


        //  Тут можно подсмореть
        //  $html = ss($html);

        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';

        $mpdf->SetAuthor('Guidejet TI, 2019');
        $mpdf->SetHeader($bar_code_html);
        $mpdf->WriteHTML($html_css, 1);

        //        $foot_str= '{PAGENO}';

        $foot_str = '

           
        ';

        //$mpdf->SetFooter($foot_str );
        $mpdf->SetHTMLFooter($foot_str, 'O');


        ///////
        $mpdf->AddPage('', '', '', '', '', 10, 10, 25, 42, '', 25, '', '', '', '', '', '', '', '', '', '');

        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'DeMontage_' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;
    }

    /**
     * Распечатка. Выходная Форма.
     * ПРЕДВАРИТЕЛЬНЫЙ ПРОСМТР СТРАНИЦЫ НАКЛАДНОЙ
     * ПЕРЕД ВЫВОДОМ в PDF
     *
     *
     * @return string
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_pdf_green()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad_inventory::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');

        $model2 = ArrayHelper::map(Spr_globam_element::find()->orderBy('id')->all(), 'id', 'name');


        ///// NOT AMORT
        $model3 = ArrayHelper::map(Spr_glob::find()->all(), 'id', 'name');

        $model4 = ArrayHelper::map(Spr_glob_element::find()->orderBy('id')->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');

        ////////////////////

        ///// BAR-CODE
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        $bar_code_html = MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        ///// BAR-CODE

        //1
        $html_css = $this->getView()->render('/sklad/html_to_pdf/_form_css.php');

        //2
        $html = $this->getView()->render('/sklad/html_to_pdf/_form_green', [
            //            'bar_code_html' => $bar_code_html,
            'model' => $model,
            'model1' => $model1,
            'model2' => $model2,
            'model3' => $model3,
            'model4' => $model4,
            'model5' => $model5,
        ]);


        //        dd($model);

        // Тут можно подсмореть
        //         $html = ss($html);

        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';


        $mpdf->SetAuthor('Guidejet TI, 2019');
        $mpdf->SetHeader($bar_code_html);
        $mpdf->WriteHTML($html_css, 1);

        //        $foot_str= '{PAGENO}';

        $foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>           
        ';

        //$mpdf->SetFooter($foot_str );
        $mpdf->SetHTMLFooter($foot_str, 'O');


        ///////
        $mpdf->AddPage('', '', '', '', '', 10, 10, 25, 42, '', 25, '', '', '', '', '', '', '', '', '', '');

        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'Sk ' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;
    }

    /**
     * @param        $id
     * @param string $adres_to_return
     *
     * @return Response
     * @throws ExitException
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public function actionDelete($id, $adres_to_return = "")
    {
        if (!Sklad_inventory_cs::findModel($id)->delete()) {
            throw new NotFoundHttpException('Не смог удалить.');
        }

        return $this->redirect(['/sklad_inventory_cs/return_to_refer']);
    }

    /**
     * id = системный длинный Ид
     * -
     * @param $id
     * @return Sklad_inventory|null
     */
    protected function findModel($id)
    {
        return Sklad_inventory::findOne($id);
    }

    /**
     * Лист Используется в Основной таблице без Амортизации
     * Справочник элементов прямого списания
     *
     * @param $id
     *
     * @return string
     */
    public
    function actionList($id = 0)
    {
        return Html::dropDownList('name_id', 0, ArrayHelper::map(Spr_glob_element::find()->where(['parent_id' => (int)$id])->orderBy("name")->all(), 'id', 'name'), ['prompt' => 'Выбор ...']);
    }

    /**
     * ЛистАморт Используется в таблице Амортизации
     * Справочник списания по амортизации
     *
     * @param $id
     *
     * @return string
     */
    public
    function actionListamort($id = 0)
    {
        return Html::dropDownList('name_id_amort', 0,
            ArrayHelper::map(Spr_globam_element::find()
                ->where(['parent_id' => (integer)$id])
                ->orderBy("name")
                ->all(), 'id', 'name'),
            ['prompt' => 'Выбор ...']);
    }

    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
     *
     * @return string
     *
     * ТОЛЬКО НЕ!!!! НЕ АСУОП !!!
     */
    public function actionList_ed_izm($id = 0)
    {
        $model = post_spr_glob_element::find()->where(['id' => (integer)$id])->one();

        return $model['ed_izm'];
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function actionList_parent_id_amort($id = 0)
    {
        $model = Spr_globam_element::find()->where(['id' => (int)$id])->one();

        //dd($model['ed_izm']);
        return $model['parent_id'];
    }

    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
     *
     * @return string
     */
    public function actionList_parent_id($id = 0)
    {
        $model = Spr_glob_element::find()->where(['id' => (integer)$id])->one();

        //        dd($model['ed_izm']);
        return $model['parent_id'];
    }

    /**
     * ПРОПИСАТЬ накладную ИНВЕНТАРИЗАЦИИ в INVENTOTY_CS
     * =
     * @param $model
     * @return bool
     */
    public static function actionInventory_in_cs($model)
    {

        $model->id = Sklad_inventory_cs::setNext_max_id();
        $model->dt_create_timestamp = strtotime($model->dt_create);
        $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_INVENTORY;
        //        $model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_INVENTARIZACIA_STR;
        $model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_INVENTARIZACIA_ZALIVKA_STR;
        $model->empty_cs = 0; // не является Пустышкой (не нуждается в заполнении)

        ///////
        /// ИСТОЧНИК
        $xx2 = Sprwhelement::findFullArray($model->wh_home_number);
        $model->wh_debet_name = $xx2['top']['name'];
        $model->wh_debet_element_name = $xx2['child']['name'];
        $model->wh_destination_name = $xx2['top']['name'];
        $model->wh_destination_element_name = $xx2['child']['name'];
        ///
        $model->user_id = Yii::$app->user->identity->id;
        $model->user_name = Yii::$app->user->identity->username;

        // Подсчет количества строк и штук в накладной (в массиве)
        $array_res = self::Array_sum_res($model->array_tk_amort);
        //ddd($array_res);
        // $model->itogo_actions = $array_res['itogo_actions'];

        $model->count_str = $array_res['itogo_strings'];
        $model->itogo_things = $array_res['itogo_things'];
        $model->calc_minus = $array_res['itogo_things'] - $array_res['itogo_strings'];
        $model->calc_errors = -1;

        //            'count_str',   //Количество строк в накладной
        //            'itogo_things', //Количество штук в накладной
        //            'calc_minus',     //// Разница строк для поиска ошибочных накладных
        //            'calc_errors',    //// Сумма всех ошибок в накладной


        /// МилисукундЫ
        //$model->setDtCreateText("NOW");

        ///////
        if (!$model->save(true)) {
            $array_err = $model->errors;
            return $array_err;
            //            throw new UnauthorizedHttpException('Не сохранил $model->save(true)');
        }


        return true;
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
                //ddd($array);

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

    /************************************
     * Этап 1.
     * ПУСТЫШКИ.
     * =
     * Создаем Накладные ИНВЕНТАРИЗАЦИИ (ЦС) ПУСТЫШКИ
     * -
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate_empty_invent()
    {
        //
        //
        // В построении участвуют все представленные в движении  АйДи ЦелевыхСкладов
        //
        //
        $all_wh_ids = Sklad::ArraySklad_cs_all_id_only();

        //
        foreach ($all_wh_ids as $item_sklad) {

            $model = new Sklad_past_inventory_cs();
            $model->id = Sklad_past_inventory_cs::setNext_max_id();

            $model->sklad_vid_oper = (int)1;
            $model->sklad_vid_oper_name = "Авто.Инвентаризация";
            $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));


            /// Полные данные
            $array = Sprwhelement::findFullArray($item_sklad);
            $model->wh_destination = (int)$array['top']['id'];
            $model->wh_destination_element = (int)$array['child']['id'];
            $model->wh_destination_name = $array['top']['name'];
            $model->wh_destination_element_name = $array['child']['name'];
            $model->wh_home_number = (int)$array['child']['cs'];

            $model->empty_cs = (int)1; // ПУСТЫШКА
            //

            $model->save(true);

        }

        return $this->redirect(['/past_inventory_cs/']);
    }


    /*****************************
     * Этап 2. Заполняем Накладные ИНВЕНТАРИЗАЦИИ (ЦС) по итогам движения ТМЦ
     * =
     * Запонение пустышек Инвентаризациями и Движениями
     *
     * @return string|Response
     */
    public function actionCreate_from_finded()
    {
        //   Получить Список  ПУСТЫШЕК
        $all_ids = Sklad_past_inventory_cs::ArrayEmpty_inventory_cs();

//           Получить Список СКЛАДОВ - ДВИЖЕНИЙ
//        $next_cs = Sklad::ArrayUniqCS_AfterDate('');
//        ddd($next_cs);

        ///
        ///  Все ПУСТЫШКИ  - ЭТО Очень Много!
        ///
        foreach ($all_ids as $id_inventory_nakl) {

            // Эту же накладную-ПУСТЫШКУ открываем для редактирования
            // В нее и запишем
            $model_in = Sklad_past_inventory_cs::findBy_Id($id_inventory_nakl);
            $model_in->empty_cs = (int)0;

            if (empty($model_in)) {
                // Статус не имеющего остатков инвентаризации после движений
                $model_in->empty_cs = (int)11;

                if (!$model_in->save(true)) {
                    ddd($model_in->errors);
                }

                continue;
            }

            /// $id_cs_sklad == wh_destination_element
            /// 929  'Алматыэлектротранс ТОО'  '1151'
            ///
            $id_cs_sklad = (int)$model_in->wh_destination_element;
            $one_last_inventory_cs = Sklad_inventory_cs::ArrayOne_Last_inventory_cs($id_cs_sklad);
            if (empty($one_last_inventory_cs)) {
                // Статус не имеющего остатков инвентаризации после движений
                $model_in->empty_cs = (int)15;

                if (!$model_in->save(true)) {
                    ddd($model_in->errors);
                }

                continue;
            }


            /// ARRAY
            $array_in = (isset($cs_inventory_new['array_tk_amort']) ? $cs_inventory_new['array_tk_amort'] : []);

            /// Время начала
            if (isset($cs_inventory_new['dt_create_timestamp'])) {
                // Из крайней накладной
                $timestamp_to_start_period = $cs_inventory_new['dt_create_timestamp'];
            } else {
                // ИЛИ Самое дальнее
                $timestamp_to_start_period = strtotime('01.01.70');
            }


            ///........
            ///
            ///  Движение по складу ОТ РАССВЕТА до ЗАКАТА ($ArrayPrihodRashod)
            ///
            ///........
            $ArrayPrihodRashod = Stat_balansController::ArrayPrihodRashod_sc(
                (int)$id_cs_sklad,
                $timestamp_to_start_period
            );


            ////
            if (empty($ArrayPrihodRashod)) {
                // Статус не имеющего остатков инвентаризации после движений
                $model_in->empty_cs = (int)10;
            }


            ///
            ///  MINUSOVKA
            /// Массив минусовых Баркодов.
            /// Работает вместе с ОТ РАССВЕТА до ЗАКАТА ($ArrayPrihodRashod)
            ///
            if (isset($ArrayPrihodRashod['minus'])) {
                $array_minusov = Stat_balansController::ArrayMinusov_or_PlusovBar($ArrayPrihodRashod['minus']);
                //            $array_plusov = Stat_balansController::ArrayMinusov_or_PlusovBar($ArrayPrihodRashod['plus']);
            }


            ///
            ///  Кидаем товар в НАкладную Инвентаризации
            /// ЗА МИНУСОМ ДВИЖЕНИЙ из $array_minusov
            ///
            $array = [];
            foreach ($array_in as $id_spr => $model_item) {
                //
                // Совмещаем по нашему принципу
                // Две накладные Целиком,
                // но построчно
                // !

                if (isset($model_item['bar_code']) && !empty($model_item['bar_code'])) {
                    //MInusovka
                    if (isset($array_minusov[$model_item['bar_code']])) {
                        continue;
                    }
                }

                $array[] = $model_item;
            }


            unset($ids_naklad);
            ///
            ///  Теперь формируем накладную ИНВЕНТАРИЗАЦИИ ЦС
            ///
            $model_in->dt_create_timestamp = strtotime('now');
            $model_in->count_str = (int)count($array);
            $model_in->array_tk_amort = $array;
            $model_in->array_tk = [];
            $model_in->array_casual = [];
            /////////////////
//            ddd($ArrayPrihodRashod);
//            ddd($model_in);
            ////
            if (!$model_in->save(true)) {
                ddd($model_in->errors);
            }

        }


        return $this->redirect(['/stat_svod/index_svod_cs']);
    }

    /**
     *  РеМОНТ Всех ASUOP GROUP=6. Заменяем
     * =
     *
     */
    static function Remont_am_6()
    {

        $array_list = ArrayHelper::getColumn(Sklad_inventory_cs::find()
            ->where(['=', "array_tk_amort.wh_tk_amort", (int)6])
            ->all(), 'id');


        //
//        $array_list = [
//            25889,
//            38680,
//            30699,
//            34830
//        ];


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
     * @param $id_cs
     * @return bool
     * @throws \Exception
     */
    protected static function Save_model($id_cs)
    {
        ///
        $model = Sklad_inventory_cs::find()
            ->where(['id' => (int)$id_cs])
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
     * ВОЗВРАТ ПО РЕФЕРАЛУ
     */
    public function actionReturn_to_refer()
    {
        //Возврат по рефералу REFER
        return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id, Yii::$app->request->url));
    }


}
