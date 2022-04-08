<?php

namespace frontend\controllers;

use \frontend\models\post_mts_crm;
use \frontend\models\mts_crm;
use frontend\models\Mts_change;
use frontend\models\Mts_demontage;
use frontend\models\Mts_montage;
use frontend\models\Pe_identification;
use frontend\models\post_mts_change;
use frontend\models\post_mts_close;

use frontend\models\post_mts_close_demontage;
use frontend\models\post_pe_identification;
use frontend\models\postsklad_for_mobile;
use frontend\models\postsklad_mts;
use frontend\models\Sklad;
use frontend\models\Sklad_inventory_cs;
use frontend\models\Spr_globam_element;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use frontend\models\Tesseract;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UploadedFile;


class MobileController extends Controller
{
    public $_name_ap, $_name_pe;
    public $_ap, $_pe, $_sklad;

    public $tmpDir;

    public $users_sklad; /// Склады ПОЛЬЗОВАТЕЛЯ (Массив)


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Задать СКЛАД
        if (empty($this->_sklad)) {
            if (is_array(Yii::$app->user->identity->sklad)) {
                $users_sklad = Yii::$app->user->identity->sklad;
                $this->_sklad = (int)Yii::$app->user->identity->sklad[0];
                //ddd($this);
                Sklad::setSkladIdActive($this->_sklad);
            } else {
                $users_sklad[] = Yii::$app->user->identity->sklad;
                $this->_sklad = (int)Yii::$app->user->identity->sklad;
                //ddd($this);
            }
        }


        $session = Yii::$app->session;
        $session->open();

        if (!Yii::$app->getUser()->identity) {
            throw new UnauthorizedHttpException('Необходима авторизация', 1); //Необходима авторизация==1
        }

        ///
        ///
        /// Исключение из аварийного сообщения
        ///
        if (
            $this->module->requestedRoute != 'mobile_inventory/save_edit_inventory' &&
            $this->module->requestedRoute != 'mobile_inventory/edit_inventory' &&
            $this->module->requestedRoute != 'mobile_inventory/big_table' &&
            $this->module->requestedRoute != 'mobile_inventory/move_to_vedomost' &&
            $this->module->requestedRoute != 'mobile_inventory/look_pe' &&
            $this->module->requestedRoute != 'mobile_inventory/view_table' &&
//            $this->module->requestedRoute != 'mobile_inventory/init_table2' &&
            $this->module->requestedRoute != 'mobile_inventory/index' &&
            $this->module->requestedRoute != 'mobile_inventory/init_table' &&
            $this->module->requestedRoute != 'mobile/crop_jq' &&
            $this->module->requestedRoute != 'mobile/crop' &&
            $this->module->requestedRoute != 'mobile/index_tree' &&
            $this->module->requestedRoute != 'mobile/init_ap_pe' &&
            $this->module->requestedRoute != 'mobile/init_ap' &&
            $this->module->requestedRoute != 'mobile/init_pe'
        ) {
            ///
            /// Если мы не в МОБИЛКЕ на первой странице
            /// Задаем Внутренние переменные
            /// .....
            $this->_sklad = Sklad::getSkladIdActive();
            if (!isset($this->_sklad) || empty($this->_sklad)) {
                ddd(1111);

                throw new HttpException(411, 'Выберите склад', 3);
            }

            ///
            ///  Из СЕССИИ  во внутренний объект THIS
            ///
            $this->_ap = Sklad::getApIdActive();
            $this->_pe = Sklad::getPeIdActive();
            if (!isset($this->_pe) || empty($this->_pe) || !isset($this->_ap) || empty($this->_ap)) {
                throw new HttpException(411, 'Выберите ПАРК и АВТОБУС', 4);
            }
            //ddd($this);
        }

        $array_full = Sprwhelement::findFullArray($this->_pe);
        $this->_name_ap = $array_full['top']['name'];
        $this->_name_pe = $array_full['child']['name'];

        //ddd($this);
        return true;

    }


    /**
     * INDEX
     *
     * {@inheritdoc}
     */
    public function actionIndex()
    {


        $sklad = Sklad::getSkladIdActive();


        $array_sklad_list = [];

        if (!isset($sklad) || empty($sklad)) {
            $array_sklad_list = Yii::$app->getUser()->identity->sklad;  // * All SKLAD's

            if (!is_array($array_sklad_list)) {
                if (isset($array_sklad_list) && !empty($array_sklad_list) && Sklad::setSkladIdActive($array_sklad_list)) {
                    $sklad = Sklad::getSkladIdActive();
                }
            } else {
                $array = [];
                foreach ($array_sklad_list as $item) {
                    $array [$item] = $item;
                }
                asort($array);
                $array_sklad_list = $array;
            }
        }

        $user_name = Yii::$app->getUser()->identity->username_for_signature;

        $ap = Sklad::getApIdActive();
        $pe = Sklad::getPeIdActive();

        $array_full = Sprwhelement::findFullArray($pe);

        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];
        $name_cs = $array_full['child']['cs'];

        //ddd($array_full);


        return $this->render('/mobile/index', [
            "user_name" => $user_name,

            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
            'name_cs' => $name_cs,

            'sklad' => $sklad,
            'array_sklad_list' => $array_sklad_list,
        ]);

    }

    /**
     * 1
     * INDEX_tree
     * =
     * {@inheritdoc}
     */
    public function actionIndex_tree()
    {
        //ddd(1111);

        if (!isset($sklad) || empty($sklad)) {
            $sklad = '';
            $array_sklad_list = Yii::$app->getUser()->identity->sklad;  // * All SKLAD's

            if (!is_array($array_sklad_list)) {
                if (isset($array_sklad_list) && !empty($array_sklad_list) && Sklad::setSkladIdActive($array_sklad_list)) {
                    $sklad = Sklad::getSkladIdActive();
                }
            } else {
                //ddd($array_sklad_list);
                $array = [];
                foreach ($array_sklad_list as $item) {
                    $array [$item] = $item;
                }
                asort($array);
                $array_sklad_list = $array;
            }
        }

//        ddd($sklad);


        ///
        $ap = Sklad::getApIdActive();
        $pe = Sklad::getPeIdActive();


        $user_name = Yii::$app->getUser()->identity->username_for_signature;

        $array_full = Sprwhelement::findFullArray($pe);

        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];
        $name_cs = $array_full['child']['cs'];


        ///
        ///
        ///
        ///
        $para = Yii::$app->request->queryParams;

        $searchModel = new post_mts_crm();
        $dataProvider_open = $searchModel->search_open($para);
        $dataProvider_close = $searchModel->search_close($para);

//        ddd($dataProvider_close->getModels());

