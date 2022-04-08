<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;


class post_mts_close_demontage extends Mts_demontage
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'sklad_id', /// ИД накладной СКЛАДА
                'act', //
                'bar_code',
                //'barcode_montage',      // BAD

                //                'id',
                //                'id_ap', // Автопарк
                //                'id_pe', // PE
                //                'mts_id',
            ],
                'safe'
            ],


        ];
    }



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

        $query = Mts_demontage::find()
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
            ->andFilterWhere(['like', 'bar_code', $this->bar_code]);

        return $dataProvider;
    }

}





