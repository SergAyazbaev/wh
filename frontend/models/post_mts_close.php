<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_mts_close extends Mts_montage
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'sklad_id', /// ИД накладной СКЛАДА
                'barcode_montage',      // BAD

                //                'id',
                //                'id_ap', // Автопарк
                //                'id_pe', // PE
                //                'mts_id',
            ],
                'safe'
            ],


        ];
    }
//    public function attributeLabels()
//    {
//        return [
//            'job_fin_timestamp' => 'Финал',
//            'job_close_timestamp' => 'Закрытие',
//        ];
//    }


    /**
     * Search STD
     *
     * @param $para
     * @return ActiveDataProvider
     */
    public function search($para)
    {
        $this->load($para);

//        if (isset($para) && !empty($para)) {
//            //            ddd($para);
//            ddd($this);
//        }

        $query = Mts_montage::find()
            ->with(
                [
                    'sprwhtop_wh_dalee',
                    'sprwhelement_wh_dalee_element',
                ]);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]
        );

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query
            ->andFilterWhere(['like', 'barcode_montage', $this->barcode_montage]);

        return $dataProvider;
    }

}





