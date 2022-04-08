<?php


use yii\helpers\Html;
use yii\grid\GridView;



/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postpvaction */
/* @var $dataProvider yii\data\ActiveDataProvider */



if(Yii::$app->request->queryParams['id']>0)
    $this->title = 'История Ремонтов (Инв.№ '.Yii::$app->request->queryParams['id'].')';
else
    $this->title = 'История Ремонтов';


$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pv-action-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?
            if( Yii::$app->request->queryParams['id']>0 ) {
                echo Html::a('Запись о ремонте', ['create?id='.Yii::$app->request->queryParams['id']],['class' => 'btn btn-success']);
            }else{
                //echo Html::a('Запись о ремонте', ['create'],['class' => 'btn btn-success']);
            }
        ?>
    </p>









    <?
    $arrSizes=[ 0 => 'Small', 1 => 'Middle', 2=>'Large' ];

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],


//            [
//
//                'label' => 'list_details',
//
//                //'value' =>  [$model->list_details]
//
////                'value' => function($data){
////                    return Html::a(
////                        'Перейти',
////                        $data->list_details,
////                        [
////                            'title' => 'Смелей вперед!',
////                            'target' => '_blank'
////                        ]
////                    );
////                }
//
//             'value' => function($data){
//                html::dropDownList(
//                     frontend\models\SprThing::find()->select(  ['name']  )-> indexBy('name') ->column(),
//                     [
//                         'prompt' => 'Выбор работы',
//                         'multiple' => 'multiple',
//                         'size' => '10',
//                     ]
//                     );
//                }
//
//
//            ],



            [
                'attribute'=> 'id',
                'value'=> 'id',
                'contentOptions' => ['style' => 'width: 25px;overflow: auto;'],
            ],

            'name',



            [
                'attribute'=> 'list_details',
                'value'=> 'list_details',

            ],

            [
                'attribute'=> 'dt_create',
                'value'=> 'dt_create',
                'format' =>  ['date', 'dd.M.yy HH:i'],
                'contentOptions' => ['style' => 'max-width: 15px;overflow: auto;'],
            ],

//            'type_bag',
            'type_action',
            'comments',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);

    ?>
</div>

