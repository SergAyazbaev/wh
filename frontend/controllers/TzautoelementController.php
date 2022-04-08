<?php

namespace frontend\controllers;


use frontend\models\posttzautoelement;
use frontend\models\Tzautoelement;
use frontend\components\MyHelpers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * SprTypeController implements the CRUD actions for sprtype model.
 */
class TzautoelementController extends Controller
{


    public function actionIndex()
    {
        $para=Yii::$app->request->queryParams;


        $searchModel = new posttzautoelement();
        $dataProvider = $searchModel->search($para);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'para' => $para,

        ]);
    }


    public function actionCreate()
    {
        $para=Yii::$app->request->queryParams;
        //dd($para);

        $model = new Tzautoelement();
        if ($para['tz_id'] > 0) {
            $model->tz_id = (integer)$para['tz_id'];
        } else
            throw new NotFoundHttpException(' TZ==0 ');

        $max_value = MyHelpers::Mongo_max_id( 'tz_auto_element', 'id' );
        $max_value++;
        $model->id=$max_value;

        //dd($max_value);

        if ( $model->load( Yii::$app->request->post() )){
            $model->id = (integer)$max_value;
            $model->tz_id = (integer)$model->tz_id;


            if ($model->save(true))
                 return $this->redirect(['/tzautoelement?tz_id='.$model->tz_id]);
//            else
//                dd($model->errors);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        if ( $model->load( Yii::$app->request->post() ))
            $model->id = (integer)$max_value;
        $model->tz_id = (integer)$model->tz_id;
        $model->parent_id = (integer)$model->parent_id;

        if ($model->save(true)) {
            return $this->redirect(['/tzautoelement']);
            }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id, $tz_id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['tzautoelement/?tz_id='.$tz_id]);
    }


    protected function findModel($id)
    {
        if (($model = Tzautoelement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Нет доступа к модели');
    }

    /**
     *  Залить в таблицу SPR_SKLAD
     */
//    public function actionEx_add()
//    {
//        MyHelpers::excel_to_wh();
//    }
}
