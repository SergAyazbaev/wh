<?php

namespace frontend\models;

use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class post_consignment extends Consignment
{


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'group_id',
                    'element_id',
                    'name',
                    'tx',

                    'dt_create_timestamp',
                    'dt_create',
                    'dt_one_day',
                    'dt_one_day_array',

                    'cena',

                    'array_update',
                    'spr_globam.name',
                    'spr_globam_element.name',
                ],
                'safe',
            ],
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

        $this->load($params);

        $query = static::find()->with(
            'spr_globam',
            'spr_globam_element'
        );


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
                'sort' => [
                    'defaultOrder' => [
                        'dt_create_timestamp' => SORT_ASC,
                        'id' => SORT_ASC,
                    ]
                ],
            ]);


        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */
        $dataProvider->setSort(
            [

                'attributes' => [

                    'group_id' => [
                        'asc' => [
                            'group_id' => SORT_ASC,
                            'cena' => SORT_ASC
                        ],
                        'desc' => [
                            'group_id' => SORT_DESC,
                            'cena' => SORT_DESC
                        ],

                    ],

                    'element_id' => [
                        'asc' => [
                            ['element_id' => [SORT_ASC, SORT_NUMERIC]],
                            ['cena' => [SORT_ASC, SORT_NUMERIC]]
                        ],
                        'desc' => [
                            ['element_id' => [SORT_DESC, SORT_NUMERIC]],
                            ['cena' => [SORT_DESC, SORT_NUMERIC]]
                        ],
                    ],

                    'dt_create' => [
                        'asc' => [
                            ['dt_create_timestamp' => [SORT_ASC]],
                            ['cena' => [SORT_ASC]]
                        ],
                        'desc' => [
                            ['dt_create_timestamp' => [SORT_DESC]],
                            ['cena' => [SORT_DESC]]
                        ],
                    ],



                    'id',
                    'cena',
                    'name',
                    'tx',


                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],

            ]);


        if (!$this->validate()) {
            return $dataProvider;
        }


//        ddd($params);
//        ddd($this);


//            $query->where(
//                    [
//                        'like',
//                        'spr_globam_element.name',
//                        $params['post_consignment']['element_id'],
//                    ]
//            );



            ////
        if (isset($params['dt_one_day'])) {

            $params['post_consignment']['dt_start'] = date('d.m.Y 00:00:00', strtotime($params['dt_one_day']));
            $params['post_consignment']['dt_stop'] = date('d.m.Y 23:59:59', strtotime($params['dt_one_day']));

//                ddd($params);

            // Дата фильтр dt_create
            if (isset($params['post_consignment']['dt_start']) && !empty($params['post_consignment']['dt_start'])) {
                // ddd($params);
                $query->andFilterWhere(
                    [
                        '>=',
                        'dt_create_timestamp',
                        strtotime($params['post_consignment']['dt_start']),
                    ]);
                $query->andFilterWhere(
                    [
                        '<=',
                        'dt_create_timestamp',
                        strtotime($params['post_consignment']['dt_stop']),
                    ]);
            }
        }


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


        $query
            ->andFilterWhere(
                [
                    'like',
                    'name',
                    $this->name,
                ])
            ->andFilterWhere(
                [
                    'like',
                    'tx',
                    $this->tx,
                ]);


        return $dataProvider;
    }


}
