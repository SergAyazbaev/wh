<?php

use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<style>
    .pv_motion_create_ok_button {
        background-color: rgba(149, 177, 156, 0.14);
        padding: 0px 24px;
        /* margin: 5px 0px; */
        margin: 10px;
        padding: 15px 30px;
    }

    .in_str {
        float: left;
        /*background-color: rgba(149, 177, 156, 0.14);*/
        padding: 0px 5px;
        /* margin: 5px 0px; */
        /*margin: 10px;*/
        /*padding: 15px 30px;*/
    }

</style>


<div class="pv_motion_create_ok_button">
    <div class="in_str">

        <?php
        echo Html::a( 'Выход', [ '/barcode_pool/return_to_refer' ], [ 'class' => 'btn btn-warning' ] );

        ?>

    </div>

    <div class="in_str">
        <?php //////////////////////////////////////
        ///
        /// МОДАЛЬНОЕ ОКНО. 1
        ///

        Modal::begin(
            [
                'header' => '<h2>Добавляем в ПУЛ ШТРИХКОДОВ</h2>',
                'toggleButton' => [
                    'label' => 'Копипаст ПУЛ Штрихкодов',
                    'tag' => 'button',
                    'class' => 'btn btn-primary',
                ],
                //'footer' => 'Низ окна',
            ]
        );

        ?>

        <?php $form = ActiveForm::begin(
            [
                'id' => 'form1',
                'action' => [ '/barcode_pool/pool_in' ],
                'method' => 'POST',

                'options' => [
                    // 'data-pjax' => 1,
                    'autocomplete' => 'off',
                ],

            ]
        );
        ?>



        <?php
        echo $form->field( $model, 'id' )->widget(
            Select2::className()
            , [
                'name' => 'st',
                'data' => $spr_globam_element,
                'theme' => Select2::THEME_BOOTSTRAP,
                //'size'  => Select2::SMALL,
                //LARGE,
            ]
        )->label( 'Выбрать наименование' );
        ?>

        <?php
        echo $form->field($model, 'barcode_consignment_id')->widget(
            Select2::className()
            , [
                'name' => 'barcode_consignment_id',
                'data' => $spr_consignment,
                'theme' => Select2::THEME_BOOTSTRAP,
                //'size'  => Select2::SMALL,
                //LARGE,
            ]
        )->label('Выбрать наименование');
        ?>
        <br>

        <?= $form
            ->field( $model, 'find_array' )
            ->textarea(
                [
                    'autofocus' => true,
                    'style' => 'height: 300px;font-size: 10px;',
                    'placeholder' => "ШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\n",
                ]
            )
            ->label( 'В это поле пастим' );
        ?>


        <div class="form-group">
            <?= Html::submitButton(
                'Залить копипаст НОВЫЕ ПОЗИЦИИ ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'add_new_pool',
                ]
            ) ?>

        </div>

        <?php ActiveForm::end(); ?>


        <?php Modal::end();
        ///////////////////////////////////?>
    </div>

    <div class="in_str">
        <?php //////////////////////////////////////
        ///
        /// МОДАЛЬНОЕ ОКНО. 2
        ///

        Modal::begin(
            [
                'header' => '<h2>Заливаем Списание</h2>',
                'toggleButton' => [
                    'label' => 'Копипаст Списание',
                    'tag' => 'button',
                    'class' => 'btn btn-primary',
                ],
                //'footer' => 'Низ окна',
            ]
        );

        ?>


        <br>

        <?php $form = ActiveForm::begin(
            [
                'id' => 'form2',
                'action' => [ '/barcode_pool/write_off_copypast' ],
                'method' => 'POST',

                'options' => [
                    // 'data-pjax' => 1,
                    'autocomplete' => 'off',
                ],

            ]
        );
        ?>



        <?= $form
//            ->field($model, 'bar_code')
            ->field( $model, 'find_array' )
            ->textarea(
                [
                    'autofocus' => true,
                    'style' => 'height: 300px;font-size: 10px;',
                    'placeholder' => "Столбцы:

				1.Штрихкод (Barcode)
				2.Основание, документ
				3.Количество
				4.Причина списания

				\n",
                ]
            )
            ->label( 'В это поле пастим' );
        ?>


        <div class="form-group">
            <?= Html::submitButton(
                'Залить копипаст СПИСАНИЕ ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'add_new_pool222222',
                ]
            ) ?>


        </div>


        <?php ActiveForm::end(); ?>


        <?php Modal::end();
        ///////////////////////////////////?>

    </div>
</div>


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

function addProduct() {
    alert(123);
    
 // var x = document.getElementById("fname").value;
 // document.getElementById("demo").innerHTML = x;
  
}


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
		    $('#sklad_inventory-wh_destination_element').html('');
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

