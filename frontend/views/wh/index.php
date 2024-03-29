<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postwh */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Whs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Wh', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            '_id',

            'name',
            'dt_create',
            'logical',
            'coordX',
            '222',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
