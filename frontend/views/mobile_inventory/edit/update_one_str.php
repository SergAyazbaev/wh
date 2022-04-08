<?php

//use \kartik\file\FileInput;
use \yii\web\View;
//use \yii\widgets\Pjax;
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;

$this->title = 'РЕДАКТОР';
?>

<style>
    .control-label {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: medium;
    }

    .form-group {
        margin-bottom: 15px;
        width: auto;
        max-width: 203px;
        display: block;
        /*background-color: aqua;*/
        float: left;
        margin-left: 2px;

        padding: 3px;
        font-family: 'fira', monospace;
        font-size: 23px;
    }

    .box_pss {
        width: 60px;
        display: block;
        position: absolute;
        right: 10px;
        top: 34px;
        font-size: 22px;
        margin-left: 0px;
        padding: 1px 0px;
        float: left;
    }

    .box_name {
        display: block;
        position: inherit;
        clear: both;
        width: 250px;
        font-size: 22px;
    }

    div.box_name {
        display: block;
        position: inherit;
        clear: both;
        width: calc(100% - 25px);
        font-size: 22px;
        float: left;
    }

    .vedomost {
        background-color: #0b58a2;
    }


    /*//PHOTO GALERY/*/
    .file-thumbnail-footer {
        /*background-color: #00ff43;*/
        display: none;
    }

    .file-preview {
        padding: 0px;
        width: 100%;
        margin-bottom: 0px;
    }

    input[type="file"] {
        display: block;
        font-size: medium;
        width: 100%;
    }

    .wrap > .container22 {
        margin-top: 0px;
    }

    .wrap {
        width: 500px;
        margin-left: 10%;
        margin-top: 5%;
    }

    @media (max-width: 500px) {
        .wrap {
            width: auto;
            margin-left: 0;
            margin-top: 5%;
        }

    }


    /*//FORM///*/
    .form-control {
        width: 70%;
        padding: 3px;
        font-family: 'fira', monospace;
        font-size: 25px;
    }


    /*/ FUTER NEXT >>//*/
    div._futer {
        margin-top: 30px;
        display: inline-flex;
        position: inherit;
        padding: 5px 0px;
        border: 0.5px solid #a3a3a3;
        border-radius: 10px;
        width: 100%;
    }

    div.past_futer {
        float: left;
        width: calc((98% - 180px));
    }

    div.next_futer {
        float: right;
        width: 140px;
    }

    /*//ALL BUTTONS/*/
    div > .width_all_butt {
        width: 100%;
    }

    div.width_all_butt a {
        margin: 7px;
        width: 94%;
    }


    .mobile-body-content {
        background-color: #96ff0000;
        width: 100%;
        min-height: min-content;
        padding: 0px;
        margin: 0px;
        display: block;
        position: relative;
    }

    a.next_step {
        max-width: 120px;
        display: block;
        position: relative;
        /*float: right;*/
    }

    a.back_step {
        max-width: 120px;
        display: block;
        position: relative;
    }

    ._sklad {
        margin-bottom: 10px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 5px;
        font-size: 22px;
        font-weight: bolder;
        text-align: center;

        display: block;
        /*position: absolute;*/
        float: left;
        width: 100%;
    }

    .ap_pe {
        margin-bottom: 30px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 5px;
        font-size: 22px;
        font-weight: bolder;
        text-align: center;

        display: block;
        /*position: absolute;*/
        float: left;
        width: 100%;
    }

    .ap_pe:empty {
        background-color: #ff1c43;
        display: none;
    }

    .ap_pe > p {
        text-align: center;
        font-size: 23px;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: bolder;
        margin-bottom: 0px;

    }

    .jumbotron > h1 {
        font-size: xx-large;
        padding: 5px;
        margin-top: 10px;
        color: #245580;
    }

    .jumbotron > h2 {
        font-size: x-large;
        margin-bottom: 10px;
    }


    @media (max-width: 700px) {
        .ap_pe {
            top: 110px;
            left: 4px;
        }


        p > a {
            height: 50px;
        }

        .btn {
            margin: 3px 10px;
            font-size: 22px;
        }

        .vedomost {
            background-color: #0b58a2;
            margin: 10% 15%;
        }


    }

    /*//BLOCK/*/
    .help-block {
        display: none;
    }

    .block_bort {
        /*background-color: #dad55e;*/
        background-color: #dad55e5c;
        width: 100%;
        border: 4px solid #a7a35e29;
        border-radius: 10px;
        margin-bottom: 15px;

        display: inline-block;
        position: relative;
        /*float: left;*/
    }

    .block_1, .block7_1 {
        float: left;
        /*width: calc(100% - 85px);*/
        width: calc(100% - 70px);
        /* background-color: red; */
        padding: 2px 2px;
    }

    .block_2, .block7_2 {
        position: absolute;
        right: -4px;
        width: 85px;
        height: 41px;
        background-color: aquamarine;
        padding: 0px 5px;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
    }

    .block_3, .block7_3 {
        display: none;
        float: left;
        width: 100%;
        background-color: #5eda701f;
        padding: 10px 12px;
        margin-top: 30px;
    }

    .check_ok {

    }

    /*//PHOTO*/
    div.block_3_button {
        display: inline-grid;
        position: relative;
        float: right;
    }

    .block_photo, .block_photo1, .block_photo2,
    .block_photo7, .block_photo71, .block_photo72 {
        border: 3px solid #d6d6ae;
        display: block;
        position: inherit;
        padding: 6px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);

    }


    .block_3_button img {
        background-color: #da955ef0;
        color: red;
        border-radius: 20px;
        float: left;
        line-height: 0.2;
        padding: 3px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
    }

    .file-drop-zone {
        border: 0;
    }

    .file-drop-zone-title {
        display: none;
    }

