<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>



    <?php

        $form = ActiveForm::begin([
                //'action' => ['index'],
                //'action' => ['excel?sort=-parent_id'],
            'action' => ['excel?sort='.$para['sort']],
            'method' => 'get',
        ]);


    ?>

    <?= Html::submitButton('To EXCEL (М)', [
        'class' => 'btn btn-default',
        'name'=>'print',
        'value' => 1,

    ]
    ) ?>
<?
//ddd($model);
//        'id' => '2'
//        'parent_id' => '5'
//        'name' => ''
//        'ed_izm' => ''
//        'tx' => ''
//        '_id' => ''
?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'parent_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'name')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'ed_izm')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'tx')->hiddenInput()->label(false) ?>

<!--    <div class="form-group">-->
<!--        --><?//= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
<!--        --><?//= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
<!--    </div>-->

    <?php ActiveForm::end(); ?>

