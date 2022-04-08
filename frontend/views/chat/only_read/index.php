<style>
    .wrap_clients {
        display: none;
        position: fixed;
        height: 300px;
        bottom: 150px;
        right: 10px;
        width: 320px;
        background-color: rgb(231, 247, 255);
        padding: 10px;
        border-radius: 10px;
        border: 2px solid #7777ee30;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
    }

    .chat_clients {
        overflow-x: hidden;
        height: 100%;
        width: 100%;
    }

    .chat_client_online {
        cursor: help;
        display: block;
        position: initial;
        width: 100%;
        float: left;
        background-color: rgb(205, 235, 251);
        border-radius: 10px;
        padding: 2px 15px;
        margin: 1px;
    }

    .chat_t {
        font-weight: bolder;
        font-size: 14px;
    }

    .chat_close {
        cursor: pointer;
        display: block;
        position: fixed;
        background-color: palegreen;
        padding: 0px 6px;
        border: 2px solid #5cb85c;
        border-radius: 20px;
        height: 25px;
        width: 25px;
        margin-top: -10px;
        margin-left: -10px;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
    }

    .answer_form {
        display: none;
        position: fixed;
        height: 150px;
        bottom: 110px;
        right: 10px;
        width: 320px;
        background-color: rgb(231, 247, 255);
        padding: 10px;
        border-radius: 10px;
        border: 2px solid #7777ee30;

        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
    }

    .chat_listing_wrap {
        display: none;
        position: fixed;

        background-color: #f2f8fb;
        width: 400px;
        height: 400px;
        padding: 20px;

        height: 300px;
        bottom: 270px;
        right: 13px;
        width: 317px;

        padding: 10px;
        border-radius: 10px;
        border: 2px solid #7777ee30;

        box-shadow: -1px 3px 20px 0px rgba(0, 0, 0, 0.03), 0 6px 20px 0 rgba(0, 0, 0, 0.1);

    }

    .chat_listing {
        display: block;
        position: relative;
        height: 99%;
        height: 274px;
    }

    #run_button {
        color: black;
        background-color: rgba(212, 217, 187, 0.91);
        width: 30px;
        height: 30px;
        font-size: larger;
        display: block;
        position: fixed;
        right: 170px;
        bottom: 35px;
        border-radius: 20px;
    }
</style>

<div class="wrap_clients">
    <div class="chat_close" id="chat_ungle_close">X</div>
    <div class="chat_clients">
        <!--    <div class="chat_client_online">Начало</div>-->
    </div>
</div>

<div class="chat_listing_wrap">
    <div class="chat_listing">
    </div>
</div>

<div class="answer_form">
    <form id="w11" name="messages_form" action="/chat/client" method="post" autocomplete="off">
        <div class="answer_into">
            Форма отправки сообщений подгружается....

        </div>
        <button type="submit" class="btn btn-success">Отправить</button>
        <button id="chat_close_func" class="btn btn-danger">Закрыть</button>
    </form>
</div>

