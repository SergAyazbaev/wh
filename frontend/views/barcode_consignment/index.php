<?php

use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

//    use yii\widgets\ActiveForm;


$this->title = 'Справочник проверочных кодов, штрихкодов';
$this->params[ 'breadcrumbs' ][] = $this->title;

Pjax::begin(['id' => 'w1']);

?>
    <div class="sprtype-index">

        <h1><?= Html::encode( $this->title ) ?></h1>
        <?php
        //         echo $this->render('_search', ['model' => $searchModel]); ?>
        <p>


            <?= Html::a( 'Создать новый Элемент склада', [ 'create' ], [ 'class' => 'btn btn-success' ] ) ?>
        </p>


        <?php

        //	Pjax::begin();

        $form = ActiveForm::begin(
            [
                'id' => 'project-form',
                'method' => 'get',
                'class' => 'form-inline',
                'action' => [ '/barcode_consignment/index' ],

                'options' => [
                    'data-pjax' => 'w1',
                    'autocomplete' => 'off',
                ],

            ]
        );
        ?>





        <?= Html::submitButton(
            'EXCEL. Заголовки накладных', [
                                            'class' => 'btn btn-default',
                                            'name' => 'print',
                                            'value' => 1,
                                        ]
        ) ?>


        <?php
        ActiveForm::end();

        $dataProvider->pagination->pageSize = 10;


        ?>


        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,

                'columns' => [


                    [
                        'attribute' => 'id',
                        'value' => 'id',
                        'contentOptions' => [ 'style' => ' ;width: 75px;' ],
                    ],

                    [
                        'attribute' => 'element_id',
                        'value' => 'element_id',
                        'contentOptions' => [ 'style' => ' ;width: 75px;' ],
                    ],

                    [
                        'attribute' => 'name',
                        'value' => 'name',
                        'contentOptions' => ['style' => ' max-width: 130px;overflow: hidden;'],
                    ],

                    [
                        'attribute' => 'tx',
                        'value' => 'tx',
                        'contentOptions' => ['style' => ' overflow: auto;'],
                    ],


                    [
                        //'header'      => 'Один день',
                        //'attribute'      => 'dt_start',
                        'attribute' => 'dt_one_day',
                        'value' => 'dt_create_timestamp',
                        'contentOptions' => [ 'style' => 'min-width: 170px; ' ],


                        'format' => [
                            'datetime',
                            'php:d.m.Y H:i:s',
                            //						'php:d.m.Y',
                        ],

                        'filter' => DatePicker::widget(
                            [
                                'type' => DatePicker::TYPE_INPUT,
                                'attribute' => 'dt_one_day',
                                'language' => 'ru',
                                'name' => 'dt_one_day',
                                'model' => $dataProvider,
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

                            ]
                        ),

                    ],
                    [
                        'header' => 'Редактирование',
                        //'attribute'      => 'dt_start',
                        'attribute' => 'dt_one_day',
                        'value' => 'dt_update_timestamp',
                        'contentOptions' => ['style' => 'min-width: 170px;max-width: 270px; '],


                        'format' => [
                            'datetime',
                            'php:d.m.Y H:i:s',
                            //						'php:d.m.Y',
                        ],

                    ],

                    [
                        'attribute' => 'cena_input',
                        'value' => 'cena_input',
                        'format' => [
                            'decimal',
                            2,
                        ],
                        'contentOptions' => [ 'style' => 'width: 190px;text-align: right;' ],
                    ],


                    //				'cena_input',
                    //				'cena_formula',
                    //				'cena_calc',


                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',

                        'contentOptions' => [
                            'class' => 'action-column',
                            'style' => ' min-width: 120px;',
                        ],
                        'buttons' => [
                            'update' => function ( $url ) {

                                //Для Талгата
                                if ( Yii::$app->getUser()->identity->group_id == 100 ||
                                    Yii::$app->getUser()->identity->group_id == 71 ) {

                                    $options = [
                                        'target' => '_blank',
                                        'title' => 'update',
                                        'aria-label' => 'update',
                                        'data-pjax' => 'w0',
                                        //'data-confirm' => Yii::t('yii', 'Редактируем... '),
                                        'data-method' => 'GET',

                                    ];

                                    return Html::a( '<span class="glyphicon glyphicon-edit"></span>', $url, $options );
                                }

                                return '';
                            },


                            'delete' => function ( $url ) {
                                //Для Талгата
                                if ( Yii::$app->getUser()->identity->group_id >= 71 ) {

                                    $options = [
                                        'title' => 'delete',
                                        'aria-label' => 'delete',
                                        'data-pjax' => 'w0',
                                        //'data-confirm' => Yii::t( 'yii', 'Точно? Удаляем? ' ),
                                        'data-method' => 'POST',
                                    ];

                                    return Html::a( '<span class="glyphicon glyphicon-trash"></span>', $url, $options );
                                }

                                return '';
                            },


                        ],


                    ],


                ],
            ]
        );


        ?>


    </div>


<?php
Pjax::end();
?>