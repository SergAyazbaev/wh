<?php

namespace frontend\models;

use app\models\Wh;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * postwh represents the model behind the search form of `app\models\Wh`.
 */
class postwh extends Wh
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id', 'name',  'dt_create', 'logical', 'coordX', '222'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Wh::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        // grid filtering conditions
        $query
            ->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'dt_create', $this->dt_create])
            ->andFilterWhere(['like', 'logical', $this->logical])
            ->andFilterWhere(['like', 'coordX', $this->coordX]);
//            ->andFilterWhere(['like', '222', $this->222]);

        return $dataProvider;
    }
}
