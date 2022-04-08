<?php

use frontend\models\post_spr_glob_element;
use frontend\models\post_spr_globam;
use frontend\models\post_spr_globam_element;
use frontend\models\Spr_glob;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;

use kartik\datetime\DateTimePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Alert;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;


//Pjax::begin([    'id' => 'pjax-container',]);
//    echo Yii::$app->request->get('page');
//Pjax::end();

?>

<style>

    .glyphicon-plus{
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;
        /*padding: 7px;*/
        /*margin: -8px;*/
        font-size: medium;
        font-weight: bold;
        border-radius: 25px;
    }

    .glyphicon-plus:hover{
        color: rgba(255, 255, 255, 0.53);
        background-color: #5cb85c;
        border-color: #4cae4c;
        box-shadow: -1px 3px 7px 0px rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.1);
    }

    /*.help-block {*/
    /*}*/

    .help-block-error{
        display: none;
    }

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

</style>


<?php //$form = ActiveForm::begin([
//    'enableAjaxValidation'      => true,
//    'enableClientValidation'    => true, //false,
//    'validateOnChange'          => true, //false,
//    'validateOnSubmit'          => true,
//    'validateOnBlur'            => true, //false,
//]);
//?>


<?php $form = ActiveForm::begin();?>

<div id="tz1">
       <div class="pv_motion_create_right_center">

            <div class="tree_land2">
                <?php
	                echo "<br>склад " . $sklad;
                ?>

                    <?php

            if(isset($model->tz_id) && !empty($model->tz_id)) {

                Modal::begin(['header' => '<h3>ТехЗадание № ' . $tz_head['id'] . '</h3> <h3>Тема:  ' . $tz_head['name_tz'] . ' </h3>',
                    //                    'header' => false,
                    'options' => ['id' => 'kartik-modal1',//'tabindex' => true // important for Select2 to work properly
                    ],
                    'toggleButton' => [
                            'label' => 'ТехЗадание №' . $model->tz_id, 'class' => 'btn btn-default'  // btn-primary'
                    ],]);
                ?>

                   <table class="modal_left">
                    <thead>
                    <tr>
                        <td>
                            Сокращение
                        </td>
                        <td>
                            Значение
                        </td>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>
                            Конечный адресат установки
                        </td>
                        <td>
                            <?=$tz_head['wh_cred_top_name']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Количество ПЕ
                        </td>
                        <td>
                            <?=$tz_head['multi_tz']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Дорожная карта
                        </td>
                        <td>
                            <?=$tz_head['street_map']?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Статус ТЗ
                        </td>
                        <td>
                            <?
                            if(isset($tz_head['status_state']))
                                echo $tz_head['status_state']?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Дата создания ТЗ
                        </td>
                        <td>
                            <?=$tz_head['dt_create']?>
                        </td>
                    </tr>
<!--                    <tr>-->
<!--                        <td>-->
<!--                            Создал ТЗ-->
<!--                        </td>-->
<!--                        <td>-->
<!--                            --><?//=$tz_head['user_create_name']?>
<!--                        </td>-->
<!--                    </tr>-->
                    <tr>
                        <td>
                            Передано в работу
                        </td>
                        <td>
                            <?
                            if(isset($tz_head['status_create_date']))
                                echo $tz_head['status_create_date']?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            Крайний срок для выполнения ТЗ
                        </td>
                        <td>
                            <?=$tz_head['dt_deadline']?>
                        </td>
                    </tr>
                    </tbody>
                </table>








                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ок.Понятно</button>
                </div>

                <?
//                echo Alert::widget([
//                    'options' => [
//                        'class' => 'alert-info'
//                    ],
//                    'body' => 'Кнопка "Прикрыть список" не удаляет и не отменяет изменения списка '
//                ]);


                Modal::end();
            }
                    ?>


                <?php
                echo "<br><h2> Накладная № ".$model->id ."</h2>";
                ?>


                <?php



                ///////////
                if (empty($model['array_bus']))
                    $sum_bus = 0;
                else
                    $sum_bus = count ($model['array_bus']);

                ///////////
                ///

                // Using a select2 widget inside a modal dialog



                if(isset($items_auto) && !empty($items_auto)){
                    Modal::begin([
                        //'header' => 'modal header',
                        'header' => false,
                        'options' => [
                            'id' => 'kartik-modal',
                            //'tabindex' => true // important for Select2 to work properly
                        ],
                        'toggleButton' => [
                            'label' => 'Автобусы ('.$sum_bus.')' ,
                            'class' => 'btn btn-default'  // btn-primary'
                        ],
                    ]);

                    echo $form->field($model, 'array_bus')->widget(Select2::className()
                        , [
                            'name' => 'st',
                            'data' => $items_auto,
                            'options' => [

                                'id' => 'array_bus_select',
                                'placeholder' => 'Список автобусов ...',
                                'allowClear' => true,
                                'multiple' => true

                            ],
                            'theme' => Select2::THEME_BOOTSTRAP, // THEME_DEFAULT, //THEME_CLASSIC, //THEME_BOOTSTRAP,
                            'size' => Select2::SMALL, //LARGE,
                            'pluginOptions' => [
                                'tags' => true,
                                'tokenSeparators' => [',', ' '],
                                'maximumInputLength' => 10
                            ],
                        ]);

                    ?>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Прикрыть список</button>
                    </div>

                    <?
                    echo Alert::widget([
                        'options' => [
                            'class' => 'alert-info'
                        ],
                        'body' => 'Кнопка "Прикрыть список" не удаляет и не отменяет изменения списка '
                    ]);


                    Modal::end();
                }


            ?>

            </div>



            <div class="tree_land">
            <?php


            $model->dt_create = date('d.m.Y H:i:s', strtotime($model['dt_create']));

            echo $form->field($model, 'dt_create')->widget(DateTimePicker::className(),
                ['name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'convertFormat' => false,
                    'options' => ['placeholder' => 'Ввод даты/времени...'],

                    'pluginOptions' => [
	                    'format'         => 'dd.mm.yyyy HH:ii:ss',
	                    'pickerPosition' => 'bottom-left',
	                    'autoclose'      => true,
	                    'weekStart'      => 1, //неделя начинается с понедельника
                        // 'startDate' => $date_now,
	                    'todayBtn'       => true, //снизу кнопка "сегодня"
                    ]]);


            ?>



            <?php
            echo $form->field($model, 'sklad_vid_oper')
                ->dropdownList(
                    [
                        '1'=>'Инвентаризация',
                        '2'=>'Приходная накладная',
                        '3'=>'Расходная накладная',
                    ],
                    [
                        'prompt' => 'Выбор ...',
                    ]
                )
                ->label("Вид операции");

            echo "<div class='close_hid'>";
            echo $form->field($model, 'sklad_vid_oper_name')
                ->hiddenInput()->label(false);
            echo "</div>";
            ?>

           </div>

           <div class="tree_land">
            <?php
            //////////
            echo $form->field($model, 'wh_debet_top')->dropdownList(
                ArrayHelper::map(
                    Sprwhtop::find()->all(),
                    'id','name'),
                [
                    'prompt' => 'Выбор ...',
                    //'id' => 'wh_debet_top',

                ]
            )->label("Компания-отправитель");


            echo $form->field($model, 'wh_debet_element')->dropdownList(
                ArrayHelper::map(
                    Sprwhelement::find()
                        ->where(['parent_id'=>(integer)$model->wh_debet_top])
                        ->all(),'id','name'),
                [
                    'prompt' => 'Выбор  ...',
                    //'id' => 'wh_debet_element',
                ]
            );

            echo "<div class='close_hid'>";
            echo $form->field($model, 'wh_debet_name')
                ->hiddenInput()->label(false);
            echo $form->field($model, 'wh_debet_element_name')
                ->hiddenInput()->label(false);
            echo "</div>";

            ?>

        </div>


        <div class="tree_land">
            <?php

            //////////
            echo $form->field($model, 'wh_destination')->dropdownList(
                ArrayHelper::map(
                    Sprwhtop::find()->all(),
                    'id','name'),
                [
                    'prompt' => 'Выбор склада ...'
                ]
            )->label("Компания-получатель");

            echo $form->field($model, 'wh_destination_element')->dropdownList(
                ArrayHelper::map(Sprwhelement::find()
                    ->where(['parent_id'=>(integer)$model->wh_destination])
                    ->all(),'id','name'),
                [
                    'prompt' => 'Выбор склада ...',
                    //'options' => [ $model->wh_destination => ['selected'=>'selected']],
                ]
            );

            echo "<div class='close_hid'>";
            echo $form->field($model, 'wh_destination_name')
                ->hiddenInput()->label(false);
            echo $form->field($model, 'wh_destination_element_name')
                ->hiddenInput()->label(false);
            echo "</div>";

            ?>

        </div>
       </div>


    <div class="pv_motion_create_ok_button">

        <!--        --><?php //= Html::Button('&#8593;',
        //            [
        //                'class' => 'btn btn-warning',
        //                'onclick'=>"window.history.back();"
        //            ]);
        //        ?>

        <?php
        echo \yii\helpers\Html::a(
            'Выход',
            //[ '/sklad/in?otbor=' . $sklad ],
            [ '/sklad/in' ],
            [
                'onclick' => 'self.close();document.location.reload(true);',
                'class' => 'btn btn-warning',
            ] );
        ?>

        <?php
        echo Html::submitButton('Сохранить изменения в накладной',
            ['class' => 'btn btn-success']
        );
        ?>

        <!--        --><?php
        //        echo Html::a('Копия с новым номером',
        //            ['/sklad/copycard_from_origin?id=' . $model->id],
        //            [
        //                'id' => "to_print",
        //                //'target' => "_blank",
        //                'class' => 'btn  btn-info' //btn-primary'   //btn-success'
        //            ]);
        //        ?>

        <!--        --><?php
        //        echo Html::a('Расходная накладная',
        //            ['/sklad/copycard_rashod?id=' . $model->id],
        //            [
        //                'id' => "to_print",
        //                //'target' => "_blank",
        //                'class' => 'btn  btn-info' //btn-primary'   //btn-success'
        //            ]);
        //        ?>


        <?php
        echo Html::a('Просмотр накладной',
            ['/sklad/proshivka_to_pdf?id=' . $model->id],
            [
                'id' => "to_print",
               'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>

        <?php
        echo Html::a('Печать',
            ['/sklad/proshivka_to_pdf?id=' . $model->id],
            [
                'id' => "to_print",
               'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>
        <?php
        echo Html::a('Накладная A',
            ['/sklad/html-asemtai/?id=' . $model->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>

        <?php
        echo Html::a('Почта в ТХА ',
            //        sklad/mail_to_tha?id=5c99dda980a063252c005fb2&sklad=3524
            ['/sklad/mail_to_tha?id=' . $model->_id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>

    </div>







    <div class="pv_motion_create_right">
	    <?php
//ddd(
//    ArrayHelper::map(
//    Sprwhelement::find()
//        ->where(['IN', 'id', array_keys($items_auto)])
//        ->all(),
//        'id', 'name')
//);

//        ddd(array_keys($items_auto));

//        [
//            230 => '054AE02'
//            279 => 'A882FW'
//            280 => 'A899FW'
//            281 => 'A924HD'
//            282 => 'A933HD'
//            283 => 'A942HD'
//        ]



        echo $form->field($model, 'array_tk_amort')->widget(MultipleInput::className(),
        [
            'id' => 'my_id2',
            'theme'  => 'default',
            //   'max'     => 50,
            'min'               => 0, // should be at least 2 rows
            'allowEmptyList'    => false,
            'enableGuessTitle'  => false,
            //   'sortable'  => true,

            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

            'rowOptions' => [
                'id' => 'row{multiple_index_my_id2}',
                'style' => 'max-height: 20px;',
            ],

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
                    'name'  => 'wh_tk_amort',
                    'type'  => 'dropDownList',
                    'title' => 'Группа',
                    'defaultValue' => 0,
                    'items' => ArrayHelper::map(
                        post_spr_globam::find()->all(),
                        'id','name'),

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'min-width: 12vw;',
                        'id' => 'subcat211-{multiple_index_my_id2}',
                        'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
    console.log(data);
    $("select#subcat22-{multiple_index_my_id2}").html(data);
});
JS
                    ],
                ],

                [
                    'name'  => 'wh_tk_element',
                    'type'  => 'dropDownList',
                    'title' => 'Компонент',
                    'defaultValue'=> [],

                    'options' => [
                        'id' => 'subcat22-{multiple_index_my_id2}',
                        'style' => 'min-width: 136px;',
                        'prompt' => '...',
                        'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});
JS
                  ],

                    'items' =>
                        ArrayHelper::map(
                            post_spr_globam_element::find()
                                ->orderBy('name')
                                ->all(),
                            'id','name')
                    ,
                ],

                [
                    'name'  => 'bar_code',
                    'title' => 'Штрих-код',

                    'options' => [
                      'id' => 'code5-{multiple_index_my_id2}',
                      'type' => 'input',
                      'style' => 'min-width:5vw;width: 120px;overflow: auto;',
                      'pattern' => '[\d]{7,12}',
                      'placeholder' => "Код отсутствует" ,
//                      'maxlength' => "12" ,

                      'onkeyup' => <<< JS
var data = $(this).val();
$("select#code5-{multiple_index_my_id2}").html( data );

     if (event.keyCode==13){            

            var str='{multiple_index_my_id2}';
            var nextrow = ((str*1)+1);
            $('#code5-'+nextrow).focus();

            //    console.log(data);
            //    alert(data);
     }
JS
                                ],
                    ],


                [
                    'name'  => 'msam_code',
                    'title' => 'MSAM',

                    'options' => [
                        'id' => 'code-msam-{multiple_index_my_id2}',
                        'type' => 'input',
                        'style' => 'min-width:5vw;width: 140px;overflow: auto;',
                        'pattern' => '[\d]{9,15}',
                        'placeholder' => "MSAM отсутствует" ,
                        'maxlength' => "15" ,

                        'onkeyup' => <<< JS
var data = $(this).val();
$("select#code5-{multiple_index_my_id2}").html( data );

     if (event.keyCode==13){            

            var str='{multiple_index_my_id2}';
            var nextrow = ((str*1)+1);
            $('#code-msam-'+nextrow).focus();

            //    console.log(data);
            //    alert(data);
     }
JS
                    ],
                ],



                [
                    'name'  => 'bus_name',
                    'title' => 'Наим.',
                     'type'  => 'dropDownList',
                    'options' => [
                        'style' => 'padding:0;min-width:5vw;width: 75px;overflow: auto;',
                    ],

                    'items' =>
                        ArrayHelper::map(
                            Sprwhelement::find()
                                ->where(['IN', 'id', array_keys($items_auto)])
                                ->all(),
                            'id', 'name')

                    ,
                ],

                [
                    'name'  => 'bus_number_bort',
                    'title' => 'Борт #',
                     'type'  => 'dropDownList',
                    'options' => [
                        'style' => 'padding:0;min-width:5vw;width: 75px;overflow: auto;',
                    ],

                    'items' =>
                        ArrayHelper::map(
                            Sprwhelement::find()
                                ->where(['IN', 'id', array_keys($items_auto)])
                                ->all(),
                            'id', 'nomer_borta')
                    //nomer_borta
                    ,
                ],


                [
                    'name'  => 'bus_number_gos',
                    'title' => 'Гос #',
                    'type'  => 'dropDownList',
                    'options' => [
                        'style' => 'padding:0;min-width:5vw;width: 105px;overflow: auto;',
                    ],

                    'items' =>
                        ArrayHelper::map(
                            Sprwhelement::find()
                                ->where(['IN', 'id', array_keys($items_auto)])
                                ->all(),
                            'id', 'nomer_gos_registr')
                    ,

                ],

                [
                    'name'  => 'bus_number_vin',
                    'title' => 'VIN #',
                    'type'  => 'dropDownList',
                    'options' => [
                        'style' => 'padding:0;min-width:5vw;width: 105px;overflow: auto;',
                    ],

                    'items' =>
                        ArrayHelper::map(
                            Sprwhelement::find()
                                ->where(['IN', 'id', array_keys($items_auto)])
                                ->all(),
                            'id', 'nomer_vin')
                    ,

                ],

                [
                    'name'  => 'the_bird',
                    'value' => 'the_bird',
                    'title' => 'ТХА',
                    'options' => [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'style' => 'min-width:50px;  max-width: 3vw;width:50px;padding:0',
                    ],
                    'type' => 'dropDownList',
                    'items' =>  [  '0'=>'Нет','1'=>'Да' ],

                ],

                [
                    'name'  => 'tx',
                    'title' => 'Коммент.',
                    'type'  => 'textarea',
                    'defaultValue' => '',
                    'enableError' => false,
                    'options' => [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'class' => 'input-priority',
                        'style' => 'min-width:1vw; height: 2vh; max-width: 7vw;',
                    ]
                ]

            ]
        ]);
        ?>

        <?php
        echo $form->field($model, 'array_tk')->widget(MultipleInput::className(), [

            'id' => 'my_id',
            'theme'  => 'default',
            'rowOptions' => [
                'id' => 'row{multiple_index_my_id}',
            ],

//            'max'               => 25,
            'min'               => 0, // should be at least 2 rows
            'allowEmptyList'    => false,
            'enableGuessTitle'  => false,
//            'sortable'  => true,
            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

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
                    'name'  => 'wh_tk',
                    'type'  => 'dropDownList',
                    'title' => 'Группа',
                    'defaultValue' => [],
                    //'items' => Spr_glob::find()->select(['name'])->column(),
                    'items' =>
                        ArrayHelper::map(
                            Spr_glob::find()->all(),
                            'id',
                            'name'),

                    'options' => [
                        'prompt' => 'Выбор ...',
//                        'style' => 'min-width: 200px;',
                        'style' => 'min-width:14vw;  max-width: 28vw;',
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
                    'name'  => 'wh_tk_element',
                    'type'  => 'dropDownList',
                    'title' => 'Компонент',
                    'defaultValue'=> [],
                    'items' =>
                        ArrayHelper::map(
                            post_spr_glob_element::find()
                                ->orderBy("name")
                                ->all(),
                            'id','name')
                    ,


                    'options' => [
                        'prompt' => '...',
                        'id' => 'subcat-{multiple_index_my_id}',
                        'style' => 'min-width:14vw;  ',
                        'onchange' => <<< JS
                        
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_id}").val(data);
});
                        
$.post("list_ed_izm?id=" + $(this).val(), function(data){
    $("select#sklad-array_tk-{multiple_index_my_id}-ed_izmer").val(data);
});
JS
                    ]
                ],

                [
                    'name'  => 'ed_izmer',
                    'title' => 'Ед. изм',
                    'type'  => 'dropDownList',
                    'defaultValue' => 1,
                    'options' => [
                        'style' => 'padding: 0px;min-width:6vw;  max-width: 7vw;',
                    ],

                    'items' => ArrayHelper::map( Spr_things::find()->all(),'id','name'),


                ],

                [
                    'name'  => 'ed_izmer_num',
                    'title' => 'Кол-во',
                    'defaultValue' => 1,
                    'enableError' => true,
                    'value' => 'ed_izmer_num',
                    'options' => [
                        'type'  => 'number',
                        'class' => 'input-priority',
                        'step'  => '0.1',
                        'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                    ]
                ],

            ]
        ]) ;

        ?>

    </div>
