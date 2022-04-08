<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>
<style>

    .tree_land > h3 {
        /*color: #00ff43;*/
        color: #8bb195;
        padding: 0px 10px;
        margin: 0px;
        font-size: large;
        font-weight: bold;
    }

    .tree_land, .tree_land3 {
        padding: 15px 30px;
        min-height: 120px;
    }

    .tree_land3 {
        min-width: 200px;
    }

</style>


<?php
//Pjax::begin(['id' => "pjax-container",]);
//echo \yii::$app->request->get('page');

?>

<?php ActiveForm::begin(); ?>

<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true,//                'data-confirm'=>'normal_doc'
], 'enableClientValidation' => true, //false,
    'validateOnChange' => true, //false,
    'validateOnSubmit' => false,//true,
    //    'enableAjaxValidation'      => true,
    //    'validateOnBlur'            => true,//false,false,

]);

?>


<div id="tz1">

    <div class="pv_motion_create_right_center">

            <?php
                echo "<h4>Отчет о движении товара со ШТРИХ-КОДОМ: ".$bar_code ." </h1>";
            ?>

    </div>




    <div class="pv_motion_create_ok_button">


        <?php
        echo Html::a('Выход', ['/stat_ostatki'], ['class' => 'btn btn-warning']);
        ?>


        <?
        echo Html::submitButton('11', ['class' => 'btn btn-success']);
        ?>


        <?php
        echo Html::a('1', ['/sklad/copycard_from_origin?id='],

            ['data-confirm' => Yii::t('yii', '<b>Вы точно хотите: </b><br>
                        Создать КОПИЮ этой НАКЛАДНОЙ ?'),

                'id' => "to_print", //'target' => "_blank",
                'class' => 'btn  btn-info' //btn-primary'   //btn-success'
            ]);
        ?>


    </div>
</div>

<?php ActiveForm::end(); ?>


<?php

ddd($this);

    //echo $this->render('_search', ['model' => $model]);
?>

<div id="stat_result">
    <div class="pv_motion_create_right">

        <?
        $provider->pagination->pageSize = 10;
//        $provider->sort=['id'=>[SORT_ASC]];

//        $provider->setSort([
//            'attributes'=>[
//                'nakladnaya'=>[
//                    'asc'=>['nakladnaya'=>SORT_ASC],
//                ],
//                'bar_code'=>[SORT_ASC],
//            ]
//        ]);

        echo GridView::widget(
          [
            'dataProvider' => $provider,
            'filterModel' => $model,

                'columns' => [

                    [
                        //'header'=>'№',
                        'attribute' => '##',
                        'contentOptions' => ['style' => 'width: 30px;'],
                        'value' =>function($model, $key, $index){
                                return ++$key;
                                },
                        ],

                    [
                        'attribute' => 'id',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                        ],

                    [
                        'attribute' => 'wh_home_number',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                        ],



//                    [
//                        //'header'=>'№',
//                        'attribute' => 'array_tk_amort',
//                        'contentOptions' => ['style' => 'width: 30px;'],
//                        'value' =>function($model, $key, $index){
//                            return $model['array_tk_amort'][$key]['ed_izmer'];
//                        },
//                    ],

//                    [
//                        //'header'=>'№',
//                        'attribute' => 'array_tk_amort',
//                        'contentOptions' => ['style' => 'width: 30px;'],
//                        'value' =>function($model, $key, $index){
//                            return $model['array_tk_amort'][$key]['ed_izmer_num'];
//                        },
//                    ],





//                    'sklad_vid_oper',
                    'sklad_vid_oper_name',
//                    'user_id',
                    'user_name',
                    'dt_update',
                    'dt_create',
//                    'wh_debet_top',
//                    'wh_debet_element',
                    'wh_debet_name',
                    'wh_debet_element_name',
//                    'wh_destination',
//                    'wh_destination_element',
                    'wh_destination_name',
                    'wh_destination_element_name',
//                    'user_ip',
//                    'user_group_id',
//                    'tz_id',
//                    'update_user_id',
//                    'update_user_name',

                    ],


            ]);
        ?>

    </div>


</div>


<?
$script = <<<JS

$('#go_home').click(function() {    
    window.history.back();  
})

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>



<?php ActiveForm::end(); ?>
<?php //Pjax::end(); ?>



