<?php

use frontend\models\Sprrestoreelement;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Pvaction */
/* @var $form yii\widgets\ActiveForm */
?>


<style>
    .pv_all{
        width: 100%;
        /*background-color: aqua;*/
        float: left;
    }
    .pv_motion_create_all{
        width: 70%;
        min-width: 243px;
        background-color: #3520b321;
        display: block;
        /* position: relative; */
        float: left;
        margin-left: 5px;
    }
    .pv_motion_create_left{

        background-color: #b6bd301f;
        width: 27%;
         max-width: 330px;
         min-width: 197px;
        /* min-height: 481px; */
        display: block;
        padding: 15px;
        float: left;
    }
    .pv_motion_create_right{
        height: 75px;
        width: 49%;
        min-width: 200px;
        float: inherit;
        padding: 10px 20px;
    }

    .pv_motion_create_button_place {
        width: 70%;
        min-width: 244px;
        background-color: #11de4e29;
        padding: 15px 30px;
        display: inline-grid;
        margin-left: 5px;
        margin-top: 5px;
        position: relative;
    }

    .form-group {
        margin-bottom: 0px;
    }


</style>
<style>
    @media (max-width: 710px) {
        h1, .h1 {
            font-size: 20px;
            padding: 5px 20px;
        }
        .pv_motion_create_all {
            width: 96%;
            margin: 6px 5px;
        }
        .pv_motion_create_button_place {
            width: 96%;
            padding: 7px;
        }
        .pv_motion_create_left {
            background-color: #888a701f;
            width: 96%;
            margin: 5px 6px;
            display: block;
            padding: 15px;
            float: left;
        }
        .pv_motion_create_right {
            height: 75px;
            width: 48%;
            min-width: 200px;
            float: inherit;
            padding: 10px 20px;
        }
    }

    @media (max-width: 600px){
        .pv_motion_create_right {
            height: 75px;
            width: 100%;

        }
    }

</style>


<div class="pv-action-form">



    <?php $form = ActiveForm::begin(); ?>


 <div class="pv_all">

    <div class="pv_motion_create_left">


        <?= $form->field($model, 'id')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 57px; margin-right: 5px;',
                'disabled' => 'true'
            ]); ?>

        <?= $form->field($model, 'pv_id')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 57px; margin-right: 5px;',
                'disabled' => 'true'
            ]); ?>

        <?= $form->field($model, 'user_name')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 130px; margin-right: 5px;',
                'placeholder' => 'UserName',
                'disabled' => 'true'])->label('Пользователь');
        ?>



        <?

        $date_now=$model->dt_create   = Date('Y-m-d H:i:s', strtotime('now'));

        $model->dt_create = $date_now;

        echo $form->field($model, 'dt_create')->widget(DateTimePicker::className(),[
                'name' => 'dp_1',
                'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                'size' => 'lg',
                'options' => ['placeholder' => 'Ввод даты/времени...'],
                'value'=> $date_now,
                'pluginOptions' => [
                    'pickerPosition' => 'bottom-left',
                        //'format' => 'dd.mm.yyyy HH:ii:ss',
                    'format' => 'yyyy-mm-dd HH:ii:ss',

                    'autoclose'=>true,
                    'weekStart'=>1, //неделя начинается с понедельника
                    //'startDate' => ''.$date_now, //самая ранняя возможная дата
                    'startDate' => date('Y-m-d H:i:s',strtotime('first day of  -1 month')),
                    //'startDate' => date('d.m.Y H:i:s',strtotime('first day of  -1 month')),

                    'todayBtn'=>true, //снизу кнопка "сегодня"

                ]


        ])->label('Дата');
        ?>

        <?php if(isset($model_pv)): ?>
        <?
        echo $form->field($model_pv, 'pv_imei')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 130px; margin-right: 5px;',
                'placeholder' => '123123123',
                'disabled' => 'true'])->label('IMEI');

        ?>

        <?
        echo $form->field($model_pv, 'pv_kcell')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 130px; margin-right: 5px;',
                'placeholder' => '7707070707',
                'disabled' => 'true'])->label('K-Cell');
        ?>

        <?
        echo $form->field($model_pv, 'pv_bee')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 130px; margin-right: 5px;',
                'placeholder' => '747474747747',
                'disabled' => 'true'])->label('BeeLine');
        ?>

        <?
        echo $form->field($model_pv, 'bar_code_pv')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 130px; margin-right: 5px;',
                'placeholder' => '123123123',
                'disabled' => 'true'])->label('BAR Code');
        ?>
        <?
        echo $form->field($model_pv, 'qr_code_pv')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 130px; margin-right: 5px;',
                'placeholder' => '123123123',
                'disabled' => 'true'])->label('QR Code');
        ?>
        <?php endif; ?>


    </div>


    <div class="pv_motion_create_all">

        <div class="pv_motion_create_right">
            <?

            echo $form->field($model, 'type_action_id')->dropdownList(
                \yii\helpers\ArrayHelper::map(frontend\models\Sprrestore::find()->all(), 'id', 'name'),
                [
                    'prompt' => '....'
                ]
            );
//            echo $form->field($model, 'type_action_name')
//                ->label('')->hiddenInput();


            ?>
        </div>
        <div class="pv_motion_create_right">
            <?
            //dd($x);
            echo $form->field($model, 'detail')->dropdownList(
                Sprrestoreelement::find()->select(  ['name']  )
                    ->where(['parent_id'=>(integer) $model->type_action_id])->indexBy('name') ->column(),
                [
                    'prompt' => '....'
                ]
            );
            ?>
        </div>

    </div>


    <div class="pv_motion_create_button_place">
                <?= $form->field($model, 'comments')
                    ->textarea(['class' => 'pv_motion_content',
                        'style' => 'min-width: 97%;height: 112px;'
                    ]); ?>



                <?= Html::submitButton('Сохранить',
                    [
                            'class' => 'btn btn-success',
                            'style' => 'padding: 11px 35px; margin: 44px 59px;'
                ]) ?>

    </div>
 </div>



    <?php ActiveForm::end(); ?>
</div>


<?
$script = <<<JS

//$('#pvmotion-type_action').change(function() {
// Обработал в контроллере!!!
//}

/// Выполнено. Пропайка 
$('#pvrestore-type_action').change(function() {
    var  number = $(this).val();    
    var  text = $('option[value='+number+']').text();
    
    $('#pvrestore-detail').val(text);

    // console.log(number);
    // console.log(text);

    // $.ajax( {
    //         url: '/pvrestore/restoreelementselect',
    //         data: {
    //                 id :number
    //                 },
    //         //dataType: "json",
    //         success: function(res) {
    //            
    //                 $('#pvrestore-detail').html('');
    //                 $('#pvrestore-detail').html(res);
    //        
    //                 // alert('OK. '+ res );
    //                 },
    //         error: function( res) {
    //                 alert('не пошло. '+ res );
    //         }
    // } );
    
});


/// Выполнено. Пропайка 
$('#pvrestore-type_action_id').change(function() {
    var  number = $(this).val();    
    var  text = $('option[value='+number+']').text();
    
    $('#pvrestore-detail').val(text);

    // console.log(number);
    // console.log(text);

    $.ajax( {
            url: '/pvrestore/restoreelementselect',
            data: {
                    id :number
                    },
            //dataType: "json",
            success: function(res) {
                
                    $('#pvrestore-detail').html('');
                    $('#pvrestore-detail').html(res);
            
                    // alert('OK. '+ res );
                    },
            error: function( res) {
                    alert('не пошло. '+ res );
            }
    } );
});


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>


