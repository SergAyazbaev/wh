<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Журнал';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    div.barcode input {
        border: 0.01px dashed;
        display: block;
        position: fixed;
        right: 278px;
    }

    @media (max-width: 600px) {
        div.barcode input {
            right: 20px;
            top: 110px;
        }

    }

    .btn {
        margin: 3px 5px;
        padding: 0px 10px;
    }

    div > form {
        width: 200px;
        display: inline;
    }


    .redstyle {
        color: rgba(54, 42, 9, 1);
        background: rgba(208, 212, 13, 0.3);
    }

    .redstyle10 {
        color: rgba(54, 42, 9, 1);
        background: rgba(209, 214, 11, 0.7);
    }

    .redstyle20 {
        color: rgba(54, 42, 9, 1);
        background: rgba(225, 174, 37, 0.5);
    }

    .redstyle30 {
        color: rgba(54, 42, 9, 1);
        background: rgba(200, 105, 26, 0.5);
    }

    .redstyle40 {
        color: rgba(54, 42, 9, 1);
        background: rgba(249, 80, 39, 0.5);
    }

    .scroll_mobile {
        width: 100%;
        /*height: calc(355px);*/
        /*height: calc(61vh);*/
        /*height: auto;*/
        /*height: calc(60vh - 60px);*/
        overflow: auto;
        margin: 0 auto;
        height: 63vh;
        /*max-height: 65vh;*/

    }

    @media (max-height: 600px) {
        body {
            font-size: 14px;
            line-height: 0.5;
            margin: 1px 1px;
            padding: 1px 1px;

        }

        .btn {
            margin: 1px 1px;
            padding: 1px 1px;
            font-size: 10px;
        }

        .scroll_mobile {
            /*height: 400px;*/
            height: 55vh;
        }

    }

    /*@media device-height (100px, 500px) {*/
    /*    .scroll_mobile {*/
    /*        width: 50%;*/
    /*        height: calc(60vh - 60px);*/
    /*        overflow: auto;*/
    /*        max-height: 65vh;*/
    /*    }        */
    /*}*/

    /*@media (max-width: 900px) {*/
    /*}*/

    ul.pagination {
        display: block;
        position: absolute;
        bottom: 20px;
        background-color: #eeee;
        padding: 5px;
    }

    .form-control {
        padding: 0px;
    }

    td {
        padding: 0px;
    }
</style>


<? //= Html::a('Новая запись в журнал', ['create'], ['class' => 'btn btn-success']) ?>


<br>


<?php
$form = ActiveForm::begin(
    [
        'id' => 'project-form',
        'method' => 'GET',

        'class' => 'form-inline',
        'action' => ['/reflection/index'],

        'options' => [
            'data-pjax' => 0,
            'autocomplete' => 'off',
        ],

    ]);
?>

<?= Html::submitButton(
    'EXCEL', [
    'class' => 'btn btn-default',
    'name' => 'print',
    'value' => 1,
]) ?>

<?= Html::submitButton(
    'Группировать по товарам', [
    'class' => 'btn btn-default',
    'name' => 'group',
    'value' => 'tovar',
]) ?>

<?= Html::a(
    'Схлопнуть по штрихкодам', '/reflection/index', [
    'class' => 'btn btn-default',
    'method' => 'POST',
    'name' => 'group',
    'value' => 1,
]) ?>

<?= Html::a(
    'Группировать по товарам', '/reflection/index', [
    'class' => 'btn btn-default',
    'name' => 'group',
    'value' => 1,
]) ?>

<?= Html::a(
    'Группировать по датам.товарам и типу операции', '/reflection/index', [
    'class' => 'btn btn-default',
    'name' => 'group',
    'value' => 1,
]) ?>


<? //= Html::a(
//    'Очистить фильтр', '/rem_history/index', [
//    'class' => 'btn btn-default',
//    'name' => 'print',
//    'value' => 1,
//]) ?>

<?php ActiveForm::end(); ?>


<!--<div style="position:absolute;right:280px;top:45px;"><input type="text" name="barcode" id="barcode"-->
<!--                                                           placeholder=" BarCode" style="border: 0.01px dashed;">-->

<!--<div class="barcode">-->
<!--    <input type="text" name="barcode" id="barcode" placeholder=" BarCode">-->
<!--</div>-->

