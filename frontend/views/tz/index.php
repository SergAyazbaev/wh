<?php

use yii\grid\GridView;
use yii\helpers\Html;
//use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Url;


//if ( isset( $model ) ) {
//    $start_date = $model->dt_deadline1;
//    $end_date = $model->dt_deadline2;
//}
//
//
//if ( empty( $start_date ) || empty( $end_date ) ) {
//    $start_date = Date( 'd.m.Y', strtotime( 'now -3 day' ) );
//    $end_date = Date( 'd.m.Y', strtotime( 'now +1 day' ) );
//}
//
//if ( isset( $model ) ) {
//    $model->dt_deadline1 = $start_date;
//    $model->dt_deadline2 = $end_date;
//}


$this->title = 'Список ТехЗаданий';
//$this->params['breadcrumbs'][] = $this->title;

?>


<style>
    .table_with {
        margin-top: 0px;
    }

    .container22 {
        padding: 0px;
        margin: 0px;
    }
</style>

<div class="table_with">


    <div class="exc">

        <h1><?= Html::encode( $this->title ) ?></h1>

    </div>


    <div class="pv-index" style="display: inline-block;">

        <div class="exc1">
        </div>

        <div class="exc2">

            <p class="breb_crumbs_pice">
                <?php
                $para_str = '';
                ?>
            </p>

            <div class="date_period">

                <?php
                //                if ( Yii::$app->getUser()->identity->group_id == 50 ) {
                //                    echo Html::a( 'Создать ТЗ (Old)', [ 'create' ], [ 'class' => 'btn btn-success' ] );
                //                }
                ?>

                <?php
                if ( Yii::$app->getUser()->identity->group_id == 50 ) {
                    echo Html::a( 'Создать ТЗ', [ 'create_new' ], [ 'class' => 'btn btn-success' ] );
                }
                ?>


                <?php $xx = 0;

                if ( !isset( $para[ 'posttz' ][ 'three' ] ) ) {
                    echo Html::a(
                        'Период',
                        [ '/tz/index?posttz[three]=1' ],
                        [ 'class' => 'btn btn-default' ]
                    );

                }

                if ( isset( $para[ 'posttz' ][ 'three' ] ) ) {

                    if ( $para[ 'posttz' ][ 'three' ] == 1 ) {
                        $xx = 2;
                        echo Html::a(
                            '1 Просрочено',
                            [ '/tz/index?posttz[three]='.$xx.'' ],
                            [ 'class' => 'btn btn-danger' ]
                        );

                    }
                    if ( $para[ 'posttz' ][ 'three' ] == 2 ) {
                        $xx = 3;
                        echo Html::a(
                            '2 Сегодня',
                            [ '/tz/index?posttz[three]='.$xx.'' ],
                            [ 'class' => 'btn btn-warning' ]
                        );

                    }
                    if ( $para[ 'posttz' ][ 'three' ] == 3 ) {
                        $xx = 1;
                        echo Html::a(
                            '3 Еще есть время',
                            [ '/tz/index?posttz[three]='.$xx.'' ],
                            [ 'class' => 'btn btn-info' ]
                        );
                    }
                }


                ?>

                <?php
                if ( Yii::$app->getUser()->identity->group_id == 50 ) {
                    echo Html::a( 'Добавить Новый Автобус', [ 'new_bus' ], [ 'class' => 'btn btn-primary' ] );
                }
                ?>


            </div>

        </div>
    </div>


    <?php Pjax::begin(); ?>


    <div class="table_with">
        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [


                    [
                        'attribute' => 'id',
                        'value' => 'id',
                        'contentOptions' => [ 'style' => ' width: 72px;' ],
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => ' {update} ',
                    ],

                    'name_tz',

                    'wh_cred_top_name',

                    [
                        'attribute' => 'dt_create_timestamp',
                        'value' => 'dt_create_timestamp',
                        'format' => [ 'datetime', 'php:d.m.Y (H:i)' ],
                    ],

                    [
                        'attribute' => 'dt_deadline_timestamp',
                        'value' => 'dt_deadline_timestamp',

                        'format' => [ 'datetime', 'php:d.m.Y (H:i)' ],

                        'contentOptions' => function ( $data ) {

//                        if ( $data[ 'dt_deadline' ] < date( "d.m.Y", strtotime( 'now' ) ) ) {
//
//                            return [ 'style' => 'color: #de3c3c;font-weight: bold;font-size: 14px;' ];
//                        } elseif ( $data[ 'dt_deadline' ] > date( "d.m.Y", strtotime( 'now' ) )
//                            && $data[ 'dt_deadline' ] < date( "d.m.Y", strtotime( 'now +1 day' ) ) ){
//                            return [ 'style' => 'color:blue;' ];
//                        } else
//                            return [ 'style' => 'color:green;' ];


                            if ( $data[ 'dt_deadline_timestamp' ] < strtotime( 'now -1 day' ) ) {
                                //ddd($data);
                                return [ 'style' => 'color: #de3c3c;font-weight: bold;font-size: 14px;' ];
                            } elseif ( $data[ 'dt_deadline_timestamp' ] > strtotime( 'now -1 day' )
                                && $data[ 'dt_deadline_timestamp' ] < strtotime( 'now +1 day' ) ){
                                return [ 'style' => 'color:blue;' ];
                            } else
                                return [ 'style' => 'color:green;' ];


                        },
                    ],


                    [
                        'attribute' => 'status_state',
                        'value' => 'status_state',
                        'content' => function ( $model ) {
                            if ( isset( $model[ 'status_state' ] ) ) {
                                if ( $model[ 'status_state' ] != 1 ) {

                                    $url = Url::to( [ '/tz/update?id='.$model->_id ] );

                                    return Html::a(
                                        'Подготовка', $url, [
                                                        'class' => 'btn btn-success btn-xs',
                                                        'date-id' => $model->id,
                                                        'method' => 'PUT',
                                                        'data-pjax' => 0,
                                                    ]
                                    );
                                } else{
                                    $url = Url::to( [ '/tz/read?id='.$model->_id ] );

                                    return Html::a(
                                        'В работе', $url, [
                                                      'class' => 'btn btn-secondary btn-xs',
                                                      'date-id' => $model->id,
                                                      'method' => 'POST',
                                                      'data-pjax' => 0,
                                                  ]
                                    );

                                }
                            }


                            $url = Url::to( [ '/tz/update?id='.$model->_id ] );

                            return Html::a(
                                'Подготовка', $url, [
                                                //'class' => 'btn btn-secondary btn-xs',
                                                'class' => 'btn btn-success btn-xs',
                                                'date-id' => $model->id,
                                                'method' => 'POST',
                                                'data-pjax' => 0,
                                            ]
                            );

                        }

                    ],

                    [
                        //'label' => 'Передано в работу',
                        'attribute' => 'status_create_date',
                        'value' => 'status_create_date',
                        'format' => [ 'datetime', 'php:d.m.Y (h:i:s)' ],
                        'content' => function ( $model ) {
                            if ( isset( $model[ 'status_create_date' ] ) ) {
                                if ( !empty( $model[ 'status_create_date' ] ) ) {
                                    return $model[ 'status_create_date' ];
                                }
                            }
                            return '';
                        }

                    ],


                    [
                        'attribute' => 'multi_tz',
                        'value' => 'multi_tz',
                        'contentOptions' => [ 'style' => ' min-width: 72px; width: 103px;' ],
                    ],


                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                        'buttons' => [
                            'delete' => function ( $url, $model ) {

                                if ( $model[ 'status_state' ] === 1 ||
                                    Yii::$app->getUser()->identity->group_id != 50 ||
                                    $model[ 'user_create_id' ] != Yii::$app->getUser()->identity->id ) {
                                    return '';
                                }

                                //return Html::a("<span class='glyphicon' >Del</span>", [$url]);
                                return '';

                            },

                        ],


                    ],

                ],

            ]
        ); ?>

    </div>
</div>


<?php Pjax::end(); ?>





<?php
$script = <<<JS

//  $('#w2').hover(function() {
//   
//       $(this).css({
//         "top": "0px",        
//       });
//      
//       $(".navbar-brand").css({        
//             "font-size": "40px",        
//             "top": "-40px",
//             "position": "absolute",
//             "display": "block",        
//       });
//      
//       $(".navbar-nav > li").css({            
//             "float": "left",        
//             "top": "5px",          
//       });     
//
//
//
// }, function(){
//     
//       $(this).animate({                 
//          "top": "-30px",
//       }, 700 );
//      
//       $(".navbar-brand").animate({
//             "margin-left": "-15px",
//             "font-size": "20px",
//             "top": "0px",            
//             "position": "absolute",
//             "display": "block",
//       },700);     
//       $(".navbar-nav > li").animate({            
//             "float": "left",        
//             "top": "15px",          
//       },700);     
//      
// });
              
//         $("h1").css("display", "none");
//       
//         $(".wrap >.container").css("padding", "26px 0px");
//   }

JS;
$this->registerJs( $script, yii\web\View::POS_READY );
?>



