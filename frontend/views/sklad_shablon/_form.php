<?php

use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


?>
<?php  Pjax::begin(); ?>

<?php $form = ActiveForm::begin(); ?>
<div class="pv_motion_create_right">

        <?
        //dd($model);

        echo $form->field($model, 'shablon_name')
            ->textInput();

        echo Html::submitButton('Создать - Сохранить',
            ['class' => 'btn btn-success']);


        echo $form->field($model, 'array_tk_amort')->widget(MultipleInput::className(), [
            'id' => 'my_id2',
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
                    'name'  => 'wh_tk_amort',
                    'type'  => 'dropDownList',
                    'title' => 'Группа',
                    'defaultValue' => 0,
                    'items' => ArrayHelper::map(
                        Spr_globam::find()->all(),
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
                        'style' => 'min-width: 136px;',
                        'prompt' => '...',
                        'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});                     


$.post("listamort_logic?id=" + $(this).val(), function(data){
   $("select#shablon-array_tk_amort-{multiple_index_my_id2}-intelligent").val(data);
             
});
JS
                        ,
                    ],
                    'items' =>
                        ArrayHelper::map(
                            Spr_globam_element::find()->orderBy('parent_id, name')->all(),
                            'id','name')
                    ,

                ],

                [
//                            'id' => 'subcat22-{multiple_index_my_id2}',
                    'name'  => 'intelligent',
                    'title' => 'Штрих',
                    'type'  => 'dropDownList',
                    'defaultValue' => 0,
                    'enableError' => true,

                    'value' =>(integer) $model->intelligent,
                    'options' => [
                        'style' => 'width:60px;padding: 0px;'

//                                'type'  => 'number',
//                                'class' => 'input-priority',
//                                'step'  => '1',
//                                'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
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
                        'style' => 'width:60px;padding: 0px;'
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
                        'step'  => '1',
                        'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                    ]
                ],

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

<?
    echo $form->field($model, 'array_tk')->widget(MultipleInput::className(), [
    'id' => 'my_id',
    'rowOptions' => [
    'id' => 'row{multiple_index_my_id}',
    ],
//    'max'               => 25,
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
            Spr_glob::find()->all(),
        'id','name'),

        'options' => [
        'prompt' => 'Выбор ...',
        'id' => 'subcat211-{multiple_index_my_id}',
        'onchange' => <<< JS
 $.post("list?id=" + $(this).val(), function(data){    
     $("select#subcat22-{multiple_index_my_id}").html(data);
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
        'id' => 'subcat22-{multiple_index_my_id}',
        'style' => 'min-width: 110px;',
        'prompt' => '...',
        'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
        $("select#subcat211-{multiple_index_my_id}").val(data);
  });
    
       
$.post("list_ed_izm?id=" + $(this).val(), function(data){
    $("select#shablon-array_tk-{multiple_index_my_id}-ed_izmer").val(data);    
});
JS

        ,
        ],
        'items' =>
        ArrayHelper::map(
            Spr_glob_element::find()->orderBy('parent_id, name')->all(),
        'id','name')
        ,

    ],



    [
        'name'  => 'ed_izmer',
        'title' => 'Ед. изм',
        'type'  => 'dropDownList',
        'defaultValue' => 1,
        'options' => [
        'style' => 'width:80px;padding: 0px;'
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
        'step'  => '1',
        'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
        ]
    ],

    [
        'name'  => 'tx',
        'title' => 'Комментарии',
        'defaultValue' => '',
        'enableError' => true,
        'options' => [
        'class' => 'input-priority',
        'style' => 'max-width: 70px;overflow: auto;'

        ]
    ]


    ]
    ]);
    //->label(false);

    ?>



</div>



<?php ActiveForm::end(); ?>


<?php

$script = <<<JS
$(document).on('keypress',function(e) {
    if(e.which == 13) {
          e.preventDefault();
          return false;
    }
});



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
						alert('JS.sklad-wh_destination '+res );
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
						alert('JS.sklad-wh_destination '+ res );
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






////////////
$('#go_home').click(function() {    
    window.history.back();  
})

     var  number = $('#sklad-sklad_vid_oper').val();
     var  text   = $('#sklad-sklad_vid_oper>option[value='+number+']').text();
     $('#sklad-sklad_vid_oper_name').val(text);

     var  number = $('#sklad-wh_debet_top').val();
     var  text   = $('#sklad-wh_debet_top>option[value='+number+']').text();
      $('#sklad-wh_debet_name').val(text);
     
     var  number = $('#sklad-wh_destination').val();
     var  text   = $('#sklad-wh_destination>option[value='+number+']').text();
      $('#sklad-wh_destination_name').val(text);     


     // var  number = $('#sklad-wh_debet_element').val();
     // var  text   = $('#sklad-wh_debet_element>option[value='+number+']').text();
     //  $('#sklad-wh_debet_element_name').val(text);
      
     // var  number2 = $('#sklad-wh_destination_element').val();     
     // var  text2   = $('#sklad-wh_destination_element>option[value='+number2+']').text();
     //  $('#sklad-wh_destination_element_name').val(text2);


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>



<?php  Pjax::end(); ?>