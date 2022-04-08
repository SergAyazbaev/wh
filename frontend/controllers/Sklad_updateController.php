<?php

namespace frontend\controllers;

use frontend\models\Barcode_pool;
use frontend\models\post_spr_glob_element;
use frontend\models\post_spr_globam_element;
use frontend\models\SignupForm;
use frontend\models\Sklad;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use frontend\models\Tz;
use frontend\components\MyHelpers;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;
use yii\base\ExitException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 *
 */
class Sklad_updateController extends Controller
{
    public $sklad;

    /**
     * $session
     *
     */
    public function init()
    {
        parent::init();

        ///
        if (!Yii::$app->getUser()->identity) {
            throw new HttpException(411, 'Необходима авторизация', 5);
        }

        /// 3000 Секунд 30000
        //if (strtotime('now') - Yii::$app->getUser()->getIdentity(true)->start_time > 3000) {
        if (strtotime('now') - Yii::$app->getUser()->getIdentity(true)->start_time > 30000) {
            Yii::$app->user->logout();
            //            $this->redirect('/site/login');
            throw new HttpException(400, 'Бездействие в накладной 50 минут. Сеанс остановлен. ', 1);

        } else {
            ///   Пишем Время Захода под своим логином
            SignupForm::TimeStartLogin(Yii::$app->user->getId());
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
                    'in' => [
                        'GET'],
                    // Главная страница
                    'update' => [
                        'GET',
                        'POST',
                    ],
                    // Редактирование НАКЛАДНОЙ
                    'create_new' => [
                        'GET',
                        'POST',
                    ],
                    // КНОПКА создать новую наклданую
                    'prihod2' => [
                        'GET',
                        'POST',
                    ],
                    // Принятие накладной из ПРИХОДА
                    'createfromtz' => [
                        'GET',
                        'POST',
                    ],
                    // Принятие накладной из Createfromtz
                    'createfrom_shablon' => [
                        'GET',
                        'POST',
                    ],
                    // Принятие накладной из Createfrom_shablon
                    'create_from_cs' => [
                        'GET'],
                    // Принятие накладной из Createfrom_shablon
                    'from_cs' => [
                        'POST',
                        'GET',
                    ],
                    // Принятие накладной из ЦС
                    'copy-to-transfer' => [
                        'GET'],
                    // ПЕРЕДАЧА В БУФЕР ОБМЕНА CopyToTransfer
                    'index' => [
                    ],
                    'create' => [
                    ],
                    'delete' => [
                    ],
                    'view' => [
                    ],
                    //  'delete' => ['POST', 'DELETE'],
                ],
            ],
        ];

    }


    /**
     * Редактирование Накладной -id-
     * =
     */
    public function actionUpdate_id()
    {
        //
        $id = Yii::$app->request->get('id');
        //
        $model = Sklad::findModelDouble($id);

        //ddd($model->wh_home_number);

        //
        //Список складов в группе
        $list_whtop = ['' => 'Выбрать ...'] + ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');
        $list_whtop_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_debet_top);
        $list_dest_whtop_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_destination);
        $list_dalee_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_dalee);

        //
        $spr_globam_element = Spr_globam_element::name_plus_id();

        //
        $sklad = (int)$model->wh_home_number;

        $alert_mess = '';

        ///
        ///
        ///   //////// ПРЕД СОХРАНЕНИЕМ
        ///
        if ($model->load(Yii::$app->request->post())) {


            ///
            $spr_debet_full = Sprwhelement::findFullArray($model->wh_debet_element);
            $spr_dest_full = Sprwhelement::findFullArray($model->wh_destination_element);

            //ddd($spr_debet_full);

            //
            $model->dt_create_timestamp = (int)strtotime($model->dt_create);
            $model->dt_update_timestamp = (int)strtotime('now');
            //
            $model->wh_debet_top = (int)$model->wh_debet_top;
            $model->wh_debet_element = (int)$model->wh_debet_element;
            $model->wh_debet_name = $spr_debet_full['top']['name'];
            $model->wh_debet_element_name = $spr_debet_full['child']['name'];

            //
            $model->wh_destination = (int)$model->wh_destination;
            $model->wh_destination_element = (int)$model->wh_destination_element;
            $model->wh_destination_name = $spr_dest_full['top']['name'];
            $model->wh_destination_element_name = $spr_dest_full['child']['name'];


            $model->wh_dalee = (int)$model->wh_dalee;
            $model->wh_dalee_element = (int)$model->wh_dalee_element;


            //ddd($model);


            ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
            ///  ТАБ 1
            $model->array_tk_amort = Sklad::setArraySort1($model->array_tk_amort);
            //					$model->array_tk_amort = Sklad::setArrayClear( $model->array_tk_amort );
            ///  ТАБ 2
            $model->array_tk = Sklad::setArraySort2($model->array_tk);
            $model->array_tk = Sklad::setArraySort2($model->array_tk);


            //  Приводим ключи в прядок! УСПОКАИВАЕМ ОЧЕРЕДЬ
            $model->array_tk_amort = Sklad::setArrayToNormal($model->array_tk_amort);

            //            $model->array_tk = Sklad::setArrayToNormal($model->array_tk);
            //            $model->array_casual = Sklad::setArrayToNormal($model->array_casual);


            unset($model->dt_transfered_date);
            unset($model->dt_transfered_user_id);
            unset($model->dt_transfered_user_name);

            //ddd($model);

            if (!$model->save(true)) {
                $alert_mess = 'Сохранение.НЕ ВОЗМОЖНО.';
            } else {
                $alert_mess = 'Сохранение. OK';
            }

            //ddd($model);


            $this->redirect('/stat_svod/index_svod');
        }


        ////
        return $this->render(
            '_form_adm', [
            'model' => $model,

            'list_whtop' => $list_whtop,
            'list_whtop_element' => $list_whtop_element,
            'list_dest_whtop_element' => $list_dest_whtop_element,

//                'list_dalee' =>  $list_dalee,
            'list_dalee_element' => $list_dalee_element,

            'sklad' => $sklad,
//            'items_auto' => $items_auto,
//            'tz_head' => $tz_head,
            //                    'erase_array' => $erase_array,

            'alert_mess' => $alert_mess,
            'spr_globam_element' => $spr_globam_element,

        ]);


