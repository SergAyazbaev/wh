<?php

use kartik\date\DatePicker;

use kartik\datetime\DateTimePicker;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use yii\widgets\Pjax;

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
    <div class="pv_motion_create_right_center">


        <?php


        $form = ActiveForm::begin(
            [
                'id' => 'project-form',
                'method' => 'get',
//				    'method' => 'POST',
                'class' => 'form-inline',
                'action' => ['/stat_svod/index_svod'],

                'options' => [
                    //'data-pjax' => 1,
                    'autocomplete' => 'off',
                ],

            ]);
        ?>

        <?= Html::submitButton(
            'EXCEL. Заголовки накладных', [
            'class' => 'btn btn-default',
            'name' => 'print',
            'value' => 1,
        ]) ?>


        <?= Html::submitButton(
            'EXCEL. Содержимое накладных', [
            'class' => 'btn btn-default',
            'name' => 'print',
            'value' => 2,
        ]) ?>

        <?= Html::submitButton(
            'EXCEL. Только АСУОП.', [
            'class' => 'btn btn-default',
            'name' => 'print',
            'value' => 3,
        ]) ?>

    </div>

    <div class="pv_motion_create_right">
        <?php
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

                    [
                        'attribute' => 'wh_home_number',
                        'value' => 'sprwhelement_home_number.name',
                        'label' => 'База',
                        'filter' => $filter_for,
                        'contentOptions' => ['style' => 'min-width: 90px;'],
                    ],

                    [
                        'attribute' => 'number',
                        'value' => 'wh_home_number',
                        'label' => 'База ID',
                        'contentOptions' => ['style' => 'min-width: 90px;'],
                    ],

                    //                    [
                    //                        'attribute' => 'sprwhelement_home_number.name',
                    //                        'value' => 'sprwhelement_home_number.name',
                    //                        'label' => 'База',
                    //                        'filter' => $filter_for ,
                    //                        'contentOptions' => ['style' => 'min-width: 90px;'],
                    //                    ],


                    [
                        'label' => '№ Накл',
                        'attribute' => 'id',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],

                        //'value' =>function($model, $key, $index){

                        'content' => function ($model) {
                            $text_ret = '';

                            if (Yii::$app->getUser()->identity->group_id >= 70 ||
                                Yii::$app->getUser()->identity->group_id == 61 ||
                                Yii::$app->getUser()->identity->group_id == 50 ||  //DOS
                                Yii::$app->getUser()->identity->group_id == 40) {

                                $url = Url::to(['/sklad/update_id?id=' . $model['id'] . '&otbor=' . $model['wh_home_number']]);

                                $text_ret .= Html::a(
                                    $model['id'],
                                    $url,
                                    [
                                        'class' => 'btn btn-success btn-xs',
                                        'data-pjax' => 0,
                                        'target' => "_blank",
                                    ]);

//                                return $text_ret;
                            }

                            if (Yii::$app->getUser()->identity->group_id >= 70 ||
                                Yii::$app->getUser()->identity->group_id == 100) {

                                $url = Url::to(['/sklad_update/update_id?id=' . $model['id'] . '&otbor=' . $model['wh_home_number']]);

                                $text_ret .= ' ' . Html::a(
                                        'Adm ' . $model['id'],
                                        $url,
                                        [
                                            'class' => 'btn btn-warning',
                                            'data-pjax' => 0,
                                        ]);

                                return $text_ret;
                            }


                            return $model['id'];
                        },

                    ],


                    [
                        'attribute' => 'dt_start',
                        'value' => 'dt_create_timestamp',
                        'label' => 'Дата START',
                        'contentOptions' => [
                            'style' => ' width: 170px;',
                            'autocomplete' => "off",
                        ],
                        'format' => [
                            'datetime',
                            'php:d.m.Y H:i:s',
                        ],
                        'visible' => 1,
                        //1

                        'filter' => DatePicker::widget(
                            [
                                'type' => DateTimePicker::TYPE_INPUT,
                                //TYPE_INLINE,
                                'model' => $searchModel,
                                'attribute' => 'dt_start',
                                'language' => 'ru',
                                'name' => 'dt_create',
                                'convertFormat' => false,
                                'options' => [
                                    'placeholder' => 'Дата - START',
                                    'autocomplete' => "off",
                                ],

                                'pluginOptions' => [
                                    'format' => 'dd.mm.yyyy',
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                    'weekStart' => 1,
                                    //неделя начинается с понедельника
                                    'pickerPosition' => 'top-left',
                                    //'pickerPosition' => 'bottom-left',
                                    // 'startDate' => $date_now,
                                    'todayBtn' => false,
                                    //снизу кнопка "сегодня"
                                ],


                            ]),


                    ],


//                    [
//                        'attribute' => 'dt_stop',
//                        'value' => 'dt_create_timestamp',
//                        'label' => 'Дата STOP',
//                        'contentOptions' => [
//                            'style' => ' width: 170px;',
//                            'autocomplete' => "off",
//                        ],
//                        'format' => [
//                            'datetime',
//                            'php:d.m.Y H:i:s',
//                        ],
//                        'visible' => 1,
//                        //1
//
//                        'filter' => DatePicker::widget(
//                            [
//                                'type' => DateTimePicker::TYPE_INPUT,
//                                //TYPE_INLINE,
//                                'model' => $searchModel,
////								    'value'         => date( 'd.m.Y', strtotime( $model->dt_stop ) ),
//                                'attribute' => 'dt_stop',
//                                'language' => 'ru',
//                                'name' => 'dt_create',
//                                'convertFormat' => true,
//                                'options' => [
//                                    'placeholder' => 'Дата - STOP',
//                                    'autocomplete' => "off",
//                                ],
//
//                                'pluginOptions' => [
//                                    'format' => 'dd.mm.yyyy',
//                                    //                                'todayHighlight' => true,
//                                    'autoclose' => true,
//                                    'weekStart' => 1,
//                                    //неделя начинается с понедельника
//                                    'pickerPosition' => 'top-left',
//                                    // 'startDate' => $date_now,
//                                    'todayBtn' => true,
//                                    //снизу кнопка "сегодня"
//                                ],
//
//
//                            ]),
//
//
//                    ],


                    [
                        'attribute' => 'sklad_vid_oper',
                        //                    'label' => 'Вид Опер',
                        'value' => 'sklad_vid_oper',
                        'filter' => [
//							    '1' => "Инвентаризация",
                            '2' => "Приход",
                            '3' => "Расход",
//                                '4' => 'Снятие(замена)',
//                                '5' => 'Установка(замена)'

                        ],

                        'content' => function ($searchModel) {
                            return ArrayHelper::getValue(
                                [
                                    '' => ".",
//									    '1' => "Инвентаризация",
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

                    [
                        'attribute' => 'tx',
                        //'label' => 'Получатель(ПЕ)',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width:110px'],

                        'content' => function ($data) {

                            //ddd($data->id);
                            if (!isset($data->tx) || empty($data->tx)) {
                                return '';
                            }

                            return $data->tx;
                        },


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


<div id="stat_result">
    <div class="pv_motion_create_right">


        <?php
        echo Html::a('Выход', ['/stat_svod/return_to_refer'], ['class' => 'btn btn-warning']);
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



