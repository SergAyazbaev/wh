<?php

use yii\grid\GridView;
use yii\helpers\Html;


$this->title = 'Справочник Неисправностей';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Описать новую неисправность', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [

                'id',
                'name',
//                'delete',
                'tx',

//                [
//                    'class' => 'yii\grid\ActionColumn',
//                    'template' => '{update} {delete}',
//                    'buttons' => [
//
//                        'update' => function ( $url, $model ) {
//
//                            if (
//                                Yii::$app->getUser()->identity->group_id == 100 ||
//                                Yii::$app->getUser()->identity->group_id == 71
//                            ) {
//                                return Html::a(
//                                    '<span class="glyphicon glyphicon-edit"></span>',
//                                    $url
//                                );
//                            }
//                            return '';
//
//                        },
//
//                        'delete' => function ( $url, $model ) {
//
//                            if ( Yii::$app->getUser()->identity->group_id == 71 ) {
//                                return Html::a(
//                                    '<span class="glyphicon glyphicon-trash"></span>',
//                                    $url
//                                );
//                            }
//
//                            return '';
//
//                        },
//                    ],
//
//
//                ],


            ],
        ]
    );
    ?>
</div>
