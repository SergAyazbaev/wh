<?php
namespace frontend\controllers;
use frontend\models\Barcode_pool;
use frontend\models\Mts_change;
use frontend\models\post_mts_change;
use frontend\models\post_pe_identification;
use frontend\models\Sklad;
use frontend\models\Sklad_transfer;
use frontend\models\Spr_globam_element;
use frontend\models\Sprwhelement;
use Yii;
use yii\base\ExitException;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;


class Mobile_close_dayController extends MobileController
{


    /**
     * Доступ только В отладочном режиме
     * =
     * {@inheritdoc}
     */
    public function beforeAction($event)
    {
        /// Только эта проверка. Больше тут не надо!!!!
        if (!isset(Yii::$app->getUser()->identity->id)) {
            throw new HttpException(411, 'Необходима авторизация', 1);
        }

        //        ddd($event->id); // exchanges_all_to_sklad  // текуший
        //        ddd($event->controller->id); //mobile_close_day // предыдущий
        //        ddd($event);

        //        return parent::init();

        return parent::beforeAction($event);
    }

    /**
     * ИНИТ
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $session = Yii::$app->session;
        $session->open();

        if (!Yii::$app->getUser()->identity) {
            throw new UnauthorizedHttpException('Необходима авторизация', 1); //Необходима авторизация==1
        }


        ///
        /// .....
        $this->_sklad = Sklad::getSkladIdActive();
        if (!isset($this->_sklad) || empty($this->_sklad)) {
            throw new HttpException(411, 'Выберите склад', 5);
        }


        $this->_ap = Sklad::getApIdActive();
        $this->_pe = Sklad::getPeIdActive();
        if (!isset($this->_pe) || empty($this->_pe) || !isset($this->_ap) || empty($this->_ap)) {
            throw new HttpException(411, 'Выберите ПАРК и АВТОБУС', 5);
        }

        //ddd($this);
        return true;
    }

    /**
     * Просмотр Сегодняшних ЗАМЕН в буфере Замен.
     * =
     */
    public function actionExchanges_view()
    {
        ///
        $array_full = Sprwhelement::findFullArray($this->_pe);
        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];

        ///
        $query = Mts_change::find()
            ->where(['AND',
                ['==', 'id_ap', $this->_ap],
                ['==', 'id_pe', $this->_pe],
                ['==', 'close_day', (int)0]   // Позиции, которые не закрыты
            ]);

