<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Wh */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Whs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            '_id',
            'name',
            'dt_create',
            'logical',
            'coordX',
            '222',
        ],
    ]) ?>

</div>
