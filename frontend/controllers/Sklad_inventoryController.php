<?php

namespace frontend\controllers;

use frontend\models\post_spr_glob_element;
use frontend\models\postsklad_inventory;

use frontend\models\Sklad;
use frontend\models\Sklad_inventory;

use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;

use frontend\components\MyHelpers;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;
use yii\base\ExitException;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

//	use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use frontend\models\Barcode_pool;


/**
 * PvController implements the CRUD actions for pv model.
 */
class Sklad_inventoryController extends Controller
{
    public $sklad;

    /**
     */
    public function init()
    {
        parent::init();

        $session = Yii::$app->session;
        $session->open();

        ///
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
                    'index' => ['GET'],

                    'create_new' => [
                        'GET',
                        'POST',
                    ],

                    'create_new_park' => [
                        'GET',
                        'POST',
                        'PUT',
                    ],

                    'update' => [
                        'POST',
                        'GET',
                        'GET',
                    ],
                    'delete' => ['GET'],

                    //                   'index'  => ['GET'],
                    // *                 'view'   => ['GET'],
                    // *                 'create' => ['GET', 'POST'],
                    // *                 'update' => ['GET', 'PUT', 'POST'],
                    // *                 'delete' => ['POST', 'DELETE'],
                    //'update' => ['POST', 'GET', 'GET'],
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


        $searchModel = new postsklad_inventory();
        $dataProvider = $searchModel->search($para);

        //ddd($dataProvider->getModels() );

        return $this->render(
            'index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

            //'sklad' => $sklad,
        ]);
    }


    /**
     * Создаем новую  накладную  (Инвентаризация)
     * -
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate_new()
    {
        //        $sklad = Sklad::getSkladIdActive();
        //        if (!isset($sklad) || empty($sklad))
        //            throw new UnauthorizedHttpException('Sklad=0');


        $max_value = Sklad_inventory::find()->max('id');
        $max_value++;

        $model = new Sklad_inventory();

        if (!is_object($model)) {
            throw new NotFoundHttpException('Склад ИНВЕНТАРИЗАЦИИ не работает');
        }


        ////////
        $model->id = (integer)$max_value;

        $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_INVENTORY; // INVENTORY

        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
        /// То самое преобразование ПОЛЯ Милисукунд
        //$model->setDtCreateText( "NOW" );   ///Милисукунд
        $model->dt_create_timestamp = strtotime($model->dt_create);


        //////////////
        if ($model->load(Yii::$app->request->post())) {
            //            ddd($model);

            $model->wh_home_number = (integer)$model->wh_destination_element;
            $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_INVENTORY; // INVENTORY


            $model->user_id = (int)Yii::$app->getUser()->identity->id;
            $model->user_name = Yii::$app->getUser()->identity->username;


//                $model->sklad_vid_oper = (integer)$model->sklad_vid_oper; // Приводим к числу
            if ((int)$model['sklad_vid_oper'] == 2) {
                $model['sklad_vid_oper_name'] = 'Приходная накладная';
            }

            if ((int)$model['sklad_vid_oper'] == 3) {
                $model['sklad_vid_oper_name'] = 'Расходная накладная';
            }


            /// СКЛАД
            $xx2 = Sprwhelement::findFullArray($model->wh_destination_element);

            $model->wh_destination_name = $xx2['top']['name'];
            $model->wh_destination_element_name = $xx2['child']['name'];


            //ddd($model);


            if ($model->save(true)) {

                //if(SkladController::actionInventory_in_cs(++$max_value, $model)) {
                return $this->redirect(['/sklad_inventory/' . $adres_to_return]);
                //                    }

            }

            //                else
            //                    ddd($model->errors);
        }


        return $this->render(
            '_form_create', [
            'new_doc' => $model,
//				'sklad'   => $sklad,
            //            'alert_mess' => 'Сохранение. Попытка',

        ]);
    }


    /**
     * Создаем новую  накладную  (Инвентаризация)
     * =
     * ПО ВСЕМУ АВТОПАРКУ, включая все ПЕ
     * -
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionCreate_new_park()
    {
        //T O D O: SKLAD INVENTORY actionCreate_new_park() Заливка ОПТОМ

        //$para_post = Yii::$app->request->get();
        //$para_post = Yii::$app->request->resolve();
        $para_post = Yii::$app->request->post();

        //ddd($para_post);

        //        $sklad = Sklad::getSkladIdActive();
        //        if (!isset($sklad) || empty($sklad))
        //            throw new UnauthorizedHttpException('Sklad=0');


        $max_value = Sklad_inventory::find()->max('id');
        $max_value++;

        $model = new Sklad_inventory();

        if (!is_object($model)) {
            throw new NotFoundHttpException('Склад ИНВЕНТАРИЗАЦИИ не работает');
        }


        ////////
        $model->id = (integer)$max_value;

        $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_INVENTORY; // INVENTORY

        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
        /// То самое преобразование ПОЛЯ Милисукунд
        //$model->setDtCreateText( "NOW" );   ///Милисукунд
        $model->dt_create_timestamp = strtotime($model->dt_create);

        //ddd($model);


        //
        // T O D O: SKLAD INVENTORY add_new_bus()
        // Залить копипаст НОВЫЕ АВТОБУСЫ
        //
        //'contact-button' => 'add_new_bus'

        if ($para_post['contact-button'] == 'add_new_bus') {

            /// ДРОБИЛКА (Parser)
            //$array = explode("\r\n", trim($para_post['Sklad_inventory']['add_text_to_inventory_am']));
            $array = explode("\r\n", $para_post['Sklad_inventory']['add_text_to_inventory_am']);


            //Создаем правильный массив
            //            0 => [
            //                0 => 'Каскеленский автопарк'
            //                1 => '018ABB05'
            //            ]

            //ddd($array);

            foreach ($array as $item) {

                $array_item = explode("\t", $item);

                if (!empty($array_item[0])) {
                    $AP_id = $array_item[0];
                }

                if (!empty($array_item[1])) {
                    $array_park_bus[] = [
                        $AP_id,
                        $array_item[1],
                    ];
                    //$arr_out[]= ''.$AP_id.' '.$array_item[1];
                }


            }


            //  ПАКЕТНОЕ Добавление в справочник WH новых автобусов
            $how = SprwhelementController::New_buses_from_array($array_park_bus);
            //'how_math' => 38
            //'find_math' => 178

            return $this->render(
                '_form_create_park', [
                'new_doc' => $model,
                'sklad' => $sklad,
                'alert_mess' => 'Заливка. Новых позиций: ' . $how['how_math'] . ' ' .
                    'Старых позиций: ' . $how['find_math'] . ' ',

            ]);

        }


        //
        // T O D O: УДАЛИТЬ СТОЛБЦОМ ПО ИД()
        // копипаст УДАЛИТЬ СТОЛБЦОМ ПО ИД
        //
        //'contact-button' => 'delete_bus_id'
        //
        if ($para_post['contact-button'] == 'delete_bus_id') {
            //ddd($para_post);

            /// ДРОБИЛКА (Parser)
            $array = explode("\r\n", $para_post['Sklad_inventory']['add_array_to_delete']);


            //Создаем правильный массив
            foreach ($array as $item) {
                if (!empty($item)) {
                    $array_park_bus[] = (int)$item; //INT (!!!)
                }
            }

            //  ПАКЕТНОЕ УДАЛЕНИЕ из справочник WH по ИД
            $how = Sprwhelement::Delete_by_id($array_park_bus);


            return $this->render(
                '_form_create_park', [
                'new_doc' => $model,
                'sklad' => $sklad,
                'alert_mess' => 'Удаление. Позиций: ' . $how['how_math'],
            ]);

        }
        //////////////


        //////////////
        if ($model->load(Yii::$app->request->post())) {


            //ddd(Yii::$app->request->post());
            //ddd($model);

            $model->wh_home_number = (integer)$model->wh_destination_element;
            $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_INVENTORY; // INVENTORY

            $model->user_id = (int)Yii::$app->getUser()->identity->id;
            $model->user_name = Yii::$app->getUser()->identity->username;


//				$model->sklad_vid_oper = (integer) $model->sklad_vid_oper; // Приводим к числу
            if ((int)$model['sklad_vid_oper'] == 2) {
                $model['sklad_vid_oper_name'] = 'Приходная накладная';
            }

            if ((int)$model['sklad_vid_oper'] == 3) {
                $model['sklad_vid_oper_name'] = 'Расходная накладная';
            }


            //  Получить справочник AM
            $spr_elem = Spr_globam_element::name_plus_id();  // Названия
            $spr_elem_parent = Spr_globam_element::id_to_parent();
            $spr_elem_intelligent = Spr_globam_element::id_to_intelligent(); /// intelligent
//				$spr_elem_am          = Spr_globam::name_am_parent(); // ddd($spr_elem_parent);

            //ddd($spr_elem_intelligent);

            //////////////////////
            /// ДРОБИЛКА (Parser)
            //////////////////////
            ///
            $str = trim($para_post['Sklad_inventory']['add_text_to_inventory_am1']);

            //$str = preg_replace("`б/н00.+\\t\\r\\n`u", "", $str);
            //$str = preg_replace("`б/н00.+\\t\\r\\n`u", "", $str);

            $str = preg_replace("`б/н00.+\\r\\n\\t\\t`u", "", $str);

            //ddd($str);


            $array = explode("\r\n", $str);


            ///////////////////
            foreach ($array as $key => $item_all) {
                //ddd($item_all);

                $item = explode("\t", $item_all); // Парсим строку в массив

                //ddd($item);
                //ddd($model);


                /// Номера АВТОПАРКОВ в копипасте =$model->wh_destination
                /// Номера АВТОБУСОВ в копипасте = $item[0]
                ///
                if (isset($model->wh_destination) && $model->wh_destination > 0) {
                    /// Получить Номер АВТОПАРКА из ПОЛЯ ФОРМЫ
                    $number_AP = $model->wh_destination; // ==14
                } else {
                    if (isset($item[0]) && !empty($item[0])) {
                        if (isset($item[1]) && !empty($item[1])) {
                            $number_PE = $item[0];
                            /// Получить Номер АВТОПАРКА по его ПОТОМКУ
                            $array_number_AP = Sprwhelement::show_me_AP_by_Name($number_PE);
                            $number_AP = $array_number_AP['parent_id'];
                        }
                    }
                }

                //                ddd($item);

                //                if('Помощник водителя GJ-DA04'==$item[1]){
                //                    ddd($item);
                //                }


                if (!isset($number_AP)) {
                    //ddd(123);

                    echo 'Заливка.НетНетНетНет СКЛАДА в Справочнике. Ошибка.';

                    //ddd($item_all);ddd($number_AP);

                    return $this->render(
                        '_form_create_park', [
                        'new_doc' => $model,
                        'sklad' => $sklad,
                        'alert_mess' => 'Заливка.НетНетНетНет СКЛАДА в Справочнике. Ошибка.',

                    ]);
                }


                //                ddd($item);


                //                if ( !isset($item[3]) || empty($item[3]) || ( $item[3]=='0'  ) ){
                //                    ddd($item);
                //
                //                    return $this->render('_form_create_park', [
                //                        'new_doc' => $model,
                //                        'sklad' => $sklad,
                //                        'alert_mess' => 'Заливка.Ошибка. Нет количества единиц',
                //
                //                    ]);
                //                }

                //ddd($item);


                ///
                ///  Массив накладной для Каждого автопарка (АП)
                /// Если есть цифра в количестве  и она не равна НУЛЮ
                ///
                if (isset($item[3]) && !empty($item[3]) && ($item[3] != '0')) {
                    if (!empty($item[1])) {
                        $key_key = array_search(trim($item[1]), $spr_elem);
                        // Наименование
                    }

                    $array_tk[$number_AP][$number_PE][] = [
                        //'wh_tk_amort' => (isset($spr_elem_am[$spr_elem_parent[$key]])?$spr_elem_am[$spr_elem_parent[$key]]:7),
                        'wh_tk_amort' => (isset($spr_elem_parent[$key]) ? $spr_elem_parent[$key] :
                            7),
                        'wh_tk_element' => $key_key,
                        // Номер ИД
                        'wh_tk_element_name' => $item[1],

                        'ed_izmer' => 1,
                        // Всегда -ШТУКИ
                        'ed_izmer_num' => ($item[3] < 0 ? -$item[3] : $item[3] * 1),
                        // MODUL INTEGER

                        'bar_code' => MyHelpers::barcode_normalise($item[2]),
                        'intelligent' => (int)$spr_elem_intelligent[$key_key],
                    ];
                }

            }

            //ddd($array_tk);
            //ddd($array_tk);

            /////////////////////////////
            $x = 1;
            if (!isset($array_tk) || !is_array($array_tk)) {

                //                echo"Заливка всех ЦС.$array_tk Не возможно сохранить. dddd === Ошибка.";
                //                ddd($array_tk);

                return $this->render(
                    '_form_create_park', [
                    'new_doc' => $model,
                    'sklad' => $sklad,
                    'alert_mess' => 'Заливка всех ЦС.$array_tk Не возможно сохранить. dddd === Ошибка.',

                ]);
            }


            // Номер АВТОПАРК
            foreach ($array_tk as $key_AP => $number_AP) {


                // Номер АВТОБУСА-СКЛАДА
                foreach ($number_AP as $key => $item_all) {
                    //print_r( $key); // Номер АВТОБУСА-СКЛАДА
                    //echo "<br>";

                    $model_new = new Sklad_inventory();     // Новая накладная ИНВЕНТАРИЗАЦИИ

                    ////////
                    $model_new->sklad_vid_oper = Sklad::VID_NAKLADNOY_INVENTORY;  // INVENTORY
                    $model_new->dt_create = date('d.m.Y H:i:s', strtotime('now'));
                    /// То самое преобразование ПОЛЯ Милисукунд
                    //$model_new->setDtCreateText( "NOW" );             ///Милисукунд
                    $model->dt_create_timestamp = strtotime($model->dt_create);

                    /// СКЛАД
                    /// Перевернутый массив (наим+ид)
                    $wh_inverse = Sprwhelement::findChi_as_Array($key_AP);

                    $model_new->wh_destination = (integer)$key_AP;      // Номер АВТОПАРК
                    $model_new->wh_destination_element = $wh_inverse[$key]; // Номер АВТОБУСА-СКЛАДА
                    $model_new->wh_home_number = $wh_inverse[$key];

                    $model_new->group_inventory = '' . $x++;
                    $model_new->group_inventory_name = 'авто-копипаст';

                    $model_new->array_tk_amort = $array_tk[$key_AP][$key];

                    //                    ddd($array_tk);
                    //                    ddd($model_new);


                    //
                    //  if ($model_new->save(true))
                    //
                    //* ПРОПИСАТЬ накладную ИНВЕНТАРИЗАЦИИ в складе ЦС
                    //
                    // Инвентаризации по ЦС БУДУТ храниться только(!) в их складах
                    //


                    if (!Sklad_inventory_csController::actionInventory_in_cs($model_new)) {

                        return $this->render(
                            '_form_create_park', [
                            'new_doc' => $model,
                            'sklad' => $sklad,
                            'alert_mess' => 'Заливка всех ЦС. Сбой. Не возможно сохранить',

                        ]);

                    }

                }
            }


            return $this->render(
                '_form_create_park', [
                'new_doc' => $model,
                'sklad' => $sklad,
                'alert_mess' => 'Заливка всех ЦС. Сохранение. Ок.',

            ]);

            //return $this->redirect(['/sklad_inventory/'.$adres_to_return]);
        }


        return $this->render(
            '_form_create_park', [
            'new_doc' => $model,
            'sklad' => $sklad,
            //            'alert_mess' => 'Сохранение. Попытка',

        ]);
    }


    /**
     * Редактирование Накладной
     * -
     * Url::to(['/sklad/update
     * ? id=_id
     * & otbor=86
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
        if (!isset($id) || empty($id)) {
            throw new UnauthorizedHttpException('$id///  Не подключен.  Sklad UPDATE');
        }


        $para_post = Yii::$app->request->post();

        //ddd($para);  //'id' => '5d3e623b80a06317bc002e05'


        ////////
        $model = Sklad_inventory::findModel($id);  // it is =  _id
        ///

        // ddd($model);


        ////////////////////////////////////
        ////////////////////////////////////
        ///
        ///  add_button_am
        ///  КНОПКА - ЗАЛИВКА КОПИПАСТ - АСУОП
        ///  из МОДАЛЬНОГО ОКНА
        ///
        ///add_button
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button_am') {


            //  Получить справочник
            $spr_elem = Spr_globam_element::name_plus_id();
            $spr_elem_parent = Spr_globam_element::id_to_parent();
            //$spr_elem_edizm = Spr_globam_element::id_to_ed_izm(); ///ВСЕГДА-ШТУКИ!!!


            /////////
            $array = explode("\r\n", trim($para_post['Sklad_inventory']['add_text_to_inventory_am']));

            //ddd($para_post);
            //ddd($array);


            // Приводим к нормальному массиву
            foreach ($array as $item) {
                $array_sign[] = array_map('trim', explode("\t", $item));   /// "TAB"
            }

            foreach ($array_sign as $key => $item2) {
                $key_key = array_search($item2[0], $spr_elem);   // $item2[1];/// штуки

                if (isset($key_key)) {
                    //ddd($key_key);

                    $array_reason[] = [
                        $spr_elem_parent[$key_key],
                        $key_key,
                        $item2[0],
                        $item2[1],
                    ];

                    $array_tk[] = [
                        'wh_tk_amort' => $spr_elem_parent[$key_key],
                        'wh_tk_element' => $key_key,
                        'ed_izmer' => 1,
                        // Всегда -ШТУКИ
                        'ed_izmer_num' => (isset($array_sign[$key][1]) ? $array_sign[$key][1] : 0),
                        'bar_code' => '',
                    ];
                }
            }

            //ddd($array_tk);

            $model->array_tk_amort = array_merge($model->array_tk_amort, $array_tk);
            //   ddd($array_reason);

        }


        //////////////////////////////////
        //////////////////////////////////
        ///
        ///  КНОПКА - ЗАЛИВКА КОПИПАСТ
        ///  - СПИСАНИЕ
        ///
        ///add_button
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button') {

            //  Получить справочник
            $spr_elem = Spr_glob_element::name_plus_id();
            $spr_elem_parent = Spr_glob_element::id_to_parent();
            $spr_elem_edizm = Spr_glob_element::id_to_ed_izm();


            /////////
            $array = explode("\r\n", trim($para_post['Sklad_inventory']['add_text_to_inventory']));


            // Приводим к нормальному массиву
            foreach ($array as $item) {
                $array_sign[] = array_map('trim', explode("\t", $item));   /// "TAB"
            }

            foreach ($array_sign as $key => $item2) {
                $key_key = array_search($item2[0], $spr_elem);   // $item2[1];/// штуки

                if (isset($key_key)) {
                    $array_reason[] = [
                        $spr_elem_parent[$key_key],
                        $key_key,
                        $item2[0],
                        $item2[1],
                    ];

                    $array_tk[] = [
                        'wh_tk' => $spr_elem_parent[$key_key],
                        'wh_tk_element' => $key_key,
                        'ed_izmer' => $spr_elem_edizm[$key_key],
                        'ed_izmer_num' => (isset($array_sign[$key][1]) ?
                            floatval(str_replace(",", ".", $array_sign[$key][1])) : 0),
                        'bar_code' => '',
                    ];
                }
            }

            //ddd( floatval( str_replace(",", ".", $array_sign[$key][1] )  ) );

            $model->array_tk = array_merge($model->array_tk, $array_tk);
        }


        //// Подсчет количества строк в массивах
        /// for VIEW
        ///

        $erase_array[0] = count($model->array_tk_amort);
        $erase_array[1] = count($model->array_tk);
        $erase_array[2] = count($model->array_casual);

        //ddd($erase_array );


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
                $start = (int)$para_post['Sklad_inventory']['erase_array'][0][0];
                $stop = (int)$para_post['Sklad_inventory']['erase_array'][0][1] - $start;

                $array = (array)$model['array_tk_amort'];
                array_splice($array, $start, $stop);
                $model['array_tk_amort'] = $array;

            }

            if (is_array($model['array_tk'])) {
                //////////array_tk
                ///
                $start = (int)$para_post['Sklad_inventory']['erase_array'][1][0];
                $stop = (int)$para_post['Sklad_inventory']['erase_array'][1][1] - $start;

                $array = (array)$model['array_tk'];
                array_splice($array, $start, $stop);

                $model['array_tk'] = $array;
            }

            if (is_array($model['array_casual'])) {
                //////////array_casual
                ///
                $start = (int)$para_post['Sklad_inventory']['erase_array'][2][0];
                $stop = (int)$para_post['Sklad_inventory']['erase_array'][2][1] - $start;

                $array = (array)$model['array_casual'];
                array_splice($array, $start, $stop);
                $model['array_casual'] = $array;
            }

            // ddd($model);

        }


        ///||||||||||||||||||||||||||||||||||
        /// Подсчет СТРОК Всего
        ///
        $xx1 = $xx2 = $xx3 = 0;

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


        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk'] = $this->getTkNames($model['array_tk']);

        //ddd($model);


        $spr_things = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');


        ///
        /// //////// ПРЕД СОХРАНЕНИЕМ
        ///
        if (!isset($para_post['contact-button']) || empty($para_post['contact-button'])) {
            if ($model->load(Yii::$app->request->post())) {
                //ddd(123);

                //  ddd($model);

                //$model->wh_home_number=(integer)$sklad;
                $model->wh_home_number = (integer)$model->wh_destination_element;
                $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_INVENTORY; // INVENTORY

                $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));

                $model->update_user_id = Yii::$app->request->getUserIP();
                $model->update_user_name = Yii::$app->user->identity->username;
                $model->update_user_id = Yii::$app->user->identity->id;
                $model->update_user_group_id = Yii::$app->user->identity->group_id;


                ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
                // $model->array_tk_amort  = Sklad::setArraySort1( $model->array_tk_amort );
                ///  ТАБ 1
                $model->array_tk_amort = Sklad::setArrayClear($model->array_tk_amort);
                ///  ТАБ 2
                $model->array_tk = Sklad::setArraySort2($model->array_tk);


                ////  Приводим ключи в прядок! УСПОКАИВАЕМ ОЧЕРЕДЬ
                $model->array_tk_amort = Sklad::setArrayToNormal($model->array_tk_amort);
                $model->array_tk = Sklad::setArrayToNormal($model->array_tk);
                $model->array_casual = Sklad::setArrayToNormal($model->array_casual);

                //ddd(345);

                ///////
                /// ПРИЕМНИК
                $xx2 = Sprwhelement::findFullArray($model->wh_destination_element);

                $model->wh_destination_name = $xx2['top']['name'];
                $model->wh_destination_element_name = $xx2['child']['name'];


                /// То самое преобразование ПОЛЯ Милисукунд
                //$model->setDtCreateText( $model[ 'dt_create' ] );
                $model->dt_create_timestamp = strtotime($model->dt_create);


                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                $model['array_tk'] = $this->getTkNames($model['array_tk']);


                //ddd($model);

                //ddd(123);

                if ($model->save(true)) {


                    return $this->render(
                        '_form',
                        [
                            'new_doc' => $model,
                            'spr_things' => $spr_things,
                            'alert_mess' => 'Сохранение.Успешно.',
                        ]);
                } else {
                    //ddd( 555 );


                    //ddd($model->errors);

                    if (isset($model->errors['array_tk'])) {
                        return $this->render(
                            '_form',
                            [
                                'new_doc' => $model,
                                'spr_things' => $spr_things,
                                'alert_mess' => $model->errors['array_tk'][0],

                            ]);
                    }
                }


            }
        }


        return $this->render(
            '_form', [
            'new_doc' => $model,
            'spr_things' => $spr_things,
            'alert_mess' => '',
        ]);

    }

    /**
     * Просто КОПИЯ этой накладной с новым номером ПО НАЖАТИЮ КНОПКИ "Копия с новым номером"
     *
     * @param $id
     *
     * @return string
     * @throws ExitException
     */
    public function actionCopycard_from_origin($id)
    {
        //        $session = Yii::$app->session;
        //        $sklad = $session->get('sklad_');

        $model = Sklad_inventory::findModelDouble($id);  /// this is  _id !!!!!
        //        dd($model);


        $max_value = Sklad_inventory::find()->max('id');
        $max_value++;

        $new_doc = new Sklad_inventory();

        ///Сливаем в новую накладную старую копию и дописываем новый номер
        //            unset($model->_id);
        //            $new_doc=$model;

        $new_doc->id = (integer)$max_value;
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

        $new_doc['sklad_vid_oper_name'] = $model['sklad_vid_oper_name'];
        $new_doc['tz_id'] = $model['tz_id'];
        $new_doc['tz_name'] = $model['tz_name'];
        $new_doc['tz_date'] = $model['tz_date'];
        $new_doc['dt_deadline'] = $model['dt_deadline'];


        $new_doc['array_tk_amort'] = $model['array_tk_amort'];
        $new_doc['array_tk'] = $model['array_tk'];
        $new_doc['array_casual'] = $model['array_casual'];
        $new_doc['array_bus'] = $model['array_bus'];


        $spr_things = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');

        //ddd($new_doc->attributes());


        if (!$new_doc->save(false)) {
            //ddd( $new_doc->errors );

            return $this->render(
                '_form',
                [
                    'new_doc' => $new_doc,
                    'spr_things' => $spr_things,
                    'alert_mess' => $new_doc->errors['array_tk'][0],
                ]);
        }

        //$new_doc->save(true , $new_doc->attributes());

        return $this->redirect('index');
    }


    /**
     * Распечатка. Выходная Форма.
     * Накладная Резервный ФОНД (ПДФ)
     *-
     *
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_reserv_fond()
    {

//			$para = Yii::$app->request->queryParams;
        $para_get_id = yii::$app->request->get('id');
        //ddd($para_get_id);


        $model = Sklad_inventory::findModelDouble($para_get_id);
//        ddd($model);


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
        //$html_css = $this->getView()->render('/sklad/html_reserv_fond/_form_css.php');
        $html_css = $this->getView()->render('/sklad_inventory/pdf_form/_form_css.php');

        //ddd($model);

        //2
        //$html = $this->getView()->render('/sklad/html_reserv_fond/_form', [

        $html = $this->getView()->render(
            '/sklad_inventory/pdf_form/_form', [
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

        $mpdf->SetAuthor('Guidejet TI, 2019');
        $mpdf->SetHeader($bar_code_html);
        $mpdf->WriteHTML($html_css, 1);

        //        $foot_str= '{PAGENO}';

        $foot_str = '

           
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
     * Распечатка. Выходная Форма.
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
        $model = Sklad_inventory::findModelDouble($para['id']);

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
        $html_css = $this->getView()->render('/sklad/html_to_pdf/_form_css.php');

        //2
        $html = $this->getView()->render(
            '/sklad/html_to_pdf/_form_green', [
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


        $mpdf->SetAuthor('Guidejet TI, 2019');
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
     * @param        $id
     * @param string $adres_to_return
     *
     * @return Response
     * @throws StaleObjectException
     */
    public function actionDelete($id, $adres_to_return = "")
    {
        self::findModel($id)->delete();

        return $this->redirect(['/sklad_inventory/' . $adres_to_return]);
    }

    /**
     * id = системный длинный Ид
     * -
     * @param $id
     * @return Sklad_inventory|null
     */
    protected function findModel($id)
    {
        return Sklad_inventory::findOne($id);
    }


    //////////........
    //////////........
    /**
     * Лист Используется в Основной таблице без Амортизации
     * Справочник элементов прямого списания
     *
     * @param $id
     *
     * @return string
     */
    public function actionList($id = 0)
    {
        $model =
            Html::dropDownList(
                'name_id',
                0,
                ArrayHelper::map(
                    Spr_glob_element::find()
                        ->where(['parent_id' => (int)$id])
                        ->orderBy("name")
                        ->all(), 'id', 'name'),
                ['prompt' => 'Выбор ...']
            );

        return $model;

        //                ArrayHelper::map(post_spr_glob_element::find()
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
        $model =
            Html::dropDownList(
                'name_id_amort',
                0,
                ArrayHelper::map(
                    Spr_globam_element::find()
                        ->where(['parent_id' => (integer)$id])
                        ->orderBy("name")
                        ->all(), 'id', 'name'),

                ['prompt' => 'Выбор ...']
            );

        return $model;
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
        $model =
            post_spr_glob_element::find()
                ->where(['id' => (integer)$id])
                ->one();

        return $model['ed_izm'];
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function actionList_parent_id_amort($id = 0)
    {
        $model =
            Spr_globam_element::find()
                ->where(['id' => (int)$id])
                ->one();

        //dd($model['ed_izm']);
        return $model['parent_id'];
    }

    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
     *
     * @return string
     */
    public function actionList_parent_id($id = 0)
    {
        $model =
            Spr_glob_element::find()
                ->where(['id' => (integer)$id])
                ->one();

        //        dd($model['ed_izm']);
        return $model['parent_id'];
    }
    //////////........
    //////////........


    /**
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     *-
     *
     * @param $array_tk
     * @return mixed
     */
    public function getTkNames_am($array_tk)
    {
        $spr_globam_model = ArrayHelper::map(
            Spr_globam::find()->orderBy('name')->all(),
            'id',
            'name');
        $spr_globam_element_model = ArrayHelper::map(
            Spr_globam_element::find()->orderBy('name')->all(),
            'id',
            'name');

        $spr_globam_element_model_intelligent = ArrayHelper::map(
            Spr_globam_element::find()->orderBy('name')->all(),
            'id',
            'intelligent');
        //ddd($spr_globam_element_model_intelligent);


        $buff = [];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                $buff[$key]['name_wh_tk_amort'] = $spr_globam_model[$item['wh_tk_amort']];
                $buff[$key]['name_wh_tk_element'] = $spr_globam_element_model[$item['wh_tk_element']];
                //$buff[$key]['name_ed_izmer']=$spr_things_model[$item['ed_izmer']];

                $buff[$key]['name_ed_izmer'] = 'шт';
                $buff[$key]['ed_izmer'] = '1';


                $buff[$key]['bar_code'] = ($item['bar_code'] > 0 ? $item['bar_code'] : '');
                $buff[$key]['intelligent'] = ((int)
                $spr_globam_element_model_intelligent[$item['wh_tk_element']]);

                $buff[$key]['wh_tk_amort'] = $item['wh_tk_amort'];
                $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                $buff[$key]['take_it'] = $item['take_it'];
                $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];

            }
        }

        return $buff;
    }

    /**
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     *-
     *
     * @param $array_tk
     * @return mixed
     */
    public function getTkNames($array_tk)
    {
        $spr_glob_model = ArrayHelper::map(
            Spr_glob::find()->orderBy('name')->all(),
            'id',
            'name');
        $spr_glob_element_model = ArrayHelper::map(
            Spr_glob_element::find()->orderBy('name')->all(),
            'id',
            'name');
        $spr_things_model = ArrayHelper::map(
            Spr_things::find()->all(), 'id', 'name');


        $buff = [];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                $buff[$key]['name_tk'] = $spr_glob_model[$item['wh_tk']];
                $buff[$key]['name_tk_element'] = $spr_glob_element_model[$item['wh_tk_element']];
                $buff[$key]['name_ed_izmer'] = $spr_things_model[$item['ed_izmer']];


                $buff[$key]['wh_tk'] = $item['wh_tk'];
                $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                $buff[$key]['ed_izmer'] = $item['ed_izmer'];
                $buff[$key]['take_it'] = (isset($item['take_it']) ? $item['take_it'] : '');
                $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];

                //$buff[$key]['name']=$item['name'];

            }
        }

        //        ddd($array_tk);
        //        ddd($buff);

        return $buff;
    }


    /**
     * Добыает  Парент ИД для таблицы АСУОП в редактировании накладной
     * =
     * Возвращает ИД Аморта
     * -
     *
     * @param $bar_code
     * @return mixed
     */
    public function actionId_amort_from_barcode($bar_code)
    {
        if (!isset($bar_code)) {
            return '111';
        }

        $model =
            Barcode_pool::find()
                ->where(['bar_code' => $bar_code])
                ->one();

        return ($model['element_id']);

    }

    /**
     * Добыает  Парент ИД для таблицы АСУОП в редактировании накладной
     * =
     *  Возвращает ИД Группы-Аморта
     *
     * @param $id
     * @return mixed
     */
    public function actionId_group_amort_from_id($id)
    {

        $model =
            Spr_globam_element::find()
                ->where(['id' => (int)$id])
                ->one();

        return ($model['parent_id']);

    }


}
