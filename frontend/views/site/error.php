<?php
if (Yii::$app->errorHandler->exception->statusCode == 400) {

    switch (Yii::$app->errorHandler->exception->getCode()) {
        /// Не быстрая переадресация 400.....
        case 0:
            echo '<meta http-equiv="refresh" content="0;url=/site/login" />'; //1
            break;
        default:
            echo '<meta http-equiv="refresh" content="3;url=/site/login" />'; //1
            break;
    }

} elseif (Yii::$app->errorHandler->exception->statusCode == 412) {

    switch (Yii::$app->errorHandler->exception->getCode()) {
        /// Не быстрая переадресация 412

        default:
            echo '<meta http-equiv="refresh" content="5;url=/sklad/in" />'; //1
            break;

    }

} else {

    switch (Yii::$app->errorHandler->exception->getCode()) {
        /// Не быстрая переадресация 404.....
        case 0:
            echo '<meta http-equiv="refresh" content="10;url=/site/login" />'; //1
            break;

        /// Быстрая переадресация
        case 1:
        case 2:
            echo '<meta http-equiv="refresh" content="0;url=/site/login" />';
            break;

        // Выбрать СКЛАД
        case 3:
            echo '<meta http-equiv="refresh" content="2;url=/" />';
            break;

        // Выбрать АВОБУС И ПАРК
        case 4:
            echo '<meta http-equiv="refresh" content="2;url=/mobile/index_tree" />';
            break;

        /// Закрытие накладных ЗАМЕНЫ Оборудования
        case 5:
            echo '<meta http-equiv="refresh" content="5;url=/mobile/index_a_day" />';
//            echo '<meta http-equiv="refresh" content="5;url=/mobile_close_day/exchanges_view" />';
            break;

        default:
            echo '<meta http-equiv="refresh" content="15;url=/"/>';
            break;
    }
}

?>
<style>
    .banana {
        width: 98%;
        background-color: azure;
        padding: 35px 7%;
        margin-left: 1%;
        border-radius: 20px;
        border: 3px solid #3333;
        font-family: fira, Helvetica, sans-serif, Font_Mult, Helvetica, sans-serif;
        font-size: xx-large;
        /*box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);*/
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), inset 0 6px 20px 0 rgba(0, 0, 0, 0.11);
    }

    .coment {
        width: 90%;
        background-color: #47557b26;
        padding: 20px;
        margin: 50px 5%;
        /*border-radius: 20px;*/
        /*border: 3px solid #3333;*/
        /*font-family: Font_Mult, Helvetica, sans-serif ;*/
        font-family: Helvetica, sans-serif;
        font-size: 22px;
        overflow: auto;
    }

    .site-login {
        padding: 0px;
    }
</style>

<div class="site-login">


    <div class="site-error">
        <?php

        //        ddd(Yii::$app->errorHandler->exception->statusCode); //415
        //        ddd(Yii::$app->errorHandler->exception);

        ?>

        <div class="banana">
            <p><b>
                    <?php
                    echo Yii::$app->errorHandler->exception->getMessage();
                    ?>
                </b>
            </p>
        </div>


        <div class="coment">

            <p>
                Продолжайте работу через пару секунд.
            </p>
            <b>
                <a href="/">Вернуться на сайт </a>
            </b>

        </div>


        <div class="coment">
            <p>
                <?php
                echo "Exception " . Yii::$app->errorHandler->exception->statusCode;
                ?>
                <?php
                echo "Code " . Yii::$app->errorHandler->exception->getCode();
                ?>
            </p>

        </div>

    </div>

</div>

<pre>
<?php
echo "Code " . Yii::$app->errorHandler->exception->getCode();
if (Yii::$app->errorHandler->exception->getCode() == 0) {
    error_reporting(3);

    echo Yii::$app->errorHandler->exception->getFile();
    echo "<br>";
    echo "<br>";
    echo Yii::$app->errorHandler->exception->getTraceAsString();
    echo "<br>";
    echo "<br>";

//    echo $this->id;
//    echo "<br>";
//    echo "<br>";

    if (is_object(Yii::$app->errorHandler->exception->getPrevious())) {
        echo Yii::$app->errorHandler->exception->getPrevious()->getLine();
        echo "<br>";
        echo "<br>";
        echo Yii::$app->errorHandler->exception->getPrevious()->getTraceAsString();
        echo "<br>";
        echo "<br>";
    }
    echo Yii::$app->errorHandler->exception->getMessage();
}
?>
</pre>




