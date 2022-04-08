<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Pv */

$this->title = 'Создаем запись для прибора учета';
$this->params['breadcrumbs'][] = ['label' => 'Приборы учета', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


//dd($dataProvider_wh);

?>
<div class="pv-create">

    <h1><?= Html::encode($this->title) ?></h1>




    <?



    echo $this->render('_form', [
        'model' => $model,
//        'searchModel_wh' => $searchModel_wh,
//        'dataProvider_wh' => $dataProvider_wh,
    ])


    ?>

</div>
