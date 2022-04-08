<?php

use \yii\helpers\Url;
use \yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'Заявки';
?>

<style>
    #w0, #w1 {
        overflow: hidden;
        padding-right: 2px;
    }

    .mobile-body-content {
        background-color: #00ff432e;
        padding: 15px;
        /* width: 60%; */
        /* max-width: 500px; */
        /* min-height: 700px; */
        /* max-height: 1000px; */
        margin-left: 0%;
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

            width: 100%;
            /*max-width: none;*/
            /*min-height: min-content;*/
            /*max-height: none;*/
            padding: 15px;
            margin-left: 0px;
            margin-bottom: 20px;
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
            font-size: 33px;
            /*background-color: #00ff434d;*/
            padding: 15px;
        }

        .form-group {
            /*margin-bottom: 30px;*/
            background-color: #ffb70017;
            padding: 3px 6px;
            border-radius: 5px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);

            display: block;
            width: min-content;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .form-control {
            font-size: 23px;
            height: auto;
        }

    }
</style>


<div class="site-index">


    <div class="jumbotron">
        <h1> Заявки для МТС</h1>
    </div>


    <div class="form-group">
        <h1>Ожидающие </h1>
    </div>


    <div class="mobile-body-content">
        <div class="form-group">

            <?php Pjax::begin(); ?>
            <?php $form = ActiveForm::begin(
                [
                    'action' => ['crm/tabl'],
                    'method' => 'POST',
                ]
            );
            ?>

            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider_open,
                    'filterModel' => $searchModel,

                    'showFooter' => false,    // футер
                    'showHeader' => false,  // заголовок
                    'summary' => false,     // всего из ..

                    'columns' => [
                        [
                            'header' => '',
                            'format' => 'raw',
                            'contentOptions' => ['style' => ' width: 50px;'],
                            'content' => function ($model) {
                                $text_ret = '';
                                $url = Url::to(['/crm/zakaz_info?id=' . $model->id]);
                                $text_ret .= ' ' . Html::a(
                                        'Вн', $url, [
                                            'class' => 'btn btn-success btn-xs',
                                            'data-pjax' => 0,
//                                            'target' => "_blank",
                                            'data-id' => $model->id,
                                        ]
                                    );
                                return $text_ret;
                            },
                        ],
                        [
                            'attribute' => 'id',
                            'contentOptions' => ['style' => 'max-width: 60px;'],
                        ],
                        [
                            'attribute' => 'dt_create_timestamp',
                            'contentOptions' => ['style' => 'width: 100px;'],
                            'format' => ['datetime', 'php:d.m.Y H:i:s',],
                        ],

                        [
                            'attribute' => 'sprwhelement_ap.name',
                            'contentOptions' => ['style' => 'max-width: 90px;overflow: hidden;'],
                        ],
                        [
                            'attribute' => 'sprwhelement_pe.name',
                            'contentOptions' => ['style' => 'max-width: 90px;overflow: hidden;'],
                        ],
                        [
                            'attribute' => 'sprwhelement_mts.name',
                            'contentOptions' => ['style' => 'max-width: 90px;overflow: hidden;'],
                        ],
                        [
                            'attribute' => 'crm_txt',
                            'contentOptions' => ['style' => 'max-width: 300px;min-width: 100px;padding:0px 10px;white-space: pre-wrap;']
                        ],


                    ],
                ]
            );
            ?>
            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>

        </div>
    </div>

    <br>
    <br>

    <div class="form-group">
        <h1>Решенные</h1>
    </div>

    <div class="mobile-body-content">
        <div class="form-group">

            <?php Pjax::begin(); ?>
            <?php $form = ActiveForm::begin(
                [
                    'action' => ['crm/tabl'],
                    'method' => 'POST',
                ]
            );
            ?>

            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider_close,
                    'filterModel' => $searchModel,

                    'showFooter' => false,    // футер
//                        'showHeader' => false,  // заголовок
                    'summary' => false,     // всего из ..

                    'columns' => [

                        [
                            'header' => '',
                            'format' => 'raw',
                            'contentOptions' => ['style' => ' width: 50px;'],
                            'content' => function ($model) {
                                $text_ret = '';
                                $url = Url::to(['/crm/closed_job?id=' . $model->id]);
                                $text_ret .= ' ' . Html::a(
                                        'Вн', $url, [
                                            'class' => 'btn btn-success btn-xs',
                                            'data-pjax' => 0,
//                                            'target' => "_blank",
                                            'data-id' => $model->id,
                                        ]
                                    );
                                return $text_ret;
                            },
                        ],

                        [
                            'attribute' => 'id',
                            'contentOptions' => ['style' => 'width: 90px;'],
                        ],
                        [
                            'attribute' => 'dt_create_timestamp',
                            'contentOptions' => ['style' => 'width: 100px;'],
                            'format' => ['datetime', 'php:d.m.Y H:i:s',],
                        ],

                        [
                            'attribute' => 'sprwhelement_ap.name',
                            'contentOptions' => ['style' => 'max-width: 90px;overflow: hidden;'],
                        ],
                        [
                            'attribute' => 'sprwhelement_pe.name',
                            'contentOptions' => ['style' => 'max-width: 90px;overflow: hidden;'],
                        ],
                        [
                            'attribute' => 'crm_txt',
                            'contentOptions' => ['style' => 'max-width: 300px;min-width: 100px;padding:0px 10px;white-space: pre-wrap;']
                        ],

                        [
                            'attribute' => 'job_fin_timestamp',
                            'contentOptions' => ['style' => 'width: 100px;'],
                            'format' => ['datetime', 'php:d.m.Y H:i:s',],
                        ],

                    ],
                ]
            );


            ?>
            <?php ActiveForm::end(); ?>
            <?php Pjax::end(); ?>

        </div>
    </div>

    <br>

    <?php
    echo Html::a(
        'Выход',
        ['/crm/index'],
        [
            'onclick' => 'window.opener.location.reload();window.close();',
            'class' => 'btn btn-warning',
        ]);
    ?>


</div>
<br>
<br>
<br>



