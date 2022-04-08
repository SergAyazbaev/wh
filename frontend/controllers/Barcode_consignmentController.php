<?php

namespace frontend\controllers;


use frontend\models\Barcode_consignment;
use frontend\models\Barcode_pool;
use frontend\models\post_barcode_consignment;
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



class Barcode_consignmentController extends Controller
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


        $searchModel = new post_barcode_consignment();
        $dataProvider = $searchModel->search($para);


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

        //Запомнить РЕФЕРee
//        Sklad::setPathRefer(Yii::$app->request->url);

        //Запомнить РЕФЕР
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);


        ///////
        ///
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
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws \Throwable
     */
    public function actionCreate()
    {

        $model = new Barcode_consignment();
        $model->id = (int)Barcode_consignment::setNext_max_id();

        $spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');
        $barcode_consignment = ArrayHelper::getColumn(Barcode_consignment::find()->all(), 'name');
        $barcode_tx = ArrayHelper::getColumn(Barcode_consignment::find()->all(), 'tx');


        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)$model->id;
            $model->element_id = (int)$model->element_id;
            $model->dt_create_timestamp = strtotime($model->dt_create);

            //сохраним и модель операции и модель контрагента
//            $transaction = Yii::$app->db->beginTransaction();
//            try {
//                $model->save(true);
//                $transaction->commit();
//            } catch (\Exception $e) {
//                $transaction->rollBack();
//                throw $e;
//            } catch (\Throwable $e) {
//                $transaction->rollBack();
//                throw $e;
//            }


            if ( $model->save() ) {
                // Возвтрат по РЕФЕРАЛУ
                return $this->redirect(['/barcode_consignment/return_to_refer']);
            }
        }


        //		$transaction->commit();
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
     * Updates an existing sprtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param $id
     * @return string|Response
     * @throws ExitException
     */
    public function actionUpdate($id)
    {
        $model = Barcode_consignment::findModel($id);
        $spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');


        if ($model->load(Yii::$app->request->post())) {
            $model->id = (int)$model->id;

            ///
            ///  Количество символов в строке числа = 15
            ///
            $xx = substr($model->cena_input, -15, 15);
            $xx = round(preg_replace('/[ ]/i', '', $xx), 2);
            $model->cena_input = (double)$xx;


            $model->dt_create_timestamp = strtotime($model->dt_create);
            $model->dt_update_timestamp = strtotime('now');

            //ddd($model);
            //barcode_consignment_id

            if ($model->save(true)) {
                // Возвтрат по РЕФЕРАЛУ
                return $this->redirect(['/barcode_consignment/return_to_refer']);
            }

        }


        $spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');
        $barcode_consignment = ArrayHelper::getColumn(Barcode_consignment::find()->all(), 'name');
        $barcode_tx = ArrayHelper::getColumn(Barcode_consignment::find()->all(), 'tx');



        return $this->render(
            '_form', [
                'model' => $model,
                'barcode_consignment' => $barcode_consignment,
                'spr_globam_element' => $spr_globam_element,
                'barcode_tx' => $barcode_tx,

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

        $model = Barcode_consignment::findModel($para['id']);

        //ddd($model);

        $model->delete();

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
     * Меню выбора. КОПИПАСТ для создание новых номеров в справочнике ШтрихКОДОВ
     * =
     *
     * @return string
     */
    public function actionCreate_new_pool()
    {
        $model = new Barcode_consignment();

        $spr_globam_element = ArrayHelper::map(
            Spr_globam_element::find()->all(),
            'id', 'name'
        );

        //ddd($spr_globam_element);


        return $this->render(
            '_form_create_pool', [
                'model' => $model,
                'spr_globam_element' => $spr_globam_element

                //            'alert_mess' => 'Сохранение. Попытка',

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
        $para = Yii::$app->request->post('Barcode_pool');
        $id = $para['id'];



        ////////
        $array = explode("\r\n", $para['bar_code']);

        foreach ($array as $item) {
            if (!empty($item)) {
                $barcode_str = (string)preg_replace('/[^\d*]/i', '', $item);

                //19600004992
                if (substr($barcode_str, 0, 6) == '019600') {
                    $barcode_str = substr($barcode_str, 1, 12);
                }

                $array_result[] = $barcode_str;
            }
        }


        //			ddd($para);
        //

        if (!isset($array_result)) {
            throw new NotFoundHttpException('Нет данных для заливки в базу.');
        }


        ////
        foreach ($array_result as $item) {
            $model = new Barcode_consignment();
            $model->id = (int)Barcode_consignment::setNext_max_id();
            $model->element_id = (int)$id;
            $model->bar_code = $item;

            if (!$model->save(true)) {
                $err[] = $model->errors;


                continue;
            }

        }

        if (isset($err) && !empty($err)) {
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


        //			return $this->render(
        //				'_form_create_pool', [
        //				'model' => $model
        //
        //				//            'alert_mess' => 'Сохранение. Попытка',
        //
        //			] );
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
        //ddd( $model_sklad );

        $this->render(
            'print_pool_out/print_excel', [
                'model_sklad' => $model_sklad,
            ]
        );


        //			$searchModel  = new postsklad_outer();
        //			$dataProvider = $searchModel->search_outer( $model_sklad );


        //ddd($dataProvider->getModels());


        //
        // To EXCEL
        //
        //			if(( isset( $print_comand[ 'print' ] ) && $print_comand[ 'print' ] == 1 ) ||
        //			   ( isset( $para[ 'print' ] ) && $para[ 'print' ] == 1 )
        //			)
        //			{

        //
        // Получаем из СЕССИИ параметры для печати
        //
        //				$dataProvider->pagination = [ 'pageSize' => - 1 ];
        //
        //				$spr_globam = ArrayHelper::map( Spr_globam::find()->all(), 'id', 'name' );


        //				$this->render(
        //					'print/print_excel', [
        //					'dataProvider'       => $dataProvider,
        //					'dataModels'         => $dataProvider->getModels(),
        //					'spr_globam'         => $spr_globam,
        //					'spr_globam_element' => $spr_globam_element,
        //				] );
        //			}

        ///////


        //			return $this->render(
        //				'index', [
        //					'searchModel'        => $searchModel,
        //					'dataProvider'       => $dataProvider,
        //					'para'               => $para,
        //					'spr_globam_element' => $spr_globam_element,
        //				]
        //			);

        return true;
    }


    ////////////////

    /**
     * Меню выбора. КОПИПАСТ для создание новых номеров в справочнике ШтрихКОДОВ
     * =
     *
     * @return string
     */
    public function actionCreate_new_consigment()
    {

        $model = new Barcode_pool();

        $spr_globam_element = ArrayHelper::map(
            Spr_globam_element::find()->all(),
            'id', 'name'
        );

        //ddd($spr_globam_element);


        return $this->render(
            '_form_load_consignment', [
                'model' => $model,
                'spr_globam_element' => $spr_globam_element

                //            'alert_mess' => 'Сохранение. Попытка',

            ]
        );
    }


    /**
     * Принмаем Сюда. КОПИПАСТ ДЛЯ ЗАЛИВКИ ПАРТИИ
     * =
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionConsignment_in()
    {
        $para = Yii::$app->request->post();
        //ddd($para);


        ///Ид названия устройства в Справочнике
        $id = $para['Barcode_pool']['id'];

        ////////
        $array = explode("\r\n", $para['Barcode_pool']['bar_code']);
        //ddd($array);


        //
        //Строка ПАРТИИ
        // 0 => 'Validator CVB24 master Терминал пассажира CVB24 ведущий (Master) - 224 136 тг (26.06.2019г.)'
        $str_partii = $array[0];
        unset($array[0]);

        ////
        if (!isset($str_partii) || empty($str_partii)) {
            throw new NotFoundHttpException('Строка ПАРТИИ...Нет данных для заливки в базу.');
        }


        //			echo $str_partii;
        $str_partii = preg_replace('/[ ]+/i', ' ', $str_partii);
        $str_partii = preg_replace('/\t/i', '', $str_partii);
        $str_partii = preg_replace('/\n/i', '', $str_partii);
        $str_partii = preg_replace('/([\w+][ ]+)( ).*/i', '$1', $str_partii);
        //ddd($str_partii);

        //'Терминал New 8210(в т.ч блок питания,SIM карта) - 90550тг (26.11.2019г.)'


        //$pattern       = '/([\w \(\)\"\]\[ ]*)(-)([ \w\W]*)/ui';
        //$pattern = '/([\w \t\r\n\(\)\s-\s]*)(-)([ \w\W]*)/ui';
        //        ddd($str_partii);


        $string_after1 = preg_replace(
            '{(.*)- ?([\d]*) ?([\d]*) ?(тг ?\(?[\d]*\.?[\d]*\.?[\d]*г\.\))$}u',
            '$1', $str_partii
        );
//         ddd($string_after1);

        $string_tenge = preg_replace(
            '/(.*)- ?([\d]*) ?([\d]*) ?(тг ?\(?[\d]*\.?[\d]*\.?[\d]*г\.\))$/u',
            '$2$3', $str_partii
        );

        $string_data = preg_replace(
            '/(.*)([\d]{2}\.?[\d]{2}\.?[\d]{4})г\..*$/u',
            '$2', $str_partii
        );


//        ddd($string_data);


        //            ddd(123);

        //			$str_cena = 1;

        $model_partii = new Barcode_consignment();
        $model_partii->id = Barcode_consignment::setNext_max_id();
        $model_partii->dt_create = date('d.m.Y 00:00:01', strtotime($string_data));
        $model_partii->dt_create_timestamp = strtotime($model_partii->dt_create);
        $model_partii->element_id = (int)$id;

        $model_partii->name = $string_after1;
        $model_partii->tx = $str_partii;
        $model_partii->cena_input = (double)$string_tenge;

        if (!$model_partii->save(true)) {
            //$err[] = $model_partii->errors;

            ddd($model_partii->errors);
        }
        //ddd($model_partii); OK !!!!!!!!


        $array_result = [];
        //Теперь Шагаем по списку ШТРИХКОДОВ из копипаста ...................
        foreach ($array as $item) {
            if (!empty($item)) {
                //                $barcode_str = (string)preg_replace( '/[^\d*]/i', '', $item );
                $pattern = '/^[0-9]{5,10}[A-Z]?[0-9]{1,5}(-5)?(-8)?(-16)?$/i';
                preg_match($pattern, $item, $barcode_str);

                //19600004992
                if (substr($barcode_str[0], 0, 6) == '019600') {
                    $array_result[] = substr($barcode_str[0], 1, 12);
                } else {
                    $array_result[] = $barcode_str[0];
                }

            }
        }

        //	0 => '19600012345'
        //  1 => '19600012344'
        //  2 => '19600012346'

        //ddd($array_result);


        foreach ($array_result as $item) {
            $model_one_barcode = Barcode_pool::find()
                ->where(
                    [
                        '=',
                        'bar_code',
                        $item,
                    ]
                )
                ->one();

            //ddd($model_one_barcode);


            if (!isset($model_one_barcode)) {
                echo '<br>' . $item;
                continue;

                //ddd(123);
            }


            $model_one_barcode->element_id = (int)$id;
            $model_one_barcode->barcode_consignment_id = (int)$model_partii->id;

            //				ddd($model_one_barcode);

            if (!$model_one_barcode->save(true)) {
                //$err[] = $model_partii->errors;
                ddd($model_one_barcode->errors);
            } else {
                echo "\n Сохранил " . $id;
            }


        }


        ///
        ///  Показывем ОШИБКИ и ВЫХОДИМ
        ///
        if (isset($err) && !empty($err)) {
            $str = implode(', ', $err);
//            ddd( $err );

            return $this->render(
                '_form_create_pool', [
                    'model' => $model,
                    'alert_mess' => $str,

                ]
            );

        } else {

            return $this->redirect('/barcode_consignment');
        }


        //			return $this->render(
        //				'_form_create_pool', [
        //				'model' => $model
        //
        //				//            'alert_mess' => 'Сохранение. Попытка',
        //
        //			] );
    }


}
