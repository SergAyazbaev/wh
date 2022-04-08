<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;


class posttz_to_work extends Tz_to_work
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[
                '_id',
//                'id',
//                'tz_id',

//                'dt_create' ,
//                'dt_deadline',
//                'dt_deadline1',
//                'dt_deadline2',

//                'user_create_group_id',
//                'user_create_id',
//                'user_create_name',

//                'name_tz' ,
//                'name_tk' ,

//                'captcha' ,
//                'three' ,

//                'status_state',
//                'status_create_user',
//                'status_create_date',


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
//         dd($_REQUEST['postpv']);

        $this->load($params);
        $query = Tz_to_work::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' =>10],
            ]);


            if (!$this->validate() ) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }


        $query
            ->andFilterWhere(['like', 'id',  $this->id]);


        return $dataProvider;
    }
}



