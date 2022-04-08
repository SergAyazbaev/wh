<?php

use frontend\models\post_spr_glob_element;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use kartik\datetime\DateTimePicker;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;


Pjax::begin([
    'id' => 'pjax-container',
]);

echo yii::$app->request->get('page');

Pjax::end();


?>


<style>

    table.multiple-input-list.table-renderer tbody tr > td {
        border: 0 !important;
        padding: 0px;
    }


    .tree_land_horizont, .tree_land_horizont_m{
        background-color: #201e4047;
        padding: 3px 6px;
        position: inherit;
        float: left;
        margin: 1px;
        display: flex;
        height: 69px;
        margin-bottom: 5px;
        margin-left: 5px;
        width: 41%;
        min-width: 417px;
    }
    .tree_land_horizont_m{
        width: 160px;
        min-width: 62px;
    }

    #tz-dt_create{
        width: 160px;
    }

    .form-group{
        float: none;
        margin: 0px;
    }
    .pv_motion_create_left_one{
        width: min-content;
        display: block;
        position: inherit;
        float: left;
    }
    .pv_motion_create_left_date{

    }

    .pv_motion_create_all{
        width: 44%;
        max-width: 358px;
        margin-right: 10px;
        float: left;
    }

    .pv_motion_create_all_new{
        /*width: 68%;*/
        /*max-width: 57%;*/
        /*overflow: hidden;*/
        /*height: max-content;*/
                /*background-color: #91a98029; !* Зеленый-Хаки*!*/
        /*background-color: #87a5e67d;         !* Сине-зеленый*!*/
        /*display: block;*/
        /*position: relative;*/
        /*float: left;*/
    }

    /*@media screen(max-width:1300px) {*/
    /*.pv_motion_create_all {*/
    /*width: 65%;*/
    /*}*/
    /*}*/


    .pv_motion_create_left{
        background-color: #965e311f;
        width: 177px;
        padding: 10px;
        /* height: 616px; */
        display: block;
        float: left;
    }
    .pv_motion_create_right, .pv_motion_create_button_place, .pv_motion_create_right_center {
        /*width: 49%;*/
        min-width: 345px;
        /*background-color: #74bb2ec2;*/
        background-color: #74bb2e33;
        display: block;
        position: inherit;
        float: left;
        padding: 9px;
        margin-left: 5px;
        margin-bottom: 5px;
    }

    .pv_motion_create_right_center, .pv_motion_create_right{
        WIDTH: 347PX;
        margin: 1px;
        padding: 12px;
    }
    .pv_motion_create_right{
        width: 100%;
    }

    .pv_motion_create_ok_button{
        display: block;
        position: inherit;
        padding: 13px 5px;
        float: left;
        width: 345px;
        background-color: #48616121;
        /*margin: 5px;*/
        margin-top: 5px;
        background-color: #48616121;

    }


    .pv_motion_content{
        width: 660px;
    }

    .help-block{
        z-index: 100;
        color: #da1713;
        display: block;
        font-size: 16px;
    }
    div>.help-block{
        display: flex;
        color: #da1713;
    }
    .btn{
        margin: 6px;
    }



    @media (max-width:1100px) {
        .pv_motion_create_all_new {
            /*width: 100%;*/
            /*overflow: auto;*/
            /*height: max-content;*/
        }

        .pv_motion_content{
            width: 490px;
        }
    }
    @media (max-width:900px) {
        /*.pv_motion_create_all {*/
        /*width: 95%;*/
        /*margin-top: 15px;*/
        /*padding: 10px;*/
        /*padding-top: 25px;*/

        /*}*/
    }


    .pv_motion_create_button_place{
        background-color: #74bb2e08;
        padding: 39px 90px;
        margin-left: 5px;
        margin-bottom: 5px;
    }

    .control-label{
        display: inline-block;
        width: 100%;
        margin-bottom: 5px;
        font-weight: bold;
    }
</style>

<style>
    .tree_land{
        background-color: cornsilk;
        display: block;
        padding: 10px;
        padding-bottom: 1px;
        margin-bottom: -9px;
    }


    .form-group.field-tz-wh_deb_top_name,
    .form-group.field-tz-wh_cred_top_name
    {
        display: none;
    }

</style>

