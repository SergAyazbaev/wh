<?php

namespace frontend\models;


use yii\data\ActiveDataProvider;

class postsklad_mts extends Sklad
{

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_god($params)
    {
        $this->load($params);

        $query = self::find()
            ->where(
                ['AND',
                    ['==', 'wh_home_number', (int)4599],  //'Татубаев Т. С.'
                    ['==', 'wh_debet_element', (int)4431], ///Дежурный'
                    ///
                    ["OR",
                        ['==', 'sklad_vid_oper', Sklad::VID_NAKLADNOY_PRIHOD],
                        ['==', 'sklad_vid_oper', (string)Sklad::VID_NAKLADNOY_PRIHOD],
                    ]
                ]
            );


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_bad($params)
    {
        $this->load($params);

        $query = self::find()
            ->where(
                ['AND',
                    ['==', 'wh_home_number', (int)4599],  //'Татубаев Т. С.'
                    ['<>', 'wh_debet_element', (int)4431], /// НЕ ОТ ДежурНОГО !!!!!!!!
                    ['==', 'sklad_vid_oper', (string)Sklad::VID_NAKLADNOY_PRIHOD],
                ]
            );


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search_one_naklad($params)
    {
        $this->load($params);

        $query = self::find()->where(['==', 'id', (int)$params['id']]);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            return $dataProvider;
        }


        return $dataProvider;
    }

}



