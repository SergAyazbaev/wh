<?php

use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


$this->title = 'Журнал';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .btn {
        margin: 3px 5px;
        padding: 0px 10px;
    }

    div > form {
        width: 200px;
        display: inline;
    }


    .redstyle {
        color: rgba(54, 42, 9, 1);
        background: rgba(208, 212, 13, 0.3);
    }

    .redstyle10 {
        color: rgba(54, 42, 9, 1);
        background: rgba(209, 214, 11, 0.7);
    }

    .redstyle20 {
        color: rgba(54, 42, 9, 1);
        background: rgba(225, 174, 37, 0.5);
    }

    .redstyle30 {
        color: rgba(54, 42, 9, 1);
        background: rgba(200, 105, 26, 0.5);
    }

    .redstyle40 {
        color: rgba(54, 42, 9, 1);
        background: rgba(249, 80, 39, 0.5);
    }

    .scroll_mobile {
        width: 100%;
        overflow: auto;
    }
</style>

<div class="sprtype-index">

    <!--    <h1>--><? //= Html::encode($this->title) ?><!--</h1>-->


<!--    --><?//= Html::a('Новая запись в журнал', ['create'], ['class' => 'btn btn-success']) ?>


    <?php
    $form = ActiveForm::begin(
        [
            'id' => 'project-form',
            'method' => 'get',

            'class' => 'form-inline',
            'action' => ['/rem_history/index'],

            'options' => [
                //'data-pjax' => 1,
                'autocomplete' => 'off',
            ],

        ]);
    ?>

<!--    --><?//= Html::submitButton(
//        'EXCEL', [
//        'class' => 'btn btn-default',
//        'name' => 'print',
//        'value' => 1,
//    ]) ?>
<!--    --><?//= Html::a(
//        'Очистить фильтр', '/rem_history/index',[
//        'class' => 'btn btn-default',
//        'name' => 'print',
//        'value' => 1,
//    ]) ?>

    <?php ActiveForm::end(); ////////////////////////        ?>

    <div class="scroll_mobile">
        <?php Pjax::begin([
            'id' => 'pjax-container1',
            'timeout' => 1000,
//            'enablePushState' => false
        ])
        ?>

        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,


                'columns' => [

                    [
                        'attribute' => 'id',
                        'contentOptions' => ['style' => 'width: 35px;'],
                    ],

                    [
                        'attribute' => 'dialog_id',
                        'contentOptions' => ['style' => 'width: 40px;    padding: 12px 7px;']
                    ],

                    [
                        'attribute' => 'user_id',
                        'contentOptions' => ['style' => 'width: 40px;    padding: 12px 7px;']
                    ],
                    [
                        'attribute' => 'sender_id',
                        'contentOptions' => ['style' => 'width: 40px;    padding: 12px 7px;']
                    ],



                    'text',


                    [
                        'attribute' => 'date_create',
                        'value' => 'date_create',
                        'format' => ['datetime',
                            'php:d.m.y (H:i:s)',
                        ],
                        'contentOptions' => ['style' => 'width: 100px;min-width: 50px;'],

                        'content' => function ($model) {
                            if (empty($model->date_create)) {
                                return '';
                            }
                            return date('d.m.y (H:i:s)', $model->date_create);
                        }

                    ],

                    [
                        'attribute' => 'status',
                        'contentOptions' => ['style' => 'width: 40px;    padding: 12px 7px;']
                    ],



                ],
            ]
        );
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>


<?php
$script = <<<JS

setInterval(function(){
 		$.pjax.reload('#pjax-container1')
 	}, 60000);

JS;

$this->registerJs($script);
?>
