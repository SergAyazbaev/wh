<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */

$this->title = 'Создаем Новый Компонент ';
$this->params['breadcrumbs'][] = ['label' => 'Справочник Компонентов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprtype-create">

    <h1><?= Html::encode($this->title);


//        dd($this->title);

        ?></h1>

    <?= $this->render('_form_all_from_txt', [
        'model' => $model,
    ]) ?>

</div>
