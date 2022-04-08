<?php

use yii\helpers\Html;



$this->title = 'Создать: ' . $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Приборы учета (полный список)', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Создать';
?>
<div class="pv-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_read_only', [
        'model' => $model,
        'new_doc' => $new_doc,
    ]) ?>

</div>
