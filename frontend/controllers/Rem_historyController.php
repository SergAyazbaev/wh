<?php

namespace frontend\controllers;

use common\models\User;
use frontend\models\Barcode_pool;
use frontend\models\post_rem_history;
use frontend\models\Rem_history;
use frontend\models\Sklad;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Sprwhelement;
use Yii;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;


/**
 *
 */
class Rem_historyController extends Controller
{
    /**
     * $session
     */
    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $session->open();

        if (!Yii::$app->getUser()->identity) {
            /// Быстрая переадресация
            throw new HttpException(411, 'Необходима авторизация', 2);
        }

    }


    /**
     * Lists all sprtype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;
        //ddd($para);


        ///
        /// PARA PRINT ALL
        ///
        $para_print = Yii::$app->request->get('print');
        $para_sort = Yii::$app->request->get('sort');
        $para_between = Yii::$app->request->get('dt_between');

        $post_rem_history = Yii::$app->request->get('post_rem_history');

        //С кнопки сброс фильтра
        $bar_code = Yii::$app->request->get('bar_code');
        if (isset($bar_code) && !empty($bar_code)) {
            $para['post_rem_history']['bar_code'] = $bar_code;
            $post_rem_history['bar_code'] = $bar_code;
        }


        ///
        if (!isset($para_print) || (int)$para_print == 0) {

            #PARA PRINT FILTER
            Sklad::setPrint_param($post_rem_history);

            #PARA SORT
            Sklad::setSort_param($para_sort);

            #PARA BETWEEN
            Sklad::setBetween_param($para_between);
        }


        ///
        ///
        $searchModel = new post_rem_history();


        ///
        ///   PRINT = 1
        ///
        // To EXCEL
        if ((int)$para_print == 1) {

            $post_rem_history['post_rem_history'] = Sklad::getPrint_param();
            $post_rem_history['sort'] = Sklad::getSort_param();
            $post_rem_history['dt_between'] = Sklad::getBetween_param();

            ///
            //$dataProvider = $searchModel->search($post_rem_history);
            $dataProvider = $searchModel->search_with_color($post_rem_history);
            $dataProvider->pagination = ['pageSize' => -1];
//            $dataProvider->sort = ['defaultOrder' => ['id' => SORT_ASC]];


            ///
            $spr_list1 = Sprwhelement::findAll_Elements_Parent(1); //Склад сервисной службы
            $spr_list2 = Sprwhelement::findAll_Elements_Parent(2); //Склад эксплуатации
            $spr_list = $spr_list1 + $spr_list2;
            asort($spr_list);

            /// USER
            $user = ArrayHelper::map(User::find()->all(), 'id', 'username_for_signature');

            //ddd($dataProvider->getModels());

            $this->render(
                'print/print_excel', [
                'dataProvider' => $dataProvider,
                'dataModels' => $dataProvider->getModels(),
                'user' => $user,
                'spr_list' => $spr_list,
            ]);
        }
        ///
        ///


        ///
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->search_with_color($para);
        //ddd($para);


        ///
        $dataProvider->setSort(
            [
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC]
                    ],
                    'user_name',
                    'bar_code' => [
                        'asc' => ['bar_code' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['bar_code' => SORT_DESC, 'id' => SORT_DESC]
                    ],
                    'short_name',
                    'diagnoz' => [
                        'asc' => ['diagnoz' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['diagnoz' => SORT_DESC, 'id' => SORT_DESC]
                    ],
                    'decision' => [
                        'asc' => ['decision' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['decision' => SORT_DESC, 'id' => SORT_DESC]
                    ],
                    'list_details',
                    'dt_rem_timestamp',
                    'rem_user_name',
                    'mts_user_id',
                    'mts_user_name',

                    'num_busline',
                ],

                'defaultOrder' => ['id' => SORT_DESC]
            ]);


        //ddd($dataProvider->getModels());

        //ddd($para['dt_between']);

        //
        $filter_cvb = Rem_history::ArrayUniq_cvb();

        //
        $rem_master = Rem_history::ArrayUniq_rem_name();
        $rem_mts = Rem_history::ArrayUniq_rem_mtsname();

//        ddd($rem_master);

//        ddd($dataProvider->getModels());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

            'rem_master' => $rem_master,
            'rem_mts' => $rem_mts,

            'filter_cvb' => $filter_cvb,
        ]);
    }


    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Rem_history();
        $model->id = Rem_history::setNext_max_id();

        // Поиск автопоиск
        $pool = ArrayHelper::getColumn(
            Barcode_pool::find()
                ->select(['bar_code'])
                ->asArray()
                ->all(), 'bar_code');

        ///
        $pool_diagnoz = ArrayHelper::getColumn(Rem_history::find()
            ->select(['diagnoz'])->all(), 'diagnoz', 'diagnoz');
        $pool_diagnoz = array_unique($pool_diagnoz);
        sort($pool_diagnoz);


        ///
        $spr_list1 = Sprwhelement::findAll_Elements_Parent(1); //Склад сервисной службы
        $spr_list2 = Sprwhelement::findAll_Elements_Parent(2); //Склад эксплуатации
        $spr_list = [] + $spr_list1 + $spr_list2;
        asort($spr_list);


        ///
        /// LOAD
        ///
        if ($model->load(Yii::$app->request->post())) {

            $model->id = Rem_history::setNext_max_id();

            $model->user_name = Yii::$app->user->identity->username;
            $model->user_group = Yii::$app->user->identity->group_id;
            $model->user_id = Yii::$app->user->identity->id;
            $model->user_ip = $this->getUserIP();

            $model->decision = '';
            $model->list_details = '';

            $model->dt_create_timestamp = strtotime('now');

            if (isset($spr_list[$model->mts_user_id]) && !empty($spr_list[$model->mts_user_id])) {
                $model->mts_user_name = $spr_list[$model->mts_user_id];
            }

//            ddd($model);

            ///
            ///
            if ($model->save(true))
                // ddd($model);

                return $this->redirect(['/rem_history']);
        }

        return $this->render('create', [
            'model' => $model,

            'pool' => $pool,
            'pool_diagnoz' => $pool_diagnoz,

            'spr_list' => $spr_list,
        ]);
    }


    function getUserIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }


    /**
     * Updates an existing sprtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {


        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)$model->id;
            $model->delete = (int)$model->delete;

            //ddd($model);


            //             if(
            $model->save(true);
            //             ){
            //                 //ddd($model);
            //             }

            return $this->redirect(['/globalam']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


        /**
         *  Основное исправление РЕМОНТНИКАМИ
         */
        public function actionNew_by_barcode()
        {
            $bar_code = Yii::$app->request->get('bar_code');

            ///
            $model = Rem_history::findOne($bar_code);


            ///
            ///  Справочная информация на основе соего же поля
            $spr_decision_all = Rem_history::findDecision_all(); //findDecision_all
            //        ddd($spr_decision_all);

            ///Возвращает массив с БОЛЕЕ подробными неисправностями
            $spr_decision_ids = Rem_history::ArrayTranslator($spr_decision_all);
            $spr_decision_all = [];


            sort($spr_decision_ids, SORT_ASC);

            // Первая буква. Сделать Заглавной
            foreach ($spr_decision_ids as $item) {
                //            $item_a = mb_convert_case($item, MB_CASE_TITLE, 'UTF-8');
                $item_a = mb_convert_case($item, MB_CASE_UPPER, 'UTF-8');
                $spr_decision_all[$item_a] = $item_a;
            }
            //ddd($spr_decision_all);


            // Двойники удаляет из массива
            $spr_decision_all = array_unique($spr_decision_all);
            //ddd($spr_decision_all);

            ///
            $model->array_decision = explode('. ', $model->decision);
            //        ddd($model);


            ///
            /// LOAD
            ///
            if ($model->load(Yii::$app->request->post())) {

                $model->id = (int)$model->id;


                $model->rem_user_name = Yii::$app->user->identity->username;
                $model->rem_user_group = Yii::$app->user->identity->group_id;
                $model->rem_user_id = Yii::$app->user->identity->id;
                $model->rem_user_ip = $this->getUserIP();

                $model->dt_rem_timestamp = strtotime('now');


                ///
                if (is_array($model->array_decision)) {
                    $model->decision = implode('. ', array_filter($model->array_decision));
                }
                if (empty($model->array_decision)) {
                    $model->decision = '';
                }


                //ddd($model);


                if (!$model->save(true)) {
                    ddd($model);
                }
                return $this->redirect(['/rem_history/index']);
            }




          /// Если это АДМИН или Саша
          if( Yii::$app->user->identity->id == 10000 ||
              Yii::$app->user->identity->id == 10012  ){

                // $model->user_group = Yii::$app->user->identity->group_id;
                // $model->user_id = Yii::$app->user->identity->id;
//ddd(1112341);
            //// By SASHA admin
            return $this->render('update_by_admin.php', [
                'model' => $model,
                'spr_decision_all' => $spr_decision_all, // Справочная информация на основе соего же поля
            ]);
          }else{
            //ddd(1112341);

            return $this->render('update_by_barcode', [
                'model' => $model,
                'spr_decision_all' => $spr_decision_all, // Справочная информация на основе соего же поля
            ]);
          }

        }




    /**
     *  Вснесение МТС. Кто снимал оборудование из автобуса
     */
    public function actionMts_by_barcode()
    {
        ///
        $model = Rem_history::findOne(Yii::$app->request->get('bar_code'));


        ///
        $spr_list1 = Sprwhelement::findAll_Elements_Parent(1); //Склад сервисной службы
        $spr_list2 = Sprwhelement::findAll_Elements_Parent(2); //Склад эксплуатации
        $spr_list = $spr_list1 + $spr_list2;
        asort($spr_list);

        ///
        /// LOAD
        ///
        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)$model->id;

            $model->rem_user_name = '';
            $model->rem_user_group = '';
            $model->rem_user_id = '';
            $model->rem_user_ip = '';
            $model->dt_rem_timestamp = '';

            $model->mts_user_id = (int)$model->mts_user_id;
            $model->mts_user_name = $spr_list[(int)$model->mts_user_id];

            //ddd($model);


            if (!$model->save(true)) {
                ddd($model);
            }
            return $this->redirect(['/rem_history/index']);
        }

        return $this->render('update_mts_by_barcode', [
            'model' => $model,
            'spr_list' => $spr_list,
        ]);
    }


    /**
     *  MTC. Статистика
     */
    public function actionStat()
    {

        ///
        $text_days_array = [


            date('d.m.Y', strtotime('now -100 days')),

            date('d.m.Y', strtotime('now -99 days')),
            date('d.m.Y', strtotime('now -98 days')),
            date('d.m.Y', strtotime('now -97 days')),
            date('d.m.Y', strtotime('now -96 days')),
            date('d.m.Y', strtotime('now -95 days')),
            date('d.m.Y', strtotime('now -94 days')),
            date('d.m.Y', strtotime('now -93 days')),
            date('d.m.Y', strtotime('now -92 days')),
            date('d.m.Y', strtotime('now -91 days')),
            date('d.m.Y', strtotime('now -90 days')),

            date('d.m.Y', strtotime('now -89 days')),
            date('d.m.Y', strtotime('now -88 days')),
            date('d.m.Y', strtotime('now -87 days')),
            date('d.m.Y', strtotime('now -86 days')),
            date('d.m.Y', strtotime('now -85 days')),
            date('d.m.Y', strtotime('now -84 days')),
            date('d.m.Y', strtotime('now -83 days')),
            date('d.m.Y', strtotime('now -82 days')),
            date('d.m.Y', strtotime('now -81 days')),
            date('d.m.Y', strtotime('now -80 days')),

            date('d.m.Y', strtotime('now -79 days')),
            date('d.m.Y', strtotime('now -78 days')),
            date('d.m.Y', strtotime('now -77 days')),
            date('d.m.Y', strtotime('now -76 days')),
            date('d.m.Y', strtotime('now -75 days')),
            date('d.m.Y', strtotime('now -74 days')),
            date('d.m.Y', strtotime('now -73 days')),
            date('d.m.Y', strtotime('now -72 days')),
            date('d.m.Y', strtotime('now -71 days')),
            date('d.m.Y', strtotime('now -70 days')),

            date('d.m.Y', strtotime('now -69 days')),
            date('d.m.Y', strtotime('now -68 days')),
            date('d.m.Y', strtotime('now -67 days')),
            date('d.m.Y', strtotime('now -66 days')),
            date('d.m.Y', strtotime('now -65 days')),
            date('d.m.Y', strtotime('now -64 days')),
            date('d.m.Y', strtotime('now -63 days')),
            date('d.m.Y', strtotime('now -62 days')),
            date('d.m.Y', strtotime('now -61 days')),
            date('d.m.Y', strtotime('now -60 days')),

            date('d.m.Y', strtotime('now -59 days')),
            date('d.m.Y', strtotime('now -58 days')),
            date('d.m.Y', strtotime('now -57 days')),
            date('d.m.Y', strtotime('now -56 days')),
            date('d.m.Y', strtotime('now -55 days')),
            date('d.m.Y', strtotime('now -54 days')),
            date('d.m.Y', strtotime('now -53 days')),
            date('d.m.Y', strtotime('now -52 days')),
            date('d.m.Y', strtotime('now -51 days')),
            date('d.m.Y', strtotime('now -50 days')),

            date('d.m.Y', strtotime('now -49 days')),
            date('d.m.Y', strtotime('now -48 days')),
            date('d.m.Y', strtotime('now -47 days')),
            date('d.m.Y', strtotime('now -46 days')),
            date('d.m.Y', strtotime('now -45 days')),
            date('d.m.Y', strtotime('now -44 days')),
            date('d.m.Y', strtotime('now -43 days')),
            date('d.m.Y', strtotime('now -42 days')),
            date('d.m.Y', strtotime('now -41 days')),
            date('d.m.Y', strtotime('now -40 days')),

            date('d.m.Y', strtotime('now -39 days')),
            date('d.m.Y', strtotime('now -38 days')),
            date('d.m.Y', strtotime('now -37 days')),
            date('d.m.Y', strtotime('now -36 days')),
            date('d.m.Y', strtotime('now -35 days')),
            date('d.m.Y', strtotime('now -34 days')),
            date('d.m.Y', strtotime('now -33 days')),
            date('d.m.Y', strtotime('now -32 days')),
            date('d.m.Y', strtotime('now -31 days')),

            date('d.m.Y', strtotime('now -30 days')),
            date('d.m.Y', strtotime('now -29 days')),
            date('d.m.Y', strtotime('now -28 days')),
            date('d.m.Y', strtotime('now -27 days')),
            date('d.m.Y', strtotime('now -26 days')),
            date('d.m.Y', strtotime('now -25 days')),
            date('d.m.Y', strtotime('now -24 days')),
            date('d.m.Y', strtotime('now -23 days')),
            date('d.m.Y', strtotime('now -22 days')),
            date('d.m.Y', strtotime('now -21 days')),
            date('d.m.Y', strtotime('now -20 days')),

            date('d.m.Y', strtotime('now -20 days')),
            date('d.m.Y', strtotime('now -19 days')),
            date('d.m.Y', strtotime('now -18 days')),
            date('d.m.Y', strtotime('now -17 days')),
            date('d.m.Y', strtotime('now -16 days')),
            date('d.m.Y', strtotime('now -15 days')),

            date('d.m.Y', strtotime('now -14 days')),
            date('d.m.Y', strtotime('now -13 days')),
            date('d.m.Y', strtotime('now -12 days')),
            date('d.m.Y', strtotime('now -11 days')),
            date('d.m.Y', strtotime('now -10 days')),
            date('d.m.Y', strtotime('now -9 days')),
            date('d.m.Y', strtotime('now -8 days')),
            date('d.m.Y', strtotime('now -7 days')),

            date('d.m.Y', strtotime('now -6 days')),
            date('d.m.Y', strtotime('now -5 days')),
            date('d.m.Y', strtotime('now -4 days')),
            date('d.m.Y', strtotime('now -3 days')),
            date('d.m.Y', strtotime('now -2 days')),
            date('d.m.Y', strtotime('now -1 day')),
            date('d.m.Y', strtotime('now'))
        ];


        ///
        $uniq_arr_mts = Rem_history::ArrayUniq_mts_user_id('now'); // OK
        $spr_list = Sprwhelement::findAll_username_by_ids($uniq_arr_mts); //Склад сервисной службы

//        ddd($spr_list);
//        ddd($uniq_arr_mts); //5114

        $series_array = [];

        foreach ($uniq_arr_mts as $item) {
            foreach ($text_days_array as $key_day => $item_day) {

                $arr[$key_day] = Rem_history::countMts_by_days($item, $item_day);

                //$series_array_22 [] = ($arr[$key_day] != 0 ? $arr[$key_day] : 0.1);
                $series_array_22 [] = $arr[$key_day];

                unset($arr);
            }

            if (isset($spr_list[$item])) {
                $series_array [] =
                    [
                        'name' => '' . (isset($spr_list[$item]) ? $spr_list[$item] : '111'),
                        'data' => $series_array_22
                    ];
            }
            unset($series_array_22);
        }

//        ddd($series_array);


        return $this->render('stat/index', [
            'series_array' => $series_array,
            'text_days_array' => $text_days_array
        ]);
    }


    /**
     *  REM. Статистика
     */
    public function actionStat_rem()
    {
        ///
        $text_days_array = [

            date('d.m.Y', strtotime('now -30 days')),
            date('d.m.Y', strtotime('now -29 days')),
            date('d.m.Y', strtotime('now -28 days')),
            date('d.m.Y', strtotime('now -27 days')),
            date('d.m.Y', strtotime('now -26 days')),
            date('d.m.Y', strtotime('now -25 days')),
            date('d.m.Y', strtotime('now -24 days')),
            date('d.m.Y', strtotime('now -23 days')),
            date('d.m.Y', strtotime('now -22 days')),
            date('d.m.Y', strtotime('now -21 days')),
            date('d.m.Y', strtotime('now -20 days')),
            date('d.m.Y', strtotime('now -19 days')),
            date('d.m.Y', strtotime('now -18 days')),
            date('d.m.Y', strtotime('now -17 days')),
            date('d.m.Y', strtotime('now -16 days')),
            date('d.m.Y', strtotime('now -15 days')),

            date('d.m.Y', strtotime('now -14 days')),
            date('d.m.Y', strtotime('now -13 days')),
            date('d.m.Y', strtotime('now -12 days')),
            date('d.m.Y', strtotime('now -11 days')),
            date('d.m.Y', strtotime('now -10 days')),
            date('d.m.Y', strtotime('now -9 days')),
            date('d.m.Y', strtotime('now -8 days')),
            date('d.m.Y', strtotime('now -7 days')),

            date('d.m.Y', strtotime('now -6 days')),
            date('d.m.Y', strtotime('now -5 days')),
            date('d.m.Y', strtotime('now -4 days')),
            date('d.m.Y', strtotime('now -3 days')),
            date('d.m.Y', strtotime('now -2 days')),
            date('d.m.Y', strtotime('now -1 day')),
            date('d.m.Y', strtotime('now'))
        ];


        ///
        $uniq_arr_mts = Rem_history::ArrayUniq_rem('now'); // OK
        $spr_list = User::findAll_username_by_ids($uniq_arr_mts); //Users
        asort($spr_list);
        //ddd($spr_list);


        $series_array = [];
        foreach ($uniq_arr_mts as $item) {
            foreach ($text_days_array as $key_day => $item_day) {

                $arr[$key_day] = Rem_history::countRem_by_days($item, $item_day);

                //$series_array_22 [] = ($arr[$key_day] != 0 ? $arr[$key_day] : 0.1);
                $series_array_22 [] = $arr[$key_day];

                unset($arr);
            }

            if (isset($spr_list[$item])) {
                $series_array [] =
                    [
                        'name' => '' . (isset($spr_list[$item]) ? $spr_list[$item] : '_???_'),
                        'data' => $series_array_22
                    ];
            }
            unset($series_array_22);
        }

//        ddd($series_array);


        return $this->render('stat/index_rem', [
            'series_array' => $series_array,
            'text_days_array' => $text_days_array
        ]);
    }


    /**
     *  REM. Статистика. Неисправности (%) ЗА НЕДЕЛЮ
     */
    public function actionStat_decision()
    {

        ///
        //        $decision_all = Rem_history::countDecision_all('now');  // 100%  - 36

        $uniq_arr = Rem_history::ArrayUniq_decision('now');

        ///Возвращает массив с БОЛЕЕ подробными неисправностями
        $uniq_arr = Rem_history::ArrayTranslator($uniq_arr);

        $arr2 = [];
        ///
        foreach ($uniq_arr as $key_id => $item_id) {
            $item_a = mb_convert_case($item_id, MB_CASE_UPPER, 'UTF-8');
            $arr2[$item_a] = Rem_history::countDecision_by_six_days($item_a, 'now');
        }

        //$item_a=mb_convert_case($item, MB_CASE_UPPER, 'UTF-8');
        //ddd($arr2);

        ///
        asort($arr2);

        $data = [];
        ///
        foreach ($arr2 as $key_name => $item) {
            $data [] =
                [
                    'name' => '' . $key_name . ' - ' . $item . '',
                    'y' => $item
                ];
        }
        //ddd($arr2);


        return $this->render('stat/index_decision', [
            'data' => $data
        ]);
    }


    /**
     *  REM.Круговая диаграмма. Статистика. Неисправности (%) ЗА МЕСЯЦ
     */
    public function actionStat_decision_month()
    {

        ///
        //        $decision_all = Rem_history::countDecision_all('now');  // 100%  - 36

        $uniq_arr = Rem_history::ArrayUniq_decision('now ', ' -90 days');


        ///Возвращает массив с БОЛЕЕ подробными неисправностями
        $uniq_arr = Rem_history::ArrayTranslator($uniq_arr);


        $arr2 = [];
        ///
        foreach ($uniq_arr as $key_id => $item_id) {
            $item_a = mb_convert_case($item_id, MB_CASE_UPPER, 'UTF-8');
            $arr2[$item_a] = Rem_history::countDecision_by_six_days($item_a, ' -90 days');
        }

        ///
        asort($arr2);

        $data = [];
        ///
        foreach ($arr2 as $key_name => $item) {
            $data [] =
                [
                    'name' => '' . $key_name . ' - ' . $item . '',
                    'y' => $item
                ];
        }

//        ddd($data);
//        ddd($arr2);


        return $this->render('stat/index_decision_month', [
            'data' => $data
        ]);
    }


    /**
     *  REM. Статистика. Неисправности (%) ЗА МЕСЯЦ
     */
    public function actionStat_decision_septemb_oktob_novemb()
    {

        $dt_start = date('d.m.Y ', strtotime('01.10.2020 00:00:01 -1 month'));
        $dt_stop = date('d.m.Y ', strtotime('01.10.2020 00:00:01'));

        //last day of -2 month 23:59:59 '
        $uniq_arr = Rem_history::ArrayUniq_decision('01.10.2020  00:00:01 ', ' -1 month');


        ///Возвращает массив с БОЛЕЕ подробными неисправностями
        $uniq_arr = Rem_history::ArrayTranslator($uniq_arr);

        $arr2 = [];
        ///
        foreach ($uniq_arr as $key_id => $item_id) {
            $item_a = mb_convert_case($item_id, MB_CASE_UPPER, 'UTF-8');
            $arr2[$item_a] = Rem_history::countDecision_by_six_days($item_a, '01.10.2020  00:00:01 ', ' -1 month');
        }

        ///
        asort($arr2);

        $data = [];
        ///
        foreach ($arr2 as $key_name => $item) {
            $data [] =
                [
                    'name' => '' . $key_name . ' - ' . $item . '',
                    'y' => $item
                ];
        }
        //ddd($arr2);


        return $this->render('stat/index_decision_period', [
            'data' => $data,
            'dt_start' => $dt_start,
            'dt_stop' => $dt_stop,
        ]);
    }


    /**
     *  REM. Замена неверных фраз
     */
    public function actionReSaveErrors()
    {

        ///
        ///  Справочная информация на основе соего же поля
        $spr_decision_all = Rem_history::findDecision_all(); //findDecision_all
        //ddd($spr_decision_all);

        ///Возвращает массив с БОЛЕЕ подробными неисправностями
        $spr_decision_ids = Rem_history::ArrayTranslator($spr_decision_all);

        unset($spr_decision_all);

        sort($spr_decision_ids, SORT_LOCALE_STRING);

        foreach ($spr_decision_ids as $item) {
            $spr_decision_all[$item] = $item;
        }


        $model = new Rem_history();

        ///
        /// LOAD
        ///
        if ($model->load(Yii::$app->request->post())) {

            /// ЧТО
            $array_decision = ($model['array_decision']);         //ddd($array_decision[0]); //    'SIM карта'

            /// НА ЧТО
            $array_target_decision = $model->decision;            //ddd($array_target_decision[0]); // 'Замена SIM'


            $array_resave = ArrayHelper::map(Rem_history::find()
                ->where(['like', 'decision', $array_decision[0]])
                ->all(), 'id', 'decision');

            // ddd($array_resave);

            foreach ($array_resave as $key => $item) {

                $model_resave = Rem_history::find()
                    ->where(['=', 'id', $key])
                    ->one();

                //ddd($model_resave);

                ///
                $arr_decision[] = $model_resave->decision;
                $array_result = str_replace($array_decision[0], $array_target_decision[0], $arr_decision);
                $model_resave->decision = $array_result[0];
                ///
                if (!$model_resave->save(true)) {
                    ddd($model_resave->errors);
                }
            }

            return $this->redirect('/rem_history/re-save-errors');


        }


        return $this->render('errors/index', [
            'model' => $model,
            'spr_decision_all' => $spr_decision_all
        ]);
    }


    /**
     * Finds the sprtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Spr_globam|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = Spr_globam::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * Deletes an existing sprtype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * По вызову Аякс находит
     * -
     * PULL BARCODES => Name Elements
     *=
     *
     * @param $bar_code
     * @return array
     */
    public function actionList_element_from_barcode($bar_code)
    {
        $id = $this->find_Name_from_pool_barcodes($bar_code);

        $model = Spr_globam_element::find()->where([
            'id' => (int)$id
        ])->one();

        return $model['short_name'];
    }

    ///
    /// POOL BARCODES
    ///
    public function find_Name_from_pool_barcodes($bar_code)
    {
        $model = Barcode_pool::find()
            ->where([
                'bar_code' => (string)$bar_code])
            ->asArray()
            ->one();

        if (isset($model['element_id']) && !empty($model['element_id'])) {
            return $model['element_id'];
        }

        return 0;
    }


}
