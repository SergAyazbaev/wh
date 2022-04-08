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
        ->textInput([
            'placeholder' => $model->getAttributeLabel('id'),
            'style' => 'width: 77px; margin-right: 5px;',
            'readonly' => 'readonly'
        ])
        ->label(false);
    ?>

    <!--    --><? //= $form->field($model, 'bar_code')
    //        ->textInput([
    //            'placeholder' => $model->getAttributeLabel('bar_code'),
    //            'style' => 'width: 177px; margin-right: 5px;',
    //        ])
    //        ->label(false);
    //    ?>
    <?php
    //ddd($pool);

    echo $form->field($model, 'bar_code')
        ->textInput()
        ->label(false);
    ?>





    <?= $form->field($model, 'short_name')
        ->textInput([
                'placeholder' => $model->getAttributeLabel('short_name'),
                'style' => 'width: 477px; margin-right: 5px;',
            ]
        )
        ->label(false);
    ?>

    <?= $form->field($model, 'diagnoz')
        ->textarea([
                'placeholder' => $model->getAttributeLabel('diagnoz'),
            ]
        )
        ->label(false);
    ?>

    <?= $form->field($model, 'decision')
        ->textarea([
                'placeholder' => $model->getAttributeLabel('decision'),
            ]
        )
        ->label(false);
    ?>

    <?= $form->field($model, 'list_details')
        ->textarea([
                'placeholder' => $model->getAttributeLabel('list_details'),
            ]
        )
        ->label(false);
    ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
