
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>

<div class="pv-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>



 <div class="top_box">
     <div class="top_box_level2">
        <div class="breb_crumbs_box">
<!--            --><?//= $form->field($model, 'dt_deadline1')->widget(
//                DatePicker::className(), [
//                'name' => 'date_start',
//                'options' => ['placeholder' => 'От ...'],
//                'value'=> $start_date,
//                'type' => DatePicker::TYPE_INLINE,//TYPE_BUTTON, // TYPE_RANGE, //TYPE_INPUT,
//                'pluginOptions' => [
//                    'format' => 'yyyd.m.Y',//'format' => 'dd-M-yyyy',
////                                        'format' => 'dd.mm.yyyy',
//                    'todayHighlight' => true,
//                    'autoclose' => true,
////                    'startDate' =>Date('d.m.Y', strtotime('first day of -1 month')),
//                    'startDate' =>Date('d.m.Y', strtotime('first day of  -1 month')),
////                    'endDate' =>Date('d.m.Y')
//                    'endDate' =>Date('d.m.Y')
//
//                ]
//            ])->label('');
//            ?>

        </div>

         <div class="breb_crumbs_box">
             <?
                //d3($end_date);
             ?>
<!--             --><?//= $form->field($model, 'dt_deadline2')->widget(
//                 DatePicker::className(), [
//                 'name' => 'date_start2',
//                 'options' => ['placeholder' => 'До ...'],
//                 'value'=> $end_date,
//                 'type' => DatePicker::TYPE_INLINE,//TYPE_BUTTON, // TYPE_RANGE, //TYPE_INPUT,
//                 'pluginOptions' => [
//                    'format' => 'yyyd.m.Y',//'format' => 'dd-M-yyyy',
//                    'todayHighlight' => true,
//                    'autoclose' => true,
////                    'startDate' =>Date('d.m.Y', strtotime('first day of -1 month')),
//                    'startDate' =>Date('d.m.Y', strtotime('first day of ')),
//                    'endDate' =>Date('d.m.Y', strtotime('now +15 days'))
//                 ]
//             ])->label('');
//             ?>

         </div>



         <div class="breb_crumbs_minibox">
             <p class="breb_crumbs_pice">
                 <?= Html::submitButton('Установить', [
                         'id' => 'ustanov',
                         'class' => 'btn btn-primary'
                 ]) ?>
             </p>

             <?//= Html::resetButton('Отменить', ['class' => 'btn btn-default']) ?>
         </div>




     </div>
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