<style>
    @media (max-width: 710px) {

        h1, .h1 {
            font-size: 20px;
            padding: 5px 15px;
        }
        /*.pv_motion_create_all {*/
        /*width: 96%;*/
        /*margin: 0px 0px;*/
        /*padding: 0px;*/
        /*}*/
        .pv_motion_create_button_place {
            width: 96%;
            padding: 0px;
        }
        .pv_motion_create_left {
            background-color: #888a701f;
            min-width: 267px;
            display: block;
            padding: 25px;
            margin-bottom: 15px;
            margin-left: 7px;
        }
        /*.pv_motion_create_right {*/
        /*width: 50%;*/
        /*min-width: 200px;*/
        /*float: inherit;*/
        /*padding: 10px 20px;*/
        /*}*/
        .pv_motion_create_right_center {
            width: 100%;
            min-width: 270px;
            background-color: #74bb2e33;
            display: block;
            position: inherit;
            float: left;
            padding: 3px;
            margin-left: 5px;
            margin-bottom: 5px;
        }


            #to_print{
                margin: 20px;
            }

        .pv_motion_create_all {
            width: 100%;
            margin-bottom: 20px;
        }
        .pv_motion_create_ok_button{
            display: block;
            position: relative;
            padding: 5%;
            padding-left: 10%;
            float: left;
            width: 100%;
        }


    }

    @media (max-width: 600px){
        .pv_motion_create_left {
            background-color: #888a701f;
            display: block;
            width: 95%;
            padding: 10px;
            margin-bottom: 15px;
        }
        .pv_motion_create_right {
            width: 100%;
            min-width: 263px;
            /*height: 115px;*/
            padding: 0px 10px;
            display: block;
            position: initial;
        }
        .tree_land {
            /*     width: 100%;
                 background-color: cornsilk;
                 padding: 4px 12px;
                 display: inline-grid;
                 float: left;*/
        }
        .form-group {
            margin-bottom: 0px;
        }

        .tree_land_horizont, .tree_land_horizont_m {
            float: left;
            margin: 1px;
            width: 41%;
            min-width: 98%;
            overflow-x: hidden;
            height: 77px;
    }

</style>


<style>
    .form-control {
        display: block;
        width: 100%;
        height: 27px;
        padding: 1px 3px;
        margin: 0px;
        font-size: 12px;
        border-radius: 4px;
    }
</style>


<br>
<br>
<br>

<div id="tz1">
    <?php $form = ActiveForm::begin(); ?>
    <div class="pv-action-form" style=" width: 100%;display: inline-table;">

        <?php
        if(empty($model->dt_create))
            $model->dt_create = Date('Y-m-d H:i:s', strtotime('now'));

        echo '<div class="tree_land_horizont_m">';
        ?>


        <?php
        if(isset($id))   // Tz - id
            $model->id = $id ;

        echo $form->field($model, 'id')
            ->textInput(['class' => 'form-control',
                'style' => 'width: 50px; margin-right: 5px;',
                'readonly' => 'readonly']);
        //->hiddenInput()->label(false);

        echo '</div>';
        ?>


        <?php
        echo '<div class="tree_land_horizont">';

        //echo $form->field($model, 'dt_create')
        echo $form->field($model, 'dt_create')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 165px; margin-right: 5px;',
                'readonly' => 'readonly']);


        if (empty($model->user_create_id) ) {
            $model->user_create_id = Yii::$app->user->identity->id;
        };

        echo $form->field($model, 'user_create_id')
            ->textInput([
                'class' => 'form-control class-content-title_series',
                'style' => 'width: 65px; margin-right: 5px;',
                'readonly' => 'readonly'])
            ->label('Id1 ');

        if (empty($model->user_create_name) ) {
            $model->user_create_name = Yii::$app->user->identity->username;
        };

        echo $form->field($model, 'user_create_name')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 100px; margin-right: 5px;',
                'readonly' => 'readonly'])
            ->label('Создал');

        echo '</div>';
        ?>



        <?php
        $model->dt_edit    = Date('Y-m-d H:i:s', strtotime('now'));

        echo '<div class="tree_land_horizont">';
        //echo $form->field($model, 'dt_edit')
        echo $form->field($model, 'dt_edit')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 165px; margin-right: 5px;',
                'readonly' => 'readonly']);


        if (empty($model->user_edit_id) ) {
            $model->user_edit_id = Yii::$app->user->identity->id;
        };
        echo $form->field($model, 'user_edit_id')
            ->textInput([
                'class' => 'form-control class-content-title_series',
                'style' => 'width: 65px; margin-right: 5px;',
                'readonly' => 'readonly'])
            ->label('Id2 ');


        if (empty($model->user_edit_name) ) {
            $model->user_edit_name = Yii::$app->user->identity->username;
        };
        echo $form->field($model, 'user_edit_name')
            ->textInput(['class' => 'form-control class-content-title_series',
                'style' => 'width: 100px; margin-right: 5px;',
                'readonly' => 'readonly'])
            ->label('Редактор');
        echo '</div>';
        ?>

    </div>





  <div class="pv_motion_create_all">
            <div class="pv_motion_create_right_center">
                Создано по ТехЗаданию
            </div>

            <div class="pv_motion_create_right_center">
                <?php
                echo '<div class="tree_land">';
                $type_words=[
                    '1'=>'Первичная полная установка',
                    '2'=>'Первичная частичная установка',
                    '3'=>'Частичная установка',
                    '4'=>'Демонтаж частичный',
                    '5'=>'Демонтаж полный',

                ];


                if ($model->street_map>0 )


                    //////////


                    if ($model->street_map>0 )
                        echo   $form->field($model, 'street_map')
                            ->dropDownList( $type_words,
                                [ 'prompt' => 'Без карты',
                                    'readonly' => 'readonly',
                                    'disabled' => 'disabled',
                                ]
                            )
                            ->label("Дорожная карта")
                            //->hint("Дорожная карта")
                        ;
                    else
                        echo  $form->field($model, 'street_map')
                            ->dropDownList( $type_words,
                                [ 'prompt' => 'Без карты',
                                ]
                            )
                            ->label("Дорожная карта")
                            //->hint("Дорожная карта")
                        ;



                    ////
