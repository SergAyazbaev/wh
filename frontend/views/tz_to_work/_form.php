<?php

use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;


//use yii\widgets\Pjax;
//
//Pjax::begin([
//    'id' => 'pjax-container',
//]);
//
//echo \yii::$app->request->get('page');
//
//Pjax::end();








/* @var $this yii\web\View */
/* @var $model frontend\models\Pvaction */
/* @var $form yii\widgets\ActiveForm */
?>



<style>
    .table_tk{
        background-color: #b5aaa22e;
        /* height: 200px; */
        margin-top: 78px;
    }

    .form-group{
        float: none;
        margin: 8px;
    }

    .pv_motion_create_left_one{
        width: min-content;
        display: block;
        position: inherit;
        float: left;
    }
    .pv_motion_create_left_date{

    }

    .pv_motion_create_all{
        width: 66%;
        /* height: max-content; */
        /* background-color: #95c75f29; */
        /* display: block; */
        /* position: relative; */
        float: left;
    }

    /*@media screen(max-width:1300px) {*/
    /*.pv_motion_create_all {*/
    /*width: 65%;*/
    /*}*/
    /*}*/


    .pv_motion_create_left{
        background-color: #965e311f;
        width: 25%;
        /* width: 90%; */
        min-width: 322px;
        /* height: 616px; */
        display: block;
        padding: 30px;
        float: left;
    }
    .pv_motion_create_right, .pv_motion_create_button_place, .pv_motion_create_right_center {
        /*width: 49%;*/
        min-width: 345px;
        /*background-color: #74bb2ec2;*/
        background-color: #74bb2e33;
        display: block;
        position: inherit;
        float: left;
        padding: 9px;
        margin-left: 5px;
        margin-bottom: 5px;
    }

    .pv_motion_create_right_center, .pv_motion_create_right{
        WIDTH: 99%;
        /*max-width: 693px;*/

    }
    .pv_motion_create_right{
    }

    /*.pv_motion_create_ok_button{*/
    /*display: block;*/
    /*position: inherit;*/
    /*padding: 12%;*/
    /*float: inherit;*/
    /*}*/


    .pv_motion_content{
        width: 660px;
    }

    div>.help-block{
        z-index: 100;
        color: #da1713;
        color: #00ff43;
        display: block;
        font-size: 16px;
    }




    @media (max-width:1100px) {
        .pv_motion_create_all {
            width: 60%;
        }
        .pv_motion_content{
            width: 490px;
        }
    }
    @media (max-width:900px) {
        .pv_motion_create_all {
            width: 95%;
            margin-top: 15px;
            padding: 10px;
            padding-top: 25px;

        }
    }


    .pv_motion_create_button_place{
        background-color: #74bb2e08;
        padding: 39px 90px;
        margin-left: 5px;
        margin-bottom: 5px;
    }

    .control-label{
        display: inline-block;
        width: 100%;
        margin-bottom: 5px;
        font-weight: bold;
    }
</style>

<style>
    .tree_land{
        background-color: cornsilk;
        padding: 15px;
        /* display: inline-grid; */
        /* float: left; */
    }


    .form-group.field-tz-wh_deb_top_name,
    .form-group.field-tz-wh_cred_top_name
    {
        display: none;
    }

</style>

<style>
    @media (max-width: 710px) {
        h1, .h1 {
            font-size: 20px;
            padding: 5px 15px;
        }
        .pv_motion_create_all {
            width: 96%;
            margin: 0px 0px;
            padding: 0px;
        }
        .pv_motion_create_button_place {
            width: 96%;
            padding: 0px;
        }
        .pv_motion_create_left {
            background-color: #888a701f;
            min-width: 267px;
            display: block;
            padding: 25px;
            margin-bottom: 15px;
            margin-left: 7px;
        }
        .pv_motion_create_right {
            width: 50%;
            min-width: 200px;
            float: inherit;
            padding: 10px 20px;
        }
        .pv_motion_create_right_center {
            width: 100%;
            min-width: 270px;
            background-color: #74bb2e33;
            display: block;
            position: inherit;
            float: left;
            padding: 3px;
            margin-left: 5px;
            margin-bottom: 5px;
        }
    }

    @media (max-width: 600px){
        .pv_motion_create_left {
            background-color: #888a701f;
            display: block;
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
        }
        .pv_motion_create_right {
            width: 100%;
            min-width: 263px;
            height: 115px;
            padding: 0px 10px;
            display: block;
            position: initial;
        }
        .tree_land {
            /*     width: 100%;
                 background-color: cornsilk;
                 padding: 4px 12px;
                 display: inline-grid;
                 float: left;*/
        }
        .form-group {
            margin-bottom: 0px;
        }
    }

</style>