</style>


<h5></h5>


<div id="res"></div>

<div class="site-index">
    <div class="mobile-body-content">


        <!--        --><?php //Pjax::begin(['id' => 'pjax_3']); ?>
        <?php $form = ActiveForm::begin(
            [
                'action' => ['/mobile_inventory/save_edit_inventory'],
//                'action' => ['/mobile_inventory/edit_inventory'],
                'method' => 'POST',
                'options' => [
//                    'data-pjax' => 'pjax_3'
                ],

            ]
        );
        ?>

        <?php
        echo $form->field($model, 'id')->hiddenInput()->label(false);
        echo $form->field($model, 'id_ap')->hiddenInput()->label(false);
        echo $form->field($model, 'id_pe')->hiddenInput()->label(false);
        ?>


        <div class="block_bort">
            <div class="block_1">
                <?php
                echo $form->field($model, 'bort')
                    ->input('text', [
                        'class' => 'box_name',
                        'pattern' => "[0-9]{3,15}",
                        'maxlength' => '20',
                        'readonly' => 'readonly',
                        'placeholder' => 'Бортовой номер...'
                    ]);
                ?>
            </div>
            <div class="block_2">
                <?php
                echo $form->field($model, 'check_bort')->checkbox(['class' => 'check_ok']);
                ?>
            </div>

        </div>


        <div class="block_bort">
            <div class="block_1">
                <?php
                //ddd($model);


                echo $form->field($model, 'gos')
                    ->input('text', [
                        'class' => 'box_name',
                        'maxlength' => '20',
                        'readonly' => 'readonly',
                        'placeholder' => 'Гос номер...',
                    ]);
                ?>
            </div>
            <div class="block_2">
                <?php
                echo $form->field($model, 'check_gos')->checkbox(['class' => 'check_ok']);
                ?>
            </div>

        </div>

        <div class="block_bort">
            <div class="block_1">

                <div class="box_name">
                    <?php
                    echo $findFullArray['child']['short_name'];
                    echo "<br>";

                    echo $form->field($model, "thing_barcode")
                        ->input('text', [
                            'class' => 'box_name',
                            'value' => (!empty($model['thing_barcode']) ? $model['thing_barcode'] : ''),
                            'maxlength' => '20'
                        ]);


                    ?>
                </div>

                <div class="box_pss">
                    <?php

                    //                    ddd($model);


                    echo $form->field($model, 'thing_count')->input('text', [
                        'class' => 'box_pss',
                        'value' => (!empty($model['thing_count']) ? $model['thing_count'] : ''),
                        'maxlength' => '20'
                    ])
                        ->label('шт.');   /// short_name
                    ?>
                </div>
            </div>
            <div class="block_2">
                <?php
                echo $form->field($model, 'thing_check')->checkbox(['class' => 'check_ok']);
                ?>
            </div>

        </div>


        <div class="_futer">
            <div class="past_futer">
                <?php
                echo Html::a(
                    'Закрыть',
                    ['/mobile_inventory/init_table'],
                    [
                        'onclick' => 'window.opener.location.reload();window.close();',
                        'class' => ' back_step btn btn-warning',
                    ]);
                ?>
            </div>
            <div class="next_futer">

                <?= Html::submitButton('Сохранить', ['class' => 'width_all btn btn-success']) ?>


            </div>
        </div>


        <?php ActiveForm::end(); ?>
        <!--        --><?php //Pjax::end(); ?>


    </
    >
</div>

<br>
<br>
<br>
<br>


<?php
$script = <<<JS
$(document).ready(function() {
    $('.alert_save').fadeOut(2500); // плавно скрываем окно временных сообщений
});


$('input,textarea').keypress(function(e){
    if(e.keyCode==13) {  //alert('Нажал - enter!');
        return false;
    }
});


JS;

$this->registerJs($script, View::POS_READY);
?>

