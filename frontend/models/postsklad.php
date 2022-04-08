<?php

namespace frontend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;


class postsklad extends Sklad
{
    public $countryName;
    public $sprwhName, $sprwhTopName;
    public $postsklad, $sklad_sort, $otbor;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    '_id',
                    'id',

                    'sklad_vid_oper',
                    'wh_home_number',
                    // ид текущего склада

                    'wh_cs_number',
                    //						'wh_cs_parent_name',

                    //						'sprwhelement_wh_cs_number.sprwhtop.id',

                    'sprwhtop_destination.id',
                    'sprwhtop_destination',

                    //						'wh_cs_parent_number',
                    //						'wh_cs_parent_name',


                    'wh_debet_top',
                    'wh_debet_name',
                    //						'wh_debet_element',
                    'wh_debet_element_name',

                    'wh_destination',
                    'wh_destination_name',
                    //						'wh_destination_element',
                    'wh_destination_element_name',

                    'wh_dalee',
                    'wh_dalee_element',


                    'dt_create',
                    'dt_update',
                    'dt_start',
                    'dt_one_day',

//                    'dt_create_timestamp',

                    'update_user_name',

                    "tx",

                    'user_name',
                    //                    'wh_cs_parent_name',

                    'sprwhName',
                    'sprwhTopName',

                    'sprwhelement_wh_cs_number.name',
                    'sprwhTopName.name',


                ],
                'safe',
            ],
        ];
    }


    /**
     * @return array
     */


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws \yii\web\HttpException
     */
    public function search($params)
    {
        //ddd($params);

        //
        // Восстанавливаю
        // Сортировку и фильтр из Сессии
        // 1
        if (isset($params['post_filter']) && $params['post_filter'] == 1) {
            $params['postsklad'] = self::getSession_filter('postsklad');
            //            $params['sklad-sort'] = self::getSession_filter('sort');
            $params['sklad-page'] = self::getSession_filter('page');
            $params['per-page'] = self::getSession_filter('per');


        } else {

            //
            // Сохраняю в Сессию ВСЕ фильтры
            // 1
            if (isset($params['sklad-sort']) && !empty($params['sklad-sort'])) {
                self::setSession_filter('sort', $params['sklad-sort']);
            }
            if (isset($params['sklad-page']) && !empty($params['sklad-page'])) {
                self::setSession_filter('page', $params['sklad-page']);
            }
            if (isset($params['per-page']) && !empty($params['per-page'])) {
                self::setSession_filter('per', $params['per-page']);
            }

            if (isset($params['postsklad']) && !empty($params['postsklad'])) {
                self::setSession_filter('postsklad', $params['postsklad']);
            }


        }
//        ddd($params);

        ////
        $this->load($params);


//        ddd($params);
        //ddd($session_sort);


        $query = Sklad::find()->with(
            [
                'sprwhelement_debet_element',
                'sprwhelement_destination_element',
            ]);

        //            ->indexBy('id') /// )))))


        //ddd($query);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            return $dataProvider;
        }


