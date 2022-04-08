<?php

namespace frontend\models;

//use Yii;
use yii\data\ActiveDataProvider;

//use yii\db\ActiveQueryInterface;
//use yii\helpers\ArrayHelper;
//use yii\mongodb\ActiveRecord;
//use yii\web\NotFoundHttpException;


class post_mobile_inventory extends Mobile_inventory
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
                'id_ap', // Автопарк
                'id_pe', // PE

                'bort', //PE
                'gos',  //PE
                'vin',  //PE

//                'check_bort',  //check_bort
//                'check_gos',  //check_gos
//                'aray_check',  //aray_check

//                'thing_group',  //GROUP
//                'thing_element',  //Element
//                'thing_count',  //COUNT
                'thing_barcode',  //Bar_code


//                'dt_create',            // Создано CRM
//                'dt_create_timestamp',  // Создано CRM
//                'mts_id',   // Ид ИСполнителя МТС

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

        //ddd($this);

        $query = Mobile_inventory::find()
            ->with(
                [
                    'sprwhelement_ap',
                    'sprwhelement_pe',

                    'spr_globam',
                    'spr_globam_element'

                ]);


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 50],
            ]
        );

        if (!$this->validate()) {
            return $dataProvider;
        }


//        'dt_create_timestamp' => ''
//        'bort' => ''
//        'gos' => '948'
//        'thing_count' => ''
//        'thing_barcode' => ''

        $query
            ->andFilterWhere(['like', 'gos', $this->gos])
            ->andFilterWhere(['like', 'bort', $this->bort]);

        return $dataProvider;
    }

}

