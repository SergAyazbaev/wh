<?php

namespace frontend\controllers;

//use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * PvController implements the CRUD actions for pv model.
 */
class PvreportsController extends Controller
{

    /**
     * @throws NotFoundHttpException
     */
    public function init()
    {
        parent::init();

//        if (! Yii::$app->getUser()->identity->id) {
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


        return $this->render('index', [

//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//            'para' => $para,


        ]);
    }


    public function actionExcel_print()
        //public function actionExcelPrint()
    {
        echo 'ExcelPrint<br>';

        dd($_REQUEST);


    }

    public function actionPvreports()
        //public function actionExcelPrint()
    {


        echo 'Pvreports<br>';

        dd($_REQUEST);


    }



}

