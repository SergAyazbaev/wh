<?php
/****
 * СКЛАД (Асемтай) (41)
 */


$menuItems = [
    [
        'label' => 'Старт',
        'url' => ['/site'],
    ],

    [
        'label' => 'Основной склад',
        'url' => ['/'],
        'items' => $array_str,
    ],
    [
        'label' => 'Склад ПРОШИВКИ ',
        'url' => ['/'],
        'items' => [
            //                            ['label' => 'Склад 1925', 'url' => ['/sklad/in?otbor=1925']],
            [
                'label' => 'Склад Обсл.Терминалов',
                'url' => ['/sklad/in?otbor=3524'],
            ],
        ],
    ],

    [
        'label' => 'Статистика',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'Аналитика. Цепочка движений По Штрихкоду. (ЦС)',
                'url' => ['/stat_ostatki/barcode_to_naklad_analitics'],
            ],
            ['label' => '-------'],

            [
                'label' => 'По штрихкодам. По Группам',
                'url' => ['/stat_ostatki/index'],
            ],

            ['label' => '-------'],

            [
                'label' => 'Количественный отчет по Складу 7188 (РЕМОНТНЫЙ)',
                'url' => ['/stat_balans/kolish?sklad=7188'],
            ],

            ['label' => '---- Своды ---'],
            [
                'label' => 'Сводная статистика',
                'url' => ['/svod/tree'],
            ],

        ],
    ],


];
?>