//        ddd($model);
//        ddd($id);


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
     * Редактирование Накладной
     * =
     * ЦС внутри нашей накладной  по признаку
     * $cs_number_id
     * -
     *
     * @param $id
     *
     * @return string|Response
     * @throws ExitException
     * @throws HttpException
     * @throws \Throwable
     */
    public function actionUpdate($id)
    {
        //T O D O:SKLAD = Update / Привести к правильному виду тип-значение в INT, FLOAT

        $para = Yii::$app->request->queryParams;
        // ddd($para);


        //Запомнить РЕФЕР
        //Sklad::setPathRefer(Yii::$app->request->referrer);

        ///
        //$para_post = Yii::$app->request->post();
        $para_post_contact_button = Yii::$app->request->post('contact-button');
        $para_post_sklad = Yii::$app->request->post('Sklad');
        //ddd(Yii::$app->request->post());


        if (isset($para['otbor']) && !empty($para['otbor'])) {
            if (!Sklad::setSkladIdActive($para['otbor'])) {
                throw new HttpException(307, '$_SESSION1 Не подключен.  Sklad=0');
            }
        }


        $sklad = Sklad::getSkladIdActive();    // Активный склад (_SESSION)
        //ddd($sklad);


        if (!isset($sklad) || empty($sklad)) {
            throw new HttpException(307, '$_SESSION3 Не подключен.  Sklad=0');
        }


//        ddd($id);

        ///
        /// MODEL
        ///
        $model = Sklad::findModel($id);  /// this is  _id !!!!! //$model->getDtCreateText()


        //ddd($model);

//        $model->scenario = Sklad::SCENARIO_DEFAULT;

        ///
        if (!is_object($model)) {
            throw new HttpException(411, 'Нет такой накладной', 5);
        }


        /// Автобусы ЕСТЬ?
        if (isset($model['array_bus']) && !empty($model['array_bus'])) {
            $items_auto = Sprwhelement::findAll_Attrib_PE(
                array_map('intval', $model['array_bus'])
            );
        } else {
            $items_auto = []; // ['нет автобусов'];
        }


        /// Получаем ТехЗадание. ШАПКА
        if ($model->tz_id) {
            $tz_head = Tz::findModelDoubleAsArray((int)$model->tz_id);
        } else {
            $tz_head = [
            ];
        }


        ///////////////////////////////////////////////////////
        ///
        ///  КНОПКА EXIT TO REFER
        ///
        if ($para_post_contact_button == 'exit_to_refer') {
            //Возврат по реферу, сохраненному в SKLAD/IN . REFER
            return $this->redirect(Sklad::getPathRefer());
        }


        ///////////////////////////////////////////////////////
        ///
        ///  КНОПКА ПЛЮС. ДОБАВКА ЕЩЕ ОДНОЙ НАКЛАДНОЙ К ЭТОЙ
        ///
        /// contact-button
        /// 1. add_aray
        /// 2. erase_aray
        ///
        if ($para_post_contact_button == 'add_button') {

            $num_next_sklad = $para_post_sklad['add_button'];

            // Сложили ВСЕ МАССИВЫ ИЗ ДВУХ НАКЛАДНЫХ
            $different_nakl = Sklad::findArray_by_id_into_sklad($sklad, $num_next_sklad);
            ///  ERROR
            if (!isset($different_nakl) || empty($different_nakl)) {
                throw new HttpException(411, 'Обнаружены ошибки сложения накладных. Сохранение не возможно ', 5);
                //return $this->redirect(Sklad::getPathRefer());
            }


            if (isset($model['array_tk_amort']) && $different_nakl['array_tk_amort']) {
                $model->array_tk_amort = Sklad::AddNaklad_to_Naklad($model['array_tk_amort'], $different_nakl['array_tk_amort']);
            }

            if (isset($model['array_tk']) && $different_nakl['array_tk']) {
                $model->array_tk = Sklad::AddNaklad_to_Naklad($model['array_tk'], $different_nakl['array_tk']);
            }

            if (isset($model['array_casual']) && $different_nakl['array_casual']) {
                $model->array_casual = Sklad::AddNaklad_to_Naklad($model['array_casual'], $different_nakl['array_casual']);
            }

            ///
            /// Кличество строк
            $count =
                (!empty($model['array_tk_amort']) ? count($model['array_tk_amort']) : 0) +
                (!empty($model['array_tk']) ? count($model['array_tk']) : 0) +
                (!empty($model['array_casual']) ? count($model['array_casual']) : 0);
            $model->array_count_all = $count;

            if ($model->save(true)) {
                return $this->redirect(Yii::$app->request->referrer);
                //return $this->redirect(Sklad::getPathRefer());
            } else {
                throw new HttpException(411, 'Обнаружены двойники. Сохранение не возможно ', 5);
            }
        }


        ///
        ///  КНОПКА УДАЛЕНИЕ СТРОК в массивах
        ///
        if (Yii::$app->request->post('contact-button') == 'erase_button') {
            $array = Yii::$app->request->post('Sklad');

            //0
            if (isset($array['erase_array'][0][1])) {
                if (is_array($model['array_tk_amort']) && (int)$array['erase_array'][0][1] > 0) {
                    if (isset($array['erase_array'][0][0]) && $array['erase_array'][0][0] > 0) {
                        $start = (int)$array['erase_array'][0][0] - 1;
                    } else {
                        $start = 0;
                    }

                    $stop = (int)$array['erase_array'][0][1] - $start;
                    $array = (array)$model['array_tk_amort'];
                    array_splice($array, $start, $stop);
                    $model['array_tk_amort'] = array_filter($array);
                }
            }

            //1
            if (isset($array['erase_array'][1][1])) {
                if (is_array($model['array_tk']) && (int)$array['erase_array'][1][1] > 0) {
                    if (isset($array['erase_array'][1][0]) && $array['erase_array'][1][0] > 0) {
                        $start = (int)$array['erase_array'][1][0] - 1;
                    } else {
                        $start = 0;
                    }

                    $stop = (int)$array['erase_array'][1][1] - $start;
                    $array = (array)$model['array_tk'];
                    array_splice($array, $start, $stop);

                    $model['array_tk'] = array_filter($array);
                }
            }

            //2
            if (isset($array['erase_array'][2][1])) {
                if (is_array($model['array_casual']) && (int)$array['erase_array'][2][1] > 0) {
                    if (isset($array['erase_array'][2][0]) && $array['erase_array'][2][0] > 0) {
                        $start = (int)$array['erase_array'][2][0] - 1;
                    } else {
                        $start = 0;
                    }

                    $stop = (int)$array['erase_array'][2][1] - $start;
                    $array = (array)$model['array_casual'];
                    array_splice($array, $start, $stop);

                    $model['array_casual'] = array_filter($array);
                }
            }

            ///
            /// Кличество строк
            $count =
                (!empty($model['array_tk_amort']) ? count($model['array_tk_amort']) : 0) +
                (!empty($model['array_tk']) ? count($model['array_tk']) : 0) +
                (!empty($model['array_casual']) ? count($model['array_casual']) : 0);
            $model->array_count_all = $count;

            ///
            if ($model->save(true)) {
                //return $this->redirect(Sklad::getPathRefer());
                return $this->redirect(Yii::$app->request->referrer);
            } else {
                throw new HttpException(411, 'Обнаружены ошибки. Операция не выполнена', 5);
            }

        }


        //$spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(),'id','name');

        $spr_globam_element = Spr_globam_element::name_plus_id();


        /////////////////
        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);
//  ddd($model);


        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk'] = $this->getTkNames($model['array_tk']);
//  ddd($model);


//        //
//        //Список складов в группе
//        //
//        $list_whtop = ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');
//        $list_whtop_element = Sprwhelement::ArrayOnParent_id($model->wh_debet_top);
//        $list_dest_whtop = Sprwhelement::ArrayOnParent_id($model->wh_destination);
//        $list_dest_whtop_element = Sprwhelement::ArrayOnParent_id($model->wh_dalee);


        //
        //Список складов в группе
        $list_whtop = ['' => 'Выбрать ...'] + ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');
        $list_whtop_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_debet_top);
        $list_dest_whtop_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_destination);
        $list_dalee_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_dalee);

