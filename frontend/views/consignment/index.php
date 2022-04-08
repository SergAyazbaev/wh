<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

//    use yii\widgets\ActiveForm;


$this->title = 'Справочник проверочных кодов, штрихкодов';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(['id' => '1']);

?>
    <div class="sprtype-index">

        <h1><?= Html::encode($this->title) ?></h1>
        <?php
        //         echo $this->render('_search', ['model' => $searchModel]); ?>


        <p>

            <?= Html::a('Создать новый Элемент склада', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <p>
            <?= Html::a('Создать новый Набор всех устройств', ['create_all_new'], ['class' => 'btn btn-success',
                'data-confirm' => Yii::t('yii', 'Точно? Создаем ЕЩЕ один список устройств? '),
            ]) ?>
        </p>


        <?php

        Pjax::begin();

        $form = ActiveForm::begin(
            [
                'id' => 'project-form',
                'method' => 'get',
                'class' => 'form-inline',
                'action' => ['/consignment'],

                'options' => [
                    //'data-pjax' => 1,
                    'autocomplete' => 'off',
                ],

            ]);
        ?>


        <?= Html::submitButton(
            'EXCEL. Заголовки накладных', [
            'class' => 'btn btn-default',
            'name' => 'print',
            'value' => 1,
        ]) ?>


        <?php
        ActiveForm::end();
        ?>


        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,

                'columns' => [


                    [
                        'attribute' => 'id',
                        'value' => 'id',
                        'contentOptions' => ['style' => ' ;width: 75px;'],
                    ],


                    [
                        'attribute' => 'name',
                        'value' => 'name',
                        //					'contentOptions' => [ 'style' => ' ;max-width: 300px;overflow: auto;' ],
                    ],
                    [
                        'attribute' => 'tx',
                        'value' => 'tx',
                        'contentOptions' => ['style' => 'max-width: 250px;overflow: auto;'],
                    ],


                    [
                        //'header'      => 'Один день',
                        //'attribute'      => 'dt_start',
                        'attribute' => 'dt_one_day',
                        'value' => 'dt_create_timestamp',


                        'contentOptions' => ['style' => 'min-width: 170px; max-width: 180px;'],

                        //					'contentOptions' => function( $data ) {
                        //						return [ 'style' => 'color:green;overflow: hidden;' ];
                        //					},

                        'format' => [
                            'datetime',
                            //'php:d.m.Y H:i:s',
                            'php:d.m.Y',
                        ],

                        'filter' => DatePicker::widget(
                            [
                                'type' => DatePicker::TYPE_INPUT,
                                'attribute' => 'dt_one_day',
                                'language' => 'ru',
                                'name' => 'dt_one_day',
                                'value' => $dt_one_day,

                                //							'model'         => $searchModel,
                                'pluginOptions' => [
                                    //'format' => 'd.m.Y',
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ],

                                'options' => [
                                    'placeholder' => 'Один день',
                                    'autocomplete' => "off",
                                ],

                                'convertFormat' => false,

                            ]),

                    ],



//                    [
//                        //'header'      => 'Один день',
//                        //'attribute'      => 'dt_start',
//                        'attribute' => 'dt_one_day',
//                        'value' => 'dt_create_timestamp',
//
//
//                        'contentOptions' => ['style' => 'min-width: 170px; max-width: 180px;'],
//
//                        //					'contentOptions' => function( $data ) {
//                        //						return [ 'style' => 'color:green;overflow: hidden;' ];
//                        //					},
//
//                        'format' => [
//                            'datetime',
//                            //'php:d.m.Y H:i:s',
//                            'php:d.m.Y',
//                        ],
//
//                        'filter' => DatePicker::widget([
//                            'name' => 'from_date',
//                            'value' => '01-Feb-1996',
//                            'type' => DatePicker::TYPE_RANGE,
//                            'name2' => 'to_date',
//                            'value2' => '27-Feb-1996',
//                            'pluginOptions' => [
//                                'autoclose' => true,
//                                'format' => 'yyyy-mm-dd'
//                            ],
//                        ]),
//
//
//                    ],

                    [
                        'attribute' => 'group_id',
                        'value' => 'spr_globam.name',
                        'contentOptions' => ['style' => ' ;width: 75px;'],
                    ],

                    [
                        'attribute' => 'element_id',
                        'value' => 'spr_globam_element.name',
                        'contentOptions' => ['style' => ' ;width: 75px;'],
                    ],


                    [
                        'attribute' => 'cena',
                        'value' => 'cena',
                        'format' => [
                            'decimal',
                            2,
                        ],
                        'contentOptions' => [
                            'style' => 'width: 190px;text-align: right;padding-right: 20px;',
                        ],
                    ],


                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',

                        'contentOptions' => [
                            'class' => 'action-column',
                            'style' => ' min-width: 120px;',
                        ],
                        'buttons' => [
                            'update' => function ($url) {

                                //Для Талгата
                                if (
                                    Yii::$app->getUser()->identity->group_id == 71 ||
                                    Yii::$app->getUser()->identity->group_id == 100
                                ) {

                                    $options = [
                                        'target' => '_blank',
                                        'title' => 'update',
                                        'aria-label' => 'update',
                                        'data-pjax' => 'w0',
                                        //'data-confirm' => Yii::t('yii', 'Редактируем... '),
                                        'data-method' => 'GET',

                                    ];

                                    return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, $options);
                                }

                                return '';
                            },


                            'delete' => function ($url) {
                                //Для Талгата
                                if (Yii::$app->getUser()->identity->group_id >= 71) {

                                    $options = [
                                        'title' => 'delete',
                                        'aria-label' => 'delete',
                                        'data-pjax' => 'w0',
//									'data-confirm' => Yii::t( 'yii', 'Точно? Удаляем? ' ),
                                        'data-method' => 'POST',
                                    ];

                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                                }

                                return '';
                            },


                        ],


                    ],


                ],
            ]);


        ?>


    </div>


<?php
Pjax::end();
?>