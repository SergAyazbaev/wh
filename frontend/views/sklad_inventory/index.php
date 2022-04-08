<?php /** @noinspection PhpUndefinedClassInspection */

use frontend\models\Sprwhtop;
use frontend\models\Sprwhelement;
use frontend\models\Sklad_inventory;
//use frontend\models\Sklad;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;


?>


<div class="table_with">
    <h1>Накладные ИНВЕНТАРИЗАЦИИ по складу </h1>

    <?php
    echo Html::a( 'Создать новую накладную', [ 'create_new' ], [ 'class' => 'btn btn-success' ] );
    ?>

    <?php


    //   if( Yii::$app->user->identity->group_id >= 70 ) {
    //       echo  Html::a('Залить Инвентаризации Автопарка (по всем автобусам)', ['create_new_park'], ['class' => 'btn btn-success']);
    //   }

    ?>

    <?php
    $dataProvider->pagination->pageParam = 'in-page';
    $dataProvider->sort->sortParam = 'in-sort';

    $dataProvider->setSort(
        [
            'defaultOrder' => [ 'id' => SORT_DESC ], ]
    );
    ?>


    <!--    --><?php //Pjax::begin(['options'=>[ 'autocomplete' => 'off' ]]); ?>

    <?php Pjax::begin(); ?>

    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,


            'columns' => [

                [
                    'attribute' => 'id',
                    'contentOptions' => [ 'style' => ' width: 72px;' ],
                ],
                [
                    'header' => '',
                    'contentOptions' => function () {
                        if ( Yii::$app->user->identity->group_id >= 30 &&
                            Yii::$app->user->identity->group_id < 40 )
                            return [ 'style' => 'background-color: #4acc6061;' ];
                        else
                            return [ 'style' => ' width: 72px;' ];
                    },

                    'content' => function ( $model ) {

                        $url = Url::to( [ 'update?id='.$model->_id ] );

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
                    'attribute' => 'wh_destination',
                    'label' => 'КОНТРАГЕНТ',


                    /// Уникальные ТОЛЬКО ТУТ ИМЕНА
                    'filter' => Sprwhtop::ArrayNamesWithIds(
                        Sklad_inventory::ArrayUniq_Wh_Ids()
                    ),

                    //'filter' => Sklad_past_inventory::ArrayUniq_Wh_Ids() ,
                    'contentOptions' => ['style' => 'width: 30px;'],
                ],


                [
                    'attribute' => 'wh_destination_element',     //'wh_home_number',
                    'contentOptions' => ['style' => 'width: 30px;'],
                    /// Уникальные ТОЛЬКО ТУТ ИМЕНА
                    'filter' => Sprwhelement::ArrayNamesWithIds(
                        Sklad_inventory::ArrayUniq_WhElements_Ids()
                    ),

                    'headerOptions' => [ 'autocomplete' => 0 ],
                ],


                [
                    'attribute' => 'group_inventory',
                    'label' => 'Инв',
                    'contentOptions' => [ 'style' => ' width: 72px;' ],
                ],
                [
                    'attribute' => 'group_inventory_name',
                    'label' => 'Инв.название',

                ],



                [
//                    'attribute' => 'dt_create',
                    'attribute' => 'dt_create_timestamp',
                    'value' => function ( $data ) {

                        if ( isset( $data[ 'dt_create_timestamp' ] ) && !empty( $data[ 'dt_create_timestamp' ] ) ) {
                            return ( date( 'd.m.Y h:i:s', $data[ 'dt_create_timestamp' ] ) );
                        }

                        //return  date( 'd.m.Y h:i:s',strtotime('now') );
                        return 0;
                    },

                    'contentOptions' => [
                        'style' => ' width: 170px;',
                        'autocomplete' => "off",
                    ],
                    'format' => [
                        'datetime',
                        'php:d.m.Y H:i:s',
                    ],
                ],

                [
                    'attribute' => 'user_name',
                    'contentOptions' => [ 'style' => ' ;min-width: 15px;' ],
                ],



                [ 'class' => 'yii\grid\ActionColumn',
                    'header' => '',
                    'contentOptions' => [ 'style' => ' width: 50px;' ],
                    'headerOptions' => [ 'width' => '10' ],

                    'template' => '{delete}',

                    'buttons' => [
                        'delete' =>
                            function ( $url, $model ) {
                                //    dd($url);

                                if ( Yii::$app->getUser()->identity->group_id == 100 ) {
                                    $url = Url::to( [ '/sklad_inventory/delete?id='.$model->_id ] );

                                    return Html::a(
                                        '<span class="glyphicon glyphicon-remove " style="color:red"></span>',
                                        $url
                                    );
                                }

                                return Html::a( '<span class="glyphicon " ></span>', $url );
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



