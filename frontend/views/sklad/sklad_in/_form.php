<?php

use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

//	use yii\widgets\Pjax;

$this->title = '№ ' . $model->id;

//	Pjax::begin( [ 'id' => 'pjax-container', ] );
//	echo yii::$app->request->get( 'page' );
//	Pjax::end();


?>
<style>
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
</style>


<div id="tz1">
    <?php $form = ActiveForm::begin(); ?>


    <div class="pv_motion_create_right_center">

        <div class="tree_land3">


            <?php
            echo "<br>склад " . $sklad;
            ?>

            <?php
            echo "<br><h2> Накладная № " . $model->id . "</h2>";
            ?>


            <?php


            ///////////
            if (empty($model['array_bus'])) {
                $sum_bus = 0;
            } else {
                $sum_bus = count($model['array_bus']);
            }

            ///////////
            ///

            // Using a select2 widget inside a modal dialog


            if (isset($items_auto) && !empty($items_auto)) {
                Modal::begin(
                    [
                        //'header' => 'modal header',
                        'header' => false,
                        'options' => [
                            'id' => 'kartik-modal',
                            //'tabindex' => true // important for Select2 to work properly
                        ],
                        'toggleButton' => [
                            'label' => 'Автобусы (' . $sum_bus . ')',
                            'class' => 'btn btn-default'
                            // btn-primary'
                        ],
                    ]);

                echo $form->field($model, 'array_bus')->widget(
                    Select2::className()
                    , [
                    ///echo Select2::widget([
                    'name' => 'st',
                    //'data' => $items_auto,
                    'data' => $items_auto,
                    'options' => [

                        'id' => 'array_bus_select',
                        'placeholder' => 'Список автобусов ...',
                        'allowClear' => true,
                        'multiple' => true,

                    ],
                    'theme' => Select2::THEME_BOOTSTRAP,
                    // THEME_DEFAULT, //THEME_CLASSIC, //THEME_BOOTSTRAP,
                    'size' => Select2::SMALL,
                    //LARGE,
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [
                            ',',
                            ' ',
                        ],
                        'maximumInputLength' => 10,
                    ],
                ]);

                ?>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Прикрыть список</button>
                </div>

                <?
                echo Alert::widget(
                    [
                        'options' => [
                            'class' => 'alert-info',
                        ],
                        'body' => 'Кнопка "Прикрыть список" не удаляет и не отменяет изменения списка ',
                    ]);


                Modal::end();
            }

            ?>

        </div>


        <div class="tree_land">
            <?php
            echo $form->field($model, 'dt_create')
                ->widget(
                    DateTimePicker::className(), [

                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,
                    //TYPE_INLINE,
                    'size' => 'lg',
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...',
                    ],
                    'disabled' => 'disabled',

                    //'value'=> $date_now,
                    'value' => $model->dt_create,
                    'pluginOptions' => [
                        'pickerPosition' => 'bottom-left',
                        'format' => 'yyyy-mm-dd HH:ii:ss',
                        'autoclose' => true,
                        'weekStart' => 1,
                        //неделя начинается с понедельника
                        // 'startDate' => $date_now,
                        'todayBtn' => true,
                        //снизу кнопка "сегодня"
                    ],
                ]);;

            ?>


            <?php
            echo $form->field($new_doc, 'sklad_vid_oper')
                ->dropdownList(
                    [
                        '1' => 'Инвентаризация',
                        '2' => 'Приходная накладная',
                        '3' => 'Расходная накладная',
                    ],
                    [
                        'prompt' => 'Выбор ...',
                        'disabled' => 'disabled',
                    ]
                )
                ->label("Вид операции");

            echo "<div class='close_hid'>";
            echo $form->field($new_doc, 'sklad_vid_oper_name')
                ->hiddenInput()->label(false);
            echo "</div>";
            ?>

        </div>


        <div class="tree_land">
            <?php
            //////////
            echo $form->field($new_doc, 'wh_debet_top')->dropdownList(
                ArrayHelper::map(
                    Sprwhtop::find()->all(),
                    'id', 'name'),
                [
                    'prompt' => 'Выбор ...',
                    'disabled' => 'disabled'
                    //'id' => 'wh_debet_top',


                ]
            )->label("Компания-отправитель");


            echo $form->field($new_doc, 'wh_debet_element')->dropdownList(
                ArrayHelper::map(
                    Sprwhelement::find()
                        ->where(['parent_id' => (integer)$new_doc->wh_debet_top])
                        ->all(), 'id', 'name'),
                [
                    'prompt' => 'Выбор  ...',
                    'disabled' => 'disabled'
                    //'id' => 'wh_debet_element',
                ]
            );

            echo "<div class='close_hid'>";
            echo $form->field($new_doc, 'wh_debet_name')
                ->hiddenInput()->label(false);
            echo $form->field($new_doc, 'wh_debet_element_name')
                ->hiddenInput()->label(false);
            echo "</div>";
            ?>
        </div>

        <div class="tree_land">
            <?php

            //////////
            echo $form->field($new_doc, 'wh_destination')->dropdownList(
                ArrayHelper::map(
                    Sprwhtop::find()->all(),
                    'id', 'name'),
                [
                    'prompt' => 'Выбор склада ...',
                    'disabled' => 'disabled',
                ]
            )->label("Компания-получатель");

            echo $form->field($new_doc, 'wh_destination_element')->dropdownList(
                ArrayHelper::map(
                    Sprwhelement::find()
                        ->where(['parent_id' => (integer)$new_doc->wh_destination])
                        ->all(), 'id', 'name'),
                [
                    'prompt' => 'Выбор склада ...',
                    'disabled' => 'disabled'
                    //'options' => [ $new_doc->wh_destination => ['selected'=>'selected']],
                ]
            );

            echo "<div class='close_hid'>";
            echo $form->field($new_doc, 'wh_destination_name')
                ->hiddenInput()->label(false);
            echo $form->field($new_doc, 'wh_destination_element_name')
                ->hiddenInput()->label(false);
            echo "</div>";

            ?>

        </div>
        <div class="tree_land" style="background-color: #ffa50052;">

            <?php
            //////////
            echo $form->field($new_doc, 'wh_dalee')->dropDownList(
                ArrayHelper::map(
                    Sprwhtop::find()->all(),
                    'id', 'name'),
                [
                    'prompt' => 'Выбор склада ...',
                    'disabled' => 'disabled',
                ]
            );
            //  ->label("Дальний-получатель ЦС");

            ///////////////////
            echo $form->field($new_doc, 'wh_dalee_element')->dropDownList(
                ArrayHelper::map(
                    Sprwhelement::find()
                        ->where(['parent_id' => (integer)$new_doc->wh_dalee])
                        ->all(), 'id', 'name'),
                [
                    'prompt' => 'Выбор склада ...',
                    'disabled' => 'disabled',
                ]
            );
            //  ->label("Дальний-получатель ЦС");

            ?>


        </div>
    </div>


    <div class="pv_motion_create_ok_button" style="display: none">


        <?= Html::Button(
            'Выход',
            [
                'class' => 'btn btn-warning',
                //'onclick'=>"window.history.back();",
                'onclick'=>"window.history.back();"
            ]);
        ?>
        <?= Html::submitButton(
            'Принять накладную',
            [
                'class' => 'btn btn-success',
            ]);
        ?>


        <!--        --><?php //
        //        echo Html::a('Принять накладную',
        //            ['/sklad/transfer_delivered/?id=' . $model->_id],
        //            [
        //                'class' => 'btn btn-success'
        //            ]);
        //        ?>

        <?php
        echo Html::a(
            'Отменить накладную ',
            ['/sklad/transfer_dont/?id=' . $model->_id],
            [
                'class' => 'btn btn-danger',
            ]);
        ?>


    </div>

