<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Wh */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wh-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'dt_create') ?>

    <?= $form->field($model, 'logical') ?>

    <?= $form->field($model, 'coordX') ?>

    <?= $form->field($model, '222') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
