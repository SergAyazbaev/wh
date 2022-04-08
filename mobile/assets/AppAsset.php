<?php

namespace frontend\assets;
//namespace app\assets;

use yii\web\AssetBundle;


class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
        'css/fonts.css',
        'css/pv.css'
    ];


    public $js = [
        //js переопределяющий yii.confirm
        'js/yii.confirm.overrides.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
//      'app\assets\BootboxAsset',

        //        'yiiassets\bootbox\BootBoxAsset',


    ];

}