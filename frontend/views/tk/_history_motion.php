<?php


use frontend\models\Spr_globam as Spr_globamAlias;
use frontend\models\Sprtype as SprtypeAlias;
	use kartik\datetime\DateTimePicker;
use yii\widgets\ActiveForm;
use yii\grid\GridView;


echo "<div class=\"pv-form\"> " ;


//dd($model_action);


//if(!isset($model->dt_create)) {
//    $model->dt_create = date("d.m.Y H:i", (integer) $model->dt_create);
//}


//_id
//[type_pv_name] => Кронштейны для терминала NEW8210
//[group_pv_name] => Для установки на автобус типа Youtong
//[pv_health] => Произведен. Новый.
//[pvaction] => 1000000
//            [dt_create] => 2018-10-12
//            [bar_code_pv] => 4605246007890
//            [date_mongo] => MongoDB\BSON\UTCDateTime Object


//$searchModel_act->where(['id'=>$id])->all();

//dd($searchModel_act);




$form = ActiveForm::begin();


echo "<div id='pv_form_head'>" ;
?>


<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            ['class' => 'yii\grid\SerialColumn'],

            [
	            'attribute' => 'name',
	            'filter'    => SprtypeAlias::find()->select( [ 'name' ] )->indexBy( 'name' )->column(),
	            'value'     => 'name',
            ],

            [
	            'attribute'      => 'author',
	            'contentOptions' => [ 'style' => 'max-width: 125px;overflow: auto;line-height: 4px;' ],
	            'filter'         => Spr_globamAlias::find()->select( [ 'name' ] )->indexBy( 'name' )->column(),
	            'value'          => 'author',
            ],
            [
                'attribute'=> 'dt_create',
                'contentOptions' => ['style' => 'max-width: 125px;overflow: auto;line-height: 4px;'],
                'filter'=>  DateTimePicker::widget([

                        'name' => 'datetime_10',
                        'type' => DateTimePicker::TYPE_INPUT,
                        'size' => 'lg',
                        'options' => ['placeholder' => 'Ввод даты/времени...'],
                        //'convertFormat' => true,
                        'value'=> date("d.m.Y H:i:s", date(time())),
        //                'value'=> date('yyyy-MM-dd H:i:s', date(time())),
                        'pluginOptions' => [
	                        'pickerPosition' => 'bottom-left',
	                        'format'         => 'php:Y-m-d H:i:s', //'dd.mm.yyyy HH:ii:ss',
	                        //                    'format' => 'php:dd.MM.yyyy H:i:s', //'dd.mm.yyyy HH:ii:ss',

	                        'autoclose' => true,
	                        'weekStart' => 1, //неделя начинается с понедельника
	                        'startDate' => '' . $date_now, //самая ранняя возможная дата
	                        'todayBtn'  => true, //снизу кнопка "сегодня"
                        ]



                            ]),

                'value'=> 'dt_create',
            ],


            'content',
            'comments',

            ['class' => 'yii\grid\ActionColumn'],

        ],
    ]); ?>



<?php
            echo "</div>" ;
        echo "</div>" ;
echo "</div>" ;
?>




<?php ActiveForm::end(); ?>


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


