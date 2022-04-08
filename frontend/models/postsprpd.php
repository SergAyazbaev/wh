<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * postsprtype represents the model behind the search form of `app\models\sprtype`.
 */
class postsprpd extends Spr_pd
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id', 'id', 'name'], 'safe'],
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
        $query = Spr_pd::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $this->load($params);

            if (!$this->validate()) {
                return $dataProvider;
            }


        $query
            ->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