//ddd($list_dalee_element);

        ///
        ///
        ///   //////// ПРЕД СОХРАНЕНИЕМ
        ///
        if ($model->load(Yii::$app->request->post())) {

            //ddd($model);

            ///          'wh_cs_number',
            //            'wh_destination_element_cs',
            //$list_dalee_element


            $model->wh_home_number = (int)$sklad;
            //ddd($model);


            ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
            ///  ТАБ 1
            $model->array_tk_amort = Sklad::setArraySort1($model->array_tk_amort);
            //					$model->array_tk_amort = Sklad::setArrayClear( $model->array_tk_amort );
            ///  ТАБ 2
            $model->array_tk = Sklad::setArraySort2($model->array_tk);
            $model->array_tk = Sklad::setArraySort2($model->array_tk);


            ////  Приводим ключи в прядок! УСПОКАИВАЕМ ОЧЕРЕДЬ
            $model->array_tk_amort = Sklad::setArrayToNormal($model->array_tk_amort);
            //                ddd($model);
            $model->array_tk = Sklad::setArrayToNormal($model->array_tk);
            $model->array_casual = Sklad::setArrayToNormal($model->array_casual);


            ////  Приводим INTELLIGENT в прядок! Прописываем каждому элементу
//                $model->array_tk_amort = Spr_globam_element::array_am_to_intelligent( $model->array_tk_amort );
            //ddd($model);
            //                    $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
            //                    $model->update_user_id = (integer)Yii::$app->getUser()->identity->id;;
            //                    $model->update_user_name = Yii::$app->getUser()->identity->username;
            ////////////

            $model->wh_debet_top = (int)$model->wh_debet_top;
            $model->wh_debet_element = (int)$model->wh_debet_element;
            $model->wh_destination = (int)$model->wh_destination;
            $model->wh_destination_element = (int)$model->wh_destination_element;

            $model->wh_dalee = (int)$model->wh_dalee;
            $model->wh_dalee_element = (int)$model->wh_dalee_element;

            unset($model->wh_debet_name);
            unset($model->wh_debet_element_name);
            unset($model->wh_destination_name);
            unset($model->wh_destination_element_name);

            $list_dalee = ['' => 'Выбрать ...'] + ArrayHelper::map(Sprwhtop::find()
                    ->orderBy('name')
                    ->all(), 'id', 'name');
            $list_dalee_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_dalee);

          //  ddd($model);


            $parent_id_sklad = Sprwhelement::find_parent_id($sklad); // Парент айди этого СКЛАДА


            if ((int)$model->sklad_vid_oper == 2) {
                $model->sklad_vid_oper_name = 'Приходная накладная';

                $model->wh_destination = (int)$parent_id_sklad;
                $model->wh_destination_element = (int)$sklad;

            }
            if ((int)$model->sklad_vid_oper == 3) {

                if (
                    isset($model->dt_transfered_date) ||
                    isset($model->dt_transfered_user_id) ||
                    isset($model->dt_transfered_user_name)
                ) {

                    return $this->render(
                        '_form',
                        [
                            'model' => $model,
                            'sklad' => $sklad,

                            'list_whtop' => $list_whtop,
                            'list_whtop_element' => $list_whtop_element,
                            'list_dest_whtop_element' => $list_dest_whtop_element,
                            //'list_dalee_element' => $list_dalee_element,

                            'list_dalee' => $list_dalee,
                            'list_dalee_element' => $list_dalee_element,


                            'items_auto' => $items_auto,
                            'tz_head' => $tz_head,
                            'alert_mess' => 'Сохранение.НЕ ВОЗМОЖНО. Накладная уже была передана',
                            'spr_globam_element' => $spr_globam_element,
                        ]);

                }

                $model->sklad_vid_oper_name = 'Расходная накладная';

                $model->wh_debet_top = (int)$parent_id_sklad;
                $model->wh_debet_element = (int)$sklad;

            }

            // ddd($model);


            //
            //Список складов в группе
            //
            $list_whtop = ['' => 'Выбрать ...'] + ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');
            //ddd($list_whtop);


            //
            $list_whtop_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_debet_top);
            $list_dest_whtop_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_destination);

            //$list_dest_whtop_element = ['' => 'Выбрать ...'] + Sprwhelement::ArrayOnParent_id($model->wh_dalee);

            //ddd($model);

            /// Инициализируем Конечный СКЛАД
            $model->wh_cs_number = (int)0;
            $model->wh_destination_element_cs = (int)0;
            $model->wh_dalee_element_name = '';
            $model->wh_dalee_name = '';


            if (isset($model->wh_debet_element) && !empty($model->wh_debet_element)) {
                ///////
                /// ИСТОЧНИК
                $xx1 = Sprwhelement::findFullArray($model->wh_debet_element);
                $model->wh_debet_name = $xx1['top']['name'];
                $model->wh_debet_element_name = $xx1['child']['name'];

                // * Этот ИД является ЦС ?
                if (Sprwhelement::is_cs($model->wh_debet_element)) {
                    $model->wh_cs_number = $model->wh_debet_element;
                }
            }


            if (isset($model->wh_destination_element) && !empty($model->wh_destination_element)) {
                /// ПРИЕМНИК
                $xx2 = Sprwhelement::findFullArray($model->wh_destination_element);
                $model->wh_destination_name = $xx2['top']['name'];
                $model->wh_destination_element_name = $xx2['child']['name'];

                // * Этот ИД является ЦС ?
                if (Sprwhelement::is_cs($model->wh_destination_element)) {
                    $model->wh_cs_number = $model->wh_destination_element;
                }
            }


