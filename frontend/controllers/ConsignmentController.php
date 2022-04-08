<?php

namespace frontend\controllers;


//use frontend\models\Barcode_consignment;
//use frontend\models\Barcode_pool;
use frontend\models\Consignment;
use frontend\models\post_barcode_consignment;
use frontend\models\post_consignment;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use Yii;
use yii\base\ExitException;
use yii\base\InvalidArgumentException;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;



class ConsignmentController extends Controller
{

    public $para = [];

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

                    'create' => [
                        'GET',
                        'POST',
                    ],
                    'actionCreate_all_new' => [
                        'GET',
                        'POST',
                    ],

                    'update' => [
                        'GET',
                        'PUT',
                        'POST',
                    ],

                    'delete' => [
                        'POST',
                        'DELETE',
                    ],

//                    'excel' => ['POST'],

                ],
            ],
        ];
    }


    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;
        $print_comand = Yii::$app->request->get();


        // регестрируем скрипты клиенат
        //Yii::$app()->nodeSocket->registerClientScripts();

        // выполнение других действий


//        // создаем обьект для работы с библиотекой
//        var socket = new YiiNodeSocket();
//
//// включение режима отладки
//        socket.debug(true);


//			ddd(123);


        ///
        /// ПЕРЕДАЧА ДАННЫХ ФИЛЬТРА ДЛЯ ПЕЧАТИ ЧЕРЕЗ СЕССИЮ
        ///
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        ///
        /// $para
        ///
        if (!isset($para['print'])) {
            $session['repquery'] = $para; // Это то самый секрет!!!
        } else {
            $para = $session['repquery'];  //  тут его получаем
        }
        ////////////


        $spr_globam_element = ArrayHelper::map(
            Spr_globam_element::find()->all(),
            'id', 'name'
        );

        $dt_one_day_array=Consignment::List_active_id();

