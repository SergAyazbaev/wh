<?php

namespace frontend\controllers;

use frontend\models\postsklad;
use frontend\models\postsprwhelement;
//use frontend\models\Spr_glob_element;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhelement_old;
use frontend\models\Sprwhtop;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;




class Park_csController extends Controller
{
    public $para=[];

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
                  'class'   => VerbFilter::className(),
                  'actions' => [
                      'index'   => ['GET'],
//                      'view'    => ['GET'],
                      'create'  => ['GET', 'POST'],
                      'update'  => ['GET', 'PUT', 'POST'],
                      'delete'  => ['POST', 'DELETE'],
                      'excel'  => ['POST'],
                      'create_all_from_txt'  => ['GET','POST'],
                      ],
                ],
            ];
    }


    /**
     * @return string
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;

        $searchModel = new postsklad();
        $dataProvider = $searchModel->search_cs( $para );

        //        ddd($dataProvider);
        //        ddd($searchModel);


        return $this->render( 'index',[
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'para' => $para,
                ]
        );

    }


    /**
     * Displays a single sprtype model.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the sprtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Sprwhelement|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sprwhelement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\db\StaleObjectException
     */
    public function actionCreate()
    {
        $max_value = Sprwhelement::find()->max('id');
        $max_value++;

        //        dd($max_value);

        $model = new Sprwhelement();
        $model->id = $max_value ;

        if ( $model->load( Yii::$app->request->post() )){

            $model->id          = (integer)$model->id;
            $model->parent_id   = (integer)$model->parent_id;

            $model->create_user_id  = Yii::$app->getUser()->identity->id ; // 'Id создателя',
            $model->date_create     = date('d.m.Y H:i:s', strtotime('now'));

            $model->delete_sign   = (integer)0; // Типа NO DEL

            // dd($model);

            if ( $model->save() )
                        return $this->redirect(['/sprwhelement']);
//            else
//                ddd($model->errors);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


//    /**
//     * FLAGS. Специально для Админов,
//     *=
//     * Жанель, Талагат
//     * -
//     * @param $id
//     * @return string|\yii\web\Response
//     * @throws NotFoundHttpException
//     */
//    public function actionFlags($id )
//    {
//        $model=Sprwhelement::findModel($id);
//
//
//        if ($model->load(Yii::$app->request->post())) {
//
//            $model->id = (int)$model->id;
//            $model->parent_id = (int)$model->parent_id;
//            $model->final_destination = (int)$model->final_destination;
//
//
//            //ddd($model);
//
//            if ($model->save(true))
//                return $this->redirect(['/sprwhelement']);
//
//
//        }
//
//
//        return $this->render('_form_flags',
//            ['model' => $model,]
//        );
//    }

    /**
     * РЕДАКТИРОВАНИЕ сопровождается отметками от пользователе-редакторе и дате редактирования
     *
     * Updates an existing sprtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ( $model->load( Yii::$app->request->post() )){

            $model->id = (int) $model->id;
            $model->parent_id = (int) $model->parent_id;
            $model->edit_user_id = Yii::$app->getUser()->identity->id ; // 'Id REDACTOR',
            $model->date_edit    = date('d.m.Y H:i:s', strtotime('now'));

            if ( $model->save(true) ){
                return $this->redirect(['/sprwhelement']);
            }
            else{
                ddd($model->errors);
            }
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete( )
    {
        $para=Yii::$app->request->get();

        $model=$this->findModel($para['id']);

        if ( $model ){
            //            $this->findModel($para['id'])->delete();
            if ( $model->delete_sign==1){
                $model->delete_sign =   0;
                $model->delete_sign_user_id = Yii::$app->getUser()->identity->id;
                $model->date_delete         = date('d.m.Y H:i:s', strtotime('now'));
            }
            else{
                $model->delete_sign =   1; // Типа УДАЛЯЕМ
                $model->delete_sign_user_id = Yii::$app->getUser()->identity->id; // Who Deleted This
                $model->date_delete         = date('d.m.Y H:i:s', strtotime('now'));
            }

            if (!$model->save(true)){
                dd($model->errors);
            }

        }

            // Возвтрат по РЕФЕРАЛУ
            $url_array=Yii::$app ->request->headers;
            $url =$url_array['referer'];
        return $this->goBack($url);
    }

    /**
     * Move TO History (ERASE)
     * Удаляем в КОРЗИНУ (в резервную базу. В историю )
     *
     */
    public function actionErase()
    {
        $para = Yii::$app->request->get();

        //AS ARRAY !!!!
        $model_element  =  $this->findModelInt( $para['id'] );

        $model_top  =  Sprwhtop::findModelDouble( $model_element->parent_id );

        //ddd($model_top);

        ///
        ///  Level ELEMENT to SAVE
        ///

            /// NEW
            $model_old_element  = new Sprwhelement_old();

            $model_old_element->id          = (int)$model_element['id'];
            $model_old_element->parent_id   = (int)$model_element['parent_id'];
            $model_old_element->name        = $model_element['name'];
            $model_old_element->tx          = $model_element['tx'];

            $model_old_element->parent_name     = $model_top->name;
            $model_old_element->parent_name_tx  = $model_top->tx;

            $model_old_element->nomer_borta        = $model_element['nomer_borta'];
            $model_old_element->nomer_gos_registr  = $model_element['nomer_gos_registr'];
            $model_old_element->nomer_vin          = $model_element['nomer_vin'];

            $model_old_element->date_delete         = $model_element['date_delete'];
            $model_old_element->delete_sign_user_id = $model_element['delete_sign_user_id'];


//            ddd($model_old_element);


            //if ($model_old_element->validate(true)) {
            if ( $model_old_element->save(true) ) {
//                ddd($model_old_element);
            }else{
                ddd($model_old_element->errors);
                //throw new NotFoundHttpException('Erase_WH_element.Не завершилось копирование в ИСТОРИЮ');
            }


        $model_element->delete();

        // Возвтрат по РЕФЕРАЛУ
        $url_array=Yii::$app ->request->headers;
        $url =$url_array['referer'];
        return $this->goBack($url);
    }

    /**
     * @param $id
     * @return array|null|\yii\mongodb\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModelInt($id)
    {
        if (($model = Sprwhelement::find()
                ->where(['id'=>(int)$id])
                ->one() ) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Печатаем таблицу в Эксел
     *
     * @return string
     */
    public function actionExcel()
    {
        //$para=Yii::$app->request->queryParams;
        //$para=Yii::$app->request->get();

        $para=Yii::$app->request->post();

        //ddd($para);

        $searchModel = new postsprwhelement();
        $dataProvider = $searchModel->search($para);


        // To EXCEL
        if ( isset($para['print']) && $para['print']==1)
        {
            //ddd($para);

            $dataProvider ->pagination = ['pageSize' =>-1];

            $dataProvider->setSort([
                'defaultOrder' => [
                    'parent_id'=>SORT_ASC,
                    'name'=>SORT_ASC
                ],
                ]);

            $spr_wh_top=ArrayHelper::map( Sprwhtop::find()->all(),'id','name');


            //  ddd($dataProvider->getModels());

            $this->render('print/print_excel', [
                'dataModels' => $dataProvider->getModels(),
                'spr_wh_top' => $spr_wh_top
            ]);
        }
        return false;
    }



    /**
     * @return mixed
     */
    protected function renderList()
    {
        $searchModel = new postsprwhelement();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $method = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$method('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }






}
