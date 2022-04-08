<?php /** @noinspection ThisExpressionReferencesGlobalObjectJS */

/** @noinspection PhpUnusedParameterInspection */

use frontend\models\Spr_things;
use kartik\datetime\DateTimePicker;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Alert;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


//use yii\widgets\Pjax;

//Pjax::begin(['id' => "pjax-container",]);
//        echo \yii::$app->request->get('page');
//Pjax::end();
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


</style>


<style>
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
            width: 100%;
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

    //    'action' => ['index'],
    'method' => 'post',
    'options' => [
//        'data-pjax' => 1,
        'autocomplete' => 'off'
    ],

    'enableAjaxValidation' => false,
    'enableClientValidation' => true,//false,
    'validateOnChange' => true,//false,
    'validateOnSubmit' => true,
    //    'validateOnBlur'            => true,//false,false,

]);
?>


<div id="tz1">
    <div class="pv_motion_create_right_center">

        <div class="tree_land3">

            <?php
            echo "<br><h2> Итоги № " . $new_doc->id . "</h2>";
            ?>

        </div>


        <div class="tree_land">

            <?php

            // Проверка Воспроизведения ИЗ миллисекунд в нормальный формат дат
            // $new_doc->dt_create = $new_doc->getDtCreateText();


            $new_doc->dt_create =
                date('d.m.Y H:i:s', strtotime($new_doc['dt_create']));

            echo $form->field($new_doc, 'dt_create')
                ->widget(DateTimePicker::className(), [
                    'name' => 'dp_2',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'convertFormat' => false,
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...'],

                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy HH:ii:ss', /// не трогать -это влияет на выбор "СЕГОДНЯ"
                        'pickerPosition' => 'bottom-left',

                        'autoclose' => true,
                        'weekStart' => 1, //неделя начинается с понедельника
                        // 'startDate' => $date_now,
                        'todayBtn' => true, //снизу кнопка "сегодня"
                    ]
                ]);
            ?>


            <?php
            $new_doc->dt_start =
                date('d.m.Y H:i:s', strtotime($new_doc['dt_start']));

            echo $form->field($new_doc, 'dt_start')
                ->widget(DateTimePicker::className(), [
                    'name' => 'dp_2',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'convertFormat' => false,
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...'],

                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy HH:ii:ss', /// не трогать -это влияет на выбор "СЕГОДНЯ"
                        'pickerPosition' => 'bottom-left',

                        'autoclose' => true,
                        'weekStart' => 1, //неделя начинается с понедельника
                        // 'startDate' => $date_now,
                        'todayBtn' => true, //снизу кнопка "сегодня"
                    ]
                ]);
            ?>


        </div>


        <div class="tree_land">
            <?php

            //////////
            echo $form->field($new_doc, 'wh_destination_name');

            ///////////////////

            echo $form->field($new_doc, 'wh_destination_element_name');


            //            echo "<div class='close_hid'>";
            ////            echo $form->field($new_doc, 'wh_destination_name')
            ////                ->hiddenInput()->label(false);
            ////            echo $form->field($new_doc, 'wh_destination_element_name')
            ////                ->hiddenInput()->label(false);
            //            echo "</div>";

            ?>


        </div>


        <div class="tree_land">
            <?php
            echo $form->field($new_doc, 'group_inventory')
                ->textInput(
                    [

                        'placeholder' => 'Посказка:' . $new_doc->id,
                        'prompt' => 'Выбор ...',
                    ]
                );
            ?>

            <?php
            echo $form->field($new_doc, 'group_inventory_name')
                ->textInput(
                    [
                        'placeholder' => 'Название группы ',

                    ]
                )//->hint('Пожалуйста, введите')
            ;
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
        echo Html::a('Выход из просмотра', ['/past_inventory'], ['class' => 'btn btn-warning']);
        ?>


        <!--        --><?php
        //        echo Html::submitButton('Сохранить изменения в накладной',
        //            [
        //                'class' => 'btn btn-success',
        //                'name' => 'contact-button',
        //                'value' => '',
        //                'data-confirm' => Yii::t('yii',
        //                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'),
        //            ]
        //        );
        //        ?>


        <?php
        echo Html::a('Ведомость',
            ['/past_inventory/pdf_form/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>



        <?php
        echo Html::a('Ведомость Excel',
            ['/past_inventory/excel_form/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>


        <?php
        echo Html::a('Инвентар.опись в Excel',
            ['/past_inventory/excel_form_inventory/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>


    </div>


    <div class="pv_motion_create_right">
                <?php
        //         ddd($new_doc);

                $xx=0;
                /** @noinspection ThisExpressionReferencesGlobalObjectJS */
                echo $form->field($new_doc, 'array_tk_amort')->widget(MultipleInput::className(), [

                    'id' => 'my_id2',
                    'theme'  => 'default',
        //            'allowEmptyList' => false,
                    'min'               => 0, // should be at least 2 rows
        //            'enableGuessTitle'  => false,
        //            'sortable'  => true,
        //            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header


        //            'rowOptions' =>function ($model, $index, $context){
        //                $text_array = ['id' => 'row{multiple_index_my_id2}'];
        //                //ddd($model->take_it=='1');
        //
        //                if(isset($model['take_it']) && $model['take_it']=='1') {
        //                    $text_array = ['id' => 'row{multiple_index_my_id2}',
        //                        'class' => 'redstyle'
        //                    ];
        //
        //                }
        //
        //                return $text_array;
        //            },


                    'columns' => [
                        [
                            'name' => 'calc',
                            'title' => '№',
                            'value' => function($data, $key) {
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
                            'name'  => 'name_wh_tk_amort',
                            'title' => 'Группа',
                            'defaultValue' => 'wh_tk_amort',

                            'options' => [
                                'prompt' => 'Выбор ...',
                                'style' => 'width:90px',
                                'id' => 'subcat211-{multiple_index_my_id2}',

                                'onchange' => <<< JS
        $.post("listamort?id=" + $(this).val(), function(data){
            $("select#subcat22-{multiple_index_my_id2}").html(data);
        });
JS
                            ],
                        ],


                        [
                            'name' => 'cc_id',
                            'title' => '1C',
                            'options' => [
                                'prompt' => 'Выбор ...',
                                'style' => 'min-width: 40px;',
                                'id' => 'subcat11-{multiple_index_my_id}',
                            ],
                        ],


                        [
                            'name'  => 'name_wh_tk_element',
                            'title' => 'Компонент',
//                            'defaultValue'=> [],

                            'options' => [
                                'id' => 'subcat22-{multiple_index_my_id2}',
                                'prompt' => 'Выбор ...',
                                'style' => 'width:40vw;overflow: auto;',

                                'onchange' => <<< JS
        $.post("list_parent_id_amort?id=" + $(this).val(), function(data){
            $("select#subcat211-{multiple_index_my_id2}").val(data);
        });
JS
                            ],



                        ],
        //                [
        //                    'name'  => 'bar_code',
        //                    'title' => 'Штрихкод',
        //                    'defaultValue' => 0,
        //                    'enableError' => true,
        //                    'value' => 'bar_code',
        //                    'options' => [
        //                        'class' => 'input-priority',
        //                        'style' => 'width: 95px;overflow: auto;text-align: right;'
        //                    ]
        //                ],

                        [
                            'name'  => 'intelligent',
                            'title' => 'BC',
                            'options' => [
                                'id' => 'subcat22-{multiple_index_my_id2}',
                                'style' => 'padding: 0px;min-width:4vw;max-width:4vw;overflow: auto;',
                            ],
                            'value' => function($data) {
                                if($data['intelligent']==1){
                                    return "Да";
                                }

                                return "Нет";
                            }
                        ],



                        [
                            'name'  => 'ed_izmer',
                            'title' => 'Ед. изм',
                            'type'  => 'dropDownList',
                            'defaultValue' => 1,
                            'options' => [
                                'id' => 'subcat-ed_izm-{multiple_index_my_id2}',
                                'prompt' => 'Выбор ...',
                                'style' => 'width:50px;padding:0px;'
                            ],
                            'items' => [1=>"шт"],
                        ],

                        [
                            'name'  => 'ed_izmer_num',
                            'title' => 'Инв.',
                            'defaultValue' => 1,
                            'enableError' => true,
                            'value' => 'ed_izmer_num',
                            'options' => [
                                'class' => 'input-priority',
                                'style' => 'width:55px;overflow: auto;text-align: right;'
                            ]
                        ],

                        [
                            'name'  => 'prihod_num',
                            'title' => 'Приход',
                            'defaultValue' => 0,
                            'enableError' => true,
                            'value' => 'ed_izmer_num',
                            'options' => [
                                'class' => 'input-priority',
                                'style' => 'width:55px;overflow: auto;text-align: right;'
                            ]
                        ],

                        [
                            'name'  => 'rashod_num',
                            'title' => 'Расход',
                            'defaultValue' => 0,
                            'enableError' => true,
                            'value' => 'ed_izmer_num',
                            'options' => [
                                'class' => 'input-priority',
                                'style' => 'width:55px;overflow: auto;text-align: right;'
                            ]
                        ],

                        [
                            'name'  => 'itog',
                            'title' => 'Итог',
                            'defaultValue' => 0,
                            'enableError' => true,
                            'value' => 'ed_izmer_num',
                            'options' => [
                                'class' => 'input-priority',
                                'style' => 'width:55px;overflow: auto;text-align: right;'
                            ]
                        ],





                    ]
                ]) ->label('Амортизация',['class' => 'my_label'] );

                ?>

    </div>


    <div class="pv_motion_create_right">
        <?php

        //////////////////////
        echo $form->field($new_doc, 'array_tk')->widget(MultipleInput::className(), [
            'id' => 'my_id',
            'theme' => 'default',
            'rowOptions' => function ($model, $index, $context) {
                $text_array = ['id' => 'row{multiple_index_my_id}'];
                //ddd($model->take_it=='1');

                if (isset($model['take_it']) && $model['take_it'] == '1') {
                    $text_array = ['id' => 'row{multiple_index_my_id}',
                        'class' => 'redstyle'
                    ];

                }

                return $text_array;
            },

            'min' => 0, // should be at least 2 rows
//            'sortable'  => true,

            'columns' => [
                ['name' => 'calc',
                    'title' => '№',
                    'value' => function ($data, $key) {
                        return ++$key['index'];
                    },
                    'defaultValue' => function ($data, $key) {
                        return ++$key['index'];
                    },

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'width: 30px;text-align: right;padding-right: 5px;',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ],
                ],

//                [
//                    'name'  => 'wh_tk',
//                    'options' => [
//                        'style' => 'width: 30px;text-align: right;padding-right: 5px;',
//                        ],
//                    ],
//                [
//                    'name'  => 'wh_tk_element',
//                    'options' => [
//                        'style' => 'width: 30px;text-align: right;padding-right: 5px;',
//                        ],
//                    ],


                [
                    'name' => 'name_tk',
                    'title' => 'Группа',


                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'min-width: 40px;',
                        'id' => 'subcat11-{multiple_index_my_id}',
                        'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    //console.log(data);
    $("select#subcat-{multiple_index_my_id}").html(data);
});
JS

                    ],
                ],

                [
                    'name' => 'cc_id',
                    'title' => '1C',
                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'min-width: 40px;',
                        'id' => 'subcat11-{multiple_index_my_id}',
                    ],
                ],

                [
                    'name' => 'name_tk_element',
                    'title' => 'Компонент',

                    'options' => [
                        'prompt' => '...',
                        'style' => 'width:40vw;overflow: auto;',
                        'id' => 'subcat-{multiple_index_my_id}',

                        'onchange' => <<< JS

$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_id}").val(data);
});

$.post("list_ed_izm?id=" + $(this).val(), function(data){
//alert(data);
    $("select#sklad-array_tk-{multiple_index_my_id}-ed_izmer").val(data);
});
JS
                        ,
                    ]
                ],


                [
                    'name' => 'ed_izmer',
                    'title' => 'Ед. изм',
                    'type' => 'dropDownList',
                    'defaultValue' => 1,
                    'options' => [
                        'style' => 'padding: 0px;width:55px;overflow: auto;',
                        'id' => "sklad-array_tk-{multiple_index_my_id}-ed_izmer",
                        'onchange' => <<< JS
// Если выбрана любая Ед.Изм, кроме ШТУК
// (метры, литры, пог.метры...)

if ( $(this).val()>1 ){
    //console.log( $("input#zero-{multiple_index_my_id}").val() );
    $("input#zero-{multiple_index_my_id}").attr('step','0.1');
}else{
    $("input#zero-{multiple_index_my_id}").attr('step','1');
}
JS
                        ,
                    ],

                    'items' => ArrayHelper::map(Spr_things::find()->all(), 'id', 'name'),
                ],


                [
                    'name' => 'ed_izmer_num',
                    'title' => 'Инв.',
                    'defaultValue' => 0.1,
                    'enableError' => true,
                    'value' => 'ed_izmer_num',
                    'options' => [
                        'class' => 'input-priority',
                        'style' => 'min-width:5vw;width: 60px;overflow: auto;text-align: right;'
                    ]
                ],

                [
                    'name' => 'prihod_num',
                    'title' => 'Приход',
                    'defaultValue' => 0,
                    'enableError' => true,
                    'value' => 'ed_izmer_num',
                    'options' => [
                        'class' => 'input-priority',
                        'style' => 'min-width:5vw;width: 60px;overflow: auto;text-align: right;'
                    ]
                ],

                [
                    'name' => 'rashod_num',
                    'title' => 'Расход',
                    'defaultValue' => 0,
                    'enableError' => true,
                    'value' => 'ed_izmer_num',
                    'options' => [
                        'class' => 'input-priority',
                        'style' => 'min-width:5vw;width: 60px;overflow: auto;text-align: right;'
                    ]
                ],

                [
                    'name' => 'itog',
                    'title' => 'Итог',
                    'defaultValue' => 0,
                    'enableError' => true,
                    'value' => 'ed_izmer_num',
                    'options' => [
                        'class' => 'input-priority',
                        'style' => 'min-width:5vw;width: 60px;overflow: auto;text-align: right;'
                    ]
                ],


            ]
        ])->label('Списание', ['class' => 'my_label']);

        ?>

    </div>
</div>


<?php ActiveForm::end(); ?>



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






//////////////////// Creditor
$('#sklad_past_inventory-wh_destination').change(function() {
    
    var  number = $(this).val();
    var  text = $('#sklad_past_inventory-wh_destination>option[value='+number+']').text();
 	    $('#sklad_past_inventory-wh_destination_name').val(text);
 	    
            // console.log(number);   
            // console.log(text);    
   
    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    // $('#sklad_past_inventory-wh_destination_element').html('');
		    $('#sklad_past_inventory-wh_destination_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('JS.sklad_past_inventory-wh_destination '+ res );
					}
    } );
   
});

//////////////////// destination_element - element
/// sklad_past_inventory-wh_destination_element 
$('#sklad_past_inventory-wh_destination_element').change(function() {    
     var  number2 = $('#sklad_past_inventory-wh_destination_element').val();     
     var  text2   = $('#sklad_past_inventory-wh_destination_element>option[value='+number2+']').text();
      $('#sklad_past_inventory-wh_destination_element_name').val(text2);
});






////////////
$('#go_home').click(function() {    
    window.history.back();  
})



JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

