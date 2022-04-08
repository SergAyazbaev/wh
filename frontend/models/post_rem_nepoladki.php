<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_rem_nepoladki extends Rem_nepoladki
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
//        ddd($this);


        $query = Rem_nepoladki::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        //ddd($dataProvider->getModels());


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
