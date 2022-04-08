<?php

use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'XPrinter';
?>


<style>
    .mobile-body-content {
        background-color: #00ff432e;
        padding: 15px;
        width: 60%;
        max-width: 500px;
        min-height: 700px;
        /*max-height: 1000px;*/
        margin-left: 20%;
    }

    .jumbotron {
        text-align: center;
        padding: 10px 1px;
    }

    .width_all {
        width: 100%;
        font-size: 24px;
        /* font-weight: 900; */
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    @media (max-width: 500px) {
        .mobile-body-content {
            /*background-color: #96ff0012;*/
            background-color: #96ff0000;
            padding: 0px;
            width: 100%;
            max-width: none;
            min-height: min-content;
            max-height: none;
            margin-left: 0px;
        }

        .width_all {
            margin: 5px 2px;
            width: 99%;
            font-size: 22px;
            /* font-weight: 900; */
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            line-height: 2.1;
        }

        .wrap > .container22 {
            padding: 0px;
            width: 99%;
            overflow: auto;
            margin-top: 0px;
            margin-bottom: 4px;
        }

        .jumbotron {
            padding-top: 4px;
            padding-bottom: 0px;
            margin-bottom: 4px;
            color: inherit;
            background-color: #fff;
        }

        p > a {
            height: 50px;
        }

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
            font-size: x-large;
        }

        h1 {
            font-size: 44px;
            /*background-color: #00ff434d;*/
            padding: 15px;
        }

        .form-group {
            margin-bottom: 30px;
            background-color: #ffb70017;
            padding: 3px 6px;
            border-radius: 10px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        }

        .form-control {
            font-size: 23px;
            height: auto;
        }

    }
</style>


<div class="site-index">

    <div class="mobile-body-content">
        <div class="jumbotron">
            <h2><b>Копипаст из EXCEL</b></h2>
        </div>


        <?php $form = ActiveForm::begin(
            [
                'id' => 'project-form',
                'action' => ['/xprinter/print_from_array'],
                'method' => 'POST',

                'options' => [
                    //'data-pjax' => 1,
                    'autocomplete' => 'off',

                ],

                'validateOnChange' => true,

                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'validateOnSubmit' => false,
                'validateOnBlur' => false,

            ]);
        ?>

        <?= $form->field($model, 'bar_code')
//            ->textarea(['rows' => 6])
            ->textarea(
                [
                    'autofocus' => true,
                    'style' => 'height: 300px;font-size: 10px;',
                    'placeholder' => "ШТРИХКОД \nШТРИХКОД \nШТРИХКОД \nШТРИХКОД \nШТРИХКОД \nШТРИХКОД \n",
                ])
            ->label('В этом поле ...');
        //            ->label(false);
        ?>


        <?= Html::submitButton('Печать', [
                'id' => 'button_poisk',
            'class' => 'btn btn-lg btn-danger',
//            'data-confirm' => Yii::t('yii',
//                '<b>Вы точно хотите: </b><br>
//                                    РАСПЕЧАТАТЬ на этикетки?'),
        ]) ?>

        <?php ActiveForm::end(); ?>

        <br>
        <br>

        <?php
        //        echo Html::a(
        //            'Выход',
        //            ['/xprinter/index'],
        //            [
        //                'onclick' => 'window.opener.location.reload();window.close();',
        //                'class' => 'btn btn-warning',
        //            ]);
        ?>

    </div>
</div>

