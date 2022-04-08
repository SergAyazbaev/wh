<?php

$this->title = 'Открытие заявки OTRS';
$this->params['breadcrumbs'][] = $this->title;


use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\bootstrap\Html;
use yii\web\View;


?>

<style>
    .row {
        width: 37%;
        float: left;
        display: block;
        /* background-color: aquamarine; */
        /* border: 2px solid; */
        padding: 8px 60px;
    }


    div.cont1 {
        /*background-color: #0b93d5;*/
        width: 70%;
        position: relative;
        margin-left: 15%;
    }

    div.cont1_fio {
        display: inline-block;
        /* position: static; */
        /* left: 2%; */
        width: 581px;
        /* border: 2px solid; */
        background-color: #2b908f66;
        margin: 10px;
        padding: 25px 25px;
        min-width: 575px;
        float: left;
    }


    @media (max-width: 1500px) {
        div.cont1 {
            /*background-color: #0b93d5;*/
            width: 70%;
            position: relative;
            margin-left: 30%;
        }
    }


    div.cont1 > div.container, div.top_container {
        display: table-cell;
        position: static;
        left: 2%;
        margin-top: 0px;
        width: 500px;
        /* border: 2px solid; */
        background-color: aliceblue;
        margin-bottom: 10px;
        padding: 25px 25px;
        min-width: unset;
    }

    div.top_container {
        display: block;
        position: fixed;
        left: 20%;
        top: 10%;

        width: 20%;
        min-width: 200px;
        background-color: #7d7d7d40;
        font-weight: 500;
        /*font-stretch: condensed;*/
        border-radius: 10px;

        z-index: 99;
        padding: 50px;
    }

    .form-group > input {
        font-family: "Helvetica Neue Light", "HelveticaNeue-Light", "Helvetica Neue", Calibri, Helvetica, Arial, sans-serif;
        font-weight: 800;
        font-stretch: condensed;
        font-size: x-large;
        width: 100%;
    }

    div.cont1_fio > .form-group > input {
        width: 40%;
    }


    .form-horizontal {
        /*background-color: aqua;*/
        padding: 23px 35px;
    }

    .form-group > label {
        /*background-color: aqua;*/
        width: 30%;
        min-width: 100px;
        float: left;
    }

    .lab_r {
        /*background-color: #0b93d5;*/
        line-height: 0.3;
        font-size: large;
    }

    div.radio {
        /*background-color: #7d7d7d;*/
        padding: 0;
        margin: 0;
    }

    div.radio > div.form-group {
        /*background-color: #00aa88;*/
        padding: 0;
        margin-left: 150px;
    }

    .alert_save {
        display: block;
        position: absolute;
        padding: 37px 170px;
        left: 17%;
        top: 50%;
        color: darkgreen;
        font-weight: bold;
        font-size: 38px;
        background-color: rgba(222, 184, 135, 1);
        z-index: 99;
    }


    div.sel {
        width: 260px;
        margin-left: 136px;
    }
</style>

<body>


<?php
// $alert_mess  MESSAGE
if (isset($alert_mess) && !empty($alert_mess)) {
    echo Alert::widget(
        [
            'options' => [
                'class' => 'alert_save',
                'animation' => "slide-from-top",
            ],
            'body' => $alert_mess,
        ]);
}
?>


<div id="err"></div>
<div id="added_task"></div>


<?php
$form = ActiveForm::begin(
    [
        'id' => 'project-form',
        'method' => 'POST',
        'class' => 'form-inline',
        'action' => ['/crm/ticket-close'],

        'options' => [
            //'data-pjax' => 1,
            'autocomplete' => 'off',
        ],

    ]);
?>


<div class="cont1">

    <div class="top_container">
<!--        <h1>Закрыть заявку</h1>-->


        <?= $form->field($model, 'id')->dropdownList([
            ['' => 'Выбрать ...'] + $ticket_ids
        ],
            [
                'style' => "width:179px"
            ]
        ); ?>


        <?= $form->field($model, 'ticket_state_id')->dropdownList([
            '' => 'Выбрать ...',
            $ticket_state_ids
        ],
            [
                'style' => "width:179px"
            ]
        ); ?>


        <?= $form->field($model, 'opername')->dropdownList([
            '' => 'Выбрать ...',
            'дисп.Калелова А.А.' => 'дисп.Калелова А.А.',
            'дисп.Құрманғалиева Ж.Т.' => 'дисп.Құрманғалиева Ж.Т.',
            'дисп.Орынбекова А.Б.' => 'дисп.Орынбекова А.Б.',
            'дисп.Туткишбаева С.М.' => 'дисп.Туткишбаева С.М.',
            'дисп.Шакарова Г.С.' => 'дисп.Шакарова Г.С.',
        ],
            [
                'style' => "width:179px"
            ]
        ); ?>




    <?php
    echo Html::submitButton(
        'Закрыть Заявку',
        [
            'name' => 'button-save',
            'value' => 'save_button',

            'class' => 'btn btn-success',
            'data-confirm' => Yii::t(
                'yii',
                'СОХРАНЯЕМ НАКЛАДНУЮ ?'),
        ]
    );
    ?>

</div>

<?php ActiveForm::end(); ////////////////////////        ?>




<?php
$script = <<<JS
    
$(document).ready(function() {
    $( ".pv_motion_create_ok_button").show();
    $('.alert_save').fadeOut(3500); // плавно скрываем окно временных сообщений
});


$(document).on('keypress',function(e) {
    if(e.which == 13) {
        // alert('Нажал - enter!');
      e.preventDefault();
      return false;
    }
});



//////////////////// Debitor -top
$('#crm-gos_bort').change(function() {
                            
    $('#crm-companyname').val('');		    
    $('#crm-statenumber').attr('readonly', true).html('');		    

        
});



//////////////////// Debitor -top
$('#crm-companyname').change(function() {        
    var  number = $(this).val();
    var  text = $('#crm-companyname>option[value='+number+']').text();    
    $('#sklad-wh_debet_name').val(text) ;
        
    // alert($('#sklad-wh_debet_name').val());

     // var  number2 = $('#sklad-wh_debet_element').val();
     // var  text2   = $('#sklad-wh_debet_element>option[value='+number2+']').text();
     //  $('#sklad-wh_debet_element_name').val(text2);    
     
     var  gos_bort = $('#crm-gos_bort').val();
     //alert(gos_bort);
     
     if(gos_bort==1){
            $.ajax( {
                url: '/crm/list-element-bort',
                data: {
                    id :number
                },		
                    success: function(res) {		 
                            $('#crm-statenumber').attr('readonly', false).html(res);		    
                            },
                    error: function( res) {
                                alert('JS.crm-statenumber '+res );
                                console.log(res);
                            }
            } );
    }

     if(gos_bort==2){
            $.ajax( {
                url: '/crm/list-element-gos',
                data: {
                    id :number
                },		
                    success: function(res) {		 
                            $('#crm-statenumber').attr('readonly', false).html(res);		    
                            },
                    error: function( res) {
                                alert('JS.crm-statenumber '+res );
                                console.log(res);
                            }
            } );
    }
        
});



////////////
$('#go_home').click(function() {    
    window.history.back();
})

JS;

$this->registerJs($script, View::POS_READY);
?>

