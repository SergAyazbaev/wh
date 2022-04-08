<?php

use frontend\models\post_spr_glob_element;
use frontend\models\posttz;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_things;
use kartik\datetime\DateTimePicker;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;
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
        width: 60px;
        min-width: 62px;
    }

    #tz-dt_create{
        width: 160px;
    }

    .form-group{
        float: none;
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
        width: 68%;
        /*max-width: 57%;*/
        overflow: hidden;
        height: max-content;
        background-color: #91a98029;
        display: block;
        position: relative;
        float: left;
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
        width: 347px;
        /*max-width: 693px;*/

    }
    .pv_motion_create_right{
        width: 100%;
    }

    .pv_motion_create_ok_button{
        display: block;
        position: inherit;
        padding: 10px 18px;
        float: left;
        width: 200px;
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
            width: 100%;
            overflow: auto;
            height: max-content;
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
        padding: 8px;
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



<div id="tz1">
    <?php $form = ActiveForm::begin(); ?>


    <div class="pv-action-form">



        <?php
        if(empty($model->dt_create))
            $model->dt_create    = Date('Y-m-d H:i:s', strtotime('now'));

        echo '<div class="tree_land_horizont_m">';
        ?>

        <?php
        if(isset($id_tz))
            $model->id = $id_tz ;

        echo $form->field($model, 'id')
            ->textInput(['class' => 'form-control',
                'style' => 'width: 50px; margin-right: 5px;',
                'readonly' => 'readonly']);
        //->hiddenInput()->label(false);

        //............
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
//        $model->dt_edit    = Date('Y-m-d H:i:s', strtotime('now'));

        echo '<div class="tree_land_horizont">';
        //echo $form->field($model, 'dt_edit')
//        echo $form->field($model, 'dt_edit')
//            ->textInput(['class' => 'form-control class-content-title_series',
//                'style' => 'width: 165px; margin-right: 5px;',
//                'readonly' => 'readonly']);


//        if (empty($model->user_edit_id) ) {
//            $model->user_edit_id = Yii::$app->user->identity->id;
//        };
//        echo $form->field($model, 'user_edit_id')
//            ->textInput([
//                'class' => 'form-control class-content-title_series',
//                'style' => 'width: 65px; margin-right: 5px;',
//                'readonly' => 'readonly'])
//            ->label('Id2 ');


//        if (empty($model->user_edit_name) ) {
//            $model->user_edit_name = Yii::$app->user->identity->username;
//        };
//        echo $form->field($model, 'user_edit_name')
//            ->textInput(['class' => 'form-control class-content-title_series',
//                'style' => 'width: 100px; margin-right: 5px;',
//                'readonly' => 'readonly'])
//            ->label('Редактор');
//        echo '</div>';
//        ?>




  <div class="pv_motion_create_all">

            <div class="pv_motion_create_right_center">

                <?php
                    echo $form->field($model, '_id')
                        ->textInput(['class' => 'form-control',
                            'style' => 'width: 265px; margin-right: 5px;',
                            'readonly' => 'readonly'])->hiddenInput()->label(false);
                ?>

                <?php
                $xx=posttz::find()->all();
                $type_words= ArrayHelper::getColumn($xx,'name_tz');

                if (isset($val12))
                    $model->name_tz=$val12['val_0_1'];



                echo '<div class="tree_land">';
                echo $form->field($model, 'name_tz')
                    ->widget(
                        AutoComplete::className(), [
                        'clientOptions' => [
                            'source' => $type_words,
                        ],
                    ])
                    ->textarea([
                        'class' => 'form-control class-content-title_series',
                        'style' => 'max-width: 100%;height: 100px;',
                    ]);

                echo '</div>';
                ?>


            </div>

            <div class="pv_motion_create_right_center">
                <?php
                //echo '<p>Адресат установки:</p>';
                echo '<div class="tree_land">';

//                echo $form->field($model, 'wh_cred_top')->dropdownList(
//                    ArrayHelper::map(Sprwhtop::find()->all(),'id','name'),
//                    [
//                        'prompt' => 'Выбор склада ...'
//                    ]
//                )->label("Автопарк");
//



//                echo $form->field($model, 'tk_top')->dropdownList(
//                    ArrayHelper::map(Tk::find()->all(),'id','name_tk'),
//                    [
//                        'prompt' => 'Без ТК... '
//                    ]
//                );


                        //            $model->wh_cred_top_name=$val12['val_2_2'];
//                echo $form->field($model, 'wh_cred_top_name')->dropdownList(
//                    Sprwhtop::find()->select(  ['name']  )-> indexBy('name') ->column(),
//                    [
//                        'style'=>'display: none ',
//                    ]
//                )->label('')->hiddenInput();


      // echo '<p>Количество комплектов:</p>';
//      echo $form->field($model, 'multi_tz')
//                        ->textInput(['validationDelay'=>5],[
//                                'style' => "width: 100%;display: inline;"
//                        ]);



                        // echo '<p>DeadLine ДЕДЛАЙН:</p>';

                //$date_now   = date("Y-m-d H:i:s", strtotime('now +1 day') );
                //$date_now   = date("Y-m-d H:i:s", strtotime('tomorrow +1 minutes') );

                //$date_now   = date("Y-m-d H:i:s", strtotime('tomorrow +5 hours') );
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

                    //                    'convertFormat' => true,
                    //'value'=> date("d.m.Y H:i:s", date(time())),

                            'value'=> $date_now,
                            'pluginOptions' => [
                                    'pickerPosition' => 'bottom-left',

	                            //'format' => 'dd.mm.yyyy HH:ii:ss',

                                    //'format' => 'dd.mm.yyyy HH:ii:ss',
                                    'format' => 'yyyy-mm-dd HH:ii:ss',

                                    //'format' => 'yyyy-mm-dd H:i:s',
                                    ///////OK2 'format' => 'yyyy-mm-dd H:i:s',


                                    'autoclose'=>true,
                                    'weekStart'=>1, //неделя начинается с понедельника
                                    //'startDate' => ''.$date_now, //самая ранняя возможная дата
                                    //'startDate' => date('Y-m-d H:i:s',strtotime('first day of  -1 month')),
                                    //'startDate' => date('Y-m-d H:i:s',strtotime('now -2 hours') ),
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
            if( $model['user_create_id']==Yii::$app->getUser()->identity->id ){
                echo Html::submitButton('Сохранить',
                    ['class' => 'btn btn-success']);
            }

              ?>



          <?php
//               if( Yii::$app->user->id == 50 )

                   echo Html::a('Печать',
                       ['/tz/excview1/?id=' . $model->id],
                       [
                           'id' => "to_print",
                           'target' => "_blank",
                           'class' => 'btn btn-default'
                       ]);
          ?>


          <?php
          if( Yii::$app->user->identity->group_id == 50 )

              echo Html::a('Передать в работу',
                  ['/tz_to_work/signal_to_work/?id=' . $model->id],
                  [
                      'id' => "to_print",
                      'target' => "_blank",
                      'class' => 'btn btn-default'
                  ]);
          ?>

      </div>

  </div>







        </div>



        <div class="pv_motion_create_all_new">




            <div class="pv_motion_create_right">

                <?php

                echo $form->field($model, 'array_tk_amort')->widget(MultipleInput::className(), [
                    'id' => 'my_id2',
                    'allowEmptyList' => false,
                    'rowOptions' => [
                        'id' => 'row{multiple_index_my_id2}',
                    ],

//                    'max'               => 25,
                    'min'               => 1, // should be at least 2 rows
//                    'allowEmptyList'    => true ,//false,
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
                            'name'  => 'ed_izmer',
                            'title' => 'Ед. изм',
                            'type'  => 'dropDownList',
                            'defaultValue' => 1,
                            'options' => [
                                'style' => 'min-width: 66px;padding: 6px 0px;'
                            ],

                            'items' => ArrayHelper::map( Spr_things::find()->all(),'id','name'),


                        ],

//                        [
//                            'name'  => 'ed_izmer_num',
//                            'title' => 'Кол-во',
//                            'defaultValue' => 1,
//                            'enableError' => true,
//                            'value' =>(integer) $model->ed_izmer_num,
//                            'options' => [
//                                'type' => 'number',
//                                //'type' => \yii\widgets\MaskedInput::className(), ['mask' => '9'],
//                                'class' => 'input-priority',
//                                'style' => 'min-width:62px;max-width: 70px;overflow: auto;'
//                            ]
//                        ],

//                        [
//                            'name'  => 'ed_izmer_num',
//                            'title' => 'Кол-во',
//                            'defaultValue' => 0,
//                            'enableError' => true,
//                            'value' =>(integer) $model->ed_izmer_num,
//                            'options' => [
//                                //'type' => 'number',
//                                'type' => \yii\widgets\MaskedInput::className(), ['mask' => '9'],
//                                'class' => 'input-priority',
//                                'style' => 'min-width:62px;max-width: 70px;overflow: auto;'
//                            ]
//                        ],
                        [
                            'name'  => 'tx',
                            'title' => 'Комментарии',
                            'defaultValue' => '',
                            'enableError' => true,
                            'options' => [
                                'class' => 'input-priority',
                                //'style' => 'max-width: 170px;overflow: auto;'
                                'style' => 'overflow: auto;'

                            ]
                        ]


                    ]
                ]);
                //->label(false);

                ?>

            </div>
            <div class="pv_motion_create_right">

                <?php

                echo $form->field($model, 'array_tk')->widget(MultipleInput::className(), [

                    'id' => 'my_id',
                    'allowEmptyList' => false,
                    'rowOptions' => [
                        'id' => 'row{multiple_index_my_id}',

                    ],

//                    'max'               => 25,
                    'min'               => 1, // should be at least 2 rows
//                    'allowEmptyList'    => true ,//false,
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
                                    'id','name'),

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

//                        [
//                            'name'  => 'ed_izmer_num',
//                            'title' => 'Кол-во',
//                            'defaultValue' => 1,
//                            'enableError' => true,
//                            'value' =>(integer) $model->ed_izmer_num,
//                            'options' => [
//                                'type' => 'number',
//                                //'type' => \yii\widgets\MaskedInput::className(), ['mask' => '9'],
//                                'class' => 'input-priority',
//                                'style' => 'min-width:62px;max-width: 70px;overflow: auto;'
//                            ]
//                        ],
                        [
                            'name'  => 'tx',
                            'title' => 'Комментарии',
                            'defaultValue' => '',
                            'enableError' => true,
                            'options' => [
                                'class' => 'input-priority',
                                //'style' => 'max-width: 170px;overflow: auto;'
                                'style' => 'overflow: auto;'

                            ]
                        ]


                    ]
                ]) ;
                //  ->label(false);
                ?>

            </div>


        </div>




    <?php ActiveForm::end(); ?>

