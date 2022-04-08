<?php

use frontend\models\post_spr_globam;
use frontend\models\Spr_globam_element;
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
use yii\web\View;
use yii\widgets\ActiveForm;


//use yii\widgets\Pjax;
//Pjax::begin(['id' => "pjax-container",]);
//        echo \yii::$app->request->get('page');
//Pjax::end();

$zam_array = [
    '1' => 'Инвентаризация',
    '2' => 'Приходная накладная',
    '3' => 'Расходная накладная',
    '4' => 'Снятие(замена)',
    '5' => 'Установка(замена)',
];

$this->title = $zam_array[$new_doc->sklad_vid_oper] . ' № ' . $new_doc->id; // Замена

?>


<style>
    .select2-selection__clear {
        display: none;
    }

    .select2-container--krajee .select2-selection--single {
        height: 26px;
        line-height: 1;
        padding: 5px 40px 5px 5px;
        margin: 0px;
    }

    .select2-container--krajee .select2-selection--single .select2-selection__arrow {
        border: none;
        border-left: 1px solid #aaa;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
        position: absolute;
        height: 24px;
        top: 1px;
        right: 1px;
        width: 20px;
    }


    div.nakl1 {
        font-size: 20px;
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
    }

    td div input {
        background-color: rgba(35, 216, 57, 0.21);
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
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
        display: block;
        position: relative;
    }


    .pv_motion_create_ok_button {
        padding: 5px 15px;
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
            display: block;
            position: inherit;
            float: left;
            padding: 3px;
            margin-left: 5px;
            margin-bottom: 5px;
        }

        .pv_motion_create_ok_button {
            padding: 0px 7px;
            background-color: #48616121;
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
            /*padding: 15px 10px;*/
        }
    }

    .modal_left {
        width: 90%;
        margin: 0px 5%;
        margin-bottom: 5%;
    }


</style>


<?php $form = ActiveForm::begin(
    [
        'id' => 'project-form',
        //'action' => ['sklad/update'],
        'method' => 'post',

        'options' => [
            //'data-pjax' => 1,
            'autocomplete' => 'off',

        ],

        'enableAjaxValidation' => false,

        'enableClientValidation' => true,
        'validateOnChange' => true,
        'validateOnSubmit' => true,
        'validateOnBlur' => true,


    ]);
?>


