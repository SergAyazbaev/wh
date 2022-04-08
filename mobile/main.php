<?php
// 'response' => [
//     'formatters' => [
//         'json' => [
//             'class' => 'yii\web\JsonResponseFormatter',
//             'prettyPrint' => YII_DEBUG,
//             'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
//         ],
//     ],
// ],
// 'user' => [
//     'identityClass' => 'common\models\User',
//     'enableAutoLogin' => false,
//     'enableSession' => false,
// ],
// 'log' => [
//     'traceLevel' => YII_DEBUG ? 3 : 0,
//     'targets' => [
//         [
//             'class' => 'yii\log\FileTarget',
//             'levels' => ['error', 'warning'],
//         ],
//     ],
// ],

// 'urlManager' => [
//     'enablePrettyUrl' => true,
//     'enableStrictParsing' => true,
//     'showScriptName' => false,
//     'rules' => [
//         [
//           'class' => 'yii\rest\UrlRule',
//           'controller' => 'user',
//           'except' => ['delete'],
//           'pluralize' => false
//
//         ],
//
//  'request' => [
//      'parsers' => [
//          'application/json' => 'yii\web\JsonParser',
//          //'application/xml' => 'yii\web\XmlParser',
//       //   'html/xml' => 'yii\web\XmlParser',
//      ],
//  ],


return [
    'id' => 'app-mobile',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'mobile\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
//        'request' => [
//            'parsers' => [
//                'application/json' => 'yii\web\JsonParser',
//                'application/xml' => 'yii\web\XmlParser',
//                'html/xml' => 'yii\web\XmlParser',
//            ],
//        ],

        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],


//        'response' => [
//            'formatters' => [
//                'json' => [
//                    'class' => 'yii\web\JsonResponseFormatter',
////                    'prettyPrint' => YII_DEBUG,
////                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
//                ],
//            ],
//        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true, //false,
            'enableSession' => true, //false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => false, // true, // Ok
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [

                '' => 'site/index',
                'item' => 'item/index',

                // 'r=new' => 'new/index',
                // 'mob/?new' => 'new/index',

            ],


            //            'rules' => [
            //                '' => 'site/index',
            //                'login' => 'site/login',
            ////                'auth' => 'site/login',
            ////                'GET profile' => 'profile/index',
            ////                'PUT,PATCH profile' => 'profile/update',
            //
            //                [
            //                    'class' => 'yii\rest\UrlRule',
            //                    'controller' => 'post'
            //                ],
            //
            //            ],

        ],
    ],
    'params' => $params,
];
