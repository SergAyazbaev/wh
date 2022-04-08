<?php
$this->title = 'Общий чат';
$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;


?>

<!DOCTYPE html>
<html>
<head>

    <style>
        .glob_mess {
            display: block;
            position: inherit;
        }

        /*.status_chat {*/
        /*    display: block;*/
        /*    background-color: #0b72b8;*/
        /*    position: fixed;*/
        /*    bottom: 30px;*/
        /*}*/

        #messages-field {
            display: block;
            /*margin-top: 50px;*/
            /*margin-left: 0;*/
            width: 40%;
            min-width: 70%;
            max-height: 300px;
            /*overflow: auto;*/
            background-color: rgba(10, 115, 187, 0.31);
            margin: 10px 15px;
        }

        .leftmessage {
            /*padding: 0px 20px;*/
            width: 100%;
            float: left;
        }

        .leftmessage > h5 {
            font-size: 14px;
            width: 114px;
            float: left;
        }

        .leftmessage > h3 {
            background-color: rgba(10, 115, 187, 0.31);
            min-width: 60%;
            padding: 2px 10px;
            float: left;
            margin-top: 0px;
            margin-left: 10px;
        }


        /*.chat-place {*/
        /*    display: block;*/
        /*    margin-left: 30%;*/
        /*    width: 40%;*/
        /*    min-width: 400px;*/
        /*    height: 250px;*/
        /*    background: #ccc;*/
        /*    padding: 20px;*/
        /*}*/

        /*.chat-place .info {*/
        /*    margin: 0 auto;*/
        /*    width: 250px;*/
        /*    text-align: center;*/
        /*}*/


        .info input {
            padding: 10px;
        }

        .row label {
            display: block;
        }

        .info input[type="submit"] {
            margin-top: 10px;
        }


        .info {
            display: block;
            position: absolute;
            left: 40%;

            width: 300px;
            background-color: rgba(11, 114, 184, 0.1);
            padding: 10px 3px;
            height: 300px;
        }

        .info_mess {
            height: 300px;
            overflow-x: auto;
        }

        .row {
            display: block;
            position: relative;
            width: 90%;
            margin: auto;
            /*overflow: auto;*/
            /*background-color: #00CC00;*/

        }

        .row > textarea {
            width: auto;
        }

        .info > button {
            margin-left: 10%;

        }


        @media (max-width: 770px) {
            .wrap {
                /*overflow: auto;*/
                display: block;
                /*width: 310px;*/
            }

            .container22 {
                display: none;
            }

            .info {
                display: block;
                position: inherit;
                overflow: auto;
                /*width: 290px;*/
                width: 100%;
                /*background-color: #0b72b8;*/
                padding: 10px 3px;
                height: 225px;
                margin: 5px 0px 300px;
            }

            .row {
                display: block;
                position: relative;
                width: 90%;
                margin: auto;
            }

            .row > textarea {
                width: auto;
            }

            #messages-field {
                /*margin: 0px -10px;*/
                max-height: 120px;
                width: auto;
            }


            /*div#status_chat {*/
            /*    background-color: rgb(10, 86, 92);*/
            /*    right: 35px;*/
            /*    width: max-content;*/
            /*    z-index: 999;*/
            /*    display: block;*/
            /*    position: fixed;*/
            /*}*/

            /*.info_mess {*/
            /*    height: 100px;*/
            /*    overflow: scroll;*/
            /*    margin: 0;*/
            /*    padding: 0;*/
            /*}*/

            .leftmessage {
                padding: 0;
                float: left;
                width: 100%;
            }

            .leftmessage > h5 {
                font-size: 14px;
                width: 104px;
                float: left;
                color: cadetblue;
                margin: 0;
            }

            .leftmessage > h3 {
                font-size: medium;
                width: 100%;
                margin: 0;
            }
        }

    </style>


</head>

<body>


<!--<div class="chat-place">-->


