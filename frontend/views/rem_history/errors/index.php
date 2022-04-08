<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .form-control {
        /*background-color: #0b93d5;*/
        width: 100%;
    }

    .form-group {
        margin-bottom: 15px;
        max-width: 400px;
    }
</style>


<div class="sprtype-form">

    <?php Pjax::begin(['id' => 'w11']); ?>
    <?php $form = ActiveForm::begin([
        'action' => ['re-save-errors'],
        'options' => ['autocomplete' => 'off']]);
    ?>

    <?= $form->field($model, 'array_decision')
        ->widget(Select2::className()
            , [
                // 'name' => 'st',
                'data' => $spr_decision_all,
                'theme' => Select2::THEME_BOOTSTRAP,
                'size' => Select2::SMALL, //LARGE,
                'maintainOrder' => true,

                'options' => [
                    'placeholder' => 'Выбрать... записать ...',
                    'tabindex' => true,
                    'multiple' => true,
                    'autocomplete' => 'off'
                ],

                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => ['. ', '; ', ': '],
                    'maximumInputLength' => 200
                ],

            ])->label("Найти ЭТО");


    //                $spr_list,
    ?>


    <?= $form->field($model, 'decision')
        ->widget(Select2::className()
            , [
                // 'name' => 'st',
                'data' => $spr_decision_all,
                'theme' => Select2::THEME_BOOTSTRAP,
                'size' => Select2::SMALL, //LARGE,
                'maintainOrder' => true,

                'options' => [
                    'placeholder' => 'Выбрать... записать ...',
                    'tabindex' => true,
                    'multiple' => true,
                    'autocomplete' => 'off'
                ],

                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => ['. ', '; ', ': '],
                    'maximumInputLength' => 200
                ],

            ])->label("Заменить НА ЭТО");


    //                $spr_list,
    ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php
    Pjax::end();
    ?>
</div>


<div class="_futer">
    <div class="past_futer">

        <?php
        echo Html::a(
            '<< Выход',
            ['/rem_history/index'],
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
