<meta http-equiv="refresh" content="3;url=/"/>

<?php
// <meta http-equiv="refresh" content="3;url=http://10.0.0.112/" />

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<div class="site-login">


    <div class="site-error">

        <!--        <h1>--><? //= Html::encode($this->title) ?><!--</h1>-->

        <!--        <div class="alert alert-danger" style="text-align: center">-->
        <!--        </div>-->

        <p>
            Отключена такая возможность. Не авария.
        </p>
        <p>
            Возможно в базе нет значений соответсвующих этому запросу или
            <?= nl2br(Html::encode($message)) ?>

        </p>
        <p>
            Продолжайте работу через пару секунд.
        </p>


        <br>
        <br>
        <b>
            <a href="/">Вернуться на сайт </a>
        </b>


    </div>
</div>
<!--    <hr>-->
<!--    <br>    $_SESSION - --><? // dd($_SESSION); ?>
<!--    <hr>-->
<!--    <br>    $_COOKIE - --><? // dd($_COOKIE); ?>




