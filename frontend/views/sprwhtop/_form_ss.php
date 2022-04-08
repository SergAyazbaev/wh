<?php

use frontend\models\Sprwhtop;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


if ( empty( $model->parent_id ) ) $model->parent_id = 0;
?>
<style>
    .sprtype-form {
        width: 60%;
        padding: 20px 40px;
        background-color: antiquewhite;
    }

    #sprwhtop-buses_variant {
        display: block;
        position: initial;
    }

    #sprwhtop-buses_variant > label {
        /*background-color: #00ff43;*/
        display: block;
    }
</style>

<div class="sprtype-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field( $model, 'id' )
        ->textInput(
            [ 'placeholder' => $model->getAttributeLabel( 'id' ),
                'style' => 'width: 77px; margin-right: 5px;',
                'readonly' => 'readonly' ]
        )
        ->label( false );
    ?>

    <?= $form->field( $model, 'name' )
        ->textarea(
            [ 'placeholder' => $model->getAttributeLabel( 'name' ),
                'style' => 'width: 868px; margin-right: 5px;',
                'readonly' => 'readonly'
            ]
        )
        ->label( false );
    ?>

    <?= $form->field( $model, 'name_tha' )
        ->textarea(
            [ 'placeholder' => $model->getAttributeLabel( 'name_tha' ),
                'style' => 'width: 868px; margin-right: 5px;'
            ]
        )
        ->label( false );
    ?>

    <?= $form->field( $model, 'tx' )
        ->textarea(
            [ 'placeholder' => $model->getAttributeLabel( 'tx' ),
                'style' => 'width: 868px'
            ]
        )
        ->label( false );
    ?>

    <?= $form->field( $model, 'final_destination' )
        ->checkbox( [ 'disabled' => "disabled" ] )
        ->label( false );
    ?>

    <?= $form->field( $model, 'f_first_bort' )  // Флаг = Учет по борту(?)
    ->checkbox( [ 'disabled' => "disabled" ] )
        ->label( false );
    ?>
    <?= $form->field( $model, 'deactive' )
        ->checkbox( [ 'disabled' => "disabled" ] )
        ->label( false );
    ?>

    <hr>

    <?= $form->field( $model, 'buses_variant' )
        ->radioList(
            [
                Sprwhtop::BUSES_VARIANT_NO => 'Нет',
                Sprwhtop::BUSES_VARIANT_ALL => 'Да.Ко всем.' ]
        )
        ->hiddenInput()
        ->label( false );
    ?>


    <div class="form-group">
        <?= Html::submitButton( 'Сохранить', [ 'class' => 'btn btn-success' ] ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
