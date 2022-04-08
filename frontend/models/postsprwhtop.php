<?php

namespace frontend\models;

//use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * postsprtype represents the model behind the search form of `app\models\sprtype`.
 * @property mixed _id
 * @property mixed id
 * @property mixed parent_id
 * @property mixed name
 */
class postsprwhtop extends Sprwhtop
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id'], 'integer'],

            [[
                'name',
                'name_tha',
                'tx'
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
        $query = Sprwhtop::find();

            // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>10],
        ]);


        $this->load($params);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if( isset($this->id) && $this->id>0)
            $query->andFilterWhere(['=', 'id', (integer)$this->id]);


        // grid filtering conditions
        $query
            ->andFilterWhere(['like', '_id', $this->_id])
            //->andFilterWhere(['like', 'id', (integer)$this->id])
            ->andFilterWhere(['like', 'parent_id', $this->parent_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_tha', $this->name_tha])
            ->andFilterWhere(['like', 'tx', $this->tx]);

        return $dataProvider;
    }

    /**
     * Поисковый запрос только для движений по ЦС
     * =
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search_move_cs_company($params)
    {
        $this->load($params);

        $query = Sprwhtop::find();


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


//        ddd( $params );





        //wh_cs_parent_number
        if ((int)$this->id > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'id',
                    (int)$this->id,
                ]);
        }



        return $dataProvider;
    }

}
