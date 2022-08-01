<?php

namespace frontend\controllers;

use frontend\components\MyHelpers;
use frontend\models\MailerForm;
use frontend\models\post_spr_globam;
use frontend\models\post_spr_globam_element;
use frontend\models\postsklad;
use frontend\models\postsklad_shablon;
use frontend\models\postsklad_transfer;
use frontend\models\posttz;
use frontend\models\Shablon;
use frontend\models\Sklad;
use frontend\models\Sklad_delete;
use frontend\models\Sklad_transfer;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Tz;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;
use yii\base\ExitException;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\mongodb\Exception;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use frontend\models\Sprwhtop;


/**
 *
 */
class SkladController extends Controller
{
    private $one_day;
    private $next_time;

    public $sklad;
    public $copy_past;


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
                    'in' => [
                        'GET'],
                    // Главная страница
                    'update' => [
                        'GET',
                        'POST',],
                    // Редактирование НАКЛАДНОЙ
                    'create_new' => [
                        'GET',
                        'POST',],
                    // КНОПКА создать новую наклданую
                    'prihod2' => [
                        'GET',
                        'POST',],
                    // Принятие накладной из ПРИХОДА
                    'createfromtz' => [
                        'GET',
                        'POST',],
                    // Принятие накладной из Createfromtz
                    'createfrom_shablon' => [
                        'GET',
                        'POST',],
                    // Принятие накладной из Createfrom_shablon
                    'create_from_cs' => [
                        'GET'],
                    // Принятие накладной из Createfrom_shablon
                    'from_cs' => [
                        'POST',
                        'GET',],
                    // Принятие накладной из ЦС
                    'copy-to-transfer' => [
                        'GET'],
                    // ПЕРЕДАЧА В БУФЕР ОБМЕНА CopyToTransfer
                    'tz-to-many-new-acts-demontage' => [
                        'GET',
                        'POST'],
                    //
                    'tz-to-many-new-acts-montage' => [
                        'GET',
                        'POST'],
                    //
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
     * ВКЛАДКИ для Склада Sklad/IN
     * =
     *
     * @return string
     * @throws Exception
     * @throws HttpException
     */
    public function actionIn()
    {
        $para = Yii::$app->request->queryParams;
        $otbor = Yii::$app->request->get('otbor');


        ///
        if (isset($otbor) && !empty($otbor)) {
            Sklad::setSkladIdActive($otbor);
        }
        ///
        $sklad = Sklad::getSkladIdActive();
        ///
        if (!isset($sklad) || empty($sklad)) {
            throw new HttpException(411, 'Выбрать склад', 2);
        }


//        if (!isset($para['otbor'])) {
//            $para['otbor'] = $sklad;
//        }


        ///
        $searchModel_tz = new posttz();
        $dataProvider_tz = $searchModel_tz->search_into($para);

        ///
        $searchModel_into = new postsklad_transfer();
        $dataProvider_into = $searchModel_into->search_into_wh($para);


        ///
        $aray_res = Sklad_transfer::findOld_transfers($sklad);

        $searchModel_shablon = new postsklad_shablon();
        $dataProvider_shablon = $searchModel_shablon->search($para);

        $searchModel_sklad = new postsklad();
        $dataProvider_sklad = $searchModel_sklad->search($para);

        //Запомнить РЕФЕРee
        //Sklad::setPathRefer(Yii::$app->request->referrer);
        Sklad::setPathRefer(Yii::$app->request->url);


        $dataProvider_sklad->setSort(
            [
                'attributes' => [
                    'tx' => [
                        'asc' => [
                            'tx' => SORT_ASC],
                        'desc' => [
                            'tx' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],


                    'id',

                    'dt_create',

                    'dt_create_timestamp' => [
                        'default' => SORT_DESC,
                    ],

                    'dt_one_day' => [
                        'asc' => [
                            'dt_create_timestamp' => SORT_ASC],
                        'desc' => [
                            'dt_create_timestamp' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],

                    'dt_start',

                    'wh_dalee_element',

                    'user_name',
                    'update_user_name',

                    'sklad_vid_oper',
                    'wh_home_number',
                    'tz_id',
                    'array_count_all',
                    'wh_debet_name',
                    'wh_debet_element_name',
                    'wh_destination_name',
                    'wh_destination_element_name',
                    'dt_update_timestamp' => [
                        'default' => SORT_DESC,
                    ],
                ],
                'defaultOrder' => [
                    'id' => SORT_DESC],
            ]
        );


        /// Работает  ОДНОДНЕВНАЯ ВЫБОРКА !!!!  через модель поиска
        /// Место только ТУТ!
        if (isset($para['postsklad']['dt_start']) && !empty($para['postsklad']['dt_start'])) {
            $searchModel_sklad['dt_start'] = date('d.m.Y', strtotime($para['postsklad']['dt_start']));
        }

        $model = new Sklad();
        $model->scenario = Sklad::SCENARIO_MODAL_COPYPAST;


        //       ddd($dataProvider_sklad->getModels());
        //       ddd($dataProvider_into->getModels());


        return $this->render(
            'accordion/_form_accordion',
            [
                'searchModel_tz' => $searchModel_tz,
                'dataProvider_tz' => $dataProvider_tz,

                'searchModel_into' => $searchModel_into,
                'dataProvider_into' => $dataProvider_into,

                'searchModel_shablon' => $searchModel_shablon,
                'dataProvider_shablon' => $dataProvider_shablon,

                'searchModel_sklad' => $searchModel_sklad,
                'dataProvider_sklad' => $dataProvider_sklad,

                'sklad' => $sklad,
                'model' => $model,

                'aray_res' => (isset($aray_res) ? $aray_res : null), /// Непринятые накладные

            ]
        );

    }


    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('/');

    }


    /**
     * Сколько СТРОК в Массиве
     *
     * @param $array
     * @return int
     */
    public function Count_rows($array)
    {
        return count($array);
    }


    /**
     * Создать новую накладную
     * =
     *
     * @return string|Response
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionCreate_new()
    {
        $para = Yii::$app->request->post();

        $sklad = Sklad::getSkladIdActive();


        if (!isset($sklad) || empty($sklad)) {
            throw new UnauthorizedHttpException('Sklad=0');
        }


        $model = new Sklad();

        if (!is_object($model)) {
            throw new NotFoundHttpException('Склад не работает');
        }

        ////////
        $model->id = (int)Sklad::setNext_max_id();
        $model->wh_home_number = (int)$sklad;
        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));


        if (!isset($model->sklad_vid_oper) || empty($model->sklad_vid_oper)) {
            $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;
        }


        //            ddd($model);
        /////////// ПРЕД СОХРАНЕНИЕМ
        if ($model->load(Yii::$app->request->post())) {

            //ddd($model);

            $model->wh_home_number = (int)$sklad;

            $model->user_id = (int)Yii::$app->getUser()->identity->id;
            $model->user_name = Yii::$app->getUser()->identity->username;

            // $model->sklad_vid_oper = (integer)$model->sklad_vid_oper; // Приводим к числу


            $model->wh_debet_top = 1;
            $model->wh_debet_element = 1;
            $model->wh_destination = 1;
            $model->wh_destination_element = 1;


            ///
            ///  Приводим INTELLIGENT в прядок!
            ///  Прописываем каждому элементу
            $model->array_tk_amort = Spr_globam_element::array_am_to_intelligent($model->array_tk_amort);

            //            ddd($model);
            /////////// ПРЕД СОХРАНЕНИЕМ
            /// Проверим ИМЕННО Нашу кнопку "Создать"

            if (isset($para['contact-button']) && $para['contact-button'] == 'create_new') {

                //Перебивка номера накладной
                if ((int)Sklad::setNext_max_id() > (int)$model->id) {
                    $model->id = (int)Sklad::setNext_max_id();
                    $tx = $model->tx;
                    $model->tx = $tx . "от админа (аварийно изм.номер накладной)";
                }
                //ddd($new_doc);

                ///
                $model->scenario = Sklad::SCENARIO_NEW_CREATE;

                if ($model->save(true)) {
                    //Обнулить РЕФЕР
                    Sklad::setPathRefer('/sklad/in');

                    return $this->redirect('/sklad/in');
                } else {
                    ddd($model->errors);
                }
            }
        }


        return $this->render(
            '_form_create', [
                'model' => $model,
                'sklad' => $sklad,
                'alert_mess' => '',
            ]
        );

    }


    /**
     * copypast (!!!!)
     * от НАТАШИ в ДОК отдел
     * -
     * Наталья Эксел
     */
    public function actionCopypast()
    {
        //
        $button_name = Yii::$app->request->post('contact-button');
        //
        $alert_mess = [];

        ///
        ///  add_copypast
        ///
        if ($button_name == 'add_copypast') {

            //
            $para = Yii::$app->request->post("Sklad");
            //
            $str = $para['add_copypast'];
            //
            $array_str_all = explode("\r\n", $str); //ok

            // Пустышки удаляет из массива
            $array_str_all = array_filter($array_str_all);
            //ddd( $array_str_all );

            //
            $arr_rez = [];
            foreach ($array_str_all as $key => $str_one) {

                $array_str = explode("\t", $str_one); //ok
                //
                foreach ($array_str as $str_1) {
                    $arr_rez[$key][] = trim($str_1);
                }
            }

            //ddd($arr_rez);
            ///    0 => '15.02.2021'
            //        1 => '798'
            //        2 => 'Акт демонтажа'
            //        3 => 'Алматыэлектротранс ТОО'
            //        4 => '017DE02'
            //        5 => 'Помощник водителя GJ-DA04'
            //        6 => '043131'
            //        7 => '1'
            //        8 => '128 608'
            //        9 => '128 608'

            //
            // Предствляем массив в новом виде для сортировки по АКТУ и ВИДу работ
            foreach ($arr_rez as $key_rez => $item_rez) {
                $arr_sort_for_act[$arr_rez[$key_rez][1]][$arr_rez[$key_rez][2]][] = $item_rez; // AKT
            }
            unset($arr_rez);

            //ddd($arr_sort_for_act);


            //
            //  Попытка Записать в базу
            //
            foreach ($arr_sort_for_act as $key_act => $item_act) {

                // Обнуляем дату накладной
                unset($next_time);
                //
                if (isset($item_act['Акт демонтажа'])) {
                    foreach ($item_act['Акт демонтажа'] as $key_act1 => $item_act_one) {
                        // * Попытка Записать в базу
                        $alert_mess[] = $this->Add_copypast($item_act_one);
                    }
                }
                //
                if (isset($item_act['Акт монтажа'])) {
                    foreach ($item_act['Акт монтажа'] as $key_act1 => $item_act_mont) {
                        // * Попытка Записать в базу
                        $alert_mess[] = $this->Add_copypast($item_act_mont);
                    }
                }
            }


            ////
        }


        //ddd($alert_mess);

        $alert_mess_str = "";
        foreach ($alert_mess as $mess) {
            $alert_mess_str .= "\n" . $mess . "\n\n";
        }

        //    ddd($alert_mess_str);
        ///OK
        if (!isset($alert_mess_str) || empty($alert_mess_str)) {
            $alert_mess_str[0] = "Сохранение. OK.";
        }


        return $this->renderContent(
            $alert_mess_str . "<br> " .
            Html::a('Выход', ['sklad/in/'], ['class' => 'btn btn-warning'])
        );

    }


    /**
     * @param mixed $otbor
     */
    public function setOtbor($otbor)
    {
        $this->otbor = $otbor;

    }

    /**
     * Заливка-Копипаст.     * Попытка Записать в базу
     * -
     *
     * @param $array
     * @return bool|Response
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    private function Add_copypast($array)
    {
        //
        // Ведем один ГЛОБАЛЬНЫЙ день  для понимания dt_create
        //
        //$one_day =  $array[0]; // грязный формат
        // ddd($this->one_day);


        // ddd(11111);



        //
        $date_day = preg_replace('/(\d.+)[\.|\/](\d.+)[\.|\/](\d.+)/', '$1.$2.$3 01:00:00', $array[0]);
        if (!$date_day) {
            return '!date_day . Дата не распознана. Формат не читается ';
        }

        //
        // $date_day = preg_replace('/(\d.+)\.(\d.+)\.(\d.+)/', '$1.$2.$3 01:00:00', $array[0]);

        if ((int)$this->one_day == (int)$date_day) {
            /// Переводим флаг на единицу
            $this->next_time = 1;
            //ddd($this->next_time);
        } else {
            // Ведем один ГЛОБАЛЬНЫЙ день  для понимания dt_create
            $this->one_day = $date_day;
        }

        //        ddd($date_day); // '17.11.2020 01:00:00'
        //        ddd(strtotime($date_day)); // 1605553200


        /// Проверяем на Дубль НАКЛАДНОЙ
        if (isset($array) && !empty($array)) {

            //
            if (!isset($date_day)) {
                return '! date_day';
            }

            //
            if (!isset($array[1]) || !isset($array[2]) || !isset($array[4]) || !isset($array[5]) || !isset($array[6])) {
                return '!array . Недостаточное количество полей передано ';
            }

            //ddd($array ); // S019600006938


            $array[6] = preg_replace('/SO1960/', '1960', $array[6]); // O (lat)
            $array[6] = preg_replace('/SО1960/', '1960', $array[6]); // О (rus)

            $array[6] = preg_replace('/S01960/', '1960', $array[6]);
            $array[6] = preg_replace('/S11960/', '1960', $array[6]);

            // ddd($array);


            // ИД Парка - ГРУППЫ
            if (!empty($array[3])) {

                //* Получить Номер Ид по названию Автопарка
                $array_ids_group = Sprwhtop::One_array_from_name($array[3]);
                //ddd($array_ids_group);

                if (empty($array_ids_group)) {
                    return ('1 АП Не найден в справочнике  =' . $array[3] . ".<br>Возможно необходимо добавить фразу в разночтения.");
                }

                $parent_id = $array_ids_group['id'];
                //ddd( $parent_id ); //14

            } else {
                return ('Не найден автопарк -' . $array[3]);
            }


            // $parent_id -ид автопарка
            /**
             * Активный склад
             */
            $wh_home_number = Sklad::getSkladIdActive();
            if (!isset($wh_home_number)) {
                return '2 Не найден в справочнике. Id = ' . $wh_home_number;
            }




            //
            if (!empty($array[6]) && !is_numeric($array[6]) ) {
                return ' array[6] . ШТРИХКОД не является числом ';
            }


            /**
             * ПОИСК полного Двойника=Накладной
             * findDoubles_many_parameters       ($para_date, $para_akt, $wh_home_number, $para_vid, $para_barcode)
             */
            if ( !empty($array[6]) &&  Sklad::findDoubles_many_parameters($date_day, $array[1], $wh_home_number, $array[2], $array[6])) {
                $alert_mess = (
                    "<br>" .
                    "<b>Двойник Накладной АСУОП. Операция остановлена.</b>" .
                    "<br> дата - " . $date_day .
                    "<br> Акт № <b>" . $array[1] . "</b>" .
                    "<br> Вид операции: " . $array[2] .
                    "<br> ПЕ = " . $array[3] . ', ' . $array[4] . ', ' .
                    //$array[5] .
                    "<br> Bar_code = " . $array[6] .
                    "<br> "
                );
                return $alert_mess;

            } else {

                /// Sklad
                $sklad = Sklad::getSkladIdActive();    // Активный склад (_SESSION)
                ///
                if (!isset($sklad)) {
                    //throw new NotFoundHttpException( 'Не найден в справочнике. Id' );
                    return '2 Не найден в справочнике. Id = ' . $sklad;
                }

                ///
                $model = new Sklad();
                $model->id = Sklad::setNext_max_id();


                //                ddd($array);
                //                ddd($this); //one_day


                //
                $model->dt_create_timestamp = strtotime($date_day);
                //ddd($model );

                ///
                /// Если задан Next_time()
                ///
                if ($array[2] == 'Акт демонтажа' || $array[2] == 'Акт демонтажа и возврата' || $array[2] == 'АДВ') {
                    /// Демонтаж
                    $model->dt_create_timestamp = strtotime($date_day);
                } else {
                    /// Монтаж
                    $model->dt_create_timestamp = strtotime($date_day) + 60; //
                }

                ///
                $model->dt_create = date('d.m.Y H:i:s', $model->dt_create_timestamp);
                $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
                $model->wh_home_number = (int)$sklad;

                //ddd($model);

                ///
                if (!empty($array)) {
                    if (!isset($array[2])) {
                        return 'array[ 2 ]';
                    }
                }


                ///
                ///   АДВ - полное снятие (иногда при переводе автобуса в другой парк)
                ///
                if ($array[2] == 'Акт демонтажа' || $array[2] == 'Акт демонтажа и возврата' || $array[2] == 'АДВ') {
                    $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
                    $model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;
                }
                if ($array[2] == 'Акт монтажа' || $array[2] == 'Акт монтажа и новых устр.') {
                    $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;
                    $model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;
                }


// ddd($array);

                //
                //
                if (!empty($array[4])) {// Гос.номер / Теперь  +БОРТ

                    //ddd($array);
                    ///  0 => '15.02.2021'
                    //    1 => '798'
                    //    2 => 'Акт демонтажа'
                    //    3 => 'Алматыэлектротранс ТОО'
                    //    4 => '017DE02'
                    //    5 => 'Помощник водителя GJ-DA04'
                    //    6 => '043131'
                    //    7 => '1'
                    //    8 => '128 608'
                    //    9 => '128 608'

                    //* Получить Номер Ид по названию / Гос.номер / БОРТ
                    $wh_element_id = Sprwhelement::All_id_from_name($array[4], $parent_id);

                }


                // ddd($wh_element_id);


                //
                if (!isset($wh_element_id) || empty($wh_element_id)) {
                    //  ddd($array);

                    return ('3 ARRAY 4 ||Не найден в справочнике. wh_element_id ' . $array[3] . ' == ' . $array[4] . '; ' .
                        //$array[5] .
                        " <br> wh_element_id ==" . implode(",", $wh_element_id) .
                        " <br> array[3] ==" . $array[3] .
                        " <br> parent_id ==" . $parent_id
                    );
                }


                // * ПОЛНЫЕ ДАННЫЕ
                // * Конечного Склада и его Компании
                $fullArray = Sprwhelement::findFullArray($wh_element_id[0]['id']);
                $fullArray_ss = Sprwhelement::findFullArray($model->wh_home_number);


                //ddd($fullArray);

                //ddd($array);

                if ($array[2] == 'Акт демонтажа' || $array[2] == 'Акт демонтажа и возврата' || $array[2] == 'АДВ') {
                    // ИСТОЧНИК
                    $model->wh_debet_top = (int)$fullArray['top']['id'];
                    $model->wh_debet_element = (int)$fullArray['child']['id'];
                    // ИСТОЧНИК
                    $model->wh_debet_name = $fullArray['top']['name'];
                    $model->wh_debet_element_name = $fullArray['child']['name'];

                    // ПРИЕМНИК
                    $model->wh_destination = (int)$fullArray_ss['top']['id'];
                    $model->wh_destination_element = (int)$fullArray_ss['child']['id'];
                    // ПРИЕМНИК
                    $model->wh_destination_name = $fullArray_ss['top']['name'];
                    $model->wh_destination_element_name = $fullArray_ss['child']['name'];

                    // CS
                    $model->wh_cs_number = $model->wh_debet_element;
                }
                if ($array[2] == 'Акт монтажа' || $array[2] == 'Акт монтажа и новых устр.') {
                    // ПРИЕМНИК
                    $model->wh_destination = (int)$fullArray['top']['id'];
                    $model->wh_destination_element = (int)$fullArray['child']['id'];
                    //ПРИЕМНИК
                    $model->wh_destination_name = $fullArray['top']['name'];
                    $model->wh_destination_element_name = $fullArray['child']['name'];

                    // ИСТОЧНИК
                    $model->wh_debet_top = (int)$fullArray_ss['top']['id'];
                    $model->wh_debet_element = (int)$fullArray_ss['child']['id'];
                    // ИСТОЧНИК
                    $model->wh_debet_name = $fullArray_ss['top']['name'];
                    $model->wh_debet_element_name = $fullArray_ss['child']['name'];

                    // CS
                    $model->wh_cs_number = (int)$model->wh_destination_element;

                    /// CS - wh_dalee
                    $model->wh_dalee = (int)$model->wh_destination;
                    $model->wh_dalee_element = (int)$model->wh_destination_element;
                    $model->wh_dalee_name = $model->wh_destination_name;
                    $model->wh_dalee_element_name = $model->wh_destination_element_name;
                }


                // Примечание
                $model->tx = 'Акт № ' . $array[1];

                 // ddd($model);

                //ddd($array);

                if (isset($array[6]) && !empty($array[6])) {
                    // * GLOBAL. Приводит Все ШТРИХКОДЫ к нормальному ВИДУ
                    $barcode = MyHelpers::barcode_normalise($array[6]);

                    /// * Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его barcode)
                    /// // Это тоже приведется к нормальному виду
                    $fullArray_BY_barcode = Spr_globam_element::findFullArray_BY_barcode($barcode);

                    //ddd($fullArray_BY_barcode);

                    ///
                    ///  Проверяем на соответсвие названия АСУОП
                    if (!isset($fullArray_BY_barcode)) {
                        if ($fullArray_BY_barcode['child']['name'] != $array[6]) {
                            $str = implode(', ', $array);
                            return ('Оотсутствует ШТРИХКОД в пуле = ' . $barcode .
                                '<br><br> ' . $str);
                        }
                    }

                    //ddd($array);


                    // ASUOP - готовый массив
                    $array_pos = [
                        "wh_tk_amort" => $fullArray_BY_barcode['top']['id'],
                        "wh_tk_element" => $fullArray_BY_barcode['child']['id'],
                        "name" => (string)$fullArray_BY_barcode['child']['name'],
                        "intelligent" => (string)$fullArray_BY_barcode['child']['intelligent'],
                        "ed_izmer" => "1",
                        "ed_izmer_num" => "1",
                        "take_it" => "0",
                        "bar_code" => $barcode
                    ];

                } else {
                    ///Если НЕТ ШТРИХКОДА, ТО ЭТО УСТРОЙСТВО ищем в справочнике

// ddd(11111);
                    //
                    if (isset($array[5]) || !empty($array[5])) {

                        // $str1 = 'Автомобильный стабилизатор напряжения';
                        // $str2 = 'для терминалов NEW8210';
                        // $str3 = 'c импульсной защитой';
                        //
                        //   //Автомобильный стабилизатор напряжения для терминалов NEW8210 c импульсной защитой
                        //   $str4 = 'Автомобильный';
                        //   $str5 = 'стабилизатор';
                        //   $str6 = 'напряжения';
                        //   $str7 = 'терминалов NEW8210';
                        //   $str8 = 'импульсной защитой';


                        /// Если они все присутсвуют в тексте
                        // if (
                        //     mb_stristr($array[5], $str1)
                        //     || mb_stristr($array[5], $str2)
                        //     || mb_stristr($array[5], $str3)
                        //     || mb_stristr($array[5], $str4)
                        //     || mb_stristr($array[5], $str5)
                        //     || mb_stristr($array[5], $str6)
                        //     || mb_stristr($array[5], $str7)
                        //     || mb_stristr($array[5], $str8)
                        // ) {

                            /// * Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его barcode)
                            /// // Это тоже приведется к нормальному виду
                            //  'Автомобильный стабилизатор напряжения''для терминалов NEW8210''c импульсной защитой';
                            //$fullArray_BY_barcode = Spr_globam_element::findFullArray(10);

                            // $fullArray_BY_barcode = Spr_globam_element::findFullArray_by_names3($str1, $str2, $str3);


                            //
                            $fullArray_BY_barcode = Spr_globam_element::findArray_by_names_and_goup( trim($array[5]) );

                            //  'Автомобильный стабилизатор напряжения''для терминалов NEW8210''c импульсной защитой';
                            // ddd($fullArray_BY_barcode);


                            $array_pos = [
                                "wh_tk_amort" => $fullArray_BY_barcode['top']['id'],
                                "wh_tk_element" => $fullArray_BY_barcode['child']['id'],
                                "name" => (string)$fullArray_BY_barcode['child']['name'],
                                "intelligent" => (string)$fullArray_BY_barcode['child']['intelligent'],
                                "ed_izmer" => "1",
                                "ed_izmer_num" => "1",
                                "take_it" => "0",
                                "bar_code" => ""
                            ];
                        // }

                        ///...
                        /// ИЛИ Вот...
                        ///


                        /// Если они все присутсвуют в тексте
                        // if (strlen($array[5]) > 10) {
                        //     $fullArray_BY_barcode = Spr_globam_element::findFullArray_by_name($array[5]);
                        //     //ddd($fullArray_BY_barcode);
                        //     $array_pos = [
                        //         "wh_tk_amort" => $fullArray_BY_barcode['top']['id'],
                        //         "wh_tk_element" => $fullArray_BY_barcode['child']['id'],
                        //         "name" => (string)$fullArray_BY_barcode['child']['name'],
                        //         "intelligent" => (string)$fullArray_BY_barcode['child']['intelligent'],
                        //         "ed_izmer" => "1",
                        //         "ed_izmer_num" => "1",
                        //         "take_it" => "0",
                        //         "bar_code" => ""
                        //     ];
                        // }


                        //ddd(1111);

                    } else {
                        return '1. В пуле отсутствует ШТРИХКОД 2. Это - не автомобильный стабилизатоор';
                    }
                }

                //  0 => '11.07.2022'
                //  1 => '01228'
                //  2 => 'Акт демонтажа'
                //  3 => 'Алматыэлектротранс ТОО'
                //  4 => '410LY02'
                //  5 => 'GSM-антенна GlobalSat TR-600'
                //  6 => ''
                //  7 => '1'
                //  8 => '10 000'

                 // Если массив меньше нуля
                 if ( count($array_pos)<1 ){
                      $model->array_count_all = 0;
                 }

                //
                $model->array_tk_amort = [$array_pos];

//                ddd($array_pos);
//                ddd($model);

                ///||||||||||||||||||||||||||||||||||
                /// Подсчет СТРОК Всего
                ///
                if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort'])
                    && is_array($model['array_tk_amort'])) {
                    $xx1 = count($model['array_tk_amort']);
                }
                $model->array_count_all = (int)$xx1;


                ///
                /// SAVE
                ///


               // ddd($model);

                if (!$model->save(true)) {
                    ddd($model->errors);

                    return 'SAVE Ошибка валидации';
                }

                return '';
            }
        }

        return '';
    }

    /**
     * @param $next_time
     */
    public function setNext_time($next_time)
    {
        $this->next_time = (int)$next_time;
    }

    /**
     */
    public function getNext_time()
    {
        return $this->next_time;
    }


    /**
     * @param $copy_past
     */
    public function setCopy_past($copy_past)
    {
        $this->copy_past = $copy_past;

    }


    /**
     */
    public function getCopy_past()
    {
        return $this->copy_past;
    }


    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTransfer_delivered($id = 0)
    {
        if (Sklad_transfer::setTransfer_delivered($id, Sklad_transfer::TRANSFERED_OK)) // Получил
        {
            //            dd($id);
            //            dd($sklad);

            return $this->actionRewrite();
        } else           //return $this->goHome();
        {
            throw new NotFoundHttpException('Обратитесь к разработчику');
        }

    }


    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTransfer_dont($id = 0)
    {
        // Отказ получать
        if (!Sklad_transfer::setTransfer_delivered(
            $id, Sklad_transfer::TRANSFERED_REFUSE
        )) // Отказ получать
        {
            throw new NotFoundHttpException('Не установлена отметка об отказе');
        }

        //return $this->goHome();
        //return $this->redirect('/sklad/in?otbor=' . $sklad);

        return $this->redirect([
            'sklad/in']);

    }


    /**
     * Sklad ASEMTAI
     * Тут будет функционал перепрошивки CVB-24 по накладным
     *
     * @return string
     */
    public function actionRewrite() // Sklad/
    {
        $para = Yii::$app->request->queryParams;

        if (!isset($para['otbor'])) {

            //#############
            $session = Yii::$app->session;
            $para['otbor'] = $sklad = $session->get('sklad_');
            //#############
        } else {
            $session = Yii::$app->session;
            $session->set('sklad_', $para['otbor']);
        }

        //        dd($para);

        return $this->redirect('in?otbor=' . $sklad);

    }


    /**
     * ВНУТРИ НАКЛАДНОЙ (Производство) ASEMTAI
     * SKLAD FOR Asemtai
     * -
     *
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     * @throws ExitException
     */
    public function actionRewrite_update($id)
    {
        $para = Yii::$app->request->queryParams;

        //$para_post = Yii::$app->request->post();
        //        if($para_post['add_button']){
        //            ddd($para_post);
        //            //            ddd($para_post['add_button']);
        //        }


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


        ////////
        $sklad = Sklad::getSkladIdActive();         // Активный склад
        $parett_sklad = Sprwhelement::find_parent_id($sklad); // Парент айди этого СКЛАДА


        if (!isset($sklad) || empty($sklad)) {
            throw new UnauthorizedHttpException('REWRITE. Sklad=0');
        }


        ////////
        $model = Sklad::findModel($id);  /// this is  _id !!!!! //$model->getDtCreateText()

        if (!is_object($model)) {
            throw new NotFoundHttpException('Нет такой накладной');
        }


        /// Автобусы ЕСТЬ?
        if (isset($model['array_bus']) && !empty($model['array_bus'])) {
            $items_auto = Sprwhelement::findAll_Attrib_PE(
                array_map('intval', $model['array_bus'])
            );
        } else {
            $items_auto = [
            ];
        } // ['нет автобусов'];
        /// Получаем ТехЗадание. ШАПКА
        if ($model->tz_id) {
            $tz_head = Tz::findModelDoubleAsArray((int)$model->tz_id);
        } else {
            $tz_head = [
            ];
        }


        //   ddd($tz_head);


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


        /////////// ПРЕД СОХРАНЕНИЕМ
        if ($model->load(Yii::$app->request->post())) {

            $model->wh_home_number = (integer)$sklad;


            ////  Прописать во все массивы ЕД.Изм и Кол-во
            ///
            $model->array_tk_amort = Sklad::setArrayEdIzm_Kolvo($model->array_tk_amort);


            ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
            ///  ТАБ 1
            // $model->array_tk_amort  = Sklad::setArraySort1( $model->array_tk_amort );
            $model->array_tk_amort = Sklad::setArrayClear($model->array_tk_amort);
            ///  ТАБ 2
            $model->array_tk = Sklad::setArraySort2($model->array_tk);

            ////  Приводим ключи в прядок! УСПОКАИВАЕМ ОЧЕРЕДЬ
            $model->array_tk_amort = Sklad::setArrayToNormal($model->array_tk_amort);
            $model->array_tk = Sklad::setArrayToNormal($model->array_tk);
            $model->array_casual = Sklad::setArrayToNormal($model->array_casual);


            if ((int)$model['sklad_vid_oper'] == 2) {
                $model['sklad_vid_oper_name'] = 'Приходная накладная';
            }
            if ((int)$model['sklad_vid_oper'] == 3) {
                $model['sklad_vid_oper_name'] = 'Расходная накладная';
            }


            ///////
            /// ИСТОЧНИК
            $xx1 = Sprwhelement::findFullArray($model->wh_debet_element);
            /// ПРИЕМНИК
            $xx2 = Sprwhelement::findFullArray($model->wh_destination_element);

            $model->wh_debet_name = $xx1['top']['name'];
            $model->wh_debet_element_name = $xx1['child']['name'];

            $model->wh_destination_name = $xx2['top']['name'];
            $model->wh_destination_element_name = $xx2['child']['name'];

            //  ddd($model);
            ////////......ПОДСЧЕТ СТРОК
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


            //            ddd($model);


            if ($model->save(true)) {
                if (isset($model['wh_home_number'])) {
                    $sklad = $model['wh_home_number'];

                    return $this->redirect('/sklad/in?otbor=' . $sklad);
                }

                return $this->actionRewrite();
            }
        }


        return $this->render(
            'sklad_rewrite/_form', [
                'model' => $model,
                'sklad' => $sklad,
                'items_auto' => $items_auto,
                'tz_head' => $tz_head,
            ]
        );

    }


    /**
     * Просто КОПИЯ этой накладной с новым номером ПО НАЖАТИЮ КНОПКИ "Расходная накладная"
     * actionCopycard_rashod
     *
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws ExitException
     */
    public function actionCopycard_rashod($id)
    {
        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');

        $model = Sklad::findModelDouble($id);  /// this is  _id !!!!!
        //  dd($model);

        $new_doc = new Sklad();


        ///Сливаем в новую накладную старую копию и дописываем новый номер
        //    unset($model->_id);
        //    $new_doc=$model;

        $new_doc->id = (int)Sklad::setNext_max_id();
        $new_doc['sklad_vid_oper'] = (int)3; //РАСХОДНАЯ накладная
        $new_doc['sklad_vid_oper_name'] = 'Расходная накладная';

        // Получить полный Массив-знаний по ОТПРАВИТЕЛЮ
        $array_request = Sprwhelement::findFullArray($sklad);
        // dd($array_request);
        //FROM
        $new_doc['wh_home_number'] = (integer)$sklad;
        $new_doc['wh_debet_top'] = $array_request['top']['id'];
        $new_doc['wh_debet_name'] = $array_request['top']['name'];
        $new_doc['wh_debet_element'] = $array_request['child']['id'];
        $new_doc['wh_debet_element_name'] = $array_request['child']['name'];

        //TO
        $new_doc['wh_destination'] = $model['wh_destination'];
        $new_doc['wh_destination_name'] = $model['wh_destination_name'];
        $new_doc['wh_destination_element'] = $model['wh_destination_element'];
        $new_doc['wh_destination_element_name'] = $model['wh_destination_element_name'];


        $new_doc['tz_id'] = $model['tz_id'];
        $new_doc['tz_name'] = $model['tz_name'];
        $new_doc['tz_date'] = $model['tz_date'];
        $new_doc['dt_deadline'] = $model['dt_deadline'];


        $new_doc['array_tk_amort'] = $model['array_tk_amort'];
        $new_doc['array_tk'] = $model['array_tk'];
        $new_doc['array_casual'] = $model['array_casual'];
        $new_doc['array_bus'] = $model['array_bus'];


        //       ddd($new_doc);

        if (!$new_doc->save(true)) {
            throw new NotFoundHttpException('Сохранить оказалось невозможно');
        }


        return $this->redirect('/sklad/in?otbor=' . $sklad);

    }


    /**
     * Sklad ASEMTAI
     * Подготовка и отправка ПИСЬМА в ТХА
     * с отчетом о ПРОШИВКЕ и ПРИВЯЗКЕ МСАМ карт к устройствам,
     * а так же привязке устройств к Маршрутам и Автобусам
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionMail_to_tha($id) // Sklad/
    {
        // $session = Yii::$app->session;
        // $sklad = $session->get('sklad_');

        $model = $this->findModel($id);  /// this is  _id !!!!!

        return $this->render(
            'mail_to_tha/_form', [
                'model' => $model,
            ]
        );

    }


    /**
     * THA-Agent (Славик).
     * Подтрверждает отправку/согласование с ТХА
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTha_agent($id)
    {
        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');

        $model = $this->findModel($id);  /// this is  _id !!!!!


        if ($model->load(Yii::$app->request->post())) {

            //            dd($model);
            ///Сливаем во едино базу с коментами Агента ТХА
            $old_xx = $model->oldAttributes;
            $new_xx = $model->attributes;

            $xx22_old = $old_xx['array_tk_amort'];
            $xx22_new = $new_xx['array_tk_amort'];

            $x = 0;
            while (isset($xx22_old[$x])) {
                $xx22_old[$x]['the_bird'] = $xx22_new[$x]['the_bird'];
                $xx22_old[$x]['tx'] = $xx22_new[$x]['tx'];
                $x++;
            }

            $model['array_tk_amort'] = $xx22_old;
            //dd($model);

            if (!$model->save())
                dd($model->errors);
        }

//            return $this->redirect( '/sklad?otbor=' . $sklad );


        return $this->render(
            'sklad_rewrite_tha/_form', [
                'model' => $model,
                'sklad' => $sklad,
            ]
        );

    }


    /**
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionPrihod()
    {

        dd(123);


        $id_array = Yii::$app->request->get();

        $sklad_transfer = Sklad_transfer::findModel($id_array['id']);

        //        dd($sklad_transfer);

        if (!isset($sklad_transfer)) {
            throw new NotFoundHttpException('Обратитесь к разработчику');
        }


        if ($sklad_transfer->load(Yii::$app->request->post())) {


            //#############
            $session = Yii::$app->session;
            $sklad = $session->get('sklad_');
            //#############
            //            'wh_home_number', // ид текущего склада
            //            'sklad_vid_oper',
            //            'dt_create',

            $new_doc = new Sklad();     // Новыая накладная


            $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
            $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

            $new_doc->wh_home_number = (int)$sklad; // мой текущий склад

            $new_doc->dt_create = $sklad_transfer->dt_create;

            $new_doc->wh_debet_top = $sklad_transfer->wh_debet_top;
            $new_doc->wh_debet_name = $sklad_transfer->wh_debet_name;
            $new_doc->wh_debet_element = $sklad_transfer->wh_debet_element;
            $new_doc->wh_debet_element_name = $sklad_transfer->wh_debet_element_name;

            $new_doc->wh_destination = $sklad_transfer->wh_destination;
            $new_doc->wh_destination_name = $sklad_transfer->wh_destination_name;
            $new_doc->wh_destination_element = $sklad_transfer->wh_destination_element;
            $new_doc->wh_destination_element_name = $sklad_transfer->wh_destination_element_name;

            $new_doc->user_group_id = (integer)$sklad_transfer->id;
            $new_doc->user_id = (int)Yii::$app->getUser()->identity->id;
            $new_doc->user_name = Yii::$app->getUser()->identity->username;

            date_default_timezone_set("Asia/Almaty");
            $new_doc->dt_update = date('d.m.Y H:i:s', strtotime('now'));

            $new_doc->array_tk_amort = $sklad_transfer->array_tk_amort;
            $new_doc->array_tk = $sklad_transfer->array_tk;


            //        vd($sklad_transfer);


            $new_doc->id = (int)Sklad::setNext_max_id();

            unset($sklad_transfer ['dt_transfer_start']);

            //         vd($new_doc);
//            if ( isset( $new_doc ) && $new_doc->save( true ) ) {
//
//
//                dd( $new_doc );
//
//
//                if ( MyHelpers::Mongo_save( 'sklad', 'id', $new_doc->_id, (integer)$max_value ) ) {
//                    return $this->redirect( 'sklad_in/in?otbor=' . $sklad );
//                }
//
//            } else {
//                vd( $new_doc );
//            }
        }


        return $this->render(
            'sklad_in/_form_sklad', [
                //                'model' => $tz_body,        //'multi_tz' => $tz_body->multi_tz,
                //                'new_doc' => $new_doc,
            ]
        );

    }


    /**
     * Создаем накладную
     * вариант со Штрихкодами
     * по умножению
     * (ТехЗадание * Мультипликатор)
     *
     * @param int $tz_id
     * @param int $multi
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate_multi($tz_id = 0, $multi = 0)
    {
        $buff2 = [
        ];

        // TZ find()
        $tz_body = Tz::find()
            ->where([
                'id' => (integer)$tz_id])
            ->one();       // Tz
        #########
        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');
        //dd($sklad);


        $new_doc = new Sklad();     // Новыая накладная

        $max_value = Sklad::setNext_max_id();
        $max_value++;
        $new_doc->id = (integer)$max_value;
        $new_doc->wh_home_number = (int)$sklad;

        $new_doc->user_id = Yii::$app->getUser()->getId();
        $new_doc->user_name = Yii::$app->user->identity->username;

        $new_doc->tz_id = (integer)$tz_body->id;
        $new_doc->tz_name = $tz_body->name_tz;

        $new_doc->tz_date = $tz_body->dt_create;
        $new_doc->dt_deadline = $tz_body->dt_deadline;


        // Получить полный Массив-знаний по ОТПРАВИТЕЛЮ
        $array_request = Sprwhelement::findFullArray(86);
        // dd($array_request);
        //FROM
        $new_doc['wh_home_number'] = (integer)$sklad;
        $new_doc['wh_debet_top'] = $array_request['top']['id'];
        $new_doc['wh_debet_name'] = $array_request['top']['name'];
        $new_doc['wh_debet_element'] = $array_request['child']['id'];
        $new_doc['wh_debet_element_name'] = $array_request['child']['name'];


        // Получить полный Массив-знаний по ОТПРАВИТЕЛЮ
        $array_request = Sprwhelement::findFullArray($sklad);
        //TO
        $new_doc['wh_destination'] = $array_request['top']['id'];
        $new_doc['wh_destination_name'] = $array_request['top']['name'];
        $new_doc['wh_destination_element'] = $array_request['child']['id'];
        $new_doc['wh_destination_element_name'] = $array_request['child']['name'];

        ///
        //create_multi


        $new_doc->array_tk_amort = $tz_body->array_tk_amort;

        //$new_doc->array_tk = $tz_body->array_tk ;
        //        $buff_amort = $new_doc->array_tk_amort;
        //        dd($new_doc);

        $all_multi = 0;
        if (isset($new_doc->array_tk_amort) && !empty($new_doc->array_tk_amort)) {
            foreach ($new_doc->array_tk_amort as $string) {

                if ($string['intelligent'] > 0) {
                    //                $x_multi = 0;
                    for ($x_multi = 0; $x_multi < $multi; $x_multi++) {
                        $buff2[$all_multi] = $string;
                        //$buff2[$all_multi]['bar_code']='';

                        $next_while = (int)$buff2[$all_multi]['ed_izmer_num'];
                        $buff2[$all_multi]['ed_izmer_num'] = 1;

                        while ($next_while > 1) {
                            $all_multi++;
                            $next_while--;
                            $buff2[$all_multi] = $string;
                            $buff2[$all_multi]['ed_izmer_num'] = 1;
                        }

                        $all_multi++;
                    }
                }
            }
        }

        $new_doc->array_tk_amort = $buff2;
        unset($buff2);

        $new_doc->array_bus = $tz_body->array_bus;
        $new_doc->array_casual = $tz_body->array_casual;


        if ($new_doc->load(Yii::$app->request->post())) {

            $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
            $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

            /// То самое преобразование ПОЛЯ Милисукунд
            //$new_doc->setDtCreateText( "NOW" );
            //dd(123);
            if ($new_doc->save(true)) {

                //return $this->redirect('/sklad/in?otbor=' . $new_doc->wh_destination_element);
                return $this->redirect('/sklad/in');
            } else {
                dd($new_doc->errors);
            }
        }


        return $this->render(
            '_form_sklad', [
                'model' => $tz_body,
                'new_doc' => $new_doc,
                'sklad' => $sklad,
            ]
        );

    }


    /**
     * Создаем накладную
     * Вариант без Штрихкодов
     *
     * @param int $tz_id
     * @param int $multi
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate_multi_without_barcode($tz_id = 0, $multi = 0)
    {

        $buff2 = [
        ];

        $tz_body = Tz::find()
            ->where([
                'id' => (integer)$tz_id])
            ->one();       // Tz
        #########
        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');
        //dd($sklad);
        #########
        $new_doc = new Sklad();     // Новыая накладная

        $max_value = Sklad::setNext_max_id();
        $max_value++;

        $new_doc->id = (integer)$max_value;

        $new_doc->wh_home_number = (int)$sklad;

        $new_doc->user_id = Yii::$app->getUser()->getId();
        $new_doc->user_name = Yii::$app->user->identity->username;

        $new_doc->tz_id = (integer)$tz_id;
        $new_doc->tz_name = $tz_body->name_tz;
        $new_doc->tz_date = $tz_body->dt_create;
        $new_doc->dt_deadline = $tz_body->dt_deadline;

        $new_doc->array_tk_amort = $tz_body->array_tk_amort;
        $new_doc->array_tk = $tz_body->array_tk;
        $new_doc->array_casual = $tz_body->array_casual;
        $new_doc->array_bus = $tz_body->array_bus;


        $full_sklad = Sprwhelement::findFullArray($sklad);
        //dd($full_sklad);
        /// ИСТОЧНИК
        $new_doc->wh_debet_top = (int)$full_sklad["top"]['id'];
        $new_doc->wh_debet_element = (int)$sklad;
        $new_doc->wh_debet_name = $full_sklad["top"]['name'];
        $new_doc->wh_debet_element_name = $full_sklad["child"]['name'];
        /// ПРИЕМНИК
        $new_doc->wh_destination = (int)$full_sklad["top"]['id'];
        $new_doc->wh_destination_element = (int)$sklad;
        $new_doc->wh_destination_name = $full_sklad["top"]['name'];
        $new_doc->wh_destination_element_name = $full_sklad["child"]['name'];


        $all_multi = 0;
        if (isset($new_doc->array_tk_amort) && !empty($new_doc->array_tk_amort)) {
            foreach ($new_doc->array_tk_amort as $string) {

                if ($string['intelligent'] > 0) {
                    //                $x_multi = 0;
                    for ($x_multi = 0; $x_multi < $multi; $x_multi++) {
                        //                    $buff2[$all_multi] = $string;
                        //                        //                print_r($buff_amort[$x_multi]);
                        //                        //                echo "<br>";
                        //                    $buff2[$all_multi]['bar_code']='101010101';
                        $all_multi++;
                    }
                } else {

                    //dd($string);

                    $buff2[$all_multi] = $string;
                    $buff2[$all_multi]['ed_izmer_num'] = $string['ed_izmer_num'] *
                        $multi;

                    $buff2[$all_multi]['bar_code'] = 'нет';
                    $all_multi++;
                }
            }
        }

        $new_doc->array_tk_amort = $buff2;
        unset($buff2);


        //.....
        $all_multi = 0;
        if (isset($new_doc->array_tk) && !empty($new_doc->array_tk)) {

            foreach ($new_doc->array_tk as $string) {

                $buff2[$all_multi] = $string;
                $buff2[$all_multi]['ed_izmer_num'] = $string['ed_izmer_num'] * $multi;

                $buff2[$all_multi]['bar_code'] = 'нет';
                $all_multi++;
            }
            //dd($buff2);
            $new_doc->array_tk = $buff2;
        }

        // Получить полный Массив-знаний по ОТПРАВИТЕЛЮ
        $array_request = Sprwhelement::findFullArray($sklad);
        //TO
        $new_doc['wh_destination'] = $array_request['top']['id'];
        $new_doc['wh_destination_name'] = $array_request['top']['name'];
        $new_doc['wh_destination_element'] = $array_request['child']['id'];
        $new_doc['wh_destination_element_name'] = $array_request['child']['name'];


        //ddd($new_doc);

        if ($new_doc->load(Yii::$app->request->post())) {

            $new_doc->user_id = (integer)Yii::$app->getUser()->identity->id;
            $new_doc->user_name = Yii::$app->getUser()->identity->username;
            $new_doc->dt_update = date('d.m.Y H:i:s', strtotime('now'));

            $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;
            $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;

            //            ddd($new_doc);

            if ($new_doc->save(true)) {
                //return $this->redirect('/sklad/in?otbor=' . $new_doc->wh_destination_element);
                return $this->redirect('/sklad/in');
            }
        }


        return $this->render(
            '_form_sklad', [
                'model' => $tz_body,
                //'multi_tz' => $tz_body->multi_tz,
                'new_doc' => $new_doc,
                'sklad' => $sklad,
            ]
        );

    }


    /**
     * Создаем новую  накладную из Шаблона
     *
     * @param int $shablon_id
     * @return string|Response
     */
    public function actionCreatefrom_shablon($shablon_id = 0)
    {
        $para = Yii::$app->request->queryParams;        //dd($para);

        $session = Yii::$app->session;
        $session->open();

        if (isset($para['otbor']) && !empty($para['otbor'])) {
            //$sklad= Yii::$app->params['sklad'] = $para['otbor'];
            $session->set('sklad_', $para['otbor']);
        }

        $sklad = $session->get('sklad_');
        //        dd($sklad);
        #############
        $shablon_body = Shablon::find()
            ->where([
                'id' => (integer)$shablon_id])
            ->one();
        #############
        #############
        $new_doc = new Sklad();     // Новая накладная

        $max_value = Sklad::setNext_max_id();
        $max_value++;
        $new_doc->id = (integer)$max_value;

        $new_doc->wh_home_number = (int)$sklad;

        //        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
        //        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

        $new_doc->wh_debet_top = 1;
        $new_doc->wh_debet_element = 1;
        //        $new_doc->wh_debet_name = "City111-Bus";
        //        $new_doc->wh_debet_element_name = "4645645";

        $new_doc->wh_destination = 2;
        $new_doc->wh_destination_element = "1917";
        //        $new_doc->wh_destination_name           = "Guidejet TI";
        //        $new_doc->wh_destination_element_name   = "Склад №1";

        $new_doc->array_tk_amort = $shablon_body->array_tk_amort;
        $new_doc->array_tk = $shablon_body->array_tk;
        //        $new_doc->array_casual      = $shablon_body->array_casual;


        if ($new_doc->load(Yii::$app->request->post())) {

            $new_doc->user_id = Yii::$app->user->identity->id;
            $new_doc->user_name = Yii::$app->user->identity->username;
            $new_doc->user_group_id = Yii::$app->user->identity->group_id;

            $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
            $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

            $new_doc->tz_id = 0;
            $new_doc->tz_name = '';
            $new_doc->tz_date = '';
            date_default_timezone_set("Asia/Almaty");
            $new_doc->dt_create = date('d.m.Y H:i:s', strtotime('now'));
            /// То самое преобразование ПОЛЯ Милисукунд
            //$new_doc->setDtCreateText( "NOW" );


            $new_doc->dt_deadline = '';

            if ($new_doc->save()) {

                return $this->redirect('/sklad/in?otbor=' . $sklad);
            }
        }


        return $this->render(
            '_form_sklad_shablon', [
                'model' => $shablon_body,
                'new_doc' => $new_doc,
                'sklad' => $sklad,
            ]
        );

    }


    /**
     * Создаем новую  накладную
     * (Приход, Расход, Инвентаризация)
     *
     * @return string|Response
     */
    public function actionCreate()
    {
        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');
        //dd($sklad);
        //        $model->id = $max_value;

        $new_doc = new Sklad();     // Новая накладная

        $new_doc->id = (int)Sklad::setNext_max_id();
        //            $new_doc->tz_id = (integer) ;

        $new_doc->wh_home_number = (int)$sklad;
        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)
        //        $new_doc->dt_create         = $model->dt_create;
        //        $new_doc->wh_debet_top      = $model->wh_debet_top;
        //        $new_doc->wh_debet_name     = $model->wh_debet_name;
        //        $new_doc->wh_debet_element  = $model->wh_debet_element;
        //        $new_doc->wh_debet_element_name = $model->wh_debet_element_name;
        //        $new_doc->wh_destination            = $model->wh_destination;
        //        $new_doc->wh_destination_name       = $model->wh_destination_name;
        //        $new_doc->wh_destination_element    = $model->wh_destination_element;
        //        $new_doc->wh_destination_element_name = $model->wh_destination_element_name;


        $new_doc->user_id = (int)Yii::$app->getUser()->identity->id;
        $new_doc->user_name = Yii::$app->getUser()->identity->username;
        date_default_timezone_set("Asia/Almaty");
        $new_doc->dt_update = date('d.m.Y H:i:s', strtotime('now'));

        $new_doc->array_tk_amort = [
        ];
        $new_doc->array_tk = [
        ];
        $new_doc->array_casual = [
        ];


        if ($new_doc->load(Yii::$app->request->post())) {
            /// То самое преобразование ПОЛЯ Милисукунд
            //$new_doc->setDtCreateText( "NOW" );
            //Перебивка номера накладной
            if ((int)Sklad::setNext_max_id() > (int)$new_doc->id) {
                $new_doc->id = (int)Sklad::setNext_max_id();
                $tx = $new_doc->tx;
                $new_doc->tx = $tx . "от админа (аварийно изм.номер накладной)";
            }
            //ddd($new_doc);
            //

            if ($new_doc->save(true)) {


                return $this->redirect('/sklad/index?sort=-id&sklad=' . $sklad);
            } else {
                //dd($model);
                return $this->redirect('/');
            }
        }


        return $this->render(
            'sklad_in/_form', [
                //            'model' => $model,
                'new_doc' => $new_doc,
                'sklad' => $sklad,
            ]
        );

    }


    /**
     *
     * ПЕРЕДАЧА накладной в TRANSFER  * по нажатию КНОПКИ
     * -
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws ExitException
     */
    public function actionCopyToTransfer()
    {
        $id = Yii::$app->request->get('id');
        //
        $model = Sklad::findModel($id);

        // CS
        //$model->wh_cs_number = (int)0;
        ///////
        $model->dt_transfered_date = date('d.m.Y H:i:s', strtotime('now'));
        $model->dt_transfered_user_id = (integer)Yii::$app->getUser()->identity->id;
        $model->dt_transfered_user_name = Yii::$app->getUser()->identity->username;


        /// Важно!!! Изименение времени накладной на одну секунду!
        /// Во время нажатия на кнопку "ПЕРЕДАТЬ В БУФЕР ПЕРЕДАЧИ"
        $model->dt_create_timestamp++;


        if ($err = Sklad_transfer::setTransfer($model) != null) {
            if (!$model->save(true)) {
                ddd($model->errors);
            }
        } else {
            ddd($err);
        }

        //Возврат по реферу. REFER
        return $this->redirect(Yii::$app->request->referrer);
    }


    /**
     * Принятие накладной из Трансфера. С ПРОСМОТРОМ.
     * =
     *
     * @return string|Response
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionPrihod2()
    {
        $para = Yii::$app->request->queryParams;        //dd($para);
        $sklad = Yii::$app->params['sklad'] = $para['otbor'];
        $id = $id_before_transfered = $para['id'];    // OID

        $model = Sklad_transfer::findModel($id);

        if ($model->dt_transfered_ok != 0) {
            throw new HttpException('Накладная уже передавалась', 5);
        }

        //dd($model);
        //#############
        //        $session = Yii::$app->session;
        //        $sklad = $session->get('sklad_');
        //#############


        $new_doc = new Sklad();     // Новая накладная

        $new_doc->id = (int)Sklad::setNext_max_id();
        $new_doc->wh_home_number = (int)$sklad;

        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

        $new_doc->wh_debet_top = $model->wh_debet_top;

        $new_doc->wh_debet_name = $model->wh_debet_name;
        $new_doc->wh_debet_element = $model->wh_debet_element;
        $new_doc->wh_debet_element_name = $model->wh_debet_element_name;
        $new_doc->wh_destination = $model->wh_destination;
        $new_doc->wh_destination_name = $model->wh_destination_name;
        $new_doc->wh_destination_element = $model->wh_destination_element;
        $new_doc->wh_destination_element_name = $model->wh_destination_element_name;

        $new_doc->wh_dalee = $model->wh_dalee;
        $new_doc->wh_dalee_element = $model->wh_dalee_element;


        $new_doc->tz_id = $model->tz_id;
        $new_doc->tz_name = $model->tz_name;
        $new_doc->tz_date = $model->tz_date;
        //        dd($new_doc);
        /////////////////
        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk'] = $this->getTkNames($model['array_tk']);


        ///
        ///  Автобусы ЕСТЬ?
        if (isset($model['array_bus']) && !empty($model['array_bus'])) {
            $items_auto = Sprwhelement::findAll_Attrib_PE(
                array_map('intval', $model['array_bus'])
            );
        } else {
            $items_auto = [
            ];
        }


        //
        // LOAD
        //
        if ($new_doc->load(Yii::$app->request->post())) {

            //$new_doc =$model;  // ПЕРЕГОНКА Noo !!!!

            $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
            $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

            $new_doc->user_id = (int)Yii::$app->getUser()->identity->id;
            $new_doc->user_name = Yii::$app->getUser()->identity->username;


            $new_doc->dt_create_timestamp = $model->dt_create_timestamp + 2;
            $new_doc->dt_create = date('d.m.Y H:i:s', $model->dt_create_timestamp + 2);
            $new_doc->dt_update = date('d.m.Y H:i:s', strtotime('now +2 seconds'));

            $new_doc->array_tk_amort = $model->array_tk_amort;
            $new_doc->array_tk = $model->array_tk;
            $new_doc->array_casual = $model->array_casual;
            $new_doc->array_bus = $model->array_bus;


            ///
            $x1 = $this->Count_rows($new_doc->array_tk_amort);
            $x2 = $this->Count_rows($new_doc->array_tk);
            $x3 = $this->Count_rows($new_doc->array_casual);
            $new_doc->array_count_all = $x1 + $x2 + $x3;


            //Перебивка номера накладной
            if ((int)Sklad::setNext_max_id() > (int)$new_doc->id) {
                $new_doc->id = (int)Sklad::setNext_max_id();
            }


            // ddd($new_doc);

            if ($new_doc->save(true)) {
                // ОТметка о получении НАКЛАДНОЙ
                Sklad_transfer::setTransfer_delivered($id, Sklad_transfer::TRANSFERED_OK);

                return $this->redirect('/sklad/in?otbor=' . $sklad);
            } else {
                $new_doc->errors;
            }
        }

        //
        return $this->render(
            'sklad_in/_form', [
                'model' => $model,
                'new_doc' => $new_doc,
                'sklad' => $sklad,
                'items_auto' => $items_auto,
            ]
        );
    }


    /**
     * ИЗ БУФЕРА ПЕРЕДАЧИ. БЕЗ просмотра. FAST (!)
     * =
     * Принятие накладной из Трансфера,
     * =
     * из буферной базы передачи накладных
     * -
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionPrihod2_fast()
    {
        $para = Yii::$app->request->queryParams;        //dd($para);
        $sklad = Yii::$app->params['sklad'] = $para['otbor'];
        $id = $id_before_transfered = $para['id'];    // OID

        $model = Sklad_transfer::findModel($id);

        if ($model->dt_transfered_ok != 0) {
            throw new NotFoundHttpException('Накладная уже передавалась');
        }

        //dd($model);
        //#############
        //        $session = Yii::$app->session;
        //        $sklad = $session->get('sklad_');
        //#############

        $new_doc = new Sklad();     // Новая накладная
        //        $new_doc =$model;  // ПЕРЕГОНКА

        $new_doc->id = (int)Sklad::setNext_max_id();
        $new_doc->wh_home_number = (int)$sklad;


        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)


        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

        $new_doc->wh_debet_top = $model->wh_debet_top;

        $new_doc->wh_debet_name = $model->wh_debet_name;
        $new_doc->wh_debet_element = $model->wh_debet_element;
        $new_doc->wh_debet_element_name = $model->wh_debet_element_name;
        $new_doc->wh_destination = $model->wh_destination;
        $new_doc->wh_destination_name = $model->wh_destination_name;
        $new_doc->wh_destination_element = $model->wh_destination_element;
        $new_doc->wh_destination_element_name = $model->wh_destination_element_name;

        $new_doc->wh_dalee = $model->wh_dalee;
        $new_doc->wh_dalee_element = $model->wh_dalee_element;


        $new_doc->tz_id = $model->tz_id;
        $new_doc->tz_name = $model->tz_name;
        $new_doc->tz_date = $model->tz_date;

        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

        $new_doc->user_id = (int)Yii::$app->getUser()->identity->id;
        $new_doc->user_name = Yii::$app->getUser()->identity->username;

        $new_doc->dt_create_timestamp = $model->dt_create_timestamp + 3;
        $new_doc->dt_create = date('d.m.Y H:i:s', $model->dt_create_timestamp + 3);
        $new_doc->dt_update = date('d.m.Y H:i:s', strtotime('now' . ' +3 seconds'));

        $new_doc->array_tk_amort = $model->array_tk_amort;
        $new_doc->array_tk = $model->array_tk;
        $new_doc->array_casual = $model->array_casual;
        $new_doc->array_bus = $model->array_bus;

        ///
        $x1 = $this->Count_rows($new_doc->array_tk_amort);
        $x2 = $this->Count_rows($new_doc->array_tk);
        $x3 = $this->Count_rows($new_doc->array_casual);
        $new_doc->array_count_all = $x1 + $x2 + $x3;


        //ddd($new_doc);

        if ($new_doc->save(true)) {
            // ОТметка о получении НАКЛАДНОЙ
            Sklad_transfer::setTransfer_delivered($id, Sklad_transfer::TRANSFERED_OK);

            return $this->redirect('/sklad/in?otbor=' . $sklad);
        } else {
            $new_doc->errors;
        }


        return $this->redirect('/sklad/in');
    }


    /**
     * @param        $id
     * @param string $adres_to_return
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public function actionDelete($id, $adres_to_return = "")
    {
        $this->findModel($id)->delete();

        return $this->redirect([
            '/sklad/' . $adres_to_return]);

    }


    /**
     * @param        $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public function actionMove_to_delete($id)
    {

//        ddd($_SESSION);


        $model_old = $this->findModel($id);

        //
        // Создаем в Sklad_delete
        //
        $model_new = new Sklad_delete();

        //
        // Копия записи в таблицу Удаленных
        //
        $model_new->attributes = $model_old->attributes;


        if (!$model_new->save(true)) {
            ddd($model_new->errors);
        }


        ///Удаляем из Sklad
        $this->findModel($id)->delete();


        return $this->redirect(
            [
                '/sklad/in?'
                . 'post_filter=1'
            ]
        );

    }

    /**
     * @param $id
     * @return Sklad|array|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Sklad::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Ответ на запрос. Этого нет в складе');

    }

    //		/**
    //		 * Лист Используется в Основной таблице без Амортизации
    //		 * Справочник элементов прямого списания
    //		 *
    //		 * @param $id
    //		 *
    //		 * @return string
    //		 */
    //		public function actionList( $id = 0 ) {
    //
    //			$model =
    //				Html::dropDownList(
    //					'name_id',
    //					0,
    //					ArrayHelper::map(
    //						post_spr_glob_element::find()
    //						                     ->where( [ 'parent_id' => (integer) $id ] )
    //						                     ->orderBy( "name" )
    //						                     ->all(), 'id', 'name' ),
    //					[ 'prompt' => 'Выбор ...' ]
    //				);
    //
    //			return $model;
    //		}
    //		/**
    //		 * ЛистАморт Используется в таблице Амортизации
    //		 * Справочник списания по амортизации
    //		 *
    //		 * @param $id
    //		 *
    //		 * @return string
    //		 */
    //		public function actionListamort( $id = 0 ) {
    //			$model =
    //				Html::dropDownList(
    //					'name_id_amort',
    //					0,
    //					ArrayHelper::map(
    //						post_spr_globam_element::find()
    //						                       ->where( [ 'parent_id' => (integer) $id ] )
    //						                       ->orderBy( "name" )
    //						                       ->all(), 'id', 'name' ),
    //
    //					[ 'prompt' => 'Выбор ...' ]
    //				);
    //
    //			return $model;
    //		}
    //		/**
    //		 * ЛистАморт Используется
    //		 * Справочник Штуки, Метры, Литры
    //		 *
    //		 * @param $id
    //		 *
    //		 * @return string
    //		 *
    //		 * ТОЛЬКО НЕ!!!! НЕ АСУОП !!!
    //		 */
    //		public function actionList_ed_izm( $id = 0 ) {
    //			$model =
    //				post_spr_glob_element::find()
    //				                     ->where( [ 'id' => (integer) $id ] )
    //				                     ->one();
    //
    //			return $model[ 'ed_izm' ];
    //		}


    /**
     * @return string
     * @throws ExitException
     */
    public function actionMail_to()
    {
        dd('Почтовое отправление в разработке');

        $xx = Yii::$app->request->get();
        $model = Sklad::findModelDouble($xx['id']);
        //        dd($model);

        if ($model->load(Yii::$app->request->post())) {


            dd($model);

            //            [id] => 30
            //            [wh_destination] => 2
            //            [wh_destination_name] => Guidejet TI
            //            [wh_destination_element] => 1925
            //            [wh_destination_element_name] => Склад Прошивки модулей
            //            [tz_id] => 13
            //            [tz_name] => 12311123
            //            [tz_date] => 2019-02-26 06:42:47
            //            [user_id] => 9
            //            [user_name] => asemtai
            //            [dt_update] => 2019-02-27 14:52:49
            //            [array_tk_amort] => Array
            //return $this->redirect(['/rewrite_update?id=' . $id]);
            return $this->redirect([
                '/rewrite_update']);
        }


        //        return $this->render('mail_to 1111111/_form', [
        //            'model' => $model,
        //            //'new_doc' => $new_doc,
        //            //'sklad' => $sklad,
        //        ]);
        //        return $this->redirect(['mailer?id'.$id]);
        return true;

    }


    /**
     * @param $id
     * @return string|Response
     */
    public function actionMailer($id)
    {
        dd($id);


        $model = new MailerForm();
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {
            Yii::$app->session->setFlash('mailerFormSubmitted');

            return $this->refresh();
        }

        return $this->render(
            'mailer', [
                'model' => $model,
            ]
        );

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
    public function actionHtml_pdf_green() {
      $para  = Yii::$app->request->queryParams;
      $model = Sklad::findModelDouble( $para[ 'id' ] );

      ////////////////////
      ///// AMORT!!
      $model1 = ArrayHelper::map(
        Spr_globam::find()
                  ->all(), 'id', 'name' );

      $model2 = ArrayHelper::map(
        Spr_globam_element::find()
                          ->orderBy( 'id' )
                          ->all(), 'id', 'name' );


      ///// NOT AMORT
      $model3 = ArrayHelper::map(
        Spr_glob::find()
                ->all(), 'id', 'name' );

      $model4 = ArrayHelper::map(
        Spr_glob_element::find()
                        ->orderBy( 'id' )
                        ->all(), 'id', 'name' );


      $model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );

      ////////////////////

      ///// BAR-CODE
      $str_pos       = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
      $bar_code_html = MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
      ///// BAR-CODE

      //1
      $html_css = $this->getView()->render( '/sklad/html_to_pdf/_form_css.php' );

      //2
      $html = $this->getView()->render(
        '/sklad/html_to_pdf/_form_green', [
        //            'bar_code_html' => $bar_code_html,
        'model'  => $model,
        'model1' => $model1,
        'model2' => $model2,
        'model3' => $model3,
        'model4' => $model4,
        'model5' => $model5,
      ] );


      //        dd($model);

      // Тут можно подсмореть
      //         $html = ss($html);

      ///
      ///  mPDF()
      ///

      $mpdf             = new mPDF();
      $mpdf->charset_in = 'utf-8';


      $mpdf->SetAuthor( 'Guidejet TI, 2019' );
      $mpdf->SetHeader( $bar_code_html );
      $mpdf->WriteHTML( $html_css, 1 );

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
      $mpdf->SetHTMLFooter( $foot_str, 'O' );


      ///////
      $mpdf->AddPage(
        '', '', '', '', '',
        10, 10, 25, 42, '', 25, '', '', '',
        '', '', '', '', '', '', '' );

      //////////


      $mpdf->WriteHTML( $html, 2 );
      $html = '';

      unset( $html );

      $filename = 'Sk ' . date( 'd.m.Y H-i-s' ) . '.pdf';
      $mpdf->Output( $filename, 'I' );


      return false;
    }



    /**
     * ПРЕДВАРИТЕЛЬНЫЙ ПРОСМТР СТРАНИЦЫ НАКЛАДНОЙ
     * ПЕРЕД ВЫВОДОМ в PDF
     * (BARCODE)
     *
     * @return string
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_pdf_green_barcode()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(
            post_spr_globam::find()
                ->all(), 'id', 'name'
        );

        $model2 = ArrayHelper::map(
            post_spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name'
        );


        ///// NOT AMORT
        $model3 = ArrayHelper::map(
            Spr_glob::find()
                ->all(), 'id', 'name'
        );

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name'
        );


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
            '/sklad/html_to_pdf/_form_green_barcode', [
                //            'bar_code_html' => $bar_code_html,
                'model' => $model,
                'model1' => $model1,
                'model2' => $model2,
                'model3' => $model3,
                'model4' => $model4,
                'model5' => $model5,
            ]
        );


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
            '', '', '', '', '', '', ''
        );

        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'Tz ' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * Asemtai
     *
     * @return string
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionProshivka_to_pdf()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(
            Spr_globam::find()
                ->all(), 'id', 'name'
        );

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name'
        );


        ///// NOT AMORT
        $model3 = ArrayHelper::map(
            Spr_glob::find()
                ->all(), 'id', 'name'
        );

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name'
        );


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////
        //1
        $html_css = $this->getView()->render('/sklad/proshivka_to_pdf/_form_css.php');

        //        dd($model);
        //2
        $html = $this->getView()->render(
            '/sklad/proshivka_to_pdf/_form_asemtai', [
                'model' => $model,
                'model1' => $model1,
                'model2' => $model2,
                'model3' => $model3,
                'model4' => $model4,
                'model5' => $model5,
            ]
        );


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
            0, 0, 0,
            0, 0,
            10, 10, 10, 20
        );
        //$html = '';
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        //       dd($model) ;
        $html .= MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'Tz ' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * Накладная Внутреннее Перемещение Виктор
     *
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_pdf_inner()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(
            Spr_globam::find()
                ->all(), 'id', 'name'
        );

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name'
        );


        ///// NOT AMORT
        $model3 = ArrayHelper::map(
            Spr_glob::find()
                ->all(), 'id', 'name'
        );

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name'
        );


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////
        //1
        $html_css = $this->getView()->render('/sklad/html_to_pdf/_form_css.php');

        //2
        $html = $this->getView()->render(
            '/sklad/html_to_pdf/_form_inner', [
                'model' => $model,
                'model1' => $model1,
                'model2' => $model2,
                'model3' => $model3,
                'model4' => $model4,
                'model5' => $model5,
            ]
        );


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
        $mpdf->AddPage();

        //$html = '';
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        //       dd($model) ;
        $html .= MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'Tz ' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionHtml_pdf()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(
            Spr_globam::find()
                ->all(), 'id', 'name'
        );

        $model2 = ArrayHelper::map(
            Spr_globam_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name'
        );


        ///// NOT AMORT
        $model3 = ArrayHelper::map(
            Spr_glob::find()
                ->all(), 'id', 'name'
        );

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name'
        );


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////
        //1
        $html_css = $this->getView()->render('/sklad/html_to_pdf/_form_css.php');

        //2
        $html = $this->getView()->render(
            '/sklad/html_to_pdf/_form', [
                'model' => $model,
                'model1' => $model1,
                'model2' => $model2,
                'model3' => $model3,
                'model4' => $model4,
                'model5' => $model5,
            ]
        );


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
        $mpdf->AddPage();

        //$html = '';
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        //       dd($model) ;
        $html .= MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'Sk_' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * */
    public function actionHtmlAsemtai()
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
                ->all(), 'id', 'name'
        );


        ///// NOT AMORT
        //        $model3 = ArrayHelper::map(Spr_glob::find()
        //            ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(
            Spr_glob_element::find()
                ->orderBy('id')
                ->all(), 'id', 'name'
        );


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
            ]
        );


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
            '', '', '', '', '', '', ''
        );

        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'DeMontage_' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;

    }


    /**
     * Простая
     *
     * @throws UnauthorizedHttpException
     */
    public function actionTo_pdf()
    {
        throw new UnauthorizedHttpException(' Форма удалена ');

    }


    /**
     * @param int $sklad_id
     * @throws MpdfException
     * @throws BarcodeException
     */
    public function actionPdfreport($sklad_id = 11)
    {

        ////////////// PDF - Barcode
        $html = '';
        $html .= MyHelpers::Barcode_HTML("sklad " . $sklad_id);
        ////////////// PDF - Barcode


        $html_css = '';
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
        $mpdf->WriteHTML($html_css, 1);

        $html .= '<h3>Техническое задание №' . $sklad_id . '</h3>';

        //        $para = [];
        //        $para = Yii::$app->request->queryParams;
        //        [start] => 2018-11-27
        //        [end] => 2018-11-29
        //d33($para);
        //        $model = Sklad::find()
        //            ->andFilterWhere(['>=', 'dt_deadline', $para['start']])
        //            ->andFilterWhere(['<', 'dt_deadline', $para['end']])
        //            ->asArray()->orderBy('id DESC')->all();
        //        }
        //        dd($model);
        //////// BUS АвтоБУсы в PDF
        //        $html .= $this->action_bus_report_pdf($sklad_id); ///BUS АвтоБУсы в PDF


        $mpdf->WriteHTML($html, 2);
        $mpdf->AddPage();

        //////////////////////
        $mpdf->Output('mpdf.pdf', 'I');

    }


    /**
     * По вызову Аякс находит
     * ПОДЧИНЕННЫЕ СКЛАДЫ
     *
     * @param int $id
     * @return string
     */
    public function actionList_element($id = 0)
    {
        $model = Html::dropDownList(
            'name_id', 0,
            ArrayHelper::map(
                Sprwhelement::find()
                    ->where([
                        'parent_id' => (integer)$id])
                    ->orderBy('name')
                    ->all(), 'id', 'name'
            ),
            [
                'prompt' => 'Выбор ...']
        );

        if (empty($model)) {
            return "Запрос вернул пустой массив";
        }

        return $model;

    }


    /**
     * По вызову Аякс находит
     * ПОДЧИНЕННЫЕ СКЛАДЫ
     *
     * @param int $id
     * @return string
     */
    public function actionList_element_bort($id = 0)
    {

        $array_three = Sprwhelement::find()
            ->select(['id', 'name', 'f_first_bort'])
            ->where(['parent_id' => (int)$id])
            ->orderBy('name')
            ->asArray()
            ->all();

      //  ddd($array_three);

        $array_rez = [];
        foreach ($array_three as $item) {

            if (!empty($item['f_first_bort']) && (int)$item['f_first_bort'] === 1) {
                $array_rez[$item['id']] = $item['name'] . ' (борт) ';
            }
             else {
                $array_rez[$item['id']] = $item['name'];
            }
        }

        $model = Html::dropDownList(
            'name_id', 0, $array_rez,
            [
                'prompt' => 'Выбор ...']
        );

        if (empty($model)) {
            return "Запрос вернул пустой массив";
        }

        return $model;
    }


    /**
     *  Подтяивает из таблицы  Element
     *  логическое поле ДА-НЕТ
     *  INTELLIGENT ( Штрихкод, интелектуально устройство )
     *
     * @param $id
     * @return mixed`
     */
    public function actionListamort_logic($id)
    {
        $model = Spr_globam_element::find()
            ->asArray()
            ->where([
                'id' => (integer)$id])
            ->one();

        //dd($model['intelligent']);
        return $model['intelligent'];    /// 1 - 0

    }


    /**
     * Select2
     *=
     * @param $parent_id
     * @return string
     */
    public static function actionListamort_select($parent_id)
    {
        return Html::dropDownList(
            'name_id',
            0,
            ArrayHelper::map(Spr_globam_element::find()
                ->where(['parent_id' => (int)$parent_id])
                ->all(), 'id', 'name'),
            ['prompt' => 'Выбор ...']
        );
    }


    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
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
     * Принятие/создание НОВОЙ накладной по ТЕХ Заданию
     *
     * @param int $tz_id - на входе
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     */

