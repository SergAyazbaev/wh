<?php
//'dialog_id' => $dialog_id,
//            'user_id' => $user_id,
//            'user_name' => $user_name,

?>

<div class="row_form">
    <!--    <label style="font-size: 11px;">Сообщение: </label>-->
    <!--    <input type="text" id="textField" autocomplete="off" name="msg" placeholder="Текст сообщения" value=""/>-->
    <textarea id="textField" style="width:292px;height:88px;resize:none;padding:2px 10px;border:0.5px dashed;"
              autocomplete="off" name="msg" placeholder="Текст сообщения" ></textarea>
</div>
<!--<div class="row_form">-->
<!--    <label style="font-size: 11px;">Диалог/Канал:</label>-->
    <input type="hidden" id="dailogField" name="dialog" value="<?= $dialog_id ?>"/>
<!--</div>-->

<input type="hidden" id="nameField" autocomplete="off" name="fname"/>
<input type="hidden" id="useridField" autocomplete="off" name="userid" value="<?= $user_id ?>"/>
<input type="hidden" id="usernameField" autocomplete="off" name="username" value="<?= $user_name ?>"/>


