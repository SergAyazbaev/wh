<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


Pjax::begin([
    'id' => 'pjax-container',
]);

echo \yii::$app->request->get('page');

Pjax::end();



/* @var $this yii\web\View */
/* @var $model frontend\models\Pvaction */
/* @var $form yii\widgets\ActiveForm */
?>

<?//= Html::Button('⭱', ['class' => 'btn btn-warning']);
//⬉
//⬅
?>



<style>
    .form-group{
        float: none;
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
        max-width: 693px;

    }
    .pv_motion_create_right{
    }

    .pv_motion_create_ok_button{
        display: block;
        position: inherit;
        padding: 12%;
        float: inherit;
    }


    .pv_motion_content{
        width: 660px;
    }

    .help-block{
        z-index: 100;
        color: #da1713;
        display: block;
        font-size: 16px;
    }
    div>.help-block{
        display: flex;
        color: #da1713;
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


    .form-group.field-pvmotion-wh_deb_top_name,
    .form-group.field-pvmotion-wh_cred_top_name
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
            <?= $form->field($model, 'id')
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 57px; margin-right: 5px;',
                    'disabled' => 'true'
                ])->label('№'); ?>
        </div>

        <div class="pv_motion_create_left_one">
            <?= $form->field($model, 'pv_id')
                ->textInput(['class' => 'form-control class-content-title_series',
                    'placeholder' => 'ошибка',
                    'style' => 'width: 57px; margin-right: 5px;',
                    'disabled' => 'true'])->label('Инв. №'); ?>
        </div>

        <div class="pv_motion_create_left_one">
            <?= $form->field($model, 'name')
                ->textInput(['class' => 'form-control class-content-title_series',
                    'style' => 'width: 130px; margin-right: 5px;',
                    'placeholder' => 'ошибка',
                'disabled' => 'true'])->label('Пользователь');
            ?>
        </div>

        <div class="pv_motion_create_left_date">
        <?
            /////////////////

            //            $date_now   = date("d.m.Y H:i:s");


            if ($model->dt_create)
                //$date_now   = date("d.m.Y H:i:s",strtotime($model->dt_create));
                $date_now   = date("d.m.Y H:i:s",strtotime($model->dt_create));
            else
                $date_now   = date("d.m.Y H:i:s");

            $model->dt_create = $date_now;

            //echo $form->field($model, 'dt_create')->widget(DatePicker::className(),[


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
                    'startDate' => date('d.m.Y H:i:s',strtotime('first day of  -1 month')),
                    //'startDate' => date('d.m.Y H:i:s',strtotime('first day of  -1 month')),

                    'todayBtn'=>true, //снизу кнопка "сегодня"

                ]
            ]);
            ?>


        </div>
    </div>








    <div class="pv_motion_create_all">
        <div class="pv_motion_create_right_center">

            <?
            echo '<div class="tree_land">';
                echo $form->field($model, 'type_action')->dropdownList(
                    \yii\helpers\ArrayHelper::map(frontend\models\Typemotion::find()->all(), 'id', 'name'),
                    //frontend\models\Typemotion::find()->select(  ['name']  )-> indexBy('name') ->column(),
                    [
                        'prompt' => 'Выбор типовой операции'
                    ]
                );


            echo '</div>';


            ?>

        </div>


        <div class="pv_motion_create_right_center">
         <?

        // dd($model);

            echo '<p>От кого:</p>';
            echo '<div class="tree_land">';
         //            'wh_deb_top',
         //            'wh_deb_top_name',
         //            'wh_deb_element',
         //
         //            'wh_cred_top',
         //            'wh_cred_top_name',
         //            'wh_cred_element',

                echo $form->field($model, 'wh_deb_top')->dropdownList(
                    \yii\helpers\ArrayHelper::map(frontend\models\Sprwhtop::find()->all(), 'id', 'name'),
                    [
                        'prompt' => 'Выбор склада ...'
                    ]
                );


                 echo $form->field($model, 'wh_deb_top_name' )
                 ->label('')->hiddenInput();


                 //dd($model);

                 echo $form->field($model, 'wh_deb_element')->dropdownList(
                     frontend\models\Sprwhelement::find()->where(['parent_id' => (integer)$model->wh_deb_top])->select(['name'])->indexBy('name')->column(),
                    [
                      //  'prompt' => $model->wh_element, //'Выбор склада #.#'
                        'prompt' => 'Выбор склада #.#'
                    ]
                );

             echo '</div>';
             ?>

        </div>

        <div class="pv_motion_create_right_center">

            <?

         echo '<p>Кому:</p>';
         echo '<div class="tree_land">';


         echo $form->field($model, 'wh_cred_top')->dropdownList(
             \yii\helpers\ArrayHelper::map(frontend\models\Sprwhtop::find()->all(), 'id', 'name'),
             [
                 'prompt' => 'Выбор склада ...'
             ]
         );

         echo $form->field($model, 'wh_cred_top_name')->dropdownList(
             frontend\models\Sprwhtop::find()->select(['name'])->indexBy('name')->column(),
             [
                 'style'=>'display: none ',
             ]
         )->label('')->hiddenInput();

         echo $form->field($model, 'wh_cred_element')->dropdownList(
             frontend\models\Sprwhelement::find()->where(['parent_id' => (integer)$model->wh_cred_top])->select(['name'])->indexBy('name')->column(),
             [
                 //  'prompt' => $model->wh_element, //'Выбор склада #.#'
                 'prompt' => 'Выбор склада #.#'
             ]
         );



         // $form->field($model, 'category_id')->dropDownList(
         // \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Category::find()->all(), 'id', 'name')
         //) ;


         // $form->field($model, 'category_id')->dropDownList(
         //    \yii\helpers\ArrayHelper::map(\app\modules\admin\models\Category::find()->all(), 'id', 'name')
         //) ;




         echo '</div>';

            ?>
    </div>

    <div class="pv_motion_create_right">
            <?= $form->field($model, 'content')
                ->textarea(['class' => 'pv_motion_content',
                    'style' => "width: 100%;display: inline;"
                ]); ?>
    </div>

    <div class="pv_motion_create_right">
            <?= $form->field($model, 'document')
                ->textarea(['class' => 'pv_motion_content',
                    'style' => "width: 100%;display: inline;"
                ]); ?>

    </div>

    <div class="pv_motion_create_right">
        <?php  echo $form->field($model, 'comments')
            ->textarea(['class' => 'pv_motion_content',
                'style' => "width: 100%;display: inline;"
            ]); ?>

    </div>

    <div class="pv_motion_create_ok_button">
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::Button('⭱', ['class' => 'btn btn-warning']);?>
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
$('#pvmotion-wh_deb_top').change(function() {
    	    
    var  number = $(this).val();
    var  text = $('#pvmotion-wh_deb_top>option[value='+number+']').text();
    
            
    	    $('#pvmotion-wh_deb_top_name').val(text);
    	    
            console.log(number);   
            console.log(text);   
    
    $.ajax( {
		url: '/pvmotion/whselect',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#pvmotion-wh_deb_element').html('');
		    $('#pvmotion-wh_deb_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
    
    
    
});

//////////////////// Creditor
$('#pvmotion-wh_cred_top').change(function() {
    var  number = $(this).val();
    var  text = $('#pvmotion-wh_cred_top>option[value='+number+']').text();
    
    	    $('#pvmotion-wh_cred_top_name').val(text);
    	    
    	    
            console.log(number);   
            console.log(text);   
    
    $.ajax( {
		url: '/pvmotion/whselect',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#pvmotion-wh_cred_element').html('');
		    $('#pvmotion-wh_cred_element').html(res);
		    
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