//        ddd($dataProvider_open->getModels());

        return $this->render('/mobile/index_tree', [
            "user_name" => $user_name,

            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
            'name_cs' => $name_cs,

            'sklad' => $sklad,
            'array_sklad_list' => $array_sklad_list,

            "searchModel" => $searchModel,
            "dataProvider_open" => $dataProvider_open,
            "dataProvider_close" => $dataProvider_close,

        ]);

    }


    /**
     * 2
     * =
     * INDEX_ident_PE
     * =
     * {@inheritdoc}
     */
    public function actionIndex_ident_pe()
    {
        // ddd(1111);
        ///
        $ap = Sklad::getApIdActive(); /// Ap
        $pe = Sklad::getPeIdActive(); /// Pe
        ///
        ///
        if (!isset($ap) || !isset($pe)) {
            return $this->redirect('/mobile/index_tree');
        }
        ///
        if (empty($ap) || empty($pe)) {
            return $this->redirect('/mobile/index_tree');
        }


        ///
        $array_full = Sprwhelement::findFullArray($pe);
        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];
        $name_cs = $array_full['child']['cs'];

        // * Array PE
        $array_pe = Sprwhelement::array_Model_Pe($pe);
        //ddd($array_pe);


        //PE
        $model = new Pe_identification();
        $model->id_ap = (int)$array_pe['parent_id'];//'Автопарк'
        $model->id_pe = (int)$array_pe['id'];//ПE
        $model->bort = $array_pe['nomer_borta'];
        $model->gos = $array_pe['nomer_gos_registr'];
        $model->vin = $array_pe['nomer_vin'];

        $model->dt_create = Date('d.m.Y H:i:s', strtotime('now'));
        $model->dt_create_timestamp = strtotime('now');
        $model->mts_id = Yii::$app->getUser()->identity->id; //  МТС. Мобильный Сотрудник


        //ddd($model);

        ///
        /// * Виртуальные остатки по ЦС
        $array_summary_from_cs = $this->Summary_from_cs($pe);
        //ddd($array_summary_from_cs);

        $array_menu = [];
        foreach ($array_summary_from_cs as $item) {
            if ($item['itog'] > 0 && !empty($item['bar_code'])) {
                $array_menu[$item['wh_tk_amort']][$item['wh_tk_element']]['bar_code'] = $item['bar_code'];

                ///* Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его ИД)
                $fullArray = Spr_globam_element::findFullArray_BY_barcode($item['bar_code']);

                $array_menu[$item['wh_tk_amort']][$item['wh_tk_element']]['name'] = $fullArray['child']['name'];
                $array_menu[$item['wh_tk_amort']][$item['wh_tk_element']]['short_name'] = $fullArray['child']['short_name'];
            }
        }
        //ddd($array_menu);


