<?php


use frontend\models\Sprtype;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;


//dd ($model);
//
//"_id": ObjectId("5bb70b2d80a0632d100038f9"),
//   "name": "wqerqwerqwr",
//   "author": "wqerqwer",
//   "content": "qwerqwer",
//   "comments": "qwerqwerqw",
//   "dt_create": "2018-01-01"

echo "<div class=\"pv-form\"> " ;

if(!isset($model_action->dt_create)) {
    $model_action->dt_create = date("d.m.Y H:i", (integer) $model_action->dt_create);
}




$form = ActiveForm::begin();


echo "<div id='pv_form_head'>" ;
?>


<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=> 'name',
                'filter' => frontend\models\Sprtype::find()->select(['name'])->indexBy('name')->column(),
                'value'=> 'name',
            ],

            [
                'attribute'=> 'author',
                'contentOptions' => ['style' => 'max-width: 125px;overflow: auto;line-height: 4px;'],
                'filter' => frontend\models\Sprglobam::find()->select(['name'])->indexBy('name')->column(),
                'value'=> 'author',
            ],
            [
                'attribute'=> 'dt_create',
                'contentOptions' => ['style' => 'max-width: 125px;overflow: auto;line-height: 4px;'],
                'filter'=>  DateTimePicker::widget([
                                'name' => 'datetime_10',
                                'options' => ['placeholder' => 'Select operating time ...'],
                                'convertFormat' => true,
                                'pluginOptions' => [
                                    'format' => 'd-M-Y g:i A',
                                    'startDate' => '01-Mar-2014 12:00 AM',
                                    'todayHighlight' => true
                                ]
                            ]),

                'value'=> 'dt_create',
            ],


            'content',
            'comments',

            ['class' => 'yii\grid\ActionColumn'],

        ],
    ]); ?>



<?
            echo "</div>" ;
        echo "</div>" ;
echo "</div>" ;
?>




<?php ActiveForm::end(); ?>

</div>




<style>
    .qr-reader {
        margin: 0px auto;
        background-color: aqua;
        /*box-shadow: 0 0 10px rgba(0,0,0,0.5);*/
    }
    #qrInput{
        background-color: darksalmon;
        font-size: larger;
        font-weight: 800;
        font-family: sans-serif;

    }
    .btn-success{
        box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }
</style>



<?php
require_once('..\..\vendor\autoload.php');
$var = 123;
//начало многосточной строки, можно использовать любые кавычки

$script = <<<TAG

//    $('#datetimepicker').datetimepicker('setEndDate', '2012-01-01');
//     function foo() {    
//     return $var; //можно использовать переменные
//     }

TAG;


//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>


