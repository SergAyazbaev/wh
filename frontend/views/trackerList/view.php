<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\trackerlist */

$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Перемещение приборов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trackerlist-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Сохранить', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы хотите УДАЛИТЬ запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'id',
            'name',
            'pv_id',
            'group_pv',
            'type_pv',
            'service_pv',
            'dt_load',
            'dt_output',
            'dt_create_mongo',

        ],
    ]) ?>

</div>
