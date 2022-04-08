<?php

namespace frontend\controllers;

use frontend\models\post_reflection;
use frontend\models\Reflection;
use frontend\models\Sklad;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;


/**
 *
 */
class ReflectionController extends Controller
{
    public $next_time;


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
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    // Главная страница
//                    'update' => [
//                        'GET',
//                        'POST',],
//                    // Редактирование НАКЛАДНОЙ
//                    //
//                    'index' => [
//                        'GET',
//                        'POST'
//                    ],
//                    'create' => [
//                    ],
//                    'delete' => [
//                    ],
//                    'view' => [
//                    ],
//                    //  'delete' => ['POST', 'DELETE'],
//                ],
//            ],
//        ];
//
//    }


    /**
     * INDEX
     * =
     * @return string
     */
    public function actionIndex()
    {
        //
        $para = Yii::$app->request->queryParams;
        //
        $para_group = Yii::$app->request->get('group');
        $para_print = Yii::$app->request->get('print');
//        $para_sort = Yii::$app->request->get('sort');


        //$post_rem_history = Yii::$app->request->get('post_rem_history');
        $post = Yii::$app->request->get();


        //        //С кнопки сброс фильтра
        //        $bar_code = Yii::$app->request->get('bar_code');
        //        if (isset($bar_code) && !empty($bar_code)) {
        //            $para['post_rem_history']['bar_code'] = $bar_code;
        //            $post_rem_history['bar_code'] = $bar_code;
        //        }


        ///
        if (!isset($para_print) || (int)$para_print == 0) {

            #PARA ALL (SORT etc)
            Sklad::setUnivers('para', $para);
        } else {

            /// PARA PRINT ALL
            $para = Yii::$app->request->get('para');
        }


        ///
        ///
        $searchModel = new post_reflection();


        //
        if (isset($para_group) && !empty($para_group)) {

            ///
            if ($para_group == 'tovar') {
                ddd($para_group);
            }


            //        $query = new Query();
            //        $query->select(['id', 'el'])
            //            ->from('reflection')
            //            ->limit(10);
            //        $rows = $query->all();

            $collection = Yii::$app->mongodb->getCollection('reflection');
            $model_group = $collection->aggregate([
                    //                [
                    //                    '$match' => ['el' => [8, 9, 10, 11, 12]],
                    //                ],

                    [
                        '$sort' => ['el' => 1]
                    ],

                    [
                        '$group' =>
                            [
                                //'_id' => array('el' => '$el')
                                '_id' => [
                                    'gr' => '$gr',
                                    'el' => '$el',
                                ],


                                'count' => ['$sum' => 1]
                            ],
                    ],

                    [
                        '$limit' => 20
                    ]
                ]
            );

        } else {
            $dataProvider = $searchModel->search_with_names($para);
        }


        $dataProvider->pagination = ['pageSize' => 10];

        //
        //ddd(self::ArrayFullHistory_by_Barcode("040066"));
        //ddd($para);

        ///
        $dataProvider->setSort(
            [
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC]
                    ], 'home_id',

                    'nnn_id' => [
                        'asc' => ['nnn_id' => SORT_ASC, 'id' => SORT_ASC],
                        'desc' => ['nnn_id' => SORT_DESC, 'id' => SORT_ASC]
                    ],

                    'gr',
                    'el',

                    'bc',

                    't_cr',
                    's',
                ],
                'defaultOrder' => ['id' => SORT_ASC]
            ]);


        //        ddd($searchModel);
        //        ddd($dataProvider->getModels());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);


        return '';
    }


    /**
     * Cоздаем ТАБЛИЦУ ЗНАНИЙ
     * =
     * @return string
     */
    public function actionLetsTable()
    {
        $date_end = strtotime('now');
        $date_start = strtotime('first day of january this year');
        //        $date_start = strtotime('first day of january last year');
        $id_pe = 86;

        /// Чистим Коллекцию перед работой
        $this->DropCollection();

        // LOADING....  Invenory WH
        $model_pillow = SvodController::actionRead_pillow_one((int)$id_pe, $date_end);

        ///
        $this->AddNextPos($id_pe, $model_pillow->id, $model_pillow->array_tk_amort, 1);
        //dd($count);        //ddd($this->CountCollection());

        //* Прихорд-расход массив по всем накладным
        self::ArrayPrihodRashod_for_reflection($id_pe, $date_start, $date_end);

        //$count = $count + self::ArrayPrihodRashod_for_reflection($id_pe, $date_start, $date_end);
        //dd($count);
        //   ddd(self::ArrayFullHistory_by_Barcode("040066"));

        return $this->redirect('/');

    }


    /**
     * Полная история по штрихкоду
     *
     * @param $barcode
     * @return array|int|\yii\mongodb\ActiveRecord
     */
    public
    static function ArrayFullHistory_by_Barcode($barcode)
    {
        return Reflection::find()
            ->where(['==', 'bc', $barcode])
            ->orderBy('t ASC')
            ->asArray()
            ->all();
    }


    /**
     * Прихорд-расход массив по всем накладным
     *
     * @param $pe_id
     * @param $date_start
     * @param $date_end
     * @return array|int|\yii\mongodb\ActiveRecord
     */
    public
    static function ArrayPrihodRashod_for_reflection($pe_id, $date_start, $date_end)
    {
        if (!isset($pe_id) || empty($pe_id)) {
            return -1;
        }

        /////////
        $array_moves_sklad = Sklad::find()
            ->select(
                [
                    'id',
                    'wh_home_number',
                    'sklad_vid_oper',
                    'dt_create',
                    'dt_create_timestamp',
                    'wh_debet_element',
                    'wh_destination_element',

                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',
                    //'array_tk_amort.intelligent', // есть не всегда
                    'array_tk_amort.bar_code',
                ]
            )
            ->where(
                [
                    'AND',
                    ['$gte', 'dt_create_timestamp', (int)$date_start],
                    ['$lte', 'dt_create_timestamp', (int)$date_end],
                    ['==', 'wh_home_number', (int)$pe_id],
                ]
            )
            ->orderBy('dt_create_timestamp ASC')
            ->asArray()
            ->all();


        ///////
        $count = 0;
        foreach ($array_moves_sklad as $item_model) {
            //dd($item_model);

            if (isset($item_model['array_tk_amort']))
                foreach ($item_model['array_tk_amort'] as $item) {
                    //ddd($item);
                    $model = new Reflection();
                    $model->id = Reflection::setNextMaxId();

                    $model->home_id = (int)$item_model['wh_home_number'];///
                    $model->nnn_id = (int)$item_model['id'];///

                    $model->gr = (int)$item['wh_tk_amort'];//
                    $model->el = (int)$item['wh_tk_element'];//
                    $model->bc = $item['bar_code'];//

                    if ((int)$item_model['sklad_vid_oper'] === 2) {
                        $model->s = (int)$item['ed_izmer_num'];///
                    } else {
                        $model->s = (int)-$item['ed_izmer_num'];///
                    }

                    $model->t = $item_model['dt_create_timestamp'];///
                    $model->t_cr = strtotime('now');
                    $model->t_in = strtotime('now');
                    $model->t_out = strtotime('now');

                    if ($model->save(true)) {
                        $count++;
                    }

                }
        }


        return $count;
    }


    /**
     *
     */
    public function DropCollection()
    {
        $collection = Yii::$app->mongodb->getCollection('reflection');
        $collection->remove();
    }

    /**
     *
     */
    public static function CountCollection()
    {
        $collection = Yii::$app->mongodb->getCollection('reflection');
        return $collection->count();
    }

    /**
     * @param $pe_id
     * @param $nnn_id
     * @param $array_thinks
     * @param int $vid_oper
     * @return string
     */
    public
    function AddNextPos($pe_id, $nnn_id, $array_thinks, $vid_oper = 1)
    {
        if (!isset($pe_id) || empty($pe_id)) {
            return -1;
        }

        $count = 0;

        foreach ($array_thinks as $item_model) {
            ///
            ///   'wh_tk_amort' => 7
            //        'wh_tk_element' => 9
            //        'ed_izmer' => 1
            //        'ed_izmer_num' => 1
            //        'bar_code' => '210100039514'
            //        'intelligent' => 1
            //        'name_wh_tk_amort' => 'АСУОП'
            //        'name_wh_tk_element' => 'Стаб. VSP01'
            //        'name_ed_izmer' => 'шт.'
            //        't' => 1611298815
            //        'id' => 15

            $model = new Reflection();
            $model->id = Reflection::setNextMaxId();

            $model->home_id = (int)$pe_id;
            $model->nnn_id = 1;
            $model->t = (int)$item_model['t'];

            $model->gr = (int)$item_model['wh_tk_amort'];
            $model->el = (int)$item_model['wh_tk_element'];
            $model->s = $item_model['ed_izmer_num'];
            $model->bc = $item_model['bar_code'];

            $model->t = strtotime('now');

            $model->t_cr = strtotime('now');
            $model->t_in = strtotime('now');
            $model->t_out = strtotime('now');

            if ($model->save(true)) {
                $count++;
            }

        }

        return $count;
    }


}

