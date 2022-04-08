<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;




if ( isset( $model ) )  {
    $start_date = $model->dt_deadline1;
    $end_date   = $model->dt_deadline2;
}




if (empty($start_date) || empty($end_date) ){
    $start_date    = Date('Y-m-d', strtotime('now -3 day'));
    $end_date   = Date('Y-m-d', strtotime('now +1 day'));
}

if ( isset( $model ) )  {
     $model->dt_deadline1=$start_date;
     $model->dt_deadline2=$end_date;
}

//d33($model);

//            [dt_deadline1] => 2018-12-16
//            [dt_deadline2] => 2018-12-20

//[0] => _id
//[1] => id
//[2] => tz_id
//[3] => dt_create
//[4] => dt_deadline
//[5] => dt_deadline1
//[6] => dt_deadline2
//[7] => user_create_group_id
//[8] => user_create_id
//[9] => user_create_name
//[10] => name_tz
//[11] => name_tk
//[12] => captcha
//[13] => three
//[14] => status_state
//[15] => status_create_user
//[16] => status_create_date


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postpv */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Список ТехЗаданий';
//$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .breadcrumb{
        width: 400px;
        float: left;
        display: block;
    }
    .exc{
        /*width: 200px;*/
         /*background-color: aquamarine;*/
        float: left;
        display: flex;
        margin-left: 5px;
        margin-bottom: 10px;
    }
    .exc1{
        float: left;
    }
    .exc1>a{
        /*height: 66px;*/
        /*width: 200px;*/
        /*font-size: 29px;*/
        /*border-radius: 12px;*/
    }


    .exc2{
        float: left;
        display: block;
    }
    .pv-index{
        display: block;
        width: 100%;
         /*background-color: #edff7f;*/
        /*float: left;*/
    }
    .btn-success {
        margin: 0px 3px;
    }

    .date_period, .date_period1 {
        width: 279px;
        background-color: #ffffff00;
        padding: 0px;
        margin: 0px;
        float: left;
    }
    .p.breb_crumbs_pice{
        float: left;
        margin: 0px;
    }

    .btn-warning {
        margin-left: 0px;
        margin-right: 0px;
        font-size: 14px;
        padding: 6px 12px;
        margin: 0px;
        height: 34px;
        line-height: 1.5;
    }

    h1, .h1 {
        /* font-size: 30px; */
        letter-spacing: 1pt;
        font-weight: bold;
        font-family: Font_EC, sans-serif;
        margin: -4px 70px;
        /* display: inline-block; */
        display: none;
    }

    #w2{
        display: block;
        position: absolute;
        top: -30px;
        background-color: black;
    }
    .wrap >.container{
        padding: 26px 0px;
    }
    .navbar-brand{
        margin-left: -15px;
        font-size:  20px;
        line-height: 2.5;
    }

    .navbar-nav > li{
        float: left;
        top: 15px;
    }

    .grid-view .filters input, .grid-view .filters select {
        min-width: 50px;
        height: 10px;
        margin: 0px;
    }
    .filters{

    }
    .table-bordered > thead > tr > td {
        border-bottom-width: 2px;
        padding: 0px;
    }
    .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
        border: 1px solid #ddd;
        padding: 3px 10px;
    }
    .grid-view .filters input, .grid-view .filters select {
        height: 20px;
    }

</style>


<div class="exc">
    <h1><?= Html::encode($this->title) ?></h1>
</div>


<!--<div class="exc">-->
    <?
//    = Html::a('Печать в Excel', ['/tz/excview1/?start='.$start_date.'&end='.$end_date],
//        ['target' => '_blank','class' => 'btn btn-success']);
    ?>

    <?
//    = Html::a('Печать в Excel', ['/pv?print=1'.$para_str],
//        ['target' => '_blank','class' => 'btn btn-success']);
    ?>

<!--</div>-->






<?php  Pjax::begin(); ?>


<div class="table_with">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute'=> 'id',
                'value'=> 'id',
            ],
'tz_id',
'dt_create',
'dt_deadline',

'user_create_group_id',
'user_create_id',
'user_create_name',
'name_tz',
'name_tk',

'status_state',
'status_create_user',
'status_create_date',

            'datetime',
            'clientIp',
//            'clientHost',
            'referer',


            [
                 'class' => 'yii\grid\ActionColumn',
                 'template' => '{delete}',
            ],

        ],

    ]); ?>

    </div>


        <?php Pjax::end(); ?>



<?php
$script = <<<JS


JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>



