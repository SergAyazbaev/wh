<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Spraction */

$this->title = 'Справочник действий: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Справочник действий', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Сохранить';
?>
<div class="spraction-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
