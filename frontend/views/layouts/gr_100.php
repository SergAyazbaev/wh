<?php

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
          [
              'label' => 'Изменить СТАТУС заявкиOTRS',
              'url' => ['/crm/status-set'],
          ],
          ['label' => '-------'],
          [
              'label' => '....Получить из CRM. День',
              'url' => ['/crm/take_day'],
          ],

          [
              'label' => '....Получить из CRM',
              'url' => ['/crm/take_next'],
          ],


          ['label' => '-------'],

          [
              //'label' => 'Site ',
              'label' => 'Мобильное приложение',
              'url' => ['/mobile/index_tree'],
          ],

          ['label' => '-------'],

          [
              'label' => 'REM неполадки',
              'url' => ['/rem/index'],
          ],
          [
              'label' => 'REM решение',
              'url' => ['/rem_decision/index'],
          ],
          ['label' => '-------------'],

          [
              'label' => 'PHOTO crop',
              'url' => ['/up/crop'],
          ],

          ['label' => '-------------'],

          [
              'label' => 'Инвентаризация. Инит',
              'url' => ['/mobile_inventory/init_table'],
          ],


      ],
  ],

    [
        'label' => 'Обработка',
        'url' => ['/'],
        'items' => [
            ['label' => '-------'],

            [
                'label' => 'Vid_OPER number to string',
                'url' => ['stat_balans/vid_oper_remont'],
            ],
            ['label' => '-------'],

            [
                'label' => 'PHP info ',
                'url' => ['/info.php'],
            ],
            [
                'label' => 'Кол-во Накладных ',
                'url' => ['/sklad/count_nakl'],
            ],
            [
                'label' => 'SCAN ',
                'url' => ['/scan.php'],
            ],
            [
                'label' => 'OCR - read text ',
                'url' => ['/stat_balans/ocr'],
            ],
            [
                'label' => 'Sklad_Freak ',
                'url' => ['/sklad_freak/update?id=5dba5afe80a063273c0040af&sklad=4229'],
            ],

            ['label' => '-------'],


            [
                'label' => 'Sklad.Admin. Write_zerro. Запишем 0 в количество строк ',
                'url' => ['/stat_balans/write_zerro'],
            ],

                        [
                            'label' => 'Sklad.Admin.Находим Все ПУСТЫЕ',
                            'url' => ['/stat_balans/all_empty'],
                        ],

            [
                'label' => 'Sklad.Admin.ИСПРАВИТЬ Все ПУСТЫЕ',
                'url' => ['/stat_balans/all_empty_remont'],
            ],


            ['label' => '-------'],
            [
                'label' => 'Sklad.Admin.ИСПРАВИТЬ Все AM-6',
                'url' => ['/stat_balans/all_am_6'],
            ],
            [
                'label' => 'Sklad.Admin.ИСПРАВИТЬ Все INVENTORY AM-6',
                'url' => ['/stat_balans/all_invenory_am_6'],
            ],


            ['label' => '-------'],

            [
                'label' => 'Sklad.Admin.ИСПРАВИТЬ Все ПУСТЫЕ',
                'url' => ['/sklad/sklad_exist_false'],
            ],


            ['label' => '-------'],
            [
                'label' => 'Remont. Sklad Flag=1',
                'url' => ['/sklad/sklad_flag_up'],
            ],
            [
                'label' => 'Remont. Sklad Flag=1 OLDVER',
                'url' => ['/sklad/sklad_flag_up_oldver'],
            ],
            [
                'label' => 'Remont. Sklad Flag=0',
                'url' => ['/sklad/sklad_flag_down'],
            ],
            [
                'label' => 'Remont. Timestamp',
                'url' => ['/sklad/remont_timestamp_from_dt_create'],
            ],


            ['label' => '-------'],
            [
                'label' => 'Remont. Очистка БД от лишних полей',
                'url' => ['/sklad/remont_sklad'],
            ],


            ['label' => '-------'],

            [
                'label' => 'SprWhElement.Двойники',
                'url' => ['/sprwhelement/double_whelement'],
            ],
            [
                'label' => 'SprWhElement.Remont. ',
                'url' => ['/sprwhelement/remont_gos_name'],
            ],
            [
                'label' => 'SprWhElement.Remont.2 ',
                'url' => ['/sprwhelement/remont_gos2'],
            ],

            //                        ['label' => 'По Date_as_TimeStamp', 'url' => ['/stat_svod/date-as-timestamp']],
            ['label' => '-------'],

            [
                'label' => 'WhElement_change.Слить ид двойников в накладных Складов и Столбовых ',
                'url' => ['/sprwhelement_change/remont_sklad_and_stolb'],
            ],


        ],
    ],

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
            ['label' => '---- обрабочик ---'],
            [
                'label' => 'Получить свежие остатки',
                'url' => ['/stat_balans/past_inventory_summary'],
            ],

            ['label' => '---- '],

            ['label' => '---- '],


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

