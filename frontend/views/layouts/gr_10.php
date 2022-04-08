<?php
/****
 * Токарев,
 * Шестопалов,
 * Артемов
 * AUKEN ERDOS,
 * Милаяров,
 * Зубов,
 * Турсунов
 * Саша Федоров  +7013330129
 *
 * Хрипунов
 */


///
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


    [
        'label' => 'Ремонты',
        'url' => ['/rem_history/index'],
        'items' => [
            ['label' => ''],

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

            ['label' => '-------------'],


//                        [
//                            'label' => 'Пул Баркодов. ОБОРОТЫ',
//                            'url' => ['/barcode_pool/index_turnover'],
//                        ],


        ],
    ],


];

?>