//        ddd($dt_one_day_array);

        $searchModel = new post_consignment();
        $dataProvider = $searchModel->search($para);

        $dt_one_day= (isset($para['dt_one_day'])?$para['dt_one_day']:'')  ;

        $dataProvider->sort = [
            'defaultOrder' => [
                'dt_create_timestamp' => SORT_ASC,
                'id' => SORT_ASC,
            ]
        ];


        //
        // To EXCEL
        //
        if ((isset($print_comand['print']) && $print_comand['print'] == 1) ||
            (isset($para['print']) && $para['print'] == 1)
        ) {

            //
            // Получаем из СЕССИИ параметры для печати
            //
            $dataProvider->pagination = ['pageSize' => -1];

            $spr_globam = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');


            $this->render(
                'print/print_excel', [
                'dataProvider' => $dataProvider,
                'dataModels' => $dataProvider->getModels(),
                'spr_globam' => $spr_globam,
                'spr_globam_element' => $spr_globam_element,
            ]);
        }

        ///////


        return $this->render(
            'index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'para' => $para,
                'spr_globam_element' => $spr_globam_element,
                'dt_one_day' => $dt_one_day,
                'dt_one_day_array' => $dt_one_day_array,
            ]
        );

    }


    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Consignment();
        $model->id = (int)Consignment::setNext_max_id();

        //ddd($model);


        $spr_globam_element = ArrayHelper::map(Spr_globam_element::find()
            ->where(['!=', 'intelligent', '1'])
            ->all(), 'id', 'name');

        $spr_globam_element_parent = ArrayHelper::map(Spr_globam_element::find()
            ->where(['!=', 'intelligent', '1'])
            ->all(), 'id', 'parent_id');

        $barcode_consignment = ArrayHelper::getColumn(Consignment::find()->all(), 'name');
        $barcode_tx = ArrayHelper::getColumn(Consignment::find()->all(), 'tx');


        $model->dt_create =
            date('d.m.Y H:i:s', strtotime('now'));


        if ($model->load(Yii::$app->request->post())) {


            $model->id = (int)$model->id;
            $model->group_id = $spr_globam_element_parent[(int)$model->element_id];
            $model->element_id = (int)$model->element_id;
            $model->dt_create_timestamp = strtotime($model->dt_create);

            //ddd($model);

            //				ddd($model);

            if ($model->save()) {
                return $this->redirect(['/consignment']);
            }
        }


        //			$transaction->commit();
        //			ddd($transaction);


        return $this->render(
            '_form',
            [
                'model' => $model,
                'spr_globam_element' => $spr_globam_element,

                'barcode_consignment' => $barcode_consignment,
                'barcode_tx' => $barcode_tx,
            ]
        );
    }


    /**
     * РЕДАКТИРОВАНИЕ сопровождается отметками от пользователе-редакторе и дате редактирования
     *
     * Updates an existing sprtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param $id
     *
     * @return string|Response
     * @throws ExitException
     */
    public function actionUpdate($id)
    {
        $model = Consignment::findModel($id);

        $spr_globam_element = ArrayHelper::map(Spr_globam_element::find()
            ->where(['!=', 'intelligent', '1'])
            ->all(), 'id', 'name');

        $spr_globam_element_parent = ArrayHelper::map(Spr_globam_element::find()
            ->where(['!=', 'intelligent', '1'])
            ->all(), 'id', 'parent_id');

        $barcode_consignment = ArrayHelper::getColumn(Consignment::find()->all(), 'name');
        $barcode_tx = ArrayHelper::getColumn(Consignment::find()->all(), 'tx');

//ddd($barcode_tx);


        if ($model->load(Yii::$app->request->post())) {


            //ddd($model);

            $model->id = (int)$model->id;
            if (isset($spr_globam_element_parent[$model->element_id]) && !empty($spr_globam_element_parent[$model->element_id])) {
                $model->group_id = $spr_globam_element_parent[$model->element_id];
            } else {
                $model->group_id = 0;
            }


            $model->dt_create_timestamp = strtotime($model->dt_create);
            $model->element_id = (int)$model->element_id;

            ///
            ///  Количество символов в строке числа = 15
            ///
            $xx = substr($model->cena, -15, 15);
            $xx = round(preg_replace('/[ ]/i', '', $xx), 2);
            $model->cena = (double)$xx;


            //////
            /// Протокол/стенограмма изменений
            ///
            $array_listing = (array)$model->array_update;

            $array_listing[] = [

                'td_update' => date('d.m.Y H:i:s', strtotime('now')),

                'user_name' => Yii::$app->user->identity->username,
                'user_group' => Yii::$app->getUser()->identity->group_id,
                'user_id' => Yii::$app->getUser()->identity->id,
                'user_ip' => Yii::$app->request->getUserIP(),
                'user_gethostbyaddr' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                //'user_gethostbynamel' =>   gethostbynamel($_SERVER['REMOTE_ADDR']),
                //'user_dns_get_record ' =>   dns_get_record ($_SERVER['REMOTE_ADDR']),


                'td_create_old' => date('d.m.Y H:i:s', $model->oldAttributes['dt_create_timestamp']),
                'group_name_old' => $model->oldAttributes['group_name'],
                'element_name_old' => $model->oldAttributes['element_name'],

                'name_old' => $model->oldAttributes['name'],
                'summ_old' => $model->oldAttributes['cena'],
                'summ_after' => $model->cena,

            ];

            $model->array_update = $array_listing;
            /////////


            //ddd($model);
            //barcode_consignment_id

            if ($model->save(true)) {
                return $this->redirect(['/consignment']);
            }

        }


        return $this->render(
            '_form', [
            'model' => $model,
            'spr_globam_element' => $spr_globam_element,

            'barcode_consignment' => $barcode_consignment,
            'barcode_tx' => $barcode_tx,
        ]);
    }


    /**
     * Создаем набор Устойств.
     *=
     * для установления текущей цены
     *-
     *
     * @return mixed
     */
    public function actionCreate_all_new()
    {


        $spr_globam_element = ArrayHelper::map(Spr_globam_element::find()
            ->where(['!=', 'intelligent', '1'])
            ->all(), 'id', 'name');

        $spr_globam_element_parent = ArrayHelper::map(Spr_globam_element::find()
            ->where(['!=', 'intelligent', '1'])
            ->all(), 'id', 'parent_id');


        $errors = [];

        /// В цикле создаем все устройства на сегодня.
        foreach ($spr_globam_element as $key => $item) {

            //ddd($item);

            $model = new Consignment();
            $model->id = (int)Consignment::setNext_max_id();

            $model->group_id = $spr_globam_element_parent[(int)$key];
            $model->element_id = (int)$key;
            $model->group_name = $spr_globam_element[(int)$key];
            $model->element_name = $item;

            $model->dt_create = date('d.m.Y 00:00:05', strtotime("now"));
            $model->dt_create_timestamp = strtotime($model->dt_create);

            $model->name = 'Партия № ' . $model->id;
            $model->tx = "б/п";


            ////
            $max = self::Find_max_summary($model->element_id);

            if (isset($max) && $max > 0) {
                $model->cena = (double)$max;
            } else {
                $model->cena = 0.01;
            }
            ///


            if (!$model->save()) {
                $errors [] = $model->errors;
            }


        }

        if ($errors) {
            ddd($errors);
        }


        //ddd($model);

        return $this->redirect(['/consignment']);
    }


    /**
     * @param $asuop_id
     * @return int
     */
    static function Find_max_summary($asuop_id)
    {

        $xx = Consignment::find()
            ->where(['element_id' => (int)$asuop_id])
            ->max('cena');

        return $xx;
    }


    /**
     * @return Response
     * @throws ExitException
     * @throws StaleObjectException
     */
    public function actionDelete()
    {
        $para = Yii::$app->request->get();

        $model = Consignment::findModel($para['id']);
        $model->delete();

        //ddd($model);


        // Возвтрат по РЕФЕРАЛУ
        $url_array = Yii::$app->request->headers;
        $url = $url_array['referer'];

        return $this->goBack($url);
    }


    /**
     * actionExcel
     * =
     *
     * @return bool
     */
    public function actionExcel()
    {
        //$para=Yii::$app->request->queryParams;
        //$para=Yii::$app->request->get();

        $para = Yii::$app->request->post();

        //ddd($para);

        $searchModel = new post_barcode_consignment();
        $dataProvider = $searchModel->search($para);


        // To EXCEL
        if (isset($para['print']) && $para['print'] == 1) {
            // ddd($para);

            $dataProvider->pagination = ['pageSize' => -1];

            $dataProvider->setSort(
                [
                    'defaultOrder' => [
                        'element_id' => SORT_ASC,
                        'name' => SORT_ASC,
                    ],
                ]);


            //				$spr_wh_top = ArrayHelper::map( Sprwhtop::find()->all(), 'id', 'name' );


            $this->render(
                'print/print_excel', [
                'dataModels' => $dataProvider->getModels(),
                'spr_wh_top' => $spr_wh_top,
            ]);
        }

        return false;
    }


}
