<?php

use yii\helpers\Html;




$this->title = 'Типовой Комплект №: '.$model->id ;
$this->params['breadcrumbs'][] = ['label' => 'Комплекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['/tk/index?sort=-id']];
$this->params['breadcrumbs'][] = 'Создать';
?>

<?php /*  echo $this->render('_search_tk',
    [
        'model' => $model,
    ]
);
*/?>

<div class="pv-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form_tk', [
        'model' => $model,
    ]) ?>

</div>
