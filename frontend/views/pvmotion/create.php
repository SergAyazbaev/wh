<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Pvaction */

$this->title = 'Перемещение устройства ';
$this->params['breadcrumbs'][] = ['label' => 'История перемещений', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pv-action-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
