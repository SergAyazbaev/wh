<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;


if (empty($model->parent_id)) $model->parent_id=0;
?>
<style>
    div.horisont{
        background-color: #ffe4c438;
        width: 100%;
        display: block;
        float: left;
        margin-bottom: 5px;
        padding: 11px;
    }
</style>


<div class="sprtype-form">
    <div class="horisont">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'tz_id')
        ->textInput(['placeholder' => $model->getAttributeLabel('tz_id'),
            'style' => 'width: 77px; margin-right: 5px;',
            'readonly' => 'readonly'])
        ->label(false);
    ?>

    <?= $form->field($model, 'id')
        ->textInput(['placeholder' => $model->getAttributeLabel('id'),
            'style' => 'width: 77px; margin-right: 5px;',
            'readonly' => 'readonly'])
        ->label(false);
    ?>

    <?= $form->field($model, 'parent_id')
        ->dropdownList(
            \yii\helpers\ArrayHelper::map(frontend\models\postsprwhtop::find()->all(), 'id', 'name'),
            //frontend\models\Typemotion::find()->select(  ['name']  )-> indexBy('name') ->column(),
            [
                'prompt' => 'Выбор Склада'
            ]
        )
        ->label(false);
    ?>
    </div>
    <div class="horisont">

        <?

//        'id',
//            'parent_id',

//            'name',
//            'nomer_borta',
//            'nomer_gos_registr',
//            'tx',

        $xx = frontend\models\Tzautoelement::find()->all();
        $type_words= \yii\helpers\ArrayHelper::getColumn($xx,'name');
        ?>

        <?= $form->field($model, 'name')
            ->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $type_words,
                ],
            ])
            ->textInput(['placeholder' => "Название, марка и т.п.",
                'style' => 'width: 277px; margin-right: 5px;'])
            ->label(false);
        ?>


    <?= $form->field($model, 'nomer_borta')
        ->textInput(['placeholder' => $model->getAttributeLabel('nomer_borta'),
            'style' => 'width: 77px; margin-right: 5px;'])
        ->label(false);
    ?>

    <?= $form->field($model, 'nomer_gos_registr')
        ->textInput(['placeholder' => $model->getAttributeLabel('nomer_gos_registr'),
            'style' => 'width: 140px; margin-right: 5px;'])
        ->label(false);
    ?>

    <?= $form->field($model, 'nomer_vin')
        ->textInput(['placeholder' => $model->getAttributeLabel('nomer_vin'),
            'style' => 'width: 140px; margin-right: 5px;'])
        ->label(false);
    ?>


    </div>

    <div class="horisont">
    <?= $form->field($model, 'tx')
        ->textarea(['placeholder' => $model->getAttributeLabel('tx'),
            'style' => 'width: 477px; margin-right: 5px;'])
        ->label(false);
    ?>

    </div>
    <div class="horisont">

    <div class="form-group">

        <?= Html::submitButton('Сохранить',
            ['class' => 'btn btn-success']) ?>

        <?= Html::Button('&#8593;',
            [
                'class' => 'btn btn-warning',
                'onclick'=>"window.history.back();"
            ])
        ?>

    </div>

    <?php ActiveForm::end();

    frontend\components\MyHelpers::WH_BinaryTree()
    ?>
    </div>

</div>
