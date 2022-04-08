<?php

namespace frontend\controllers;

use frontend\models\Tz;
use frontend\models\Tz_to_work;
use frontend\components\MyHelpers;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * PvController implements the CRUD actions for pv model.
 */
class Tz_to_workController extends Controller
{


    /**
     */
    public function init()
    {
        parent::init();

//        if ( !Yii::$app->getUser()->identity->group_id  )  {
//            throw new NotFoundHttpException('Необходима авторизация');
//        }
//
//        if ( Yii::$app->getUser()->identity->group_id <= 0  )  {
//            throw new NotFoundHttpException('Группа пользователей не определена');
//        }
    }

    /**
     * Displays a single pv model.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView( $id )
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Creates a new pv model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tz_to_work();

        $max_value = MyHelpers::Mongo_max_id( 'tz_to_work', 'id' );
        $max_value++;
        $model->id = $max_value;



        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {

            return $this->redirect(['/tz_to_work/update?id=' . $model->_id]);

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    /**
     * Updates an existing pv model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate( $id )
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {
            $model->id = (integer)$model->id;

            if ($model->save(true))
                return $this->redirect('/tz_to_work/?sort=dt_deadline');
        }


        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing pv model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException
     */
    public function actionDelete( $id )
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/tz_to_work/?sort=dt_deadline']);
    }

    /**
     * Finds the pv model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Tz_to_work|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id )
    {
        if (($model = Tz_to_work::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [  'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                //                    'delete' => ['POST', 'DELETE'],
                    'create' => ['POST', 'GET'],
                //                    'update' => ['POST', 'PUT', 'POST'],
//                    'update' => ['POST', 'GET', 'GET'],
                    'update' => ['POST', 'GET'],
                    ],
                ],
            ];
    }



    /**
     * Lists all pv models.
     * @return mixed
     */

//    public function actionIndex()
//    {
//
//        $para = Yii::$app->request->queryParams;
//
//
//        $searchModel = new posttz_to_work();
//        $dataProvider = $searchModel->search($para);
//
//
//
//        return $this->render('index', [
//
//            'model' => $searchModel,
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//            'para' => $para,
//        ]);
//    }


    /**
     * @return Response
     */
    public function actionSignal_to_work()
    {

        $para = Yii::$app->request->queryParams;

        $to_work=new Tz_to_work;
        $to_work->id = $para['id'];     //  id=6
        $to_work->tz_id = $para['id'];  //

        //dd($para);

        $to_work->status_state = Tz::STATE_IN_WORK;
        $to_work->user_create_id = Yii::$app->getUser()->identity->getId();
        $to_work->user_create_group_id = Yii::$app->getUser()->identity->group_id;
        $to_work->user_create_name = Yii::$app->getUser()->identity->username;

        ///////////

        $tz=Tz::find()
            ->where(['id'=> (integer)$para['id'] ] )
            ->one();


        $tz->status_state       = Tz::STATE_IN_WORK;
        $tz->status_create_user = Yii::$app->getUser()->identity->getId();
        $tz->status_create_date = Date(  "d.m.Y h:i:s",strtotime("now"));


        $tz->save(true);


        return $this->redirect(['/tz/?sort=-dt_create']);
    }


    /**
     * @return Response
     */
    public function actionSignal_to_return()
    {
        $para = Yii::$app->request->queryParams;

        $tz=Tz::find()
            ->where(['id'=> (integer)$para['id'] ] )
            ->one();

        $tz->status_return = Tz::STATE_TO_RETURN; // Вернуть на  БАЗУ
        $tz->status_return_create_user = Yii::$app->getUser()->identity->getId();
        $tz->status_return_create_date = Date(  "d.m.Y h:i:s",strtotime("now"));

        //dd($tz);

        if ( $tz->save(true)) {

            //return $this->redirect( ['/tz/?sort=dt_deadline'] );
            return $this->redirect( ['/tz'] );
        }

        return $this->redirect( ['/tz'] );
    }







}
