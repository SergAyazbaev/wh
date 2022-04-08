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
 * @property mixed tx
 */
class postsprrestoreelement extends Sprrestoreelement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id',
            '_id', 'name', 'tx'], 'safe'],
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

        $query = Sprrestoreelement::find();


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


        if (isset($this->id) && $this->id>0 )
            $query->where(['=','id' , (integer) $this->id ]);


        if(isset($this->parent_id) && $this->parent_id>0)
            $query->andFilterWhere(['=','parent_id' , (integer) $this->parent_id ]);


//        $query
//            //            ->andFilterWhere(['like', '_id', $this->_id])
//            ->andFilterWhere(['like', 'name', $this->name])
//            ->andFilterWhere(['like', 'tx', $this->tx]);


        return $dataProvider;
    }
}
