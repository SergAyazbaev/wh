<?php

	use kartik\date\DatePicker;

	use kartik\datetime\DateTimePicker;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\widgets\ActiveForm;
	use yii\widgets\Pjax;

?>


<?php
	Pjax::begin();

	//    Pjax::begin([
	//    'id' => "pjax-container",
	//            'linkSelector' => 'a:not(.target-blank)']
	//    );
	//echo \yii::$app->request->get('page');
?>


<style>
    .grid-view .filters input,
    .grid-view .filters select {
        min-width: 80px;
        padding: 0px 3px;
    }

</style>


<h2>Сводная таблица. ПО БАЗОВОМУ СКЛАДУ (Приход/Расход) </h2>
<div id="stat_result">
    <div class="pv_motion_create_right_center">


		<?php

			//	Pjax::begin();

			$form = ActiveForm::begin(
				[
					'id'     => 'project-form',
					'method' => 'get',
					'class'  => 'form-inline',
					'action' => [ '/stat_svod/index_move_cs' ],

					'options' => [
						//'data-pjax' => 1,
						'autocomplete' => 'off',
					],

				] );
		?>

		<?= Html::submitButton(
			'EXCEL. Заголовки накладных', [
			'class' => 'btn btn-default',
			'name'  => 'print',
			'value' => 1,
		] ) ?>


		<?= Html::submitButton(
			'EXCEL. Содержимое накладных', [
			'class' => 'btn btn-default',
			'name'  => 'print',
			'value' => 2,
		] ) ?>

		<?= Html::submitButton(
			'EXCEL. Только АСУОП.', [
			'class' => 'btn btn-default',
			'name'  => 'print',
			'value' => 3,
		] ) ?>

    </div>

    <div class="pv_motion_create_right">
		<?php
			ActiveForm::end();
			////////////////////////

			// ddd($searchModel);

			$dataProvider->pagination->pageSize = 10;

			$filter_date = [];

			//Pjax::begin();

			//        ddd($searchModel);

			echo GridView::widget(
				[
					'dataProvider' => $dataProvider,
					'filterModel'  => $searchModel,

					'columns' => [


						[
							'attribute'      => 'wh_cs_parent_name',
							'value'          => 'sprwhelement_cs_number.sprwhtop.name',
							'label'          => 'Группа ЦС',
							//'filter'         => $filter_for,
							'contentOptions' => [ 'style' => 'min-width: 90px;' ],
						],

						[
							'attribute'      => 'wh_cs_number',
							'value'          => 'sprwhelement_cs_number.name',
							'label'          => 'ЦС',
							'filter'         => $filter_for,
							'contentOptions' => [ 'style' => 'min-width: 90px;' ],
						],

						//						[
						//							'attribute'      => 'wh_cs_number',
						//							'value'          => 'sprwhelement_cs_number.name',
						//							'label'          => 'ЦС',
						//							'contentOptions' => [ 'style' => 'min-width: 90px;' ],
						//
						//
						// 'filter' => \kartik\select2\Select2::widget([
						//        'name' => 'wh_cs_number',
						//        'data' => $filter_for,
						////        'value' => $searchModel->section_id,
						//        'value' => 'sprwhelement_cs_number.id',
						//        'options' => [
						//            'class' => 'form-control',
						//            'placeholder' => 'Выберите значение'
						//        ],
						//        'pluginOptions' => [
						//            'allowClear' => true,
						//            'selectOnClose' => true,
						//        ]
						//    ])
						//
						//						],

						[
							'label'          => '№ Накл',
							'attribute'      => 'id',
							'contentOptions' => [ 'style' => 'overflow: hidden;width: 50px;' ],

							//'value' =>function($model, $key, $index){

							'content' => function( $model ) {

								if(Yii::$app->getUser()->identity->group_id >= 70 ||
								   Yii::$app->getUser()->identity->group_id == 61 ||
								   Yii::$app->getUser()->identity->group_id == 40)
								{

									$url = Url::to( [ '/sklad/update_id?id=' . $model[ 'id' ] . '&otbor=' . $model[ 'wh_home_number' ] ] );

									$text_ret = Html::a(
										$model[ 'id' ],
										$url,
										[
											'class'     => 'btn btn-success btn-xs',
											'data-pjax' => 0,
											'target'    => "_blank",
										] );

									return $text_ret;
								}


								return $model[ 'id' ];
							},

						],


						[
							'attribute'      => 'dt_start',
							'value'          => 'dt_create',
							'label'          => 'Дата START',
							'contentOptions' => [
								'style'        => ' width: 170px;',
								'autocomplete' => "off",
							],
							'format'         => [
								'datetime',
								'php:d.m.Y H:i:s',
							],
							'visible'        => 1,
							//1

							'filter' => DatePicker::widget(
								[
									'type'          => DateTimePicker::TYPE_INPUT,
									//TYPE_INLINE,
									'model'         => $searchModel,
//									'value'         => date( 'd.m.Y', strtotime( $model->dt_start ) ),
									'attribute'     => 'dt_start',
									'language'      => 'ru',
									'name'          => 'dt_create',
									'convertFormat' => false,
									'options'       => [
										'placeholder'  => 'Дата - START',
										'autocomplete' => "off",
									],

									'pluginOptions' => [
										'format'         => 'dd.mm.yyyy 23:59:59',
										//                                'todayHighlight' => true,
										'autoclose'      => true,
										'weekStart'      => 1,
										//неделя начинается с понедельника
										'pickerPosition' => 'top-left',
										//'pickerPosition' => 'bottom-left',
										// 'startDate' => $date_now,
										'todayBtn'       => true,
										//снизу кнопка "сегодня"
									],


								] ),


						],


						[
							'attribute'      => 'dt_stop',
							'value'          => 'dt_create',
							'label'          => 'Дата STOP',
							'contentOptions' => [
								'style'        => ' width: 170px;',
								'autocomplete' => "off",
							],
							'format'         => [
								'datetime',
								'php:d.m.Y H:i:s',
							],
							'visible'        => 1,
							//1

							'filter' => DatePicker::widget(
								[
									'type'          => DateTimePicker::TYPE_INPUT,
									//TYPE_INLINE,
									'model'         => $searchModel,
//									'value'         => date( 'd.m.Y', strtotime( $model->dt_stop ) ),
									'attribute'     => 'dt_stop',
									'language'      => 'ru',
									'name'          => 'dt_create',
									'convertFormat' => false,
									'options'       => [
										'placeholder'  => 'Дата - STOP',
										'autocomplete' => "off",
									],

									'pluginOptions' => [
										'format'         => 'dd.mm.yyyy 23:59:59',
										//                                'todayHighlight' => true,
										'autoclose'      => true,
										'weekStart'      => 1,
										//неделя начинается с понедельника
										'pickerPosition' => 'top-left',
										// 'startDate' => $date_now,
										'todayBtn'       => true,
										//снизу кнопка "сегодня"
									],


								] ),


						],


						[
							'attribute' => 'sklad_vid_oper',
							//                    'label' => 'Вид Опер',
							'value'     => 'sklad_vid_oper',
							'filter'    => [
								'1' => "Инвентаризация",
								'2' => "Приход",
								'3' => "Расход",
                                '4' => 'Снятие(замена)',
                                '5' => 'Установка(замена)'

                            ],

							'content' => function( $searchModel ) {
								return ArrayHelper::getValue(
									[
										''  => ".",
										'1' => "Инвентаризация",
										'2' => "Приход",
										'3' => "Расход",
									], $searchModel[ 'sklad_vid_oper' ] );
							},

							'contentOptions' => [ 'style' => 'overflow: hidden;width: 110px;' ],
						],


						[
							'attribute'      => 'wh_debet_name',
							'label'          => 'Отправитель',
							'contentOptions' => [ 'style' => 'overflow: hidden;max-width:90px' ],
						],

						[
							'attribute'      => 'wh_debet_element_name',
							'label'          => 'Отправитель',
							'contentOptions' => [ 'style' => 'overflow: hidden;max-width:90px' ],
						],

						[
							'attribute'      => 'wh_destination_name',
							'label'          => 'Получатель',
							'contentOptions' => [ 'style' => 'overflow: hidden;max-width:110px' ],
						],
						[
							'attribute'      => 'wh_destination_element_name',
							'label'          => 'Получатель(ПЕ)',
							'contentOptions' => [ 'style' => 'overflow: hidden;max-width:110px' ],
						],

						[
							'attribute'      => 'tx',
							//'label' => 'Получатель(ПЕ)',
							'contentOptions' => [ 'style' => 'overflow: hidden;max-width:110px' ],

							'content' => function( $data ) {

								//ddd($data->id);
								if( !isset( $data->tx ) || empty( $data->tx ))
								{
									return '';
								}

								return $data->tx;
							},


						],


					],
				] );

			Pjax::end();
		?>

    </div>


</div>


<?php //ActiveForm::begin(); ?>


<div id="stat_result">
    <div class="pv_motion_create_right">


		<?php
			echo Html::a( 'Выход', [ '/site' ], [ 'class' => 'btn btn-warning' ] );
		?>


    </div>
</div>
<?php //ActiveForm::end(); ?>


<?php


	$script = <<<JS

$('#go_home').click(function() {    
    window.history.back();  
})

JS;

	$this->registerJs( $script, yii\web\View::POS_READY );
?>

<?php //Pjax::end(); ?>



