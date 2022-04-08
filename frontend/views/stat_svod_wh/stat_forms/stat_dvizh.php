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


        //ddd($model_tz);

        //Создано по ТехЗаданию
        echo "<h4>Отчет о передаче ТМЦ на АП: <b> " . $ap . "</b> </h1>";

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

<div id="stat_result">


    <div class="pv_motion_create_right">

        <?


        //            'searh_id' => '6'
        //            'deb' => 0
        //            'cred' => 0
        //            'ed_izmer' => '1'
        //            'ed_izmer_num' => '1'
        //            'bar_code' => ''

        //                ddd($provider);

        $searchModel =
            [

                'nakladnaya' => null,
                'ed_izmer' => '',
                'bar_code' => '',
            ];

        echo GridView::widget(
            [
                'dataProvider' => $provider,
                'filterModel' => $searchModel,

                'columns' => [

                    [
                        //'header'=>'№',
                        'attribute' => '##',
                        'contentOptions' => ['style' => 'width: 30px;'],
                        'value' => function ($model, $key, $index) {
                            return ++$key;
                        },
                    ],

                    [
                        //'header'=>'Вид операции',
                        'attribute' => 'nakladnaya',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                    ],

                    [
                        'attribute' => 'ed_izmer',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                    ],
                    [
                        //'header'=>'Вид операции',
                        'attribute' => 'ed_izmer_num',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                    ],

                    [
                        'attribute' => 'wh_home_number',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                    ],
                    [
                        'attribute' => 'wh_debet_name',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                    ],
                    [
                        'attribute' => 'wh_debet_element',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                    ],
                    [
                        'attribute' => 'wh_debet_element_name',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                    ],


//                    [
//                        'attribute' => 'wh_destination',
//                        'contentOptions' => ['style' => 'overflow: hidden;'],
//                        ],

                    [
                        'attribute' => 'wh_destination_name',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                    ],

//                    [
//                        'attribute' => 'wh_destination_element',
//                        'contentOptions' => ['style' => 'overflow: hidden;'],
//                        ],
                    [
                        'attribute' => 'wh_destination_element_name',
                        'contentOptions' => ['style' => 'overflow: hidden;'],
                    ],

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



