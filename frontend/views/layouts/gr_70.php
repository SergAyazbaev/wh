<?php
/***
 * Бухгалтер Жанель
 ***/

$menuItems = [
    [
        'label' => 'Старт',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'Склад 4190',
                'url' => ['/sklad/in?otbor=4190'],
            ],
        ],
    ],

    [
        'label' => 'Обработка',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'ИНВЕНТАРИЗАЦИИ ',
                'url' => ['/sklad_inventory'],
            ],
            ['label' => '-------'],
            [
                'label' => 'ЕЖЕДНЕВНЫЕ ОСТАТКИ',
                'url' => ['/past_inventory'],
            ],
            ['label' => '---- обрабочик ---'],
            [
                'label' => 'Получить свежие остатки',
                'url' => ['/stat_balans/past_inventory_summary'],
            ],


        ],
    ],
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
        'label' => 'Статистика',
        'url' => ['/'],
        'items' => [

            [
                'label' => 'По штрихкодам. По Группам',
                'url' => ['/stat_ostatki'],
            ],
            ['label' => '-------'],
            [
                'label' => 'По Базе (Приход/Расход) ',
                'url' => ['/stat_svod/index_svod'],
            ],
            ['label' => '-------'],
            [
                'label' => 'По Оконечным Складам',
                'url' => ['/stat_svod/index_svod_pe'],
            ],


            ['label' => '-------'],
            [
                'label' => 'Количественный отчет по Складу 86(Виктор, АСУОП)',
                'url' => ['/stat_balans/kolish?sklad=86'],
            ],
            [
                'label' => 'Количественный отчет по Складу 87(Виктор, СиМ)',
                'url' => ['/stat_balans/kolish?sklad=87'],
            ],
            [
                'label' => 'Количественный отчет по Складу 90(Виктор, Сорт.)',
                'url' => ['/stat_balans/kolish?sklad=90'],
            ],
            [
                'label' => 'Количественный отчет по 4229(Карбаев Нурлан )',
                'url' => ['/stat_balans/kolish?sklad=4229'],
            ],
            [
                'label' => 'Количественный отчет по Складу ЗАМЕН 4419(Садиров Санжар )',
                'url' => ['/stat_balans/kolish?sklad=4419'],
            ],

            ['label' => '-------'],
            [
                'label' => 'Количественный отчет по Складу 3991 (Сергеев Евгений)',
                'url' => ['/stat_balans/kolish?sklad=3991'],
            ],
            [
                'label' => 'Количественный отчет по Складу 84 (Минжасаров Марат)',
                'url' => ['/stat_balans/kolish?sklad=84'],
            ],
            [
                'label' => 'Количественный отчет по Складу 3523 (Токарев Владимир)',
                'url' => ['/stat_balans/kolish?sklad=3523'],
            ],
            [
                'label' => 'Количественный отчет по Складу 3989 (Ибрагимов Асемтай)',
                'url' => ['/stat_balans/kolish?sklad=3989'],
            ],
            [
                'label' => 'Количественный отчет по Складу 3992 (Шестопалов Дмитрий)',
                'url' => ['/stat_balans/kolish?sklad=3992'],
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
            //                        ['label' => 'Cклады Element МНОГО', 'url' => ['/sprwhelement/create_all_from_txt']],
            ['label' => '-------------'],

            [
                'label' => 'MSAM - ключи (Т)',
                'url' => ['/pvmsam'],
            ],
            [
                'label' => 'Типы Актов',
                'url' => ['/typeact'],
            ],
            [
                'label' => 'Виды работ монтажников',
                'url' => ['/sprvid'],
            ],

        ],
    ],
];
?>