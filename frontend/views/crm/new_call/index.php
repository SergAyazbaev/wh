<?php

$this->title = 'Открытие заявки OTRS';
$this->params['breadcrumbs'][] = $this->title;

use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\bootstrap\Html;
use yii\web\View;
use yii\widgets\MaskedInput;

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
        width: 230px;
        min-width: 200px;
        background-color: #7d7d7d40;
        font-weight: 500;
        font-stretch: condensed;
        display: block;
        position: fixed;
        left: 5px;
        border-radius: 10px;

        z-index: 99;
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
        'action' => ['/crm/new-call'],

        'options' => [
            //'data-pjax' => 1,
            'autocomplete' => 'off',
        ],

    ]);
?>


<div class="cont1">

    <div class="top_container">
        <h1>Новый звонок</h1>

        <?= $form->field($model, 'caller_id_hide')
            ->widget(MaskedInput::className(), [
                'mask' => '8(999)9999999',
                'class' => 'form-control'
            ])->label(false);
        ?>


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

        <?= $form->field($model, 'nameTechnics')->dropdownList([
            '' => 'Выбрать ...',
            'дисп.Калелова А.А.' => 'техник Калелова А.А.',
            'дисп.Құрманғалиева Ж.Т.' => 'техник Құрманғалиева Ж.Т.',
        ],
            [
                'style' => "width:179px"
            ]
        ); ?>




        <div> Язык абонента <span id="call_lng" style="padding:0 5px;">undefined</span></div>
    </div>
    <div id="loader"
         style=" display: none; ">
    </div>

    <div class="cont1_fio">

        <?= $form->field($model, 'inputPhone')
            ->widget(MaskedInput::className(), [
                'mask' => '89999999999',
            ]);
        ?>


        <?= $form->field($model, 'inputName')->textInput([
            'type' => 'text',
            'style' => "width:300px",
            'placeholder' => "Имя"
        ]); ?>


        <?= $form->field($model, 'needCallBack')->dropdownList(['0' => 'Нет', '1' => 'Да'], ['style' => "width:100px", 'readonly' => "readonly"]); ?>

        <?= $form->field($model, 'inputEmail')->textInput([
            'type' => 'text',
            'style' => "width:300px",
            'placeholder' => "e-mail",
            'readonly' => "readonly"
        ]); ?>

        <?= $form->field($model, 'inputLogin')->textInput([
            'type' => 'text',
            'style' => "width:300px",
            'readonly' => ""
        ]); ?>

        <?= $form->field($model, 'commentArea')->textarea([
            'style' => "width: 505px; margin: 0px 150px 0px 0px; height: 76px;",
            'value' => "."
        ]); ?>


        <label class="lab_r">Дальнейшие действия с заявкой</label>
        <div class="radio">
            <?= $form->field($model, 'optionsRadios')->radio(['label' => 'Сформировать заявку и отправить ответственному', 'value' => 1, 'checked' => true, 'uncheck' => null]) ?>
            <?= $form->field($model, 'optionsRadios')->radio(['label' => 'Заявка решена при звонке', 'value' => 2, 'uncheck' => null]) ?>
            <?= $form->field($model, 'optionsRadios')->radio(['label' => 'Заявка отклонена', 'value' => 3, 'uncheck' => null]) ?>
        </div>


        <?= $form->field($model, 'owner')->dropdownList([
            '' => 'Выбрать ...',
            "22" => 'Техническое обслуживание (вн 0)',
            "25" => 'Вячеслав Бердюгин (вн 0)',
            "28" => 'Денис ТелегинМ (вн ',
            "32" => 'Сергей Кудрявцев (вн 0)',
            "34" => 'Ильяс Джакупов (вн ',
            "37" => 'Оператор Guidejet (вн 0)',
        ],
            [
                'options' => ['37' => ['Selected' => true]],
                'style' => "width:300px"

            ]
        ); ?>


    </div>


    <div class="container">


        <?= $form->field($model, 'inputType')->dropdownList([
            '' => 'Выбрать ...',
            "31" => 'Техническое сопровождение',
            "5" => 'Информация по картам',
            "3" => 'Блокировка карт',
            "8" => 'CityBus',
            "2" => 'Как оформить льготу',
            "25" => 'Информация по проездным билетам',
            "33" => 'Проблемы с платёжными терминалами',
            "24" => 'Пункты реализации',
            "14" => 'Корпоративным клиентам',
            "15" => 'Учебные заведения',
            "29" => 'Социальная защита',
            "4" => 'Инфраструктура',
            "18" => 'Информация по маршрутам',
            "17" => 'Утеря ТК',
            "36" => 'Метрополитен',
            "30" => 'Работа с населением по СМИ',
            "37" => 'Не работает ТК',
            "10" => 'Жалобы на КРУ',
            "12" => 'Жалобы на водителей',
            "13" => 'Консультация',
            "19" => 'Нетранспортные платежи',
            "35" => 'Финансовые операции по МП',
            "32" => 'Терминалы пополнения и покупки Онай',
            "26" => 'Повторная оплата',
            "34" => 'BRT(Скоростной автобусный транспорт)',
            "23" => 'Прочее'
        ],
            [
                'options' => ['31' => ['Selected' => true]],
                'style' => "width:260px"

            ]
        ); ?>





        <?= $form->field($model, 'routeNumber')->textInput([
            'type' => 'text',
            'style' => "width:100px",
            //'readonly' => ""
        ]); ?>



        <!--        // Гос / Борт-->
        <?= $form->field($model, 'gos_bort')->dropdownList([
            '1' => 'Борт',
            '2' => 'Гос',
        ],
            [
                'options' => ['11' => ['Selected' => true]],
                'style' => "width:260px"
            ]
        ); ?>


        <!--        //АП-->
        <?= $form->field($model, 'companyName')->dropdownList([
            $spr_top
        ],
            [
                'style' => "width:260px"
            ]
        ); ?>


        <div class="sel">
        <!--        // Гос номер ПЕ-->
        <?php
        echo $form->field($model, 'stateNumber')->widget(
            Select2::className(), [
            'data' => [],
            'size' => Select2::SMALL,
            'options' => ['placeholder' => 'Выбрать номер  ...', 'autocomplete' => 'off','style' => "width:160px"]
        ]);
        ?>
        </div>

        <!---->
        <!--        --><? //= $form->field($model, 'stateNumber')->dropdownList([
        //            ['' => '...Сначала АвтоПарк']
        //        ],
        //            [
        //                'type' => 'text',
        //                'style' => "width:260px",
        //                'readonly' => ""
        //            ]
        //        ); ?>


        <?= $form->field($model, 'oezap')->dropdownList([
            '' => 'Выбрать ...',
            'Инкассация ПЕ после перестановки маршрута' => 'Инкассация ПЕ после перестановки маршрута',
            'Не работает ПВ' => 'Не работает ПВ',
            'Не работает ПВ и МТТ' => 'Не работает ПВ и МТТ',
            'Не работает ПВ и 1-й терминал' => 'Не работает ПВ и 1-й терминал',
            'Не работает ПВ и 2-й терминал' => 'Не работает ПВ и 2-й терминал',
            'Не работает ПВ и оба терминал' => 'Не работает ПВ и оба терминал',
            'Не работает ПВ, оба терминала и МТТ' => 'Не работает ПВ, оба терминала и МТТ',
            'Не работает МТТ' => 'Не работает МТТ',
            'Не работают МТТ и терминалы' => 'Не работают МТТ и терминалы',
            'Привязка ПВ' => 'Привязка ПВ',
            'На экране "требуется синхронизация"' => 'На экране "требуется синхронизация"',
            'На экране "фатальная ошибка"' => 'На экране "фатальная ошибка"',
            'На экране "заблокирована МСАМ карта"' => 'На экране "заблокирована МСАМ карта"',
            'Умышленная порча оборудования' => 'Умышленная порча оборудования',
            'Отключен 1-й терминал' => 'Отключен 1-й терминал',
            'Отключен 2-й терминал' => 'Отключен 2-й терминал',
            'Отключен 3-й терминал' => 'Отключен 3-й терминал',
            'Отключен 4-й терминал' => 'Отключен 4-й терминал',
            'Отключены оба терминала' => 'Отключены оба терминала',
            'Заблокирован 1-й терминал' => 'Заблокирован 1-й терминал',
            'Заблокирован 2-й терминал' => 'Заблокирован 2-й терминал',
            'Заблокирован 3-й терминал' => 'Заблокирован 3-й терминал',
            'Заблокирован 4-й терминал' => 'Заблокирован 4-й терминал',
            'Терминалы зависли' => 'Терминалы зависли',
            'Заблокированы оба терминала' => 'Заблокированы оба терминала',
            'Ремонт поручня' => 'Ремонт поручня',
            'Микрофон ПВ' => 'Микрофон ПВ',
            'Нет доступа к ПЕ' => 'Нет доступа к ПЕ',
            'Не принимает оплату' => 'Не принимает оплату',
            'ПЕ с долгосрочного ремонта' => 'ПЕ с долгосрочного ремонта',
            'Монтаж АСУОП' => 'Монтаж АСУОП',
            'Демонтаж АСУОП' => 'Демонтаж АСУОП',


        ],
            [
                'options' => ['31' => ['Selected' => true]],
                'style' => "width:260px"

            ]
        ); ?>


        <?= $form->field($model, 'responsible')->textInput([
            'type' => 'text',
            'value' => 'Турганова А.А.',
            'style' => "width:260px",
            'readonly' => ""
        ]); ?>


    </div>

    <?php
    echo Html::submitButton(
        'Создать',
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

