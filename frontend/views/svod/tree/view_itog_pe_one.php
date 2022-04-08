<?php

use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<style>
    .pv_motion_create_right_center, .pv_motion_create_right {
        padding: 3px 0%;
        margin: 0px;
    }

    .pv_motion_create_right {
        overflow: hidden;
        max-width: min-content;
        min-width: max-content;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 10px;
    }

    .pv_motion_create_right > div {
        width: 75%;
        margin: 0px;
        /*width: max-content;*/
        padding: 0px 8px;
    }

    .itogo {
        width: 300px;
        height: 20px;
        line-height: 1.2;
        padding: 10px 10px;
        background-color: rgba(153, 151, 156, 0.23);
        text-align: right;
    }

    .list-cell__button {
        display: none;
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


    /*@media (max-width: 1100px) {*/


    /*    !*.wrap {*!*/
    /*    !*    padding: 10px 0px;*!*/
    /*    !*    margin: 1px 0px;*!*/
    /*    !*}*!*/
    /*    .tree_datetime {*/
    /*        min-width: 95%;*/
    /*    }*/

    /*    .tree_land, .tree_land_left, .tree_land_right {*/
    /*        overflow: hidden;*/
    /*        float: left;*/
    /*        height: auto;*/
    /*        padding: 10px 3px;*/
    /*        margin: 5px;*/
    /*    }*/

    /*    #res_window {*/
    /*        display: block;*/
    /*        position: relative;*/
    /*        right: 0px;*/
    /*        bottom: 10px;*/
    /*        width: 100%;*/
    /*        min-width: 400px;*/
    /*        padding: 20px 0px;*/
    /*        height: 200px;*/
    /*    }*/

    /*    !*.pv_motion_create_right {*!*/
    /*    !*    overflow: scroll;*!*/
    /*    !*    padding: 10px 0px;*!*/
    /*    !*    margin: 10px 1px;*!*/
    /*    !*}*!*/
    /*}*/


</style>


<?php $form = ActiveForm::begin(
    [
        //        'action' => ['/svod/tree_print'],

        'id' => 'project-form',
        'method' => 'POST',
        'options' => [
            'autocomplete' => 'off',
        ],

        'enableClientValidation' => true,
        //  'enableAjaxValidation' => false,
        //  'validateOnSubmit' => true,
    ]);
?>

<div class="pv_motion_create_right">
    <h1><?= $wh_name ?> ( до <?= ($last_time_str ? $last_time_str : '') ?>)</h1>
    <div class="excel_buttons">

        <?= Html::a(
            'EXCEL. Остатки на дату. По одному ПЕ',
            [
                '/svod/tree_print_pe_one',
//                '/svod/one_pe_svod',
                'id' => $id,
                'date' => $last_time,
                'print' => 1,
            ],
            [
                'class' => 'btn btn-default',
            ]
        ) ?>

    </div>


    <?php
    echo $form->field($model, 'array_tk_amort')->widget(
        MultipleInput::className(), [
            'id' => 'my_id2',
            'theme' => MultipleInput::THEME_DEFAULT, //THEME_BS,
            'allowEmptyList' => true,
            'min' => 0,
            //            'addButtonPosition' => MultipleInput::POS_FOOTER,
            'removeButtonOptions' => ['style' => 'display:none'],


            'columns' => [
                [
                    'name' => 'calc',
                    'title' => '№',
                    'value' => function ($data, $key) {
                        return ++$key['index'];
                    },

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'max-width: 50px;text-align: right;padding-right: 1px;',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ],
                ],

                [
                    'title' => 'Группа',
                    'name' => 'wh_tk_amort_name',
                    'value' => 'wh_tk_element',
                    'options' => [
                        'style' => 'width: 50px;text-align: center',
                        'readonly' => 'readonly',
                    ],
                ],

                [
                    'title' => 'Компонент',
                    'name' => 'wh_tk_element_name',
                    'value' => 'wh_tk_element',
                    'options' => [
                        'style' => 'min-width: 550px;text-align: left;overflow: scroll;',
                        'readonly' => 'readonly',
                    ],
                ],

                [
                    'title' => 'ШтрихКод',
                    'name' => 'bar_code',
                    'value' => 'bar_code',
                    'options' => [
                        'style' => 'width:110px;text-align: center',
                        'readonly' => 'readonly',
                    ],
                ],

//                [
//                    'title' => 'Ед. изм',
//                    'name' => 'ed_izmer_name',
//                    'value' => 'ed_izmer',
//                    'options' => [
//                        'style' => 'width:70px;text-align: center',
//                        'readonly' => 'readonly',
//                    ],
//                ],

                [
                    'title' => 'Кол-во',
                    'name' => 'ed_izmer_num',
                    'value' => 'ed_izmer_num',
                    'enableError' => true,
                    'options' => [
                        'style' => 'width:70px;text-align: center',
                        'readonly' => 'readonly',
                    ],
                ],
//                [
//                    'title' => 'Штрих-код',
//                    'name' => 'bar_code',
//                ],

///        'wh_tk_amort' => 7
//                'wh_tk_element' => 3
//                'intelligent' => 0
//                'ed_izmer' => 1
//                'ed_izmer_num' => 1
//                'bar_code' => '19600007189'
//                't' => 1609437638
//                'id' => 5
//                'name_wh_tk_amort' => 'АСУОП'
//                'name_wh_tk_element' => 'CVB24 slave '
//                'name_ed_izmer' => 'шт.'
///
            ],
        ]
    )->label(false);
    ?>
    <div class="itogo">Итого: <?= $counter_things ?> </div>
</div>


<?php $form = ActiveForm::end(); ?>
