<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
    </style>


<?php $form = ActiveForm::begin( [
    'action' => [ '/sprwhelement/save_renew_id' ],
    'method' => 'GET',

] ); ?>

    <div class="sprtype-form">
        <div class="horisont">


            <?= $form->field( $model, 'id' )
                ->textInput( [ 'placeholder' => $model->getAttributeLabel( 'id' ),
                    'style' => 'width: 77px; margin-right: 5px;',
                    'readonly' => 'readonly' ] )
                ->label( false );
            ?>

            <?= $form->field( $model, 'write_id' )
                ->textInput( [ 'placeholder' => '№ Ид замены',
                    'style' => 'width: 177px; margin-right: 5px;',
                ] )
                ->label( false );
            ?>

        </div>


        <div class="form-group">
            <?= Html::submitButton( 'Сохранить', [ 'class' => 'btn btn-success' ] ) ?>
        </div>

        <?php
        echo Html::a(
            'Выход', [ '#' ],
            [
                //'onclick'=>"window.history.back();",
                'onclick'=>"window.history.back();",
                'class' => 'btn btn-warning',
            ] );
        ?>

    </div>

<?php ActiveForm::end(); ?>