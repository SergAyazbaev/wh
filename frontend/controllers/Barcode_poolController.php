<?php

namespace frontend\controllers;

use frontend\models\Barcode_consignment;
use frontend\models\Barcode_pool;
use frontend\models\post_barcode_pool;
use frontend\models\Sklad;
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
use yii\web\NotFoundHttpException;
use yii\web\Response;


class Barcode_poolController extends Controller
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
        if (!isset(Yii::$app->getUser()->identity->id)) {
            throw new HttpException(411, 'Необходима авторизация', 1);
        }

        return [
            'verbs' => [

                'class' => VerbFilter::className(),

                'actions' => [

                    'create' => [
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


                    'excel' => ['POST'],

                    'write_off_copypast' => [
                        'POST',
                    ],

                ],
            ],
        ];
    }


    /**
     * INDEX
     *
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;
        $print_comand = Yii::$app->request->get();

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
            Spr_globam_element::find()
                ->where(['intelligent' => '1'])
                ->all(),
            'id', 'name'
        );


        $searchModel = new post_barcode_pool();
        $dataProvider = $searchModel->search($para);


        //ddd($dataProvider->getModels());


        //
        // To EXCEL
        //
        if ((isset($print_comand['print']) && $print_comand['print'] == 1) || (isset($para['print']) && $para['print'] == 1)) {

            //
            // Получаем из СЕССИИ параметры для печати
            //
            $dataProvider->pagination = ['pageSize' => -1];

            $spr_globam = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');


            //ddd($dataProvider->getModels());

            $this->render(
                'print/print_excel', [
                    'dataProvider' => $dataProvider,
                    'dataModels' => $dataProvider->getModels(),
                    'spr_globam' => $spr_globam,
                    'spr_globam_element' => $spr_globam_element,
                ]
            );
        }

        ///////

        //ddd($dataProvider->getModels());

        /**
         * Запомнить РЕФЕР
         */
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);


        return $this->render(
            'index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'para' => $para,
                'spr_globam_element' => $spr_globam_element,
            ]
        );

    }


    /**
     * ВОЗВРАТ ПО РЕФЕРАЛУ
     */
    public function actionReturn_to_refer()
    {
        //Возврат по рефералу REFER
        return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id, Yii::$app->request->url));
    }

    /**
     * В связке со списком партий
     * =
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex_with()
    {
        $para = Yii::$app->request->queryParams;
        $print_comand = Yii::$app->request->get();

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
            Spr_globam_element::find()
                ->where(['intelligent' => '1'])
                ->all(),
            'id', 'name'
        );


        $searchModel = new post_barcode_pool();
        $dataProvider = $searchModel->search_with_consigment($para);


        //ddd($dataProvider->getModels());


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
                ]
            );
        }

        ///////


        return $this->render(
            'index_consignment', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'para' => $para,
                'spr_globam_element' => $spr_globam_element,
            ]
        );

    }


    /**
     * TURNOVER
     * =
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex_turnover()
    {
        $para = Yii::$app->request->queryParams;
        //$print_comand = Yii::$app->request->get();

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
            Spr_globam_element::find()
                ->where(['intelligent' => '1'])
                ->all(),
            'id', 'name'
        );


        $searchModel = new post_barcode_pool();
        $dataProvider = $searchModel->search_with_consigment($para);


        //ddd($dataProvider->getModels());


        //
        // To EXCEL
        //
//        if ((isset($print_comand['print']) && $print_comand['print'] == 1) ||
//            (isset($para['print']) && $para['print'] == 1)
//        ) {
//
//            //
//            // Получаем из СЕССИИ параметры для печати
//            //
//            $dataProvider->pagination = ['pageSize' => -1];
//
//            $spr_globam = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');
//
//
//            $this->render(
//                'print/print_excel', [
//                    'dataProvider' => $dataProvider,
//                    'dataModels' => $dataProvider->getModels(),
//                    'spr_globam' => $spr_globam,
//                    'spr_globam_element' => $spr_globam_element,
//                ]
//            );
//        }

        ///////


        return $this->render(
            'index_turnover', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'para' => $para,
                'spr_globam_element' => $spr_globam_element,
            ]
        );

    }

    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {

        $model = new barcode_pool();
        $model->id = Barcode_pool::setNext_max_id();
        //ddd($model);

        $spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');
        $barcode_pool = ArrayHelper::getColumn(Barcode_pool::find()->all(), 'bar_code');
        $barcode_consignment = ArrayHelper::getColumn(Barcode_consignment::find()->all(), 'name');

        //ddd($barcode_pool);

        //			$spr_autocomplete = ArrayHelper::getColumn( Spr_globam_element::find()->all(), 'name' );


        if ($model->load(Yii::$app->request->post())) {

            $model->id = (integer)$model->id;
            $model->element_id = (integer)$model->element_id;

            //				$model->create_user_id = Yii::$app->getUser()->identity->id; // 'Id создателя',
            //$model->dt_create = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
            //				$model->delete_sign = (integer) 0; // Типа NO DEL
            //				ddd($model);

            if ($model->save()) {
                return $this->redirect(['/barcode_pool/return_to_refer']);
            }
        }


        return $this->render(
            '_form_new',
            [
                'model' => $model,
                'spr_globam_element' => $spr_globam_element,
                'barcode_pool' => $barcode_pool,
                'barcode_consignment' => $barcode_consignment,
            ]
        );
    }

    /**
     * РЕДАКТИРОВАНИЕ сопровождается отметками от пользователе-редакторе и дате редактирования
     * Updates an existing sprtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param $id
     * @return string|Response
     * @throws ExitException
     */
    public function actionUpdate($id)
    {
        $model = Barcode_pool::findModel($id);
        $spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');
        $barcode_consignment = ArrayHelper::map(Barcode_consignment::find()->all(), 'id', 'name');

        if ($model->load(Yii::$app->request->post())) {
            $model->id = (int)$model->id;
            $model->element_id = (int)$model->element_id;
            $model->barcode_consignment_id = (int)$model->barcode_consignment_id;

            if ($model->save(true)) {
                //return $this->redirect(['/barcode_pool']);
                return $this->redirect(['/barcode_pool/return_to_refer']);
            }
        }

        return $this->render(
            '_form', [
                'model' => $model,
                'spr_globam_element' => $spr_globam_element,
                'barcode_consignment' => $barcode_consignment,
            ]
        );
    }

    /**
     * @return Response
     * @throws ExitException
     * @throws StaleObjectException
     */
    public function actionDelete()
    {
        $para = Yii::$app->request->get();

        $model = Barcode_pool::findModel($para['id']);

        //ddd($model);

        $model->delete();

        // Возвтрат по РЕФЕРАЛУ
        $url_array = Yii::$app->request->headers;
        $url = $url_array['referer'];

        //return $this->goBack($url);
        return $this->redirect(['/barcode_pool/return_to_refer']);
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

        $searchModel = new post_barcode_pool();
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
                ]
            );


            //				$spr_wh_top = ArrayHelper::map( Sprwhtop::find()->all(), 'id', 'name' );


            $this->render(
                'print/print_excel', [
                    'dataModels' => $dataProvider->getModels(),
                    'spr_wh_top' => $spr_wh_top,
                ]
            );
        }

        return false;
    }


    ////////////////

    /**
     * СПИСАНИЕ!!!!!!!!!!!!!
     * =
     * Заливка два вида-два пути.
     * 1. Заливка от Назиры.
     * КОПИПАСТ для создание новых номеров в справочнике ШтрихКОДОВ
     * 2. Заливка от Назиры.
     * КОПИПАСТ для отметок о СПИСАНИИ
     * =
     * 2. СПИСАНИЕ (Write_off)
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionWrite_off_copypast()
    {
        $para = Yii::$app->request->post();

        //$id = $para[ 'Barcode_pool' ][ 'id' ];


        ////////
        $array = explode("\r\n", $para['Barcode_pool']['find_array']);

//            ddd($array);
//        ddd( $para );

        foreach ($array as $item) {
            $array_str = explode("\t", $item);

            //ddd($array_str);

            //    0 => '015057A04308'
            //    1 => 'списание №214'
            //    2 => '-1'
            //    3 => 'утеряно'


            if (!empty($item)) {
                if (!empty($array_str['0'])) {

                    $barcode_str = (string)preg_replace('/[^\d*]/i', '', $array_str['0']);

                    //ddd($barcode_str);

                    //015057043082141
//                    if (substr($barcode_str, 0, 6) == '015057') {
//                        $barcode_str = substr($barcode_str, 0, 11);
//                    }
                    //0196000
                    if (substr($barcode_str, 0, 6) == '019600') {
                        $barcode_str = substr($barcode_str, 1, 11);
                    }
                    //196000
                    if (substr($barcode_str, 0, 6) == '19600') {
                        $barcode_str = substr($barcode_str, 0, 10);
                    }

                    $array_result[] = $barcode_str;

                    $barcode_id = Barcode_pool::getId_Barcode($barcode_str);
                    //ddd($barcode_id);


                    if (!isset($barcode_id) || empty($barcode_id)) {
                        $array_error[$barcode_str] = $barcode_id;
                        continue;
                    }

                    if (Barcode_pool::updatePosition($barcode_id, $array_str[1], $array_str[3]) == -1) {
                        ddd(123);
                    }


                }
            }
        }

        ///


        //
        if (!isset($array_result)) {
            throw new NotFoundHttpException('Нет данных для заливки в базу.');
        } else {


            echo '<br>OK! SAVE <br>';
            ddd($array_result);
            //            if( isset( $array_error ))
            {
                echo '<br>errors<br>';
                ddd($array_error);
            }

        }


        return $this->redirect('/barcode_pool');
    }

    /**
     * Заливка два вида - два пути.
     * 1. Заливка от Назиры.
     * КОПИПАСТ для создание новых номеров в справочнике ШтрихКОДОВ
     * 2. Заливка от Назиры.
     * КОПИПАСТ для отметок о СПИСАНИИ
     * =
     * 1
     *
     * @return string
     */
    public function actionCreate_new_pool()
    {
        $model = new Barcode_pool();
        ///
        $spr_globam_element = ArrayHelper::map(
            Spr_globam_element::find()->all(),
            'id', 'name'
        );
        //
        $spr_consignment = ArrayHelper::map(
            Barcode_consignment::find()
                ->orderBy(['name'])
                ->all(),
            'id', 'name'
        );


        ///
        return $this->render(
            '_form_create_pool',
            [
                'model' => $model,
                'spr_globam_element' => $spr_globam_element,
                'spr_consignment' => $spr_consignment
            ]
        );
    }


    /**
     * Принмаем Сюда. КОПИПАСТ ДЛЯ ЗАЛИВКИ ШТРИХКОДОВ
     * =
     * для Мастеров и Слейвов
     * -
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPool_in()
    {
        ///   ...
        $barcode_pool = Yii::$app->request->post('Barcode_pool');
        //ddd($barcode_pool);

        $element_id = (int)$barcode_pool['id'];
        $barcode_consignment_id = (int)$barcode_pool['barcode_consignment_id'];


        //ddd($id);
        //ddd($barcode_pool);

        ////////
        $array = explode("\r\n", $barcode_pool['find_array']);

        $array_result = [];
        foreach ($array as $item) {
            if (!empty($item)) {
                $pattern = '/^[0-9]{5,10}[A-Z]?[0-9]{1,5}(-5)?(-8)?(-16)?$/i';
                preg_match($pattern, $item, $barcode_str);
                ///
                if (substr($barcode_str[0], 0, 6) == '019600') {
                    $array_result[] = substr($barcode_str[0], 1, 12);
                } else {
                    $array_result[] = $barcode_str[0];
                }
            }
        }

        //ddd($array_result); // OK
        if (!isset($array_result)) {
            throw new NotFoundHttpException('Нет данных для заливки в базу.');
        }

        $err = [];
        ////
        foreach ($array_result as $item) {
            $model = new Barcode_pool();
            $model->id = (int)Barcode_pool::setNext_max_id();
            $model->element_id = (int)$element_id;
            $model->barcode_consignment_id = (int)$barcode_consignment_id;
            $model->bar_code = (string)$item;

            ////SAVE
            if (!$model->save(true)) {
                $err[] = $model->errors;
                continue;
            }
            //ddd($model);

        }


        if (isset($err) && !empty($err)) {
            ddd($err);
            $str = implode(', ', $err);
            ddd($err);


            return $this->render(
                '_form_create_pool', [
                    'model' => $model,
                    'alert_mess' => $str,

                ]
            );

        } else {

            return $this->redirect('/barcode_pool');
        }

    }

    /**
     * НЕвошедшее в ПУЛ ПРОВЕРОЧНЫХ НОМЕРОВ (Штрихкодов)
     * =
     *
     * @return string
     */
    public function actionOuter()
    {

        $para = Yii::$app->request->queryParams;
//			$print_comand = Yii::$app->request->get();

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
        }
