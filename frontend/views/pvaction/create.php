<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Pvaction */

$this->title = 'Create pv Action';
$this->params['breadcrumbs'][] = ['label' => 'pv Actions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pv-action-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
