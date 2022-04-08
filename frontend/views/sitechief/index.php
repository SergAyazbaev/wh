<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;


//dd($_REQUEST);

if (isset($_REQUEST['postpv']) && !empty($_REQUEST['postpv']))
{
//    if (isset($_REQUEST['postpv']['dt_create']))
//        //$DateStart=$_REQUEST['postpv']['dt_create'];
//        $DateStart=date('Y-m-d', strtotime($_REQUEST['postpv']['dt_create']));
//
//
//    if (isset($_REQUEST['postpv']['dt_create_end']))
//        //$DateStop=$_REQUEST['postpv']['dt_create_end'];
//        $DateStop=date('Y-m-d', strtotime($_REQUEST['postpv']['dt_create_end']));

}


if (empty($DateStart) || empty($DateStop) ){
    $DateStart  = date('Y-m-d', strtotime('now -1 day'));
    $DateStop   = date('Y-m-d', strtotime('now'));
}




/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postpv */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Приборы учета (полный список)';
$this->params['breadcrumbs'][] = $this->title;
?>




<div class="pv-index" style="display: inline-block;">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>


        <?//php   echo $this->render('_search', ['model' => $searchModel]);     ?>



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

        <?= Html::a('Печать в Excel', ['/pv?print=1'.$para_str],
            ['target' => '_blank','class' => 'btn btn-success']);    ?>

    </p>


    <?= Html::button('Период с '.($DateStart?$DateStart:' ...')." по ".($DateStop?$DateStop:' ...'),
        ['class' => 'btn btn-success ','id' => 'period' ])
    ?>


    <p class="breb_crumbs_pice">
         <?= Html::a('Создать новый прибор', ['create'], ['class' => 'btn btn-success']);    ?>
    </p>


    </div>
</div>

<div class="table_with">
    <?

//    dd($searchModel);

    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

//            [
//                'header'=>'Накл.',
//                'content' => function($model) {
//                    //dd($model);
//
//                    //$url = Url::to(['pv/cart?id='. $model->_id. '&n_id='. $model->id ]);
//                    //$url = Url::to(['pv/cart_act?id='. $model->id ]);
//
//                    $url = Url::to(['/pvmotion?postpvmotion[pv_id]='. $model->id ]);
//
//                    return Html::a('Движ..', $url, [
//
//                        'class' => 'btn btn-success btn-xs',
//                        'data-pjax' => 0,
//                        'data-id' => $model->_id,
//                    ]);
//                }
//            ],

//            [
//                'header'=>'Акты',
//                'content' => function($model) {
//                    //dd($model);
//
//                    //$url = Url::to(['/pvmobile?postpvmotion[pv_id]='. $model->id ]);
//                    //
//                    $url = Url::to(['/pvmobile?postpvmobile[pv_id]='. $model->id ]);
//
//                    return Html::a('Установка', $url, [
//
//                        'class' => 'btn btn-success btn-xs',
//                        'data-pjax' => 0,
//                        'data-id' => $model->_id,
//                    ]);
//                }
//            ],

            [
                'attribute'=> 'id',
                'value'=> 'id',
                'contentOptions' => ['style' => ' width: 25px;'],
            ],



//            [
//                'header'=>'Ремонты',
//                'content' => function($model) {
//                    //dd($model);
//
//                    //$url = Url::to(['pv/cart?id='. $model->_id. '&n_id='. $model->id ]);
//                    //$url = Url::to(['pv/cart_act?id='. $model->id ]);
//
//                    $url = Url::to(['/pvrestore?postpvrestore[pv_id]='. $model->id ]);
//
//                    return Html::a('Ремонты', $url, [
//
//                        'class' => 'btn btn-success btn-xs',
//                        'data-pjax' => 0,
//                        'data-id' => $model->_id,
//                    ]);
//                }
//            ],

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '80'],
                //'template' => '{view} {update} {delete}{link}',
                'template' => ' {update} - - {delete} ',
            ],

//            [
//                'attribute'=> 'type_pv_name',
//                'filter'=> frontend\models\Sprtype::find()->select(['name'])->indexBy('name')->column(),
//                'value'=> 'type_pv_name',
//                'contentOptions' => ['style' => ''],
//            ],

//            [
//                'attribute'=> 'group_pv_name',
//                'contentOptions' => ['style' => 'max-width: 250px;overflow: auto;'],
//                'filter'=> frontend\models\SprGroup::find()->select(['name'])->indexBy('name')->column(),
//                'value'=> 'group_pv_name',
//                'contentOptions' => ['style' => ''],
//            ],


//            [
//                'attribute'=> 'dt_create',
//                'value'=> 'dt_create',
//                'format' =>  ['date', 'dd.M.yy HH:i'],
//                'contentOptions' => ['style' => ''],
//            ],



            //'pv_health',


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
    ]); ?>
    <?php Pjax::end(); ?>

    </div>




<?php
$script = <<<JS

// $('#form').yiiActiveForm('add', {
//    id: 'address',
//    name: 'address',
//    container: '.field-address',
//    input: '#address',
//    error: '.help-block',
//    validate:  function (attribute, value, messages, deferred, $form) {
//        yii.validation.required(value, messages, {message: "Validation Message Here"});
//    }
//});

 
 
 // function excelobject(){
 //                    alert('excel_object');   
 //
 // }

JS;
$this->registerJs($script);
?>



