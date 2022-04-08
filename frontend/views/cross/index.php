<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

if (empty($model->dt_create) || empty($model->dt_create_end) ){

    $model->dt_create    = Date('d.m.Y', strtotime('now -3 day'));
    $model->dt_create_end   = Date('d.m.Y', strtotime('now'));

}

$start_date=$model->dt_create;
$end_date=$model->dt_create_end;

$this->title = 'Сквозная нумерация ';
$this->params['breadcrumbs'][] = $this->title;
?>




<div class="pv-index" style="display: inline-block;">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>


    <?
//    php   echo $this->render('_search',
//        [
//            'model' => $searchModel,
//            'start_date' => $start_date,
//            'end_date' => $end_date
//        ]
//
//    );
    ?>




    <p class="breb_crumbs_pice">
        <?
            $para_str='';

                if ( isset($para['postpv']) && !empty($para['postpv'])){
                    foreach ($para['postpv'] as $key => $item) {
                        $para_str.= '&postpv['.$key.']='.$item.'';
                    }
                }

                if ( $para['sort'] )
                    $para_str.='&sort='.$para['sort'];
        ?>


    </p>




    <?
//    = Html::a('Создать',
//        ['create'],
//        ['class' => 'btn btn-success']);
    ?>

    <?
//    = Html::button('Период с '.($start_date?$start_date:' ...')." по ".($end_date?$end_date:' ...'),
//        ['class' => 'btn btn-success ','id' => 'period' ])
    ?>


    </div>


<div class="table_with">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // 'layout'=>"{sorter} \n{items}\n{pager}\n{summary}\n",

        'columns' => [
            ['class' => 'yii\grid\ActionColumn',
                'header'=>'',
                'headerOptions' => ['width' => '10px'],
                //'template' => '{view} {update} {delete}{link}',
                'template' => ' {update} ',
            ],


            [
                'attribute'=> 'id',
                'value'=> 'id',
                'contentOptions' => ['style' => ' max-width: 15px;'],
            ],
            [
                'attribute'=> 'parent_id',
                'value'=> 'parent_id',
                'contentOptions' => ['style' => ' max-width: 15px;'],
            ],


//            [
//                'attribute'=> 'id',
//                'value'=> 'id',
//                'contentOptions' => ['style' => ' width: 25px;'],
//            ],




//            [
//                'attribute'=> 'dt_create',
//                'value'=> 'dt_create',
//                'format' =>  ['date', 'dd.M.yy HH:i'],
//                'contentOptions' => ['style' => ''],
//            ],


            'dt_create',
            'dt_print',

//            'dt_create_end',
//            'dt_edit',

//            [
//                'attribute'=> 'bar_code_aa',
//                'value'=> 'bar_code_aa',
//                'contentOptions' => ['style' => ' max-width: 15px;'],
//            ],
//            [
//                'attribute'=> 'bar_code_int',
//                'value'=> 'bar_code_int',
//                'contentOptions' => ['style' => ' max-width: 15px;'],
//            ],


            'bar_code_cross',

//            'name',

            [
                'attribute'=> 'html_text',
                'value'=> 'html_text',
                'contentOptions' => ['style' => ' max-width: 330px;overflow: hidden;'],
            ],





        ],
    ]); ?>



    <?php Pjax::end(); ?>

    </div>




<?php
$script = <<<JS



JS;
$this->registerJs($script);
?>



