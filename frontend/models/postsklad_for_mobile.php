<?php

namespace frontend\models;


use yii\data\ActiveDataProvider;

class postsklad_for_mobile extends Sklad
{
    public function rules()
    {
        return [
            [['_id', 'id', 'name', 'tx'], 'safe'],
        ];
    }

    /**
     * FOR Mobile Montage  (Еще не ИСПОЛНЕННЫЙ)
     * Поисковый запрос
     * =
     * @param array $params
     *
     * @param $sklad
     * @param $array_black_ids
     * @return ActiveDataProvider
     */
    public function search_for_montage_open($params, $sklad, $array_black_ids)
    {
        $this->load($params);

        $query = Sklad::find()
            ->select([
                'id',// => 18677
                'wh_home_number',// => 4599
                'sklad_vid_oper',// => '2'
                'wh_debet_element',// => 4431
                'wh_destination_element',// => 4599

                'wh_dalee',// => 14
                'wh_dalee_element',// => 4603
                'sprwhtop_wh_dalee.name',     ///
                'sprwhelement_wh_dalee_element.name', ///
                ///
                'sprwhelement_wh_dalee_element.nomer_borta', ///
                'sprwhelement_wh_dalee_element.nomer_gos_registr', ///
                'sprwhelement_wh_dalee_element.nomer_vin', ///
                'sprwhelement_wh_dalee_element.final_destination', ///
                'sprwhelement_wh_dalee_element.deactive', ///
                'sprwhelement_wh_dalee_element.f_first_bort', ///


                'tz_id',// => 0
                'dt_create_timestamp',// => 1589865684
                'array_tk_amort'
            ])
            ->with(
                [
                    'sprwhtop_wh_dalee',
                    'sprwhelement_wh_dalee_element',
                ])
            ->where(
                ['AND',
                    ['==', 'wh_home_number', (int)$sklad],
                    ['==', 'sklad_vid_oper', (string)Sklad::VID_NAKLADNOY_PRIHOD],
                    ['==', 'wh_debet_element', (int)4431], // 'Guidejet TI. Склад инженеров эксплуатации Дежурный'
                    ['>', 'wh_dalee', (int)0],          // DALEE
                    ['>', 'wh_dalee_element', (int)0],  // DALEE

                    ['NOT IN', 'id', $array_black_ids],  // BLACK LIST OPEN
                ]
            );


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);

        $dataProvider->setSort(
            [
                'defaultOrder' => ['id' => SORT_DESC],
            ]);

        if (!$this->validate()) {
            return $dataProvider;
        }


        if ((int)$this->id > 0) {
            $query->andFilterWhere(['==', 'id', (int)$this->id]);
        }

        return $dataProvider;
    }

    /**
     * FOR Mobile Montage  (Еще не УЖЕ ИСПОЛНЕННЫЙ СПИСОК)
     * Поисковый запрос
     * =
     * @param array $params
     *
     * @param $sklad
     * @param $array_black_ids
     * @return ActiveDataProvider
     */
    public function search_for_montage_close($params, $sklad, $array_black_ids)
    {
        $this->load($params);


        $query = Sklad::find()
            ->select([
                'id',// => 18677
                'wh_home_number',// => 4599
                'sklad_vid_oper',// => '2'
                'wh_debet_element',// => 4431
                'wh_destination_element',// => 4599

                'wh_dalee',// => 14
                'wh_dalee_element',// => 4603
                'sprwhtop_wh_dalee.name',     ///
                'sprwhelement_wh_dalee_element.name', ///
                ///
                'sprwhelement_wh_dalee_element.nomer_borta', ///
                'sprwhelement_wh_dalee_element.nomer_gos_registr', ///
                'sprwhelement_wh_dalee_element.nomer_vin', ///
                'sprwhelement_wh_dalee_element.final_destination', ///
                'sprwhelement_wh_dalee_element.deactive', ///
                'sprwhelement_wh_dalee_element.f_first_bort', ///


                'tz_id',// => 0
                'dt_create_timestamp',// => 1589865684
                'array_tk_amort'
            ])
            ->with(
                [
                    'sprwhtop_wh_dalee',
                    'sprwhelement_wh_dalee_element',
                ])
            ->where(
                ['AND',
                    ['==', 'wh_home_number', (int)$sklad],
                    ['==', 'sklad_vid_oper', (string)Sklad::VID_NAKLADNOY_PRIHOD],
                    ['==', 'wh_debet_element', (int)4431], // 'Guidejet TI. Склад инженеров эксплуатации Дежурный'
                    ['>', 'wh_dalee', (int)0],          // DALEE
                    ['>', 'wh_dalee_element', (int)0],  // DALEE

                    ['IN', 'id', $array_black_ids],  // BLACK LIST
                ]
            );


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);

        $dataProvider->setSort(
            [
                'defaultOrder' => ['id' => SORT_DESC],
            ]);

        if (!$this->validate()) {
            return $dataProvider;
        }


        if ((int)$this->id > 0) {
            $query->andFilterWhere(['==', 'id', (int)$this->id]);
        }

        return $dataProvider;
    }

}



