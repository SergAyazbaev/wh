<?php

use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'GuideJet';
?>

<style>

    h1 {
        font-size: xx-large;
        padding: 5px;
        margin-top: 10px;
    }

    h2 {
        font-size: x-large;
        margin-bottom: 10px;
    }

    #w0, #w1 {
        overflow: hidden;
        padding-right: 2px;
    }

    .btn {
        margin: 10px 0px;
        padding: 3px 17px;
        font-size: 22px;
    }

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
            /*background-color: #00ff1a33;*/
            background-color: #ffb70017;
            padding: 3px 6px;
            border-radius: 10px;
            /*box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);*/

            overflow: hidden;
        }

        .form-control {
            font-size: 23px;
            height: auto;
        }

    }
</style>


<?php $form = ActiveForm::begin(
    [
        'action' => ['/mobile/init_sklad'],
        'method' => 'POST',
    ]
);
?>


<div class="site-index">

    <div class="mobile-body-content">
        <div class="jumbotron">
            <h2><b> МТС остатки</b></h2>
        </div>


        <div class="form-group">
            <h2>Выбрать ПАРК</h2>

            <?php
            echo $form->field($model, 'id')->dropDownList($array_sklad_list, ['prompt' => 'Выбрать СКЛАД ...']);
            ?>


            <div class="form-group">
                <?= Html::submitButton('Выбор подтверждаю', ['class' => 'width_all btn btn-success']) ?>
            </div>


            <br>
            <br>

            <?php
            echo Html::a(
                'Выход',
                ['/mobile/index'],
                [
                    'onclick' => 'window.opener.location.reload();window.close();',
                    'class' => 'btn btn-warning',
                ]);
            ?>


        </div>


    </div>
</div>


<?php ActiveForm::end(); ?>
