<?php


use frontend\models\post_spr_glob_element;
use frontend\models\post_spr_globam;
use frontend\models\post_spr_globam_element;
use frontend\models\Spr_glob;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;


Pjax::begin([    'id' => 'pjax-container', ]);
    echo yii::$app->request->get('page');


?>


<style>
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


    /*@media(max-width:600px) {*/
        /*.tree_land, .tree_land2 {*/
            /*width: 95%;*/
        /*}*/
    /*}*/

</style>






<?php $form = ActiveForm::begin(); ?>

<!--<div id="tz1">    -->
<?php
if ( isset( $id ) )   // Tz - id
    $model->id = $id;
?>

<!--    </div>-->






<div class="pv_motion_create_right_center">

    <div class="tree_land2">

        <?php
        //Создано по ТехЗаданию
        echo "<h2>(".$sklad.") TZ<br> Накладная № ".$new_doc->id ."</h2>";

        ?>
    </div>


<!--    <div class="tree_land">-->


    <?php

//            echo $form->field($new_doc, 'sklad_vid_oper')->dropdownList(
//                [
//                    '1'=>'Инвентаризация',
//                    '2'=>'Приходная накладная',
//                    '3'=>'Расходная накладная',
//                ],
//                [
//                    'prompt' => 'Выбор ...',
//                    //'disabled' => 'disabled',
//                    // 'options' => [ 3 => ['selected'=>'selected']],
//                ]
//            )->label("Вид операции");


//           echo "<div class='close_hid'>";
//            //  $new_doc->sklad_vid_oper_name="Расходная накладная";
//
//                echo $form->field($new_doc, 'sklad_vid_oper_name')
//                    ->hiddenInput()->label(false);
//            echo "</div>";

            ?>

<!--    </div>-->

    <div class="tree_land">
        <?php
            //////////
            echo $form->field($new_doc, 'wh_debet_top')->dropdownList(
                ArrayHelper::map(
                    Sprwhtop::find()->all(),
                    'id','name'),
                [
                    'prompt' => 'Выбор склада ...',
                    //'disabled' => 'disabled',
                ]
            )->label("Склад-источник");


            echo $form->field($new_doc, 'wh_debet_element')->dropdownList(
                ArrayHelper::map(
                    Sprwhelement::find()
                        ->where(['parent_id'=>(integer)$new_doc->wh_debet_top])
                        ->all(),'id','name'),
                [
                    'prompt' => 'Выбор склада ...',
                   // 'disabled' => 'disabled',
                ]
            );


        echo "<div class='close_hid'>";
            echo $form->field($new_doc, 'wh_debet_name');
                //->hiddenInput()->label(false);
            echo $form->field($new_doc, 'wh_debet_element_name');
                //->hiddenInput()->label(false);
        echo "</div>";

        ?>
    </div>


    <div class="tree_land">
        <?php
            //////////

            echo $form->field($new_doc, 'wh_destination')->dropdownList(
                ArrayHelper::map(
                    Sprwhtop::find()->all(),
                    'id','name'),
                [
                    'prompt' => 'Выбор склада ...',
                    //'disabled' => 'disabled',
                ]
            )->label("Склад-приемник");

            echo $form->field($new_doc, 'wh_destination_element')->dropdownList(
                ArrayHelper::map(Sprwhelement::find()
                    ->where(['parent_id'=>(integer)$new_doc->wh_destination])
                    ->all(),'id','name'),
                [
                    'prompt' => 'Выбор склада ...',
                    //'disabled' => 'disabled',

                ]
            );

        echo "<div class='close_hid'>";
            echo $form->field($new_doc, 'wh_destination_name')
                ->hiddenInput()->label(false);
            echo $form->field($new_doc, 'wh_destination_element_name')
                ->hiddenInput()->label(false);
        echo "</div>";

        ?>
    </div>



</div>




<div class="pv_motion_create_ok_button">

            <?= Html::Button('&#8593;',
                [
                    'class' => 'btn btn-warning',
                    'onclick'=>"window.history.back();"
                ]);
            ?>

            <?php
            echo Html::submitButton('Создать накладную по этому шаблону',
                ['class' => 'btn btn-success']
            );
            ?>

</div>




