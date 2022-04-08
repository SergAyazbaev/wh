<?php

use kartik\date\DatePicker;

use kartik\datetime\DateTimePicker;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>


<?php
//    Pjax::begin(['id' => "pjax-container",
//            'linkSelector' => 'a:not(.target-blank)']
//    );
//echo \yii::$app->request->get('page');
?>


<style>
    .grid-view .filters input,
    .grid-view .filters select {
        min-width: 80px;
        padding: 0px 3px;
    }

</style>


<h2>Сводная таблица. ПО БАЗОВОМУ СКЛАДУ (Приход/Расход) </h2>
<div id="stat_result">
    <div class="pv_motion_create_right">


        <?php

        $form = ActiveForm::begin([
            'id' => 'project-form',
//            'action' => ['/stat_svod/index_svod'],
            'action' => ['/stat_svod/svod-to-pdf'],
        ]);

        echo Html::submitButton(
            'To EXCEL',
            ['class' => 'btn btn-default',
                'name' => 'print', 'value' => 1,]);


        ActiveForm::end();
        ////////////////////////


        $dataProvider->pagination->pageSize = 10;

        $filter_date = [];

        Pjax::begin();

        echo GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,

                'columns' => [
//                    [
//                        'attribute' => '##',
//                        'label' => '№',
//                        'contentOptions' => ['style' => 'width: 30px;'],
//                        'value' => function($model, $key, $index){
//                                return ++$key;
//                                },
//                        ],


                    [
                        'attribute' => 'wh_home_number',
                        'label' => 'База',
                        'filter' => $filter_for,
                        'contentOptions' => ['style' => 'min-width: 90px;'],
                    ],


//                    [
//                        'label' => 'Контрагент',
//                        'value' => 'sprwhelement.name',
//                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
//                    ],

                    [
                        'attribute' => 'id',
                        'label' => 'Накладная',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 40px;'],
                    ],
//                    [
//                        'attribute'=> 'dt_create',
//                        'value' => 'dt_create',
//                        'label' => 'Дата',
//                        'contentOptions' => ['style' => ' width: 140px;min-width: 180px;'],
//                        'format' =>  ['date', 'php:d.m.Y H:i:s'],
//                    ],


                    [
                        'attribute' => 'dt_start', 'value' => 'dt_create',
                        'label' => 'Дата START',
                        'contentOptions' => ['style' => ' width: 170px;', 'autocomplete' => "off"],
                        'format' => ['datetime', 'php:d.m.Y H:i:s'],
                        'visible' => 1, //1

                        'filter' => DatePicker::widget([
                            'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                            'model' => $searchModel,
                            'value' => date('d.m.Y', strtotime($model->dt_start)),
                            'attribute' => 'dt_start',
                            'language' => 'ru',
                            'name' => 'dt_create',
                            'convertFormat' => false,
                            'options' => [
                                'placeholder' => 'Дата - СТОП',
                                'autocomplete' => "off"
                            ],

                            'pluginOptions' => [
                                'format' => 'dd.mm.yyyy 23:59:59',
//                                'todayHighlight' => true,
                                'autoclose' => true,
                                'weekStart' => 1, //неделя начинается с понедельника
                                'pickerPosition' => 'bottom-left',
                                // 'startDate' => $date_now,
                                'todayBtn' => true, //снизу кнопка "сегодня"
                            ]


                        ]),


                    ],


                    [
                        'attribute' => 'dt_stop', 'value' => 'dt_create',
                        'label' => 'Дата STOP',
                        'contentOptions' => ['style' => ' width: 170px;', 'autocomplete' => "off"],
                        'format' => ['datetime', 'php:d.m.Y H:i:s'],
                        'visible' => 1, //1

                        'filter' => DatePicker::widget([
                            'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                            'model' => $searchModel,
                            'value' => date('d.m.Y', strtotime($model->dt_stop)),
                            'attribute' => 'dt_stop',
                            'language' => 'ru',
                            'name' => 'dt_create',
                            'convertFormat' => false,
                            'options' => [
                                'placeholder' => 'Дата - СТОП',
                                'autocomplete' => "off"
                            ],

                            'pluginOptions' => [
                                'format' => 'dd.mm.yyyy 23:59:59',
//                                'todayHighlight' => true,
                                'autoclose' => true,
                                'weekStart' => 1, //неделя начинается с понедельника
                                'pickerPosition' => 'bottom-left',
                                // 'startDate' => $date_now,
                                'todayBtn' => true, //снизу кнопка "сегодня"
                            ]


                        ]),


                    ],


                    [
                        'attribute' => 'sklad_vid_oper',
                        //                    'label' => 'Вид Опер',
                        'value' => 'sklad_vid_oper',
                        'filter' => [
                            '1' => "Инвентаризация",
                            '2' => "Приход",
                            '3' => "Расход",
                        ],

                        'content' => function ($searchModel) {
                            return ArrayHelper::getValue([
                                '' => ".",
                                '1' => "Инвентаризация",
                                '2' => "Приход",
                                '3' => "Расход",
                            ], $searchModel['sklad_vid_oper']);
                        },

                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
                    ],


//                    [
//                        'attribute' => 'vid_oper',
//                        'label' => 'Вид операции',
//                        'value' => 'sklad_vid_oper',
//                        'filter' => [
//                                '0' => "....",
//                                '1' => "Инвентаризация",
//                                '2' => "Приход",
//                                '3' => "Расход",
//                        ],
//                        'content' => function ($model) {
//                            return ArrayHelper::getValue([
//                                '0' => "....",
//                                '1' => "Инвентаризация",
//                                '2' => "Приход",
//                                '3' => "Расход",
//                            ], $model['sklad_vid_oper']);
//                        },
//
//                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
//                        ],


                    [
                        'attribute' => 'wh_debet_name',
                        'label' => 'Отправитель',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width:90px'],
                    ],

                    [
                        'attribute' => 'wh_debet_element_name',
                        'label' => 'Отправитель',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width:90px'],
                    ],

                    [
                        'attribute' => 'wh_destination_name',
                        'label' => 'Получатель',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width:110px'],
                    ],
                    [
                        'attribute' => 'wh_destination_element_name',
                        'label' => 'Получатель(ПЕ)',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width:110px'],
                    ],


//                    [
//                        'attribute' => 'wh_tk_amort',
//                        'label' => 'Группа-ТМЦ',
//                        'contentOptions' => ['style' => 'overflow: hidden;max-width:110px'],
//                        ],
//
//                    [
//                        'attribute' => 'wh_tk_element',
//                        'label' => 'ТМЦ',
//
//                        ],
//
//                    [
//                        'attribute' => 'ed_izmer',
//                        'label' => 'Ед.изм',
//                        'contentOptions' => ['style' => 'overflow: hidden;min-width: 40px;'],
//                        ],
//
//                    [
//                        'attribute' => 'ed_izmer_num',
//                        'label' => 'Кол',
//                        'contentOptions' => ['style' => 'width: 60px;'],
//                        ],


                ],
            ]);

        Pjax::end();
        ?>

    </div>


</div>


<?php //ActiveForm::begin(); ?>

<?= Html::beginForm(['stat_svod/index_svod'],
    'post',
    [
//'data-pjax' => '',
        'class' => 'form-inline'
    ]);
?>


<div id="stat_result">
    <div class="pv_motion_create_right">


        <?php
        echo Html::a('Выход', ['/site'], ['class' => 'btn btn-warning']);
        ?>


    </div>
</div>
<?php //ActiveForm::end(); ?>


<?php


$script = <<<JS

$('#go_home').click(function() {    
    window.history.back();  
})

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

<?php //Pjax::end(); ?>



