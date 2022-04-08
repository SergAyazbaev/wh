<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Pvaction */

$this->title = 'Ремонт';
$this->params['breadcrumbs'][] = ['label' => 'История Ремонтов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pv-action-create">

    <h1><?= Html::encode($this->title) ?></h1>
<?
dd($model_pv);
?>
    <?= $this->render('_form', [
        'model' => $model,
        'model_pv' => $model_pv,

    ]) ?>

</div>
