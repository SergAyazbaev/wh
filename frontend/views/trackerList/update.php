<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\trackerlist */

$this->title = 'Изменить запись: ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Перемещение приборов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="trackerlist-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
