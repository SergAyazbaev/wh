<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Авторизация на сайте';
$this->params['breadcrumbs'][] = $this->title;
?>

<br>
<br>

<h1 style="text-align: center"><?= Html::encode($this->title) ?></h1>

<div class="site-login">

    <p>Пожалуйста, заполните поля для авторизации:</p>

    <div class="row">
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <div class="col-lg-5">
            <?= $form->field($model, 'username')->textInput(['autofocus' => true])
                ->label('Логин ') ?>
            <?= $form->field($model, 'password')->passwordInput()
                ->label('Пароль ') ?>
            <?= $form->field($model, 'rememberMe')->checkbox()
                ->label('Запомнить меня. ') ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>

    <!--    <div style="color:#999;margin:1em 0">-->
    <!--        Если не помните свой пароль жмите сюда: -->
    <? //= Html::a('reset it', ['site/request-password-reset']) ?><!--.-->
    <!--    </div>-->


</div>
