<?php

namespace frontend\models;


//use yii\base\Model;
use yii\data\ActiveDataProvider;



class postsklad_inventory extends Sklad_inventory
{

    public function rules()
    {
        return [

            [[
                'id',
                'wh_destination',
                'wh_destination_element',

                'group_inventory',
                'group_inventory_name',
                'wh_home_number',
            ],'safe'],

        ];
    }
    public function attributeLabels()
    {
        return [
            'wh_destination' => 'Группа',
            'wh_destination_element' => 'Склад',

            'group_inventory' => 'Группа ведомостей',
            'group_inventory_name' => 'Группа ведомостей',

            'dt_create'         => 'Дата накладной',
            'dt_update'         => 'Дата исправления',

            'wh_home_number'    => 'ID',

            'comments'          => 'Коменты',
        ];

    }


    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);

//        ddd($this->wh_destination);
//        ddd($this);

        $query = Sklad_inventory::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>10],
        ]);


        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($this->id) && $this->id > 0)
            $query->andFilterWhere(['=', 'id', (integer)$this->id]);

        if (isset($this->wh_destination) && !empty($this->wh_destination))
            $query->andFilterWhere([ 'OR',
                ['wh_destination'=> (int)$this->wh_destination],
                ['wh_destination'=> $this->wh_destination]
            ]);

        if (isset($this->wh_destination_element) && !empty($this->wh_destination_element))
            $query->andFilterWhere([ 'OR',
                ['wh_destination_element'=> (int)$this->wh_destination_element],
                ['wh_destination_element'=> $this->wh_destination_element]
            ]);

        if (isset($this->group_inventory) && !empty($this->group_inventory))
            $query->andFilterWhere([ 'OR',
                ['group_inventory'=> (int)$this->group_inventory],
                ['group_inventory'=> $this->group_inventory]
            ]);



        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'group_inventory_name', $this->group_inventory_name]);




        return $dataProvider;
    }

}



