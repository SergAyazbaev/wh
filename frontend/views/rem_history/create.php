<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Sprtype */

$this->title = 'Создаем ';
$this->params['breadcrumbs'][] = ['label' => 'Журнал неисправностей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprtype-create">

    <h1><?= Html::encode($this->title);


        //        dd($this->title);

        ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'pool' => $pool,
        'pool_diagnoz' => $pool_diagnoz,
        'spr_list' => $spr_list,
    ]) ?>

</div>