//                if ($model->street_map>0 )
//                    $new_doc->sklad_vid_oper=3; // Отгрузка со склада



            echo $form->field($new_doc, 'sklad_vid_oper')->dropdownList(
                    [
                        '1'=>'Инвентаризация',
                        '2'=>'Приходная накладная',
                        '3'=>'Расходная накладная',
                    ],
                    [
                        'prompt' => 'Выбор ...',
                //                        'options' => [ 3 => ['selected'=>'selected']],
                    ]
                )->label("Вид операции");

            //dd($new_doc);

//                $new_doc->sklad_vid_oper_name="Расходная накладная";
            echo $form->field($new_doc, 'sklad_vid_oper_name')
                ->hiddenInput()->label(false);



                /////////
                echo "</div><br>";
                echo '<div class="tree_land">';
                //////////

                //////////
                echo $form->field($new_doc, 'wh_debet_top')->dropdownList(
                    ArrayHelper::map(
                            Sprwhtop::find()->all(),
                            'id','name'),
                    [
                        'prompt' => 'Выбор склада ...'
                    ]
                )->label("Склад-источник");


                echo $form->field($new_doc, 'wh_debet_element')->dropdownList(
                    ArrayHelper::map(
                            Sprwhelement::find()
                        ->where(['parent_id'=>(integer)$new_doc->wh_debet_top])
                        ->all(),'id','name'),
                    [
                        'prompt' => 'Выбор склада ...'
                    ]
                );



