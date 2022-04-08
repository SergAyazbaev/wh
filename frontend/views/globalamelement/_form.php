<?php

use frontend\models\post_spr_globam;
use frontend\models\post_spr_globam_element;
use \yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */
/* @var $form yii\widgets\ActiveForm */


if (empty($model->parent_id)) $model->parent_id = 0;
?>

<style>
    div.horisont {
        background-color: #ffe4c438;
        width: 100%;
        display: block;
        float: left;
        margin-bottom: 5px;
        padding: 11px;
    }

    div.sprtype-update {
        width: 50%;
        display: block;
        position: absolute;
        top: 100px;
        left: 100px;
    }
</style>



<?php $form = ActiveForm::begin(); ?>
<div class="sprtype-form">

    <div class="horisont">


        <?= $form->field($model, 'id')
            ->textInput(['placeholder' => $model->getAttributeLabel('id'),
                'style' => 'width: 77px; margin-right: 5px;',
                'readonly' => 'readonly'])
            ->label(false);
        ?>

        <?= $form->field($model, 'cc_id')
            ->textInput(['placeholder' => '1C код',
                'style' => 'width: 177px; margin-right: 5px;',
            ])
            ->label(false);
        ?>
        <?= $form->field($model, 'parent_id')
            ->dropdownList(
                ArrayHelper::map(post_spr_globam::find()->all(), 'id', 'name'),
                [
                    'prompt' => 'Выбор ....'
                ]
            )
            ->label(false);
        ?>

        <?= $form->field($model, 'intelligent')
            ->dropdownList([0 => "Штрихкода не имеет", 1 => "Имеет Штрихкод"],


                [
                    'prompt' => 'Выбор ....'
                ]
            )
            ->label(false);
        ?>


    </div>

    <div class="horisont">


        <?
        $xx = post_spr_globam_element::find()->all();
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

        <?= $form->field($model, 'short_name')
            ->textarea(['placeholder' => $model->getAttributeLabel('Сокращенное наименование для АСУОП'),
                'style' => 'width: 370px; margin-right: 5px;'])
            ->label(false);
        ?>


        <?
        $xx = post_spr_globam_element::find()->all();
        $type_words = ArrayHelper::getColumn($xx, 'tx');
        ?>

        <?= $form->field($model, 'tx')
            ->widget(
                AutoComplete::className(), [
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

    </div>

</div>

<?php ActiveForm::end(); ?>
