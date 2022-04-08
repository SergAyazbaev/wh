<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

//dd($para['otbor']);
//dd($para['otbor']);

$this->title = 'GuideJet ->';
$this->params['breadcrumbs'][] = $this->title;
Pjax::begin();

?>
<h1><?= Html::encode('Приходные накладные') ?></h1>

<?
    echo '<div class="table_with">';

    $dataProvider_into->pagination->pageParam = 'into-page';
    $dataProvider_into->sort->sortParam = 'into-sort';

//    $dataProvider_into->setSort([

    ///////
    echo GridView::widget([
        'dataProvider' => $dataProvider_into,
        'filterModel' => $searchModel_into,

        'showFooter'=>false, // показать
        'showHeader' => true,

        'rowOptions' => function ($model) {


            if ($model->dt_transfered_ok == (int) 2) {
                return ['class' => 'danger'];
            }
            if ($model->dt_transfered_ok == (int) 1) {
                return ['class' => 'info'];
            }

            return '';
        },

            'columns' => [

                [
                    'header'=>'',
                    'contentOptions' => ['style' => ' width: 50px;'],
                    'content' => function($model) {
                        // dd($model);

                        if($model->dt_transfered_ok==0) {
                            $url = Url::to(['/sklad/prihod2?id=' . $model->_id]);

                            return Html::a('Вн', $url, [
                                'class' => 'btn btn-success btn-xs',
                                'data-pjax' => 0,
                                'data-id' => $model->id,

                            ]);
                        }
                        return '';
                    }

                ],

                //'sklad_vid_oper_name',

                "id",

                "tz_id",

                "dt_create",

                "wh_debet_name",
                "wh_debet_element_name",
                "wh_destination_name",

                "wh_destination_element_name",

                // "user_id",
                "user_name",
                // "wh_home_number",

            ],
        ]);

     ?>









        <br>
        <h1>Склад №<?=$para['otbor']?> .Асемтай. Накладные для ПРОШИВКИ </h1>

        <?= Html::a('Создать новую накладную', ['create_new'], ['class' => 'btn btn-success']);
        //= Html::a('Создать новую накладную', ['create?sklad='.$sklad], ['class' => 'btn btn-success']);
        ?>

<!--        --><?//= Html::a('Отчет "ОСТАТКИ по складу" ',
//            ['total_stock'], ['class' => 'btn btn-info']);
//        ?>
<!--        --><?//= Html::a('Отчет "ОСТАТКИ по всем складам пользователя" ',
//            ['total_stock_for_user'], ['class' => 'btn btn-info']);
//        ?>

        <?
//        = Html::a('Печать в Excel', ['/pv?print=1'.$para_str],
//            ['target' => '_blank','class' => 'btn btn-success']);
        ?>

        <?
//        = Html::button('Период с '.($start_date?$start_date:' ...')." по ".($end_date?$end_date:' ...'),
//            ['class' => 'btn btn-success ','id' => 'period' ])
        ?>




        <?
        $dataProvider->pagination->pageParam = 'post-page';
        $dataProvider->sort->sortParam = 'post-sort';


        $dataProvider->setSort([
                'defaultOrder' => ['dt_create'=>SORT_DESC],]);
        ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

            //'summary'=>'', // скрыть
            //'showFooter'=>true, // показать
            //'showHeader' => false, // показать=true

            'columns' => [

                ['class' => 'yii\grid\ActionColumn',
                    'header'=>'',
                    'contentOptions' => ['style' => ' width: 50px;'],
                    'headerOptions' => ['width' => '10'],
                    //'template' => '{view} {update} {delete}{link}',
                    'template' => '{delete}',
                ],


                [
                    'header'=>'',
                    'contentOptions' => ['style' => ' width: 50px;'],
                    'content' => function($model) {
                        // dd($para);

                        $url = Url::to(['/sklad/rewrite_update?id='. $model->_id.'&sklad='.$model->wh_home_number ]);

                        return Html::a('Вн', $url, [
                            'class' => 'btn btn-success btn-xs',
                            'data-pjax' => 0,
                            'data-id' => $model->_id,

                        ]);
                    }
                ],

                [
                    'attribute'=> 'sklad_vid_oper_name',
                    'contentOptions' => ['style' => 'max-width:90px;overflow:hidden;'],
                ],

                'id',
                'tz_id',

                [
                    'attribute'=> 'dt_create',
                    'contentOptions' => ['style' => ' width: 160px;'],
                    'format' =>  ['date', 'd.MM.Y H:i:s'],
                ],


                //"wh_debet_top",


//                [
//                    'attribute'=> "wh_debet_name",
//                    'value'=> 'wh_debet_name',
//                    'header'=>'wer',
//                ],


                [
                    'attribute'=> 'wh_debet_name',
                    'contentOptions' => ['style' => 'max-width:90px;overflow:hidden;'],
                ],
                [
                    'attribute'=> 'wh_debet_element_name',
                    'contentOptions' => ['style' => 'max-width:90px;overflow:hidden;'],
                ],

                [
                    'attribute'=> 'wh_destination_name',
                    'contentOptions' => ['style' => 'max-width:90px;overflow:hidden;'],
                ],
                [
                    'attribute'=> 'wh_destination_element_name',
                    'contentOptions' => ['style' => 'max-width:90px;overflow:hidden;'],
                ],


                // Вариант первый
//        [
//            'attribute'=>'wh_debet_top',
//            'label'=>'Родительская категория',
//            'headerOptions' => ['width' => '30'],
//
//            'contentOptions' =>function ($model, $key, $index, $column){
//                return ['class' => 'wh_debet_top'];
//            },
//
//            'content'=>function($data){
//                return $data['tz_date'];
//            }
//
//        ],
//            'user_id',

//                [
//                    'attribute'=> 'user_name',
//                    'contentOptions' => ['style' => ' width: 75px;'],
//                ],

//                'wh_home_number',


            ],
        ]); ?>



 <?php Pjax::end(); ?>







