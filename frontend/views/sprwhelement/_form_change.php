<?php
use frontend\models\postsprwhelement;
use frontend\models\postsprwhtop;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;
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
        border: 1px solid #d2d2d2;
        border-radius: 15px;
        margin: 5px;
    }
</style>


<?php $form = ActiveForm::begin(
    [
        //'action' => [ '/sklad_cs/from_cs_one_barcode' ],
        'method' => 'GET'
        ]
); ?>

<div class="sprtype-form">
    <div class="horisont">
        <div class="gos_nomer">

            <?= $form->field($model, 'id')
                ->textInput(
                    ['placeholder' => $model->getAttributeLabel('id'),
                        'style' => 'width: 77px; margin-right: 5px;',
                        'readonly' => 'readonly']
                )
                ->label(false);
            ?>


            <?= $form->field($model, 'parent_id')
                ->dropdownList(
                    ArrayHelper::map(
                        postsprwhtop::find()
                            ->orderBy('name')
                            ->all(), 'id', 'name'
                    ),
                    [
                        'prompt' => 'Выбор Склада',
                        'style' => 'width: 477px; margin-right: 5px;',
                        'readonly' => 'readonly'
                    ]
                )
                ->label(false);
            ?>

        </div>
    </div>

    <div class="horisont">

        <div class="gos_nomer">
            <?php
            $xx = postsprwhelement::find()->all();
            $type_words = ArrayHelper::getColumn($xx, 'name');

            echo $form->field($model, 'name')
                ->widget(
                    AutoComplete::className(), [
                        'clientOptions' => [
                            'source' => $type_words,
                        ],
                    ]
                )
                ->textInput(
                    ['placeholder' => $model->getAttributeLabel('name'),
                        'style' => 'width: 277px; margin-right: 5px;',
                        'readonly' => 'readonly']
                )
                ->label("Название:");
            ?>
        </div>


        <div class="gos_nomer">
            <?php
            $xx = postsprwhelement::find()->all();
            $type_words = ArrayHelper::getColumn($xx, 'nomer_borta');

            echo $form->field($model, 'nomer_borta')
                ->widget(
                    AutoComplete::className(), [
                        'clientOptions' => [
                            'source' => $type_words,
                        ],
                    ]
                )
                ->textInput(
                    ['placeholder' => $model->getAttributeLabel('nomer_borta'),
                        'style' => 'width: 277px; margin-right: 5px;']
                )
                ->label("БОРТ НОМЕР");
            ?>
            <?php echo Html::submitButton(
                'Изменить БОРТ НОМЕР', [
                    'class' => 'btn btn-success',
                    'name' => 'button-click',
                    'value' => 'bort_change',
                ]
            );
            ?>
        </div>


        <div class="gos_nomer">
            <?php
            echo $form->field($model, 'nomer_gos_registr')
                ->textInput(
                    ['placeholder' => $model->getAttributeLabel('nomer_gos_registr'),
                        'style' => 'width: 277px; margin-right: 5px;']
                )
                ->label("ГОС НОМЕР");
            ?>
            <?php
            echo Html::submitButton(
                'Изменить ГОС НОМЕР', [
                    'class' => 'btn btn-success',
                    'name' => 'button-click',
                    'value' => 'gos_change',
                ]
            );
            ?>
        </div>


        <div class="gos_nomer">
            <?php
            echo $form->field($model, 'nomer_traktor')
                ->textInput(
                    ['placeholder' => $model->getAttributeLabel('nomer_traktor'),
                        'style' => 'width: 277px; margin-right: 5px;',
                        'readonly' => 'readonly'
                    ]
                )
                ->label("Тракторный НОМЕР");
            ?>
            <?php
            //            echo Html::submitButton(
            //                'Изменить НОМЕР', [
            //                    'class' => 'btn btn-success',
            //                    'name' => 'button-click',
            //                    'value' => 'gos_change',
            //                ]
            //            );
            ?>
        </div>


        <div class="gos_nomer">
            <?php
            echo $form->field($model, 'nomer_vin')
                ->textInput(
                    ['placeholder' => $model->getAttributeLabel('nomer_vin'),
                        'style' => 'width: 277px; margin-right: 5px;']
                )
                ->label("VIN НОМЕР");
            ?>
            <?php
            echo Html::submitButton(
                'Изменить VIN НОМЕР', [
                    'class' => 'btn btn-success',
                    'name' => 'button-click',
                    'value' => 'vin_change',
                ]
            );
            ?>
        </div>


        <?= $form->field($model, 'final_destination')
            ->checkbox(['disabled' => "disabled"])
            ->label(false);
        ?>

        <?= $form->field($model, 'f_first_bort')
            ->checkbox(['disabled' => "disabled"])
        ?>

        <?= $form->field($model, 'deactive')
            ->checkbox(['disabled' => "disabled"])
        ?>


    </div>

    <div class="horisont">
        <?= $form->field($model, 'tx')
            ->textarea(
                ['placeholder' => $model->getAttributeLabel('tx'),
                    'style' => 'overflow: auto;width: 100%;min-width: 550px;height: 137px;',
                    'readonly' => 'readonly']
            )
            ->label(false);
        ?>

    </div>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>

    <?php
    echo Html::a(
        'Выход', ['sprwhelement/return_to_refer'],
        [
            'class' => 'btn btn-warning',
        ]);
    ?>


</div>

<?php ActiveForm::end(); ?>
<br>
<br>
<br>
<br>