<?php

use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<style>
    .pv_motion_create_right_center, .pv_motion_create_right {
        padding: 3px 0%;
        margin: 0px;
    }

    .pv_motion_create_right {
        overflow: hidden;
        max-width: min-content;
        min-width: max-content;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 10px;
    }

    .pv_motion_create_right > div {
        /*width: 75%;*/
        margin: 0px;
        /*width: max-content;*/
        padding: 0px 8px;
    }

    .itogo {
        width: 300px;
        height: 20px;
        line-height: 1.2;
        padding: 10px 10px;
        background-color: rgba(153, 151, 156, 0.23);
        text-align: right;
    }

    .list-cell__button {
        display: none;
    }

    .modal-vn {
        display: block;
        position: relative;
    }

    .modal-vn > .btn.btn-default {
        float: left;
        margin-right: 10px;
        height: 22px;
        padding: 0;
        background-color: #00ff437d;
        border: 3px solid #2ebf0a5c;
    }


    .redstyle {
        color: rgba(65, 145, 69, 0.9);
        background: rgba(65, 145, 69, 0.15);
    }

    .multiple-input-list__item:hover {
        background: rgba(65, 145, 69, 0.27);
    }

    div > .has-error > .help-block {
        padding: 9px;
        font-size: 21px;
        background-color: #ffd57fd4;
        color: crimson;
        width: 80%;
        left: 10%;
        text-align: center;
    }

    .glyphicon-plus {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;

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
    }


</style>


<?php $form = ActiveForm::begin(
    [
//        'action' => ['#'],

        'id' => 'form_ap' . $id . $last_time,
        //'method' => 'GET',
        'method' => 'POST',
        'options' => [
            'autocomplete' => 'off',
        ],

        'enableClientValidation' => false,
        'enableAjaxValidation' => false,
        'validateOnSubmit' => false,
    ]);
?>

