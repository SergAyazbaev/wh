<?php


use frontend\models\Sklad;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use kartik\datetime\DateTimePicker;
//use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\widgets\Pjax;


?>
<style>
    .select_long, #sklad_vid_oper{
        padding: 4px 4px;
        /*background-color: #7fffd48a;*/
        background-color: #00ff4338;
        margin: 5px 0px;
        width: 100%;
        max-width: calc(100% - 5px);
    }
    .tree_land{
        max-width: calc((99% - 410px )/4);
        /*width:300px;*/

        border: 1px solid #c3bfae;
        border-radius: 10px;
        font-size: small;
        font-weight: normal;
        font-variant: normal;
        position: relative;
        float: left;
        clear: inherit;

        padding: 15px 30px;
        padding: 17px 14px;
        min-height: 90px;
        min-width: 200px;
    }

    .tree_land>input {
        background-color: #00ff4338;
        font-size: 20px;
        padding: 0px 7px;
    }

    .tree_land.tree_land_long{
        width: 400px;
        min-width: 400px;
        padding: 15px 15px;
    }

    #wh_top,
    #dt_start{
        /*margin-top: -5px;*/
        margin-bottom: 10px;
    }
    .tree_land > h3 {
        /*color: #00ff43;*/
        color: #8bb195;
        padding: 0px 10px;
        margin: 0px;
        font-size: large;
        font-weight: bold;
    }


    #sklad_vid_oper{
        /*background-color: aqua;*/
        background-color: #00ff4338;
        width: 100%;
        margin-top: 10px;
    }



    @media (max-width: 710px) {
        .tree_land, .tree_land.tree_land_long,  .select_long{
            width: 100%;
            max-width: calc(100% - 5px);
            min-width: auto;
            overflow: hidden;
        }
    }

</style>


<?php
//    Pjax::begin(['id' => "pjax-container",
//            'linkSelector' => 'a:not(.target-blank)']
//    );
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
//                echo "<h4>Отчет о движении товара со ШТРИХ-КОДОМ: ".$bar_code ." </h1>";
            ?>


  <?= Html::beginForm(['stat_svod/index_svod'],
      'post',  ['data-pjax' => '', 'class' => 'form-inline']);

            ?>


        <div class="tree_land tree_land_long">


            <?php
//            ddd($model);

            echo $form->field($model, 'wh_top')
                ->dropDownList(
                    ArrayHelper::map( Sprwhtop::find()->all(),'id','name'),
                    [
                        'class'=>'select_long',
                        'id'=>'wh_top',
                        'prompt' => 'Группа складов ...',
//                        'options'=>array(
//                             $model['wh_top'] => ['selected'=>true],
//                        ),

//                        'data-confirm' => Yii::t('yii',
//                            '<b>Вы точно хотите: </b><br>
//                                    Изменить НАПРАВЛЕНИЕ этой НАКЛАДНОЙ ?'),
                    ]
                )
                ->label("Группа складов");
            ?>

            <?php
            //ddd($model);

            echo $form->field($model, 'wh_home_number')
                ->dropDownList(
                    ArrayHelper::map( Sprwhelement::find()
                        ->where(['parent_id'=> (int)$model['wh_top'] ])
                        ->all(),'id','name'),
                    [
                        'class'=>'select_long',
                        'id'=>'wh_top',
                        'prompt' => 'Cклад не выбран ...',
//                        'options'=>array(
//                             $model['wh_top'] => ['selected'=>true],
//                        ),

//                        'data-confirm' => Yii::t('yii',
//                            '<b>Вы точно хотите: </b><br>
//                                    Изменить НАПРАВЛЕНИЕ этой НАКЛАДНОЙ ?'),
                    ]
                )
                ->label("Cклад");
            ?>



        </div>

        <div class="tree_land">

            <?php

//ddd($model);

            $model->dt_start =
                date('d.m.Y H:i:s',strtotime($model['dt_start']));

            echo $form->field($model, 'dt_start' )
                ->widget(DateTimePicker::className(),[
                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'convertFormat' => false,
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...'],

                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy HH:ii:ss', /// не трогать -это влияет на выбор "СЕГОДНЯ"
                        'pickerPosition' => 'bottom-left',

                        'autoclose'=>true,
                        'weekStart'=>1, //неделя начинается с понедельника
                        // 'startDate' => $date_now,
                        'todayBtn'=>true, //снизу кнопка "сегодня"
                    ]
                ]);
            ?>
            <?php

            $model->dt_stop =
                date('d.m.Y H:i:s',strtotime($model['dt_stop']));

            echo $form->field($model, 'dt_stop')
                ->widget(DateTimePicker::className(),[
                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'convertFormat' => false,
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...'],

                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy HH:ii:ss', /// не трогать -это влияет на выбор "СЕГОДНЯ"
                        'pickerPosition' => 'bottom-left',

                        'autoclose'=>true,
                        'weekStart'=>1, //неделя начинается с понедельника
                        // 'startDate' => $date_now,
                        'todayBtn'=>true, //снизу кнопка "сегодня"
                    ]
                ]);
            ?>
        </div>


        <div class="tree_land">

            <?= Html::input('text', 'bar',
                Yii::$app->request->post('bar'), [
                      'pattern' => '[0-9]{5,25}',
                      'placeholder' => "Штрих-код" ,
                      'class' => 'form-control'
                ])?>

            <?php
