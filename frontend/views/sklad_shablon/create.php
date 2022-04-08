<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Pv */

$this->title = 'Новый шаблон накладной ';
$this->params['breadcrumbs'][] = ['label' => 'Список шаблонов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


//dd($dataProvider_wh);

?>
<div class="pv-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
    ])


    ?>

</div>
