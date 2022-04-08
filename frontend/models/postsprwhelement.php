<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * postsprtype represents the model behind the search form of `app\models\sprtype`.
 * @property mixed _id
 * @property mixed id
 * @property mixed parent_id
 * @property mixed name
 * @property mixed tx
 */
class postsprwhelement extends Sprwhelement
{
    /* Вычисляемое поле */
    //    public $fullName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'parent_id',
                'deactive',
                'name',
                'nomer_borta',
                'nomer_gos_registr',
                'nomer_traktor',
                'nomer_vin',
                'tx',
                'delete_sign',
                'final_destination',
                'nomer_traktor',
            ],
                'safe'],

            /* Настройка правил */
            //            [['fullName'], 'safe']

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№ Id',
            'parent_id' => 'parent',
            'deactive' => 'Деакт.',
            'date_create' => 'Дата создания',

            'f_first_bort' => 'Б/Г',
            'delete_sign' => 'Удаление',
            'fullName' => 'АП',
            'name' => 'Наим. склада',

            'final_destination' => 'ЦС',

            'nomer_borta' => 'Борт №',
            'nomer_gos_registr' => 'Гос. №',
            'nomer_traktor' => 'Трактор',
            'nomer_vin' => 'VIN',

            'tx' => 'Примечание',
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
        $query = static::find();

        //ddd($query);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);


        $this->load($params);


        if (!$this->validate()) {
            return $dataProvider;
        }


        if (isset($this->id) && $this->id > 0) {
            $query->where(
                ['OR',
                    ['=', 'id', (string)$this->id],
                    ['=', 'id', (integer)$this->id]
                ]
            );
        }

        if ($this->parent_id) {
            //dd($this);
            $query->where(['=', 'parent_id', (integer)$this->parent_id]);
            //unset( $params );
        }


        // ddd($this);

        // фильтр по имени
