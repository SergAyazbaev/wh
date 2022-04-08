<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_spr_glob_element extends Spr_glob_element
{

    public function rules()
    {
        return [
            [[
                '_id',
                'id',
                'parent_id',
                'cc_id',
                'pd_id',
                'name',
                'ed_izm',
                'tx'],
                'safe'],
        ];
    }



    /**
     * Creates data provider instance with search query applied
     *-
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Spr_glob_element::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (isset($this->id) && $this->id > 0)
            $query->andFilterWhere(['=', 'id', (integer)$this->id]);

        if ($this->parent_id) {
            $query->where(['=', 'parent_id', (integer)$this->parent_id]);
        }

        if (isset($this->ed_izm) && $this->ed_izm > 0) {
            $query->where(['=', 'ed_izm', $this->ed_izm]);
        }


        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tx', $this->tx]);

        return $dataProvider;
    }

    /**
     * with_spr_glob
     * -
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_with_spr_glob($params)
    {
        $query = Spr_glob_element::find()
            -> with("spr_glob");
            //-> with("spr_glob","spr_things");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (isset($this->id) && $this->id > 0)
            $query->andFilterWhere(['=', 'id', (integer)$this->id]);

        if ($this->parent_id) {
            $query->where(['=', 'parent_id', (integer)$this->parent_id]);
        }

        if (isset($this->ed_izm) && $this->ed_izm > 0) {
            $query->where(['=', 'ed_izm', $this->ed_izm]);
        }


        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tx', $this->tx]);

        return $dataProvider;
    }


    /**
     * С привязкой к таблице Групп Товаров (Подразделов) spr_pd
     *
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_with($params)
    {
        $query = Spr_glob_element::find();

        //ddd($query);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (isset($this->id) && $this->id > 0)
            $query->andFilterWhere(['=', 'id', (int)$this->id]);


        if ($this->parent_id) {
            $query->where(['=', 'parent_id', (int)$this->parent_id]);
        }

        if (isset($this->ed_izm) && $this->ed_izm > 0) {
            $query->where(['=', 'ed_izm', $this->ed_izm]);
        }


        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'cc_id', $this->cc_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['=', 'pd_id', $this->pd_id])
            ->andFilterWhere(['like', 'tx', $this->tx]);

        return $dataProvider;
    }



}
