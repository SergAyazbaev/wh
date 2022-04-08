<?php

use frontend\models\postsprwhelement;
use frontend\models\postsprwhtop;
//use frontend\components\MyHelpers;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;


if ( empty( $model->parent_id ) ) $model->parent_id = 0;
?>
<style>
    div.sprtype-form {
        width: 45%;
        min-width: 590px;
        margin-left: 20%;
    }

    div.sprtype-update {
        background-color: #ffe4c438;
        width: 40%;
        display: block;
        float: left;
        margin-bottom: 5px;
        padding: 11px;
    }

    div.horisont {
        background-color: #ffe4c438;
        width: 100%;
        display: block;
        float: left;
        margin-bottom: 5px;
        padding: 1px 4%;
    }

    div.gos_nomer {
        background-color: #ffe4c438;
        width: 100%;
        display: grid;
        float: left;
        padding: 2px 20px;
        margin: 2px 1px;
    }
</style>


<?php $form = ActiveForm::begin(); ?>

<div class="sprtype-form">
    <div class="horisont">

        <?= $form->field($model, 'id')
            ->textInput(['placeholder' => $model->getAttributeLabel('id'),
                'style' => 'width: 77px; margin-right: 5px;',
            ])
            ->label(false);
        ?>


        <?= $form->field($model, 'parent_id')
            ->dropdownList(
                ArrayHelper::map(postsprwhtop::find()
                    ->orderBy('name ASC')
                    ->all(), 'id', 'name'),
                [
                    'prompt' => 'Выбор Склада',
                    'style' => 'width: 477px; margin-right: 5px;',
                ]
            )
            ->label(false);
        ?>
    </div>
    <div class="horisont">
        <?php
        $xx = postsprwhelement::find()->all();
        $type_words = ArrayHelper::getColumn( $xx, 'name' );

        echo $form->field( $model, 'name' )
            ->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $type_words,
                ],
            ] )
            ->textInput( [ 'placeholder' => $model->getAttributeLabel( 'name' ),
                'style' => 'width: 277px; margin-right: 5px;',

            ] )
            ->label( false );
        ?>


        <?= $form->field($model, 'nomer_borta')
            ->textInput(['placeholder' => $model->getAttributeLabel('nomer_borta'),
                'style' => 'width: 77px; margin-right: 5px;',
            ] )
            ->label(false);
        ?>

        <?= $form->field($model, 'nomer_gos_registr')
            ->textInput(['placeholder' => $model->getAttributeLabel('nomer_gos_registr'),
                'style' => 'width: 140px; margin-right: 5px;',
            ] )
            ->label(false);
        ?>

        <?= $form->field($model, 'nomer_traktor')
            ->textInput(['placeholder' => $model->getAttributeLabel('nomer_traktor'),
                'style' => 'width: 140px; margin-right: 5px;',
            ])
            ->label(false);
        ?>

        <?= $form->field($model, 'nomer_vin')
            ->textInput(['placeholder' => $model->getAttributeLabel('nomer_vin'),
                'style' => 'width: 140px; margin-right: 5px;',
            ] )
            ->label(false);
        ?>


        <?= $form->field($model, 'final_destination')
            ->checkbox()
            ->label(false);
        ?>

        <?= $form->field( $model, 'f_first_bort' )
            ->checkbox();
        ?>


        <?= $form->field($model, 'deactive')
            ->checkbox()
            ->label( false );
        ?>


    </div>

    <div class="horisont">
        <?= $form->field($model, 'tx')
            ->textarea(['placeholder' => $model->getAttributeLabel('tx'),
                           'style' => 'width: 717px; margin: 0px; height: 250px;' ]
            )
            ->label(false);
        ?>

    </div>
    <div class="horisont">

        <div class="form-group">
            <?= Html::submitButton( 'Сохранить', [
                'class' => 'btn btn-success',
                //'onclick' => 'self.close();document.location.reload(true);',
                'onclick' => 'window.opener.location.reload(true);',
                //'onclick' => 'self.close();location.reload(true);',

                //'onsubmit' => 'window.opener.location.reload(true);  window.close()',
                //'onsubmit' => 'window.opener.location.reload(true)',

                //'onsubmit' => '$.pjax.reload({container: "#pjax_1"});window.close()',
            ] )

            ?>

        </div>


    </div>


    <?php
    echo Html::a(
        'Выход', [ '/sprwhelement/return_to_refer' ],
        [
            'onclick'=>"window.history.back();",
            'class' => 'btn btn-warning',
        ] );
    ?>

</div>

<?php ActiveForm::end(); ?>