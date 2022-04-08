<?php

use yii\helpers\Html;
use yii\grid\GridView;


//dd($_REQUEST);
//[postpvmotion] => Array
//(
//    [dt_create_start] => 2018-10-01
//            [dt_time_start] => 18:21
//            [dt_create_stop] => 2018-10-26
//            [dt_time_stop] => 18:21
//        )


//if (isset($_REQUEST['postpvmotion']) && !empty($_REQUEST['postpvmotion']))
//{
//    if (isset($_REQUEST['postpvmotion']['dt_create']))
//        $DateStart=date('d.m.Y', strtotime($_REQUEST['postpvmotion']['dt_create']));
//
//
//    if (isset($_REQUEST['postpvmotion']['dt_create_end']))
//        $DateStop=date('d.m.Y', strtotime($_REQUEST['postpvmotion']['dt_create_end']));
//
//}
//
//
//if (empty($DateStart) || empty($DateStop) ){
//    $DateStart  = date('d.m.Y', strtotime('now -1 day'));
//    $DateStop   = date('d.m.Y', strtotime('now'));
//}


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postpvaction */
/* @var $dataProvider yii\data\ActiveDataProvider */

//dd( Yii::$app->request->queryParams['postpvmotion']['pv_id'] );

if(Yii::$app->request->queryParams['postpvmotion']['pv_id']>0)
    $this->title = 'Ключи MSAM'.Yii::$app->request->queryParams['postpvmotion']['pv_id'].')';
else
    $this->title = 'Ключи MSAM';

$this->params['breadcrumbs'][] = $this->title;
?>
<style>



</style>

<div class="pv-action-index">
    <h1><?= Html::encode($this->title) ?></h1>



    <p>
        <div class="date_period1">
            <?
                echo Html::Button('⭱', ['class' => 'btn btn-warning']);


                echo Html::a('Новый ключ',
                        ['create?pv_id='.Yii::$app->request->queryParams['postpvmotion']['pv_id'] ],
                        ['class' => 'btn btn-success']);
            ?>

            <?
                $para_str='?print=0';

                //dd($para);

                if ( isset($para['postpvmotion']) && !empty($para['postpvmotion'])){
                    foreach ($para['postpvmotion'] as $key => $item) {
                        //$para_str.= '&postpv['.$key.']="'.trim($item).'"';
                        $para_str.= '&postpv['.$key.']='.trim($item).'';
                    }
                }

                if ( $para['sort'] )
                    $para_str.='&sort='.$para['sort'];



                echo  Html::a('Сводная ведомость', ['/pvmotion/printsvod'.$para_str],
                    ['target' => '_blank','class' => 'btn btn-success',
                        'style' => 'margin-right:3px' ]);

                echo  Html::a('Печать  АПП', ['/pvmotion/printapp'.$para_str],
                    ['target' => '_blank','class' => 'btn btn-success',
                        'style' => 'margin-right:3px']);

                echo  Html::a('Печать  АДВ', ['/pvmotion/printadv'.$para_str],
                    ['target' => '_blank','class' => 'btn btn-success',
                        'style' => 'margin-right:3px' ]);
            ?>


            <?= $this->render('_search', [
                'model' => $searchModel,
                'para' => Yii::$app->request->queryParams,
            ]); ?>


            <?
//            echo Html::a('Отчёт АПП', ['#'],[
//                    'id' => 'excel_app',
//                    'class' => 'btn btn-success'
//            ]);?>




            <?
//            = Html::button('Период с '.($DateStart?$DateStart:' ...')." по ".($DateStop?$DateStop:' ...'),
//                ['class' => 'btn btn-success ','id' => 'period' ])
            ?>


    </div>



    </p>




    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            '_id',

            [
                'attribute'=> 'dt_create',
                'value'=> 'dt_create',
                'format' =>  ['date', 'dd.M.yy HH:i'],
                'contentOptions' => ['style' => 'width:35px;'],
            ],

            [
                'attribute'=> 'type_action_tx',
                'filter' => \yii\helpers\ArrayHelper::map(frontend\models\Typemotion::find()->all(), 'id', 'tx'),

                //'filter'=> frontend\models\Typemotion::find()->select(['name'])->indexBy('name')->column(),
                //'filter'=> frontend\models\Typemotion::find()->select(['tx'])->indexBy('tx')->column(),

                'value'=> 'type_action_tx',
                'contentOptions' => ['style' => 'max-width: 25px;overflow: auto;'],
            ],


            [
                'attribute'=> 'document',
                'value'=> 'document',
                'contentOptions' => ['style' => 'max-width: 50px;overflow: auto;'],
            ],

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '80'],
                //'template' => '{view} {update} {delete}{link}',
                'template' => ' {update} - - {delete} ',
            ],




