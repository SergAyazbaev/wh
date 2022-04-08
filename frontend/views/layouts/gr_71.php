<?php
/***
 * Модератор Талгат Татубаев
 **
 */


$menuItems = [
    //                ['label' => 'Старт', 'url' => ['/site']],
    //                ['label' => 'Склады TEST', 'url' => ['/'],
    //                    'items' => $array_str
    //                    ],


    [
        'label' => 'Мобильное приложение',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'Мобильное приложение',
                'url' => ['/mobile/index'],
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

            [
//                            'label' => 'Tree ',
                'label' => 'Мобильное приложение',
                'url' => ['/mobile/index_tree'],
            ],

            ['label' => '-------'],

            [
                'label' => 'CRM',
                'url' => ['/crm/index'],
            ],

            ['label' => '-------------'],
            [
                'label' => 'Принтер MTP-2/BLUETOOTH',
                'url' => ['/mtp2printer/pdf_create_for_mtp2'],
            ],


        ],
    ],

    [
        'label' => 'Инвентаризация',
        'url' => ['/past_inventory/index'],
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
            ['label' => '---- обрабочик ---'],
            [
                'label' => 'Получить свежие остатки',
                'url' => ['/stat_balans/past_inventory_summary'],
            ],

//                        ['label' => '---- Своды ---'],
//                        [
//                            'label' => 'Сводная статистика',
//                            'url' => ['/svod/tree'],
//                        ],


        ],
    ],
    [
        'label' => 'Инвентаризация WH',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'WH. Заливка инвентаризации',
                'url' => ['/sklad_inventory_wh/index'],
            ],

            [
                'label' => 'WH. Инвентаризации по WH',
                'url' => ['/stat_svod_wh/index_svod_wh'],
            ],

            ['label' => '---- обрабочик ---'],


            ['label' => '---- Закрытие. Один ЦС в любой день  ---'],
            [
                'label' => 'WH. Закрытие. Один WH в любой день ',
                'url' => ['/inventory_wh_generator/pillars_one_wh'],
            ],


            ['label' => '---- Своды ---'],
            [
                'label' => 'Сводная статистика',
                'url' => ['/svod/tree'],
            ],


        ],
    ],

    [
        'label' => 'Инвентаризация ЦС',
        'url' => ['/past_inventory_cs_/index'],
        'items' => [
            [
                'label' => 'CS. Заливка инвентаризации по ЦС',
                'url' => ['/sklad_inventory_cs/index'],
            ],

            [
                'label' => 'CS. Инвентаризации по ЦС',
                'url' => ['/stat_svod/index_svod_cs'],
            ],

            ['label' => '---- Свежие ---'],
            [
                'label' => 'CS. ЕЖЕДНЕВНЫЕ ОСТАТКИ',
                'url' => ['/past_inventory_cs_/index'],
            ],

            ['label' => '---- обрабочик ---'],
            [
                'label' => 'Создать инвентаризации',
                'url' => ['/stat_balans_cs_/cs_past_inventory_summary'],
            ],


            ['label' => '---- Закрытие. Все  ЦС  ---'],
            [
                'label' => 'CS. Закрытие прошлого месяца',
                'url' => ['/inventory_cs_generator/pillars'],
            ],

            [
                'label' => 'CS. Закрытие 2 месяца назад ',
                'url' => ['/inventory_cs_generator/pillars_2month'],
            ],

            ['label' => '---- Закрытие. Один ЦС в любой день  ---'],
            [
                'label' => 'CS. Закрытие. Один ЦС в любой день ',
                'url' => ['/inventory_cs_generator/pillars_one_cs'],
            ],

            ['label' => '-'],


            ['label' => '-------'],
            [
                'label' => 'Ведомость по ЦС (Приход/Расход) ',
                'url' => ['/stat_svod/index_move_cs'],
            ],

            [
                'label' => 'Ведомость по одному ЦС ',
                'url' => ['/stat_svod/index_move_cs_one'],
            ],

            ['label' => '-'],
            ['label' => '---- Своды ЦС---'],
            [
                'label' => 'Сводная статистика ЦС',
                'url' => ['/svod_cs/tree'],
            ],

            ['label' => '-'],
            ['label' => '--- Цепочка ----'],
            [
                'label' => 'Цепочка движений По Штрихкоду. (ЦС)',
                'url' => ['/stat_ostatki/barcode_to_naklad'],
            ],
            [
                'label' => 'Аналитика. Цепочка движений По Штрихкоду. (ЦС)',
                'url' => ['/stat_ostatki/barcode_to_naklad_analitics'],
            ],


        ],
    ],

    [
        'label' => 'Статистика',
        'url' => ['/'],
        'items' => [
            [
                'label' => 'Уникальные номера складов (по накладным)',
                'url' => ['/stat_svod/uniq_wh_numbers'],
            ],

            [
                'label' => 'Самая свежая накладная',
                'url' => ['/stat_svod/last_new_one'],
            ],
            ['label' => '-------'],

            [
                'label' => 'По Техзаданию',
                'url' => ['/statistics/index'],
            ],
            [
                'label' => 'По штрихкодам. По Группам',
                'url' => ['/stat_ostatki/index'],
            ],
            [
                'label' => 'По Базе (Приход/Расход) ',
                'url' => ['/stat_svod/index_svod'],
            ],
            [
                'label' => 'По Оконечным Складам',
                'url' => ['/stat_svod/index_svod_pe'],
            ],

            ['label' => '-------'],

            [
                'label' => 'Инвентаризации по ЦС',
                'url' => ['/stat_svod/index_svod_cs'],
            ],

            [
                'label' => 'По ЦС (Приход/Расход) ',
                'url' => ['/stat_svod/index_move_cs'],
            ],

            ['label' => '-------'],

            [
                'label' => 'Количественный отчет по Складу 7188 (РЕМОНТНЫЙ)',
                'url' => ['/stat_balans/kolish?sklad=7188'],
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
        'label' => 'Партии',
        'url' => ['/barcode_pool/index'],
        'items' => [

            [
                'label' => 'Партии',
                'url' => ['/barcode_consignment/index'],
            ],

            [
                'label' => 'Партии. Заливка от Назиры',
                'url' => ['/barcode_consignment/create_new_consigment'],
            ],
            ['label' => '-------'],

            [
                'label' => 'Пул Баркодов со списком ПАРТИЙ',
                'url' => ['/barcode_pool/index_with'],
            ],
            ['label' => '-------'],

            [
                'label' => 'Пул Баркодов',
                'url' => ['/barcode_pool/index'],
            ],

            [
                'label' => 'Пул Баркодов. ОБОРОТЫ-РЕМОНТ',
                'url' => ['/barcode_pool/index_turnover'],
            ],


            [
                'label' => 'Пул. Заливка от Назиры',
                'url' => ['/barcode_pool/create_new_pool'],
            ],
            ['label' => '-------'],

            [
                'label' => 'Не вошедшее в ПУЛ',
                'url' => ['/barcode_pool/outer'],
            ],

            ['label' => '-------'],

            [
                'label' => 'Партии Без ШтрихКодов',
                'url' => ['/consignment/index'],
            ],


        ],
    ],

    [
        'label' => 'Ремонты',
        'url' => ['/rem_history/index'],
        'items' => [
            ['label' => ''],

            [
                'label' => 'Справочник.Неполадки',
                'url' => ['/rem/index'],
            ],

            [
                'label' => 'Справочник.Решения',
                'url' => ['/rem_decision/index'],
            ],
            ['label' => '-------------'],

            [
                'label' => 'Принтер ШТРИХКОДОВ',
                'url' => ['/xprinter/index'],
            ],
            [
                'label' => 'Принтер ШТРИХКОДОВ. Копипаст из ЭКСЕЛА',
                'url' => ['/xprinter/index_for_array'],
            ],

            ['label' => '-------------'],
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


        ],
    ],

    [
        'label' => 'Справочники',
        'url' => ['/sprwhelement/index'],
        'items' => [
            [
                'label' => 'ШАБЛОНЫ Накладных',
                'url' => ['/sklad_shablon'],
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
            [
                'label' => 'Копипаст УМЦ ',
                'url' => ['/globalelement/create_all_from_txt'],
            ],
            ['label' => '-------------'],

            [
                'label' => 'MSAM - ключи (Т)',
                'url' => ['/pvmsam/index'],
            ],
            [
                'label' => 'Типы Актов',
                'url' => ['/typeact/index'],
            ],
            [
                'label' => 'Виды работ монтажников',
                'url' => ['/sprvid/index'],
            ],
            ['label' => '-------------'],
            [
                'label' => 'Замена ГОС-БОРТ. История',
                'url' => ['/sprwhelement_change/index'],
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
                'label' => 'Ед.Изм',
                'url' => ['/things/index'],
            ],
            [
                'label' => 'Справочник PD',
                'url' => ['/sprpd/index'],
            ],

        ],
    ],


];

?>