//    /**
//     * СИНЯЯ КНОПКА ВНУТРИ НАКЛАДНОЙ
//     * Создаем МНОГО НОВЫХ НАКЛАДНЫХ-АКТОВ
//     * (Монтажа)
//     * По колитчествву Автобусов (ПЕ)
//     *
//     * @param $tz_id
//     * @return Response
//     * @throws Exception
//     * @throws NotFoundHttpException
//     * @throws UnauthorizedHttpException
//     */
//    public function actionTzToManyNewActsMontage()
//    {
//        $para = Yii::$app->request->get();
//        ddd( $para );
//
//        $load_model = new Sklad();     // Новая накладная
//
//        //ddd( $load_model );
//
//
//        //if ( $load_model->load( Yii::$app->request->post() ) ) {
//        if ( $load_model->load( Yii::$app->request->post() ) ) {
//
//            ddd( $load_model );
//
//
//            $para = Yii::$app->request->get();
//
//            ddd( $para );
//
//            //Читаем Т.З.
//            $model_tz = Tz::findModelDouble( $para[ 'tz_id' ] );
//            // TZ
//
//            //ddd($model_tz);
//
//
//            $session = Yii::$app->session;
//            $sklad = $session->get( 'sklad_' ); //64
//            //
//
//
//            // Хозяин МОЕГО склада
//            $array_full = Sprwhelement::findFullArray( $sklad );
//            //dd($array_full);
//
//            //            ddd( $array_full );
//            //            ddd( $load_model[ 'array_bus' ] );
//            //            ddd( $load_model );
//
//            //        ddd($model_tz);
//
//
//            // ЦИКЛ
//            if ( ! isset( $load_model[ 'array_bus' ] ) || empty( $load_model[ 'array_bus' ] ) ) {
//                //$x_casual = 0;
//                throw new NotFoundHttpException( 'load_model[array_bus] Список Автобусов. Нет данных' );
//            } else{
//                foreach ( $model_tz[ 'array_bus' ] as $key ) {
//
//                    //
//                    // РАСШИФРОВКА для Инженера
//                    //
//                    $array_full_xx2 = Sprwhelement::findFullArray( $load_model->wh_destination_element );
//
//                    //
//                    // РАСШИФРОВКА для  АВТОБУСа
//                    //
//                    $array_full_bus = Sprwhelement::findFullArray( $key );
//
//
//                    //Это номер группы складаов
//                    //''wh_cred_top
//
//                    // Это номер склада
//                    //4425 ddd($key);
//
//                    //            dd($key); // $key=177   //$item=5001
//
//
//                    $new_model = new Sklad();     // Новая накладная
//
//                    $new_model->id = (int)Sklad::setNext_max_id();
//
//                    $new_model->wh_home_number = (int)$sklad;
//                    $new_model->tz_id = $model_tz[ 'id' ];
//
//                    $new_model->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;            // РАСХОД
//                    $new_model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;   // РАСХОД
//
//
//                    $new_model->wh_debet_top = $array_full[ 'top' ][ 'id' ];
//                    $new_model->wh_debet_name = $array_full[ 'top' ][ 'name' ];
//                    $new_model->wh_debet_element = $array_full[ 'child' ][ 'id' ];
//                    $new_model->wh_debet_element_name = $array_full[ 'child' ][ 'name' ];
//
//                    $new_model->wh_destination = $array_full_xx2[ 'top' ][ 'id' ];
//                    $new_model->wh_destination_name = $array_full_xx2[ 'top' ][ 'name' ];
//                    $new_model->wh_destination_element = $array_full_xx2[ 'child' ][ 'id' ];
//                    $new_model->wh_destination_element_name = $array_full_xx2[ 'child' ][ 'name' ];
//
//
//                    /// DaLEE
//                    $new_model->wh_dalee = $array_full_bus[ 'top' ][ 'id' ];
//                    $new_model->wh_dalee_element = $array_full_bus[ 'child' ][ 'id' ];
//
//
//                    $new_model->user_id = (int)Yii::$app->getUser()->identity->id;
//                    $new_model->user_name = Yii::$app->getUser()->identity->username;
//                    $new_model->dt_create = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
//                    $new_model->dt_update = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
//                    /// То самое преобразование ПОЛЯ Милисукунд
//                    //$new_model->setDtCreateText( "NOW" );
//
//
//                    $new_model->array_tk = $model_tz[ 'array_tk' ];
//                    $new_model->array_bus = $model_tz[ 'array_bus' ];
//
//                    //$new_model->array_tk_amort = $model_tz[ 'array_tk_amort' ];
//                    //ddd( $new_model );
//                    //ddd($model_tz);
//
//                    $new_model->array_tk_amort = Sklad::setAmArrayIntelegentToStrings( $model_tz[ 'array_tk_amort' ] );
//
//                    //$new_model->array_tk_amort = Sklad::setAmArrayIntelegentAll( $model_tz[ 'array_tk_amort' ], count( $new_model->array_bus ) );
//                    //ddd( $new_model );
//
//                    if ( $x_casual == 0 ) {
//                        $new_model->array_casual = $model_tz[ 'array_casual' ];
//                        $x_casual++;
//                    }
//
//
//                    //ddd($new_model);
//
//                    if ( ! $new_model->save( true ) ) {
//                        dd( $new_model->errors );
//                    }
//                    //            else
//                    //                dd($new_model);
//
//                    //unset($new_model);
//                }
//            }
//
//
//        }
//
//        return $this->redirect( '/sklad/in' );
//    }
//
//    /**
//     * Прием накладных ИЗ Буфера Обмена
//     * DEmontage
//     * Создаем МНОГО НОВЫХ НАКЛАДНЫХ-АКТОВ
//     * (ДеМонтажа)
//     * По колитчествву Автобусов (ПЕ)
//     *
//     * @return Response
//     * @throws NotFoundHttpException
//     */
//    public function actionTzToManyNewActsDemontage()
//    {
//        $para = Yii::$app->request->get();
//        // $para[tz_id] => 8        dd($para);
//
//        //Читаем Т.З.
//        $model_tz = Tz::findModelDouble( $para[ 'tz_id' ] );
//        // TZ TZ TZ
//
//        $session = Yii::$app->session;
//        $sklad = $session->get( 'sklad_' ); //64
//        //
//
//        // Хозяин МОЕГО склада
//        $array_full = Sprwhelement::findFullArray( $sklad );
//
//        if ( isset( $model_tz[ 'array_bus' ] ) && !empty( $model_tz[ 'array_bus' ] ) ) {
//
//            $x_casual = 0;
//            foreach ( $model_tz[ 'array_bus' ] as $key ) {
//
//                //dd($key); // $key=177   //$item=5001
//
//
//                $new_model = new Sklad();     // Новая накладная
//
//                $new_model->id = (int)Sklad::setNext_max_id();
//                $new_model->wh_home_number = (int)$sklad;
//                $new_model->tz_id = $model_tz[ 'id' ];
//
//                $new_model->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
//                $new_model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)
//
//                // Хозяин АВТОБУСА
//                $array_full_bus = Sprwhelement::findFullArray( $key );
//
//                /// Уход (Демонтаж) из АВТОБУСА и ПАРКА
//                $new_model->wh_debet_top = $array_full_bus[ 'top' ][ 'id' ];
//                $new_model->wh_debet_name = $array_full_bus[ 'top' ][ 'name' ];
//                $new_model->wh_debet_element = $array_full_bus[ 'child' ][ 'id' ];
//                $new_model->wh_debet_element_name = $array_full_bus[ 'child' ][ 'name' ];
//
//                /// Приход СЕБЕ, на свой ЛИЧНЫЙ склад
//                $new_model->wh_destination = $array_full[ 'top' ][ 'id' ];
//                $new_model->wh_destination_name = $array_full[ 'top' ][ 'name' ];
//                $new_model->wh_destination_element = $array_full[ 'child' ][ 'id' ];
//                $new_model->wh_destination_element_name = $array_full[ 'child' ][ 'name' ];
//
//                /// DaLEE
//                $new_model->wh_dalee = $array_full[ 'top' ][ 'id' ];
//                //$new_model->wh_dalee_name = $array_full['top']['name'];
//                $new_model->wh_dalee_element = $array_full[ 'child' ][ 'id' ];
//                //$new_model->wh_dalee_element_name = $array_full['child']['name'];
//
//
//                $new_model->user_id = (int)Yii::$app->getUser()->identity->id;
//                $new_model->user_name = Yii::$app->getUser()->identity->username;
//                //            $new_model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
//                //            $new_model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
//                $new_model->dt_create = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
//                $new_model->dt_update = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
//                /// То самое преобразование ПОЛЯ Милисукунд
//                //$new_model->setDtCreateText( "NOW" );
//
//
//                $new_model->array_tk = $model_tz[ 'array_tk' ];
//                $new_model->array_tk_amort = $model_tz[ 'array_tk_amort' ];
//                $new_model->array_bus = $model_tz[ 'array_bus' ];
//
//                if ( $x_casual == 0 ) {
//                    $new_model->array_casual = $model_tz[ 'array_casual' ];
//                    $x_casual++;
//                }
//
//                //                ddd($model_tz);
//
//                //ddd($new_model);
//
//
//                if ( !$new_model->save( true ) ) {
//                    dd( $new_model->errors );
//                }
//
//                unset( $new_model );
//            }
//        } else{
//            throw new NotFoundHttpException( 'Список Автобусов. Нет данных' );
//        }
//
//
//        return $this->redirect( '/sklad/in' );
//    }
//


    public function actionCreatefromtz($tz_id)
    {
        $tz_body = Tz::find()
            ->where([
                'id' => (integer)$tz_id])
            ->one();

        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');

        if (!isset($sklad) || empty($sklad)) {
            throw new UnauthorizedHttpException('Createfromtz. Sklad=0');
        }

        //ddd($sklad);


        $new_doc = new Sklad();     // Новая накладная
        $new_doc->id = (int)Sklad::setNext_max_id();
        $new_doc->wh_home_number = (int)$sklad;

        $new_doc->tz_id = (int)$tz_body['id'];

        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;

        $new_doc->array_tk_amort = $tz_body->array_tk_amort;
        $new_doc->array_tk = $tz_body->array_tk;
        $new_doc->array_bus = $tz_body->array_bus;
        $new_doc->array_casual = $tz_body->array_casual;


        // Полное звание этого Элемента
        $full_sklad = Sprwhelement::findFullArray($sklad);

        /// ИСТОЧНИК
        $new_doc->wh_debet_top = (int)$full_sklad["top"]['id'];
        $new_doc->wh_debet_element = (int)$sklad;
        /// ПРИЕМНИК
        //        $new_doc->wh_destination = (int)$full_sklad["top"]['id'];
        //        $new_doc->wh_destination_element = (int)$sklad;
        /// Автобусы ЕСТЬ?
        if (isset($new_doc['array_bus']) && !empty($new_doc['array_bus'])) {
            $items_auto = Sprwhelement::findAll_Attrib_PE(
                array_map('intval', $new_doc['array_bus'])
            );
        } else {
            $items_auto = [
                'нет автобусов'];
        }


        /// Получаем ТехЗадание. ШАПКА
        if (isset($tz_body->id)) {
            $tz_head = Tz::findModelDoubleAsArray($tz_body->id);
        } else {
            throw new Exception('Получаем ТехЗадание');
        }


        //dd( $tz_head );
        //dd($items_auto);
        //        ddd($new_doc);


        ///
        /// LOAD
        ///
        if ($new_doc->load(Yii::$app->request->post())) {

            $para = Yii::$app->request->post();

            //// Montage / DeMontage !!!!!!!!!!
            if (isset($para['contact-button']) && !empty($para['contact-button'])) {

                //// Montage !!!!!!!!!!
                if ($para['contact-button'] == 'create_montage') {
                    if ($this->actionMany_montage($new_doc)) {
                        //echo 'OK';
                        return $this->redirect('/sklad/in');
                    }
                }


                //// DeMontage !!!!!!!!!!
                if ($para['contact-button'] == 'create_demontage') {
                    if ($this->actionMany_demontage($new_doc)) {
                        //echo 'OK';
                        return $this->redirect('/sklad/in');
                    }
                }
            }


            $new_doc->user_id = Yii::$app->user->identity->id;
            $new_doc->user_name = Yii::$app->user->identity->username;
            $new_doc->user_group_id = Yii::$app->user->identity->group_id;

            $new_doc->tz_id = (int)$tz_body->id;
            $new_doc->tz_name = $tz_body->name_tz;
            $new_doc->tz_date = $tz_body->dt_create;

            $new_doc->dt_create = date('d.m.Y H:i:s', strtotime('now'));
            /// То самое преобразование ПОЛЯ Милисукунд
            //$new_doc->setDtCreateText( "NOW" );
            $new_doc->dt_deadline = $tz_body->dt_deadline;


            $new_doc->user_id = (int)Yii::$app->getUser()->identity->id;
            $new_doc->user_name = Yii::$app->getUser()->identity->username;

            $new_doc->wh_debet_top = (int)$new_doc->wh_debet_top;
            $new_doc->wh_debet_element = (int)$new_doc->wh_debet_element;
            $new_doc->wh_destination = (int)$new_doc->wh_destination;
            $new_doc->wh_destination_element = (int)$new_doc->wh_destination_element;

            $new_doc->wh_dalee = (int)$new_doc->wh_dalee;
            $new_doc->wh_dalee_element = (int)$new_doc->wh_dalee_element;

            $new_doc->tx = 'Накладная создана по запросу ТехЗадания';

//            ddd($tz_body);
//            ddd($new_doc);
            //            ddd( $new_doc->array_bus );
            //            ddd(count( $new_doc->array_bus ));
            //
            //   * Приводим только первую табицу (АМОРТИЗАЦИЯ/АСУОП)
            //   * к виду : Каждая запись(интелегент) записана в своей, новой строке
            // При этом умножено на количество АВТОБУСОВ (ПЕ)
            //


            $new_doc->array_tk_amort = Sklad::setAmArrayIntelegentAll($new_doc->array_tk_amort, count($new_doc->array_bus));
            $new_doc->array_tk = Sklad::setAmArrayIntelegentAll($new_doc->array_tk, count($new_doc->array_bus));


            //ddd($new_doc);


            if ($new_doc->save(true)) {
                return $this->redirect('/sklad/in');
            }
        }


        return $this->render(
            '_form_sklad', [
                'model' => $tz_body,
                'new_doc' => $new_doc,
                'sklad' => $sklad,
                'items_auto' => $items_auto,
                'tz_head' => $tz_head,
            ]
        );

    }


    /**
     * Прием накладных ИЗ Буфера Обмена
     * DEmontage
     * Создаем МНОГО НОВЫХ НАКЛАДНЫХ-АКТОВ
     * (ДеМонтажа)
     * По колитчествву Автобусов (ПЕ)
     *
     * @param $model
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionMany_demontage($model)
    {
//        ddd($model);
        //Читаем Т.З.
        $model_tz = Tz::findModelDouble($model['tz_id']);
        // TZ TZ TZ

        $session = Yii::$app->session;
        $sklad = $session->get('sklad_'); //64
        //
        // Хозяин МОЕГО склада
        $array_full = Sprwhelement::findFullArray($sklad);


        if (isset($model['array_bus']) && !empty($model['array_bus'])) {

            $x_casual = 0;
            foreach ($model['array_bus'] as $key) {

                //dd($key); // $key=177   //$item=5001


                $new_model = new Sklad();     // Новая накладная

                $new_model->id = (int)Sklad::setNext_max_id();
                $new_model->wh_home_number = (int)$sklad;
                $new_model->tz_id = $model['tz_id'];

                $new_model->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
                $new_model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)
                // Хозяин АВТОБУСА
                $array_full_bus = Sprwhelement::findFullArray($key);


                /// Уход (Демонтаж) из АВТОБУСА и ПАРКА
                $new_model->wh_debet_top = $array_full_bus['top']['id'];
                $new_model->wh_debet_name = $array_full_bus['top']['name'];
                $new_model->wh_debet_element = $array_full_bus['child']['id'];
                $new_model->wh_debet_element_name = $array_full_bus['child']['name'];

                /// Приход СЕБЕ, на свой ЛИЧНЫЙ склад
                $new_model->wh_destination = $array_full['top']['id'];
                $new_model->wh_destination_name = $array_full['top']['name'];
                $new_model->wh_destination_element = $array_full['child']['id'];
                $new_model->wh_destination_element_name = $array_full['child']['name'];

                /// DaLEE
                $new_model->wh_dalee = $array_full_bus['top']['id'];
                //$new_model->wh_dalee_name = $array_full['top']['name'];
                $new_model->wh_dalee_element = $array_full_bus['child']['id'];
                //$new_model->wh_dalee_element_name = $array_full['child']['name'];


                $new_model->user_id = (int)Yii::$app->getUser()->identity->id;
                $new_model->user_name = Yii::$app->getUser()->identity->username;
                //            $new_model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
                //            $new_model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
                $new_model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
                $new_model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
                /// То самое преобразование ПОЛЯ Милисукунд
                //$new_model->setDtCreateText( "NOW" );


                $new_model->array_tk = $model_tz['array_tk'];
                $new_model->array_tk_amort = $model_tz['array_tk_amort'];
                $new_model->array_bus = $model_tz['array_bus'];

                if ($x_casual == 0) {
                    $new_model->array_casual = $model_tz['array_casual'];
                    $x_casual++;
                }

                //                ddd($model_tz);
                //ddd($new_model);


                if (!$new_model->save(true)) {
                    dd($new_model->errors);
                }

                unset($new_model);
            }
        } else {
            throw new NotFoundHttpException('Список Автобусов. Нет данных');
        }


        return $this->redirect('/sklad/in');

    }


    /**
     * Оптовое создание новых накладных ИЗ ТехЗадания
     * =
     * actionMany_montage
     *
     * @param $model
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionMany_montage($model)
    {

        $session = Yii::$app->session;
        $sklad = $session->get('sklad_'); //64
        //
        // Хозяин МОЕГО склада (VICTOR)
        $array_full = Sprwhelement::findFullArray($sklad);


        if (isset($model['array_bus']) && !empty($model['array_bus'])) {

            $x_casual = 0;
            foreach ($model['array_bus'] as $key) {

                //dd($key); // $key=177   //$item=5001


                $new_model = new Sklad();     // Новая накладная

                $new_model->id = (int)Sklad::setNext_max_id();
                $new_model->wh_home_number = (int)$sklad;
                $new_model->tz_id = (int)$model['tz_id'];

                $new_model->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;            // ПРИХОД (приходная накладная)
                $new_model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;   // ПРИХОД (приходная накладная)
                // Хозяин АВТОБУСА
                $array_full_bus = Sprwhelement::findFullArray($key);

                ///// Уход из нашего СКЛАДА
                $new_model->wh_debet_top = (int)$array_full['top']['id'];
                $new_model->wh_debet_name = $array_full['top']['name'];
                $new_model->wh_debet_element = (int)$array_full['child']['id'];
                $new_model->wh_debet_element_name = $array_full['child']['name'];


                // Склад-получатель
                $array_full_destination = Sprwhelement::findFullArray($model['wh_destination_element']);

                /// Приход В АВТОБУС
                $new_model->wh_destination = (int)$array_full_destination['top']['id'];
                $new_model->wh_destination_name = $array_full_destination['top']['name'];
                $new_model->wh_destination_element = (int)$array_full_destination['child']['id'];
                $new_model->wh_destination_element_name = $array_full_destination['child']['name'];

                /// DaLEE в АВТОБУС
                $new_model->wh_dalee = (int)$array_full_bus['top']['id'];
                $new_model->wh_dalee_element = (int)$array_full_bus['child']['id'];


                $new_model->user_id = (int)Yii::$app->getUser()->identity->id;
                $new_model->user_name = Yii::$app->getUser()->identity->username;
                //            $new_model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
                //            $new_model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
                $new_model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
                $new_model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
                /// То самое преобразование ПОЛЯ Милисукунд
                //$new_model->setDtCreateText( "NOW" );


                $new_model->array_tk = $model['array_tk'];
                $new_model->array_tk_amort = $model['array_tk_amort'];
                $new_model->array_bus = $model['array_bus'];


                if ($x_casual == 0) {
                    $new_model->array_casual = $model['array_casual'];
                    $x_casual++;
                }

                //                ddd($model_tz);
                //$new_model->dt_create_timestamp = strtotime($model->dt_create);
                $new_model->dt_create_timestamp = strtotime('NOW');

                //ddd( $new_model );


                if (!$new_model->save(true)) {
                    return false;
                }
            }
        } else {
            throw new NotFoundHttpException('Список Автобусов. Нет данных');
        }


        return true;

    }


    /**
     * Вход только по номеру склада и номеру накладной
     * -
     *
     * @return string
     * @throws HttpException
     */
    public function actionUpdate_id()
    {
        //        $para = Yii::$app->request->queryParams;
        //        $otbor = Yii::$app->request->get('otbor');

        $id = Yii::$app->request->get('id');
        $el = Yii::$app->request->get('el');
        $sklad = Sklad::getSkladIdActive();

        ///
        $model = Sklad::find()
            ->where(['id' => (int)$id])
            ->one();

        /// Элемент для выделения крассным цветом
        if (isset($el)) {
            $model->tk_element = $el;
        }

        /////////////////
        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am(isset($model['array_tk_amort']) ? $model['array_tk_amort'] : []);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk'] = $this->getTkNames((isset($model['array_tk']) ? $model['array_tk'] : []));
        //ddd($model);


        return $this->render(
            '_form_read_only', [
                'new_doc' => $model,
                'sklad' => $sklad,
                'items_auto' => [],
                'tz_head' => [],
                'erase_array' => [],
                'alert_mess' => '',
            ]
        );

    }


    /**
     * Во всех(!) накладных установить Флаг=1
     * -
     * Mongo. Монго. Флаг прописан по всей коллекции Склад одной командой
     *
     * @return mixed
     * @throws ExitException
     */
    public function actionSklad_flag_up()
    {
        if (MyHelpers::Mongo_sklad_flag_on()) {
            throw new ExitException('Mongo.  Флаг прописан по всей коллекции Склад одной командой');
        } else {
            throw new ExitException('Mongo.  Флаг прописка НЕ ПОШЛА!');
        }

    }


    /**
     */
    public function actionSklad_flag_up_oldver()
    {
        if (MyHelpers::Mongo_sklad_flag_on_oldver()) {
            throw new ExitException('Mongo.  Флаг прописан по всей коллекции Склад одной командой');
        } else {
            throw new ExitException('Mongo.  Флаг прописка НЕ ПОШЛА!');
        }

    }


    /**
     * *
     * @return bool
     */
    public function actionSklad_exist_false()
    {
        return MyHelpers::Mongo_exist_false();

    }

    /**
     * *
     * @return void
     * @throws ExitException
     */
    public function actionSklad_flag_down()
    {
        if (MyHelpers::Mongo_sklad_flag_off()) {
            throw new ExitException('Mongo. Флаг удален');
        } else {
            throw new ExitException('Mongo.  Флаг НЕ Сработал!');
        }
    }


    /**
     * Ремонтируем.
     * Во всех(!) накладных исправляем TIMESTAMP. Генерим его из DT_create
     * -
     *
     * @return mixed
     * @throws ExitException
     * @throws Exception
     */
    public function actionRemont_timestamp_from_dt_create()
    {
        $all_id_sklad = ArrayHelper::getColumn(
            Sklad::find()
                ->where([
                    '==',
                    'flag',
                    1])
                ->limit(500)
                ->all()
            , 'id'
        );

        //ddd($all_id_sklad);

        foreach ($all_id_sklad as $item_id) {

            $model = Sklad::find()
                ->where(
                    [
                        '==',
                        'id',
                        $item_id]
                )
                ->one();

            if (!isset($model->dt_create)) {
                ddd($model);
            }

            $full_date = date('d.m.Y H:i:s', strtotime($model->dt_create));
            $model->dt_create_timestamp = (int)strtotime($full_date);
            $model->flag = 0;


            if (!$model->save(true)) {
                //ddd($model->errors);
                $err[] = $model->errors;
            }
        }


        $count = Sklad::find()
            ->where([
                '==',
                'flag',
                1])
            ->count();

        //ddd($count);

        if (isset($err)) {
            //ddd($err);
            foreach ($err as $err_item) {
                if (is_array($err_item['array_tk_amort'])) {
                    foreach ($err_item['array_tk_amort'] as $item) {
                        echo "\n<br> $item";
                    }
                } else {
                    echo "\n $item";
                }
            }
        }

        throw new ExitException("\n<br>" . 'Ремонт ПРОШЕЛ УСПЕШНО . Осталось еще = ' . $count);

    }


    /**
     * Ремонтируем. Урезаем лишние поля в СКЛАДЕ
     * -
     *
     * @return mixed
     * @throws ExitException
     * @throws Exception
     */
    public function actionRemont_sklad()
    {
        $all_id_sklad = ArrayHelper::getColumn(
            Sklad::find()
                ->where([
                    '==',
                    'flag',
                    1])
                ->limit(1500)
                ->all()
            , 'id'
        );
        ///ddd($all_id_sklad);

        ///
        foreach ($all_id_sklad as $item_id) {

            $model = Sklad::find()
                ->where(
                    [
                        '==',
                        'id',
                        $item_id]
                )
                ->one();

            if (!isset($model->dt_create)) {
                ddd($model);
            }

            $full_date = date('d.m.Y H:i:s', strtotime($model->dt_create));
            $model->dt_create_timestamp = (int)strtotime($full_date);
            $model->flag = 0;


            unset($model->dt_create);
            unset($model->dt_update);
            unset($model->sklad_vid_oper_name);
            unset($model->wh_debet_name);
            unset($model->wh_debet_element_name);
            unset($model->wh_destination_name);
            unset($model->wh_destination_element_name);
            unset($model->user_ip);
            unset($model->user_id);
            unset($model->update_user_id);
            unset($model->user_group_id);

            $model->sklad_vid_oper = (string)$model->sklad_vid_oper;
            $model->wh_home_number = (int)$model->wh_home_number;
            $model->wh_cs_number = (int)$model->wh_cs_number;

            $model->wh_debet_top = (int)$model->wh_debet_top;
            $model->wh_debet_element = (int)$model->wh_debet_element;
            $model->wh_destination = (int)$model->wh_destination;
            $model->wh_destination_element = (int)$model->wh_destination_element;


            //ddd($model);

            if (!$model->save(true)) {
                //ddd($model->errors);
                $err[] = $model->errors;
            }
        }


        $count = Sklad::find()
            ->where([
                '==',
                'flag',
                1])
            ->count();

        //ddd($count);

        if (isset($err)) {
            //ddd($err);
            foreach ($err as $err_item) {
                if (is_array($err_item['array_tk_amort'])) {
                    foreach ($err_item['array_tk_amort'] as $item) {
                        echo "\n<br> $item";
                    }
                } else {
                    echo "\n $item";
                }
            }
        }

        throw new ExitException("\n<br>" . 'Ремонт ПРОШЕЛ УСПЕШНО . Осталось еще = ' . $count);
    }

