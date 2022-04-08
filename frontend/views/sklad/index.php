<?php

	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\Pjax;

	//if (empty($model->dt_create) || empty($model->dt_create_end) ){
	//
	//    $model->dt_create    = Date('d.m.Y', strtotime('now -3 day'));
	//    $model->dt_create_end   = Date('d.m.Y', strtotime('now'));
	//
	//}
	//$start_date=$model->dt_create;
	//$end_date=$model->dt_create_end;


	$this->title                     = 'ТехЗадания';
	$this->params[ 'breadcrumbs' ][] = $this->title;
?>


<!--    --><?php //  echo $this->render('_search',
	//        [
	//            'model' => $searchModel,
	//            'start_date' => $start_date,
	//            'end_date' => $end_date
	//        ]
	//
	//    );
	//    ?>



<?php Pjax::begin(); ?>

<div class="table_with">
	<?php

		if( Yii::$app->user->identity->group_id >= 40 &&
		    Yii::$app->user->identity->group_id < 50 )
		{

	?><h1><?= Html::encode( $this->title ) ?></h1><?


		$dataProvider_into->pagination->pageParam = 'into-page';
		$dataProvider_into->sort->sortParam       = 'into-sort';

		$dataProvider_into->setSort(
			[
				'defaultOrder' => [ 'dt_deadline' => SORT_DESC ],
			] );
		$dataProvider_into->pagination->pageSize = 3;


		echo GridView::widget(
			[
				'dataProvider' => $dataProvider_into,
				'filterModel'  => $searchModel_into,

				'columns' => [

					[
						'header'         => '',
						'contentOptions' => [ 'style' => 'width: 43px;overflow: auto;' ],
						'content'        => function( $model ) {
							//dd($model);
							$url = Url::to( [ '/sklad/createfromtz?tz_id=' . $model->id ] );

							return Html::a(
								'Вн', $url, [
								'class'     => 'btn btn-success btn-xs',
								'data-pjax' => 0,
								'data-id'   => $model->_id,
							] );
						},
					],

					[
						'attribute'      => 'id',
						'contentOptions' => [ 'style' => 'width: 30px;' ],
					],

					[
						'attribute'      => 'name_tz',
						'contentOptions' => [ 'style' => 'overflow: hidden;' ],
					],
					[
						'attribute'      => 'wh_cred_top_name',
						'contentOptions' => [ 'style' => 'overflow: hidden;' ],
					],

					[
						'attribute'      => 'user_create_name',
						'contentOptions' => [ 'style' => ' ;min-width: 80px;' ],
					],

					[
						'attribute' => 'dt_deadline',
						//                'value'=> 'dt_deadline',
						'value'     => 'dt_deadline',

						'format' => [
							'date',
							'до dd.M.yyyy ( HH:i )',
						],

						'contentOptions' => function( $data ) {
							if( $data[ 'dt_deadline' ] < date( "d.m.Y", strtotime( 'now' ) ) )
							{

								return [ 'style' => 'color: #de3c3c;font-weight: bold;font-size: 14px;overflow: hidden;' ];
							} elseif( $data[ 'dt_deadline' ] > date( "d.m.Y", strtotime( 'now' ) )
							          && $data[ 'dt_deadline' ] < date( "d.m.Y", strtotime( 'now +1 day' ) ) )
							{
								return [ 'style' => 'color:blue;overflow: hidden;' ];
							} else
							{
								return [ 'style' => 'color:green;overflow: hidden;' ];
							}

						},
					],

					[
						'attribute'      => 'multi_tz',
						'contentOptions' => [ 'style' => ' ;min-width: 80px;' ],
					],


					//'status_state',
					//'status_create_user',

				],

			] );

	?>
</div>

<?php } ?>

