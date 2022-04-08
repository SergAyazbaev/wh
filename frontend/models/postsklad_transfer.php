<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class postsklad_transfer extends Sklad_transfer
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

                'sklad_vid_oper',
                'sklad_vid_oper_name',

                'dt_create',
                'dt_update',

                'dt_transfer_start',    // начало передачи (from Sklad)
                'dt_transfered_ok',     // принято получателем (to Sklad)

                'wh_home_number', // ид текущего склада

                'wh_debet_top',
                'wh_debet_name',
                'wh_debet_element',
                'wh_debet_element_name',

                'wh_destination',
                'wh_destination_name',
                'wh_destination_element',
                'wh_destination_element_name',

                'user_id',
                "user_name",
                "user_group_id",

                "tz_id",
                "tz_name",
                "tz_date",

                "tz_user_edit_id",
                "tz_user_edit_name",

                "dt_to_work_signal",
                "dt_deadline",

                'array_tk_amort',
                'array_tk',

                'bar_code',


                // 'wh_dalee_element',
                // 'wh_dalee_element_name'


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
        //     dd($params['otbor']);

           $query = Sklad_transfer::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' =>5],
            ]);

            if (!$this->validate() ) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }


        $query->where(['=', 'wh_home_number',(integer) $params['otbor'] ]);

           $query->andFilterWhere(['like', 'sklad_vid_oper_name', $this->sklad_vid_oper_name]);

            $query->andFilterWhere(['like', 'wh_debet_top', $this->wh_debet_top]);
            $query->andFilterWhere(['like', 'wh_debet_name', $this->wh_debet_name]);
            $query->andFilterWhere(['like', 'wh_debet_element', $this->wh_debet_element]);
            $query->andFilterWhere(['like', 'wh_debet_element_name', $this->wh_debet_element_name]);

            $query->andFilterWhere(['like', 'wh_destination', $this->wh_destination]);
            $query->andFilterWhere(['like', 'wh_destination_name', $this->wh_destination_name]);
            $query->andFilterWhere(['like', 'wh_destination_element', $this->wh_destination_element]);
            $query->andFilterWhere(['like', 'wh_destination_element_name', $this->wh_destination_element_name]);


        if((integer) $this->wh_home_number>0)
            $query->andFilterWhere(['=', 'wh_home_number', (integer) $this->wh_home_number]);

        if((integer) $this->id>0)
            $query->andFilterWhere(['=', 'id', (integer) $this->id]);

        if((integer) $this->tz_id>0)
            $query->andFilterWhere(['=', 'tz_id', (integer) $this->tz_id]);

//            ->andFilterWhere(['>='  , 'dt_create',
//                date('Y-m-d',strtotime($_REQUEST['postpv']['dt_create']))
//            ])

//            ->andFilterWhere(['<='  , 'dt_create',
//                date('Y-m-d 23:59:59',strtotime($_REQUEST['postpv']['dt_create_end']." +1day" ))
//            ]);

        return $dataProvider;
    }


    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_into_wh($params)
    {
        $this->load($params);

            //$query = Sklad_transfer::find();

            $query = Sklad_transfer::find() -> with('sprwhelement_wh_dalee_element');

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
//                'pagination' => ['pageSize' =>3],
            ]);


            if (!$this->validate() ) {
                return $dataProvider;
            }


             //ddd($query);

            // ddd($this);


            //dd($params);


        $query->where(['=', 'wh_home_number',(integer) Sklad::getSkladIdActive() ]);

        $query->andFilterWhere(['like', 'wh_debet_name', $this->wh_debet_name]);
        $query->andFilterWhere(['like', 'wh_debet_element_name', $this->wh_debet_element_name]);
        $query->andFilterWhere(['like', 'wh_destination_name', $this->wh_destination_name]);
        $query->andFilterWhere(['like', 'wh_destination_element_name', $this->wh_destination_element_name]);

        if((integer) $this->id>0)
            $query->andFilterWhere(['=', 'id', (integer) $this->id]);

        if((integer) $this->tz_id>0)
            $query->andFilterWhere(['=', 'tz_id', (integer) $this->tz_id]);


        return $dataProvider;
    }
}
