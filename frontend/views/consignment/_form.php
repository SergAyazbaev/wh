<?php

use \yii\widgets\MaskedInput;
	use kartik\date\DatePicker;
	use yii\jui\AutoComplete;
	use kartik\select2\Select2;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;


?>

<style>
    div.sprtype-form {
        /*display: block;*/
        /*position: fixed;*/
        /*width: max-content;*/
        /*max-width: 670px;*/
        /*margin-left: calc((98%) / 3.5);*/
        /*margin-top: 100px;*/
        /*padding: 5px 10px;*/

        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
        display: block;
        position: fixed;
        width: 775px;
        max-width: 734px;
        margin-left: calc((98%) / 4);
        margin-top: 2px;
        padding: 5px 10px;
    }


    div.horisont {
        background-color: #ffe4c438;
        width: 100%;
        /* display: block; */
        float: left;
        margin: 5px 0px;
        padding: inherit;
    }

    div.form-group {
        margin-bottom: 1px;
        padding: 10px 30px;
    }
</style>


<?php $form = ActiveForm::begin(
	[
		//'id'     => 'project-form',
		//    'action' => ['index'],
		//'method' => 'post',

		'options' => [
			//'data-pjax' => 1,
			'autocomplete' => 'off',

		],

		'enableAjaxValidation'   => false,
		'enableClientValidation' => true,
		'validateOnChange'       => true,
		'validateOnSubmit'       => true,
		'validateOnBlur'         => true,

	] );
?>


<div class="sprtype-form">
    <div class="horisont">
		<?= $form->field( $model, 'id' )
		         ->textInput(
			         [
				         'placeholder' => $model->getAttributeLabel( 'id' ),
				         'style'       => 'width: 77px; margin-right: 5px;',
				         'readonly'    => 'readonly',
			         ] )
		         ->label( false );
		?>

		<?php


			echo $form->field( $model, 'dt_create' )
			          ->widget(
				          DatePicker::className(), [
				          //				          'name' => 'dp_1',
				          //				          'type' => DatePicker::widget(
				          //					          [
				          'type'          => DatePicker::TYPE_INPUT,
				          //TYPE_INLINE,
				          'model'         => $model,
				          //						          'value'         => date( 'd.m.Y', strtotime( $model->dt_stop ) ),
				          'attribute'     => 'dt_create',
				          'language'      => 'ru',
				          'name'          => 'dt_create',
				          'convertFormat' => false,
				          'options'       => [
					          'placeholder'  => 'Дата - STOP',
					          'autocomplete' => "off",
				          ],

				          'pluginOptions' => [
					          'format'         => 'dd.mm.yyyy 00:00:00',
					          //                                'todayHighlight' => true,
					          'autoclose'      => true,
					          'weekStart'      => 1,
					          //неделя начинается с понедельника
					          'pickerPosition' => 'top-left',
					          // 'startDate' => $date_now,
					          'todayBtn'       => true,
					          //снизу кнопка "сегодня"
				          ],


				          //					          ] ),
			          ] );
		?>


		<?php
			echo $form->field( $model, 'element_id' )->widget(
				Select2::className()
				, [
				'name'  => 'st',
				'data'  => $spr_globam_element,
				'theme' => Select2::THEME_BOOTSTRAP,
				//'size'  => Select2::SMALL,
				//LARGE,
			] )
                ->label( "НЕ имеющие ШТРИХКОД" );
		?>

		<?php
			echo $form->field( $model, 'name' )
			          ->widget(
				          AutoComplete::className(), [
				          'clientOptions' => [
					          'source'    => $barcode_consignment,
					          'minLength' => '1',
					          'autoFill'  => true,
				          ],
			          ] )
			          ->textarea(
				          [
					          'placeholder' => 'Название Партии',
					          'style'       => 'width: 100%; margin: 0px;height: 106px;',
				          ] )
			          ->label( false );
		?>

		<?php
			echo $form->field( $model, 'tx' )
			          ->widget(
				          AutoComplete::className(), [
				          'clientOptions' => [
					          'source'    => $barcode_tx,
					          'minLength' => '1',
					          'autoFill'  => true,
				          ],
			          ] )
			          ->textarea(
				          [
					          'placeholder' => 'Примечание',
					          'style'       => 'width: 100%; margin-right: 5px;',
				          ] )
			          ->label( false );
		?>

		<?php
			echo $form->field( $model, 'cena' )
			          ->widget(
				          MaskedInput::className(), [
				          //'mask' => '[999999999999]9.99',
				          //'mask' => "[999999999[.99]]",
				          //'mask' => '9{1,13}.9{,2}',


				          'clientOptions' => [
					          'greedy'    => true,
					          'alias'     => 'decimal',
					          'autoGroup' => true,
				          ],

			          ] )
			          ->textInput(
				          [
					          'type'        => 'double',
					          'placeholder' => 'Стоимость (тенге)',
					          'style'       => 'width: 100%; margin-right: 5px;',
				          ] );

//			          ->label( false );
		?>

    </div>

    <div class="horisont">
        <div class="form-group">

			<?php
				echo Html::a(
					'Выход',
					[ '/consignment' ],
					[ 'class' => 'btn btn-warning' ] );
			?>
			<?= Html::submitButton( 'Сохранить', [ 'class' => 'btn btn-success' ] ) ?>

        </div>


    </div>

</div>

<?php ActiveForm::end();
	//MyHelpers::WH_BinaryTree()
?>
