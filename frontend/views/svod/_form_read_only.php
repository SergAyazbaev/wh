<?php

use frontend\models\Spr_things;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = ' № ' . $new_doc->id; // Заголовок
?>


<style>

    div#itogo {
        background-color: #caf5d0;
        font-size: 20px;
        align-items: center;
        padding-left: 50%;
    }

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


<?php $form = ActiveForm::begin([
    'id' => 'project-form',

    //    'action' => ['index'],
    'method' => 'post',
    'options' => [
        'data-pjax' => 1,
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

            if (isset($new_doc->tz_id) && !empty($new_doc->tz_id)) {

                Modal::begin([
                    'header' => '<h3>ТехЗадание № ' . (isset($tz_head['id']) ? $tz_head['id'] : 0) . '</h3> <h3>Тема:  ' . (isset($tz_head['name_tz']) ? $tz_head['name_tz'] : 0) . ' </h3>',
                    //                    'header' => false,
                    'options' => [
                        'id' => 'kartik-modal1',
                        //'tabindex' => true // important for Select2 to work properly
                    ],
                    'toggleButton' => [
                        'label' => 'ТехЗадание №' . $new_doc->tz_id,
                        'class' => 'btn btn-default'  // btn-primary'
                    ],
                ]);


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
                            <?= (isset($tz_head['wh_cred_top_name']) ? $tz_head['wh_cred_top_name'] : '') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Количество ПЕ
                        </td>
                        <td>
                            <?= (isset($tz_head['multi_tz']) ? $tz_head['multi_tz'] : '') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Дорожная карта
                        </td>
                        <td>
                            <?= (isset($tz_head['street_map']) ? $tz_head['street_map'] : '') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Статус ТЗ
                        </td>
                        <td>
                            <?
                            if (isset($tz_head['status_state']))
                                echo $tz_head['status_state'] ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Дата создания ТЗ
                        </td>
                        <td>
                            <?= (isset($tz_head['dt_create']) ? $tz_head['dt_create'] : '') ?>
                        </td>
                    </tr>
                    <!--                    <tr>-->
                    <!--                        <td>-->
                    <!--                            Создал ТЗ-->
                    <!--                        </td>-->
                    <!--                        <td>-->
                    <!--                            --><?//=$tz_head['user_create_name']
                    ?>
                    <!--                        </td>-->
                    <!--                    </tr>-->
                    <tr>
                        <td>
                            Передано в работу
                        </td>
                        <td>
                            <?
                            if (isset($tz_head['status_create_date']))
                                echo $tz_head['status_create_date'] ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            Крайний срок для выполнения ТЗ
                        </td>
                        <td>
                            <?= (isset($tz_head['dt_deadline']) ? $tz_head['dt_deadline'] : '') ?>
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
            //            echo "<br>склад " . $sklad;
            ?>

            <?php
            echo "<br><h2> Накладная № " . (isset($new_doc->id) ? $new_doc->id : '') . "</h2>";
            ?>


            <?php

            ///////////
            if (empty($new_doc['array_bus']))
                $sum_bus = 0;
            else
                $sum_bus = count($new_doc['array_bus']);

            ///////////
            ///

            // Using a select2 widget inside a modal dialog

            if (isset($items_auto) && !empty($items_auto)) {
                Modal::begin([
                    //'header' => 'modal header',
                    'header' => false,
                    'options' => [
                        'id' => 'kartik-modal',
                        //'tabindex' => true // important for Select2 to work properly
                    ],
                    'toggleButton' => [
                        'label' => 'Автобусы (' . $sum_bus . ')',
                        'class' => 'btn btn-default'  // btn-primary'
                    ],
                ]);

                echo $form->field($new_doc, 'array_bus')->widget(Select2::className()
                    , [

                        'theme' => Select2::THEME_BOOTSTRAP, // THEME_DEFAULT, //THEME_CLASSIC, //THEME_BOOTSTRAP,
                        'size' => Select2::SMALL, //LARGE,

                        'name' => 'state_40',
                        'data' => $items_auto,
                        'options' => [
                            'id' => 'array_bus_select',
                            'placeholder' => 'Список автобусов ...',
                            'multiple' => true,
                            'tabindex' => false,
                            'hideSearch' => true,
                            'tags' => true,
                        ],

                        'pluginOptions' => [
                            'allowClear' => true,
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

            echo $form->field($new_doc, 'dt_create')
                ->widget(DateTimePicker::className(), [
                    'name' => 'dp_1',
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

            echo $form->field($new_doc, 'sklad_vid_oper')
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
            ?>

        </div>


        <div class="tree_land">
            <?php
            //ddd($new_doc);

            //////////
            echo $form->field($new_doc, 'wh_destination_name')
                ->textInput()
                ->label("Компания-получатель");


            ///////////////////

            echo $form->field($new_doc, 'wh_destination_element_name')
                ->textInput();


            ?>


        </div>


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
    echo Html::a('Выход', ['/'], [
        'onclick'=>"window.history.back();",
        'class' => 'btn btn-warning']);
    ?>


</div>


<div class="pv_motion_create_right">
    <?php
    $xx = 0;
    echo $form->field($new_doc, 'array_tk_amort')->widget(MultipleInput::className(), [

        'id' => 'my_id2',
        'theme' => 'default',
        'allowEmptyList' => false,
        'min' => 0, // should be at least 2 rows
        'enableGuessTitle' => false,
        'sortable' => true,
        'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

        'rowOptions' => function ($model, $index, $context) {
            $text_array = ['id' => 'row{multiple_index_my_id2}'];
            //ddd($model->take_it=='1');

            if (isset($model['take_it']) && $model['take_it'] == '1') {
                $text_array = ['id' => 'row{multiple_index_my_id2}',
                    'class' => 'redstyle'
                ];

            }

            return $text_array;
        },


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
                //                    'items' => ArrayHelper::map(
                //	                    post_spr_globam::find()
                //	                                   ->orderBy( 'name' )
                //	                                   ->all(),
                //                        'id','name'),


                'items' => function ($data) {
                    return [$data['wh_tk_amort'] => $data['name_wh_tk_amort']];
                },


                'options' => [
                    'prompt' => 'Выбор ...',
                    'style' => 'padding: 0px;overflow: auto;',
                    'id' => 'subcat211-{multiple_index_my_id2}',

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

                ],

                //                    'items' =>
                //	                    ArrayHelper::map(
                //		                    Spr_globam_element::find()
                //		                                      ->orderBy( 'name' )
                //		                                      ->all(),
                //		                    'id', 'name' )

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
                    'id' => 'subcat-ed_izm-{multiple_index_my_id2}',
                    'prompt' => 'Выбор ...',
                    'style' => 'min-width:1vw;width: 75px;overflow: auto;'
                ],


                'items' => ArrayHelper::map(
                    Spr_things::find()
                        ->all(),
                    'id', 'name'),

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
                'name' => 'take_it',
                'title' => '',
                'type' => 'checkbox',
            ],


            [
                'name' => 'bar_code',
                'title' => 'Штрих-код',

                'options' => [
                    'id' => 'code5-{multiple_index_my_id2}',
                    'style' => 'min-width:5vw;width: 130px;overflow: auto;',

                    'pattern' => '[0-9]{5,25}',
                    'type' => 'input',

                    //'pattern' => '(1[0-2]|0[1-9])\/(1[5-9]|2\d)',
                    //'pattern' => '[\d]{12}',
                    //'placeholder' => "Штрих-код отсутствует" ,
                    //'placeholder' => "Штрих-код",
                    //'maxlength' => "12" ,

                    'onkeyup' => /** @lang text */ <<< JS
//// Сортировка А-Я по Компонентам F5
if (event.keyCode==115){
        alert('Сортировка А-Я по Компонентам F5');
}

//// Копия строки F9
if (event.keyCode==120){
        //alert('Копия строки F9'); 

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
    ])->label('Амортизация', ['class' => 'my_label']);;
    ?>
</div>


<div id="itogo"> Итого: <?php echo $itogo ?> шт.</div>
<br>
<br>


<?php ActiveForm::end(); ?>


<?php
$script = <<<JS
        ///Полезное украшение всех выбранных селекторов
        $( "select" ).find('option:selected').css('color', 'red').css('font-weight', 'bold');
        ///////////

        // var id = $("div#my_id").find("select:selected");        
        // var id2 = $( id ).find("value=128").text();        
        // alert( id2 );
        
        //
        // //.css('color', 'green').css('font-weight', 'bold');
        
        

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

