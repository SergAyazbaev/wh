<?php

//	use frontend\models\Sprwhelement;
//	use frontend\models\Sprwhtop;
	use kartik\datetime\DateTimePicker;
	use yii\bootstrap\Alert;
//	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;


?>


<style>
    div > .has-error > .help-block {
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

    .glyphicon-plus {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;

        /*padding: 7px;*/
        /*margin: -8px;*/
        font-size: medium;
        font-weight: bold;
        border-radius: 25px;
    }

    .glyphicon-plus:hover {
        color: rgba(255, 255, 255, 0.53);
        background-color: #5cb85c;
        border-color: #4cae4c;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);

    }

    /*.help-block {*/

    /*}*/
    .help-block-error {
        display: none;
    }

    thead th {
        height: 22px;
        padding: 10px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    table tr, table td, {
        border: 0px;
        margin: 0px;
        width: 100%;
    }

    thead tr, thead td {
        background-color: rgba(11, 147, 213, 0.12);
        padding: 5px 10px;
        margin: 0px;
        padding: 10px;
    }

    tbody tr, tbody td {
        padding: 1px 3px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    td div select {
        /*background-color: rgba(65, 255, 61, 0.24);*/
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        width: 100%;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }

    td div input {
        background-color: rgba(35, 216, 57, 0.21);
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }


    .list-cell__button {
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


    .pv_motion_create_ok_button {
        /*display: block;*/
        /*position: inherit;*/
        /*padding: 10px 18px;*/
        /*float: left;*/
        width: 100%;
        /*margin: 5px;*/
        /*background-color: #48616121;*/
        /*}*/

        /*.btn{*/
        /*border: 2px solid #2aff00;*/
        /*margin: 5px 1px;*/
    }


    @media (max-width: 780px) {
        .scroll_window {

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


        .pv_motion_create_ok_button {
            display: block;
            position: inherit;
            padding: 10px 18px;
            float: left;
            width: 100%;
            background-color: #48616121;
            /*margin: 5px;*/
            margin-top: 5px;
            background-color: #48616121;
        }

    }
</style>


<style>
    .pv_motion_create_right_center {
        width: 100%;
        min-width: 240px;
        margin: 0px;
        padding: 3px 3px;
    }

    @media (min-width: 605px) {
        .pv_motion_create_right_center {
            padding: 3px 3px;
        }
    }

    @media (min-width: 1214px) {
        .pv_motion_create_right_center {
            padding: 3px 10px;
        }
    }


</style>


<?php $form = ActiveForm::begin(
	[
		'id'     => 'project-form',
		//    'action' => ['index'],
		//    'method' => 'post',
		//    'method' => 'GET',
		'method' => 'POST',

		'options' => [
			'data-pjax'    => 1,
			'autocomplete' => 'off',
		],

		'enableAjaxValidation'   => false,
		'enableClientValidation' => true,
		//false,
		'validateOnChange'       => true,
		//false,
		'validateOnSubmit'       => true,
		//    'validateOnBlur'            => true,//false,false,

	] );
?>


<?php //$form = ActiveForm::begin([
	//    'enableAjaxValidation'      => false, //!!!
	//    'enableClientValidation'    => true,
	//    'validateOnChange'          => true,
	//    'validateOnSubmit'          => true,
	////    'validateOnBlur'            => true,//false,false,
	//
	//    'action' => 'create_new',
	////    'method' => 'get',
	//    'method' => 'post',
	//
	//    'options' => [
	//        //'class' => 'form-horizontal',
	//    ]
	//]);
	//?>


<div id="tz1">
    <div class="pv_motion_create_right_center">

        <div class="tree_land3">
	        <?php
		        echo "<br>склад " . $sklad . "<br><h2> Накладная № " . $model->id . "</h2>";
	        ?>

	        <?php
		        ///////////
		        if(empty( $model[ 'array_bus' ] ))
		        {
			        $sum_bus = 0;
		        } else
		        {
			        $sum_bus = count( $model[ 'array_bus' ] );
		        }

		        ///////////

	        ?>


        </div>

        <div class="tree_land">

	        <?php

		        $model->dt_create =
			        date( 'd.m.Y H:i:s', strtotime( $model[ 'dt_create' ] ) );

		        echo $form->field( $model, 'dt_create' )
		                  ->widget(
			                  DateTimePicker::className(), [
			                  'name'          => 'dp_1',
			                  'type'          => DateTimePicker::TYPE_INPUT,
			                  //TYPE_INLINE,
			                  'size'          => 'lg',
			                  'convertFormat' => false,
			                  'options'       => [
				                  'placeholder' => 'Ввод даты/времени...',
			                  ],

			                  'pluginOptions' => [
				                  'format'         => 'dd.mm.yyyy HH:ii:ss',
				                  'pickerPosition' => 'bottom-left',

				                  'autoclose' => true,
				                  'weekStart' => 1,
				                  //неделя начинается с понедельника
				                  // 'startDate' => $date_now,
				                  'todayBtn'  => true,
				                  //снизу кнопка "сегодня"
			                  ],
		                  ] );
	        ?>


	        <?php
		        echo $form->field( $model, 'sklad_vid_oper' )
		                  ->dropDownList(
			                  [
				                  '1' => 'Инвентаризация',
				                  '2' => 'Приходная накладная',
				                  '3' => 'Расходная накладная',
			                  ],
			                  [
				                  'prompt'       => 'Выбор ...',
				                  'data-confirm' => Yii::t(
					                  'yii',
					                  '<b>Вы точно хотите: </b><br>
                                    Изменить НАПРАВЛЕНИЕ этой НАКЛАДНОЙ ?' ),
			                  ]
		                  )
		                  ->label( "Вид операции" );

		        echo "<div class='close_hid'>";
		        echo $form->field( $model, 'sklad_vid_oper_name' )
		                  ->hiddenInput()->label( false );
		        echo "</div>";
	        ?>

        </div>


        <!--        <div class="tree_land">-->
        <!--	        --><?php
        //		        //////////
        //		        echo $form->field( $model, 'wh_debet_top' )->dropDownList(
        //			        ArrayHelper::map(
        //                        Sprwhtop::find()
        //                            ->orderBy( 'name' )
        //                            ->all(),
        //				        'id', 'name' ),
        //			        [
        //				        'prompt' => 'Выбор ...',
        //				        //'id' => 'wh_debet_top',
        //
        //			        ]
        //		        )->label( "Компания-отправитель" );
        //
        //
        //		        echo $form->field( $model, 'wh_debet_element' )->dropDownList(
        //			        ArrayHelper::map(
        //				        Sprwhelement::find()
        //				                    ->where( [ 'parent_id' => (integer) $model->wh_debet_top ] )
        //                                    ->orderBy( 'name DESC' )
        //				                    ->all(), 'id', 'name' ),
        //			        [
        //				        'prompt' => 'Выбор  ...',
        //				        //'id' => 'wh_debet_element',
        //			        ]
        //		        );
        //
        //		        echo "<div class='close_hid'>";
        //		        echo $form->field( $model, 'wh_debet_name' )
        //		                  ->hiddenInput()->label( false );
        //		        echo $form->field( $model, 'wh_debet_element_name' )
        //		                  ->hiddenInput()->label( false );
        //		        echo "</div>";
        //	        ?>
        <!--        </div>-->
        <!---->
        <!--        <div class="tree_land">-->
        <!--		    --><?php
        //
        //			    //////////
        //			    echo $form->field( $model, 'wh_destination' )->dropDownList(
        //				    ArrayHelper::map(
        //                        Sprwhtop::find()
        //                            ->orderBy( 'name' )
        //                            ->all(),
        //					    'id', 'name' ),
        //				    [
        //					    'prompt' => 'Выбор склада ...',
        //				    ]
        //			    )->label( "Компания-получатель" );
        //
        //			    echo $form->field( $model, 'wh_destination_element' )->dropDownList(
        //				    ArrayHelper::map(
        //					    Sprwhelement::find()
        //					                ->where( [ 'parent_id' => (integer) $model->wh_destination ] )
        //                            ->orderBy( 'name' )
        //					                ->all(), 'id', 'name' ),
        //				    [
        //					    'prompt' => 'Выбор склада ...',
        //					    //'options' => [ $model->wh_destination => ['selected'=>'selected']],
        //				    ]
        //			    );
        //
        //			    echo "<div class='close_hid'>";
        //			    echo $form->field( $model, 'wh_destination_name' )
        //			              ->hiddenInput()->label( false );
        //			    echo $form->field( $model, 'wh_destination_element_name' )
        //			              ->hiddenInput()->label( false );
        //			    echo "</div>";
        //
        //		    ?>
        <!---->
        <!--        </div>-->

    </div>


    <div class="pv_motion_create_ok_button">


	    <?php
		    ////////// ALERT

		    if(isset( $alert_mess ) && !empty( $alert_mess ))
		    {
			    echo Alert::widget(
				    [
					    'options' => [
						    'class'     => 'alert_save',
						    'animation' => "slide-from-top",
					    ],
					    'body'    => $alert_mess,
				    ] );
		    }

		    ////////// ALERT
	    ?>


	    <?php
		    echo Html::a(
			    'Выход', [ 'sklad/in/' ], [
			    'onclick'=>"window.history.back();",
			    'class'   => 'btn btn-warning',
		    ] );
	    ?>


	    <?php
		    echo Html::submitButton(
			    'Создать НАКЛАДНУЮ',
			    [
				    'class'        => 'btn btn-success',
				    'data-confirm' => Yii::t(
					    'yii',
					    'СОХРАНЯЕМ НАКЛАДНУЮ ?' ),

				    'name'  => 'contact-button',
				    'value' => 'create_new',
			    ]
		    );
	    ?>

    </div>


    <div class="pv_motion_create_right">

    </div>


    <div class="pv_motion_create_right">

    </div>


    <div class="pv_motion_create_right">

    </div>
</div>


<?php ActiveForm::end(); ?>


<?php
	$script = <<<JS
//////////////////// CLOSE --ALERT--
$(document).ready(function() {
    $('.alert_save').fadeOut(2500); // плавно скрываем окно временных сообщений
});


//////////////////// ENTER
$(document).on('keypress',function(e) {
    if(e.which == 13) {
        // alert('Нажал - enter!');
      e.preventDefault();
      return false;
    }
});



//////////////////// Debitor -top
$('#sklad-wh_debet_top').change(function() {
        
    var  number = $(this).val();
    var  text = $('#sklad-wh_debet_top>option[value='+number+']').text();    
    $('#sklad-wh_debet_name').val(text) ;
    
    
    // alert($('#sklad-wh_debet_name').val());

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
						alert('JS.sklad-wh_destination '+res );
						console.log(res);
					}
    } );
    
    
    
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
		//    $('#sklad-wh_destination_element').html('');
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

JS;

	$this->registerJs( $script, yii\web\View::POS_READY );
?>

