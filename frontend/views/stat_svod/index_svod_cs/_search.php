
<?php

//use kartik\date\DatePicker;

use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>

<div class="pv-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index_move_cs'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>



 <div>


             <?=$form->field( $searchModel_company, 'id' )->dropDownList(
                 $searchModel_filter,
                 [
                     'prompt' => 'Выбор склада ...',
                 ]
             )
                 ->label( "Автопарк ЦС" );
             ?>

             <?=$form->field( $searchModel_filter, 'id' )->dropDownList(
                 $searchModel_filter,
                 [
                     'prompt' => 'Выбор склада ...',
                 ]
             )
                 ->label( "Автопарк ЦС" );



             ?>



             <p class="breb_crumbs_pice">
                 <?= Html::submitButton('Установить', [
                         'id' => 'ustanov',
                         'class' => 'btn btn-primary'
                 ]) ?>
             </p>




</div>


    <?php ActiveForm::end(); ?>


</div>



<?php
$script = <<<JS
    
           
    $('#ustanov').click(function () {
        $('div.table_with').html('');
    });
    
    
    $('#period').click(function () {
        $('.top_box').slideToggle( "slow" );
    });


JS;
$this->registerJs($script);
?>

