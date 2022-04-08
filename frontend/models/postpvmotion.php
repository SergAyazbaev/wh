<?php

namespace frontend\models;

//use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use app\models\Pvmotion;

/**
 * postpvaction represents the model behind the search form of `app\models\pvaction`.
 */
class postpvmotion extends Pvmotion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id',
                //'id',

                'pv_id',
                'name',
                'user_id',
                'type_action',
                'content',
                'comments',
                'document',

                'wh_deb_top',
                'wh_deb_top_name',
                'wh_deb_element',

                'wh_cred_top',
                'wh_cred_top_name',
                'wh_cred_element',


                'dt_create',
                'dt_create_start',
                'dt_create_stop',

                'dt_time_start',
                'dt_time_stop',


                'type_action',
                'type_action_tx',
                'diagnoz',
                'fact_bag',


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

        $query = Pvmotion::find();

//        [postpvmotion] => Array
//    (
//        [pv_id] => 1
//            [dt_create_start] => 2018-10-22
//            [dt_create_stop] => 2018-11-01
//        )

//        dd($params);


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


//        if (isset($this->dt_create_start) )   {
//            //dd($this->dt_create_start.' '.$this->dt_time_start);
//
//            $query
//                ->andFilterWhere(['>', 'dt_create', $this->dt_create_start.' '.$this->dt_time_start ]);
//        }
//
//        if (isset($this->dt_create_stop) )   {
//            $query
//                ->andFilterWhere(['<=', 'dt_create', $this->dt_create_stop.' '.$this->dt_time_stop]);
//        }



        // grid filtering conditions
        $query

            //   ->andFilterWhere(['like', '_id', $this->_id])
            //   ->andFilterWhere(['like', 'id', $this->n_id])
            //->andFilterWhere(['like', 'id', $this->id])
            //->andFilterWhere(['like', 'pv_id', $this->pv_id])

            //->andFilterWhere(['like', 'name', $this->name])

            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'user_id', $this->user_id])

            ->andFilterWhere(['like', 'type_action', $this->type_action_tx]) // перекресное значение

            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'document', $this->document])


            ->andFilterWhere(['like', 'wh_deb_top', $this->wh_deb_top])
            ->andFilterWhere(['like', 'wh_deb_top_name', $this->wh_deb_top_name])
            ->andFilterWhere(['like', 'wh_deb_element', $this->wh_deb_element])

            ->andFilterWhere(['like', 'wh_cred_top', $this->wh_cred_top])
            ->andFilterWhere(['like', 'wh_cred_top_name', $this->wh_cred_top_name])
            ->andFilterWhere(['like', 'wh_cred_element', $this->wh_cred_element])

            ->andFilterWhere(['like', 'comments', $this->comments])
            ;




        return $dataProvider;
    }
}


