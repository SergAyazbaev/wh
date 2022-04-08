<?php


use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;


$this->title = 'Типовые Комплекты';
$this->params[ 'breadcrumbs' ][] = $this->title;
?>
<style>
    .grid-view .filters input, .grid-view .filters select {
        min-width: 50px;
        height: 10px;
        margin: 0px;
    }

    .filters {

    }

    .table-bordered > thead > tr > td {
        border-bottom-width: 2px;
        padding: 0px;
    }

    .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
        border: 1px solid #ddd;
        padding: 3px 10px;
    }

    .grid-view .filters input, .grid-view .filters select {
        height: 20px;
    }

    @media (max-width: 710px) {
        .glyphicon {

        }
    }
</style>

<div class="pv-index" style="display: inline-block;">

    <h1><?= Html::encode( $this->title ) ?></h1>
    <?php Pjax::begin(); ?>

    <p class="breb_crumbs_pice">

        <?php
        if ( Yii::$app->user->identity->group_id == 50 ) {
            echo Html::a(
                'Создать новый Типовой Комплект', [ 'create' ],
                [ 'class' => 'btn btn-success' ]
            );
        }
        ?>
    </p>


</div>


<div class="table_with">
    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'header' => '',
                    'content' => function ( $model ) {
                        //dd($model);
                        // /tk/update?id=5be936da80a06324300010ac

                        $url = Url::to( [ '/tk/update_tk?id='.$model->_id ] );

                        return Html::a(
                            'Вн', $url, [

                                    'class' => 'btn btn-success btn-xs',
                                    'data-pjax' => 0,
                                    'data-id' => $model->_id,
                                ]
                        );
                    }
                ],

                [
                    'attribute' => 'id',
                    'value' => 'id',
                    'contentOptions' => [ 'style' => ' width: 25px;' ],
                ],

                'name_tk',

                'dt_edit',

                'user_edit_name',


                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ( $url, $model ) {

                            if ( $model[ 'user_create_id' ] != Yii::$app->getUser()->identity->id ) {
                                return '';
                            }

                            return Html::a(
                                '<span class="glyphicon glyphicon-trash"></span>',
                                $url
                            );
                        },


                    ],


                ],


            ],
        ]
    );
    ?>
    <?php Pjax::end(); ?>
</div>


<?php
$script = <<<JS


JS;
$this->registerJs( $script );
?>