<div class="pv-action-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="pv_motion_create_left">

        <div class="pv_motion_create_left_one">
            <?= $form->field($model, 'id', ['inputOptions' => ['readonly'=>'readonly']] )
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 57px; margin-right: 5px;',
                ])->label('№:'); ?>
        </div>

        <div class="pv_motion_create_left_one">
            <?
            if (empty($model->user_create_id) ) {
                $model->user_create_id = Yii::$app->user->identity->id;
            };
            ?>

            <?= $form->field($model, 'user_create_id', ['inputOptions' => ['readonly'=>'readonly']] )
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 47px; margin-right: 5px;',
                    'readonly'=>'readonly',
                ])->label('Id'); ?>
        </div>

        <div class="pv_motion_create_left_one">
            <?
                if (empty($model->user_edit_group_id) ) {
                    $model->user_edit_group_id = Yii::$app->user->identity->group_id;
                };
            ?>
            <?= $form->field($model, 'user_edit_group_id', ['inputOptions' => ['readonly'=>'readonly']] )
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 47px; margin-right: 5px;',
                    'readonly'=>'readonly',
                ])->label('Gr'); ?>
        </div>

        <div class="pv_motion_create_left_one">
            <?
//            if (empty($model->user_create_name) ) {
//                $model->user_create_name = Yii::$app->user->identity->username;
//            };
            ?>

            <?
//            = $form->field($model, 'user_create_name', ['inputOptions' => ['readonly'=>'readonly']] )
//                ->textInput(['class' => 'form-control class-content-title_series',
//                    'placeholder' => 'ошибка',
//                    'style' => 'width: 100px; margin-right: 5px;',
//                    'readonly'=>'readonly',
//                ])->label('Login'); ?>
        </div>

        <div class="pv_motion_create_left_one">
            <?php

            if (empty($model->dt_create) ){
                $model->dt_create    = Date('Y-m-d H:i:s', strtotime('now'));
            };

            echo  $form->field($model, 'dt_create',
                ['inputOptions' => [
                    'value' => $model->dt_create,
                    'readonly'=>'readonly',


                ]]);


            ?>

        </div>


        <div class="pv_motion_create_left_date">
            <div class="table_tk">
            </div>
        </div>

    </div>


    <div class="pv_motion_create_all">
        <div class="pv_motion_create_right_center">

            <?
            $xx = frontend\models\posttz::find()->all();
            $type_words= \yii\helpers\ArrayHelper::getColumn($xx,'name_tz');

            echo '<div class="tree_land">';
            ?>

            <?= $form->field($model, 'name_tz')
                ->widget(
                    AutoComplete::className(), [
                    'clientOptions' => [
                        'source' => $type_words,
                    ],
                ])
                ->textInput(['placeholder' => $model->getAttributeLabel('name_tz'),
                    'style' => 'margin-right: 5px;'])
                ->label(false)
                ->hint('Пожалуйста, введите название ТехЗадания');




            echo '</div>';
            ?>


            <?= $form->field($model, 'multi_tz')
                        ->hiddenInput(['value' => 1 ])
                        ->label(false);

            ?>

            <?
//            = $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::classname(),
            // [    //                             configure additional widget properties here
//                        ])->label(false);
            ?>


        </div>


        <div class="form-group">
            <div class="pv_motion_create_ok_button">

                <?= Html::Button('&#8593;',
                    [
                        'class' => 'btn btn-warning',
                        'onclick'=>"window.history.back();"
                    ])
                ?>

                <br><br>
                <?= Html::submitButton('Сохранить',
                    ['class' => 'btn btn-success']) ?>

            </div>
        </div>



    </div>




    <?php ActiveForm::end(); ?>

</div>





<?
//Modal::begin([
//    'header' => '<h2>Вот это модальное окно!</h2>',
//    'toggleButton' => [
//        'tag' => 'button',
//        'class' => 'btn btn-lg btn-block btn-info',
//        'label' => 'Нажмите здесь, забавная штука!',
//    ]
//]);
//
//echo 'Надо взять на вооружение.';
//
//Modal::end();



//use yii\widgets\Pjax;
//use yii\web\JsExpression;




$script = <<<JS

//$('#pvmotion-type_action').change(function() {
// Обработал в контроллере!!!     
//}    

//////////////////// Debitor
$('#tz-wh_deb_top').change(function() {
    	    
    var  number = $(this).val();
    var  text = $('#tz-wh_deb_top>option[value='+number+']').text();
    
            
    	    $('#tz-wh_deb_top_name').val(text);
    	    
            console.log(number);   
            console.log(text);   
    
    $.ajax( {
		url: '/pvmotion/whselect',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#tz-wh_deb_element').html('');
		    $('#tz-wh_deb_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
    
    
    
});

//////////////////// Creditor
$('#tz-wh_cred_top').change(function() {
    var  number = $(this).val();
    var  text = $('#tz-wh_cred_top>option[value='+number+']').text();
    
    	    $('#tz-wh_cred_top_name').val(text);
    	    
    	    
            console.log(number);   
            console.log(text);   
    
    $.ajax( {
		url: '/pvmotion/whselect',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#tz-wh_cred_element').html('');
		    $('#tz-wh_cred_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
    
    
    
});


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

