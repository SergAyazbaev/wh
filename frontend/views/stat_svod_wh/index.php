<?php

use frontend\models\Spr_glob;
use frontend\models\Spr_globam;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'Выбираем группу';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .glyphicon:hover {
        background-color: aquamarine;
        border-radius: 20px;
        padding: 0px;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
        width: 26px;
    }

    .glyphicon-eye-open::before {
        width: 26px;
    }

    span.glyphicon {
        width: 26px;
    }

    td > a {
        text-decoration: none;
        float: left;
    }

</style>

<div class="sprtype-index">
    <h1>Амортизация АСУОП</h1>

    <p>
        <?= Html::a('Все позиции AP (' . $ap . ')',
            ['/stat_svod/tab_at_name?ap=' . $ap . '&tabl=1&id=2&parent_id=1'],
            ['class' => 'btn btn-success']) ?>
    </p>


    <?php $dataProvider->pagination->pageSize = 10; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'header' => '',
                'contentOptions' => ['style' => ' width: 50px;'],
                'content' => function ($model) {
                    $text_ret = '';

                    //ddd($model);

                    $url = Url::to(['/stat_ostatki/ost_element_one?tabl=1&id=' . $model->id . '&parent_id=' . $model->parent_id]);

                    $text_ret .= Html::a('GRAF', $url, [
                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
                        'data-id' => $model->_id,

                    ]);


                    return $text_ret;

                }

            ],

            [
                'attribute' => 'id',
                'value' => 'id',
                'contentOptions' => ['style' => 'width: 80px;max-width: 125px;overflow: auto;'],
            ],

            [
                'attribute' => 'parent_id',
                'filter' =>
                    ArrayHelper::map(
                        Spr_globam::find()->orderBy(['name'])->all(),
                        'id', 'name'),

                'value' => 'parent_id',
                'contentOptions' => ['style' => 'width: 180px;max-width: 225px;overflow: auto;'],
            ],


            [
                'attribute' => 'name',
                'value' => 'name',
                'contentOptions' => ['style' => 'max-width: 325px;overflow: hidden;'],
            ],


            [
                'attribute' => 'tx',
                'value' => 'tx',
                'contentOptions' => ['style' => 'width: 180px;max-width: 325px;overflow: hidden;'],
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    //                    'view' => function ($url,$model) {
                    //
                    //                        if ( Yii::$app->getUser()->identity->group_id==100 ){
                    //                            return Html::a(
                    //                                '<span class="glyphicon glyphicon-ok"></span>',
                    //                                $url);
                    //                        }
                    //                        return '';
                    //
                    //                    },


                    'update' => function ($url, $model) {

                        if (Yii::$app->getUser()->identity->group_id >= 70) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                $url);
                        }

                        return '';

                    },
                    'delete' => function ($url, $model) {

                        if (Yii::$app->getUser()->identity->group_id >= 100) {
                            return Html::a(
                                '<span class="glyphicon glyphicon glyphicon-trash"></span>',
                                $url,
                                [
                                    'data-method' => 'POST',
                                    'data-confirm' => Yii::t('yii',
                                        'УДАЛЯЕМ ЭТУ ПОЗИЦИЮ?'),]
                            );
                        }

                        return '';

                    },
                ],


            ],
        ],
    ]); ?>


    <h1>Списание</h1>
    <?php $dataProvider2->pagination->pageSize = 10; ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'filterModel' => $searchModel2,
        'columns' => [
            [
                'header' => '',
                'contentOptions' => ['style' => ' width: 50px;'],
                'content' => function ($model) {
                    $text_ret = '';
                    //ddd($model);

                    $url = Url::to(['/stat_ostatki/ost_element_one?tabl=2&id=' . $model->id . '&parent_id=' . $model->parent_id]);

                    $text_ret .= Html::a('GRAF', $url, [
                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
                        'data-id' => $model->_id,

                    ]);

                    return $text_ret;

                }

            ],

            [
                'attribute' => 'id',
                'value' => 'id',
                'contentOptions' => ['style' => 'width: 80px;max-width: 125px;overflow: auto;'],
            ],

            [
                'attribute' => 'parent_id',
                'filter' =>
                    ArrayHelper::map(
                        Spr_glob::find()->orderBy(['name'])->all(),
                        'id', 'name'),

                'value' => 'parent_id',
                'contentOptions' => ['style' => 'width: 180px;max-width: 225px;overflow: auto;'],
            ],


            [
                'attribute' => 'name',
                'value' => 'name',
                'contentOptions' => ['style' => 'max-width: 325px;overflow: hidden;'],
            ],


            [
                'attribute' => 'tx',
                'value' => 'tx',
                'contentOptions' => ['style' => 'width: 180px;max-width: 325px;overflow: hidden;'],
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    //                    'view' => function ($url,$model) {
                    //
                    //                        if ( Yii::$app->getUser()->identity->group_id==100 ){
                    //                            return Html::a(
                    //                                '<span class="glyphicon glyphicon-ok"></span>',
                    //                                $url);
                    //                        }
                    //                        return '';
                    //
                    //                    },


                    'update' => function ($url, $model) {

                        if (Yii::$app->getUser()->identity->group_id >= 70) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                $url);
                        }

                        return '';

                    },
                    'delete' => function ($url, $model) {

                        if (Yii::$app->getUser()->identity->group_id >= 100) {
                            return Html::a(
                                '<span class="glyphicon glyphicon glyphicon-trash"></span>',
                                $url,
                                [
                                    'data-method' => 'POST',
                                    'data-confirm' => Yii::t('yii',
                                        'УДАЛЯЕМ ЭТУ ПОЗИЦИЮ?'),]
                            );
                        }

                        return '';

                    },
                ],


            ],
        ],
    ]); ?>

</div>


