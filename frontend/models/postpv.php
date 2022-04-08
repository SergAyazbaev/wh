<?php

namespace frontend\models;


//use MongoDB\BSON\UTCDateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/** @noinspection PhpClassNamingConventionInspection */

/**
 * postpv represents the model behind the search form of `app\models\pv`.
 * @property mixed group_pv_name
 * @property mixed type_pv_name
 * @property mixed active_pv
 * @property mixed active_pv_name
 * @property mixed dt_create_mongo
 * @property mixed dt_create_end
 * @property mixed dt_create
 * @property mixed pv_health
 * @property mixed pvaction
 * @property mixed qr_code_pv
 * @property mixed pv_bee
 * @property mixed pv_kcell
 * @property mixed pv_imei
 */
class postpv extends Pv
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id',
                'name_pv',
                //'dt_create_pv',
                'dt_create',
                'date_mongo',
                'dt_create_end',
                'group_pv', 'type_pv',
                'group_pv_name', 'type_pv_name',

                'pv_health' ,  'pvaction',

                'bar_code_pv',
                'qr_code_pv',
                'pv_bee',
                'pv_kcell',
                'pv_imei',

                'parameters_pv', 'service_pv',
                'active_pv'
            ], 'safe'],


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


//    /**
//     * @param $timestamp
//     * @return UTCDateTime
//     */
//    function to_date($timestamp)
//    {
//        return new UTCDateTime($timestamp*1000); //  к виду: 1538381757000
//
//    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
//         dd($_REQUEST['postpv']);

        $this->load($params);

        $query = Pv::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' =>10],
            ]);


//            if (!$this->validate() ) {
//                // uncomment the following line if you do not want to return any records when validation fails
//                // $query->where('0=1');
//                return $dataProvider;
//            }





        // grid filtering conditions
        $query
//            ->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'id',  $this->id])
            ->andFilterWhere(['like', 'name_pv', $this->name_pv])

//            ->andFilterWhere(['>='  , 'dt_create',
//                date('d.m.Y',strtotime($_REQUEST['postpv']['dt_create']))
//            ])

//            ->andFilterWhere(['<='  , 'dt_create',
//                date('d.m.Y 23:59:59',strtotime($_REQUEST['postpv']['dt_create_end']." +1day" ))
//            ])


//            ->andFilterWhere(['<='  , 'dt_create_end',
//                    //date('d.m.Y H:i:s',strtotime($this->dt_create_end))  ])
//                    date('d.m.Y',strtotime($this->dt_create_end))  ])

            //->andFilterWhere(['<='  , 'dt_create', $this->dt_create_end ])

            ->andFilterWhere(['like', 'group_pv', $this->group_pv])
            ->andFilterWhere(['like', 'type_pv', $this->type_pv])

            ->andFilterWhere(['like', 'pv_health', $this->pv_health])
//            ->andFilterWhere(['like', 'pvaction', $this->pv_action])

            ->andFilterWhere(['like', 'bar_code_pv', $this->bar_code_pv])
            ->andFilterWhere(['like', 'qr_code_pv', $this->qr_code_pv])

            ->andFilterWhere(['like', 'pv_bee', $this->pv_bee])
            ->andFilterWhere(['like', 'pv_kcell', $this->pv_kcell])
            ->andFilterWhere(['like', 'pv_imei', $this->pv_imei])

            ->andFilterWhere(['like', 'group_pv_name', $this->group_pv_name])
            ->andFilterWhere(['like', 'type_pv_name', $this->type_pv_name])

            ->andFilterWhere(['like', 'parameters_pv', $this->parameters_pv])
            ->andFilterWhere(['like', 'service_pv', $this->service_pv])
            ->andFilterWhere(['like', 'active_pv', $this->active_pv])
            ->andFilterWhere(['like', 'active_pv_name', $this->active_pv_name]);


        //$this->dt_create_end=date('d.m.Y',strtotime($this->dt_create_end.'-1 day' ));

        return $dataProvider;
    }
}



