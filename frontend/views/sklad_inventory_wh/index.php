<?php

use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<style>

    .scroll_mobile {
        width: 100%;
        overflow: auto;
    }

    .info {
        background-color: #99979c;
    }

    .gray {
        background-color: rgba(231, 228, 236, 0.8);
    }
</style>


<div class="table_with">
    <h1>Накладные ИНВЕНТАРИЗАЦИИ WH </h1>

    <?php
    if (Yii::$app->user->identity->group_id >= 70) {
        echo Html::a('Залить новую Инвентаризацию ', ['create_new_wh'], ['class' => 'btn btn-success']);
    }
    ?>

    <div class="scroll_mobile">

        <?php
        $dataProvider->pagination->pageParam = 'in-page';
        $dataProvider->sort->sortParam = 'in-sort';
        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],
        ]);
        ?>

        <?php Pjax::begin(); ?>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'label' => 'ДокЦС',
                    'attribute' => 'id',
                    'contentOptions' => ['style' => ' width: 72px;'],
                ],
                [
                    'header' => '',
                    'contentOptions' => function () {
                        if (Yii::$app->user->identity->group_id >= 30 &&
                            Yii::$app->user->identity->group_id < 40)
                            return ['style' => 'background-color: #4acc6061;'];
                        else
                            return ['style' => ' width: 72px;'];
                    },

                    'content' => function ($model) {

                        $url = Url::to(['update?id=' . $model->_id]);
                        $url_edit = Url::to(['update_edit?id=' . $model->_id]);
                        $url_svod = Url::to(['update_svod?id=' . $model->_id]);
                        $url_tab = Url::to(['excel?print=1&id=' . $model->id]);

                        return Html::a('Вн', $url, [
                                'class' => 'btn btn-success btn-xs',
                                'data-pjax' => 0,
                                'data-id' => $model->_id,
                            ]) . ' ' .
                            Html::a('Svod', $url_svod, [
                                'class' => 'btn btn-success btn-xs',
                                'data-pjax' => 0,
                                'data-id' => $model->_id,
                            ]) . ' ' .
                            Html::a('Edit', $url_edit, [
                                'class' => 'btn btn-success btn-xs',
                                'data-pjax' => 0,
                                'data-id' => $model->_id,
                            ]) . ' ' .
                            Html::a('EXCEL', $url_tab, [
                                'class' => 'btn btn-warning btn-xs',
                                'data-pjax' => 0,
                                'data-id' => $model->_id,
                            ]);
                    }
                ],


                [
                    'attribute' => 'wh_destination',
                    'value' => 'wh_destination_name',
                    'filter' => Select2::widget(
                        [
                            'name' => 'select2_array_destination',
                            'data' => $filter_for_group,
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
                            'maintainOrder' => true,
                            'value' => $searchModel->wh_destination,
                            'options' => [
                                'id' => 'wh_122',
                                'placeholder' => 'Список...',
                                ////////////'multiple'    => true,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],

                        ]
                    ),
                    'headerOptions' => ['autocomplete' => 0],
                    'contentOptions' => ['style' => 'width: 210px;'],


                ],


                [
                    'attribute' => 'wh_home_number',
                    'value' => 'sprwhelement_home_number.name',
                    'label' => 'ЦС',
                    'contentOptions' => ['style' => 'width: 210px;'],
                    'filter' => Select2::widget(
                        [
                            'name' => 'select2_array_cs_numbers',
                            'data' => $filter_for_element,
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
                            'maintainOrder' => true,
                            'value' => $searchModel->wh_home_number,

                            'options' => [
                                'id' => 'wh_1',
                                'placeholder' => 'Список...',
                                ////////////'multiple'    => true,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                //'minimumInputLength' => 2,
                                'maximumInputLength' => 10,
                            ],
                        ]
                    ),
                ],

                [
                    'attribute' => 'dt_create_day',
                    'value' => 'dt_create_day',
                    'format' => ['datetime',
                        'php:d.m.y (H:i:s)',
                    ],
                    'contentOptions' => ['style' => 'width: 150px'],
                    'filter' => Select2::widget(
                        [
                            'name' => 'select2_array_day',
                            'data' => $filter_for_date,
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
                            'maintainOrder' => true,
                            'value' => $searchModel->dt_create_day,
                            'options' => [
                                'id' => 'wh_12',
                                'placeholder' => 'Список...',
                                ////////////'multiple'    => true,
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
//                                //'minimumInputLength' => 2,
//                                //'maximumInputLength' => 10,
                            ],
                        ]
                    ),
                    'content' => function ($model) {
                        if (empty($model->dt_create_day)) {
                            return '';
                        }
                        return date('d.m.y (H:i:s)', $model->dt_create_day);
                    }
                ],
                [
                    //'attribute' => 'dt_create_timestamp',
                    'attribute' => 'dt_create',
                    'value' => 'dt_create',
                    'format' => ['datetime',
                        'php:d.m.y (H:i:s)',
                    ],
                    'contentOptions' => ['style' => 'width: 100px'],
                    'content' => function ($model) {
                        if (empty($model->dt_create)) {
                            return '';
                        }
                        return $model->dt_create;
                    }
                ],

                [
                    'attribute' => 'user_name',
                    'value' => 'user_name',
                    'contentOptions' => ['style' => 'width: 100px;'],
                ],

                ['class' => 'yii\grid\ActionColumn',
                    'header' => '',
                    'contentOptions' => ['style' => ' width: 50px;'],
                    'headerOptions' => ['width' => '10'],

                    'template' => '{delete}',

                    'buttons' => [
                        'delete' =>
                            function ($url, $model) {
                                //    dd($url);

                                if (
                                    Yii::$app->getUser()->identity->group_id == 100 ||
                                    Yii::$app->getUser()->identity->group_id == 71
                                ) {
                                    $url = Url::to(['/sklad_inventory_wh/delete?id=' . $model->_id]);

                                    $options = [
                                        'title' => 'delete',
                                        'aria-label' => 'delete',
                                        //   'data-pjax' => 'w0',
                                        'data-confirm' => Yii::t('yii', 'Точно Удаляем ?'),
                                    ];


                                    return Html::a(
                                        '<span class="glyphicon glyphicon-remove " style="color:red"></span>',
                                        $url, $options);
                                }

                                return Html::a('<span class="glyphicon " ></span>', $url);
                            },
                    ],
                ],

                [
                    'attribute' => 'dt_update_timestamp',
                    'value' => 'dt_update_timestamp',
                    'format' => ['datetime',
                        'php:d.m.y (H:i:s)',
                    ],
                    'contentOptions' => ['style' => 'width: 100px;min-width: 50px;'],
                    'content' => function ($model) {
                        if (empty($model->dt_update_timestamp)) {
                            return '';
                        }
                        return date('d.m.y (H:i:s)', $model->dt_update_timestamp);
                    }

                ],

                [
                    'attribute' => 'tx',
                    'contentOptions' => ['style' => 'width: 100px;min-width: 200px;padding:0px 10px;white-space: pre-wrap;'],
                    'content' => function ($model) {
                        if (empty($model->tx)) {
                            return '';
                        }
                        return $model->tx;
                    }

                ],
                [
                    'attribute' => 'count_str',
                    'contentOptions' => ['style' => 'width: 30px;min-width: 20px;'],
                    'content' => function ($model) {
                        if (empty($model->count_str)) {
                            return '';
                        }
                        return $model->count_str;
                    }

                ],
            ],
        ]);


        ?>

        <?php Pjax::end(); ?>
    </div>

</div>


