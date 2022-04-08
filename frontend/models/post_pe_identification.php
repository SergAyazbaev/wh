<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_pe_identification extends Pe_identification
{

    public function rules()
    {
        return [
            [['id'], 'safe'],
        ];
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

        $query = Pe_identification::find()
            ->with(['sprwhelement_ap', 'sprwhelement_pe'])
            ->indexBy('id') /// )))))
        ;


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );

        if (!$this->validate()) {
            return $dataProvider;
        }

//        ddd($dataProvider);

//        $now_time = strtotime('now');
//        $now_start_day = strtotime('today');
//        $now_end_day = strtotime('today +24 hours');


//        $query
//            ->andFilterWhere( [ 'like', 'wh_cred_top_name', $this->wh_cred_top_name ] )
//            ->andFilterWhere( [ 'like', 'name_tz', $this->name_tz ] );

        return $dataProvider;
    }

}





