<?php

namespace frontend\controllers;

use frontend\models\post_spr_things;
use frontend\models\Spr_things;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;


class ThingsController extends Controller
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
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'delete' => ['POST'],
//                ],
//            ],
//        ];
//    }


    public function actionIndex()
    {

        $searchModel = new post_spr_things();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Spr_things::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCreate()
    {
        $max_value = Spr_things::find()->max('id');
        $max_value++;

        $model = new Spr_things();
        $model->id=$max_value;


        if (Yii::$app->user->identity->id > 0) {

            $model->user_id = Yii::$app->user->identity->id ;
            $model->user_name = Yii::$app->user->identity->username ;
        }
//        else return;

        if ($model->load(Yii::$app->request->post())) {

            $model->id = (integer)$max_value;

            if ($model->save())
                return $this->redirect(['/things']);

        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {
            $model->id = (integer)$model->id;
            $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));

            //ddd($model);

            if ($model->save())
                return $this->redirect(['/things']);

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

}