</div>



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
///////////////
$('#tz-tk_top').change(function() {
    	    
    
    var  number_tz_id = $('#tz-_id').val();           
    var  number_tk = $(this).val();
    
    //var  text = $('#tz-tk_top>option[value='+number_tk+']').text();

    

    var  val_0_1 = $('#tz-name_tz').text();           //// ..ТехЗадание     
    // var  val_1_1 = $('#tz-wh_deb_top').val();
    // var  val_1_2 = $('#tz-wh_deb_element').val();
    
    if(!$('#tz-wh_cred_top').val()){
        alert("Выберите Автопарк" );        
        return false;
    }
    
    var  val_2_1 = $('#tz-wh_cred_top').val();
    var  val_2_2 = $('#tz-wh_cred_top>option[value='+val_2_1+']').text();
    
    
    // var  val_2_2 = $('#tz-wh_cred_element').val();
    
    //id="tz-wh_cred_top"
    	    
             // console.log(number_tz_id);   
             // console.log(number_tk);
             // console.log(text);            

    $.ajax( {
		url: '/tz/createnext',
		data: {
		    id_tz : number_tz_id,		    		    
		    id_tk : number_tk,		    
		        //text: text,		    
		    val12: {
                val_0_1: val_0_1, //name_tz
                    // val_1_1: val_1_1,
                    // val_1_2: val_1_2,
                val_2_1: val_2_1, // wh_cred_top  id
                val_2_2: val_2_2, // wh_cred_top_name
                    // val_2_2: val_2_2,
		    }
		},		
			success: function(res) {		 
		    
		    //alert('OK. '+ res );
		    
		    $('#tz1').html('');
		    $('#tz1').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
							    $('#tz1').html('');
							    $('#tz1').html('не пошло. '+ res);
					}
    } );
    
    
    
});




//////////////////// Debitor
$('#tz-wh_deb_top').change(function() {
    var  number = $(this).val();
    var  text = $('#tz-wh_deb_top>option[value='+number+']').text();
    
    	    $('#tz-wh_deb_top_name').val(text);
    	    
    	    
            console.log(number);   
            console.log(text);   
    
    $.ajax( {
		url: '/pvmotion/whselect',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#tz-wh_deb_element').html('');
		    $('#tz-wh_deb_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
    
});



//////////////////// Creditor
$('#tz-wh_cred_top').change(function() {
    var  number = $(this).val();
    var  text = $('#tz-wh_cred_top>option[value='+number+']').text();
    
    	    $('#tz-wh_cred_top_name').val(text);
    	    
    	    
            console.log(number);   
            console.log(text);   
    
    $.ajax( {
		url: '/pvmotion/whselect',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#tz-wh_cred_element').html('');
		    $('#tz-wh_cred_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
    
});

$('#go_home').click(function() {    
    window.history.back();  
})


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

