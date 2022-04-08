<?php

namespace frontend\models;

//use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
//use app\models\Typemotion;

/**
 * postpvaction represents the model behind the search form of `app\models\pvaction`.
 */
class postpvtypemotion extends Typemotion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                '_id', 'id',
                'name',
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
        //dd($params);

        $query = Typemotion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             //$query->where('0=1');
            return $dataProvider;
        }


         //$query->where(['>=', 'id', (integer) $this->id]);
         //$query->where(['>=', 'id', $this->id]);


        // grid filtering conditions
        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'id', $this->id]);

            //dd($query);


        return $dataProvider;
    }
}