//            ddd($model);

            echo $form->field($model, 'vid_oper')
                ->dropDownList(
                    [
                        '1'=>'Инвентаризация',
                        '2'=>'Приходная накладная',
                        '3'=>'Расходная накладная',
                    ],
                    [
                        'prompt' => 'Вид операции ...',
                        'title' => 'Вид операции',
                        'id' => 'sklad_vid_oper',

                        //                        'options'=>array(
                        //                             $model['wh_top'] => ['selected'=>true],
                        //                        ),

                        //                        'data-confirm' => Yii::t('yii',
                        //                            '<b>Вы точно хотите: </b><br>
                        //                                    Изменить НАПРАВЛЕНИЕ этой НАКЛАДНОЙ ?'),
                    ]
                )
                ->label("Группа складов");
            ?>





        </div>




    </div>


    <div class="pv_motion_create_ok_button">


        <?php
        echo Html::a('Выход', ['/stat_ostatki'], ['class' => 'btn btn-warning']);
        ?>

        <?= Html::submitButton('Поиск', [
            'class' => 'btn btn-lg btn-primary',
            'name'=>'print',
            'value' => 0,
        ]) ?>

        <?= Html::submitButton('To EXCEL', [
                'class' => 'btn btn-default',
            'name'=>'print',
            'value' => 1,
        ]) ?>



        <?= Html::endForm() ?>



    </div>
</div>

<?php ActiveForm::end(); ?>



<div id="stat_result">
    <div class="pv_motion_create_right">

        <?

//        $provider->pagination = [
//                'pageSize' => 10,
//            ];


        //        $provider->sort=['id'=>[SORT_ASC]];

//        $provider->setSort([
//            'attributes'=>[
//                'nakladnaya'=>[
//                    'asc'=>['nakladnaya'=>SORT_ASC],
//                ],
//                'bar_code'=>[SORT_ASC],
//            ]
//        ]);


//      $xxx=  ArrayHelper::map(Sprwhelement::find()
//            //->where(['parent_id'=> (int) $model->wh_home_number])
//            ->orderBy('id')
//            ->all(), 'id','name');
//
//ddd($xxx);




        if (isset($model->wh_top) && !empty($model->wh_top)){
            $wh_top_select=ArrayHelper::map(Sprwhelement::find()
                ->where(['parent_id'=> $model->wh_top])
                ->orderBy('id')
                ->all(), 'id','name');
        }
        else{
            $wh_top_select=ArrayHelper::map(Sprwhelement::find()
                ->orderBy('id')
                ->all(), 'id','name');
        }




        $dataProvider->pagination->pageSize = 10;

        echo GridView::widget(
          [
            'dataProvider' => $dataProvider,
            'filterModel' => $model,

                'columns' => [
//                    [
//                        'attribute' => '##',
//                        'label' => '№',
//                        'contentOptions' => ['style' => 'width: 30px;'],
//                        'value' => function($model, $key, $index){
//                                return ++$key;
//                                },
//                        ],


                    [
                        'attribute' => 'wh_home_number',
                        'label' => 'База',
                        'filter' => Sklad::ArraySklad_Uniq_wh_numbers_plus_id_name() ,
                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
                    ],

                    [
                        'attribute' => 'id',
                        'label' => 'Накладная',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 40px;'],
                    ],
                    [
                        'attribute'=> 'dt_create',
                        'label' => 'Дата',
                        'contentOptions' => ['style' => ' width: 110px;'],
                        'format' =>  ['date', 'php:d.m.Y H:i:s'],
                    ],



                    [
                        'attribute' => 'vid_oper',
                        'label' => 'Вид операции',
                        'value' => 'sklad_vid_oper',
                        'filter' => [
                                '0' => "....",
                                '1' => "Инвентаризация",
                                '2' => "Приход",
                                '3' => "Расход",
                        ],
                        'content' => function ($model) {
                            return ArrayHelper::getValue([
                                '0' => "....",
                                '1' => "Инвентаризация",
                                '2' => "Приход",
                                '3' => "Расход",
                            ], $model['sklad_vid_oper']);
                        },

                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
                        ],


                    [
                        'attribute' => 'wh_debet_name',
                        'label' => 'Отправитель',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width:90px'],
                        ],

                    [
                        'attribute' => 'wh_debet_element_name',
                        'label' => 'Отправитель',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width:90px'],
                        ],

                    [
                        'attribute' => 'wh_destination_name',
                        'label' => 'Получатель',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width:110px'],
                        ],
                    [
                        'attribute' => 'wh_destination_element_name',
                        'label' => 'Получатель(ПЕ)',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width:110px'],
                        ],


//                    [
//                        'attribute' => 'wh_tk_amort',
//                        'label' => 'Группа-ТМЦ',
//                        'contentOptions' => ['style' => 'overflow: hidden;max-width:110px'],
//                        ],
//
//                    [
//                        'attribute' => 'wh_tk_element',
//                        'label' => 'ТМЦ',
//
//                        ],
//
//                    [
//                        'attribute' => 'ed_izmer',
//                        'label' => 'Ед.изм',
//                        'contentOptions' => ['style' => 'overflow: hidden;min-width: 40px;'],
//                        ],
//
//                    [
//                        'attribute' => 'ed_izmer_num',
//                        'label' => 'Кол',
//                        'contentOptions' => ['style' => 'width: 60px;'],
//                        ],


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



