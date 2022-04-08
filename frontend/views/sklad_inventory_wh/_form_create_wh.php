<?php

//use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<style>
    .modal-body {
        background-color: #f3f3f3;
        position: relative;
        padding: 15px;
        width: 100%;
        display: block;
        clear: both;
        float: left;
    }

    .name_nn {
        background-color: #faebd729;
        margin: 2px;
        display: block;
        width: 100%;
        height: 26px;
        float: left;
        font-size: 20px;
        padding: 0 40px;
    }

    .name_s {
        width: 50px;
        float: left;
        text-align: right;
        padding: 0 7px;
    }

    .name_s1 {
        float: left;
        display: block;
        height: 37px;
    }


    .modal-vn {
        /*background-color: #00ff43;*/
        display: block;
        position: relative;
    }

    .modal-vn > .btn.btn-default {
        float: left;
        margin-right: 10px;
        height: 22px;
        padding: 0;
        background-color: #00ff437d;
        border: 3px solid #2ebf0a5c;
    }


    .redstyle {
        color: rgba(65, 145, 69, 0.9);
        background: rgba(65, 145, 69, 0.15);
    }

    .multiple-input-list__item:hover {
        background: rgba(65, 145, 69, 0.27);
    }

    /*select:hover, select:active,select:after {*/
    /*    background-color: aquamarine;*/
    /*}*/

    div > .has-error > .help-block {
        /*display: block;*/
        /*position: fixed;*/
        /*top: 190px;*/
        padding: 9px;
        font-size: 21px;
        background-color: #ffd57fd4;
        color: crimson;
        width: 80%;
        left: 10%;
        text-align: center;
    }


    /*.glyphicon{}*/

    /*.glyphicon-remove{*/
    /*color: #b81900;*/
    /*content: "\2603";*/
    /*}*/
    /*.glyphicon-remove::before{*/
    /*content: "\2716";*/
    /*color: #b81900;*/
    /*}*/

    .glyphicon-plus {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;

        /*padding: 7px;*/
        /*margin: -8px;*/
        font-size: medium;
        font-weight: bold;
        border-radius: 25px;
    }

    .glyphicon-plus:hover {
        color: rgba(255, 255, 255, 0.53);
        background-color: #5cb85c;
        border-color: #4cae4c;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);

    }

    /*.help-block {*/

    /*}*/
    .help-block-error {
        display: none;
    }

    thead th {
        height: 22px;
        padding: 10px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    table tr, table td, {
        border: 0px;
        margin: 0px;
        width: 100%;
    }

    thead tr, thead td {
        background-color: rgba(11, 147, 213, 0.12);
        padding: 5px 10px;
        margin: 0px;
        padding: 10px;
    }

    tbody tr, tbody td {
        padding: 1px 3px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    td div select {
        /*background-color: rgba(65, 255, 61, 0.24);*/
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        width: 100%;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }

    td div input {
        background-color: rgba(35, 216, 57, 0.21);
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }


    .list-cell__button {
        text-align: center;
    }

    table.multiple-input-list.table-renderer tbody tr > td {
        border: 0 !important;
        padding: 0;
    }

    .pv_motion_create_right_center {
        /*width: 49%;*/
        min-width: 345px;
        /*background-color: #74bb2ec2;*/
        background-color: #74bb2e33;
        display: block;
        position: relative;
        /*float: left;*/
        /*padding: 9px;*/
        /*margin-left: 5px;*/
        /*margin-bottom: 5px;*/
    }


    .pv_motion_create_ok_button {
        display: block;
        position: inherit;
        padding: 10px 18px;
        float: left;
        width: 100%;
        /*margin: 5px;*/
        margin-top: 5px;
        background-color: #48616121;
    }


    @media (max-width: 780px) {
        .scroll_window {

            width: 99%;
            display: grid;
            position: inherit;
            overflow: auto;
            margin-bottom: 100px;
        }

    }


    @media (max-width: 710px) {

        h1 {
            font-size: 20px;
            padding: 5px 15px;
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


        .pv_motion_create_ok_button {
            display: block;
            position: inherit;
            padding: 10px 18px;
            float: left;
            width: auto;
            background-color: #48616121;
            /*margin: 5px;*/
            margin-top: 5px;
            background-color: #48616121;
        }

    }


    .pv_motion_create_right_center {
        WIDTH: 100%;
        min-width: 240px;
        margin: 0px;
        padding: 3px 3px;
    }

    @media (min-width: 605px) {
        .pv_motion_create_right_center {
            padding: 3px 3px;
        }
    }

    @media (min-width: 1214px) {
        .pv_motion_create_right_center {
            padding: 15px 10px;
        }
    }

    .modal_left {
        width: 90%;
        margin: 0px 5%;
        margin-bottom: 5%;
    }


</style>


<?php $form = ActiveForm::begin([
    'id' => 'project-form',
    'method' => 'POST',

    'options' => [
        // 'data-pjax' => 1,
        'autocomplete' => 'off'
    ],

    'enableClientValidation' => false,
    'validateOnChange' => true,
    'validateOnSubmit' => true,

]);
?>


<div id="tz1">
    <div class="pv_motion_create_right_center">
        <div class="tree_land3">
            <?php
            echo "<br><h2> Столбовые WH </h2>";
            ?>
        </div>

        <div class="tree_land">
            <?php

            echo $form->field($new_doc, 'dt_create')
                ->widget(DateTimePicker::className(), [

                    'type' => DateTimePicker::TYPE_INPUT,
                    //TYPE_INLINE,
                    // 'model' => $searchModel,
                    //									'value'         => date( 'd.m.Y', strtotime( $model->dt_stop ) ),
                    'attribute' => 'dt_stop',
                    'language' => 'ru',
                    'name' => 'dt_stop',
                    'convertFormat' => false,
                    'options' => [
                        'placeholder' => 'Дата - STOP',
                        'autocomplete' => "off",
                    ],

                    'pluginOptions' => [
                        //'format' => 'dd.mm.yyyy 00:00:01',
                        'format' => 'dd.mm.yyyy HH:ii:ss',
                        //                                'todayHighlight' => true,
                        'autoclose' => true,
                        'weekStart' => 1,
                        //неделя начинается с понедельника
                        //'pickerPosition' => 'top-left',
                        // 'startDate' => $date_now,
                        'todayBtn' => true,
                        //снизу кнопка "сегодня"
                    ],


                ]);
            ?>


            <?php
            echo $form->field($new_doc, 'dt_update')
                ->textInput(['readonly' => true]);
            ?>


        </div>


        <div class="tree_land">
            <?php

            //////////
            echo $form->field($new_doc, 'wh_destination')->dropDownList(
                $list_top
            )->label("Компания");


            ?>

            <?php

            //////////
            echo $form->field($new_doc, 'wh_destination_element')->dropDownList(
                ['...'],
                [
                    'prompt' => 'Выбор склада ...'
                ]
            )->label("Компания");


            ?>


        </div>

    </div>


    <div class="pv_motion_create_ok_button">


        <?php
        // $alert_mess  MESSAGE
        if (isset($alert_mess) && !empty($alert_mess)) {
            echo Alert::widget([
                'options' => [
                    'class' => 'alert_save',
                    'animation' => "slide-from-top",
                ],
                'body' => $alert_mess
            ]);
        }
        ?>



        <?php
        echo Html::a('Выход', ['/sklad_inventory_cs/return_to_refer'], ['class' => 'btn btn-warning']);
        ?>




        <?php
        ///////////////////////
        Modal::begin([
            'header' => '<h2>Заливаем Остатки по всем автобусам из Эксела.</h2>',
            'toggleButton' => [
                'label' => 'Копипаст BarCodes',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],//'footer' => 'Низ окна',
        ]);
        ?>
        <?= $form
            ->field($new_doc, 'add_text_to_inventory_am1')
            ->textarea(['autofocus' => true,
                'style' => 'height: 300px;font-size: 12px;',
                'placeholder' => "Один столбец:\n\nШтрихкод\nШтрихкод\nШтрихкод\nШтрихкод\n"])
            ->label(false);
        ?>

        <div class="form-group">
            <?= Html::submitButton('Добавить ( залить ) копипаст ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'add_button_wh'
                ]) ?>

        </div>
        <?php Modal::end();
        ///////////////////////////////////?>



    </div>


    <?php ActiveForm::end(); ?>


    <div>
        <pre>
            <?

            if (isset($alert_mess)) {
                echo 'Сообщение: ' . $alert_mess;
                echo '<br>';
                echo '<br>';
            }

            if (isset($sum_bus) && !empty($sum_bus)) {
                echo 'Список номеров: <br>';
                echo '<br>';
                echo 'Посчитано автобусов: ' . $sum_bus;
                echo '<br>';
                echo '<br>';
            }

            if (isset($sum_bus_save) && !empty($sum_bus_save)) {
                echo 'Список номеров: <br>';
                echo '<br>';
                echo 'Сохранил автобусов: ' . $sum_bus_save;
                echo '<br>';
                echo '<br>';
            }


            if (isset($err) && !empty($err)) {
                echo 'Список номеров: <br>';
                echo '<br>';
                echo implode('<br>', $err);
                echo '<br>';
            }

            if (isset($err_not_exist_PE) && !empty($err_not_exist_PE)) {
                echo 'Список номеров PE: <br>';
                echo '<br>';
                echo implode('<br>', $err_not_exist_PE);
                echo '<br>';
            }
            //$err_exist_pillow

            if (isset($err_exist_pillow) && !empty($err_exist_pillow)) {
                echo 'Уже залиты: <br>';
                echo '<br>';
                if (is_array($err_exist_pillow)) {
                    foreach ($err_exist_pillow as $key => $item) {
                        if (is_array($item)) {
                            //echo 'key='.$key. '  ';
                            echo '' . $key . '<br> ';
                            print_r($item);
                            echo '<br>';
                        } else {
                            echo '' . $key . '=';
                            echo $item;
                            echo '<br>';

                        }
                    }
                }
                //ddd($err_exist_pillow);
            }

            ?>
        </pre>

    </div>


    <?php

    $script = <<<JS
$(document).ready(function() {
    $('.alert_save').fadeOut(2500); // плавно скрываем окно временных сообщений
});

$(document).on('keypress',function(e) {
    if(e.which == 13) {
        // alert('Нажал - enter!');
      e.preventDefault();
      return false;
    }
});


//////////////////// Debitor -top
$('#sklad_wh_invent-wh_destination').change(function() {       
    var  number = $(this).val();

    $.ajax( {
		url: '/sklad_inventory_wh/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		            $('#sklad_wh_invent-wh_destination_element').html('');
		            $('#sklad_wh_invent-wh_destination_element').html(res);		    
					},
			error: function( res) {
						alert('JS.sklad_inventory-wh_destination '+res );
						console.log(res);
					}
    } );
    
    
    
});

//////////////////// Debitor -top
$('#sklad_wh_invent-dt_create').change(function() {       
    
		            $('#sklad_wh_invent-wh_destination').val('');
		            $('#sklad_wh_invent-wh_destination_element').html('');
	
});


////////////
$('#go_home').click(function() {    
    window.history.back();  
})

JS;

    $this->registerJs($script, yii\web\View::POS_READY);
    ?>

