<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_mts_change extends Mts_change
{

    public function rules()
    {
        // Ораничение полей для фильтра
        return [
            [[
//                'name'
            ],
                'safe'],
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

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );

        if (!$this->validate()) {
            return $dataProvider;
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
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search_change_things($params)
    {
        $this->load($params);
        ///
        $query = Mts_change::find()
            ->with([
                'sprwhelement_ap',
                'sprwhelement_pe',
            ])
            ->where(
                ['AND',
                    ['<>', 'close_day', (int)1],
                    //                    ['==', 'id_ap', (int)$this->_ap],
                    //                    ['==', 'id_pe', (int)$this->_ap],
                ]
            );


        ///
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );

        //ddd($params);
        //ddd($dataProvider->getModels());

        ///
        if (!$this->validate()) {
            return $dataProvider;
        }

        //        $query
        //            ->andFilterWhere( [ 'like', 'wh_cred_top_name', $this->wh_cred_top_name ] )
        //            ->andFilterWhere( [ 'like', 'name_tz', $this->name_tz ] );

        return $dataProvider;
    }

}





