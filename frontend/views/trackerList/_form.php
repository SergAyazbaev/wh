<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\trackerlist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trackerlist-form">

    <?php
    $form = ActiveForm::begin();
    ?>

            <?= $form->field($model, 'name') ?>

            <?= $form->field($model, 'pv_id') ?>

            <?= $form->field($model, 'group_pv')?>

            <?= $form->field($model, 'type_pv') ?>

            <?= $form->field($model, 'service_pv') ?>

            <?= $form->field($model, 'dt_load') ?>

            <?= $form->field($model, 'dt_output') ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>


    <?php
    ActiveForm::end();
    ?>

</div>
