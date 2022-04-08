<?php

namespace frontend\models;

	use yii\base\Model;
	use yii\data\ActiveDataProvider;


	class postsklad_outer extends Sklad {


		/**
		 * @return array
		 */
		public function attributes() {

			return [
				'id',

				'wh_home_number',

				'wh_cs_number',
				'wh_cs_parent_number',

				'sklad_vid_oper',
			];
		}


		/**
		 * {@inheritdoc}
		 */

		public function rules() {
			return [
				[
					[
						'_id',
						'id',

						'sklad_vid_oper',
						'wh_home_number',

						'wh_cs_number',

						'wh_debet_top',
						'wh_debet_name',
						'wh_debet_element_name',

						'wh_destination',
						'wh_destination_name',
						'wh_destination_element_name',


						"dt_create",
						"dt_create_timestamp",
						"dt_update",

						"tx",
					],
					'safe',
				],
			];
		}

		/**
		 * {@inheritdoc}
		 */
		public function scenarios() {
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
		public function search( $params ) {
			$this->load( $params );

			$query = Sklad::find()->with(
				[
					'sprwhelement_debet',
					'sprwhelement_debet_element',
					'sprwhelement_destination',
					'sprwhelement_destination_element',

					'sprwhelement_dalee',
					'sprwhelement_dalee_element',

				] );

			//ddd($query);

			$dataProvider = new ActiveDataProvider(
				[
					'query'      => $query,
					'pagination' => [ 'pageSize' => 10 ],
				] );

			$dataProvider->setSort(
				[
					'defaultOrder' => [ 'id' => SORT_DESC ],
				] );

			if( !$this->validate())
			{
				// uncomment the following line if you do not want to return any records when validation fails
				// $query->where('0=1');
				return $dataProvider;
			}


			if(isset( $params[ 'otbor' ] ))
			{
				$query->where(
					[
						'=',
						'wh_home_number',
						(integer) $params[ 'otbor' ],
					] );
			}

			//ddd($params);
			//ddd($this->dt_start);


			// Дата фильтр dt_create
			if(isset( $params[ 'postsklad' ][ 'dt_start' ] ) && !empty( $params[ 'postsklad' ][ 'dt_start' ] ))
			{
				//ddd($params);
				$query->andFilterWhere(
					[
						'like',
						'dt_create',
						$params[ 'postsklad' ][ 'dt_start' ],
					] );
			}
			// Дата фильтр dt_create
			if(isset( $params[ 'dt_start' ] ) && !empty( $params[ 'dt_start' ] ))
			{
				//ddd($params);
				$query->andFilterWhere(
					[
						'like',
						'dt_create',
						$params[ 'dt_start' ],
					] );
			}
			if(isset( $params[ 'dt_create' ] ) && !empty( $params[ 'dt_create' ] ))
			{
				$query->andFilterWhere(
					[
						'like',
						'dt_create',
						$params[ 'dt_create' ],
					] );
			}


			//$query->andFilterWhere(['like', 'sklad_vid_oper_name', $this->sklad_vid_oper_name]);

			//        $query->andFilterWhere(['=', 'wh_debet_top', $this->wh_debet_top]);
			//        $query->andFilterWhere(['like', 'wh_debet_name', $this->wh_debet_name]);
			//        $query->andFilterWhere(['=', 'wh_debet_element', $this->wh_debet_element]);
			//        $query->andFilterWhere(['like', 'wh_debet_element_name', $this->wh_debet_element_name]);

			//        $query->andFilterWhere(['=', 'wh_destination', $this->wh_destination]);
			//        $query->andFilterWhere(['like', 'wh_destination_name', $this->wh_destination_name]);
			//        $query->andFilterWhere(['=', 'wh_destination_element', $this->wh_destination_element]);
			//        $query->andFilterWhere(['like', 'wh_destination_element_name',  $this->wh_destination_element_name]);


			if((int) $this->id > 0)
			{
				$query->andFilterWhere(
					[
						'=',
						'id',
						(int) $this->id,
					] );
			}

			if((int) $this->sklad_vid_oper > 0)
			{
				$query->andFilterWhere(
					[
						'OR',
						[
							'=',
							'sklad_vid_oper',
							(int) $this->sklad_vid_oper,
						],
						[
							'like',
							'sklad_vid_oper',
							(string) $this->sklad_vid_oper,
						],
					] );
			}


			//$query->andFilterWhere(['like', 'wh_destination_element_name',  $this->sprwhelement_debet.name]);


			//        ddd($params['postsklad']['sprwhelement_debet.name']);
			//        ddd( $this['sprwhelement_debet.name'] );

			//        'sprwhelement_debet.name' => 'алм'

			//$query->andFilterWhere(['like', 'sprwhelement_debet.name', $params['postsklad']['sprwhelement_debet.name'] ]);


			//                'sprwhelement_debet.name',
			//                'sprwhelement_debet_element.name',
			//                'sprwhelement_destination.name',
			//
			//                'sprwhelement_destination_element.name',
			//                'sprwhelement_destination_element.nomer_borta',
			//                'sprwhelement_destination_element.nomer_gos_registr',
			//                'sprwhelement_destination_element.nomer_vin',
			//                'sprwhelement_destination_element.tx',


			return $dataProvider;
		}


		/**
		 * @param $model_sklad
		 *
		 * @return ActiveDataProvider
		 */
		public function search_outer( $model_sklad ) {
			$this->load( $model_sklad );


			//			->select(
			//				[
			//					'id',
			//					'sklad_vid_oper',
			//					'array_tk_amort.wh_tk_amort',
			//					'array_tk_amort.wh_tk_element',
			//					'array_tk_amort.ed_izmer',
			//					'array_tk_amort.ed_izmer_num',
			//
			//					'dt_create',
			//					'dt_update',
			//				] )
			//				->where(
			//					[
			//						'AND',
			//						[ 'wh_home_number' => $sklad_id ],
			//						[ 'array_tk_amort.wh_tk_amort' => [ '$eq' => $group_id ] ],
			//						[ 'array_tk_amort.wh_tk_element' => [ '$eq' => $element_id ] ],
			//					] )
			//				->asArray()
			//				->all();

			$query = Sklad::find()
			              ->with(
				[
					'sprwhelement_debet',
					'sprwhelement_debet_element',
					'sprwhelement_destination',
					'sprwhelement_destination_element',

					'sprwhelement_dalee',
					'sprwhelement_dalee_element',

				] )
			              ->where( [ '' => $model_sklad ] );

			ddd( $query );


			$dataProvider = new ActiveDataProvider(
				[
					'query'      => $query,
					'pagination' => [ 'pageSize' => 10 ],
				] );

			$dataProvider->setSort(
				[
					'defaultOrder' => [ 'id' => SORT_DESC ],
				] );

			if( !$this->validate())
			{
				// uncomment the following line if you do not want to return any records when validation fails
				// $query->where('0=1');
				return $dataProvider;
			}


			if(isset( $params[ 'otbor' ] ))
			{
				$query->where(
					[
						'=',
						'wh_home_number',
						(integer) $params[ 'otbor' ],
					] );
			}

			//ddd($params);
			//ddd($this->dt_start);


			// Дата фильтр dt_create
			if(isset( $params[ 'postsklad' ][ 'dt_start' ] ) && !empty( $params[ 'postsklad' ][ 'dt_start' ] ))
			{
				//ddd($params);
				$query->andFilterWhere(
					[
						'like',
						'dt_create',
						$params[ 'postsklad' ][ 'dt_start' ],
					] );
			}
			// Дата фильтр dt_create
			if(isset( $params[ 'dt_start' ] ) && !empty( $params[ 'dt_start' ] ))
			{
				//ddd($params);
				$query->andFilterWhere(
					[
						'like',
						'dt_create',
						$params[ 'dt_start' ],
					] );
			}
			if(isset( $params[ 'dt_create' ] ) && !empty( $params[ 'dt_create' ] ))
			{
				$query->andFilterWhere(
					[
						'like',
						'dt_create',
						$params[ 'dt_create' ],
					] );
			}


			//$query->andFilterWhere(['like', 'sklad_vid_oper_name', $this->sklad_vid_oper_name]);

			//        $query->andFilterWhere(['=', 'wh_debet_top', $this->wh_debet_top]);
			//        $query->andFilterWhere(['like', 'wh_debet_name', $this->wh_debet_name]);
			//        $query->andFilterWhere(['=', 'wh_debet_element', $this->wh_debet_element]);
			//        $query->andFilterWhere(['like', 'wh_debet_element_name', $this->wh_debet_element_name]);

			//        $query->andFilterWhere(['=', 'wh_destination', $this->wh_destination]);
			//        $query->andFilterWhere(['like', 'wh_destination_name', $this->wh_destination_name]);
			//        $query->andFilterWhere(['=', 'wh_destination_element', $this->wh_destination_element]);
			//        $query->andFilterWhere(['like', 'wh_destination_element_name',  $this->wh_destination_element_name]);


			if((int) $this->id > 0)
			{
				$query->andFilterWhere(
					[
						'=',
						'id',
						(int) $this->id,
					] );
			}

			if((int) $this->sklad_vid_oper > 0)
			{
				$query->andFilterWhere(
					[
						'OR',
						[
							'=',
							'sklad_vid_oper',
							(int) $this->sklad_vid_oper,
						],
						[
							'like',
							'sklad_vid_oper',
							(string) $this->sklad_vid_oper,
						],
					] );
			}


			//$query->andFilterWhere(['like', 'wh_destination_element_name',  $this->sprwhelement_debet.name]);


			//        ddd($params['postsklad']['sprwhelement_debet.name']);
			//        ddd( $this['sprwhelement_debet.name'] );

			//        'sprwhelement_debet.name' => 'алм'

			//$query->andFilterWhere(['like', 'sprwhelement_debet.name', $params['postsklad']['sprwhelement_debet.name'] ]);


			//                'sprwhelement_debet.name',
			//                'sprwhelement_debet_element.name',
			//                'sprwhelement_destination.name',
			//
			//                'sprwhelement_destination_element.name',
			//                'sprwhelement_destination_element.nomer_borta',
			//                'sprwhelement_destination_element.nomer_gos_registr',
			//                'sprwhelement_destination_element.nomer_vin',
			//                'sprwhelement_destination_element.tx',


			return $dataProvider;
		}


	}



