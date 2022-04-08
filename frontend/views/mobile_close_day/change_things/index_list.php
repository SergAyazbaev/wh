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
        overflow: auto;
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
            background-color: #96ff0000;
            padding: 0px;
            width: 100%;
            max-width: none;
            min-height: min-content;
            margin-left: 0px;
            overflow: auto;
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

    <div class="ap_pe">
        <!--        <p>--><? //= $name_ap ?><!--</p>-->
        <!--        <p> --><? //= $name_pe ?><!--    --><? //= (!empty($name_cs) ? '(cs)' : "") ?><!--</p>-->
    </div>

    <h3>Экстренные замены. Сегодня.</h3>


    <div class="mobile-body-content">
        <div class="form-group">




            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider,
//                    'dataProvider' => $dataProvider_change_things,
//                    'filterModel' => $searchModel,

//                    'showFooter' => false,    // футер
//                    'showHeader' => true,  // заголовок
//                    'summary' => false,     // всего из ..

                    'columns' => [
//            'id' => 6
//            'barcode_bad' => '1510081377'
//            'barcode_god' => '041806'
                        [
                            'attribute' => 'id',
                            'contentOptions' => ['style' => 'width: 45px;'],
                        ],

                        [
                            'attribute' => 'barcode_bad',
                            'contentOptions' => ['style' => 'width: 105px;'],
                        ],

                        [
                            'attribute' => 'barcode_god',
                            'contentOptions' => ['style' => 'width: 105px;'],
                        ],

//                        [
//                            'attribute' => 'imageFiles_old',
//                            'contentOptions' => ['style' => 'width: 110px;'],
//                            'content' => function ($model) {
//
//                                $retu_str = '';
//                                $array1 = $model->imageFiles_old;
//
//                                foreach ($array1 as $item) {
//                                    $retu_str .= Html::a(
//                                        $item, DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . 'mts_change_old' . DIRECTORY_SEPARATOR . $model->path_hash_old . DIRECTORY_SEPARATOR . $item, [
//                                            'class' => 'btn btn-success btn-xs',
//                                            'method' => 'get',
//                                            'data-pjax' => 'w0',
//                                        ]
//                                    );
//                                    $retu_str .= ' ';
//                                }
//
//
//                                return $retu_str;
//                            }
//
//                        ],
//                        [
//                            'attribute' => 'imageFiles_new',
//                            'contentOptions' => ['style' => 'width: 110px;'],
//                            'content' => function ($model) {
//
//                                $retu_str = '';
//                                $array1 = $model->imageFiles_new;
//
//
//                                foreach ($array1 as $item) {
//                                    $retu_str .= Html::a(
//                                        $item, DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . 'mts_change_new' . DIRECTORY_SEPARATOR . $model->path_hash_new . DIRECTORY_SEPARATOR . $item, [
//                                            'class' => 'btn btn-success btn-xs',
//                                            'method' => 'get',
//                                            'data-pjax' => 'w0',
//                                        ]
//                                    );
//                                    $retu_str .= ' ';
//                                }
//
//
//                                return $retu_str;
//                            }
//
//                        ],
//                        [
//                            'attribute' => 'dt_create_timestamp',
//                            'contentOptions' => ['style' => 'width: 110px;'],
//                            'format' => ['datetime', 'php:d.m.Y H:i:s'],
//                        ],
//
//                        [
//                            'attribute' => 'mts_id',
//                            'header' => 'mts',
//
//                        ],
//                        [
//                            'attribute' => 'sklad',
//                        ],
//                        [
//                            'attribute' => 'id_ap',
//                        ],
//                        [
//                            'attribute' => 'id_pe',
//                        ],


                    ],
                ]
            );


            ?>
        </div>


    </div>

    <br>
    <br>
    <?php
    echo Html::a(
        'Выход',
        ['/mobile/change_things'],
        [
            'onclick' => 'window.opener.location.reload();window.close();',
            'class' => 'btn btn-warning',
        ]);
    ?>

    <?php
    echo Html::a(
        'Оформление документов.Финал.',
        ['/mobile_close_day/exchanges_all_to_sklad'],
        [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Закроется безвозвратно. Создадутся накладные. Закрываем АВТОБУС?',
                'method' => 'post',
            ],

        ]);
    ?>

</div>


<?php ActiveForm::end(); ?>
