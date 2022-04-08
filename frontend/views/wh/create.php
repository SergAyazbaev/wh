<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Wh */

$this->title = 'Create Wh';
$this->params['breadcrumbs'][] = ['label' => 'Whs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wh-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