<?php
//$script = <<<JS
//
//
//     window.onload = function () {
//        
//         var socket = new WebSocket('ws://79.143.22.33:8809');  /// OK !!!!        
//         // var socket = new WebSocket('ws://10.0.0.151:8081'); /// ok
//         // var socket = new WebSocket('ws://localhost:8081'); /// ok localhost for HOME=10.0.0.64
//        
//         // var socket = new WebSocket('ws://localhost:8809'); /// ok
//
//         //
//         var status = document.getElementById('status_chat');
//        
//
//         ///
//         socket.onopen = function (event) {            
//             status.innerHTML = '<a href="/chat/client"> чат на связи </a>';
//            
//             $('div#run_button').on('click',function() {                
//                 open_users();
//                 return false;  
//             });         
//         };
//
//         ///
//         socket.onclose = function (event) {
//             if (event.wasClean) {
//                 status.innerHTML = 'чат закрыт';
//             } else {
//                 status.innerHTML = 'сервер-чат';
//             }
//         };
//        
//         ///
//         socket.onmessage = function (event) {
//             let mess = JSON.parse(event.data);
//            
//                if(mess.dialog=='1'){
//                    $('div.chat').hide();
//                    $('div.chat').delay(0);
//                    //$('div.pull-chat').html( 'Сообщение от:' + mess.name );
//                    //$('div.pull-chat').html( 'Сообщение от:' + mess.username );
//                    $('div.pull-chat').html( ' ' + mess.username );
//                    $('div.pull-chat-mess').html( '' + mess.msg );
//                    $('div.chat').show().animate({height: "100px"}, 500).delay(15000).animate({height: "-100px"}, 1500);
//                    $('div.chat').hide(15);
//                     
//                    //есть сообщение
//                    $('#status_chat').show().delay(15000)
//                    .css('cursor', 'help')
//                    .css('background-color', 'rgb(198, 219, 221)')
//                    .html('<p onclick="open_wind()"> есть сообщение</p>'); 
//                }
//              
//
//                  ///
//                   if( $(".chat_listing_wrap").css('display')==='block' ){
//                          show_listing();
//                   }
//                       
//                   return false;
//         };
//
//         ///
//         socket.onerror = function (event) {
//             status.innerHTML = 'error ' + event.message;
//         };
//       
//        
//         //
//         // messages_form        
//         // 
//         document.forms['messages_form'].onsubmit=function(event){
//             event.preventDefault();
//                 //
//             let message = {
//                 msg: this.msg.value,   
//                 dialog: this.dialog.value,                
//                 name: this.fname.value,                                             
//                 userid: this.userid.value,
//                 username: this.username.value
//             }            
//                ///            
//                socket.send(JSON.stringify(message));
//                // return false;               
//
//             ///textArea.Clear 
//             $("textarea#textField").val('');
//        
//         }
//        
//
//             
//             //
//             // Кнопка закрытия Формы
//             $("#chat_close_func").on('click',function() {
//                    let message = {
//                                     msg: 'Временно покинул чат',   
//                                     dialog: $("#dailogField").val(),        
//                                     name: -2, // System message
//                                     userid:  $("#useridField").val(),
//                                     username: $("#usernameField").val()
//                                 }            
//                             ///            
//                             socket.send(JSON.stringify(message));
//                   
//                 $(".chat_listing_wrap").css('display','none'); 
//                 open_wind();
//                 return false;
//             });
//
//
// // return false;
// };
//
//             // 
//             // Угловой крестик. Закрыть окна
//             $("#chat_ungle_close").on('click',function() {
//                  ///
//                  $( ".wrap_clients" ).css('display','none');            
//             });
//            
//             //
//             // Кнопка открытия списка на странице 
//             $("div#run_button").on('click',function() {
//                 open_wind();
//                 return false;
//             });
//
//
//            
// // Открываем окно Временного Чата
// function open_wind() {      
//     //Вехний листинг
//     //$(".scroll_mobile").css('display','none');           
//     $(".chat_listing_wrap").css('display','none');           
//     //Форма ответов    
//     $( ".answer_form" ).css('display','none');
//       
//     //           
//     $( ".wrap_clients" ).css('display','block');
// }
//           
// // Открываем Список всех пользователей
// function open_users() {
//    
//          $.ajax( {
//                 async: false, 
//                 url: '/chat/show_all_users',
//                
//                 // data: {
//                 //     id :1  //number
//                 // },		
//                     success: function(res) {
//                                 $('.chat_clients').html(res);
//                                                                   
//                                 // $('.scroll_mobile').scrollTop($('.scroll_mobile').height()*2000);
//                                    // $('.chat_clients').scrollTop($('.chat_clients').height()/20);            
//                             },
//                     error: function( res) {
//                                 alert('ERROR show_listing=> '+res );
//                                 console.log(res);
//                             }
//             });
//         
//     return false;
// }
//
//
// ///Делаем запрос через контроллер к базе. 
// // Получаем всю переписку 
// function show_listing(id) {
//    
//          $.ajax( {
//                 async: true, 
//                  //cache: false,                
//                 url: '/chat/show_listing',
//                 data: {
//                     sender_id: id  
//                 },		
//                     success: function(res) {
//                                 $('.chat_listing').html(res);
//                                 $('.scroll_mobile').scrollTop($('.chat_listing').height()*2000);
//                             },
//                     error: function( res) {
//                                 alert('ERROR show_listing=> '+res );
//                                 console.log(res);
//                             }
//             });
//         
//             //
//             $( ".chat_listing_wrap" ).css('display','block');
//             //
//             $( ".wrap_clients" ).css('display','none');
//            
//             // $('.scroll_mobile').scrollTop($('.scroll_mobile').height()*2000);
//             //$('.scroll_mobile').scrollTop($('.chat_listing').height()*2);
//            
//             // console.log($('.chat_listing').height());
//            
//             //alert(55545);
//
//   return true;
// }
//
//
// ///Делаем запрос через контроллер к базе. 
// // Получаем форму для отправки сообщений
// function show_form() {       
//     $( ".answer_form" ).css('display','block');
//      
//     $.ajax( {
//             async: false, 
//              url: '/chat/js_forma_by_id',
//              data: {
//                  id :1  //number                 
//              },		
//                  success: function(res) { 
//                       $('.answer_into').html(res);
//                              //return false;
//                          },
//                  error: function( res) {
//                       alert('err show_form=> '+res );
//                              //console.log(res);
//                          }
//          });                   
// return true;
//}


//JS;
//$this->registerJs($script, yii\web\View::POS_END);
?>

