<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;


$this->title = 'GuideJet ->';
$this->params['breadcrumbs'][] = $this->title;

//Pjax::begin();
?>


<div class="table_with">
    <?php
    Pjax::begin([
        'id' => 'pjax-shablon',
    ]);

    $dataProvider_shablon->pagination->pageParam = 'shablon-page';
    $dataProvider_shablon->sort->sortParam = 'shablon-sort';

    $dataProvider_shablon->setSort([
            'defaultOrder' => ['id'=>SORT_DESC],]);

    $dataProvider_shablon->pagination->pageSize=15;

    ///////
    echo GridView::widget([
        'dataProvider' => $dataProvider_shablon,
        'filterModel' => $searchModel_shablon,

        'showFooter'=>false, // показать
        'showHeader' => true,

            'columns' => [
                [
                    'header'=>'',
                    'contentOptions' => ['style' => ' width: 50px;'],
                    'content' => function($model,$key,$key2) {
                            $url = Url::to(['/sklad/createfrom_shablon?shablon_id='.$model->id.'&otbor=' ]);

                            return Html::a('Вн', $url, [
                                'class' => 'btn btn-success btn-xs',
                               // 'data-pjax' => 0,
                                'data-id' => $model->id,

                            ]);

                    }

                ],
                [
                    'attribute'=> 'id',
                    'contentOptions' => ['style' => 'width: 30px;'],

                    'header'=>'',
                ],


                "shablon_name",

            ],
        ]);

     ?>

    <?php Pjax::end(); ?>

</div>




