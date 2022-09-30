<?php

use yii\helpers\VarDumper;

error_reporting(0);
date_default_timezone_set("Asia/Almaty");

ini_set('max_input_vars','10000');
ini_set('upload_max_size','30M');
ini_set('post_max_size','30M');

function ddd( $var = null )
{
    echo '<br>';
    echo '<br>';
    echo '<b>ddd()</b>';

    if ( !isset( $var ) ) {
        echo '<b>ddd()</b> - пустое значение на входе';
        echo '';
        echo '';
        die();
    }

    if ( is_array( $var ) ) {
        echo 'ddd(is_array()) - массив <br>';
    }
    if ( is_object( $var ) ) {
        echo 'ddd(is_object()) - объект <br>';
    }
    if ( is_bool( $var ) ) {
        echo 'ddd(is_bool()) <br>';
    }

    echo "<pre>";
    VarDumper::dump( $var, 10, 1 );
    echo "<pre>";
    die();
}


/**
 * @param $var
 */
function dd($var)
{
    echo "<pre>";
    print_r($var);
    echo "<pre>";
    die();
}

defined('YII_DEBUG') or define('YII_DEBUG', false);
//defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

(new yii\web\Application($config))->run();
