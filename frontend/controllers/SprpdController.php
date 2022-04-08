<?php

namespace frontend\controllers;

use frontend\models\postsprpd;
use frontend\models\Spr_pd;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class SprpdController extends Controller
{

    public function init()
    {
        parent::init();

        if (! Yii::$app->user->identity) {
            throw new NotFoundHttpException('Нужно авторизоваться');
        }
    }


    public function actionIndex()
    {

        $searchModel = new postsprpd();
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
        if (($model = Spr_pd::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    public function actionCreate()
    {
        $model = new Spr_pd();
        $model->id = Spr_pd::find()->max('id');
        $model->id ++;


            if ($model->load(Yii::$app->request->post())) {


                if ($model->save())
                    return $this->redirect(['/sprpd']);
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
