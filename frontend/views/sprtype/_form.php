<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sprtype-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')
        ->textInput(['placeholder' => $model->getAttributeLabel('id')])
        ->label(false);
    ?>
    <?= $form->field($model, 'name')
        ->textInput(['placeholder' => $model->getAttributeLabel('name')])
        ->label(false);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
