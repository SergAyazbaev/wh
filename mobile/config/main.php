<?php

return [
    'id' => 'app-mobile',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'mobile\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'application/xml' => 'yii\web\XmlParser',
                'html/xml' => 'yii\web\XmlParser',
            ],
        ],

//        'request' => [
//            'csrfParam' => '_csrf-frontend',
//        ],


        'response' => [
            'formatters' => [
                'json' => [
                    'class' => 'yii\web\JsonResponseFormatter',
//                    'prettyPrint' => YII_DEBUG,
//                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true, //false,
            'enableSession' => true, //false,
        ],

//        'api' => [
//            'api/model:\w+>', 'verb' => 'GET'
//        ],


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
            'enablePrettyUrl' => true,      //false, // true, // Ok
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [

                '' => 'site/index_id', /// http://79.143.22.33:8808/?id=555
                ///
//                'item' => 'item/index',
//                'new' => 'new/index',
//                'new&id=1' => 'new/index?id=333',

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
