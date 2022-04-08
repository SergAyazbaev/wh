<?php
namespace frontend\controllers;

use frontend\models\postpv;
use Yii;

//use yii\base\InvalidParamException;
//use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;


/**
 * Site controller
 */
class SitemobileController extends Controller
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
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        //$para=[];

        $para=Yii::$app->request->queryParams;

        $searchModel = new postpv();
        $dataProvider = $searchModel->search( $para );


        //dd($dataProvider);


        return $this->render('index', [

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
//            'para' => $para,

        ]);


    }






}
