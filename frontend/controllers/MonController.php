<?php


namespace frontend\controllers;

use frontend\models\postmod;
use frontend\models\User_mod;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class MonController extends Controller
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
     * @return string
     */
    public function actionIndex()
   {
       $para = Yii::$app->request->queryParams;

       $model= User_mod::find()->all();
        // dd(123);

       $searchModel = new postmod();
       $dataProvider = $searchModel->search($para);


        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }



    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
//    public function actionUpdate($id)
//    {
////        $session = Yii::$app->session;
//
//        $model = $this->findModel($id);  /// this is  _id !!!!!
//
//
//        if ($model->load(Yii::$app->request->post())){
//
//            $model->id= (integer) $model->id;
//            $model->status= (integer) $model->status;
//            $model->group_id= (integer) $model->group_id;
//
//            //            [username] => viktor
//            //            [email] => 222@qwe.kz
//            //            [status] => 10
//            //            [role] => user
//            //            [group_id] => 40
//
////               dd($model);
//
//            if($model->save(true))
//                    return $this->redirect('/mon');
//
//        }
//
//        return $this->render('_form', [
//            'model' =>  $model,
//        ]);
//
//    }


    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if ($model=User_mod::find()->where( ['_id' => $id])->one() !== null){
            return $model;
        }

            throw new NotFoundHttpException('The requested page does not exist.');



    }

    /**
     * @param $id
     * @return array|null|\yii\mongodb\ActiveRecord
     */
    protected function findModel_double($id)
    {
        return User_mod::find()->where( ['id' => (integer) $id])->one();

    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['/mon']);
//    }



}
