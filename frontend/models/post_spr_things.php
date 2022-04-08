<?php

namespace frontend\models;



use yii\data\ActiveDataProvider;



class post_spr_things extends Spr_things
{

    public function rules()
    {
        return [
            [['_id', 'id', 'name', 'tx'], 'safe'],
        ];
    }



    public function search($params)
    {
        $query = Spr_things::find();

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => ['pageSize' =>10],
                ]);

                $this->load($params);

                if (!$this->validate()) {
                    return $dataProvider;
                }


        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tx', $this->tx]);

        return $dataProvider;
    }


}
