<?php

use frontend\models\post_spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use kartik\datetime\DateTimePicker;
use unclead\multipleinput\MultipleInput;
//use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<style>
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

    .glyphicon-plus {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;

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
        width: 100%;
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
            padding: 10px 0px;
            float: left;
            width: 100%;
            background-color: #48616121;
            /*margin: 5px;*/
            background-color: #48616121;
        }

    }
</style>

<style>
    /*.pv_motion_create_ok_button {*/
    /*    display: block;*/
    /*    float: left;*/
    /*    width: 100%;*/
    /*    overflow: auto;*/
    /*}*/

    .pv_motion_create_right_center {
        width: 100%;
        min-width: 240px;
        margin: 0px;
        /*padding: 3px 3px;*/
        padding: 0px;
    }

    /*@media (min-width: 690px) {*/
    /*    .pv_motion_create_right_center {*/
    /*        padding: 3px 3px;*/
    /*    }*/

    /*    .tree_land {*/
    /*        width: calc((98% - 210px) / 4);*/
    /*    }*/

    /*}*/

    @media (min-width: 1214px) {
        .pv_motion_create_right_center {
            padding: 3px 10px;
        }
    }


    @media (max-width: 2100px) {
        .tree_land, .tree_land3 {
            /*display: none;*/
            /*width: calc((98%) / 4);*/
            width: calc((98%) / 5);
            /*width: 100px;*/
            height: 133px;
            text-align: justify;
        }
    }

    @media (max-width: 1100px) {

        .tree_land3 {
            width: calc((98%) / 3);
            /*height: 133px;*/
            /*text-align: justify;*/
        }

        .tree_land {
            /*display: none;*/
            /*width: calc((98%) / 4);*/
            width: calc((98%) / 3);
            /*width: 100px;*/
            height: 133px;
            text-align: justify;
        }

    }

    @media (max-width: 680px) {
        .tree_land, .tree_land3 {
            width: 99%;
            padding: 0px;
            /*height: 133px;*/
            /*text-align: justify;*/
        }
    }

</style>


<?php $form = ActiveForm::begin([
    'id' => 'form1',
    'enableAjaxValidation' => false, //!!!
    'enableClientValidation' => true,
    'validateOnChange' => true,
    'validateOnSubmit' => true,


    'action' => '/sklad_cs/save_twin',
    'method' => 'GET',

]);
?>


