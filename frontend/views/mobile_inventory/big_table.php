<?php

use yii\helpers\Url;
use \yii\widgets\Pjax;
use \yii\grid\GridView;
use \yii\helpers\Html;

$this->title = 'Ведомость ';
?>

<style>
    .wrap > .container22 {
        margin-top: 0px;
    }

    .wrap {
        width: 500px;
        margin-left: 10%;
        margin-top: 5%;
    }

    @media (max-width: 500px) {
        .wrap {
            width: auto;
            margin-left: 0;
            margin-top: 5%;
        }

    }


    /*/ FUTER NEXT >>//*/
    div._futer {
        margin-top: 30px;
        display: inline-flex;
        position: inherit;
        padding: 5px 0px;
        border: 0.5px solid #a3a3a3;
        border-radius: 10px;
        width: 100%;
    }

    div.past_futer {
        float: left;
        width: calc((98% - 140px));
    }

    div.next_futer {
        float: right;
        width: 140px;
    }

    /*//ALL BUTTONS/*/
    div > .width_all_butt {
        width: 100%;
        padding: 60px 20px;
    }

    div.width_all_butt a {
        margin: 7px;
        width: 94%;
    }


    .mobile-body-content {
        background-color: #96ff0000;
        width: 100%;
        min-height: min-content;
        padding: 0px;
        margin: 0px;
        display: block;
        position: relative;
    }

    a.next_step {
        max-width: 120px;
        display: block;
        position: relative;
        /*float: right;*/
    }

    a.back_step {
        max-width: 120px;
        display: block;
        position: relative;
    }

    ._sklad {
        margin-bottom: 10px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 5px;
        font-size: 22px;
        font-weight: bolder;
        text-align: center;

        display: block;
        /*position: absolute;*/
        float: left;
        width: 100%;
    }

    .ap_pe {
        margin-bottom: 30px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 5px;
        font-size: 22px;
        font-weight: bolder;
        text-align: center;

        display: block;
        /*position: absolute;*/
        float: left;
        width: 100%;
    }

    .ap_pe:empty {
        background-color: #ff1c43;
        display: none;
    }

    .ap_pe > p {
        text-align: center;
        font-size: 23px;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: bolder;
        margin-bottom: 0px;

    }

    .jumbotron > h1 {
        font-size: xx-large;
        padding: 5px;
        margin-top: 10px;
        color: #245580;
    }


    .jumbotron > h2 {
        font-size: x-large;
        margin-bottom: 10px;
    }


    @media (max-width: 700px) {
        .ap_pe {
            top: 110px;
            left: 4px;
        }


        p > a {
            height: 50px;
        }

        .btn {
            margin: 3px 10px;
            font-size: 22px;
        }
    }

    .alert_str {

    }

    .btn-xs, .btn-group-xs > .btn {
        padding: 1px 5px;
        font-size: 16px;
        line-height: 1.5;
        border-radius: 3px;
    }

    .grid-view .filters input, .grid-view .filters select {
        min-width: 50px;
        font-size: 24px;
        padding: 3px;
    }

</style>


