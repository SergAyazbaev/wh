<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postsprtype */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочник. Комплектующие. Списание. Группа ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Списание.Создать новую Группу. ', ['create'], ['class' => 'btn btn-success']) ?>
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
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {update} {delete}',
                'buttons' => [

                    'update' => function ($url,$model) {
                        //Для Талгата
                        if ( Yii::$app->getUser()->identity->group_id == 71 ){
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                $url);
                        }
                        return '';

                    },
                    'delete' => function ($url,$model) {

                        //Для Талгата
                        if ( Yii::$app->getUser()->identity->group_id == 71 ){
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