//			else
//			{
//				$para = $session[ 'repquery' ];  //  тут его получаем
//			}
        ////////////


//			$spr_globam_element = ArrayHelper::map(
//				Spr_globam_element::find()->all(),
//				'id', 'name'
//			);


        //
        // Ищет баркоды, которые
        // 1. есть в накладных,
        // 2. но нет в справочнике
        //

        $model_sklad = Sklad::Out_array_barcode_all();

        // * Удаляет ПУСТЫШКИ из массива
        $model_sklad = $this->Empty_kill($model_sklad);


        ddd($model_sklad);


//        echo "Есть накладные, в торорых есть строки со ШТРИХКОДАМИ.<br><br>
//        Но эти штрихкоды НЕ ПОДТВЕРЖДЕНЫ В пуле номеров.<br>
//         Соответственно это НЕ может быть ИДЕНТИФИЦИРОВАНО";
//        ddd( $model_sklad );


        $this->render(
            'print_pool_out/print_excel', [
                'model_sklad' => $model_sklad,
            ]
        );


        return true;
    }


    /**
     * Удаляет ПУСТЫШКИ из массива
     * =
     * @param $array
     * @return
     */
    function Empty_kill($array)
    {
        foreach ($array as $key => $item) {
            if (empty($item)) {
                unset($array[$key]);
            }
        }

        return $array;
    }


}