<div id="tz1">
    <div class="pv_motion_create_right_center" style="overflow: auto;">

        <div class="tree_land3"><?php

            if (isset($new_doc->tz_id) && !empty($new_doc->tz_id)) {

                Modal::begin(
                    [
                        'header' => '<h3>ТехЗадание № ' . $tz_head['id'] . '</h3> <h3>Тема:  ' . $tz_head['name_tz'] . ' </h3>',
                        //                    'header' => false,
                        'options' => [
                            'id' => 'kartik-modal1',
                            //'tabindex' => true // important for Select2 to work properly
                        ],
                        'toggleButton' => [
                            'label' => 'ТехЗадание №' . $new_doc->tz_id,
                            'class' => 'btn btn-default'
                            // btn-primary'
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
                            <?= $tz_head['wh_cred_top_name'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Количество ПЕ
                        </td>
                        <td>
                            <?= $tz_head['multi_tz'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Дорожная карта
                        </td>
                        <td>
                            <?= $tz_head['street_map'] ?>
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
                            <?= $tz_head['dt_create'] ?>
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
                            <?= $tz_head['dt_deadline'] ?>
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
            echo "<br>склад " . $sklad;
            ?><?php
            echo "<br><div class='nakl1'> Накладная № " . $new_doc->id . "</div>";
            ?>


            <?php

            ///////////
            if (empty($new_doc['array_bus'])) {
                $sum_bus = 0;
            } else {
                $sum_bus = count($new_doc['array_bus']);
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

                echo $form->field($new_doc, 'array_bus')->widget(
                    Select2::className()
                    , [

                    'theme' => Select2::THEME_BOOTSTRAP,
                    // THEME_DEFAULT, //THEME_CLASSIC, //THEME_BOOTSTRAP,
                    'size' => Select2::SMALL,
                    //LARGE,

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

            // Проверка Воспроизведения ИЗ миллисекунд в нормальный формат дат
            // $new_doc->dt_create = $new_doc->getDtCreateText();


            $new_doc->dt_create =
                date('d.m.Y H:i:s', strtotime($new_doc['dt_create']));

            echo $form->field($new_doc, 'dt_create')
                ->widget(
                    DateTimePicker::className(), [
                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,
                    //TYPE_INLINE,
                    'size' => 'lg',
                    'convertFormat' => false,
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...',
                    ],

                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy HH:ii:ss',
                        /// не трогать -это влияет на выбор "СЕГОДНЯ"
                        'pickerPosition' => 'bottom-left',

                        'autoclose' => true,
                        'weekStart' => 1,
                        //неделя начинается с понедельника
                        // 'startDate' => $date_now,
                        'todayBtn' => true,
                        //снизу кнопка "сегодня"
                    ],
                ]);
            ?>


            <?php
            //ddd($new_doc['sklad_vid_oper']);

            echo $form->field($new_doc, 'sklad_vid_oper')
                ->dropDownList(
                    [
                        //'1'=>'Инвентаризация',
                        '2' => 'Приходная накладная',
                        '3' => 'Расходная накладная',
                    ],
                    [
                        'prompt' => 'Выбор ...',
                        'data-confirm' => Yii::t(
                            'yii',
                            '<b>Вы точно хотите: </b><br>
                                    Изменить НАПРАВЛЕНИЕ этой НАКЛАДНОЙ ?'),
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
            $filter_whtop = ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');


            //////////
            echo $form->field($new_doc, 'wh_debet_top')->dropDownList(
                $filter_whtop,
                [
                    'prompt' => 'Выбор ...',
                    //'id' => 'wh_debet_top',

                ]
            )->label("Компания-отправитель");


            echo $form->field($new_doc, 'wh_debet_element')->widget(
                Select2::className()
                , [
                'name' => 'wh_debet_element',
                'data' => ArrayHelper::map(
                    Sprwhelement::find()
                        ->where(['parent_id' => (integer)$new_doc->wh_debet_top])
                        ->all(), 'id', 'name'),
                'options' => [
                    'placeholder' => 'Список автобусов ...',
                ],
                //                    'theme' => Select2::THEME_BOOTSTRAP, // THEME_DEFAULT, //THEME_CLASSIC, //THEME_BOOTSTRAP,
                'size' => Select2::SMALL,
                //LARGE,
            ]);


            //                echo $form->field($new_doc, 'wh_debet_element')->dropDownList(
            //                        ArrayHelper::map(
            //                                Sprwhelement::find()
            //                        ->where(['parent_id'=>(integer)$new_doc->wh_debet_top])
            //                        ->all(),'id','name'),
            //                    [
            //                        'prompt' => 'Выбор  ...',
            //                        //'id' => 'wh_debet_element',
            //                    ]
            //                );

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
            echo $form->field($new_doc, 'wh_destination')->dropDownList(
                $filter_whtop,
                [
                    'prompt' => 'Выбор склада ...',
                ]
            )->label("Компания-получатель");


            ///////////////////

            echo $form->field($new_doc, 'wh_destination_element')->widget(
                Select2::className()
                , [
                'name' => 'st',
                'data' => ArrayHelper::map(
                    Sprwhelement::find()
                        ->where(['parent_id' => (integer)$new_doc->wh_destination])
                        ->all(), 'id', 'name'),
                'theme' => Select2::THEME_BOOTSTRAP,
                'size' => Select2::SMALL,
                //LARGE,
            ]);

            echo "<div class='close_hid'>";
            echo $form->field($new_doc, 'wh_destination_name')
                ->hiddenInput()->label(false);
            echo $form->field($new_doc, 'wh_destination_element_name')
                ->hiddenInput()->label(false);
            echo "</div>";

            ?>


        </div>


        <?php /////
        ///**/            $user=Yii::$app->user->getIdentity();
        //            if ( isset($user['username']) && ( $user['username']!='VICTOR5') ) {
        ?>

        <div class="tree_land" style="background-color: #ffa50052;">
            <?php
            echo $form->field($new_doc, 'tx')->textarea(
                [
                    'style' => "margin: 0px; height: 93px; font-size: 15px;",
                ]);
            ?>
        </div>

        <!--        --><?php //} ?>

        <?php ///
        //            $user=Yii::$app->user->getIdentity();
        //            if ( isset($user['username']) && ( $user['username']==='VICTOR5') ) {
        ?>


        <div class="tree_land" style="background-color: #ffa50052;">

            <?php
            //////////
            echo $form->field($new_doc, 'wh_dalee')->dropDownList(
                $filter_whtop,
                [
                    'prompt' => 'Выбор склада ...',
                ]
            );

            ///////////////////
            echo $form->field($new_doc, 'wh_dalee_element')->widget(
                Select2::className()
                , [
                    'name' => 'st',
                    'data' => ArrayHelper::map(
                        Sprwhelement::find()
                            ->where(['parent_id' => (integer)$new_doc->wh_dalee])
                            ->all(), 'id', 'name'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'size' => Select2::SMALL,
                    //LARGE,
                ]
            );

            ?>


        </div>
        <!--            --><?php //} ?>

    </div>


    <div class="pv_motion_create_ok_button">


        <?php
        // $alert_mess  MESSAGE
        if (isset($alert_mess) && !empty($alert_mess)) {
            echo Alert::widget(
                [
                    'options' => [
                        'class' => 'alert_save',
                        'animation' => "slide-from-top",
                    ],
                    'body' => $alert_mess,
                ]);
        }
        ?>



        <?php
        echo Html::a(
            'Выход',
            ['/sklad/in?otbor=' . $sklad],
            [
                'onclick'=>"window.history.back();",
                'class' => 'btn btn-warning',
            ]);
        ?>


        <?php
        echo Html::submitButton(
            'Сохранить изменения в накладной',
            [
                'class' => 'btn btn-success',
                'data-confirm' => Yii::t(
                    'yii',
                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'),
            ]
        );
        ?>




        <?php
        /////////////////
        $user = Yii::$app->user->getIdentity();
        /////////////////

        if (isset($user['username']) && (
                $user['username'] === 'VICTOR5' ||
                $user['username'] === 'NURLAN'
            )) {

            //ddd( $user );

            Modal::begin(
                [
                    'header' => '<h2>Номер накладной</h2>',
                    'toggleButton' => [
                        'label' => 'Добавить накладную',
                        'tag' => 'button',
                        'class' => 'btn btn-primary',
                    ],
                    //'footer' => 'Низ окна',
                ]);

            ?>

            <!--  --><?php//= $form->field($new_doc, 'add_button')->textInput(['autofocus' => true])
            ?>


            <?= $form->field($new_doc, 'add_button')->textInput(['autofocus' => true])->label(false); ?>


            <div class="form-group">
                <?= Html::submitButton(
                    'Добавить',
                    [
                        'class' => 'btn btn-primary',
                        'name' => 'contact-button',
                        'value' => 'add_button',
                    ]) ?>

            </div>


            <?php Modal::end();
        }
        ///////////////////////////////////?>


        <?php //////////////////////////////////////
        $user = Yii::$app->user->getIdentity();

        if (isset($user['group_id'])) {
            if (
                (int)$user['group_id'] == 70 ||
                (int)$user['group_id'] == 71 ||
                $user['username'] == 'VICTOR5'
            ) {


                Modal::begin(
                    [
                        'header' => '<h2>Номера строк</h2>',

                        'toggleButton' => [
                            'label' => 'Удалить строки',
                            'tag' => 'button',
                            'class' => 'btn btn-primary',
                        ],

                        //'footer' => 'Низ окна',
                    ]);
                ?>


                <div class="name_ss">
                    <div class="name_nn">
                        Амортизация:
                    </div>
                    <div class="name_s1" style="width: 45%">
                        <div class="name_s">
                            c:
                        </div>
                        <?= $form->field($new_doc, 'erase_array[0][0]')->textInput(
                            [
                                'placeholder' => '(1)',
                                'autofocus' => true,
                                'style' => 'width: 120px',
                            ])->label(false); ?>
                    </div>
                    <div class="name_s1" style="width: 45%">
                        <div class="name_s">
                            по:
                        </div>
                        <?= $form->field($new_doc, 'erase_array[0][1]')
                            ->textInput(
                                [
                                    'placeholder' => '(' . $erase_array[0] . ')',
                                    'autofocus' => true,
                                    'style' => 'width: 120px',
                                ])
                            ->label(false); ?>
                    </div>
                </div>


                <div class="name_ss">
                    <div class="name_nn">
                        Списание:
                    </div>
                    <div class="name_s1" style="width: 45%">
                        <div class="name_s">
                            с:
                        </div>
                        <?= $form->field($new_doc, 'erase_array[1][0]')->textInput(
                            [
                                'placeholder' => '(1)',
                                'autofocus' => true,
                                'style' => 'width: 120px',
                            ])->label(false); ?>
                    </div>
                    <div class="name_s1" style="width: 45%">
                        <div class="name_s">
                            по:
                        </div>
                        <?= $form->field($new_doc, 'erase_array[1][1]')->textInput(
                            [
                                'placeholder' => '(' . $erase_array[1] . ')',
                                'autofocus' => true,
                                'style' => 'width: 120px',
                            ])->label(false); ?>
                    </div>
                </div>

                <div class="name_ss">
                    <div class="name_nn">
                        Расходные материалы:
                    </div>
                    <div class="name_s1" style="width: 45%">
                        <div class="name_s">
                            c:
                        </div>
                        <?= $form->field($new_doc, 'erase_array[2][0]')->textInput(
                            [
                                'placeholder' => '(1)',
                                'autofocus' => true,
                                'style' => 'width: 120px',
                            ])->label(false); ?>

                    </div>
                    <div class="name_s1" style="width: 45%">
                        <div class="name_s">
                            по:
                        </div>
                        <?= $form->field($new_doc, 'erase_array[2][1]')->textInput(
                            [
                                'placeholder' => '(' . $erase_array[2] . ')',
                                'autofocus' => true,
                                'style' => 'width: 120px',
                            ])->label(false); ?>
                    </div>
                </div>


                <div class="form-group">
                    <?= Html::submitButton(
                        'Удалить',
                        [
                            'class' => 'btn btn-primary',
                            'name' => 'contact-button',
                            'value' => 'erase_button',
                        ])
                    ?>
                </div>


                <?php Modal::end();
            }
        }///////////////////////////////////?>


        <?php
        $user = Yii::$app->user->getIdentity();

        //ddd($user);

        if (isset($user['group_id'])) {
            if (
                (int)$user['group_id'] == 100 ||
                (int)$user['group_id'] == 70 ||
                (int)$user['group_id'] == 71
            ) {

                //////////////////////////////////////
                ///
                /// МОДАЛЬНОЕ ОКНО. Добавляем в ПУЛ ШТРИХКОДОВ для АСУОП
                ///

                Modal::begin(
                    [
                        'header' => '<h2>Добавляем в ПУЛ ШТРИХКОДОВ для АСУОП</h2>',
                        'toggleButton' => [
                            'label' => 'Копипаст ПУЛ Штрихкодов',
                            'tag' => 'button',
                            'class' => 'btn btn-primary',
                        ],
                        //'footer' => 'Низ окна',
                    ]);

                ?>


                <?php

                //			    public $pool_copypast_id;
                //			    public $pool_copypast_fufer;

                echo $form->field($new_doc, 'pool_copypast_id')->widget(
                    Select2::className()
                    , [
                    'name' => 'st',
                    'data' => $spr_globam_element,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    //'size'  => Select2::SMALL,
                    //LARGE,
                ])->label('Выбрать наименование');
                ?>
                <br>

                <?= $form
                    ->field($new_doc, 'pool_copypast_fufer')
                    ->textarea(
                        [
                            'autofocus' => true,
                            'style' => 'height: 300px;font-size: 10px;',
                            'placeholder' => "ШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\nШТРИХКОД ( \ n)\n",
                        ])
                    ->label('В это поле пастим');
                ?>


                <div class="form-group">
                    <?= Html::submitButton(
                        'Залить копипаст НОВЫЕ ПОЗИЦИИ ',
                        [
                            'class' => 'btn btn-primary',
                            'name' => 'contact-button',
                            'value' => 'add_new_pool_copypast_fufer',
                        ]) ?>

                </div>


                <?php Modal::end();
            }
        }
        ///////////////////////////////////?>



        <?php

        echo Html::a(
            'Копия с новым номером',
            ['/sklad/copycard_from_origin?id=' . $new_doc->id],

            [
                'data-confirm' => Yii::t(
                    'yii',
                    '<b>Вы точно хотите: </b><br>
                            Создать КОПИЮ этой НАКЛАДНОЙ ?'),

                'id' => "to_print",
                //'target' => "_blank",
                'class' => 'btn  btn-info'
                //btn-primary'   //btn-success'
            ]);
        ?>


        <!--            --><?php
        //                if ( isset($user['username']) && (
        //                    $user['username']==='VICTOR5'   ||
        //                    $user['username']==='NURLAN'
        //                ))
        //                {
        //                ?>

        <?php

        echo Html::a(
            'Зеленая НАКЛАДНАЯ',
            ['/sklad/html_pdf_green/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default',
            ]);
        ?>

        <!--            --><?php
        //            echo Html::a('Зеленая НАКЛАДНАЯ (BarCode)',
        //                ['/sklad/html_pdf_green_barcode/?id=' . $new_doc->id],
        //                [
        //                    'id' => "to_print",
        //                    'target' => "_blank",
        //                    'class' => 'btn btn-default'
        //                ]);
        //            ?>


        <?php
        echo Html::a(
            'НАКЛАДНАЯ Жанель Монтаж',
            ['/sklad/html_pdf_janel_montage/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default',
            ]);
        ?>

        <?php
        echo Html::a(
            'НАКЛАДНАЯ Жанель Демонтаж',
            ['/sklad/html_pdf_janel_demontage/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default',
            ]);
        ?>

        <!--            --><?php
        //            echo Html::a('АСУОП',
        //                ['/sklad/html_pdf/?id=' . $new_doc->id],
        //                [
        //                    'id' => "to_print",
        //                    'target' => "_blank",
        //                    'class' => 'btn btn-default'
        //                ]);
        //            ?>


        <?php

        echo Html::a(
            'Накладная Резервный Фонд',
            ['/sklad/html_reserv_fond/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default',
            ]);

        ?>

        <!--        --><?php //} ?>

        <?php
        // 65 - Санжар
        //                if ( Yii::$app->user->identity->group_id == 65 ){
        echo Html::a(
            'Акт Монтажа',
            ['/sklad/html_akt_mont/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default',
            ]);
        //                    }
        ?>

        <?php
        // 65 - Санжар
        //                if ( Yii::$app->user->identity->group_id == 65 ){

        echo Html::a(
            'Акт Демонтажа',
            ['/sklad/html_akt_demont/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default',
            ]);

        //                }
        ?>


    </div>


    <div class="pv_motion_create_right">
        <?php
        $xx = 0;


        echo $form->field($new_doc, 'array_tk_amort')->widget(
            MultipleInput::className(), [

            'id' => 'my_id2',
            'theme' => 'default',
            'allowEmptyList' => false,
            'min' => 0,
            // should be at least 2 rows
            'enableGuessTitle' => false,
            'sortable' => true,
            'addButtonPosition' => MultipleInput::POS_HEADER,
            // show add button in the header

            'rowOptions' => function ($model, $index, $context) {
                $text_array = ['id' => 'row{multiple_index_my_id2}'];
                //ddd($model->take_it=='1');

                if (isset($model['take_it']) && $model['take_it'] == '1') {
                    $text_array = [
                        'id' => 'row{multiple_index_my_id2}',
                        'class' => 'redstyle',
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

                //
                //				    [
                //					    'name'         => 'wh_tk_amort',
                //					    'type'         => 'dropDownList',
                //					    'title'        => 'Группа',
                //					    'defaultValue' => 0,
                //					    'items'        => ArrayHelper::map(
                //						    post_spr_globam::find()->orderBy( 'name' )->all(),
                //						    'id', 'name' ),
                //
                //					    'options' => [
                //						    'prompt' => 'Выбор ...',
                //						    'style'  => 'padding: 0px;overflow: auto;',
                //						    'id'     => 'subcat211-{multiple_index_my_id2}',
                //
                //						    'onchange' => <<< JS
                //$.post("listamort?id=" + $(this).val(), function(data){
                //    //$("select#subcat22-{multiple_index_my_id2}").html(data);
                //    $("select#sklad-array_tk_amort-{multiple_index_my_id2}-wh_tk_element").html(data);
                //});
                //JS
                //						    ,
                //					    ],
                //				    ],
                //
                //
                //				    [
                //					    'name'  => 'wh_tk_element',
                //					    'title' => 'Компонент',
                //					    'type'  => Select2::className(),
                //
                //					    'options' => [
                //						    'options' => [
                //							    'id'       => 'subcat24442-{multiple_index_my_id2}',
                //							    //'placeholder' => 'Поиск  ...',
                //							    'prompt'   => 'Выбор ...',
                //							    'style'    => 'padding: 0px;min-width:1vw;overflow: auto;',
                //							    'onchange' => <<< JS
                //$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
                //    $("select#subcat211-{multiple_index_my_id2}").val(data);
                //});
                //JS
                //							    ,
                //						    ],
                //
                //						    'data'    => ArrayHelper::map(
                //							    Spr_globam_element::find()->orderBy( 'name' )->all(),
                //							    'id', 'name' ),
                //
                //						    'pluginOptions' => [
                //							    'allowClear'         => true,
                //							    'maximumInputLength' => 10,
                //							    //		'minimumInputLength' => 30,
                //							    'language'           => 'ru',
                //						    ],
                //
                //					    ],
                //				    ],


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
                        'prompt' => 'Выбор ...',
                        'style' => 'padding: 0px;min-width:1vw;overflow: auto;',
                        'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});                     
JS
                        ,
                    ],

                    'items' =>
                        ArrayHelper::map(
                            Spr_globam_element::find()->orderBy('name')->all(),
                            'id', 'name')
                    ,

                ],

                //					[
                //						'name'    => 'intelligent',
                //						'title'   => 'BC',
                //						'options' => [
                //							'id'    => 'subcat22-{multiple_index_my_id2}',
                //							'style' => 'padding: 0px;width:3vw;overflow: auto;',
                //						],
                //						'value'   => function( $data ) {
                //							if($data[ 'intelligent' ] == 1)
                //							{
                //								return "Да";
                //							}
                //
                //							return "Нет";
                //						},
                //					],

                [
                    'name' => 'ed_izmer',
                    'title' => 'Ед. изм',
                    'type' => 'dropDownList',
                    'defaultValue' => 1,
                    'options' => [
                        'id' => 'subcat-ed_izm-{multiple_index_my_id2}',
                        'prompt' => 'Выбор ...',
                        'style' => 'min-width:1vw;width: 75px;overflow: auto;',
                    ],

                    'items' => ArrayHelper::map(Spr_things::find()->all(), 'id', 'name'),
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
                        'style' => 'min-width:5vw;width: 60px;overflow: auto;',
                    ],
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

                        'type' => 'input',
                        //'pattern' => '(1[0-2]|0[1-9])\/(1[5-9]|2\d)',
                        //'pattern' => '[\d]{12}',
                        'pattern' => '[0-9]{5,25}',
                        //'placeholder' => "Штрих-код отсутствует" ,
                        'placeholder' => "Штрих-код",
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
        data = data.replace(/[^\w*]/g,'');
        data = data.replace(/[^.]*/g,'');
        
        //alert(data);
        
        $(this).val(data);
        
        var bar_code=$(this).val();


                //// Обрезка для CVB24
                //Воходжение строки с позиции
                var otstup = bar_code.indexOf('19600') ;                
                var bar_code_obrez = bar_code;
                                
                if ( otstup >= 0 ){                
                    bar_code=bar_code_obrez.substr( otstup, 11 );
                    $('#code5-{multiple_index_my_id2}').val( bar_code_obrez.substr( otstup, 11 ));
                       
                    //alert('otstup ='+otstup);                 
                }
                
                            
        

        //// Заполнение полученой строки значениями
        
        //Если поле-3 имеет значение
        if( pole3 ){    
            //alert('Если поле-3 имеет значение');
            return false;
        }
        else{
            //bar_code                      
            
            if(bar_code){
                $.post("id_amort_from_barcode?bar_code=" + bar_code, 
                function(data_id){            
                    $("select#subcat22-{multiple_index_my_id2}").val(data_id);
    
                        //parent_id                
                        $.post("id_group_amort_from_id?id=" + data_id, 
                        function(data_parent_id){
                            $("select#subcat211-{multiple_index_my_id2}").val(data_parent_id);
                            
                            // 1 штука
                            $("#sklad-array_tk_amort-{multiple_index_my_id2}-ed_izmer_num").val(1);
    
                        });   
                
                
    
                });          
            }         
    
        }




                //Добавил новую строку
                $('#my_id2').multipleInput('add', 1);        



            //// переход на строку ниже
            var str='{multiple_index_my_id2}';
            var nextrow = (str*1)+1 ;
            $('#code5-'+nextrow).focus();
}


JS
                        ,
                    ],
                ],

            ],
        ])->label('Амортизация', ['class' => 'my_label']);;
        ?>
    </div>


    <div class="pv_motion_create_right">
        <?php

        //////////////////////
        echo $form->field($new_doc, 'array_tk')->widget(
            MultipleInput::className(), [
            'id' => 'my_id',
            'theme' => 'default',
            'rowOptions' => function ($model, $index, $context) {
                $text_array = ['id' => 'row{multiple_index_my_id}'];
                //ddd($model->take_it=='1');

                if (isset($model['take_it']) && $model['take_it'] == '1') {
                    $text_array = [
                        'id' => 'row{multiple_index_my_id}',
                        'class' => 'redstyle',
                    ];

                }

                return $text_array;
            },

            //            'rowOptions' => [
            //                'id' => 'row{multiple_index_my_id}',
            //            ],

            //  'max'    => 50,

            'min' => 0,

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
                        'id' => 'subcat11-{multiple_index_my_id}',
                        'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    //console.log(data);
    //$("select#subcat-{multiple_index_my_id}").html(data);
    $("select#sklad-array_tk-{multiple_index_my_id}-wh_tk_element").html(data);
    // sklad-array_tk-0-wh_tk_element
    //alert(data);
});
JS
                        ,

                    ],

                ],

                //					[
                //						'name'         => 'wh_tk_element',
                //						'type'         => 'dropDownList',
                //						'title'        => 'Компонент',
                //						'defaultValue' => [],
                //						'items'        =>
                //							ArrayHelper::map(
                //								Spr_glob_element::find()
                //								                ->orderBy( 'name' )->all(),
                //								'id', 'name' )
                //						,
                //						'options'      => [
                //							'prompt' => '...',
                //							'id'     => 'subcat-{multiple_index_my_id}',
                //
                //							'onchange' => <<< JS
                //
                //$.post("list_parent_id?id=" + $(this).val(), function(data){
                //    $("select#subcat11-{multiple_index_my_id}").val(data);
                //});
                //
                //$.post("list_ed_izm?id=" + $(this).val(), function(data){
                ////alert(data);
                //    $("select#sklad-array_tk-{multiple_index_my_id}-ed_izmer").val(data);
                //});
                //JS
                //							,
                //						],
                //					],


                [
                    'title' => 'Компонент',
                    'name' => 'wh_tk_element',
                    'type' => Select2::className(),
                    'options' => [
                        'options' => [
                            'placeholder' => 'Поиск  ...',
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
                        ],
                        'data' => ArrayHelper::map(
                            Spr_glob_element::find()->orderBy('name')->all(),
                            'id', 'name'),

                        'pluginOptions' => [
                            'allowClear' => true,
                            'maximumInputLength' => 10,
                            //		'minimumInputLength' => 30,
                            'language' => 'ru',
                        ],

                    ],
                ],


                [
                    'name' => 'ed_izmer',
                    'title' => 'Ед. изм',
                    'type' => 'dropDownList',
                    'defaultValue' => 1,
                    'options' => [
                        'style' => 'padding: 0px;min-width:75px;overflow: auto;',
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
                        'id' => 'zero-{multiple_index_my_id}',
                        'type' => 'number',
                        'class' => 'input-priority',
                        'style' => 'max-width: 70px;overflow: auto;',
                        'step' => '0.1',

                    ],
                ],


            ],
        ])->label('Списание', ['class' => 'my_label']);;
        ?>
    </div>


    <div class="pv_motion_create_right">
        <?php

        /////////////////........
        echo $form->field($new_doc, 'array_casual')->widget(
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
    $("select#sklad-array_casual-{multiple_index_my_casual}-wh_tk_element").html(data);
    //sklad-array_casual-0-wh_tk_element
});
JS
                        ,

                    ],
                ],

                //				    [
                //					    'name'         => 'wh_tk_element',
                //					    'type'         => 'dropDownList',
                //					    'title'        => 'Компонент',
                //					    'defaultValue' => [],
                //					    'items'        =>
                //						    ArrayHelper::map(
                //							    Spr_glob_element::find()
                //							                    ->orderBy( 'name' )->all(),
                //							    'id', 'name' )
                //					    ,
                //					    'options'      => [
                //						    'prompt' => '...',
                //						    'id'     => 'subcat_casual-{multiple_index_my_casual}',
                //
                //						    'onchange' => <<< JS
                //$.post("list_parent_id?id=" + $(this).val(), function(data){
                //// alert(data);
                //
                //    $("select#subcat11_casual-{multiple_index_my_casual}").val(data);
                //});
                //
                //$.post("list_ed_izm?id=" + $(this).val(), function(data){
                ////alert(data);
                //    $("select#sklad-array_casual-{multiple_index_my_casual}-ed_izmer").val(data);
                //});
                //JS
                //						    ,
                //					    ],
                //				    ],


                [
                    'title' => 'Компонент',
                    'name' => 'wh_tk_element',
                    'type' => Select2::className(),
                    'options' => [
                        'options' => [
                            'placeholder' => 'Поиск  ...',
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
                        'data' => ArrayHelper::map(
                            Spr_glob_element::find()->orderBy('name')->all(),
                            'id', 'name'),

                        'pluginOptions' => [
                            'allowClear' => true,
                            'maximumInputLength' => 10,
                            //		'minimumInputLength' => 30,
                            'language' => 'ru',
                        ],

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







//$(document).on('keypress',function(e) {
//
//    if(e.which == 13) {
//        
//            var xx=e;
//            //console.log(xx.target.value);
//            console.log(xx.target);
//                        
//    
//            //alert('Нажал - enter!');
//            $('#sklad-array_tk_amort-6-bar_code').focus();
//            
//           // e.preventDefault(false);
//      return false;
//    }
//});



//var data = $(this).val();
//$("select#code5-{multiple_index_my_id2}").html( data );
//
//     if (event.keyCode==13){
//                          DOM_VK_ENTER
//
//            var str='{multiple_index_my_id2}';
//            var nextrow = ((str*1)+1);
//            $('#code5-'+nextrow).focus();
//
//                console.log(data);
//                alert(data);
//     }
//

//$(document).on('keypress',function(e) {
//    if(e.which == 13) {
//            alert('Нажал - enter!');
////      e.preventDefault();
//      return false;
//    }
//});




//function myFunction() {
//  var x = document.getElementById("fname").value;
//  document.getElementById("demo").innerHTML = x;
//}

     

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

////////////////// VID - sklad-sklad_vid_oper
//$('#sklad-sklad_vid_oper').change(function() {    
//     var  number2 = $('#sklad-sklad_vid_oper').val();
//     var  text2   = $('#sklad-sklad_vid_oper>option[value='+number2+']').text();
//      $('#sklad-sklad_vid_oper_name').val(text2);
//});
//


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

////////////////// Debitor - element sklad-wh_debet_element
//$('#sklad-wh_debet_element').change(function() {    
//     var  number2 = $('#sklad-wh_debet_element').val();
//     var  text2   = $('#sklad-wh_debet_element>option[value='+number2+']').text();
//      $('#sklad-wh_debet_element_name').val(text2);
//});


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





//////////////////// Creditor
$('#sklad-wh_dalee').change(function() {    
    var  number = $(this).val();
    
   
    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},				
			success: function(res) {		 
		    // $('#sklad-wh_dalee_element').html('');
		    $('#sklad-wh_dalee_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('JS.sklad-wh_dalee '+ res );
					}
    } );
   
});



////////////
$('#go_home').click(function() {    
    window.history.back();  
})

//     var  number = $('#sklad-sklad_vid_oper').val();
//     var  text   = $('#sklad-sklad_vid_oper>option[value='+number+']').text();
//     $('#sklad-sklad_vid_oper_name').val(text);
//
//     var  number = $('#sklad-wh_debet_top').val();
//     var  text   = $('#sklad-wh_debet_top>option[value='+number+']').text();
//      $('#sklad-wh_debet_name').val(text);
//     
//     var  number = $('#sklad-wh_destination').val();
//     var  text   = $('#sklad-wh_destination>option[value='+number+']').text();
//      $('#sklad-wh_destination_name').val(text);     
//

     // var  number = $('#sklad-wh_debet_element').val();
     // var  text   = $('#sklad-wh_debet_element>option[value='+number+']').text();
     //  $('#sklad-wh_debet_element_name').val(text);
      
     // var  number2 = $('#sklad-wh_destination_element').val();     
     // var  text2   = $('#sklad-wh_destination_element>option[value='+number2+']').text();
     //  $('#sklad-wh_destination_element_name').val(text2);


JS;

$this->registerJs($script, View::POS_READY);
?>

