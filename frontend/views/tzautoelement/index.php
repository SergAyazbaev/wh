
<?php
//use miloschuman\highcharts\Highcharts;

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel frontend\models\postsprtype */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список автобусов.';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<div class="sprtype-index">
    <?

    $tz_id = "";

    if (isset($para['tz_id'])) $tz_id = $para['tz_id'];
    if (isset($model['tz_id'])) $tz_id = $model['tz_id'];
    ?>

    <h1><?= Html::encode($this->title) ?> Тех задание № <?= $tz_id ?> </h1>


    <p>

        <?= Html::a('Создать новый Элемент склада',
            [
                "create?tz_id=" . $tz_id
            ],
            ['class' => 'btn btn-success'])

        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\ActionColumn',
                'header'=>'',
                'headerOptions' => ['width' => '40'],
                'template' => ' {update}',

            ],

            'tz_id',
            'id',
//            'parent_id',
//            'name',
            'nomer_borta',
            'nomer_gos_registr',
            'nomer_vin',
            'tx',

            ['class' => 'yii\grid\ActionColumn',
                'header'=>'',
                'headerOptions' => ['width' => '40'],
                'template' => ' {delete}',

            ],

            [
                'header'=>'',
                'contentOptions' => ['style' => ' width: 50px;'],

                'content' => function($model) {
                     //dd($model);

                    $url = Url::to(['/tzautoelement/delete?id='. $model->_id.'&tz_id='.$model->tz_id ]);

                    return Html::a('Вн', $url, [
                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
                        'data-id' => $model->_id,

                    ]);
                }
            ],

        ],
    ]);



    ?>

    <?= Html::Button('&#8593;',
        [
            'class' => 'btn btn-warning',
            'onclick'=>"window.history.back();"
        ])
    ?>

</div>

<?

//echo Highcharts::widget([
//    'options' => [
//        'title' => ['text' => 'Темпы отгрузки'],
//        'xAxis' => [
//            'categories' => ['Дни']
//        ],
//        'yAxis' => [
//            'title' => ['text' => 'Комплекты за период ']
//        ],
//        'series' => [
//            ['name' => 'Склад №1', 'data' => $xx  ],
//
//            ['name' => 'Склад №20', 'data' => [1, 12, 16, 17, 14, 21 ]],
//            ['name' => 'Склад №30', 'data' => [1, 12, 14, 15, 14, 17 ]],
//            ['name' => 'Склад №1',  'data' => [1, 12, 14, 15, 14, 13 ]],
//            ['name' => 'Склад №7',  'data' => [1, 12, 17, 17, 14, 17 ]],
//        ]
//    ]
//]);

//echo Highcharts::widget([
//    'options' => [
//        'chart' => ['type'=> 'bar' ],
//        'title' => ['text' => 'Темпы отгрузки'],
//        'xAxis' => [
//            'categories' => ['Дни']
//        ],
//        'yAxis' => [
//            'title' => ['text' => 'Комплекты за период ']
//        ],
//        'series' => [
//
//            ['name' => 'Склад №20', 'data' => [1, 12, 16, 17, 14, 21 ]],
//            ['name' => 'Склад №30', 'data' => [1, 12, 14, 15, 14, 17 ]],
//            ['name' => 'Склад №1',  'data' => [1, 12, 14, 15, 14, 13 ]],
//            ['name' => 'Склад №7',  'data' => [1, 12, 17, 17, 14, 17 ]],
//        ]
//    ]
//]);

?>

