<style>
    .tree_land {
        width: 450px;
        min-width: 300px;
        background-color: cornsilk;
        padding: 15px;
    }
</style>

<?php


	use yii\grid\GridView;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\widgets\Pjax;

	//    use yii\widgets\ActiveForm;


	$this->title                     = 'Справочник проверочных кодов, штрихкодов';
	$this->params[ 'breadcrumbs' ][] = $this->title;

	Pjax::begin( [ 'id' => '1' ] );

?>
<div class="sprtype-index">

    <h1><?= Html::encode( $this->title ) ?></h1>
	<?php
		//         echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>


		<?= Html::a( 'Создать новый Элемент склада', [ 'create' ], [ 'class' => 'btn btn-success' ] ) ?>
    </p>


	<?php

		//	Pjax::begin();

		$form = ActiveForm::begin(
			[
				'id'     => 'project-form',
				'method' => 'get',
				'class'  => 'form-inline',
				'action' => [ '/barcode_pool/index' ],

				'options' => [
					//'data-pjax' => 1,
					'autocomplete' => 'off',
				],

			] );
	?>





	<?= Html::submitButton(
		'Передать в EXCEL', [
		'class' => 'btn btn-default',
		'name'  => 'print',
		'value' => 1,
	] ) ?>


	<?php
		ActiveForm::end();

		$dataProvider->pagination->pageSize = 10;
	?>


	<?= GridView::widget(
		[
			'dataProvider' => $dataProvider,
			'filterModel'  => $searchModel,

			'columns' => [

				[
					'attribute'      => 'id',
					'value'          => 'id',
					'contentOptions' => [ 'style' => ' ;width: 75px;' ],
				],

				[
					//'attribute'      => 'parent_name',
					//'filter'         => $spr_globam_element,
					'value'          => 'spr_globam_element.spr_globam.name',
					'contentOptions' => [ 'style' => ' ;min-width: 15px;' ],
				],

				[
					'attribute'      => 'parent_name',
					'filter'         => $spr_globam_element,
					'value'          => 'spr_globam_element.name',
					'contentOptions' => [ 'style' => ' ;min-width: 15px;' ],
				],

				//				[
				//					'attribute'      => 'element_id',
				//					'value'          => 'element_id',
				//					'contentOptions' => [ 'style' => ' ;min-width: 15px;' ],
				//				],

				'bar_code',

				//				[
				//					'header'         => 'Один день',
				//					'attribute'      => 'dt_one_day',
				//					'value'          => 'barcode_consignment.dt_create_timestamp',
				//
				//					'contentOptions' => [ 'style' => ' width: 170px;' ],
				//
				//					//					'contentOptions' => function( $data ) {
				//					//						return [ 'style' => 'color:green;overflow: hidden;' ];
				//					//					},
				//
				//					'format' => [
				//						'datetime',
				//						'php:d.m.Y H:i:s',
				//						//						'php:d.m.Y',
				//					],
				//
				//					'filter' => DatePicker::widget(
				//						[
				//							'type'          => DatePicker::TYPE_INPUT,
				//							'attribute'     => 'dt_one_day',
				//							'language'      => 'ru',
				//							'name'          => 'dt_one_day',
				//							'model'         => 'barcode_consignment.dt_create_timestamp',
				//
				//							//							'model'         => $dataProvider,
				//							//							'model'         => $searchModel,
				//							'pluginOptions' => [
				//								//'format' => 'd.m.Y',
				//								'todayHighlight' => true,
				//								'autoclose'      => true,
				//							],
				//
				//							'options' => [
				//								'placeholder'  => 'Один день',
				//								'autocomplete' => "off",
				//							],
				//
				//							'convertFormat' => false,
				//
				//						] ),
				//
				//				],


                [
                    'attribute' => 'turnover',
                    'contentOptions' => ['style' => 'overflow: hidden;width: 90px;'],
                    'content' => function ($model) {
                        if (isset($model['turnover']) && !empty($model['turnover'])) {
                            return $model['turnover'];
                        }
                        return '';
                    }
                ],

                'barcode_consignment_id',

                'barcode_consignment.name',

                [
					'attribute'      => 'barcode_consignment.tx',
					//					'value'          => 'barcode_consignment.tx',
					'contentOptions' => [ 'style' => 'max-width: 150px;overflow: hidden;' ],
				],

				'barcode_consignment.cena_input',
				//				'barcode_consignment.dt_create_timestamp',

				//				'barcode_consignment.cena_formula',
				//				'barcode_consignment.cena_calc',


				//				[
				//					'class'    => 'yii\grid\ActionColumn',
				//					'template' => '{update} {delete}',
				//					'contentOptions' => [
				//						'class' => 'action-column',
				//						'style' => ' width: 120px;',
				//					],
				//
				//					'buttons'        => [
				//						'update' => function( $url, $model, $key ) {
				//
				//							//Для Талгата
				//							if(Yii::$app->getUser()->identity->group_id == 71)
				//							{
				//
				//								$options = [
				//									'target'      => '_blank',
				//									'title'       => 'update',
				//									'aria-label'  => 'update',
				//									'data-pjax'   => 'w0',
				//									//'data-confirm' => Yii::t('yii', 'Редактируем... '),
				//									'data-method' => 'GET',
				//
				//								];
				//
				//								return Html::a( '<span class="glyphicon glyphicon-edit"></span>', $url, $options );
				//							}
				//
				//							//return '';
				//							return Html::a( '<span class="glyphicon glyphicon-edit"></span>', $url, $options );
				//						},
				//						'delete' => function( $url, $model, $key ) {
				//							//Для Талгата
				//							if(Yii::$app->getUser()->identity->group_id >= 71)
				//							{
				//
				//								$options = [
				//									'title'        => 'delete',
				//									'aria-label'   => 'delete',
				//									'data-pjax'    => 'w0',
				//									//'data-confirm' => Yii::t( 'yii', 'Точно? Удаляем? ' ),
				//									'data-method'  => 'POST',
				//								];
				//
				//								return Html::a( '<span class="glyphicon glyphicon-trash"></span>', $url, $options );
				//							}
				//
				//							return '';
				//						},
				//					],
				//				],


			],
		] );


	?>


</div>


<?php
	Pjax::end();
?>