<?php

use frontend\models\Spr_glob;

use frontend\models\Spr_things;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


$this->title = 'Справочник. УМЦ';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .tree_land {
        width: 450px;
        min-width: 300px;
        background-color: cornsilk;
        padding: 15px;
    }
</style>


<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Создать новый Компонент', ['create'], ['class' => 'btn btn-success'])
    ?>

    <?= Html::a('Связать с кодами 1С', ['apend'], ['class' => 'btn btn-success'])
    ?>


    <?php
    echo $this->render('_excel', [
        'model' => $searchModel,
        'para' => $para
    ]);
    ?>


</div>


<div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //            ['class' => 'yii\grid\SerialColumn'],


            [
                'attribute' => 'parent_id',
                'filter' =>
                    ArrayHelper::map(
                        Spr_glob::find()->orderBy(['name'])->all(),
                        'id', 'name'),

                //'value'=> 'parent_id',
                'value' => 'spr_glob.name',
                'contentOptions' => ['style' => 'width: 180px;max-width: 225px;overflow: auto;'],
            ],

            [
                'attribute' => 'id',
                'value' => 'id',
                'contentOptions' => ['style' => 'width: 80px;max-width: 125px;overflow: auto;'],
            ],

            [
                'attribute' => 'cc_id',
                'value' => 'cc_id',
            ],


            [
                'attribute' => 'name',
                'value' => 'name',
                'contentOptions' => ['style' => 'max-width: 325px;overflow: hidden;'],
            ],

            [
                //                'header'=>'',
                'content' => function ($model) {
                    return ArrayHelper::getValue(
                        ArrayHelper::map(Spr_things::find()->all(), 'id', 'name'),
                        $model['ed_izm']);
                },

                'filter' =>
                    ArrayHelper::map(
                        Spr_things::find()
                            ->orderBy(['id'])
                            ->all(),
                        'id', 'name'),

                'attribute' => 'ed_izm',
                'contentOptions' => ['style' => 'min-width: 110px;max-width: 125px;overflow: auto;'],
            ],

            [
                'attribute' => 'tx',
                'value' => 'tx',
                'contentOptions' => ['style' => 'width: 180px;max-width: 325px;overflow: hidden;'],
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        //Для Талгата
                        if (
                            Yii::$app->getUser()->identity->group_id == 71 ||
                            Yii::$app->getUser()->identity->group_id == 100
                        ) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                $url,
                                ['target' => '_blank']);
                        }

                        return '';

                    },
                    'delete' => function ($url, $model) {

                        //Для Талгата
                        if (
                            Yii::$app->getUser()->identity->group_id == 71 ||
                            Yii::$app->getUser()->identity->group_id == 100
                        ) {
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


