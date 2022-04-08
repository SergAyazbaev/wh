<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;


class post_spr_globam_element extends Spr_globam_element
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[

                '_id',
                'id',
                'cc_id',
                'parent_id',
                'name',
                'short_name',
                'intelligent',
                'tx'], 'safe'],
        ];
    }

    /**
     *
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
        $query = Spr_globam_element::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);


        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($this->id) && $this->id > 0)
            $query->andFilterWhere(['=', 'id', (int)$this->id]);

        if (isset($this->cc_id) && $this->cc_id > 0)
            $query->andFilterWhere(['=', 'cc_id', (int)$this->cc_id]);

        if ($this->parent_id) {
            //dd($this->parent_id);
            $query->where(['=', 'parent_id', (integer)$this->parent_id]);
        }

        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'intelligent', $this->intelligent])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like',   'tx',       $this->tx]);

        return $dataProvider;
    }




}
