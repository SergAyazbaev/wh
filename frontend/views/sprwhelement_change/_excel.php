<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>



    <?php

        $form = ActiveForm::begin([
                //'action' => ['index'],
//                'action' => ['/sprwhelement/excel?sort=-parent_id'],
            'action' => ['/sprwhelement/excel?sort=' . (isset($para['sort']) ? $para['sort'] : '')],
            //'method' => 'get',
            'method' => 'post',
        ]);


    ?>

    <?= Html::submitButton('Фильтруемая выгрузка в EXCEL ', [
        'class' => 'btn btn-default',
        'name'=>'print',
        'value' => 1,

    ]
    ) ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'parent_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'name')->hiddenInput()->label(false) ?>



    <?php ActiveForm::end(); ?>

