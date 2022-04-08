<?php

use \yii\helpers\Url;
use \yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'GuideJet';
?>

<style>

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
            font-size: 44px;
            /*background-color: #00ff434d;*/
            padding: 15px;
        }

        .form-group {
            margin-bottom: 30px;
            background-color: #ffb70054;
            border-radius: 10px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
            overflow: hidden;
            padding: 0px 15px;
        }

        .form-control {
            font-size: 23px;
            height: auto;
        }

    }
</style>


<?php Pjax::begin(); ?>
<?php $form = ActiveForm::begin(
    [
        'action' => ['/mobile/shaman'],
        'method' => 'POST',
    ]
);
?>


<div class="site-index">

    <div class="mobile-body-content">
        <div class="jumbotron">
            <h3><b>Заявки на сегодня</b></h3>
        </div>

        <div class="form-group">
            <h3>Ожидают решения</h3>

            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider_open,
                    'filterModel' => $searchModel,

                    'showFooter' => false,    // футер
                    'showHeader' => false,  // заголовок
                    'summary' => false,     // всего из ..

                    'columns' => [

                        [
                            'attribute' => 'id',
                            'contentOptions' => ['style' => 'max-width: 60px;'],

                            'content' => function ($model) {
                                $url = Url::to(['shaman?id=' . $model->id]);

                                return Html::a(
                                    $model->id, $url, [
                                        'class' => 'btn btn-success btn-xs',
                                        'method' => 'get',
                                        'data-pjax' => 0,
//                                    'data-id' => $model->id,
                                    ]
                                );
                            }

                        ],
                        [
                            'attribute' => 'dt_create_timestamp',
                            'contentOptions' => ['style' => 'width: 100px;'],
                            'format' => ['datetime', 'php:d.m.Y H:i:s',],
                        ],

                        [
                            'attribute' => 'sprwhelement_ap.name',
                            'contentOptions' => ['style' => 'max-width: 60px;overflow: hidden;'],
                        ],

                        [
                            'attribute' => 'sprwhelement_pe.name',
                            'contentOptions' => ['style' => 'max-width: 60px;overflow: hidden;'],
                        ],

                        'job_fin',
//                        'job_fin_timestamp',
//                        'zakaz_fin',
//                        'zakaz_fin_timestamp',

                    ],
                ]
            );


            ?>
        </div>

        <div class="form-group">
            <h3>Решено</h3>

            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider_close,
                    'filterModel' => $searchModel,

                    'showFooter' => false,    // футер
                    'showHeader' => false,  // заголовок
                    'summary' => false,     // всего из ..

                    'columns' => [

                        [
                            'attribute' => 'id',
                            'contentOptions' => ['style' => 'max-width: 90px;'],
                        ],

                        [
                            'attribute' => 'job_fin_timestamp',
                            'contentOptions' => ['style' => 'width: 100px;'],
                            'format' => ['datetime', 'php:d.m.Y H:i:s',],
                        ],

                        [
                            'attribute' => 'sprwhelement_ap.name',
                            'contentOptions' => ['style' => 'max-width: 60px;overflow: hidden;'],
                        ],

                        [
                            'attribute' => 'sprwhelement_pe.name',
                            'contentOptions' => ['style' => 'max-width: 60px;overflow: hidden;'],
                        ],


//                        'zakaz_fin_timestamp',

                    ],
                ]
            );


            ?>
        </div>


        <br>
        <br>

        <?php
        echo Html::a(
            'Выход',
            ['/mobile/index'],
            [
//                'onclick' => 'window.opener.location.reload();window.close();',
                'class' => 'btn btn-warning',
            ]);
        ?>


    </div>
</div>



<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
