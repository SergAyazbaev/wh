<?php

use yii\jui\AutoComplete;
use \yii\web\View;
use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'XPrinter';
?>


<style>
    .mobile-body-content {
        background-color: #00ff432e;
        padding: 15px;
        width: 60%;
        max-width: 500px;
        min-height: 700px;
        /*max-height: 1000px;*/
        margin-left: 20%;
    }

    .jumbotron {
        text-align: center;
        padding: 10px 1px;
    }

    .width_all {
        width: 100%;
        font-size: 24px;
        /* font-weight: 900; */
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    @media (max-width: 500px) {
        .mobile-body-content {
            /*background-color: #96ff0012;*/
            background-color: #96ff0000;
            padding: 0px;
            width: 100%;
            max-width: none;
            min-height: min-content;
            max-height: none;
            margin-left: 0px;
        }

        .width_all {
            margin: 5px 2px;
            width: 99%;
            font-size: 22px;
            /* font-weight: 900; */
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            line-height: 2.1;
        }

        .wrap > .container22 {
            padding: 0px;
            width: 99%;
            overflow: auto;
            margin-top: 0px;
            margin-bottom: 4px;
        }

        .jumbotron {
            padding-top: 4px;
            padding-bottom: 0px;
            margin-bottom: 4px;
            color: inherit;
            background-color: #fff;
        }

        p > a {
            height: 50px;
        }

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
            font-size: x-large;
        }

        h1 {
            font-size: 44px;
            /*background-color: #00ff434d;*/
            padding: 15px;
        }

        .form-group {
            margin-bottom: 30px;
            background-color: #ffb70017;
            padding: 3px 6px;
            border-radius: 10px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        }

        .form-control {
            font-size: 23px;
            height: auto;
        }

    }
</style>



<div class="site-index">

    <div class="mobile-body-content">
        <div class="jumbotron">
            <h1>Штрих-код</h1>
            <br>
        </div>



        <?php $form = ActiveForm::begin(
            [
                'id' => 'project-form',
                'action' => ['/xprinter/show-one-thing'],
                'method' => 'POST',

                'options' => [
                    //'data-pjax' => 1,
                    'autocomplete' => 'off',

                ],

                'validateOnChange' => true,

                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'validateOnSubmit' => false,
                'validateOnBlur' => false,

            ] );
        ?>

        <?php
        echo $form->field( $model, 'find_name' )
            ->widget(
                AutoComplete::className(), [

                'options' => [
                    'class' => 'form-control',
                    'style' => 'width:60%;overflow: hidden;max-height: 100px;',
                    'placeholder'=>"BarCode"
                ],

                'clientOptions' => [
                    'source' => $pool,
                    'autoFill' => true,
                    'minLength' => '3',
                    'showAnim' => 'fold',

                    //                'select' => new JsExpression( "function( event, ui ) {
                    //                $('#user-company').val(ui.item.id);
                    //            }" )

                ],
            ])
            ->textInput(['placeholder' => 'Штрих-код', 'autofocus' => true,])
            ->label(false);
        ?>

        <?= Html::submitButton('Поиск', ['id' => 'button_poisk', 'class' => 'btn btn-lg btn-primary']) ?>
<!--        --><?//= Html::submitButton('Печать', ['id' => 'button_print', 'class' => 'btn btn-lg btn-danger']) ?>

        <?php ActiveForm::end(); ?>

        <br>
        <br>

        <?php
//        echo Html::a(
//            'Выход',
//            ['/xprinter/index'],
//            [
//                'onclick' => 'window.opener.location.reload();window.close();',
//                'class' => 'btn btn-warning',
//            ]);
        ?>

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



//////////////////// Creditor
$('#mts_crm-id_ap').change(function() {    
    var  number = $(this).val();
    
   //actionBort_element
   
    $.ajax( {        
        url: '/crm/gos_element',        
		data: {
		    id :number
		},				
			success: function(res) {
                //console.log(111);
                
	    	    $('#mts_crm-id_gos_pe').html(res);
	    	    
	    	        $.ajax( {        
                            url: '/crm/bort_element',        
                            data: {
                                id :number
                            },				
                                success: function(res) {            
                                    $('#mts_crm-id_bort_pe').html(res);	    
                                                
                                        },
                                error: function( res) {
                                            alert('JS.sklad-wh_dalee '+ res );
                                        }
                        } );

    						
					},
			error: function( res) {
						alert('JS.sklad-wh_dalee '+ res );
					}
    } );
   
});



//////////////////// Creditor
$('#mts_crm-id_gos_pe').change(function() {
     $('#mts_crm-id_bort_pe').html('');
     return false;
});


//////////////////// Creditor
$('#mts_crm-id_bort_pe').change(function() {    
    $('#mts_crm-id_gos_pe').html('');
    return false;
});




////////////
$('#go_home').click(function() {    
    window.history.back();  
});


JS;

$this->registerJs($script, View::POS_READY);
?>

