<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;


class post_cs_past_inventory extends Sklad
{


    public $wh_destination_gos;
    public $wh_destination_bort;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'wh_home_number', // ид текущего склада
                'wh_destination',
                'wh_destination_element',

                'wh_destination_element_name',
                'wh_destination_gos',
                'wh_destination_bort',
            ],
                'safe'],
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
        $this->load($params);
        $query = Sklad_cs_past_inventory::find();

//        $query = Sklad_cs_past_inventory::find()->with('sprwhelement_wh_destination_element');

//ddd($query);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);


        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        if (isset($params['otbor'])) {
            $query->where(['=', 'wh_home_number', (integer)$params['otbor']]);
        }

        //        ddd($params);
        //        ddd (  $query );
        //        ddd($this->select2_array_cs_numbers);


        if (isset($this->wh_destination) && !empty($this->wh_destination)) {
            $query->andFilterWhere(['OR',
                ['=', 'wh_destination', (int)$this->wh_destination],
                ['like', 'wh_destination', $this->wh_destination]
            ]);
        }

        if (isset($this->wh_destination_element) && !empty($this->wh_destination_element)) {
            $query->andFilterWhere(['OR',
                ['=', 'wh_destination_element', (int)$this->wh_destination_element],
                ['like', 'wh_destination_element', $this->wh_destination_element]
            ]);
        }

        //ddd($this);

        if ((integer)$this->id > 0)
            $query->andFilterWhere(['=', 'id', (integer)$this->id]);

        if ((integer)$this->tz_id > 0)
            $query->andFilterWhere(['=', 'tz_id', (integer)$this->tz_id]);

        if (isset($params['select2_array_cs_numbers']) && (int)$params['select2_array_cs_numbers'] > 0) {
            $query->andFilterWhere(['=', 'wh_destination_element', (int)$params['select2_array_cs_numbers']]);
        }
        if (isset($params['select2_array_cs_numbers_gos']) && (int)$params['select2_array_cs_numbers_gos'] > 0) {
            $query->andFilterWhere(['=', 'wh_destination_element', (int)$params['select2_array_cs_numbers_gos']]);
        }
        if (isset($params['select2_array_cs_numbers_bort']) && (int)$params['select2_array_cs_numbers_bort'] > 0) {
            $query->andFilterWhere(['=', 'wh_destination_element', (int)$params['select2_array_cs_numbers_bort']]);
        }


        return $dataProvider;
    }


    /**
     * Search FOR Mobile INVENTORY
     *=
     * @param $ap
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search_for_mobile_tabl($ap, $params)
    {
        $this->load($params);
        $query = Sklad_cs_past_inventory::find()
            ->where(['==', 'wh_destination', (int)$ap]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);


        $dataProvider->setSort([
            'defaultOrder' => ['wh_destination_element' => SORT_ASC]
        ]);


        if (!$this->validate()) {
            return $dataProvider;
        }


        // ddd($this);

        if (isset($this->wh_destination_element_name) && !empty($this->wh_destination_element_name)) {
            $query->andFilterWhere(
                ['like', 'wh_destination_element_name', $this->wh_destination_element_name]
            );
        }

        if (isset($this->wh_destination_gos) && !empty($this->wh_destination_gos)) {
            $query->andFilterWhere(
                ['like', 'wh_destination_gos', $this->wh_destination_gos]
            );
        }

        if (isset($this->wh_destination_bort) && !empty($this->wh_destination_bort)) {
            $query->andFilterWhere(
                ['like', 'wh_destination_bort', trim($this->wh_destination_bort)]
            );
        }


        return $dataProvider;
    }


}



