<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PostTrackerList */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Перемещение приборов (Tracker)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trackerlist-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>






    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'pv_id',
            'group_pv',
            'type_pv',
            'service_pv',
            'dt_load',
            'dt_output',
            '_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

