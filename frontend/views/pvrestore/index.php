<?php

use yii\grid\GridView;
use yii\helpers\Html;


//dd($_REQUEST);
//[postpvrestore] => Array
//(
//    [dt_create_start] => 2018-10-01
//            [dt_time_start] => 18:21
//            [dt_create_stop] => 2018-10-26
//            [dt_time_stop] => 18:21
//        )


//if (isset($_REQUEST['postpvrestore']) && !empty($_REQUEST['postpvrestore']))
//{
//    if (isset($_REQUEST['postpvrestore']['dt_create']))
//        $DateStart=date('d.m.Y', strtotime($_REQUEST['postpvrestore']['dt_create']));
//
//
//    if (isset($_REQUEST['postpvrestore']['dt_create_end']))
//        $DateStop=date('d.m.Y', strtotime($_REQUEST['postpvrestore']['dt_create_end']));
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

//dd( Yii::$app->request->queryParams['postpvrestore']['pv_id'] );

if(Yii::$app->request->queryParams['postpvrestore']['pv_id']>0)
    $this->title = 'История обслуживаний (Инв.№ '.Yii::$app->request->queryParams['postpvrestore']['pv_id'].')';
else
    $this->title = 'История обслуживаний (для всех устройств)';

$this->params['breadcrumbs'][] = $this->title;
?>
<style>

    h1, .h1 {
        font-size: 20px;
        padding: 1px 18px;
    }

</style>

<div class="pv-action-index">
    <h1><?= Html::encode($this->title) ?></h1>



    <p>
        <div class="date_period1">
            <?
                echo Html::Button('⭱', ['class' => 'btn btn-warning']);


                if( Yii::$app->request->queryParams['postpvrestore']['pv_id']>0 ) {
                    echo Html::a('Запись действий',
                        ['create?pv_id='.Yii::$app->request->queryParams['postpvrestore']['pv_id'] ],
                        ['class' => 'btn btn-success']);
                }else{
                    //echo Html::a('Запись о перемещении', ['create'],['class' => 'btn btn-success']);
                }
            ?>

            <?
                $para_str='?print=0';

                //dd($para);

                if ( isset($para['postpvrestore']) && !empty($para['postpvrestore'])){
                    foreach ($para['postpvrestore'] as $key => $item) {
                        //$para_str.= '&postpv['.$key.']="'.trim($item).'"';
                        $para_str.= '&postpv['.$key.']='.trim($item).'';
                    }
                }

                if ( $para['sort'] )
                    $para_str.='&sort='.$para['sort'];



//                echo  Html::a('Сводная ведомость', ['/pvrestore/printsvod'.$para_str],
//                    ['target' => '_blank','class' => 'btn btn-success',
//                        'style' => 'margin-right:3px' ]);
//
//                echo  Html::a('Печать  АПП', ['/pvrestore/printapp'.$para_str],
//                    ['target' => '_blank','class' => 'btn btn-success',
//                        'style' => 'margin-right:3px']);
//
//                echo  Html::a('Печать  АВД', ['/pvrestore/printavd'.$para_str],
//                    ['target' => '_blank','class' => 'btn btn-success',
//                        'style' => 'margin-right:3px' ]);
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



            [
                'attribute'=> 'dt_create',
                'value'=> 'dt_create',
                'format' =>  ['date', 'dd.M.yy HH:i'],
                'contentOptions' => ['style' => 'width:35px;'],
            ],
            [
                'attribute'=> 'type_action_name',
                'filter' => frontend\models\Sprrestore::find()->select(['name'])->indexBy('name')->column(),
                'value'=> 'type_action_name',
                'contentOptions' => ['style' => 'max-width: 250px;overflow: auto;'],
            ],


            ['class' => 'yii\grid\ActionColumn',
              'header'=>'Действия',
            'headerOptions' => ['width' => '80'],
            //'template' => '{view} {update} {delete}{link}',
            'template' => ' {update} - - {delete} ',
            ],

            [
                'attribute'=> 'id',
                'header'=>'Id',
                'value'=> 'id',
                'contentOptions' => ['style' => 'width: 20px;overflow: auto;'],
            ],



