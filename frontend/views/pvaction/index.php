<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postpvaction */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'pv Actions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pv-action-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create pv Action', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'name',
            'author',
            'content',
            'comments',
            //'dt_create',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
