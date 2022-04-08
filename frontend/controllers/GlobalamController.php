<?php

namespace frontend\controllers;


use frontend\models\post_spr_globam;
use frontend\models\Spr_globam;
//use frontend\components\MyHelpers;
use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class GlobalamController extends Controller
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
        $searchModel = new post_spr_globam();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
//            'sort' => $sort,
        ]);
    }


    /**
     * Displays a single sprtype model.
     *
     * @param $id
     *
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
        $max_value = Spr_globam::find()->max('id');

        $max_value++;

        $model = new Spr_globam();
        $model->id=$max_value;


        if ($model->load(Yii::$app->request->post())) {
            $model->id = (integer)$max_value;

            if ($model->save())
                return $this->redirect(['/globalam']);
        }


        return $this->render('create', [
            'model' => $model,
        ]);
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
            $model->save( true );
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
}
