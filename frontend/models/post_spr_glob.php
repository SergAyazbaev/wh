<?php /** @noinspection ALL */

namespace frontend\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * Class postglobalam
 * @package app\models
 */
class post_spr_glob extends Spr_glob
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id', 'id', 'name', 'tx'], 'safe'],
        ];
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
        $query = Spr_glob::find();

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

        // grid filtering conditions
        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'tx', $this->tx]);

        return $dataProvider;
    }
}
