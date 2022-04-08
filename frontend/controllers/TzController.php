<?php

namespace frontend\controllers;


use frontend\models\post_spr_globam_element;
use frontend\models\posttz;

use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use frontend\models\Tk;
use frontend\models\Tz;
use frontend\models\Tzautoelement;
use frontend\components\MyHelpers;

use Mpdf\Mpdf;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use Picqer\Barcode\Exceptions\BarcodeException;
use Mpdf\MpdfException;
use yii\db\StaleObjectException;
use yii\web\Response;


class TzController extends Controller
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
     * @param $event
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction( $event )
    {
        if ( Yii::$app->getUser()->identity->group_id < 50 ) {
            throw new NotFoundHttpException( Yii::$app->controller->id.'. Доступ только группе главного инженера' );
        }

        return parent::beforeAction( $event );
    }



    /**
     * */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'GET' ],
                    //                    'delete' => ['POST', 'DELETE'],
                    'create' => [
                        'POST',
                        'GET',
                    ],
                    //                    'update' => ['POST', 'PUT', 'POST'],
                    //                    'update' => ['POST', 'GET', 'GET'],

                    'update' => [
                        'POST',
                        'PUT',
                        'GET',
                    ],
                    'new_bus_resave' => [
                        'POST',
                        'PUT',
                        'GET',
                    ],
                    'read' => [
                        'POST',
                        'PUT',
                        'GET',
                    ],
                ],
            ],
        ];
    }

    /**
     * */
    public function actionIndex()
    {

//        ddd(Yii::$app->params['vars']);


        $para = Yii::$app->request->queryParams;

        if ( !isset( $para[ 'posttz' ] ) ) {

            $para[ 'posttz' ][ 'dt_deadline1' ] = date( 'd.m.Y', strtotime( 'now -7 day' ) );
            $para[ 'posttz' ][ 'dt_deadline2' ] = date( 'd.m.Y', strtotime( 'now +7 day' ) );

        }


        $searchModel = new posttz();
        $dataProvider = $searchModel->search( $para );


        $dataProvider->setSort(
            [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],

            ]
        );


        //ddd($dataProvider->getModels());

        return $this->render(
            'index',
            [
                'model' => $searchModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'para' => $para,

            ]
        );
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView( $id )
    {
        return $this->render( 'view', [ 'model' => $this->findModel( $id ), ] );
    }


    /**
     * */
    public function actionCreate()
    {
        $max_value = Tz::find()->max( 'id' );

        $model = new Tz();
        $model->id = ++$max_value;

        if ( $model->load( Yii::$app->request->post() ) ) {

            $model->id = (integer)$model->id;
            $model->dt_create = date( "d.m.Y 00:00:00", strtotime( 'now' ) );
            $model->dt_deadline = date( "d.m.Y 00:00:00", strtotime( 'now' ) );

            $model->dt_deadline = date( "d.m.Y 00:00:00", strtotime( 'now' ) );

            $tt = Sprwhtop::findModelDouble( $model->wh_cred_top )->toArray();
            $model->wh_cred_top_name = $tt[ 'name' ];


            if ( $model->save( true ) ) {
                return $this->redirect( [ '/tz/update?id='.$model->_id ] );
            } else{
                ddd( $model->errors );
            }
        }

        return $this->render( 'create', [ 'model' => $model, ] );
    }


    /**
     * Создать новое ТЕХЗАДАНИЕ
     * =
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate_new()
    {

        $session = Yii::$app->session;
        if ( !$session->isActive ) {
            $session->open();
        }


//            if ( !isset( $sklad ) || empty( $sklad ) ) {
//                throw new UnauthorizedHttpException( 'Sklad=0' );
//            }


        $model = new Tz();

        if ( !is_object( $model ) ) {
            throw new NotFoundHttpException( 'Склад не работает' );
        }


        ////////
        $model->id = (int)Tz::setNext_max_id();
        $model->user_create_id = Yii::$app->user->identity->id;
        $model->user_edit_group_id = Yii::$app->user->identity->group_id;
        $model->user_create_name = Yii::$app->getUser()->identity->username;


        $model->dt_create = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
        $model->dt_deadline = date( 'd.m.Y H:i:s', strtotime( 'now + 5 days' ) );

        $model->dt_create_timestamp = strtotime( 'now' );
        $model->dt_deadline_timestamp = strtotime( 'now + 5 days' );

        //ddd( $model );

        /////////// ПРЕД СОХРАНЕНИЕМ
        if ( $model->load( Yii::$app->request->post() ) ) {

            $para = Yii::$app->request->post();
            //ddd($para);
            //   'Tz' => [
            //        'dt_create' => '12.02.2020 09:42:45'
            //        'street_map' => '1'
            //    ]
            //    'contact-button' => 'create_new'


            $model->id = (int)Tz::setNext_max_id();
            $model->user_create_id = Yii::$app->user->identity->id;
            $model->user_edit_group_id = Yii::$app->user->identity->group_id;
            $model->user_create_name = Yii::$app->getUser()->identity->username;

            $model->wh_cred_top = (int)$model->wh_cred_top;
            $model->status_state = (int)0;
            $model->array_bus = [];


            if ( isset( $para[ 'Tz' ][ 'street_map' ] ) ) {
                $model->street_map = (int)$para[ 'Tz' ][ 'street_map' ];
                $model->multi_tz = (int)1;
            }

            //ddd($model);


            /////////// ПРЕД СОХРАНЕНИЕМ
            /// Проверим ИМЕННО Нашу кнопку "Создать"
            if ( isset( $para[ 'contact-button' ] ) && $para[ 'contact-button' ] == 'create_new' ) {

                // ddd(123);

                if ( $model->save( true ) ) {
//                    ddd(123);
                    return $this->redirect( '/tz' );
                } else{
                    ddd( $model->errors );
                }
            }
        }


        $from_ap = Sprwhtop::get_ListFinalDestination();

        return $this->render(
            '_form_create', [
                              'model' => $model,
                              'from_ap' => $from_ap,

//                              'alert_mess' => '',
                          ]
        );
    }


    /**
     * Updates an existing pv model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     * @throws NotFoundHttpException охнанение изменений ACTION  = open_tz_new_tk
     */
    public function actionUpdate()
    {
        //        'wh_cred_top' => '14'
        //        'array_bus' => ''
        //        'multi_tz' => '1'


        $para = Yii::$app->request->get(); // id
        $id = $para[ 'id' ];
        $model = $this->findModel( $id );

        // Все Автобусы Одного Автопарка
        $items_auto = Sprwhelement::findAll_Elements_Parent( $model->wh_cred_top );


        ///////////
        if ( $model->load( Yii::$app->request->post() ) ) {
            if ( isset( $model->array_bus ) && is_array( $model->array_bus ) ) {
                //Сколько автобусов?
                $count_xx = count( $model->array_bus );
            } else{
                $count_xx = 0;
            }
            $model->multi_tz = (int)$count_xx;

            $model->wh_cred_top = (int)$model->wh_cred_top;

            $spr_name = ArrayHelper::getValue( Sprwhtop::findModelDouble( (int)$model->wh_cred_top ), 'name' );
            $model->wh_cred_top_name = $spr_name;


            //ddd( $model );

            if ( $model->save( true ) ) {
                return $this->redirect( '/tz' );
            }
        }


        //ddd($model);

        return $this->render(
            '_form_tz',
            [
                'model' => $model,
                'items_auto' => $items_auto,
            ]
        );
    }


    /**
     *Созздание НОВОГО АВТОБУСА. Правильный ввод в справочник.
     * =
     */
    public function actionNew_bus()
    {
        //        if ( !is_object( $model ) ) {
        //            throw new NotFoundHttpException( 'Склад не работает' );
        //        }

//        $model_wh = Sprwhtop::find()->one();
        $all_wh = Sprwhtop::get_ListFinalDestination();

        $model = Sprwhelement::find()->one();
        $all = [];


        /////////// ПРЕД СОХРАНЕНИЕМ
        if ( $model->load( Yii::$app->request->post() ) ) {
            $para = Yii::$app->request->post();
            //ddd( $model );

            //
            // ПОиск ПОХОЖЕГО среди Имеющихся во всех полях (name, gos, bort)
            //
            if ( $para[ 'contact-button' ] == 'create_new' &&
                isset( $para[ 'Sprwhelement' ][ 'find_parent_id' ] ) &&
                isset( $para[ 'Sprwhelement' ][ 'name' ] ) ) {

                $xx_model = Sprwhelement::findFirst_in_park( $model->name, $para[ 'Sprwhelement' ][ 'find_parent_id' ] );

                //ddd( $para );
                //ddd( $xx_model );

                //// Нашел!!!
                if ( isset( $xx_model ) ) {
                    return $this->render(
                        '_form_new_bus_exist', [ 'model' => $xx_model, ]
                    );
                }
            }


            ///////////
            ///
            /// Создаем НОВУЮ ЗАПИСЬ
            ///
            $para = Yii::$app->request->post();
            //ddd($para);

            if ( $para[ 'contact-button' ] == 'create_new' &&
                isset( $para[ 'Sprwhelement' ][ 'find_parent_id' ] ) &&
                isset( $para[ 'Sprwhelement' ][ 'name' ] ) ) {

                //PARENT $model_parent
                $model_parent = Sprwhtop::findModelDouble( (int)$para[ 'Sprwhelement' ][ 'find_parent_id' ] );

                $model = new Sprwhelement();
                $model->id = (int)Sprwhelement::setNext_max_id();
                $model->parent_id = (int)$para[ 'Sprwhelement' ][ 'find_parent_id' ];
                $model->name = $para[ 'Sprwhelement' ][ 'name' ];

                $model->nomer_borta = '';
                $model->nomer_gos_registr = '';
                $model->nomer_vin = '';

                $model->final_destination = $model_parent->final_destination;
                $model->deactive = $model_parent->deactive;

                if ( isset( $model_parent->f_first_bort ) && (int)$model_parent->f_first_bort == 1 ) {
                    $model->f_first_bort = $model_parent->f_first_bort;
                } else{
                    $model->f_first_bort = (int)0;
                }


                //  * Функция приведения записей по полям ГОС и БОРТ в норму.
                // $model = SprwhelementController::Normalise_GOS_BORT( $model );

                //ddd($model);

                //ddd( $para[ 'Sprwhelement' ][ 'name' ] );
                //ddd( $para[ 'Sprwhtop' ][ 'name' ] );


                /////////// ПРЕД СОХРАНЕНИЕМ
                /// Проверим ИМЕННО Нашу кнопку "Создать"

                if ( $model->save( true ) ) {
                    return $this->render(
                        '_form_new_bus_exist', [ 'model' => $model, ]
                    );
                }
            }

        }


        return $this->render(
            '_form_new_bus', [
                               'model' => $model,
//                               'model_wh' => $model_wh,
                               'all_wh' => $all_wh,
                               'all' => $all,
                           ]
        );
    }


    /**
     * 2 RESAVE
     * =
     */
    public function actionNew_bus_resave()
    {
        $model = new  Sprwhelement();


        /////////// ПРЕД СОХРАНЕНИЕМ
        if ( $model->load( Yii::$app->request->post() ) ) {
            //ddd( $model );

            //        'id' => '180'
            //        'name' => '5004'
            //        'nomer_borta' => '5004'
            //        'nomer_gos_registr' => '956DR02'
            //        'nomer_vin' => '234234'

            $model_save = Sprwhelement::findModelDouble( $model->id );
            $model_save->name = $model->name;
            $model_save->nomer_borta = $model->nomer_borta;
            $model_save->nomer_gos_registr = $model->nomer_gos_registr;
            $model_save->nomer_vin = $model->nomer_vin;


            //  * Функция приведения записей по полям ГОС и БОРТ в норму.
            // $model_save = SprwhelementController::Normalise_GOS_BORT( $model_save );


            //            ddd($model);
            //            ddd( $model_save );

            ////
            /////////// ПРЕД СОХРАНЕНИЕМ
            /// Проверим ИМЕННО Нашу кнопку "Создать"
            if ( $model_save->save( true ) ) {
                return $this->redirect( '/tz' );
            }
//            else{
//                ddd( $model_save->errors );
//            }

        }


        return $this->render(
            '_form_new_bus_resave', [
                                      'model' => $model,
//                               'model_wh' => $model_wh,
                                      'all_wh' => $all_wh,
                                      'all' => $all,
                                  ]
        );
    }


    /**
     * JS Upload//ПОДЧИНЕННЫЕ СКЛАДЫ
     *=
     *
     * @param int $id
     * @return string
     */
    public function actionList_whelement( $id )
    {
        $model = Html::dropDownList(
            'name_id', 0,
            ArrayHelper::map(
                Sprwhelement::find()
                    ->where( [ 'parent_id' => (integer)$id ] )
                    ->all(), 'name', 'name'
            ),
            [ 'prompt' => 'Выбор ...' ]
        );

        if ( empty( $model ) ) {
            return "Запрос вернул пустой массив";
        }

        return $model;
    }


    /**
     * Обработчик ТАЙМШТАМП
     */
    public function actionRemont_date()
    {
        $xx = ArrayHelper::getColumn( Tz::find()->all(), 'id' );
        //ddd($xx);

        foreach ( $xx as $item_xx ) {
            $model = Tz::findModelDouble( $item_xx );

            $model->id = (int)$model->id;
            $model->user_create_id = (int)$model->user_create_id;
            $model->user_edit_group_id = (int)$model->user_edit_group_id;

            $model->user_edit_id = (int)$model->user_edit_id;

            $model->dt_create_timestamp = strtotime( $model->dt_create );
            $model->dt_deadline_timestamp = strtotime( $model->dt_deadline );

            $model->street_map = (int)$model->street_map;
            $model->multi_tz = (int)$model->multi_tz;

            if ( (int)$model->multi_tz < 1 ) {
                $model->multi_tz = (int)1;
            }

            if ( !$model->save( true ) ) {
                //ddd($model);
                ddd( $model->errors );
            }

        }


        ddd( "OK" );
    }

    /**
     * READ
     * =
     */
    public function actionRead()
    {
        $para = Yii::$app->request->get(); // id
        $id = $para[ 'id' ];
        $model = $this->findModel( $id );

//        ddd($model);

        // Все Автобусы Одного Автопарка
        $items_auto = Sprwhelement::findAll_Elements_Parent( $model->wh_cred_top );


        if ( $model->load( Yii::$app->request->post() ) ) {

            if ( $model->save( true ) ) {
                //ddd($model);
                return $this->redirect( '/tz' );
            } else
                ddd( $model->errors );
        }


        //ddd($model);

        return $this->render(
            '_form_tz_read',
            [
                'model' => $model,
                'items_auto' => $items_auto,
            ]
        );
    }


    /**
     * Эта функция применяется для подстановки
     * Целого массива ТК (Типового комплекта) в
     * Массив ТЗ (Типового задания)
     *
     * @param $id_tz
     * @param $id_tk
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreatenext( $id_tz, $id_tk )
    {
        if ( $id_tz ) {
            $model = $this->findModel( $id_tz );
        }
        //        else
        //            throw new NotFoundHttpException( 'Createnext.Не хватает параметра' );

        //    [id_tz] => 5cab0a7180a0631674007bf5
        //    [id_tk] => 1
        //    [val12] => Array
        //        [val_0_1] => 2
        //        [val_2_1] => 12
        //        [val_2_2] => BATU HOLDING ТОО

        $xx = Tk::find()->where( [ 'id' => (integer)$id_tk ] )->one();


        /// ДВА МАССИВА из ТК в TZ
        $model->array_tk = $xx[ 'array_tk' ]; // Списание простое
        $model->array_tk_amort = $xx[ 'array_tk_amort' ];  // Амортизация

        //        $model->tk_top = $id_tk;  // Список-дропдаун
        //        $model->wh_cred_top = $_REQUEST['val12']['val_2_1'];
        //        $model->wh_cred_top_name = $_REQUEST['val12']['val_2_2'];


        if ( $model->load( Yii::$app->request->post() ) ) {

            //dd($model);
            $model->id = (integer)$model->id;

            if ( $model->save( true ) ) {
                return $this->redirect( [ '/tz' ] );
            } else{
                dd( $model->errors );
            }
        }


        return $this->renderAjax( 'update_tz', [ 'model' => $model, ] );
    }


    /**
     * UPDATE->SAVE()
     * Вот тут запись ПРОИСХОДИТ от update
     * через форму ActiveForm::begin( 'action'  => ['tz/open_tz_new_tk'])
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionOpen_tz_new_tk()
    {
        $para = Yii::$app->request->post();

        //        dd($para);
        ///////SAVE
        if ( isset( $para[ "Tz" ] ) ) /// Тут Понимаем, что началась запись
        {
            //dd($para);
            $model = $this->findModel( $para[ 'Tz' ][ '_id' ] ); // Наша модель по нашему _id

            $model->array_bus = $para[ "Tz" ][ 'array_bus' ];    //
            $model->array_tk_amort = $para[ "Tz" ][ 'array_tk_amort' ];//
            $model->array_tk = $para[ "Tz" ][ 'array_tk' ];     //
            $model->array_casual = $para[ "Tz" ][ 'array_casual' ]; //


            $model->id_tk = $para[ "Tz" ][ 'id_tk' ];   // Для менюшки, после сохранения
            $model->name_tz = $para[ "Tz" ][ 'name_tz' ]; // Название для ТехЗадания
            $model->dt_deadline = $para[ "Tz" ][ 'dt_deadline' ]; //

            $tt = Sprwhtop::findModelDouble( $model->wh_cred_top )->toArray();
            $model->wh_cred_top_name = $tt[ 'name' ];  // Название СКЛАДА-АВТОПАРКА

            $model->multi_tz = count( $model->array_bus ); //$model->multi_tz = $para["Tz"]['multi_tz'] ; //


            $model->id = (integer)$model->id;


            if ( empty( $model->array_bus ) ) {
                $model->multi_tz = $para[ "Tz" ][ 'multi_tz' ];
            } else{
                $model->multi_tz = count( $para[ "Tz" ][ 'array_bus' ] );
            }


            ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
            //$model->array_tk_amort  = Tz::setArraySort1( $model->array_tk_amort );
            $model->array_tk = Tz::setArraySort2( $model->array_tk );

            ////  Приводим ключи в прядок! УСПОКАИВАЕМ
            $model->array_tk_amort = Tz::setArrayToNormal( $model->array_tk_amort );
            $model->array_tk = Tz::setArrayToNormal( $model->array_tk );


            if ( $model->save( true ) ) {
                return $this->redirect( '/tz' );
            } else{
                dd( $model->errors );
            }


        }
        ///////SAVE


        //        dd($para);
        if ( isset( $para[ 'id_tz' ] ) ) {
            $model = $this->findModel( $para[ 'id_tz' ] );

            if ( isset( $para[ 'id_tk' ] ) ) {
                $model->name_tz = $para[ 'name_tz' ];
                $model->id_tk = $para[ 'id_tk' ];
                $model->array_bus = $para[ 'array_bus_select' ];
                $model->multi_tz = $para[ 'multi_tz' ];
                $model->dt_deadline = $para[ 'dt_deadline' ];

            } else{
                throw new NotFoundHttpException( 'Нет параметра.Open_tz_new_tk. id_tk' );
            }

            // Список всех автобусов в этом автопарке
            $items_auto = Sprwhelement::findAll_Elements_Parent( $model->wh_cred_top );

        } else{
            throw new NotFoundHttpException( 'Нет параметра.Open_tz_new_tk. id_tz' );
        }


        /// ДВА МАССИВА слив в Активном ОКНЕ
        if ( isset( $para[ 'id_tk' ] ) ) {
            $xx = Tk::findModelDouble( $para[ 'id_tk' ] );

            /// ДВА МАССИВА из ТК в TZ
            $model->array_tk = $xx->array_tk; // Списание простое
            $model->array_tk_amort = $xx->array_tk_amort;  // Амортизация
        }


        return $this->renderAjax(
            'update_tz',
            [
                'model' => $model,
                'items_auto' => $items_auto,
            ]
        );
    }


    /**
     * Deletes an existing pv model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public function actionDelete( $id )
    {
        $this->findModel( $id )->delete();

        return $this->redirect( [ '/tz' ] );
    }


    /**
     * @param $id
     * @return Tz|bool|null
     * @throws NotFoundHttpException
     */
    protected function findModel( $id )
    {
        if ( ( $model = Tz::findOne( $id ) ) !== null ) {
            return $model;
        }
        throw new NotFoundHttpException( 'TZ. Model не создана' );
    }


    /**
     * Галвный Инженер = TZ
     *
     * @return bool
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws BarcodeException
     */
    public function actionHtml_pdf()
    {
        //$para = Yii::$app->request->queryParams;
        $para = Yii::$app->request->get(); // тоже работает
        $model = Tz::findModelDouble( $para[ 'id' ] );

        //        dd($model);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map( Spr_globam::find()->all(), 'id', 'name' );

        $model2 = ArrayHelper::map( Spr_globam_element::find()->orderBy( 'id' )->all(), 'id', 'name' );


        ///// NOT AMORT
        $model3 = ArrayHelper::map( Spr_glob::find()->all(), 'id', 'name' );

        $model4 = ArrayHelper::map( Spr_glob_element::find()->orderBy( 'id' )->all(), 'id', 'name' );

        $model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );


        $model6 = [
            '1' => 'Первичная полная установка',
            '2' => 'Первичная частичная установка',
            '3' => 'Демонтаж ',
            '4' => 'Монтаж',
            '5' => 'Выдача расходных материалов',
        ];
        ////////////////////
        $model7 = Sprwhtop::findModelDouble( $model->wh_cred_top )->toArray();

        // Делаем Справочник ID->NAME ([177] => 5001)
        $model8 = ArrayHelper::map( Sprwhelement::find()->where( [ 'parent_id' => (integer)$model->wh_cred_top ] )->all(), 'id', 'name' );

        //$model7['name'];
        //        dd($model8);
        //        [177] => 5001
        //        [178] => 5002
        //        [179] => 5003


        //1
        $html_css = $this->getView()->render( '/tz/html_pdf/_form_css.php' );

        //2
        $html = $this->getView()->render(
            '/tz/html_pdf/_form',
            [
                'model' => $model,
                'model1' => $model1,
                'model2' => $model2,
                'model3' => $model3,
                'model4' => $model4,
                'model5' => $model5,
                'model6' => $model6,
                'model7' => $model7,
                'model8' => $model8,
            ]
        );


        //  Тут можно подсмореть
        //          $html = ss($html);
        //          dd($html );


        ///
        ///  mPDF()
        ///

        unset( $mpdf );

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';
        $mpdf->WriteHTML( $html_css, 1 );

        ///////
        $mpdf->AddPage( 0, 0, 0, 0, 0, 10, 10, 20, 20 );
        //$html = '';
        $str_pos = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную


        //        ddd($model) ;
        $html .= MyHelpers::Barcode_HTML( 'TZ'.$model->id.'-'.$str_pos );
        //////////


        $mpdf->WriteHTML( $html, 2 );
        $html = '';


        unset( $html );

        $filename = 'tz'.date( 'd.m.Y H-i-s' ).'.pdf';
        $mpdf->Output( $filename, 'I' );


        return false;
    }


    /**
     * Галвный Инженер = TZ
     *
     * @return bool
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws BarcodeException
     */
    public function actionHtml_pdf_norma()
    {
        //$para = Yii::$app->request->queryParams;
        $para = Yii::$app->request->get(); // тоже работает
        $model = Tz::findModelDouble( $para[ 'id' ] );

        //dd($model->wh_cred_top);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map( Spr_globam::find()->all(), 'id', 'name' );

        $model2 = ArrayHelper::map( Spr_globam_element::find()->orderBy( 'id' )->all(), 'id', 'name' );


        ///// NOT AMORT
        $model3 = ArrayHelper::map( Spr_glob::find()->all(), 'id', 'name' );

        $model4 = ArrayHelper::map( Spr_glob_element::find()->orderBy( 'id' )->all(), 'id', 'name' );


        $model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );


        $model6 = [
            '1' => 'Первичная полная установка',
            '2' => 'Первичная частичная установка',
            '3' => 'Демонтаж ',
            '4' => 'Монтаж',
            '5' => 'Выдача расходных материалов',
        ];
        ////////////////////
        $model7 = Sprwhtop::findModelDouble( $model->wh_cred_top )->toArray();

        // Делаем Справочник ID->NAME ([177] => 5001)
        $model8 = ArrayHelper::map( Sprwhelement::find()->where( [ 'parent_id' => (integer)$model->wh_cred_top ] )->all(), 'id', 'name' );

        //$model7['name'];
        //        dd($model8);
        //        [177] => 5001
        //        [178] => 5002
        //        [179] => 5003


        //1
        $html_css = $this->getView()->render( '/tz/html_pdf_norma/_form_css.php' );

        //2
        $html = $this->getView()->render(
            '/tz/html_pdf_norma/_form', [
                                          'model' => $model,
                                          'model1' => $model1,
                                          'model2' => $model2,
                                          'model3' => $model3,
                                          'model4' => $model4,
                                          'model5' => $model5,
                                          'model6' => $model6,
                                          'model7' => $model7,
                                          'model8' => $model8,
                                      ]
        );


        //  Тут можно подсмореть
        //          $html = ss($html);
        //          dd($html );


        ///
        ///  mPDF()
        ///

        unset( $mpdf );

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';
        $mpdf->WriteHTML( $html_css, 1 );

        ///////
        $mpdf->AddPage( 0, 0, 0, 0, 0, 10, 10, 20, 20 );
        //$html = '';
        $str_pos = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
        //       dd($model) ;
        $html .= MyHelpers::Barcode_HTML( 'TZ'.$model->id.'-'.$str_pos );
        //////////


        $mpdf->WriteHTML( $html, 2 );
        $html = '';


        unset( $html );

        $filename = 'tz'.date( 'd.m.Y H-i-s' ).'.pdf';
        $mpdf->Output( $filename, 'I' );


        return false;
    }

    /**
     * Галвный Инженер = TZ
     * НОРМА РАСХОДА-3
     *
     * @return bool
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws BarcodeException
     */
    public function actionHtml_pdf_norma_3()
    {
        //$para = Yii::$app->request->queryParams;
        $para = Yii::$app->request->get(); // тоже работает
        $model = Tz::findModelDouble( $para[ 'id' ] );

        //dd($model->wh_cred_top);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map( Spr_globam::find()->all(), 'id', 'name' );

        $model2 = ArrayHelper::map( Spr_globam_element::find()->orderBy( 'id' )->all(), 'id', 'name' );


        ///// NOT AMORT
        $model3 = ArrayHelper::map( Spr_glob::find()->all(), 'id', 'name' );

        $model4 = ArrayHelper::map( Spr_glob_element::find()->orderBy( 'id' )->all(), 'id', 'name' );


        $model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );


        $model6 = [
            '1' => 'Первичная полная установка',
            '2' => 'Первичная частичная установка',
            '3' => 'Демонтаж ',
            '4' => 'Монтаж',
            '5' => 'Выдача расходных материалов',
        ];
        ////////////////////
        $model7 = Sprwhtop::findModelDouble( $model->wh_cred_top )->toArray();

        // Делаем Справочник ID->NAME ([177] => 5001)
        $model8 = ArrayHelper::map(
            Sprwhelement::find()
                ->where( [ 'parent_id' => (integer)$model->wh_cred_top ] )
                ->all(), 'id', 'name'
        );

        //        ddd($model8);

        //$model7['name'];
        //        dd($model8);
        //        [177] => 5001
        //        [178] => 5002
        //        [179] => 5003


        //1
        $html_css = $this->getView()->render( '/tz/html_pdf_norma_3/_form_css.php' );

        //2
        $html = $this->getView()->render(
            '/tz/html_pdf_norma_3/_form', [
                                            'model' => $model,
                                            'model1' => $model1,
                                            'model2' => $model2,
                                            'model3' => $model3,
                                            'model4' => $model4,
                                            'model5' => $model5,
                                            'model6' => $model6,
                                            'model7' => $model7,
                                            'model8' => $model8,
                                        ]
        );


        //  Тут можно подсмореть
        //          $html = ss($html);
        //          dd($html );


        ///
        ///  mPDF()
        ///

        unset( $mpdf );

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';
        $mpdf->WriteHTML( $html_css, 1 );

        ///////
        $mpdf->AddPage( 0, 0, 0, 0, 0, 10, 10, 20, 20 );
        //$html = '';
        $str_pos = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
        //       dd($model) ;
        $html .= MyHelpers::Barcode_HTML( 'TZ'.$model->id.'-'.$str_pos );
        //////////


        $mpdf->WriteHTML( $html, 2 );
        $html = '';


        unset( $html );

        $filename = 'tz'.date( 'd.m.Y H-i-s' ).'.pdf';
        $mpdf->Output( $filename, 'I' );


        return false;
    }


    /**
     * @param $tz_id
     * @return string
     */
    public function action_bus_report_pdf( $tz_id )
    {

        $model_auto = Tzautoelement::find()->with( 'sprwhtop' )->where( [ 'tz_id' => (integer)$tz_id ] )->asArray()->orderBy( 'id DESC' )->all();


        //dd($model_auto);


        $html = '';
        $html .= '<p>Автобусы</p>';
//        $html .= '<table cellpadding=0 cellspacing=0>';
        $html .= '<tr><th>';
        $html .= 'Т.З.';
        $html .= '</th><th>';
        $html .= '№';
        $html .= '</th><th>';
        $html .= 'Название';
        $html .= '</th><th>';
        $html .= 'Борт';
        $html .= '</th><th>';
        $html .= 'Гос.номер';
        $html .= '</th><th>';
        $html .= 'VIN';
        $html .= '</th></tr>';


        //            [tz_id] => 11
        //            [id] => 3
        //            [parent_id] => 6
        //            [name] => 380DS02
        //            [nomer_borta] => 2148
        //            [nomer_gos_registr] => 380DS02
        //            [nomer_vin] =>
        //            [tx] => 380DS02

        if ( isset( $model_auto ) && !empty( $model_auto ) ) {
            foreach ( $model_auto as $block_str ) {

                $html .= '<tr><td>';
                $html .= ''.$block_str[ 'tz_id' ];
                $html .= '</td>';
                $html .= '<td>';
                $html .= ''.$block_str[ 'id' ];
                $html .= '</td>';
                $html .= '<td>';
                $html .= ''.$block_str[ 'sprwhtop' ][ 'name' ];
                $html .= '</td>';
                $html .= '<td>';
                $html .= ''.$block_str[ 'nomer_borta' ];
                $html .= '</td>';
                $html .= '<td>';
                $html .= ''.$block_str[ 'nomer_gos_registr' ];
                $html .= '</td>';
                $html .= '<td>';
                $html .= ''.$block_str[ 'nomer_vin' ];
                //            $html.= '</td>';
                //            $html.= '<td>';
                //            $html.= ''.$block_str['tx'];
                $html .= '</td></tr>';

            }
        }

        $html .= '</table>';

        return $html;
    }


    /**
     * @param int $tz_id
     * @throws MpdfException
     * @throws BarcodeException
     */
    public function actionPdfreport( $tz_id = 11 )
    {

        ////////////// PDF - Barcode
        $html = '';
        $html .= MyHelpers::Barcode_HTML( "Tz ".$tz_id );
        ////////////// PDF - Barcode


        $html_css .= '
        table{            
               border: 1px solid #ddd;
               /*background-color: azure;*/                
               margin: 0px;
               padding: 0px;    
        }
        th{
               font-size: 11px;
               border: 0px solid ;
               background-color: #ddd;
               padding: 10px 10px;
        }
        td,tr{
               font-size: 10px;
               border: 1px solid #ddd;       
               padding: 5px 10px;      
        }
        .bar_code{
              position: absolute;
              top: 20px;
              right: 50px;                            
        }
        ';

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';
        $mpdf->WriteHTML( $html_css, 1 );

        $html .= '<h3>Техническое задание №'.$tz_id.'</h3>';


        //        $para = Yii::$app->request->queryParams;

        //        [start] => 2018-11-27
        //        [end] => 2018-11-29

        //d33($para);


        //        $model = Tz::find()->andFilterWhere( ['>=', 'dt_deadline', $para['start']] )->andFilterWhere( ['<', 'dt_deadline', $para['end']] )->asArray()->orderBy( 'id DESC' )->all();
        //        }

        //        dd($model);


        ///////////////////////// BUS АвтоБУсы в PDF
        $html .= $this->action_bus_report_pdf( $tz_id ); ///BUS АвтоБУсы в PDF


        $mpdf->WriteHTML( $html, 2 );
        $mpdf->AddPage();

        //////////////////////
        $mpdf->Output( 'mpdf.pdf', 'I' );

    }


    //////////////////////////////////


    ////////////////////////
    ///
    /**
     * Справочник элементов прямого списания
     *
     * @param $id
     * @return string
     */
    public function actionList( $id = 0 )
    {
        $model = html::dropdownList(
            'name_id', 0,
            ArrayHelper::map(
                Spr_glob_element::find()
                    ->where( [ 'parent_id' => (integer)$id ] )
                    ->orderBy( "name" )
                    ->all(),
                'id', 'name'
            ), [ 'prompt' => 'Выбор ...' ]
        );

        return $model;
    }


    /**
     * @param $id
     * @return string
     */
    public function actionListamort( $id )
    {
        $model = html::dropdownList(
            'name_id_amort', 0,
            ArrayHelper::map(
                Spr_globam_element::find()//->with('Spr_glob')
                ->orderBy( "name" )
                    ->where( [ 'parent_id' => (integer)$id ] )->all(), 'id', 'name'
            ),

            [ 'prompt' => 'Выбор ...' ]
        );

        return $model;
    }


    /**
     * Подтягивает из таблицы  Element
     *  логическое поле ДА-НЕТ
     *  INTELLIGENT ( Штрихкод, интелектуально устройство )
     *
     * @param $id
     * @return string
     */
    public function actionListamort_logic( $id )
    {
        $model = Spr_globam_element::find()->asArray()->where( [ 'id' => (integer)$id ] )->one();

        //dd($model['intelligent']);
        return $model[ 'intelligent' ];
    }


    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
     * @return string
     */
    public function actionList_ed_izm( $id = 0 )
    {
        $model = Spr_glob_element::find()->where( [ 'id' => (integer)$id ] )->one();

        return $model[ 'ed_izm' ];
    }


    /**
     * @param int $id
     * @return mixed
     */
    public function actionList_parent_id_amort( $id = 0 )
    {
        $model = post_spr_globam_element::find()->where( [ 'id' => (integer)$id ] )
            ->orderBy( 'name' )
            ->one();

        //        dd($model['ed_izm']);
        return $model[ 'parent_id' ];
    }

    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
     * @return string
     */
    public function actionList_parent_id( $id = 0 )
    {
        $model = Spr_glob_element::find()->where( [ 'id' => (integer)$id ] )->one();

        //        dd($model['ed_izm']);
        return $model[ 'parent_id' ];
    }


}
