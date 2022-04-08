<?php

use kartik\datetime\DateTimePicker;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>

<style>
    .modal-content {
        width: 900px;
    }

    .btn_filter {
        /*background-color: #99979c;*/
        display: block;
        width: max-content;
        float: left;
        padding: 2px 5px;
    }

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

    .name_s1 {
        display: block;
        width: max-content;
    }

    .name_s {
        display: inline-block;
        margin-left: 5px;
        width: 200px;
        float: left;
        text-align: right;
    }

    .modal-vn {
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

    div > .has-error > .help-block {
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
        min-width: 345px;
        background-color: #74bb2e33;
        display: block;
        position: relative;
    }

    .pv_motion_create_ok_button {
        display: block;
        position: inherit;
        padding: 0px 20px;
        float: left;
        width: 100%;
        margin-top: 5px;
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
            margin-top: 5px;
        }

    }


    .pv_motion_create_right_center {
        width: 100%;
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

    'method' => 'post',
    'options' => [
        'data-pjax' => 0,
        'autocomplete' => 'off'
    ],

    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'validateOnChange' => true,
    'validateOnSubmit' => true,
//    'validateOnBlur'            => true,//false,false,

]);
?>


<div id="tz1">
    <div class="pv_motion_create_right_center">

        <div class="tree_land3">

            <?php
            echo "<br>склад " . $model->wh_home_number;
            ?>
            <?php
            echo "<br><h2> Ведомость № " . $model->id . "</h2>";

            echo $form->field($model, 'id')->hiddenInput()->label(false);
            ?>

        </div>


        <div class="tree_land">

            <?php

            // Проверка Воспроизведения ИЗ миллисекунд в нормальный формат дат
            // $model->dt_create = $model->getDtCreateText();


            $model->dt_create =
                date('d.m.Y H:i:s', strtotime($model['dt_create']));

            echo $form->field($model, 'dt_create')
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
            echo $form->field($model, 'dt_update')
                ->textInput(['readonly' => true]);
            ?>


        </div>


        <div class="tree_land">
            <?php

            //////////
            echo $form->field($model, 'wh_destination')->dropDownList(
                $list_wh_top,
                [
                    'prompt' => 'Выбор склада ...'
                ]
            )->label("Компания");


            ///////////////////
            echo $form->field($model, 'wh_destination_element')->dropDownList(
                $list_wh_element,
                [
                    'prompt' => 'Выбор склада ...'
                ]
            )->label("Склад");


            //            echo "<div class='close_hid'>";
            //            echo $form->field($model, 'wh_destination_name')
            //                ->hiddenInput()->label(false);
            //            echo $form->field($model, 'wh_destination_element_name')
            //                ->hiddenInput()->label(false);
            //            echo "</div>";

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
        echo Html::a('Выход', ['/sklad_inventory_wh/return_to_refer'], ['class' => 'btn btn-warning']);
        ?>

        <?php
        echo Html::submitButton(
            'Сохранить изменения в накладной',
            [
                'class' => 'btn btn-success',
                'name' => 'button',
                'value' => 'save',
                'data-confirm' => Yii::t(
                    'yii',
                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'),
            ]
        );
        ?>

        <?= Html::submitButton(
            'EXCEL', [
            'class' => 'btn btn-default',
            'name' => 'print',
            'value' => 1,
        ]) ?>

        <?php
        echo Html::submitButton(
            'Объединить файлы',
            [
                'class' => 'btn btn-primary',
                'name' => 'button',
                'value' => 'save_lot_files',

//                'data-confirm' => Yii::t(
//                    'yii',
//                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'),
            ]
        );
        ?>





        <?php //////////////////////////////////////
        Modal::begin(['header' => '<h2>Новая запись в накладную</h2>',
            'toggleButton' => [
                'label' => ' + ',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],
            //'footer' => 'Низ окна',
        ]);
        ?>

        <div class="name_ss">
            <!--            <div class="name_nn">-->
            <!--                Амортизация:-->
            <!--            </div>-->

            <div class="name_s1">
                <div class="name_s">
                    <?php
                    //
                    // $add_group, $add_item,
                    // $add_group_name, $add_item_name,
                    // add_barcode;
                    ?>

                    <?php
                    echo $form->field($model, 'add_group')->dropDownList(
                        $spr_group,
                        [
                            'prompt' => 'Выбор группы ...'
                        ]
                    )->label(false);           //->label("Группа");
                    ?>
                </div>

                <div class="name_s">
                    <?php
                    echo $form->field($model, 'add_item')->dropDownList(
                        [],
                        [
                            'prompt' => ' ...'
                        ]
                    )->label(false);
                    ?>
                </div>

                <div class="name_s">
                    <?= $form->field($model, 'add_kol')
                        ->textInput()
                        ->label(false);
                    ?>
                </div>

                <div class="name_s">
                    <?= $form->field($model, 'add_barcode')
                        ->textInput(['placeholder' => 'add_barcode', 'autofocus' => true,])
                        ->label(false);
                    ?>
                </div>
                <div class="name_s">
                    <?= Html::submitButton('Добавить',
                        [
                            'class' => 'btn btn-info',
                            'name' => 'button',
                            'value' => 'add_item',
                        ])
                    ?>
                </div>

            </div>
        </div>


        <?php Modal::end();
        //}
        ///////////////////////////////////?>

    </div>

    <?php $form = ActiveForm::begin(); ?>

    <div class="pv_motion_create_ok_button ">
        <div class="btn_filter">

            <?= $form->field($model, 'filter_bc')
                ->textInput(['placeholder' => "Штрих-код",
                    'style' => 'width: 177px; margin-right: 5px;',
                ])
                ->label(false);
            ?>
        </div>
        <div class="btn_filter">
            <?= $form->field($model, 'filter_id')
                ->textInput(['placeholder' => '№ накладной',
                    'style' => 'width: 140px; margin-right: 5px;',
                    //'readonly' => 'readonly'
                ])
                ->label(false);

            //public $filter_bc, $filter_id, $filter_dt;
            ?>
        </div>
        <div class="btn_filter">
            <?= $form->field($model, 'filter_dt')
                ->textInput(['placeholder' => "Дата",
                    'style' => 'width: 110px; margin-right: 5px;',
                ])
                ->label(false);
            ?>
        </div>

        <div class="btn_filter">
            <!--            --><? //= Html::submitButton('Фильтр', ['class' => 'btn btn-success']) ?>
            <?= Html::submitButton('Фильтр', [
                'class' => 'btn btn-primary',
                'name' => 'filtr',
                'value' => 'filtr',
            ]) ?>


            <?= Html::submitButton('Фильтр двойников', [
                'class' => 'btn btn-primary',
                'name' => 'filtr',
                'value' => 'double_sting',
            ]) ?>


            <?= Html::submitButton('Сброс фильтра', [
                'class' => 'btn btn-warning',
                'name' => 'filtr',
                'value' => 'close',
            ]) ?>


        </div>

    </div>

    <div class="pv_motion_create_right">

        <?php Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $provider,
            //'filterModel' => $filter_model,
            'columns' => [
                [
                    'label' => '#',
                    'attribute' => 'id_key',
                    'contentOptions' => ['style' => 'width: 100px'],
                    'content' => function ($model, $key1, $key2) {
                        if ($model['id_key'] > -1) {
                            return $model['id_key'];
                        }
                        return '';
                    }
                ],

                [
                    'label' => 'Группа ',
                    'attribute' => 'wh_tk_amort',
                    'value' => 'name_wh_tk_amort',
//                'contentOptions' => ['style' => ' width: 72px;'],
                ],
                [
                    'label' => 'Наименование',
                    'attribute' => 'wh_tk_element',
                    'value' => 'name_wh_tk_element',
//                'contentOptions' => ['style' => ' width: 72px;'],
                ],
                [
                    'label' => 'Ед.Изм',
                    'attribute' => 'name_ed_izmer',
                    'value' => 'name_ed_izmer',
                    'contentOptions' => ['style' => ' width: 42px;'],
                ],
                [
                    'label' => 'Кол.',
                    'attribute' => 'ed_izmer_num',
                    'value' => 'ed_izmer_num',
                    'contentOptions' => ['style' => ' width: 52px;'],
                ],


                [
                    'label' => 'Штрих-код',
                    'attribute' => 'bar_code',
                    'contentOptions' => ['style' => 'width: 120px;'],
                ],


                [
                    'label' => '№ накладной',
                    'attribute' => 'id',
                    'contentOptions' => ['style' => ' width: 120px;'],
                ],

                [
                    'label' => 'Дата',
                    'attribute' => 't',
                    'format' => ['datetime',
                        'php:d.m.y (H:i:s)',
                    ],
                    'contentOptions' => ['style' => 'width: 100px'],
                    'content' => function ($mod) {
                        if (isset($mod['t']) && !empty($mod['t'])) {
                            return date('d.m.y (H:i:s)', $mod['t']);
                        }
                        return '';
                    }
                ],


                ['class' => 'yii\grid\ActionColumn',
                    'header' => '',
                    'contentOptions' => ['style' => ' width: 50px;'],
                    'headerOptions' => ['width' => '10'],

                    'template' => '{delete}',

                    'buttons' => [
                        'delete' =>
                            function ($url, $model, $key) {

                                if (
                                    Yii::$app->getUser()->identity->group_id == 100 ||
                                    Yii::$app->getUser()->identity->group_id == 71
                                ) {

//                                    $url = Url::to(['/sklad_inventory_wh/delete?key=' . $model['id_key']]);

//                                    $options = [
//                                        'title' => 'delete',
//                                        'aria-label' => 'delete',
//                                        'name' => 'button',
//                                        'value' => 'delete_by_key',
//                                        //'data-pjax' => '0',
//                                        'data-confirm' => Yii::t('yii', 'Точно Удаляем ?'),
//                                    ];


//                                    return Html::a(
//                                        '<span class="glyphicon glyphicon-remove " style="color:red"></span>',
//                                        $url, $options);


                                    return Html::submitButton('', [
//                                        'class' => 'btn btn-primary',
                                        'class' => 'glyphicon-remove',
                                        'name' => 'button_delete',
                                        'value' => $model['id_key'],
                                        'data-confirm' => Yii::t('yii', 'Точно Удаляем ?'),
                                    ]);
                                }

                                //return Html::a('<span class="glyphicon " ></span>', $url);
                                return '';
                            },
                    ],
                ],

            ],
        ]);
        ?>
        <?php Pjax::end(); ?>

    </div>

    <?php ActiveForm::end(); ?>

    <div class="pv_motion_create_right">


    </div>


</div>

<?php ActiveForm::end(); ?>






<?php
$script = <<<JS
$(document).ready(function() {
    $('.alert_save').fadeOut(1500); // плавно скрываем окно временных сообщений
});


//////////////////// Debitor -top
$('#sklad_wh_invent-add_group').change(function() {
    
    var  number = $(this).val();       
    //
        
    $.ajax( {    
		url: '/sklad_inventory_wh/listamort_select',
		data: {
		    parent_id :number
		},			
			success: function(res) {                       
                        $('#sklad_wh_invent-add_item').html(res);
					},										
			error: function( res) {		    
						alert('JS.sklad_wh_invent-add_item ==='+res );
						console.log(res);						
					}					
    } );
        
    
});


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>



