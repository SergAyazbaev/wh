<style>
    .glyphicon:hover {
        background-color: aquamarine;
        border-radius: 20px;
        padding: 0px;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
        width: 26px;
    }

    .glyphicon-eye-open::before {
        width: 26px;
    }

    span.glyphicon {
        width: 26px;
    }

    td > a {
        text-decoration: none;
        float: left;
    }

</style>

<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'Накладные по ЦС';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprtype-index">
    <h1><?= $this->title ?></h1>

    <!--    --><? //= Html::a('Список  в EXCEL',
    //        '/stat_ostatki/ost_element_one_sum_print',
    //        [
    //            'class' => 'btn btn-default',
    //            'name' => 'print',
    //            'value' => 1,
    //        ]);
    //    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            [
                'attribute' => 'id',
//                'header' => 'Накладная, №',
                'contentOptions' => ['style' => 'width: 100px;'],
                'content' => function ($model) {
                    $text_ret = '';

                    //ddd($model['sklad_id']);

                    $url = Url::to(['/sklad/update_id?id=' . $model['id'] .
                        '&otbor=' . (isset($model['sklad_id']) ? $model['sklad_id'] : 0)

//                            .'&gr='.$model['wh_tk'].
//                            '&el='.$model['wh_tk_element']
                    ]);

                    $text_ret .= Html::a('' . $model['id'], $url, [
                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
//                            'data-id' => $model->_id,
                        'target' => '_blank'

                    ]);
                    return $text_ret;
                }
            ],

            //  'dt_create',
            [
                'attribute' => 'dt_create_timestamp',

                'contentOptions' => ['style' => 'width: 70px;'],
                'format' => [
                    'datetime',
                    'php:d.m.Y H:i:s',
                ],
            ],


            [
                'attribute' => 'sklad_vid_oper',

                'contentOptions' => ['style' => 'width: 80px;'],
                'content' => function ($model) {

                    if ((int)$model['sklad_vid_oper'] == 2) {
                        return "Приходная";
                    }
                    if ((int)$model['sklad_vid_oper'] == 3) {
                        return "Расходная";
                    }
                    return "";
                }

            ],


//            'wh_debet_element',
//            'wh_destination_element',

//            'wh_home_number',
//            'sprwhelement_home_number.name',
            [
                'attribute' => 'wh_home_number',

                'contentOptions' => ['style' => 'width: 80px;'],
                'content' => function ($model) {

                    //        ddd($model);
                    if (isset($model['sprwhelement_home_number']['name'])) {
                        return $model['sprwhelement_home_number']['name'] . ' (' . $model['sprwhelement_home_number']['id'] . ')';
                    }
                    return "";
                }

            ],


            [
                'attribute' => 'sprwhelement_debet_element.name',
//                'contentOptions' => ['style' => 'width: 80px;'],
            ],
            [
                'attribute' => 'sprwhelement_destination_element.name',
//                'contentOptions' => ['style' => 'width: 80px;'],
            ],
//            'tx',
//            "user_name",
//            "wh_dalee_element_name",
            [
                'attribute' => 'tx',
                'contentOptions' => ['style' => 'width: 80px;'],
            ],
            [
                'attribute' => 'wh_dalee_element_name',
                'contentOptions' => ['style' => 'width: 80px;'],
            ],
            [
                'attribute' => 'user_name',
                'contentOptions' => ['style' => 'width: 80px;'],
            ],


//            [
//                'class' => 'yii\grid\ActionColumn',
//                'template' => '{view} {update} {delete}',
//                'buttons' => [
//                    //                    'view' => function ($url,$model) {
//                    //
//                    //                        if ( Yii::$app->getUser()->identity->group_id==100 ){
//                    //                            return Html::a(
//                    //                                '<span class="glyphicon glyphicon-ok"></span>',
//                    //                                $url);
//                    //                        }
//                    //                        return '';
//                    //
//                    //                    },
//
//
//                    'update' => function ($url) {
//
//                        if ( Yii::$app->getUser()->identity->group_id>=70 ){
//                            return Html::a(
//                                '<span class="glyphicon glyphicon-edit"></span>',
//                                $url);
//                        }
//
//                        return '';
//
//                    },
//                    'delete' => function ($url) {
//
//                        if ( Yii::$app->getUser()->identity->group_id>=100 ){
//                            return Html::a(
//                                '<span class="glyphicon glyphicon glyphicon-trash"></span>',
//                                $url,
//                                [
//                                    'data-method' => 'POST',
//                                    'data-confirm' => Yii::t('yii',
//                                        'УДАЛЯЕМ ЭТУ ПОЗИЦИЮ?'),]
//                            );
//                        }
//
//                        return '';
//
//                    },
//                ],
//
//
//            ],
        ],
    ]); ?>


</div>


