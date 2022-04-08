<?php

use frontend\models\post_spr_glob_element;
use frontend\models\Spr_glob;
use frontend\models\Spr_things;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */
/* @var $form yii\widgets\ActiveForm */


if (empty($model->parent_id)) $model->parent_id=0;

?>

<style>
    .sprtype-form{
        width: 80%;
        max-width: 400px;
    }

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

        <?= $form->field($model, 'id')
            ->textInput(['placeholder' => $model->getAttributeLabel('id'),
                'style' => 'width: 77px; margin-right: 5px;',
                'readonly' => 'readonly'])
            ->label(false);
        ?>

        <?= $form->field($model, 'parent_id')
            ->dropdownList(
                ArrayHelper::map(Spr_glob::find()->all(), 'id', 'name'),                
            [
                'prompt' => 'Выбор группы... '
            ]
        )
            ->label(false);
        ?>


    </div>

    <div class="horisont">


        <?= $form->field($model, 'all_from_txt')
            ->textarea(['placeholder' => 'Сюда можно копипастить весь столбец сразу из Эксела',
                        'style' => 'width: 370px; margin-right: 5px;height: 210px;    font-size: 12px;'])
            ->label(false);
        ?>


        <?php
        $xx = post_spr_glob_element::find()->all();
        $type_words= ArrayHelper::getColumn($xx,'tx');
        ?>

        <?= $form->field($model, 'ed_izm')
            ->dropDownList(

                ArrayHelper::map( Spr_things::find()->all(),'id','name'),

                    [   'prompt' => 'Выбор расчетных единиц  ...',]
                )
            ;
        ?>

        <?= $form->field($model, 'tx')
            ->widget(AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $type_words,
                ],
            ])
            ->textarea(['placeholder' => $model->getAttributeLabel('tx'),
                'style' => 'width: 370px; margin-right: 5px;'])
            ->label(false);
        ?>


        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end();

        //  frontend\components\MyHelpers::WH_BinaryTree()
        ?>
    </div>

</div>