//                        ['label' => '---- Свежие ---'],
//                        [
//                            'label' => 'CS. ЕЖЕДНЕВНЫЕ ОСТАТКИ',
//                            'url' => ['/past_inventory_cs_/index'],
//                        ],
//

            ['label' => '---- обрабочик ---'],

//                        [
//                            'label' => 'Создать инвентаризации',
//                            'url' => ['/stat_balans_cs_/cs_past_inventory_summary'],
//                        ],
//
//
//                        ['label' => '---- Закрытие. Все  ЦС  ---'],
//                        [
//                            'label' => 'CS. Закрытие прошлого месяца',
//                            'url' => ['/inventory_cs_generator/pillars'],
//                        ],
//
//                        [
//                            'label' => 'CS. Закрытие 2 месяца назад ',
//                            'url' => ['/inventory_cs_generator/pillars_2month'],
//                        ],
//

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

            ['label' => '---- Пробная цепь размышлений ---'],
            [
                'label' => 'Создать таблицу',
                'url' => ['/reflection/lets-table'],
            ],
            [
                'label' => 'Цепь размышлений',
                'url' => ['/reflection'],
            ],



        ],
    ],

    [
        'label' => 'Инвентаризация ЦС',
        'url' => ['/'],
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
                'label' => 'CS. Обработчик. Получить Свежие остатки ЦС',
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


//                        [
//                            'label' => 'Создаем Пустышки ИНВЕНТАРИЗАЦИИ',
//                            'url' => ['/sklad_inventory_cs/create_empty_invent'],
//                        ],
//                        [
//                            'label' => 'Заполняем Накладные ИНВЕНТАРИЗАЦИИ из перемещений',
//                            'url' => ['/sklad_inventory_cs/create_from_finded'],
//                        ],
            ['label' => '-'],

//                        [
//                            'label' => 'Промежуточные Инвентаризации ЦС',
//                            'url' => ['/past_inventory_cs'],
//                        ],


            [
                'label' => 'Ведомость по ЦС (Приход/Расход) ',
                'url' => ['/stat_svod/index_move_cs'],
            ],

            [
                'label' => 'Ведомость по одному ЦС ',
                'url' => ['stat_svod/index_move_cs_one'],
            ],

            ['label' => '-'],
            ['label' => '---- Своды ЦС---'],
            [
                'label' => 'Сводная статистика ЦС',
                'url' => ['/svod_cs/tree'],
            ],

            ['label' => '-'],
            ['label' => '---- Цепочка ---'],
            [
                'label' => 'Цепочка движений По Штрихкоду. (ЦС)',
                //'url' => [ '/stat_ostatki/barcode_to_naklad?bar=040193' ],
                'url' => ['/stat_ostatki/barcode_to_naklad'],
            ],
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
                'label' => 'Уникальные номера складов (по накладным)',
                'url' => ['/stat_svod/uniq_wh_numbers'],
            ],

            [
                'label' => 'Самая свежая накладная',
                'url' => ['/stat_svod/last_new_one'],
            ],

            //                        ['label' => 'Уникальные Date (по накладным)', 'url' => ['/stat_svod/uniq-dates']],
            //                        ['label' => 'Уникальные Накладных', 'url' => ['/stat_svod/uniq-naklad']],
            [
                'label' => 'По Техзаданию',
                'url' => ['/statistics/index'],
            ],
            [
                'label' => 'По штрихкодам. По Группам',
                'url' => ['/stat_ostatki/index'],
            ],
            [
                'label' => 'По Оконечным Складам',
                'url' => ['/stat_svod/index_svod_pe'],
            ],
            [
                'label' => 'Различные типы накладных',
                'url' => ['/park_cs/index'],
            ],

            [
                'label' => 'По Базе (Приход/Расход) ',
                'url' => ['/stat_svod/index_svod'],
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
        'url' => ['/barcode_pool'],
        'items' => [

            [
                'label' => 'Партии',
                'url' => ['/barcode_consignment/index'],
            ],
            [
                'label' => 'Партии Без ШтрихКодов',
                'url' => ['/consignment/index'],
            ],


            [
                'label' => 'Партии. Заливка от Назиры',
                'url' => ['/barcode_consignment/create_new_consigment'],
            ],
            ['label' => '-------'],
            ['label' => '-------'],


            [
                'label' => 'Пул Баркодов',
                'url' => ['/barcode_pool/index'],
            ],
            [
                'label' => 'Пул Баркодов со списком ПАРТИЙ',
                'url' => ['/barcode_pool/index_with'],
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
            ['label' => '-------'],

            [
                'label' => 'Не вошедшее в ПУЛ',
                'url' => ['/barcode_pool/outer'],
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
                'label' => 'REM.Исправление.ADMIN ',
                'url' => ['/rem_history/re-save-errors'],
            ],

            ['label' => '-------------'],
            [
                'label' => 'Принтер MTP-2/BLUETOOTH',
                'url' => ['/mtp2printer/pdf_create_for_mtp2'],
            ],
            ['label' => '-------------'],
            [
                'label' => 'Принтер ШТРИХКОДОВ тест',
                'url' => ['/xprinter/pdf_create'],
            ],
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

            [
                'label' => 'Неисправности Сент,Окт,Ноя',
                'url' => ['/rem_history/stat_decision_septemb_oktob_novemb'],
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
        'url' => ['/'],
        'items' => [


            [
                'label' => 'Диалог. Чаты. ',
                'url' => ['/dialog/index'],
            ],

            [
                'label' => 'Чаты. История. ',
                'url' => ['/chat_history/index'],
            ],

            ['label' => '-------------'],


            [
                'label' => 'Типы перемещений ',
                'url' => ['/typemotion/index'],
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
                'label' => 'Cп.ТК элементы МНОГО',
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
//                        [
//                            'label' => 'Cклады Element с ГОС-БОРТ ',
//                            'url' => ['/sprwhelement/index_change'],
//                        ],
            ['label' => '-------------'],
            [
                'label' => 'Cклады Element МНОГО',
                'url' => ['/sprwhelement/create_all_from_txt'],
            ],


            ['label' => '----Ремонт справочника Элемент - sprwhelement---------'],
            [
                'label' => 'Cклады Element Flag=1',
                'url' => ['/sprwhelement/mongo_flag_on'],
            ],
            [
                'label' => 'Cклады Element Flag=0',
                'url' => ['/sprwhelement/mongo_flag_off'],
            ],
            [
                'label' => 'Cклады Element REMONT ГОС-БОРТ',
                'url' => ['/sprwhelement/element_remont'],
            ],


            ['label' => '-------------'],
            [
                'label' => 'Cклады Element ГОС-БОРТ из таблицы Копипаст',
                'url' => ['/sprwhelement/create_new_bort_gos'],
                //'url' => [ '/sprwhelement/element_remont_copypast' ],
            ],

            ['label' => '-------------'],
            [
                'label' => 'Ед.Изм',
                'url' => ['/things/index'],
            ],

            //                        ['label' => 'Справочник PD', 'url' => ['/sprpd']], // Ложная ветка Виктор Сбил своими "Подразделениями из Эксела"

            ['label' => '-------------'],
            [
                'label' => 'Конвейер Номеров (Cross)',
                'url' => ['/cross?sort=-id'],
            ],


        ],
    ],

    /*					                ['label' => 'Mail',
                                            'items' => [
                                                ['label' => 'Mailer', 'url' => ['/site/mailer']]
                                            ]
                                        ],*/

    [
        'label' => 'Регистация',
        'url' => ['/site/signup'],
    ],

];

?>
