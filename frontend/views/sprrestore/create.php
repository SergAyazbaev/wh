<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */

$this->title = 'Создаем новый вид обслуживания оборудования ';
$this->params['breadcrumbs'][] = ['label' => 'Справочник видов обслуживания оборудования', 'url' => ['index']];
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
