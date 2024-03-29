<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/** @noinspection PhpClassNamingConventionInspection */

/**
 * postinstallset represents the model behind the search form of `app\models\installset`.
 */
class postinstallset extends installset
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id', 'id', 'install_group', 'ids_pv', 'name'], 'safe'],
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
        $query = installset::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            ->andFilterWhere(['like', 'install_group', $this->install_group])
            ->andFilterWhere(['like', 'ids_pv', $this->ids_pv])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
