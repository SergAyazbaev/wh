<?php

use frontend\models\post_spr_glob;
use frontend\models\post_spr_glob_element;
use frontend\models\Spr_pd;
use frontend\models\Spr_things;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;



if (empty($model->parent_id)) $model->parent_id=0;
?>
<style>
    .wrap {
        padding: 10px;
        padding-bottom: 10px;
        padding-bottom: 100px;
        margin: 1px 11%;
        width: 735px;
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
                ArrayHelper::map(post_spr_glob::find()->all(), 'id', 'name'),
                [
                    'prompt' => 'Выбор группы... '
                ]
            )
            ->label(false);
        ?>

        <?= $form->field($model, 'cc_id')
            ->textInput(['placeholder' => '1c числовой код',
                'style' => 'width: 177px; margin-right: 5px;',
            ])
            ->label(false);
        ?>


    </div>

    <div class="horisont">


        <?
        $xx = post_spr_glob_element::find()->all();
        $type_words = ArrayHelper::getColumn($xx, 'name');
        ?>

        <?= $form->field($model, 'name')
            ->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $type_words,
                ],
            ])
            ->textarea(['placeholder' => $model->getAttributeLabel('name'),
                        'style' => 'width: 370px; margin-right: 5px;'])
            ->label(false);
        ?>


        <?
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


        <?= $form->field($model, 'pd_id')
            ->dropDownList(

                ArrayHelper::map( Spr_pd::find()->all(),'id','name'),

                    [   'prompt' => 'Выбор подраздела ...',]
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
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']);

            ?>
        </div>

        <?php ActiveForm::end();

          frontend\components\MyHelpers::WH_BinaryTree()
        ?>
    </div>

</div>
