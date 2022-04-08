<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

//    use yii\widgets\ActiveForm;


$this->title = 'Справочник проверочных кодов, штрихкодов';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(['id' => '1']);

?>
<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    //         echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>


        <?= Html::a('Создать новый Элемент ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php

    //  Pjax::begin();

    $form = ActiveForm::begin(
        [
            'id' => 'project-form',
            'method' => 'get',
            'class' => 'form-inline',
            'action' => ['/barcode_pool/index'],

            'options' => [
                'data-pjax' => 0,
                'autocomplete' => 'off',
            ],

        ]);
    ?>

    <?= Html::submitButton(
        'Передать в EXCEL', [
        'class' => 'btn btn-default',
        'name' => 'print',
        'data-pjax' => 0,
        'value' => 1,
    ]) ?>


    <?php
    ActiveForm::end();

    $dataProvider->pagination->pageSize = 10;
    ?>


    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

            'columns' => [

                [
                    'attribute' => 'id',
                    'value' => 'id',
                    'contentOptions' => ['style' => ' ;width: 75px;'],
                ],

                [
                    //'attribute'      => 'parent_name',
                    //'filter'         => $spr_globam_element,
                    'value' => 'spr_globam_element.spr_globam.name',
                    'contentOptions' => ['style' => ' ;min-width: 15px;'],
                ],

                [
                    'attribute' => 'parent_name',
                    'filter' => $spr_globam_element,
                    'value' => 'spr_globam_element.name',
                    'contentOptions' => ['style' => ' ;min-width: 15px;'],
                ],

                [
                    'attribute' => 'bar_code',
                    'contentOptions' => ['style' => 'width: 130px;'],
                ],


                'barcode_consignment_id',

                [
                    'attribute' => 'write_off',
                    'contentOptions' => ['style' => 'overflow: hidden;width: 90px;'],
                    'content' => function ($model) {
                        if (isset($model['write_off']) && !empty($model['write_off'])) {
                            return $model['write_off'];
                        }
                        return '';
                    }
                ],

                [
                    'attribute' => 'write_off_doc',
                    'contentOptions' => ['style' => 'overflow: hidden;width: 90px;'],
                    'content' => function ($model) {
                        if (isset($model['write_off_doc']) && !empty($model['write_off_doc'])) {
                            return $model['write_off_doc'];
                        }
                        return '';
                    }
                ],

                [
                    'attribute' => 'write_off_note',
                    'contentOptions' => ['style' => 'overflow: hidden;width: 90px;'],
                    'content' => function ($model) {
                        if (isset($model['write_off_note']) && !empty($model['write_off_note'])) {
                            return $model['write_off_note'];
                        }
                        return '';
                    }
                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'contentOptions' => [
                        'class' => 'action-column',
                        'style' => ' width: 120px;',
                    ],


                    'buttons' => [
                        'update' => function ($url) {

                            //Для Талгата
                            if (Yii::$app->getUser()->identity->group_id == 100 ||
                                Yii::$app->getUser()->identity->group_id == 71) {

                                $options = [
                                    'target' => '_blank',
                                    'title' => 'update',
                                    'aria-label' => 'update',
                                    'data-pjax' => 'w0',
                                    //'data-confirm' => Yii::t('yii', 'Редактируем... '),
                                    'data-method' => 'GET',

                                ];

                                return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, $options);
                            }

                            return "";
                        },
                        'delete' => function ($url) {
                            //Для Талгата
                            if (Yii::$app->getUser()->identity->group_id >= 71) {

                                $options = [
                                    'title' => 'delete',
                                    'aria-label' => 'delete',
                                    'data-pjax' => 'w0',
                                    //'data-confirm' => Yii::t( 'yii', 'Точно? Удаляем? ' ),
                                    'data-method' => 'POST',
                                ];

                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                            }

                            return '';
                        },
                    ],
                ],

                [
                    'attribute' => 'turnover',
                    'contentOptions' => ['style' => 'overflow: hidden;width: 90px;'],
                    'content' => function ($model) {
                        if (isset($model['turnover']) && !empty($model['turnover'])) {
                            return $model['turnover'];
                        }
                        return '';
                    }
                ],

                [
                    'attribute' => 'turnover_alltime',
                    'contentOptions' => ['style' => 'overflow: hidden;width: 90px;'],
                    'content' => function ($model) {
                        if (isset($model['turnover_alltime']) && !empty($model['turnover_alltime'])) {
                            return $model['turnover_alltime'];
                        }
                        return '';
                    }
                ],


            ],
        ]);


    ?>


</div>


<?php
Pjax::end();
?>