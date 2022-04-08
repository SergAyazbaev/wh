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
        ->textInput(['placeholder' => $model->getAttributeLabel('id'),
            'style' => 'width: 77px; margin-right: 5px;',
            'readonly' => 'readonly'])
        ->label(false);
    ?>

    <?= $form->field($model, 'name')
        ->textarea(['placeholder' => $model->getAttributeLabel('name'),
            'style' => 'width: 477px; margin-right: 5px;',
        ])
        ->label(false);
    ?>

    <?= $form->field($model, 'tx')
        ->textarea(['placeholder' => $model->getAttributeLabel('tx'),
            'style' => 'width: 177px; margin-right: 5px;',
        ])
        ->label(false);
    ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
