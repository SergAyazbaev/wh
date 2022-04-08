<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Справочник Групп типов приборов учета', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprtype-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Записать', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => (string)$model->_id], [
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
            'id',
            'name',
        ],
    ]) ?>

</div>
