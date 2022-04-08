<?php


use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

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

    <p>
        <?= Html::a('Замена ГОС, БОРТ', ['change_gos_bort'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?= Html::a('Заливка из EXCEL', ['input_from_excel'], ['class' => 'btn btn-primary']) ?>
    </p>


    <?php Pjax::begin(['id' => 'pjax-container',]); ?>

    <?php Pjax::begin(['id' => 'pjax_1']); ?>
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

            'columns' => [
                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 30px'],
                ],
                [
                    'attribute' => 'parent_id',
                    'contentOptions' => ['style' => 'width: 30px'],
                ],

                [

                    'attribute' => 'do_timestamp',
                    'contentOptions' => ['style' => 'width: 100px;'],
                    'format' => ['datetime', 'php:d.m.Y H:i:s',],
                ],


                [
                    'attribute' => 'sprwhelement.sprwhtop.name',
                    'contentOptions' => ['style' => 'min-width: 120px;white-space: pre-wrap;'],
                ],
                [
                    'attribute' => 'sprwhelement.name',
                    'contentOptions' => ['style' => 'width: 110px;white-space: pre-wrap;'],
                ],


                [
                    'attribute' => 'dt_cr_timestamp',
                    'contentOptions' => ['style' => 'width: 100px;'],
                    'format' => ['datetime', 'php:d.m.Y H:i:s',],
                ],
                [
                    'attribute' => 'doc_num',
                    'contentOptions' => ['style' => 'width: 190px;white-space: pre-wrap;'],
                ],


                [
                    'attribute' => 'old_bort',
                    'contentOptions' => ['style' => 'min-width: 115px;'],
                ],
                [
                    'attribute' => 'n_bort',
                    'contentOptions' => ['style' => 'min-width: 50px;'],
                ],

                [
                    'attribute' => 'old_gos',
                    'contentOptions' => ['style' => 'min-width: 115px;'],
                ],
                [
                    'attribute' => 'n_gos',
                    'contentOptions' => ['style' => 'min-width: 50px;'],
                ],


                [
                    'attribute' => 'tx',
                    'contentOptions' => ['style' => 'width: 110px;overflow: hidden;white-space: pre-wrap;'],
                ],

                [
                    'attribute' => 'user_name',
                    'contentOptions' => ['style' => 'width: 70px;'],
                ],
                [
                    'attribute' => 'user_do_timestamp',
                    'contentOptions' => ['style' => 'width: 100px;'],
                    'format' => ['datetime', 'php:d.m.Y H:i:s'],
                ],


                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete} ',

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
                                    'aria-label' => 'edit',
                                    'data-pjax' => 0,
                                    //'data-pjax' => 'w0',
                                    'data-method' => 'POST',

                                ];

                                return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, $options);
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


                    ],


                ],


            ],
        ]
    );


    ?>

    <?php Pjax::end(); ?>

</div>



