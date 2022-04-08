<?php

use frontend\models\Sprwhtop;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */
/* @var $form yii\widgets\ActiveForm */


if (empty($model->parent_id)) $model->parent_id=0;
?>
<style>
    .wrap {
        width: 600px;
    }

    .sprtype-form {
        width: 60%;
        padding: 20px 40px;
        background-color: antiquewhite;
    }

</style>

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
            'style' => 'width: 370px; margin-right: 5px;',
            'readonly' => 'readonly'
        ])
        ->label(false);
    ?>

    <?= $form->field($model, 'tx')
        ->textarea(['placeholder' => $model->getAttributeLabel('tx')])
        ->label(false);
    ?>

    <?= $form->field($model, 'final_destination')
        ->checkbox()
        ->label(false);
    ?>


    <?= $form->field($model, 'buses_variant')
        ->radioList(
                [
                    Sprwhtop::BUSES_VARIANT_NO=>'Нет',
                    Sprwhtop::BUSES_VARIANT_ALL=>'Да.Ко всем.'
                ])
        ->label('Применить ко всем дочерним складам?');
    ?>




    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
