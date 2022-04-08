<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PostTrackerList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trackerlist-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'pv_id') ?>

    <?= $form->field($model, 'group_pv') ?>

    <?= $form->field($model, 'type_pv') ?>

    <?= $form->field($model, 'service_pv') ?>

    <?php // echo $form->field($model, 'dt_load') ?>

    <?php // echo $form->field($model, 'dt_output') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
