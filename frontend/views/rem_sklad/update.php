<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */

$this->title = 'Редактирование существующего';
?>
<style>
    .textarea.form-control {
        font-size: 21px;
        height: 120px;
    }
</style>


<div class="sprtype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