<div class="pv_motion_create_right">
    <h1><?= $wh_name ?> ( до <?= ($last_time_str ? $last_time_str : '') ?>)</h1>

    <input id='input<?= $id . $last_time ?>' type="hidden" value="<?= $wh_name . ' (до ' . $last_time_str . ' )' ?>">

    <div class="excel_buttons">
        <?= Html::submitButton(
            'Excel fast', [
//        'id' => 'excel_print',
                'class' => 'btn btn-default',
            ]
        ) ?>


        <?= Html::a(
            'EXCEL. Свод АП.',
            [
                '/svod/tree_print_ap',
                'id' => $id,
                'date' => $last_time,
                'print' => 1,
            ],
            [
                'class' => 'btn btn-default',
            ]
        ) ?>

        <?= Html::a(
            'EXCEL. Остатки на дату. Список ПЕ. Свод.',
            [
                '/svod/tree_print_pe',
                'id' => $id,
                'date' => $last_time,
                'print' => 1,
            ],
            [
                'class' => 'btn btn-default',
            ]
        ) ?>

    </div>


    <?php
    echo $form->field($model, 'array_tk_amort')->widget(
        MultipleInput::className(), [
            'id' => 'my_id2',
            'theme' => MultipleInput::THEME_DEFAULT, //THEME_BS,
            'allowEmptyList' => true,
            'min' => 0,
            //            'addButtonPosition' => MultipleInput::POS_FOOTER,
            'removeButtonOptions' => ['style' => 'display:none'],


            'columns' => [
                [
                    'name' => 'calc',
                    'title' => '№',
                    'value' => function ($data, $key) {
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
                    'title' => 'Группа',
                    'name' => 'wh_tk_amort_name',
                    'value' => 'wh_tk_element',
                    'options' => [
                        'style' => 'width: 150px;text-align: center',
                        'readonly' => 'readonly',
                    ],
                ],

                [
                    'title' => 'Компонент',
                    'name' => 'wh_tk_element_name',
                    'value' => 'wh_tk_element',
                    'options' => [
                        'style' => 'min-width: 550px;text-align: left',
                        'readonly' => 'readonly',
                    ],
                ],

                [
                    'title' => 'Ед. изм',
                    'name' => 'ed_izmer_name',
                    'value' => 'ed_izmer',
                    'options' => [
                        'style' => 'width:70px;text-align: center',
                        'readonly' => 'readonly',
                    ],
                ],

                [
                    'title' => 'Кол-во',
                    'name' => 'ed_izmer_num',
                    'value' => 'ed_izmer_num',
                    'enableError' => true,
                    'options' => [
                        'style' => 'width:70px;text-align: center',
                        'readonly' => 'readonly',
                    ],
                ],

            ],
        ]
    )->label(false);
    ?>
    <div class="itogo">Итого: <?= $counter_things ?> </div>
</div>


<?php $form = ActiveForm::end(); ?>


<?php
$script = <<<JS

$('form#form_ap'+ $id + $last_time).submit(function() {
        
        
    var array_rez = [] ;
    
    var array_num = $('form#form_ap'+ $id + $last_time).serializeArray();
    // console.log(array_num.length);    
    // console.log(array_num);
    // console.log(array_num[3]['name']);
    
    var x = array_num.length;
    
    while (x > 0){
        x--
        // console.log(x);
               
        var str_name = String(array_num[x]['name']);
        var str_name_vare3 = '';
        var str_name_vare4 = '';

       str_name_vare3 = str_name.replace(/(.*)(\[.*\])(\[.*\])(\[.*\])(.*)$/u,'$3');//!!!![16]        
       str_name_vare3 = str_name_vare3.replace(/(\[)(.*)(\])$/u,'$2');//!!!![16]        
        //console.log(str_name_vare3);

       str_name_vare4 = str_name.replace(/(.*)(\[.*\])(\[.*\])(\[.*\])(.*)$/u,'$4');//!!!![16]
       str_name_vare4 = str_name_vare4.replace(/(\[)(.*)(\])$/u,'$2');//!!!![16]        
        //console.log(str_name_vare4);

       if ( Number.isNaN(str_name_vare3*1) ){
           continue;
       }
       
       array_rez.unshift([
            str_name_vare3*1,
            str_name_vare4,
            array_num[x]['value']
           ]);

    }
    
     //console.log(array_rez);
     
        // преобразуем наши данные JSON в строку    
        var str_json = JSON.stringify(array_rez);
        //console.log(str_json);
 
 
              // адрес, куда мы отправим нашу JSON-строку 
              // let url = "http://mihailmaximov.ru/projects/json/json.php";
               
            //  // создаём новый экземпляр запроса XHR 
            //  let xhr = new XMLHttpRequest(); 
            //  let url = "/svod/print_ap"; 
            //  // открываем соединение 
            //  xhr.open("POST", url, true); 
            //  // устанавливаем заголовок — выбираем тип контента, который отправится на сервер,
            //  // в нашем случае мы явно пишем, что это JSON 
            //  // xhr.setRequestHeader("Content-Type", "application/json"); 
            //  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            //
            //  // когда придёт ответ на наше обращение к серверу, мы его обработаем здесь 
            //  xhr.onreadystatechange = function () { 
            //      // если запрос принят и сервер ответил, что всё в порядке 
            //      if (xhr.readyState === 4 && xhr.status === 200) { 
            //          // выводим то, что ответил нам сервер — так мы убедимся, 
            //          // что данные он получил правильно 
            //          result.innerHTML = this.responseText; 
            //      } 
            //  }; 
            //  // когда всё готово, отправляем JSON на сервер 
            //  xhr.send(str_json);
            //  
            //  console.log(xhr);
  
 
  var strLocation='';
  var whname = $('#input$id$last_time' ).val();
  var rezstr = $('.rezult$id$last_time' ).val();
  
  //alert(whname);
  
  
  
         $.ajax({
               url: '/svod/print_ap', 
               type: 'POST',         
               // contentType: "application/json; charset=utf-8",
               // dataType: "json",      
               dataType: 'text', // тип данных, который вы ожидаете получить от сервера	
               data: {
		              str_json : str_json,
		              whname : whname
		             },
		             
               success: function (data) {
                   // var x = $('#rezult' ).html();
                   // console.log(x);
                                                         
                     // $("#rezult").html('RESULT <a id=123 href="open_xls?str='+data+'" target="_blank">OPEN XLS</a>');
                     // $("a#123").attr('download','download');
                     // $("a#123").ajaxStart();
                     
                     // $("#rezult").html('');
                     // $("#rezult").html(' <a id=123> OPEN1212 XLS </a> ');

                     //var sss= '/OSPanel/domains/wh/frontend/web/assets/reports/';
                     // var sss= '/frontend/web/assets/reports/';
                     // $("a#123").attr('href', sss+data).attr('download','download');
                     // $("a#123").click();
                            console.log('~~~~1~~~~~~', data)
                    
                        // if (data === 'ok')
                        // {
                        //   console.log('~~~~~~~1~~~',data)
                        // }
                        // else
                        // {
                            var link = document.createElement('a');
                            link.setAttribute('href','open_xls?str='+data);
                            link.setAttribute('download','download');
                            link.click();
                            console.log('~~~~2~~~~~~', link)
                            
                            //link.click();
                            // console.log('~~~~2~~~~~~',data)
                        // }
                   },
                      
               error: function( res) {
                      alert('JS err = '+res );
                      // console.log(res);
                    }		                            
               //cache: false,
               // success: function (data) {
               //     alert(data);
               //     // $ e . attr('src', data.url);
               //     // $('body').data(settings.hashKey, [data.hash1, data.hash2]);
               // }
           });
	  
           
         // if (window.ActiveXObject) {
         //     try {
         //            var objExcel;
         //            objExcel = new ActiveXObject("Excel.Application");
         //            objExcel.Visible = true;
         //            objExcel.Workbooks.Open(strLocation, false, [readonly: true|false]);
         //        }
         //        catch (e) {
         //            alert (e.message);
         //        }
         //    }
         //    else {
         //        alert ("Your browser does not support this.");
         //    }
         
	  return false;
	  
});

JS;

$this->registerJs($script, View::POS_READY);


?>

<!--<div id="rezult">Result:</div>-->
