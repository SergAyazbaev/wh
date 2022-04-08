<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\installset */

$this->title = 'Создаем установочный набор';
$this->params['breadcrumbs'][] = ['label' => 'Установочные наборы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="installset-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
