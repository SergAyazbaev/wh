<?php

//use frontend\models\post_spr_globam;
//use frontend\models\Spr_glob;
//use frontend\models\Spr_glob_element;
//use frontend\models\Spr_globam_element;
//use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
//use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use yii\widgets\ActiveForm;

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
        WIDTH: 100%;
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
    // 'method' => 'post',
    'method' => 'POST',

    'options' => [
       // 'data-pjax' => 1,
        'autocomplete' => 'off'
    ],

    'enableClientValidation'    => false,
    'validateOnChange'          => true,
    'validateOnSubmit'          => true,

]);
?>




<div id="tz1" >
    <div class="pv_motion_create_right_center">

        <div class="tree_land3">

            <?php
            echo "<br><h2> Остатки автобусов  </h2>";
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
                        ->orderBy('name')
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
                    'data' =>   ArrayHelper::map(
                            Sprwhelement::find()
                                ->where(['parent_id'=>(integer)$new_doc->wh_destination])
                                ->orderBy('name')
                                ->all()
                            ,'id','name'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'size' => Select2::SMALL, //LARGE,
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


<!--        --><?php
//        echo Html::submitButton('Сохранить изменения в накладной',
//            [
//                'class' => 'btn btn-success',
//                'name' => 'contact-button',
//                'value' => '',
//                'data-confirm' => Yii::t('yii',
//                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'),
//            ]
//        );
//        ?>


        <?php //////////////////////////////////////
        ///
        /// МОДАЛЬНОЕ ОКНО. ИМПУТ под КОПИПАСТ - ПАРК
        ///

        Modal::begin([
            'header' => '<h2>Заливаем копипаст </h2>',
            'toggleButton' => [
                'label' => 'Копипаст. Заливка остатков в ЦС. Оптом',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],//'footer' => 'Низ окна',
        ]);

        ?>


        <?= $form
            ->field($new_doc, 'add_text_to_inventory_am1')
            ->textarea(['autofocus' => true,
                'style'=>'height: 300px;font-size: 10px;',
                'placeholder'=>" Госномер (таб) Наименование оборудования (таб) Штрихкод (таб) Количество (таб) Сумма \n Госномер (таб) Наименование оборудования (таб) Штрихкод (таб) Количество (таб) Сумма \n Госномер (таб) Наименование оборудования (таб) Штрихкод (таб) Количество (таб) Сумма \n " ])
            ->label(false);
        ?>


        <div class="form-group">
            <?= Html::submitButton(  'Добавить ( залить ) копипаст ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'add_button_park'
                ]) ?>

        </div>


        <?php Modal::end();
        ///////////////////////////////////?>


        <?php //////////////////////////////////////
        ///
        /// МОДАЛЬНОЕ ОКНО. ИМПУТ КОПИПАСТ - НОВЫЕ АВТОБУСЫ В ПАРКЕ
        ///

        Modal::begin([
            'header' => '<h2>Копипаст в Спраочник Складов. НОВЫЕ АВТОБУСЫ </h2>',
            'toggleButton' => [
                'label' => 'Копипаст. Добавить новые автобусы в Спраочник Складов',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],//'footer' => 'Низ окна',
        ]);

        ?>


        <?= $form
            ->field($new_doc, 'add_text_to_inventory_am')
            ->textarea(['autofocus' => true,
                'style'=>'height: 300px;font-size: 10px;',
                'placeholder'=>" Автопарк (\таб) Госномер,   [ (\таб) Бортномер, (\таб) VIN, (\таб) Примечание ]  ( \ n) \n Автопарк (\таб) Госномер,   [ (\таб) Бортномер, (\таб) VIN, (\таб) Примечание ]  ( \ n) \n Автопарк (\таб) Госномер,   [ (\таб) Бортномер, (\таб) VIN, (\таб) Примечание ]  ( \ n) \n " ])
            ->label(false);
        ?>


        <div class="form-group">
            <?= Html::submitButton(  'Залить копипаст НОВЫЕ АВТОБУСЫ ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'add_new_bus'
                ]) ?>

        </div>


        <?php Modal::end();
        ///////////////////////////////////?>

        <?php //////////////////////////////////////
        ///
        /// МОДАЛЬНОЕ ОКНО. КОПИПАСТ-УДАЛИТЬ СТОЛБЦОМ ПО ИД
        ///

        Modal::begin([
            'header' => '<h2>Копипаст УДАЛИТЬ СТОЛБЦОМ ПО ИД </h2>',
            'toggleButton' => [
                'label' => 'Копипаст УДАЛИТЬ СТОЛБЦОМ ПО ИД из Спраочника Складов ',
                'tag' => 'button',
                'class' => 'btn btn-primary',
            ],//'footer' => 'Низ окна',
        ]);

        ?>


        <?= $form
            ->field($new_doc, 'add_array_to_delete')
            ->textarea(['autofocus' => true,
                'style'=>'height: 300px;font-size: 10px;',
                'placeholder'=>"ИД\nИД\nИД\n" ])
            ->label(false);
        ?>


        <div class="form-group">
            <?= Html::submitButton(  'УДАЛИТЬ СТОЛБЦОМ ПО ИД ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'delete_bus_id'
                ]) ?>

        </div>


        <?php Modal::end();
        ///////////////////////////////////?>



<!--        --><?php
//        echo Html::a('Ведомость',
//            ['/sklad_inventory/html_reserv_fond/?id=' . $new_doc->id],
//            [
//                'id' => "to_print",
//                'target' => "_blank",
//                'class' => 'btn btn-default'
//            ]);
//        ?>

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

function addProduct() {
    alert(123);
    
 // var x = document.getElementById("fname").value;
 // document.getElementById("demo").innerHTML = x;
  
}


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
		    $('#sklad_inventory-wh_destination_element').html('');
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

