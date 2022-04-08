<?php

use miloschuman\highcharts\Highcharts;


$this->title = 'РемОтдел. Статистика';
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<meta name="viewport" content="width=device-width, initial-scale=0.86, maximum-scale=5.0, minimum-scale=0.86">
<!--<meta name="viewport" content="width=500, initial-scale=1">-->
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

    @media (max-width: 800px) {
        .tree_land {
            /*display: none;*/
            min-width: 800px;
            min-height: 500px;
            overflow: auto;
        }
    }

    @media all and (orientation:portrait) {
        /* стили для портрета */

    }
</style>

<div class="tree_land">


    <?php
    echo Highcharts::widget([
        'options' => [
            //'chart' => ['type' => 'spline'],

            'chart' => [        // inverted: true,
                'zoomType' => 'x',
//                  'height'=> (9 / 16 * 70) . '%' // 16:9 ratio
                 'height' => 500,
                 'visible' => true,
            ],

            'title' => ['text' => 'Ремонтный отдел'],
            'subtitle' => ['text' => 'за 31 день'],

            'colors' => ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],


            'xAxis' => [
                'categories' => $text_days_array
            ],

            'yAxis' => [
                'title' => ['text' => 'Произведено ремонтов (шт.)']
            ],

            'series' => $series_array

            //        'series' =>
            //            [
            //                ['name' => 'Склад №20', 'data' => [1, 9, 12, 10, 9, 2]],
            //                ['name' => 'Склад №30', 'data' => [1, 5, 7, 9, 14, 17]],
            //                ['name' => 'Склад №1', 'data' => [1, 12, 14, 15, 11, 13]],
            //                ['name' => 'Склад №7', 'data' => [1, 12, 17, 17, 14, 17]],
            //            ]
        ]
    ]);


    ////        'chart' => ['type'=> 'bar' ],
    //        'chart' => ['type'=> 'column' ],
    ?>

</div>