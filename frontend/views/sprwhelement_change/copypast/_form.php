<?php

use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
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
    // 'action' => ['index'], ///ВНИМАНИЕ! Эта форма используется в СОЗДАНИИ и еще в РЕДАКТИРОВАНИИ
    'method' => 'POST',
    'options' => [
        // 'data-pjax' => 1,
        'autocomplete' => 'off'
    ],
    'enableClientValidation' => true,
    'validateOnChange' => true,
    'validateOnSubmit' => true,

]);
?>


<div id="tz1">
    <div class="pv_motion_create_right_center">
                <div class="tree_land3">
                    <?php
                    echo "<br><h2> Заливаем справочные данные из EXCEL </h2>";
                    ?>
                </div>



<!--        <div class="tree_land">-->
<!--            --><?php
//            echo $form->field($model, 'wh_top')->dropDownList(
//                $list_whtop, ['prompt' => 'Выбор ...',]);
//
//            ///
//            echo $form->field($model, 'wh_element')->widget(
//                Select2::className(), [
//                'name' => 'wh_debet_element',
//                'data' => $list_whtop_element,
//                'size' => Select2::SMALL,
//            ]);
//
//            ?>
<!--        </div>-->



    </div>


    <div class="pv_motion_create_ok_button">


        <?php
        echo Html::a('Выход', ['/sprwhelement_change/return_to_refer'], ['class' => 'btn btn-warning']);
        ?>

<!--        --><?php
//        echo Html::submitButton(
//            'Сохранить изменения ',
//            [
//                'name' => 'contact-button',
//                'value' => 'save_button',
//
//                'class' => 'btn btn-success',
//                'data-confirm' => Yii::t(
//                    'yii',
//                    'СОХРАНЯЕМ ?'),
//            ]
//        );
//        ?>






        <?php
        ///////////////////////
        Modal::begin([
            'header' => '<h2>Заливаем ЗАМЕНЫ НОМЕРОВ (ГОС/БОРТ)  </h2>',
            'toggleButton' => [
                'label' => 'Копипаст ЗАМЕНЫ НОМЕРОВ',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],//'footer' => 'Низ окна',
        ]);
        ?>
        <?= $form
            ->field($model, 'add_text')
            ->textarea(['autofocus' => true,
                'style' => 'height: 300px;font-size: 12px;',
                'placeholder' => "Четыре столбца:                
                \n1. Дата операции\n2. АП\n3. Госномер новый\n4. Госномер старый\n5. Комент Док\n6. Комент ПредНомер"])
            ->label(false);
        ?>
        <div class="form-group">
            <?= Html::submitButton('Добавить ( залить ) копипаст ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'add_from_excel'
                ]) ?>

        </div>
        <?php Modal::end();
        ///////////////////////////////////?>





    </div>


    <?php ActiveForm::end(); ?>

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


////////////
$('#go_home').click(function() {    
    window.history.back();  
})



//////////////////// WH-top
$('#sprwhelement_change-wh_top').change(function() {        
    var  number = $(this).val();
    var  text = $('#sprwhelement_change-wh_top>option[value='+number+']').text();
        
    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		            $('#sprwhelement_change-wh_element').html('');
		            $('#sprwhelement_change-wh_element').html(res);		    
					},
			error: function( res) {
						alert('JS.sklad-wh_destination '+res );
						console.log(res);
					}
    } );
        
});


//////////////////// WH-element
$('#sprwhelement_change-wh_element').change(function() {        
    var  number = $(this).val();
    var  text = $('#sprwhelement_change-wh_top>option[value='+number+']').text();
        
    $.ajax( {
		url: '/sprwhelement_change/gos_old',
		data: {
		    id :number
		},		
			success: function(res) {
		            $('#sprwhelement_change-old_gos').val(res);		    
					},
			error: function( res) {
		            $('#sprwhelement_change-old_gos').val('');
						alert('JS.sklad-wh_destination '+res );						
					}
    } );
    
    $.ajax( {
		url: '/sprwhelement_change/bort_old',
		data: {
		    id :number
		},		
			success: function(res) {
		            $('#sprwhelement_change-old_bort').val(res);		    
					},
			error: function( res) {
		            $('#sprwhelement_change-old_bort').val('');
						alert('JS.sklad-wh_destination '+res );
						
					}
    } );
        
});


JS;

    $this->registerJs($script, yii\web\View::POS_READY);
    ?>

