<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Spraction */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Spractions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spraction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Сохранить', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'name',
        ],
    ]) ?>

</div>
