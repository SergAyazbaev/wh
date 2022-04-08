<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;


$this->title = 'GuideJet ->';
$this->params['breadcrumbs'][] = $this->title;


?>


<div class="table_with">
    <?php
    Pjax::begin([
        'id' => 'pjax-deliver',
    ]);

    $dataProvider_into->pagination->pageParam = 'deliver-page';
    $dataProvider_into->sort->sortParam = 'deliver-sort';
    //
    $dataProvider_into->setSort([
        'defaultOrder' => ['id' => SORT_DESC],]);

    $dataProvider_into->pagination->pageSize = 15;


    ///////
    echo GridView::widget([
        'dataProvider' => $dataProvider_into,
        'filterModel' => $searchModel_into,

        'showFooter' => false, // показать
        'showHeader' => true,

        'columns' => [

            [
                'header' => '',
                'contentOptions' => ['style' => ' width:10px;'],
                'content' => function ($model) {

                    if ($model->dt_transfered_ok == (int)0) {
                        $url = Url::to(['/sklad/prihod2?id=' . $model->_id . '&otbor=' . $model->wh_home_number]);

                        return Html::a('Вн', $url, [
                            'class' => 'btn btn-success btn-xs',
                            //'data-pjax' => 0,
                            'target' => "_blank",
                            'data-id' => $model->id,

                        ]);
                    }
                    return '';
                }
            ],


            [
                'attribute' => 'id',
                'contentOptions' => ['style' => 'width:10px;min-width:10px;max-width:70px'],
            ],
//            [
//                'attribute' => 'dt_create',
//                'contentOptions' => ['style' => ' width: 110px;'],
//                'format' => ['date', 'php:d.m.Y'],
//            ],
            [
                'attribute' => 'dt_create_timestamp',
                'contentOptions' => ['style' => ' width: 110px;'],
                'format' => ['date', 'php:d.m.Y H:i:s'],
            ],
            [
                'attribute' => 'dt_transfer_start_timestamp',
                'contentOptions' => ['style' => ' width: 110px;'],
                'format' => ['date', 'php:d.m.Y H:i:s'],
            ],

            [
                'attribute' => 'user_name',
                //'header'=>'Имя перед.',
                'contentOptions' => ['style' => 'width: 70px; max-width: 110px;overflow: hidden;'],
            ],

            [
                'header' => '',
                'value' => "wh_home_number",
                'contentOptions' => ['style' => 'width: 60px;overflow: hidden;'],
            ],


            [
                'attribute' => 'sklad_vid_oper_name',
                'contentOptions' => ['style' => 'width: 70px; max-width: 90px;overflow: hidden;'],
            ],

            [
                'header' => '',
                'contentOptions' => ['style' => ' width:50px;'],
                'content' => function ($model) {
                    //dd($model);

                    if ($model->dt_transfered_ok == (int)0) {
                        $url = Url::to(['/sklad/prihod2_fast?id=' . $model->_id . '&otbor=' . $model->wh_home_number]);

                        return Html::a(
                            'Fast', $url, [
                            'class' => 'btn btn-success btn-xs',
                          //  'data-pjax' => 0,
                            'data-id' => $model->id,

                        ]);
                    }

                    return '';
                },
            ],

            //'wh_dalee_element',

            [
              'header' => 'Получатель:',
                'attribute' => 'wh_dalee_element',
                'value' => 'sprwhelement_wh_dalee_element.name',
                'contentOptions' => ['style' => 'max-width:20px;overflow:hidden;word-break: break-word;']
              
            ],



            [
                'attribute' => 'wh_debet_name',
                'contentOptions' => ['style' => 'max-width:20px;overflow:hidden;word-break: break-word;'],
            ],
            [
                'attribute' => 'wh_debet_element_name',
                'contentOptions' => ['style' => 'width:30px;overflow:auto;'],

            ],

            [
                'attribute' => 'dt_transfered_user_name',
                'contentOptions' => ['style' => 'width:90px;overflow:hidden;'],
                'content' => function ($model) {
                    //dd($model->dt_transfered_user_name);

                    if (isset($model->dt_transfered_user_name) && !empty($model->dt_transfered_user_name)) {
                        return $model->dt_transfered_user_name;
                    }
                    return '';
                }


            ],

        ],
    ]);

    Pjax::end();
    ?>


</div>
