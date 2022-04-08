<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * Class posttz
 * @package app\models
 */
class postsklad_shablon extends Shablon
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['_id',

                "id",
                "shablon_name",

                'array_tk_amort',
                'array_tk',

                'user_id',
                'user_name',
                'user_group_id',

                'wh_home_number',

                'comments',

            ], 'safe'],


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

            $query = Shablon::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' =>20],
            ]);


            if (!$this->validate() ) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }


            if(isset($this->id) && $this->id>0 ){
                $query
                    ->andFilterWhere(['='  , 'id', (integer)$this->id ]);
            }


            $query
                ->andFilterWhere(['like', 'shablon_name',  $this->shablon_name]);

        return $dataProvider;
    }


    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_into($params)
    {
        $this->load($params);

        $query = Shablon::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>10],
        ]);


        if (!$this->validate() ) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $now_time=date('Y-m-d H:i:s',strtotime('now'));
        $now_start_day=date('Y-m-d H:i:s',strtotime('today'));
        $now_end_day=date('Y-m-d H:i:s',strtotime('today +24 hours'));


        if(isset($this->three) && $this->three==1 ){ //Просрочено
            $query
                ->andFilterWhere(['<='  , 'dt_deadline', $now_end_day ]);
        }

        elseif(isset($this->three) && $this->three==2 ){ /// Сегодня
            $query
                ->andFilterWhere(['>='  , 'dt_deadline', $now_start_day ])
                ->andFilterWhere(['<='  , 'dt_deadline', $now_end_day ]);
        }

        elseif(isset($this->three) && $this->three==3 ){ // Еще зеленое
            $query
                ->andFilterWhere(['>='  , 'dt_deadline', $now_time ]);
        }

        $query
            ->andFilterWhere(['like',   'id',  $this->id])
            ->andFilterWhere(['like',   'wh_cred_top_name',  $this->wh_cred_top_name])

            ->andFilterWhere(['=',      'multi_tz',  $this->multi_tz])
            ->andFilterWhere(['like',   'user_create_id',  $this->user_create_id])

            ->andFilterWhere(['like',   'name_tz',  $this->name_tz]);


        return $dataProvider;
    }

}





