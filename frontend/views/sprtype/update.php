<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */

$this->title = 'Изменить название: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Справочник типов приборов учета', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="sprtype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


</div>


