<?php
namespace frontend\controllers;

use frontend\models\Barcode_pool;
use frontend\models\Sklad;
use frontend\models\Spr_globam_element;
use frontend\models\Sprwhelement;
use frontend\models\Tz;
use Yii;
use yii\base\ExitException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class Sklad_checkController extends Controller
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
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),

                'actions' => [

                    // Главная страница
                    'update' => [
                        'GET',
                        'POST',
                    ],

                    // Поиск по ШТРИХКОДУ
                    'find_check' => [
                        'GET',
                        'POST',
                    ],

                    'index' => [],
                    'create' => [],
                    'delete' => [],
                    'view' => [],

                    //  'delete' => ['POST', 'DELETE'],
                ],
            ],
        ];
    }

    /**
     * @param $event
     *
     * @return bool
     * @throws BadRequestHttpException
     */
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
     * Редактирование Накладной
     * =
     * ЦС внутри нашей накладной  по признаку
     * $cs_number_id
     * -
     *
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     * @throws ExitException
     */
    public function actionUpdate($id)
    {

        //T O D O:SKLAD = Update
        $para = Yii::$app->request->queryParams;

        $para_post = Yii::$app->request->post();

        //			ddd($para);
        //ddd($para_post);


        if (isset($para['otbor']) && !empty($para['otbor'])) {
            if (!Sklad::setSkladIdActive($para['otbor'])) {
                throw new UnauthorizedHttpException('$_SESSION1 Не подключен.  Sklad=0');
            }
        }

        if (isset($para['sklad']) && !empty($para['sklad'])) {
            if (!Sklad::setSkladIdActive($para['sklad'])) {
                throw new UnauthorizedHttpException('$_SESSION2 Не подключен.  Sklad=0');
            }
        }

        $sklad = Sklad::getSkladIdActive();    // Активный склад (_SESSION)
        //dd($sklad);


        if (!isset($sklad) || empty($sklad)) {
            throw new UnauthorizedHttpException('$_SESSION3 Не подключен.  Sklad=0');
        }


        ////////
        $model = Sklad::findModel($id);  /// this is  _id !!!!! //$model->getDtCreateText()
        ///
        if (!is_object($model)) {
            throw new NotFoundHttpException('Нет такой накладной');
        }


        /// Автобусы ЕСТЬ?
        if (isset($model['array_bus']) && !empty($model['array_bus'])) {

            $items_auto = Sprwhelement::findAll_Attrib_PE(
                array_map('intval', $model['array_bus'])
            );

            //            ddd( $items_auto );
            //            ddd( $model['array_bus'] );
        } else {
            $items_auto = []; // ['нет автобусов'];
        }


        /// Получаем ТехЗадание. ШАПКА
        if ($model->tz_id) {
            $tz_head = Tz::findModelDoubleAsArray((int)$model->tz_id);
        } else {
            $tz_head = [];
        }


        $parett_sklad = Sprwhelement::find_parent_id($sklad); // Парент айди этого СКЛАДА


        if ((int)$model['sklad_vid_oper'] == 2) {
            $model['sklad_vid_oper_name'] = 'Приходная накладная';
            $model->wh_destination = $parett_sklad;  // Мой склад ОТПРАВИТЕЛЬ
            $model->wh_destination_element = $sklad; // Мой склад ОТПРАВИТЕЛЬ

            //ddd($model);
        }


        if ((int)$model['sklad_vid_oper'] == 3) {
            $model['sklad_vid_oper_name'] = 'Расходная накладная';
            $model->wh_debet_top = $parett_sklad;       // Мой склад ПОЛУЧАТЕЛЬ
            $model->wh_debet_element = $sklad;      // Мой склад ПОЛУЧАТЕЛЬ
        }


        //// Подсчет количества строк в массивах
        /// for VIEW
        ///

        $erase_array[0] = count($model->array_tk_amort);
        $erase_array[1] = count($model->array_tk);
        $erase_array[2] = count($model->array_casual);

        //ddd($erase_array );


        ///////////////////////////////////////////////////////
        ///
        ///  КНОПКА ПЛЮС. ДОБАВКА ЕЩЕ ОДНОЙ НАКЛАДНОЙ К ЭТОЙ
        ///
        /// contact-button
        /// 1. add_aray
        /// 2. erase_aray
        ///
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button') {
            $num_next_sklad = $para_post['Sklad']['add_button'];
            //ddd($num_next_sklad); //8641


            // Сложили ВСЕ МАССИВЫ ИЗ ДВУХ НАКЛАДНЫХ
            $different_nakl = Sklad::findArray_by_id_into_sklad($sklad, $num_next_sklad);

            //ddd($different_nakl);


            ///
            ///  ERROR
            ///
            if (!isset($different_nakl) || empty($different_nakl)) {
                return $this->render(
                    '_form',
                    [
                        'model' => $model,
                        'sklad' => $sklad,
                        'items_auto' => $items_auto,
                        'tz_head' => $tz_head,
                        'alert_mess' => 'Не найдена накладная.',
//							'spr_globam_element' => $spr_globam_element,

                    ]);
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


            //				ddd($model);

            if ($model->save(true)) {

                return $this->render(
                    '_form',
                    [
                        'model' => $model,
                        'sklad' => $sklad,
                        'items_auto' => $items_auto,
                        'tz_head' => $tz_head,
                        'alert_mess' => 'Сохранение.Успешно.',
//							'spr_globam_element' => $spr_globam_element,
                    ]);
            } else {
                ddd($model->errors);
            }


        }


        ///
        ///  КНОПКА УДАЛЕНИЕ СТРОК в массивах
        ///
        /// $para_post['contact-button']=='erase_aray'
        ///
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'erase_button') {
            //ddd($para_post);

            if (is_array($model['array_tk_amort'])) {
                //////////array_tk_amort
                ///
                $start = (int)$para_post['Sklad']['erase_array'][0][0] - 1;
                $stop = (int)$para_post['Sklad']['erase_array'][0][1] - $start;

                $array = (array)$model['array_tk_amort'];
                array_splice($array, $start, $stop);
                $model['array_tk_amort'] = $array;

                //  ddd($model);

                //                $start=$stop=0;
            }

            if (is_array($model['array_tk'])) {
                //////////array_tk
                ///
                $start = (int)$para_post['Sklad']['erase_array'][1][0] - 1;
                $stop = (int)$para_post['Sklad']['erase_array'][1][1] - $start;

                $array = (array)$model['array_tk'];
                array_splice($array, $start, $stop);
                $model['array_tk'] = $array;

                //                $start=$stop=0;
            }

            if (is_array($model['array_casual'])) {
                //////////array_casual
                ///
                $start = (int)$para_post['Sklad']['erase_array'][2][0] - 1;
                $stop = (int)$para_post['Sklad']['erase_array'][2][1] - $start;

                $array = (array)$model['array_casual'];
                array_splice($array, $start, $stop);
                $model['array_casual'] = $array;

                //                $start=$stop=0;
            }

            //ddd($model);

            if ($model->save(true)) {

                /////////////////
                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                $model['array_tk'] = $this->getTkNames($model['array_tk']);

                //ddd($model);


                return $this->render(
                    '_form',
                    [
                        'model' => $model,
                        'sklad' => $sklad,
                        'items_auto' => $items_auto,
                        'tz_head' => $tz_head,
                        'alert_mess' => 'Сохранение.Успешно.',
//							'spr_globam_element' => $spr_globam_element,
                    ]);
            } else {
                ddd($model->errors);
            }

        }


        //$spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(),'id','name');

        $spr_globam_element = Spr_globam_element::name_plus_id();


        /////////////////
        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk'] = $this->getTkNames($model['array_tk']);


        //ddd(Yii::$app->request->post());
        //ddd(Yii::$app->getResponse());


        ///
        ///
        ///
        ///   //////// ПРЕД СОХРАНЕНИЕМ
        ///
        if ($model->load(Yii::$app->request->post())) {

            $model->wh_home_number = (int)$sklad;


            ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
            ///  ТАБ 1
            $model->array_tk_amort = Sklad::setArraySort1($model->array_tk_amort);
            //					$model->array_tk_amort = Sklad::setArrayClear( $model->array_tk_amort );
            ///  ТАБ 2
            $model->array_tk = Sklad::setArraySort2($model->array_tk);


            ////  Приводим ключи в прядок! УСПОКАИВАЕМ ОЧЕРЕДЬ
            $model->array_tk_amort = Sklad::setArrayToNormal($model->array_tk_amort);
            //                ddd($model);
            $model->array_tk = Sklad::setArrayToNormal($model->array_tk);
            $model->array_casual = Sklad::setArrayToNormal($model->array_casual);

            //                ddd($model);


            ////  Приводим INTELLIGENT в прядок! Прописываем каждому элементу
//                $model->array_tk_amort = Spr_globam_element::array_am_to_intelligent( $model->array_tk_amort );

            //ddd($model);

            //                    $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
            //                    $model->update_user_id = (integer)Yii::$app->getUser()->identity->id;;
            //                    $model->update_user_name = Yii::$app->getUser()->identity->username;

            ////////////


            if ((int)$model['sklad_vid_oper'] == 2) {
                $model['sklad_vid_oper_name'] = 'Приходная накладная';
            }
            if ((int)$model['sklad_vid_oper'] == 3) {
                $model['sklad_vid_oper_name'] = 'Расходная накладная';
            }

            $model->wh_debet_top = (int)$model->wh_debet_top;
            $model->wh_debet_element = (int)$model->wh_debet_element;
            $model->wh_destination = (int)$model->wh_destination;
            $model->wh_destination_element = (int)$model->wh_destination_element;
            $model->wh_dalee = (int)$model->wh_dalee;
            $model->wh_dalee_element = (int)$model->wh_dalee_element;


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
            if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_new_pool_copypast_fufer') {

                $id = $para_post['Sklad']['pool_copypast_id'];
                $parent_id = Spr_globam_element::getParent_id($id);

//                    ddd( $parent_id );

//                    ddd( $para_post );


                ////////
                $array = explode("\r\n", $para_post['Sklad']['pool_copypast_fufer']);

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
                    //throw new NotFoundHttpException( 'Нет данных для заливки в базу.' );
                }


                $array_model = $model['array_tk_amort'];

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


                $model['array_tk_amort'] = $array_model;

                //				ddd( $model );
                //				ddd( $array_model );
                //				ddd( $array_result );


            }


            //ddd($model);

            $model->dt_create_timestamp = strtotime($model->dt_create);

            ///
            ///
            /// SAVE
            ///
            if ($model->save(true)) {
//                    ddd($model);


                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                $model['array_tk'] = $this->getTkNames($model['array_tk']);

                return $this->render(
                    '_form',
                    [
                        'model' => $model,
                        'sklad' => $sklad,
                        'items_auto' => $items_auto,
                        'tz_head' => $tz_head,
                        'alert_mess' => 'Сохранение.Успешно.',
                        'spr_globam_element' => $spr_globam_element,
                    ]);
            } else {


//                    ddd($model->errors);


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
                                'items_auto' => $items_auto,
                                'tz_head' => $tz_head,
                                'alert_mess' => 'Ошибка.' . $err_str,
                                'spr_globam_element' => $spr_globam_element,


                            ]);
                    }

                }
            }
        }


        return $this->render(
            '_form', [
            'model' => $model,
            'sklad' => $sklad,
            'items_auto' => $items_auto,
            'tz_head' => $tz_head,
            'erase_array' => $erase_array,
            'alert_mess' => '',
            'spr_globam_element' => $spr_globam_element,


        ]);
    }


    /**
     * Поиск товара по ШТРИХКОДУ по накладным в диапазоне дат
     *=
     * @return string
     * @throws ExitException
     */
    public function actionFind_check()
    {
        $para = Yii::$app->request->get();
        $alert_str = "";

        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }


        //SKLAD My
        $sklad = Sklad::getSkladIdActive();
        //        ddd($sklad );