//        if (isset($params['otbor'])) {
//            $query->where(['=', 'wh_home_number', (integer)$params['otbor']]);
//        }

        $sklad = Sklad::getSkladIdActive();

        if (isset($sklad)) {
            $query->where(['=', 'wh_home_number', (int)$sklad]);
        }


        // Дата фильтр dt_create
        if (isset($params['postsklad']['dt_start']) && !empty($params['postsklad']['dt_start'])) {
            $query->andFilterWhere(['like', 'dt_create', $params['postsklad']['dt_start']]);
        }

        // Дата фильтр dt_create
        if (isset($params['dt_start']) && !empty($params['dt_start'])) {
            $query->andFilterWhere(['like', 'dt_create_timestamp', $params['dt_start']]);
        }

        if (isset($this->dt_start) && !empty($this->dt_start)) {
            $query->andFilterWhere(['like', 'dt_create', $this->dt_start]);
        }

        ///
        if (isset($params['dt_create']) && !empty($params['dt_create'])) {
            $query->andFilterWhere(['like', 'dt_create_timestamp', strtotime($params['dt_create'])]);
        }

        //
        if (isset($params['update_user_name']) && !empty($params['update_user_name'])) {
            $query->andFilterWhere(['like', 'update_user_name', strtotime($params['update_user_name'])]);
        }

        if ((int)$this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }

        if ((int)$this->sklad_vid_oper > 0) {
            $query->andFilterWhere(
                [
                    'OR',
                    ['=', 'sklad_vid_oper', (int)$this->sklad_vid_oper],
                    ['like', 'sklad_vid_oper', (string)$this->sklad_vid_oper],
                ]);
        }


        $query
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'tx', $this->tx])
            ->andFilterWhere(['like', 'wh_debet_element_name', $this->wh_debet_element_name])
            ->andFilterWhere(['like', 'wh_destination_element_name', $this->wh_destination_element_name]);


        return $dataProvider;
    }


    /**
     * Считываю из Сессии ЛЮБЫЕ фильтры. (Указать название)
     * =
     *
     * @param $name_filter
     * @return mixed
     */
    static function getSession_filter($name_filter)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }

        return $session->get($name_filter);
    }


    /**
     * Сохраняю в Сессию ЛЮБЫЕ фильтры. (Указать название)
     * =
     *
     * @param $name_filter
     * @param $filter
     * @return bool
     */
    static function setSession_filter($name_filter, $filter)
    {
        $session = Yii::$app->session;
        try {
            if (!$session->isActive) {
                $session->open();
            }
            $session->set($name_filter, $filter);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search_cs($params)
    {
        $this->load($params);


        //
        // Жадная загрузка со связями
        //

        $query = Sklad::find()->with(
            [
                'sprwhelement_debet',
                'sprwhelement_debet_element',
                'sprwhelement_destination',
                'sprwhelement_destination_element',
            ])//            ->indexBy('id') /// )))))
        ;

        //ddd($query);


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);

        $dataProvider->setSort(
            [
                'defaultOrder' => ['id' => SORT_DESC],
            ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        if (isset($params['otbor'])) {
            $query->where(
                [
                    '=',
                    'wh_home_number',
                    (integer)$params['otbor'],
                ]);
        }

        //ddd($params);
        //ddd($this->dt_start);


        // Дата фильтр dt_create
        if (isset($params['postsklad']['dt_start']) && !empty($params['postsklad']['dt_start'])) {
            //ddd($params);
            $query->andFilterWhere(
                [
                    'like',
                    'dt_create',
                    $params['postsklad']['dt_start'],
                ]);
        }
        // Дата фильтр dt_create
        if (isset($params['dt_start']) && !empty($params['dt_start'])) {
            //ddd($params);
            $query->andFilterWhere(
                [
                    'like',
                    'dt_create',
                    $params['dt_start'],
                ]);
        }
        if (isset($params['dt_create']) && !empty($params['dt_create'])) {
            $query->andFilterWhere(
                [
                    'like',
                    'dt_create',
                    $params['dt_create'],
                ]);
        }


        if ((int)$this->id > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'id',
                    (int)$this->id,
                ]);
        }

        if ((int)$this->sklad_vid_oper > 0) {
            $query->andFilterWhere(
                [
                    'OR',
                    [
                        '=',
                        'sklad_vid_oper',
                        (int)$this->sklad_vid_oper,
                    ],
                    [
                        'like',
                        'sklad_vid_oper',
                        (string)$this->sklad_vid_oper,
                    ],
                ]);
        }

        return $dataProvider;
    }


    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search_into_wh($params)
    {
        $query = Sklad_transfer::find();
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                //                'pagination' => ['pageSize' =>3],
            ]);


        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->where(
            [
                '=',
                'wh_destination_element',
                $params['otbor'],
            ]);


        return $dataProvider;
    }


    /**
     * Сортировка и фильтрация для создания
     * СВОДНОЙ ТАБЛИЦЫ (ПРОСТОЙ МАССИВ)
     * -
     *
     * @param $para
     *
     * @return ArrayDataProvider
     */
    public function search_into_svod($para)
    {
        $this->load($para);


        ////////////
        $array_select = [
            'id',
            'wh_home_number',

            'dt_create',
            'sklad_vid_oper',
            'wh_debet',
            'wh_debet_name',
            'wh_debet_element',
            'wh_debet_element_name',
            'wh_destination',
            'wh_destination_name',
            'wh_destination_element',
            'wh_destination_element_name',

            'array_tk_amort',

        ];

        ///////////////
        ///
        // Все Ключи складов в этой ГРУППЕ СКЛАДОВ
        $array_group_sklad = [];
        if (isset($para['postsklad']['wh_top']) &&
            !empty($para['postsklad']['wh_top'])
        ) {
            $ap = $para['postsklad']['wh_top'];
            $array_group_sklad = Sprwhelement::findAll_keys_from_parent($ap);
            //ddd($para_post);
        }
        /////////
        if (isset($para['postsklad']['wh_element']) &&
            !empty($para['postsklad']['wh_element'])
        ) {
            $array_group_sklad = [
                $para['postsklad']['wh_element'],
            ];

        }

        $model_sklad = Sklad::find()
            ->select($array_select)
            ->where(['wh_home_number' => $array_group_sklad])
            ->asArray()
            ->all();


        $provider = new ArrayDataProvider(
            [
                'allModels' => $model_sklad,
                //// her is $query
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

        unset($model_sklad);

        $provider->setSort(
            [
                'attributes' => [
                    'wh_tk_amort' => [
                        'asc' => ['wh_tk_amort' => SORT_ASC],
                        'desc' => ['wh_tk_amort' => SORT_DESC],
                    ],

                    'wh_tk_element' => [
                        'asc' => ['wh_tk_element' => SORT_ASC,],
                        'desc' => ['wh_tk_element' => SORT_DESC,],
                    ],

                    'wh_home_number' => [SORT_ASC],
                    'wh_debet_element' => [SORT_ASC],
                    'wh_debet_element_name' => [SORT_ASC],
                    'wh_debet_name' => [SORT_ASC],
                    'wh_destination' => [SORT_ASC],
                    'wh_destination_name' => [SORT_ASC],
                    'wh_destination_element' => [SORT_ASC],
                    'wh_destination_element_name' => [SORT_ASC],
                    'ed_izmer' => [SORT_ASC],
                    'ed_izmer_num' => [SORT_ASC],
                    'bar_code' => [SORT_ASC],
                    'dt_create' => [SORT_ASC],
                    'naklad' => [SORT_ASC],
                    'id' => [SORT_ASC],
                    'vid_oper' => [SORT_ASC],

                ],
            ]);


        return $provider;
    }


    /**
     * ПОИСК для ПРОСТЫХ ЗАГОЛОВКОВ  без тел-Накладных
     * =
     *
     * @param       $params
     * @param array $filter_for
     *
     * @return ActiveDataProvider
     */
    public function search_svod($params, $filter_for = [])
    {
        $this->load($params);

        //ddd($this);


        $query = self::find()
            ->with('sprwhelement_home_number');


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            return $dataProvider;
        }

//        if (empty($this->dt_start)) {
//            $this->dt_start = date("d.m.Y 00:00:00", strtotime("now -1 month "));
//        }


        // Дата фильтр
        if ((int)$this->wh_home_number > 0) {
            $query->andFilterWhere(['=', 'wh_home_number', (int)$this->wh_home_number]);
        }

            // Дата фильтр
        if (isset($this->dt_start) && !empty($this->dt_start)) {
            $query->andFilterWhere([
                '>=', 'dt_create_timestamp', strtotime(date("d.m.Y 00:00:00", strtotime($this->dt_start)))
            ]);
        }




        if (isset($this->dt_stop) && !empty($this->dt_stop)) {
            $query->andFilterWhere(
                [
                    '<=',
                    'dt_create_timestamp',
//                    strtotime(date("d.m.Y 23:59:59", strtotime($this->dt_stop))),
                    strtotime($this->dt_stop),
                ]);
        }


//        ddd($this);


        // Дата фильтр
        if (isset($this['sklad_vid_oper']) && !empty($this['sklad_vid_oper'])) {
            $query->andFilterWhere(
                [
                    'OR',
                    ['=', 'sklad_vid_oper', (string)$this->sklad_vid_oper,],
                    ['=', 'sklad_vid_oper', (int)$this->sklad_vid_oper],
                ]);
        }

        $query
            ->andFilterWhere(['like', 'sklad_vid_oper', $this->sklad_vid_oper])
            ->andFilterWhere(['like', 'wh_debet_name', $this->wh_debet_name])
            ->andFilterWhere(['like', 'wh_debet_element_name', $this->wh_debet_element_name])
            ->andFilterWhere(['like', 'wh_destination_name', $this->wh_destination_name])
            ->andFilterWhere(['like', 'wh_destination_element_name', $this->wh_destination_element_name])
            ->andFilterWhere(['like', 'tx', $this->tx]);

        if ((integer)$this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id,]);
//            $this->dt_start = date("d.m.Y", strtotime(' -3 years'));
            unset($this->wh_home_number);
            unset($this->sklad_vid_oper);
            unset($this->wh_debet_name);
            unset($this->wh_debet_element_name);
            unset($this->wh_destination_name);
            unset($this->wh_destination_element_name);
            unset($this->tx);

        }
        return $dataProvider;
    }


    /**
     *
     * ПОИСК для ЗАГОЛОВКОВ  С телами-Накладных
     * =
     *
     * @param       $params
     * @param array $filter_for
     *
     * @return ActiveDataProvider
     */
    public function search_svod_excel($params, $filter_for = [])
    {
        $this->load($params);
        //         ddd($this);

        // Разный уровень Доступа Админа и Остальных
        if (isset($filter_for) && !empty($filter_for)) {
            $query = Sklad::find()
                ->select(
                    [
                        'id',
                        'sklad_vid_oper',
                        'wh_home_number',
                        'wh_debet_top',
                        'wh_debet_name',
                        'wh_debet_element',
                        'wh_debet_element_name',
                        'wh_destination',
                        'wh_destination_name',
                        'wh_destination_element',
                        'wh_destination_element_name',
                        'dt_create',
                        'dt_create_timestamp',
                        'sprwhelement_home_number.name',
                        'tx',

                        'array_tk_amort',
                        'array_tk',
                        'array_casual',

                        //            'array_bus',

                        //            'sklad_vid_oper_name',
                        //            'dt_create_timestamp',
                        //            'update_user_name',


                        //            'dt_create',
                        //            'dt_update',
                        //            'user_ip',
                        //            'user_id',
                        //            'user_name',
                        //            'update_user_id',
                        //            'update_user_name',
                        //            'user_group_id',
                        //            'array_count_all',
                        //            'dt_transfered_date',
                        //            'dt_transfered_user_id',
                        //            'dt_transfered_user_name',
                    ]
                )
                ->with('sprwhelement_home_number')
                ->where(
                    [
                        'in',
                        'wh_home_number',
                        $filter_for,
                    ]);


        } else {
            $query = Sklad::find()
                ->select(
                    [
                        'id',
                        'sklad_vid_oper',
                        'wh_home_number',
                        'wh_debet_top',
                        'wh_debet_name',
                        'wh_debet_element',
                        'wh_debet_element_name',
                        'wh_destination',
                        'wh_destination_name',
                        'wh_destination_element',
                        'wh_destination_element_name',
                        'dt_create',
                        'dt_create_timestamp',
                        'sprwhelement_home_number.name',

                        'array_tk_amort',
                        'array_tk',
                        'array_casual',
                        'tx',
                    ]
                )
                ->with('sprwhelement_home_number');
        }

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            return $dataProvider;
        }


        // Дата фильтр
        if ($this->wh_home_number > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'wh_home_number',
                    (int)$this->wh_home_number,
                ]
            );
        }

        // Дата фильтр
        if (isset($this->dt_start) && !empty($this->dt_start)) {
            $query->andFilterWhere(
                [
                    '>=',
                    'dt_create_timestamp',
                    (int)strtotime(date("d.m.Y 00:00:00", strtotime($this->dt_start))),
                ]);

        }

        if (isset($this->dt_stop) && !empty($this->dt_stop)) {
            $query->andFilterWhere(
                [
                    '<=',
                    'dt_create_timestamp',
                    (int)strtotime(date("d.m.Y 23:59:59", strtotime($this->dt_stop))),
                ]);
        }


        // Дата фильтр
        if (isset($this['sklad_vid_oper']) && (int)$this['sklad_vid_oper'] > 0) {
            $query->andFilterWhere(
                [
                    'OR',
                    [
                        '=',
                        'sklad_vid_oper',
                        (string)$this->sklad_vid_oper,
                    ],
                    [
                        '=',
                        'sklad_vid_oper',
                        (int)$this->sklad_vid_oper,
                    ],
                ]);
        }


        if ((integer)$this->id > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'id',
                    (integer)$this->id,
                ]);
        }


        return $dataProvider;
    }


    /**
     * search_svod_to_pdf
     *
     * @param       $params
     * @param array $filter_for
     *
     * @return ActiveDataProvider
     */
    public function search_svod_to_pdf($params, $filter_for = [])
    {
        $this->load($params);


        // Разный уровень Доступа Админа и Остальных
        if (isset($filter_for) && !empty($filter_for)) {
            $query = Sklad::find()
                ->where(
                    [
                        'in',
                        'wh_home_number',
                        $filter_for,
                    ]);
        } else {
            //$query = Sklad::find()->all();
            $query = Sklad::find();
        }

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            return $dataProvider;
        }


        if (isset($params['otbor'])) {
            $query->where(
                [
                    '=',
                    'wh_home_number',
                    (integer)$params['otbor'],
                ]);
        }


        if ($this->wh_home_number > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'wh_home_number',
                    (int)$this->wh_home_number,
                ]);
        }
        if (!empty($this->sklad)) {
            $query->andFilterWhere(
                [
                    '=',
                    'wh_home_number',
                    (int)$this->sklad,
                ]);
        }


        //  ddd($this);

        $query->andFilterWhere(
            [
                '=',
                'sklad_vid_oper',
                $this->vid_oper,
            ]);


        if (isset($this->dt_start) && !empty($this->dt_start)) {
            $query->andFilterWhere(
                [
                    '>=',
                    'dt_create_timestamp',
                    (integer)strtotime(date("d.m.Y 00:00:00", strtotime($this->dt_start))),
                ]);
            //            ddd(strtotime(date("d.m.Y 00:00:00",strtotime($this->dt_start))));
        }
        if (isset($this->dt_stop) && !empty($this->dt_stop)) {
            $query->andFilterWhere(
                [
                    '<=',
                    'dt_create_timestamp',
                    (integer)strtotime(date("d.m.Y 23:59:59", strtotime($this->dt_stop))),
                ]);
        }


        $query->andFilterWhere(
            [
                'like',
                'wh_debet_top',
                $this->wh_debet_top,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_debet_name',
                $this->wh_debet_name,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_debet_element',
                $this->wh_debet_element,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_debet_element_name',
                $this->wh_debet_element_name,
            ]);

        $query->andFilterWhere(
            [
                'like',
                'wh_destination',
                $this->wh_destination,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_destination_name',
                $this->wh_destination_name,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_destination_element',
                $this->wh_destination_element,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_destination_element_name',
                $this->wh_destination_element_name,
            ]);


        if ((integer)$this->id > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'id',
                    (integer)$this->id,
                ]);
        }

        if ((integer)$this->tz_id > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'tz_id',
                    (integer)$this->tz_id,
                ]);
        }

        //        ddd(123);

        return $dataProvider;
    }


    /**
     * @param       $params
     * @param array $filter_for
     *
     * @return ActiveDataProvider
     */
    public function search_svod_date($params, $filter_for, $array_date)
    {
        $this->load($params);
        //        ddd( $filter_for );

        if (isset($array_date) && !empty($array_date)) {
            //dd(123);
            $this->dt_start = strtotime($array_date[0]);
            $this->dt_stop = strtotime($array_date[1]);
        }
        //        else{
        //            $this ->dt_start = strtotime( "28.06.2019 15:34:08");
        //            $this ->dt_stop  = strtotime( "03.07.2019 23:59:59");
        //        }


        // Разный уровень Доступа Админа и Остальных
        if (isset($filter_for) && !empty($filter_for)) {
            $query = Sklad::find()
                ->where(
                    [
                        'in',
                        'wh_home_number',
                        $filter_for,
                    ]);
        } else {
            $query = Sklad::find()->all();
        }


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        if ($this->wh_home_number > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'wh_home_number',
                    (int)$this->wh_home_number,
                ]);
        }

        $query->andFilterWhere(
            [
                '>=',
                'dt_create_timestamp',
                $this->dt_start,
            ]);
        $query->andFilterWhere(
            [
                '<=',
                'dt_create_timestamp',
                $this->dt_stop,
            ]);


        $query->andFilterWhere(
            [
                '=',
                'sklad_vid_oper',
                $this->vid_oper,
            ]);

        $query->andFilterWhere(
            [
                'like',
                'wh_debet_top',
                $this->wh_debet_top,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_debet_name',
                $this->wh_debet_name,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_debet_element',
                $this->wh_debet_element,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_debet_element_name',
                $this->wh_debet_element_name,
            ]);

        $query->andFilterWhere(
            [
                'like',
                'wh_destination',
                $this->wh_destination,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_destination_name',
                $this->wh_destination_name,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_destination_element',
                $this->wh_destination_element,
            ]);
        $query->andFilterWhere(
            [
                'like',
                'wh_destination_element_name',
                $this->wh_destination_element_name,
            ]);


        if ((integer)$this->id > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'id',
                    (integer)$this->id,
                ]);
        }


        return $dataProvider;
    }


    /**
     * PE
     * -
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search_svod_pe($params)
    {
        $this->load($params);

        //dd($params['otbor']);
        //   $query = Sklad::find_with('sprwhelement'); ЖАДНАЯ ЗАГРУЗКА НЕ ХОЧУ

        $query = Sklad::find();

        //        ddd($params);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,

                'pagination' => ['pageSize' => 10],

                'sort' => [
                    //                'defaultOrder' => ['id'=>SORT_ASC],


                    'attributes' => [

                        'dt_create' => [
                            //'value'=>date("dmY",strtotime('dt_create')),
                            'asc' => [
                                strtotime('dt_create') => SORT_ASC,
                            ],
                            'desc' => [
                                strtotime('dt_create') => SORT_DESC,
                            ],
                            'label' => 'Дата создания',
                            'default' => SORT_ASC,
                        ],


                        'id' => [
                            'asc' => [
                                (int)'id' => SORT_DESC,
                            ],
                            'desc' => [
                                (int)'id' => SORT_DESC,
                            ],
                            'label' => 'Дата создания',
                            'default' => SORT_ASC,
                        ],


                        'wh_destination_element_name' => [
                            'asc' => [
                                'wh_destination_element_name' => SORT_ASC,
                                (int)'id' => SORT_ASC,
                            ],
                            'desc' => [
                                'wh_destination_element_name' => SORT_DESC,
                                (int)'id' => SORT_ASC,
                            ],
                        ],
                    ],
                ],
            ]);


        $dataProvider->setSort(
            [
                'defaultOrder' => ['id' => SORT_DESC],
            ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        //ddd($dataProvider->getModels());


        //        ddd($params);

        if (isset($params['otbor'])) {
            $query->where(
                [
                    '=',
                    'wh_home_number',
                    (integer)$params['otbor'],
                ]);
        }


        if ($this->wh_home_number > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'wh_home_number',
                    (int)$this->wh_home_number,
                ]);
        }

        if ($this->sklad_vid_oper > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'sklad_vid_oper',
                    (int)$this->sklad_vid_oper,
                ]);
        }

        //ddd($params);wh_destination

        $query->andFilterWhere(
            [
                '=',
                'sklad_vid_oper',
                $this->vid_oper,
            ]);

        //			$query->andFilterWhere(
        //				[
        //					'like',
        //					'wh_debet_top',
        //					$this->wh_debet_top,
        //				] );
        $query->andFilterWhere(
            [
                'like',
                'wh_debet_name',
                $this->wh_debet_name,
            ]);
        //			$query->andFilterWhere(
        //				[
        //					'like',
        //					'wh_debet_element',
        //					$this->wh_debet_element,
        //				] );

        $query->andFilterWhere(
            [
                'like',
                'wh_debet_element_name',
                $this->wh_debet_element_name,
            ]);

        if (isset($this->wh_destination) && !empty($this->wh_destination)) {
            $query->andFilterWhere(
                [
                    '=',
                    'wh_destination',
                    (int)$this->wh_destination,
                ]);
        }

        $query->andFilterWhere(
            [
                'like',
                'wh_destination_name',
                $this->wh_destination_name,
            ]);
        //			$query->andFilterWhere(
        //				[
        //					'like',
        //					'wh_destination_element',
        //					$this->wh_destination_element,
        //				] );

        $query->andFilterWhere(
            [
                'like',
                'wh_destination_element_name',
                $this->wh_destination_element_name,
            ]);


        if (isset($this->id) && !empty($this->id)) {
            $query->andFilterWhere(
                [
                    '=',
                    'id',
                    (int)$this->id,
                ]);
        }

        return $dataProvider;
    }


    /**
     * Поисковый запрос только для движений по ЦС
     * =
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search_move_cs($params)
    {
        $this->load($params);
        // ddd($params);
        // ddd($this);

        $select = [
            'dt_create',
            'sklad_vid_oper',
            'wh_debet_top',
            'wh_debet_element',
            'wh_debet_name',
            'wh_debet_element_name',

            'wh_destination',
            'wh_destination_element',
            'wh_destination_name',
            'wh_destination_element_name',

            'tx',
            'id',
            'wh_home_number',
            'dt_create_timestamp',

            'wh_cs_number',
            'array_count_all',
            'dt_update',
            'user_ip',
            'user_id',
            'user_name',
            'update_user_id',
            'update_user_name',
            'user_group_id',
            'tz_id',
            'dt_update_timestamp',
            'flag',

            'sprwhelement_wh_cs_number.name',
            'sprwhelement_wh_cs_number.nomer_borta',
            'sprwhelement_wh_cs_number.nomer_gos_registr',
            'sprwhelement_wh_cs_number.final_destination',

            'sprwhelement_wh_cs_number.sprwhtop.name',
        ];

        //
        // Жадная загрузка со связями
        //
        $query = Sklad::find()
            ->select($select)
            ->with([
                'sprwhelement_wh_cs_number',
                'sprwhelement_wh_cs_number.sprwhtop',
            ])
            ->where(['>=', 'wh_cs_number', (int)1])
//            ->asArray()
//            ->one()
        ;

//        ddd($query);


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        ///
        ///  CS =  'array_wh_cs_multiple_numbers' => [
        if (isset($params['select2_array_cs_numbers']) && !empty($params['select2_array_cs_numbers'])) {

            if ($params['select2_array_cs_numbers'] == '0') {


                $params['select2_array_cs_numbers'] = '';
            }

            //ddd( $params );

            $query->andFilterWhere(
                [
                    '=',
                    'wh_cs_number',
                    (int)$params['select2_array_cs_numbers'],
                ]);
        }

        ///
        ///  wh_cs_parent_name
        ///
        if ((int)$this->wh_cs_parent_name > 0) {
            //            ddd($params['postsklad']['wh_cs_parent_name']);


            $query->andFilterWhere(
                [
                    '==',
                    'wh_cs_parent_name',
                    (int)$params['postsklad']['wh_cs_parent_name']
                ]);
        }


        // Дата фильтр dt_create
        if (isset($params['postsklad']['dt_start']) && !empty($params['postsklad']['dt_start'])) {
            // ddd($params);
            $query->andFilterWhere(
                [
                    '>=',
                    'dt_create_timestamp',
                    strtotime($params['postsklad']['dt_start']),
                ]);
        }

        if (isset($params['postsklad']['dt_stop']) && !empty($params['postsklad']['dt_stop'])) {
            $query->andFilterWhere(
                [
                    '<=',
                    'dt_create_timestamp',
                    strtotime($params['postsklad']['dt_stop']),
                ]);
        } else {
            $query->andFilterWhere(
                [
                    '<=',
                    'dt_create_timestamp',
                    strtotime('now'),
                ]);

        }


        if (isset($this['dt_start']) && isset($this['dt_stop'])) {
            if ($this['dt_start'] >= $this['dt_stop']) {
                $this['dt_stop'] = date('d.m.Y 23:59:59', strtotime('now'));
            }
        }

        // Дата фильтр dt_create
        if (isset($this['dt_start']) && !empty($this['dt_start'])) {
            //ddd( $params );
            $query->andFilterWhere(
                [
                    '>=',
                    'dt_create_timestamp',
                    strtotime($this['dt_start']),
                ]);
        }

        if (isset($this['dt_stop']) && !empty($this['dt_stop'])) {
            $query->andFilterWhere(
                [
                    '<=',
                    'dt_create_timestamp',
                    strtotime($this['dt_stop']),
                ]);
        }


//        ddd($this);


        //wh_home_number
        if ((int)$this->wh_home_number > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'wh_home_number',
                    (int)$this->wh_home_number,
                ]);
        }


        //wh_cs_parent_number
        if ((int)$this->id > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'id',
                    (int)$this->id,
                ]);
        }
//        //wh_cs_parent_number
//        if (isset($this->wh_debet_name)) {
//            $query->andFilterWhere(
//                [
//                    'like',
//                    'wh_debet_name',
//                    $this->wh_debet_name,
//                ]);
//        }

        //wh_cs_parent_number
        if ((int)$this->wh_debet_name > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'wh_debet_top',
                    (int)$this->wh_debet_name,
                ]);
        }

//        //wh_destination_name
//        if ((int)$this->wh_destination_name >0) {
//            $query->andFilterWhere(
//                [
//                    '=',
//                    'wh_destination',
//                    (int)$this->wh_destination_name,
//                ]);
//        }


        if ((int)$this->sklad_vid_oper > 0) {
            $query->andFilterWhere(
                [
                    'OR',
                    [
                        '=',
                        'sklad_vid_oper',
                        (int)$this->sklad_vid_oper,
                    ],
                    [
                        'like',
                        'sklad_vid_oper',
                        (string)$this->sklad_vid_oper,
                    ],
                ]);
        }


        $query
            ->andFilterWhere(
                [
                    'like',
                    'sklad_vid_oper',
                    $this->sklad_vid_oper,
                ])
            ->andFilterWhere(
                [
                    'like',
                    'sprwhelement_wh_cs_number.name',
                    $this->sprwhName,
                ])
            ->andFilterWhere(
                [
                    'like',
                    'wh_debet_element_name',
                    $this->wh_debet_element_name,
                ])
            ->andFilterWhere(
                [
                    'like',
                    'wh_destination_name',
                    $this->wh_destination_name,
                ])
            ->andFilterWhere(
                [
                    'like',
                    'wh_destination_element_name',
                    $this->wh_destination_element_name,
                ])
            ->andFilterWhere(
                [
                    'like',
                    'tx',
                    $this->tx,
                ])
            ->andFilterWhere(
                [
                    'like',
                    'user_name',
                    $this->user_name,
                ]);


        return $dataProvider;
    }


    /**
     * Поисковый запрос Полный сисок для =Select2=
     * =
     * Для выпадаюшего списка картик селект2 (не мульти)
     * -
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search_wh($params)
    {

        $this->load($params);

        $query = Sklad::find()
            ->where(
                [
                    '>=',
                    'wh_cs_number',
                    (int)1,
                ]);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => -1],
            ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


//        $params['postsklad']['dt_start'] = date('d.m.Y 00:00:00', strtotime($params['postsklad']['dt_start']));
//        $params['postsklad']['dt_stop'] = date('d.m.Y 23:59:59', strtotime($params['postsklad']['dt_stop']));


        // Дата фильтр dt_create
        if (isset($params['postsklad']['dt_start']) && !empty($params['postsklad']['dt_start'])) {
            //ddd( $params );
            $query->andFilterWhere(
                [
                    '>=',
                    'dt_create_timestamp',
                    strtotime($params['postsklad']['dt_start']),
                ]);
        }

        if (isset($params['postsklad']['dt_stop']) && !empty($params['postsklad']['dt_stop'])) {
            $query->andFilterWhere(
                [
                    '<=',
                    'dt_create_timestamp',
                    strtotime($params['postsklad']['dt_stop']),
                ]);
        }


        //ddd($dataProvider);

        return $dataProvider;
    }


    /**
     * Поисковый запрос только для одного ЦС
     * =
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search_move_cs_one($params)
    {
        $this->load($params);


        //
        // Жадная загрузка со связями
        //
        $query = Sklad::find()
            ->with(
                [
                    'sprwhelement_cs_number.sprwhtop',
                    'sprwhelement_cs_number',
                    'sprwhelement_debet',
                    'sprwhelement_debet_element',
                    'sprwhelement_destination',
                    'sprwhelement_destination_element',

                ])
            ->where(
                [
                    'AND',
                    [
                        '>',
                        'wh_cs_number',
                        1,
                    ],
                    [
                        'NOT',
                        'wh_cs_number',
                        null,
                    ],
                ]


            );


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);

        $dataProvider->setSort(
            [
                'defaultOrder' => ['id' => SORT_DESC],
            ]);

        if (!$this->validate()) {
            return $dataProvider;
        }


        ///
        ///  CS = wh_cs_number
        ///
        if ($this->wh_cs_number > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'wh_cs_number',
                    (int)$this->wh_cs_number,
                ]);
        }


        return $dataProvider;
    }

}



