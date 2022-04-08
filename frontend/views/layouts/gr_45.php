<?php

/*** *
 * Жанночка . Старший Диспетчер 45
 *
 **
 */

$menuItems = [
    [
        'label' => 'Мобильное приложение',
        'url' => ['/'],
        'items' => [
            ['label' => '-------'],

            [
                'label' => 'CRM',
                'url' => ['/crm/index'],
            ],

            ['label' => '-------'],
            [
                'label' => 'Новая заявка OTRS',
                'url' => ['/crm/new-call'],
            ],

            [
                'label' => 'Закрыть заявку OTRS',
                'url' => ['/crm/ticket-close'],
            ],

            ['label' => '-------'],

        ],
    ],
    [
        'label' => 'Аналитика',
        'url' => ['/'],
        'items' => [
            ['label' => '-------'],

            [
                'label' => 'Аналитика. Цепочка движений По Штрихкоду. (ЦС)',
                //'url' => [ '/stat_ostatki/barcode_to_naklad_analitics?bar=040193' ],
                'url' => ['/stat_ostatki/barcode_to_naklad_analitics'],
            ],

        ],

    ],


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


        ],
    ],
];
?>