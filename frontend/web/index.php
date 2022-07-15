<?php

use yii\helpers\VarDumper;


error_reporting(-1);
date_default_timezone_set("Asia/Almaty");

ini_set('max_input_vars','10000');
ini_set('upload_max_size','30M');
ini_set('post_max_size','30M');

function ddd($var = null)
{
    echo '<br>';
    echo '<br>';
    echo '<b>ddd()</b>';

    if (!isset($var)) {
        echo '<b>ddd()</b> - пустое значение на входе';
        echo '';
        echo '';
        die();
    }

    if (is_array($var)) {
        echo 'ddd(is_array()) - массив <br>';
    }
    if (is_object($var)) {
        echo 'ddd(is_object()) - объект <br>';
    }
    if (is_bool($var)) {
        echo 'ddd(is_bool()) <br>';
    }

    echo "<pre>";
    VarDumper::dump($var, 10, 1);
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
  //  die();
}



/**
 * @param $var
 */
function vd1($var)
{
    if($var)
    {
        echo '<pre><b style="background: green;padding: 15px;">';
        var_dump($var);
        echo '</b></pre><br>';
    }
    else
    {
        echo '<pre><b style="background: red;padding: 15px;">';
        var_dump($var);
        echo '</b></pre><br>';
    }

}
function dump($var)
{
    var_dump($var);
    //die();
}

function d33($var = 0)
{
    static $int=0;
    echo '<pre><b style="color: red;padding: 1px 5px;">' . $int . '</b> ';
    print_r($var);
    echo '</pre>';
    $int++;
}

function pr($var)
{
    static $int=0;
    echo '<pre><b style="background: red;padding: 1px 5px;">' . $int . '</b> ';
    print_r($var);
    echo '</pre>';
    $int++;
}


/**
 * @param $model
 */
function dderror($model)
{
    foreach ($model->getErrors() as $key => $value) {
        echo '<pre><b style="color: red;font-size: 20px;padding: 1px 5px;">' . $int;
        echo $key . ': ' . $value[0];
        echo '</b></pre>';
    }
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../../common/config/bootstrap.php';
require __DIR__ . '/../config/bootstrap.php';

//require __DIR__ . '/../../Classes/PHPExcel.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../common/config/main.php',
    require __DIR__ . '/../../common/config/main-local.php',
    require __DIR__ . '/../config/main.php',
    require __DIR__ . '/../config/main-local.php'
);

(new yii\web\Application($config))->run();
