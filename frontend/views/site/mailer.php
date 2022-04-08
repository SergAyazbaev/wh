<?php
//views/site/mailer.php

/* @var yii\web\View $this */
/* @var yii\bootstrap\ActiveForm $form */

/* @var frontend\models\MailerForm $model */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'ПОЧТА';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('mailerFormSubmitted')) : ?>

        <div class="alert alert-success">
            Ваше письмо отправлено
        </div>

    <?php else : ?>


        <p>
            Эта форма для отправки писем
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'mailer-form']); ?>

                <?= $form->field($model, 'fromName')->label('От кого:') ?>

                <?= $form->field($model, 'fromEmail')->label('Ваша почта:') ?>

                <?= $form->field($model, 'toEmail')->label('Почта получателя:') ?>

                <?= $form->field($model, 'subject')->label('Тема письма:') ?>

                <?= $form->field($model, 'body')->textArea(['rows' => 6])->label('Текст письма:') ?>

                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    <?php endif; ?>
</div>