<?php

namespace frontend\controllers;


//use frontend\models\postpvmobile;
use frontend\models\postpvmsam;
use frontend\models\Pvmsam;
//use frontend\models\Sklad;
use frontend\models\Sprwhelement;
use frontend\models\Typemotion;
use frontend\components\MyHelpers;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * pvmsamController implements the CRUD actions for pvmsam model.
 */
class PvmsamController extends Controller
{
    /**
     * @throws NotFoundHttpException
     */
    public function init()
    {
        parent::init();

//        if (! Yii::$app->user->id) {
        if (! Yii::$app->user->identity) {
            throw new NotFoundHttpException('Нужно авторизоваться');
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
     * @return string
     */
    public function actionIndex()
    {

//        $xx= Sklad::find()->asArray()->all();
//        dd($xx);



        $para=Yii::$app->request->queryParams;

//        if ( !isset($para['postpvmsam']['dt_create_start'])
//                || empty($para['postpvmsam']['dt_create_start']) )  {
//
//            $para['postpvmsam']['dt_create_start']=date('d.m.Y',strtotime('now -10 days'));
//            //$para['postpvmsam']['dt_time_start']]
//        }
//        if ( !isset($para['postpvmsam']['dt_create_stop'])
//                || empty($para['postpvmsam']['dt_create_stop']) )  {
//
//            $para['postpvmsam']['dt_create_stop']=date('d.m.Y',strtotime('now'));
//            //$para['postpvmsam']['dt_time_start']]
//        }
//
//        if ( !isset($para['postpvmsam']['type_action_tx'])
//                || empty($para['postpvmsam']['type_action_tx']) )  {
//
//            //$para['postpvmsam']['type_action_tx']='5'; // ?????????????
//
//            //$para['postpvmsam']['dt_time_start']]
//        }

            dd($para);

        $searchModel = new postpvmsam();
        $dataProvider = $searchModel->search( $para );


        //if ( isset($para) && !empty($para)) $para=$para; else $para=[];

        return $this->render('index', [

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'para' => $para,

        ]);
    }

    /**
     * Svodnaja Vedomost
     */
    public function actionPrintsvod()
    {

        $para=Yii::$app->request->queryParams;

        //[sort] => dt_create

        $ord_txt="";
        if(isset( $para['sort'] )){
            if( substr($para['sort'],0,1) <>'-'){
                $ord_txt=$para['sort'].' ASC';
            }
            else{
                $ord_txt=substr($para['sort'],1 ).' DESC';
            }

        }



//        if( isset($para['postpv']['type_action_tx'])
//            && !empty($para['postpv']['type_action_tx']) ){
//
//            $action_txt = $para['postpv']['type_action_tx'];
//        }
//
//        //postpv[wh_top_name]=Green bus company (КАП1)
//        $sklad_txt="";
//        if( isset($para['postpv']['wh_top'])
//            && !empty($para['postpv']['wh_top'])  ){
//
//            $sklad_txt = $para['postpv']['wh_top'];
//
//        }


        if(isset($para) ) {
            $xx_pvmsam = Pvmsam::find()
                ->with('typemotion', 'pv')
                ->andFilterWhere(['like','type_action',
                    $para['postpv']['type_action_tx'], false])
                ->andFilterWhere(['like','wh_top',
                    $para['postpv']['wh_top'] , false])
                ->andFilterWhere(['>', 'dt_create', $para['postpv']['dt_create_start'] . " " . $para['postpv']['dt_time_start']])
                ->andFilterWhere(['<=', 'dt_create', $para['postpv']['dt_create_stop'] . " " . $para['postpv']['dt_time_stop']])
                ->orderBy( $ord_txt )
                ->asArray()->all();





//            dd($xx_pvmsam);

//            $searchModel = new postpvmobile();
//            $dataProvider = $searchModel->search( $para );

//dd($searchModel);

            //$str = $this->render('print_excel_svod',
            return $this->render('print_excel_svod',
                [
                    'dataModels' => $xx_pvmsam,
                    'dataStart' => $para['postpv']['dt_create_start'],
                    'dataStop' => $para['postpv']['dt_create_stop']
                ]);
        }
//        else
//            return $this->render('print_error');


        return $this->render('print_error');
    }


    /**
     * @return string
     */
    public function actionPrintapp()
    {

        $para=Yii::$app->request->queryParams;

        if(isset($para) ) {
            $xx_pvmsam = Pvmsam::find()
                ->with('typemotion', 'pv')
//                ->where(['type_action' => $para['postpv']['type_action_tx']])
                ->andFilterWhere([ 'type_action' => '5' ]) // APP
                ->andFilterWhere(['>', 'dt_create', $para['postpv']['dt_create_start'] . " " . $para['postpv']['dt_time_start']])
                ->andFilterWhere(['<=', 'dt_create', $para['postpv']['dt_create_stop'] . " " . $para['postpv']['dt_time_stop']])
                ->orderBy('dt_create ASC')
                ->asArray()->all();

            // dd($xx_pvmsam);

            return $this->render('print_excel_app', ['dataModels' => $xx_pvmsam, 'dataStart' => $para['postpv']['dt_create_start'], 'dataStop' => $para['postpv']['dt_create_stop']]);
        }
//        else
//            return $this->render('print_error');

        return $this->render('print_error');
    }

    /**
     * EXCEL-ADV report
     * @return string
     */
    public function actionPrintadv()
    {

        $para=Yii::$app->request->queryParams;

        if(isset($para) ) {
            $xx_pvmsam = Pvmsam::find()
                ->with('typemotion', 'pv')
                ->andFilterWhere([ 'type_action' => '4' ]) // ADV -4
                ->andFilterWhere(['>', 'dt_create', $para['postpv']['dt_create_start'] . " " . $para['postpv']['dt_time_start']])
                ->andFilterWhere(['<=', 'dt_create', $para['postpv']['dt_create_stop'] . " " . $para['postpv']['dt_time_stop']])
                ->orderBy('dt_create ASC')
                ->asArray()->all();

            // dd($xx_pvmsam);

            return  $this->render('print_excel_adv', ['dataModels' => $xx_pvmsam, 'dataStart' => $para['postpv']['dt_create_start'], 'dataStop' => $para['postpv']['dt_create_stop']]);
        }
//        else
//            return $this->render('print_error');

        return $this->render('print_error');
    }









    /**
     * Displays a single pvmsam model.
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
     * Creates a new pvmsam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Pvmsam();


        if ($_REQUEST['pv_id']>0){
            $pv_id=$_REQUEST['pv_id'];
            $model->pv_id=$pv_id;
        }
        else
            $pv_id=0;


            $max_value = MyHelpers::Mongo_max_id( 'pvmsam', 'id' );
            $max_value++;
            $model->id=$max_value;


        if (Yii::$app->user->identity->id>0){
            //$model->user_id= Yii::$app->user->id ;
            //$model->name= Yii::$app->user->Username ;

            $model->user_id = Yii::$app->user->identity->id ;
            $model->user_name = Yii::$app->user->identity->username ;
        }
        else
            return true;




        if ($model->load(Yii::$app->request->post())){
           // dd($model);

            $str_APP=Typemotion::find()->where(['id'=>$model->type_action])->one();  // там не double!!!
            //dd($str_APP['tx']);

            if($model->save(true)) {

                if (MyHelpers::Mongo_save('pvmsam','type_action_tx', $model->_id, $str_APP['tx'] ) )
                {
                    if (MyHelpers::Mongo_save('pvmsam','id', $model->_id, (integer) $max_value ) )
                    {
                        if (MyHelpers::Mongo_save('pvmsam','pv_id', $model->_id, (integer) $pv_id ) )
                        {
                            //if (MyHelpers::Mongo_save_date('pvmsam', $model->_id, $model->dt_create)) {

                                if ($pv_id>0)   return $this->redirect(['/pvmsam?postpvmsam[pv_id]='.$pv_id]);
                                else  return $this->redirect(['/pvmsam']);
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
     * Updates an existing pvmsam model.
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

                if (MyHelpers::Mongo_save('pvmsam','type_action_tx', $model->_id, $str_APP['tx'] ) )

                {
                    if (MyHelpers::Mongo_save('pvmsam','id', $model->_id, (integer) $model->id ) )
                    {
                        if (MyHelpers::Mongo_save('pvmsam','pv_id', $model->_id, (integer) $model->pv_id ) )
                        {
                           // if (MyHelpers::Mongo_save_date('pvmsam', $model->_id, $model->dt_create)) {
                                if ($pv_id>0)   return $this->redirect(['/pvmsam?postpvmsam[pv_id]='.$pv_id]);
                                else  return $this->redirect(['/pvmsam']);
                            //}
                        }
                    }
                }
            }
        }



//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            //return $this->redirect(['view', 'id' => (string)$model->_id]);
//            return $this->redirect(['/pvmsam?id='.$model->pv_id ]);
//        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing pvmsam model.
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
            return $this->redirect(['/pvmsam?id='.$model->pv_id ]);
        else
            return $this->redirect(['#']);


//            pvmsam?postpvmsam[pv_id]=16

//        $this->findModel($id)->delete();
//        return $this->redirect(['#']);
    }

    /**
     * Finds the pvmsam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Pvmsam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pvmsam::findOne($id)) !== null) {
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
            Html::dropDownList(
                'name_id',
                $id,
                    //ArrayHelper::map(Sprwhelement::find()->where(['parent_id'=>1])->all(),'id','name'),
                Sprwhelement::find()->where(['parent_id'=>(integer)$id] )->select(  ['name']  )-> indexBy('name')->column(),
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
            Html::dropDownList('name_wh_element',
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
