

<style>

    #pv_form_head{
        display: block;
        padding: 35px;
        float: left;
        width: 100%;
        height: auto;
        margin-bottom: 31px;
        background-color: #0000001c;
    }


    #pv_form_left{
        background-color: #9c9c7429;
        width: 35%;
        min-width: 263px;
        display: block;
        position: inherit;
        float: left;
        margin-left: 5%;
        padding: 25px;
    }
    #pv_form_left2{
        background-color: #f5f5dc42;
        /* width: 100%; */
        /* display: block; */
        /* position: relative; */
        float: left;
        padding: 10px;
    }
    #pv_form_center{
        width: 27%;
        min-width: 263px;
        background-color: #b7b73245;
        display: block;
        position: inherit;
        float: left;
        margin-left: 5%;
        padding: 25px 25px;
    }
    #pv_form_right{
        width: 26%;
        max-width: 276px;
        min-width: 263px;
        background-color: #8a6d3b47;
        display: block;
        position: inherit;
        float: left;
        margin-left: 5%;
        padding: 25px;
    }

</style>


<?php

//use frontend\models\SprGroup;
use frontend\models\Sprtype;
//use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


//dd(Yii::$app->language);


/* @var $this yii\web\View */
/* @var $model frontend\models\Pv */
/* @var $form yii\widgets\ActiveForm */

//dd ($model);
echo "<div class=\"pv-form\"> " ;

//if(!isset($model->dt_create)) {
//    $model->dt_create = date("d.m.Y H:i", (integer) $model->dt_create);
//}




$form = ActiveForm::begin();

    echo "<div id='pv_form_head'>" ;
        echo "<div id='pv_form_left'>" ;


            echo $form->field($model, 'type_pv_name')->dropdownList(
                Sprtype::find()->select( ['name']  )->indexBy('name')->column(),
              [
                'prompt' => 'Выбор категории',
                //'id' => 'type_pv',
                //                'options' => [
                //                    '2' => ['Selected' => true]
                //                ]
              ]

            );
//                echo $form->field($model, 'type_pv_name')
//                    ->textInput(['type' => 'hidden']);


//            echo $form->field($model, 'group_pv_name')->dropdownList(
//                Sprglobam::find(),
//                [
//                    'prompt' => 'Выбор категории',
//                    'id' => 'group_pv_name'
//                    ]
//            );
//                echo $form->field($model, 'group_pv_name')
//                    ->textInput(['type' => 'hidden']);



            echo $form->field($model, 'pv_health')->dropdownList(
                frontend\models\Sprhealth::find()->select(['name'])->indexBy('name')->column(),
                [
                    'prompt' => 'Выбор категории'
                ]
            );


            echo $form->field($model, 'pvaction')->dropdownList(
                frontend\models\Spraction::find()->select(['name'])->indexBy('name')->column(),
                [
                    'prompt' => 'Выбор категории',
                    //'id' => 'group_pv'
                ]
            );


    echo "<div id='pv_form_left2'>" ;
        echo  $form->field($model, 'pv_imei')
            ->widget(\yii\widgets\MaskedInput::className(), [
                'mask' => '9999999999[9999]',
            ]) ;

        echo  $form->field($model, 'pv_kcell')
            ->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '+7 999-999-9999',
        ]) ;

//        echo  $form->field($model, 'pv_kcell')
//            ->textarea(['class' => 'pv_motion_content',
//                'style' => 'width: 94%;height:25px; margin: 0px;'
//            ]);

        echo  $form->field($model, 'pv_bee')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '+7 999-999-9999',
        ]) ;

    echo "</div>" ;

echo "</div>" ;

echo "<div id='pv_form_right'> " ;

//        $date_now   = date("d.m.y h:i:s");
//        $date_now   = date("d.m.Y H:i:s");

        $date_now   = date("Y-m-d H:i:s");

        $model->dt_create = $date_now;



        echo $form->field($model, 'dt_create')->widget(DateTimePicker::className(),[


                'name' => 'dp_1',
                'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                'size' => 'lg',
                'options' => ['placeholder' => 'Ввод даты/времени...'],
//                    'convertFormat' => true,
                    //'value'=> date("d.m.Y H:i:s", date(time())),
                'value'=> $date_now,
                'pluginOptions' => [
                    'pickerPosition' => 'bottom-left',

	                //'format' => 'dd.mm.yyyy HH:ii:ss',

                    //'format' => 'dd.mm.yyyy HH:ii:ss',
                    'format' => 'yyyy-mm-dd HH:ii:ss',

                    //'format' => 'yyyy-mm-dd H:i:s',
                    ///////OK2 'format' => 'yyyy-mm-dd H:i:s',


                    'autoclose'=>true,
                    'weekStart'=>1, //неделя начинается с понедельника
                    //'startDate' => ''.$date_now, //самая ранняя возможная дата

                    //'startDate' => date('d.m.Y H:i:s',strtotime('first day of  -1 month')),
                    'startDate' => date('Y-m-d H:i:s',strtotime('first day of  -1 month')),
                    'todayBtn'=>true, //снизу кнопка "сегодня"
                ]
            ]);





echo "</div>" ;
echo "<div id='pv_form_center'> " ;

            echo $form->field($model, 'id')
                ->textInput([
                    'type' => 'text',
                    'readonly' => 'readonly',

                ]);




            echo  $form->field($model, 'bar_code_pv')
                ->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '9999999999',
                ]) ;

            echo  $form->field($model, 'qr_code_pv')
                ->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '9999999999',
                ]) ;



echo "</div>" ;


echo "</div>" ;

?>



    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

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
//require_once('..\..\vendor\autoload.php');
//$var = 123;
//начало многосточной строки, можно использовать любые кавычки

$script = <<<TAG
$('#type_pv').change(function() {    
        var txt = $("#type_pv option:selected").text();
        $('#pv-type_pv_name').val(txt);
      
    });


$('#group_pv').change(function() {
        var txt = $("#group_pv option:selected").text();
        $('#pv-group_pv_name').val(txt);
      
    });


$('#active_pv').change(function() {
        var txt = $("#active_pv option:selected").text();
        $('#pv-active_pv_name').val(txt);      
    });

$('#pv-dt_create').datetimepicker(
    { format: 'MM/DD/YYYY' }).change( function (e) {
        
                var x=$('#pv-dt_create').val();
        
                var date = new Date(x);        
                var isodate = Date.parse(date);    //!!!!
        
        $('#pv-dt_create_mongo').val(isodate);
        
    });
        

//    $('#datetimepicker').datetimepicker('setEndDate', '2012-01-01');
//     function foo() {    
//     return  $ var; //можно использовать переменные
//     }

TAG;


//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>






