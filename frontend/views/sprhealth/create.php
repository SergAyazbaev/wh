<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Sprhealth */

$this->title = 'Создать новое';
$this->params['breadcrumbs'][] = ['label' => 'Справочник состояний', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprhealth-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
