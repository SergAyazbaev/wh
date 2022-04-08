<?php
/***
 * Бухгалтер САНЖАР (низ) 65 (Замена оборудования на ЦС)
 ***/


$menuItems = [

    [
        'label' => 'Инвентаризация',
        'url' => ['/'],
        'items' => [

            [
                'label' => 'ИНВЕНТАРИЗАЦИИ ',
                'url' => ['/sklad_inventory/index'],
            ],
            ['label' => '-------'],
            [
                'label' => 'ЕЖЕДНЕВНЫЕ ОСТАТКИ',
                'url' => ['/past_inventory/index'],
            ],
//                        ['label' => '---- обрабочик ---'],
//                        [
//                            'label' => 'Получить свежие остатки',
//                            'url' => ['/stat_balans/past_inventory_summary'],
//                        ],


        ],
    ],


    [
        'label' => 'Инвентаризация ЦС',
        'url' => ['/past_inventory_cs_/index'],
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
                'url' => ['/stat_svod/index_move_cs_one'],
            ],


        ],
    ],

    [
        'label' => 'Партии',
        'url' => ['/barcode_pool'],
        'items' => [

            [
                'label' => 'Пул Баркодов со списком ПАРТИЙ',
                'url' => ['/barcode_pool/index_with'],
            ],

        ],
    ],


    [
        'label' => 'Справочники',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'ШАБЛОНЫ Накладных',
                'url' => ['/sklad_shablon/index'],
            ],
            ['label' => '-------------'],

            [
                'label' => 'Амортизация. АСУОП',
                'url' => ['/globalam/index'],
            ],
            [
                'label' => 'Амортизация. АСУОП элементы ',
                'url' => ['/globalamelement/index'],
            ],
            ['label' => '-------------'],

            [
                'label' => 'Cписание. УМЦ',
                'url' => ['/global/index'],
            ],
            [
                'label' => 'Cписание. УМЦ элементы ',
                'url' => ['/globalelement/index'],
            ],
            ['label' => '-------------'],


            [
                'label' => 'Cклады ',
                'url' => ['/sprwhtop/index'],
            ],
            [
                'label' => 'Cклады Element',
                'url' => ['/sprwhelement/index'],
            ],
            ['label' => '-------------'],

        ],
    ]
];

//            if ( Yii::$app->getUser()->identity->username == 'ss' ) {
//                $menuItems [] =
//
//                    [
//                        'label' => 'SS',
//                        'url' => [ '/' ],
//                        'items' => [
//
//                            [
//                                'label' => 'SCAN ',
//                                'url' => [ '/scan.php' ],
//                            ],
//                            [
//                                'label' => 'OCR - read text ',
//                                'url' => [ '/stat_balans/ocr' ],
//                            ],
//                            [
//                                'label' => 'Sklad_Freak ',
//                                'url' => [ '/sklad_freak/update?id=5dba5afe80a063273c0040af&sklad=4229' ],
//                            ],
//
//                        ],
//
//                    ];
//            }

?>