        ///
        /// DATA Provider
        ///
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        ///
        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC]
        ]);


        //return $this->render('change_things/index_list', [
        return $this->render('change_things/index2', [
            "dataProvider" => $dataProvider,

            "name_ap" => $name_ap,
            "name_pe" => $name_pe,
        ]);

    }

    /**
     * DELETE
     * =
     * @return Response
     * @throws HttpException
     * @throws \yii\db\StaleObjectException
     */
    public function actionMove_to_delete()
    {
        $para = Yii::$app->request->queryParams;

        if (!isset($para['id']) || empty($para['id'])) {
            throw new HttpException(411, 'Не возможно удалить. Нет ИД', 3);
        }

        if (!$model = Mts_change::findOne($para['id'])->delete()) {
            throw new HttpException(411, 'Не возможно удалить. Сбой сети', 3);
        }

        return $this->redirect(['/mobile_close_day/exchanges_view']);
    }

    /**
     * 11111
     *
     * ЗАМЕНЫ. Сброс накладных в базу.
     * На Склад-МТС. Монтаж+Демонтаж накладные
     * -
     * Закрытие АВТОБУСА. ОФОРМЛЕНИЕ ДОКУМЕНТОВ в базу
     * =
     */
    public function actionExchanges_all_to_sklad()
    {
        ///
        /// Все записи за сегодня по этому АВТОБУСУ
        ///
        $model_mts_cahge = Mts_change::find()
            ->select(['barcode_bad', 'barcode_god'])
            ->where(['AND',
                ['==', 'id_ap', (int)$this->_ap],
                ['==', 'id_pe', (int)$this->_pe],
                ['==', 'sklad', (int)$this->_sklad],
                ['==', 'close_day', (int)0]   // Позиции, которые не закрыты
            ])
            ->orderBy('dt_create_timestamp ASC')
            ->asArray()
            ->all();


        if (isset($model_mts_cahge) && !empty($model_mts_cahge)) {
            ///
            $array_god = [];
            $array_bad = [];
            foreach ($model_mts_cahge as $item) {

                /////////////////
                /////////////////

                ///
                /// Good
                ///
                $full_array = Barcode_pool::findFull_array($item['barcode_god']);

                ///
                $array_god[] = [
                    "wh_tk_amort" => (string)$full_array['spr_globam_element']['parent_id'],
                    "wh_tk_element" => (string)$full_array['spr_globam_element']['id'],
                    //"name" => $full_array['spr_globam_element']['name'],
                    "name" => (string)$full_array['spr_globam_element']['short_name'],
                    "intelligent" => (string)$full_array['spr_globam_element']['intelligent'],
                    "ed_izmer" => "1",
                    "ed_izmer_num" => "1",
                    "take_it" => "0",
                    "bar_code" => (string)$full_array['bar_code'],
                ];


                ///
                ///
                /// BAD
                ///
                $full_array_bad = Barcode_pool::findFull_array($item['barcode_bad']);


                ///
                $array_bad[] = [
                    "wh_tk_amort" => (string)$full_array_bad['spr_globam_element']['parent_id'],
                    "wh_tk_element" => (string)$full_array_bad['spr_globam_element']['id'],
                    "name" => (string)$full_array_bad['spr_globam_element']['short_name'],
                    "intelligent" => (string)$full_array_bad['spr_globam_element']['intelligent'],
                    "ed_izmer" => "1",
                    "ed_izmer_num" => "1",
                    "take_it" => "0",
                    "bar_code" => (string)$full_array_bad['bar_code'],
                ];


                ///
                ///   АВТОБУС документально ЗАКРЫТ по позициям (по одной)
                ///
                $model_mts_cahge2 = Mts_change::findOne($item['_id']);
                $model_mts_cahge2->close_day = (int)1;
                if (!$model_mts_cahge2->save(true)) {
                    ddd($model_mts_cahge2->errors);
                }

            }

            ////////////////////////////////////
            ///
            ////////////////////////////////////

            //        ddd($array_god);
            //        ddd($array_bad);

            ///
            /// Монтаж в ЦС
            ///
            /// СТАРТ
            ///
            $model = new Sklad();
            $model->id = Sklad::setNext_max_id();

            $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;
            $model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;

            $model->wh_home_number = (int)$this->_sklad;
            $model->dt_create = date('d.m.Y H:i:s', strtotime('now '));
            $model->dt_create_timestamp = strtotime('now');

            ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
            ///  ТАБ 1
//        $model->array_tk_amort = Sklad::setArraySort1($model->array_tk_amort);
            //					$model->array_tk_amort = Sklad::setArrayClear( $model->array_tk_amort );
            ///  ТАБ 2
//        $model->array_tk = Sklad::setArraySort2($model->array_tk);

            /// ИСТОЧНИК
            $xx2 = Sprwhelement::findFullArray($this->_sklad);
            $model->wh_debet_name = $xx2['top']['name'];
            $model->wh_debet_element_name = $xx2['child']['name'];
            $model->wh_debet_top = (int)$xx2['top']['id'];
            $model->wh_debet_element = (int)$xx2['child']['id'];

            /// ПРИЕМНИК
            $xx2 = Sprwhelement::findFullArray($this->_pe);
            $model->wh_destination_name = $xx2['top']['name'];
            $model->wh_destination_element_name = $xx2['child']['name'];
            $model->wh_destination = (int)$xx2['top']['id'];
            $model->wh_destination_element = (int)$xx2['child']['id'];

            /// ДАЛЕЕ
            $model->wh_dalee = (int)$xx2['top']['id'];
            $model->wh_dalee_element = (int)$xx2['child']['id'];

            /// Инициализируем Конечный СКЛАД
            $model->wh_cs_number = (int)$xx2['child']['id'];
            $model->wh_destination_element_cs = (int)$xx2['child']['id'];

            /// Коммент - АКТ
            $model->tx = "Экстренная замена ";

            ///Всего строк в накладной
            $model->array_count_all = count($array_god);

            //////
            $model->array_tk_amort = $array_god;


            ///
            if (!$model->save(true)) {
                ddd($model->errors);
            }

            unset($model);


            ///\\\\\\\\\\\\\\\\\\\
            ///
            /// ДЕМОНТАЖ ИЗ ЦС
            ///
            /// СТАРТ
            ///
            ///\\\\\\\\\\\\\\\\\\\
            $model2 = new Sklad();
            $model2->id = Sklad::setNext_max_id();
            $model2->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
            $model2->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

            $model2->wh_home_number = (int)$this->_sklad;
            $model2->dt_create = date('d.m.Y H:i:s', strtotime('now 2 seconds'));
            $model2->dt_create_timestamp = strtotime('now 2 seconds');

            ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
            ///  ТАБ 1
//        $model->array_tk_amort = Sklad::setArraySort1($model->array_tk_amort);
            //					$model->array_tk_amort = Sklad::setArrayClear( $model->array_tk_amort );
            ///  ТАБ 2
//        $model->array_tk = Sklad::setArraySort2($model->array_tk);

            /// ИСТОЧНИК
            $xx2 = Sprwhelement::findFullArray($this->_pe);
            $model2->wh_debet_name = $xx2['top']['name'];
            $model2->wh_debet_element_name = $xx2['child']['name'];
            $model2->wh_debet_top = (int)$xx2['top']['id'];
            $model2->wh_debet_element = (int)$xx2['child']['id'];
            /// Инициализируем ЦС
            $model2->wh_cs_number = (int)$xx2['child']['id'];
            $model2->wh_destination_element_cs = (int)$xx2['child']['id'];

            /// ПРИЕМНИК
            $xx2 = Sprwhelement::findFullArray($this->_sklad);
            $model2->wh_destination_name = $xx2['top']['name'];
            $model2->wh_destination_element_name = $xx2['child']['name'];
            $model2->wh_destination = (int)$xx2['top']['id'];
            $model2->wh_destination_element = (int)$xx2['child']['id'];


            /// ДАЛЕЕ
            $model2->wh_dalee = (int)$xx2['top']['id'];
            $model2->wh_dalee_element = (int)$xx2['child']['id'];

            /// Коммент - АКТ
            $model2->tx = "Экстренная замена ";

            ///Всего строк в накладной
            $model2->array_count_all = count($array_bad);

            ///
            $model2->array_tk_amort = $array_bad;


            ///
            if (!$model2->save(true)) {
                ddd($model2->errors);
            }
        }


        //ddd(11111);


        return $this->redirect('/mobile/index');

    }

    /**
     * СОВСЕМ ЗАКРЫВАЕМ ДЕНЬ
     * ===
     *
     * Оборотный Фонд
     * -
     * Закрытие ДНЯ. СОздание Грязной и Читстой накладной по Оборотному Фонду
     * -
     * 1.Создание собственных накладных на передачу Дежурному Складу
     * -
     * 2.Передача накладных Дежурному Складу через буфер обмена
     * -
     */
    public function actionClose_a_day()
    {
        ///* Проверяем ВСЕ ЛИ закрыты сегодняшние ЗАМЕНЫ в буфере.
        if ($this->actionRealy_all_closes() > 0) {
            throw new HttpException(411, 'Накладные ЗАМЕНЫ ОБОРУДОВАНИЯ ожидают закрытия. Закройте все автобусы ЗА СЕГОДНЯ', 5);
        }

        ///
        ///  День закрываем один раз в сутки.
        ///  УТОЧНИТЬ
        ///

        ///
        ///  Все записи в БУФЕРЕ за сегодня по этому АВТОБУСУ
        ///
        $model_mts_cahge = Mts_change::find()
            ->where(['AND',
                ['==', 'id_ap', (int)$this->_ap],
                ['==', 'id_pe', (int)$this->_pe],
                ['==', 'sklad', (int)$this->_sklad],
                ['==', 'close_day', (int)1]   // Позиции, которые ЗАКРЫТЫ
            ])
            ->orderBy('dt_create_timestamp ASC')
            ->asArray()
            ->all();

        //ddd($model_mts_cahge);

        //
        // Создаю два МАССИВА
        // 1. Чистые изделия
        // 2. Грязные изделия
        //
        $array_good = [];
        $array_bad = [];
        foreach ($model_mts_cahge as $item) {

            $array_good[] = $item['barcode_god'];
            $array_bad[] = $item['barcode_bad'];
        }

        // 1.       ddd($array_bad);

        // 2.       ddd($array_good);

        ///
        ///  Сегодня
        ///  Все накладные от Дежурного в ОФ
        ///
        /// Весь ОБОРОТНЫЙ ФОНД
        ///
        ///OBMEN FOND
        $array_mts_obmen_fond = self::mts_obmen_fond($this->_sklad);
        // ddd($array_mts_obmen_fond);

        //BAD
        //        0 => '040366'
        //    1 => '1510080316'

        //GOOD
        //        0 => '040424'
        //    1 => '040719'


        ///
        ///  ИСКЛЮЧИТЬ!!!....УСТАНОВЛЕННЫЕ (GOOD) БАРКОДЫ из Оборотного фонда
        ///
        $result = [];
        foreach ($array_mts_obmen_fond as $item) {
            if (!in_array($item['bar_code'], [''])) {              // Пустые БАРКОДЫ ИСКЛЮЧИТЬ
                if (!in_array($item['bar_code'], $array_good)) {  // БАРКОДЫ-ХОРОШИЕ (установленные из ОФ)  ИСКЛЮЧИТЬ

                    $result[] = $item;

                }
            }
        }

        //
        // БАРКОДЫ-ГРЯЗНЫЕ  (снятые на ремонт)  ДОБАВИТЬ временно
        //  ddd($array_bad);
        $array_full_bad = [];
        foreach ($array_bad as $item) {

            ///* Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его ИД)
            $full = Spr_globam_element::findFullArray_BY_barcode($item);
            $array_full_bad[] = [
                'wh_tk_amort' => (string)$full['top']['id'],
                'wh_tk_element' => (string)$full['child']['id'],
                'ed_izmer' => '1',
                'ed_izmer_num' => '1',
                'take_it' => '0',
                'bar_code' => (string)$item,
            ];

        }
        //ddd($array_full_bad);


        ///
        /// ГОТОВЫЙ МАССИВ Чистые приборы ОБОРОТНОГО ФОНДА + Грязные приборы СНЯТЫЕ ИЗ АВТОБУСОВ
        ///
        $result = array_merge($result, $array_full_bad);

        //   ddd($result);

        ////////////////////////
        /// РАСХОДНАЯ НАКЛАДНАЯ
        ///  С МТС на Дежурный склад
        /// =
        /// (временно  и чистая и грязная одновременно)
        ///
        /// СТАРТ
        ///
        $model = new Sklad();
        $model->id = Sklad::setNext_max_id();

        $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;
        $model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;
        /// Коммент - АКТ
        $model->tx = "Возврат ОФ от МТС";

        $model->wh_home_number = (int)$this->_sklad;
        $model->dt_create = date('d.m.Y H:i:s', strtotime('now '));
        $model->dt_create_timestamp = strtotime('now');

        ////  СОРТИРОВКА
        asort($result);

        /// ИСТОЧНИК
        $xx2 = Sprwhelement::findFullArray($this->_sklad);
        $model->wh_debet_name = $xx2['top']['name'];
        $model->wh_debet_element_name = $xx2['child']['name'];
        $model->wh_debet_top = (int)$xx2['top']['id'];
        $model->wh_debet_element = (int)$xx2['child']['id'];

        /// ПРИЕМНИК
        /// 4431  'Guidejet TI. Склад инженеров эксплуатации Дежурный'
        $wh_engineer = 4431; // 'Guidejet TI. Склад инженеров эксплуатации Дежурный'

        $xx2 = Sprwhelement::findFullArray($wh_engineer);
        $model->wh_destination_name = $xx2['top']['name'];
        $model->wh_destination_element_name = $xx2['child']['name'];
        $model->wh_destination = (int)$xx2['top']['id'];
        $model->wh_destination_element = (int)$xx2['child']['id'];

//        /// ДАЛЕЕ
//        $model->wh_dalee = (int)$xx2['top']['id'];
//        $model->wh_dalee_element = (int)$xx2['child']['id'];

//        /// Инициализируем Конечный СКЛАД
//        $model->wh_cs_number = (int)$xx2['child']['id'];
//        $model->wh_destination_element_cs = (int)$xx2['child']['id'];


        ///Всего строк в накладной
        $model->array_count_all = count($result);

        //////
        $model->array_tk_amort = $result;


        ///
        //        if (!$model->save(true)) {

        //        ddd($model);


        if (!$model->save(false)) {
            ddd($model->errors);
        } else {


            /// Адресат: Дежурный склад
            $for_wh_home_number = (int)4431;



            /// СРАЗУ ЖЕ ОТДАЕМ ЕЕ в БУФЕР передачи накладных
            $this->CopyToTransfer($model->_id, $for_wh_home_number);

            $this->setFlag_transfer_ok($model_mts_cahge);
//            ddd($eee);

        }

        //ddd($this->_name_ap);
        //return $this->redirect('/mobile/index_close_day');

        return $this->render('/mobile/index_close_day', [
            'alert' => 'ОК. День закрыт',
            'alert_str' => 'ОК. День закрыт',

            'sklad' => $this->_sklad,
            'name_ap' => $this->_name_ap,
            'name_pe' => $this->_name_pe
        ]);
    }

    /**
     * -
     * @param $array
     * @return bool
     */
    public function setFlag_transfer_ok($array)
    {
        foreach ($array as $item) {
            $model = Mts_change::findOne($item['_id']);
            $model->transfer_ok = (int)1;

            if (!$model->save(true)) {
                ddd($model);
                return false;
            }
        }

        return true;
    }


    /**
     * ПЕРЕДАЧА накладной в БУФЕР
     * =
     * по нажатию ЗЕЛЕНОЙ КНОПКИ-ОТПРАВКИ
     * -
     *
     * @param $id
     * @param $for_wh_home_number
     * @return string|Response
     * @throws ExitException
     * @throws HttpException
     */
    public function CopyToTransfer($id, $for_wh_home_number)
    {
        //ddd($id);
        $model = Sklad::findModel($id);

        ///* Проверяем
        if (!isset($model)) {
            throw new HttpException(411, 'Накладная не найдена для передачи в Буфер  ', 5);
        }

        ///////
        $model->dt_transfered_date = date('d.m.Y H:i:s', strtotime('now'));
        $model->dt_transfered_user_id = (integer)Yii::$app->getUser()->identity->id;
        $model->dt_transfered_user_name = Yii::$app->getUser()->identity->username;


        ///
        ///
        ///
        ///
        $model_transfer = new Sklad_transfer();
        $model_transfer->attributes = $model->attributes;

        $model_transfer->wh_home_number = (int)$for_wh_home_number;

        $model_transfer->dt_create_timestamp = strtotime('now');
        $model_transfer->dt_transfer_start_timestamp = strtotime('now');
        $model_transfer->tx = 'Возврат ОФ от МТС';

        //        ddd($model_transfer);

        //ddd($model_transfer);


        ///
        if (!$model_transfer->save(true)) {
            ddd($model_transfer->errors);
            return false;
        }

        if (!$model->save(true)) {
            ddd($model->errors);
            return false;
        }


        return true;
    }

    /**
     * Проверяем ВСЕ ЛИ закрыты сегодняшние ЗАМЕНЫ в буфере.
     * ===
     * -
     */
    public function actionRealy_all_closes()
    {
        ///
        ///  Все записи в БУФЕРЕ за сегодня по этому АВТОБУСУ
        ///
        $cou = Mts_change::find()
            ->where(['AND',
                ['==', 'id_ap', (int)$this->_ap],
                ['==', 'id_pe', (int)$this->_pe],
                ['==', 'sklad', (int)$this->_sklad],
                ['!=', 'close_day', (int)1]   // Позиции, которые НЕ ЗАКРЫТЫ!!!
            ])
            ->count();

        return $cou;
    }

    /**
     * По номеру ПРИХОДНОЙ накладной делать ее двойник, но РАСХОДНЫЙ.
     * =
     * Учетное Время - время текушей замены.
     *
     * @param $sklad_id
     * @return mixed
     * @throws \yii\base\ExitException
     * @throws \yii\web\NotFoundHttpException
     */
    public function Rashod_from_prihod($sklad_id)
    {
        ///
        $model_1 = Sklad::findModelDouble($sklad_id);
//        ddd($model_1);

        ///
        /// Монтаж в ЦС
        ///
        /// СТАРТ
        ///
        $model = new Sklad();
        $model->attributes = $model_1->attributes;

        $model->id = Sklad::setNext_max_id();
        $model->update_points = [];
        $model->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;
        $model->wh_home_number = (int)$this->_sklad;
        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
        $model->dt_create_timestamp = strtotime('now');

        /// Коммент
        $model->tx = "Дежурому Чистое";


        /// ИСТОЧНИК
        $xx2 = Sprwhelement::findFullArray($this->_sklad);
        $model->wh_debet_name = $xx2['top']['name'];
        $model->wh_debet_element_name = $xx2['child']['name'];
        $model->wh_debet_top = (int)$xx2['top']['id'];
        $model->wh_debet_element = (int)$xx2['child']['id'];

        /// ПРИЕМНИК
        $xx2 = Sprwhelement::findFullArray($this->_pe);
        $model->wh_destination_name = $xx2['top']['name'];
        $model->wh_destination_element_name = $xx2['child']['name'];
        $model->wh_destination = (int)$xx2['top']['id'];
        $model->wh_destination_element = (int)$xx2['child']['id'];

        /// ДАЛЕЕ
        $model->wh_dalee = (int)$xx2['top']['id'];
        $model->wh_dalee_element = (int)$xx2['child']['id'];
        unset($model->wh_dalee_name);
        unset($model->wh_dalee_element_name);

        /// Инициализируем Конечный СКЛАД
        $model->wh_cs_number = (int)$xx2['child']['id'];
        $model->wh_destination_element_cs = (int)$xx2['child']['id'];


        //        ddd($model);
        ///
        if (!$model->save(true)) {
            ddd($model->errors);
        }

        return $model->id;
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
     * Gallery
     * Галерея ФОТОК для одного автобуса
     * =
     * {@inheritdoc}
     */
    public
    function actionGallery()
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

//        ddd($dataProvider->getModels());

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
     * INDEX_tree
     * =
     * {@inheritdoc}
     */
    public
    function actionIndex_close_day()
    {
        //        ddd($this);
        //$sklad = Sklad::getSkladIdActive();

//        ddd($_SESSION);

        $sklad = $this->_sklad; /// Sklad


        if (!isset($sklad) || empty($sklad)) {
            $array_sklad_list = Yii::$app->getUser()->identity->sklad;  // * All SKLAD's

            if (!is_array($array_sklad_list)) {
                if (isset($array_sklad_list) && !empty($array_sklad_list) && Sklad::setSkladIdActive($array_sklad_list)) {
                    $sklad = Sklad::getSkladIdActive();
                }
            } else {
                foreach ($array_sklad_list as $item) {
                    $array [$item] = $item;
                }
                asort($array);
                $array_sklad_list = $array;
            }
        }

        $user_name = Yii::$app->getUser()->identity->username_for_signature;


        $ap = $this->_ap; /// Ap
        $pe = $this->_pe; /// Pe
        ///
        //        $ap = Sklad::getApIdActive();
        //        $pe = Sklad::getPeIdActive();

        $array_full = Sprwhelement::findFullArray($pe);

        $name_ap = $array_full['top']['name'];
        $name_pe = $array_full['child']['name'];
        $name_cs = $array_full['child']['cs'];
        //ddd($array_full);


        return $this->render('/mobile/index_close_day', [
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

}