</div>


<?php ActiveForm::end(); ?>



<?php
$script = <<<JS

$(document).on('keypress',function(e) {
    if(e.which == 13) {
        // alert('Нажал - enter!');
      e.preventDefault();
      return false;
    }
});



    // Вид ОПЕРАЦИИ (Инвентаризация, Приход, Расход)   
// $('#sklad-sklad_vid_oper').change(function() {
//    
//      var  number = $(this).val();
//      var  text = $('#sklad-sklad_vid_oper>option[value='+number+']').text();        
//     
//      $('#sklad-sklad_vid_oper_name').val(text);
//     
//          if(number==1){      /// Инвентаризация             
//              // $('#sklad-wh_destination').val(2);
//              // $('#sklad-wh_debet_top').val(2);
//              //
//              // $('#sklad-wh_destination_element').val('Склад №1');
//              // $('#sklad-wh_debet_element').val('Склад №1');
//             
//             
//             
//          }
//         
//          if(number==2){      /// Приходная накладная             
//              // $('#sklad-wh_destination').val(2);
//              //
//              // //$('#sklad-wh_destination_element').val('Склад №1');
//              //
//              // $('#sklad-wh_debet_top').val(0);
//              // $('#sklad-wh_debet_element').val(0);
//             
//          }
//         
//          if(number==3){      /// Расходная накладная           
//              // $('#sklad-wh_debet_top').val(2);
//              // //$('#sklad-wh_debet_element').val('Склад №1');
//              //
//              // $('#sklad-wh_destination').val(0);
//              // $('#sklad-wh_destination_element').val(0);             
//          }
// });