//            $query->andFilterWhere(['like', 'fullName', $this->fullName]);


        $query
            ->andFilterWhere(['like', 'name', trim($this->name)])
            ->andFilterWhere(['like', 'nomer_borta', $this->nomer_borta])
            ->andFilterWhere(['like', 'nomer_gos_registr', $this->nomer_gos_registr])
            ->andFilterWhere(['like', 'nomer_vin', $this->nomer_vin])
            ->andFilterWhere(['like', 'tx', $this->tx])
            ->andFilterWhere(['=', 'delete_sign', (int)$this->delete_sign]);


        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search_with($params)
    {
        $query = static::find()->with('sprwhtop');
//            ->where(
//                [
//                    '!=', 'deactive', (int)1
//                ]);

//ddd($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);


        $this->load($params);
        //ddd((int)$this->parent_id);


        if (!$this->validate()) {
            return $dataProvider;
        }


        if ( !empty($this->name)) {
          $query
            ->andFilterWhere(['like', 'name', $this->name ]);
              return $dataProvider;
          }


      //  ddd($this);


        ///
        if (isset($this->parent_id) && (int)$this->parent_id > 0) {
            $query->where(['parent_id' => (int)$this->parent_id]);
        }

        if ((int)$this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }




        ///
        $query
            ->andFilterWhere(['like', 'nomer_borta', trim($this->nomer_borta)])
            ->andFilterWhere(['like', 'nomer_gos_registr', trim($this->nomer_gos_registr)])
            ->andFilterWhere(['like', 'nomer_vin', trim($this->nomer_vin)])
            ->andFilterWhere(['==', 'delete_sign', (int)$this->delete_sign])
            ->andFilterWhere(['like', 'tx', trim($this->tx)]);


//            $query->andFilterWhere(['parent_id' => (int)$this->parent_id]);
//        }
//        else {

        /// Целевой Склад + Остальные склады
        if ($this->final_destination == 2) {
            $query->andFilterWhere(
                ['OR',
                    ['final_destination' => ''],
                    ['final_destination' => (int)0],
                    ['final_destination' => (int)1]
                ]
            );
        }
        /// Целевой Склад
        if ($this->final_destination == 1) {
            $query->andFilterWhere(['final_destination' => (int)1]);
        }
        /// Остальные склады
        if ($this->final_destination == '') {
            $query->andFilterWhere(['final_destination' => '']);
        }
        /// Остальные склады
        if ($this->final_destination == '0') {
            $query->andFilterWhere(['final_destination' => (int)0]);
        }
        ///
//        }


        return $dataProvider;
    }


    /**
     * Creap With Changes
     *=
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search_with_change($params)
    {
        $query = static::find()
            ->with([
                'sprwhtop',
                'sprwhelement_change',
            ])
            ->where(['deactive' => 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);


        $this->load($params);
        //ddd((int)$this->parent_id);


        if (!$this->validate()) {
            return $dataProvider;
        }


        //ddd($this);


        ///
        if (isset($this->parent_id) && (int)$this->parent_id > 0) {
            $query->where(['parent_id' => (int)$this->parent_id]);
        }

        if ((int)$this->id > 0) {
            $query->andFilterWhere(['=', 'id', (int)$this->id]);
        }

        ///
        $query
            ->andFilterWhere(['like', 'name', trim($this->name)])
            ->andFilterWhere(['like', 'nomer_borta', trim($this->nomer_borta)])
            ->andFilterWhere(['like', 'nomer_gos_registr', trim($this->nomer_gos_registr)])
            ->andFilterWhere(['like', 'nomer_vin', trim($this->nomer_vin)])
            ->andFilterWhere(['==', 'delete_sign', (int)$this->delete_sign])
            ->andFilterWhere(['like', 'tx', trim($this->tx)]);


//            $query->andFilterWhere(['parent_id' => (int)$this->parent_id]);
//        }
//        else {

        /// Целевой Склад + Остальные склады
        if ($this->final_destination == 2) {
            $query->andFilterWhere(
                ['OR',
                    ['final_destination' => ''],
                    ['final_destination' => (int)0],
                    ['final_destination' => (int)1]
                ]
            );
        }
        /// Целевой Склад
        if ($this->final_destination == 1) {
            $query->andFilterWhere(['final_destination' => (int)1]);
        }
        /// Остальные склады
        if ($this->final_destination == '') {
            $query->andFilterWhere(['final_destination' => '']);
        }
        /// Остальные склады
        if ($this->final_destination == '0') {
            $query->andFilterWhere(['final_destination' => (int)0]);
        }
        ///
//        }


        return $dataProvider;
    }


    /**
     * Показать все ЦС Парки + автобусы
     *=
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search_all_cs($params)
    {

        $query = static::find()->where(['>=', 'final_destination', (int)1]);
        //ddd($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);


        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */
        $dataProvider->setSort([
            'defaultOrder' => [
                'parent_id' => SORT_ASC,
                'name' => SORT_ASC
            ]]);


        //        $dataProvider->setSort([
        //            'attributes' => [
        //                'id',
        //                //'fullName' => ['name' => SORT_ASC],
        //
        ////                'fullName' => [
        ////                    'asc' => ['sprwhtop.name' => SORT_ASC],
        ////                    'desc' => ['sprwhtop.name' => SORT_DESC],
        ////                    'label' => 'Full Name',
        ////                    'default' => SORT_ASC
        ////                ],
        //                'name'
        //            ]
        //        ]);
        //        /*


        $this->load($params);


        if (!$this->validate()) {
            return $dataProvider;
        }


        if (isset($this->id) && $this->id > 0) {
            $query->where(
                ['OR',
                    ['=', 'id', (string)$this->id],
                    ['=', 'id', (integer)$this->id]
                ]
            );
        }

        if ($this->parent_id) {
            //dd($this);
            $query->where(['=', 'parent_id', (integer)$this->parent_id]);
            //unset( $params );
        }


        // ddd($this);

        // фильтр по имени
        //     $query->andFilterWhere(['like', 'fullName', $this->fullName]);


        $query
            ->andFilterWhere(['like', 'name', trim($this->name)])
            ->andFilterWhere(['like', 'nomer_borta', $this->nomer_borta])
            ->andFilterWhere(['like', 'nomer_gos_registr', $this->nomer_gos_registr])
            ->andFilterWhere(['like', 'nomer_vin', $this->nomer_vin])
            ->andFilterWhere(['like', 'tx', $this->tx]);


        return $dataProvider;
    }

    /**
     * Поисковый запрос только для движений по ЦС
     * =
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search_move_cs_company($params)
    {
        $this->load($params);

        $query = Sprwhtop::find();


        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


//        ddd( $params );


        //wh_cs_parent_number
        if ((int)$this->id > 0) {
            $query->andFilterWhere(
                [
                    '=',
                    'id',
                    (int)$this->id,
                ]);
        }


        return $dataProvider;
    }


}
