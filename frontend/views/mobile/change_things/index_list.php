<?php

use \yii\grid\GridView;
use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'GuideJet';
?>

<style>
    .ap_pe > p {
        text-align: center;
        font-size: 23px;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: bolder;
        margin-bottom: 0px;
    }

    .ap_pe {
        margin-bottom: 30px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 15px;
    }

    .crm_txt {
        background-color: #b4d20e38;
        padding: 20px;
        white-space: pre-wrap;
        font-size: 22px;
        border: 3px solid #3333333d;
        border-radius: 10px;
        margin: 40px 0px;
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
            /*max-height: none;*/
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
            font-size: xx-large;
            padding: 5px;
            margin-top: 10px;
        }

        h2 {
            font-size: x-large;
            margin-bottom: 10px;
        }

        .sqware {
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


<?php $form = ActiveForm::begin(
    [
        'action' => ['/mobile/change_things'],
        'method' => 'GET',
    ]
);
?>


<div class="site-index">
    <div class="mobile-body-content">

        <div class="form-group">

            <h3>Замены. Демонтаж/Монтаж</h3>

            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider_change_things,
                    'filterModel' => $searchModel,

                    'showFooter' => false,    // футер
                    'showHeader' => true,  // заголовок
                    'summary' => false,     // всего из ..

                    'columns' => [

                        [
                            'attribute' => 'id',
                            'contentOptions' => ['style' => 'width: 45px;'],
                        ],
                        [
                            'attribute' => 'sprwhelement_ap.name',
                            'contentOptions' => ['style' => 'width: 105px;min-width: 95px;'],
                        ],
                        [
                            'attribute' => 'sprwhelement_pe.name',
                            'contentOptions' => ['style' => 'width: 105px;min-width: 95px;'],
                        ],
                        [
                            'attribute' => 'dt_create_timestamp',
                            'contentOptions' => ['style' => 'width: 110px;'],
                            'format' => ['datetime', 'php:d.m.Y H:i:s'],
                        ],

                        [
                            'attribute' => 'barcode_bad',
                            'contentOptions' => ['style' => 'width: 105px;min-width: 95px;'],
                        ],
                        [
                            'attribute' => 'barcode_god',
                            'contentOptions' => ['style' => 'width: 105px;min-width: 95px;'],
                        ],



                    ],
                ]
            );


            ?>
        </div>

        <!--        <div class="form-group">-->
        <!--            --><? //= Html::submitButton('Работа выполнена', ['class' => 'width_all btn btn-success']) ?>
        <!--        </div>-->


        <br>
        <br>

        <?php
        echo Html::a(
            'Выход',
            ['/mobile/index_list'],
            [
                'onclick' => 'window.opener.location.reload();window.close();',
                'class' => 'btn btn-warning',
            ]);
        ?>

    </div>
</div>


<?php ActiveForm::end(); ?>
