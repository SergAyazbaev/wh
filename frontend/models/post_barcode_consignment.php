<?php

namespace frontend\models;

	use yii\base\Model;
	use yii\data\ActiveDataProvider;


	class post_barcode_consignment extends Barcode_consignment {


		/**
		 * @return array
		 */
		public function rules() {
			return [
				[
					[
						'id',
						'element_id',
						'name',
						'tx',

						'dt_create_timestamp',
						'dt_create',
						'dt_update',

						'cena_input',
						'cena_formula',
						'cena_calc',
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
		 * @throws \yii\base\InvalidArgumentException
		 */
		public function search( $params ) {

			$this->load( $params );


			$query = static::find()
			               ->with(
				               [
					               'spr_globam_element',
					               //Устройства АСУОП

					               'spr_globam_element.spr_globam',
					               //Группы АСУОП
				               ] );


			//ddd($this->cena_input);
			//ddd($query);


			$dataProvider = new ActiveDataProvider(
				[
					'query'      => $query,
					'pagination' => [ 'pageSize' => 10 ],
				] );


			/**
			 * Настройка параметров сортировки
			 * Важно: должна быть выполнена раньше $this->load($params)
			 */
			$dataProvider->setSort(
				[
					//					'cena_input',
					//					'cena_formula',
					//					'cena_calc',

					'attributes' => [


						'parent_name' => [
							'asc'     => [ 'element_id' => SORT_ASC ],
							'desc'    => [ 'element_id' => SORT_DESC ],
							//							'asc'     => [ 'spr_globam_element.name' => SORT_ASC ],
							//							'desc'    => [ 'spr_globam_element.name' => SORT_DESC ],
							'default' => SORT_DESC,
						],

						'name'                    => [
							'asc'     => [ 'name' => SORT_ASC ],
							'desc'    => [ 'name' => SORT_DESC ],
							'default' => SORT_DESC,
						],
						'spr_globam_element.name' => [
							'asc'     => [ 'element_id' => SORT_ASC ],
							'desc'    => [ 'element_id' => SORT_DESC ],
							'default' => SORT_DESC,
						],

						'tx' => [
							'asc'     => [ 'tx' => SORT_ASC ],
							'desc'    => [ 'tx' => SORT_DESC ],
							'default' => SORT_DESC,
						],


						'id' => [
							'asc'     => [ 'id' => SORT_ASC ],
							'desc'    => [ 'id' => SORT_DESC ],
							'default' => SORT_DESC,
						],

						'element_id' => [
							'asc'     => [ 'element_id' => SORT_ASC ],
							'desc'    => [ 'element_id' => SORT_DESC ],
							'default' => SORT_DESC,
						],
						'dt_one_day' => [
							'asc'     => [ 'dt_create_timestamp' => SORT_ASC ],
							'desc'    => [ 'dt_create_timestamp' => SORT_DESC ],
							'default' => SORT_DESC,
						],

						'cena_input' => [
							'asc'     => [ 'cena_input' => SORT_ASC ],
							'desc'    => [ 'cena_input' => SORT_DESC ],
							'default' => SORT_DESC,
						],

						'defaultOrder' => [
							'id' => SORT_DESC,
						],
					],

				] );



			if( !$this->validate())
			{
				return $dataProvider;
			}


			//			ddd($params);
			//			ddd($this);

			if(isset( $params[ 'dt_one_day' ] ))
			{
				$one_day_start = date( "d.m.Y 00:00:00", strtotime( $params[ 'dt_one_day' ] ) );
				$one_day_stop  = date( "d.m.Y 23:59:59", strtotime( $params[ 'dt_one_day' ] ) );

				$query->where(
					[
						'AND',
						[
							'>=',
							'dt_create_timestamp',
							strtotime( $one_day_start ),
						],
						[
							'<=',
							'dt_create_timestamp',
							strtotime( $one_day_stop ),
						],
					] );


			}

			//			if(isset( $params[ 'cena_input' ] ))
			//			{
			//
			//				//ddd($params['cena_input']);
			//
			//
			//				//				$query->where(
			//				//					[
			//				//						'OR',
			//				//						[
			//				//							'==',
			//				//							'cena_input',
			//				//							$this->cena_input,
			//				//						],
			//				//						[
			//				//							'==',
			//				//							'cena_input',
			//				//							(double)$this->cena_input,
			//				//						],
			//				//
			//				//					] );
			//
			//
			//			}


			if(isset( $this->id ) && $this->id > 0)
			{
				$query->where(
					[
						'OR',
						[
							'=',
							'id',
							(string) $this->id,
						],
						[
							'=',
							'id',
							(integer) $this->id,
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
					] )
				->andFilterWhere(
					[
						'like',
						'tx',
						$this->tx,
					] );


			return $dataProvider;
		}


	}
