<?php

use frontend\models\Spr_globam;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Справочник.АСУОП';
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

    <p>
        <?= Html::a('Создать новый Элемент', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['id' => 'w0']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //            ['class' => 'yii\grid\SerialColumn'],


            [
                'attribute' => 'parent_id',
                'filter' =>
                    ArrayHelper::map(
                        Spr_globam::find()->orderBy(['name'])->all(),
                        'id', 'name'),

                //'value' => 'parent_id',
                'value' => 'spr_globam.name',
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
                'contentOptions' => ['style' => 'width: 80px;max-width: 125px;overflow: auto;'],
            ],


            [
                'attribute' => 'intelligent',
                'filter' => ['0' => "Нет", '1' => "Да"],

                'content' => function ($model) {

                    return ArrayHelper::getValue([
                        0 => 'Нет',
                        1 => 'Да'], $model['intelligent']);
                },


                'value' => 'intelligent',
                'contentOptions' => ['style' => 'width: 80px;max-width: 325px;overflow: auto;'],
            ],


            [
                'attribute' => 'name',
                'contentOptions' => ['style' => 'width:150px;overflow: auto;'],
            ],

            [
                'attribute' => 'short_name',
                'contentOptions' => ['style' => 'max-width: 325px;overflow: auto;'],
            ],

            [
                'attribute' => 'tx',
                'contentOptions' => ['style' => 'width: 180px;max-width: 325px;overflow: auto;'],
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{view} {update} {delete}',
                'template' => '{update} {delete}',
                'buttons' => [

                    'update' => function ($url) {

                        //Для Талгата
                        if (
                            Yii::$app->getUser()->identity->group_id == 71 ||
                            Yii::$app->getUser()->identity->group_id == 100
                        ) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                $url);
                        }
                        return '';

                    },

                    'delete' => function ($url) {

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


    <?php Pjax::end(); ?>
</div>


