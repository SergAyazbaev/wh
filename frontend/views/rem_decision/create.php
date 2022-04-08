<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */

$this->title = 'Создаем новую неисправность ';
$this->params['breadcrumbs'][] = ['label' => 'Справочник Неисправностей', 'url' => ['index']];
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
