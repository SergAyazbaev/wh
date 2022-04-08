<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use app\models\Sprwhtop;
use common\models\User;

use yii\bootstrap\ActiveForm;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

Pjax::begin([
    'id' => 'pjax-container',
]);

echo yii::$app->request->get('page');

Pjax::end();


$this->title = 'Регистрация нового пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .group_team {
        background-color: blanchedalmond;
        padding: 16px;
        border-radius: 10px;
        border: 2px solid #33333340;
        margin: 17px 1px;
    }
</style>

<br>
<br>
<h1 style="text-align: center"><?= Html::encode($this->title) ?></h1>

<div class="site-signup">

    <p>Пожалуйста, введите следующие данные:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>



            <?php
            echo $form->field($model, 'username_for_signature')->textInput();
            ?>


            <?php
            echo $form->field($model, 'group_id')->dropdownList(
                [
                    //    const GROUP_GUEST   = 0;            // 'Гость',
                    //    const GROUP_NIGHT   = 20;           // 'Ночной ИНЖЕНЕР',
                    //    const GROUP_AGENT   = 30;           // 'Агент ТХА',
                    //    const GROUP_SKLAD   = 40;           // 'Пользователь ЗавСКЛАД',
                    //    const GROUP_GL_ENG  = 50;           // 'Пользователь Гл.ИНЖЕНЕР',

                    //    const GROUP_BUH_2  = 60;           // ,'Бухгалтерия 2 этаж',
                    //    const GROUP_BUH_2_NAZIRA  = 61;    //'Бухгалтерия 2 этаж Назира',

                    //    const GROUP_BUH_2  = 65;           // 'Бухгалтерия 1 этаж',

                    //    const GROUP_MODER   = 70;           // Модератор
                    //    const GROUP_ADMIN   = 100;          // Админ

                    //    User::GROUP_ADMIN   =>  'Админ',
                    //    User::GROUP_MODER   =>  'Модератор',

                    User::GROUP_STAT_86 => 'Просмотр остатков на главном складе',
                    User::GROUP_BUH_2 => 'Бухгалтерия 2 этаж',
                    //User::GROUP_BUH_2_NAZIRA   =>  'Бухгалтерия 2 этаж Назира',
                    User::GROUP_BUH_1 => 'Бухгалтерия 1 этаж',
                    User::GROUP_GL_ENG => 'Главные ИНЖЕНЕРЫ',
                    User::GROUP_NIGHT => 'Ночные ИНЖЕНЕРЫ',
                    User::GROUP_SKLAD => 'Заведующие СКЛАДОМ',
                    User::GROUP_AGENT => 'Агенты ТХА',
                    User::GROUP_REMONT => 'Ремонтники',
                    User::GROUP_MONTAGE => 'Монтажники',
                    User::GROUP_GUEST => 'Гости',
                ],
                [
                    'prompt' => 'Выбор ...',
                    //'disabled' => 'disabled',
                ]
            );
            //                    ->label("Склад-приемник");


            ?>


            <div class="group_team">
                <?php


                echo $form->field($model, 'wh_destination')->dropdownList(
                    ArrayHelper::map(
                        Sprwhtop::find()->all(),
                        'id', 'name'),
                    [
                        'prompt' => 'Выбор склада ...',
                        //'disabled' => 'disabled',
                    ]
                )->label("Склад-приемник");


                echo $form->field($model, 'sklad')->dropdownList(
                    [],
                    [
                        'prompt' => 'Выбор склада ...',
                        //'disabled' => 'disabled',

                    ]
                );
                ?>

            </div>


            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>





            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<?php
$script = <<<JS
//////////////////// Debitor -top
$('#signupform-wh_destination').change(function() {

    var  number = $(this).val();
    //alert(number);
            
            //    var  text = $('#sklad-wh_debet_top>option[value='+number+']').text();    
            //    $('#sklad-wh_debet_name').val(text) ;
            //
            //     // var  number2 = $('#sklad-wh_debet_element').val();
            //     // var  text2   = $('#sklad-wh_debet_element>option[value='+number2+']').text();
            //     //  $('#sklad-wh_debet_element_name').val(text2);


    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {
		            //$('#signupform-wh_destination_element').html(res);		    
		            $('#signupform-sklad').html(res);		    
					},
			error: function( res) {
						alert('нет данных ' );
						console.log(res);
					}
    } );
    
});



JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
