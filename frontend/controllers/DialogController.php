<?php

namespace frontend\controllers;


use frontend\models\Dialog;
use frontend\models\post_dialog;
use Yii;
use yii\web\Controller;

//use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;


class DialogController extends Controller
{
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

    /**
     * Lists all sprtype models.
     * @return mixed
     */
    public function actionIndex()
    {
        // ddd(Yii::$app->request->queryParams);

        $searchModel = new post_dialog();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $model = new Dialog();
        $model->id = Dialog::setNextMaxId();
        $model->creator_id = Yii::$app->user->identity->id;

        ///
        ///
        if ($model->load(Yii::$app->request->post())) {
            //ddd($model);
            $model->id = Dialog::setNextMaxId();

            if (!$model->save(true)) {
                ddd($model->errors);
            }

            return $this->redirect('index');
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

            if (!$model->save(true)) {
                ddd($model->errors);
            }

            return $this->redirect('index');

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
     * @return Dialog|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Dialog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