//            [
//                'attribute'=> 'type_action',
//                'filter'=> frontend\models\Typemotion::find()->select(['name'])->indexBy('name')->column(),
//                'value'=> 'type_action',
//                'contentOptions' => ['style' => 'max-width: 250px;overflow: auto;'],
//            ],



//            [
//                'attribute'=> 'content',
//                'value'=> 'content',
//                'contentOptions' => ['style' => 'max-width: 250px;overflow: auto;'],
//            ],


//            [
//                'attribute'=> 'comments',
//                'value'=> 'comments',
//                'contentOptions' => ['style' => 'max-width: 250px;overflow: auto;'],
//            ],


//            [
//                'attribute'=> 'wh_top',
//                'filter'=> \yii\helpers\ArrayHelper::map(frontend\models\Sprwhtop::find()->all(),'id','name'),
//                'value'=> 'wh_top_name',
//            ],


            [
                'attribute'=> 'wh_top',
                //'filter'=> frontend\models\Sprwhtop::find()->select(['name'])->indexBy('name')->column(),
                'filter' => \yii\helpers\ArrayHelper::map(frontend\models\Sprwhtop::find()->all(), 'id', 'name'),
                'value'=> 'wh_top_name',
                //'contentOptions' => ['style' => 'max-width: 50px;overflow: auto;'],
            ],


            'wh_element',

            [
                'attribute'=> 'id',
                'value'=> 'id',
                'contentOptions' => ['style' => 'width: 25px;overflow: auto;'],
            ],

            [
                'attribute'=> 'pv_id',
                'value'=> 'pv_id',
                'contentOptions' => ['style' => 'width: 25px;overflow: auto;'],
            ],



            [
                'attribute'=> 'name',
                'value'=> 'name',
                'contentOptions' => ['style' => 'width: 25px;overflow: auto;'],
            ],

            'user_id',


        ],
    ]); ?>
</div>



<?php
$script = <<<JS

$('#date_start').change(function() {   
    
    var date_start  = $('input[id="date_start"]').val();
    var date_stop   = $('input[id="date_stop"]').val();
    
    var pv_id= $('input[name="postpvmotion[pv_id]"]').val();
    var name= $('input[name="postpvmotion[name]"]').val();
    var type_action_tx= $('select[name="postpvmotion[type_action_tx]"]').val();
    var document= $('input[name="postpvmotion[document]"]').val();
    var wh_top_name= $('select[name="postpvmotion[wh_top_name]"]').val();
    var wh_element = $('input[name="postpvmotion[wh_element]"]').val();
    
    
    //alert(123);
    
       $.ajax({
                // url: '/pvmotion/index',
                url: '#',
                type : "get",
                async : true,     
                data: {
                        print : 33,

                    postpvmotion:{
                        date_start: date_start,
                        date_stop:  date_stop,
                        pv_id   :   pv_id,
                        // name    :name,
                        type_action_tx  :type_action_tx,
                        document        :document,
                        wh_top_name     :wh_top_name,
                        wh_element      :wh_element,                             
                    }
                   
                },
//                complete: function (jqXHR, textStatus) {
//                    //$form.trigger(events.ajaxComplete, [jqXHR, textStatus]);
//                },
//                beforeSend: function (jqXHR, settings) {
//                    //$form.trigger(events.ajaxBeforeSend, [jqXHR, settings]);
//                },
                success: function (msg) {
                    console.log(msg);
                    //alert(msg);
                },
                error: function () {
                    alert('ERROR');
                }
         });
   
   
  
});




    $('#excel_app').click(function() {
        var date_start=$('#date_start').val();
        var date_stop=$('#date_stop').val();
        
        console.log(date_start);
        console.log(date_stop);
        
        $.ajax({
                url: '/pv',     
                data: {
                    print:1,
                    date_start: date_start,
                    date_stop:  date_stop,
                },
//                complete: function (jqXHR, textStatus) {
//                    //$form.trigger(events.ajaxComplete, [jqXHR, textStatus]);
//                },
//                beforeSend: function (jqXHR, settings) {
//                    //$form.trigger(events.ajaxBeforeSend, [jqXHR, settings]);
//                },
                success: function (msg) {
                    console.log(msg);
                    //alert(msg);
                },
                error: function () {
                    alert('ERROR');
                }
         });

            
        
    });

    $('#excel_adv').click(function() {
        alert("excel_adv");  
    });

JS;
$this->registerJs($script);
?>
