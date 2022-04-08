<?php

namespace frontend\controllers;

use frontend\models\Barcode_pool;
use frontend\models\post_rem_history;
use frontend\models\Rem_history;
use frontend\models\Sklad;
use Yii;
use yii\web\Controller;


class Rem_skladController extends Controller
{

    public $bar_code;

    public function actionIndex()
    {
        ///
        $this->id = Yii::$app->request->get('id');

        ///
        $model = Sklad::findModelDouble($this->id);

        if (!empty($model->array_tk_amort)) {
            $array_tk_amort = $model->array_tk_amort;
        } else {
            return $this->render('/rem_sklad/pred', [
                'array_tk_amort' => [],
                'array_by_array' => [],
            ]);

        }


        ///
        foreach ($array_tk_amort as $item) {
            $array_history[] = $item['bar_code'];
        }

        ///
        /// *  Поиск фактического Массива Штрихкодов  по Массиву штрихкодов на входе
        ///
        $array_by_array = Rem_history::findArray_by_Array($array_history);

        return $this->render('/rem_sklad/pred', [

            'array_tk_amort' => $array_tk_amort,
            //'array_history' => $array_history,
            'array_by_array' => $array_by_array,

            'sklad_id' => $this->id,
        ]);


    }

    public function actionIndex_tabl()
    {
        $bar_code = Yii::$app->request->get('id');

        if (isset($bar_code)) {
            $this->bar_code = $bar_code;
            //            ddd($this);
        }

        $searchModel = new post_rem_history();
        $dataProvider = $searchModel->search_for_filter($this->bar_code, Yii::$app->request->queryParams);

        return $this->render('/rem_sklad/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

            'bar_code' => $bar_code,
        ]);
    }


    /**
     * Lists all sprtype models.
     * @return mixed
     */
//    public function actionIndex2()
//    {
//        $searchModel = new post_rem_sklad();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
////        ddd($dataProvider->getModels());
//
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
////            'sort' => $sort,
//        ]);
//    }


    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        ///
        $sklad_id = Yii::$app->request->get('sklad_id');

        ///содержимое  массива  array_tk_amort (!)
        $sklad_model = Sklad::findArray_tk_amort($sklad_id);

//        ddd($sklad_id);

        // Поиск автопоиск
        $pool = Sklad::List_barcodes_barcodes_of_this($sklad_model);

//        ddd($pool);



        $model = new Rem_history();
        $model->id = Rem_history::setNext_max_id();


        ///
        /// LOAD
        ///
        if ($model->load(Yii::$app->request->post())) {
            $model->id = (int)$model->id;

            $model->user_name = Yii::$app->user->identity->username;
            $model->user_group = Yii::$app->user->identity->group_id;
            $model->user_id = Yii::$app->user->identity->id;
            $model->user_ip = $this->getUserIP();
            $model->dt_create_timestamp = strtotime('now');

            //ddd($model);
            if ($model->save()) {
                return $this->redirect(['/rem_sklad/index?id=' . $sklad_id]);
            }
        }


        return $this->render('create', [
            'model' => $model,
            'pool' => $pool,
        ]);

    }

    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate_for_barcode()
    {
        $bar_code = Yii::$app->request->get('id');

        ///
        $findFull_array = Barcode_pool::findFull_array($bar_code);

        ///
        $model = new Rem_history();
        $model->id = Rem_history::setNext_max_id();
        $model->bar_code = $bar_code;
        // $model->short_name = $findFull_array['spr_globam_element']['name'];
        $model->short_name = $findFull_array['spr_globam_element']['short_name'];


        if ($model->load(Yii::$app->request->post())) {
            $model->id = (int)$model->id;

            $model->user_name = Yii::$app->user->identity->username;
            $model->user_group = Yii::$app->user->identity->group_id;
            $model->user_id = Yii::$app->user->identity->id;
            // $model->user_ip = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $model->user_ip = $this->getUserIP();
            // $model->dt_create = date('d.m.Y H:i:s', strtotime('now '));
            $model->dt_create_timestamp = strtotime('now');

            //ddd($model);

            if ($model->save(true)) {
                return $this->redirect(['/rem_sklad/index_tabl?id=' . $bar_code]);
            }
        }


        return $this->render('create_by_barcode', [
            'model' => $model,
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
     * @return mixed
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');
        //ddd($id);

        ///
        $model = Rem_history::findModelDouble($id);
        //ddd($model);


        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)$model->id;

            //            'id' => 6
            //        'bar_code' => '040238'
            //        'short_name' => 'sdgsdfhgs eesra teart'
            //        'diagnoz' => 'ewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wert'
            //        'decision' => 'ewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wert'
            //        'list_details' => 'ewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wertewrtewrtwe ewr twert wert'
            //        'user_name' => 'rr'
            //        'user_group' => 10
            //        'user_id' => 22
            //        'user_ip' => '127.0.0.1'
            //        'dt_create_timestamp' => 1594960024

            if (!$model->save(true)) {
                ddd($model->errors);
            }
            return $this->redirect(['/rem_sklad/index_tabl?id=' . $model->bar_code]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


}
