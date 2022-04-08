<?php

use kartik\datetime\DateTimePicker;
use yii\widgets\Pjax;


Pjax::begin([    'id' => 'pjax-container',]);

    echo yii::$app->request->get('page');

Pjax::end();


?>


<div class="tree_land"></div>



            <?

//            dd($model);

            echo $form->field($model, 'dt_create')
                ->widget(DateTimePicker::className(),[

                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...'],

                    //'value'=> $date_now,
                    'value'=> $model->dt_create,
                    'pluginOptions' => [
                        'pickerPosition' => 'bottom-left',
                        'format' => 'yyyy-mm-dd HH:ii:ss',
                        'autoclose'=>true,
                        'weekStart'=>1, //неделя начинается с понедельника
                        // 'startDate' => $date_now,
                        'todayBtn'=>true, //снизу кнопка "сегодня"
                    ]
                ]);

            ?>



            <?
            echo $form->field($model, 'sklad_vid_oper')
                ->dropdownList(
                    [
                        '1'=>'Инвентаризация',
                        '2'=>'Приходная накладная',
                        '3'=>'Расходная накладная',
                    ],
                    [
                        'prompt' => 'Выбор ...',
                    ]
                )
                ->label("Вид операции");

            echo "<div class='close_hid'>";
            echo $form->field($model, 'sklad_vid_oper_name')
                ->hiddenInput()->label(false);
            echo "</div>";
            ?>






            <?
            //////////
            echo $form->field($model, 'wh_debet_top')->dropdownList(
                \yii\helpers\ArrayHelper::map(
                    frontend\models\Sprwhtop::find()->all(),
                    'id','name'),
                [
                    'prompt' => 'Выбор ...',
                    //'id' => 'wh_debet_top',

                ]
            )->label("Компания-отправитель");


            echo $form->field($model, 'wh_debet_element')->dropdownList(
                \yii\helpers\ArrayHelper::map(
                    frontend\models\Sprwhelement::find()
                        ->where(['parent_id'=>(integer)$model->wh_debet_top])
                        ->all(),'id','name'),
                [
                    'prompt' => 'Выбор  ...',
                    //'id' => 'wh_debet_element',
                ]
            );

            echo "<div class='close_hid'>";
            echo $form->field($model, 'wh_debet_name')
                ->hiddenInput()->label(false);
            echo $form->field($model, 'wh_debet_element_name')
                ->hiddenInput()->label(false);
            echo "</div>";
            ?>



            <?

            //////////
            echo $form->field($model, 'wh_destination')->dropdownList(
                \yii\helpers\ArrayHelper::map(
                    frontend\models\Sprwhtop::find()->all(),
                    'id','name'),
                [
                    'prompt' => 'Выбор склада ...'
                ]
            )->label("Компания-получатель");

            echo $form->field($model, 'wh_destination_element')->dropdownList(
                \yii\helpers\ArrayHelper::map(frontend\models\Sprwhelement::find()
                    ->where(['parent_id'=>(integer)$model->wh_destination])
                    ->all(),'id','name'),
                [
                    'prompt' => 'Выбор склада ...',
                    //'options' => [ $model->wh_destination => ['selected'=>'selected']],
                ]
            );

            echo "<div class='close_hid'>";
            echo $form->field($model, 'wh_destination_name')
                ->hiddenInput()->label(false);
            echo $form->field($model, 'wh_destination_element_name')
                ->hiddenInput()->label(false);
            echo "</div>";

            ?>




<div class="tree_land">
</div>
