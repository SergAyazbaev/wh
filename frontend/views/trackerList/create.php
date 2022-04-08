<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\trackerlist */

$this->title = 'Создать запись';
$this->params['breadcrumbs'][] = ['label' => 'Перемещение приборов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trackerlist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_new_form', [
        'model' => $model,
    ]) ?>

</div>
