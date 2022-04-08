<?php

use \yii\widgets\ActiveForm;
use \kartik\select2\Select2;
use frontend\models\Sprwhtop;
use frontend\models\Sklad_cs_past_inventory;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>


<style>
    .table_with {
        margin-top: 0px;
    }

    .container22 {
        padding: 0px;
        margin: 0px;
    }
</style>

<div class="table_with">
    <br>
    <br>
    <h1>Промежуточные итоги</h1>

    <?php
    $form = ActiveForm::begin(
        [
            //            'id' => 'project-form',
            'method' => 'get',
            'class' => 'form-inline',
            'action' => ['/stat_svod/index_svod'],
//            'action' => ['/past_inventory_cs_/index'],

            'options' => [
                //'data-pjax' => 1,
                'autocomplete' => 'off',
            ],

        ]
    );
    ?>

    <!--    --><? //= Html::submitButton(
    //        'EXCEL. Заголовки накладных', [
    //            'class' => 'btn btn-default',
    //            'name' => 'print',
    //            'value' => 1,
    //        ]
    //    ) ?>


    <!--    --><? //= Html::submitButton(
    //        'EXCEL. Содержимое накладных', [
    //            'class' => 'btn btn-default',
    //            'name' => 'print',
    //            'value' => 2,
    //        ]
    //    ) ?>

    <!--    --><? //= Html::submitButton(
    //        'EXCEL. Только АСУОП.', [
    //            'class' => 'btn btn-default',
    //            'name' => 'print',
    //            'value' => 3,
    //        ]
    //    ) ?>


    <?php
    $form = ActiveForm::end();
    ?>




    <?php
    $dataProvider->pagination->pageParam = 'in-page';
    $dataProvider->sort->sortParam = 'in-sort';

    $dataProvider->setSort(
        [
            'defaultOrder' => ['id' => SORT_DESC],]
    );
    ?>


    <?php Pjax::begin(); ?>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 30px;'],
                ],
                [
                    'header' => '',
                    'contentOptions' => ['style' => ' width: 52px;'],

                    'content' => function ($model) {

                        $url = Url::to(['update?id=' . $model->_id]);

                        return Html::a(
                            'Вн', $url, [
                                'class' => 'btn btn-success btn-xs',
                                'data-pjax' => 0,
                                'data-id' => $model->_id,
                            ]
                        );
                    }

                ],


                [
                    'attribute' => 'wh_destination',
//                'label' => 'КОНТРАГЕНТ',
                    'value' => 'wh_destination_name',


                    /// Уникальные ТОЛЬКО ТУТ ИМЕНА
                    'filter' => Sprwhtop::ArrayNamesWithIds(
                        Sklad_cs_past_inventory::ArrayUniq_Wh_Ids()
                    ),

                    //'filter' => Sklad_past_inventory::ArrayUniq_Wh_Ids() ,
                    'contentOptions' => ['style' => 'overflow: hidden;max-width: 290px;'],
                ],


                [
                    'attribute' => 'wh_destination_element',
                    'value' => 'wh_destination_element_name',
                    'contentOptions' => ['style' => 'max-width: 160px;'],
                    //'filter'         => $filter_for,

                    'filter' => Select2::widget(
                        [
                            'name' => 'select2_array_cs_numbers',
                            'data' => $filter_spr,

                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
                            'maintainOrder' => true,
                            'value' => [0 => 'Сброс'],
                            'options' => [
                                'id' => 'wh_1',
                                'placeholder' => 'Наименование',
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
                    'attribute' => 'wh_destination_bort',
                    'value' => 'wh_destination_bort',
                    'contentOptions' => ['style' => 'min-width: 110px;'],
                    //'filter'         => $filter_for,

                    'filter' => Select2::widget(
                        [
                            'name' => 'select2_array_cs_numbers_bort',
                            'data' => $filter_bort,

                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
//                        'maintainOrder' => true,
                            'value' => [0 => 'Сброс'],
                            'options' => [
                                'id' => 'wh_2',
                                'placeholder' => 'Борт. номер',
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
                    'attribute' => 'wh_destination_gos',
                    'value' => 'wh_destination_gos',
                    //                'label' => 'ЦС',
                    'contentOptions' => ['style' => 'min-width: 110px;'],
                    //'filter'         => $filter_gos,

                    'filter' => Select2::widget(
                        [
                            'name' => 'select2_array_cs_numbers_gos',
                            'data' => $filter_gos,

                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
                            'maintainOrder' => true,
                            'value' => [0 => 'Сброс'],
                            'options' => [
                                'id' => 'wh_3',
                                'placeholder' => 'Гос. номер',
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
                    'attribute' => 'dt_start',
                    'contentOptions' => ['style' => ' min-width: 42px;'],
                ],
                [
                    'attribute' => 'dt_create',
                    'contentOptions' => ['style' => ' min-width: 42px;'],
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

                                if (Yii::$app->getUser()->identity->group_id == 100) {
                                    $url = Url::to(['/past_inventory_cs_/delete?id=' . $model->_id]);

                                    return Html::a(
                                        '<span class="glyphicon glyphicon-remove " style="color:red"></span>',
                                        $url
                                    );
                                }

                                return Html::a('<span class="glyphicon " ></span>', $url);
                            },
                    ],

                ],

//                 "itogo_strings" : 10,
//    "itogo_things" : 10,
                [
                    'attribute' => 'itogo_actions',
                    'contentOptions' => ['style' => 'min-width: 54px;'],
                ],
                [
                    'attribute' => 'itogo_strings',
                    'contentOptions' => ['style' => 'min-width: 54px;'],
                ],
                [
                    'attribute' => 'itogo_things',
                    'contentOptions' => ['style' => ' min-width: 50px;'],
                ],

            ],
        ]
    );


    //        /**
    //         * @param $d
    //         * @return false|string
    //         */
    //        function Data_format($d)
    //    {
    //        return date('d.m.Y h:i:s', strtotime($d));
    //    }
    //

    ?>

    <?php Pjax::end(); ?>


</div>


<?php
$script = <<<JS



JS;
$this->registerJs($script);
?>



