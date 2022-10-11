<?php

namespace frontend\controllers;

use frontend\models\post_spr_globam_element;
use frontend\models\Sklad;
use frontend\models\Spr_globam_element;
use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class GlobalamelementController extends Controller
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
        $para = Yii::$app->request->queryParams;

        $searchModel = new post_spr_globam_element();
        $dataProvider = $searchModel->search($para);

//        ddd($dataProvider->getModels());

        //Запомнить РЕФЕР
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * ВОЗВРАТ ПО РЕФЕРАЛУ
     */
    public function actionReturn_to_refer()
    {
        //Возврат по рефералу REFER
        return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id, Yii::$app->request->url));
    }


    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $max_value = Spr_globam_element::find()->max("id");
        $max_value++;

        $model = new Spr_globam_element();
        $model->id = $max_value;

        /**
         */
        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->name = trim($model->name);
            $model->short_name = trim($model->short_name);
            


            if ($model->save()){
                return $this->redirect(['/globalamelement/return_to_refer']);
            }
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing sprtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
//ddd($model);

        if ($model->load(Yii::$app->request->post())) {

            $model->id = (integer)$model->id;
            $model->parent_id = (integer)$model->parent_id;
            $model->name = trim($model->name);
            $model->short_name = trim($model->short_name);


            $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
            $model->user_ip = Yii::$app->request->getUserIP();
            $model->user_id = Yii::$app->user->identity->id;
            $model->user_name = Yii::$app->user->identity->username;

            if ($model->save(true)){
                return $this->redirect(['/globalamelement/return_to_refer']);
            }
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
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['/globalamelement/return_to_refer']);
    }

    /**
     * Finds the sprtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return post_spr_globam_element|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = post_spr_globam_element::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
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
}
