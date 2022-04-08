<?php

namespace frontend\controllers;

use frontend\models\Barcode_pool;
use frontend\models\FiltersForm;
use frontend\models\post_spr_glob_element;
use frontend\models\postSklad_wh_invent;
use frontend\models\postsklad_inventory_wh;
use frontend\models\Sklad;
use frontend\models\Sklad_inventory;
use frontend\models\Sklad_wh_invent;
use frontend\models\Sklad_past_inventory_cs;

use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\components\MyHelpers;
use frontend\models\Sprwhtop;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;
use yii\base\ExitException;
use yii\data\ArrayDataProvider;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;


class Sklad_inventory_whController extends Controller
{
    public $sklad;

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
                    'create_new_wh' => ['GET', 'POST', 'PUT'],
                    'update' => ['POST', 'GET', 'GET'],
                    'delete' => ['POST', 'GET'],

                ],
            ],
        ];
    }

    /**
     */
    public function init()
    {
        parent::init();

        $session = Yii::$app->session;
        $session->open();

        ///
//        if (!Yii::$app->getUser()->identity->getId()) {
        if (!isset(Yii::$app->getUser()->identity)) {
            /// Быстрая переадресация
            throw new HttpException(411, 'Необходима авторизация', 2);
        }

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
        $postSklad_wh_invent = Yii::$app->request->get('postSklad_wh_invent');//


        ///
        if (!isset($para_print) || (int)$para_print == 0) {
            #PARA PRINT FILTER
            Sklad::setPrint_param($postSklad_wh_invent);
            #PARA SORT
            Sklad::setSort_param($para_sort);
            #PARA BETWEEN
            Sklad::setSelect_param($para_select);
        }

        ///
        $searchModel = new postsklad_inventory_wh();
        $dataProvider = $searchModel->search($para);

        //ddd($para);
        //ddd($dataProvider->getModels());

        ///
        $dataProvider->setSort(
            [
                'attributes' => [
                    'dt_create_timestamp',
                    'dt_create',
                    'dt_create_day',
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


//         ddd($dataProvider->getModels());
//         ddd($para);
        // 'select2_array_destination' => '41'

        ///Ораничение Массивом ИДСS
        $array_ids_into_table = [];


        /////////////GROUP
        $filter_for_group = Sklad_wh_invent::ArrayUniq_destination();

        // GROUP
        //* Получить Список-Дроп только Используемых номеров Складов
        if (isset($para['select2_array_destination']) && !empty($para['select2_array_destination'])) {

            ///Ораничение Массивом ИДСS
            $array_ids_into_table = Sklad_wh_invent::ArrayIDS_by_wh_destination_element($para['select2_array_destination']);
            ///
            $filter_for_element = Sklad_wh_invent::ArrayUniq_wh_numbers_by_id($para['select2_array_destination'], $array_ids_into_table);

        } else {
            $filter_for_element = Sklad_wh_invent::ArrayUniq_wh_numbers(-1);
            $filter_for_date = Sklad_wh_invent::ArrayUniq_dates();
        }

        //  ddd($para);
        // ELEMENT
        //* Получить Список-Дроп только Используемых номеров Складов
        if (isset($para['select2_array_cs_numbers']) && !empty($para['select2_array_cs_numbers'])) {

            ///Ораничение Массивом ИДСS
            $array_ids_into_table = Sklad_wh_invent::ArrayIDS_by_wh_days($para['select2_array_cs_numbers']);
            ///
            $filter_for_date = Sklad_wh_invent::ArrayDAYS_by_id($para['select2_array_cs_numbers'], $array_ids_into_table);

        } else {
            //* Даты Инвентаризаций = all
            $filter_for_date = Sklad_wh_invent::ArrayUniq_dates();
        }


//        //* Даты Инвентаризаций
//        if (isset($para['select2_array_cs_numbers']) && !empty($para['select2_array_cs_numbers'])) {
//            $filter_for_date = Sklad_wh_invent::ArrayUniq_dates($para['select2_array_cs_numbers']);
//        } else {
//            $filter_for_date = Sklad_wh_invent::ArrayUniq_dates(0);
//        }
        //ddd($filter_for_date);


        //Запомнить РЕФЕР
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);

        //$filter_for_group
        //$filter_for_element

        //ddd($dataProvider->getModels());

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
     * Редактирование Накладной
     * =
     * @return string|Response
     * @throws UnauthorizedHttpException
     */
    public function actionUpdate()
    {
        ini_set('max_execution_time', '30'); //300 seconds = 5 minutes
        ini_set('memory_limit', '32M');
        ini_set('max_input_vars', '100000');
        //ini_set('upload_max_size','30M');
        //ini_set('post_max_size','30M');


        $id = Yii::$app->request->get('id');


        if (!isset($id) || empty($id)) {
            throw new UnauthorizedHttpException('Не подключен ID');
        }

        $para_post = Yii::$app->request->post();
        ////////
        $model = Sklad_wh_invent::findModel($id);


        if ($model->load(Yii::$app->request->post())) {
            $model->update_user_id = (int)Yii::$app->getUser()->identity->id;

            //$para = Yii::$app->request->post();
            $model->wh_destination = (int)$model->wh_destination;
            $model->wh_destination_element = (int)$model->wh_destination_element;

            // $model->array_tk_amort
            $model->array_tk_amort = Sklad_wh_invent::Adapter_amort($model->array_tk_amort);

            //ddd($model);

//            "name_wh_tk_amort" : "АСУОП",
//            "name_wh_tk_element" : "CVB24 master",
//            "name_ed_izmer" : "шт.",

//                'bar_code' => '041111'
//                't' => '23.10.2020 08:53:51'
//                'id' => '30946'

            //ddd($model);

//            dd( memory_get_usage());
//            ini_set('memory_limit', '12M');


            if (!$model->save(true)) {
                ddd($model->errors);
            }

            //            ddd($model);
            //            ddd($para);

        }


        //ddd($model);


        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
//        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        //$model['array_tk'] = $this->getTkNames($model['array_tk']);

        //ddd($model);


        $spr_things = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');

        $list_wh_top = ArrayHelper::map(Sprwhtop::find()
            ->orderBy('name')
            ->all(), 'id', 'name');

        $list_wh_element = ['....'];
        $list_wh_top[$model->wh_destination] = $model->wh_destination_name;
        $list_wh_element[$model->wh_destination_element] = $model->wh_destination_element_name;
        //ddd($model);


        return $this->render('_form', [
            'new_doc' => $model,
            'spr_things' => $spr_things,
            'list_wh_top' => $list_wh_top,
            'list_wh_element' => $list_wh_element,
            'alert_mess' => '',
        ]);

    }

    /**
     * Сводная по наименованиям товаров
     * =
     * @return string|Response
     * @throws UnauthorizedHttpException
     */
    public function actionUpdate_svod()
    {
        //
        $para_post = Yii::$app->request->post();
        //
        $id = Yii::$app->request->get('id');
        if (!isset($id) || empty($id)) {
            throw new UnauthorizedHttpException('Не подключен ID');
        }

        $alert = '';

        ////////
        $model = Sklad_wh_invent::findModel($id);
        //
        $model->add_kol = 1;
        $count_svod = 0;

        if (!empty($model->array_tk_amort) && is_array($model->array_tk_amort)) {

            $array_model = $model->array_tk_amort;
            $array_model = self::group_by_SVOD($array_model);
            $count_svod = self::count_by_SVOD($array_model);
            //ddd($count_svod);

        } else {
            $array_model = [];
        }

//        ddd($array_model);


        ///
        ///
        if ($model->load(Yii::$app->request->post())) {
            if (isset($para_post['button'])) {
                //
                switch ($para_post['button']) {
                    case 'save':
                        //SAVE
                        //
                        $model->update_user_id = (int)Yii::$app->getUser()->identity->id;
                        //$para = Yii::$app->request->post();
                        $model->wh_destination = (int)$model->wh_destination;
                        $model->wh_destination_element = (int)$model->wh_destination_element;

                        // $model->array_tk_amort
                        $model->array_tk_amort = Sklad_wh_invent::Adapter_amort($model->array_tk_amort);
                        //
                        $model->count_str = count($model->array_tk_amort);

                        //            ddd($model);
                        if ($model->save(true)) {
                            $alert = 'Сохранение. Все нормально...';
                        } else {
                            $alert = 'Сохранение. ЕСТЬ ОШИБКИ. НЕ СОХРАНЕНО';
                        }
                        break;


                }
            }
        }


        ///////////////
        $provider = new ArrayDataProvider([
            'allModels' => $array_model,
            'pagination' => ['pageSize' => -1]
        ]);


        //
        $provider->setSort(
            [
                'attributes' => [
                    'id_key',
                    'tx',
                    'wh_tk_amort' => [
                        'asc' => ['name_wh_tk_amort' => SORT_ASC, 'wh_tk_element' => SORT_ASC],
                        'desc' => ['name_wh_tk_amort' => SORT_DESC, 'wh_tk_element' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'wh_tk_element' => [
                        'asc' => ['wh_tk_element' => SORT_ASC, 'bar_code' => SORT_ASC],
                        'desc' => ['wh_tk_element' => SORT_DESC, 'bar_code' => SORT_ASC],
                        'default' => SORT_ASC,
                    ],
                    'bar_code',
                    'id' => [
                        'asc' => ['id' => SORT_ASC, 'bar_code' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC, 'bar_code' => SORT_ASC],
                        'default' => SORT_ASC,
                    ],
                    't' => [
                        'asc' => ['t' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['t' => SORT_DESC, 'id' => SORT_ASC],
                        'default' => SORT_ASC,
                    ],
                ],

            ]);


        //На печать в EXCEL
        if ((int)Yii::$app->request->post('print') == 1) {

            //ddd($model);

            // Название склада
            $fill_name1 = $model->wh_destination_name;
            $fill_name2 = $model->wh_destination_element_name;
            $fill_name3 = $model->sklad_vid_oper_name;

            //
            return $this->render('print/print_excel', [
                'dataModels' => $array_model,
                'fill_name1' => $fill_name1,
                'fill_name2' => $fill_name2,
                'fill_name3' => $fill_name3,
            ]);


        }


        //dd(count($array_model));

        //ddd($model);


//         Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
//        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);
//         Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
//        $model['array_tk'] = $this->getTkNames($model['array_tk']);
//        ddd($model);

        //
        $spr_group = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');
        //
        $spr_things = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        //
        $list_wh_top = ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');
        $list_wh_element = ['....'];
        $list_wh_top[$model->wh_destination] = $model->wh_destination_name;
        $list_wh_element[$model->wh_destination_element] = $model->wh_destination_element_name;

        // ddd($model);

        return $this->render('_form_svod', [
            'id' => $id,
            'model' => $model,
            'provider' => $provider,
            'spr_group' => $spr_group,
            'spr_things' => $spr_things,
            'list_wh_top' => $list_wh_top,
            'list_wh_element' => $list_wh_element,

            'count_svod' => $count_svod,
            'alert_mess' => $alert,
        ]);

    }

    /**
     * Редактирование Накладной. Новый вариант
     * =
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionUpdate_edit()
    {
        //
        $para_post = Yii::$app->request->post();
        //
        $id = Yii::$app->request->get('id');
        if (!isset($id) || empty($id)) {
            throw new UnauthorizedHttpException('Не подключен ID');
        }

        $alert = '';

        ////////
        $model = Sklad_wh_invent::findModel($id);
        //
        $model->add_kol = 1;

        if (!empty($model->array_tk_amort) && is_array($model->array_tk_amort)) {
            $array_model = $model->array_tk_amort;
            $array_model = self::add_keys($array_model);
        } else {
            $array_model = [];
        }

        //ddd($array_model);


        ///
        ///
        if ($model->load(Yii::$app->request->post())) {
            //

            //$model->id = Sklad_wh_invent::setNext_max_id();

            if (isset($para_post['button'])) {

                //
                switch ($para_post['button']) {
                    case 'save':
                        //SAVE
                        //
                        $model->update_user_id = (int)Yii::$app->getUser()->identity->id;
                        //$para = Yii::$app->request->post();
                        $model->wh_destination = (int)$model->wh_destination;
                        $model->wh_destination_element = (int)$model->wh_destination_element;

                        // $model->array_tk_amort
                        $model->array_tk_amort = Sklad_wh_invent::Adapter_amort($model->array_tk_amort);
                        //
                        $model->count_str = count($model->array_tk_amort);

                        //            ddd($model);
                        if ($model->save(true)) {
                            $alert = 'Сохранение. Все нормально...';
                        } else {
                            $alert = 'Сохранение. ЕСТЬ ОШИБКИ. НЕ СОХРАНЕНО';
                        }
                        break;

                    case 'add_item':
                        //ADD + . ДОБАВИТЬ ОДНУ НОВУЮ ЗАПИСЬ
                        $arr_land = [];
                        //
                        if (!empty($model->array_tk_amort) && is_array($model->array_tk_amort)) {
                            $arr_land = (array)$model->array_tk_amort;
                            //dd(count($arr_land));

                            //
                            $full_name = Spr_globam_element::findFullArray($model->add_item);
                            //
                            $array_add = [
                                'wh_tk_amort' => (int)$model->add_group,
                                'wh_tk_element' => (int)$model->add_item,
                                'ed_izmer' => $arr_land[0]['ed_izmer'],

                                //'ed_izmer_num' => $arr_land[0]['ed_izmer_num'],
                                'ed_izmer_num' => (int)$model->add_kol,

                                'bar_code' => (string)$model->add_barcode,
                                'intelligent' => $full_name['child']['intelligent'],
                                'name_wh_tk_amort' => $full_name['top']['name'],
                                //'name_wh_tk_element' => $full_name['child']['name'],
                                'name_wh_tk_element' => $full_name['child']['short_name'],
                                'name_ed_izmer' => 'шт.',
                                //'t' => strtotime('now'),
                                //'id' => $arr_land[0]['id'],
                                't' => '',
                                'id' => '',
                            ];

                            //
                            array_push($arr_land, $array_add);
                        }

                        //dd(count($arr_land));

                        //
                        //$array_model->array_tk_amort=$arr_land ;
                        $model->array_tk_amort = $arr_land;

                        //
                        if ($model->save(true)) {
                            $alert = 'Добавление одной позиции. Все нормально...' . count($arr_land);
                        } else {
                            $alert = 'Добавление одной позиции. ЕСТЬ ОШИБКИ. НЕ СОХРАНЕНО';
                        }
                        //
                        break;


                    case 'save_lot_files':
                        //СЛИТЬ В ЕДИНОЕ ЦЕЛОЕ НЕСКОЛЬКО НАКЛАДНЫХ

                        $model_new = new  Sklad_wh_invent();
                        $model_new->id = (int)Sklad_wh_invent::setNext_max_id();

                        ///Все накладные за этот день по данному складу.
                        $model_all = Sklad_wh_invent::models_byOne_day($model->wh_home_number, $model->dt_create_day);

                        ///
                        $model_new->dt_create_timestamp = $model_all[0]['dt_create_timestamp'] + 10;
                        $model_new->dt_create = date('d.m.Y H:i:s', $model_new->dt_create_timestamp);
                        $model_new->dt_create_day = strtotime(date('d.m.Y 00:00:02', $model_new->dt_create_timestamp));
                        $model_new->sklad_vid_oper = (int)1;
                        $model_new->sklad_vid_oper_name = $model_all[0]['sklad_vid_oper_name'];
                        $model_new->wh_home_number = $model_all[0]['wh_home_number'];
                        $model_new->wh_destination = $model_all[0]['wh_destination'];
                        $model_new->wh_destination_element = $model_all[0]['wh_destination_element'];
                        $model_new->wh_destination_name = $model_all[0]['wh_destination_name'];
                        $model_new->wh_destination_element_name = $model_all[0]['wh_destination_element_name'];
                        $model_new->dt_update = $model_all[0]['dt_update'];
                        $model_new->dt_update_timestamp = $model_all[0]['dt_update_timestamp'];
                        //
                        $model_new->user_id = (int)Yii::$app->getUser()->identity->id;
                        $model_new->user_name = Yii::$app->getUser()->identity->username;

                        $model_new->tx = 'Создано слиянием нескольких накладных ';

                        $model_res = [];
                        foreach ($model_all as $model_one) {
                            $model_one_am = (array)$model_one['array_tk_amort'];
                            foreach ($model_one_am as $item) {
                                $model_res[] = $item;
                            }
                            unset($model_one_am);
                        }

                        //ddd($model_res);

                        ///
                        $model_new->array_tk_amort = $model_res;
                        //
                        $model_new->count_str = count($model_res);

                        //ddd($model_new);
                        ///

                        if ($model_new->save(true)) {
                            $alert = 'Объединение. Все нормально...';
                        } else {
                            ddd($model_new->errors);
                            $alert = 'Объединение. ЕСТЬ ОШИБКИ. НЕ СОХРАНЕНО';
                        }

                        break;
                }
            }

            //DELETE. УДАЛИТЬ ОДНУ ЗАПИСЬ
            if (isset($para_post['button_delete'])) {
                //
                foreach ($array_model as $item) {
                    if ((int)$item['id_key'] === (int)$para_post['button_delete']) {
                        continue;
                    }
                    $array_rez[] = $item;
                }
                ///
                $model->array_tk_amort = $array_rez;
                $model->count_str = count($array_rez);
                //
                $alert = 'Удаление. Все нормально...';
            }

            //ФИЛЬТР. ОТМЕНА
            if (isset($para_post['filtr']) && $para_post['filtr'] === 'close') {
                //Закрыть сессию
                Sklad::setUnivers('filter_bc', '');
                Sklad::setUnivers('filter_id', '');
                Sklad::setUnivers('filter_dt', '');
                Sklad::setUnivers('filter_double_sting', '');
            }

            //ФИЛЬТР
            if (isset($para_post['filtr']) && $para_post['filtr'] === 'filtr') {
                Sklad::setUnivers('filter_bc', $para_post['Sklad_wh_invent']['filter_bc']);
                Sklad::setUnivers('filter_id', $para_post['Sklad_wh_invent']['filter_id']);
                Sklad::setUnivers('filter_dt', $para_post['Sklad_wh_invent']['filter_dt']);
            }


            //            if (!empty($arr_land)) {
            //                $model->count_str = count($arr_land);
            //                ///
            //                $model->array_tk_amort = $arr_land;
            //                ///
            //                if (!$model->save(true)) {
            //                    ddd($model->errors);
            //                }
            //            }

            //ddd($para_post);

            //Двойники
            if (Sklad::getUnivers('filter_double_sting') == 'double_sting' ||
                isset($para_post['filtr']) && $para_post['filtr'] === 'double_sting') {

                Sklad::setUnivers('filter_double_sting', 'double_sting');

                //ddd($array_model);

                // $array_model = $model->array_tk_amort;
                //ddd($array_model);
                foreach ($array_model as $key => $item) {
                    $arr_key[$key] = $item['bar_code'];
                }
                asort($arr_key);

                //dd($arr_key);

                $array_model_itog = [];
                $str = '';
                $key_2 = '';
                foreach ($arr_key as $key => $item) {
                    if ($item === $str) {
                        $arr_rez[$key] = $item;
                        $arr_key_itog[] = $key;
                        $arr_key_itog[] = $key_2;
                        ///
                        $array_model_itog[] = $array_model[$key];
//                    $array_model_itog[] = $array_model[$key_2];
                    }
                    $str = $item;
//                $key_2 = $key;
                }

                ///
                $array_model = $array_model_itog;
            }
        }


        ///
        ///
        $array_model = Sklad_wh_invent::FilterModel('bar_code', Sklad::getUnivers('filter_bc'), $array_model);
        $array_model = Sklad_wh_invent::FilterModel('id', Sklad::getUnivers('filter_id'), $array_model);
        $array_model = Sklad_wh_invent::FilterModel('t', Sklad::getUnivers('filter_dt'), $array_model);

        //
        $provider = new ArrayDataProvider([
            'allModels' => $array_model,
            'pagination' => ['pageSize' => 10]
        ]);


        //
        $provider->setSort(
            [
                'attributes' => [
                    'id_key',
                    'tx',
                    'wh_tk_amort' => [
                        'asc' => ['name_wh_tk_amort' => SORT_ASC, 'wh_tk_element' => SORT_ASC],
                        'desc' => ['name_wh_tk_amort' => SORT_DESC, 'wh_tk_element' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'wh_tk_element' => [
                        'asc' => ['wh_tk_element' => SORT_ASC, 'bar_code' => SORT_ASC],
                        'desc' => ['wh_tk_element' => SORT_DESC, 'bar_code' => SORT_ASC],
                        'default' => SORT_ASC,
                    ],
                    'bar_code',
                    'id' => [
                        'asc' => ['id' => SORT_ASC, 'bar_code' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC, 'bar_code' => SORT_ASC],
                        'default' => SORT_ASC,
                    ],
                    't' => [
                        'asc' => ['t' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['t' => SORT_DESC, 'id' => SORT_ASC],
                        'default' => SORT_ASC,
                    ],
                ],

            ]);


        //На печать в EXCEL
        if ((int)Yii::$app->request->post('print') == 1) {

            //ddd($model);

            // Название склада
            $fill_name1 = $model->wh_destination_name;
            $fill_name2 = $model->wh_destination_element_name;
            $fill_name3 = $model->sklad_vid_oper_name;

            //
            return $this->render('print/print_excel', [
                'dataModels' => $array_model,
                'fill_name1' => $fill_name1,
                'fill_name2' => $fill_name2,
                'fill_name3' => $fill_name3,
            ]);

        }


        //dd(count($array_model));

        //ddd($model);


//         Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
//        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);
//         Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
//        $model['array_tk'] = $this->getTkNames($model['array_tk']);
//        ddd($model);

        //
        $spr_group = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');
        //
        $spr_things = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        //
        $list_wh_top = ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');
        $list_wh_element = ['....'];
        $list_wh_top[$model->wh_destination] = $model->wh_destination_name;
        $list_wh_element[$model->wh_destination_element] = $model->wh_destination_element_name;

        // ddd($model);

        return $this->render('_form_edit', [
            'id' => $id,
            'model' => $model,
            'provider' => $provider,
            'spr_group' => $spr_group,
            'spr_things' => $spr_things,
            'list_wh_top' => $list_wh_top,
            'list_wh_element' => $list_wh_element,
            'alert_mess' => $alert,
        ]);
    }


    /**
     * add_keys
     *=
     * @param $array
     * @return array
     */
    public static function count_by_SVOD($array)
    {
        $res = 0;
        foreach ($array as $item) {
            $res += $item['ed_izmer_num'];
        }
        return $res;
    }

    /**
     * add_keys
     *=
     * @param $array
     * @return array
     */
    public static function group_by_SVOD($array)
    {
        $array_res = [];

        ///   'wh_tk_amort' => 7
        //        'wh_tk_element' => 9
        //        'ed_izmer' => 1
        //        'ed_izmer_num' => 1
        //        'bar_code' => '210100039309'
        //        'intelligent' => 1
        //        'name_wh_tk_amort' => 'АСУОП'
        //        'name_wh_tk_element' => 'Стаб. VSP01'
        //        'name_ed_izmer' => 'шт.'
        //        't' => 1611298815
        //        'id' => 15
        //        'id_key' => 1

        foreach ($array as $key => $item) {

            if (!isset($array_res[$item['wh_tk_element']])) {

                $array_res[$item['wh_tk_element']] = [
                    'el' => $item['wh_tk_element'],
                    'ed_izmer_num' => $item['ed_izmer_num'],
                    'name_wh_tk_amort' => $item['name_wh_tk_amort'],
                    'name_wh_tk_element' => $item['name_wh_tk_element'],
                    'name_ed_izmer' => $item['name_ed_izmer'],
                ];

            } else {

                $array_res[$item['wh_tk_element']]['el'] = $item['wh_tk_element'];
                $array_res[$item['wh_tk_element']]['ed_izmer_num'] = $array_res[$item['wh_tk_element']]['ed_izmer_num'] + $item['ed_izmer_num'];

                //Номера накладных вписываем сюда
                if (isset($array_res[$item['wh_tk_element']]['nnn'])) {
                    foreach ($array_res[$item['wh_tk_element']]['nnn'] as $key_1 => $nnn) {
                        if ($array_res[$item['wh_tk_element']]['nnn'][$key_1] != $item['id']) {
                            $array_res[$item['wh_tk_element']]['nnn'][$key_1] = $item['id'];
                        }
                    }
                } else {
                    $array_res[$item['wh_tk_element']]['nnn'][] = $item['id'];
                }

            }

//            $array_res[$key + 1] = $item;
//            $array_res[$key + 1]['id_key'] = $key + 1;
        }
        ksort($array_res);

        //ddd($array_res);


        return $array_res;
    }


    /**
     * add_keys
     *=
     * @param $array
     * @return string
     */
    public static function add_keys($array)
    {
        foreach ($array as $key => $item) {
            $array_res[$key + 1] = $item;
            $array_res[$key + 1]['id_key'] = $key + 1;
        }
        return $array_res;
    }

    /**
     *
     *=
     * @param $parent_id
     * @return string
     */
    public static function actionListamort_select($parent_id)
    {
        return Html::dropDownList(
            'name_id',
            0,
            ArrayHelper::map(Spr_globam_element::find()
                ->where(['parent_id' => (int)$parent_id])
                ->all(), 'id', 'name'),
            ['prompt' => 'Выбор ...']
        );
    }


    /**
     * INDEX
     * =
     *
     * @return string
     */
    public
    function actionUpdate_tab()
    {
        $para = Yii::$app->request->queryParams;
//        $para_id = Yii::$app->request->get('id');
//        ddd($para_id);

        ///
        /// PARA PRINT ALL
        //$para_print = Yii::$app->request->get('print');
        $para_sort = Yii::$app->request->get('sort'); //
        $para_select = Yii::$app->request->get('select2_array_cs_numbers');//
        $postSklad_wh_invent = Yii::$app->request->get('postSklad_wh_invent');//


        ///
        if (!isset($para_print) || (int)$para_print == 0) {
            #PARA PRINT FILTER
            Sklad::setPrint_param($postSklad_wh_invent);
            #PARA SORT
            Sklad::setSort_param($para_sort);
            #PARA BETWEEN
            Sklad::setSelect_param($para_select);
        }

        ///
        $searchModel = new postsklad_inventory_wh();
        $dataProvider = $searchModel->search_byid($para);

        ddd($dataProvider->getModels());


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
            $filter_for_element = Sklad_wh_invent::ArrayUniq_wh_numbers_by_id($para['select2_array_destination']);
        } else {
            $filter_for_element = Sklad_wh_invent::ArrayUniq_wh_numbers(-1);
            //* Даты Инвентаризаций = all
            $filter_for_date = Sklad_wh_invent::ArrayUniq_dates(-1);
        }

        //GROUP
        $filter_for_group = Sklad_wh_invent::ArrayUniq_destination();

        //* Даты Инвентаризаций
        if (isset($para['select2_array_cs_numbers']) && !empty($para['select2_array_cs_numbers'])) {
            $filter_for_date = Sklad_wh_invent::ArrayUniq_dates($para['select2_array_cs_numbers']);
        } else {
            $filter_for_date = Sklad_wh_invent::ArrayUniq_dates(0);
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
     * Создаем новые накладные (Инвентаризации СКЛАДОВ)
     * =
     * ПО ВСЕМУ АВТОПАРКУ
     * -
     */
    public
    function actionCreate_new_wh()
    {
        $para_post = Yii::$app->request->post();
        //ddd($para_post);

        ///
        $model = new Sklad_wh_invent();
        if (!is_object($model)) {
            throw new HttpException(411, 'Склад ИНВЕНТАРИЗАЦИИ не работает', 2);
        }
        ////////
        $model->id = Sklad_wh_invent::setNext_max_id();
        $model->sklad_vid_oper = 1; // INVENTORY
        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
        $model->dt_create_timestamp = strtotime($model->dt_create);
        $model->dt_update = date('d.m.Y H:i:s', strtotime("now"));

        //
        $list_top = Sprwhtop::get_List_NON_FinalDestination();

        /// !!!!!!!!!!!
        ///
        /// Заливка ПО КОПИПАСТ ПЕ ПАРК
        /// "add_button_park"
        ///
        //////////////
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button_wh') {
            //
            if ($model->load(Yii::$app->request->post())) {

                //ddd($model);
                /// 'id' => 3
                //        'sklad_vid_oper' => 1
                //        'dt_create' => '22.01.2021 11:10:19'
                //        'dt_create_timestamp' => 1611292335
                //        'dt_update' => '22.01.2021 11:10:19'
                //        'wh_destination' => '41'
                //        'wh_destination_element' => '4165'


                //
                $array_full = Sprwhelement::findFullArray((int)$model->wh_destination_element);

                /**
                 * Шапку надстроим
                 */
//                $model_invenotry = new Sklad_wh_invent();
//                $model_invenotry->id = Sklad_wh_invent::setNext_max_id();
//                $model->dt_create_timestamp = $model->dt_create_timestamp;
//                $model->dt_create = $model->dt_create;

                //ddd($array_full);

                ///
                $model->dt_create_day = strtotime(date('d.m.Y 00:00:01', strtotime($model->dt_create)));
                $model->dt_create_timestamp = strtotime($model->dt_create);
                //
                $model->sklad_vid_oper = (int)1;
                $model->sklad_vid_oper_name = 'Закрытие по ' . date('d.m.Y', $model->dt_create_timestamp);
                $model->wh_home_number = (int)$model->wh_destination_element;
                $model->wh_destination_element = (int)$model->wh_destination_element;
                $model->wh_destination = $array_full['top']['id'];
                $model->wh_destination_name = $array_full['top']['name'];
                $model->wh_destination_element_name = $array_full['child']['name'];
                $model->user_id = (int)Yii::$app->getUser()->identity->id;
                $model->user_name = Yii::$app->getUser()->identity->username;

                //ddd($model);

                //
                $itog = [];

                //////////////////////
                /// Parser основного куска таблицы
                //////////////////////
                $array = explode("\r\n", trim($para_post['Sklad_wh_invent']['add_text_to_inventory_am1']));
                //dd($array);
                foreach ($array as $key => $item) {
                    $array_full = Barcode_pool::findFull_array($item);

                    $array_inventary[] = [
                        "wh_tk_amort" => (int)$array_full['spr_globam_element']['parent_id'],
                        "wh_tk_element" => (int)$array_full['spr_globam_element']['id'],
                        "ed_izmer" => 1,
                        "ed_izmer_num" => 1,
                        "bar_code" => $item,
                        "intelligent" => (int)$array_full['spr_globam_element']['intelligent'],

                        "name_wh_tk_amort" => 'АСУОП',
                        "name_wh_tk_element" => $array_full['spr_globam_element']['short_name'],
                        "name_ed_izmer" => 'шт.',

                        "t" => $model->dt_create_timestamp,
                        "id" => $model->id
                    ];

                }
                //ddd($itog);

                $model->array_tk_amort = $array_inventary;
                $array_inventary = [];
                //
                $model->count_str = count($array_inventary);

                //ddd($model);

                if (!$model->save(true)) {
                    ddd($model->errors);
                }


                ///
                return $this->render('_form_create_wh', [
                    'new_doc' => $model,
                    'list_top' => $list_top,
                    'alert_mess' => 'Заливка всех ЦС. Сохранение. Ок.',
                ]);

            }
            ///
        }


        return $this->render('_form_create_wh', [
            'new_doc' => $model,
            'list_top' => $list_top,
        ]);
    }

    /**
     * Создаем новую  накладную  (Инвентаризация)
     * -
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public
    function actionCreate_new()
    {
        //        $sklad = Sklad::getSkladIdActive();
        //        if (!isset($sklad) || empty($sklad))
        //            throw new UnauthorizedHttpException('Sklad=0');


        $model = new Sklad_wh_invent();

        if (!is_object($model)) {
            throw new NotFoundHttpException('Склад ИНВЕНТАРИЗАЦИИ-ЦС не работает');
        }

        ////////
        $model->id = (int)Sklad_wh_invent::setNext_max_id();

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
                return $this->redirect(['/Sklad_wh_invent/' . $adres_to_return]);
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
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     *-
     *
     * @param $array_tk
     *
     * @return mixed
     */
    public
    function getTkNames_am($array_tk)
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
    public
    function actionCopycard_from_origin($id)
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
    public
    function actionHtml_reserv_fond()
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
    public
    function actionHtml_pdf_green()
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
     * Из таблицы INDEX удаляет по одной строке
     * =
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public
    function actionDelete($id)
    {
        if (!Sklad_wh_invent::findModel($id)->delete()) {
            throw new NotFoundHttpException('Не смог удалить.');
        }

        return $this->redirect(['/sklad_inventory_wh/return_to_refer']);
    }

    /**
     * id = системный длинный Ид
     * -
     * @param $id
     * @return Sklad_inventory|null
     */
    protected
    function findModel($id)
    {
        return Sklad_inventory::findOne($id);
    }

    /**
     * По вызову Аякс находит
     * ПОДЧИНЕННЫЕ СКЛАДЫ
     *
     * @param int $id
     * @return string
     */
    public
    function actionList_element($id = 0)
    {
        $model = Html::dropDownList(
            'name_id', 0,
            ArrayHelper::map(
                Sprwhelement::find()
                    ->where([
                        'parent_id' => (integer)$id])
                    ->orderBy('name')
                    ->all(), 'id', 'name'
            ),
            [
                'prompt' => 'Выбор ...']
        );

        if (empty($model)) {
            return "Запрос вернул пустой массив";
        }

        return $model;

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
    public
    function actionList_ed_izm($id = 0)
    {
        $model = post_spr_glob_element::find()->where(['id' => (integer)$id])->one();

        return $model['ed_izm'];
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public
    function actionList_parent_id_amort($id = 0)
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
    public
    function actionList_parent_id($id = 0)
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
    public
    static function actionInventory_in_cs($model)
    {

        $model->id = Sklad_wh_invent::setNext_max_id();
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
    public
    function actionCreate_empty_invent()
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
    public
    function actionCreate_from_finded()
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
            $one_last_inventory_cs = Sklad_wh_invent::ArrayOne_Last_inventory_cs($id_cs_sklad);
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

        $array_list = ArrayHelper::getColumn(Sklad_wh_invent::find()
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
    protected
    static function Save_model($id_cs)
    {
        ///
        $model = Sklad_wh_invent::find()
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
    public
    function actionReturn_to_refer()
    {
        // Сбросить НАСТРОЙКИ ФИЛЬТРА
        Sklad::setUnivers('filter_bc', '');
        Sklad::setUnivers('filter_id', '');
        Sklad::setUnivers('filter_dt', '');

        //Возврат по рефералу REFER
        return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id, Yii::$app->request->url));
    }


    /**
     * Печатаем таблицу в Эксел
     *
     * @return string
     */
    public
    function actionExcel()
    {
        //$para = Yii::$app->request->queryParams;
        $para_print = Yii::$app->request->get('print');
        $para_id = Yii::$app->request->get('id');


        /// "wh_destination_name" : "Guidejet TI. Склад ремонта",
        //    "wh_destination_element_name" : "Милаяров Сухраб Рустамович",

        $model = Sklad_wh_invent::find()
            ->select([
                'wh_destination_name',
                'wh_destination_element_name',
                'array_tk_amort'
            ])
            ->where(['=', 'id', (int)$para_id])->one();

        $fill_name1 = $model->wh_destination_name;
        $fill_name2 = $model->wh_destination_element_name;

        $array = $model->array_tk_amort;
        // ddd($array);

        $svod = [];
        foreach ($array as $item) {
            //ddd($item);

            if (!isset($svod[$item['wh_tk_amort']] [$item['wh_tk_element']])) {
                $svod[$item['wh_tk_amort']] [$item['wh_tk_element']]['n'] = 0;
            }
            $svod[$item['wh_tk_amort']][$item['wh_tk_element']]['n'] = $svod[$item['wh_tk_amort']][$item['wh_tk_element']]['n'] + (int)$item['ed_izmer_num'];

            $svod[$item['wh_tk_amort']][$item['wh_tk_element']]['gr_name'] = $item['name_wh_tk_amort'];
            $svod[$item['wh_tk_amort']][$item['wh_tk_element']]['ch_name'] = $item['name_wh_tk_element'];
        }
        //ddd($svod);

        ///
        /// DATA Provider
        ///
        $dataProvider = new ArrayDataProvider([
            'allModels' => $array
        ]);


        // To EXCEL
        if (isset($para_print) && $para_print == 1) {
            $dataProvider->pagination = ['pageSize' => -1];

            $things = ArrayHelper::map(Spr_things::find()
                ->all(), 'id', 'name');

            $this->render('print/print_excel', [
                'dataModels' => $dataProvider->getModels(),
                'things' => $things,
                'fill_name1' => $fill_name1,
                'fill_name2' => $fill_name2,
                'svod' => $svod,
            ]);
        }
        return false;
    }


}