//ddd($model);
//ddd(123);
            //ddd($model);


            if (isset($model->wh_dalee_element) && !empty($model->wh_dalee_element)) {
                /// ПРИЕМНИК
                $xx2 = Sprwhelement::findFullArray($model->wh_dalee_element);
                $model->wh_dalee_name = $xx2['top']['name'];
                $model->wh_dalee_element_name = $xx2['child']['name'];
            }

            //ddd($model);

            $xx1 = $xx2 = $xx3 = 0;


            /// То самое преобразование ПОЛЯ Милисукунд
            //            $model->setDtCreateText( "NOW" );
            //$model->setDtCreateText( $model[ 'dt_create' ] );
            ///||||||||||||||||||||||||||||||||||
            /// Подсчет СТРОК Всего
            ///
            if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort'])
                && is_array($model['array_tk_amort'])) {
                $xx1 = count($model['array_tk_amort']);
            }

            if (isset($model['array_tk']) && !empty($model['array_tk'])
                && is_array($model['array_tk'])) {
                $xx2 = count($model['array_tk']);
            }


            if (isset($model['array_casual']) && !empty($model['array_casual'])
                && is_array($model['array_casual'])) {
                $xx3 = count($model['array_casual']);
            }


            $model['array_count_all'] = (int)$xx1 + $xx2 + $xx3;


            /////////////////////////
            ///
            ///   МОДАЛЬНОЕ ОКНО. Добавляем в ПУЛ ШТРИХКОДОВ для АСУОП
            ///
            /////////////////////////
            if ($para_post_contact_button == 'add_new_pool_copypast_fufer') {

                $id = $para_post_sklad['pool_copypast_id'];
                $parent_id = Spr_globam_element::getParent_id($id);

                //ddd($parent_id);
                //ddd($id);

                ////////
                $array = explode("\r\n", $para_post_sklad['pool_copypast_fufer']);
                ///
                unset($para_post_sklad['pool_copypast_fufer']);  //OK


                $array = array_filter($array);

                $array_result = [];

                foreach ($array as $item) {

                    $pattern = '/^[0-9]{5,10}[A-Z]?[0-9]{1,5}(-5|-8|-16)?$/i';
                    preg_match($pattern, $item, $barcode_str);

                    //19600004992
                    if (substr($barcode_str[0], 0, 6) == '019600') {
                        $array_result[] = substr($barcode_str[0], 1, 12);
                    } else {
                        $array_result[] = $barcode_str[0];
                    }

                }
                ///
                unset($array);  //OK

                ///
                if (!isset($array_result)) {
                    return $this->render(
                        '_form',
                        [
                            'new_doc' => $model,
                            'sklad' => $sklad,
                            'items_auto' => $items_auto,
                            'tz_head' => $tz_head,
                            'alert_mess' => 'Нет данных для заливки в базу.',
                            'spr_globam_element' => $spr_globam_element,
                        ]);
                }


//                $array_model = $model['array_tk_amort'];

                foreach ($array_result as $item) {

                    $array_model[] = [
                        'wh_tk_amort' => $parent_id,
                        'wh_tk_element' => (int)$id,
                        'ed_izmer' => 1,
                        'ed_izmer_num' => 1,
                        'intelligent' => 1,
                        'bar_code' => $item,
                    ];
                }
                ///
                unset($array_result);  //OK

                ///
                $model['array_tk_amort'] = $array_model;

            }


            //ddd($model);


            ///
            ///
            /// SAVE
            ///


            //ddd($model);

            try {
                $model->dt_create_timestamp = strtotime($model->dt_create);
                //
                $model->save(true);
                // ...другие операции с базой данных...

            } catch (\Exception $e) {
                throw $e;
            } catch (\Throwable $e) {
                throw $e;
            }

            // ddd($model);

            //if ($this->TransactionSave($model) && !$model->errors) {
            if (!isset($model->errors)) {

                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                $model['array_tk'] = $this->getTkNames($model['array_tk']);

                return $this->render(
                    '_form',
                    [
                        'model' => $model,

                        'list_whtop' => $list_whtop,
                        'list_whtop_element' => $list_whtop_element,
                        'list_dest_whtop_element' => $list_dest_whtop_element,

//                        'list_dalee' =>  $list_dalee,
                        'list_dalee_element' => $list_dalee_element,

                        'sklad' => $sklad,
                        'items_auto' => $items_auto,
                        'tz_head' => $tz_head,
                        'alert_mess' => 'Сохранение.Успешно.',
                        'spr_globam_element' => $spr_globam_element,
                    ]);

            } else {



                if (isset($model->errors)) {
                    $err = $model->errors;

                    if (isset($err['array_tk_amort'])) {

                        $err_str = implode(', ', $err['array_tk_amort']);
                        //ddd($err_str);

                        return $this->render(
                            '_form',
                            [
                                'model' => $model,
                                'sklad' => $sklad,

                                'list_whtop' => $list_whtop,
                                'list_whtop_element' => $list_whtop_element,
                                'list_dest_whtop_element' => $list_dest_whtop_element,

//                                'list_dalee' =>  $list_dalee,
                                'list_dalee_element' => $list_dalee_element,

                                'items_auto' => $items_auto,
                                'tz_head' => $tz_head,
                                'alert_mess' => 'Ошибка.' . $err_str,
                                'spr_globam_element' => $spr_globam_element,
                            ]);
                    }
                }
            }


        }



