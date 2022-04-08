<?php

use miloschuman\highcharts\Highcharts;


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
            'subtitle' => ['text' => 'c ' . $dt_start . '  по ' . $dt_stop],


            'series' =>
                [
                    [
                        'name' => '',
                        'colorByPoint' => true,
                        'data' => $data
                    ],
                ]
        ]
    ]);


    ////        'chart' => ['type'=> 'bar' ],
    //        'chart' => ['type'=> 'column' ],
    ?>

</div>