<?php

namespace frontend\controllers;

use frontend\models\Cross;
use frontend\models\postcross;
use frontend\components\MyHelpers;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class CrossController
 * @package frontend\controllers
 */
class CrossController extends Controller
{
    /**
     * @throws NotFoundHttpException
     */
    public function init()
    {
        parent::init();

//        if (! Yii::$app->user->identity) {
//            throw new NotFoundHttpException('Необходима авторизация');
//        }
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
     * Lists all pv models.
     * @return mixed
     */

    public function actionIndex()
    {
            $para=Yii::$app->request->queryParams;

            $searchModel = new postcross();
            $dataProvider = $searchModel->search( $para );
            //dd($dataProvider);


//        dd($dataProvider);


        return $this->render('index', [
            'model' => $searchModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'para' => $para,

        ]);
    }


    /**
     * Displays a single pv model.
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
     * Creates a new pv model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Cross();

        $max_value = MyHelpers::Mongo_max_id( 'cross', 'id' );
        $max_value++;
        $model->id=$max_value;


        if ($model->load(Yii::$app->request->post())) {
            $model->id = (integer)$max_value;
            $model->dt_create = date("d.m.Y H:i:s", strtotime($model->dt_create));

            if ($model->save(true))
                return $this->redirect(['/cross']);
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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);



        if ($model->parent_id > 0){
            $original_text=Cross::find()
                ->where(['id'=>(integer) $model->parent_id] )
                ->one();
            //$original_text['html_text'];
        }


        if ($model->load(Yii::$app->request->post())) {
            $model->id = (integer)$model->id;

            if ($model->save(true))

                return $this->redirect('/cross');
        }

        return $this->render('update', [
             'model' => $model,
             'original_text' => $original_text['html_text'],

         ]);

    }




    /**
     * Deletes an existing pv model.
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
     * @param $id
     * @return Cross|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Cross::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}

