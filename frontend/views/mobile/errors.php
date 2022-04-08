<?php
echo '<meta http-equiv="refresh" content="5;url=/mobile/index_tree" />';

//ddd(Yii::$app->errorHandler );

//ddd(Yii::$app->errorHandler->exception->getCode() );

//switch (Yii::$app->errorHandler->exception->getCode()) {
//    case 0:
//    case 1:
//        echo '<meta http-equiv="refresh" content="1;url=/site/login" />';
//        break;
//
//    case 5:
//        echo '<meta http-equiv="refresh" content="1;url=/mobile/index_tree" />';
//        break;
//
//    default:
//        echo '<meta http-equiv="refresh" content="15;url=/"/>';
//        break;
//}

?>
<style>
    .banana {
        width: 60%;
        background-color: azure;
        padding: 30px 100px;
        margin-left: 20%;
        border-radius: 20px;
        border: 3px solid #3333;
        /*font-family: Font_Mult, Helvetica, sans-serif ;*/
        font-family: Helvetica, sans-serif;
        font-size: xx-large;
        /*box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);*/
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), inset 0 6px 20px 0 rgba(0, 0, 0, 0.11);
    }

    .coment {
        width: 60%;
        background-color: azure;
        padding: 20px;
        margin: 20px 20%;
        border-radius: 20px;
        border: 3px solid #3333;
        /*font-family: Font_Mult, Helvetica, sans-serif ;*/
        font-family: Helvetica, sans-serif;
        font-size: large;
        overflow: auto;
    }


</style>

<div class="site-login">


    <div class="site-error">
        <?php

        if (isset(Yii::$app->errorHandler->exception)) {
            ddd(Yii::$app->errorHandler->exception->statusCode); //415
            //                echo "Code " . Yii::$app->errorHandler->exception->getCode();
            //            echo Yii::$app->errorHandler->exception->getFile();
        }

        ?>

        <?php
        if (isset(Yii::$app->errorHandler->exception)) {
            echo '<div class="banana"><p><b>';
            echo Yii::$app->errorHandler->exception->getMessage();
            echo '</b></p></div>';
        }

        ?>


        <div class="coment">
            <p>MOBILE</p>
            <p>
                Продолжайте работу через пару секунд.
            </p>
            <b>
                <a href="/">Вернуться на сайт </a>
            </b>

        </div>


    </div>
</div>