//////////////////// VID - sklad-sklad_vid_oper
$('#sklad-sklad_vid_oper').change(function() {    
     var  number2 = $('#sklad-sklad_vid_oper').val();
     var  text2   = $('#sklad-sklad_vid_oper>option[value='+number2+']').text();
      $('#sklad-sklad_vid_oper_name').val(text2);
});

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
		    $('#sklad-wh_destination_element').html('');
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
// $('#go_home').click(function() {    
//     window.history.back();  
// })

     var  number = $('#sklad-sklad_vid_oper').val();
     var  text   = $('#sklad-sklad_vid_oper>option[value='+number+']').text();
     $('#sklad-sklad_vid_oper_name').val(text);

     var  number = $('#sklad-wh_debet_top').val();
     var  text   = $('#sklad-wh_debet_top>option[value='+number+']').text();
      $('#sklad-wh_debet_name').val(text);
     
     var  number = $('#sklad-wh_destination').val();
     var  text   = $('#sklad-wh_destination>option[value='+number+']').text();
      $('#sklad-wh_destination_name').val(text);     


     // var  number = $('#sklad-wh_debet_element').val();
     // var  text   = $('#sklad-wh_debet_element>option[value='+number+']').text();
     //  $('#sklad-wh_debet_element_name').val(text);
      
     // var  number2 = $('#sklad-wh_destination_element').val();     
     // var  text2   = $('#sklad-wh_destination_element>option[value='+number2+']').text();
     //  $('#sklad-wh_destination_element_name').val(text2);
JS;


$this->registerJs($script, yii\web\View::POS_READY);
?>