</div>


<div class="scroll_window">


    <div class="pv_motion_create_right">

        <?php

        echo $form->field($model, 'array_tk_amort')->widget(
            MultipleInput::className(), [
            'id' => 'my_id2',
            'theme' => 'default',
            'rowOptions' => [
                'id' => 'row{multiple_index_my_id2}',
                'style' => 'max-height: 20px;',
            ],

            //            'max'               => 25,
            'min' => 0,
            // should be at least 2 rows
            'allowEmptyList' => true,
            //false,
            'enableGuessTitle' => true,
            //false,
            'sortable' => true,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            // show add button in the header

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


                    'items' => function ($data) {
                        return [$data['wh_tk_amort'] => $data['name_wh_tk_amort']];
                    },


                    'options' => [
                        'style' => 'max-width:18vw;overflow: auto;padding: 0px;',
                        'prompt' => 'Выбор ...',
                        'id' => 'subcat211-{multiple_index_my_id2}',

                        'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
    console.log(data);
    $("select#subcat22-{multiple_index_my_id2}").html(data);
});
JS
                        ,

                    ],
                ],

                [
                    'name' => 'wh_tk_element',
                    'type' => 'dropDownList',
                    'title' => 'Компонент',
                    'defaultValue' => [],

                    'options' => [
                        'id' => 'subcat22-{multiple_index_my_id2}',
                        'style' => 'min-width: 136px;',
                        'prompt' => '...',
                    ],


                    'items' => function ($data) {
                        return [$data['wh_tk_element'] => $data['name_wh_tk_element']];
                    },


                ],

                [
                    'name' => 'ed_izmer',
                    'title' => 'Ед. изм',
                    'type' => 'dropDownList',
                    'defaultValue' => 1,
                    'options' => [
                        'style' => 'min-width:1vw;max-width:4vw;overflow: auto;padding: 0px;',
                    ],

                    'items' => function ($data) {
                        return [$data['ed_izmer'] => $data['name_ed_izmer']];
                    },


                ],


                [
                    'name' => 'ed_izmer_num',
                    'title' => 'Кол',
                    'defaultValue' => 0,
                    'enableError' => true,
                    'value' => 'ed_izmer_num',
                    'options' => [
                        'type' => 'number',
                        'class' => 'input-priority',
                        'style' => 'min-width:55px;max-width:4vw;overflow: auto;',

                    ],
                ],

                [

                    'name' => 'bar_code',
                    'title' => 'Код',
                    'enableError' => true,
                    'options' => [
                        'style' => 'min-width:130px;max-width: 160px;overflow: auto;color:red;',
                        'id' => 'code5-{multiple_index_my_id2}',
                        'onchange' => <<< JS
//$('#sklad-sklad_vid_oper').css('color','red').val('');
//var skladval=$('#sklad-sklad_vid_oper').val();
//var str='{multiple_index_my_id2}';
//var nextrow = ((str*1)+1);
//$('#code5-'+nextrow).focus();
JS
                    ],
                ],


            ],
        ])->label('Амортизация', ['class' => 'my_label']);
        //->label(false);

        ?>




        <?php

        echo $form->field($model, 'array_tk')->widget(
            MultipleInput::className(), [

            'id' => 'my_id',
            'theme' => 'default',
            'rowOptions' => [
                'id' => 'row{multiple_index_my_id}',
            ],

            //            'max'               => 25,
            'min' => 0,
            // should be at least 2 rows
            'allowEmptyList' => true,
            //false,
            'enableGuessTitle' => true,
            //false,
            'sortable' => true,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            // show add button in the header

            'columns' => [
                [
                    'name' => 'wh_tk',
                    'type' => 'dropDownList',
                    'title' => 'Группа',
                    'defaultValue' => [],

                    'items' => function ($data) {
                        return [$data['wh_tk'] => $data['name_tk']];
                    },


                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'min-width: 20px;',
                        'id' => 'subcat11-{multiple_index_my_id}',
                        'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    //console.log(data);
    $("select#subcat-{multiple_index_my_id}").html(data);
});
JS
                        ,

                    ],
                ],

                [
                    'name' => 'wh_tk_element',
                    'type' => 'dropDownList',
                    'title' => 'Компонент',
                    'defaultValue' => [],

                    'items' => function ($data) {
                        return [$data['wh_tk_element'] => $data['name_tk_element']];
                    },


                    'options' => [
                        'prompt' => '...',
                        'id' => 'subcat-{multiple_index_my_id}',
                        'style' => 'min-width: 100px;',
                        'onchange' => <<< JS
                        
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_id}").val(data);
});                    


        
$.post("list_ed_izm?id=" + $(this).val(), function(data){  

   $("select#sklad-array_tk_amort-{multiple_index_my_id}-intelligent").val(data);
});                                

