<?php

use frontend\models\Sklad;

//use frontend\models\Sprwhelement;
	//use kartik\date\DatePicker;
	use yii\grid\GridView;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

?>


<style>
    .pv_motion_create_name {
        /*width: 65%;*/
        /*background-color: #d56e18;*/
    }

    .form-group.field-postsklad-dt_start, .form-group.field-postsklad-dt_stop {
        width: 300px;
        float: none;
    }


    .form-group.field-postsklad-dt_start {
        display: block;
        position: relative;
        width: 300px;
        float: left;
        background-color: #00ff4324;
        padding: 5px 10px;
        margin-left: 10px;
    }

    .form-group.field-postsklad-dt_stop {
        display: block;
        position: inherit;
        width: 300px;
        background-color: #00ff4324;
        padding: 5px 10px;
        float: left;
        margin-left: 10px;
    }

    .pv_motion_create_left {
        width: 30%;
        background-color: #0b93d5;
        float: left;
        position: relative;
        float: right;
        margin: 0px;
    }

</style>


<?php
	//    Pjax::begin(['id' => "pjax-container",
	//            'linkSelector' => 'a:not(.target-blank)']
	//    );
	//echo \yii::$app->request->get('page');
?>



<?php $form = ActiveForm::begin( [ 'options' => [ 'autocomplete' => 'off' ] ] ); ?>

<?= Html::beginForm(
	[ 'stat_svod/index_svod_pe' ],
	'post',
	[
		'data-pjax' => '',
		'class'     => 'form-inline',
	] );
?>


<div id="pv_motion_create_name">
    <div class="pv_motion_create_name">

        <h2>Сводная таблица. ПО ОТНОШЕНИЮ К ПОЛУЧАТЕЛЮ </h2>

    </div>
    <div class="pv_motion_create_name">

		<?php
			echo Html::a( 'Выход', [ '/site' ], [ 'class' => 'btn btn-warning' ] );
		?>

		<?= Html::submitButton(
			'To EXCEL', [
			'class' => 'btn btn-default',
			'name'  => 'print',
			'value' => 1,
		] ) ?>


		<?= Html::submitButton(
			'To EXCEL (РАСКРЫТО ПО НАКЛАДНЫМ)', [
			'class' => 'btn btn-default',
			'name'  => 'print',
			'value' => 2,
		] ) ?>

        <!--        --><?php //= Html::submitButton('Ok', [
			//            'class' => 'btn ',
			//            'name'=>'print',
			//            'value' => 3,
			//        ]) ?>

    </div>

    <div class="pv_motion_create_name">
        <!--        --><?php //
			//        //$model->dt_start =date('d.m.Y H:i:s',strtotime($dt_start));
			//        echo $form->field($model, 'dt_start')
			//            ->widget(DatePicker::className(),[
			//                'name' => 'dp_1',
			//                //'autocomplete' => ['disabled'=>true],
			//                'type' => DatePicker::TYPE_INPUT,   //TYPE_INLINE,
			//                'size' => 'lg',
			//                'convertFormat' => false,
			//
			//                'options' => [
			//                    'placeholder'  => 'Ввод даты/времени...'],
			//
			//                'pluginOptions' => [
			//                    'format' => 'dd.mm.yyyy 00:00:00', /// не трогать -это влияет на выбор "СЕГОДНЯ"
			//                    'pickerPosition' => 'bottom-left',
			//
			//                    'autoclose'=>true,
			//                    'weekStart'=>1, //неделя начинается с понедельника
			//                    // 'startDate' => $date_now,
			//                    'todayBtn'=>true, //снизу кнопка "сегодня"
			//                ]
			//            ]);
			//        ?>
        <!--        --><?php
			//        //  $model->dt_stop = date('d.m.Y H:i:s',strtotime($model['dt_stop']));
			//
			//        echo $form->field($model, 'dt_stop')
			//            ->widget(DatePicker::className(),[
			//                'name' => 'dp_1',
			//                'type' => DatePicker::TYPE_INPUT,   //TYPE_INLINE,
			//                'size' => 'lg',
			//                'convertFormat' => false,
			//                'options' => [
			//                    'placeholder' => 'Ввод даты/времени...'],
			//
			//                'pluginOptions' => [
			//                    'format' => 'dd.mm.yyyy 00:00:00', /// не трогать -это влияет на выбор "СЕГОДНЯ"
			//                    'pickerPosition' => 'bottom-left',
			//
			//                    'autoclose'=>true,
			//                    'weekStart'=>1, //неделя начинается с понедельника
			//                    // 'startDate' => $date_now,
			//                    'todayBtn'=>true, //снизу кнопка "сегодня"
			//                ]
			//            ]);
			//        ?>

    </div>

</div>


<?php ActiveForm::end(); ?>

<div id="stat_result">
    <div class="pv_motion_create_right">

		<?php

			//
			//        if (isset($model->wh_top) && !empty($model->wh_top)){
			//            $wh_top_select=ArrayHelper::map(Sprwhelement::find()
			//                ->where(['parent_id'=> $model->wh_top])
			//                ->orderBy('id')
			//                ->all(), 'id','name');
			//        }
			//        else{
			//            $wh_top_select=ArrayHelper::map(Sprwhelement::find()
			//                ->orderBy('id')
			//                ->all(), 'id','name');
			//        }


			//        ddd($dataProvider);


			$dataProvider->pagination->pageSize = 10;

			echo GridView::widget(
				[
					'dataProvider' => $dataProvider,
					'filterModel'  => $model,

					'columns' => [

						[
							'attribute'      => 'wh_home_number',
							'label'          => 'База',
							//                        'filter' => Sklad::ArraySklad_Uniq_wh_numbers_plus_id_name() ,
							'contentOptions' => [ 'style' => 'overflow: hidden;width: 110px;' ],
						],

						[
							'attribute'      => 'tz_id',
							'label'          => 'TZ',
							'contentOptions' => [ 'style' => 'overflow: hidden;width: 40px;' ],
						],

						[
							'attribute'      => 'id',
							'label'          => 'Накладная',
							'contentOptions' => [ 'style' => 'overflow: hidden;width: 90px;' ],
						],
						[
							'attribute'      => 'dt_create',
							'label'          => 'Дата',
							'contentOptions' => [ 'style' => ' width: 110px;' ],
							'format'         => [
								'date',
								'php:d.m.Y H:i:s',
							],
						],

						[
							'attribute' => 'sklad_vid_oper',
							'label'     => 'Вид операции',
							'value'     => 'sklad_vid_oper',
							'filter'    => [
								//'0' => "....",
								'1' => "Инвентаризация",
								'2' => "Приход",
								'3' => "Расход",
                                '4' => 'Снятие(замена)',
                                '5' => 'Установка(замена)'

                            ],
							'content'   => function( $model ) {
								return ArrayHelper::getValue(
									[
										//'0' => "....",
										'1' => "Инвентаризация",
										'2' => "Приход",
										'3' => "Расход",
									], $model[ 'sklad_vid_oper' ] );
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
							'attribute'      => 'wh_destination',
							'label'          => 'Получатель',
							'filter'         => Sklad::ArraySklad_Uniq_transporter(),
							'contentOptions' => [ 'style' => 'overflow: hidden;width: 110px;' ],
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


					],
				] );


		?>

    </div>


</div>


<?php
	$script = <<<JS

$('#go_home').click(function() {    
    window.history.back();  
})

JS;

	$this->registerJs( $script, yii\web\View::POS_READY );
?>

<?php //Pjax::end(); ?>



