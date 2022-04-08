<?php

namespace frontend\models;


use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class post_barcode_pool extends Barcode_pool
{


    public function rules()
    {
        return [
            [
                [
                    'id',
                    'element_id',
                    'parent_name',
                    'bar_code',
                    'barcode_consignment_id',
                    'write_off',
                    'write_off_doc',
                    'write_off_note',

                    'turnover',
                    'turnover_alltime',
                ],
                'safe',
            ],
        ];

    }


    public function attributeLabels()
    {
        return [
            'id' => '#',
            'element_id' => '111',
            'parent_name' => 'Название устройства',
            'bar_code' => 'Штрих код',
            'barcode_consignment_id' => 'Ид Партии',
            'write_off' => 'Списано',
            'write_off_doc' => 'Основание',
            'write_off_note' => 'Причина',
            'turnover' => 'Обр.',
            'turnover_alltime' => 'Обр. все',
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
     * @throws InvalidArgumentException
     */
    public function search($params)
    {

        //    "id" : 3,
        //    "element_id" : 2,
        //    "bar_code" : "19600002383",
        //    "barcode_consignment_id" : 1,
        //    "write_off" : 1,
        //    "write_off_doc" : "списание №698",
        //    "write_off_note" : "вышло из строя"

        $query = static::find()
            ->with(
                [
                    'barcode_consignment2',
                    'spr_globam_element',
                    'spr_globam_element.spr_globam',
                ]);


        //ddd($query);


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10],
            ]);


        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */
        $dataProvider->setSort(
            [
                'attributes' => [
                    'parent_name' => [
                        'asc' => [
                            'element_id' => SORT_ASC],
                        'desc' => [
                            'element_id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'bar_code' => [
                        'asc' => [
                            'bar_code' => SORT_ASC],
                        'desc' => [
                            'bar_code' => SORT_DESC],
                    ],
                    'barcode_consignment_id' => [
                        'asc' => [
                            'barcode_consignment_id' => SORT_ASC,'bar_code' => SORT_ASC],
                        'desc' => [
                            'barcode_consignment_id' => SORT_DESC,'bar_code' => SORT_DESC],

                    ],
                    'id' => [
                        'asc' => [
                            'id' => SORT_ASC],
                        'desc' => [
                            'id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'write_off',
                    'write_off_doc',
                    'write_off_note',
                    'turnover',
                    'turnover_alltime',

                    'defaultOrder' => [
                        'element_id' => SORT_ASC,
                    ],
                ],
            ]);

//            'write_off',
//                    'write_off_doc',
//                    'write_off_note',

        $this->load($params);


        if (!$this->validate()) {
            return $dataProvider;
        }


        //0 => 'id'
        //1 => 'element_id'
        //2 => 'parent_name'
        //3 => 'bar_code'
//        ddd($this);
//write_off
//write_off_note

        if (isset($this->id) && (int)$this->id > 0) {
            $query->where(['=', 'id', (int)$this->id]);
        }

        if (!empty($this->parent_name)) {
            $query->where(['=', 'element_id', (integer)$this->parent_name]);
        }


        if (isset($this->write_off) && $this->write_off > 0) {
            $query->andFilterWhere(
                ['OR',
                    ['=', 'write_off', (string)$this->write_off,],
                    ['=', 'write_off', (integer)$this->write_off,],
                ]
            );
        }


        if ($this->write_off_doc) {
            $query->andFilterWhere(['like', 'write_off_doc', $this->write_off_doc]);
        }

        if ($this->write_off_note) {
            $query->andFilterWhere(['like', 'write_off_note', $this->write_off_note,]);
        }

        if ($this->bar_code) {
            $query->andFilterWhere(['like', 'bar_code', (string)$this->bar_code]);
        }


       // ddd($this);

        if ((int)$this->barcode_consignment_id>0) {
            $query->andFilterWhere(['=', 'barcode_consignment_id', (int)$this->barcode_consignment_id]);
        }

        if (isset($this->turnover) && (int)$this->turnover > 0) {
            $query->where(['=', 'turnover', (int)$this->turnover]);
        }

        if (isset($this->turnover_alltime) && (int)$this->turnover_alltime > 0) {
            $query->where(['=', 'turnover_alltime', (int)$this->turnover_alltime]);
        }



        return $dataProvider;

    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws InvalidArgumentException
     */
    public function search_with_consigment($params)
    {
        $this->load($params);

        $query = static::find()
            ->with(
                [
                    'spr_globam_element',
                    'spr_globam_element.spr_globam',
                    'barcode_consignment',
                    //'barcode_consignment2',
                ])
            ->where(
                [
                    '>',
                    'barcode_consignment_id',
                    0,
                ]);


        //ddd($params);


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10],
            ]);


        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */
        $dataProvider->setSort(
            [
                'attributes' => [
                    'turnover',
                    'element_id' => [
                        'asc' => [
                            'element_id' => SORT_ASC],
                        'desc' => [
                            'element_id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'parent_name' => [
                        'asc' => [
                            'element_id' => SORT_ASC],
                        'desc' => [
                            'element_id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'bar_code' => [
                        'asc' => [
                            'bar_code' => SORT_ASC],
                        'desc' => [
                            'bar_code' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'barcode_consignment_id' => [
                        'asc' => [
                            'barcode_consignment_id' => SORT_ASC],
                        'desc' => [
                            'barcode_consignment_id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'barcode_consignment.name' => [
                        'asc' => [
                            'barcode_consignment_id' => SORT_ASC],
                        'desc' => [
                            'barcode_consignment_id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'id' => [
                        'asc' => [
                            'id' => SORT_ASC],
                        'desc' => [
                            'id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],
                    'defaultOrder' => [
                        'element_id' => SORT_ASC,
                    ],
                ],
            ]);


        if (!$this->validate()) {
            return $dataProvider;
        }


        //0 => 'id'
        //1 => 'element_id'
        //2 => 'parent_name'
        //3 => 'bar_code'

        if (isset($this->id) && $this->id > 0) {
            $query->where(
                [
                    'OR',
                    [
                        '=',
                        'id',
                        (string)$this->id,
                    ],
                    [
                        '=',
                        'id',
                        (integer)$this->id,
                    ],
                ]
            );
        }

        if ($this->element_id) {
            $query->where(
                [
                    '=',
                    'element_id',
                    (integer)$this->element_id,
                ]);
        }


        if ($this->parent_name) {
            //ddd($this->parent_name);

            $query->where(
                [
                    '=',
                    'element_id',
                    (integer)$this->parent_name,
                ]);
        }


        if ($this->barcode_consignment_id) {
            $query->where(
                [
                    '=',
                    'barcode_consignment_id',
                    (integer)$this->barcode_consignment_id,
                ]);
        }

        $query
            ->andWhere(
                [
                    'like',
                    'bar_code',
                    (string)$this->bar_code,
                ]);

        if ($this->turnover) {
            //ddd($this->parent_name);

            $query->where(
                [
                    '=',
                    'turnover',
                    (integer)$this->turnover,
                ]);
        }


        return $dataProvider;

    }

}