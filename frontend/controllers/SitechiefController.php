<?php
namespace frontend\controllers;


use frontend\models\post_spr_glob_element;
use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class SitechiefController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
//        $para=[];

        $para=Yii::$app->request->queryParams;

        $searchModel = new post_spr_glob_element();
        $dataProvider = $searchModel->search( $para );


        //dd($searchModel);


        return $this->render('index', [

            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
//            'para' => $para,

        ]);


    }






}
