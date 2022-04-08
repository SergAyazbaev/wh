<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postsprtype */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Справочник видов работ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать новый Вид работы', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'contentOptions' => ['class' => 'action-column'],
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            $url, [
                                'title' => 'Delete',
                                'data-pjax' => '#model-grid',
                            ]);
                    },
                ],
            ],

        ],
    ]);
    ?>
</div>