//echo 11111111111;
                echo $form->field($new_doc, 'wh_debet_name')
                ->hiddenInput()->label(false);
                echo $form->field($new_doc, 'wh_debet_element_name')
                ->hiddenInput()->label(false);



                /////////
           echo "</div><br>";
           echo '<div class="tree_land">';
                //////////

                echo $form->field($new_doc, 'wh_destination')->dropdownList(
                    ArrayHelper::map(
                            Sprwhtop::find()->all(),
                            'id','name'),
                    [
                        'prompt' => 'Выбор склада ...'
                    ]
                )->label("Склад-приемник");

                echo $form->field($new_doc, 'wh_destination_element')->dropdownList(
                    ArrayHelper::map(Sprwhelement::find()
                        ->where(['parent_id'=>(integer)$new_doc->wh_destination])
                        ->all(),'id','name'),
                    [
                        'prompt' => 'Выбор склада ...'

                    ]
                );

                echo $form->field($new_doc, 'wh_destination_name')
                  ->hiddenInput()->label(false);
                echo $form->field($new_doc, 'wh_destination_element_name')
                   ->hiddenInput()->label(false);


                /////////
                echo "</div><br>";
                echo '<div class="tree_land">';
                //////////




                /////////////////
                // echo '<p>Количество комплектов:</p>';
                  echo $form->field($model, 'multi_tz')
                       ->textInput(['validationDelay'=>5,
                            'style' => "width: 100%;display: inline;"  ]);



    //////////// echo '<p>DeadLine ДЕДЛАЙН:</p>';
                ///
                $date_now   = date("Y-m-d H:i:s", strtotime('now') );

                if (empty($model->dt_deadline))
                    $model->dt_deadline = $date_now;

                echo $form->field($model, 'dt_deadline')
                    ->widget(DateTimePicker::className(),[

                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'options' => [
                            'placeholder' => 'Ввод даты/времени...'],

                            'value'=> $date_now,
                            'pluginOptions' => [
                                    'pickerPosition' => 'bottom-left',
                                    'format' => 'yyyy-mm-dd HH:ii:ss',
                                    'autoclose'=>true,
                                    'weekStart'=>1, //неделя начинается с понедельника
                                    'startDate' => $date_now,
                                    'todayBtn'=>true, //снизу кнопка "сегодня"
                                ]
                ]);





                echo '</div>';
                ?>

            </div>


      <div class="pv_motion_create_ok_button">
          <?= Html::Button('&#8593;',
              [
                  'class' => 'btn btn-warning',
                  'onclick'=>"window.history.back();"
              ]);
          ?>

          <?php
                echo Html::submitButton('Создать накладную(ые)',
                    ['class' => 'btn btn-success']
                );
          ?>

          <?php
            //if( $model['user_create_id']==Yii::$app->user->id ){
//                echo Html::submitButton('Сохранить',
//                    ['class' => 'btn btn-success']);
            //}

          ?>



          <?php
//               if( Yii::$app->user->id == 50 )

//                   echo Html::a('Печать',
//                       ['/sklad/to_pdf/?id=' . $model->id],
//                       [
//                           'id' => "to_print",
//                           //'target' => "_blank",
//                           'class' => 'btn btn-default'
//                       ]);
          ?>



          <?php
                   echo Html::a('Только для Штрихкодов',
                       ['/sklad/create_multi?tz_id=' . $model->id .'&multi=' . $model->multi_tz],
                       [
                           'id' => "to_print",
                          'target' => "_blank",
                           'class' => 'btn btn-default'
                       ]);
          ?>

          <?php
                   echo Html::a('Только без Штрихкодов',
                       ['/sklad/create_multi_without_barcode?tz_id=' . $model->id .'&multi=' . $model->multi_tz],
                       [
                           'id' => "to_print",
                         'target' => "_blank",
                           'class' => 'btn btn-default'
                       ]);
          ?>


          <?php
          //dd($model->status_state);

//          if( isset($model->status_state) && $model->status_state>0) {
//              echo "<div>Передано в работу: "
//                  .date('d.m.Y h:i:s', strtotime($model->status_create_date))
//                  ."</div>";
//          }
//          else {
//
//              if (Yii::$app->user->identity->group_id == 50)
//
//                  echo Html::a('Передать в работу',
//                      ['/tz_to_work/signal_to_work?id=' . $model->id],
//                      [
//                          'id' => "tz_to_work",
//                          //'target' => "_blank",
//                          'class' => 'btn btn-default'
//                      ]);
//          }
          ?>

      </div>
  </div>
</div>





