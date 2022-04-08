<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


Pjax::begin([
    'id' => 'pjax-container',
]);

echo \yii::$app->request->get('page');

Pjax::end();



?>

<?php $form = ActiveForm::begin(); ?>

<div id="tz1" >
    <div class="pv_motion_create_right_center">

        <div class="tree_land2">
            <?php
                echo "<h2> 123123</h2>";
            ?>
        </div>

        <div class="tree_land">
             <?php
                    echo $form->field($model, 'created_at')
                        ->widget(DateTimePicker::className(),[

                            'name' => 'dp_1',
                            'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                            'size' => 'lg',
                            'options' => [
                                'placeholder' => 'Ввод даты/времени...'],

                            //'value'=> $date_now,
                            'value'=> date("d.m.Y H:i:s",strtotime($model->created_at)),
                            'pluginOptions' => [
                                'pickerPosition' => 'bottom-left',
                                'format' => 'yyyy-mm-dd HH:ii:ss',
                                'autoclose'=>true,
                                'weekStart'=>1, //неделя начинается с понедельника
                                // 'startDate' => $date_now,
                                'todayBtn'=>true, //снизу кнопка "сегодня"
                            ]
                        ]);

             echo $form->field($model, 'created_at')->input('date',['value'=>$model->created_at]);
             ?>

        </div>



        <div class="tree_land">
                <?php


                //            [id] => 1
                //            [username] => viktor
                //            [email] => 222@qwe.kz
                //            [password_hash] => $2y$13$c.y/CWhKFoHp.JuoZmQPuuG1UqVz/GgRGJEzkrUuD3NUuEcHlPJju
                //            [auth_key] => 5W28-CDuln1NMSSOkfsKQjE0wulUaNnR
                //            [status] => 10
                //            [role] => user
                //            [group_id] => 40
                //            [created_at] => 1549887428
                //            [updated_at] => 1549887428



                //////////
                echo $form->field($model, 'id')->textInput();


                echo $form->field($model, 'username')->textInput();
            ?>
            </div>

            <div class="tree_land">
            <?php

            //////////
            echo $form->field($model, 'email')->textInput();

            echo $form->field($model, 'status')->textInput();

            ?>
        </div>

        <div class="tree_land">
            <?php

            //////////
            echo $form->field($model, 'role')->textInput();

            echo $form->field($model, 'group_id')->textInput();

            ?>

            </div>

    </div>






    <div class="pv_motion_create_ok_button">
            <?= Html::button('&#8593;',
                [
                    'class' => 'btn btn-warning',
                    'onclick'=>"window.history.back();"
                ]);
            ?>


            <?php
            echo Html::submitButton('Сохранить изменения в накладной',
                ['class' => 'btn btn-success']
            );
            ?>


    </div>

</div>





<?php ActiveForm::end(); ?>



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


$script = <<<JS
$(document).on('keypress',function(e) {
    if(e.which == 13) {
//            alert('Нажал - enter!');
      e.preventDefault();
      return false;
    }
});




function myFunction() {
  var x = document.getElementById("fname").value;
  document.getElementById("demo").innerHTML = x;
}

     

//////////////////// VID - sklad-sklad_vid_oper
$('#sklad-sklad_vid_oper').change(function() {    
     var  number2 = $('#sklad-sklad_vid_oper').val();
     var  text2   = $('#sklad-sklad_vid_oper>option[value='+number2+']').text();
      $('#sklad-sklad_vid_oper_name').val(text2);
});




JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

