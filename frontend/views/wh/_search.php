<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\postwh */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wh-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


                <?= $form->field($model, '_id') ?>

                <?= $form->field($model, 'name') ?>
                <?= $form->field($model, 'author') ?>
                <?= $form->field($model, 'content') ?>
                <?= $form->field($model, 'comments') ?>

                <?= $form->field($model, 'dt_create') ?>

                <?= $form->field($model, 'logical') ?>

                <?= $form->field($model, 'coordX') ?>

                <?= $form->field($model, '222') ?>

                <div class="form-group">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
                </div>

    <?php ActiveForm::end(); ?>

</div>
