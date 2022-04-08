<?php


$menuItems = [
    [
        'label' => 'Старт',
        'url' => ['/site'],
    ],


    [
        'label' => 'Склад ПРОШИВКИ',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'Склад 3524',
                'url' => ['/agent_tha/?otbor=3524'],
            ],
        ],
    ],
];
?>