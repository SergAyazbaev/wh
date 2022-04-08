<?php


use yii\grid\GridView;
use \yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Журнал ремонтов ';
?>
<style>
    .wrap {
        width: 90%;
        margin-left: 5%;
        margin-top: 5%;
    }

    .table_all {
        background-color: #032B3F22;
        display: grid;
        position: relative;
        margin-top: 10px;
        padding: 15px 45px;
    }

    .table_decision {
        background-color: #a8151b22;
        position: inherit;
        display: table-caption;
        float: right;
        margin: 10px;
        padding: 10px;
        float: left;
    }

    .table_1 {
        background-color: #012B3F22;
        position: inherit;
        display: table-caption;
        float: right;
        margin: 10px;
        padding: 10px;
    }

    .wrap > .container22 {
        margin-top: 0px;
    }


    @media (max-width: 500px) {
        .wrap {
            width: auto;
            margin-left: 0;
            margin-top: 5%;
        }

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
    }

    .alert_str {

    }

    .btn-xs, .btn-group-xs > .btn {
        padding: 1px 5px;
        font-size: 16px;
        line-height: 1.5;
        border-radius: 3px;
    }

    .grid-view .filters input, .grid-view .filters select {
        min-width: 50px;
        font-size: 24px;
        padding: 3px;
    }

</style>


<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новая запись для ШТРИХ-КОДА ' . $bar_code, ['/rem_sklad/create_for_barcode?id=' . $bar_code], [
            'class' => 'btn btn-success'
        ]) ?>
    </p>

    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [


                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 40px;'],
                ],

                [
                    'attribute' => 'bar_code',
                    'contentOptions' => ['style' => 'width: 80px;'],
                    'content' => function ($model) {
                        //ddd($model['bar_code']);

                        $url = Url::to(['/rem_sklad/update?id=' . $model['id']]);

                        $text_ret = ' ' . Html::a(
                                $model['bar_code'], $url, [
                                    'class' => 'btn btn-success btn-xs',
                                    'data-pjax' => 0,
                                ]
                            );
                        return $text_ret;
                    }


                ],

                [
                    'attribute' => 'short_name',
                    'contentOptions' => ['style' => 'width: 150px;overflow: hidden;'],
                ],

                [
                    'attribute' => 'diagnoz',
                    'contentOptions' => ['style' => 'max-width: 150px;overflow: auto;'],
                ],
                [
                    'attribute' => 'decision',
                    'contentOptions' => ['style' => 'max-width: 150px;overflow: auto;'],
                ],
                [
                    'attribute' => 'list_details',
                    'contentOptions' => ['style' => 'max-width: 150px;overflow: auto;'],
                ],


                [
                    'attribute' => 'user_name',
                    'contentOptions' => ['style' => 'min-width: 70px;'],
                ],


//            'user_id',
//            'user_group',
//            'user_ip',

//            'dt_create',

                [
                    'attribute' => 'dt_create_timestamp',
                    'value' => 'dt_create_timestamp',
                    'format' => ['datetime', 'php:d.m.Y (H:i:s)'],
                    'contentOptions' => ['style' => 'width: 120px;overflow: auto;'],
                ],


            ],
        ]
    );
    ?>
</div>


<div class="_futer">
    <div class="past_futer">

        <?php
        echo Html::a(
            '<< Выход',
            ['/rem_sklad/index'],
            [
                'onclick' => 'window.opener.location.reload();window.close();',
                'class' => ' back_step btn btn-warning',
            ]);
        ?>
    </div>
    <!--            <div class="next_futer">-->
    <!---->
    <!--                --><? //= Html::a('Далее >>', ['mobile_inventory/init_table'], ['class' => ' next_step btn btn-success']) ?>
    <!---->
    <!--            </div>-->
</div>




