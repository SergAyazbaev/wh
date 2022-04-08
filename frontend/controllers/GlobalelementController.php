<?php

namespace frontend\controllers;

use frontend\models\post_spr_glob_element;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_things;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class GlobalelementController extends Controller
{

    public $para;


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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all sprtype models.
     * @return mixed
     */
    public function actionIndex()
    {

        $para = Yii::$app->request->queryParams;


        $searchModel = new post_spr_glob_element();
        $dataProvider = $searchModel->search_with($para);    // С привязкой класса

        if (isset($para['print']) && $para['print'] == 1) {

            $dataProvider->pagination = ['pageSize' => -1];

            $this->render('print/print_excel', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,

                'dataModels' => $dataProvider->getModels()
            ]);

        }


        //ddd($para);


        if (!isset($para['sort'])) $para['sort'] = '';

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'para' => $para,
        ]);
    }


    /**
     * Новые номера из 1С. Заливка из Эксела. Коппаст.
     * =
     */
    public function actionApend()
    {
        $model = new Spr_glob_element();

        return $this->render('_apend', [
            'model' => $model,
        ]);
    }


    /**
     * Add Pull
     *
     * */
    public function actionAdd_pull()
    {
        $para_post = Yii::$app->request->post();


        if ($para_post['contact-button'] == 'add_new_pool') {
            $array = explode("\r\n", $para_post['Spr_glob_element']['new_pull']);
            //ddd($array);

            foreach ($array as $item) {
                $arr_number = explode("\t", $item);
                //    0 => '00000002014'
                //    1 => 'Бокорезы TU-7В'

                //
                // Ищем по названию в справочнике
                //
                $model_new = Spr_glob_element::find()
                    ->where(['==', 'name', $arr_number[1]])
                    ->one();

//                if (!isset($model_new) && isset($arr_number[1]) ) {
//                    $model_new = Spr_glob_element::find()
//                        ->where(['like', 'name', "%".$arr_number[1]."%"])
//                        ->one();
//                }

                if (!isset($model_new)) {
                    continue;
                }


                if (isset($arr_number[0]) && !empty($arr_number[0])) {
                    $model_new->cc_id = $arr_number[0];
                }


                if (!$model_new->save(true)) {
                    ddd($model_new->errors);
                }


            }

        }


        return $this->redirect(['/globalelement']);


    }


    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate()
    {
      //
      $id = Yii::$app->request->get('id');

      $model = Spr_glob_element::findModel($id);


        if ($model->load(Yii::$app->request->post())) {

            $model->id = (integer)$model->id;
            $model->parent_id = (integer)$model->parent_id;

            $model->ed_izm = (int)$model->ed_izm;

            $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
            $model->user_ip = Yii::$app->request->getUserIP();
            $model->user_id = Yii::$app->user->identity->id;
            $model->user_name = Yii::$app->user->identity->username;

//            ddd($model);

            if ($model->save())
                return $this->redirect(['/globalelement']);
        }

        return $this->render('update', [
            'model' => $model,
//            'spr_pd' => $spr_pd,
        ]);
    }


    /**
     * Печатаем таблицу в Эксел
     *
     * @return string
     */
    public function actionExcel()
    {
        $para = Yii::$app->request->queryParams;

        $searchModel = new post_spr_glob_element();
        $dataProvider = $searchModel->search_with_spr_glob($para);


        // To EXCEL
        if (isset($para['print']) && $para['print'] == 1) {

//                    ddd($para);


            $dataProvider->pagination = ['pageSize' => -1];

            //ddd($dataProvider->getModels());


            $things = ArrayHelper::map(Spr_things::find()
                ->all(), 'id', 'name');
            //ddd($things);


            $this->render('print/print_excel', [
                'dataModels' => $dataProvider->getModels(),
                'things' => $things
            ]);
        }
        return false;
    }


    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $max_value = Spr_glob_element::find()->max("id");
        $max_value++;

        $model = new Spr_glob_element();
        $model->id = $max_value;


        if ($model->load(Yii::$app->request->post())) {
            $model->id = (integer)$model->id;
            $model->parent_id = (integer)$model->parent_id;
            $model->ed_izm = (integer)$model->ed_izm;

            if ($model->save()) {
                return $this->redirect(['/globalelement/index?sort=-id']);
            }

        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * ПАКЕТНОЕ Добавление в справочник
     * Используется текстовый файл с разделителем таб {\r\n} или {\r\n\t}
     *
     * @return string|Response
     */
    public function actionCreate_all_from_txt()
    {
        $max_value = Spr_glob_element::find()->max("id");
        $max_value++;

        $model = new Spr_glob_element();
        $model->id = $max_value;

        if ($model->load(Yii::$app->request->post())) {

            //dd($model['all_from_txt']);
            //            if ($model['all_from_txt'])
            //                $array_pieces = explode("\r\n\t", $model['all_from_txt'] );

            if ($model['all_from_txt'])
                $array_pieces = explode("\r\n", $model['all_from_txt']);

            $res_parent_id = $model->parent_id;
            $res_ed_izm = $model->ed_izm;

//            dd($array_pieces);


            foreach ($array_pieces as $item) {

                if (!empty($item)) {

                    $model = new Spr_glob_element();

                    $model->id = (integer)$max_value;
                    $model->parent_id = (integer)$res_parent_id;
                    //$model->ed_izm  =(integer)$res_ed_izm;
                    $model->ed_izm = $res_ed_izm;

                    $model->name = $item;

                    $model->all_from_txt = '';
                    $model->tx = 'Доб. автоматически';

                    if (!$model->save(true)) {
                        dd($model->errors);
                    }

                    $max_value++;
                }

            }

            //  $model->id          = (integer) $model->id;
            //  $model->parent_id   = (integer) $model->parent_id;

            if ($model->save(true))
                return $this->redirect(['/globalelement/index?sort=-id']);

        }


        return $this->render('create_all_from_txt', [
            'model' => $model,
        ]);
    }


    /**
     * @param $id
     * @return Response
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        Spr_glob_element::findModel($id)->delete();
        return $this->redirect(['/globalelement/index?sort=-id']);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => Spr_glob_element::findModel($id),
        ]);
    }


}
