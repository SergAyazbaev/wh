<?php

use frontend\models\post_spr_globam;
use frontend\models\post_spr_globam_element;
use frontend\models\posttz;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhtop;
use frontend\models\Tk;
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

    .pv_motion_create_all_new {
        width: 100%;
        /*max-width: 57%;*/
        overflow: hidden;
        height: max-content;
        background-color: #91a98029;
        display: block;
        position: relative;
        float: left;
    }

    .pv_motion_create_right, .pv_motion_create_right_center {
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

    .pv_motion_create_right_center, .pv_motion_create_right {
        WIDTH: 98%
        /*max-width: 693px;*/

    }

    .pv_motion_create_right {
        width: 100%;
    }

    .pv_motion_create_ok_button {
        display: block;
        position: inherit;
        padding: 10px 18px;
        float: left;
        width: 98%;
        background-color: #48616121;
        /*margin: 5px;*/
        margin-top: 5px;
        background-color: #48616121;

    }

    .tree_land {
        background-color: cornsilk;
        display: block;
        padding: 8px;
        min-height: 186px;
    }

    .tree_land2 {
        min-height: 186px;
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

        .pv_motion_create_ok_button {
            display: block;
            position: relative;
            padding: 5%;
            padding-left: 10%;
            float: left;
            width: 100%;
        }

    }

    @media (max-width: 600px) {
        .pv_motion_create_right {
            width: 100%;
            min-width: 263px;
            /*height: 115px;*/
            padding: 0px 10px;
            display: block;
            position: initial;
        }

</style>

<?php
if (empty($model->dt_create))
    $model->dt_create = Date('d.m.Y H:i:s', strtotime('now'));
?>

<?php $form = ActiveForm::begin(); ?>


<div id="tz1">


    <div class="pv_motion_create_right_center">

        <div class="tree_land2">
            <?php
            echo $form->field($model, '_id')
                ->textInput(['class' => 'form-control',
                    'style' => 'width: 265px; margin-right: 5px;',
                    'readonly' => 'readonly'])->hiddenInput()->label(false);
            ?>

            <?php
            $xx = posttz::find()->all();
            $type_words = ArrayHelper::getColumn($xx, 'name_tz');

            if (isset($val12))
                $model->name_tz = $val12['val_0_1'];


            echo $form->field($model, 'name_tz')
                ->widget(
                    AutoComplete::className(), [
                    'clientOptions' => [
                        'source' => $type_words,
                    ],
                ])
                ->textarea([
                    'class' => 'form-control class-content-title_series',
                    'readonly' => 'readonly',
                    'disabled' => 'disabled',
                    'style' => 'max-width: 100%;font-size: 21px;margin: 0px;width: 248px;',
                ]);

            ?>

        </div>
        <div class="tree_land">

            <?php
            $type_words = [
                '1' => 'Первичная полная установка',
                '2' => 'Первичная частичная установка',
                '3' => 'Демонтаж ',
                '4' => 'Монтаж',
                '5' => 'Выдача расходных материалов',

            ];

            if ($model->street_map > 0)

                echo $form->field($model, 'street_map')
                    ->dropDownList($type_words,
                        ['prompt' => 'Без карты',
                            'readonly' => 'readonly',
                            'disabled' => 'disabled',
                        ]
                    )
                    ->label("Дорожная карта")//->hint("Дорожная карта")
                ;
            else
                echo $form->field($model, 'street_map')
                    ->dropDownList($type_words,
                        ['prompt' => 'Без карты',
                        ]
                    )
                    ->label("Дорожная карта")//->hint("Дорожная карта")
                ;


            ?>

        </div>
        <div class="tree_land">

            <?php


            echo $form->field($model, 'wh_cred_top')->dropdownList(
                ArrayHelper::map(Sprwhtop::find()->all(), 'id', 'name'),
                [
                    'prompt' => 'Выбор склада ...',
                    'readonly' => 'readonly',
                    'disabled' => 'disabled',
                ]
            )->label("Автопарк");


            echo $form->field($model, 'tk_top')->dropdownList(
                ArrayHelper::map(Tk::find()->all(), 'id', 'name_tk'),
                [
                    'prompt' => 'Без ТК... ',
                    'readonly' => 'readonly',
                    'disabled' => 'disabled',
                ]
            );


            echo $form->field($model, 'wh_cred_top_name')->dropdownList(
                Sprwhtop::find()->select(['name'])->indexBy('name')->column(),
                [
                    'style' => 'display: none ',
                ]
            )->label('')->hiddenInput();

            ?>

        </div>
        <div class="tree_land">

            <?php
            // echo '<p>Количество комплектов:</p>';
            echo $form->field($model, 'multi_tz')
                ->textInput([
                    'validationDelay' => 5,
                    'readonly' => 'readonly',
                    'disabled' => 'disabled',
                    'style' => "width: 100%;display: inline;"
                ]);

            $date_now = date("d.m.Y H:i:s", strtotime('now'));

            if (empty($model->dt_deadline))
                $model->dt_deadline = $date_now;


            echo $form->field($model, 'dt_deadline')
                ->widget(DateTimePicker::className(), [

                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ],
                    'value' => $date_now,
                    'pluginOptions' => [
                        'pickerPosition' => 'bottom-left',
                        'format' => 'yyyy-mm-dd HH:ii:ss',

                        'autoclose' => true,
                        'weekStart' => 1, //неделя начинается с понедельника
                        'startDate' => $date_now,
                        'todayBtn' => true, //снизу кнопка "сегодня"
                    ]
                ]);
            ?>

        </div>
    </div>

    <div class="pv_motion_create_ok_button">
        <?= Html::Button('&#8593;',
            [
                'class' => 'btn btn-warning',
                'onclick' => "window.history.back();"
            ]);
        ?>

        <?php

        //ddd(Yii::$app->getUser()->identity->id);

//        if( $model['user_create_id'] == Yii::$app->getUser()->identity->id
//        || Yii::$app->getUser()->identity->group_id>=50
//        ){
//            echo Html::submitButton('Сохранить',
//                ['class' => 'btn btn-success']);
//        }
        ?>

        <?php
        //dd($model->status_state);

        if (isset($model->status_state) && $model->status_state > 0) {
            echo "<div>Передано в работу: "
                . date('d.m.Y h:i:s', strtotime($model->status_create_date))
                . "</div>";
        }
        else {


            if (Yii::$app->user->identity->group_id >= 50)

                echo Html::a('Передать в работу', ['/tz_to_work/signal_to_work?id=' . $model->id], ['id' => "tz_to_work", //  //'target' => "_blank",
                    'class' => 'btn btn-warning']);

        }
        ?>




        <?php
        echo Html::a('Печать',
            ['/tz/html_pdf/?id=' . $model->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>

        <?php
        echo Html::a('ТЗ Норма Расхода',
            ['/tz/html_pdf_norma?id=' . $model->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>
        <?php
        echo Html::a('ТЗ Норма Расхода 3.0',
            ['/tz/html_pdf_norma_3?id=' . $model->id],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>





    </div>


    <div class="pv_motion_create_all_new">
        <div class="pv_motion_create_right">

            <?php
            //dd($model);


            echo $form->field($model, 'array_tk_amort')->widget(MultipleInput::className(), [
                'id' => 'my_id2',
                'rowOptions' => [
                    'id' => 'row{multiple_index_my_id2}',
                ],
//                'max' => 25,
                'min' => 0, // should be at least 2 rows
                'allowEmptyList' => true,//false,
                'enableGuessTitle' => true,//false,
                'sortable' => true,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                'columns' => [
                    [
                        'name' => 'wh_tk_amort',
                        'type' => 'dropDownList',
                        'title' => 'Группа',
                        'defaultValue' => 0,
                        'items' => ArrayHelper::map(
                            post_spr_globam::find()->all(),
                            'id', 'name'),

                        'options' => [
                            'prompt' => 'Выбор ...',
                            'id' => 'subcat211-{multiple_index_my_id2}',
                            'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){    
    $("select#subcat22-{multiple_index_my_id2}").html(data);
});
JS
                        ],
                    ],

                    [
                        'name' => 'wh_tk_element',
                        'type' => 'dropDownList',
                        'title' => 'Компонент',
                        'defaultValue' => [],

                        'options' => [
                            'id' => 'subcat22-{multiple_index_my_id2}',
                            'style' => 'min-width: 336px;',
                            'prompt' => '...',

                            'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});                             

$.post("listamort_logic?id=" + $(this).val(), function(data){
   //console.log(data);   
   $("select#tz-array_tk_amort-{multiple_index_my_id2}-intelligent").val(data);
});
JS
                            ,
                        ],
                        'items' =>
                            ArrayHelper::map(
                                post_spr_globam_element::find()
                                    ->orderBy('name')
                                    ->all(),
//                                    postglobalamelement::find()->all(),
                                'id', 'name')
                        ,

                    ],

                    [
//                            'id' => 'subcat22-{multiple_index_my_id2}',
                        'name' => 'intelligent',
                        'title' => 'Штрих',
                        'type' => 'dropDownList',
                        'defaultValue' => 0,
                        'enableError' => true,

                        'value' => (integer)$model->intelligent,
                        'options' => [
                            'style' => 'width:60px;padding: 0px;'

                        ],
                        'items' => [
                            0 => 'Нет',
                            1 => 'Да',
                        ],

                    ],


                    [
                        'name' => 'ed_izmer_num',
                        'title' => 'Кол-во',
                        'defaultValue' => 1,
                        'enableError' => true,
                        'value' => (integer)$model->ed_izmer_num,
                        'options' => [
                            'type' => 'number',
                            'class' => 'input-priority',
                            'step' => '1',
                            'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                        ]
                    ],

                    [
                        'name' => 'tx',
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
            ])->label('Амортизация',['class' => 'my_label'] );


            ?>

        </div>


        <div class="pv_motion_create_right">
            <?php
            echo $form->field($model, 'array_tk')->widget(MultipleInput::className(), [

                'id' => 'my_id',
                'rowOptions' => [
                    'id' => 'row{multiple_index_my_id}',

                ],

//                'max' => 25,
                'min' => 0, // should be at least 2 rows
                'allowEmptyList' => true,//false,
                'enableGuessTitle' => true,//false,
                'sortable' => true,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                'columns' => [
                    [
                        'name' => 'wh_tk',
                        'type' => 'dropDownList',
                        'title' => 'Группа',
                        'defaultValue' => [],
                        //'items' => Spr_glob::find()->select(['name'])->column(),
                        'items' =>
                            ArrayHelper::map(
                                Spr_glob::find()
                                    ->orderBy('name')
                                    ->all(),
                                'id',
                                'name'),

                        'options' => [
                            'prompt' => 'Выбор ...',
                            'id' => 'subcat11-{multiple_index_my_id}',

                            'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    $("select#subcat-{multiple_index_my_id}").html(data);
});
JS
                        ],
                    ],

                    [
                        'name' => 'wh_tk_element',
                        'type' => 'dropDownList',
                        'title' => 'Компонент',
                        'defaultValue' => [],
                        'items' =>
                            ArrayHelper::map(
                                Spr_glob_element::find()->all(),
                                'id', 'name')
                        ,


                        'options' => [
                            'prompt' => '...',
                            'id' => 'subcat-{multiple_index_my_id}',
                            'style' => 'min-width: 336px;',
                            'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_id}").val(data);
});
                            
                            
$.post("list_ed_izm?id=" + $(this).val(), function(data){
    $("select#sklad-array_tk-{multiple_index_my_id}-ed_izmer").val(data);
});
JS
                            ,
                        ]
                    ],


                    [
                        'name' => 'ed_izmer',
                        'title' => 'Ед. изм',
                        'type' => 'dropDownList',
                        'defaultValue' => 1,
                        'options' => [
                            'style' => 'width:60px;padding: 0px;',
                            'id' => "sklad-array_tk-{multiple_index_my_id}-ed_izmer",
                        ],

                        'items' => ArrayHelper::map( Spr_things::find()->all(),'id','name'),


                    ],

                    [
                        'name' => 'ed_izmer_num',
                        'title' => 'Кол-во',
                        'defaultValue' => 1,
                        'enableError' => true,
                        'value' => (integer)$model->ed_izmer_num,
                        'options' => [
                            'type' => 'number',
                            'class' => 'input-priority',
                            'step' => '0.1',
                            'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                        ]
                    ],

                    [
                        'name' => 'tx',
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
            ])->label('Списание',['class' => 'my_label'] );


            ?>

        </div>

        <div class="pv_motion_create_right">
            <?php
            echo $form->field($model, 'array_casual')->widget(MultipleInput::className(), [

                'id' => 'my_casual',
                'rowOptions' => [
                    'id' => 'row{multiple_index_my_casual}',

                ],

//                'max' => 25,
                'min' => 0, // should be at least 2 rows
                'allowEmptyList' => true,//false,
                'enableGuessTitle' => true,//false,
                'sortable' => true,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                'columns' => [
                    [
                        'name' => 'wh_tk',
                        'type' => 'dropDownList',
                        'title' => 'Группа',
                        'defaultValue' => [],
                        //'items' => Spr_glob::find()->select(['name'])->column(),
                        'items' =>
                            ArrayHelper::map(
                                Spr_glob::find()
                                    ->orderBy('name')
                                    ->all(),
                                'id',
                                'name'),

                        'options' => [
                            'prompt' => 'Выбор ...',
                            'id' => 'subcat11_casual-{multiple_index_my_casual}',

                            'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    $("select#subcat_casual-{multiple_index_my_casual}").html(data);
});
JS
                        ],
                    ],

                    [
                        'name' => 'wh_tk_element',
                        'type' => 'dropDownList',
                        'title' => 'Компонент',
                        'defaultValue' => [],
                        'items' =>
                            ArrayHelper::map(
                                Spr_glob_element::find()
                                    ->orderBy('name')
                                    ->all(),
                                'id', 'name')
                        ,


                        'options' => [
                            'prompt' => '...',
                            'id' => 'subcat_casual-{multiple_index_my_casual}',
                            'style' => 'min-width: 336px;',
                            'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11_casual-{multiple_index_my_casual}").val(data);
});
                            
                            
$.post("list_ed_izm?id=" + $(this).val(), function(data){
    $("select#sklad-array_casual-{multiple_index_my_casual}-ed_izmer").val(data);
});
JS
                            ,
                        ]
                    ],


                    [
                        'name' => 'ed_izmer',
                        'title' => 'Ед. изм',
                        'type' => 'dropDownList',
                        'defaultValue' => 1,
                        'options' => [
                            'style' => 'width:60px;padding: 0px;',
                            'id' => "sklad-array_casual-{multiple_index_my_casual}-ed_izmer",
                        ],

                        'items' => ArrayHelper::map( Spr_things::find()->all(),'id','name'),


                    ],

                    [
                        'name' => 'ed_izmer_num',
                        'title' => 'Кол-во',
                        'defaultValue' => 1,
                        'enableError' => true,
                        'value' => (integer)$model->ed_izmer_num,
                        'options' => [
                            'type' => 'number',
                            'class' => 'input-priority',
                            'step' => '0.1',
                            'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                        ]
                    ],

                    [
                        'name' => 'tx',
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
            ])->label('Расходные материалы',['class' => 'my_label'] );

            //  ->label(false);
            ?>

        </div>


    </div>

</div>


<?php ActiveForm::end(); ?>





<?php

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
        		    $('#tz1').html('');
        		    $('#tz1').html(res);
		    },
			error: function( res ) {													    
							    $('#tz1').html('Запрос не вернул значение ');
							    console.log(res);
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

