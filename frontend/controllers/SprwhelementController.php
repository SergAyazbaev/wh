<?php

namespace frontend\controllers;

use frontend\models\postsprwhelement;
use frontend\models\Sklad;
use frontend\models\Sklad_inventory_cs;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhelement_old;
use frontend\models\Sprwhtop;
use frontend\models\ImageUpload;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;


/**
 * @property integer id
 * @property integer parent_id
 *
 */
class SprwhelementController extends Controller
{

    /**
     * $session
     */
    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $session->open();

        if (!Yii::$app->getUser()->identity) {
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
                    'delete' => [
                        'POST',
                        'GET',
                        'DELETE',
                    ],
                    'flags' => [
                        'GET',
                        'POST',
                    ],
                    'gos_number' => [
                        'GET',
                        'POST',
                    ],
                    'changenumber' => [
                        'POST',
                        'GET',
                    ],
                    'excel' => [
                        'POST'
                    ],
                    'create_all_from_txt' => [
                        'GET',
                        'POST',
                    ],
                ],
            ],
        ];
    }


    /**
     * @return string
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;
        ///
        /// PARA PRINT ALL
        ///
        $para_print = Yii::$app->request->get('print');
        $para_sort = Yii::$app->request->get('sort');
        $para_postsprwhelement = Yii::$app->request->get('postsprwhelement');
        //$post_rem_history = Yii::$app->request->get('post_rem_history');

        ///
        if (!isset($para_print) || (int)$para_print == 0) {
            #PARA PRINT FILTER
            Sklad::setPrint_param($para_postsprwhelement);
            #PARA SORT
            Sklad::setSort_param($para_sort);
        }

        // ddd( Yii::$app->user->identity);
        // ddd( Yii::$app->user->identity->id);


        $model = new Sprwhelement();

        $searchModel = new postsprwhelement();
        $dataProvider = $searchModel->search_with($para);

        $dataProvider->pagination->pageSize = 5;

        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */

        $dataProvider->setSort(
            [
                'attributes' => [
                    'id',
                    'parent_id',
                    'name',
                    'nomer_borta',
                    'nomer_gos_registr',
                    'nomer_traktor',
                    'nomer_vin',
                    'tx',
                    'delete_sign',
                    'final_destination',
                    'deactive',
                    'f_first_bort',
                    'date_create',
                ],
                'defaultOrder' => ['parent_id' => SORT_ASC, 'id' => SORT_ASC]
            ]
        );


        // ddd($dataProvider->getModels());

        //Запомнить РЕФЕР
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);

        return $this->render(
            'index', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
//                'para' => $para,
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
     * Creates a new  model.
     * -
     * @return mixed
     */
    public function actionCreate()
    {
        $max_value = Sprwhelement::find()->max('id');
        $max_value++;

        $model = new Sprwhelement();
        $model->id = $max_value;

        if (empty($model->parent_id)) {
            $model->parent_id = 0;
        }


        ///
        ///
        if ($model->load(Yii::$app->request->post())) {

            /// ПРОВЕРКА. Является ли группа (Автопарк) Целевым ПАРКОМ
            if (Sprwhelement::is_cs_group($model->parent_id)) {
                $model->final_destination = 1;
            }

            //ddd($sprav);

            $model->id = (integer)$model->id;
            $model->parent_id = (integer)$model->parent_id;

            $model->create_user_id = Yii::$app->getUser()->identity->id; // 'Id создателя',
            $model->date_create = date('d.m.Y H:i:s', strtotime('now'));

            $model->delete_sign = (integer)0; // Типа NO DEL


            //  * Функция приведения записей по полям ГОС и БОРТ в норму.
            //$model = $this->Normalise_GOS_BORT($model);


            //ddd($model);

            if ($model->save()) {
                return $this->redirect(['/sprwhelement/return_to_refer']);
            }
        }


        return $this->render(
            'create', [
                'model' => $model,
            ]
        );
    }


    /**
     * deactive. Специально для Модераторов,
     *=
     * Назира, Айдана
     * -
     *
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionDeactive($id)
    {
        $model = Sprwhelement::findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->final_destination = (int)$model->final_destination;
            $model->deactive = (int)$model->deactive;

            //ddd($model);

            if ($model->save(true)) {
                return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id));
                //return $this->redirect(['/sprwhelement']);
            }
        }

        return $this->render(
            '_form_deactivate',
            ['model' => $model,]
        );
    }


    /**
     * РЕДАКТИРОВАНИЕ сопровождается отметками от пользователе-редакторе и дате редактирования
     * Updates an existing sprtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|Response
     * @throws \Throwable
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');
        $model = Sprwhelement::findOne($id);

        ///
        if ($model->load(Yii::$app->request->post())) {
            //  * Функция приведения записей по полям ГОС и БОРТ в норму.
            //$model = SprwhelementController::Normalise_GOS_BORT($model);

            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->final_destination = (int)$model->final_destination;
            $model->deactive = (int)$model->deactive;
            $model->f_first_bort = (int)$model->f_first_bort;
            //

            //ddd($model);
            if ($model->save(true)) {
                // ddd($model->errors);
                return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id));
            }
        }

        return $this->render(
            '_form',
            ['model' => $model,]
        );
    }


    /**
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     * @throws \yii\mongodb\Exception
     */
    public function actionDelete()
    {
        $_id = Yii::$app->request->get('id');

        //ddd($_id);


        //MODEL
        $model = $this->findModel($_id);

        //
        //1. ИД. Найден в ПРОСТЫХ накладных по движению?
        //2. ИД. Найден в СТОЛБОВЫХ ЦС накладных ?
        //
        //ddd($this->is_exist_into_sklad($model['id']));
        //ddd($this->is_exist_into_inventory_cs($model['id']));

        if ($this->is_exist_into_sklad($model['id']) > 0) //НАХЕР!!!    || $this->is_exist_into_inventory_cs($model['id']) > 0)
        {
            return $this->render('_form_write_id', ['model' => $model]);
        }


        //
        if ($model) {
            if ($model->delete_sign == 1) {
                $model->delete_sign = 0;
                $model->delete_sign_user_id = Yii::$app->getUser()->identity->id;
                $model->date_delete = date('d.m.Y H:i:s', strtotime('now'));
            } else {
                $model->delete_sign = 1; // Типа УДАЛЯЕМ
                $model->delete_sign_user_id = Yii::$app->getUser()->identity->id; // Who Deleted This
                $model->date_delete = date('d.m.Y H:i:s', strtotime('now'));
            }


            if (!$model->delete()) {
                ddd($model->errors);
            }
//            if (!$model->save(false)) {
//                dd($model->errors);
//            }

        }

        // Возвтрат по РЕФЕРАЛУ
        $url_array = Yii::$app->request->headers;
        $url = $url_array['referer'];

        return $this->goBack($url);
    }


    /**
     * INDEX
     * =
     * @return string
     */
    public function actionIndex_change()
    {
        $para = Yii::$app->request->queryParams;


        $model = new Sprwhelement();

        $searchModel = new postsprwhelement();
        $dataProvider = $searchModel->search_with_change($para);
        //ddd($dataProvider->getModels());

        ///
        $dataProvider->pagination->pageSize = 5;

        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */
        $dataProvider->setSort(
            [
                'attributes' => [
                    'id',
                    'parent_id',
                    'name',
                    'nomer_borta',
                    'nomer_gos_registr',
                    'nomer_vin',
                    'tx',
                    'delete_sign',
                    'final_destination',
                    'deactive',
                    'f_first_bort',
                    'date_create',
                ],
                'defaultOrder' => ['parent_id' => SORT_ASC, 'id' => SORT_ASC]
            ]
        );

        //ddd($dataProvider->getModels());

        /**
         * Запомнить РЕФЕР
         */
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);

        ///
        return $this->render(
            'index', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );

    }


    /**
     * @return string
     */
    public function actionTab_all_ids()
    {
        $para = Yii::$app->request->queryParams;

        $searchModel = new postsprwhelement();
        $dataProvider = $searchModel->search_all_cs($para);


        return $this->render(
            'tab_all', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'para' => $para,
            ]
        );

    }


    /**
     * Displays a single sprtype model.
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render(
            'view', [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * FLAGS. Специально для Админов,
     *=
     * Жанель, Талагат
     * -
     *
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionFlags($id)
    {

        $model = Sprwhelement::findModel($id);

        ////
        ///
        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->final_destination = (int)$model->final_destination;

            //  * Функция приведения записей по полям ГОС и БОРТ в норму.
            // $model = SprwhelementController::Normalise_GOS_BORT($model);


            //ddd($model);

            if ($model->save(true)) {
                return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id));
                //return $this->redirect(['/sprwhelement']);
            }
        }


        return $this->render(
            '_form_flags',
            ['model' => $model,]
        );
    }


    /**
     * actionGos
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionGos()
    {
        $para = Yii::$app->request->get();


        $model = Sprwhelement::findModel($para['id']);

        if ($model->load(Yii::$app->request->post())) {
            //ddd($model);


            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->final_destination = (int)$model->final_destination;
            $model->deactive = (int)$model->deactive;
            $model->f_first_bort = (int)$model->f_first_bort;

            //  * Функция приведения записей по полям ГОС и БОРТ в норму.
            //$model = $this->Normalise_GOS_BORT($model);

//            ddd($model);

            if ($model->save(true)) {
                return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id));
                //return $this->redirect(['/sprwhelement']);
            }
        }

        return $this->render('_form_gos', ['model' => $model,]);
    }

    /**
     * Функция приведения записей по полям ГОС и БОРТ в норму.
     * =
     *
     * @param $model
     * @return mixed
     */
    public function Normalise_GOS_BORT($model)
    {
        ddd($model);

        $model->id = (int)$model->id;
        $model->parent_id = (int)$model->parent_id;

        $model->final_destination = (int)$model->final_destination;
        $model->deactive = (int)$model->deactive;

        $number_name = $model->name;
        $number_gos = $model->nomer_gos_registr;
        $number_bort = $model->nomer_borta;
        //            $number_vin = $model->nomer_vin;

        $number_name = (string)preg_replace('/\W/i', '', $number_name);
        $number_gos = (string)preg_replace('/\W/i', '', $number_gos);
        $number_bort = (string)preg_replace('/\W/i', '', $number_bort);


        // Если ГОС не пустой
        // Потом ГОС '/^[1-9a-zA-Z]/i'
        if (isset($model->nomer_gos_registr) && !empty($model->nomer_gos_registr)) {
            $number_gos = strtoupper(preg_replace('/{[A-Z]?}{[0-9]+}{[A-Z]+}/i', '$1$2$3', $number_gos));
            $model->nomer_gos_registr = strtoupper($number_gos);
            $model->name = strtoupper($number_gos);
        }

        // Если ГОС Пустой
        if (!isset($model->nomer_gos_registr) || empty($model->nomer_gos_registr)) {
            // Если NAME похоже на ГОС
            if (preg_match('/[A-Z]?[0-9]+[A-Z]+/i', $number_name)) {
                $model->nomer_gos_registr = strtoupper($number_name);
            }
        }

        // Если БОРТ Пустой
        if (!isset($model->nomer_borta) || empty($model->nomer_borta)) {
            // Если NAME похоже на БОРТ
            if (preg_match('/^[0-9]+$/i', $number_name)) {
                $model->nomer_borta = strtoupper($number_name);
            }
        }
        //ddd($model);

        //
        // ЕСЛИ учет в парке ведется по БОРТУ
        //
        if ((int)$model->f_first_bort == 1) {
            // Сначала БОРТ '/[^\d*]/i'
            if (isset($model->nomer_borta) && !empty($model->nomer_borta)) {
                $number_bort = (string)preg_replace('/^{[0-9]+}$/i', '$1', $number_bort);
                $model->name = strtoupper($number_bort);
            }
        }

        return $model;
    }


    /**
     * Gos_number. Специально для Модераторов,
     *=
     * Назира, Айдана
     * -
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionGos_number()
    {
        $para = Yii::$app->request->get(); //id
        $para_post = Yii::$app->request->post();

        if (isset($para['id'])) {
            $model = Sprwhelement::findModel($para['id']);
        } else {
            $model = new Sprwhelement();
        }


        ///
        ///
        if ($model->load(Yii::$app->request->get())) {

            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->final_destination = (int)$model->final_destination;
            $model->deactive = (int)$model->deactive;


            //ddd($model);

            if ($model->save(true)) {
                return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id));
                //return $this->redirect(['/sprwhelement']);
            }


        }


        if (isset($para_post['Sprwhelement'])) {
            //ddd($para_post);
            $model = Sprwhelement::findModelDouble($para_post['Sprwhelement'] ['id']);
        }


        if ($model->load($para_post)) {
            //ddd($model);
            //        'id' => '4788'
            //        'parent_id' => '26'
            //        'name' => '5033'
            //        'nomer_borta' => '5033'
            //        'nomer_gos_registr' => '066LF02'
            //        'nomer_vin' => ''
            //        'final_destination' => 1
            //        'tx' => ''
            //        'create_user_id' => 4
            //        'date_create' => '26.12.2019 11:39:26'
            //        'delete_sign' => 0
            //        'edit_user_id' => 0
            //        'delete_sign_user_id' => 0
            //        'deactive' => 1

//            $number_name = $model->name;
            $number_gos = $model->nomer_gos_registr;
//            $number_bort = $model->nomer_borta;
//            $number_vin = $model->nomer_vin;

            //ddd( $number_vin );
            if (isset($model->nomer_gos_registr) && !empty($model->nomer_gos_registr)) {
                $number_gos = (string)preg_replace('/^[\d*]$/i ', '', $number_gos);
                $model->name = $number_gos;
            }


            ////SAVE()
            if ($model->save(true)) {
                return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id));
                //return $this->redirect(['/sprwhelement']);
            }
        }


        //ddd($model);
        return $this->render('_form_gos_number', ['model' => $model]);
    }


    /**
     *actionUpload
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpload()
    {
        $para = Yii::$app->request->get();
        if (isset($para['id'])) {
            $id = $para['id'];
            $spr_wh = $this->findModel($id);
        }


        $model = new ImageUpload();

        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstance($model, 'imageFile');
            $spr_wh->imageFile = $model->uploadFile($file);
            ddd($spr_wh);
        }


        return $this->render('uploadImage', ['model' => $model]);
    }

    /**
     * PROC
     *
     * @param $array
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\mongodb\Exception
     */
    public function delete_from_array($array)
    {
        //ddd($array);

        foreach ($array as $item_array) {

            // Его MODEL
            $model = $this->findModel($item_array);

            // Его ИД. Найден в накладных?
            if ($this->is_exist_into_sklad($model['id']) <= 0) {

                if ($model) {
                    $model->delete_sign = 1; // Типа УДАЛЯЕМ
                    $model->delete_sign_user_id = Yii::$app->getUser()->identity->id; // Who Deleted This
                    $model->date_delete = date('d.m.Y H:i:s', strtotime('now'));

                    if (!$model->save(false)) {
                        dd($model->errors);
                    }

                }


            }
        }

        return true;
        //ddd(123);
    }


    /**
     * Обработчик формы. Опрос - на какой ИД меняем все НАКЛАДНЫЕ ДВИЖЕНИЙ и СТОЛБОВЫЕ НАКЛАДНЫЕ
     * -
     * Перезапись в накладных. Меняем ИД склада отправителя,ИД склада получателя и ДОМАШНИЙ номер;
     *=
     */
    public function actionSave_renew_id()
    {
        $para = Yii::$app->request->get();
        //        'Sprwhelement' => [
        //            'id' => '1205'
        //        'write_id' => '123'
        //    ]

        if (isset($para['Sprwhelement']['id']) && isset($para['Sprwhelement']['write_id'])) {
            $old_id = $para['Sprwhelement']['id'];
            $new_id = $para['Sprwhelement']['write_id'];

            //   'Sprwhelement' => [
            //        'id' => '4698'
            //        'write_id' => '4425'
            //    ]


            ///
            // Список ПРОСТЫХ НАКЛАДНЫХ с этим номером в качестве контрагента
            $array_all = Sklad::findArrayAll_by_idSprWhElement($old_id);

            /// Пробегаем по списку номеров накладных
            foreach ($array_all as $item) {
                /// Заменяем НАКЛАДНЫЕ ДВИЖЕНИЙ (ПРОСТЫЕ)  ID-old на ID-new
                if (!self::Save_renew_id($item, (int)$old_id, (int)$new_id)) {
                    echo $item . " =BAD\n";
                }
            }


            ///
            // Список ИНВЕНТАРИЗАЦИЙ INVENORY_CS STOLB накладных с этим номером в качестве контрагента
            $array_all_inventory = Sklad_inventory_cs::findArrayAll_by_idSprWhElement($old_id);

            /// Пробегаем по списку номеров накладных
            foreach ($array_all_inventory as $item) {
                /// Заменяем НАКЛАДНЫЕ ДВИЖЕНИЙ (ПРОСТЫЕ)  ID-old на ID-new
                if (!self::Save_renew_inventory_cs($item, (int)$old_id, (int)$new_id)) {
                    echo $item . " =BAD\n";
                    //                    ddd($new_id); //'4425'
                    //                    ddd($old_id); //'4698'
                    ddd($array_all_inventory);

                }
            }
            ///
            ///


        }

        return $this->redirect('/sprwhelement');
    }

    /**
     * Важно для перезаписи ИД Склада! Заменяем ID-old на ID-new
     *SKLAD
     * =
     *
     * @param $id
     * @param $old_id
     * @param $new_id
     * @return bool
     * @throws \yii\base\ExitException
     */
    public static function Save_renew_id($id, $old_id, $new_id)
    {

        $model = Sklad::findModelDouble($id);

        if ((int)$model->wh_debet_element == $old_id) {
            $model->wh_debet_element = (int)$new_id;
        }
        if ((int)$model->wh_destination_element == $old_id) {
            $model->wh_destination_element = (int)$new_id;
        }
        if ((int)$model->wh_cs_number == $old_id) {
            $model->wh_cs_number = (int)$new_id;
        }
        if ((int)$model->wh_home_number == $old_id) {
            $model->wh_home_number = (int)$new_id;
        }

        //ddd($model);

        if ($model->save(true)) {
            return true;
        }

        return false;
    }


    /**
     * Важно для перезаписи ИД Склада! Заменяем ID-old на ID-new
     * STOLB
     * =
     *
     * @param $id
     * @param $old_id
     * @param $new_id
     * @return bool
     * @throws \yii\base\ExitException
     */
    private static function Save_renew_inventory_cs($id, $old_id, $new_id)
    {
        $model = Sklad_inventory_cs::findModelDouble($id);
        $model->wh_destination_element = (int)$new_id;
        $model->wh_home_number = (int)$new_id;

        //ddd($model);

        //
//        if ((int)$model->wh_destination_element == $old_id) {
//            $model->wh_destination_element = (int)$new_id;
//        }
//        //
//        if ((int)$model->wh_home_number == $old_id) {
//            $model->wh_home_number = (int)$new_id;
//        }
        //    "wh_destination" : 26,
        //    "wh_destination_element" : 4697,
        //    "wh_debet_name" : "Алматыэлектротранс ТОО",
        //    "wh_debet_element_name" : "603LK02",
        //    "wh_destination_name" : "Алматыэлектротранс ТОО",
        //    "wh_destination_element_name" : "603LK02",


        //dd($model);

        if ($model->save(false)) {
            return true;
        }

        return false;
    }

    /**
     * Проверка. Существует в базе СКЛАД в накладныых этот номер или нет
     *=
     *
     * @param $spr_id
     * @return bool
     * @throws \yii\mongodb\Exception
     */
    protected function is_exist_into_sklad($spr_id)
    {
        // Сколько всего накладных с этим номером в качестве контрагента
        $count_all = Sklad::findCountAll_by_idSprWhElement($spr_id);
        return $count_all;
    }

    /**
     * Проверка. Существует этот номер или нет В СТОЛБОВЫХ - ЦС
     *=
     * @param $spr_id
     * @return bool
     * @throws \yii\mongodb\Exception
     */
    protected function is_exist_into_inventory_cs($spr_id)
    {
        // Сколько всего накладных с этим номером в качестве контрагента
        $count_all = Sklad_inventory_cs::findCountAll_by_idSprWhElement($spr_id);
        return $count_all;
    }


    /**
     * Move TO History (ERASE)
     * Удаляем в КОРЗИНУ (в резервную базу. В историю )
     */
    public function actionErase()
    {
        $para = Yii::$app->request->get();

        if (!isset($para['id']) || empty($para['id'])) {
            return $this->goBack('/');
        }

        //AS ARRAY !!!!
        $model_element = $this->findModelInt($para['id']);

        $model_top = Sprwhtop::findModelDouble($model_element->parent_id);

        //ddd($model_top);

        ///
        ///  Level ELEMENT to SAVE
        ///

        /// NEW
        $model_old_element = new Sprwhelement_old();

        $model_old_element->id = (int)$model_element['id'];
        $model_old_element->parent_id = (int)$model_element['parent_id'];
        $model_old_element->name = $model_element['name'];
        $model_old_element->tx = $model_element['tx'];

        $model_old_element->parent_name = $model_top->name;
        $model_old_element->parent_name_tx = $model_top->tx;

        $model_old_element->nomer_borta = $model_element['nomer_borta'];
        $model_old_element->nomer_gos_registr = $model_element['nomer_gos_registr'];
        $model_old_element->nomer_vin = $model_element['nomer_vin'];

        $model_old_element->date_delete = $model_element['date_delete'];
        $model_old_element->delete_sign_user_id = $model_element['delete_sign_user_id'];


        if (!$model_old_element->save(false)) {
            ddd($model_old_element->errors);
            //throw new NotFoundHttpException('Erase_WH_element.Не завершилось копирование в ИСТОРИЮ');
        }


        $model_element->delete();

        // Возвтрат по РЕФЕРАЛУ
        $url_array = Yii::$app->request->headers;
        $url = $url_array['referer'];

        return $this->goBack($url);
    }


    /**
     * Finds the sprtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $id
     * @return Sprwhelement|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sprwhelement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * @param $id
     * @return array|null|ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModelInt($id)
    {
        if (($model = Sprwhelement::find()
                ->where(['id' => (int)$id])
                ->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * ПАКЕТНОЕ Добавление в справочник
     * Используется текстовый файл с разделителем таб {\r\n\t}
     *
     * @return string|Response
     */
    public function actionCreate_all_from_txt()
    {
        $model = new Sprwhelement();
        $model->id = Sprwhelement::setNext_max_id();


        ////
        ///
        if ($model->load(Yii::$app->request->post())) {
//            ddd($model);

            $res_parent_id = $model->parent_id; //Фиксируем парент ид

            //            ddd($model);

            $array_str = $model['all_from_txt'];
            $array = explode("\r\n", $array_str);

            // Пустышки удаляет из массива
            $array = array_filter($array);
            // Двойники удаляет из массива
            $array_pieces = array_unique($array);
            //ddd($array_pieces);


            //            [0] => BATU HOLDING ТОО	063CH02
            //            [1] => BATU HOLDING ТОО	079CH02
            //            [2] => BATU HOLDING ТОО	091CH02

            ////
            //            $str = array_shift($array_pieces);
            //            dd(trim(strchr($str,"\t")));


            //            ddd($array_pieces);

            foreach ($array_pieces as $item) {
                if (!empty($item)) {

                    //dd( trim(strchr($item,"\t")));

                    $model = new Sprwhelement();
                    $model->id = Sprwhelement::setNext_max_id();

                    $model->parent_id = (integer)$res_parent_id;

                    $model->name = trim(strchr($item, "\t"));
                    $model->all_from_txt = '';
                    $model->tx = 'Доб. автоматически';

                    //dd($model);

                    if (!$model->save(true)) {
                        dd($model->errors);
                    }


                }

            }


        }


        return $this->render(
            'create_all_from_txt', [
                'model' => $model,
            ]
        );
    }


    /**
     * @return mixed
     */
    protected function renderList()
    {
        $searchModel = new postsprwhelement();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $method = Yii::$app->request->isAjax ? 'renderAjax' : 'render';

        return $this->$method(
            'index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Печатаем таблицу в Эксел
     *
     * @return string
     */
    public function actionExcel()
    {
        $para = Yii::$app->request->post();
        //
        $searchModel = new postsprwhelement();


        //
        // To EXCEL
        if (isset($para['print']) && $para['print'] == 1) {
            ///
            $post_rem_history['postsprwhelement'] = Sklad::getPrint_param();
            $post_rem_history['sort'] = Sklad::getSort_param();
            //
            $dataProvider = $searchModel->search($post_rem_history);

            ///ddd($post_rem_history);

            //
            $dataProvider->pagination = ['pageSize' => -1];

            //
            $spr_wh_top = ArrayHelper::map(Sprwhtop::find()->all(), 'id', 'name');
            //ddd($spr_wh_top);


            //ddd($dataProvider->getModels());

            $this->render(
                'print/print_excel', [
                    'dataModels' => $dataProvider->getModels(),
                    'spr_wh_top' => $spr_wh_top,
                ]
            );
        }

        return false;
    }


    /**
     * ПАКЕТНОЕ Добавление в справочник WH новых автобусов
     * =
     * Используем Подготовленный массив
     * -
     *
     * @param $array_park_bus
     * @return array
     * @throws NotFoundHttpException
     */
    public static function New_buses_from_array($array_park_bus)
    {
        if (!isset($array_park_bus)) {
            throw new NotFoundHttpException('SprWhElement. Массив не получен');
        }

        $how_math = 0;
        $find_math = 0;

        //    * Получить полный Список Групп Складов
        //    * и представить его в инверсии для поиска по ключу
        $wh_top_inverce = Sprwhtop::ArrayNames_inverce_id();

        //ddd($wh_top_inverce[]);

        //    * Получить полный Список ИМЕН
        //    * и представить его в инверсии для поиска по ключу
        $wh_names_inverce = Sprwhelement::ArrayNames_inverce_id();


        //ddd($array_park_bus);
        foreach ($array_park_bus as $item) {

            if (isset($wh_names_inverce[$item[0]]) && !empty($wh_names_inverce[$item[0]])) {
                if (isset($wh_names_inverce[$item[1]]) && !empty($wh_names_inverce[$item[1]])) {
                    $find_math++;
                    continue;
                }
            }

            $model = new Sprwhelement();
            $max_value = Sprwhelement::find()->max("id");
            $max_value++;

            $model->id = $max_value;

            //ddd($item[0]);

            //                ddd( $wh_top_inverce );

            //'Адылет Авто'


            if (!isset($wh_top_inverce[$item[0]])) {
                throw new NotFoundHttpException(' В справочнике АвтоПАРКОВ Не найдено => "' . $item[0] . '"');
            }

            $AP_id = (int)$wh_top_inverce[$item[0]];


            if ($AP_id == 0 || empty($AP_id)) {
                throw new NotFoundHttpException('Залито' . $how_math . '\r Не найдено Название = ' . $item[0]);
            }


            $model->parent_id = (int)$wh_top_inverce[$item[0]];
            $model->name = $item[1];    //   1 => 'B413CDO'

            $model->create_user_id = Yii::$app->getUser()->identity->id; // 'Id создателя',
            $model->date_create = date('d.m.Y H:i:s', strtotime('now'));
            $model->delete_sign = (integer)0; // Типа NO DEL


            if (!$model->save(true)) {
                $find_math++;
                continue;
                //ddd( $model->errors );
            } else {
                $how_math++;
            }

            //				$max_value ++;
        }

        //ddd($model);


        $array = [
            'how_math' => $how_math,
            'find_math' => $find_math,
        ];

        return $array;

        //return $find_math;
        //return $how_math;
    }


    /**
     * Ремонт Sprwhelement
     * */
    public function actionRemont_gos_name()
    {
        ///
        $num_ids = ArrayHelper::getColumn(
            Sprwhelement::find()
                ->select(['id'])
                ->where(
                    ['AND',
                        ['name' => ''],
                        ['!=', 'nomer_gos_registr', null]
                    ]
                )
                ->asArray()
                ->all(), 'id'
        );

        ///
        foreach ($num_ids as $num) {
            $model = Sprwhelement::find()->where(['==', 'id', $num])->one();
            $model->name = $model->nomer_gos_registr;

            if (!$model->save(true)) {
                $err[] = $model->errors;
            }
        }

        ///
        if (isset($err)) {
            ddd($err);
        }

        echo "OK";

        return false;
    }


    /**
     * Ремонт Sprwhelement 2
     * */
    public function actionRemont_gos2()
    {

        ///
        foreach (Sprwhelement::find()->each() as $model) {

            //$str_bort = preg_replace('/~\d{2,}$/ui', '$1', $model->name);

            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;


            if (!$model->save(true)) {
                $err[] = $model->errors;
            }

        }

        ///
        if (isset($err)) {
            ddd($err);
        }

        return "OK";
    }


    public function actionDouble_whelement()
    {
        ddd(123);

        ///
        $num_ids = ArrayHelper::getColumn(
            Sprwhelement::find()
//        $num_ids = Sprwhelement::find()
                ->where(['!=', 'nomer_borta', ''])
                ->orderBy(['nomer_borta'])
                ->all(), 'nomer_borta'
        );


        ddd($num_ids);
//        'nomer_borta' => ''
//        'nomer_gos_registr' => ''


        ///
        if (isset($err)) {
            ddd($err);
        }

        echo "OK";

        return false;
    }


    /**
     * gos_bort_copypast
     * =
     * */
    public function actionGos_bort_copypast()
    {
        $para = Yii::$app->request->post();

        ////////
        $array = explode("\r\n", $para['Sprwhelement']['find_name']);

//    ddd($array);
//    0 => 'ТП №1    #1151'
//    1 => 'ТП №1    #1152'
//    2 => 'ТП №1    #1153'
//    3 => 'ТП №1    #1154'
//    4 => 'ТП №1    #1155'
//    5 => 'ТОО \"БaTy trаvel компаниясы\"    791YB02'

//        (5066)860LF02


        foreach ($array as $item) {
            $array_str = explode("\t", $item);

            //ddd($array_str);
            //    0 => 'ТП №1'
            //    1 => '#1151'

            $name_gos = '';
            $name_bort = '';
            //            $name_poisk = '';


            if (!empty($item)) {


                if (!empty($array_str['0']) && !empty($array_str['1'])) {

                    $name_tha = (string)preg_replace('/^[\w*]/i', '', $array_str['0']);

                    $name_poisk = (string)$array_str['1'];
                    //ddd($name_poisk);


                    ////
                    if (substr($name_poisk, 0, 1) != "(") {
                        if (strlen($name_poisk) <= 5) {
                            $name_gos = '';
                            $name_bort = $name_poisk;
                        }
                        if (strlen($name_poisk) > 5) {
                            $name_gos = $name_poisk;
                            $name_bort = '';
                        }
                    }

                    ////
                    if (substr($name_poisk, 0, 1) == "(") { //ddd(111212);
                        $name_bort = preg_replace('/\((\d*)\)(.*)/ui', '$1', $name_poisk);
                        $name_gos = preg_replace('/\((\d*)\)(.*)/ui', '$2', $name_poisk);
                    }

                    ////
                    if (substr($name_bort, 0, 1) == "#") {
                        $name_bort = preg_replace('/#/ui', '', $name_bort);
                    }
                    //ddd($name_poisk);


                    if ($name_gos != '' || $name_bort != '') {
                        $array_error[] = $this->updatePosition($name_tha, $name_gos, $name_bort);
                    }


                }
            }
        }
        ///


        //
//        if (!isset($array_result)) {
//            throw new NotFoundHttpException('Нет данных для заливки в базу.');
//        }


        if (isset($array_error)) {
            echo "Ошибки \r\r";
            ddd($array_error);
        }


        return $this->redirect('/sprwhelement');
    }


    /**
     * Проба найти и разу запись в СПРАВОЧНИК
     *=
     *
     * @param $name_tha
     * @param $name_gos
     * @param $name_bort
     * @return bool
     */
    function updatePosition($name_tha, $name_gos, $name_bort)
    {

        ///
        $top_id_model = Sprwhtop::find()
            ->where(['like', 'name_tha', $name_tha])
            ->one();
        if (!isset($top_id_model)) {
            return 'Ошибка. Не найдено название парка.; Гос=' . $name_gos . ' Борт=' . $name_bort;
        }
        $parent_id = $top_id_model['id'];


        //ddd($top_id_model);

        /**
         *  Пробуем по ГОС
         *    "nomer_borta" : "",
         *    "nomer_gos_registr" : "",
         * */
        if (isset($name_gos) && !empty($name_gos)) {
            $model = Sprwhelement::find()
                ->where(
                    ['AND',
                        ['=', 'parent_id', $parent_id],
                        ['OR',
                            ['=', 'name', $name_gos],
                            ['=', 'nomer_gos_registr', $name_gos]
                        ]
                    ]
                )
                ->one();
        }

//        ddd($name_gos);
//        ddd($model);


        /**
         *    /// Пробуем БОРТ
         */
        if (!isset($model) || empty($model)) {


            ///
            /// Пробуем БОРТ
            if (isset($name_bort) && !empty($name_bort)) {
                $model = Sprwhelement::find()
                    ->where(
                        ['AND',
                            ['=', 'parent_id', $parent_id],
                            ['OR',
                                ['like', 'name', $name_bort],
                                ['=', 'nomer_borta', $name_bort]
                            ]
                        ]
                    )
                    ->one();
            }
        }


        //ddd($model);


        if (!isset($model)) {
            return 'Ошибка. Не найден НИ БОРТ, НИ ГОС; Гос=' . $name_gos . ' Борт=' . $name_bort;
        }


        $model->id = (int)$model->id;
        $model->parent_id = (int)$model->parent_id;

        //$model->name='';  // НЕ ТРОГАЕМ

        $model->fullName = '';
        $model->nomer_borta = $name_bort;
        $model->nomer_gos_registr = $name_gos;


        if (!$model->save(true)) {
            return 'Ошибка. Не произведена запись; Гос=' . $name_gos . ' Борт=' . $name_bort;
        }


        return 'OK. Гос=' . $name_gos . ' Борт=' . $name_bort;
    }


    /**
     * КРАЙНЯЯ ИНВЕНТАРИЗАЦИЯ +приход -расход
     * -
     * временно : по номеру списку активных складов ИНВЕНТАРИЗАЦИИ
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\mongodb\Exception
     */
    function actionElement_remont()
    {

        ///
        ///  Список всех ID
        ///
        /// final_destination=1
        /// flag=1
        ///
        $list_ids = ArrayHelper::getColumn(
            Sprwhelement::find()
                ->select(['id'])
                ->where(
                    ['AND',
                        ['=', 'final_destination', 1],
                        ['=', 'flag', 1]
                    ]
                )
                ->all(), 'id'
        );

        //ddd( $list_ids );


        foreach ($list_ids as $item) {
            $cc = $this->actionCahge_gos_bort($item);
            if ($cc != 0) {
                $err[$item][] = $cc;
            }

//            $xx = $this->actionNonAcceptable_id( $item );
//            if ( $xx != 0 ) {
//                echo "<br>".$xx;
//                $this->actionDelete_id_flag( $xx );
//                //ddd($xx);
//            }
        }


        if (isset($err)) {
            ddd($err);
            $this->delete_from_array($err);
        }

        return $this->redirect(['/sprwhelement']);
    }



    ////////////////

    /**
     * Меню выбора. КОПИПАСТ для УКАЗАНИЯ ГЛАВНОГО Борт или Гос
     * =
     *
     * @return string
     */
    public function actionCreate_new_bort_gos()
    {
        $model = new Sprwhelement();

        $spr_wh_top = ArrayHelper::map(
            Sprwhtop::find()
                ->orderBy('name')
                ->all(),
            'id', 'name'
        );

        //ddd($spr_globam_element);


        return $this->render(
            'load_copypast/_form_load',
            [
                'model' => $model,
                'spr_wh_top' => $spr_wh_top


            ]
        );
    }


    /**
     * БОРТ-ГОС копипаст
     * -
     *
     * @return string
     */
    function actionElement_remont_copypast()
    {
        $para = Yii::$app->request->post();
        //ddd( $para );


        ///Ид названия устройства в Справочнике
//        $id = $para[ 'Sprwhelement' ][ 'id' ];

        ////
        ////
        if (!isset($para['Sprwhelement']['name']) || empty($para['Sprwhelement']['name'])) {
            return $this->render('/');
        }

        ////////
        $array = explode("\r\n", $para['Sprwhelement']['name']);
        //ddd($array);

        foreach ($array as $item_array) {
            $comm = explode("\t", $item_array);
            //    ddd($comm);
            //    0 => '635'
            //    1 => 'борт'

            $model = Sprwhelement::findModelDouble($comm[0]);

            if (isset($model)) {
                if ($comm[1] == 'борт') {
                    $model->f_first_bort = (int)1;
                }
                if ($comm[1] == 'гос') {
                    $model->f_first_bort = (int)0;
                }
                //ddd($model);

                $model->save(true);


            } else {
                if (!empty($item_array)) {
                    $err[] = $item_array;
                }
            }


        }

        if (isset($err) && !empty($err)) {
            return $this->renderContent('Сообщение! ' . implode('<br>', $err));
        }

        return $this->renderContent('Сообщение!  OK');
        //return $this->redirect( [ '/sprwhelement' ] );
    }


    /**
     * Процедура в цикле по одному ИД
     *
     * @param $item
     * @return array|int
     */
    function actionCahge_gos_bort($item)
    {
        $model = Sprwhelement::findModelDouble($item);

        //  * Функция приведения записей по полям ГОС и БОРТ в норму.
        //$model = $this->Normalise_GOS_BORT($model);


        /// FLAG=0
        $model->flag = 0;

        //
        if (isset($model->date_create)) {
            $model->dc_timestamp = strtotime($model->date_create);
        } else {
            $model->dc_timestamp = strtotime('now');
        }
        //ddd($model);


        if (!$model->save(true)) {
            return $model->errors;
        }

        return 0;
    }


    /**
     * Процедура в цикле по одному ИД
     *
     * @param $item
     * @return int
     */
    function actionNonAcceptable_id($item)
    {
        $model = Sklad::find()
            ->select(['id'])
            ->where(
                ['OR',
                    ['=', 'wh_home_number', $item],
                    ['=', 'wh_cs_number', $item],

                    ['=', 'wh_debet_element', $item],
                    ['=', 'wh_destination_element', $item],

                    ['=', 'wh_dalee_element', $item],
                ]
            )
            ->one();


        if (!isset($model)) {
            return (int)$item;
        }

        return 0;
    }

    /**
     * Процедура в цикле по одному ИД
     *
     * @param $item_id
     * @return int
     */
    function actionDelete_id_flag($item_id)
    {
        $model = Sprwhelement::find()
            ->where(['=', 'id', $item_id])
            ->one();

        if (isset($model)) {
            //$model->delete_sign = (int)1;
            $model->delete_sign = (int)0;

            //ddd($model);

            if (!$model->save(true)) {
                ddd($model->errors);
            }
        }

        return 0;
    }


    /**
     * Вспомогательный Хелпер. Ставит ФЛАГ в значение поднят, 1
     * -
     * Mongo. Монго. Флаг прописан по всей коллекции Склад одной командой
     * =
     *
     * @return bool
     */
     function actionMongo_flag_on()
     {
         try {
             //////////$collection = Yii::$app->mongodb->getDatabase("wh_develop")->getCollection('sprwh_element');
             $collection = Yii::$app->mongodb->getDatabase(Yii::$app->params['vars'])->getCollection('sprwh_element');

             //            ddd(Yii::$app->mongodb);
             //            ddd($collection);

             $collection->update(
                 [],
                 ['$set' =>
                     [
                         'flag' => 1,
                     ]
                 ],
                 ['multi' => true, 'timestamps' => true]
             );
         } catch (Exception $ex) {
             echo $ex->getMessage();
             return false;
         }

         //ddd($collection);

         return 'OK';
     }

     function actionMongo_flag_off()
     {
         try {
             //////////$collection = Yii::$app->mongodb->getDatabase("wh_develop")->getCollection('sprwh_element');
             $collection = Yii::$app->mongodb->getDatabase(Yii::$app->params['vars'])->getCollection('sprwh_element');

             //            ddd(Yii::$app->mongodb);
             //            ddd($collection);

             $collection->update(
                 [],
                 ['$set' =>
                     [
                         'flag' => 0,
                     ]
                 ],
                 ['multi' => true, 'timestamps' => true]
             );
         } catch (Exception $ex) {
             echo $ex->getMessage();
             return false;
         }

         //ddd($collection);

         return 'OK';
     }


    /**
     * Процедура ЗАМЕНА ГОС НОМЕРА
     *=
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionChangenumber()
    {
        ///
        $id = Yii::$app->request->get('id'); // _id
        //
        $model = Sprwhelement::findModel($id);

        //  * Функция приведения записей по полям ГОС и БОРТ в норму.
        // $model = $this->Normalise_GOS_BORT($model);


        /// FLAG=0
        $model->flag = 0;


        if ($model->load(Yii::$app->request->get())) {

            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->edit_user_id = Yii::$app->getUser()->identity->id; // 'Id REDACTOR',
            $model->date_edit = date('d.m.Y H:i:s', strtotime('now'));


            //ddd($model);


            if (Yii::$app->request->get('button-click') == 'name_change') {
                ddd($para);
            }


            ///
            ///  Установка нового Гос.номера на Автобус
            ///
            if (Yii::$app->request->get('button-click') == 'bort_change') {
                $old_str = 'нет данных';
                $str = 'нет данных';

                if (isset($model->oldAttributes['name']) && !empty($model->oldAttributes['name'])) {
                    $old_str = $model->oldAttributes['name'];
                }

                if (isset($model->oldAttributes['nomer_gos_registr']) && !empty($model->oldAttributes['nomer_gos_registr'])) {
                    $old_str = $model->oldAttributes['nomer_gos_registr'];
                }

                if (isset($model->name) && !empty($model->name)) {
                    $str = $model->name;
                }

                if (isset($model->nomer_gos_registr) && !empty($model->nomer_gos_registr)) {
                    $str = $model->nomer_gos_registr;
                }


                $str .= ' (' . date('d.m.Y') . ')';
                $model->tx = $model->tx . "\r\n БОРТ снят: " . $old_str . ", установлен: " . $str;
            }


            ///
            ///  Установка нового Гос.номера на Автобус
            ///
            if (Yii::$app->request->get('button-click') == 'gos_change') {

//                ddd(111);

                $old_str = 'нет данных';
                $str = 'нет данных';

                //$model->oldAttributes['dt_create_timestamp']),

                if (isset($model->oldAttributes['name']) && !empty($model->oldAttributes['name'])) {
                    $old_str = $model->oldAttributes['name'];
                }

                if (isset($model->oldAttributes['nomer_gos_registr']) && !empty($model->oldAttributes['nomer_gos_registr'])) {
                    $old_str = $model->oldAttributes['nomer_gos_registr'];
                }

                if (isset($model->name) && !empty($model->name)) {
                    $str = $model->name;
                }

                if (isset($model->nomer_gos_registr) && !empty($model->nomer_gos_registr)) {
                    $str = $model->nomer_gos_registr;
                }

                $str .= ' (' . date('d.m.Y') . ')';
                $model->tx = $model->tx . "\r\n ГОС снят: " . $old_str . ", установлен: " . $str;
            }


            ///
            ///  Установка нового VIN.номера на Автобус
            ///
            if (Yii::$app->request->get('button-click') == 'vin_change') {
                $old_str = 'нет данных';
                $str = 'нет данных';

                //$model->oldAttributes['dt_create_timestamp']),

                if (isset($model->oldAttributes['name']) && !empty($model->oldAttributes['name'])) {
                    $old_str = $model->oldAttributes['name'];
                }

                if (isset($model->oldAttributes['nomer_gos_registr']) && !empty($model->oldAttributes['nomer_gos_registr'])) {
                    $old_str = $model->oldAttributes['nomer_gos_registr'];
                }

                if (isset($model->name) && !empty($model->name)) {
                    $str = $model->name;
                }

                if (isset($model->nomer_gos_registr) && !empty($model->nomer_gos_registr)) {
                    $str = $model->nomer_gos_registr;
                }


                $str .= ' (' . date('d.m.Y') . ')';
                $model->tx = $model->tx . "\r\n VIN снят: " . $old_str . ", установлен: " . $str;

                // ddd($model);

            }


            //  * Функция приведения записей по полям ГОС и БОРТ в норму.
            //$model = $this->Normalise_GOS_BORT($model);


            // Name
            if ((int)$model->f_first_bort <> 1) {
                $model->name = $model->nomer_gos_registr;
            } else {
                $model->name = $model->nomer_borta;
            }

            // ddd($model);


            if ($model->save(true)) {
                //ddd($model);
                return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id));
                //return $this->redirect(['/sprwhelement']);
            }
        }


        ///
        return $this->render(
            '_form_change', [
                'model' => $model,
            ]
        );

    }


}
