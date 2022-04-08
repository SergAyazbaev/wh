

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
        /*width: 35%;*/
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


    .pv_form_left_text{
        /*background-color: #a0a57f5c;*/
        /* background-color: #a0a57f5c; */
        margin: 9px 4px;
        display: block;
        float: left;
        padding: 29px;
    }
    .bar_code{
        position: relative;
        top: 20px;
        display: block;
        left: 20px;
        float: right;
    }


    @media (max-width: 710px) {

        #pv_form_head {
            display: block;
            padding: 10px;
            float: left;
            width: 100%;
            height: auto;
            margin-bottom: 31px;
            background-color: #0000001c;
        }
        .pv_form_left_text {
            background-color: #a0a57f5c;
            background-color: #a0a57f5c;
            margin: 9px 0px;
            padding: 11px;
            display: block;
            float: left;
            overflow: auto;
            width: 100%;
        }
    }
</style>


<?php


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



            echo $form->field($model, 'id')
                ->textInput([
                    'type' => 'text',
                    'readonly' => 'readonly',

                ]);





            $date_now   = date("d.m.Y H:i:s");


        if(!isset($model->dt_create))
        {
            $model->dt_create = $date_now;
        }

        echo $form->field($model, 'dt_create')
//            ->widget(DateTimePicker::className(),[
//
//                'name' => 'dp_1',
//                'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
//                'size' => 'lg',
//                'options' => ['placeholder' => 'Ввод даты/времени...'],
////                    'convertFormat' => true,
//                    //'value'=> date("d.m.Y H:i:s", date(time())),
//                'value'=> $date_now,
//                'pluginOptions' => [
//                    'pickerPosition' => 'bottom-left',
//
//                    //'format' => 'dd.mm.yyyy HH:ii:ss',
//
//                    //'format' => 'dd.mm.yyyy HH:ii:ss',
//                    'format' => 'yyyy-mm-dd HH:ii:ss',
//
//                    //'format' => 'yyyy-mm-dd H:i:s',
//                    ///////OK2 'format' => 'yyyy-mm-dd H:i:s',
//
//
//                    'autoclose'=>true,
//                    'weekStart'=>1, //неделя начинается с понедельника
//                    //'startDate' => ''.$date_now, //самая ранняя возможная дата
//
//                    //'startDate' => date('d.m.Y H:i:s',strtotime('first day of  -1 month')),
//                    'startDate' => date('d.m.Y H:i:s',strtotime('first day of  -1 month')),
//                    'todayBtn'=>true, //снизу кнопка "сегодня"
//                ]
//            ])
            ->textInput([
                'type' => 'text',
                'format' => 'php:dd.mm.yyyy H:i:s',
                'readonly' => 'readonly',

            ]);




        $model->dt_print = $date_now;



            //                ->widget(\yii\widgets\MaskedInput::className(), [
            //                'mask' => '[9]{5,15}[9]{5,15}',
            //            ]) ;


            echo $form->field($model, 'dt_print')
                ->textInput([
                    'type' => 'text',
                    'readonly' => 'readonly',

                ]);






                    //echo  $form->field($model, 'bar_code_aa');
                    //echo  $form->field($model, 'bar_code_int');
                    echo  $form->field($model, 'bar_code_cross')
                        ->textInput([
                            'type' => 'text',
                            'readonly' => 'readonly',

                        ]);




            echo "</div>" ;



        echo "<div class='pv_form_left_text'>" ;
            echo $model->html_text;
        echo "</div>";






echo "</div>" ;
echo "</div>" ;

if (!empty($original_text))
    echo $original_text;

?>




    <div class="form-group">
        <?= Html::Button('&#8593;',
            [
                'class' => 'btn btn-warning',
                'onclick'=>"window.history.back();"
            ])
        ?>

        <?php
//        = Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
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
$var = 123;
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




//$('#pv-dt_create').datetimepicker(
//    { format: 'MM/DD/YYYY' }).change( function (e) {
//        
//                var x=$('#pv-dt_create').val();
//        
//                var date = new Date(x);        
//                var isodate = Date.parse(date);    //!!!!
//        
//        $('#pv-dt_create_mongo').val(isodate);
//        
//    });
// });


        //$("#button1").click(function(){    
    
$(".bar_code").removeAttr("style");
$(".bar_code").css("top", "-3px");
$(".bar_code").css("left", "0px");   

$(".bar_code").css('color','red');
            
            //$(".bar_code").css('color','red');
        //});

TAG;


//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);
?>

