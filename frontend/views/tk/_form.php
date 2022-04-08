<?php

use frontend\models\posttk;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


Pjax::begin([
    'id' => 'pjax-container',
]);

echo yii::$app->request->get('page');

Pjax::end();
?>



<style>
    thead th{
        height: 22px;
        padding: 10px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    table tr,table td,{
        border: 0px;
        margin: 0px;
        width: 100%;
    }
    thead tr,thead td{
        background-color: rgba(11, 147, 213, 0.12);
        padding: 5px 10px;
        margin: 0px;
        padding: 10px;
    }
    tbody tr,tbody td{
        padding: 1px 3px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }
    td div select{
        /*background-color: rgba(65, 255, 61, 0.24);*/
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        width: 100%;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }
    td div input{
        background-color: rgba(35, 216, 57, 0.21);
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }



    /*********/
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
        /*background-color: #965e311f;*/
        width: 25%;
        /* width: 90%; */
        min-width: 322px;
        /* height: 616px; */
        display: block;
        padding: 30px;
        float: left;
    }

    .pv_motion_create_right_center {
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

    .pv_motion_create_right_center {
        WIDTH: 99%;
        /*max-width: 693px;*/

    }

    /*div>.help-block{*/
    /*z-index: 100;*/
    /*color: #da1713;*/
    /*color: #00ff43;*/
        /*display: block;*/
    /*font-size: 16px;*/
    /*}*/




    @media (max-width:1100px) {
        .pv_motion_create_all {
            width: 60%;
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

    /*.form-group.field-tz-wh_deb_top_name,*/
    /*.form-group.field-tz-wh_cred_top_name*/
    /*{*/
    /*display: none;*/
    /*}*/

    @media (max-width: 710px) {
        h1 {
            font-size: 20px;
            padding: 5px 15px;
        }
        .pv_motion_create_all {
            width: 96%;
            margin: 0px 0px;
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
        .form-group {
            margin-bottom: 0px;
        }
    }

</style>



<div class="pv-action-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="pv_motion_create_left">

        <div class="pv_motion_create_left_one">
            <?= $form->field($model, 'id')
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 57px; margin-right: 5px;',
                    'readonly'=>'readonly',
                ])->label('№:'); ?>
        </div>

        <div class="pv_motion_create_left_one">
            <?php
                if (empty($model->user_create_id) ) {
                    $model->user_create_id = Yii::$app->user->identity->id;
                };
            echo  $form->field($model, 'user_create_id')
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 47px; margin-right: 5px;',
                    'readonly'=>'readonly',
                ])->label('Id');
            ?>

        </div>

        <div class="pv_motion_create_left_one">
            <?php
            if (empty($model->user_edit_group_id) ) {
                $model->user_edit_group_id = Yii::$app->user->identity->group_id;
            };

            echo  $form->field($model, 'user_edit_group_id')
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 47px; margin-right: 5px;',
                    'readonly'=>'readonly',
                ])->label('GR'); ?>
        </div>

        <div class="pv_motion_create_left_one">
            <?php
                if (empty($model->user_create_name) ) {
                    $model->user_create_name = Yii::$app->user->identity->username;
                };
            ?>

            <?= $form->field($model, 'user_create_name')
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 100px; margin-right: 5px;',
                    'readonly'=>'readonly',
                ])->label('Login'); ?>
        </div>

        <div class="pv_motion_create_left_one">

        </div>


        <div class="pv_motion_create_left_date">
            <div class="table_tk">
            </div>
        </div>

    </div>


    <div class="pv_motion_create_all">
        <div class="pv_motion_create_right_center">

        <?php

        $xx=posttk::find()->all();
        $type_words= ArrayHelper::getColumn($xx,'name_tk');


        ?>

        <?= $form->field($model, 'name_tk')
            ->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $type_words,
                ],
            ])
            ->textInput(['placeholder' => $model->getAttributeLabel('name_tk'),
                'style' => 'margin-right: 5px;'])
            ->label(false);

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


<?php
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

