<?php

use frontend\models\Sprwhtop;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

$this->title = 'Справочник складов';
?>

<style>

    .tree_land {
        width: 450px;
        min-width: 300px;
        background-color: cornsilk;
        padding: 15px;
    }

    p, form {
        width: 275px;
        display: block;
        position: relative;
        float: left;
    }

</style>


<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php //         echo $this->render('_search', ['model' => $searchModel]);    ?>

    <p>
        <?= Html::a('Создать новый Элемент склада', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php
    echo $this->render('_excel', ['model' => $searchModel]);
    ?>


    <?php Pjax::begin(['id' => 'pjax-container',]); ?>



    <?php $form = ActiveForm::begin(
        [
            'id' => 'project-form',

            'action' => ['/sprwhelement/gos_bort_copypast'],
            'method' => 'POST',
            'options' => [
                'autocomplete' => 'off',
            ],
            'enableClientValidation' => false,
            'validateOnChange' => true,
            'validateOnSubmit' => true,

        ]
    );
    ?>

    <?php
    Modal::begin(
        [
            'header' => '<h2>Добавляем ГОС и БОРТ номера в справочник</h2>',
            'toggleButton' => [
                'label' => 'Копипаст ПУЛ Штрихкодов',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],
            //'footer' => 'Низ окна',
        ]
    );

    ?>

    <br>

    <?= $form
        ->field($model, 'find_name')
        ->textarea(
            [
                'autofocus' => true,
                'style' => 'height: 300px;font-size: 10px;',
                'placeholder' => "Столбцы:
                
                1. Перевозчик (АРМ)
                2. Гос. номер / борт (АРМ)
                
                Информация получена из сайта ТХА
                ",
            ]
        )
        ->label('В это поле пастим');
    ?>


    <div class="form-group">
        <?= Html::submitButton(
            'Залить копипаст и принять НОВЫЕ ЗНАЧЕНИЯ ',
            [
                'class' => 'btn btn-primary',
                'name' => 'contact-button',
                'value' => 'add_new_pool',
            ]
        ) ?>

    </div>


    <?php Modal::end();
    ///////////////////////////////////?>

    <?php ActiveForm::end(); ?>



    <?php Pjax::begin(['id' => 'pjax_1']); ?>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

            'columns' => [

                [
                    'header' => 'Наименование 1С',
                    'attribute' => 'parent_id',
                    'value' => 'sprwhtop.name',
                    'filter' => ArrayHelper::map(
                        Sprwhtop::find()
                            ->orderBy(['name'])
                            ->all(), 'id', 'name'
                    ),

                    'contentOptions' => ['style' => 'min-width: 150px;overflow: hidden;white-space: pre-wrap;'],
                ],


                [
                    'header' => 'Наим.ТХА',
                    'contentOptions' => ['style' => 'min-width: 150px;white-space: pre-wrap;'],
                    'value' => function ($model, $key) {
                        //ddd($model['sprwhtop']['name']);
                        if (isset($model['sprwhtop']['name_tha'])) {
                            return $model['sprwhtop']['name_tha'];
                        }

                        return '';
                    },
                ],


                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 30px'],
                ],


                [
                    'attribute' => 'name',
                    'contentOptions' => ['style' => 'min-width: 150px;white-space: pre-wrap;'],
                ],

                [
                    'attribute' => 'nomer_borta',
                    'contentOptions' => ['style' => 'width: 70px;overflow: hidden;'],
                ],
                [
                    'attribute' => 'nomer_gos_registr',
                    'contentOptions' => ['style' => 'width: 70px;overflow: hidden;'],
                ],
                [
                    'attribute' => 'nomer_traktor',
                    'contentOptions' => ['style' => 'width: 70px;overflow: hidden;'],
                    'value' => function ($model) {
                        if (isset($model['nomer_traktor'])) {
                            return $model['nomer_traktor'];
                        }
                        return '';
                    },

                ],
                [
                    'attribute' => 'nomer_vin',
                    'contentOptions' => ['style' => 'width: 40px;overflow: hidden;'],
                ],


                [
                    'attribute' => 'tx',
                    'contentOptions' => ['style' => 'min-width: 150px;white-space: pre-wrap;'],
                ],

                [
                    'attribute' => 'delete_sign',
                    'value' => function ($url, $model, $key) {

                        if (isset($url['delete_sign'])) {
                            return $url['delete_sign'];
                        }

                        return '';
                    },
                    'contentOptions' => ['style' => ' width: 32px;'],
                ],


                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {flags} {deactive} - {gos} {gos_number} {changenumber} - {delete} {erase}',

                    'contentOptions' => [
                        'class' => 'action-column',
                        'style' => 'width: 50px;overflow: hidden;'
                    ],
                    'buttons' => [
                        'update' => function ($url, $model, $key) {

                            //Для Талгата
                            if (
                                Yii::$app->user->identity->group_id == 100 ||
                                Yii::$app->user->identity->group_id == 71
                            ) {

                                $options = [
                                    'title' => 'Редактирование',
//                                    'target' => '_blank',
                                    'aria-label' => 'edit',
                                    'data-pjax' => 0,
                                    //'data-pjax' => 'w0',
                                    'data-method' => 'POST',

                                ];

                                return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, $options);
                            }

                            return '';
                        },

                        'deactive' => function ($url, $model) {

                            if (
                                Yii::$app->getUser()->identity->group_id >= 70 ||
                                Yii::$app->getUser()->identity->group_id == 65 ||
                                Yii::$app->getUser()->identity->group_id == 61
                            ) {

                                $options = [
                                    'title' => 'Деактивация',
//                                    'target' => '_blank',
                                    'aria-label' => 'edit',
                                    'data-pjax' => 0,
                                    //'data-pjax' => 'w0',
                                    'data-method' => 'POST',
                                ];

                                return Html::a(
//                                    '<span class="glyphicon glyphicon-fire"></span>',
                                    '<span class="glyphicon glyphicon-ok-circle"></span>',
                                    $url, $options
                                );
                            }

                            return '';
                        },

                        'changenumber' => function ($url, $model) {

                            if (
                                Yii::$app->getUser()->identity->group_id >= 70 ||
                                Yii::$app->getUser()->identity->group_id == 65 ||
                                Yii::$app->getUser()->identity->group_id == 61
                            ) {

                                //ddd($model);

                                $options = [
                                    'id' => $model->id,
                                    'title' => 'Замена ГОС номера',
                                    'aria-label' => 'edit',
                                    'data-pjax' => 0,
                                    //'data-pjax' => 'w0',
                                    'data-method' => 'POST', //!!
                                ];

                                return Html::a(
//                                    '<span class="glyphicon glyphicon-fire"></span>',
                                    '<span class="glyphicon glyphicon-serg5"></span>',
                                    $url, $options
                                );
                            }

                            return '';
                        },


                        'flags' => function ($url, $model) {

                            if (
                                Yii::$app->getUser()->identity->group_id >= 70
                            ) {

                                $options = [
                                    'title' => 'Целевой склад',
                                    'aria-label' => 'edit',
                                    'data-pjax' => 0,
                                    'data-method' => 'POST',
                                ];

                                return Html::a(
                                    '<span class="glyphicon glyphicon-ok-circle"></span>',
                                    $url, $options
                                );
                            }

                            return '';
                        },


                        'gos' => function ($url, $model) {

                            if (
                                Yii::$app->getUser()->identity->group_id >= 100 ||  // Админ
                                Yii::$app->getUser()->identity->group_id >= 70 ||   // Талгат
                                Yii::$app->getUser()->identity->group_id == 65 ||   // Султанова Н.
                                Yii::$app->getUser()->identity->group_id == 61      // Назик, Айдана
                            ) {

                                $options = [
                                    'id' => $model->id,
                                    'title' => 'ГОС и БОРТ сохранить на свои места',
                                    'data-pjax' => 0,
//                                    'target' => "_blank",
                                    'aria-label' => 'edit',
                                    'data-method' => 'POST',
                                ];

                                return Html::a(
                                    ' <span class="glyphicon glyphicon-eye-close"></span>',
                                    $url, $options
                                );
                            }

                            return '';
                        },

                        'gos_number' => function ($url, $model) {

                            if (
                                Yii::$app->getUser()->identity->group_id >= 100 ||  // Админ
                                Yii::$app->getUser()->identity->group_id >= 70 ||   // Талгат
                                Yii::$app->getUser()->identity->group_id == 65 ||   // Султанова Н.
                                Yii::$app->getUser()->identity->group_id == 61      // Назик, Айдана
                            ) {

                                $options = [
                                    'title' => 'Фото документа',
                                    'aria-label' => 'edit',
//                                    'data-pjax' => 'w0',
                                    'data-method' => 'POST',
                                ];

                                return Html::a(
//                                    '  <span class="glyphicon glyphicon-paperclip"></span>',
                                    '  <span class="glyphicon glyphicon-ok-circle"></span>',
                                    $url, $options
                                );
                            }

                            return '';
                        },


                        'delete' => function ($url, $model, $key) {
                            //Для Талгата
                            if (Yii::$app->getUser()->identity->group_id >= 71) {

                                $options = [
                                    'title' => 'Удаление',
                                    'aria-label' => 'delete',
                                    'data-pjax' => 'w0',
                                    'data-confirm' => Yii::t('yii', 'Точно? Удаляем? '),
                                    'data-method' => 'GET',
//                                    'data-method' => 'DELETE',
                                ];

                                return Html::a(' <span class="glyphicon glyphicon-trash"></span>', $url, $options);
                            }

                            return '';
                        },


                        'erase' => function ($url, $model, $key) {
                            //        ddd($model->id);


                            if ($model->delete_sign == 1) {
                                $url = '/sprwhelement/erase?id=' . $model->id;

                                if (
                                    Yii::$app->getUser()->identity->group_id >= 100 ||
                                    Yii::$app->getUser()->identity->group_id >= 71
                                ) {
                                    $options = [
                                        'title' => 'XXXXX',
                                        'aria-label' => 'erase',
                                        //                                    'data-pjax' => 'w0',
                                        'data-confirm' => Yii::t('yii', 'Точно? Удаляем?'),
                                        'data-method' => 'POST',
                                    ];

                                    return Html::a(
                                        '<span class="glyphicon glyphicon-thumbs-down"></span>',
                                        $url, $options
                                    );
                                }

                            }

                            return '';
                        },


                    ],


                ],

                [
                    //                    final_destination
                    'attribute' => 'final_destination',
                    'value' => function ($url, $model, $key) {

                        if (isset($url['final_destination']) && (int)$url['final_destination'] == 1) {
                            return 'ЦС';
                        } elseif (isset($url['final_destination'])) {
                            return $url['final_destination'];
                        }

                        return '';
                    },

                    'contentOptions' => ['style' => 'width: 50px;overflow: hidden;'],
                ],


                [
                    'attribute' => 'f_first_bort',
                    'contentOptions' => ['style' => 'max-width: 50px;overflow: hidden;'],
                    'value' => function ($url, $model, $key) {
                        if (isset($url['f_first_bort']) && (int)$url['f_first_bort'] == 1) {
                            return 'Борт';
                        } else {
                            return 'Гос';
                        }
                    }
                ],
                [

                    'attribute' => 'deactive',
                    'value' => function ($url, $model, $key) {

                        if (isset($url['deactive']) && $url['deactive'] == 1) {
                            return 'Деактивизирован';
                        }

                        return '';
                    },

                    'contentOptions' => ['style' => 'width: 50px;overflow: hidden;'],
                ],

                [
                    'attribute' => 'date_create',
                    'contentOptions' => ['style' => 'width: 70px;'],
                ],

            ],
        ]
    );


    ?>

    <?php Pjax::end(); ?>

</div>



