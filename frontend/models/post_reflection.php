<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_reflection extends Reflection
{


    public function rules()
    {
        return [[
            [
                '_id',
                'id',

                'home_id',

                'nnn_id',
                'bc',

                'gr',
                'el',
                's',

                't',
                't_cr',
                't_in',
                't_out',

                'spr_globam.name',
                'spr_globam_element.name',
            ],
            'safe'],
        ];
    }


    /**
     *
     * @param $para
     * @return ActiveDataProvider
     */
    public function search($para)
    {
        $this->load($para);

        $query = Reflection::find();

        //        $query = Rem_history::find()
        //            ->where([
        //                '==', 'bar_code', $bar_code
        //            ]);

        //
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        //
        if (!$this->validate()) {
            return $dataProvider;
        }


        //        $query
        //            ->andFilterWhere(['==', 'bar_code', $this->bar_code]);


        return $dataProvider;
    }


    /**
     *
     * @param $para
     * @return ActiveDataProvider
     */
    public function search_with_names($para)
    {
        $this->load($para);
        //
        $query = Reflection::find()->with('spr_globam', 'spr_globam_element');
        //
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        //
        if (!$this->validate()) {
            return $dataProvider;
        }

        //        $query
        //            ->andFilterWhere(['==', 'bar_code', $this->bar_code]);

        return $dataProvider;
    }


}
