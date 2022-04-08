<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_mts_crm extends mts_crm
{

    public function rules()
    {
//        return [
//            [[
//                'id',
//                'tx',
//                'crm_txt',
//            ], 'safe'],
//        ];

        return [];
    }

    /**
     * Search STD
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);


        $query = mts_crm::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );


        if (!$this->validate()) {
            return $dataProvider;
        }


        //        $now_time=date('Y-m-d H:i:s',strtotime('now'));
        //        $now_start_day=date('Y-m-d H:i:s',strtotime('today'));
        //        $now_end_day=date('Y-m-d H:i:s',strtotime('today +24 hours'));

        $now_time = strtotime('now');
        $now_start_day = strtotime('today');
        $now_end_day = strtotime('today +24 hours');


        //        ddd($params);

        //        'posttz' => [
        //        'three' => '2'
        //    ]
        //    'sort' => 'dt_deadline'

        //        ddd($this);

        //        if(isset( $this->three )){
        //            ddd($this->three);
        //        }


        if (isset($params['posttz']['three'])) {

            if (isset($params['posttz']['three']) && $params['posttz']['three'] == 1) { //Просрочено
                $query
                    //->andFilterWhere( [ '<=', 'dt_deadline', $now_end_day ] );
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 2) { /// Сегодня
                $query
                    ->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_start_day])
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 3) { // Еще зеленое
                $query->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_time]);
            }

        }


        /// ID
        if (isset($this->id) && $this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }


//        $query
//            ->andFilterWhere( [ 'like', 'wh_cred_top_name', $this->wh_cred_top_name ] )
//            ->andFilterWhere( [ 'like', 'name_tz', $this->name_tz ] );

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search_open($params)
    {
        $this->load($params);

        $query = mts_crm::find()
            ->select([
                'id',
                'id_ap',
                'id_pe',
                'mts_id',
                'sprwhelement_ap.name',
                'sprwhelement_pe.name',
                'sprwhelement_mts.name',
                'dt_create_timestamp',
                'dt_update_timestamp', ///

                'job_fin',
                'job_fin_timestamp',
                'zakaz_fin',
                'zakaz_fin_timestamp',

                'crm_txt',
            ])
            ->with('sprwhelement_mts', 'sprwhelement_ap', 'sprwhelement_pe')
            ->where(
                ['<>', 'job_fin', (int)1]
            );

        //ddd($query);


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );


        if (!$this->validate()) {
            return $dataProvider;
        }


        //        $now_time=date('Y-m-d H:i:s',strtotime('now'));
        //        $now_start_day=date('Y-m-d H:i:s',strtotime('today'));
        //        $now_end_day=date('Y-m-d H:i:s',strtotime('today +24 hours'));

        $now_time = strtotime('now');
        $now_start_day = strtotime('today');
        $now_end_day = strtotime('today +24 hours');


        //        ddd($params);

        //        'posttz' => [
        //        'three' => '2'
        //    ]
        //    'sort' => 'dt_deadline'

        //        ddd($this);

        //        if(isset( $this->three )){
        //            ddd($this->three);
        //        }


        if (isset($params['posttz']['three'])) {

            if (isset($params['posttz']['three']) && $params['posttz']['three'] == 1) { //Просрочено
                $query
                    //->andFilterWhere( [ '<=', 'dt_deadline', $now_end_day ] );
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 2) { /// Сегодня
                $query
                    ->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_start_day])
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 3) { // Еще зеленое
                $query->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_time]);
            }

        }


        /// ID
        if (isset($this->id) && $this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }


//        $query
//            ->andFilterWhere( [ 'like', 'wh_cred_top_name', $this->wh_cred_top_name ] )
//            ->andFilterWhere( [ 'like', 'name_tz', $this->name_tz ] );

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search_close($params)
    {
        $this->load($params);

        //        'zakaz_fin' => 'Заявка закрыта',
        //            'zakaz_fin_timestamp' => 'Время закрытия',
        $query = mts_crm::find()
            ->select([
                'id',
                'id_ap',
                'id_pe',
                'mts_id',
                'sprwhelement_pe.name',
                'sprwhelement_mts.name',
                'dt_create_timestamp',
                'dt_update_timestamp', ///

                'job_fin',
                'job_fin_timestamp',
                'zakaz_fin',
                'zakaz_fin_timestamp',
                'crm_txt',
            ])
            ->with('sprwhelement_mts', 'sprwhelement_ap', 'sprwhelement_pe')
            ->where(
                ['==', 'job_fin', (int)1]
            );

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );


        if (!$this->validate()) {
            return $dataProvider;
        }


        //        $now_time=date('Y-m-d H:i:s',strtotime('now'));
        //        $now_start_day=date('Y-m-d H:i:s',strtotime('today'));
        //        $now_end_day=date('Y-m-d H:i:s',strtotime('today +24 hours'));

        $now_time = strtotime('now');
        $now_start_day = strtotime('today');
        $now_end_day = strtotime('today +24 hours');


        //        ddd($params);

        //        'posttz' => [
        //        'three' => '2'
        //    ]
        //    'sort' => 'dt_deadline'

        //        ddd($this);

        //        if(isset( $this->three )){
        //            ddd($this->three);
        //        }


        if (isset($params['posttz']['three'])) {

            if (isset($params['posttz']['three']) && $params['posttz']['three'] == 1) { //Просрочено
                $query
                    //->andFilterWhere( [ '<=', 'dt_deadline', $now_end_day ] );
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 2) { /// Сегодня
                $query
                    ->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_start_day])
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 3) { // Еще зеленое
                $query->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_time]);
            }

        }


        /// ID
        if (isset($this->id) && $this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }


//        $query
//            ->andFilterWhere( [ 'like', 'wh_cred_top_name', $this->wh_cred_top_name ] )
//            ->andFilterWhere( [ 'like', 'name_tz', $this->name_tz ] );

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param $pe
     * @return ActiveDataProvider
     */
    public function search_open_activ($params, $pe)
    {
        $this->load($params);

//        ddd($pe);

        $query = mts_crm::find()
            ->select([
                'id',
                'id_ap',
                'id_pe',
                'mts_id',
                'sprwhelement_ap.name',
                'sprwhelement_pe.name',
                'sprwhelement_mts.name',
                'dt_create_timestamp',
                'dt_update_timestamp', ///

                'job_fin',
                'job_fin_timestamp',
                'zakaz_fin',
                'zakaz_fin_timestamp',
            ])
            ->with('sprwhelement_mts', 'sprwhelement_ap', 'sprwhelement_pe')
            ->where(['AND',
                    ['<>', 'job_fin', (int)1],
                    ['==', 'id_pe', (int)$pe]
                ]
            );

        //ddd($query);


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );


        if (!$this->validate()) {
            return $dataProvider;
        }


        $now_time = strtotime('now');
        $now_start_day = strtotime('today');
        $now_end_day = strtotime('today +24 hours');


        if (isset($params['posttz']['three'])) {

            if (isset($params['posttz']['three']) && $params['posttz']['three'] == 1) { //Просрочено
                $query
                    //->andFilterWhere( [ '<=', 'dt_deadline', $now_end_day ] );
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 2) { /// Сегодня
                $query
                    ->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_start_day])
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 3) { // Еще зеленое
                $query->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_time]);
            }

        }


        /// ID
        if (isset($this->id) && $this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }


        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search_close_activ($params, $pe)
    {
        $this->load($params);

        $query = mts_crm::find()
            ->select([
                'id',
                'id_ap',
                'id_pe',
                'mts_id',
                'sprwhelement_pe.name',
                'sprwhelement_mts.name',
                'dt_create_timestamp',
                'dt_update_timestamp', ///

                'job_fin',
                'job_fin_timestamp',
                'zakaz_fin',
                'zakaz_fin_timestamp',
            ])
            ->with('sprwhelement_mts', 'sprwhelement_ap', 'sprwhelement_pe')
            ->where(['AND',
                    ['==', 'job_fin', (int)1],
                    ['==', 'id_pe', (int)$pe]
                ]
            );

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );


        if (!$this->validate()) {
            return $dataProvider;
        }


        //        $now_time=date('Y-m-d H:i:s',strtotime('now'));
        //        $now_start_day=date('Y-m-d H:i:s',strtotime('today'));
        //        $now_end_day=date('Y-m-d H:i:s',strtotime('today +24 hours'));

        $now_time = strtotime('now');
        $now_start_day = strtotime('today');
        $now_end_day = strtotime('today +24 hours');


        //        ddd($params);

        //        'posttz' => [
        //        'three' => '2'
        //    ]
        //    'sort' => 'dt_deadline'

        //        ddd($this);

        //        if(isset( $this->three )){
        //            ddd($this->three);
        //        }


        if (isset($params['posttz']['three'])) {

            if (isset($params['posttz']['three']) && $params['posttz']['three'] == 1) { //Просрочено
                $query
                    //->andFilterWhere( [ '<=', 'dt_deadline', $now_end_day ] );
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 2) { /// Сегодня
                $query
                    ->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_start_day])
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 3) { // Еще зеленое
                $query->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_time]);
            }

        }


        /// ID
        if (isset($this->id) && $this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }


//        $query
//            ->andFilterWhere( [ 'like', 'wh_cred_top_name', $this->wh_cred_top_name ] )
//            ->andFilterWhere( [ 'like', 'name_tz', $this->name_tz ] );

        return $dataProvider;
    }

}





