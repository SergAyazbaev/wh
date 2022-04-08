<?php

namespace frontend\controllers;

use frontend\models\postpv;
use frontend\models\postpvaction;
use frontend\models\Pv;
use frontend\components\MyHelpers;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

//use yii\mongodb\Query;

/**
 * PvController implements the CRUD actions for pv model.
 */
class PvController extends Controller
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
     * Lists all pv models.
     * @return mixed
     */

    public function actionIndex()
    {
//        MyHelpers::excel_to_3();
//        return;

//        $para=[];

            $para=Yii::$app->request->queryParams;

            $searchModel = new postpv();
            $dataProvider = $searchModel->search( $para );


            //dd($dataProvider);

            if ( isset($para['print']) && $para['print']==1)
            {
                $dataProvider ->pagination = ['pageSize' =>-1];

                $str = $this->render('print_excel', [
                            //'dataProvider' => pv::find()->asArray()->all(),
                        'dataProvider' => $dataProvider,
                        'dataModels' => $dataProvider->getModels()
                    ]);

                    echo $str;
//                    return;
            }



        //if ( isset($para) && !empty($para)) $para=$para; else $para=[];

        return $this->render('index', [
            'model' => $searchModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'para' => $para,


        ]);
    }


//    public function actionExcel_print()
//        //public function actionExcelPrint()
//    {
//        echo 'ExcelPrint<br>';
//
//        dd($_REQUEST);
//
//
//    }

//    public function actionPvreports()
//        //public function actionExcelPrint()
//    {
//
//
//        echo 'Pvreports<br>';
//
//        dd($_REQUEST);
//
//
//    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCart($id)
    {
       // $model = new pv();

            /// Поиск по ЭКШЕНУ!!!!
        $searchModel = new postpv();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('_cart', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Таблица история движений по единичному-ID
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCart_act($id)
    {

//        $model = new pvaction();

        /// Поиск по ЭКШЕНУ!!!!
        $searchModel = new postpvaction();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


//dd($searchModel);



        return $this->render('_history_motion', [
            'model' => $this->findModel_act($id),

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

        $model = new Pv();

        $max_value = MyHelpers::Mongo_max_id( 'pv', 'id' );
        $max_value++;
        $model->id=$max_value;


        if ($model->load(Yii::$app->request->post()) && $model->save(true)) {
            if (MyHelpers::Mongo_save('pv','id', $model->_id, $max_value ) )
            {
//                if (MyHelpers::Mongo_save_date('pv', $model->_id, $model->dt_create ) )
//                {
                    return $this->redirect(['/pv']);
//                }

            }
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionCreate_copy($id)
    {
        $model = $this->findModel($id);
            if (!isset($model))  return $this->redirect('/pv');



        if ($model->load(Yii::$app->request->post()) && $model->save(true)) {


            ////////////
//            $utcdatetime = new \MongoDB\BSON\UTCDateTime($model->dt_create_mongo) ;

            $collection = Yii::$app->mongodb->getCollection('pv');
            $collection->update(['_id' => $model->_id ],
                [
                    'dt_create_mongo' => Pv::to_isoDate($model->dt_create),
                    'number_mongo' => (Pv::Maxpv($model->number_mongo))+1,

                ]  // OK Работает
            );
            ////////////


            //return $this->redirect('/pv');
            return $this->redirect(['view','id' => (string)$model->_id]);
        }



        return $this->render('create_copy', [
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

        //dd($model);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                ////////////
                $collection = Yii::$app->mongodb->getCollection('pv');
                $collection->update(['_id' => $model->_id ],
                    [
                        'dt_create_mongo' => MyHelpers::to_isoDate($model->dt_create),
                        'number_mongo' => ($model->number_mongo)+1
                    ]  // OK Работает
                );
                ////////////

                return $this->redirect('/pv');

            //return $this->redirect(['view', 'id' => (string)$model->_id]);
        }

        return $this->render('update', [
             'model' => $model,
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
     * @return Pv|null
     * @throws NotFoundHttpException
     */
    protected function findModel( $id)
    {
        if (($model = Pv::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return array|\yii\mongodb\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel_act( $id)
    {
        if (($model = Pv::find()->where(['id'=>$id])->all() ) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    //    private function addParams(array $array)
    //    {
    //    }

}

