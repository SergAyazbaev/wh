
<?php

use kartik\date\DatePicker;

use yii\helpers\Html;
use yii\widgets\ActiveForm;


//if ($_REQUEST) dd($_REQUEST);

//
//if (isset($_REQUEST['postpv']) && !empty($_REQUEST['postpv']))
//{
//    if (isset($_REQUEST['postpv']['dt_create_pv']))
//        $DateStart=$_REQUEST['postpv']['dt_create_pv'];
//
//    if (isset($_REQUEST['postpv']['dt_create_end']))
//        $DateStop=$_REQUEST['postpv']['dt_create_end'];
//}


//dd($_REQUEST['postpv']);

//[postpv] => Array
//(
//    [dt_create] => 22.10.2018
//            [dt_create_end] => 26.10.18
//        )

if (empty($model->dt_create) || empty($model->dt_create_end) ){

    $model->dt_create    = Date('Y-m-d', strtotime('now -3 day'));
    $model->dt_create_end   = Date('Y-m-d', strtotime('now'));

//    $model->dt_create    = Date('d.m.Y', strtotime('now -3 day'));
//    $model->dt_create_end   = Date('d.m.Y', strtotime('now'));

    $start_date=$model->dt_create;
    $end_date=$model->dt_create_end;
}

if(isset($_REQUEST['postpv']['dt_create']) ){
    //$model->dt_create    = Date('d.m.Y', strtotime($_REQUEST['postpv']['dt_create']));
    $start_date = Date('Y-m-d', strtotime($_REQUEST['postpv']['dt_create']));
    //$start_date=$model->dt_create;
}
else
//    $start_date =$model->dt_create = Date('d.m.Y 00:00:00', strtotime('now - 10 day'));
    $start_date = Date('Y-m-d', strtotime('now - 10 day'));



if(isset($_REQUEST['postpv']['dt_create_end'])){
    $end_date = Date('Y-m-d', strtotime($_REQUEST['postpv']['dt_create_end']));

}
else
    $end_date = Date('Y-m-d', strtotime('now'));

/* @var $this yii\web\View */
/* @var $model frontend\models\postpv */
/* @var $form yii\widgets\ActiveForm */
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
            <?
//            dd($start_date);

            ?>
            <?= $form->field($model, 'dt_create')->widget(
                DatePicker::className(), [
                'name' => 'date_start',
                'options' => ['placeholder' => 'От ...'],
                'value'=> $start_date,
                'type' => DatePicker::TYPE_INLINE,//TYPE_BUTTON, // TYPE_RANGE, //TYPE_INPUT,
                'pluginOptions' => [
                    'format' => 'yyyy-m-d',//'format' => 'dd-M-yyyy',
//                                        'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
//                    'startDate' =>Date('d.m.Y', strtotime('first day of -1 month')),
                    'startDate' =>Date('Y-m-d', strtotime('first day of  -1 month')),
//                    'endDate' =>Date('d.m.Y')
                    'endDate' =>Date('Y-m-d')

                ]
            ]);
            ?>

        </div>

         <div class="breb_crumbs_box">
             <?= $form->field($model, 'dt_create_end')->widget(
                 DatePicker::className(), [
                 'name' => 'date_start2',
                 'options' => ['placeholder' => 'До ...'],
                 'value'=> $end_date,
                 'type' => DatePicker::TYPE_INLINE,//TYPE_BUTTON, // TYPE_RANGE, //TYPE_INPUT,
                 'pluginOptions' => [
                    'format' => 'yyyy-m-d',//'format' => 'dd-M-yyyy',
//                                        'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true,
                    'autoclose' => true,
//                    'startDate' =>Date('d.m.Y', strtotime('first day of -1 month')),
                    'startDate' =>Date('Y-m-d', strtotime('first day of ')),
//                    'endDate' =>Date('d.m.Y')
                    'endDate' =>Date('Y-m-d', strtotime('now +15 days'))
                 ]
             ]);
             ?>

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