<div class="scroll_mobile">
    <?php Pjax::begin([
        'id' => 'pjax-container1',
        'timeout' => 2000
    ])
    ?>

    <?= GridView::widget(
        [
            'id' => 'w1', //OK
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

            'columns' => [
                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 35px;'],
                ],

//                [
//                    'attribute' => 'dt_between',
//                    'header' => 'Дата. Диапазон',
//                    'value' => 'dt_create_timestamp',
//
//                    'contentOptions' => function ($model) {
//                        ///
//                        if ($model->dt_create_timestamp >= strtotime(date('d.m.Y 00:00:00', strtotime('now')))) {
//                            return ['style' => 'width: 50px;overflow: hidden;', 'class' => 'info'];
//                        }
//                        return ['style' => 'width: 50px;overflow: hidden;'];
//                    },
//
//                    'format' => [
//                        'datetime',
//                        'php:d.m.y (H:i:s)',
//                    ],
//
//                    'filter' => DateRangePicker::widget(
//
//                        [
//                            'name' => 'dt_between',
//                            'presetDropdown' => true,
//                            'convertFormat' => true,
//                            'includeMonthsFilter' => true,
//                            'pluginOptions' => ['locale' => ['format' => 'd.m.Y']],
//                            'options' => ['placeholder' => 'Выбрать...']
//                        ]
//
//                    ),
//                ],


                /// "_id" : ObjectId("6025ee8e843f290454007433"),
                //    "id" : 10,
                //    "home_id" : 86,
                //    "nnn_id" : 1,
                //    "t" : 1613098638,
                //    "gr" : 7,
                //    "el" : 9,
                //    "s" : 1,
                //    "bc" : "210100039507",
                //    "t_cr" : 1613098638,
                //    "t_in" : 1613098638,
                //    "t_out" : 1613098638

                [
                    'attribute' => 'home_id',
                    'contentOptions' => ['style' => 'width: 80px; ']
                ],
                [
                    'attribute' => 'nnn_id',
                    'contentOptions' => ['style' => 'width: 80px; ']
                ],

                [
                    'attribute' => 'gr',
                    'contentOptions' => ['style' => 'width: 40px; ']
                ],
                [
                    'attribute' => 'el',
                    'contentOptions' => ['style' => 'width: 40px; ']
                ],


                [
                    'attribute' => 'gr',
                    'value' => 'spr_globam.name',
                    'contentOptions' => ['style' => 'width: 230px; ']
                ],

                [
                    'attribute' => 'el',
                    'value' => 'spr_globam_element.short_name',

//                    'content' => function ($model) {
//                        ddd($model['spr_globam']);
//                        ddd($model->spr_globam[0]);
//                    }

                ],


                [
                    'attribute' => 'bc',
                    'contentOptions' => ['style' => 'width: 120px; ']
                ],


                [
                    'attribute' => 't_cr',
                    'value' => 't_cr',
                    'format' => ['datetime',
                        'php:d.m.y (H:i:s)',
                    ],
                    'contentOptions' => ['style' => 'width: 100px;min-width: 50px;'],

                    'content' => function ($model) {
                        if (empty($model->t_cr)) {
                            return '';
                        }
                        return date('d.m.y (H:i:s)', $model->t_cr);
                    }

                ],

                [
                    'attribute' => 's',
                    'contentOptions' => ['style' => 'width: 120px; ']
                ],


//                [
//                    'contentOptions' => ['style' => 'width: 10px;'],
//                    'content' => function ($model) {
//
//                        $str = '';
//
//                        $url = Url::to(['new_by_barcode?bar_code=' . $model->_id]);
//                        $str .= Html::a(
//                            'Вн', $url, [
//                                'class' => 'btn btn-success',
//                                'method' => 'get',
//                                'data-pjax' => 0,
//                            ]
//                        );
//
//                        if (Yii::$app->getUser()->identity->group_id == 45) {  // Жанна
//                            $url = Url::to(['mts_by_barcode?bar_code=' . $model->_id]);
//                            $str .= Html::a(
//                                'MTC', $url, [
//                                    'class' => 'btn btn-success',
//                                    'method' => 'get',
//                                    'data-pjax' => 0,
//                                ]
//                            );
//                        }
//
//                        return $str;
//                    }
//
//                ],


//                [
//                    'attribute' => 'bar_code',
//                    'contentOptions' => function ($model) {
//
//                        ///
//                        if (isset($model->barcode_pool->turnover) && $model->barcode_pool->turnover > 4) {
//                            return ['width' => '60px', 'class' => 'redstyle40'];
//                        }
//                        if (isset($model->barcode_pool->turnover) && $model->barcode_pool->turnover > 3) {
//                            return ['width' => '60px', 'class' => 'redstyle30'];
//                        }
//                        if (isset($model->barcode_pool->turnover) && $model->barcode_pool->turnover > 2) {
//                            return ['width' => '60px', 'class' => 'redstyle20'];
//                        }
//                        if (isset($model->barcode_pool->turnover) && $model->barcode_pool->turnover > 1) {
//                            return ['width' => '60px', 'class' => 'redstyle10'];
//                        }
//                        if (isset($model->barcode_pool->turnover) && $model->barcode_pool->turnover > 0) {
//                            return ['width' => '60px', 'class' => 'redstyle'];
//                        }
//                        //ddd($model);
//
//                        //return ['class' => 'info', 'style' => 'width: 10px;'];
//                        return ['width' => '60px'];
//                    },
//
//                    'content' => function ($model) {
//                        $str = '';
//                        //Bbar_code%5D=04076
//                        if (isset($model->bar_code) && !empty($model->bar_code)) {
//                            $url = Url::to(['rem_history/index?bar_code=' . $model->bar_code]);
//                            $str .= Html::a(
//                                $model->bar_code, $url, [
//                                    'method' => 'get',
//                                    'data-pjax' => 0,
//                                ]
//                            );
//                        }
//
//                        return $str;
//                    }
//                ],
//
//                [
//                    'attribute' => 'barcode_pool_turnover',
//                    'value' => 'barcode_pool.turnover',
//                    'contentOptions' => ['style' => 'width: 10px;'],
//                ],

//                [
//                    'attribute' => 'barcode_pool_turnover_alltime',
//                    'value' => 'barcode_pool.turnover_alltime',
//
//                    'contentOptions' => ['style' => 'width: 10px;'],
//                    'content' => function ($model) {
//                        if (isset($model->barcode_pool->turnover_alltime) && !empty($model->barcode_pool->turnover_alltime)) {
//                            return $model->barcode_pool->turnover_alltime;
//                        }
//
//                        return '';
//                    }
//                ],


//                [
//                    'header' => 'Партия',
//                    'value' => 'barcode_pool.barcode_consignment.dt_create_timestamp',
//                    'contentOptions' => ['style' => 'width: 10px;'],
//                    'format' => [
//                        'datetime',
//                        'php:m.Y',
//                    ],
//                ],
//
//                [
//                    'attribute' => 'short_name',
//                    'contentOptions' => ['style' => 'min-width: 100px;']
//                ],
//
//                [
//                    'attribute' => 'diagnoz',
//                    'contentOptions' => ['style' => 'min-width: 120px;max-width: 130px;overflow: hidden;']
//                ],

//                [
//                    'attribute' => 'decision',
//                    'contentOptions' => ['style' => 'max-width: 40%;min-width: 300px;padding:0px 10px;white-space: pre-wrap;']
//                ],

//                [
//                    'attribute' => 'list_details',
//                    'contentOptions' => ['style' => 'max-width: 11%;overflow: hidden;'],
//                ],

//                [
//                    'attribute' => 'dt_rem_timestamp',
//                    'value' => 'dt_rem_timestamp',
//                    'format' => ['datetime',
//                        'php:d.m.y (H:i:s)',
//                    ],
//                    'contentOptions' => ['style' => 'width: 100px;min-width: 50px;'],
//
//                    'content' => function ($model) {
//                        if (empty($model->dt_rem_timestamp)) {
//                            return '';
//                        }
//                        return date('d.m.y (H:i:s)', $model->dt_rem_timestamp);
//                    }
//
//                ],
//
//                [
//                    'attribute' => 'rem_user_name',
//                    'contentOptions' => ['style' => 'width: 40px;overflow: hidden;'],
//                    'filter' => $rem_master,
//                    'content' => function ($model) {
//                        if (!empty($model->rem_user_name)) {
//                            return $model->rem_user_name;
//                        }
//                        return '';
//                    }
//                ],

//                [
//                    'attribute' => 'mts_user_name',
//                    'contentOptions' => ['style' => 'width: 40px;overflow: hidden;'],
//                    'filter' => $rem_mts,
//                    'content' => function ($model) {
//                        if (isset($model->mts_user_name) && !empty($model->mts_user_name)) {
//                            return preg_replace('/^(\w+)([\S]?)(.*)/u', '$1', $model->mts_user_name);
//                        }
//                        return '';
//                    }
//                ],

//                [
//                    'attribute' => 'num_busline',
//
//                    'contentOptions' => ['style' => 'width: 40px;overflow: hidden;'],
//                    'content' => function ($model) {
//                        if (isset($model->num_busline) && !empty($model->num_busline)) {
//                            return $model->num_busline;
//                        }
//                        return '';
//                    }
//                ],


            ],
        ]
    );
    ?>
    <?php Pjax::end(); ?>
</div>


<?php
$script = <<<JS

setInterval(function(){
 		$.pjax.reload('#pjax-container1')
 	}, 60000);


$(document).on('keypress',function(e) {
    if(e.which == 13) {
      e.preventDefault();
      return false;
    }    
});


///
 $('#barcode').focus();

    // GJDA043382
    // GJDA043176
$('#barcode').on('keyup', function(event) {
    
    if (event.keyCode==13) {
        var data=$(this).val();
        data=data.replace( /(\D)*/, '');
        //alert(data);     // GJDA043382
        //alert(data);     // GJDA043176
        // console.log(data);
        
        //
        var element = document.getElementsByName('post_rem_history[bar_code]');
        $(element).val(data);
        $(element).trigger('click');
        
        /// yiiGridView   
        $("#w1").yiiGridView("applyFilter");
       }
});

JS;


$this->registerJs($script, View::POS_READY);

?>

