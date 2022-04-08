<?php

use frontend\models\Sprtype;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

if (empty($model->dt_create) || empty($model->dt_create_end) ){

    $model->dt_create    = Date('Y-m-d', strtotime('now -3 day'));
    $model->dt_create_end   = Date('Y-m-d', strtotime('now'));

}
$start_date=$model->dt_create;
$end_date=$model->dt_create_end;




/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postpv */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Приборы учета (полный список)';
$this->params['breadcrumbs'][] = $this->title;
?>




<div class="pv-index" style="display: inline-block;">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php   echo $this->render('_search',
        [
            'model' => $searchModel,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]

    );
    ?>




        <?
            $para_str='';

                if ( isset($para['postpv']) && !empty($para['postpv'])){
                    foreach ($para['postpv'] as $key => $item) {
                        $para_str.= '&postpv['.$key.']='.$item.'';
                    }
                }

//                if ( $para['sort'] )
//                    $para_str.='&sort='.$para['sort'];
        ?>


    <p class="breb_crumbs_pice">

        <?= Html::a('Создать новый прибор', ['create'], ['class' => 'btn btn-success']);
        ?>

<!--        --><?//= Html::a('Печать в Excel', ['/pv?print=1'.$para_str],
//            ['target' => '_blank','class' => 'btn btn-success']);
//        ?>

        <?= Html::button('Период с '.($start_date?$start_date:' ...')." по ".($end_date?$end_date:' ...'),
            ['class' => 'btn btn-success ','id' => 'period' ])
        ?>

    </p>



</div>


<?php Pjax::begin(); ?>


<div class="table_with">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        // 'layout'=>"{sorter} \n{items}\n{pager}\n{summary}\n",

        'columns' => [

            [
                'header'=>'',
                'content' => function($model) {
                    //dd($model);

                    //$url = Url::to(['pv/cart?id='. $model->_id. '&n_id='. $model->id ]);
                    //$url = Url::to(['pv/cart_act?id='. $model->id ]);

                    $url = Url::to(['/pvmotion?postpvmotion[pv_id]='. $model->id ]);

                    return Html::a('Движ..', $url, [

                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
                        'data-id' => $model->_id,
                    ]);
                }
            ],

            [
                'header'=>'Акты',
                'content' => function($model) {
                    //dd($model);

                    //$url = Url::to(['/pvmobile?postpvmotion[pv_id]='. $model->id ]);
                    //
                    $url = Url::to(['/pvmobile?postpvmobile[pv_id]='. $model->id ]);

                    return Html::a('Установка', $url, [

                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
                        'data-id' => $model->_id,
                    ]);
                }
            ],

            [
                'attribute'=> 'id',
                'value'=> 'id',
                'contentOptions' => ['style' => ' width: 25px;'],
            ],


            [
                'header'=>'Ремонты',
                'content' => function($model) {
                    //dd($model);

                    //$url = Url::to(['pv/cart?id='. $model->_id. '&n_id='. $model->id ]);
                    //$url = Url::to(['pv/cart_act?id='. $model->id ]);

                    $url = Url::to(['/pvrestore?postpvrestore[pv_id]='. $model->id ]);

                    return Html::a('Ремонты', $url, [

                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
                        'data-id' => $model->_id,
                    ]);
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '80'],
                //'template' => '{view} {update} {delete}{link}',
                'template' => ' {update} - - {delete} ',
            ],

            [
                'attribute'=> 'type_pv_name',
                'filter'=> Sprtype::find()->select(['name'])->indexBy('name')->column(),
                'value'=> 'type_pv_name',
                'contentOptions' => ['style' => ''],
            ],

//            [
//                'attribute'=> 'group_pv_name',
//                'contentOptions' => ['style' => 'max-width: 250px;overflow: auto;'],
//                'filter'=> frontend\models\SprGroup::find()->select(['name'])->indexBy('name')->column(),
//                'value'=> 'group_pv_name',
//                'contentOptions' => ['style' => ''],
//            ],


            //'dt_create_pv',

            [
                'attribute'=> 'dt_create',
                'value'=> 'dt_create',
                'format' =>  ['date', 'dd.M.yy HH:i'],
                'contentOptions' => ['style' => ''],
            ],



            //'pv_health',


////               'format' => 'boolean',
////               'format' => 'integer',
//               'format' => 'text',




            [
                'attribute'=> 'bar_code_pv',
                'value'=> 'bar_code_pv',
                'contentOptions' => ['style' => ''],
            ],

            [
                'attribute'=> 'qr_code_pv',
                'value'=> 'qr_code_pv',
                'contentOptions' => ['style' => ''],
            ],

            [
                'attribute'=> 'pv_bee',
                'value'=> 'pv_bee',
                'contentOptions' => ['style' => ''],
            ],

            [
                'attribute'=> 'pv_kcell',
                'value'=> 'pv_kcell',
                'contentOptions' => ['style' => ''],
            ],

            [
                'attribute'=> 'pv_imei',
                'value'=> 'pv_imei',
                'contentOptions' => ['style' => ''],
            ],





//            [
//                'label' => 'Ссылка',
//                'format' => 'raw',
//                'value' => function($data){
//                    return Html::a(
//                        'Перейти',
//                        '/dsfgsdfg/sdfgsd',
//                        [
//                            'title' => 'Смелей вперед!',
//                            'target' => '_blank'
//                        ]
//                    );
//                }
//            ],

        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

    </div>




<?php
$script = <<<JS
JS;
$this->registerJs($script);
?>



