<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_sklad_filter extends Sklad_wh_invent
{


    public function rules()
    {
        return [
            [['_id', 'id', 'name', 'tx'], 'safe'],
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
        $this->load($params);
        ddd($this);


        $query = Sklad_wh_invent::find();
//        $model = Sklad_wh_invent::findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'name', $this->name]);


        return $dataProvider;
    }
}
