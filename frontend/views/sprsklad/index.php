<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postsprtype */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочник складов компании';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php


//    dd(1231231);

    // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать новый Склад', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            '_id',
            'id',
            'name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
