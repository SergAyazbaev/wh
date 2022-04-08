<style>
    .modal-body {
        position: inherit;
        height: 300px;
        width: 290px;
        display: block;
        margin: auto;
        padding: 15px 0px;
    }
    .modal-header {
        padding: 15px 71px;
        border-bottom: 1px solid #e5e5e5;
        /* background-color: antiquewhite; */
        width: 81%;
        margin-left: 70px;
    }
</style>

<?php
// dd($para);

//    [dt_create_start] => 2018-10-15
//            [dt_time_start] => 06:00
//            [dt_create_stop] => 2018-10-30
//            [dt_time_stop] => 06:00



if (isset($para['postpvmotion']['dt_create_start'] ))
{
    $DateOld = date('Y-m-d',strtotime($para['postpvmotion']['dt_create_start']));
    $TimeOld = date('H:i',strtotime($para['postpvmotion']['dt_time_start']));

}
else
{
    $DateOld = date('Y-m-d',strtotime("Monday last week"));
    $TimeOld = date('H:i',strtotime("Monday last week"));
}

if (isset($para['postpvmotion']['dt_create_stop'] ))
{
    $DateNow = date('Y-m-d',strtotime($para['postpvmotion']['dt_create_stop']));
    $TimeNow = date('H:i',strtotime($para['postpvmotion']['dt_time_stop']));
}
else
{
//    $DateNow = date('Y-m-d',strtotime("saturday last week" ));
//    $TimeNow = date('H:i',strtotime("saturday last week"));
    $DateNow = date('Y-m-d',strtotime("now" ));
    $TimeNow = date('H:i',strtotime("now"));
}



$DateStart = date('Y-m-d',strtotime("first day of  -1 month " ));
//$TimeStart = date('H:i',strtotime("first day of  -1 month "));
$DateEnd = date('Y-m-d',strtotime("now + 15 day" ));
//$TimeEnd = date('H:i',strtotime("now + 15 day"));
?>

<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

Modal::begin([
    'header' => '<h2>Период дат и времени</h2>',
    'toggleButton' => [
        'label' => 'Период с '.($DateOld?date("d.m.Y",strtotime($DateOld)):' ...')." по ".
            ($DateNow?date("d.m.Y",strtotime($DateNow)):' ...'),
        'tag' => 'button',
        'class' => 'btn btn-success',
    ],
    //'footer' => 'низ',
]);




/* @var $this yii\web\View */
/* @var $model frontend\models\postpvaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pv-action-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>



    <div class="date_period">
        <?php //<p>Начало:</p>

            echo $form->field($model, 'dt_create_start')
                ->input('date',[
                    'id'=>'date_start',
                    'value'=>$DateOld,
                    'min'=>$DateStart,
                    'max'=>$DateEnd,
                ]) ;
            echo $form->field($model, 'dt_time_start')
                ->input('time',[
                    'id'=>'time_start',
                    'value'=>$TimeOld,
                ]) ;

        ?>
    </div>
    <div class="date_period">
        <?php //<p>Конец:</p>

            echo $form->field($model, 'dt_create_stop')
                ->input('date',[
                    'id'=>'date_stop',
                    'value'=>$DateNow,
                    'min'=>$DateStart,
                    'max'=>$DateEnd,
                ]) ;

            echo $form->field($model, 'dt_time_stop')
                ->input('time',[
                    'id'=>'time_stop',
                    'value'=>$TimeNow,
                ]) ;

        ?>
    </div>



    <div class="form-group">
        <?= Html::submitButton('Применить',
            [
                'class' => 'btn btn-primary',
                'style' => 'margin: 6px;'
            ]) ?>
        <?//= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php Modal::end(); ?>