<div class="scroll_window">


        <div class="pv_motion_create_all_new">

            <div class="pv_motion_create_right">

                <?php

                echo $form->field($new_doc, 'array_tk_amort')->widget(MultipleInput::className(), [
                    'id' => 'my_id2',
                    'rowOptions' => [
                        'id' => 'row{multiple_index_my_id2}',
                    ],

//                    'max'               => 25,
                    'min'               => 0, // should be at least 2 rows
                    'allowEmptyList'    => true ,//false,
                    'enableGuessTitle'  => true ,//false,
                    'sortable'  => true,
                    'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                    'columns' => [
                        [
                            'name'  => 'wh_tk_amort',
                            'type'  => 'dropDownList',
                            'title' => 'Группа',
                            'defaultValue' => 0,
                            'items' => ArrayHelper::map(
                                post_spr_glob_element::find()->all(),
                                'id','name'),

                            'options' => [
                                'prompt' => 'Выбор ...',
                                'style' => 'min-width: 200px;',
                                'id' => 'subcat211-{multiple_index_my_id2}',

                                'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
    console.log(data);
    $("select#subcat22-{multiple_index_my_id2}").html(data);
});
JS

                            ],
                        ],

                        [
                            'name'  => 'wh_tk_element',
                            'type'  => 'dropDownList',
                            'title' => 'Компонент',
                            'defaultValue'=> [],

                            'options' => [
                                'id' => 'subcat22-{multiple_index_my_id2}',
                                'style' => 'min-width: 336px;',
                                'prompt' => '...',
                            ],

                            'items' =>
                                ArrayHelper::map(
                                    post_spr_glob_element::find()->orderBy('parent_id, name')->all(),
                                    'id','name')
                            ,

                        ],


                        [
                            'name'  => 'intelligent',
                            'title' => 'Штр',
                            'type'  => 'dropDownList',
                            'defaultValue' => 0,
                            'enableError' => true,
                            'value' =>(integer) $model->intelligent,
                            'options' => [
//                                'type'  => 'number',
//                                'class' => 'input-priority',
//                                'step'  => '1',
                                'style' => 'min-width:50px;max-width:50px;overflow: auto;',
                            ],
                            'items' => [
                                0 => 'Нет',
                                1 => 'Да',
                            ],
                        ],


                        [
                            'name'  => 'ed_izmer',
                            'title' => 'Ед. изм',
                            'type'  => 'dropDownList',
                            'defaultValue' => 1,
                            'options' => [
                                'style' => 'min-width: 66px;padding: 6px 0px;'
                            ],

                            'items' => ArrayHelper::map( Spr_things::find()->all(),'id','name'),

                        ],

                        [
                            'name'  => 'ed_izmer_num',
                            'title' => 'Кол-во',
                            'defaultValue' => 0,
                            'enableError' => true,
                            'value' =>(integer) $model->ed_izmer_num,
                            'options' => [

                                //'type' => 'number',
                                'type' => MaskedInput::className(), ['mask' => '9'],
                                'class' => 'input-priority',
                                'style' => 'min-width:62px;max-width: 70px;overflow: auto;'
                            ]
                        ],

                        [
                            'name'  => 'bar_code',
                            'title' => 'Код',
                            //'defaultValue' => 10101010101,
                            'enableError' => true,
                            //'value' =>(integer) $model->ed_izmer_num,
                            'headerOptions' => ['width' => '170'],
                            'options' => [
                                //'type' => 'number',
                                //'type' => MaskedInput::className(), ['mask' => '9'],
                                //'class' => 'input-priority',
//                                'style' => 'min-width:62px;max-width: 170px;overflow: auto;'
                            ]
                        ],


                    ]
                ]);

                //->label(false);

                ?>

            </div>
        </div>

        <div class="scroll_window">

            <div class="pv_motion_create_right">

                <?php

                if (isset($new_doc->array_tk) && !empty($new_doc->array_tk))
                echo $form->field($new_doc, 'array_tk')->widget(MultipleInput::className(), [

                    'id' => 'my_id',
                    'rowOptions' => [
                        'id' => 'row{multiple_index_my_id}',

                    ],


//                    'max'               => 25,
                    'min'               => 0, // should be at least 2 rows
                    'allowEmptyList'    => true ,//false,
                    'enableGuessTitle'  => true ,//false,
                    'sortable'  => true,
                    'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                    'columns' => [
                        [
                            'name'  => 'wh_tk',
                            'type'  => 'dropDownList',
                            'title' => 'Группа',
                            'defaultValue' => [],
                            //'items' => Spr_glob::find()->select(['name'])->column(),
                            'items' =>
                                ArrayHelper::map(
                                    Spr_glob::find()->all(),
                                    'id',
                                    'name'),

                            'options' => [
                                'prompt' => 'Выбор ...',
                                'style' => 'min-width: 200px;',
                                'id' => 'subcat11-{multiple_index_my_id}',
                                'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    //console.log(data);
    $("select#subcat-{multiple_index_my_id}").html(data);
});
JS

                            ],
                        ],

                        [
                            'name'  => 'wh_tk_element',
                            'type'  => 'dropDownList',
                            'title' => 'Компонент',
                            'defaultValue'=> [],
                            'items' =>
                                ArrayHelper::map(
                                    Spr_glob_element::find()->all(),
                                    'id','name')
                            ,


                            'options' => [
                                'prompt' => '...',
                                'id' => 'subcat-{multiple_index_my_id}',
                                'style' => 'min-width: 336px;'
                            ]
                        ],


                        [
                            'name'  => 'ed_izmer',
                            'title' => 'Ед. изм',
                            'type'  => 'dropDownList',
                            'defaultValue' => 1,
                            'options' => [
                                'style' => 'min-width: 66px;padding: 6px 0px;'
                            ],

                            'items' => ArrayHelper::map( Spr_things::find()->all(),'id','name'),


                        ],

                        [
                            'name'  => 'ed_izmer_num',
                            'title' => 'Кол-во',
                            'defaultValue' => 1,
                            'enableError' => true,
                            'value' =>(integer) $model->ed_izmer_num,
                            'options' => [
                                'type'  => 'number',
                                'class' => 'input-priority',
                                'step'  => '0.1',
                                'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                            ]
                        ],

//                        [
//                            'name'  => 'tx',
//                            'title' => 'Комментарии',
//                            'defaultValue' => '',
//                            'enableError' => true,
//                            'options' => [
//                                'class' => 'input-priority',
//                                //'style' => 'max-width: 170px;overflow: auto;'
//                                'style' => 'overflow: auto;'
//
//                            ]
//                        ]

//                    [
//                            'name' => 'dt_create',
//                            'format' => ['datetime', 'php:Y-m-d h:i:s'],
//                    ],



                    ]
                ]) ;
                //  ->label(false);
                ?>

            </div>
        </div>