//        ddd(111);

        ///
        /// LOAD
        ///
        if ($model->load(Yii::$app->request->post())) {
            //ddd($model);

            // Печать
            //  'contact-button' => 'print_mtp210'
            $para_print = Yii::$app->request->post('contact-button');
            if ($para_print == 'print_mtp210') {
                $model->scenario = Pe_identification::SCENARIO_PRINT_MTP210;


//                ddd($model);
//                ddd($para_print);

                //   'id_ap' => 14
                //        'id_pe' => 187
                //        'bort' => '5011'
                //        'gos' => '993DR02'
                //        'vin' => ''
                //        'dt_create' => '21.12.2020 15:51:25'
                //        'dt_create_timestamp' => 1608544285
                //        'mts_id' => 1


                ///
//                return $this->render('print/print_excel', [
//                    "model" => $model,
//                ]);


            }


            //ddd(111);

            //$model = new UploadForm();
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            //  $model->aray_photo = UploadedFile::getInstances($model, 'aray_photo');
            /// UPLOAD
            ///
            if (!$path_hash = $model->upload()) {
                return $this->render('/mobile/errors', ['err' => "Не загрузил фото"]);
            }

            $model->path_hash = $path_hash;

            /// SAVE  Model
            /// PHOTO GOS
            if (isset($_FILES['Pe_identification']['name']['imageFiles'][1])) {
                $model->aray_photo_gos = $_FILES['Pe_identification']['name']['imageFiles'][1];
            }
            /// PHOTO BORT
            if (isset($_FILES['Pe_identification']['name']['imageFiles'][2])) {
                $model->aray_photo_bort = $_FILES['Pe_identification']['name']['imageFiles'][2];
            }

            /// PHOTO NEXT All
            if (isset($_FILES['Pe_identification']['name']['imageFiles'][7])) {
                $model->aray_photo = $_FILES['Pe_identification']['name']['imageFiles'][7]; // ASUOP
            }


//            unset($model->imageFiles);

            if (!$model->save(true)) {
                ddd($model->errors);
            }

//            ddd($model);
            return $this->redirect('/mobile/index');
        }


        return $this->render('/mobile/index_ident_pe', [
            'model' => $model,
            'array_menu' => $array_menu,

            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
            'name_cs' => $name_cs,

        ]);

    }

    /**
     * Gallery
     * Галерея ФОТОК для одного автобуса
     * =
     * {@inheritdoc}
     */
    public function actionGallery()
    {
        ///
        $ap = Sklad::getApIdActive(); /// Ap
        $pe = Sklad::getPeIdActive(); /// Pe
        if (!isset($ap) || !isset($pe)) {
            return $this->redirect('/mobile/index');
        }

        ///
        $array_full = Sprwhelement::findFullArray($pe);
        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];
        $name_cs = $array_full['child']['cs'];

        ///
        ///$array_full = Pe_identification::findArrayImages_ByDate(177,1590999652);
        ///

        ///
        $para = Yii::$app->request->queryParams;
        $searchModel = new post_pe_identification();


        $dataProvider = $searchModel->search($para);

        ddd($dataProvider->getModels());

        //Yii::setAlias('@imageurl',dirname(dirname(__DIR__)).'/frontend/web/photo');
        //Yii::setAlias('@imageurl',imagewebp('')'/photo');


        return $this->render('gallery/index', [
            "searchModel" => $searchModel,
            "dataProvider" => $dataProvider,
//            'array_full' => $array_full,

            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
            'name_cs' => $name_cs,

        ]);
    }

    /**
     *
     * =
     * {@inheritdoc}
     */
    public function actionIndex_list()
    {
        //        ddd($this);
        //$sklad = Sklad::getSkladIdActive();

//        ddd($_SESSION);

        $sklad = Sklad::getSkladIdActive(); /// Sklad


//        if (!isset($sklad) || empty($sklad)) {
//            $array_sklad_list = Yii::$app->getUser()->identity->sklad;  // * All SKLAD's
//
//            if (!is_array($array_sklad_list)) {
//                if (isset($array_sklad_list) && !empty($array_sklad_list) && Sklad::setSkladIdActive($array_sklad_list)) {
//                    $sklad = Sklad::getSkladIdActive();
//                }
//            } else {
//                foreach ($array_sklad_list as $item) {
//                    $array [$item] = $item;
//                }
//                asort($array);
//                $array_sklad_list = $array;
//            }
//        }

        $user_name = Yii::$app->getUser()->identity->username_for_signature;


        $ap = Sklad::getApIdActive(); /// Ap
        $pe = Sklad::getPeIdActive(); /// Pe

        ///
        //        $ap = Sklad::getApIdActive();
        //        $pe = Sklad::getPeIdActive();

        $array_full = Sprwhelement::findFullArray($pe);

        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];
        $name_cs = $array_full['child']['cs'];
        //ddd($array_full);


        return $this->render('/mobile/index_list', [
            "user_name" => $user_name,

            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
            'name_cs' => $name_cs,

            'sklad' => $sklad,
            //            'array_sklad_list' => $array_sklad_list,
        ]);

    }

    /**
     * Закрытие дня. Первая страница
     * =
     * {@inheritdoc}
     */
    public function actionIndex_close_day()
    {
        $sklad = $this->_sklad; /// Sklad

        $ap = $this->_ap; /// Ap
        $pe = $this->_pe; /// Pe


        $array_full = Sprwhelement::findFullArray($pe);
        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];


        return $this->render('/mobile/index_close_day', [
            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,

            'sklad' => $sklad,
        ]);

    }


    /**
     * Замены. Демонтаж/Монтаж;
     * =
     * ЗАКРЫТИЕ ДНЯ. Показ Таблицы накладных Демонтаж, Монтаж, Замена.
     * Создание итоговой накладной и передача ее Дежурному Складу
     *
     * @return mixed
     */
    public function actionIndex_a_day()
    {
        $para = Yii::$app->request->queryParams;

        $searchModel = new post_mts_change();
        $dataProvider_change_things = $searchModel->search_change_things($para);


        return $this->render('change_things/index_list', [
            "searchModel" => $searchModel,
            "dataProvider_change_things" => $dataProvider_change_things,
        ]);
    }

    /**
     * Водим скалад. SKLAD для МТС
     * =
     * {@inheritdoc}
     */
    public function actionInit_sklad()
    {
        $array_sklad_list = Yii::$app->getUser()->identity->sklad;  // * All SKLAD's

        ddd($array_sklad_list);


        if (!is_array($array_sklad_list)) {
            if (isset($array_sklad_list) && !empty($array_sklad_list) && Sklad::setSkladIdActive($array_sklad_list)) {
                return $this->redirect('/mobile');
            }
        } else {
            $array = [];
            foreach ($array_sklad_list as $item) {
                $array [$item] = $item;
            }
            asort($array);
            $array_sklad_list = $array;
        }


        $model = new mts_crm();
        $model->id = Sklad::getSkladIdActive();


        //        ddd($array_sklad_list);

        ///
        ///
        if ($model->load(Yii::$app->request->post())) {
            Sklad::setSkladIdActive($model->id);
            return $this->redirect('/mobile/index');
        }


        return $this->render('init_sklad/index_sklad', [
            'array_sklad_list' => $array_sklad_list,
            'model' => $model,
        ]);
    }

    /**
     * Init_ap *
     * Вод номера AP
     * =
     * {@inheritdoc}
     */
    public function actionInit_ap_pe()
    {
        $para = Yii::$app->request->queryParams;

        if (!isset($para['ap']) || !isset($para['pe'])) {
            throw new HttpException(411, 'ПАРК и АВТОБУС', 4);
        }

        ///
        ///
        Sklad::setApIdActive($para['ap']);
        Sklad::setPeIdActive($para['pe']);


        $sklad = Sklad::getSkladIdActive();
        $this->_sklad = $sklad;

        $ap = Sklad::getApIdActive();
        $this->_ap = $ap;

        $pe = Sklad::getPeIdActive();
        $this->_pe = $pe;


        $array_full = Sprwhelement::findFullArray($pe);
        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];


        $para = Yii::$app->request->queryParams;

        $searchModel = new post_mts_crm();
        $dataProvider_open = $searchModel->search_open($para);
        $dataProvider_close = $searchModel->search_close($para);


        ///
        return $this->render('/mobile/index_tree', [
            'sklad' => $this->_sklad,
            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,


            "searchModel" => $searchModel,
            "dataProvider_open" => $dataProvider_open,
            "dataProvider_close" => $dataProvider_close,
        ]);
    }


    /**
     * Init_ap *
     * Вод номера AP
     * =
     * {@inheritdoc}
     */
    public function actionInit_ap()
    {
        $array_ap_list = Sprwhtop::Array_cs();  // * All CS
        $model = new Sprwhtop();

        ///
        /// Подставим Ид, если уже выбран
        ///
        $this->_ap = Sklad::getApIdActive();
        if (isset($this->_ap) || !empty($this->_ap)) {
            $model->id = (int)$this->_ap;
        }

        $this->_sklad = Sklad::getSkladIdActive();

        ///
        ///
        if ($model->load(Yii::$app->request->post())) {

            //            ddd($model);

            if (Sklad::setApIdActive($model->id)) {
                $ap = Sklad::getApIdActive();
                $this->_ap = $ap;
            }

            if (Sklad::setPeIdActive(0)) {
                $pe = 0;
                $this->_pe = 0;
            }


            $array_full = Sprwhelement::findFullArray($pe);
            $name_ap = $array_full['top']['name'];
            $name_pe = $array_full['child']['name'];

            //ddd($this);

            return $this->render('/mobile/index_tree', [
                'sklad' => $this->_sklad,
                'ap' => $ap,
                'pe' => $pe,
                'name_ap' => $name_ap,
                'name_pe' => $name_pe,
            ]);
        }


        return $this->render('init_ap_pe/index_ap', [
            'array_ap_list' => $array_ap_list,
            'model' => $model,
        ]);
    }


    /**
     * Init_pe     *
     * Вод номера PE
     * =
     * {@inheritdoc}
     */
    public function actionInit_pe()
    {
        $ap = Sklad::getApIdActive();
        if (!isset($ap) || empty($ap)) {
            //            return $this->render('/mobile/errors', ['err' => "С начала выберите ПАРК"]);
            throw new HttpException(411, 'Выберите ПАРК и АВТОБУС', 4);
        }

        $array_pe_list = Sprwhelement::Array_cs($ap);  // * All CS

        $model = new Sprwhelement();
        ///
        ///
        $pe = Sklad::getPeIdActive();
        if (isset($pe) || !empty($pe)) {
            $model->id = (int)$pe;
        }

        ///
        $sklad = Sklad::getSkladIdActive();


        if ($model->load(Yii::$app->request->post())) {

            $ap = Sklad::getApIdActive();

            if (Sklad::setPeIdActive($model->id)) {
                $pe = Sklad::getPeIdActive();
            }

            $array_full = Sprwhelement::findFullArray($pe);
            $name_ap = $array_full['top']['name'];
            $name_pe = $array_full['child']['name'];
            //ddd($array_full);


            return $this->render('/mobile/index_tree', [
                'sklad' => $sklad,
                'ap' => $ap,
                'pe' => $pe,
                'name_ap' => $name_ap,
                'name_pe' => $name_pe,
            ]);
        }


        return $this->render('init_ap_pe/index_pe', [
            'array_pe_list' => $array_pe_list,
            'model' => $model,
        ]);
    }


    /**
     * Seekbus
     * =
     * Заявки на сегодня из CRM
     * -
     * {@inheritdoc}
     */
    public function actionSeekbus()
    {
        $ap = Sklad::getApIdActive();
        if (!isset($ap) || empty($ap)) {
            throw new HttpException(411, 'Выберите ПАРК и АВТОБУС', 5);
        }
        $pe = Sklad::getPeIdActive();
        if (!isset($pe) || empty($pe)) {
            throw new HttpException(411, 'Укажите ПЕ', 5);
        }

        $para = Yii::$app->request->queryParams;

        $searchModel = new post_mts_crm();
        //  $dataProvider_open = $searchModel->search_open($para);
        //  $dataProvider_close = $searchModel->search_close($para);

        $dataProvider_open = $searchModel->search_open_activ($para, $this->_pe);    //только для активного автобуса
        $dataProvider_close = $searchModel->search_close_activ($para, $this->_pe);  //только для активного автобуса

        return $this->render('shaman/seekbus', [
            "searchModel" => $searchModel,
            "dataProvider_open" => $dataProvider_open,
            "dataProvider_close" => $dataProvider_close,
        ]);
    }


    /**
     * ShamaN
     * =
     * {@inheritdoc}
     */
    public function actionShaman()
    {
        $para = Yii::$app->request->get();

        ///Ap
        $ap = Sklad::getApIdActive();
        if (!isset($ap) || empty($ap)) {
            throw new HttpException(411, 'Укажите АП', 5);
        }

        ///Pe
        $pe = Sklad::getPeIdActive();
        if (!isset($pe) || empty($pe)) {
            throw new HttpException(411, 'Укажите ПЕ', 5);            //return $this->render('/mobile/errors', ['err' => "Не установлен ПЕ.<br> Рекомендация: Укажите ПЕ"]);
        }

        $nepoladki = [
            'Белый экран ', 'Черный экран ', 'Микрофон', 'Не работает сенсор', 'Привязка ПВ', 'Отсутствует связь',
            'Неправильная привязка МСАМ карты', 'Ошибка программного обеспечения', 'Сломан ролик', 'Сломан нож',
            'Не работает принтер', 'Неправильная привязка служебной карты', 'На экране "фатальная ошибка"',
            'Не принимает оплату', 'Умышленная порча оборудования', 'Отсутствует связь'

        ];

        $reshenia = [
            0 => "Нет решения",
            1 => "Перезагрузка",
            2 => "Переобжимка коннекторов",
            3 => "Пересборка контактов",
        ];

        $reshenia2 = [
            'Водитель не дождался мастера',
            'Водитель не отвечает',
            'Водитель отказался от ремонта',
            'Восстановление питания',
            'Монтаж АСУОП завершен',
            'Демонтаж АСУОП завершен',
            'Демонтаж МТТ',
            'Демонтаж стабилизатора МТТ',
            'Замена 1-го терминала',
            'Замена 2-го терминала',
            'Замена автомобильного стабилизатора от МТТ',
            'Замена антенны',
            'Замена колодки',
            'Замена МСАМ',
            'Замена МТТ',
            'Замена обоих терминалов',
            'Замена ПВ',
            'Замена поручня',
            'Замена предохранителя',
            'Замена разъёма',
            'Замена свитча',
            'Замена сим карты',
            'Замена стабилизатора VSP01',
            'Мастер проверил, всё оборудование работает',
            'Нет доступа к ПЕ',
            'Обмен сервера инкассации',
            'Перезапуск МТТ',
            'Перезапуск ПВ',
            'Перезапуск терминалов',
            'Переобжим коннектора RJ45',
            'Переобжим коннектора молекс на ПВ',
            'Переобжим коннектора молекс на терминале',
            'Переобжим коннектора на стабилизаторе VSP01',
            'Поломка самого ТС',
            'Привязка ПВ',
            'Привязка служебной карты МТТ',
            'Со слов водителя МТТ работает',
            'Со слов водителя ПВ работает',
            'Со слов водителя терминалы работают',
            'Телефон отключен',
            'Укрепление поручня в парке',
        ];
        $reshenia = $reshenia + $reshenia2;
        asort($reshenia);

        $itog = [
            '1' => 'Решено на месте',
            '2' => 'Замена из ОФ',
            '3' => 'Нет решения',
        ];


        if (isset($para['id'])) {
            $model = mts_crm::findModelDouble((int)$para['id']);
        }


        if (isset($para['mts_crm']['id'])) {
            $model = mts_crm::findModelDouble($para['mts_crm']['id']);
        }

        ///
        if (isset(Yii::$app->getUser()->identity->id) && !empty(Yii::$app->getUser()->identity->id)) {
            $model->mts_id = Yii::$app->getUser()->identity->id;
        } else {
            $model->mts_id = 0;
        }


        if ($model->load(Yii::$app->request->get())) {

            $model->id = (int)$model->id;
            $model->mts_id = Yii::$app->getUser()->identity->id;
//            $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
//            $model->dt_update_timestamp = strtotime('now');

            $model->job_fin = (int)1; // РАбота выполнена
            $model->job_fin_timestamp = strtotime('now');

//            'job_fin' => 'Работа выполнена',
//            'job_fin_timestamp' => 'Время',


            if (!$model->save(true)) {
                ddd($model->errors);
            }

            return $this->redirect('/mobile/index');
        }


        return $this->render('shaman/index', [
//            "user_name" => $user_name,
            "nepoladki" => $nepoladki,
            "reshenia" => $reshenia,
            "itog" => $itog,

            "model" => $model,
        ]);

    }

    /**
     * =
     * {@inheritdoc}
     */
    public function actionSummary_all()
    {
        $para = Yii::$app->request->queryParams;

        $searchModel = new postsklad_mts();
        $dataProvider_god = $searchModel->search_god($para);
        $dataProvider_bad = $searchModel->search_bad($para);


        return $this->render('changer/summary', [
            "searchModel" => $searchModel,
            "dataProvider_god" => $dataProvider_god,
            "dataProvider_bad" => $dataProvider_bad,
        ]);
    }

    /**
     * =
     * {@inheritdoc}
     */
    public function actionSummary_one()
    {
        $para = Yii::$app->request->queryParams;

        $model = Sklad::find()
            ->where(['==', 'id', (int)$para['id']])
            ->asArray()
            ->one();

        $data = $model['array_tk_amort'];

        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'attributes' => ['id', 'name'],
            ],
        ]);

