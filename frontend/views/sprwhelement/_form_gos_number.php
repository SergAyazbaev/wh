<?php

use frontend\models\postsprwhelement;
use frontend\models\postsprwhtop;
use frontend\components\MyHelpers;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;


if ( empty( $model->parent_id ) ) $model->parent_id = 0;
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

    div.popup_gos {
        height: 500px;
        display: block;
        position: absolute;
        top: 15%;
        left: 30%;
        background-color: aquamarine;
        padding: 20px 80px;
        /*box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);*/
        box-shadow: 5px 6px 20px 9px rgba(0, 0, 0, 0.125);
    }
</style>


<div class="sprtype-form">
    <div class="horisont">

        <?php $form = ActiveForm::begin( [
            'action' => 'gos',
            'method' => 'GET',
            'options' => [
                'data-pjax' => 1,
                'autocomplete' => 'off',
            ],
        ] ); ?>

        <?= $form->field( $model, 'id' )
            ->textInput( [ 'placeholder' => $model->getAttributeLabel( 'id' ),
                'style' => 'width: 77px; margin-right: 5px;',
                'readonly' => 'readonly'
            ] )
            ->label( false );
        ?>


        <?= $form->field( $model, 'parent_id' )
            ->dropdownList(
                ArrayHelper::map( postsprwhtop::find()->all(), 'id', 'name' ),
                [
                    'prompt' => 'Выбор Склада',
                    'style' => 'width: 477px; margin-right: 5px;',
                    'readonly' => 'readonly'
                ]
            )
            ->label( false );
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
                'readonly' => 'readonly'
            ] )
            ->label( false );
        ?>


        <?= $form->field( $model, 'nomer_borta' )
            ->textInput( [ 'placeholder' => $model->getAttributeLabel( 'nomer_borta' ),
                'style' => 'width: 77px; margin-right: 5px;',
                'readonly' => 'readonly'
            ] )
            ->label( false );
        ?>

        <?= $form->field( $model, 'nomer_gos_registr' )
            ->textInput( [ 'placeholder' => $model->getAttributeLabel( 'nomer_gos_registr' ),
                'style' => 'width: 140px; margin-right: 5px;',
                'readonly' => 'readonly'
            ] )
            ->label( false );
        ?>
        <?= $form->field($model, 'nomer_traktor')
            ->textInput(['placeholder' => $model->getAttributeLabel('nomer_traktor'),
                'style' => 'width: 140px; margin-right: 5px;',
                'readonly' => 'readonly'
            ])
            ->label(false);
        ?>

        <?= $form->field( $model, 'nomer_vin' )
            ->textInput( [ 'placeholder' => $model->getAttributeLabel( 'nomer_vin' ),
                'style' => 'width: 140px; margin-right: 5px;',
                'readonly' => 'readonly'
            ] )
            ->label( false );
        ?>


        <?= $form->field( $model, 'final_destination' )
            ->checkbox( [ 'disabled' => "disabled" ] )
            ->label( false );
        ?>
        <?= $form->field( $model, 'f_first_bort' )
            ->checkbox();
        ?>


        <?= $form->field( $model, 'deactive' )
            ->checkbox( [ 'disabled' => "disabled" ] )
            ->label( false );
        ?>


    </div>

    <div class="horisont">
        <?= $form->field( $model, 'tx' )
            ->textarea( [ 'placeholder' => $model->getAttributeLabel( 'tx' ),
                'style' => 'width: 477px; margin-right: 5px;' ] )
            ->label( false );
        ?>

    </div>
    <div class="horisont">
        <?= Html::a(
            'Загрузить Фото', '/sprwhelement/upload?id='.$model->_id,
            [
                'id' => $model->id,
                'class' => 'btn btn-warning',
                //'onclick'=>"window.history.back();",
            ] );
        ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton( 'Сохранить', [
            'class' => 'btn btn-success',
            //'onclick' => 'self.close();document.location.reload(true);',
            //'onclick' => 'self.close();location.reload(true);',
            'onclick' => 'window.opener.location.reload(true);  window.close();',

        ] )

        ?>


        <?= Html::submitButton( 'Сохранить с заменой ГОС-БОРТ', [
            'data-id' => $model->_id,
            'name' => 'contact-button',
            'value' => 'exchange_gos_bort',
            'class' => 'btn btn-success'
        ] ) ?>

    </div>

    <?php ActiveForm::end();

    MyHelpers::WH_BinaryTree()
    ?>

</div>