//        ddd($model);

        if (
            Yii::$app->getUser()->identity->group == 10 || // ERDOS, SASHA
            Yii::$app->getUser()->identity->group == 41  // ASEMTAI
        ) {

            return $this->render(
                '_form_rem', [
                'model' => $model,
                'sklad' => $sklad,

                'list_whtop' => $list_whtop,
                'list_whtop_element' => $list_whtop_element,
//                'list_dest_whtop' => $list_dest_whtop,
                'list_dest_whtop_element' => $list_dest_whtop_element,

//                'list_dalee' =>  $list_dalee,
                'list_dalee_element' => $list_dalee_element,

                'items_auto' => $items_auto,
                'tz_head' => $tz_head,
//                    'erase_array' => $erase_array,
                'alert_mess' => '',
                'spr_globam_element' => $spr_globam_element,
            ]);

        } else {


            if ((int)$model->sklad_vid_oper == 3) {
              //ddd(1112221);

                return $this->render(
                    '_form_3', [
                    'model' => $model,

                    'list_whtop' => $list_whtop,
                    'list_whtop_element' => $list_whtop_element,
                    'list_dest_whtop_element' => $list_dest_whtop_element,

//                'list_dalee' =>  $list_dalee,
                    'list_dalee_element' => $list_dalee_element,

                    'sklad' => $sklad,
                    'items_auto' => $items_auto,
                    'tz_head' => $tz_head,
                    //                    'erase_array' => $erase_array,
                    'alert_mess' => '',
                    'spr_globam_element' => $spr_globam_element,
                ]);

            } else {



                return $this->render(
                    '_form', [
                    'model' => $model,

                    'list_whtop' => $list_whtop,
                    'list_whtop_element' => $list_whtop_element,
                    'list_dest_whtop_element' => $list_dest_whtop_element,

//                'list_dalee' =>  $list_dalee,
                    'list_dalee_element' => $list_dalee_element,

                    'sklad' => $sklad,
                    'items_auto' => $items_auto,
                    'tz_head' => $tz_head,
                    //                    'erase_array' => $erase_array,
                    'alert_mess' => '',
                    'spr_globam_element' => $spr_globam_element,
                ]);
            }

        }

        ////
    }


    /**
     * Попытка Записи в БД с помощью Транзакции
     * =
     * @param $model
     * @return bool
     * @throws HttpException
     */
    public function TransactionSave($model)
    {
        try {
            $model->save(true);
        } catch (Exception $e) {
            throw new HttpException(411, 'Обнаружены ошибки ТРАНЗАКЦИИ. Сохранение не возможно ', 5);
        }
        return true;
    }


    /**
     * КОПИРОВАНИЕ НАКЛАДНОЙ ЦЕЛИКОМ.
     * Просто КОПИЯ этой накладной с новым номером ПО НАЖАТИЮ КНОПКИ "Копия с новым номером"
     * =
     *
     * @param $id
     *
     * @return string
     * @throws ExitException
     * @throws NotFoundHttpException
     */
    public function actionCopycard_from_origin($id)
    {

        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');

        //
        $model = Sklad::findModelDouble($id);  /// this is  _id !!!!!


        //
        $new_doc = new Sklad();


        ///Сливаем в новую накладную старую копию и дописываем новый номер
        //            unset($model->_id);
        //            $new_doc=$model;

        $new_doc->id = (int)Sklad::setNext_max_id();
        $new_doc['sklad_vid_oper'] = $model['sklad_vid_oper'];


        $new_doc['wh_home_number'] = $model['wh_home_number'];

        $new_doc['wh_debet_top'] = $model['wh_debet_top'];
        $new_doc['wh_debet_name'] = $model['wh_debet_name'];
        $new_doc['wh_debet_element'] = $model['wh_debet_element'];
        $new_doc['wh_debet_element_name'] = $model['wh_debet_element_name'];

        $new_doc['wh_destination'] = $model['wh_destination'];
        $new_doc['wh_destination_name'] = $model['wh_destination_name'];
        $new_doc['wh_destination_element'] = $model['wh_destination_element'];
        $new_doc['wh_destination_element_name'] = $model['wh_destination_element_name'];

        $new_doc['wh_dalee'] = $model['wh_dalee'];
        $new_doc['wh_dalee_element'] = $model['wh_dalee_element'];


        $new_doc['sklad_vid_oper_name'] = $model['sklad_vid_oper_name'];
        $new_doc['tz_id'] = $model['tz_id'];
        $new_doc['tz_name'] = $model['tz_name'];
        $new_doc['tz_date'] = $model['tz_date'];
        $new_doc['dt_deadline'] = $model['dt_deadline'];


        $new_doc['array_tk_amort'] = $model['array_tk_amort'];
        $new_doc['array_tk'] = $model['array_tk'];
        $new_doc['array_casual'] = $model['array_casual'];
        $new_doc['array_bus'] = $model['array_bus'];


        ///
        $x1 = $this->Count_rows((array)$new_doc->array_tk_amort);
        $x2 = $this->Count_rows((array)$new_doc->array_tk);
        $x3 = $this->Count_rows((array)$new_doc->array_casual);
        $new_doc->array_count_all = $x1 + $x2 + $x3;

        //ddd($new_doc);

        //ddd($model);

        $new_doc['dt_create'] = date('d.m.Y H:i:s', strtotime('now'));
        $new_doc['dt_create_timestamp'] = strtotime($new_doc['dt_create']);


        $new_doc['user_id'] = Yii::$app->getUser()->identity->id;
        $new_doc['user_name'] = Yii::$app->getUser()->identity->username;
        $new_doc['user_group_id'] = Yii::$app->getUser()->identity->group_id;


        ///////////////
        // ЕСЛИ Склад-Приемник является Целевым Складом (ЦС)
        // и накладная = РАСХОДНАЯ
        ///////////////

        if (
            Sprwhelement::is_FinalDestination($new_doc->wh_destination_element) &&
            $new_doc->sklad_vid_oper == Sklad::VID_NAKLADNOY_RASHOD
        ) {
            $new_doc ['wh_cs_number'] = $new_doc['wh_destination_element'];
            $new_doc ['wh_destination_element_cs'] = 1;
        }


        if ($new_doc->save(true)) {

            return $this->redirect(Url::toRoute('sklad/in'));


            //            sklad/in

//            return $this->render(
//                '_form',
//                [
//                    'model' => $model,
//                    'sklad' => $sklad,
//                    //'items_auto'         => $items_auto,
//                    //'tz_head'            => $tz_head,
//                    'alert_mess' => 'Сохранение.Успешно.',
//                ]);

        }


        return $this->render(
            '_form',
            [
                'model' => $model,
                'sklad' => $sklad,
                //'items_auto'         => $items_auto,
                //'tz_head'            => $tz_head,
                'alert_mess' => 'Сохранение.Пробуйте снова.',
            ]);

    }


    /**
     * Сколько СТРОК в Массиве
     *
     * @param $array
     * @return int
     */
    public function Count_rows($array = [])
    {
        return count($array);

    }


    /**
     * Накладная Резервный ФОНД (ПДФ)
     *
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_reserv_fond()
    {

        $para = Yii::$app->request->queryParams;

        $model = Sklad::findModelDouble($para['id']);


        ////////////////////
        ///// AMORT!!
        //        $model1 = ArrayHelper::map(Spr_globam::find()
        //            ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        ///// NOT AMORT
        //        $model3 = ArrayHelper::map(Spr_glob::find()
        //            ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');

        ////////////////////
        ///// BAR-CODE
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        $bar_code_html = MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        ///// BAR-CODE
        //1
        $html_css = $this->getView()->render('/sklad/html_reserv_fond/_form_css.php');

        //ddd($model);
        //2
        $html = $this->getView()->render(
            '/sklad/html_reserv_fond/_form', [
            //            'bar_code_html' => $bar_code_html,
            'model' => $model,
            //            'model1' => $model1,
            'model2' => $model2,
            //            'model3' => $model3,
            'model4' => $model4,
            'model5' => $model5,
        ]);


        //  Тут можно подсмореть
        //  $html = ss($html);
        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';

        $mpdf->SetAuthor('Guidejet TI. Morozov S., 2019');
        $mpdf->SetHeader($bar_code_html);
        $mpdf->WriteHTML($html_css, 1);

        //        $foot_str= '{PAGENO}';

        $foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>
        ';

        //$mpdf->SetFooter($foot_str );
        $mpdf->SetHTMLFooter($foot_str, 'O');


        ///////
        $mpdf->AddPage(
            '', '', '', '', '',
            10, 10, 25, 42, '', 25, '', '', '',
            '', '', '', '', '', '', '');

        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'DeMontage_' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     *
     * ПРЕДВАРИТЕЛЬНЫЙ ПРОСМТР СТРАНИЦЫ НАКЛАДНОЙ
     * ПЕРЕД ВЫВОДОМ в PDF
     *
     *
     * @return string
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_pdf_green()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(
            Spr_globam::find()
                ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        ///// NOT AMORT
        $model3 = ArrayHelper::map(
            Spr_glob::find()
                ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');

        ////////////////////
        ///// BAR-CODE
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        $bar_code_html = MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        ///// BAR-CODE
        //1
        $html_css = $this->getView()->render('/sklad_update/print/html_to_pdf/_form_css.php');

        //2
        $html = $this->getView()->render(
            '/sklad_update/print/html_to_pdf/_form_green', [
            //            'bar_code_html' => $bar_code_html,
            'model' => $model,
            'model1' => $model1,
            'model2' => $model2,
            'model3' => $model3,
            'model4' => $model4,
            'model5' => $model5,
        ]);


        //        dd($model);
        // Тут можно подсмореть
        //         $html = ss($html);
        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';


        $mpdf->SetAuthor('Guidejet TI. Morozov S., 2019');
        $mpdf->SetHeader($bar_code_html);
        $mpdf->WriteHTML($html_css, 1);

        //        $foot_str= '{PAGENO}';

        $foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>
        ';

        //$mpdf->SetFooter($foot_str );
        $mpdf->SetHTMLFooter($foot_str, 'O');


        ///////
        $mpdf->AddPage(
            '', '', '', '', '',
            10, 10, 25, 42, '', 25, '', '', '',
            '', '', '', '', '', '', '');

        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);


        $filename = 'Sk ' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');

        return false;

    }


    /**
     * Накладная Внутреннее Перемещение 2 Жанель
     *
     * @return bool
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_pdf_janel_demontage()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad::findModelDouble($para['id']);


        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(
            Spr_globam::find()
                ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        ///// NOT AMORT
        $model3 = ArrayHelper::map(
            Spr_glob::find()
                ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////

        $model6 = Sprwhelement::findFullArray($model['wh_destination_element']);

        $model7 = Sprwhelement::findFullArray($model['wh_dalee_element']);

        //ddd($model);

        $wh_debet_name = $model['wh_debet_name'];
        $wh_debet_element_name = $model['wh_debet_element_name'];

        ///// BAR-CODE
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        $bar_code_html = MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        ///// BAR-CODE
        //1
        $html_css = $this->getView()->render('/sklad_update/print/html_to_pdf/_form_css.php');


        //2
        $html = $this->getView()->render(
            '/sklad_update/print/html_to_pdf/_form_inner_janel_demontage', [
            //            'bar_code_html' => $bar_code_html,
            'model' => $model,
            'model1' => $model1,
            'model2' => $model2,
            'model3' => $model3,
            'model4' => $model4,
            'model5' => $model5,
            'model6' => $model6,
            'model7' => $model7,
            'wh_debet_name' => $wh_debet_name,
            'wh_debet_element_name' => $wh_debet_element_name,
        ]);


        //        dd($model);
        // Тут можно подсмореть
        //         $html = ss($html);
        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';


        $mpdf->SetAuthor('Guidejet TI. Morozov S., 2019');
        $mpdf->SetHeader($bar_code_html);
        $mpdf->WriteHTML($html_css, 1);

        //        $foot_str= '{PAGENO}';

        $foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>
        ';

        //$mpdf->SetFooter($foot_str );
        $mpdf->SetHTMLFooter($foot_str, 'O');


        ///////
        $mpdf->AddPage(
            '', '', '', '', '',
            10, 10, 25, 42, '', 25, '', '', '',
            '', '', '', '', '', '', '');

        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'Montage_' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * Накладная Внутреннее Перемещение 2 Жанель
     *
     * @return bool
     * @throws NotFoundHttpException
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_pdf_janel_montage()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(
            Spr_globam::find()
                ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        ///// NOT AMORT
        $model3 = ArrayHelper::map(
            Spr_glob::find()
                ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////

        $model6 = Sprwhelement::findFullArray($model['wh_destination_element']);
        //			ddd($model6);
        $model7 = Sprwhelement::findFullArray($model['wh_dalee_element']);
        //			ddd($model7);
        $wh_debet_name = $model['wh_debet_name'];
        $wh_debet_element_name = $model['wh_debet_element_name'];


        //ddd($model);
        ///// BAR-CODE
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        $bar_code_html = MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        ///// BAR-CODE
        //1
        $html_css = $this->getView()->render('/sklad_update/print/html_to_pdf/_form_css.php');

        //2
        $html = $this->getView()->render(
            '/sklad_update/print/html_to_pdf/_form_inner_janel', [
            //            'bar_code_html' => $bar_code_html,
            'model' => $model,
            'model1' => $model1,
            'model2' => $model2,
            'model3' => $model3,
            'model4' => $model4,
            'model5' => $model5,
            'model6' => $model6,
            'model7' => $model7,
            'wh_debet_name' => $wh_debet_name,
            'wh_debet_element_name' => $wh_debet_element_name,
        ]);


        //        dd($model);
        // Тут можно подсмореть
        //         $html = ss($html);
        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';


        $mpdf->SetAuthor('Guidejet TI. Morozov S., 2019');
        $mpdf->SetHeader($bar_code_html);
        $mpdf->WriteHTML($html_css, 1);

        //        $foot_str= '{PAGENO}';

        $foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>
        ';

        //$mpdf->SetFooter($foot_str );
        $mpdf->SetHTMLFooter($foot_str, 'O');


        ///////
        $mpdf->AddPage(
            '', '', '', '', '',
            10, 10, 25, 42, '', 25, '', '', '',
            '', '', '', '', '', '', '');

        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'Montage_' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * Акт МОНТАЖА
     *
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_akt_mont()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(
            Spr_globam::find()
                ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        ///// NOT AMORT
        $model3 = ArrayHelper::map(
            Spr_glob::find()
                ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////
        //1
        $html_css = '<title>Монтаж</title>';
        $params = Yii::$app->params;

//ddd($params);

        if($params['vars'] ==='wh_prod'){
              $html_css .= $this->getView()->render('/sklad_update/print/html_akt_mont/_form_css.php');
              //2
              $html = $this->getView()->render(
                  '/sklad_update/print/html_akt_mont/_form', [
                  'model' => $model,
                  //            'model1' => $model1,
                  'model2' => $model2,
                  //            'model3' => $model3,
                  'model4' => $model4,
                  'model5' => $model5,
              ]);
        }




        if($params['vars'] ==='wh_kar'){
              $html_css .= $this->getView()->render('/sklad_update/print/kar_html_akt_mont/_form_css.php');
              //2
              $html = $this->getView()->render(
                  '/sklad_update/print/kar_html_akt_mont/_form', [
                  'model' => $model,
                  //            'model1' => $model1,
                  'model2' => $model2,
                  //            'model3' => $model3,
                  'model4' => $model4,
                  'model5' => $model5,
              ]);
        }


        //        dd($model);
        // Тут можно подсмореть
        //         $html = ss($html);
        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';
        $mpdf->WriteHTML($html_css, 1);

        ///////
        $mpdf->AddPage(
            '', '', '', '', '',
            10, 10, 20, '', '', 1);

        //$html = '';
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        //       dd($model) ;
        $html .= MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'Montage_' . date('d.m.Y_H-i-s') . '.pdf';

        //header('Content-type: application/pdf');
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * Акт ДЕ-МОНТАЖА
     *
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_akt_demont()
    {
        $para = Yii::$app->request->queryParams;

        $model = Sklad::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
//			$model1 = ArrayHelper::map(
//				Spr_globam::find()
//				          ->all(), 'id', 'name' );

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        ///// NOT AMORT
//			$model3 = ArrayHelper::map(
//				Spr_glob::find()
//				        ->all(), 'id', 'name' );

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////
        //			ddd($model1);
        //			ddd($model5);
        //ddd( 123 );
        //1


        $html_css = '<title>Демонтаж</title>';
        $params = Yii::$app->params;
        if($params['vars'] ==='wh_prod'){
              $html_css .= $this->getView()->render('/sklad_update/print/html_akt_demont/_form_css.php');
              //2
              $html = $this->getView()->render(
                  '/sklad_update/print/html_akt_demont/_form', [
                  'model' => $model,
                  //            'model1' => $model1,
                  'model2' => $model2,
                  //            'model3' => $model3,
                  'model4' => $model4,
                  'model5' => $model5,
              ]);
        }

        if($params['vars'] ==='wh_kar'){
              $html_css .= $this->getView()->render('/sklad_update/print/kar_html_akt_demont/_form_css.php');
              //2
              $html = $this->getView()->render(
                  '/sklad_update/print/kar_html_akt_demont/_form', [
                  'model' => $model,
                  //            'model1' => $model1,
                  'model2' => $model2,
                  //            'model3' => $model3,
                  'model4' => $model4,
                  'model5' => $model5,
              ]);
        }



        //ddd($html);
        //  Тут можно подсмореть
        //$html = ss( $html );
        //ddd($html_css);
        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';
        $mpdf->WriteHTML($html_css, 1);

        ///////
        $mpdf->AddPage('', '', '', '', '', 10, 10, 20, '', '', 1);

        //$html = '';
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        //       dd($model) ;


        $html .= MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';
        unset($html);

        $filename = 'DeMontage_' . date('d.m.Y_H-i-s') . '.pdf';

        //header('Content-type: application/pdf');
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * Акт МОНТАЖА. АСУОП
     *
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_akt_mont_asuop()
    {

        //ddd(Yii::$app->user->identity->username);
        //ddd(Yii::$app->user->identity->username_for_signature);
        //ddd(Yii::$app->user->identity->getUserusername_for_signature());


        $para = Yii::$app->request->queryParams;
        $model = Sklad::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(
            Spr_globam::find()
                ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        ///// NOT AMORT
        $model3 = ArrayHelper::map(
            Spr_glob::find()
                ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////
        //1
        $html_css = '<title>Монтаж</title>';
        $params = Yii::$app->params;
        if($params['vars'] ==='wh_prod'){
              $html_css .= $this->getView()->render('/sklad_update/print/html_akt_mont_asuop/_form_css.php');
              //2
              $html = $this->getView()->render(
                  '/sklad_update/print/html_akt_mont_asuop/_form', [
                  'model' => $model,
                  //            'model1' => $model1,
                  'model2' => $model2,
                  //            'model3' => $model3,
                  'model4' => $model4,
                  'model5' => $model5,
              ]);
        }

        if($params['vars'] ==='wh_kar'){
              $html_css .= $this->getView()->render('/sklad_update/print/kar_html_akt_mont_asuop/_form_css.php');
              //2
              $html = $this->getView()->render(
                  '/sklad_update/print/kar_html_akt_mont_asuop/_form', [
                  'model' => $model,
                  //            'model1' => $model1,
                  'model2' => $model2,
                  //            'model3' => $model3,
                  'model4' => $model4,
                  'model5' => $model5,
              ]);
        }


        //        dd($model);
        // Тут можно подсмореть
        //         $html = ss($html);
        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';
        $mpdf->WriteHTML($html_css, 1);

        ///////
        $mpdf->AddPage(
            '', '', '', '', '',
            10, 10, 20, '', '', 1);

        //$html = '';
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        //       dd($model) ;
        $html .= MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'Montage_asuop_' . date('d.m.Y_H-i-s') . '.pdf';

        //header('Content-type: application/pdf');
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * Акт ДЕ-МОНТАЖА. АСУОП
     *
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_akt_demont_asuop()
    {
        $para = Yii::$app->request->queryParams;

        $model = Sklad::findModelDouble($para['id']);


        // ddd( $params['vars'] ==='wh_prod' );

        ////////////////////
        ///// AMORT!!
        //			$model1 = ArrayHelper::map(
        //				Spr_globam::find()
        //				          ->all(), 'id', 'name' );

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        ///// NOT AMORT
        //			$model3 = ArrayHelper::map(
        //				Spr_glob::find()
        //				        ->all(), 'id', 'name' );

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////

        //1
      $html_css = '<title>Демонтаж</title>';
      $params = Yii::$app->params;
      if($params['vars'] ==='wh_prod'){
            $html_css .= $this->getView()->render('/sklad_update/print/html_akt_demont_asuop/_form_css.php');
            //2
            $html = $this->getView()->render(
                '/sklad_update/print/html_akt_demont_asuop/_form', [
                'model' => $model,
                //            'model1' => $model1,
                'model2' => $model2,
                //            'model3' => $model3,
                'model4' => $model4,
                'model5' => $model5,
            ]);
      }

      if($params['vars'] ==='wh_kar'){
            $html_css .= $this->getView()->render('/sklad_update/print/kar_html_akt_demont_asuop/_form_css.php');
            //2
            $html = $this->getView()->render(
                '/sklad_update/print/kar_html_akt_demont_asuop/_form', [
                'model' => $model,
                //            'model1' => $model1,
                'model2' => $model2,
                //            'model3' => $model3,
                'model4' => $model4,
                'model5' => $model5,
            ]);
      }

        //ddd($html);
        //  Тут можно подсмореть
        //$html = ss( $html );
        //ddd($html_css);
        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';
        $mpdf->WriteHTML($html_css, 1);

        ///////
        $mpdf->AddPage('', '', '', '', '', 10, 10, 20, '', '', 1);

        //$html = '';
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        //       dd($model) ;


        $html .= MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';
        unset($html);

        $filename = 'DeMontage_asuop_' . date('d.m.Y_H-i-s') . '.pdf';

        //header('Content-type: application/pdf');
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * Лист Используется в Основной таблице без Амортизации
     * Справочник элементов прямого списания
     *
     * @param $id
     *
     * @return string
     */
    public function actionList($id)
    {

        //			if( empty($id ))
        //			{
        //				$model =
        //					Html::dropDownList(
        //						'name_id',
        //						0,
        //						['1'=>'пусто']
        //						, [ 'prompt' => 'Выбор ...' ]
        //					);
        //			}
        //
        //			if($id == '0')
        //			{
        //				$model =
        //					Html::dropDownList(
        //						'name_id',
        //						0,
        //						ArrayHelper::map(
        //							Spr_glob_element::find()
        //							                ->orderBy( "name" )
        //							                ->all(),
        //							'id', 'name' )
        //						, [ 'prompt' => 'Выбор ...' ]
        //					);
        //			}

        $model = [
        ];

        if ($id > 0) {
            $model = Html::dropDownList(
                '',
                0,
                ArrayHelper::map(
                    Spr_glob_element::find()
                        ->where([
                            'parent_id' => (int)$id])
                        ->orderBy("name")
                        ->all(),
                    'id', 'name')
                , [
                    'prompt' => 'Выбор ...']
            );
        }

        return $model;

    }


    /**
     * ЛистАморт Используется в таблице Амортизации
     * Справочник списания по амортизации
     *
     * @param $id
     *
     * @return string
     */
    public function actionListamort($id = 0)
    {
        return Html::dropDownList(
            'name_id_amort',
            0,
            ArrayHelper::map(
                post_spr_globam_element::find()
                    ->where([
                        'parent_id' => (integer)$id])
                    ->orderBy("name")
                    ->all(), 'id', 'name'),
            [
                'prompt' => 'Выбор ...']
        );

    }

    /**
     * Полный список АМ. По запросу в MultipleInput(е)
     * =
     *
     * @param $parent_id
     *
     * @return string
     */
    //        public function actionList_full_amort( $id )
    //        {
    //
    //            //            return
    //            //                Html::dropDownList(
    //            //                    'name_id_amort',
    //            //                    0,
    //            //                    ArrayHelper::map(
    //            //                        Spr_globam::find()
    //            //                            ->where( [ 'parent_id' => (integer) $parent_id ] )
    //            //                            ->orderBy( "name" )
    //            //                            ->all(), 'id', 'name' ),
    //            //
    //            //                    [ 'prompt' => 'Выбор ...' ]
    //            //                );
    //
    //            //            $xx = ArrayHelper::map(
    //            //				Spr_globam::find()
    //            //                    ->where( [ 'id' => (int)$id ] )
    //            //				          ->orderBy( "name" )
    //            //                    ->one(),
    //            //				'id', 'name' );
    //            //
    //            //            ddd( $xx );
    //		}


    /**
     * Добыает  Парент ИД для таблицы АСУОП в редактировании накладной
     * =
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionList_parent_id_amort($id = 0)
    {
        $model = post_spr_globam_element::find()
            ->where([
                'id' => (integer)$id])
            ->one();

        //        dd($model['ed_izm']);
        return $model['parent_id'];

    }


    /**
     * Добыает  Парент ИД для таблицы АСУОП в редактировании накладной
     * =
     * Возвращает ИД Аморта
     * -
     *
     * @param $bar_code
     *
     * @return mixed
     */
    public function actionId_amort_from_barcode($bar_code)
    {

        //    "id" : 14204,
        //    "element_id" : 2,
        //    "bar_code" : "19600005913"

        $model = Barcode_pool::find()
            ->where([
                'bar_code' => $bar_code])
            ->one();

        return ($model['element_id']);

    }


    /**
     * Добыает  Парент ИД для таблицы АСУОП в редактировании накладной
     * =
     *  Возвращает ИД Группы-Аморта
     *
     * @param $id
     *
     * @return mixed
     */
    public function actionId_group_amort_from_id($id)
    {

        $model = Spr_globam_element::find()
            ->where([
                'id' => (int)$id])
            ->one();

        return ($model['parent_id']);

    }


    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
     *
     * @return string
     */
    public function actionList_parent_id($id)
    {
        $model = Spr_glob_element::find()
            ->where([
                'id' => (int)$id])
            ->one();

        if (!isset($model)) {
            return 0;
        }

        return $model['parent_id'];

    }


    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
     *
     * @return string
     *
     * ТОЛЬКО НЕ!!!! НЕ АСУОП !!!
     */
    public function actionList_ed_izm($id = 0)
    {
        $model = post_spr_glob_element::find()
            ->where([
                'id' => (integer)$id])
            ->one();

        return $model['ed_izm'];

    }


    /**
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     * -
     *
     * @param $array_tk
     *
     * @return mixed
     */
    public function getTkNames_am($array_tk)
    {
        $spr_globam_model = ArrayHelper::map(
            Spr_globam::find()->orderBy('name')->all(),
            'id', 'name'
        );
        $spr_globam_element_model = ArrayHelper::map(
            Spr_globam_element::find()->orderBy('name')->all(),
            'id', 'name'
        );

        ///  BAR_CODE с пробелом в конце
        $spr_turnover = ArrayHelper::map(
            Barcode_pool::find()
                ->where(['>=', 'turnover', (int)1])
                ->orderBy('turnover DESC')
                ->all(),
            'stringName', 'turnover');


        //ddd($spr_turnover);


        //  $spr_globam_element_model_intelligent = ArrayHelper::map(Spr_globam_element::find()->orderBy('name')->all(), 'id', 'intelligent');
        //ddd($spr_globam_element_model_intelligent);


        $buff = [];

        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                //ddd($item);


                if (isset($item['wh_tk_amort']) && !empty($item['wh_tk_amort'])) {
                    if (isset($item['wh_tk_element']) && !empty($item['wh_tk_element'])) {
                        $buff[$key]['name_wh_tk_amort'] = (isset($spr_globam_model[$item['wh_tk_amort']]) ? $spr_globam_model[$item['wh_tk_amort']] : [
                        ]);

                        $buff[$key]['name_wh_tk_element'] = (isset($spr_globam_element_model[$item['wh_tk_element']]) ? $spr_globam_element_model[(int)$item['wh_tk_element']] : '');
                        //$buff[$key]['name_ed_izmer']=$spr_things_model[$item['ed_izmer']];

                        $buff[$key]['name_ed_izmer'] = 'шт';
                        $buff[$key]['ed_izmer'] = '1';


                        if (isset($item['bar_code'])) {
                            $buff[$key]['bar_code'] = ($item['bar_code'] > 0 ? $item['bar_code'] : '');
                        }
//                $buff[$key]['intelligent'] = ((int)$spr_globam_element_model_intelligent[$item['wh_tk_element']]);

                        $buff[$key]['wh_tk_amort'] = (isset($item['wh_tk_amort']) ? $item['wh_tk_amort'] : '');
                        $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                        $buff[$key]['take_it'] = (isset($item['take_it']) ? $item['take_it'] : 0);
                        $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];


                        $buff[$key]['rem_turmover'] = 0;
                        if (isset($spr_turnover[$item['bar_code'] . ' '])) {
                            $buff[$key]['rem_turmover'] = $spr_turnover[$item['bar_code'] . ' ']; //18
                        }


//                        $buff[$key]['rem_nepoladki'] = (isset($item['rem_nepoladki']) ? $item['rem_nepoladki'] : '0');
//                        $buff[$key]['rem_decision'] = (isset($item['rem_decision']) ? $item['rem_decision'] : '0');
                    }
                }
            }
        }


        //ddd($buff);

        return $buff;

    }


    /**
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     * -
     *
     * @param $array_tk
     *
     * @return mixed
     */
    public function getTkNames($array_tk)
    {
        $spr_glob_model = ArrayHelper::map(Spr_glob::find()->orderBy('name')->all(), 'id', 'name');
        $spr_glob_element_model = ArrayHelper::map(Spr_glob_element::find()->orderBy('name')->all(), 'id', 'name');
        $spr_things_model = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');


        $buff = [
        ];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                if (!empty($item)) {

                    $buff[$key]['name_tk'] = (isset($spr_glob_model[$item['wh_tk']]) ? $spr_glob_model[$item['wh_tk']] : '');
                    $buff[$key]['name_tk_element'] = (isset($spr_glob_element_model[$item['wh_tk_element']]) ? $spr_glob_element_model[$item['wh_tk_element']] : 0);
                    $buff[$key]['name_ed_izmer'] = $spr_things_model[$item['ed_izmer']];

                    $buff[$key]['wh_tk'] = $item['wh_tk'];
                    $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                    $buff[$key]['ed_izmer'] = $item['ed_izmer'];
                    $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];

                }
            }
        }


        return $buff;

    }


    /**
     * REM
     * @param $array_tk
     * @return array
     */
    public function getTkNames_rem($array_tk)
    {
        $spr_glob_model = ArrayHelper::map(Spr_globam::find()->orderBy('name')->all(), 'id', 'name');
        $spr_glob_element_model = ArrayHelper::map(Spr_globam_element::find()->orderBy('name')->all(), 'id', 'name');
        $spr_things_model = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');


        $buff = [];

        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                if (!empty($item)) {

                    $buff[$key]['name_tk'] = (isset($spr_glob_model[$item['wh_tk']]) ? $spr_glob_model[$item['wh_tk']] : '');
                    $buff[$key]['name_tk_element'] = (isset($spr_glob_element_model[$item['wh_tk_element']]) ? $spr_glob_element_model[$item['wh_tk_element']] : 0);
                    $buff[$key]['name_ed_izmer'] = $spr_things_model[$item['ed_izmer']];

                    $buff[$key]['wh_tk'] = $item['wh_tk'];
                    $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                    $buff[$key]['ed_izmer'] = $item['ed_izmer'];
                    //						$buff[ $key ][ 'take_it' ]       = $item[ 'take_it' ];
                    $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];

                    $buff[$key]['rem_nepoladki'] = (isset($item['rem_nepoladki']) ? $item['rem_nepoladki'] : '0');
                    $buff[$key]['rem_decision'] = (isset($item['rem_decision']) ? $item['rem_decision'] : '0');

                }
            }
        }

        return $buff;

    }

}
