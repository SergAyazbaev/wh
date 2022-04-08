<?php
namespace frontend\controllers;

use frontend\models\post_stat_tz;
use frontend\models\Sklad;

use frontend\models\Tz;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\HttpException;

//use yii\web\HttpException;


/**
 * PvController implements the CRUD actions for pv model.
 */
class StatisticsController extends Controller
{
//    public $sklad;

    /**
     * {@inheritdoc}
     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [  'class' => VerbFilter::className(),
////                'actions' => [
////                    'delete' => ['POST', 'DELETE'],
////                    'create' => ['POST', 'GET'],
////                    'update' => ['POST', 'GET', 'GET'],
////                    'viewStat' => ['POST'],
////                ],
//            ],
//        ];
//    }

    public function beforeAction($event)
    {
//        if ((Yii::$app->getUser()->identity->group_id >= 50 ||
//            Yii::$app->getUser()->identity->group_id < 40)) {
//            if ( Yii::$app->getUser()->identity->group_id !=30 )
//                throw new NotFoundHttpException('Доступ только отрудникам SKLAD');
//        }
        return parent::beforeAction($event);
    }


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


////////////


    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new post_stat_tz();
        $dataProvider = $searchModel->search_stat(Yii::$app->request->queryParams);


            //        if (isset($_REQUEST) && !empty($_REQUEST)){
            //            ddd( $_REQUEST );
            //        }


        return $this->render('index', [
            'searchModel_into' => $searchModel,
            'dataProvider_into' => $dataProvider,
        ]);

    }

    public function actionStat()
    {
        $para=Yii::$app->request->queryParams;

        /// Жадная загрузка ТЗ+ВСЕ НАКЛАДНЫЕ
        /// Ответ = массив
        $model_one_tz_all_sklad=Tz::findOneModelWith($para['id'],'sklad');

        /////////////////

//        ddd($model_one_tz_all_sklad);

//        if ($model->load(Yii::$app->request->post())) {
//
//            ddd($model);
//            if ($model->save(true)) {
//                //return $this->redirect('/sklad/index?sort=-id&sklad=' . $sklad);
//            } else {
//                //dd($model);
//                return $this->redirect('/');
//            }
//        }


        return $this->render('stat_one', [
            'model' => $model_one_tz_all_sklad,
        ]);

    }



    public function actionStat_1()
    {
        //dd(123);

        $para=Yii::$app->request->queryParams;

            //dd($para);
            //    [id] => 18
            //    [sort] => id

        ////// SKLAD
        $array_select=[
            'id',
            'wh_home_number' ,
            'sklad_vid_oper' ,
            'sklad_vid_oper_name' ,
            'user_name' ,
            'tz_name' ,
            'tz_date' ,
            'dt_deadline' ,
            'wh_debet_top' ,
            'wh_debet_name' ,
            'wh_debet_element' ,
            'wh_debet_element_name' ,
            'wh_destination' ,
            'wh_destination_name' ,
            'wh_destination_element' ,
            'wh_destination_element_name' ,
            'array_count_all' ,
        ];

        $id=$para['id'];
        $model_sklad=Sklad::findModelAsArrayToTz( $id, $array_select );


        $provider = new ArrayDataProvider([
            'allModels' => $model_sklad,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['id', 'tz_name'],
            ],
        ]);

//        dd($provider);

        ////// TZ
        $array_select=[
        'id',
        'user_create_id',
        'user_edit_group_id',
        'user_create_name',
        'dt_create',
        'name_tz',
        'street_map',
        'multi_tz',
        'wh_cred_top',
        'dt_deadline',
        'wh_cred_top_name',
        'user_edit_id',
        'user_edit_name',
        'id_tk'
        ];

        $model_tz=Tz::findOneModelAsArrayToTz( $id, $array_select );




        return $this->render('stat_forms/stat_1', [
            'provider' => $provider,
            'model' => $model_sklad,

            'model_tz' => $model_tz,

        ]);

    }





    public function actionCreate()
    {
        dd(123);

        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');
        //dd($sklad);


        $max_value = Sklad::find()->max('id');;
        $max_value++;


        $new_doc = new Sklad();     // Новая накладная

        $new_doc->id = (integer)$max_value;

        $new_doc->wh_home_number = (int)$sklad;
        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

        $new_doc->user_id = (int)Yii::$app->getUser()->identity->id;
        $new_doc->user_name = Yii::$app->getUser()->identity->username;
        date_default_timezone_set ("Asia/Almaty");
        $new_doc->dt_update = date('d.m.Y H:i:s', strtotime('now'));

        $new_doc->array_tk_amort = [];
        $new_doc->array_tk = [];


        if ($new_doc->load(Yii::$app->request->post())) {

            if ($new_doc->save(true)) {


                return $this->redirect('/sklad/index?sort=-id&sklad=' . $sklad);

            } else {
                //dd($model);
                return $this->redirect('/');
            }
        }


        return $this->render('sklad_in/_form', [
            'new_doc' => $new_doc,
            'sklad' => $sklad,
        ]);
    }

    //public function actionDelete($id, $adres_to_return = "")
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
    }





}
