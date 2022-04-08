<?php /** @noinspection ALL */

namespace frontend\models;




use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * Class postcross
 * @package app\models
 */
class postcross extends Cross
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
//                '_id',

//                'id',
//                'parent_id',

//                'dt_print',
//                'dt_create',
//                'dt_create_end',

//                'dt_edit',

//                'bar_code_aa',
//                'bar_code_int',
//                'bar_code_cross',

//                'name',
//                'html_text',

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

        $query = Cross::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' =>10],
            ]);


//        if (!$this->validate() ) {
//                // uncomment the following line if you do not want to return any records when validation fails
//                // $query->where('0=1');
//                return $dataProvider;
//            }


        // grid filtering conditions
        //$query->andFilterWhere(['like', '_id', $this->_id]);

//            ->andFilterWhere(['>='  , 'dt_create',
//                date('Y-m-d',strtotime($_REQUEST['postpv']['dt_create']))
//            ]);

//            ->andFilterWhere(['<='  , 'dt_create',
//                date('Y-m-d 23:59:59',strtotime($_REQUEST['postpv']['dt_create_end']." +1day" ))
//            ]);

        return $dataProvider;
    }
}



