<meta http-equiv="refresh" content="7;url=/"/>

<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->title = $name;
?>

<div class="site-login">


    <div class="site-error">

        <!--        <h1>--><? //= Html::encode($this->title) ?><!--</h1>-->

        <div class="alert alert-danger" style="text-align: center">
            <h3>
                <?= nl2br( Html::encode( $message ) ) ?>
            </h3>
        </div>

        <p>
            <?php
            ////////// ALERT
            if ( isset( $alert_mess ) && !empty( $alert_mess ) ) {
                echo Alert::widget(
                    [
                        'options' => [
                            'class' => 'alert_save',
                            'animation' => "slide-from-top",
                        ],
                        'body' => $alert_mess
                    ]
                );
            }
            ////////// ALERT
            ?>

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