</div>



    <?php ActiveForm::end(); ?>




<?php
//Modal::begin([
//    'header' => '<h2>Вот это модальное окно!</h2>',
//    'toggleButton' => [
//        'tag' => 'button',
//        'class' => 'btn btn-lg btn-block btn-info',
//        'label' => 'Нажмите здесь, забавная штука!',
//    ]
//]);
//
//echo 'Надо взять на вооружение.';
//
//Modal::end();



//use yii\widgets\Pjax;
//use yii\web\JsExpression;




$script = <<<JS

//////////////////// VID - sklad-sklad_vid_oper
$('#sklad-sklad_vid_oper').change(function() {    
     var  number2 = $('#sklad-sklad_vid_oper').val();
     var  text2   = $('#sklad-sklad_vid_oper>option[value='+number2+']').text();
      $('#sklad-sklad_vid_oper_name').val(text2);
});




//////////////////// Debitor -top
$('#sklad-wh_debet_top').change(function() {
        
    var  number = $(this).val();
    var  text = $('#sklad-wh_debet_top>option[value='+number+']').text();    
    $('#sklad-wh_debet_name').val(text) ;

     // var  number2 = $('#sklad-wh_debet_element').val();
     // var  text2   = $('#sklad-wh_debet_element>option[value='+number2+']').text();
     //  $('#sklad-wh_debet_element_name').val(text2);


    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		            $('#sklad-debet_element').html('');
		            $('#sklad-wh_debet_element').html(res);		    
					},
			error: function( res) {
						alert('нет данных ' );
						console.log(res);
					}
    } );
    
});

//////////////////// Debitor - element sklad-wh_debet_element
$('#sklad-wh_debet_element').change(function() {    
     var  number2 = $('#sklad-wh_debet_element').val();
     var  text2   = $('#sklad-wh_debet_element>option[value='+number2+']').text();
      $('#sklad-wh_debet_element_name').val(text2);
});


//////////////////// Creditor
$('#sklad-wh_destination').change(function() {
    
    var  number = $(this).val();
    var  text = $('#sklad-wh_destination>option[value='+number+']').text();
 	    $('#sklad-wh_destination_name').val(text);
 	    
            // console.log(number);   
            // console.log(text);   
   
    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#sklad-wh_destination_element').html('');
		    $('#sklad-wh_destination_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
   
});

//////////////////// destination_element - element
/// sklad-wh_destination_element 
$('#sklad-wh_destination_element').change(function() {    
     var  number2 = $('#sklad-wh_destination_element').val();     
     var  text2   = $('#sklad-wh_destination_element>option[value='+number2+']').text();
      $('#sklad-wh_destination_element_name').val(text2);
});







$('#go_home').click(function() {    
    window.history.back();  
})


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

