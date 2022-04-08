<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);


return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    //'bootstrap' => ['log'],
//    'bootstrap' => new \yii\helpers\ReplaceArrayValue([]),
    'controllerNamespace' => 'console\controllers',
    'timeZone' => 'Asia/Almaty',

    'aliases' => [
        '@common' => dirname(__DIR__),
        '@frontend' => dirname(dirname(__DIR__)) . '/frontend',
        '@backend' => dirname(dirname(__DIR__)) . '/backend',
        '@console' => dirname(dirname(__DIR__)) . '/console',
        '@imageurl' => '/photo',
    ],

    'components' => [
//        'mongodb' => [
//            'class' => '\yii\mongodb\Connection',
//            'dsn' => 'mongodb://localhost:27017/wh_prod',
//            //'enableLogging' => true, // включить логирование
//            //'enableProfiling' => true, // включить профилирование
//        ],
//        'db' => [
//            'class' => '\yii\mongodb\Connection',
//            'dsn' => 'mongodb://localhost:27017/wh_prod',
//        ],

//        'log' => [
//            'traceLevel' => YII_DEBUG ? 3 : 0,
//            'flushInterval' => 1,
//            'targets' => [
//                [
//                    'class' => 'yii\log\FileTarget',
//                    'levels' => ['error', 'warning', 'info'],
//                    'logVars' => [],
//                    'exportInterval' => 1
//                ],
//            ],
//        ],

//        'log' => [
//            'targets' => [
//                [
//                    'class' => 'yii\log\FileTarget',
//                    'levels' => ['error', 'warning'],
//                ],
//            ],
//        ],

    ],

    'params' => $params,

//    'controllerMap' => [
//        'mongodb-migrate' => 'yii\mongodb\console\controllers\MigrateController'
//    ],


];