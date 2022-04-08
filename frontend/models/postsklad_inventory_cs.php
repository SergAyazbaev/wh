<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class postsklad_inventory_cs extends Sklad_inventory_cs
{

    /**
     * @return array
     */
    public function rules()
    {
        return [

            [[
                'id',
                'empty_cs',
                'count_str',
                'sklad_vid_oper_name',

                'wh_destination',
                'wh_home_number',
                'wh_destination_element_name',

                'sprwh_wh_destination.name',

                'itogo_things',
                'calc_minus',
                'calc_errors',

                'dt_create_timestamp',
            ],
                'safe'
            ],

        ];
    }


    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        //ddd($params);


        if (isset($params['select2_array_cs_numbers']) && isset($params['select2_array_cs_numbers']) > 0) {
            $params['postsklad_inventory_cs']['wh_home_number'] = $params['select2_array_cs_numbers'];
        }
        //wh_destination
        if (isset($params['select2_array_destination']) && isset($params['select2_array_destination']) > 0) {
            $params['postsklad_inventory_cs']['wh_destination'] = $params['select2_array_destination'];
            //ddd($params);
        }
        if (isset($params['select2_array_timestamp']) && isset($params['select2_array_timestamp']) > 0) {
            $params['postsklad_inventory_cs']['dt_create_timestamp'] = $params['select2_array_timestamp'];
            //ddd($params);
        }


        $this->load($params);
          //ddd($this);


        $query = Sklad_inventory_cs::find()
            ->with(
                'sprwhelement_home_number',
                'sprwh_wh_destination',
                'sprwhelement_change'
            );


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);


        if (!$this->validate()) {
            return $dataProvider;
        }


       // ddd($this);

        if (isset($this->id) && !empty($this->id) && (int)$this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }


        //empty count_str
        if ($this->count_str != '' && (int)$this->count_str === 0) {
            //ddd($this);
            $query->andFilterWhere(
                ['OR',
                    ['=', 'count_str', (int)0],
                    ['=', 'count_str', ''],
                    ['=', 'count_str', null]
                ]
            );
        }


        if (isset($this->count_str) && !empty($this->count_str) && (int)$this->count_str > 0) {
            $query->andFilterWhere(['=', 'count_str', (int)$this->count_str]);
        }


        //empty itogo_things
        if ($this->itogo_things != '' && (int)$this->itogo_things === 0) {
            //ddd($this);
            $query->andFilterWhere(
                ['OR',
                    ['=', 'itogo_things', 0],
                    ['=', 'itogo_things', ''],
                    ['=', 'itogo_things', null]
                ]
            );
        }

        //ddd($this);
        //ddd($params['select2_array_timestamp']);

        ///
        if (isset($this->wh_destination) && $this->wh_destination > 0) {
            $query->andFilterWhere(['=', 'wh_destination', (int)$this->wh_destination]);
        }
        ///
        if (isset($this->wh_home_number) && $this->wh_home_number > 0) {
            $query->andFilterWhere(['=', 'wh_home_number', (int)$this->wh_home_number]);
        }

        //
        if (isset($this->itogo_things) && !empty($this->itogo_things) && (int)$this->itogo_things > 0) {
            $query->andFilterWhere(['=', 'itogo_things', (int)$this->itogo_things]);
        }

        if (isset($this->calc_minus) && !empty($this->calc_minus) && (int)$this->calc_minus >= 0) {
            $query->andFilterWhere(['=', 'calc_minus', (int)$this->calc_minus]);
        }


        //empty itogo_things
        if ($this->calc_errors != '' && (int)$this->calc_errors === 0) {
            //ddd($this);
            $query->andFilterWhere(
                ['OR',
                    ['=', 'calc_errors', 0],
                    ['=', 'calc_errors', ''],
                    ['=', 'calc_errors', null]
                ]
            );
        }
        if (isset($this->calc_errors) && $this->calc_errors > 0) {
            $query->andFilterWhere(['>=', 'calc_errors', (int)$this->calc_errors]);
        }


        ///
        if (isset($this->wh_destination) && !empty($this->wh_destination)) {
            $query->andFilterWhere(['OR',
                ['wh_destination' => (int)$this->wh_destination],
                ['wh_destination' => $this->wh_destination]
            ]);
        }


        if (isset($this->wh_destination_element_name) && !empty($this->wh_destination_element_name)) {
            $query->andFilterWhere(['like', 'wh_destination_element_name', (string)$this->wh_destination_element_name]);
        }

        if (isset($this->sklad_vid_oper_name) && !empty($this->sklad_vid_oper_name)) {
            $query
                ->andFilterWhere(['like', 'sklad_vid_oper_name', $this->sklad_vid_oper_name]);
        }

        //dt_update_timestamp
        if (isset($this->dt_create_timestamp) && $this->dt_create_timestamp > 0) {
            $query->andFilterWhere(['dt_create_timestamp' => (int)$this->dt_create_timestamp]);
        }


        return $dataProvider;
    }


}



