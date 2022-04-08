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
        'id' => 'pjax-tz',
    ]);

    $dataProvider_tz->pagination->pageParam = 'tz-page';
    $dataProvider_tz->sort->sortParam = 'tz-sort';

    $dataProvider_tz->setSort([
            'defaultOrder' => ['id'=>SORT_DESC],]);

    $dataProvider_tz->pagination->pageSize=10;


    ///////
    echo GridView::widget([
        'dataProvider' => $dataProvider_tz,
        'filterModel' => $searchModel_tz,

        'showFooter'=>false, // показать
        'showHeader' => true,

            'columns' => [

                [
                    'header'=>'',
                    'contentOptions' => ['style' => ' width: 50px;'],
                    'content' => function($model) {

                            $url = Url::to(['/sklad/createfromtz?tz_id=' . $model->id  ]);

                            return Html::a('Вн', $url, [
                                'class' => 'btn btn-success btn-xs',
                                'data-pjax' => 0,
                                'data-id' => $model->id,

                            ]);

                    }

                ],

                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 80px;overflow: hidden;'],
                ],
                [
                    'attribute' => 'name_tz',
                    'contentOptions' => ['style' => ' min-width: 20px;max-width: 60px;overflow: hidden;'],
                ],
                [
                    'attribute' => 'multi_tz',
                    'contentOptions' => ['style' => ' width: 160px;'],
                ],

                "wh_cred_top_name",

                [
                    'attribute'=> 'dt_create',
                    'contentOptions' => [ 'style' => ' width: 130px;' ],
                    'format' => [ 'date', 'php:d.m.Y (H:i:s)' ],
                ],

                [
                    'attribute'=> 'dt_deadline',
                    'contentOptions' => ['style' => ' width: 110px;'],
                    'format' =>  ['date', 'php:d.m.Y'],
                ],


                [

                    'attribute'=> 'user_create_name',
                    'contentOptions' => ['style' => ' max-width: 60px;overflow: hidden;'],

                    'content' => function($model) {

                        return
                            $model['user_create_name'];
//                            .' '.
//                            '['.$model['user_edit_group_id'].','.
//                            $model['user_create_id'].'] '
//                            ;

                    }
                ]


            ],
        ]);

    Pjax::end();
     ?>

</div>



