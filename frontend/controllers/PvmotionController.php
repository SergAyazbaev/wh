<?php

namespace frontend\controllers;


use frontend\models\postpvmotion;
use frontend\models\Pvmotion;
use frontend\models\Sprwhelement;
use frontend\models\Typemotion;
//use execut\yii\base\Exception;
use frontend\components\MyHelpers;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * PvMotionController implements the CRUD actions for pvmotion model.
 */
class PvmotionController extends Controller
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
     * @return string
     */
    public function actionIndex()
    {

        $para=Yii::$app->request->queryParams;

        if ( !isset($para['postpvmotion']['dt_create_start'])
                || empty($para['postpvmotion']['dt_create_start']) )  {

            $para['postpvmotion']['dt_create_start']=date('d.m.Y',strtotime('now -10 days'));
            //$para['postpvmotion']['dt_time_start']]
        }
        if ( !isset($para['postpvmotion']['dt_create_stop'])
                || empty($para['postpvmotion']['dt_create_stop']) )  {

            $para['postpvmotion']['dt_create_stop']=date('d.m.Y',strtotime('now'));
            //$para['postpvmotion']['dt_time_start']]
        }

//        if ( !isset($para['postpvmotion']['type_action_tx'])
//                || empty($para['postpvmotion']['type_action_tx']) )  {
//
//            //$para['postpvmotion']['type_action_tx']='5'; // ?????????????
//
//            //$para['postpvmotion']['dt_time_start']]
//        }

            //dd($para);

        $searchModel = new postpvmotion();
        $dataProvider = $searchModel->search( $para );


        //if ( isset($para) && !empty($para)) $para=$para; else $para=[];

        return $this->render('index', [

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'para' => $para,

        ]);
    }

    /**
     *
     */



    /**
     * Svodnaja Vedomost
     */
//    public function actionPrintsvod()
//    {
//
//        $para=Yii::$app->request->queryParams;
//
//        //[sort] => dt_create
//
//        $ord_txt="";
//        if(isset( $para['sort'] )){
//            if( substr($para['sort'],0,1) <>'-'){
//                $ord_txt=$para['sort'].' ASC';
//            }
//            else{
//                $ord_txt=substr($para['sort'],1 ).' DESC';
//            }
//
//        }
//
//
//
////        if( isset($para['postpv']['type_action_tx'])
////            && !empty($para['postpv']['type_action_tx']) ){
////
////            $action_txt = $para['postpv']['type_action_tx'];
////        }
////
////        //postpv[wh_top_name]=Green bus company (КАП1)
////        $sklad_txt="";
////        if( isset($para['postpv']['wh_top'])
////            && !empty($para['postpv']['wh_top'])  ){
////
////            $sklad_txt = $para['postpv']['wh_top'];
////
////        }
//
//
//        if(isset($para) ) {
//            $xx_pvmotion = Pvmotion::find()
//                ->with('typemotion', 'pv')
//                ->andFilterWhere(['like','type_action',
//                    $para['postpv']['type_action_tx'], false])
//                ->andFilterWhere(['like','wh_deb_top',
//                    $para['postpv']['wh_deb_top'] , false])
//                ->andFilterWhere(['>', 'dt_create', $para['postpv']['dt_create_start'] . " " . $para['postpv']['dt_time_start']])
//                ->andFilterWhere(['<=', 'dt_create', $para['postpv']['dt_create_stop'] . " " . $para['postpv']['dt_time_stop']])
//                ->orderBy( $ord_txt )
//                ->asArray()->all();
//
//
//
//
//
////            dd($xx_pvmotion);
//
////            $searchModel = new postpvmotion();
////            $dataProvider = $searchModel->search( $para );
//
////dd($searchModel);
//
//            $str = $this->render('print_excel_svod',
//                [
//                    'dataModels' => $xx_pvmotion,
//                    'dataStart' => $para['postpv']['dt_create_start'],
//                    'dataStop' => $para['postpv']['dt_create_stop']
//                ]);
//        }
//        else
//            return $this->render('print_error');
//    }


    /**
     * @return string
     */
//    public function actionPrintapp()
//    {
//
//        $para=Yii::$app->request->queryParams;
//
//        if(isset($para) ) {
//            $xx_pvmotion = Pvmotion::find()
//                ->with('typemotion', 'pv')
////                ->where(['type_action' => $para['postpv']['type_action_tx']])
//                ->andFilterWhere([ 'type_action' => '5' ]) // APP
//                ->andFilterWhere(['>', 'dt_create', $para['postpv']['dt_create_start'] . " " . $para['postpv']['dt_time_start']])
//                ->andFilterWhere(['<=', 'dt_create', $para['postpv']['dt_create_stop'] . " " . $para['postpv']['dt_time_stop']])
//                ->orderBy('dt_create ASC')
//                ->asArray()->all();
//
//            // dd($xx_pvmotion);
//
//            $str = $this->render('print_excel_app',
//                [
//                    'dataModels' => $xx_pvmotion,
//                    'dataStart' => $para['postpv']['dt_create_start'],
//                    'dataStop' => $para['postpv']['dt_create_stop']
//                ]);
//        }
//        else
//            return $this->render('print_error');
//
//    }


    /**
     * EXCEL-ADV report
     * @return string
     */
