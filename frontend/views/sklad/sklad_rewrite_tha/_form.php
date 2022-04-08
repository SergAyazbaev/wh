<?php

use kartik\datetime\DateTimePicker;
use kartik\form\ActiveForm;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Html;
use yii\widgets\Pjax;


Pjax::begin([    'id' => 'pjax-container',]);
    echo Yii::$app->request->get('page');
Pjax::end();

?>




<div id="tz1">

    <?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => true,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
]);
    ?>

    <div class="pv_motion_create_right_center">

        <div class="tree_land2">
            <?php
            if ( isset($sklad))
                    echo "<h2>(".$sklad.")<br>";

            echo "Накладная № ".$model->id ."</h2>";

            echo "Т.З. - ".$model->tz_id ;
            ?>
        </div>


        <div class="tree_land">
            <?php

            echo $form->field($model, 'dt_create')
                ->widget(DateTimePicker::className(),[

                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ],
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



            <?php
            echo $form->field($model, 'sklad_vid_oper')
                ->dropdownList(
                    [
                        '1'=>'Инвентаризация',
                        '2'=>'Приходная накладная',
                        '3'=>'Расходная накладная',
                    ],
                    [
                        'prompt' => 'Выбор ...',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ]
                )
                ->label("Вид операции");

            echo "<div class='close_hid'>";
            echo $form->field($model, 'sklad_vid_oper_name')
                ->hiddenInput()->label(false);
            echo "</div>";
            ?>

        </div>



        <div class="tree_land">
            <?php
            //////////
            echo $form->field($model, 'wh_debet_top')->dropdownList(
                \yii\helpers\ArrayHelper::map(
                    frontend\models\Sprwhtop::find()->all(),
                    'id','name'),
                [
                    'prompt' => 'Выбор ...',
                    'readonly' => 'readonly',
                    'disabled' => 'disabled',

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
                    'readonly' => 'readonly',
                    'disabled' => 'disabled',

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
        </div>

        <div class="tree_land">
            <?php

            //////////
            echo $form->field($model, 'wh_destination')->dropdownList(
                \yii\helpers\ArrayHelper::map(
                    frontend\models\Sprwhtop::find()->all(),
                    'id','name'),
                [
                    'prompt' => 'Выбор склада ...',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',

                ]
            )->label("Компания-получатель");

            echo $form->field($model, 'wh_destination_element')->dropdownList(
                \yii\helpers\ArrayHelper::map(frontend\models\Sprwhelement::find()
                    ->where(['parent_id'=>(integer)$model->wh_destination])
                    ->all(),'id','name'),
                [
                    'prompt' => 'Выбор склада ...',
                    'readonly' => 'readonly',
                    'disabled' => 'disabled',

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

        </div>

    </div>






    <div class="pv_motion_create_ok_button">

        <?= Html::Button('&#8593;',
            [
                'class' => 'btn btn-warning',
                'onclick'=>"window.history.back();"
            ]);
        ?>

        <?php
        echo Html::submitButton('Сохранить изменения в накладной',
            ['class' => 'btn btn-success']
        );
        ?>

<!--        --><?php
//        echo Html::a('Просмотр накладной',
//            ['/sklad/proshivka_to_pdf?id=' . $model->id],
//            [
//                'id' => "to_print",
//               'target' => "_blank",
//                'class' => 'btn btn-default'
//            ]);
//        ?>
<!---->
<!--        --><?php
//        echo Html::a('Печать',
//            ['/sklad/proshivka_to_pdf?id=' . $model->id],
//            [
//                'id' => "to_print",
//               'target' => "_blank",
//                'class' => 'btn btn-default'
//            ]);
//        ?>

        <?php
        echo Html::a('Почта в ТХА ',
            ['/sklad/mail_to 1111111/?id=' . $model->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>


    </div>

</div>




<div class="scroll_window">
    <div class="pv_motion_create_right">

        <?php
        echo $form->field($model, 'array_tk_amort')->widget(MultipleInput::className(),
        [
            'id' => 'my_id2',
            'rowOptions' => [
                'id' => 'row{multiple_index_my_id2}',
                'style' => 'max-height: 20px;',
            ],

//            'max'               => 25,
            'min'               => 0, // should be at least 2 rows
            'allowEmptyList'    => false,
            'enableGuessTitle'  => false,
            'removeButtonOptions'  => [],
            'addButtonPosition' => MultipleInput::POS_HEADER,

//            'sortable'  => true,
//            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

            'columns' => [
                [
                    'name'  => 'wh_tk_amort',
                    'type'  => 'dropDownList',
                    'title' => 'Группа',
                    'defaultValue' => 0,
                    'items' => \yii\helpers\ArrayHelper::map(
                        frontend\models\post_spr_glob_element::find()->all(),
                        'id','name'),

                    'options' => [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',

                        'prompt' => 'Выбор ...',
                        'style' => 'min-width: 12vw;',
                        'id' => 'subcat211-{multiple_index_my_id2}',
                        'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
    console.log(data);
    $("select#subcat22-{multiple_index_my_id2}").html(data);
});
JS
                    ],
                ],

                [
                    'name'  => 'wh_tk_element',
                    'type'  => 'dropDownList',
                    'title' => 'Компонент',
                    'defaultValue'=> [],

                    'options' => [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'id' => 'subcat22-{multiple_index_my_id2}',
                        'style' => 'min-width: 136px;',
                        'prompt' => '...',
                    ],

                    'items' =>
                        \yii\helpers\ArrayHelper::map(
                            frontend\models\post_spr_glob_element::find()->orderBy('parent_id, name')->all(),
                            //frontend\models\postglobalamelement::find()->all(),
                            'id','name')
                    ,

                ],
                [
                    'name'  => 'bar_code',
                    'title' => 'Штрих-код',
                    'options' => [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'style' => 'min-width:150px;min-width:12vw;color:red;',
                        'id' => 'code5-{multiple_index_my_id2}',
                    ],
                ],
                [
                    'name'  => 'msam_code',
                    'title' => 'MSAM #',
                    'options' => [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'style' => 'min-width:150px;min-width:12vw;color:red;',
                    ],
                ],
                [
                    'name'  => 'bus_number_bort',
                    'title' => 'Борт #',
                     'type'  => 'dropDownList',
                    'options' => [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'style' => 'min-width: 80px;min-width: 9vw;padding: 0;',
                    ],
                    'items' =>
                        \yii\helpers\ArrayHelper::map(
                            frontend\models\posttzautoelement::find()->where(['like', 'tz_id', $model->tz_id])->all(),
                            'id','nomer_borta')
                    ,

                ],


                [
                    'name'  => 'bus_number_gos',
                    'title' => 'Гос #',
                    'type'  => 'dropDownList',
                    'options' => [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'style' => 'min-width: 150px;min-width: 5vw;padding: 0;width: 93px;',
                    ],
                    'items' =>
                        \yii\helpers\ArrayHelper::map(
                            frontend\models\posttzautoelement::find()->where(['like', 'tz_id', $model->tz_id])->all(),
                            'id','nomer_gos_registr')
                    ,

                ],
                [
                    'name'  => 'bus_number_vin',
                    'title' => 'VIN #',
                    'type'  => 'dropDownList',
                    'options' => [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                        'style' => 'min-width: 150px;min-width: 5vw;padding: 0;width: 93px;',
                    ],
                    'items' =>
                        \yii\helpers\ArrayHelper::map(
                            frontend\models\posttzautoelement::find()->where(['like', 'tz_id', $model->tz_id])->all(),
                            'id','nomer_vin')
                    ,

                ],

                [
                    'name'  => 'the_bird',
                    'title' => 'ТХА',
                    'type'  => 'checkbox',
                    'options' => [
                        'style' => 'min-width: 40px;height: 30px;margin: -5px -24px; padding: 0px;',
                    ],
                ],

                [
                      'name'  => 'tx',
                      'title' => 'Коммент.',
                      'type'  => 'textarea',
                      'defaultValue' => '',
                      'enableError' => false,
                      'options' => [
                          'class' => 'input-priority',
                          'style' => 'min-width:6vw; width: 203px;height: 5vh;',
                      ]
                ]

            ]
        ]);


        ?>




    </div>



</div>


<?php ActiveForm::end(); ?>

<?php

//Modal::begin([
//    'header' => '<h2>Вот это модальное окно!</h2>',
//    'toggleButton' => [
//        'tag' => 'button',
//        'class' => 'btn btn-lg btn-block btn-info',
//        'label' => 'Нажмите здесь, забавная штука!',
//    ]
//]);
//echo 'Надо взять на вооружение.';
//Modal::end();

$script = <<<JS

$('.multiple-input-list__btn').hide();
$('.multiple-input-list__btn').on('click', function() {
  alert('Закрыта возможность изменения структуры документа'); 
  return false;
});



JS
;

$this->registerJs($script, yii\web\View::POS_READY);
?>

