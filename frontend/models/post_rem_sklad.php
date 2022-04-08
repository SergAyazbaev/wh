<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_rem_sklad extends Sklad
{


    public function rules()
    {
        return [
            [['_id', 'id', 'tx'], 'safe'],
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
//        ddd($this);


        $query = Sklad::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        //ddd($dataProvider->getModels());


        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'id', $this->id]);
//            ->andFilterWhere(['like', 'name', $this->name]);


        return $dataProvider;
    }
}
