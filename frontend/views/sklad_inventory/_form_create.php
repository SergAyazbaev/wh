<?php

use frontend\models\post_spr_globam;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\widgets\ActiveForm;



//use yii\widgets\Pjax;

//Pjax::begin(['id' => "pjax-container",]);
//        echo \yii::$app->request->get('page');
//Pjax::end();
?>

<style>
    .modal-body {
        background-color: #f3f3f3;
        position: relative;
        padding: 15px;
        width: 100%;
        display: block;
        clear: both;
        float: left;
    }
    .name_nn{
        background-color: #faebd729;
        margin: 2px;
        display: block;
        width: 100%;
        height: 26px;
        float: left;
        font-size: 20px;
        padding: 0 40px;
    }
    .name_s{
        width: 50px;
        float: left;
        text-align: right;
        padding: 0 7px;
    }

    .name_s1{
        float: left;
        display: block;
        height: 37px;
    }



</style>


<style>
    .modal-vn{
        /*background-color: #00ff43;*/
        display: block;
        position: relative;
    }
    .modal-vn>.btn.btn-default{
        float: left;
        margin-right: 10px;
        height: 22px;
        padding: 0;
        background-color: #00ff437d;
        border: 3px solid #2ebf0a5c;
    }



    .redstyle{
        color: rgba(65, 145, 69, 0.9);
        background: rgba(65, 145, 69, 0.15);
    }
    .multiple-input-list__item:hover{
        background: rgba(65, 145, 69, 0.27);
    }

    /*select:hover, select:active,select:after {*/
    /*    background-color: aquamarine;*/
    /*}*/

    div>.has-error>.help-block{
        /*display: block;*/
        /*position: fixed;*/
        /*top: 190px;*/
        padding: 9px;
        font-size: 21px;
        background-color: #ffd57fd4;
        color: crimson;
        width: 80%;
        left: 10%;
        text-align: center;
    }


    /*.glyphicon{}*/

    /*.glyphicon-remove{*/
    /*color: #b81900;*/
    /*content: "\2603";*/
    /*}*/
    /*.glyphicon-remove::before{*/
    /*content: "\2716";*/
    /*color: #b81900;*/
    /*}*/

    .glyphicon-plus{
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;

        /*padding: 7px;*/
        /*margin: -8px;*/
        font-size: medium;
        font-weight: bold;
        border-radius: 25px;
    }
    .glyphicon-plus:hover{
        color: rgba(255, 255, 255, 0.53);
        background-color: #5cb85c;
        border-color: #4cae4c;
        box-shadow: -1px 3px 7px 0px rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.1);

    }

    /*.help-block {*/

    /*}*/
    .help-block-error{
        display: none;
    }

    thead th{
        height: 22px;
        padding: 10px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    table tr,table td,{
        border: 0px;
        margin: 0px;
        width: 100%;
    }
    thead tr,thead td{
        background-color: rgba(11, 147, 213, 0.12);
        padding: 5px 10px;
        margin: 0px;
        padding: 10px;
    }
    tbody tr,tbody td{
        padding: 1px 3px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }
    td div select{
        /*background-color: rgba(65, 255, 61, 0.24);*/
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        width: 100%;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }
    td div input{
        background-color: rgba(35, 216, 57, 0.21);
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }



    .list-cell__button{
        text-align: center;
    }
    table.multiple-input-list.table-renderer tbody tr > td {
        border: 0 !important;
        padding: 0;
    }

    .pv_motion_create_right_center {
        /*width: 49%;*/
        min-width: 345px;
        /*background-color: #74bb2ec2;*/
        background-color: #74bb2e33;
        display: block;
        position: relative;
        /*float: left;*/
        /*padding: 9px;*/
        /*margin-left: 5px;*/
        /*margin-bottom: 5px;*/
    }


    .pv_motion_create_ok_button{
        display: block;
        position: inherit;
        padding: 10px 18px;
        float: left;
        width: 100%;
        /*margin: 5px;*/
        margin-top: 5px;
        background-color: #48616121;
    }



    @media (max-width:780px) {
        .scroll_window{

            width: 99%;
            display: grid;
            position: inherit;
            overflow: auto;
            margin-bottom: 100px;
        }

    }



    @media (max-width: 710px) {

        h1 {
            font-size: 20px;
            padding: 5px 15px;
        }

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


        .pv_motion_create_ok_button{
            display: block;
            position: inherit;
            padding: 10px 18px;
            float: left;
            width: auto;
            background-color: #48616121;
            /*margin: 5px;*/
            margin-top: 5px;
            background-color: #48616121;
        }

    }


    .pv_motion_create_right_center {
        width: 100%;
        min-width: 240px;
        margin: 0px;
        padding: 3px 3px;
    }

    @media (min-width: 605px){
        .pv_motion_create_right_center {
            padding: 3px 3px;
        }
    }

    @media (min-width: 1214px){
        .pv_motion_create_right_center {
            padding: 15px 10px;
        }
    }

    .modal_left  {
        width: 90%;
        margin: 0px 5%;
        margin-bottom: 5%;
    }


</style>





<?php $form = ActiveForm::begin([
    'id'=>'project-form',

    //    'action' => ['index'],
    'method' => 'post',
    'options' => [
        'data-pjax' => 1,
        'autocomplete' => 'off'
    ],

    'enableAjaxValidation'      => false,
    'enableClientValidation'    => true,//false,
    'validateOnChange'          => true,//false,
    'validateOnSubmit'          => true,
    //    'validateOnBlur'            => true,//false,false,

]);
?>




<div id="tz1" >
    <div class="pv_motion_create_right_center">

        <div class="tree_land3">

            <?php
            echo "<br><h2> Ведомость № ".$new_doc->id ."</h2>";
            ?>

        </div>


        <div class="tree_land">

            <?php

            // Проверка Воспроизведения ИЗ миллисекунд в нормальный формат дат
            // $new_doc->dt_create = $new_doc->getDtCreateText();


            $new_doc->dt_create =
                date('d.m.Y H:i:s',strtotime($new_doc['dt_create']));

            echo $form->field($new_doc, 'dt_create')
                ->widget(DateTimePicker::className(),[
                    'name' => 'dp_2',
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
            echo $form->field($new_doc, 'dt_update')
                ->textInput(['readonly'=> true]);
            ?>


        </div>




        <div class="tree_land">
            <?php

            //////////
            echo $form->field($new_doc, 'wh_destination')->dropDownList(
                ArrayHelper::map(
	                Sprwhtop::find()
	                        ->orderBy( 'name' )
	                        ->all(),
                    'id','name'),
                [
                    'prompt' => 'Выбор склада ...'
                ]
            )->label("Компания");



            ///////////////////

            echo $form->field($new_doc, 'wh_destination_element')->widget(Select2::className()
                , [
                    'name' => 'st',
                    'data' =>   ArrayHelper::map(Sprwhelement::find()
                                                             ->where( [ 'parent_id' => (integer) $new_doc->wh_destination ] )
                                                             ->orderBy( 'name' )
                                                             ->all(), 'id', 'name' ),

                    //                    'options' => [
                    //
                    //                        'id' => 'array_bus_select',
                    //                        'placeholder' => 'Список автобусов ...',
                    //                        'allowClear' => true,
                    //                        'multiple' => true
                    //
                    //                    ],

                    'theme' => Select2::THEME_BOOTSTRAP,
                    'size' => Select2::SMALL, //LARGE,

                    //                    'pluginOptions' => [
                    //                        'tags' => true,
                    //                        'tokenSeparators' => [',', ' '],
                    //                        'maximumInputLength' => 10
                    //                    ],
                ]);





            echo "<div class='close_hid'>";
            echo $form->field($new_doc, 'wh_destination_name')
                ->hiddenInput()->label(false);
            echo $form->field($new_doc, 'wh_destination_element_name')
                ->hiddenInput()->label(false);
            echo "</div>";

            ?>


        </div>





        <div class="tree_land">
            <?php
            echo $form->field($new_doc, 'group_inventory')
                ->textInput(
                    [

                        'placeholder' => 'Посказка:'. $new_doc->id ,
                        'prompt' => 'Выбор ...',
                    ]
                )
            ;
            ?>

            <?php
            echo $form->field($new_doc, 'group_inventory_name')
                ->textInput(
                    [
                        'placeholder' => 'Название группы ',

                    ]
                )
                //->hint('Пожалуйста, введите')
            ;
            ?>


        </div>




    </div>







    <div class="pv_motion_create_ok_button">



        <?php
        // $alert_mess  MESSAGE
        if(isset($alert_mess) && !empty($alert_mess) ){
            echo Alert::widget([
                'options' => [
                    'class' => 'alert_save',
                    'animation' => "slide-from-top",
                ],
                'body' => $alert_mess
            ]);
        }
        ?>



        <?php
        echo Html::a('Выход', ['/sklad_inventory'],['class' => 'btn btn-warning'] );
        ?>


        <?php
        echo Html::submitButton('Сохранить изменения',
            [
                'class' => 'btn btn-success',
                'name' => 'contact-button',
                'value' => '',
                'data-confirm' => Yii::t('yii',
                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'),
            ]
        );
        ?>


        <?php //////////////////////////////////////
        ///
        /// МОДАЛЬНОЕ ОКНО. ИМПУТ под КОПИПАСТ - АСУОП
        ///


        //            $user=Yii::$app->user->getIdentity();
        //        if ( isset($user['username']) && $user['username']=='VICTOR5' ) {

        Modal::begin([
            'header' => '<h2>Заливаем копипаст </h2>',
            'toggleButton' => [
                'label' => 'Копипаст АСУОП',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],//'footer' => 'Низ окна',
        ]);

        ?>


        <?= $form
            ->field($new_doc, 'add_text_to_inventory_am')
            ->textarea(['autofocus' => true,
                'style'=>'height: 300px;font-size: 10px;',
                'placeholder'=>"текст (таб) количество\nтекст (таб) количество\nтекст (таб) количество" ])
            ->label(false);
        ?>


        <div class="form-group">
            <?= Html::submitButton(  'Добавить ( залить ) копипаст ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'add_button_am'
                ]) ?>

        </div>


        <?php Modal::end();
        //        }
        ///////////////////////////////////?>


        <?php //////////////////////////////////////
        ///
        /// МОДАЛЬНОЕ ОКНО. ИМПУТ под КОПИПАСТ - СПИСАНИЕ-РАСХОДНИКИ
        ///


        //            $user=Yii::$app->user->getIdentity();
        //        if ( isset($user['username']) && $user['username']=='VICTOR5' ) {

        Modal::begin([
            'header' => '<h2>Заливаем копипаст </h2>',
            'toggleButton' => [
                'label' => 'Копипаст СПИСАНИЕ',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],//'footer' => 'Низ окна',
        ]);

        ?>


        <?= $form
            ->field($new_doc, 'add_text_to_inventory')
            ->textarea(['autofocus' => true,
                'style'=>'height: 300px;font-size: 10px;',
                'placeholder'=>"текст (таб) количество\nтекст (таб) количество\nтекст (таб) количество" ])
            ->label(false);
        ?>


        <div class="form-group">
            <?= Html::submitButton(  'Добавить ( залить ) копипаст ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'add_button'
                ]) ?>

        </div>


        <?php Modal::end();
        //        }
        ///////////////////////////////////?>





        <?php //////////////////////////////////////
        //        $user=Yii::$app->user->getIdentity();
        //        if ( isset($user['username']) && $user['username']=='VICTOR5' ) {

        Modal::begin(['header' => '<h2>Номера строк</h2>',

            'toggleButton' => [
                'label' => 'Удалить строки',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],

            //'footer' => 'Низ окна',
        ]);
        ?>


        <div class="name_ss">
            <div class="name_nn" >
                Амортизация:
            </div>
            <div class="name_s1" style="width: 45%">
                <div class="name_s" >
                    c:
                </div>
                <?= $form->field($new_doc, 'erase_array[0][0]')->textInput(['placeholder' => '(1)', 'autofocus' => true, 'style' => 'width: 120px'])->label(false); ?>
            </div>
            <div class="name_s1" style="width: 45%">
                <div class="name_s" >
                    по:
                </div>
                <?= $form->field($new_doc, 'erase_array[0][1]')
//                    ->textInput(['placeholder' => '(' . $erase_array[0] . ')',
                    ->textInput(['placeholder' => '( 123 )',
                        'autofocus' => true, 'style' => 'width: 120px'])
                    ->label(false); ?>
            </div>
        </div>

        <div class="name_ss">
            <div class="name_nn" >
                Списание:
            </div>
            <div class="name_s1" style="width: 45%">
                <div class="name_s" >
                    с:
                </div>
                <?= $form->field($new_doc, 'erase_array[1][0]')->textInput(['placeholder' => '(1)', 'autofocus' => true, 'style' => 'width: 120px'])->label(false); ?>
            </div>
            <div class="name_s1" style="width: 45%">
                <div class="name_s" >
                    по:
                </div>
                <?= $form->field($new_doc, 'erase_array[1][1]')
                    //->textInput(['placeholder' => '(' . $erase_array[1] . ')',
                    ->textInput(['placeholder' => '( 2222 )',
                        'autofocus' => true, 'style' => 'width: 120px'])->label(false); ?>
            </div>
        </div>

        <div class="name_ss">
            <div class="name_nn" >
                Расходные материалы:
            </div>
            <div class="name_s1" style="width: 45%">
                <div class="name_s" >
                    c:
                </div>
                <?= $form->field($new_doc, 'erase_array[2][0]')->textInput(['placeholder' => '(1)', 'autofocus' => true, 'style' => 'width: 120px'])->label(false); ?>

            </div>
            <div class="name_s1" style="width: 45%">
                <div class="name_s" >
                    по:
                </div>
                <?= $form->field($new_doc, 'erase_array[2][1]')
                    //->textInput(['placeholder' => '(' . $erase_array[2] . ')',
                    ->textInput(['placeholder' => '( 333 )',
                        'autofocus' => true, 'style' => 'width: 120px'])->label(false); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Удалить',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'erase_button'
                ])
            ?>
        </div>


        <?php Modal::end();
        //}
        ///////////////////////////////////?>









        <?php
        echo Html::a('Копия с новым номером',
            ['/sklad_inventory/copycard_from_origin?id=' . $new_doc->id],

            [
                'data-confirm' => Yii::t('yii',
                    '<b>Вы точно хотите: </b><br>
                        Создать КОПИЮ этой НАКЛАДНОЙ ?'),

                'id' => "to_print",
                //'target' => "_blank",
                'class' => 'btn  btn-info' //btn-primary'   //btn-success'
            ]);
        ?>




        <!--            --><?php
        //            echo Html::a('Зеленая НАКЛАДНАЯ',
        //                ['/sklad_inventory/html_pdf_green/?id=' . $new_doc->id],
        //                [
        //                    'id' => "to_print",
        //                    'target' => "_blank",
        //                    'class' => 'btn btn-default'
        //                ]);
        //            ?>



        <?php
        echo Html::a('Ведомость',
            ['/sklad_inventory/html_reserv_fond/?id=' . $new_doc->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>



    </div>


    <div class="pv_motion_create_right">
        <?php
        //ddd($new_doc);

        $xx=0;
        echo $form->field($new_doc, 'array_tk_amort')->widget(MultipleInput::className(), [

            'id' => 'my_id2',
            'theme'  => 'default',
            'allowEmptyList' => false,
            'min'               => 0, // should be at least 2 rows
            'enableGuessTitle'  => false,
            'sortable'  => true,
            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

            'rowOptions' =>function ($model){
                $text_array = ['id' => 'row{multiple_index_my_id2}'];
                //ddd($model->take_it=='1');

                if(isset($model['take_it']) && $model['take_it']=='1') {
                    $text_array = ['id' => 'row{multiple_index_my_id2}',
                        'class' => 'redstyle'
                    ];

                }

                return $text_array;
            },


            'columns' => [
                [
                    'name' => 'calc',
                    'title' => '№',
                    'value' => function($data, $key) {
                        return ++$key['index'];
                    },

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'width: 30px;text-align: right;padding-right: 5px;',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ],
                ],


                [
                    'name'  => 'wh_tk_amort',
                    'type'  => 'dropDownList',
                    'title' => 'Группа',
                    'defaultValue' => 'wh_tk_amort',
                    'items' => ArrayHelper::map(
	                    post_spr_globam::find()
	                                   ->orderBy( 'name' )
	                                   ->all(),
                        'id','name'),

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'padding: 0px;overflow: auto;',
                        'id' => 'subcat211-{multiple_index_my_id2}',

                        'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
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
                        'prompt' => 'Выбор ...',
                        'style' => 'padding: 0px;min-width:1vw;overflow: auto;',
                        'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});                     
JS
                    ],

                    'items' =>
                        ArrayHelper::map(
	                        Spr_globam_element::find()
	                                          ->orderBy( 'name' )
	                                          ->all(),
                            'id','name')
                    ,

                ],



                [
                    'name'  => 'intelligent',
                    'title' => 'BC',
                    'options' => [
                        'id' => 'subcat22-{multiple_index_my_id2}',
                        'style' => 'padding: 0px;width:2vw;overflow: auto;',
                    ],
                    'value' => function($data) {
                        if($data['intelligent']==1){
                            return "Да";
                        }

                        return "Нет";
                    }
                ],


                [
	                'name'         => 'ed_izmer',
	                'title'        => 'Ед. изм',
	                'type'         => 'dropDownList',
	                'defaultValue' => 1,
	                'options'      => [
                        'id' => 'subcat-ed_izm-{multiple_index_my_id2}',
                        'prompt' => 'Выбор ...',
                        'style' => 'min-width:1vw;width: 75px;overflow: auto;'
                    ],


	                'items' => ArrayHelper::map(
		                Spr_things::find()
		                          ->orderBy( 'name' )
		                          ->all(),
		                'id', 'name' ),

                ],

                [
                    'name'  => 'ed_izmer_num',
                    'title' => 'Кол-во',
                    'defaultValue' => 1,
                    'enableError' => true,
                    'value' => 'ed_izmer_num',
                    'options' => [
                        'type' => 'number',
                        'class' => 'input-priority',
                        'style' => 'min-width:5vw;width: 60px;overflow: auto;'
                    ]
                ],


//                [
//                    'name' => 'take_it',
//                    'title' => '',
//                    'type' => 'checkbox',
//                ],



            ]
        ]) ->label('Амортизация',['class' => 'my_label'] );
        ;
        ?>
    </div>



    <div class="pv_motion_create_right">
        <?php

        //////////////////////
        echo $form->field($new_doc, 'array_tk')->widget(MultipleInput::className(), [
            'id' => 'my_id',
            'theme'  => 'default',
            'rowOptions' =>function ($model, $index, $context){
                $text_array = ['id' => 'row{multiple_index_my_id}'];
                //ddd($model->take_it=='1');

                if(isset($model['take_it']) && $model['take_it']=='1') {
                    $text_array = ['id' => 'row{multiple_index_my_id}',
                        'class' => 'redstyle'
                    ];

                }

                return $text_array;
            },

            //            'rowOptions' => [
            //                'id' => 'row{multiple_index_my_id}',
            //            ],

            //            'max'               => 50,
            'min'               => 0, // should be at least 2 rows
            'allowEmptyList'    => false,
            'enableGuessTitle'  => false,
            'sortable'  => true,
            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

            'columns' => [
                [                    'name' => 'calc',
                    'title' => '№',
                    'value' => function($data, $key) {
                        return ++$key['index'];
                    },
                    'defaultValue' => function($data, $key) {
                        return ++$key['index'];
                    },

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'width: 30px;text-align: right;padding-right: 5px;',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ],
                ],
                [
                    'name'  => 'wh_tk',
                    'options' => [
                        'style' => 'width: 30px;text-align: right;padding-right: 5px;',
                    ],
                ],
                [
                    'name'  => 'wh_tk_element',
                    'options' => [
                        'style' => 'width: 30px;text-align: right;padding-right: 5px;',
                    ],
                ],

                [
                    'name'  => 'wh_tk',
                    'type'  => 'dropDownList',
                    'title' => 'Группа',
                    'defaultValue' => [],
                    //'items' => Spr_glob::find()->select(['name'])->column(),
                    'items' =>
                        ArrayHelper::map(
	                        Spr_glob::find()
	                                ->orderBy( 'name' )
	                                ->all(),
                            'id',
                            'name'),

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'min-width: 60px;',
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
                            Spr_glob_element::find()
                                            ->orderBy( 'name' )
                                            ->all(),
                            'id','name')
                    ,
                    'options' => [
                        'prompt' => '...',
                        'id' => 'subcat-{multiple_index_my_id}',

                        'onchange' => <<< JS
                        
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_id}").val(data);
});
                        
$.post("list_ed_izm?id=" + $(this).val(), function(data){
//alert(data);
    $("select#sklad-array_tk-{multiple_index_my_id}-ed_izmer").val(data);
});
JS
                        ,
                    ]
                ],




                [
	                'name'         => 'ed_izmer',
	                'title'        => 'Ед. изм',
	                'type'         => 'dropDownList',
	                'defaultValue' => 1,
	                'options'      => [
                        'style' => 'padding: 0px;min-width:75px;overflow: auto;',
                        'id' => "sklad-array_tk-{multiple_index_my_id}-ed_izmer",
                        'onchange' => <<< JS
// Если выбрана любая Ед.Изм, кроме ШТУК 
// (метры, литры, пог.метры...)
                            
if ( $(this).val()>1 ){
    //console.log( $("input#zero-{multiple_index_my_id}").val() );
    $("input#zero-{multiple_index_my_id}").attr('step','0.1');
}else{
    $("input#zero-{multiple_index_my_id}").attr('step','1');
};
JS
                        ,
                    ],


	                'items' => ArrayHelper::map(
		                Spr_things::find()
		                          ->orderBy( 'name' )
		                          ->all(),
		                'id', 'name' ),


                ],

                [
                    'name' => 'take_it',
                    'title' => '',
                    'type' => 'checkbox',
                ],


                [
                    'name'  => 'ed_izmer_num',
                    'title' => 'Кол-во',
                    'defaultValue' => 0.1,
                    'enableError' => true,
                    'value' => 'ed_izmer_num',
                    'options' => [
                        'id' => 'zero-{multiple_index_my_id}',
                        'type'  => 'number',
                        'class' => 'input-priority',
                        'style' => 'max-width: 70px;overflow: auto;',
                        'step'  => '0.1',

                    ]
                ],



            ]
        ]) ->label('Списание',['class' => 'my_label'] );

        ;
        ?>
    </div>


    <div class="pv_motion_create_right">
        <?php

        /////////////////........
        echo $form->field($new_doc, 'array_casual')->widget(MultipleInput::className(), [
            'id' => 'my_casual',
            'theme'  => 'default',

            'rowOptions' =>function ($model, $index, $context){
                $text_array = ['id' => 'row{multiple_index_my_casual}'];
                //ddd($model->take_it=='1');

                if(isset($model['take_it']) && $model['take_it']=='1') {
                    $text_array = ['id' => 'row{multiple_index_my_casual}',
                        'class' => 'redstyle'
                    ];

                }

                return $text_array;
            },


            //            'rowOptions' => [
            //                'id' => 'row{multiple_index_my_casual}',
            //            ],

            //            'max'               => 50,

            'min'               => 0, // should be at least 2 rows
            'allowEmptyList'    => false,
            'enableGuessTitle'  => false,
            'sortable'  => true,
            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

            'columns' => [
                [
                    'name' => 'calc',
                    'title' => '№',
                    'value' => function($data, $key) {
                        return ++$key['index'];
                    },
                    'defaultValue' => function($data, $key) {
                        return ++$key['index'];
                    },

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'width: 30px;text-align: right;padding-right: 5px;',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ],
                ],

                [
                    'name'  => 'wh_tk',
                    'type'  => 'dropDownList',
                    'title' => 'Группа',
                    'defaultValue' => [],
                    //'items' => Spr_glob::find()->select(['name'])->column(),
                    'items' =>
                        ArrayHelper::map(
	                        Spr_glob::find()
	                                ->orderBy( 'name' )
	                                ->all(),
                            'id',
                            'name'),
                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'min-width: 60px;',
                        'id' => 'subcat11_casual-{multiple_index_my_casual}',
                        'onchange' => <<< JS
                        
$.post("list?id=" + $(this).val(), function(data){
    //console.log(data);
    $("select#subcat_casual-{multiple_index_my_casual}").html(data);
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
                            Spr_glob_element::find()
                                            ->orderBy( 'name' )
                                            ->all(),
                            'id','name')
                    ,
                    'options' => [
                        'prompt' => '...',
                        'id' => 'subcat_casual-{multiple_index_my_casual}',

                        'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
// alert(data);

    $("select#subcat11_casual-{multiple_index_my_casual}").val(data);
});
                        
$.post("list_ed_izm?id=" + $(this).val(), function(data){
//alert(data);
    $("select#sklad-array_casual-{multiple_index_my_casual}-ed_izmer").val(data);
});
JS
                        ,
                    ]
                ],


                [
	                'name'         => 'ed_izmer',
	                'title'        => 'Ед. изм',
	                'type'         => 'dropDownList',
	                'defaultValue' => 1,
	                'options'      => [
                        'style' => 'padding: 0px;min-width:75px;overflow: auto;',
                        'id' => "sklad-array_casual-{multiple_index_my_casual}-ed_izmer",
                        'onchange' => <<< JS
                        
// Если выбрана любая Ед.Изм, кроме ШТУК 
// (метры, литры, пог.метры...)
                            
if ( $(this).val()>1 ){
    //console.log( $("input#zero-{multiple_index_my_casual}").val() );
    $("input#zero-{multiple_index_my_casual}").attr('step','0.1');
}else{
    $("input#zero-{multiple_index_my_casual}").attr('step','1');
};
JS
                        ,
                    ],


	                'items' => ArrayHelper::map(
		                Spr_things::find()
		                          ->orderBy( 'name' )
		                          ->all(),
		                'id', 'name' ),


                ],

                [
                    'name' => 'take_it',
                    'title' => '',
                    'type' => 'checkbox',
                ],


                [
                    'name'  => 'ed_izmer_num',
                    'title' => 'Кол-во',
                    'defaultValue' => 0.1,
                    'enableError' => true,
                    'value' => 'ed_izmer_num',
                    'options' => [
                        'id' => 'zero-{multiple_index_my_casual}',
                        'type'  => 'number',
                        'class' => 'input-priority',
                        'style' => 'max-width: 70px;overflow: auto;',
                        'step'  => '0.1',

                    ]
                ],


            ]
        ]) ->label('Расходные материалы',['class' => 'my_label'] );
        ;
        ?>
    </div>