//        ddd($provider);


        return $this->render('changer/summary_one_god', [
//            "searchModel" => $searchModel,
            "dataProvider" => $provider,
            "id" => $para['id'],

        ]);
    }


    /**
     * change_things   FROM OF
     * Экстренная замена из Оборотного Фонда
     * =
     * {@inheritdoc}
     */
    public function actionChange_things()
    {
        ///
        /// Получить номер склада МТС
        ///

        //        $number_sklad_user = Yii::$app->getUser()->identity->sklad;
        //        if (is_array($number_sklad_user)) {
        //            return $this->render('/mobile/errors', ['err' => "МТС не должен иметь несколько складов"]);
        //        } else {
        //            if (Sklad::setSkladIdActive($number_sklad_user)) {
        //                $sklad = $number_sklad_user;
        //            }
        //        }

        ///
        $sklad = Sklad::getSkladIdActive();
        if (!isset($sklad) || empty($sklad)) {
            throw new HttpException(411, 'Выберите Ваш склад', 5);
        }

        ///OBMEN FOND
        $array_mts_obmen_fond = self::mts_obmen_fond($sklad);
        //ddd($array_mts_obmen_fond);

        $array_list_obmen_fond = [];
        foreach ($array_mts_obmen_fond as $item) {
            if ($item['bar_code'] != '') {
                $array_list_obmen_fond [$item['bar_code']] = $item['bar_code'];
            }
        }


//        ddd($array_list_obmen_fond);
//        ddd($array_mts_obmen_fond);


        ///Ap
        $ap = Sklad::getApIdActive();
        if (!isset($ap) || empty($ap)) {
            throw new HttpException(411, 'Укажите АП', 5);
        }

        ///Pe
        $pe = Sklad::getPeIdActive();
        if (!isset($pe) || empty($pe)) {
            throw new HttpException(411, 'Укажите ПЕ', 5);            //return $this->render('/mobile/errors', ['err' => "Не установлен ПЕ.<br> Рекомендация: Укажите ПЕ"]);
        }

        ///
        /// * Виртуальные остатки по ЦС
        $array_summary_from_cs = $this->Summary_from_cs($pe);
        $array_from_cs = [];
        foreach ($array_summary_from_cs as $item) {
            if ($item['itog'] > 0 && strlen($item['bar_code']) > 1) {
                $array_from_cs [$item['bar_code']] = $item['bar_code'];
            }
        }
        asort($array_from_cs);

        ///
        ///FULL
        ///
        $array_full = Sprwhelement::findFullArray($pe);
        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];
        $name_cs = $array_full['child']['cs'];

        ///
        ///Model
        $model = new Mts_change();
        $model->id = Mts_change::setNext_max_id();


        ///
        ///
        /// LOAD. ONLY POST !!!
        ///
        ///
        if ($model->load(Yii::$app->request->post())) {

//            ddd($_FILES);
//            ddd($model);
//            if (!empty($model->imageFiles[1][0])) {

            $model->imageFiles_old = UploadedFile::getInstances($model, 'imageFiles[1]');
            /// UPLOAD OLD
            if (!$path_hash = $model->upload_old()) {
                //return $this->render('/mobile/errors', ['err' => "Не загрузил фото OLD"]);
                throw new HttpException(411, 'Не загрузил фото OLD');
            }
            $model->path_hash_old = $path_hash;
            foreach ($_FILES['Mts_change']['name']['imageFiles'][1] as $item) {
                $array_old[] = $item;
            }
            $model->imageFiles_old = $array_old;

//            }
//            if (!empty($model->imageFiles[2][0])) {

            $model->imageFiles_new = UploadedFile::getInstances($model, 'imageFiles[2]');
            /// UPLOAD NEW
            if (!$path_hash = $model->upload_new()) {
                //return $this->render('/mobile/errors', ['err' => "Не загрузил фото NEW"]);
                throw new HttpException(411, 'Не загрузил фото NEW');
            }
            $model->path_hash_new = $path_hash;
            foreach ($_FILES['Mts_change']['name']['imageFiles'][2] as $item) {
                $array_new[] = $item;
            }
            $model->imageFiles_new = $array_new;
//            }

            ///
            unset($model->imageFiles);

            /// SAVE  Model
            $model->id = (int)$model->id;
            $model->mts_id = Yii::$app->getUser()->identity->id;
            ///
            $model->sklad = (int)$this->_sklad;
            $model->id_ap = (int)$this->_ap;
            $model->id_pe = (int)$this->_pe;
            ///
            $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
            $model->dt_create_timestamp = strtotime('now');
            $model->job_fin = (int)1;   ///job_fin

            ///
            ///
            if (!$model->save(true)) {
                ddd($model->errors);
            }

            return $this->redirect('/mobile_close_day/exchanges_view');
        }


        //                ddd($array_summary_from_cs);
        //        ddd($array_mts_obmen_fond);

        return $this->render('change_things/index', [
            'array_list_obmen_fond' => $array_list_obmen_fond,
            'array_from_cs' => $array_from_cs,
            'model' => $model,

            'ap' => $ap,
            'pe' => $pe,
            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
            'name_cs' => $name_cs,

        ]);
    }

    /**
     * Два способа.
     * -
     * Изменить размер ИЗОБРАЖЕНИЯ. Файл уменьшить
     * =
     * @param $path_for_photo
     * @return mixed
     */
    static function resize_img_file($path_for_photo)
    {
        ///
        ///
        if (!isset($path_file_img) || empty($path_file_img)) {
            $path_for_photo = '2.jpg';
        }
        $path_for_photo_save = 'save_' . $path_for_photo;


        $imgFull = yii::getAlias('@path_img'); ///

//        $imgDir = DIRECTORY_SEPARATOR . 'photo';
//                    echo '<img src="' . $imgDir . DIRECTORY_SEPARATOR . $path_for_photo . '" />';
//                    echo '<br>';
//                    echo '<br>';
//                    echo '<br>';

        // создаём исходное изображение на основе
        // исходного файла и опеределяем его размеры
        $src = imagecreatefromjpeg($imgFull . DIRECTORY_SEPARATOR . $path_for_photo); /// OK!!

        //        $w_src = imagesx($src);
        //        $h_src = imagesy($src);
        //        //$w = 218; // пропорциональная шириной 218
        //        $w = 300; // пропорциональная шириной 100
        //
        //
        //        // вычисление пропорций
        //        $ratio = $w_src / $w;
        //        //$percent = $w / $w_src * 100;
        //        $w_dest = round($w_src / $ratio);
        //        $h_dest = round($h_src / $ratio);
        //
        //        // создаём пустую картинку
        //        // важно именно truecolor!, иначе будем иметь 8-битный результат
        //        $dest = imagecreatetruecolor($w_dest, $h_dest);
        //
        //        imagecopyresized($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);//
        //
        ////        imagecopyresampled($imd,$im,0,0,0,0,$W/2,$H/2,$W,$H);
        ////        imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
        //
        //        // вывод картинки и очистка памяти
        //        imagejpeg($dest, $imgFull . DIRECTORY_SEPARATOR . $path_for_photo_save, '50'); // 40%
        //
        ////        ddd($dest);


        ///
        ///
        ///
        // Set a  fixed height and width
        $width = 300;
        $height = 300;

        // Get image dimensions
        list($width_orig, $height_orig) = getimagesize($imgFull . DIRECTORY_SEPARATOR . $path_for_photo);


        // Resample the image
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($imgFull . DIRECTORY_SEPARATOR . $path_for_photo);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        // Output the image
        //        header('Content-Type: image/jpeg');
        //        imagejpeg($image_p, null, 100);

        //imagejpeg($image_p, 'C:\\OSPanel\\domains\\wh\\frontend\\web\\photo\\save_2.jpg', 50); // 40%
        imagejpeg($image_p, $imgFull . DIRECTORY_SEPARATOR . $path_for_photo_save, 50); // 40%


        imagedestroy($dest);
        imagedestroy($src);

        return $imgFull . DIRECTORY_SEPARATOR . $path_for_photo_save;
    }

    /**
     * OBMENYi FOND
     * =
     * @param $sklad
     * @return mixed
     */
    static function mts_obmen_fond($sklad)
    {
        ///
        /// Получить массив накладных от Дежурного склада Ночных инженеров
        ///
        $model = Sklad::find()
            ->select([
                '*',
                'array_tk_amort.wh_tk_amort',
                'array_tk_amort.wh_tk_element',
                'array_tk_amort.ed_izmer',
                'array_tk_amort.ed_izmer_num',
                'array_tk_amort.take_it',
                'array_tk_amort.bar_code',
                //                'array_tk_amort.name',
            ])
            ->where(
                ['AND',
                    ['==', 'wh_home_number', (int)$sklad],
                    ['==', 'sklad_vid_oper', (string)Sklad::VID_NAKLADNOY_PRIHOD],
                    ['==', 'wh_debet_element', (int)4431], // 'Guidejet TI. Склад инженеров эксплуатации Дежурный'

                    //                    ['<>', 'of_closed', (int)1],  // Обменный фонд открыт. Накладная входит в сегодняшний ОФ.
                ]
            )
            ->asArray()
            ->all(); ///?????

        //        ddd($model);

        ///
        $array = [];
        foreach ($model as $item) {
            $array = array_merge($array, $item['array_tk_amort']);
        }
        ///
        asort($array);

        return $array;
    }