JS
                        ,

                    ],
                ],


                [
                    'name' => 'ed_izmer',
                    'title' => 'Ед. изм',
                    'type' => 'dropDownList',
                    'defaultValue' => 1,
                    'options' => [
                        'style' => 'padding: 0px;',
                        'id' => 'sklad-array_tk_amort-{multiple_index_my_id}-intelligent',

                    ],


                    'items' => function ($data) {
                        return [$data['ed_izmer'] => $data['name_ed_izmer']];
                    },


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
                        'step' => '0.1',
                        'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                    ],
                ],


            ],
        ])->label('Списание', ['class' => 'my_label']);
        //  ->label(false);
        ?>

        <div class="pv_motion_create_right">
            <?php

            /////////////////........
            echo $form->field($model, 'array_casual')->widget(
                MultipleInput::className(), [
                'id' => 'my_casual',
                'theme' => 'default',

                'rowOptions' => function ($model, $index, $context) {
                    $text_array = ['id' => 'row{multiple_index_my_casual}'];
                    //ddd($model->take_it=='1');

                    if (isset($model['take_it']) && $model['take_it'] == '1') {
                        $text_array = [
                            'id' => 'row{multiple_index_my_casual}',
                            'class' => 'redstyle',
                        ];

                    }

                    return $text_array;
                },


                //            'rowOptions' => [
                //                'id' => 'row{multiple_index_my_casual}',
                //            ],

                //            'max'               => 50,

                'min' => 0,
                // should be at least 2 rows
                'allowEmptyList' => false,
                'enableGuessTitle' => false,
                'sortable' => true,
                'addButtonPosition' => MultipleInput::POS_HEADER,
                // show add button in the header

                'columns' => [
                    [
                        'name' => 'calc',
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

                    [
                        'name' => 'wh_tk',
                        'type' => 'dropDownList',
                        'title' => 'Группа',
                        'defaultValue' => [],
                        //'items' => Spr_glob::find()->select(['name'])->column(),
                        'items' =>
                            ArrayHelper::map(
                                Spr_glob::find()->orderBy('name')->all(),
                                'id',
                                'name'),
                        'options' => [
                            'prompt' => 'Выбор ...',
                            'style' => 'min-width: 60px;',
                            'id' => 'subcat11_casual-{multiple_index_my_casual}',
                            'onchange' => <<< JS
                        
$.post("list?id=" + $(this).val(), function(data){
    //console.log(data);
    $("select#subcat_casual-{multiple_index_my_casual}").html(data);
});
JS
                            ,

                        ],
                    ],

                    [
                        'name' => 'wh_tk_element',
                        'type' => 'dropDownList',
                        'title' => 'Компонент',
                        'defaultValue' => [],
                        'items' =>
                            ArrayHelper::map(
                                Spr_glob_element::find()
                                    ->orderBy('name')->all(),
                                'id', 'name')
                        ,
                        'options' => [
                            'prompt' => '...',
                            'id' => 'subcat_casual-{multiple_index_my_casual}',

                            'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
// alert(data);

    $("select#subcat11_casual-{multiple_index_my_casual}").val(data);
});
                        
$.post("list_ed_izm?id=" + $(this).val(), function(data){
//alert(data);
    $("select#sklad-array_casual-{multiple_index_my_casual}-ed_izmer").val(data);
});
JS
                            ,
                        ],
                    ],


                    [
                        'name' => 'ed_izmer',
                        'title' => 'Ед. изм',
                        'type' => 'dropDownList',
                        'defaultValue' => 1,
                        'options' => [
                            'style' => 'padding: 0px;min-width:75px;overflow: auto;',
                            'id' => "sklad-array_casual-{multiple_index_my_casual}-ed_izmer",
                            'onchange' => <<< JS
                        
// Если выбрана любая Ед.Изм, кроме ШТУК 
// (метры, литры, пог.метры...)
                            
if ( $(this).val()>1 ){
    //console.log( $("input#zero-{multiple_index_my_casual}").val() );
    $("input#zero-{multiple_index_my_casual}").attr('step','0.1');
}else{
    $("input#zero-{multiple_index_my_casual}").attr('step','1');
}
JS
                            ,
                        ],


                        'items' => ArrayHelper::map(Spr_things::find()->all(), 'id', 'name'),


                    ],

                    [
                        'name' => 'take_it',
                        'title' => '',
                        'type' => 'checkbox',
                    ],


                    [
                        'name' => 'ed_izmer_num',
                        'title' => 'Кол-во',
                        'defaultValue' => 0.1,
                        'enableError' => true,
                        'value' => 'ed_izmer_num',
                        'options' => [
                            'id' => 'zero-{multiple_index_my_casual}',
                            'type' => 'number',
                            'class' => 'input-priority',
                            'style' => 'max-width: 70px;overflow: auto;',
                            'step' => '0.1',

                        ],
                    ],


                ],
            ])->label('Расходные материалы', ['class' => 'my_label']);;
            ?>
        </div>
    </div>


