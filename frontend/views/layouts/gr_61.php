<?php

/***
 * Бухгалтер Назира !!!!!!!!(верх) 61
 ***/


$menuItems = [

    [
        'label' => 'ОСТАТКИ по складам',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'ЕЖЕДНЕВНЫЕ ОСТАТКИ',
                'url' => ['/past_inventory/index'],
            ],

        ],
    ],


    [
        'label' => 'Инвентаризация ЦС',
        'url' => ['/past_inventory_cs_'],
        'items' => [

            [
                'label' => 'Инвентаризации по ЦС',
                'url' => ['/stat_svod/index_svod_cs'],
            ],


            ['label' => '---- Свежие ---'],

            [
                'label' => 'ЕЖЕДНЕВНЫЕ ОСТАТКИ по ЦС',
                'url' => ['/past_inventory_cs_/index'],
            ],


            ['label' => '-------'],


            [
                'label' => 'Цепочка движений По Штрихкоду.',
                'url' => ['/stat_ostatki/barcode_to_naklad?bar=040193'],
            ],

            [
                'label' => 'Аналитика. Цепочка движений По Штрихкоду. (ЦС)',
                'url' => ['/stat_ostatki/barcode_to_naklad_analitics?bar=040193'],
            ],


            ['label' => '-------'],


            [
                'label' => 'Ведомость по ЦС (Приход/Расход) ',
                'url' => ['/stat_svod/index_move_cs'],
            ],

            [
                'label' => 'Ведомость по одному ЦС ',
                'url' => ['/stat_svod/index_move_cs_one?сs_id=177'],
            ],


        ],
    ],

    [
        'label' => 'Статистика',
        'url' => ['/'],
        'items' => [

//                        [
//                            'label' => 'По штрихкодам. По Группам',
//                            'url' => ['/stat_ostatki'],
//                        ],
//                        ['label' => '-------'],
            [
                'label' => 'По Базе (Приход/Расход) ',
                'url' => ['/stat_svod/index_svod'],
            ],
            ['label' => '-------'],
            [
                'label' => 'По Оконечным Складам',
                'url' => ['/stat_svod/index_svod_pe'],
            ],

//                        ['label' => '-------'],
            // ['label' => 'Количественный отчет по Складу 4190 (Жанель)', 'url' => ['/stat_balans/kolish?sklad=4190']],
//                        [
//                            'label' => 'Количественный отчет по Складу 86 (Виктор)',
//                            'url' => ['/stat_balans/kolish?sklad=86'],
//                        ],

        ],
    ],

    [
        'label' => 'Партии',
        'url' => ['/barcode_pool'],
        'items' => [

            [
                'label' => 'Партии',
                'url' => ['/barcode_consignment'],
            ],
            [
                'label' => 'Партии Без ШтрихКодов',
                'url' => ['/consignment'],
            ],


            [
                'label' => 'Партии. Заливка от Назиры',
                'url' => ['/barcode_consignment/create_new_consigment'],
            ],

            ['label' => '-------'],


            [
                'label' => 'Пул Баркодов',
                'url' => ['/barcode_pool'],
            ],
            [
                'label' => 'Пул Баркодов со списком ПАРТИЙ',
                'url' => ['/barcode_pool/index_with'],
            ],

            [
                'label' => 'Пул. Заливка от Назиры',
                'url' => ['/barcode_pool/create_new_pool'],
            ],


        ],
    ],
    [
        'label' => 'Справочники',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'ШАБЛОНЫ Накладных',
                'url' => ['/sklad_shablon'],
            ],
            ['label' => '-------------'],

            [
                'label' => 'Амортизация. АСУОП',
                'url' => ['/globalam'],
            ],
            [
                'label' => 'Амортизация. АСУОП элементы ',
                'url' => ['/globalamelement'],
            ],
            ['label' => '-------------'],

            [
                'label' => 'Cписание. УМЦ',
                'url' => ['/global'],
            ],
            [
                'label' => 'Cписание. УМЦ элементы ',
                'url' => ['/globalelement'],
            ],
            ['label' => '-------------'],


            [
                'label' => 'Cклады ',
                'url' => ['/sprwhtop'],
            ],
            [
                'label' => 'Cклады Element',
                'url' => ['/sprwhelement'],
            ],
            ['label' => '-------------'],

        ],
    ],
];
?>