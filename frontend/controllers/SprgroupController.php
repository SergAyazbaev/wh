<?php

namespace frontend\controllers;

use frontend\models\post_spr_glob_element;
use frontend\models\Spr_glob;
use frontend\components\MyHelpers;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;


/**
 * Class SprgroupController
 * @package frontend\controllers
 */
class SprgroupController extends Controller
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


        $searchModel = new post_spr_glob_element();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


//dd($searchModel);
//dd($dataProvider);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
//            'sort' => $sort,
        ]);
    }

    /**
     * Displays a single sprtype model.
     * @param integer $_id
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
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Spr_glob();

        $max_value = MyHelpers::Mongo_max_id( 'spr_group_type', 'id' );
        $max_value++;
        $model->id=$max_value;


        if (Yii::$app->user->identity->id>0){
            //$model->user_id= Yii::$app->user->id ;
            //$model->name= Yii::$app->user->Username ;

            $model->user_id = Yii::$app->user->identity->id ;
            $model->user_name = Yii::$app->user->identity->username ;
        }
        else
            return false;


        if ($model->load(Yii::$app->request->post())) {
            $model->id = (integer)$max_value;

            if ($model->save(true))
                return $this->redirect(['/Spr_glob']);
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing sprtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->id = (integer)$model->id;

            if ($model->save(true))
                return $this->redirect(['/Spr_glob']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing sprtype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the sprtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Spr_glob|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Spr_glob::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
