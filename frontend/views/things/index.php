<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Справочник. Единицы измерения.';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('ЕдИзм. Создать новую единицу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=> 'id',
                'value'=> 'id',
                'contentOptions' => ['style' => 'width: 80px;max-width: 125px;overflow: auto;'],
            ],

            [
                'attribute'=> 'name',
                'value'=> 'name',
                'contentOptions' => ['style' => 'max-width: 325px;overflow: auto;'],
            ],

            [
                'attribute'=> 'tx',
                'value'=> 'tx',
                'contentOptions' => ['style' => 'width: 180px;max-width: 325px;overflow: auto;'],
            ],
            [
                'attribute' => 'type_num',
                'contentOptions' => ['style' => 'width: 180px;max-width: 325px;overflow: auto;'],
            ],

            [
                'attribute'=> 'dt_create',
                'value'=> 'dt_create',
                'contentOptions' => ['style' => 'width: 180px;max-width: 325px;overflow: auto;'],
            ],
            [
                'attribute'=> 'dt_update',
                'value'=> 'dt_update',
                'contentOptions' => ['style' => 'width: 180px;max-width: 325px;overflow: auto;'],
            ],


//            "user_id",
            "user_name",


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url,$model) {

                        if ( Yii::$app->getUser()->identity->group_id==100 ){
                            return Html::a(
                                '<span class="glyphicon glyphicon glyphicon-ok"></span>',
                                $url);
                        }
                        return '';

                    },
                    'update' => function ($url,$model) {

                        if ( Yii::$app->getUser()->identity->group_id==100 ){
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                $url);
                        }
                        return '';

                    },
                    'delete' => function ($url,$model) {

                        if ( Yii::$app->getUser()->identity->group_id==100 ){
                            return Html::a(
                                '<span class="glyphicon glyphicon glyphicon-trash"></span>',
                                $url);
                        }
                        return '';

                    },
                ],


            ],

        ],
    ]); ?>
</div>
