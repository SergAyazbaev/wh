<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class postmod
 * @package app\models
 */
class postmod extends User_mod
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
            $this->load($params);
            $query = User_mod::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' =>10],
            ]);

            $dataProvider->setSort([
                'defaultOrder' => ['id'=>SORT_DESC],]);

            if (!$this->validate() ) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }

            //        $query->where(['=', 'id',(integer) $this->id ]);
            //        $query->andFilterWhere(['like', 'sklad_vid_oper_name', $this->sklad_vid_oper_name]);

        if((integer) $this->id>0)
            $query->andFilterWhere(['=', 'id', (integer) $this->id]);

        return $dataProvider;
    }


}



