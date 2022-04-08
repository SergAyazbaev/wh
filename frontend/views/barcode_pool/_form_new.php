<?php


	use yii\jui\AutoComplete;
	use kartik\select2\Select2;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;


?>

<style>
    div.sprtype-form {
        display: block;
        position: fixed;
        width: max-content;
        max-width: 670px;
        margin-left: calc((98%) / 3.5);
        margin-top: 100px;
        padding: 5px 10px;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
    }

    div.form-group {
        padding: 10px 30px;
    }

    div.horisont {
        background-color: #ffe4c438;
        width: 100%;
        /* display: block; */
        float: left;
        margin: 5px 0px;
        padding: inherit;
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
			echo $form->field( $model, 'element_id' )->widget(
				Select2::className()
				, [
				'name'  => 'st',
				'data'  => $spr_globam_element,
				'theme' => Select2::THEME_BOOTSTRAP,
				//'size'  => Select2::SMALL,
				//LARGE,


			] )->label( false );
		?>

		<?php
			//= $form->field( $model, 'bar_code' )
			//		         ->textInput(
			//			         [
			//				         'placeholder' => $model->getAttributeLabel( 'bar_code' ),
			//				         'style'       => 'width: 240px; margin-right: 5px;',
			//			         ] )
			//		         ->label( false );
		?>

		<?php
			echo $form->field( $model, 'barcode_consignment_id' )->widget(
				Select2::className()
				, [
				//'name'  => 'st',
				'data'  => [ '' => "Пусто..." ] + $barcode_consignment,
				'theme' => Select2::THEME_BOOTSTRAP,
				'size'  => Select2::SMALL,
			] )->label( false );

		?>

		<?php
			echo $form->field( $model, 'bar_code' )
			          ->widget(
				          AutoComplete::className(), [
				          'clientOptions' => [
					          'source'    => $barcode_pool,
					          'minLength' => '4',
					          'autoFill'  => true,
				          ],
			          ] )
			          ->textInput(
				          [
					          'placeholder' => 'штрих код ',
					          'style'       => 'width: 100%; margin-right: 5px;',
				          ] )
			          ->label( false );
		?>

    </div>

    <div class="horisont">
        <div class="form-group">

			<?php
				echo Html::a(
					'Выход',
					[ '/barcode_pool/return_to_refer' ],
					[ 'class' => 'btn btn-warning' ] );
			?>
			<?= Html::submitButton( 'Сохранить', [ 'class' => 'btn btn-success' ] ) ?>

        </div>


    </div>

</div>

<?php ActiveForm::end();
	//MyHelpers::WH_BinaryTree()
?>
