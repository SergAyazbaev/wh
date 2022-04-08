<?php

use miloschuman\highcharts\Highcharts;


$this->title = 'МТС. Статистика';
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
            /*transform: scale(0.9);*/
            width: 100%;
            margin: 0;
            padding: 0;
        }

    }

    @media (max-width: 794px) {
        .tree_land {
            display: none;
        }
    }
</style>


<div class="tree_land">
    <?php
    echo Highcharts::widget([
        'options' => [
            //'colors' => ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
            'colors' => ['#50B432'],

//            'chart' => ['type' => 'spline'],
            'chart' => [
                'type' => 'column',
                'height' => (9 / 16 * 70) . '%' // 16:9 ratio
            ],

            'title' => ['text' => 'Мобильные Технические Специалисты за месяц'],

            'xAxis' => [
                //'categories' => ['Дни']
                'categories' => $text_days_array
            ],

            'yAxis' => [
                'title' => ['text' => 'Произведено замен (шт.)']
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