//            ddd($para); //4431


        $bar_code = '';

        // 'Barcode_pool' => [
        //        'find_name' => '040007'
        //    ]

        if (isset($para['Barcode_pool']['find_name'])) {


            /////
            $bar_code = $para['Barcode_pool']['find_name'];

            // Очистка от букв. Оставляем только цифры
            $bar_code = preg_replace('/[^\d*]/i', '', $bar_code);

            //19600
            $bar_code = preg_replace('/019600/u', '19600', $bar_code);


            ///
            /// Для ДОК отдела. Поиск по Штрихкоду.
            //
            //  * Из него создается потом накладная Демонтаж/Монтаж
            ///
            /// 1

            $number_id = Sklad::Find_number_waybill_by_barcode($bar_code, $sklad);
            $model_find_one = Sklad::findModelDouble($number_id);

            //ddd($model_find_one);

            if (isset($model_find_one)) {
                $array_am = $model_find_one->array_tk_amort;

                foreach ($array_am as $item_array_tk_amort) {
                    //if ($item_array_tk_amort['bar_code'] == $bar_code) {
                    $array [] = $item_array_tk_amort;
                    //}
                }

                $model_find_one->array_tk_amort = $array;
            } else {
                ///
                /// Для ДОК отдела. Поиск по Штрихкоду.
                //
                //  * Из него создается потом накладная Демонтаж/Монтаж
                ///
                /// 2
                ///

                $model_find_one = Sklad::Find_barcode_one($bar_code);
            }


            ///
            /// Если до сих пор не найден Штрихкод
            ///
            if (!isset($model_find_one)) {
                $alert_str = "Штрихкод не найден.";
            }


//                    $xx1 = Sprwhelement::findFullArray($sklad);
//                    ddd($xx1);
//
//                    $sklad_model->wh_destination = $xx1['top']['id'];
//                    $sklad_model->wh_destination_element = $xx1['child']['id'];
//                    $sklad_model->wh_destination_name = $xx1['top']['name'];
//                    $sklad_model->wh_destination_element_name = $xx1['child']['name'];

//                    $sklad_model->array_tk_amort = $model_find_one->array_tk_amort;


//            $sklad_model = $model_find_one;

            ////////////////////


            //                    // ТУТ вход в ТВИН-ФОРМУ
            //                    return $this->render(
            //                        '_form_twin1', [
            //
            //                        'model_new' => $sklad_model,
            //                        'sklad' => $sklad,
            //                        'alert_str' => '' . $alert_str,
            //
            //                    ]);
        }


        // Поле для формы
        $model_text = Barcode_pool::find()->one();

        // Список-массив. Поиск автопоиск
        $pool = Barcode_pool::Array_for_auttofinder();


        return $this->render(
            'stat_forms/stat_dvizh_barcode', [
            //            'provider' => $provider,
            //            'model' => $model_find_one,

            'sklad' => $sklad,
            'bar_code' => $bar_code,
            'pool' => $pool,
            'model_text' => $model_text,
            'alert_str' => $alert_str,
        ]);


    }


}