//    /**
//     * Montage
//     * МТС. Монтаж по ТЕхЗаданию
//     * =
//     * @return mixed
//     */
//    public function actionMontage()
//    {
//        $para = Yii::$app->request->queryParams;
////        ddd($para);
//
//        $sklad = Sklad::getSkladIdActive();
//        if (!isset($sklad) || empty($sklad)) {
//            return $this->render('/mobile/errors', ['err' => "Введите номер своего склада"]);
//        }
//
//        // BLACK LIST FOR OPEN
//        $array_black_ids = Mts_montage::arrayBlack_list_all(); ///  Sklad_ids
//        //ddd($array_black_ids);
//
//        ///SKLAD-id
//        $searchModel = new postsklad_for_mobile();
//        $dataProvider = $searchModel->search_for_montage_open($para, $sklad, $array_black_ids);
//
//        ///MTS-MONTAGE
//        $searchModel_montage = new post_mts_montage();
//        $dataProvider_montage = $searchModel_montage->search($para);
//
//        //CLOSES
//        $searchModel_close = new post_mts_close();
//        $dataProvider_close = $searchModel_close->search($para);
//
//        //        ddd($searchModel_montage);
//        //        ddd($dataProvider_montage->getModels());
//
//        return $this->render(
//            'montage/index', [
//                "searchModel" => $searchModel,
//                "dataProvider" => $dataProvider,
//
//                "searchModel_montage" => $searchModel_montage,
//                "dataProvider_montage" => $dataProvider_montage,
//
//                "searchModel_close" => $searchModel_close,
//                "dataProvider_close" => $dataProvider_close,
//            ]
//        );
//    }

    /**
     * Montage 1
     * Step 1
     * =
     * МТС. Монтаж по ТЕхЗаданию
     * =
     * @return mixed
     */
    public function actionMontage()
    {
        $para = Yii::$app->request->queryParams;
//        ddd($para);

        $sklad = Sklad::getSkladIdActive();
        if (!isset($sklad) || empty($sklad)) {
            return $this->render('/mobile/errors', ['err' => "Введите номер своего склада"]);
        }

        //
        // BLACK LIST FOR OPEN
        // Уже отработанные накландые
        //
        $array_black_ids = Mts_montage::arrayBlack_list_all(); ///  Sklad_ids
        //ddd($array_black_ids);

        ///
        ///  SKLAD-id
        /// Накладные от Дежурного склада
        ///
        $searchModel = new postsklad_for_mobile();
        $dataProvider = $searchModel->search_for_montage_open($para, $sklad, $array_black_ids);
        //CLOSES
        $searchModel_close = new post_mts_close();
        $dataProvider_close = $searchModel_close->search($para);


        return $this->render(
            'montage/step1', [
                "searchModel" => $searchModel,
                "dataProvider" => $dataProvider,

                "searchModel_close" => $searchModel_close,
                "dataProvider_close" => $dataProvider_close,
            ]
        );
    }

    /**
     * Установка на Автобус (Монтаж)
     * =
     * {@inheritdoc}
     */
    public function actionMontage_summary_one()
    {
        ///
        $pe = Sklad::getPeIdActive();
        if (!isset($pe) || empty($pe)) {
            throw new HttpException(411, 'Укажите ПЕ', 5);
        }

        ///
        ///LOAD
        $model = new Mts_montage();
        if ($model->load(Yii::$app->request->get())) {

            /// * Подсчет строк, содержащих Баркод
            //$munber_of_summary = Sklad::summPos_of_barcodes($model->sklad_id);
            ///
            //$array_barcode_montage = $model['barcode_montage'];

            /// содержимое  массива  array_tk_amort (!)
            $array_amort = Sklad::findArray_tk_amort($model->sklad_id);

            /// SAVE1
            foreach ($array_amort as $item) {

                if ((int)$item['bar_code'] > 0) {

                    $new_model = new Mts_montage();
                    $new_model->id = (int)Mts_montage::setNext_max_id();

                    $new_model->id_ap = (int)$model->id_ap; // Автопарк
                    $new_model->id_pe = (int)$model->id_pe; // PE
                    $new_model->sklad_id = (int)$model->sklad_id;

                    $new_model->mts_id = Yii::$app->getUser()->identity->id;
                    ///
                    $new_model->barcode_montage = $item['bar_code'];
                    ///
                    $new_model->job_fin = date('d.m.Y H:i:s', strtotime('now'));
                    $new_model->job_fin_timestamp = strtotime('now');


                    if (!$new_model->save(true)) {
                        ddd($new_model->errors);
                    }
                }

            }
            //ddd($model);

            return $this->redirect('/mobile/montage');
        }


        $para = Yii::$app->request->queryParams;

        ///
        $sklad_id = $para['id'];
        if (!isset($sklad_id) && !isset($para['bar_code_to_cange'])) {
            return $this->render('/mobile/errors', ['err' => "Накладная " . $sklad_id . " не открылась"]);
        }

        $model->id = Mts_montage::setNext_max_id();
        /// Накладная
        $model->sklad_id = $sklad_id;  /// ИД накладной СКЛАДА

        //
        //montage_summary_one
        // Из накладной берем массив Array_tk_amort
        //
        $data = Sklad::findArray_tk_amort($sklad_id);
        //ddd($data);

        $provider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 50,
            ],
            //            'sort' => [
            //                'attributes' => ['id', 'name'],
            //            ],
        ]);


        // Получить массив номеров БАРКОДОВ [code=>code];
        $array_barcode = Sklad::findArray_amort_double($sklad_id);

        //
        // Получить массив
        //  ddd($array_ap_pe['wh_dalee_element']);
        //  ddd($array_ap_pe['wh_dalee']);
        $array_ap_pe = Sklad::findArray_ap_pe($sklad_id);
        $model->id_ap = $array_ap_pe['wh_dalee'];
        $model->id_pe = $array_ap_pe['wh_dalee_element'];

        //ddd($sklad_id);

        return $this->render('montage/index_one', [
            'model' => $model,

            'sklad_id' => $sklad_id,
            'array_barcode' => $array_barcode,
            'dataProvider' => $provider,
        ]);
    }

    /**
     * DeMontage
     * Step 1
     * =
     * МТС. DEМонтаж полный (без ТЕХЗАДАНИЯ)
     * =
     * @return mixed
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionDemontage()
    {
        $para = Yii::$app->request->queryParams;
        //        ddd($para);

        $sklad = Sklad::getSkladIdActive();
        if (!isset($sklad) || empty($sklad)) {
            return $this->render('/mobile/errors', ['err' => "Введите номер своего склада"]);
        }

        ///Ap
        $ap = Sklad::getApIdActive();
        if (!isset($ap) || empty($ap)) {
            throw new HttpException(411, 'Укажите АП', 5);
        }

        ///Pe
        $pe = Sklad::getPeIdActive();
        if (!isset($pe) || empty($pe)) {
            throw new HttpException(411, 'Укажите ПЕ', 5);            //return $this->render('/mobile/errors', ['err' => "Не установлен ПЕ.<br> Рекомендация: Укажите ПЕ"]);
        }

        ///
        /// * Виртуальные остатки по ЦС
        $array_summary_from_cs = $this->Summary_from_cs($pe);
        foreach ($array_summary_from_cs as $item) {
            if ($item['itog'] > 0 && strlen($item['bar_code']) > 1) {
                $array_from_cs [$item['bar_code']] = $item['bar_code'];
            }
        }

        ///
        /// В этот же массив загоняем расшифровки названий
        ///
        foreach ($array_summary_from_cs as $key => $item) {
            if ($item['itog'] > 0 && !empty($item['bar_code'])) {
                ///* Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его ИД)
                $fullArray = Spr_globam_element::findFullArray_BY_barcode($item['bar_code']);

                $array_summary_from_cs[$key]['name'] = $fullArray['child']['name'];
                $array_summary_from_cs[$key]['short_name'] = $fullArray['child']['short_name'];
            } else {
                unset($array_summary_from_cs[$key]);
            }
        }
        //        ddd($array_summary_from_cs); //OK


        ///
        /// Запрос остатков от ЦС
        ///
        $provider = new ArrayDataProvider([
            'allModels' => $array_summary_from_cs,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'attributes' => ['id', 'bar_code', 'short_name', 'name'],
            ],
        ]);

        //        ddd($provider);

        //CLOSES DEMONTAGE'S
        $searchModel_close = new post_mts_close_demontage();
        $dataProvider_close = $searchModel_close->search($para);


        return $this->render(
            'demontage/index', [
                "dataProvider" => $provider,

                "searchModel_close" => $searchModel_close,
                "dataProvider_close" => $dataProvider_close,
            ]
        );
    }

    /**
     * DeMontage ONE
     * Step 2
     * =
     * МТС. Снятие одной позиции с автобуса в БУФЕР ДНЯ Мобильника МТС
     * =
     * @return mixed
     * @throws HttpException
     */
    public function actionDemontage_one()
    {
        $para = Yii::$app->request->queryParams;
        //ddd($para);

        $sklad = Sklad::getSkladIdActive();
        if (!isset($sklad) || empty($sklad)) {
            return $this->render('/mobile/errors', ['err' => "Введите номер своего склада"]);
        }

        ///Ap
        $ap = Sklad::getApIdActive();
        if (!isset($ap) || empty($ap)) {
            throw new HttpException(411, 'Укажите АП', 5);
        }


        ///Pe
        $pe = Sklad::getPeIdActive();
        if (!isset($pe) || empty($pe)) {
            throw new HttpException(411, 'Укажите ПЕ', 5);            //return $this->render('/mobile/errors', ['err' => "Не установлен ПЕ.<br> Рекомендация: Укажите ПЕ"]);
        }

        $model = new Mts_demontage();
        $model->id = Mts_demontage::setNext_max_id();
        $model->mts_id = Yii::$app->getUser()->identity->id;

        if (isset($para['id'])) {
            $model->bar_code = (string)$para['id'];

            ///* Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его ИД)
            $fullArray = Spr_globam_element::findFullArray_BY_barcode($para['id']);
            $model->name = $fullArray['child']['name'];
        }

        ///
        ///  Time
        ///
        $model->job_fin = date('d.m.Y H:i:s', strtotime('now'));
        $model->job_fin_timestamp = strtotime('now');

        $model->akt = Mts_demontage::setNext_max_id();
        $model->id_ap = Sklad::getApIdActive();
        $model->id_pe = Sklad::getPeIdActive();

//        ddd($model);

        ///
        $pe = Sklad::getPeIdActive();
        $array_full = Sprwhelement::findFullArray($pe);
        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];

        //ddd($array_full);


        ///
        /// LOAD
        ///
        if ($model->load(Yii::$app->request->get())) {
            $model->id = (int)Mts_demontage::setNext_max_id();
            $model->mts_id = Yii::$app->getUser()->identity->id;
            $model->akt = (string)$model->akt;

            $model->job_fin = date('d.m.Y H:i:s', strtotime('now')); // РАбота выполнена
            $model->job_fin_timestamp = strtotime('now');


//            ddd($model);


            if (!$model->save(true)) {
                ddd($model->errors);
            }

            return $this->redirect('/mobile/demontage');
        }


        //ddd($name_pe);
        return $this->render('demontage/_form', [
            "model" => $model,

            'name_ap' => $name_ap,
            'name_pe' => $name_pe,
        ]);

    }

    /**
     * Виртуальные остатки по ЦС
     * =
     * @param int $pe
     * @return array|string
     */
    public function Summary_from_cs($pe = 0)
    {
        if (!isset($pe) || empty($pe) || $pe == 0) {
            return $this->render('/mobile/errors', ['err' => 'Не установлен ПЕ.<br> Рекомендация: Укажите ПЕ']);
        }


        /// ПРИЕМНИК
        //$full_sklad = Sprwhelement::findFullArray($sklad);

        ///
        ///   ПОДСЧЕТ ПРИХОДОВ-РАСХОДОВ (!!!!!)
        ///
        if (isset($pe) && !empty($pe)) {
            ///
            /// Читаем КРАЙНЮЮ Ежедневныю Инвентаризацию по заданному складу.
            $model_inventory = Sklad_inventory_cs::ArrayRead_LastInventory($pe);
            if (!isset($model_inventory)) {
                $model_inventory = new Sklad_inventory_cs();
                $model_inventory->array_tk_amort = [];

                ///
                ///  ПРИХОД / РАСХОД
                ///   Полный Массив по Group+Id
                ///
                $arrayPrihodRashod = Stat_balans_cs_Controller::ArrayPrihodRashod_without_timestamp($pe);
            } else {
                ///
                ///  ПРИХОД / РАСХОД
                ///   Полный Массив по Group+Id
                ///
                $arrayPrihodRashod = Stat_balans_cs_Controller::ArrayPrihodRashod_v2(
                    $pe,
                    $model_inventory['dt_create_timestamp']
                );

                //ddd(123);
            }


//            ddd($model_inventory['dt_create_timestamp']);
//            ddd($arrayPrihodRashod);


            $array_itog_amort = [];
            $array_amort = $model_inventory['array_tk_amort']; // Ok ИНВЕНТАРИЗАЦИЯ - СТОЛБ
//ddd($model_inventory);

            ///
            /// ........
            //
            // СВОДИМ ОСТАТКИ С ПРИХОДОМ-РАСХОДОМ
            //
            ///........
            ///
//            ddd($para);
//            ddd($arrayPrihodRashod);
//            if ( isset( $array_amort ) && !empty( $array_amort ) )
            foreach ($array_amort as $item) {

                // Начало
                $ed_izmer_num = (isset($item['ed_izmer_num']) ? $item['ed_izmer_num'] : 0);
                $prihod_num = 0;
                $rashod_num = 0;

                $bar_code = $item['bar_code'];

                //// bar_code
                if (isset($item['bar_code']) && !empty($bar_code)) {


                    ///
                    ///
                    ///   PLUS
                    ///
                    if (isset($arrayPrihodRashod['plus'][$item['wh_tk_amort']][$item['wh_tk_element']]['bar_code'])) {

                        $arr_plus = $arrayPrihodRashod['plus'][$item['wh_tk_amort']][$item['wh_tk_element']]['bar_code'];
                    }


                    if (isset($arr_plus)) {
                        // KAKOI MOI?
                        // KAKOI MOI?
                        $xx = 0;
                        foreach ($arr_plus as $key => $arr_plus_item) {
                            if ($arr_plus_item == $item['bar_code']) {
                                $xx = $key;
                            }
                        }

                        //
                        if (isset($arr_plus[$xx])) {
                            $prihod_num = 1;
                            unset($arrayPrihodRashod['plus'][$item['wh_tk_amort']][$item['wh_tk_element']]['bar_code'][$xx]);
                            unset($arrayPrihodRashod['plus'][$item['wh_tk_amort']][$item['wh_tk_element']]['ed_izmer_num'][$xx]);
                            unset($arrayPrihodRashod['plus'][$item['wh_tk_amort']][$item['wh_tk_element']]['wh_tk_amort'][$xx]);
                            unset($arrayPrihodRashod['plus'][$item['wh_tk_amort']][$item['wh_tk_element']]['wh_tk_element'][$xx]);
                        }
                    }


                    ///
                    ///
                    ///   MINUS
                    ///
                    if (isset($arrayPrihodRashod['minus'][$item['wh_tk_amort']][$item['wh_tk_element']]['bar_code'])) {

                        $arr_plus = $arrayPrihodRashod['minus'][$item['wh_tk_amort']][$item['wh_tk_element']]['bar_code'];
                    }


                    if (isset($arr_plus)) {
                        // KAKOI MOI?
                        $xx = 0;
                        foreach ($arr_plus as $key => $arr_plus_item) {
                            if ($arr_plus_item == $item['bar_code']) {
                                $xx = $key;
                            }
                        }

                        //
                        if (isset($arr_plus[$xx])) {

                            $rashod_num = 1;
                            unset($arrayPrihodRashod['minus'][$item['wh_tk_amort']][$item['wh_tk_element']]['bar_code'][$xx]);
                            unset($arrayPrihodRashod['minus'][$item['wh_tk_amort']][$item['wh_tk_element']]['ed_izmer_num'][$xx]);
                            unset($arrayPrihodRashod['minus'][$item['wh_tk_amort']][$item['wh_tk_element']]['wh_tk_amort'][$xx]);
                            unset($arrayPrihodRashod['minus'][$item['wh_tk_amort']][$item['wh_tk_element']]['wh_tk_element'][$xx]);
                        }
                    }


                    ////
                }


                ////
                ///
                $array_itog_amort[] = [
                    'wh_tk_amort' => (int)$item['wh_tk_amort'],
                    'wh_tk_element' => (int)$item['wh_tk_element'],
                    'ed_izmer' => (int)$item['ed_izmer'],
                    'bar_code' => (isset($item['bar_code']) ? $item['bar_code'] : ''),
                    'ed_izmer_num' => (int)$ed_izmer_num,
                    'prihod_num' => (int)$prihod_num,
                    'rashod_num' => (int)$rashod_num,
                    'itog' => (int)$ed_izmer_num + $prihod_num - $rashod_num,
                ];
            }

//            ddd($array_itog_amort);

            ///
            ///           Вот теперь остались только(!) НОВЫЕ ПОЗИЦИИ в ПРИХОДЕ (и потом в расходе)
            ///
            ///
            //ddd($arrayPrihodRashod);
            //
            //plus
            //
            if (isset($arrayPrihodRashod['plus'])) {
                foreach ($arrayPrihodRashod['plus'] as $key_group => $item_group) {
                    foreach ($item_group as $key_tmc => $item_tmc) {
                        if (isset($item_tmc['bar_code'])) {
                            foreach ($item_tmc['bar_code'] as $key => $item_ed) {


                                //ddd($item_ed); //19600002542

                                $array_itog_amort[] = [
                                    'wh_tk_amort' => $key_group,
                                    'wh_tk_element' => $key_tmc,
                                    'ed_izmer' => 1,
                                    'bar_code' => ((isset($item_ed) && !empty($item_ed)) ? $item_ed : ''),
                                    'ed_izmer_num' => 0,
                                    'prihod_num' => 1,
                                    'rashod_num' => 0,
                                    'itog' => 1,
//                                    'dt_create_timestamp_plus' => $item_tmc['dt_create_timestamp'][$key]
                                ];

                                //$array_itog_amort[ $xx_finder ][ 'dt_create_timestamp' ] =  $item_tmc['dt_create_timestamp'][$key];
                                //ddd($item_tmc);
                            }
                        } else {
                            ddd(123);
                        }
                    }
                }
            }


            //
            //minus
            //
            if (isset($arrayPrihodRashod['minus'])) {
                foreach ($arrayPrihodRashod['minus'] as $key_group => $item_group) {
                    foreach ($item_group as $key_tmc => $item_tmc) {
                        if (isset($item_tmc['bar_code'])) {
                            foreach ($item_tmc['bar_code'] as $key => $item_ed) {


                                ///
                                foreach ($array_itog_amort as $key_itog =>
                                         $item_itog) {
                                    //if($item_itog['bar_code']=='19600002542'){
                                    if ($item_itog['bar_code'] == $item_ed) {
                                        $xx_finder = $key_itog;

                                        $xx_itog = $array_itog_amort[$xx_finder]['itog'];
                                        $array_itog_amort[$xx_finder]['rashod_num'] = 1;
                                        $array_itog_amort[$xx_finder]['itog'] = $xx_itog -
                                            1;

                                        if ($array_itog_amort[$xx_finder]['itog'] <
                                            0) {
                                            $array_itog_amort[$xx_finder]['itog'] = 0;
                                        }

                                        $array_itog_amort[$xx_finder]['dt_create_timestamp_minus'] = $item_tmc['dt_create_timestamp'][$key];

                                        //ddd($item_group);
                                    }
                                }
                            }
                        }
                    }
                }
            }


//            ddd($array_itog_amort);
//                ddd($arrayPrihodRashod);
//        ddd($arrayPrihodRashod);
//        ddd($array_itog_amort);
        }


        //
        // Устанавливаем столбец - Итого, в позицию Штуки
        //
        if (isset($array_itog_amort)) {
            foreach ($array_itog_amort as $item) {
                //ddd($item);
//                if ( $item[ 'itog' ] > 0 ) {
                if (!empty($item['itog'])) {

                    $arr_norm[] = [
                        'wh_tk_amort' => $item['wh_tk_amort'],
                        'wh_tk_element' => $item['wh_tk_element'],
                        'ed_izmer' => $item['ed_izmer'],
                        'bar_code' => $item['bar_code'],
                        'ed_izmer_num' => $item['itog'],
                        'dt_create_timestamp_plus' => (isset($item['dt_create_timestamp_plus']) ? $item['dt_create_timestamp_plus'] : ''),
                        'dt_create_timestamp_minus' => (isset($item['dt_create_timestamp_minus']) ? $item['dt_create_timestamp_minus'] : ''),
                    ];
                }
            }
        }


        return $array_itog_amort;

//        ddd($arr_norm);


//        return $this->render(
//            'cs/_form_create',[
//                'model_new' => $model_new,
//                'sklad' => $sklad,
//                'itogo' => $this->summa_itog([]),
//                'alert_mess' => '',
//                'alert_string' => '',
//            ]
//        );

    }

