<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
$this->title = 'Справочник складов компании';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .tree_land{
        width: 450px;
        min-width: 300px;
        background-color: cornsilk;
        padding: 15px;
    }
</style>


<div class="sprtype-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать новый Склад', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model) {
            //
            if (isset($model->deactive) && $model->deactive == 1) {
                //return ['class' => 'info'];
                return ['class' => 'danger'];
            }
            //
            if (isset($model->final_destination) && $model->final_destination == 1) {
                return ['class' => 'success'];
                //                return ['class' => 'info'];
            }
            // Накладная НЕ ПРИНЯТА И НЕ ОТВЕРГНУТА.
            return [];
        },

        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'name_tha',
                'contentOptions' => [ 'style' => 'max-width:250px;overflow: hidden' ],

                'value' => function ($model, $key) {

                    if (isset($model['name_tha'])) {
                        return $model['name_tha'];
                    }
                    return '';
                }
            ],

            'tx',


            [
                'attribute' => 'delete_sign',
                'value' => function ($url, $model, $key) {

                    if (isset($url['delete_sign'])) {
                        return $url['delete_sign'];
                    }

                    return '';
                },
                'contentOptions' => ['style' => ' width: 72px;'],
            ],

            [
//                    final_destination
                'attribute'=> 'final_destination',
                'value'=>function($url, $model, $key) {

                    if (isset($url['final_destination'])  && (int)$url['final_destination']==1 ){
                        return 'ЦС';
                    }

                    return '';
                },

                'contentOptions' => ['style' => ' width: 72px;'],
            ],

            [
                'attribute' => 'f_first_bort',
                'contentOptions' => [ 'style' => ' min-width: 172px;' ],
                'value'=>function($url, $model, $key) {
                    if ( isset( $url[ 'f_first_bort' ] ) && (int)$url[ 'f_first_bort' ] == 1 ) {
                        return 'Борт';
                    } else{
                        return 'Гос';
                    }
                }
            ],

//            [
//                'attribute'=> 'buses_variant',
//                'value'=>'buses_variant',
//                'value'=>function($url, $model, $key) {
//
//                    if (isset($url['buses_variant']) && (int)$url['buses_variant'] == 1) {
//                        return 'Да';
//                    } else {
//                        return 'Нет';
//                    }
//
//                },
//                'contentOptions' => ['style' => ' min-width: 172px;'],
//            ],

            [
                'contentOptions' => ['style' => 'min-width: 172px;'],
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {update_ss} {flags} {delete} {erase} ',
                'buttons' => [

                    'update' => function ($url, $model) {
                        if (
                            Yii::$app->getUser()->identity->group_id == 100 ||
                            Yii::$app->getUser()->identity->group_id == 71
                        ) {
                            $options = [
                                'title' => 'Редактирование',
                                'aria-label' => 'edit',
                                'data-pjax' => 'w0',
                                'data-method' => 'PUT',
                                'target' => '_blank',
                            ];
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                $url, $options);
                        }
                        return '';
                    },

                    'update_ss' => function ( $url, $model ) {
                        if (
                            Yii::$app->getUser()->identity->group_id == 100 ||
                            Yii::$app->getUser()->identity->group_id == 71 ||   // tt
//                                Yii::$app->getUser()->identity->group_id == 61 || //Nazira
                            Yii::$app->getUser()->identity->group_id == 65 // Nazik,Aidans, SS
                        ) {
                            $options = [
                                'title' => 'Редактирование',
                                'aria-label' => 'edit',
                                'data-pjax' => 'w0',
                                'data-method' => 'PUT',
                                'target' => '_blank',
                            ];
                            return Html::a(
//                                '  <span class="glyphicon glyphicon-ok-circle"></span>',
//                                '  <span class="glyphicon glyphicon-eye-close"></span>',
                                '<span class="glyphicon glyphicon-serg5"></span>',

                                $url, $options
                            );
                        }
                        return '';
                    },


                    'flags' => function ($url,$model) {
                        //Для Талгата
                        if ( Yii::$app->getUser()->identity->group_id == 71 ){
                            $options = [
                                'title' => 'Редактирование',
                                'aria-label' => 'edit',
                                'data-pjax' => 'w0',
                                'data-method' => 'POST',
                                'target' => '_blank',
                            ];
                            return Html::a(
                                '<span class="glyphicon glyphicon-alert"></span>',
                                $url, $options);
                        }
                        return '';
                    },


                    'delete' => function($url, $model, $key) {
                        if (Yii::$app->getUser()->identity->group_id >= 70) {
                            $options = [
                                    'title' => 'Удаление',
                                'aria-label' => 'delete',
                                'data-pjax' => 'w0',
                                'data-confirm' => Yii::t('yii', 'Точно? Удаляем?'),
                                'data-method' => 'DELETE',
                            ];
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                        }
                        return '';
                    },


                    'erase' => function($url, $model, $key) {
                        if($model->delete_sign==1){
                            if (Yii::$app->getUser()->identity->group_id >= 100 ) {
                                $options = [
                                    'title' => 'XXXXX',
                                    'aria-label' => 'erase',
//                                    'data-pjax' => 'w0',
                                    'data-confirm' => Yii::t('yii', 'Точно? Удаляем?'),
                                    'data-method' => 'POST',
                                ];
                                return Html::a('<span class="glyphicon glyphicon-thumbs-down"></span>',
                                    $url, $options);
                            }
                        }
                        return '';
                    },

                ],
            ],






        ],
    ]); ?>
</div>


<?php Pjax::end(); ?>


