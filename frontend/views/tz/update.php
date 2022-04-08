<?php

echo "<br>";

$this->title = 'Техническое Задание';
$this->params['breadcrumbs'][] = ['label' => 'Приборы учета (полный список)', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->_id, 'url' => ['view', 'id' => (string)$model->_id]];
$this->params['breadcrumbs'][] = 'Создать';
?>
<div class="pv-update">

    <h1><?//= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_tz', [
        'model' => $model,
        'items_auto' => $items_auto,
    ]) ?>

</div>
