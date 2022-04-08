<?php

use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<style>
    .info {
        background-color: #99979c;
    }

    .gray {
        background-color: rgba(231, 228, 236, 0.8);
    }
</style>


<div class="table_with">
    <h1>Накладные ИНВЕНТАРИЗАЦИИ по складам ЦС </h1>

    <?php
    if (Yii::$app->user->identity->group_id >= 70) {
        echo Html::a('Залить Инвентаризации ЦС(по всем автобусам)', ['create_new_park'], ['class' => 'btn btn-success']);
    }
    ?>



    <?php
    $dataProvider->pagination->pageParam = 'in-page';
    $dataProvider->sort->sortParam = 'in-sort';

    $dataProvider->setSort([
        'defaultOrder' => ['id' => SORT_DESC],]);
    ?>


    <!--    --><?php //Pjax::begin(['options'=>[ 'autocomplete' => 'off' ]]); ?>

    <?php Pjax::begin();
    //ddd($searchModel);
    //ddd($searchModel->dt_create_timestamp);
    ?>


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

                    return Html::a('Вн', $url, [
                        'class' => 'btn btn-success btn-xs',
                        //'target' => '_blank',
                        'data-pjax' => 0,
                        'data-id' => $model->_id,
                    ]);
                }

            ],


            [
                'attribute' => 'wh_destination',
                'value' => 'sprwh_wh_destination.name',
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

                //'filter'         => $filter_for,


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
                'attribute' => 'dt_create_timestamp',
                'value' => 'dt_create_timestamp',
                'format' => ['datetime',
                    'php:d.m.y (H:i:s)',
                ],
                'contentOptions' => ['style' => 'width: 100px'],
                'filter' => Select2::widget(
                    [
                        'name' => 'select2_array_timestamp',
                        'data' => $filter_for_date,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'size' => Select2::SMALL,
                        'maintainOrder' => true,
                        'value' => $searchModel->dt_create_timestamp,
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
                    if (empty($model->dt_create_timestamp)) {
                        return '';
                    }
                    return date('d.m.y (H:i:s)', $model->dt_create_timestamp);
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
                                $url = Url::to(['/sklad_inventory_cs/delete?id=' . $model->_id]);

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
                'attribute' => 'old_gos',
                'value' => 'sprwhelement_change.old_gos',
                'label' => 'Гос № СТАР',

                'contentOptions' => function ($model) {
                    return ['style' => 'width: 90px;', 'class' => 'gray'];
                },
                'content' => function ($model) {
                    if (isset($model->sprwhelement_change->old_gos) && !empty($model->sprwhelement_change->old_gos)) {
                        return $model->sprwhelement_change->old_gos;
                    }
                    return '';
                },


//                'filter' => Select2::widget(
//                    [
//                        'name' => 'select2_array_cs_numbers',
//                        'data' => $filter_for_element,
//                        'theme' => Select2::THEME_BOOTSTRAP,
//                        'size' => Select2::SMALL,
//                        'maintainOrder' => true,
//                        'value' => $searchModel->wh_home_number,
//
//                        'options' => [
//                            'id' => 'wh_1',
//                            'placeholder' => 'Список...',
//                            ////////////'multiple'    => true,
//                        ],
//                        'pluginOptions' => [
//                            'allowClear' => true,
//                            //'minimumInputLength' => 2,
//                            'maximumInputLength' => 10,
//                        ],
//                    ]
//                ),
            ],

            [
                'attribute' => 'n_gos',
                'value' => 'sprwhelement_change.n_gos',
                'label' => 'Гос № НОВ',

                'contentOptions' => function ($model) {
                    return ['style' => 'width: 90px;', 'class' => 'gray'];
                },

                'content' => function ($model) {
                    if (isset($model->sprwhelement_change->n_gos) && !empty($model->sprwhelement_change->n_gos)) {
                        return $model->sprwhelement_change->n_gos;
                    }
                    return '';
                },

//                'filter' => Select2::widget(
//                    [
//                        'name' => 'select2_array_cs_numbers',
//                        'data' => $filter_for_element,
//                        'theme' => Select2::THEME_BOOTSTRAP,
//                        'size' => Select2::SMALL,
//                        'maintainOrder' => true,
//                        'value' => $searchModel->wh_home_number,
//                        'options' => [
//                            'id' => 'wh_1',
//                            'placeholder' => 'Список...',
//                        ],
//                        'pluginOptions' => [
//                            'allowClear' => true,
//                            'maximumInputLength' => 10,
//                        ],
//                    ]
//                ),

            ],


            [
                'attribute' => 'do_timestamp',
                'value' => 'sprwhelement_change.do_timestamp',
                'label' => 'Дата замены',

                'contentOptions' => function ($model) {
                    return ['style' => 'width: 90px;', 'class' => 'gray'];
                },

                'content' => function ($model) {
                    if (isset($model->sprwhelement_change->do_timestamp) && $model->sprwhelement_change->do_timestamp > 0) {
                        return date('d.m.y (H:i:s)', $model->sprwhelement_change->do_timestamp);
                    }
                    return '';
                },

                'format' => ['datetime',
                    'php:d.m.y (H:i:s)',
                ],
            ],

        ],
    ]);


    ?>

    <?php Pjax::end(); ?>
</div>