<div class="table_with">
    <h1>Накладные по складу <?php echo $sklad ?>  </h1>

	<?php
		echo Html::a( 'Создать новую накладную', [ 'create_new' ], [ 'class' => 'btn btn-success' ] );
	?>
	<?php
		//= Html::a('Создать новую накладную', ['create?sklad='.$sklad], ['class' => 'btn btn-success']);
	?>

    <!--        --><?php //= Html::a('Отчет "ОСТАТКИ по складу" ',
		//            ['total_stock'], ['class' => 'btn btn-info']);
		//        ?>
    <!--        --><?php //= Html::a('Отчет "ОСТАТКИ по всем складам пользователя" ',
		//            ['total_stock_for_user'], ['class' => 'btn btn-info']);
		//        ?>

	<?php
		//        = Html::a('Печать в Excel', ['/pv?print=1'.$para_str],
		//            ['target' => '_blank','class' => 'btn btn-success']);
	?>

	<?php
		//        = Html::button('Период с '.($start_date?$start_date:' ...')." по ".($end_date?$end_date:' ...'),
		//            ['class' => 'btn btn-success ','id' => 'period' ])

		//        dd($para['otbor']);
	?>


	<?php
		$dataProvider->pagination->pageParam = 'in-page';
		$dataProvider->sort->sortParam       = 'in-sort';

		$dataProvider->setSort(
			[
				'defaultOrder' => [ 'id' => SORT_DESC ],
			] );
	?>

	<?= GridView::widget(
		[
			'dataProvider' => $dataProvider,
			'filterModel'  => $searchModel,

			//  'options'=>['class'=>'mynewclass'], // новый класс

			// 'layout'=>"{sorter} \n{items}\n{pager}\n{summary}\n",

			//    'rowOptions'=>function ($model, $key, $index, $grid){
			//			$class=$index%2?'odd':'even';  // стилизация четной и нечетной строки
			//			return array('key'=>$key,'index'=>$index,'class'=>$class);
			//		},


			'columns' => [
				[
					'class'          => 'yii\grid\ActionColumn',
					'header'         => '',
					'contentOptions' => [ 'style' => ' width: 50px;' ],
					'headerOptions'  => [ 'width' => '10' ],
					//                    'content' => function() {
					//                            dd(Yii::$app->user);
					//                      },

					'template' => '{delete}',

					'buttons' => [
						'delete' =>

							function( $url, $model ) {
								//    dd($url);

								if( Yii::$app->getUser()->identity->group_id == 100 )
								{
									$url = Url::to( [ '/sklad/delete?id=' . $model->_id ] );

									return Html::a(
										'<span class="glyphicon glyphicon-remove " style="color:red"></span>',
										$url );

								}
								if( $model[ 'user_id' ] != Yii::$app->getUser()->identity->id )
								{
									$url = Url::to( [ '/sklad/tha_agent?id=' . $model->_id ] );

									return Html::a(
										'<span class="glyphicon glyphicon-edit"></span>',
										$url );

								}

								return Html::a(
									'<span class="glyphicon " ></span>',
									$url );

							},
					],

				],

				[
					'header'         => '',
					'contentOptions' => function() {
						if( Yii::$app->user->identity->group_id >= 30 &&
						    Yii::$app->user->identity->group_id < 40 )
						{
							return [ 'style' => 'background-color: #4acc6061;' ];
						} else
						{
							return [ 'style' => ' width: 72px;' ];
						}
					},
					'content'        => function( $model ) {  //dd(Yii::$app->user->identity->group_id);

						$url = Url::to( [ '/sklad/update?id=' . $model->_id ] );

						if( Yii::$app->user->identity->group_id >= 30 &&
						    Yii::$app->user->identity->group_id < 40 )
						{
							$url = Url::to( [ '/sklad/tha_agent?id=' . $model->_id ] );
						}

						return Html::a(
							'Вн', $url, [
							'class'     => 'btn btn-success btn-xs',
							'data-pjax' => 0,
							'data-id'   => $model->_id,
						] );
					},

				],

				[
					'attribute'      => 'id',
					'contentOptions' => [ 'style' => ' width: 72px;' ],
				],

				[
					'attribute'      => 'tz_id',
					'contentOptions' => [ 'style' => ' width: 72px;' ],
				],
				[
					'attribute' => 'sklad_vid_oper_name',
					//                    'contentOptions' => ['style' => ' width: 10px;'],
				],

				[
					'attribute' => 'dt_create',
					'format'    => [
						'date',
						'd.MM.Y H:i:s',
					],
				],

				"wh_debet_name",
				"wh_debet_element_name",

				"wh_destination_name",
				"wh_destination_element_name",

				[
					'attribute'      => 'user_name',
					'contentOptions' => [ 'style' => ' ;min-width: 15px;' ],
				],

			],
		] );


		/**
		 * @param $d
		 *
		 * @return false|string
		 */
		function Data_format( $d ) {
			return date( 'd.m.Y h:i:s', strtotime( $d ) );
		}


	?>


	<?php Pjax::end(); ?>

</div>


<?php
	$script = <<<JS



JS;
	$this->registerJs( $script );
?>



