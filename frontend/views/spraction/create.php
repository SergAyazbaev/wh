<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Spraction */

$this->title = 'Создать название действия';
$this->params['breadcrumbs'][] = ['label' => 'Справочник действий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spraction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
