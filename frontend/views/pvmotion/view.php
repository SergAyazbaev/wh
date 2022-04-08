<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Pvaction */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'pv Actions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pv-action-view">

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
            '_id',
            'name',
            //'author',
            'wh_top',
            'wh_top_name',
            'wh_element',

            'content',
            'comments',
            'dt_create',
        ],
    ]) ?>

</div>
