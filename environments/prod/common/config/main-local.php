<?php


$config = [
    'components' => [

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],

//        'mail' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            'useFileTransport' => false,
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.gmail.com',
//                'username' => 'username@gmail.com',
//                'password' => 'password',
//                'port' => '587',
//                'encryption' => 'tls',
//            ],
//        ],


        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://localhost:27017/wh_prod',

            //'enableLogging' => true, // включить логирование
            //'enableProfiling' => true, // включить профилирование
        ],



        'db_otrs' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.30.5.7;dbname=otrs;port=3306',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'Dvlprgj0',
            'charset' => 'utf8',
        ],





        'cache' => [
            'class' => 'yii\mongodb\Cache',
        ],

        'session' => [
            'class' => 'yii\mongodb\Session',
        ],

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\mongodb\i18n\MongoDbMessageSource'
                ]
            ]
        ],

        'formatter' => [
            'dateFormat' => 'd.M.Y',
            'datetimeFormat' => 'd.M.Y H:i:s',
            'timeFormat' => 'H:i:s',

            'locale' => 'ru-RU', //your language locale
            'defaultTimeZone' => 'Asia/Almaty', // time zone
        ],


//        'nodeSocket' => array(
//            'class' => 'application.extensions.yii-node-socket.lib.php.NodeSocket',
////            'host' => 'localhost',	// по умолчанию 127.0.0.1, может быть как ip так и доменом, только без http
//            'port' => 3001		// по умолчанию 3001, должен быть целочисленным integer-ом
//        ),


    ]
];


//if (!YII_ENV_TEST) {

    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][] = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//        'panels' => [
//            'mongodb' => [
//                'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
//                // 'db' => 'mongodb',
//                //
//                //  MongoDB component ID, defaults to `db`.
//                //  Uncomment and change this line,
//                //  if you registered MongoDB component with a different ID.
//            ],
//        ],
//
//    ];

    ////    'bootstrap' => ['debug'],
    ////    'modules' => [
    ////        'debug' => [
    //            'class' => 'yii\\debug\\Module',
    //            'panels' => [
    //                'mongodb' => [
    //                    'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
    //                    // 'db' => 'mongodb', // MongoDB component ID, defaults to `db`. Uncomment and change this line, if you registered MongoDB component with a different ID.
    //                ],
    //            ],
    ////        ],
    ////    ],

// Важно!

//    $config['bootstrap'][] = 'gii';
//    $config['modules']['gii'] = [
//        'class' => 'yii\gii\Module',
//        'generators' => [
//            'mongoDbModel' => [
//                'class' => 'yii\mongodb\gii\model\Generator'
//            ]
//        ],
//    ];

//}

return $config;

