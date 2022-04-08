<?php

	use kartik\select2\Select2;
	use yii\bootstrap\Modal;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;


	$this->title = 'Заливка ПАРТИИЙ';
?>


<style>
    .pv_motion_create_ok_button {
        background-color: rgba(149, 177, 156, 0.14);
        padding: 0px 24px;
        /* margin: 5px 0px; */
        margin: 10px;
        padding: 15px 30px;
    }

</style>


<?php $form = ActiveForm::begin(
	[
		'id' => 'project-form',

		'action' => [ '/barcode_consignment/consignment_in' ],
		'method' => 'POST',

		'options' => [
			// 'data-pjax' => 1,
			'autocomplete' => 'off',
		],

		'enableClientValidation' => false,
		'validateOnChange'       => true,
		'validateOnSubmit'       => true,

	] );
?>


<div class="pv_motion_create_ok_button">

	<?php
		echo Html::a( 'Выход', [ '/barcode_consignment' ], [ 'class' => 'btn btn-warning' ] );
	?>


	<?php //////////////////////////////////////
		///
		/// МОДАЛЬНОЕ ОКНО. ИМПУТ КОПИПАСТ - НОВЫЕ АВТОБУСЫ В ПАРКЕ
		///

		Modal::begin(
			[
				'header'       => '<h2>Добавляем ПАРТИЮ <br>и привязываем ее к ПУЛ-Штрихкодов</h2>',
				'toggleButton' => [
					'label' => 'Добавляем ПАРТИЮ и привязываем ее к ПУЛу',
					'tag'   => 'button',
					'class' => 'btn btn-primary',
				],
				//'footer' => 'Низ окна',
			] );

	?>


	<?php
		echo $form->field( $model, 'id' )->widget(
			Select2::className()
			, [
			'name'  => 'st',
			'data'  => $spr_globam_element,
			'theme' => Select2::THEME_BOOTSTRAP,
			//'size'  => Select2::SMALL,
			//LARGE,
		] )->label( 'Выбрать наименование' );
	?>
    <br>

	<?= $form
		->field( $model, 'bar_code' )
		->textarea(
			[
				'autofocus'   => true,
				'style'       => 'height: 300px;font-size: 10px;',
				'placeholder' => "Первая строка: Аттрибуты ПАРТИИ (!) \n\nШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\n",
			] )
		->label( 'В это поле пастим' );
	?>


    <div class="form-group">
		<?= Html::submitButton(
			'Залить копипаст НОВАЯ ПАРТИЯ (Одна)',
			[
				'class' => 'btn btn-primary',
				'name'  => 'contact-button',
				'value' => 'add_new_pool',
			] ) ?>

    </div>


	<?php Modal::end();
		///////////////////////////////////?>


</div>


<?php ActiveForm::end(); ?>





<?php

	$script = <<<JS
$(document).ready(function() {
    $('.alert_save').fadeOut(2500); // плавно скрываем окно временных сообщений
});

$(document).on('keypress',function(e) {
    if(e.which == 13) {
        // alert('Нажал - enter!');
      e.preventDefault();
      return false;
    }
});



//////////////////// Debitor -top
$('#sklad_inventory-wh_debet_top').change(function() {
        
    var  number = $(this).val();
    var  text = $('#sklad_inventory-wh_debet_top>option[value='+number+']').text();    
    $('#sklad_inventory-wh_debet_name').val(text) ;
    

    $.ajax( {
		url: '/sklad_inventory/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		            $('#sklad_inventory-debet_element').html('');
		            $('#sklad_inventory-wh_debet_element').html(res);		    
					},
			error: function( res) {
						alert('JS.sklad_inventory-wh_destination '+res );
						console.log(res);
					}
    } );
    
    
    
});



//////////////////// Creditor
$('#sklad_inventory-wh_destination').change(function() {
    
    var  number = $(this).val();
    var  text = $('#sklad_inventory-wh_destination>option[value='+number+']').text();
 	    $('#sklad_inventory-wh_destination_name').val(text);
 	    
            // console.log(number);   
            // console.log(text);    
   
    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    // $('#sklad_inventory-wh_destination_element').html('');
		    $('#sklad_inventory-wh_destination_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('JS.sklad_inventory-wh_destination '+ res );
					}
    } );
   
});

//////////////////// destination_element - element
/// sklad_inventory-wh_destination_element 
$('#sklad_inventory-wh_destination_element').change(function() {    
     var  number2 = $('#sklad_inventory-wh_destination_element').val();     
     var  text2   = $('#sklad_inventory-wh_destination_element>option[value='+number2+']').text();
      $('#sklad_inventory-wh_destination_element_name').val(text2);
});




////////////
$('#go_home').click(function() {    
    window.history.back();  
})



JS;

	$this->registerJs( $script, yii\web\View::POS_READY );
?>

