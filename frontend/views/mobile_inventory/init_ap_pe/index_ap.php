<?php

use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'Инвентаризация';
?>

<style>
    .vedomost {
        background-color: #0b58a2;
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


    h1 {
        font-size: xx-large;
        padding: 5px;
        margin-top: 10px;
    }

    h2 {
        font-size: x-large;
        margin-bottom: 10px;
    }


    .mobile-body-content {
        background-color: #00ff432e;
        padding: 15px;
        width: 60%;
        max-width: 500px;
        margin-left: 20%;
    }

    .jumbotron {
        text-align: center;
        padding: 10px 1px;
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

    /*div.next_futer {*/
    /*    float: right;*/
    /*    width: 140px;*/
    /*}*/

    div.next_futer button {
        float: right;
        width: 180px;
    }

    /*//ALL BUTTONS/*/
    div > .width_all_butt {
        width: 100%;
    }

    div.width_all_butt > button, div.width_all_butt > select {
        margin: 7px;
        width: 94%;
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

        .vedomost {
            background-color: #0b58a2;
            margin: 10% 15%;
        }

    }
</style>


<?php $form = ActiveForm::begin(
    [
        'action' => ['mobile_inventory/init_table'],
        'method' => 'POST',
    ]
);
?>


<div class="site-index">

    <div class="mobile-body-content">


        <p>Инвентаризация.</p>
        <h2>Выбрать ПАРК</h2>


        <?php
        echo $form->field($model, 'id')->dropDownList($array_ap_list, ['prompt' => 'Выбрать ПАРК...']);
        ?>


    </div>
</div>

<div class="_futer">

    <div class="past_futer">
        <?php
        echo Html::a(
            '<< Выход',
            ['/'],
            [
                'onclick' => 'window.opener.location.reload();window.close();',
                'class' => ' back_step btn btn-warning',
            ]);
        ?>
    </div>

    <div class="next_futer">
        <?= Html::submitButton('Выбор подтверждаю', ['class' => 'width_all btn btn-success']) ?>
    </div>
</div>

<?php
echo Html::a(
    'Просмотр ведомости',
    ['/mobile_inventory/big_table'],
    [
        'data' => '10',
        'class' => ' vedomost btn btn-warning',
    ]
);
?>

<?php ActiveForm::end(); ?>

