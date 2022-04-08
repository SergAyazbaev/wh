<?php

namespace frontend\controllers;

use frontend\models\Barcode_pool;
use frontend\models\post_spr_glob_element;
use frontend\models\post_spr_globam_element;
use frontend\models\Sklad_wh_invent;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Sklad;
use frontend\models\VirtFilter;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\UnauthorizedHttpException;


class Stat_ostatkiController extends Controller
{
    public $sklad;


    /**
     * Если не авторизован, то нет доступа
     *
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


    ////////////

    /**
     * ОТСТАТКИ ПО СКЛАДУ
     * ПЕРВАЯ СТРАНИЦА, где выбираем ГРУППУ ЭЛЕМЕНТОВ
     *
     * @return string
     * @var 123
     *
     */
    public function actionIndex()
    {
        $searchModel = new post_spr_globam_element();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchModel2 = new post_spr_glob_element();
        $dataProvider2 = $searchModel2->search(Yii::$app->request->queryParams);

        //ddd(111);

        return $this->render(
            'index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

            'searchModel2' => $searchModel2,
            'dataProvider2' => $dataProvider2,
        ]);
    }


    /**
     * ПО ДВУМ ВИДАМ (АМОРТИЗАЦИЯ(1) И СПИСАНИЕ(2))
     *
     * @return string
     * @throws UnauthorizedHttpException
     */
    public function actionOst_element_one()
    {
        $para = Yii::$app->request->queryParams;
        // ddd($para);

        if (!isset($para['tabl']) || empty($para['tabl'])) {
            throw new UnauthorizedHttpException('Необходимо значение "tabl" ');
        }
        $tabl = $para['tabl'];

        if (!isset($para['id']) || empty($para['id'])) {
            throw new UnauthorizedHttpException('Необходим Id');
        }
        $element_id = (int)$para['id'];


//        if (isset($para['bar']) && !empty($para['bar'])) {
//            $bar_code = $para['bar'];
//        } else {
//            $bar_code = '';
//        }


        $array_select = [
            'id',
            'wh_home_number',

            'wh_debet',
            'wh_debet_name',
            'wh_debet_element',
            'wh_debet_element_name',
            'wh_destination',
            'wh_destination_name',
            'wh_destination_element',
            'wh_destination_element_name',

            'array_tk_amort',
            //                    'array_tk',
            //                    'array_casual',
            //                    'array_bus',
            //                    'array_count_all',
        ];

        //ddd( $element_id);
        $model_sklad = Sklad::Svod_globalam_element($element_id, $array_select);

//        ddd($model_sklad);


        // Возвращает СВОДНЫЙ ОТЧЕТ - массив (Old Variant)
//        $model_sklad = Sklad::Svod_globalam_element_barcode(
//            $element_id,
//            //$bar_code,
//            $array_select
//        );


        //        // Возвращает СВОДНЫЙ ОТЧЕТ - массив (New)
        //        $model_sklad=Sklad::Svod_globalam_element_barcode(
        //            $element_id,
        //            $bar_code,
        //            $array_select
        //        );

        //        ddd($model_sklad);


        $provider = new ArrayDataProvider(
            [
                'allModels' => $model_sklad,

                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [

                    'attributes' => [
                        'id',
                        'sum_id',
                        '##',
                        'ed_izmer',
                        'ed_izmer_num',
                        'bar_code',

                        'nakladnaya',

                        'wh_home_number',

                        'wh_debet',
                        'wh_debet_name',
                        'wh_debet_element',
                        'wh_debet_element_name',

                        'wh_destination',
                        'wh_destination_name',
                        'wh_destination_element',
                        'wh_destination_element_name',

                    ],
                ],
            ]);


        $provider->setSort(
            [
                'attributes' => [
                    'nakladnaya' => [
                        'asc' => ['nakladnaya' => SORT_ASC],
                    ],
                    'bar_code' => [SORT_ASC],
                    'wh_debet_element' => [SORT_ASC],
                    'wh_debet_element_name' => [SORT_ASC],
                    'wh_destination_element' => [SORT_ASC],
                    'wh_destination_element_name' => [SORT_ASC],
                    'sum_id' => [SORT_ASC],
                ],
            ]);


        if ($tabl == 1) {
            $element_name = Spr_globam_element::find()
                ->where(['id' => (int)$element_id])
                ->asArray()
                ->one();
        }
        if ($tabl == 2) {
            $element_name = Spr_glob_element::find()
                ->where(['id' => (int)$element_id])
                ->asArray()
                ->one();
        }


        // Поле для формы
        $model_text = Barcode_pool::find()->one();

        // Поиск автопоиск
        $pool = ArrayHelper::getColumn(
            Barcode_pool::find()
                ->select(['bar_code'])
                ->asArray()
                ->all(), 'bar_code');


        //        ddd($element_name);
        //        ddd($provider);

        return $this->render(
            'stat_forms/stat_dvizh', [
            'provider' => $provider,
            'model' => $model_sklad,

            'element_id' => $element_id,
            'element_name' => $element_name['name'],

            'model_text' => $model_text,
            'pool' => $pool,

        ]);
    }


    /**
     * Статистика
     * =
     * @return string
     * @throws HttpException
     */
    public function actionOst_element_one_sum()
    {
        //$para = Yii::$app->request->queryParams;
        $para = Yii::$app->request->get();

        //     ddd($para);
        //     'Barcode_pool' => [
        //     'find_name' => '040430'


        ///
        if (isset($para['Barcode_pool']['find_name']) && !empty($para['Barcode_pool']['find_name'])) {
            $tabl = 3;
            //$para = Yii::$app->request->queryParams;
            //
            $bar_code = $para['Barcode_pool']['find_name'];
            // Возвращает СВОДНЫЙ ОТЧЕТ - ONE
            $model_sklad = Sklad::Svod_one_barcode($bar_code);

//            ddd($bar_code);
//            ddd($model_sklad);


        } else {

            if (!isset($para['tabl']) || empty($para['tabl'])) {
                throw new HttpException(411, 'Необходимо значение "tabl" ', 0);
            }
            $tabl = $para['tabl'];

            if (!isset($para['id']) || empty($para['id'])) {
                throw new HttpException(411, 'Необходим Id ', 0);
            }
            $element_id = (int)$para['id'];

            // Возвращает СВОДНЫЙ ОТЧЕТ - массив (New)
            $model_sklad = Sklad::Svod_globalam_element_barcode(
                $element_id
            );

        }

//        ddd($model_sklad);


        $provider = new ArrayDataProvider(
            [
                'allModels' => $model_sklad,
                'pagination' => [
                    'pageSize' => 10,
                ],

            ]);

        $provider->setSort(
            [
                'defaultOrder' => ['sum_id' => SORT_DESC],
                'attributes' => [
                    'sum_id' => [
                        'asc' => ['sum_id' => SORT_ASC],
                        'desc' => ['sum_id' => SORT_DESC],
                    ],
                ],
            ]);

        //ddd($provider);


        if ($tabl == 1) {
            $element_name = Spr_globam_element::find()
                ->where(['id' => (int)$element_id])
                ->asArray()
                ->one();
        }

        if ($tabl == 2) {
            $element_name = Spr_glob_element::find()
                ->where(['id' => (int)$element_id])
                ->asArray()
                ->one();
        }
        if ($tabl == 3) {
            $element_name['name'] = '';
            $element_id = 0;
        }


        //        ddd($element_name);
        //        ddd($provider);

        // Поле для формы
        $model_text = Barcode_pool::find()->one();

        // Поиск автопоиск
        $pool = ArrayHelper::getColumn(
            Barcode_pool::find()
                ->select(['bar_code'])
                ->asArray()
                ->all(), 'bar_code');


        return $this->render(
            'stat_forms/stat_dvizh_sum', [
            'provider' => $provider,
            'model' => $model_sklad,

            'element_id' => $element_id,
            'element_name' => $element_name['name'],

            'model_text' => $model_text,
            'pool' => $pool,

        ]);

    }

    /**
     * Статистика. КРУГОВОРОТ приборов в природе
     * =
     * @return string
     * @throws HttpException
     */
    public function actionFast_turnover()
    {
        $para = Yii::$app->request->get();

        ///
        if (isset($para['Barcode_pool']['find_name']) && !empty($para['Barcode_pool']['find_name'])) {

            $bar_code = $para['Barcode_pool']['find_name'];
            // Возвращает СВОДНЫЙ ОТЧЕТ - ONE
            $model_sklad = Sklad::Svod_one_barcode($bar_code);

        } else {

            if (!isset($para['id']) || empty($para['id'])) {
                throw new HttpException(411, 'Необходим Id ', 0);
            }
            $element_id = (int)$para['id'];

            // Возвращает СВОДНЫЙ ОТЧЕТ - массив (New)
            $model_sklad = Sklad::Fast_turnover(
                $element_id
            );

        }


        $provider = new ArrayDataProvider(
            [
                'allModels' => $model_sklad,
                'pagination' => [
                    'pageSize' => 10,
                ],

            ]);

        $provider->setSort(
            [
                'defaultOrder' => ['sum_id' => SORT_DESC],
                'attributes' => [
                    'sum_id' => [
                        'asc' => ['sum_id' => SORT_ASC],
                        'desc' => ['sum_id' => SORT_DESC],
                    ],
                ],
            ]);


        ///
        $element_name = Spr_globam_element::find()
            ->where(['id' => (int)$element_id])
            ->asArray()
            ->one();


        // Поле для формы
        $model_text = Barcode_pool::find()->one();

        // Поиск автопоиск
        $pool = ArrayHelper::getColumn(
            Barcode_pool::find()
                ->select(['bar_code'])
                ->asArray()
                ->all(), 'bar_code');


        return $this->render(
            'stat_forms/stat_dvizh_sum', [
            'provider' => $provider,
            'model' => $model_sklad,

            'element_id' => $element_id,
            'element_name' => $element_name['name'],

            'model_text' => $model_text,
            'pool' => $pool,

        ]);

    }


    /**
     * @return bool
     */
    public function actionOst_element_one_sum_print()
    {
        $array_select = [
            'id',
            'wh_home_number',
            'array_tk_amort.bar_code',
            'array_tk_amort.wh_tk_amort',
            'array_tk_amort.wh_tk_element',
        ];

        // Возвращает СВОДНЫЙ ОТЧЕТ - массив (New)
        $model_sklad = Sklad::Svod_globalam_element_barcode_print($array_select);

        //arsort($model_sklad);
        //array_multisort($model_sklad, SORT_ASC, SORT_STRING);
        //        array_multisort($ar[0], SORT_ASC, SORT_STRING,
        //            $ar[1], SORT_NUMERIC, SORT_DESC);
        //        natcasesort($model_sklad );

        //        ddd($model_sklad);


        // Spravka Group
        $group_name = ArrayHelper::map(
            Spr_globam::find()
                ->asArray()
                ->all(), 'id', 'name');

        // Spravka Element
        $element_name = ArrayHelper::map(
            Spr_globam_element::find()
                ->asArray()
                ->all(), 'id', 'name');


        $this->render(
            'print/print_excel', [
            'model_sklad' => $model_sklad,

            'group_name' => $group_name,
            'element_name' => $element_name,
        ]);


        return false;
    }

    /**
     * Поиск По ШТРИХКОДУ с выводом в ТАБЛИЦУ
     * -
     *
     * @return string
     */
    public function actionBarcode_to_naklad()
    {
        $para = Yii::$app->request->queryParams;

        //ddd($para);
        //ddd($para['bar']);

        $bar_code = '';

        if (isset($para['Barcode_pool']['find_name'])) {
            $bar_code = $para['Barcode_pool']['find_name'];
        }


        if (isset($para['bar']) && !empty($para['bar'])) {
            $bar_code = $para['bar'];
        }

        //
        // Очистка от букв. Оставляем только цифры
        //
//        $bar_code = MyHelpers::barcode_normalise($bar_code);


        //        ddd($bar_code);

        $array_select = [
            'id',
            'wh_home_number',
            'sklad_vid_oper_name',
            'user_name',
            'dt_create_timestamp',

            'wh_debet_name',
            'wh_debet_element_name',
            'wh_destination_name',
            'wh_destination_element_name',
            'tx',

        ];


        /////////Возвращает СВОДНЫЙ ОТЧЕТ - массив
        $model_sklad = Sklad::BarCode_to_Nakladnye_simpleVariant(
            $bar_code,
            $array_select
        );

//
//        $array_select = [
//            'id',
//            'wh_home_number',
//            ];
//
//        // ЦС
//        $model_sc = Sklad_inventory_cs::BarCode_to_Nakladnye(
//            $bar_code,
//            $array_select
//        );
//        ddd($model_sc);


        $provider = new ArrayDataProvider(
            [
                'allModels' => $model_sklad,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);


        $provider->setSort(
            [
                'attributes' => [
                    'id' => [SORT_ASC],
                    'bar_code' => [SORT_ASC],
                    'user_id' => [SORT_ASC],
                    'user_name' => [SORT_ASC],
                    'dt_update' => [SORT_ASC],
                    'dt_create' => [SORT_ASC],
                    'dt_create_timestamp' => [SORT_ASC],
                    'tx' => [SORT_ASC],
                ],
//                'defaultOrder' => ['dt_create_timestamp' => SORT_ASC],
            ]);


        // Поле для формы
        $model_text = Barcode_pool::find()->one();

        // Поиск автопоиск
        $pool = ArrayHelper::getColumn(
            Barcode_pool::find()
                ->select(['bar_code'])
                ->asArray()
                ->all(), 'bar_code');


        ///
        return $this->render(
            'stat_forms/stat_dvizh_barcode', [
            'provider' => $provider,
            'model' => $model_sklad,
            'pool' => $pool,
            'model_text' => $model_text,

            'bar_code' => $bar_code,


        ]);

    }

    /**
     * Аналитика!  Поиск По ШТРИХКОДУ с выводом в ТАБЛИЦУ!!!!!!
     * -
     *
     * @return string
     */
    public function actionBarcode_to_naklad_analitics()
    {

        ///
        $virt_filter = new VirtFilter();
        $bar_code = '';
        $filter_dest = [];
        $array_ids_dest = [];
        $array_ids_sender = [];


        ///
        ///
        if ($virt_filter->load(Yii::$app->request->get())) {

//            $para = Yii::$app->request->get();
//            ddd($para);

            //
            $para_VirtFilter = Yii::$app->request->get('VirtFilter');
            //
            $para_sort = Yii::$app->request->get('sort');  //'sort' => '-dt_create_timestamp'

            //bar_code_str
            if (isset($para_VirtFilter['bar_code_str'])) {
                $bar_code = $para_VirtFilter['bar_code_str'];
                $virt_filter->bar_code_str = $para_VirtFilter['bar_code_str'];
            }

            //Массив ИДС SENDERs
            if (isset($para_VirtFilter['id_sender']) && !empty($para_VirtFilter['id_sender'])) {
                $array_ids_sender = $para_VirtFilter['id_sender'];
                $virt_filter->id_sender = $array_ids_sender;
            }

            //Массив ИДС DESTENYs
            if (isset($para_VirtFilter['id_dest']) && !empty($para_VirtFilter['id_dest'])) {
                $array_ids_dest = $para_VirtFilter['id_dest'];
                $virt_filter->id_dest = $array_ids_dest;
            }

            $filter_dest = Sklad::BarCode_to_Nakladnye_filtr($bar_code);

        }


        $array_select = [
            'id',
            'wh_home_number',

            'sklad_vid_oper',
            'sklad_vid_oper_name',
            'user_name',
            'dt_create_timestamp',

            'wh_debet_name',
            'wh_debet_element',
            'wh_debet_element_name',
            'wh_destination',
            'wh_destination_name',
            'wh_destination_element',
            'wh_destination_element_name',
            'tx',
        ];

        //
        // Возвращает массив
        //
        // СВОДНЫЙ ОТЧЕТ
        //
        // Шаг 1. Запрос простой цепочки
        $model_sklad = Sklad::BarCode_to_Nakladnye(
            $bar_code,
            $array_select,
            $array_ids_sender,
            $array_ids_dest
        );

        //
        $inventory = Sklad_wh_invent::BarCode_to_Nakladnye($bar_code, $array_select);
        foreach ($inventory as $key => $item) {
            $inventory[$key]['sklad_vid_oper'] = '1';
            $inventory[$key]['wh_debet_name'] = '-';
            $inventory[$key]['wh_debet_element_name'] = '-';
            $inventory[$key]['tx'] = 'Инвентаризация';
        }

        //ddd($inventory);

        //
        $model_sklad = array_merge($model_sklad, $inventory);

//        ddd($inventory);
//        ddd($model_sklad);


        //
        $array_rezult = [];

        $wh_home_number_buf = '';
        $debet_buf = '';
        $destination_buf = '';
        $xx_key = 0;

        // Логика для РАСКРАСКИ
        foreach ($model_sklad as $item_string) {
            $xx_key++;
            $array_rezult[$xx_key] = $item_string;


            if ($debet_buf != '' && $destination_buf != '') {
                $array_rezult[$xx_key] = $item_string;

                if ($wh_home_number_buf == (int)$item_string['wh_home_number']) {


                    if ($debet_buf == (int)$item_string['wh_debet_element']) {
                        $array_rezult[$xx_key]['update_user_name'] = 'errors2';
                    }

                    if ($destination_buf == (int)$item_string['wh_destination_element']) {
                        $array_rezult[$xx_key]['update_user_name'] = 'errors2';
                    }

                    if ($destination_buf != (int)$item_string['wh_debet_element']) {
                        $array_rezult[$xx_key]['update_user_name'] = 'errors';
                    }


                }

                if ($wh_home_number_buf != (int)$item_string['wh_home_number']) {

                    if (isset($item_string['wh_debet_element'])) {

                        if ($debet_buf != (int)$item_string['wh_debet_element']) {
                            $array_rezult[$xx_key]['update_user_name'] = 'errors2';
                        }


                        if ($destination_buf == (int)$item_string['wh_debet_element']) {
                            $array_rezult[$xx_key]['update_user_name'] = 'errors';
                        }

                    }


                }

            }


            if (isset($item_string['wh_debet_element'])) {
                // Сохраняем Источник и Приемник. Дату не сохраняем. Она - ключ сортировки.
                $debet_buf = (int)$item_string['wh_debet_element'];
            }
            $destination_buf = (int)$item_string['wh_destination_element'];
            // Сохраняем home.
            $wh_home_number_buf = (int)$item_string['wh_home_number'];

        }

        //        ddd($array_rezult);
        // 9327


        $model_sklad = $array_rezult;


        $provider = new ArrayDataProvider(
            [
                'allModels' => $model_sklad,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);


        $provider->setSort(
            [
                'attributes' => [
                    'id',
                    'dt_create_timestamp'
                ],
                'defaultOrder' => ['dt_create_timestamp' => SORT_ASC],
            ]);


//        // Поле для формы
//        $model_text = Barcode_pool::find()->one();
//        //
//        $virt_filter = new VirtFilter();

        //ddd($virt_filter);

        // Поиск автопоиск
        $pool = ArrayHelper::getColumn(Barcode_pool::find()
            ->select(['bar_code'])
            ->asArray()
            ->all(), 'bar_code');

        //
        // Поле Фильтра
        $filter = Barcode_pool::find()->one();


        //        ddd($provider->getModels());
        return $this->render(
            'stat_forms/barcode_analitics', [
            'provider' => $provider,
            'model' => $model_sklad,
            'pool' => $pool,

//            'model_text' => $model_text,
//            'bar_code' => $bar_code,

            'virt_filter' => $virt_filter,
            'filter_dest' => $filter_dest,


        ]);

    }

    /**
     *
     * */
    public function actionAutocompletefind()
    {
        $pool = ArrayHelper::getColumn(
            Barcode_pool::find()
                ->select(['bar_code'])
                ->all(), 'bar_code');

        $str = implode(', ', $pool);
        return $str;
    }

    public function actionCreate()
    {
        dd(123);

        $session = Yii::$app->session;
        $sklad = $session->get('sklad_');
        //dd($sklad);


        $max_value = Sklad::find()->max('id');;
        $max_value++;


        $new_doc = new Sklad();     // Новая накладная

        $new_doc->id = (integer)$max_value;

        $new_doc->wh_home_number = (int)$sklad;
        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

        $new_doc->user_id = (int)Yii::$app->getUser()->identity->id;
        $new_doc->user_name = Yii::$app->getUser()->identity->username;
        date_default_timezone_set("Asia/Almaty");
        $new_doc->dt_update = date('d.m.Y H:i:s', strtotime('now'));

        $new_doc->array_tk_amort = [];
        $new_doc->array_tk = [];


        if ($new_doc->load(Yii::$app->request->post())) {

            if ($new_doc->save(true)) {


                return $this->redirect('/sklad/index?sort=-id&sklad=' . $sklad);

            } else {
                //dd($model);
                return $this->redirect('/');
            }
        }


        return $this->render(
            'sklad_in/_form', [
            'new_doc' => $new_doc,
            'sklad' => $sklad,
        ]);
    }

//    public function actionDelete($id)
//    {
//        return $this->findModel($id)->delete();
//    }


}
