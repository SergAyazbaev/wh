<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class posttz extends Tz
{

    public function rules()
    {
        return [
            [['id'], 'safe'],
        ];
    }

    const SCENARIO_OPEN_PAGE = 'open_first_page';


    public function scenarios()
    {
        $scenarios[self::SCENARIO_DEFAULT] = [
            'id',
            'name_tz',
            'multi_tz',
            'wh_cred_top_name',
        ];
        return $scenarios;
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);


        $query = Tz::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );


        if (!$this->validate()) {
            return $dataProvider;
        }

        $now_time = strtotime('now');
        $now_start_day = strtotime('today');
        $now_end_day = strtotime('today +24 hours');


        if (isset($params['posttz']['three'])) {

            if (isset($params['posttz']['three']) && $params['posttz']['three'] == 1) { //Просрочено
                $query
                    //->andFilterWhere( [ '<=', 'dt_deadline', $now_end_day ] );
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 2) { /// Сегодня
                $query
                    ->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_start_day])
                    ->andFilterWhere(['<=', 'dt_deadline_timestamp', (int)$now_end_day]);

            } elseif (isset($params['posttz']['three']) && $params['posttz']['three'] == 3) { // Еще зеленое
                $query->andFilterWhere(['>=', 'dt_deadline_timestamp', (int)$now_time]);
            }

        }


        /// ID
        if (isset($this->id) && $this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }


        $query
            ->andFilterWhere(['like', 'wh_cred_top_name', $this->wh_cred_top_name])
            ->andFilterWhere(['like', 'name_tz', $this->name_tz]);

        return $dataProvider;
    }


    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_into($params)
    {
        $this->load($params);

        $query = Tz::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $now_time = date('d.m.Y H:i:s', strtotime('now'));
        $now_start_day = date('d.m.Y H:i:s', strtotime('today'));
        $now_end_day = date('d.m.Y H:i:s', strtotime('today +24 hours'));


        if (isset($this->three) && $this->three == 1) { //Просрочено
            $query
                ->andFilterWhere(['<=', 'dt_deadline', $now_end_day]);
        } elseif (isset($this->three) && $this->three == 2) { /// Сегодня
            $query
                ->andFilterWhere(['>=', 'dt_deadline', $now_start_day])
                ->andFilterWhere(['<=', 'dt_deadline', $now_end_day]);
        } elseif (isset($this->three) && $this->three == 3) { // Еще зеленое
            $query
                ->andFilterWhere(['>=', 'dt_deadline', $now_time]);
        }

        //ddd($this);

        if ((int)$this->id > 0) {
            $query->
            andFilterWhere(['=', 'id', (int)$this->id]);
        }

        if ((int)$this->multi_tz > 0) {
            $query->
            andFilterWhere(['=', 'multi_tz', (int)$this->multi_tz]);
        }

        $query
            ->andFilterWhere(['like', 'wh_cred_top_name', $this->wh_cred_top_name])
            ->andFilterWhere(['like', 'wh_cred_top_name', $this->wh_cred_top_name])
            ->andFilterWhere(['like', 'user_create_id', $this->user_create_id])
            ->andFilterWhere(['like', 'name_tz', $this->name_tz]);


        return $dataProvider;
    }

}
