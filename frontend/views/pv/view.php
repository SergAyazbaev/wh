<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Pv */

$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Учетная единица', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pv-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Сохранить', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы хотите УДАЛИТЬ запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            '_id',
            'name_pv',

            'dt_create_pv',
            'dt_create_mongo',

            'group_pv',
            'group_pv_name',
            'type_pv',
            'type_pv_name',

            'bar_code_pv',
            'parameters_pv',
            'active_pv',
            'active_pv_name',
        ],
    ]) ?>

</div>
