<?php

//use kartik\datetime\DateTimePicker;
//use unclead\multipleinput\MultipleInput;
//use unclead\multipleinput\MultipleInputColumn;
//use yii\helpers\ArrayHelper;
//use yii\helpers\Html;
//use yii\jui\AutoComplete;

use yii\grid\GridView;
use yii\widgets\ActiveForm;


use yii\widgets\Pjax;

Pjax::begin([
    'id' => 'pjax-container',
]);

echo \yii::$app->request->get('page');

Pjax::end();


?>


<style>
    .table_result{
        background-color: #7fffd430;
        width: 70%;
        padding: 5px;
        display: table;
    }
    .table_row{
        background-color: #d0d6af38;
        padding: 1px;
        display: table-row;
    }

    .table_row_cell{
        background-color: #ce9a4e75;
        width: 100px;
        display: table-cell;
        padding: 5px;
        border: 0.2px solid;
    }
    .table_row_cell2{
        background-color: burlywooded;
        width: 100px;
        display: table-cell;
        padding: 5px;
        border: 0.2px solid;
    }
</style>


<br>
<br>
<br>


<div id="tz1">
    <?php $form = ActiveForm::begin(); ?>
    <div class="pv-action-form" style=" width: 100%;display: inline-table;">

        <?
//        dd($provider);
//                    [id_group] => 1
//                    [id_element] => 14
//                    [ed_izmer] => 1
//                    [ed_izmer_num] => 1
        ?>

       <?= GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id_group',

//        [
//            'attribute'=> 'id_group',
//            //'contentOptions' => ['style' => 'max-width: 125px;overflow: auto;line-height: 4px;'],
//            'filter'=>
//                \yii\helpers\ArrayHelper::map(frontend\models\Spr_glob::find()->all(),
//                'id','name'),
//
//            'value'=> 'id_group',
//        ],

//        [
//            'attribute'=> 'dt_create',
//            'contentOptions' => ['style' => 'max-width: 125px;overflow: auto;line-height: 4px;'],
//            'filter'=>  DateTimePicker::widget([
//                'name' => 'datetime_10',
//                'options' => ['placeholder' => 'Select operating time ...'],
//                'convertFormat' => true,
//                'pluginOptions' => [
//                    'format' => 'd-M-Y g:i A',
//                    'startDate' => '01-Mar-2014 12:00 AM',
//                    'todayHighlight' => true
//                ]
//            ]),
//
//            'value'=> 'dt_create',
//        ],


        'id_element',
        'ed_izmer',
        'ed_izmer_num',


        ['class' => 'yii\grid\ActionColumn',
        'contentOptions' => ['style' => 'width:84px; font-size:18px;']],
    ],
]);

       ?>



        <?
//
//        foreach ($total_table as $key=>$item ){
//
//            echo "<br>".$key;
//            echo "<br><pre>";
//            print_r($item);
//            echo "</pre>";
//        }

//        dd($total_table);
        ?>


        <div class="table_result" >

            <?
//
//            foreach ($array_amort as $key=>$item ){
//
//                echo "<div class='table_row'>";
//                    echo "<div class='table_row_cell'>";
//                        echo $key;
//                    echo "</div>";
//                    echo "<div class='table_row_cell'>";
//                            foreach ($item as $key2=>$item2 ) {
//                                echo "<div class='table_row_cell2'>";
//                                    echo $key2;
//                                echo "</div>";
//
//                                echo "<div class='table_row_cell'>";
//                                    //print_r($item2);
//                                    echo " ".$item2[ed_izmer] ;
//                                    echo " ".$item2[ed_izmer_num];
//
//                                echo "</div>";
//
//                            }
//
//                    echo "</div>";
//                echo "</div>";
//            }

            //        dd($array_amort);
            ?>


        </div>



    </div>
    <?php ActiveForm::end(); ?>

</div>





<?
$script = <<<JS

$('#go_home').click(function() {    
    window.history.back();  
})


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

