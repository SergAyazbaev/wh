<?php

use frontend\models\posttz;
use frontend\models\Sprwhtop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;

?>



<style>
    .table_tk{
        background-color: #b5aaa22e;
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
        float: left;
    }



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

    .pv_motion_create_right_center {
        min-width: 345px;
        background-color: #74bb2e33;
        display: block;
        position: inherit;
        float: left;
        padding: 9px;
        margin-left: 5px;
        margin-bottom: 5px;
    }

    .pv_motion_create_right_center {
        width: 99%;
    }


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
            <?= $form->field($model, 'id', ['inputOptions' => ['readonly'=>'readonly']] )
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 57px; margin-right: 5px;',
                ])->label('№:'); ?>
        </div>

        <div class="pv_motion_create_left_one">
	        <?php
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
	        <?php
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
	        <?php
            if (empty($model->user_create_name) ) {
                $model->user_create_name = Yii::$app->user->identity->username;
            };
            ?>

            <?= $form->field($model, 'user_create_name', ['inputOptions' => ['readonly'=>'readonly']] )
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 100px; margin-right: 5px;',
                    'readonly'=>'readonly',
                ])->label('Login'); ?>
        </div>

        <div class="pv_motion_create_left_one">
            <?php

//            if (empty($model->dt_create) ){
//                $model->dt_create    = Date('d.m.Y H:i:s', strtotime('now'));
//            };


//            ddd($model);


//            echo  $form->field($model, 'dt_create',
//                ['inputOptions' => [
//                    'value'     => $model->dt_create,
//                    'readonly'  => 'readonly',
//                ]]);


            ?>

        </div>


        <div class="pv_motion_create_left_date">
            <div class="table_tk">
            </div>
        </div>

    </div>


    <div class="pv_motion_create_all">
        <div class="pv_motion_create_right_center">

	        <?php
                $xx= posttz::find()->all();
                $type_words=  ArrayHelper::getColumn($xx,'name_tz');
            ?>

        <div class="tree_land">
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
            ?>
        </div>

	        <?php
            $type_words=[
                '1' => 'Первичная полная установка',
                '2' => 'Первичная частичная установка',
                '3' => 'Демонтаж',
                '4' => 'Монтаж',
                '5' => 'Выдача расходных материалов',
                ];


            ?>

       <div class="tree_land">

            <?= $form->field($model, 'street_map')
                ->dropDownList(
                          $type_words,
                    [ 'prompt' => 'Выберите один вариант' ])
                ->label(false)
                ->hint(' Маршрут накладных');
            ?>

            <?= $form->field($model, 'multi_tz')
                ->hiddenInput(['value' => 1 ])
                ->label(false);
            ?>

       </div>


                <div class="tree_land">
	                <?php
                        echo $form->field($model, 'wh_cred_top')->dropdownList(
	                        ArrayHelper::map(
		                        Sprwhtop::find()
		                                ->orderBy( 'name' )
		                                ->all(),
		                        'id', 'name' ),
                            [
                                'prompt' => 'Выбор склада ...'
                            ]
                        )
                            //->label("Автопарк")
                            ->label(false)
                            ->hint(' Маршрут накладных');

                    ?>
                </div>






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