<div class="pv_motion_create_right">

    <?php
        echo $form->field($new_doc, 'array_tk_amort')->widget(MultipleInput::className(), [
            'id' => 'my_id2',
            'theme'  => 'default',
            'rowOptions' => [
                'id' => 'row{multiple_index_my_id2}',
            ],

//            'max'               => 25,
            'min'               => 0, // should be at least 2 rows
            'allowEmptyList'    => true ,//false,
            'enableGuessTitle'  => true ,//false,
            'sortable'  => true,
            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

            'columns' => [
                [
                    'name' => 'calc',
                    'title' => '№',
                    'value' => function($data, $key) {
                        return ++$key['index'];
                    },

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'style' => 'width: 40px;',
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ],
                ],
                [
                    'name'  => 'wh_tk_amort',
                    'type'  => 'dropDownList',
                    'title' => 'Группа',
                    'defaultValue' => 0,
                    'items' => ArrayHelper::map(
                        post_spr_globam::find()->all(),
                        'id','name'),

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'id' => 'subcat211-{multiple_index_my_id2}',
                        'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
    console.log(data);
    $("select#subcat223-{multiple_index_my_id2}").html(data);
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
                        'id' => 'subcat223-{multiple_index_my_id2}',
                        'prompt' => 'Выбор ...',
                        'style' => 'padding: 0px;min-width:2vw;overflow: auto;',
                        'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});                    
        
$.post("listamort_logic?id=" + $(this).val(), function(data){   
   $("select#sklad-array_tk_amort-{multiple_index_my_id2}-intelligent").val(data);
});                                

JS
 ,
                        'step'  => '1',
                    ],


                    'items' =>
                        ArrayHelper::map(
                            post_spr_globam_element::find()->orderBy('parent_id, name')->all(),
//                            postglobalamelement::find()->all(),
                            'id','name')
                    ,

                ],


                [
                    'name'  => 'intelligent',
                    'title' => ' ШКод',
                    'type'  => 'dropDownList',
                    'defaultValue' => 0,
                    'enableError' => true,
                    'options' => [
                        'style' => 'min-width: 60px;'
                    ],
                    'value' =>(integer) $model->intelligent,
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
                        'id' => 'subcat-ed_izm-{multiple_index_my_id2}',
                        'style' => 'min-width: 80px;'
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


            ]
        ]);

        //->label(false);

        ?>
</div>

<div class="pv_motion_create_right">

    <?php
//        dd($new_doc);
        //dd(empty($new_doc->array_tk));

        if (isset($new_doc->array_tk) && !empty($new_doc->array_tk))
            echo $form->field($new_doc, 'array_tk')->widget(MultipleInput::className(), [

                'id' => 'my_id',
                'theme'  => 'default',
                'rowOptions' => [
                    'id' => 'row{multiple_index_my_id}',

                ],


//                'max'               => 25,
                'min'               => 0, // should be at least 2 rows
                'allowEmptyList'    => true ,//false,
                'enableGuessTitle'  => true ,//false,
                'sortable'  => true,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                'columns' => [
                    [
                        'name' => 'calc',
                        'title' => '№',
                        'value' => function($data, $key) {
                            return ++$key['index'];
                        },

                        'options' => [
                            'prompt' => 'Выбор ...',
                            'style' => 'width: 40px;',
                            'readonly' => 'readonly',
                            'disabled' => 'disabled',
                        ],
                    ],

                    [

                        'name'  => 'wh_tk',
                        'value' => 'wh_tk',
                        'type'  => 'dropDownList',
                        'title' => 'Группа',
//                        'defaultValue' => [],
                        'items' =>
                            ArrayHelper::map(
                                Spr_glob::find()->all(),
                                'id',
                                'name'),

                        'options' => [
                            'prompt' => 'Выбор ...',
                            'style' => 'min-width: 20px;',
                            'id' => 'subcat111-{multiple_index_my_id}',
                            'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    //console.log(data);
    $("select#subcat1112-{multiple_index_my_id}").html(data);
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
                                post_spr_glob_element::find()->all(),
                                'id','name')
                        ,
                        'options' => [
                            'prompt' => '...',
                            'id' => 'subcat1112-{multiple_index_my_id}',
                            'style' => 'min-width: 100px;',
                            'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat111-{multiple_index_my_id}").val(data);
});
JS
,
                        ]
                    ],

                    [
                        'name'  => 'ed_izmer',
                        'title' => 'Ед. изм',
                        'type'  => 'dropDownList',
                        'defaultValue' => 1,
                        'options' => [
                            'style' => 'min-width: 66px;'
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



                ]
            ]) ;
        //  ->label(false);
        ?>

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

////////////////// VID - sklad-sklad_vid_oper
//$('#sklad-sklad_vid_oper').change(function() {    
//     var  number22 = $('#sklad-sklad_vid_oper').val();
//     var  text22   = $('#sklad-sklad_vid_oper>option[value='+number22+']').text();
//      $('#sklad-sklad_vid_oper_name').val(text22);
//});




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





// $( "form" ).submit(function( event ) {
 $( "form" ).submit(function(  ) {
  
    var  number1  = $('#sklad-wh_debet_top').val();
    var  text1  = $('#sklad-wh_debet_top>option[value='+number1+']').text();    
     $('#sklad-wh_debet_name').val(text1);
     
    var  number2  = $('#sklad-wh_debet_element').val();
    var  text2  = $('#sklad-wh_debet_element>option[value='+number2+']').text();    
     $('#sklad-wh_debet_element_name').val(text2);
    //------------------
    
     var  number3  = $('#sklad-wh_destination').val();
     var  text3  = $('#sklad-wh_destination>option[value='+number3+']').text();    
      $('#sklad-wh_destination_name').val(text3);
      

    var  number4  = $('#sklad-wh_destination_element').val();
    var  text4  = $('#sklad-wh_destination_element>option[value='+number4+']').text();    
     $('#sklad-wh_destination_element_name').val(text4);
    // ------------------
     
    // alert(text2);
    
  // event.preventDefault();
});




$('#go_home').click(function() {    
    window.history.back();  
})


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>



<?php ActiveForm::end();

Pjax::end();
?>