</div>


<?php ActiveForm::end(); ?>



<?php
$script = <<<JS
$(document).ready(function() {
    $('.alert_save').fadeOut(2500); // плавно скрываем окно временных сообщений
});

$(document).on('keypress',function(e) {
    if(e.which == 13) {
        // alert('Нажал - enter!');
      e.preventDefault();
      return false;
    }
});


//////////////////// Debitor -top
$('#sklad_inventory-wh_debet_top').change(function() {
        
    var  number = $(this).val();
    var  text = $('#sklad_inventory-wh_debet_top>option[value='+number+']').text();    
    $('#sklad_inventory-wh_debet_name').val(text) ;
    

    $.ajax( {
		url: '/sklad_inventory/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		            $('#sklad_inventory-debet_element').html('');
		            $('#sklad_inventory-wh_debet_element').html(res);		    
					},
			error: function( res) {
						alert('JS.sklad_inventory-wh_destination '+res );
						console.log(res);
					}
    } );
    
    
    
});



//////////////////// Creditor
$('#sklad_inventory-wh_destination').change(function() {
    
    var  number = $(this).val();
    var  text = $('#sklad_inventory-wh_destination>option[value='+number+']').text();
 	    $('#sklad_inventory-wh_destination_name').val(text);
 	    
            // console.log(number);   
            // console.log(text);    
   
    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    // $('#sklad_inventory-wh_destination_element').html('');
		    $('#sklad_inventory-wh_destination_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('JS.sklad_inventory-wh_destination '+ res );
					}
    } );
   
});

//////////////////// destination_element - element
/// sklad_inventory-wh_destination_element 
$('#sklad_inventory-wh_destination_element').change(function() {    
     var  number2 = $('#sklad_inventory-wh_destination_element').val();     
     var  text2   = $('#sklad_inventory-wh_destination_element>option[value='+number2+']').text();
      $('#sklad_inventory-wh_destination_element_name').val(text2);
});






////////////
$('#go_home').click(function() {    
    window.history.back();  
})



JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

