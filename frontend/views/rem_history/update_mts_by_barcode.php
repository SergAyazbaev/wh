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

    <?php
    Pjax::begin(['id' => 'w11']);
    ?>

    <?php $form = ActiveForm::begin([

            'options' => [
                'autocomplete' => 'off',
//                'data-pjax' => 'w11',
            ],
        ]

    ); ?>


    <?= $form->field($model, 'id')
        ->textInput([
            'placeholder' => $model->getAttributeLabel('id'),
            'style' => 'width: 77px; margin-right: 5px;',
            'readonly' => 'readonly'
        ])
        ->label(false);
    ?>

    <?php
    //ddd($pool);

    echo $form->field($model, 'bar_code')
        ->textInput(['readonly' => 'readonly']);

    ?>


    <?= $form->field($model, 'short_name')
        ->textInput(['readonly' => 'readonly',]);

    ?>

    <?= $form->field($model, 'mts_user_id')
        ->widget(Select2::className()
            , [
                'name' => 'st',
                'data' => ['' => "Не выбран..."] + $spr_list,
                'theme' => Select2::THEME_BOOTSTRAP,
                'size' => Select2::SMALL, //LARGE,
            ]);

    ?>


    <?= $form->field($model, 'num_busline')
        ->textInput([
            'placeholder' => '1,2,3... 150',
            'style' => 'width: 177px; margin-right: 5px;',
        ])->label('Номер маршрута');
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
