<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;

/**
 * @property mixed _id
 * @property mixed id
 * @property mixed parent_id
 * @property mixed name
 * @property mixed tx
 */
class postsprwhelement_change extends Sprwhelement_change
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                '_id',
                'id',
                'parent_id',

                'n_bort',
                'n_gos',
                'old_bort',
                'old_gos',

                'user_id',
                'dt_cr_timestamp',

                'doc_cr',
                'doc_num',
                'tx',

            ],
                'safe'],

        ];
    }

    /**
     * Search
     *-
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = static::find()
            ->with([
                'sprwhelement',
                'sprwhelement.sprwhtop'
            ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        ///
        if (isset($this->id) && $this->id > 0) {
            $query->where(['=', 'id', (integer)$this->id]);
        }

        ///
        if ($this->parent_id) {
            $query->where(['=', 'parent_id', (integer)$this->parent_id]);
            //unset( $params );
        }

        ///
        $query
            ->andFilterWhere(['like', 'n_bort', $this->n_bort])
            ->andFilterWhere(['like', 'n_gos', $this->n_gos])
            ->andFilterWhere(['like', 'old_bort', $this->old_bort])
            ->andFilterWhere(['like', 'old_gos', $this->old_gos])
            ->andFilterWhere(['like', 'doc_num', $this->doc_num])
            ->andFilterWhere(['like', 'tx', $this->tx]);


        return $dataProvider;
    }


}
