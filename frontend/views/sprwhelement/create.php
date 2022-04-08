<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */

$this->title = 'Создаем Новый Элемент склада ';
$this->params['breadcrumbs'][] = ['label' => 'Справочник складов компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprtype-create">

    <h1><?= Html::encode($this->title);


//        dd($this->title);

        ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
