<?php


use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Pv */

$this->title = 'Создаем новый Типовой Комплект';
$this->params['breadcrumbs'][] = ['label' => 'Типорвые Комплекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


//dd($dataProvider_wh);

?>
<div class="pv-create">

    <h1><?= Html::encode($this->title) ?></h1>



    <?php echo $this->render('_form', [
        'model' => $model,
        'model_table_tk' => $model_table_tk,
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ])


    ?>

</div>
