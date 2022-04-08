<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Pvaction */

//dd($model);

$this->title = 'Изменить запись о перемещении: ' . $model->pv_id ;
$this->params['breadcrumbs'][] = ['label' => 'История перемещений', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pv_id, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Сохранить';
?>
<div class="pv-action-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
