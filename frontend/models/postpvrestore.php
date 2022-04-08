<?php

namespace frontend\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * postpvaction represents the model behind the search form of `app\models\pvaction`.
 * @property mixed type_action
 * @property mixed detail
 */
class postpvrestore extends Pvrestore
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
//                '_id',
                'id','pv_id','user_id',
                'user_name',
                'dt_create',

                //'type_action',
                'type_action_id',
                'type_action_name',

                'detail',
                'list_details',
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
        $query = Pvrestore::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>10],

        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             //$query->where('0=1');
            return $dataProvider;
        }

        if (isset($this->pv_id))
            $query->andFilterWhere([ 'pv_id'=> (integer)$this->pv_id ]);

        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'dt_create', $this->dt_create])

            //->andFilterWhere(['like', 'type_action', $this->type_action])
            ->andFilterWhere(['like', 'type_action_id', $this->type_action_id])
            ->andFilterWhere(['like', 'type_action_name', $this->type_action_name])

            ->andFilterWhere(['like', 'detail', $this->detail])
            ->andFilterWhere(['like', 'comments', $this->comments])
        ;

        return $dataProvider;
    }
}