<div class="glob_mess">
    <div class="info_mess">
        <div id="messages-field"></div>
    </div>

    <?php Pjax::begin(['id' => 'w11']); ?>
    <?php
    $form = ActiveForm::begin([
            'id' => 'w11',

            'action' => ['client'],
            'options' => [
                'name' => "messages",   // !!!!
                'autocomplete' => 'off',
            ],
        ]

    ); ?>


    <div class="info">
        <div class="row">

            <?= $form->field($model, 'dialog_id')->dropDownList(
                $array_dialog,
                [
                    'prompt' => 'Выбор из списка ...',
                    'id' => "dailogField",  // !!!!
                    'name' => "dialog",
                ]
            )->label("Чат");
            ?>
        </div>

        <div class="row" style="display: none">
            <?= $form->field($model, 'sender_id')
                ->hiddenInput(['class' => 'form-control class-content-title_series',
                    'id' => "nameField",  // !!!!
                    'name' => "fname"  // !!!!
                ])->label(false);
            ?>
            <?= $form->field($model, 'user_id')
                ->hiddenInput([
                    'id' => "useridField",
                    'name' => "userid"
                ])->label(false);
            ?>
            <?= $form->field($model, 'user_name')
                ->hiddenInput([
                    'id' => "usernameField",
                    'name' => "username"
                ])->label(false);
            ?>
        </div>

        <div class="row">
            <?= $form->field($model, 'text')
                ->textarea(['class' => 'form-control class-content-title_series',
                    'style' => "height: 45px;",
                    'id' => "textField",  // !!!!
                    'name' => "msg"  // !!!!

                ]);
            ?>
        </div>


        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>

</div>

<?php
$script = <<<JS


var socket;

    window.onload = function () {
        
        var socket = new WebSocket('ws://79.143.22.33:8809'); /// ok
        
        // var socket = new WebSocket('ws://10.0.0.151:8081'); /// ok localhost for HOME=10.0.0.64
        // var socket = new WebSocket('ws://localhost:8081'); /// ok localhost for HOME=10.0.0.64
        //var socket = new WebSocket('ws://localhost:8809'); /// ok 111111
        
        // socket = new WebSocket('ws://localhost:8809'); /// ok 111111

        var status = document.getElementById('status_chat');

        socket.onopen = function (event) {
                //console.log(event);            
                // status.innerHTML = 'на связи';
               
              // Ok  
              status.innerHTML = '<a href="/chat/client"> на связи </a>';
             
              let  doc=document.forms['messages'];
                let message1 = {
                    msg: 'Статус.Активен в основном чате',   
                    name: '-1',     // Ok                                        
                    dialog: doc.dialog.value,                
                    userid: doc.userid.value,
                    username: doc.username.value
                };
                
                //console.log(message1);
                
                socket.send(JSON.stringify(message1));
              //let message1 ='{"msg":"На связи","dialog":"1"}';
              //socket.send(message1);
            //msg name dialog=1, userid=1, username 
        };

        socket.onclose = function (event) {
            if (event.wasClean) {
                status.innerHTML = 'соединение закрыто';
            } else {
                status.innerHTML = 'перезагрузить страницу';
            }
        };

        socket.onmessage = function (event) {
            ///            
            let mess = JSON.parse(event.data);
            ///
            let dialogmenu = document.getElementById('dailogField');
                //console.log(dialogmenu.value);
            
            if(mess.dialog==dialogmenu.value){                   
                   
                var div = document.getElementById('messages-field');
                var innerDiv = document.createElement('div');
                innerDiv.classList.add('leftmessage');
                var h2 = document.createElement('h5');
                var h3 = document.createElement('h3');
                
                //console.log(mess);           
                
                //h3.innerHTML = mess.dialog + ':'+ mess.userid + ':' +  mess.name +':'+  mess.msg ;
                //h3.innerHTML = ''+mess.dialog + '. '+ mess.username +'('+ mess.userid + ') :' +   mess.msg ;
                h2.innerHTML = ''+mess.dialog + '. '+ mess.username ;
                h3.innerHTML = mess.msg ;
                innerDiv.appendChild(h2);
                innerDiv.appendChild(h3);
                div.appendChild(innerDiv);    
                
                //document.getElementById('nameField').value = '';
                document.getElementById('textField').value = '';       
                
                
                // $('#messages-field').scrollTop($('#messages-field').height()*2000);
                $('.info_mess').scrollTop($('.info_mess').height()*2000);
                //background-color: rgb(197, 230, 194);
                // $('#status_chat').show().delay(15000).css('cursor', 'help').css('background-color', 'rgb(197, 230, 194)').html('<a href="/chat/client"> есть сообщение</a>');
                //cursor:help;
            }

        };


        socket.onerror = function (event) {
            status.innerHTML = 'error ' + event.message;
        };

        

        document.forms['messages'].onsubmit=function(){            
            let message = {
                msg: this.msg.value,   
                name: this.fname.value,                                             
                dialog: this.dialog.value,                
                userid: this.userid.value,
                username: this.username.value
            }
            socket.send(JSON.stringify(message));
            return false;
        }

        
        // function startmess(){    
        //     let message = {
        //                 dialog: this.dialog.value,
        //                 name: this.fname.value,
        //                 msg: this.msg.value
        //             }
        //             socket.send(JSON.stringify(message));
        //             return false;            
        // }

        return false;
    };   

JS;

$this->registerJs($script, yii\web\View::POS_END);
?>


</body>
</html>

