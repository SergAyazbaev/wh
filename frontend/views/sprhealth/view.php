<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sprhealth */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Справочник состояний', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprhealth-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Сохранить', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы хотите удалить эту запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'_id',
            'name',
        ],
    ]) ?>

</div>