//            [
//                'attribute'=>  'list_details',
//                //'filter'=> frontend\models\Typemotion::find()->select(['name'])->indexBy('name')->column(),
//                'value'=>  'list_details',
//                'contentOptions' => ['style' => 'max-width: 250px;overflow: auto;'],
//            ],



            [
                'attribute'=> 'comments',
                'value'=> 'comments',
                'contentOptions' => ['style' => 'max-width: 250px;overflow: auto;'],
            ],



            [
                'attribute'=> 'pv_id',
                'value'=> 'pv_id',
                'contentOptions' => ['style' => 'width: 25px;overflow: auto;'],
            ],

            'user_id',
            [
                'attribute'=> 'user_name',
                'value'=> 'user_name',
                'contentOptions' => ['style' => 'width: 25px;overflow: auto;'],
            ],

        ],
    ]); ?>
</div>



<?php
$script = <<<JS
//
//$('#date_start').change(function() {   
//    
//    var date_start  = $('input[id="date_start"]').val();
//    var date_stop   = $('input[id="date_stop"]').val();
//    
//    var pv_id= $('input[name="postpvrestore[pv_id]"]').val();
//    var name= $('input[name="postpvrestore[name]"]').val();
//    var type_action_tx= $('select[name="postpvrestore[type_action_tx]"]').val();
//    var document= $('input[name="postpvrestore[document]"]').val();
//    var wh_top_name= $('select[name="postpvrestore[wh_top_name]"]').val();
//    var wh_element = $('input[name="postpvrestore[wh_element]"]').val();
//    
//    
//    //alert(123);
//    
//       $.ajax({
//                // url: '/pvrestore/index',
//                url: '#',
//                type : "get",
//                async : true,     
//                data: {
//                        print : 33,
//
//                    postpvrestore:{
//                        date_start: date_start,
//                        date_stop:  date_stop,
//                        pv_id   :   pv_id,
//                        // name    :name,
//                        type_action_tx  :type_action_tx,
//                        document        :document,
//                        wh_top_name     :wh_top_name,
//                        wh_element      :wh_element,                             
//                    }
//                   
//                },
////                complete: function (jqXHR, textStatus) {
////                    //$form.trigger(events.ajaxComplete, [jqXHR, textStatus]);
////                },
////                beforeSend: function (jqXHR, settings) {
////                    //$form.trigger(events.ajaxBeforeSend, [jqXHR, settings]);
////                },
//                success: function (msg) {
//                    console.log(msg);
//                    //alert(msg);
//                },
//                error: function () {
//                    alert('ERROR');
//                }
//         });
//   
//   
//  
//});
//
//
//
//
//    $('#excel_app').click(function() {
//        var date_start=$('#date_start').val();
//        var date_stop=$('#date_stop').val();
//        
//        console.log(date_start);
//        console.log(date_stop);
//        
//        $.ajax({
//                url: '/pv',     
//                data: {
//                    print:1,
//                    date_start: date_start,
//                    date_stop:  date_stop,
//                },
////                complete: function (jqXHR, textStatus) {
////                    //$form.trigger(events.ajaxComplete, [jqXHR, textStatus]);
////                },
////                beforeSend: function (jqXHR, settings) {
////                    //$form.trigger(events.ajaxBeforeSend, [jqXHR, settings]);
////                },
//                success: function (msg) {
//                    console.log(msg);
//                    //alert(msg);
//                },
//                error: function () {
//                    alert('ERROR');
//                }
//         });
//
//            
//        
//    });
//
//    $('#excel_adv').click(function() {
//        alert("excel_adv");  
//    });

JS;
$this->registerJs($script);
?>
