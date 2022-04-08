<?php

use yii\helpers\Html;
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


<div class="popup_gos">
    <div class="horisont">

        <div class="form-group">

            <?php $form = ActiveForm::begin(
                [
                    'id' => 'project-form',
                    'method' => 'get',
                    'class' => 'form-inline',
                    'action' => [ '/sprwhelement/upload' ],

                    'options' => [
                        'enctype' => 'multipart/form-data',
                        //'data-pjax' => 1,
//                        'autocomplete' => 'off',
                    ],
                ]
            ) ?>

            <?= $form->field( $model, 'imageFile' )->fileInput() ?>

            <!--            <button>Submit</button>-->

            <?= Html::submitButton( 'Загрузить фото', [
                'id' => 'ustanov',
                'class' => 'btn btn-primary'
            ] ) ?>

            <?php
            ActiveForm::end()
            ?>


        </div>

    </div>
</div>



