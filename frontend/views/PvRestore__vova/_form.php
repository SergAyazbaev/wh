<?php


	use unclead\multipleinput\MultipleInput;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Pvaction */
/* @var $form yii\widgets\ActiveForm */
?>


<style>

    .pv_motion_create_all{
        width: 100%;
        min-width: 1%;
        /* height: max-content; */
        background-color: #2746d2b0;
        display: block;
        position: relative;
        /* float: left; */
    }
    .pv_motion_create_left{
        background-color: #bd6f3003;
        width: 27%;
        max-width: 330px;
        min-width: 340px;
        min-height: 580px;
        display: block;
        padding: 30px;
        float: left;
    }
    .pv_motion_create_right,.pv_motion_create_right_actions, .pv_motion_create_button_place {

    min-width: 54%;
        min-height: 88px;
        background-color: #7bde1a30;
        display: block;
        position: initial;
        float: left;
        padding: 9px;
        margin-left: 5px;
        margin-bottom: 5px;
    }
    .pv_motion_create_right_actions,.pv_motion_create_button_place{
        width: 43%;
        background-color: #008b8b00;
        padding: 10px 60px;
    }

/*
    .pv_motion_right_long{
        width: 36%;
        min-width: 221px;
        height: 247px;
        background-color: #74bb2e30;
        display: block;
        position: initial;
        float: left;
        padding: 9px;
        margin-left: 5px;
        margin-bottom: 5px;

    }
    .pv_motion_create_button_place{
        background-color: #74bb2e08;
        padding: 39px 90px;
        margin-left: 5px;
        margin-bottom: 5px;
    }

    .multiple-input-list__item,
    .list-cell__Exchange{
        margin: 0px;
        padding: 0px;
    }
*/

</style>



<div class="pv-action-form">

    <?php $form = ActiveForm::begin(); ?>

        <div class="pv_motion_create_left">


                <?= $form->field($model, 'id')
                    ->textInput(['class' => 'form-control class-content-title_series',
                        'style' => 'width: 57px; margin-right: 5px;',
                        'disabled' => 'true'
                    ]); ?>

                <?= $form->field($model, 'pv_id')
                    ->textInput(['class' => 'form-control class-content-title_series',
                        'style' => 'width: 57px; margin-right: 5px;',
                        'disabled' => 'true'
                    ]); ?>

                <?= $form->field($model, 'name')
                    ->textInput(['class' => 'form-control class-content-title_series',
                        'style' => 'width: 130px; margin-right: 5px;',
                        'placeholder' => 'UserName',
                        'disabled' => 'true'])->label('Пользователь');
                ?>


                <?php
                //$date_now   = date("Y-m-d H:i:s");
                $date_now   = date("Y-m-d");
                $model->dt_create = $date_now;

                echo $form->field($model, 'dt_create')->widget(DatePicker::className(),[

                    'name' => 'dp_1',
                    //'type' => DateTimePicker::TYPE_INPUT,

                    'type' => DatePicker::TYPE_INLINE,
                    'size' => 'lg',
                    'options' => ['placeholder' => 'Ввод даты/времени...'],

                    //'convertFormat' => true,
                    //'value'=> date("d.m.Y H:i:s", date(time())),

                    'value'=> $date_now,
                    'pluginOptions' => [
	                    'pickerPosition' => 'bottom-left',
	                    'format'         => 'yyyy-m-d', //'dd.mm.yyyy HH:ii:ss',
	                    //                    'format' => 'php:dd.MM.yyyy H:i:s', //'dd.mm.yyyy HH:ii:ss',

	                    'autoclose' => true,
	                    'weekStart' => 1, //неделя начинается с понедельника

                        //'startDate' => ''.$date_now, //самая ранняя возможная дата

	                    'startDate' => date( 'Y-m-d', strtotime( 'first day of  -1 month' ) ),
	                    'todayBtn'  => false, //снизу кнопка "сегодня"
                    ]
                ]);
                ?>


                <?= $form->field($model_pv, 'pv_imei')
                    ->textInput(['class' => 'form-control class-content-title_series',
                        'style' => 'width: 130px; margin-right: 5px;',
                        'placeholder' => '123123123',
                        'disabled' => 'true'])->label('IMEI');

                ?>

                <?= $form->field($model_pv, 'pv_kcell')
                    ->textInput(['class' => 'form-control class-content-title_series',
                        'style' => 'width: 130px; margin-right: 5px;',
                        'placeholder' => '7707070707',
                        'disabled' => 'true'])->label('K-Cell');
                ?>

                <?= $form->field($model_pv, 'pv_bee')
                    ->textInput(['class' => 'form-control class-content-title_series',
                        'style' => 'width: 130px; margin-right: 5px;',
                        'placeholder' => '747474747747',
                        'disabled' => 'true'])->label('BeeLine');



                ?>

                <?= $form->field($model_pv, 'bar_code_pv')
                    ->textInput(['class' => 'form-control class-content-title_series',
                        'style' => 'width: 130px; margin-right: 5px;',
                        'placeholder' => '123123123',
                        'disabled' => 'true'])->label('BAR Code');
                ?>
                <?= $form->field($model_pv, 'qr_code_pv')
                    ->textInput(['class' => 'form-control class-content-title_series',
                        'style' => 'width: 130px; margin-right: 5px;',
                        'placeholder' => '123123123',
                        'disabled' => 'true'])->label('QR Code');
                ?>



        </div>

    <div class="pv_motion_create_all">

        <div class="pv_motion_create_right_actions">
	        <?php
                 echo $form->field($model, 'type_action')->dropdownList(
                     frontend\models\Sprrestore::find()->select(['name'])->indexBy('name')->column(),
                     [
                      'prompt' => 'Выбор работы'
                     ]
                 );
                ?>
        </div>



        <div class="pv_motion_create_right">
	        <?php

            echo $form->field($model, 'list_details')
                ->widget(MultipleInput::className(), [
                'max' => 6,
                'columns' => [
//                    [
//                        'name' => 'question',
//                        'type' => 'textarea',
//                    ],
                    [
	                    'name'        => 'list_details',
	                    'title'       => 'Список компонентов',
	                    'type'        => 'dropDownList',
	                    'enableError' => true,
	                    'options'     => [
                            'class' => 'input-priority'
                        ],
                        'items' => \yii\helpers\ArrayHelper::map(frontend\models\Spr_things::find()->all(), 'id', 'name'),
                        // frontend\models\SprThing::find()->select(['name'])-> indexBy('name') ->column(),

                    ]
                ]
            ]);
?>

        </div>
        <div class="pv_motion_create_right">
                <?= $form->field($model, 'comments')
                    ->textarea(['class' => 'pv_motion_content',
                        'style' => 'width: 100%;height: 185px; margin: 0px;'
                    ]); ?>
        </div>


        <div class="pv_motion_create_button_place">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

        </div>

    </div>


    <?php ActiveForm::end(); ?>

</div>
