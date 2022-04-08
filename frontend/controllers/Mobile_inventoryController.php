<?php
namespace frontend\controllers;

use frontend\models\Mobile_inventory;
use frontend\models\post_cs_past_inventory;
use frontend\models\post_mobile_inventory;
use frontend\models\Sklad;
use frontend\models\Spr_globam_element;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use Yii;
use yii\web\HttpException;


class Mobile_inventoryController extends MobileController
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
     * INDEX
     * =
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        $sklad = $this->_sklad; /// Sklad

        $user_name = Yii::$app->getUser()->identity->username_for_signature;

        $ap = $this->_ap; /// Ap
        $pe = $this->_pe; /// Pe

        $array_full = Sprwhelement::findFullArray($pe);

        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];
        $name_cs = $array_full['child']['cs'];

//        ddd($array_full);


        return $this->render('/mobile/index_close_day', [
            "user_name" => $user_name,

            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
            'name_cs' => $name_cs,

            'sklad' => $sklad,
            'array_sklad_list' => $array_sklad_list,
        ]);

    }



    /**
     * Вод номера AP
     * =
     * {@inheritdoc}
     */
    public function actionInit_table()
    {
        //    public  function Inventory_init_ap()

        $array_ap_list = Sprwhtop::Array_cs();  // * All CS
        $model = new Sprwhtop();


        ///
        /// Подставим Ид, если уже выбран
        ///
        $this->_ap = Sklad::getApIdActive();


        if (isset($this->_ap) || !empty($this->_ap)) {
            $model->id = (int)$this->_ap;
        }

        $this->_sklad = Sklad::getSkladIdActive();


        ///
        ///  LOAD ...
        ///
        if ($model->load(Yii::$app->request->post())) {


            if (Sklad::setApIdActive($model->id)) {
                $ap = Sklad::getApIdActive();
                $this->_ap = $ap;
            }

            if (Sklad::setPeIdActive(0)) {
//                $pe = 0;
                $this->_pe = 0;
            }


            //ddd($this);
            //ddd(2222);

            ///
            return $this->actionView_table();
        }

        ///
        ///  LOAD PARA
        ///
        if ($para = Yii::$app->request->queryParams) {
            //ddd($para);
            return $this->actionView_table($para);
        }

        return $this->render('init_ap_pe/index_ap', [
            'array_ap_list' => $array_ap_list,
            'model' => $model,
        ]);
    }


    /**
     * INIT TABLE
     * =
     * {@inheritdoc}
     */
    public function actionView_table()
    {
        $sklad = $this->_sklad;     /// Sklad

        $user_name = Yii::$app->getUser()->identity->username_for_signature;

        $ap = $this->_ap; /// Ap


        $array_full = Sprwhtop::findModelDouble($ap);
        $name_ap = $array_full['name'];

        ///
        ///
        $para = Yii::$app->request->queryParams;
        $searchModel = new post_cs_past_inventory();
        $dataProvider = $searchModel->search_for_mobile_tabl($ap, $para);


        //  ddd($dataProvider->getModels());


        return $this->render('/mobile_inventory/index', [
            "user_name" => $user_name,

            'ap' => $ap,
            'name_ap' => $name_ap,

            'sklad' => $sklad,

            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,


        ]);

    }

    /**
     * Create LOOK
     * =
     * {@inheritdoc}
     */
    public function actionLook_pe()
    {

        $sklad = $this->_sklad; /// Sklad

        $user_name = Yii::$app->getUser()->identity->username_for_signature;

        $pe = Yii::$app->request->get('pe');
        $array_pe = Sprwhelement::findFullArray($pe);

        //ddd($array_pe);


        $name_ap = $array_pe['top']['name'];
        $name_pe = $array_pe['child']['name'];
        $name_cs = $array_pe['child']['cs'];


        $model = new Mobile_inventory();

        /// AP - PE
        $model->id_ap = (int)$array_pe['top']['id'];
        $model->id_pe = (int)$array_pe['child']['id'];

        $model->bort = $array_pe['child']['nomer_borta'];
        $model->gos = $array_pe['child']['nomer_gos_registr'];
        $model->vin = $array_pe['child']['nomer_vin'];


        ///
        /// Пререносим параметры из масива с названием склада ЦС
        /// в активный класс
        ///
//        $this->_ap=$array_pe['top']['id'];
//        $this->_pe=$array_pe['child']['id'];
//        $this->_name_ap=$array_pe['top']['name'];
//        $this->_name_pe=$array_pe['child']['name'];

        //ddd($array_pe);
        //ddd($this);


        $model->dt_create = Date('d.m.Y H:i:s', strtotime('now'));
        $model->dt_create_timestamp = strtotime('now');
        $model->mts_id = Yii::$app->getUser()->identity->id; //  МТС. Мобильный Сотрудник


        ///
        /// LOAD
        ///
//        if ($model->load(Yii::$app->request->post())) {
//
//            ddd(1111);
//            //ddd($this);
//            //ddd($model);
//
//            if ($model->save(true)) {
//                return $this->redirect(['/mobile_inventory/init_table']);
//            } else {
//                ddd($model->errors);
//            }
//
//
//        }


        ///
        /// * Виртуальные остатки по ЦС
        $array_summary_from_cs = $this->Summary_from_cs($pe);

        //ddd($array_summary_from_cs);

        $array_menu = [];
        foreach ($array_summary_from_cs as $item) {

            $array_menu[$item['wh_tk_amort']][$item['wh_tk_element']]['bar_code'] = $item['bar_code'];

            ///* Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его ИД)
            if (isset($item['wh_tk_element']) && !empty($item['wh_tk_element'])) {
                $fullArray = Spr_globam_element::findFullArray($item['wh_tk_element']);
            }

            $array_menu[$item['wh_tk_amort']][$item['wh_tk_element']]['ed_izmer_num'] = $item['ed_izmer_num'];

            $array_menu[$item['wh_tk_amort']][$item['wh_tk_element']]['name'] = $fullArray['child']['name'];
            $array_menu[$item['wh_tk_amort']][$item['wh_tk_element']]['short_name'] = (isset($fullArray['child']['short_name']) ? $fullArray['child']['short_name'] : $fullArray['child']['name']);
        }

        //ddd($array_menu);

        //        'bar_code' => ''
        //            'ed_izmer_num' => 2
        //            'name' => 'Fastener for CVB24 (Крепление для CVB24)'
        //            'short_name' => 'Крепление CVB24'


        return $this->render('/mobile_inventory/update_one', [
            "user_name" => $user_name,

            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
            'name_cs' => $name_cs,

            'sklad' => $sklad,

            'array_menu' => $array_menu,
            'model' => $model,
        ]);

    }


    /**
     * Create LOOK
     * =
     * {@inheritdoc}
     */
    public function actionMove_to_vedomost()
    {
        $para = Yii::$app->request->post('Mobile_inventory');

        $dt_create = date('d.m.Y H:i:s', strtotime('now'));
        $dt_create_timestamp = strtotime('now');
        $mts_id = Yii::$app->getUser()->identity->id; //  МТС. Мобильный Сотрудник


        $array_barcode_box = $para['array_barcode_box'];
        $array_check_box = $para['array_check_box'];
        $array_check = $para['array_check'];


        //ddd($para);

        //  'array_barcode_box' => [
        //        7 => [
        //            13 => ''
        //            12 => ''
        //            2 => '19600003866'
        //            3 => '19600011895'
        //            9 => '210100021977'
        //            10 => ''
        //            14 => ''
        //            15 => ''
        //            5 => '1510081377'
        //        ]
        //    ]
        //    'array_check_box' => [
        //        7 => [
        //            13 => '3'
        //            12 => '1'
        //            2 => '1'
        //            3 => '1'
        //            9 => '1'
        //            10 => '1'
        //            14 => '1'
        //            15 => '1'
        //            5 => '1'
        //        ]
        //    ]
        //    'array_check' => [
        //        7 => [
        //            13 => '0'
        //            12 => '0'
        //            2 => '0'
        //            3 => '0'
        //            9 => '0'
        //            10 => '0'
        //            14 => '0'
        //            15 => '0'
        //            5 => '0'
        //        ]
        //    ]


        foreach ($array_check_box as $key2 => $array_check_box2) {
            foreach ($array_check_box2 as $key3 => $array_check_box3) {
                //                ddd($key2); // 7
                //                ddd($key3); // 13
                //                ddd($aray_check_box3); // 3

                ///
                $model = new Mobile_inventory();

                ///
                $model->id = (int)Mobile_inventory::setNext_max_id();
                $model->id_ap = (int)$para['id_ap'];//
                $model->id_pe = (int)$para['id_pe'];//
                $model->bort = $para['bort'];//
                $model->gos = $para['gos'];

                ///
                $model->check_bort = $para['check_bort'];
                $model->check_gos = $para['check_gos'];


                ///
                $model->thing_group = $key2;
                $model->thing_element = $key3;
                $model->thing_count = (int)$array_check_box[$key2][$key3];
                $model->thing_barcode = $array_barcode_box[$key2][$key3];
                $model->thing_check = $array_check[$key2][$key3];


                ///
                $model->dt_create = $dt_create;
                $model->dt_create_timestamp = $dt_create_timestamp;
                $model->mts_id = $mts_id; //  МТС. Мобильный Сотрудник


                ///SAVE
                if (!$model->save(true)) {
                    ddd($model->errors);
                }
            }
        }

        return $this->redirect("/mobile_inventory/init_table");
    }

    /**
     *  Big_table
     * =
     * {@inheritdoc}
     */
    public function actionBig_table()
    {
        $para = Yii::$app->request->queryParams;
        $searchModel = new post_mobile_inventory();
        $dataProvider = $searchModel->search($para);

        ///
        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_ASC]
        ]);



        ///ddd($dataProvider->getModels());


        return $this->render('/mobile_inventory/big_table', [
//            "user_name" => $user_name,
//            'ap' => $ap,
            'name_ap' => '',
//            'sklad' => $sklad,

            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,


        ]);

    }


    /**
     *  Edit_inventory
     * =
     */
    public function actionEdit_inventory()
    {
        ///
        $id = Yii::$app->request->get('id');

        if (!isset($id)) {
            $para = Yii::$app->request->post('Mobile_inventory');
            $id = $para['id'];
        }


        ///
        $model = Mobile_inventory::findOne($id);

        //ddd($model);


        ///
        /// LOAD
        ///
        if ($model->load(Yii::$app->request->queryParams)) {

            ddd($model);

            //ddd($this);
            //ddd($model);

            if ($model->save(true)) {
                return $this->redirect(['/mobile_inventory/init_table']);
            } else {
                ddd($model->errors);
            }
        }


        if (isset($model->thing_element)) {
            $findFullArray = Spr_globam_element::findFullArray($model->thing_element);
        }


        return $this->render('edit/update_one_str', [
            'name_ap' => '',

            'id' => $id,
            'model' => $model,
            'findFullArray' => $findFullArray,
        ]);
    }

    /**
     *  SAVE Edit_inventory
     * =
     */
    public function actionSave_edit_inventory()
    {


        $model = new Mobile_inventory();
        ///
        /// LOAD
        ///
        if ($model->load(Yii::$app->request->post())) {

            $model_new = Mobile_inventory::findModelDouble($model->id);
            $model_new->check_bort = $model->check_bort;
            $model_new->check_gos = $model->check_gos;
            $model_new->thing_count = (int)$model->thing_count;
            $model_new->thing_barcode = $model->thing_barcode;
            $model_new->thing_check = $model->thing_check;

            //        'mts_id' => null

            //ddd($model_new);
            if ($model_new->save(true)) {
                return $this->redirect(['/mobile_inventory/big_table']);
            } else {
                ddd($model_new->errors);
            }
        }

        return '';
    }


}