<div class="site-index">
    <div class="mobile-body-content">

        <?php

        if (isset($alert_str) && !empty($alert_str)) {
            echo '<div class="alert_str" style="display: block;text-align: center;background-color: #ff7100;font-size: 26px;padding: 41px 10%;position: absolute;z-index: 99;top: 130px;width: 100%;">' .
                $alert_str
                . '</div>';
        }


        ?>


        <div class="ap_pe"><?php
            if (isset($name_pe) && strlen($name_pe) > 1) {
                //echo $name_ap . ' - ' . $name_pe;
                echo $name_ap;
            }
            ?></div>


        <div class="form-group">


            <?php Pjax::begin(['id' => 'pjax_2']); ?>


            <?php

            if (isset($dataProvider)) {

                echo GridView::widget(
                    [
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,

                        'summary' => 'Всего строк {totalCount}',

                        'rowOptions' => function ($model) {


                            if (isset($model['thing_check']) && $model['thing_check'] === '1') {
                                return [
                                    'class' => 'success',
                                ];
                            }

                            return '';
                        },


                        'columns' => [

//                            [
//                                'attribute' => 'wh_destination_element_name',
//
//                                'format' => 'raw',
//                                'contentOptions' => ['style' => ' width: 55px;'],
//                                'content' => function ($model) {
//
//
//                                    // ddd($model->wh_destination_element);
//
//
//                                    $text_ret = '';
//
//                                    //$url = Url::to(['/mobile_inventory/look_pe?pe=' . $model->wh_destination_element]);
//                                    $url = "";
//
//                                    //$str_menu = (isset($model->wh_destination_element_name) ? $model->wh_destination_element_name : 'Б / Н');
//                                    $str_menu = "1231";
//
//                                    $text_ret .= ' ' . Html::a(
//                                        //'Выбрать',
//                                            $str_menu,
//                                            $url, [
//                                                'class' => 'btn btn-success btn-xs',
//                                                'target' => '_blank',
//                                                'data-pjax' => 0,
//                                                'data-id' => $model->id,
//                                            ]
//                                        );
//
//
//                                    return $text_ret;
//                                },
//                            ],


                            [
                                'attribute' => 'dt_create_timestamp',
//                                'contentOptions' => ['style' => 'width: 100px;'],
                                'contentOptions' => function ($model) {

                                    $string = substr(dechex($model['dt_create_timestamp'] . $model['bort'] . $model['gos']), -6);
                                    $backgroundColor = '#' . substr($string, 0, 6) . '41';
                                    $num = 'width: 100px;background-color: ' . $backgroundColor;

                                    return ['style' => $num];
                                },

                                'format' => ['datetime', 'php:d.m.Y H:i:s',]
                            ],


                            [
                                'attribute' => 'bort',
                                'contentOptions' => function ($model) {

                                    $string = substr(dechex($model['dt_create_timestamp'] . $model['bort'] . $model['gos']), -6);
                                    $backgroundColor = '#' . substr($string, 0, 6) . '41';
                                    $num = 'width: 100px;background-color: ' . $backgroundColor;

                                    return ['style' => $num];
                                },

                            ],

                            [
                                'attribute' => 'check_bort',
                                'contentOptions' => ['style' => 'width: 15px;'],

                                'content' => function ($model) {
                                    if ($model['check_bort'] === '1') {
                                        return 'Ok';
                                    }
                                    return '';
                                }
                            ],
                            [
                                'attribute' => 'gos',
                                'contentOptions' => function ($model) {

                                    $string = substr(dechex($model['dt_create_timestamp'] . $model['bort'] . $model['gos']), -6);
                                    $backgroundColor = '#' . substr($string, 0, 6) . '41';
                                    $num = 'width: 100px;background-color: ' . $backgroundColor;

                                    return ['style' => $num];
                                },
                            ],
                            [
                                'attribute' => 'check_gos',
                                'contentOptions' => ['style' => 'width: 15px;'],

                                'content' => function ($model) {
                                    if ($model['check_gos'] === '1') {
                                        return 'Ok';
                                    }
                                    return '';
                                }
                            ],


//                            [
//                                'attribute' => 'spr_globam.name',
//                                'contentOptions' => ['style' => 'width: 75px;'],
//                            ],
                            [
                                'attribute' => 'spr_globam_element.name',
                                'contentOptions' => function ($model) {

                                    $string = substr(dechex($model['dt_create_timestamp'] . $model['bort'] . $model['gos']), -6);
                                    $backgroundColor = '#' . substr($string, 0, 6) . '61';
                                    $num = 'width: 100px;background-color: ' . $backgroundColor;

                                    return ['style' => $num];
                                },
                            ],

                            [
                                'attribute' => 'thing_count',
                                'contentOptions' => ['style' => 'width: 75px;'],
                            ],
                            [
                                'attribute' => 'thing_check',
                                'contentOptions' => ['style' => 'width: 75px;'],

                                'content' => function ($model) {

                                    if ($model['thing_check'] === '1') {
                                        return 'Ok';
                                    }

                                    return '';
                                }

                            ],
                            [
                                'attribute' => 'thing_barcode',
                                'contentOptions' => ['style' => 'width: 75px;'],
                            ],

//                            [
//                                'attribute' => 'mts_id',
//                                'contentOptions' => ['style' => 'width: 75px;'],
//                            ],

                            [

                                'format' => 'raw',
                                'contentOptions' => ['style' => ' width: 55px;'],
                                'content' => function ($model) {

                                    $text_ret = '';

                                    $url = Url::to(['/mobile_inventory/edit_inventory?id=' . $model->_id]);


                                    $text_ret .= ' ' . Html::a(
                                            'Ред',
                                            $url, [
                                                'class' => 'btn btn-success btn-xs',
                                                'target' => '_blank',
                                                'data-pjax' => 0,
                                                //'data-id' => $model->id,
                                            ]
                                        );


                                    return $text_ret;
                                },
                            ],



                        ],
                    ]
                );

            }

            ?>

            <?php Pjax::end(); ?>
        </div>
        <br>


        <div class="_futer">
            <div class="past_futer">

                <?php
                echo Html::a(
                    '<< Выход',
                    ['/mobile_inventory/init_table'],
                    [
                        'onclick' => 'window.opener.location.reload();window.close();',
                        'class' => ' back_step btn btn-warning',
                    ]);
                ?>
            </div>
            <div class="next_futer">

                <?= Html::a('Далее >>', ['mobile_inventory/init_table'], ['class' => ' next_step btn btn-success']) ?>

            </div>
        </div>

    </div>
</div>



<br>
<br>
<br>