</div>


<?php ActiveForm::end(); ?>



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


$script = <<<JS

$(document).ready(function() {
    $( ".pv_motion_create_ok_button").show();
});

     

//////////////////// VID - sklad-sklad_vid_oper
// $('#sklad-sklad_vid_oper').change(function() {    
//      var  number2 = $('#sklad-sklad_vid_oper').val();
//      var  text2   = $('#sklad-sklad_vid_oper>option[value='+number2+']').text();
//       $('#sklad-sklad_vid_oper_name').val(text2);
// });



//////////////////// Debitor -top
$('#sklad-wh_debet_top').change(function() {
        
    var  number = $(this).val();
    var  text = $('#sklad-wh_debet_top>option[value='+number+']').text();    
    $('#sklad-wh_debet_name').val(text) ;

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
						alert('нет данных ' );
						console.log(res);
					}
    } );
    
});

//////////////////// Debitor - element sklad-wh_debet_element
$('#sklad-wh_debet_element').change(function() {    
     var  number2 = $('#sklad-wh_debet_element').val();
     var  text2   = $('#sklad-wh_debet_element>option[value='+number2+']').text();
      $('#sklad-wh_debet_element_name').val(text2);
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
						alert('не пошло. '+ res );
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

     // var  number = $('#sklad-sklad_vid_oper').val();
     // var  text   = $('#sklad-sklad_vid_oper>option[value='+number+']').text();
     // $('#sklad-sklad_vid_oper_name').val(text);
     //
     // var  number = $('#sklad-wh_debet_top').val();
     // var  text   = $('#sklad-wh_debet_top>option[value='+number+']').text();
     //  $('#sklad-wh_debet_name').val(text);
     //
     // var  number = $('#sklad-wh_destination').val();
     // var  text   = $('#sklad-wh_destination>option[value='+number+']').text();
     //  $('#sklad-wh_destination_name').val(text);     


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

