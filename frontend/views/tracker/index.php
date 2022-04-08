

<?

//dd($searchModel);

use yii\grid\GridView;

echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [  'attribute'=>'id', 'label'=>'###'],


                'name',

                  [  'attribute'=>'dt_create',
                      'label'=>'Дата',
                      'format' =>  ['date', 'php:d.m.Y  H:i:s'],
                  ],


                'qr_code',
                'hr_code',
//            [
//                'attribute'=>'machineType.id',
//                'label'=>'ID устройства',
//            ],
            [
                'attribute'=>'machineType.name',
                'label'=>' Тип устройства',
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Действия',
                'headerOptions' => ['width' => '80'],
                'template' => '{view} {update} {delete}{link}',
            ],
        ],
    ]);