//
//    /**
//     *
//     */
//    public function actionCount_nakl()
//    {
//        $xx = Sklad::find()
//            ->count('id');
//
//        echo "Количество накладных: ";
//        ddd($xx);
//    }


    /**
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     * -
     *
     * @param $array_tk
     * @return mixed
     */
    public function getTkNames_am($array_tk)
    {
        $spr_globam_model = ArrayHelper::map(Spr_globam::find()->orderBy('name')->all(), 'id', 'name');
        $spr_globam_element_model = ArrayHelper::map(Spr_globam_element::find()->orderBy('name')->all(), 'id', 'name');

        $spr_globam_element_model_intelligent = ArrayHelper::map(Spr_globam_element::find()->orderBy('name')->all(), 'id', 'intelligent');
        //ddd($spr_globam_element_model_intelligent);


        $buff = [
        ];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $key => $item) {

                $buff[$key]['name_wh_tk_amort'] = (isset($spr_globam_model[$item['wh_tk_amort']]) ? $spr_globam_model[$item['wh_tk_amort']] : 0);
                $buff[$key]['name_wh_tk_element'] = $spr_globam_element_model[$item['wh_tk_element']];
                //$buff[$key]['name_ed_izmer']=$spr_things_model[$item['ed_izmer']];

                $buff[$key]['name_ed_izmer'] = 'шт';
                $buff[$key]['ed_izmer'] = '1';


                $buff[$key]['bar_code'] = ($item['bar_code'] > 0 ? $item['bar_code'] : '');
                $buff[$key]['intelligent'] = ((int)$spr_globam_element_model_intelligent[$item['wh_tk_element']]);

                $buff[$key]['wh_tk_amort'] = $item['wh_tk_amort'];
                $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                $buff[$key]['take_it'] = (isset($item['take_it']) ? $item['take_it'] : '');
                $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];
            }
        }

        return $buff;

    }


    /**
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     * -
     *
     * @param $array_tk
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

                    $buff[$key]['name_tk'] = $spr_glob_model[$item['wh_tk']];
                    $buff[$key]['name_tk_element'] = (isset($spr_glob_element_model[$item['wh_tk_element']]) ? $spr_glob_element_model[$item['wh_tk_element']] : 0);
                    $buff[$key]['name_ed_izmer'] = $spr_things_model[$item['ed_izmer']];


                    $buff[$key]['wh_tk'] = $item['wh_tk'];
                    $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                    $buff[$key]['ed_izmer'] = $item['ed_izmer'];
                    $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];
                    //                    if(isset($item[ 'take_it' ])){
                    //                        $buff[ $key ][ 'take_it' ] = $item[ 'take_it' ];
                    //                    }
                    //$buff[$key]['name']=$item['name'];
                }
            }
        }

        //        ddd($array_tk);
        //        ddd($buff);

        return $buff;

    }

}