//Подсчет общего количества товара
    function summa_itog($array)
    {
        $x = 0;

        foreach ($array as $item) {
            $x += $item['ed_izmer_num'];
        }
        return $x;

    }

    /**
     * OCR - reader txt     *
     * -
     * Распознавание текста по ФОТО
     *=
     *
     */
    public function actionOcr()
    {
        $para = Yii::$app->request->queryParams;

        if (!isset($para) || !isset($path_for_photo) || empty($path_for_photo)) {
//            $path_for_photo = '8055.png';
//            $path_for_photo = '1.png'; // big text
//            $path_for_photo = '17.png'; ///Null
//            $path_for_photo = '16.jpg';
//            $path_for_photo = '18.jpg';

            $path_for_photo = '14.jpg';
//            $path_for_photo = '12.jpg';
        }


        $imgDir = DIRECTORY_SEPARATOR . 'photo';

        $imgFull = yii::getAlias('@path_img'); ///

        echo '<img src="' . $imgDir . DIRECTORY_SEPARATOR . $path_for_photo . '" />';
//        echo '<br>';
//        echo '<br>';
//        echo '<br>';


        /// РАБОТАЕТ!!!! DEBIAN
        //        $ocr = new TesseractOCR();
        $ocr = new Tesseract();

        $ocr->image($imgFull . DIRECTORY_SEPARATOR . $path_for_photo);
        $ocr->setWhitelist(range('a', 'z'), range('A', 'Z'), range(0, 9));
        $ocr->setLanguage('eng');
        //->recognize();
        //        $ocr->setPsm(2); //!
        $str = preg_split('/[ \\n]/', ($ocr)->run());        //$str = ($ocr)->run();

        //        ddd($ocr);

        ddd($str);


        //$ocr->setLanguage('eng')->recognize(true)->autofocus(true);

        //        $ver = ($ocr)->version();
        //        ddd($ver); // '4.0.0'


        //chmod(Yii::getAlias($tmpDir), 0777);

        /// РАБОТАЕТ!!!! Into WINDOWS
//        $text = (new TesseractOCR($imgFull . DIRECTORY_SEPARATOR . $path_for_photo))
//            //->executable('C:\Program Files (x86)\Tesseract-OCR\tesseract.exe')
//            ->executable('C:\Program Files\Tesseract-OCR\tesseract.exe')
//            ->whitelist( range('A', 'Z'), range(0, 9))
//            ->run();
//        ddd($text);

        return '';

    }


    /**
     * OCR - reader txt     *
     * -
     * Распознавание текста по ФОТО
     *=
     *
     */
    public function actionGim()
    {
        //ddd(111111);

        return $this->render('gim/index');

    }


}