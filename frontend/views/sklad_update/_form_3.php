<?php

use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_things;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


$zam_array = [
    '2' => 'Приходная накладная',
    '3' => 'Расходная накладная',
];

if (isset($model->sklad_vid_oper) && ((int)$model->sklad_vid_oper == 2 || (int)$model->sklad_vid_oper == 3)) {
    $this->title = $zam_array[$model->sklad_vid_oper] . ' № ' . $model->id; // Замена
}

?>

<style>
    .list-cell__wh_tk_element {
        min-width: 700px;
    }

    @media (max-width: 1390px) {
        .list-cell__wh_tk_element {
            min-width: 300px;
        }
    }

    @media (max-width: 790px) {
        .list-cell__wh_tk_element {
            min-width: 300px;
        }

    }


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

    .pv_motion_create_right_center {
        width: 100%;
        min-width: 240px;
        margin: 0px;
        padding: 3px 3px;
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

        .wrap > .container22 {
            margin-top: 11px;
        }

        .pv_motion_create_right_center {
            width: 100%;
            min-width: 270px;
            display: block;
            position: inherit;
            float: left;
            padding: 3px;
            margin-left: 5px;
            margin-bottom: 15px;
        }

        .pv_motion_create_ok_button {
            padding: 15px 7px;
            background-color: #48616121;
        }


    }


    @media (min-width: 605px) {
        .pv_motion_create_right_center {
            padding: 3px 3px;
        }
    }


    .modal_left {
        width: 90%;
        margin: 0px 5%;
        margin-bottom: 5%;
    }


</style>
