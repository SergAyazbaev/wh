<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postsprtype */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочник типы документов ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать новую запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'tx',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