<div class="pv_motion_create_right_center">


    <div class="tree_land3">
        <?php

        echo "<br>  " . $sklad . "<br><br><h2> № " . $model_new->id . "</h2>";
        ?>
    </div>

    <div class="tree_land">

        <?php


        $model_new->dt_create = date('d.m.Y H:i:s', strtotime('now'));

        echo $form->field($model_new, 'dt_create')->widget(DateTimePicker::className(), [
            'name' => 'dp_1',
            'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
            'size' => 'lg',
            'convertFormat' => false,
            'options' => [
                'placeholder' => 'Ввод даты/времени...',
            ],

            'pluginOptions' => [
                'format' => 'dd.mm.yyyy HH:ii:ss', /// не трогать -это влияет на выбор "СЕГОДНЯ"
                'pickerPosition' => 'bottom-left',

                'autoclose' => true,
                'weekStart' => 1, //неделя начинается с понедельника
                // 'startDate' => $date_now,
                'todayBtn' => true, //снизу кнопка "сегодня"
            ],
        ])
            ->label("Дата проведения работ");;

        ?>


        <?php
        echo $form->field($model_new, 'sklad_vid_oper')
            ->dropDownList(
                [
                    '1' => 'Инвентаризация',
                    '2' => 'Приходная накладная',
                    '3' => 'Расходная накладная',
                ],
                [
                    'prompt' => 'Выбор ...',
                    'data-confirm' => Yii::t('yii',
                        '<b>Вы точно хотите: </b><br>
                                    Изменить НАПРАВЛЕНИЕ этой НАКЛАДНОЙ ?'),
                ]
            )
            ->label("Вид операции");

        echo "<div class='close_hid'>";
        echo $form->field($model_new, 'sklad_vid_oper_name')
            ->hiddenInput()->label(false);
        echo "</div>";
        ?>

    </div>

    <div class="tree_land">
        <?php
        //////////
        echo $form->field($model_new, 'wh_debet_top')->dropDownList(
            ArrayHelper::map(
                Sprwhtop::find()->all(),
                'id', 'name'),
            [
                'prompt' => 'Выбор ...',
                //'id' => 'wh_debet_top',

            ]
        )->label("Компания-отправитель");

        ///////////////////

        echo $form->field($model_new, 'wh_debet_element')->dropDownList(
            ArrayHelper::map(
                Sprwhelement::find()
                    ->where(['parent_id' => (integer)$model_new->wh_debet_top])
                    ->all(), 'id', 'name'),
            [
                'prompt' => 'Выбор  ...',
                //'id' => 'wh_debet_element',
            ]
        )
            ->label("Склад-отправитель");


        echo "<div class='close_hid'>";
        echo $form->field($model_new, 'wh_debet_name')
            ->hiddenInput()->label(false);
        echo $form->field($model_new, 'wh_debet_element_name')
            ->hiddenInput()->label(false);
        echo "</div>";
        ?>
    </div>

    <div class="tree_land">
        <?php

        //////////
        echo $form->field($model_new, 'wh_destination')->dropDownList(
            ArrayHelper::map(
                Sprwhtop::find()->all(),
                'id', 'name'),
            [
                'prompt' => 'Выбор склада ...'
            ]
        )->label("Компания-получатель");

        echo $form->field($model_new, 'wh_destination_element')->dropDownList(
            ArrayHelper::map(Sprwhelement::find()
                ->where(['parent_id' => (integer)$model_new->wh_destination])
                ->all(), 'id', 'name'),
            [
                'prompt' => 'Выбор склада ...',
                //'options' => [ $model_new->wh_destination => ['selected'=>'selected']],
            ]
        )
            ->label("Склад-получатель");


        echo "<div class='close_hid'>";

        echo $form->field($model_new, 'wh_destination_name')
            ->hiddenInput()->label(false);

        echo $form->field($model_new, 'wh_destination_element_name')
            ->hiddenInput()->label(false);

        echo $form->field($model_new, 'sklad')
            ->hiddenInput()->label(false);

        echo "</div>";

        ?>

    </div>

    <div class="tree_land" style="background-color: #ffa50052;">
        <?php
        echo $form->field($model_new, 'tx')->textarea(
            [
                'style' => "margin: 0px; height: 93px; font-size: 15px;",
            ]);
        ?>
    </div>


    <div class="pv_motion_create_ok_button">

        <?php
        //        ////////// ALERT
        //
        //        if (isset($alert_str) && !empty($alert_str)) {
        //            echo Alert::widget([
        //                'options' => [
        //                    'class' => 'alert_save',
        //                    'animation' => "slide-from-top",
        //                ],
        //                'body' => $alert_str
        //            ]);
        //        }

        ////////// ALERT END
        ?>


        <?php
        ////////////// Выход
        echo Html::a(
            'Выход', ['sklad/in/'], [
            'onclick'=>"window.history.back();",
            'class' => 'btn btn-warning',
        ]);
        ?>


        <?php
        echo " ";
        echo Html::submitButton('Создать НАКЛАДНУЮ Демонтаж',
            [
                'class' => 'btn btn-success',
                'data-confirm' => Yii::t('yii',
                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'),

                'name' => 'contact-button',
                'value' => 'create_new1'
            ]
        );
        ?>
        <?php
        echo " ";
        echo Html::submitButton('Создать две. Демонтаж и Монтаж',
            [
                'class' => 'btn btn-success',
                'data-confirm' => Yii::t('yii',
                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'),

                'name' => 'contact-button',
                'value' => 'create_new2'
            ]
        );
        ?>
    </div>
</div>


<div class="pv_motion_create_right">
    <?php
    $xx = 0;
    echo $form->field($model_new, 'array_tk_amort')->widget(MultipleInput::className(), [

        'id' => 'my_id2',
        'theme' => 'default',
        'allowEmptyList' => false,
        'min' => 0, // should be at least 2 rows
        'enableGuessTitle' => false,
        'sortable' => true,
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

        'rowOptions' => ['id' => 'row{multiple_index_my_id2}',],

        'columns' => [
            [
                'name' => 'calc',
                'title' => '№',
                'value' => function ($data, $key) {
                    return ++$key['index'];
                },

                'options' => [
                    'prompt' => 'Выбор ...',
                    'style' => 'width: 30px;text-align: right;padding-right: 5px;',
                    'readonly' => 'readonly',
                    'disabled' => 'disabled',
                ],
            ],


            [
                'name' => 'wh_tk_amort',
                'type' => 'dropDownList',
                'title' => 'Группа',
                'defaultValue' => 0,
                'items' => ArrayHelper::map(
                    post_spr_globam::find()->orderBy('name')->all(),
                    'id', 'name'),

                'options' => [
                    'prompt' => 'Выбор ...',
                    'style' => 'padding: 0px;overflow: auto;',
                    'id' => 'subcat211-{multiple_index_my_id2}',

                    'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
    $("select#subcat22-{multiple_index_my_id2}").html(data);
});
JS
                ],
            ],

            [
                'name' => 'wh_tk_element',
                'type' => 'dropDownList',
                'title' => 'Компонент',
                'defaultValue' => [],

                'options' => [
                    'id' => 'subcat22-{multiple_index_my_id2}',
                    'prompt' => 'Выбор ...',
                    'style' => 'padding: 0px;min-width:1vw;overflow: auto;',
                    'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});
JS
                ],

                'items' =>
                    ArrayHelper::map(
                        Spr_globam_element::find()->orderBy('name')->all(),
                        'id', 'name')
                ,

            ],

            [
                'name' => 'intelligent',
                'title' => 'BC',
                'type' => 'dropDownList',

                'options' => [
                    'id' => 'subcat22-{multiple_index_my_id2}',
                    'style' => 'padding: 0px;width:50px;',
                ],
                'items' =>
                    [
                        0 => "Нет",
                        1 => "Да"
                    ]
                ,
            ],

            [
                'name' => 'ed_izmer',
                'title' => 'Ед. изм',
                'type' => 'dropDownList',
                'defaultValue' => 1,
                'options' => [
                    'id' => 'subcat-ed_izm-{multiple_index_my_id2}',
                    'prompt' => 'Выбор ...',
                    'style' => 'min-width:1vw;width: 60px;overflow: auto;'
                ],

                'items' =>
                    ArrayHelper::map(Spr_things::find()->all(), 'id', 'name')
                ,

            ],

            [
                'name' => 'ed_izmer_num',
                'title' => 'Кол-во',
                'defaultValue' => 1,
                'enableError' => true,
                'value' => 'ed_izmer_num',
                'options' => [
                    'type' => 'number',
                    'class' => 'input-priority',
                    'style' => 'min-width:5vw;width: 60px;overflow: auto;'
                ]
            ],

            [
                'name' => 'bar_code',
                'title' => 'Штрих-код',

                'options' => [
                    'id' => 'code5-{multiple_index_my_id2}',
                    'style' => 'min-width:5vw;width: 130px;overflow: auto;',

                    'type' => 'input',
                    //'pattern' => '(1[0-2]|0[1-9])\/(1[5-9]|2\d)',
                    //'pattern' => '[\d]{12}',
                    'pattern' => '[0-9]{5,25}',
                    //'placeholder' => "Штрих-код отсутствует" ,
                    'placeholder' => "",


                    'onkeyup' => <<< JS
//// Копия строки F9
if (event.keyCode==120){
        var data = $(this);
        var index = '{multiple_index_my_id2}';

        var pole3=$('#subcat211-'+index).val();
        var pole4=$('#subcat22-'+index).val();
        var pole5=$('#subcat-ed_izm-'+index).val();

     // Add TO MultipleInput
    $('#my_id2').multipleInput('add',
    {
        1 :1,
        2 :pole3,
        3 :pole4,
        4 :pole5,
        5 :1,
    });
}

if (event.keyCode==13){

        var data = $(this).val();
        data = data.replace(/[^\d*]/g,'');
        $(this).val(data);

            //console.log( data.replace(/[a-z]*/g,'') ); // Удаляет любые буквы, оставляет цифры
            //console.log( data.replace(/[^\d*]/g,'') ); // Удаляет ВСЕ, кроме цифр

        //// переход на строку ниже
            var str='{multiple_index_my_id2}';
                //// Обрезка последнего знака для CVB24
                if ( data.substr(0,6) == 196000 ){ //19600004992
                       //console.log( data.substr( 0,6 ) ) ;
                       //console.log( data.substr( 0,11 ) ) ;
                    $('#code5-'+str).val( data.substr( 0,11 ));
                }

            var nextrow = ((str*1)+1);
            $('#code5-'+nextrow).focus();
}


JS
                ],
            ],

        ]
    ])->label('Демонтаж АСУОП', ['class' => 'my_label']);;
    ?>


</div>


<?php ActiveForm::end(); ?>


<?php
if (!empty($alert_str)) {
    echo '<div class="alert_str" style="display:block;background-color:#ff71003d;font-size:26px;padding: 41px 10%;width:76%;margin: 0px 10%;">' .
        $alert_str
        . '</div>';
}
?>

<?php
$script = <<<JS
//////////////////// CLOSE --ALERT--
$(document).ready(function() {
    $('.alert_save').fadeOut(2500); // плавно скрываем окно временных сообщений
});


//////////////////// ENTER
$(document).on('keypress',function(e) {
    if(e.which == 13) {
        // alert('Нажал - enter!');
      e.preventDefault();
      return false;
    }
});



//////////////////// Debitor -top
$('#sklad-wh_debet_top').change(function() {

    var  number = $(this).val();
    var  text = $('#sklad-wh_debet_top>option[value='+number+']').text();
    $('#sklad-wh_debet_name').val(text) ;


    // alert($('#sklad-wh_debet_name').val());

     // var  number2 = $('#sklad-wh_debet_element').val();
     // var  text2   = $('#sklad-wh_debet_element>option[value='+number2+']').text();
     //  $('#sklad-wh_debet_element_name').val(text2);


    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",
			success: function(res) {
		            $('#sklad-debet_element').html('');
		            $('#sklad-wh_debet_element').html(res);
					},
			error: function( res) {
						alert('JS.sklad-wh_destination '+res );
						console.log(res);
					}
    } );



});


//////////////////// Creditor
$('#sklad-wh_destination').change(function() {

    var  number = $(this).val();
    var  text = $('#sklad-wh_destination>option[value='+number+']').text();
 	    $('#sklad-wh_destination_name').val(text);

            // console.log(number);
            // console.log(text);

    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",
			success: function(res) {
		    // $('#sklad-wh_destination_element').html('');
		    $('#sklad-wh_destination_element').html(res);

						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('JS.sklad-wh_destination '+ res );
					}
    } );

});

//////////////////// destination_element - element
/// sklad-wh_destination_element
$('#sklad-wh_destination_element').change(function() {
     var  number2 = $('#sklad-wh_destination_element').val();
     var  text2   = $('#sklad-wh_destination_element>option[value='+number2+']').text();
      $('#sklad-wh_destination_element_name').val(text2);
});






////////////
$('#go_home').click(function() {
    window.history.back();
})

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
