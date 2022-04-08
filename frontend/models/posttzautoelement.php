<?php

namespace frontend\models;

use Yii;
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
class posttzautoelement extends Tzautoelement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'tz_id',
                'id',
                'parent_id',
                'name',
                'nomer_borta',
                'nomer_gos_registr',
                'nomer_vin',
                'tx'
            ],
                'safe'],
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
        if (isset($params['tz_id'])) {
            $tz_id = $params['tz_id'];
        } else {
            $tz_id = Yii::$app->request->queryParams;
        }
        //dd($tz_id);

        $query = posttzautoelement::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' =>10],
        ]);


        if (!$this->validate() ) {
            return $dataProvider;
        }


        $query->andFilterWhere(['==', 'tz_id', (integer)$tz_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'nomer_borta', $this->nomer_borta])
            ->andFilterWhere(['like', 'nomer_gos_registr', $this->nomer_gos_registr])
            ->andFilterWhere(['like', 'nomer_vin', $this->nomer_vin])
            ->andFilterWhere(['like', 'tx', $this->tx]);

        return $dataProvider;
    }
}
