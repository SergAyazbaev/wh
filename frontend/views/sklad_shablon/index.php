<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* Тут создаются ШАБЛОНЫ для работы с накладными */
/* ШАБЛОНЫ пишут кладовшики сами для себя */

//$this->title = 'Список ТехЗаданий';
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="exc">
    <h1><?= Html::encode($this->title) ?></h1>
</div>



<div class="pv-index" style="display: inline-block;">
    <div class="exc2">
        <p class="breb_crumbs_pice">
            <?
                $para_str='';

                    if ( isset($para['postpv']) && !empty($para['postpv'])){
                        foreach ($para['postpv'] as $key => $item) {
                            $para_str.= '&postpv['.$key.']='.$item.'';
                        }
                    }

    //                if ( $para['sort'] )
    //                    $para_str.='&sort='.$para['sort'];
            ?>
        </p>

        <?
        if( Yii::$app->getUser()->identity->group_id < 50 && Yii::$app->getUser()->identity->group_id >= 40){
            echo Html::a('Создать ШАБЛОН', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
        <?
        if( Yii::$app->getUser()->identity->group_id >= 50 ){
            echo Html::a('Создать ШАБЛОН', ['create'], ['class' => 'btn btn-success']);
        }
        ?>

    </div>
</div>





<?php  Pjax::begin(); ?>


<div class="table_with">
    <?= GridView::widget([
        'dataProvider' => $dataProvider_shablon,
        'filterModel' => $searchModel_shablon,
        'columns' => [

            ['class' => 'yii\grid\ActionColumn',
                'template' => ' {update} ',
                'contentOptions' =>function($data){
                    if ( $data['user_id']!=Yii::$app->getUser()->identity->id )
                    {
                        return ['style' => 'color:blue;'];
                    }
                    else
                        return ['style' => 'color:green;'];
                },

                'buttons' => [
                    'update' => function ($url,$model) {
                        if( $model['user_id']==Yii::$app->getUser()->identity->id){
                            return Html::a(
                                '<span class="glyphicon glyphicon-edit"></span>',
                                $url);
                        }
                        return '';

                    },
                ],


            ],

            [
                'attribute'=> 'id',
                'value'=> 'id',
                'contentOptions' => ['style' => ' ;min-width: 50px;width: 80px;'],

                //'contentOptions' =>['class' => 'table_class','style'=>'display:block;'],

//                'contentOptions' =>function($data){
//                    if ( $data['dt_deadline']<date("Y-m-d",strtotime('now')) ){
//
//                        return ['style' => 'color: #de3c3c;font-weight: bold;font-size: 14px;'];
//                    }
//                    elseif ( $data['dt_deadline']>date("Y-m-d",strtotime('now') )
//                        && $data['dt_deadline']<date("Y-m-d",strtotime('now +1 day')) ){
//                        return ['style' => 'color:blue;'];
//                    }
//                    else
//                        return ['style' => 'color:green; display:block;'];
//                },

            ],

            'shablon_name',

            [
                'attribute'=> 'user_id',
                'value'=> 'user_id',
                'contentOptions' => ['style' => ' ;min-width: 50px;width: 80px;'],
            ],

            [
                'attribute'=> 'user_name',
                'value'=> 'user_name',
                'contentOptions' => ['style' => ' ;min-width: 50px;width: 180px;'],
            ],

            [
                 'class' => 'yii\grid\ActionColumn',
                 'template' => '{delete}',
                 'buttons' => [
                        'delete' => function ($url,$model) {
                          if( $model['user_id']==Yii::$app->getUser()->identity->id){
                              return Html::a(
                                  '<span class="glyphicon glyphicon-trash"></span>',$url,
                                  ['data-confirm' => 'Удалить?']
                              );
                          }

                          return '';
                          },
                 ],

            ],

        ],

    ]); ?>

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
$this->registerJs($script, yii\web\View::POS_READY);
?>