//    public function actionPrintadv()
//    {
//
//        $para=Yii::$app->request->queryParams;
//
//        if(isset($para) ) {
//            $xx_pvmotion = Pvmotion::find()
//                ->with('typemotion', 'pv')
//                ->andFilterWhere([ 'type_action' => '4' ]) // ADV -4
//                ->andFilterWhere(['>', 'dt_create', $para['postpv']['dt_create_start'] . " " . $para['postpv']['dt_time_start']])
//                ->andFilterWhere(['<=', 'dt_create', $para['postpv']['dt_create_stop'] . " " . $para['postpv']['dt_time_stop']])
//                ->orderBy('dt_create ASC')
//                ->asArray()->all();
//
//            // dd($xx_pvmotion);
//
////            $str = $this->render('print_excel_adv', ['dataModels' => $xx_pvmotion, 'dataStart' => $para['postpv']['dt_create_start'], 'dataStop' => $para['postpv']['dt_create_stop']]);
//        }
//        else
//            return $this->render('print_error');
//
//    }









    /**
     * Displays a single pvmotion model.
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
     * Creates a new pvmotion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Pvmotion();


        if ($_REQUEST['pv_id']>0){
            $pv_id=$_REQUEST['pv_id'];
            $model->pv_id=$pv_id;
        }
        else
            $pv_id=0;


            $max_value = MyHelpers::Mongo_max_id( 'pvmotion', 'id' );
            $max_value++;
            $model->id=$max_value;


        if (Yii::$app->user->identity->id>0){
            //$model->user_id= Yii::$app->user->id ;
            //$model->name= Yii::$app->user->Username ;

            $model->user_id = Yii::$app->user->identity->id ;
            $model->name    = Yii::$app->user->identity->username ;
        }
        else
            return false;




        if ($model->load(Yii::$app->request->post())){
//            dd($model);
//
            $str_APP=Typemotion::find()->where(['id'=>$model->type_action])->one();  // там не double!!!
            //dd($str_APP['tx']);

            if($model->save(true)) {

                if (MyHelpers::Mongo_save('pvmotion','type_action_tx', $model->_id, $str_APP['tx'] ) )
                {
                    if (MyHelpers::Mongo_save('pvmotion','id', $model->_id, (integer) $max_value ) )
                    {
                        if (MyHelpers::Mongo_save('pvmotion','pv_id', $model->_id, (integer) $pv_id ) )
                        {
                            //if (MyHelpers::Mongo_save_date('pvmotion', $model->_id, $model->dt_create)) {

                                if ($pv_id>0)   return $this->redirect(['/pvmotion?postpvmotion[pv_id]='.$pv_id]);
                                else  return $this->redirect(['/pvmotion']);
                            //}
                        }
                    }
                }
            }
        }



        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing pvmotion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //dd($model);

        if ($model->pv_id>0){
            $pv_id=$model->pv_id;
            $model->pv_id=$pv_id;
        }
        else
            $pv_id=0;


        if ($model->load(Yii::$app->request->post())){


            $str_APP=Typemotion::find()->where(['id'=>$model->type_action])->one();

            //dd($str_APP['tx']);




            if($model->save(true)) {

                // type_action_tx

                if (MyHelpers::Mongo_save('pvmotion','type_action_tx', $model->_id, $str_APP['tx'] ) )

                {
                    if (MyHelpers::Mongo_save('pvmotion','id', $model->_id, (integer) $model->id ) )
                    {
                        if (MyHelpers::Mongo_save('pvmotion','pv_id', $model->_id, (integer) $model->pv_id ) )
                        {
                           // if (MyHelpers::Mongo_save_date('pvmotion', $model->_id, $model->dt_create)) {
                                if ($pv_id>0)   return $this->redirect(['/pvmotion?postpvmotion[pv_id]='.$pv_id]);
                                else  return $this->redirect(['/pvmotion']);
                            //}
                        }
                    }
                }
            }
        }



//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            //return $this->redirect(['view', 'id' => (string)$model->_id]);
//            return $this->redirect(['/pvmotion?id='.$model->pv_id ]);
//        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing pvmotion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->findModel($id)->delete();

        if ($model->pv_id)
            return $this->redirect(['/pvmotion?id='.$model->pv_id ]);
        else
            return $this->redirect(['#']);


//            pvmotion?postpvmotion[pv_id]=16

//        $this->findModel($id)->delete();
//        return $this->redirect(['#']);
    }

    /**
     * Finds the pvmotion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Pvmotion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pvmotion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * функция создает готовый объект html-select из базы
     *
     * @param $id
     * @return string
     */
    public function actionWhselect($id)
    {
        $model =
            html::dropdownList(
                'name_id',
                $id,
                    //ArrayHelper::map(Sprwhelement::find()->where(['parent_id'=>1])->all(),'id','name'),
                Sprwhelement::find()->where(['parent_id'=>(integer)$id] )
                    ->select(  ['name']  )
                    -> indexBy('name')
                    ->column(),
                [

                'prompt' => 'Выбор склада #.#'
                ]
            );


        //dd( $model );

        return $model;
    }

    /**
     * для вывода значения в виде списка
     *
     * @param $name
     * @return string
     */
    public function wh_element_select($name)
    {
        $model =
            html::dropdownList('name_wh_element',
                $name,
                    //ArrayHelper::map(Sprwhelement::find()->where(['parent_id'=>1])->all(),'id','name'),
                Sprwhelement::find()->where(['name'=>$name] )->select(  ['name']  )-> indexBy('name')->column(),
                [

                    'prompt' => 'Выбор склада #.#'
                ]
            );


        //dd( $model );

        return $model;
    }
}
