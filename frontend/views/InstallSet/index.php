<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postinstallset */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Установочные наборы (InstallSets)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="installset-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Installset', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'id',
            'install_group',
            'ids_pv',
            '_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
