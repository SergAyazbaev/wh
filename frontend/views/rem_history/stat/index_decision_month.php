<?php

use miloschuman\highcharts\Highcharts;

//use miloschuman\highcharts\SeriesDataHelper;


$this->title = 'РемОтдел. Статистика';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .tree_land {
        width: 90%;
        /*min-width: 730px;*/
        min-width: 730px;

        background-color: cornsilk;
        padding: 15px;
        margin: 20px 5%;
    }

    @media (max-width: 890px) {
        .tree_land {
            width: 100%;
            margin: 0;
            padding: 0;
        }

    }

    @media (max-width: 794px) {
        .tree_land {
            width: 800px;
            overflow: auto;
            /*display: none;*/
        }
    }
</style>

<div class="tree_land">


    <?php
    echo Highcharts::widget([
        'options' => [
            'chart' => ['type' => 'pie',
                'height' => (9 / 16 * 80) . '%' // 16:9 ratio
            ],

//            'colors' => ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],

            'title' => ['text' => 'Неисправности устройств'],
            //'subtitle' => ['text' => 'за месяц'],
            'subtitle' => ['text' => 'за 30 дней'],


            'series' =>
                [
                    [
                        'name' => '',
                        'data' => $data,

                        'colorByPoint' => true,

//                        'name' => 'Результат',
//                        'type' => 'areaspline',
//                        'data' => new SeriesDataHelper($data, ['date:timestamp', 'result:float']),

                        'marker' => [
                            'enabled' => true,
                            'radius' => 1,
                            'states' => ['hover' => ['radius' => 20]],
                        ],
                        'dataLabels' => [
                            'enabled' => true,
                        ],

                    ],
                ]

//               'tooltip'=> ['split'=>true],
//                'series' => [
//                    [
//                        'type' => 'areaspline',
//                        'name' => 'Результат',
//                        'data' => new SeriesDataHelper($dataProvider, ['date:timestamp', 'result:float']),
//                        'yAxis' => 0,
//                        'marker' => [
//                            'enabled'=> true,
//                            'radius'=> 3,
//                            'states'=>['hover'=>['radius'=>2]],
//                        ],
//                        'dataLabels'=> [
//                            'enabled'=>true,
//                        ],
//                    ],
//                    [
//                        'type' => 'area',
//                        'name' => 'Просрочка (дней)',
//                        'data' => new SeriesDataHelper($dataProvider, ['date:timestamp', 'days_overdue:int']),
//                        'yAxis' => 1,
//                    ],
//                ],
        ]
    ]);


    ////        'chart' => ['type'=> 'bar' ],
    //        'chart' => ['type'=> 'column' ],
    ?>

</div>
