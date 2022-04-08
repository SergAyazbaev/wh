<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_rem_history extends Rem_history
{
    public $dt_between = ''; /// ДАТА

    public function rules()
    {
        return [[
            [
                'bar_code',
                'short_name',
                'diagnoz',
                'decision',
                'list_details',
                'mts_user_name',
                'num_busline',

                'rem_user_name',
                'mts_user_name',
                'dt_rem_timestamp',
            ],
            'safe'],
        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        //ddd($params['sort']);

        $this->load($params);
        //ddd($this);

        //        ddd($params['dt_between']); /// '24-06-2020 - 23-07-2020'

        if (isset($params['dt_between']) && !empty(isset($params['dt_between']))) {

            $array_between = explode(' - ', $params['dt_between']);

            if (isset($array_between[0])) {
                $dt_start = strtotime(date('d.m.Y 00:00:00', strtotime($array_between[0])));
            }
            if (isset($array_between[1])) {
                $dt_stop = strtotime(date('d.m.Y 23:59:59', strtotime($array_between[1])));
            }

            //ddd($params);
        }


        ///
        if (isset($params['sort'])) {
            if (substr($params['sort'], 0, 1) == '-') {
                $query = Rem_history::find()
                    ->with('sprwhelement')
                    ->orderBy(substr($params['sort'], 1, -1) . ' DESC');

            } else {

                $query = Rem_history::find()
                    ->with('sprwhelement')
                    ->orderBy($params['sort']);
            }

        } else {

            $query = Rem_history::find()
                ->with('sprwhelement');
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 15],
            //'sort' => ['defaultOrder' => ['sprwhelement.name' => SORT_ASC, 'sprwhelement.name' => SORT_ASC]]
        ]);


        //ddd($dataProvider->getModels());


        if (!$this->validate()) {
            return $dataProvider;
        }

        //START
        if (isset($dt_start) && (int)$dt_start > 0) {
            $query
                ->andFilterWhere(['>=', 'dt_create_timestamp', $dt_start]);
        }
        //STOP
        if (isset($dt_stop) && (int)$dt_stop > 0) {
            $query
                ->andFilterWhere(['<=', 'dt_create_timestamp', $dt_stop]);
        }


        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'diagnoz', $this->diagnoz])
            ->andFilterWhere(['like', 'decision', $this->decision])
            ->andFilterWhere(['like', 'list_details', $this->list_details])
            ->andFilterWhere(['like', 'bar_code', $this->bar_code]);


        return $dataProvider;
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search_with_color($params)
    {
        $this->load($params);
//        ddd($this);
//        ddd($params);


        //        ddd($params['dt_between']); /// '24-06-2020 - 23-07-2020'
        if (isset($params['dt_between']) && !empty(isset($params['dt_between']))) {

            $array_between = explode(' - ', $params['dt_between']);

            if (isset($array_between[0])) {
                $dt_start = strtotime(date('d.m.Y 00:00:00', strtotime($array_between[0])));
            }
            if (isset($array_between[1])) {
                $dt_stop = strtotime(date('d.m.Y 23:59:59', strtotime($array_between[1])));
            }

            //ddd($params);
        }


        ///
        if (isset($params['sort'])) {
            if (substr($params['sort'], 0, 1) == '-') {
                //ddd(substr($params['sort'], 1));

                $query = Rem_history::find()
                    ->with('sprwhelement', 'barcode_pool')
                    ->orderBy(substr($params['sort'], 1) . ' DESC');

            } else {
                $query = Rem_history::find()
                    ->with('sprwhelement', 'barcode_pool')
                    ->orderBy($params['sort']);
            }

        } else {

            $query = Rem_history::find()
                ->with([
                    'sprwhelement',
                    'barcode_pool',
                    'barcode_pool.barcode_consignment'
                ]);

        }


        //ddd($query);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10]
        ]);


        //ddd($dataProvider->getModels());


        if (!$this->validate()) {
            return $dataProvider;
        }


        //START
        if (isset($dt_start) && (int)$dt_start > 0) {
            $query
                ->andFilterWhere(['>=', 'dt_create_timestamp', $dt_start]);
        }
        //STOP
        if (isset($dt_stop) && (int)$dt_stop > 0) {
            $query
                ->andFilterWhere(['<=', 'dt_create_timestamp', $dt_stop]);
        }



        //barcode_pool_turnover
//        if (isset($barcode_pool_turnover) && (int)$barcode_pool_turnover > 0) {
//            $query
//                ->andFilterWhere(['<=', 'barcode_pool.turnover', $barcode_pool_turnover]);
//        }

        // ddd($this);

        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'mts_user_name', $this->mts_user_name])
            ->andFilterWhere(['like', 'rem_user_name', $this->rem_user_name])
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'diagnoz', $this->diagnoz])
            ->andFilterWhere(['like', 'decision', $this->decision])
            ->andFilterWhere(['like', 'list_details', $this->list_details])
            ->andFilterWhere(['like', 'bar_code', $this->bar_code])
            ->andFilterWhere(['like', 'num_busline', $this->num_busline])
            ->andFilterWhere(['like', 'mts_user_name', $this->mts_user_name]);


        //ddd($dataProvider->getModels());
        return $dataProvider;
    }


    /**
     * История без перелистывания!
     * только для выбранных ШТРИХКОДОВ по фильтру
     *
     * @param $bar_code
     * @param $para
     * @return ActiveDataProvider
     */
    public function search_for_filter($bar_code, $para)
    {
        $this->load($para);
        //        ddd($bar_code);
        //        ddd($this);


        $query = Rem_history::find()
            ->where([
                '==', 'bar_code', $bar_code
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query
            ->andFilterWhere(['==', 'bar_code', $this->bar_code]);


        return $dataProvider;
    }
}
