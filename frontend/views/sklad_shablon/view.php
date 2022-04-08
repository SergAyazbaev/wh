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
        <?= Html::a('Копировать',
            ['create_copy',
                'id' => (string)$model->_id ],
            ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
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
            'id',
            'type_pv_name',
            'group_pv_name',
            'type_pv_name',

            'dt_create',
            'pv_health',
            'bar_code_pv',
            'qr_code_pv',

            'pv_bee',
            'pv_kcell',
            'pv_imei',

        ],
    ]) ?>

</div>
