<?php
/***
 * Главный ИНЖЕНЕР /// Досан, Нурлан (Гл.Инженер)
 ***/

$menuItems = [
    [
        'label' => 'Главный ИНЖЕНЕР',
        'url' => ['/tz/index'],
        'items' => [
            [
                'label' => 'Типовые комплекты (Tk)',
                'url' => ['/tk/index?sort=-id'],
            ],
            [
                'label' => 'ТехЗадание  (Tz)',
//                            'url' => ['/tz/?sort=dt_deadline'],
                'url' => ['/tz/index'],
            ],
//                        [
//                            'label' => 'Ремонт ТЗ Дата',
//                            'url' => ['/tz/remont_date'],
//                        ],
        ],
    ],

    [
        'label' => 'Статистика',
        'url' => ['/stat_ostatki/index'],
        'items' => [


            [
                'label' => 'Аналитика. Цепочка движений По Штрихкоду. (ЦС)',
                //'url' => [ '/stat_ostatki/barcode_to_naklad_analitics?bar=040193' ],
                'url' => ['/stat_ostatki/barcode_to_naklad_analitics'],
            ],
            ['label' => '-------'],

//                        [
//                            'label' => 'По Техзаданию',
//                            'url' => ['/statistics/index'],
//                        ],
            [
                'label' => 'По штрихкодам. По Группам',
                'url' => ['/stat_ostatki/index'],
            ],

            [
                'label' => 'По Базе (Приход/Расход) ',
                'url' => ['/stat_svod/index_svod'],
            ],


            ['label' => '-------'],


            [
                'label' => 'Количественный отчет по Складу 86(Виктор, АСУОП)',
                'url' => ['/stat_balans/kolish?sklad=86'],
            ],
            [
                'label' => 'Количественный отчет по 4229(Карбаев Нурлан )',
                'url' => ['/stat_balans/kolish?sklad=4229'],
            ],
            [
                'label' => 'Количественный отчет по Складу 4431(Дежурный)',
                'url' => ['/stat_balans/kolish?sklad=4431'],
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
        'label' => 'Ремонты',
        'url' => ['/rem_history/index'],
        'items' => [


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
        'url' => ['/sprwhelement/index'],
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
            [
                'label' => 'Cклады Element МНОГО',
                'url' => ['/sprwhelement/create_all_from_txt'],
            ],
            ['label' => '-------------'],

            [
                'label' => 'Типы Актов',
                'url' => ['/typeact/index'],
            ],
            [
                'label' => 'Виды работ монтажников',
                'url' => ['/sprvid/index'],
            ],

        ],
    ],
];

?>