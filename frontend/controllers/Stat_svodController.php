<?php

namespace frontend\controllers;

use frontend\models\Barcode_consignment;
use frontend\models\Barcode_pool;
use frontend\models\Consignment;
use frontend\models\postsklad;
use frontend\models\postsklad_inventory_cs;
use frontend\models\Sklad;
use frontend\models\Sklad_inventory_cs;
use frontend\models\Sklad_cs_past_inventory;

use frontend\models\Sklad_wh_invent;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use Yii;
use yii\base\ExitException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\mongodb\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
//use yii\web\HttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
//use yii\web\UnauthorizedHttpException;
use yii\helpers\Html;


class Stat_svodController extends Controller
{
    public $sklad;
    protected $repquery; //// Это красивое решение настроенных параметров ТАБЛИЦЫ, чтобы их не терять ни когда в печати или еще где-то


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

        if ((Yii::$app->getUser()->identity->group_id < 40 ||
            Yii::$app->getUser()->identity->group_id > 100)) {

            throw new NotFoundHttpException('Доступ только STATISTIC-группе');
        }

        return parent::beforeAction($event);
    }




    /**
     * actionIndex_chain
     * -
     * Цепочка движений ПО ИД НОМЕРУ АВТОБУСА
     * =
     *
     * @return string
     */
    public function actionIndex_chain_from_id()
    {
        $id = Yii::$app->request->get('id'); //4330
        //ddd($id);

        ///  * Версия 2 NEW
        //     *
        //     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
        //     * -
        //     * Возвращает массив с ПРРИХОДОМ и Расходом
        // SKLAD,  START-TIME ,  STOP-TIME
        $query = Inventory_cs_generatorController::Query_PrihodRashod_v2($id, 0, strtotime('now'));

        // ddd($query->all() );

        ///
        /// DATA Provider
        ///
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        //ddd($dataProvider->models);

        //        ddd($dataProvider);

        /////

        $dataProvider->setSort(
            [
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],

                    'dt_create',
                    'dt_create_timestamp',
                    'sklad_vid_oper',
                    'wh_home_number',
                    'tx',
                    "user_name",
                    "wh_dalee_element_name",

                    'sprwhelement_debet_element.name' => [
                        'asc' => ['wh_debet_element' => SORT_ASC],
                        'desc' => ['wh_debet_element' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'sprwhelement_destination_element.name' => [
                        'asc' => ['wh_destination_element' => SORT_ASC],
                        'desc' => ['wh_destination_element' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],


                ],

                'defaultOrder' => ['dt_create_timestamp' => SORT_ASC],

            ]);


        ///
//        $spr_wh_element=Sprwhelement::ArrayNamesAllIds();
        //ddd($spr_wh_element);

        return $this->render(
            'chain/index', [
            'dataProvider' => $dataProvider,
//            'spr_wh_element' => $spr_wh_element,
        ]);
    }

    /**
     * Сводная таблица. ПО БАЗОВОМУ СКЛАДУ (Приход/Расход)
     * -
     * @return string
     */
    public function actionIndex_svod()
    {
        $para = Yii::$app->request->get();
        $print = Yii::$app->request->get('print');
        $para_print = Yii::$app->request->get('print');
        $para_sort = Yii::$app->request->get('sort');

        $print_comand = Yii::$app->request->get();
        //ddd($print_comand);

        ////////////
        /// ПЕРЕДАЧА ДАННЫХ ФИЛЬТРА ДЛЯ ПЕЧАТИ ЧЕРЕЗ СЕССИЮ
        ///
        $session = Yii::$app->session;

        /// $para
        if (isset($print) && !empty($print)) {
            $para = $session['repquery'];
        } else {
            $session['repquery'] = $para; // Это то самый секрет!!!
        }
        ////////////

        ///
        if (!isset($para_print) || (int)$para_print == 0) {
            #PARA PRINT FILTER
            Sklad::setPrint_param($para);

            #PARA SORT
            Sklad::setSort_param($para_sort);
        }


//        ddd($para);

        //
        // Разный уровень доступа Для Админа и Остальных пользователей
        //
        if (
            Yii::$app->getUser()->identity->group_id >= 100 ||
            Yii::$app->getUser()->identity->group_id >= 61 ||
            Yii::$app->getUser()->identity->group_id >= 70 ||
            Yii::$app->getUser()->identity->group_id == 50
        ) {

            //            if (isset ($para['postsklad']['dt_start']) || isset ($para['postsklad']['dt_stop'])) {
            //            $filter_for = Sklad::ArraySklad_Uniq_time($para['postsklad']['dt_start'], $para['postsklad']['dt_stop']);
            //            } else {
            //                $filter_for = Sklad::ArraySklad_Uniq_time(date('d.m.Y 00:00:00', strtotime('now -3 month')), date('d.m.Y 23:59:59', strtotime('now')));
            //            }

            $filter_for = Sklad::ArraySklad_Uniq_wh_numbers_all();
        } else {
            /// Только номера складов ИЗ USERS
            $filter_for = Sklad::ArraySklad_Uniq_wh_numbers_plus_id_name();
        }


        $searchModel = new postsklad();

//        ddd($filter_for);
//        ddd($searchModel);



        ///
        ///  Работает  ОДНОДНЕВНАЯ ВЫБОРКА !!!!  через модель поиска
        ///  Место только ТУТ!
        ///
//        if (isset($para['postsklad']['dt_start']) && !empty($para['postsklad']['dt_start'])) {
//            $searchModel['dt_start'] = date('d.m.Y', strtotime($para['postsklad']['dt_start']));
//        }
//        if (isset($para['postsklad']['dt_stop']) && !empty($para['postsklad']['dt_stop'])) {
//            $searchModel['dt_stop'] = date('d.m.Y', strtotime($para['postsklad']['dt_stop']));
//        }

//        if (isset($para['postsklad']['dt_start']) && isset($para['postsklad']['dt_stop'])) {
//            if ($para['postsklad']['dt_start'] >= $para['postsklad']['dt_stop']) {
//                $searchModel['dt_stop'] = $para['postsklad']['dt_start'];
//            }
//        }


//        ddd($para);

        if (isset($para['print']) && $para['print'] == 1) {
            $para['postsklad'] = Sklad::getPrint_param();
            $para['sort'] = Sklad::getSort_param();
            //
            $dataProvider = $searchModel->search_svod_excel($para);

        } else {
            //
            $dataProvider = $searchModel->search_svod($para);
        }

//        ddd($dataProvider);
        /////

        $dataProvider->setSort(
            [
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'dt_start' => [
                        'asc' => ['dt_create_timestamp' => SORT_ASC],
                        'desc' => ['dt_create_timestamp' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'dt_stop' => [
                        'asc' => ['dt_create_timestamp' => SORT_ASC],
                        'desc' => ['dt_create_timestamp' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],

                    'sklad_vid_oper',
//                    'wh_home_number',

//                    'tz_id',
//                    'array_count_all',
//                    'wh_debet_name',
                    'wh_debet_element_name',
//                    'wh_destination_name',
                    'wh_destination_element_name',
                    'tx',
                ],

                'defaultOrder' => ['dt_start' => SORT_ASC],

            ]);


        //
        // To EXCEL
        //
        if ((isset($print_comand['print']) && $print_comand['print'] == 1) ||
            (isset($para['print']) && $para['print'] == 1)
        ) {

            //
            // Получаем из СЕССИИ параметры для печати
            //
            //            $para= $session['repquery'];


            $dataProvider->pagination = ['pageSize' => -1];


            $wh_top = Sprwhtop::ArrayNamesAllIds();
            $wh_element = Sprwhelement::ArrayNamesAllIds();

            //  * Массив Соответсвия ИД-склада == ИД-группы складов
            $wh_parent_from = Sprwhelement::ArrayParentId_FromID();


            $this->render(
                'print/print_excel', [
                'dataProvider' => $dataProvider,
                'dataModels' => $dataProvider->getModels(),
                'wh_top' => $wh_top,
                'wh_element' => $wh_element,
                'wh_parent_from' => $wh_parent_from,
            ]);
        }


        //
        // EXCEL ( РАСКРЫТО ПО НАКЛАДНЫМ)
        //
        if (isset($print_comand['print']) && $print_comand['print'] == 2) {
            $dataProvider->pagination = ['pageSize' => -1];
            //* Раскрытие Накладных внутри таблицы по позициям
            $data_array = Sklad::in_model_out_array($dataProvider->getModels());

            $this->render(
                'print/print_excel_pe', [
                'dataProvider' => $dataProvider,
                //'dataModels' => $dataProvider->getModels(),
                'dataModels' => $data_array,
            ]);
        }


        //
        // EXCEL ТОЛЬКО АСУОП  ( РАСКРЫТО ПО НАКЛАДНЫМ )
        //
        if (isset($print_comand['print']) && $print_comand['print'] == 3) {
            $dataProvider->pagination = ['pageSize' => -1];


            //* Раскрытие Накладных внутри таблицы по позициям
            $data_array = Sklad::in_model_out_array_amort($dataProvider->getModels());

            //  ddd($data_array);

            $this->render(
                'print/print_excel_pe', [
                'dataModels' => $data_array,
            ]);
        }


        //Запомнить РЕФЕР
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);


        return $this->render(
            'stat_forms/tab_at_name', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter_for' => $filter_for,
            'print_comand' => $print_comand,
        ]);
    }

    /**
     * ВОЗВРАТ ПО РЕФЕРАЛУ
     */
    public function actionReturn_to_refer()
    {
        //Возврат по рефералу REFER
        return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id, Yii::$app->request->url));
    }



    /**
     *  ЧТО ПОСТАВИЛИ ПЕРЕВОЗЧИКАМ
     * АСУОП
     *-
     *
     * @return string
     */
    public function actionIndex_svod_pe()
    {
        $para = Yii::$app->request->queryParams;
        $print_comand = Yii::$app->request->post();
        //        ddd($para);


        $searchModel = new postsklad();

        // To EXCEL (РАСКРЫТО ПО НАКЛАДНЫМ)
        if (isset($print_comand['print']) && $print_comand['print'] == 3) {

            //                    'postsklad' => [
            //                    'dt_start' => '01.05.2019 00:00:00'
            //                    'dt_stop' => '24.05.2019 00:00:00'
            //                    ]
            //                    'print' => '3'


            /////////////
            if (isset($print_comand['postsklad']['dt_start'])) {
                $para['dt_start'] = $searchModel->dt_start = $print_comand['postsklad']['dt_start'];
            } else {
                $para['dt_start'] = $searchModel->dt_start = date('d.m.Y 00:00:00', strtotime('now -1 month'));

            }

            if (isset($print_comand['postsklad']['dt_start'])) {
                $para['dt_stop'] = $searchModel->dt_stop = $print_comand['postsklad']['dt_stop'];
            } else {
                $para['dt_stop'] = $searchModel->dt_stop = date('d.m.Y 23:59:59', strtotime('now'));
            }

            /////////////

            //ddd($searchModel);
        }


        // ddd($para);
        $dataProvider = $searchModel->search_svod_pe($para);


        //
        // To EXCEL
        //
        if (isset($print_comand['print']) && $print_comand['print'] == 1) {
            $dataProvider->pagination = ['pageSize' => -1];

            $wh_top = Sprwhtop::ArrayNamesAllIds();
            $wh_element = Sprwhelement::ArrayNamesAllIds();
            //  * Массив Соответсвия ИД-склада == ИД-группы складов
            $wh_parent_from = Sprwhelement::ArrayParentId_FromID();

            //$wh_element=Sprwhelement::ArrayNamesWithIds( [] );


            //ddd($dataProvider->getModels());

            $this->render(
                'print/print_excel', [
                'dataProvider' => $dataProvider,
                'dataModels' => $dataProvider->getModels(),
                'wh_top' => $wh_top,
                'wh_element' => $wh_element,
                'wh_parent_from' => $wh_parent_from,
            ]);
        }


        // To EXCEL PE ( РАСКРЫТО ПО НАКЛАДНЫМ ) PE
        if (isset($print_comand['print']) && $print_comand['print'] == 2) {

            $dataProvider->pagination = ['pageSize' => -1];


            //* Раскрытие Накладных внутри таблицы по позициям
            $data_array = Sklad::in_model_out_array($dataProvider->getModels());

            //ddd($data_array);

            $this->render(
                'print/print_excel_pe', [
                'dataModels' => $data_array,
            ]);
        }


        return $this->render(
            'stat_forms/tab_at_name_pe', [
            'model' => $searchModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }


    /**
     *  Сводная таблица по всем ИНВЕНТАРИЗАЦИЯМ - АП ЦС
     * -
     *
     * @return string
     */
    public function actionIndex_svod_cs()
    {

        $para = Yii::$app->request->queryParams;
        $print_comand = Yii::$app->request->post();

        $searchModel = new postsklad_inventory_cs();

        //ddd(1111);

        //
        // To EXCEL (РАСКРЫТО ПО НАКЛАДНЫМ)
        //
        if (isset($print_comand['print']) && $print_comand['print'] == 3) {
            //                    'postsklad' => [
            //                    'dt_start' => '01.05.2019 00:00:00'
            //                    'dt_stop' => '24.05.2019 00:00:00'
            //                    ]
            //                    'print' => '3'
            /////////////
            if (isset($print_comand['postsklad']['dt_start'])) {
                $para['dt_start'] = $searchModel->dt_start = $print_comand['postsklad']['dt_start'];
            } else {
                $para['dt_start'] = $searchModel->dt_start = date('d.m.Y 00:00:00', strtotime('now -1 month'));

            }

            if (isset($print_comand['postsklad']['dt_start'])) {
                $para['dt_stop'] = $searchModel->dt_stop = $print_comand['postsklad']['dt_stop'];
            } else {
                $para['dt_stop'] = $searchModel->dt_stop = date('d.m.Y 23:59:59', strtotime('now'));
            }

            /////////////

            //ddd($searchModel);
        }

        /**
         * Получаем массив из нескольких дат, котрый соответсвует датам Столбовых Накладных по дням
         *
         */
        $array_stolb_group = $this->Stolb_Group_A_Days();
        //        ddd(Date('d.m.Y H:i:s', $array_stolb_group[3]['next_day']));
        //        'date_start' => 1588615200
        //        'date_end' => 1588701599
        //        'count_all' => 1
        //        'next_day' => 1589239530

        //ddd($array_stolb_group);


        $dataProvider = $searchModel->search($para);

        $dataProvider->setSort(
            [
                'attributes' => [
                    'id',
                    'count_str',
                    'wh_destination' => [
                        'asc' => ['wh_destination' => SORT_ASC, 'wh_destination_element_name' => SORT_ASC],
                        'desc' => ['wh_destination' => SORT_DESC, 'wh_destination_element_name' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'wh_destination_element_name',
                    'dt_create_timestamp' => [
                        'asc' => ['dt_create_timestamp' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['dt_create_timestamp' => SORT_DESC, 'id' => SORT_DESC],
                        'default' => SORT_ASC,
                    ]
                    ,

                    'sklad_vid_oper',
                    'sklad_vid_oper_name',
                    'empty_cs',
                    'itogo_things',
                    'calc_minus',
                    'calc_errors',

                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ]
            ]
        );


        //
        // To EXCEL
        // 1
        //
        if (isset($print_comand['print']) && $print_comand['print'] == 1) {

            $dataProvider->pagination = ['pageSize' => -1];

            $wh_top = Sprwhtop::ArrayNamesAllIds();
            $wh_element = Sprwhelement::ArrayNamesAllIds();
            //  * Массив Соответсвия ИД-склада == ИД-группы складов
            $wh_parent_from = Sprwhelement::ArrayParentId_FromID();

            $this->render(
                'print/print_excel', [
                'dataProvider' => $dataProvider,
                'dataModels' => $dataProvider->getModels(),
                'wh_top' => $wh_top,
                'wh_element' => $wh_element,
                'wh_parent_from' => $wh_parent_from,
            ]);
        }


        //
        // To EXCEL PE ( РАСКРЫТО ПО НАКЛАДНЫМ ) PE
        // 2
        //
        if (isset($print_comand['print']) && $print_comand['print'] == 2) {

            $dataProvider->pagination = ['pageSize' => -1];

            //* Раскрытие Накладных внутри таблицы по позициям
            $data_array = Sklad_cs_past_inventory::in_model_out_array($dataProvider->getModels());

//            ddd($dataProvider->getModels());
//            ddd($data_array);

            $this->render(
                'print/print_excel_pe', [
                'dataModels' => $data_array,
            ]);
        }


        //
        $filer_cs = Sklad::Array_cs();
        //
        $para = Yii::$app->request->get('postsklad_inventory_cs');
        //* Даты Инвентаризаций
        if (isset($para['wh_destination']) && !empty($para['wh_destination'])) {
            $filter_for_date = Sklad_inventory_cs::ArrayUniq_dates_byAP($para['wh_destination']);
        } else {
            $filter_for_date = Sklad_inventory_cs::ArrayUniq_dates_byAP(0);
        }


        //        ddd($dataProvider->getModels());
        ///
        ///
        return $this->render('stat_forms_cs/tab_at_name_pe',
            [
                'model' => $searchModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'array_stolb_group' => $array_stolb_group,

                'filer_cs' => $filer_cs,
                'filter_for_date' => $filter_for_date,

                //            'filter_all_cs' => $filter_all_cs,
                //            'filter_cs_element' => $filter_cs_element,
            ]);
    }

    /**
     * Вход только по номеру накладной
     * -
     *
     * @param $id
     *
     * @return string
     */
    public function actionUpdate_id($id)
    {
        $id = Yii::$app->request->get('id');
        $para = Yii::$app->request->queryParams;

        //
        $model = Sklad_inventory_cs::find()
            ->where(['id' => (int)$id])
            ->one();

        /// Элемент для выделения крассным цветом
        if (isset($para['el'])) {
            $model->tk_element = $para['el'];
        }


        /////////////////
        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am(isset($model['array_tk_amort']) ? $model['array_tk_amort'] : []);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk'] = $this->getTkNames((isset($model['array_tk']) ? $model['array_tk'] : []));

        // ddd($model);


        return $this->render(
            '_form_read_only', [
            'new_doc' => $model,
            //            'sklad' => $sklad,

            'items_auto' => [],
            'tz_head' => [],
            'erase_array' => [],
            'alert_mess' => '',
            'itogo' => $model->itogo_things,
        ]);
    }

    /**
     * Вход только по номеру накладной
     * -
     *
     * @param $id
     *
     * @return string
     */
    public function actionUpdate_wh_id($id)
    {
//        ddd(111);

        $id = Yii::$app->request->get('id');
        $para = Yii::$app->request->queryParams;

        //
        $model = Sklad_wh_invent::find()
            ->where(['id' => (int)$id])
            ->one();

        /// Элемент для выделения крассным цветом
        if (isset($para['el'])) {
            $model->tk_element = $para['el'];
        }


        /////////////////
        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am(isset($model['array_tk_amort']) ? $model['array_tk_amort'] : []);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk'] = $this->getTkNames((isset($model['array_tk']) ? $model['array_tk'] : []));


        return $this->render(
            '/stat_svod_wh/_form_read_only', [
            'new_doc' => $model,
            'items_auto' => [],
            'tz_head' => [],
            'erase_array' => [],
            'alert_mess' => '',
//            'itogo' => $model->itogo_things,
        ]);
    }


    /**
     * Возвращает массив номеров складов (БАЗ) по накладным
     * -
     */
    public function actionUniq_wh_numbers()
    {
        $keys = Sklad::ArraySklad_Uniq_wh_numbers();
        $alert_mess = implode('<br>', $keys);

        return $this->renderContent(
            $alert_mess .
            "<br> <br> <br> " .
            Html::a('Выход', ['/'], ['class' => 'btn btn-warning'])
        );

    }

    /**
     * Возвращает самую новую накладную
     * -
     */
    public function actionLast_new_one()
    {

      //  Справочник Ids на эту дату
      //ОТЛОЖЕННАЯ загрузка частями
      // foreach (Barcode_pool::find()
      //       ->each(50) as $customer) {
      // }

      // $customer= Barcode_pool::find()->all();

      // foreach (static::find()->where(["AND",
      //         ['in', 'parent_id', $list_ids],
      //         ['<', 'do_timestamp', $time_point],
      //     ]
      // )->orderBy(['do_timestamp ASC'])->each(50) as $customer) {


      // foreach (Barcode_pool::find()->each(50) as $customer) {
      //   // ddd($customer['bar_code']);
      //         $barcode_pool[] = $customer['bar_code'];
      //   }
      //   ddd($barcode_pool);



      //.....................


        $max_id = Sklad::setNext_max_id() - 1;
        $array = Sklad::findModelAsArray($max_id);
        //  ddd($array['dt_create']);

        $alert_arr = [
            'id' => $array['id'],
            'dt_create' => $array['dt_create'],
            'dt_update' => $array['dt_update'],
            'wh_home_number' => $array['wh_home_number'],
            'sklad_vid_oper_name' => $array['sklad_vid_oper_name'],
            'wh_debet_top' => $array['wh_debet_top'],
            'wh_debet_element' => $array['wh_debet_element'],
            'wh_debet_name' => $array['wh_debet_name'],
            'wh_debet_element_name' => $array['wh_debet_element_name'],
            'wh_destination' => $array['wh_destination'],
            'wh_destination_element' => $array['wh_destination_element'],
            'wh_destination_name' => $array['wh_destination_name'],
            'wh_destination_element_name' => $array['wh_destination_element_name'],
            //            'wh_cs_number' => $array['wh_cs_number']
        ];

        $alert_mess = '';
        foreach ($alert_arr as $key => $item) {
            $alert_mess .= "<br>" . $key . " = " . $item;
        }


        //        ddd($alert_mess);

        return $this->renderContent(
            $alert_mess .
            "<br> <br> <br> " .
            Html::a('Выход', ['/'], ['class' => 'btn btn-warning'])
        );

    }

    /**
     * Перегонка дат в Timestamp формат (набор цифр - количество миллисекунд с начала времен )
     *-
     */
    public function actionDateAsTimestamp()
    {

        $sklad = ArrayHelper::map(
            Sklad::find()
                ->select(
                    [
                        'id',
                        'dt_create',
                    ])
                ->asArray()
                ->all()
            , 'id', 'dt_create');


        foreach ($sklad as $key => $item) {
            // ddd($key); ddd($item);

            $sklad_upd = Sklad::find()
                ->where(['id' => $key])
                ->one();


            $sklad_upd->dt_create_timestamp = (int)strtotime($sklad_upd->dt_create);

            if (!$sklad_upd->save(false)) {
                ddd($sklad_upd->errors);

            }

        }


        echo "<br>Timestamp. All is OK<br>";
        ddd($sklad);

        echo "OK";

    }

    /**
     *Возвращает массив Дат по накладным
     * -
     */
    public function actionUniqDates()
    {
        $keys = Sklad::ArraySklad_Uniq_dates();
        ddd($keys);
    }

    /**
     * Возвращает массив ЗАГОЛОВКОВ накладных
     *-
     */
    public function actionUniqNaklad()
    {
        $keys = Sklad::ArraySklad_Uniq_naklad();
        ddd($keys);
    }

    /**
     * СВОДНАЯ УНИВЕРСАЛЬНАЯ ТАБЛИЦА
     * Статистика ПО ЛЮБОЙ ГРУППЕ складов
     *-
     *
     * @return string
     */
    public function actionTab_at_name()
    {
        $para = Yii::$app->request->queryParams;

        //ddd($para);


        //////////////////////////////
        ///
        $searchModel_svod = new postsklad();
        $provider = $searchModel_svod->search_into_svod($para);


        //        ddd($provider);

        ///
        /////////////////////////////


        //         ddd($provider);

        ////  START - STOP
        if (isset($para['postsklad']['dt_start']) && !empty($para['postsklad']['dt_start'])) {
            $searchModel_svod['dt_start'] = $para['postsklad']['dt_start'];
            $searchModel_svod['dt_stop'] = $para['postsklad']['dt_stop'];
        } else {
            $searchModel_svod['dt_start'] = date('d.m.Y H:i:s', strtotime('now -6 month'));
            $searchModel_svod['dt_stop'] = date('d.m.Y H:i:s', strtotime('now'));
        }

        ////  wh_top
        if (isset($para['postsklad']['wh_top']) && !empty($para['postsklad']['wh_top'])) {
            $searchModel_svod['wh_top'] = $para['postsklad']['wh_top'];
        } else {
            $searchModel_svod['wh_top'] = 0;
        }
        ////  wh_element
        if (isset($para['postsklad']['wh_element']) && !empty($para['postsklad']['wh_element'])) {
            $searchModel_svod['wh_element'] = $para['postsklad']['wh_element'];
        } else {
            $searchModel_svod['wh_element'] = 0;
        }

        ////  vid_oper
        if (isset($para['postsklad']['vid_oper']) && !empty($para['postsklad']['vid_oper'])) {
            $searchModel_svod['vid_oper'] = $para['postsklad']['vid_oper'];
        } else {
            $searchModel_svod['vid_oper'] = 0;
        }


        // ddd($model);
        // ddd($searchModel_svod);
        /// print EXCEL
        if (isset($para['print']) && (int)$para['print'] == 1) {

            //                $provider->pagination->pageSize = 1500;


            $provider->pagination->pageSize = -1;

            //            ddd($provider);

            $this->render(
                'print/print_excel', [
                //'dataProvider' => pv::find()->asArray()->all(),
                'dataProvider' => $provider,
                'dataModels' => $provider->getModels(),
            ]);

            //ddd(222);
            return '';
        }

        /// print EXCEL


        return $this->render(
            'stat_forms/tab_at_name', [
            'provider' => $provider,
            'model' => $searchModel_svod,
        ]);

    }

    /**
     * Поиск По ШТРИХКОДУ с выводом в ТАБЛИЦУ
     * -
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionBarcode_to_naklad()
    {
        $para = Yii::$app->request->queryParams;
        //ddd($para);


        //        if(!isset($para['id']) || empty($para['id']) ){
        //           throw new UnauthorizedHttpException('Необходим Id SKLAD');
        //        }
        //        else{
        //            $element_id  = $para['id'];
        //        }


        if (isset($para['bar']) && !empty($para['bar'])) {
            $bar_code = $para['bar'];
        } else {
            $bar_code = '';
        }


        $array_select = [
            'id',
            'wh_home_number',

            'sklad_vid_oper',
            'sklad_vid_oper_name',
            'user_id',
            'user_name',
            'dt_update',
            'dt_create',
            'wh_debet_top',
            'wh_debet_element',
            'wh_debet_name',
            'wh_debet_element_name',
            'wh_destination',
            'wh_destination_element',
            'wh_destination_name',
            'wh_destination_element_name',
            'user_ip',
            'user_group_id',
            'tz_id',
            'update_user_id',
            'update_user_name',

            'array_tk_amort.ed_izmer',
            'array_tk_amort.ed_izmer_num',

            //            "wh_tk_amort" : "5",
            //            "wh_tk_element" : "13",
            //            "ed_izmer" : "1",
            //            "ed_izmer_num" : "2",
            //            "bar_code" : ""


            //                    'array_tk_amort',
            //                    'array_tk',
            //                    'array_casual',
            //                    'array_bus',
            //                    'array_count_all',
        ];


        // Возвращает СВОДНЫЙ ОТЧЕТ - массив
        // Mongo-запрос
        // Ускоренный !!!!
        $model_sklad = Sklad::BarCode_to_Nakladnye(
            $bar_code,
            $array_select,
            [],
            []
        );

        //        ddd($model_sklad);


        $provider = new ArrayDataProvider(
            [
                'allModels' => $model_sklad,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

        $provider->setSort(
            [
                'attributes' => [
                    //                    'id'=>[
                    //                        'asc'=>['nakladnaya'=>SORT_ASC],
                    //                        ],
                    'id' => [SORT_ASC],
                    'bar_code' => [SORT_ASC],
                    'user_id' => [SORT_ASC],
                    'user_name' => [SORT_ASC],
                    'dt_update' => [SORT_ASC],
                    'dt_create' => [SORT_ASC],
                ],
            ]);


        return $this->render(
            'stat_forms/stat_dvizh_barcode', [
            'provider' => $provider,
            'model' => $model_sklad,

            'bar_code' => $bar_code,


        ]);

    }

    /**
     * @return string|Response
     */
    public function actionCreate()
    {

        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');
        //dd($sklad);


        $max_value = Sklad::find()->max('id');;
        $max_value++;


        $new_doc = new Sklad();     // Новая накладная

        $new_doc->id = (integer)$max_value;

        $new_doc->wh_home_number = (int)$sklad;
        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

        $new_doc->user_id = (int)Yii::$app->getUser()->identity->id;
        $new_doc->user_name = Yii::$app->getUser()->identity->username;
        date_default_timezone_set("Asia/Almaty");
        $new_doc->dt_update = date('d.m.Y H:i:s', strtotime('now'));

        $new_doc->array_tk_amort = [];
        $new_doc->array_tk = [];


        if ($new_doc->load(Yii::$app->request->post())) {

            if ($new_doc->save(true)) {


                return $this->redirect('/sklad/index?sort=-id&sklad=' . $sklad);

            } else {
                //dd($model);
                return $this->redirect('/');
            }
        }


        return $this->render(
            'sklad_in/_form', [
            'new_doc' => $new_doc,
            'sklad' => $sklad,
        ]);
    }


    /**
     * @param        $id
     * @throws ExitException
     * @throws StaleObjectException
     */
    //public function actionDelete($id, $adres_to_return = "")
    public function actionDelete($id)
    {
        Sklad::findModel($id)->delete();
    }

    /**
     * Удаляем блок Столбовых Инвентаризаций ЦС за один день
     * -
     * @param $day_timestamp
     * @return void
     * @throws ExitException
     * @throws StaleObjectException
     */
    public function actionDelete_by_day($day_timestamp)
    {
        Sklad_inventory_cs::Delete_by_day($day_timestamp);
        $this->redirect('/stat_svod/index_svod_cs');
    }

    /**
     * Секрет В ПЕЧАТЬ
     * -
     *
     */
    public function actionSvodToPdf()
    {
        /////.........
        // Прием ПЕРЕДАННОГО в ПРИНТЕР
        $array_para = Yii::$app->session->get('widget_array');


        // Разный уровень доступа Для Админа и Остальных пользователей
        if (Yii::$app->getUser()->identity->group_id >= 70) {
            $filter_for = Sklad::ArraySklad_Uniq_wh_numbers_all();
        } else {
            $filter_for = Sklad::ArraySklad_Uniq_wh_numbers_plus_id_name();
        }

        if (Yii::$app->getUser()->identity->group_id == 61) {
            $filter_for = [86 => 86];
        }


        $searchModel = new postsklad();
        $searchModel['dt_start'] = $array_para['postsklad']['dt_start'];
        $searchModel['dt_stop'] = $array_para['postsklad']['dt_stop'];
        $searchModel['vid_oper'] = $array_para['postsklad']['sklad_vid_oper'];
        $searchModel['sklad'] = $array_para['postsklad']['wh_home_number'];


        //        ddd($searchModel);
        //        ddd($array_para);


        $dataProvider = $searchModel->search_svod_to_pdf($searchModel, $filter_for);

        // ddd(123);
        // $dataProvider = $searchModel->search_svod_to_pdf(Yii::$app->request->queryParams, $filter_for );

        $dataProvider->setSort(
            [
                'defaultOrder' => ['id' => SORT_ASC],
            ]);

        $dataProvider->pagination = ['pageSize' => -1];

        $this->render(
            'print/print_excel', [
            'dataProvider' => $dataProvider,
            'dataModels' => $dataProvider->getModels(),
        ]);


        return false;
    }

    /**
     * Даты создания Столбовых Инвнтаризаций.
     * Ищем даты содания и группируем в отдельные кнопки
     * Общий ЦИКЛ.
     */
    function Stolb_Group_A_Days()
    {
        $first_number = 0;
        $end_number = strtotime('now +1 day');


        $array_all_days = [];
        while ($first_number <= $end_number) {
            $array_for_me = $this->Stolb_A_Day($first_number);
            if (isset($array_for_me) && !empty($array_for_me)) {
                $array_all_days[] = $array_for_me;
            }
            if (!isset($array_for_me['next_day'])) {
                break;
            }
            $first_number = $array_for_me['next_day'];
            //$x++;
        }

        return $array_all_days;
    }

    /**
     * Даты создания Столбовых Инвнтаризаций.
     * =
     * Ищем даты содания и группируем в отдельные кнопки
     * -
     * @param int $first_number
     * @return array
     * @throws Exception
     */
    function Stolb_A_Day($first_number = 0)
    {
        if ($first_number == 0) {
            ///
            /// Получаем первую дату  в формате timestamp
            ///
            $array_first_date = Sklad_inventory_cs::find()
                ->select(['id', 'dt_create', 'dt_create_timestamp'])
                ->orderBy('dt_create_timestamp ASC')
                ->asArray()
                ->one();

            //ddd($array_first_date);
        } else {
            $array_first_date['dt_create_timestamp'] = $first_number;
        }

        ///
        /// Получаем первый блок на первую дату (первые полные сутки)
        ///   в формате timestamp
        ///
        $date_start = strtotime(Date('d.m.Y 00:00:00', $array_first_date['dt_create_timestamp']));
        $date_end = strtotime(Date('d.m.Y 23:59:59', $array_first_date['dt_create_timestamp']));

        ///
        /// Получаем первую дату после  заявленой
        /// в формате timestamp
        ///
//        $date_start = Date('d.m.Y H:i:s', $date_start);
//        ddd($date_start);

        $next_day = $this->next_a_Day($date_end);

//        $date_start = Date('d.m.Y H:i:s', $next_day);
//        ddd($date_start);


        ///
        /// Получаем количество Столбовых накладных за эти сутки
        ///
        $count_all = Sklad_inventory_cs::find()
            ->select(['id', 'dt_create_timestamp'])
            ->where(['AND',
                    ['>=', 'dt_create_timestamp', $date_start],
                    ['<=', 'dt_create_timestamp', $date_end]
                ]
            )
            ->orderBy('dt_create_timestamp ASC')
            ->count();

        ///
        /// Оправляем в нашем протоколе данных
        ///
        $array_one_day = [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'count_all' => $count_all,

            'next_day' => $next_day,  /// Следующий день, если он есть
        ];

        if ($count_all == 0) {
            $array_one_day = [];
        }

//        ddd($array_one_day);
        return $array_one_day;
    }

    /**
     *  Получаем первую дату после  заявленой
     *  в формате timestamp
     * @param $dt_timestamp
     * @return array
     */
    function next_a_Day($dt_timestamp)
    {
        ///
        /// Получаем первую дату после  заявленой
        /// в формате timestamp
        ///
        $array_first_date = Sklad_inventory_cs::find()
            ->select(['id', 'dt_create_timestamp'])
            ->where(
                ['>', 'dt_create_timestamp', $dt_timestamp]
            )
            ->orderBy('dt_create_timestamp ASC')
            ->asArray()
            ->one();

        //        $date_start = Date('d.m.Y 00:00:00', $dt_timestamp);
        //        ddd($date_start);

        return $array_first_date['dt_create_timestamp'];
    }

    /**
     * Главная таблица. Свод по Базовому складу.
     * =
     * Полное движение по складам ЦС
     * -
     *
     *
     * @return string
     */
    public function actionIndex_move_cs()
    {
        $para = Yii::$app->request->queryParams;
        $print_comand = Yii::$app->request->get();

        //ddd($para);


        //Если поступил новый АВтопрк ЦС
        if (isset($para['postsprwhtop'])) {
            $session['id'] = $para['postsprwhtop']['id']; // Это то самый секрет!!!
        }


        ///
        /// ПЕРЕДАЧА ДАННЫХ ФИЛЬТРА ДЛЯ ПЕЧАТИ ЧЕРЕЗ СЕССИЮ
        ///
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        ///
        /// $para
        ///
        if (!isset($para['print'])) {
            $session['repquery'] = $para; // Это то самый секрет!!!
        } else {
            $para = $session['repquery'];  //  тут его получаем
        }
        ////////////

        //ddd($para);


        //ddd($session['repquery']);

        //
        // Разный уровень доступа Для Админа и Остальных пользователей
        //
        //			if(
        //				Yii::$app->getUser()->identity->group_id == 61 ||
        //				Yii::$app->getUser()->identity->group_id >= 70
        //			)
        //			{

        //			if(isset ( $para[ 'postsklad' ][ 'dt_start' ] ) || isset ( $para[ 'postsklad' ][ 'dt_stop' ] ))
        //			{
        //				$filter_for = Sklad::ArraySklad_cs_Uniq_time(
        //					date( 'd.m.Y 00:00:00', strtotime( $para[ 'postsklad' ][ 'dt_start' ] ) ),
        //					date( 'd.m.Y 23:59:59', strtotime( $para[ 'postsklad' ][ 'dt_stop' ] ) )
        //				);
        //
        //
        //				//ddd($para);
        //				//ddd($filter_for);
        //			} else
        //			{
        //				$filter_for = Sklad::ArraySklad_cs_Uniq_time(
        //					date( 'd.m.Y 00:00:00', strtotime( 'now -7 days' ) ),
        //					date( 'd.m.Y 23:59:59', strtotime( 'now' ) )
        //				);
        //			}

        //$filter_for = Sklad::ArraySklad_Uniq_wh_numbers_all();
        //			} else
        //			{
        //				$filter_for = Sklad::ArraySklad_Uniq_wh_numbers_plus_id_name();
        //			}


        //
        // Назира имеет доступ только к складу
        // Виктора
        //
        //			if(Yii::$app->getUser()->identity->group_id == 61)
        //			{
        //				$filter_for = [ 86 => "АСУОП. Виктор" ]; // Только к складу Виктора
        //			}

        //
        // САНЖАРа имеет доступ только к складам ЦС
        //


        $searchModel = new postsklad();

        //        $searchModel_company = new postsprwhtop();
        //        $searchModel_company_elem = new postsprwhelement();


        //        if (isset($para['postsklad']['sklad_vid_oper']) && !empty($para['postsklad']['sklad_vid_oper'])) {
        //            $searchModel['dt_stop'] = date('d.m.Y', strtotime($para['postsklad']['dt_stop']));
        //        }


        //        if (empty($para['postsklad']['dt_stop'])) {
        //            $para['postsklad']['dt_start'] = date('d.m.Y', strtotime('now -1 month'));
        //            $para['postsklad']['dt_stop'] = date('d.m.Y', strtotime('now'));
        //        }


        ///
        ///  Работает  ОДНОДНЕВНАЯ ВЫБОРКА !!!!  через модель поиска
        ///  Место только ТУТ!
        ///
        ///

        //ddd($para);


        if (isset($para['postsklad'])) {

            if (isset($para['postsklad']['dt_stop']) && !empty($para['postsklad']['dt_stop'])) {

                $searchModel['dt_stop'] = date('d.m.Y 23:59:59', strtotime($para['postsklad']['dt_stop']));
            }
            if ($para['postsklad']['dt_start'] >= $para['postsklad']['dt_stop']) {

                $searchModel['dt_stop'] = date('d.m.Y 23:59:59', strtotime('now'));
            }
        }


        /////
        /// $print_comand['print']
        if (!isset($print_comand['print'])) {

            $dataProvider = $searchModel->search_move_cs($para);

        } else {

            $dataProvider = $searchModel->search_svod_excel($para);
            //ddd($print_comand);

        }
        /////
        ///


        $dataProvider->setSort(
            [
                'attributes' => [

                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],


                    'dt_start' => [
                        'asc' => ['dt_create_timestamp' => SORT_ASC],
                        'desc' => ['dt_create_timestamp' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'dt_stop' => [
                        'asc' => ['dt_create_timestamp' => SORT_ASC],
                        'desc' => ['dt_create_timestamp' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'user_name' => [
                        'asc' => ['user_name' => SORT_ASC],
                        'desc' => ['user_name' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'wh_cs_parent_name' => [
                        'asc' => ['wh_cs_parent_name' => SORT_ASC],
                        'desc' => ['wh_cs_parent_name' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],

                    'sklad_vid_oper',
                    'wh_home_number',

                    //'wh_cs_number',
                    //'wh_cs_parent_number',
                    //'sprwhelement_cs_number.sprwhtop',


                    'wh_cs_number' => [
                        'asc' => [
                            'wh_cs_parent_number' => SORT_ASC,
                            'wh_cs_number' => SORT_ASC,
                        ],

                        'desc' => [
                            'wh_cs_parent_number' => SORT_DESC,
                            'wh_cs_number' => SORT_DESC,
                        ],

                        'default' => SORT_ASC,
                    ],

                    'tz_id',
                    'array_count_all',
                    'wh_debet_name',
                    'wh_debet_element_name',
                    'wh_destination_name',
                    'wh_destination_element_name',

                    'tx',
                ],

                'defaultOrder' => ['dt_start' => SORT_ASC],

            ]);


        /// Для выпадаюшего списка картик селект2 (не мульти)
        $dataWh = $searchModel->search_wh($para);

        //для
        //wh_cs_number
        $www = ArrayHelper::getColumn($dataWh->getModels(), 'wh_cs_number');
        $filter_for_wh_cs_number = Sklad::ListFromInput_array_sprwhelement($www);
        //для
        //wh_debet_element
        $www = ArrayHelper::getColumn($dataWh->getModels(), 'wh_debet_element');
        $filter_for_wh_debet_element = Sklad::ListFromInput_array_sprwhelement($www);
        //для
        //wh_destination_element
        $www = ArrayHelper::getColumn($dataWh->getModels(), 'wh_destination_element');
        $filter_for_wh_destination_element = Sklad::ListFromInput_array_sprwhelement($www);


        //
        // To EXCEL
        //1
        if ((isset($print_comand['print']) && $print_comand['print'] == 1) ||
            (isset($para['print']) && $para['print'] == 1)
        ) {

            //
            // Получаем из СЕССИИ параметры для печати
            //
            //            $para= $session['repquery'];


            $dataProvider->pagination = ['pageSize' => -1];


            $wh_top = Sprwhtop::ArrayNamesAllIds();
            $wh_element = Sprwhelement::ArrayNamesAllIds();

            //  * Массив Соответсвия ИД-склада == ИД-группы складов
            $wh_parent_from = Sprwhelement::ArrayParentId_FromID();


            $this->render(
                'print/print_excel', [
                'dataProvider' => $dataProvider,
                'dataModels' => $dataProvider->getModels(),
                'wh_top' => $wh_top,
                'wh_element' => $wh_element,
                'wh_parent_from' => $wh_parent_from,
            ]);
        }


        //
        // EXCEL ( РАСКРЫТО ПО НАКЛАДНЫМ)
        //2
        if (isset($print_comand['print']) && $print_comand['print'] == 2) {
            //            ddd($dataProvider->getModels());

            $dataProvider->pagination = ['pageSize' => -1];


            //* Раскрытие Накладных внутри таблицы по позициям
            //            $data_array=Sklad::in_model_out_array(ArrayHelper::toArray( $dataProvider->getModels()));
            $data_array = Sklad::in_model_out_array($dataProvider->getModels());

            //ddd($data_array);

            $this->render(
                'print/print_excel_pe', [
                'dataProvider' => $dataProvider,
                //'dataModels' => $dataProvider->getModels(),
                'dataModels' => $data_array,
            ]);
        }


        //
        // EXCEL ( РАСКРЫТО ПО НАКЛАДНЫМ).
        // С ЦЕНОЙ
        //22
        if (isset($print_comand['print']) && $print_comand['print'] == 22) {
            //            ddd($dataProvider->getModels());

            $dataProvider->pagination = ['pageSize' => -1];


            //* Раскрытие Накладных внутри таблицы по позициям
            //            $data_array=Sklad::in_model_out_array(ArrayHelper::toArray( $dataProvider->getModels()));
            $data_array = Sklad::in_model_out_array($dataProvider->getModels());

            //            ddd($data_array);

            foreach ($data_array as $key => $item) {
                if (isset($item['bar_code']) && $item['bar_code']) {

                    $id_part = Barcode_pool::getBarcode_consignment_id($item['bar_code']);
                    $part_array = Barcode_consignment::getConsignment_one($id_part);

                    //                        $data_array[$key]['part']= $part_array['cena_input'];
                    $data_array[$key]['part_cena_input'] = $part_array['cena_input'];
                    $data_array[$key]['part_date'] = $part_array['dt_create'];
                    $data_array[$key]['part_name'] = $part_array['name'];
                } else {

                    if (isset($item['wh_tk_element_id'])) {
                        $max_summary = Consignment::max_summary_by_id($item['wh_tk_element_id']);

                        $full_array_by_maxsummary = Consignment::full_array_by_maxsummary($item['wh_tk_element_id'], $max_summary);
                        //ddd($full_array_by_maxsummary);


                        //ddd($max_summary);
                        $data_array[$key]['part_cena_input'] = $max_summary;
                        $data_array[$key]['part_date'] = $full_array_by_maxsummary['dt_create'];
                        //$data_array[$key]['part_name'] = $full_array_by_maxsummary['element_name'];
                        $data_array[$key]['part_name'] = $full_array_by_maxsummary['name'];
                    }

                }

            }


            //ddd($data_array);


            $this->render(
                'print/print_excel_summ', [
                'dataProvider' => $dataProvider,
                //'dataModels' => $dataProvider->getModels(),
                'dataModels' => $data_array,
            ]);
        }


        //
        // EXCEL ТОЛЬКО АСУОП  ( РАСКРЫТО ПО НАКЛАДНЫМ )
        //3
        if (isset($print_comand['print']) && $print_comand['print'] == 3) {
            $dataProvider->pagination = ['pageSize' => -1];

            //ddd($dataProvider->getModels());


            //* Раскрытие Накладных внутри таблицы по позициям
            $data_array = Sklad::in_model_out_array_amort($dataProvider->getModels());


            //ddd($data_array);

            $this->render(
                'print/print_excel_pe', [
                'dataModels' => $data_array,
            ]);
        }


        //Список ЦС - групп
        $filter_wh_top_cs = Sprwhtop::Array_cs(); // map

        $filter_group_cs1 = Sklad::Array_cs();
        $filter_group_cs2 = Sklad::Array_cs();


//        $filter_group_cs_element1 = Sklad::Array_cs_where(7);


        //
        //Список Пользователей ЦС
        //$filter_wh_top_cs = \common\models\User::Array_cs(); // map


        //        $filter_wh_element = Sprwhelement::Array_cs(1); // map


//        ddd($dataProvider->getModels());


        return $this->render(
            'index_svod_cs/tab_at_name', [


            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

            'print_comand' => $print_comand,

            'filter_wh_top_cs' => $filter_wh_top_cs,

            'filter_group_cs1' => $filter_group_cs1,
            'filter_group_cs2' => $filter_group_cs2,
//            'filter_group_cs_element1' => $filter_group_cs_element1,

            'filter_for_wh_cs_number' => $filter_for_wh_cs_number,
            'filter_for_wh_debet_element' => $filter_for_wh_debet_element,
            'filter_for_wh_destination_element' => $filter_for_wh_destination_element,

        ]);
    }

    /**
     * Движение по Одному СКЛАДУ ЦС
     * =
     * за все время
     * =
     * Аналогия ПО БАЗЕ
     *
     * @return string
     */
    public function actionIndex_move_cs_one()
    {
        $para = Yii::$app->request->queryParams;
        $print_comand = Yii::$app->request->get();

        //ddd( $para[ 'сs_id' ] );
        //'сs_id' => '177'
        //сs_id=177
        //$filter_for = Sklad::ArraySklad_Uniq_wh_numbers_all();


        $filter_for = Sklad::ArraySklad_cs_all();

        $searchModel = new postsklad();

        /////
        $dataProvider = $searchModel->search_move_cs_one($para);
        /////


        return $this->render(
            'index_svod_cs/tab_one', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filter_for' => $filter_for,
            'print_comand' => $print_comand,
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

        $spr_globam_element_model_intelligent = ArrayHelper::map(Spr_globam_element::find()->orderBy('name')->all(), 'id', 'intelligent');
        //ddd($spr_globam_element_model_intelligent);


        $buff = [];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                $buff[$key]['name_wh_tk_amort'] = $spr_globam_model[$item['wh_tk_amort']];
                $buff[$key]['name_wh_tk_element'] = $spr_globam_element_model[$item['wh_tk_element']];
                //$buff[$key]['name_ed_izmer']=$spr_things_model[$item['ed_izmer']];

                $buff[$key]['name_ed_izmer'] = 'шт';
                $buff[$key]['ed_izmer'] = '1';


                if (isset($item['bar_code'])) {
                    $buff[$key]['bar_code'] = ($item['bar_code'] > 0 ? $item['bar_code'] : '');
                    $buff[$key]['intelligent'] = ((int)$spr_globam_element_model_intelligent[$item['wh_tk_element']]);
                }

                $buff[$key]['wh_tk_amort'] = $item['wh_tk_amort'];
                $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                //                $buff[$key]['take_it'] = $item['take_it'];
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
    public function getTkNames($array_tk)
    {
        $spr_glob_model = ArrayHelper::map(Spr_glob::find()->orderBy('name')->all(), 'id', 'name');
        $spr_glob_element_model = ArrayHelper::map(Spr_glob_element::find()->orderBy('name')->all(), 'id', 'name');
        $spr_things_model = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');


        $buff = [];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                if (!empty($item)) {

                    $buff[$key]['name_tk'] = $spr_glob_model[$item['wh_tk']];
                    $buff[$key]['name_tk_element'] = (isset($spr_glob_element_model[$item['wh_tk_element']]) ? $spr_glob_element_model[$item['wh_tk_element']] : 0);
                    $buff[$key]['name_ed_izmer'] = $spr_things_model[$item['ed_izmer']];


                    $buff[$key]['wh_tk'] = $item['wh_tk'];
                    $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                    $buff[$key]['ed_izmer'] = $item['ed_izmer'];
                    $buff[$key]['take_it'] = $item['take_it'];
                    $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];

                    //$buff[$key]['name']=$item['name'];
                }

            }
        }

        //        ddd($array_tk);
        //        ddd($buff);

        return $buff;
    }

}
