<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;



class post_past_inventory extends Sklad
{


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

        $query = Sklad_past_inventory::find();


            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' =>10],
            ]);

            $dataProvider->setSort([
                'defaultOrder' => ['id'=>SORT_DESC],]);

            if (!$this->validate() ) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }


            if (isset($params['otbor'])){
                $query->where(['=', 'wh_home_number',(integer) $params['otbor'] ]);
            }

        //ddd($params);
        //ddd($this->dt_start);

//        ddd($this);


        if(isset($this->wh_destination) && !empty($this->wh_destination)) {
            $query->andFilterWhere(['OR',
                ['=', 'wh_destination', (int)$this->wh_destination],
                ['like', 'wh_destination', $this->wh_destination]
            ]);
        }

        if(isset($this->wh_destination_element) && !empty($this->wh_destination_element)) {
            $query->andFilterWhere(['OR',
                ['=', 'wh_destination_element', (int)$this->wh_destination_element],
                ['like', 'wh_destination_element', $this->wh_destination_element]
            ]);
        }




//        // Дата фильтр dt_create
//        if(isset($params['postsklad']['dt_start']) && !empty($params['postsklad']['dt_start'])){
//            //ddd($params);
//            $query->andFilterWhere(['like', 'dt_create', $params['postsklad']['dt_start'] ]);
//        }
//        // Дата фильтр dt_create
//        if(isset($params['dt_start']) && !empty($params['dt_start'])){
//            //ddd($params);
//            $query->andFilterWhere(['like', 'dt_create', $params['dt_start'] ]);
//        }
//        if(isset($params['dt_create']) && !empty($params['dt_create'])){
//            $query->andFilterWhere(['like', 'dt_create', $params['dt_create'] ]);
//        }



        if((integer) $this->id >0)
            $query->andFilterWhere(['=', 'id', (integer) $this->id]);

        if((integer) $this->tz_id > 0)
            $query->andFilterWhere(['=', 'tz_id', (integer) $this->tz_id]);




        return $dataProvider;
    }


}



