<?php

namespace frontend\controllers;


use frontend\models\postpvrestore;
use frontend\models\Pv;
use frontend\models\Pvrestore;
use frontend\models\Sprrestore;
use frontend\models\Sprrestoreelement;
use frontend\components\MyHelpers;
//use MongoDB\Driver\Exception\Exception;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * PvRestoreController implements the CRUD actions for pvrestore model.
 */
class PvrestoreController extends Controller
{
    /**
     * @throws NotFoundHttpException
     */
    public function init()
    {
        parent::init();

//        if (! Yii::$app->user->identity->id) {
        if (! Yii::$app->user->identity) {
            throw new NotFoundHttpException('Нужно авторизоваться');
        }
    }


    /**
     * Lists all pvrestore models.
     * @return mixed
     */
    /**
     * @return string
     */
    public function actionIndex()
    {

        $para=Yii::$app->request->queryParams;

//        [postpvrestore] => Array
//        [pv_id] => 5


        if ( !isset($para['postpvrestore']['dt_create_start'])
            || empty($para['postpvrestore']['dt_create_start']) )  {

            $para['postpvrestore']['dt_create_start']=date('d.m.Y',strtotime('now -10 days'));
            //$para['postpvrestore']['dt_time_start']]
        }
        if ( !isset($para['postpvrestore']['dt_create_stop'])
            || empty($para['postpvrestore']['dt_create_stop']) )  {

            $para['postpvrestore']['dt_create_stop']=date('d.m.Y',strtotime('now'));
            //$para['postpvrestore']['dt_time_start']]
        }

//        if ( !isset($para['postpvrestore']['type_action_tx'])
//            || empty($para['postpvrestore']['type_action_tx']) )  {
//
//            //$para['postpvrestore']['type_action_tx']='5'; // ?????????????
//
//            //$para['postpvrestore']['dt_time_start']]
//        }

//        dd($para);

        $searchModel = new postpvrestore();
        $dataProvider = $searchModel->search( $para );


        //if ( isset($para) && !empty($para)) $para=$para; else $para=[];

        return $this->render('index', [

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'para' => $para,

        ]);
    }

    /**
     * Displays a single pvrestore model.
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
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {


        $model = new Pvrestore();



        if ($_REQUEST['pv_id']>0){
            $pv_id=$_REQUEST['pv_id'];
            $model->pv_id=$pv_id;
        }
        else
            $pv_id=0;


        $model_pv=Pv::find()->where(['id'=>(integer)$pv_id])->one();

        $max_value = MyHelpers::Mongo_max_id( 'pvrestore', 'id' );
        $max_value++;
        $model->id=$max_value;

        //dd(Yii::$app->user->identity->id);
        //dd(Yii::$app->user->identity->username);

        if (Yii::$app->user->identity->id>0){
            //$model->user_id= Yii::$app->user->id ;
            //$model->name= Yii::$app->user->Username ;

            $model->user_id = Yii::$app->user->identity->id ;
            $model->user_name = Yii::$app->user->identity->username ;
        }
//        else return;

        $str_APP=Sprrestore::find()->where(['id'=>(integer) $model->type_action_id])->one();
        //dd($str_APP);

        if ($model->load(Yii::$app->request->post())) {
            $model->id = (integer)$max_value;

            $model->type_action_name = $str_APP['name'];
            $model->$pv_id = (integer)$model->$pv_id;
            $model->dt_create = MyHelpers::to_isoDate($model->dt_create);

            if ($model->save(true))
                //return $this->redirect(['/sprwhtop']);


                if ($pv_id>0)  {
                    return $this->redirect(['/pvrestore?postpvrestore[pv_id]='.$pv_id]);
                }
                else
                {
                    return $this->redirect(['/pvrestore']);
                }
        }



        return $this->render('create', [
            'model' => $model,
            'model_pv' => $model_pv,
        ]);
    }


    /**
     * Updates an existing pvrestore model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);



        if (Yii::$app->user->identity->id>0){
            //$model->user_id= Yii::$app->user->id ;
            //$model->name= Yii::$app->user->Username ;

            $model->user_id = Yii::$app->user->identity->id ;
            $model->user_name = Yii::$app->user->identity->username ;
        }
//        else return;




        if ($model->load(Yii::$app->request->post()) && $model->save()) {

//                return $this->redirect(['view', 'id' => $model->_id]);
                return $this->redirect(['/pvrestore']);

        }




        return $this->render('update', [
            'model' => $model,
            //'model_pv' => $model_pv,
        ]);
    }

    /**
     * Deletes an existing pvrestore model.
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
     * Finds the pvrestore model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Pvrestore the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pvrestore::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionRestoreelementselect($id)
    {
        $model =
            html::dropdownList(
                'name_id',
                $id,
                Sprrestoreelement::find()->where(['parent_id'=>(integer)$id] )->select(  ['name']  )-> indexBy('name')->column(),
                [
                    'prompt' => '.......'
                ]
            );

        return $model;
    }

}
