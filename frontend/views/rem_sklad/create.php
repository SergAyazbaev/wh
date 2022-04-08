<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Создаем запись в журнал ';
?>

<style>
    .form-control {
        /*background-color: #0b93d5;*/
        width: auto;
    }

    .form-group {
        margin-bottom: 15px;
        max-width: 400px;
    }
</style>

<div class="sprtype-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'id')
        ->textInput([
            'placeholder' => $model->getAttributeLabel('id'),
            'style' => 'width: 77px; margin-right: 5px;',
            'readonly' => 'readonly'
        ])
        ->label(false);
    ?>

    <?php
    echo $form->field($model, 'bar_code')
        ->widget(
            Select2::className()
            , [
            'name' => 'bar_code',
            'data' => $pool,
            'options' => [
                'class' => 'form-group',
                'placeholder' => 'Список ...',
            ],
            'size' => Select2::SMALL,
        ]);


    ?>





    <?= $form->field($model, 'short_name', [])
        ->textInput([
                'placeholder' => $model->getAttributeLabel('short_name'),
                'style' => 'width: 100%; margin-right: 5px;',
            ]
        )
        ->label(false);
    ?>

    <?= $form->field($model, 'diagnoz')
        ->textarea([
                'placeholder' => $model->getAttributeLabel('diagnoz'),
                'style' => 'width: 477px; margin-right: 5px;',
            ]
        );
    //        ->label(false);
    ?>

    <?= $form->field($model, 'decision')
        ->textarea([
                'placeholder' => $model->getAttributeLabel('decision'),
                'style' => 'width: 477px; margin-right: 5px;',
            ]
        );
    //        ->label(false);
    ?>

    <?= $form->field($model, 'list_details')
        ->textarea([
                'placeholder' => $model->getAttributeLabel('list_details'),
                'style' => 'width: 477px; margin-right: 5px;',
            ]
        );
    //        ->label(false);
    ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<div class="_futer">
    <div class="past_futer">

        <?php
        echo Html::a(
            '<< Выход',
            ['/rem_sklad/index'],
            [
                'onclick' => 'window.opener.location.reload();window.close();',
                'class' => ' back_step btn btn-warning',
            ]);
        ?>
    </div>
    <!--            <div class="next_futer">-->
    <!---->
    <!--                --><? //= Html::a('Далее >>', ['mobile_inventory/init_table'], ['class' => ' next_step btn btn-success']) ?>
    <!---->
    <!--            </div>-->
</div>


<?php
$script = <<<JS
    
////////////
$(document).ready(function() {
    $( ".pv_motion_create_ok_button").show();
    $('.alert_save').fadeOut(2500); // плавно скрываем окно временных сообщений
});

////////////
$(document).on('keypress',function(e) {
    if(e.which == 13) {
        // alert('Нажал - enter!');
      e.preventDefault();
      return false;
    }
});

////////////
$('#go_home').click(function() {    
    window.history.back();
});

//////////////////// Debitor -top
$('#rem_history-bar_code').change(function() {
        
    var  number = $(this).val();
    var  text = $('#sklad-wh_debet_top>option[value='+number+']').text();    
     //alert(number);

    $.ajax( {
		url: '/rem_history/list_element_from_barcode',
		data: {
		    bar_code :number
		},
			success: function(res) {		    
		             $('input#rem_history-short_name').val(res);		    
					},
			error: function( res) {
						alert('JS.sklad-wh_destination '+res );
						console.log(res);
					}
    } );
    
});

JS;

$this->registerJs($script);
?>
