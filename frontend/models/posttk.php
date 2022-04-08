<?php

namespace frontend\models;


use MongoDB\BSON\UTCDateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

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
class posttk extends Tk
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_id',
                'name_tk',
                'dt_create',
                'dt_edit',

                'user_edit_name',

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


    /**
     * @param $timestamp
     * @return UTCDateTime
     */
    function to_date($timestamp)
    {
        return new UTCDateTime($timestamp*1000); //  к виду: 1538381757000

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

        $query = Tk::find();

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
        $query->andFilterWhere(['like', '_id', $this->_id])
//            ->andFilterWhere(['like', 'id',  $this->id])
            ->andFilterWhere(['like', 'name_tk', $this->name_tk])

            ->andFilterWhere(['like', 'dt_edit', $this->dt_edit , false])

            ->andFilterWhere(['like'  , 'dt_create', $this->dt_edit, false ] )

            ->andFilterWhere(['like'  , 'user_edit_name', $this->user_edit_name, false ] );



//            ->andFilterWhere(['<='  , 'dt_create',
//                date('Y-m-d 23:59:59',strtotime($_REQUEST['postpv']['dt_create_end']." +1day" ))
//            ]);



        //$this->dt_create_end=date('Y-m-d',strtotime($this->dt_create_end.'-1 day' ));

        return $dataProvider;
    }
}



