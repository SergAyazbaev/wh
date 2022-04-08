<?php

namespace frontend\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class posttz
 * @package app\models
 */
class post_stat_tz extends Tz
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            ['dt_deadline1', 'default',
                'value' => date('d.m.Y 00:00:00',strtotime("now -3 days" )) ],

            ['dt_deadline2', 'default',
                'value' => date('d.m.Y 23:59:59',strtotime("now" )) ],


            [[
                "id",
                "name_tz",
                "multi_tz",

                'dt_create',
                'dt_deadline',

            ], 'safe'],

            [[ 'id', ],   'unique'],
            [['id','multi_tz' ] , 'integer'],


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
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_stat($params)
    {
        $this->load($params);
        $query = Tz::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>10],
        ]);


        if (!$this->validate() ) {
            return $dataProvider;
        }



//        $now_time=date('d.m.Y H:i:s',strtotime('now'));
//        $now_start_day=date('d.m.Y H:i:s',strtotime('today'));
//        $now_end_day=date('d.m.Y H:i:s',strtotime('today +24 hours'));
//
//
//        if(isset($this->three) && $this->three==1 ){ //Просрочено
//            $query
//                ->andFilterWhere(['<='  , 'dt_deadline', $now_end_day ]);
//        }
//
//        elseif(isset($this->three) && $this->three==2 ){ /// Сегодня
//            $query
//                ->andFilterWhere(['>='  , 'dt_deadline', $now_start_day ])
//                ->andFilterWhere(['<='  , 'dt_deadline', $now_end_day ]);
//        }
//
//        elseif(isset($this->three) && $this->three==3 ){ // Еще зеленое
//            $query
//                ->andFilterWhere(['>='  , 'dt_deadline', $now_time ]);
//        }

        ////////////////////////////////
        ///
        if( isset($this->multi_tz) && $this->multi_tz>0)
        $query
            ->andFilterWhere(['=',      'multi_tz', (int) $this->multi_tz]);

        $query
            ->andFilterWhere(['like',   'id', $this->id])
            ->andFilterWhere(['like',   'name_tz',  $this->name_tz]);


        return $dataProvider;
    }


}





