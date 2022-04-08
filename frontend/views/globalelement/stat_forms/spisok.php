<?php

use yii\grid\GridView;
use yii\helpers\Html;


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
<!---->
<?php //ActiveForm::begin(); ?>
<!---->
<?php //$form = ActiveForm::begin([
//        'options' => [
//                'data-pjax' => true,
//            //                'data-confirm'=>'normal_doc'
//
//
//        ],
//    'enableClientValidation' => true, //false,
//    'validateOnChange' => true, //false,
//    'validateOnSubmit' => false,//true,
//    //    'enableAjaxValidation'      => true,
//    //    'validateOnBlur'            => true,//false,false,
//
//]);

?>


<div id="tz1">
    <div class="pv_motion_create_right_center">

            <?php
                //ddd($model_tz);

                //Создано по ТехЗаданию
                echo "<h4>Отчет о движении:  </h4>";
//                echo "<h1>".$element_name ." </h1>";
            ?>

    </div>

    <div class="pv_motion_create_right_center">

            <?= Html::beginForm(['stat_ostatki/barcode_to_naklad'], 'get',
                ['data-pjax' => '', 'class' => 'form-inline']);

            //http://wh/stat_ostatki/barcode_to_naklad?bar=19600007218
            ?>


            <?= Html::input('text', 'bar',
                Yii::$app->request->post('bar'), ['class' => 'form-control'])
            ?>

            <?= Html::submitButton('Поиск', ['class' => 'btn btn-lg btn-primary', 'name' => 'hash-button']) ?>

            <?= Html::endForm() ?>

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

<?php //ActiveForm::end(); ?>

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

//        $searchModel =
//            [
//
//                'nakladnaya' => null,
//                'ed_izmer' => '',
//                'bar_code' => '',
//                ];

        echo GridView::widget(
          [
            'dataProvider' => $provider,
            'filterModel' => $searchModel,

                'columns' => [

                    [
                        //'header'=>'№',
                        'attribute' => '##',
                        'contentOptions' => ['style' => 'width: 30px;'],
                        'value' =>function($model, $key, $index){
                                return ++$key;
                                },
                        ],


//                    [
//                        'attribute' => 'nakladnaya',
//                        'label' => 'Накл',
//                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],
//                    ],
//                    [
//                        'attribute' => 'ed_izmer',
//                        'label' => 'Ед.изм',
//                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],
//                    ],
//                    [
//                        'attribute' => 'ed_izmer_num',
//                        'label' => 'Кол-во',
//                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],
//                    ],
//
//
//                    [
//                        'label' => 'Штрих-код',
//                        'attribute' => 'bar_code',
//
//                        'contentOptions' => ['style' => 'overflow: hidden;'],
//                        //'value' =>function($model, $key, $index){
//
//                        'content' => function($model) {
//
//
//                            if (isset($model['bar_code']) && $model['bar_code']>0 ){
//                                //$url = Url::to(['/stat_ostatki/barcode_to_naklad?id='.$model['wh_home_number'].'&bar='.$model['bar_code'] ]);
//                                $url = Url::to(['/stat_ostatki/barcode_to_naklad?bar='.$model['bar_code'] ]);
//
//                                $text_ret = Html::a($model['bar_code'], $url, [
//                                    'class' => 'btn btn-success btn-xs',
//                                    'data-pjax' => 0,
//                                ]);
//
//                                return $text_ret ;
//                            }
//
//                            return 'Б/Н' ;
//
//                        },
//
//                    ],
//
//                    [
//                        'attribute' => 'wh_home_number',
//                        'label' => 'База',
//                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],
//                    ],
//
//                    //.......
//                    [
//                        'attribute' => 'wh_debet_name',
//                        'label' => 'Группа складов',
//                        'contentOptions' => ['style' => 'overflow: hidden;max-width: 40px;'],
//                    ],
//                    [
//                        'attribute' => 'wh_debet_element_name',
//                        'label' => 'Отпустил',
//                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
//                    ],
//                    [
//                        'attribute' => 'wh_destination_name',
//                        'label' => 'Группа складов',
//                        'contentOptions' => ['style' => 'overflow: hidden;max-width: 40px;'],
//                    ],
//                    [
//                        'attribute' => 'wh_destination_element_name',
//                        'label' => 'Получил',
//                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
//                    ],



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



<?php //ActiveForm::end(); ?>
<?php //Pjax::end(); ?>



