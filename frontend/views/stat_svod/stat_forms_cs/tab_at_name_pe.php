<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>


<style>
    .form-group.field-postsklad-dt_start, .form-group.field-postsklad-dt_stop {
        width: 300px;
        float: none;
    }


    .form-group.field-postsklad-dt_start {
        display: block;
        position: relative;
        width: 300px;
        float: left;
        background-color: #00ff4324;
        padding: 5px 10px;
        margin-left: 10px;
    }

    .form-group.field-postsklad-dt_stop {
        display: block;
        position: inherit;
        width: 300px;
        background-color: #00ff4324;
        padding: 5px 10px;
        float: left;
        margin-left: 10px;
    }

    .pv_motion_create_left {
        width: 30%;
        background-color: #0b93d5;
        position: relative;
        float: right;
        margin: 0px;
    }

</style>


<?php $form = ActiveForm::begin([
    'options' => ['autocomplete' => 'off']
]); ?>


<?= Html::beginForm(
    ['stat_svod/index_svod_pe'],
    'post',
    [
        'data-pjax' => '',
        'class' => 'form-inline',
    ]);
?>


<div id="pv_motion_create_name">
    <div class="pv_motion_create_name">

        <h2>Инвентаризация ЦС</h2>

    </div>


    <div class="pv_motion_create_name">

        <?php
        echo Html::a('Выход', ['/site'], ['class' => 'btn btn-warning']);
        ?>

        <?= Html::submitButton(
            'To EXCEL', [
            'class' => 'btn btn-default',
            'name' => 'print',
            'value' => 1,
        ]) ?>


        <?= Html::submitButton(
            'To EXCEL (РАСКРЫТО ПО НАКЛАДНЫМ)', [
            'class' => 'btn btn-default',
            'name' => 'print',
            'value' => 2,
        ]) ?>
        <?


        ///
        /// Блок кнопок по датам Столбовых Инвентаризаций
        ///

        if (isset($array_stolb_group) && !empty($array_stolb_group))
            foreach ($array_stolb_group as $item) {
                echo " ";

                echo Html::submitButton(
                    "ИнвСт " . Date('d.m.Y', $item['date_start']) . " ( " . $item['count_all'] . " стр. )", [
                    'class' => 'btn btn-default',
                    'name' => 'delete' . $item['date_start'],
                    'value' => 1,
                ]);
                echo " ";

                echo Html::a('Удалить ' . Date('d.m.Y', $item['date_start']), [
                    'delete_by_day',
                    'day_timestamp' => $item['date_start']
                ],
                    [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Будет уничтожена вся Столбовая Инвентаризация ЦС за эту дату ' . Date('d.m.Y', $item['date_start'])
                                . '<br>Удалить?',
                            'method' => 'post',
                        ],
                    ]);


            }


        ?>


    </div>

    <div class="pv_motion_create_name">

        <?php

        //
        //        //$model->dt_start =date('d.m.Y H:i:s',strtotime($dt_start));
        //        echo $form->field($model, 'dt_start')
        //            ->widget(DatePicker::className(),[
        //                'name' => 'dp_1',
        //                //'autocomplete' => ['disabled'=>true],
        //                'type' => DatePicker::TYPE_INPUT,   //TYPE_INLINE,
        //                'size' => 'lg',
        //                'convertFormat' => false,
        //
        //                'options' => [
        //                    'placeholder'  => 'Ввод даты/времени...'],
        //
        //                'pluginOptions' => [
        //                    'format' => 'dd.mm.yyyy 00:00:00', /// не трогать -это влияет на выбор "СЕГОДНЯ"
        //                    'pickerPosition' => 'bottom-left',
        //
        //                    'autoclose'=>true,
        //                    'weekStart'=>1, //неделя начинается с понедельника
        //                    // 'startDate' => $date_now,
        //                    'todayBtn'=>true, //снизу кнопка "сегодня"
        //                ]
        //            ]);

        ?>

    </div>

</div>


<?php ActiveForm::end(); ?>

<div id="stat_result">
    <div class="pv_motion_create_right">

        <?php

        Pjax::begin();

        $dataProvider->pagination->pageSize = 10;

        echo GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $model,

                'columns' => [

                    [
                        'attribute' => 'id',
                        'label' => 'Накладная',
                        'contentOptions' => ['style' => 'width: 110px;'],
                        'content' => function ($model) {

                            $url = Url::to(['/stat_svod/update_id?id=' . $model->id]);

                            return Html::a('# ' . $model->id, $url, [
                                'class' => 'btn btn-success btn-xs',
                                'target' => '_blank',
                                'data-pjax' => 0,
                                'data-id' => $model->_id,
                            ]);
                        }


                    ],

                    [
                        'attribute' => 'count_str',
                        'contentOptions' => ['style' => 'width: 10px;'],
                    ],

                    [
                        'attribute' => 'itogo_things',
                        'contentOptions' => ['style' => 'width: 10px;'],
                    ],

                    [
                        'attribute' => 'calc_minus',
                        'contentOptions' => ['style' => 'width: 10px;'],
                    ],


                    [
                        //'attribute'      => 'sprwhelement_wh_destination_element.name',
                        'attribute' => 'wh_destination',
                        'value' => 'sprwh_wh_destination.name',
                        'label' => 'ЦС',
                        'filter' => $filer_cs,
                        'contentOptions' => ['style' => 'overflow: hidden;max-width: 180px;'],
                    ],


                    [
                        'attribute' => 'wh_destination_element_name',
                        'label' => 'Склад ЦС',
                        'contentOptions' => ['style' => 'width: 110px;'],
                        'content' => function ($model) {
                            //ddd($model->wh_destination_element); //4330

                            $url = Url::to(['/stat_svod/index_chain_from_id?id=' . $model->wh_destination_element]);

                            return Html::a(' ' . $model->wh_destination_element_name, $url, [
                                'class' => 'btn btn-success btn-xs',
                                'target' => '_blank',
                                'data-pjax' => 0,
                                'data-id' => $model->_id,
                            ]);
                        }
                    ],


                    [
                        'attribute' => 'dt_create_timestamp',
                        'label' => 'Дата',
                        'contentOptions' => ['style' => ' width: 110px;'],
                        'format' => [
                            'date',
                            'php:d.m.Y H:i:s',
                        ],
                        'filter' => $filter_for_date,
                    ],

                    [
                        'attribute' => 'sklad_vid_oper_name',
                        //'label'     => 'Вид операции',
                        'value' => 'sklad_vid_oper_name',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
                    ],

                    [
                        'attribute' => 'calc_errors',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
                    ],


                ],
            ]);


        //        ddd(222);

        Pjax::end();

        ?>

    </div>


</div>


<?php
$script = <<<JS

$('#go_home').click(function() {    
    window.history.back();  
})

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

<?php //Pjax::end(); ?>



