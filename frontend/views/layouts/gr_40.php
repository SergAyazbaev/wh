<?php
/**
 *
 *Главный СКЛАД (Виктор) (40)
 *
 */



$menuItems = [

    [
        'label' => 'Старт',
        'url' => ['/site'],

        'items' => [
            [
                'label' => 'Аналитика. Цепочка движений По Штрихкоду. (ЦС)',
                'url' => ['/stat_ostatki/barcode_to_naklad_analitics?bar=040193'],
            ],
        ],
    ],

//    [
//        'label' => 'Основной склад',
//        'url' => ['/'],
//        'items' => array_str,
//    ],
  [
        'label' => 'Инвентаризация WH',
        'url' => ['/'],
        'items' => [


            ['label' => '---- Своды ---'],
            [
                'label' => 'Сводная статистика',
                'url' => ['/svod/tree'],
            ],

            ['label' => '-'],

            ['label' => '---- Цепочка ---'],
            [
                'label' => 'Аналитика. Цепочка движений По Штрихкоду. (ЦС)',
                //'url' => [ '/stat_ostatki/barcode_to_naklad_analitics?bar=040193' ],
                'url' => ['/stat_ostatki/barcode_to_naklad_analitics'],
            ],


        ],
    ],
    [
        'label' => 'Аналитика',
        'url' => ['/'],
        'items' => [

            [
                'label' => 'Аналитика. Цепочка движений По Штрихкоду. (ЦС)',
                'url' => ['/stat_ostatki/barcode_to_naklad_analitics'],
            ],

            [
                'label' => 'По штрихкодам. По Группам',
                'url' => ['/stat_ostatki/index'],
            ],

            ['label' => '---- Инв ---'],

            [
                'label' => 'WH. Инвентаризации',
                'url' => ['/sklad_inventory_wh/index'],
            ],

            ['label' => '---- Своды ---'],
            [
                'label' => 'Сводная статистика',
                'url' => ['/svod/tree'],
            ],

            ['label' => '---- ШТУЧНЫЙ. БЕЗ ШТРИХКОДА ---'],
           [
                'label' => 'ЕЖЕДНЕВНЫЕ ОСТАТКИ',
                'url' => ['/past_inventory'],
            ],



        ],
] ,

    [
        'label' => 'Ремонты',
        'url' => ['/rem_history/index'],
        'items' => [
            ['label' => ''],


            [
                'label' => 'Статистика MTC',
                'url' => ['/rem_history/stat'],
            ],

            [
                'label' => 'Статистика REM',
                'url' => ['/rem_history/stat_rem'],
            ],

            [
                'label' => 'Неисправности %, Неделя',
                'url' => ['/rem_history/stat_decision'],
            ],
            [
                'label' => 'Неисправности %, Месяц',
                'url' => ['/rem_history/stat_decision_month'],
            ],


            ['label' => '-------------'],

            [
                'label' => 'Журнал РЕМОНТОВ',
                'url' => ['/rem_history/index'],
            ],

            ['label' => '-------------'],

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
                'label' => 'Справочник Cкладов',
                'url' => ['/sprwhelement/index'],
            ],

            [
                'label' => 'ЕЖЕДНЕВНЫЕ ОСТАТКИ по ЦС',
                'url' => ['/past_inventory_cs_/index'],
            ],

            //							[ 'label' => '-------------' ],
            //
            //							[
            //								'label' => 'Амортизация. АСУОП',
            //								'url'   => [ '/globalam' ],
            //							],
            //							[
            //								'label' => 'Амортизация. АСУОП элементы ',
            //								'url'   => [ '/globalamelement' ],
            //							],
            //							[ 'label' => '-------------' ],
            //
            //							[
            //								'label' => 'Cписание. УМЦ',
            //								'url'   => [ '/global' ],
            //							],
            //							[
            //								'label' => 'Cписание. УМЦ элементы ',
            //								'url'   => [ '/globalelement' ],
            //							],
            //
        ],
    ],


];


?>
