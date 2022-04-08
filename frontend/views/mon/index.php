<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>


<?php Pjax::begin(); ?>

<div class="table_with">

    <?php

//dd($model);


//    $dataProvider_into->pagination->pageParam = 'into-page';
//    $dataProvider_into->sort->sortParam = 'into-sort';
//
//    $dataProvider_into->setSort([
//             'defaultOrder' => ['dt_deadline'=>SORT_DESC],]);
//    $dataProvider_into->pagination->pageSize=3;

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

            'columns' => [


                [
                    'header'=>'',
                    'contentOptions' => ['style' => 'width: 43px;overflow: auto;'],
                    'content' => function($model) {
                        //dd($model);
                        $url = Url::to(['/mon/update?id='. $model->_id ]);
                        return Html::a('Вн', $url, [
                            'class' => 'btn btn-success btn-xs',
                            'data-pjax' => 0,
                            'data-id' => $model->_id,
                        ]);
                    }
                ],


                [
                    'attribute'=> 'id',
                    'contentOptions' => ['style' => 'width: 30px;'],
                ],

                [
                    'attribute'=> 'username',
                    'contentOptions' => ['style' => 'overflow: hidden;'],
                ],


   "email",
//   "password_hash",
//   "auth_key",
   "status",
   "role",
   "group_id",

                ['class' => 'yii\grid\ActionColumn'],
            ],

        ]);

     ?>
</div>



    <?php Pjax::end(); ?>





