<meta http-equiv="refresh" content="5;url=<?= (isset($path_to_redirect) ? $path_to_redirect : '/') ?>/"/>

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

        //        ddd(Yii::$app->errorHandler->exception->statusCode); //415
        //        ddd(Yii::$app->errorHandler->exception);

        ?>

        <div class="banana">
            <p><b>555555555555
                    <?php
                    echo Yii::$app->errorHandler->exception->getMessage();
                    ?>
                </b>
            </p>
        </div>


        <div class="coment">
            <p>23232323
                <?php
                echo "Exception " . Yii::$app->errorHandler->exception->statusCode;
                ?>
            </p>

            <?php
            echo Yii::$app->errorHandler->exception->getFile();
            ?>
            <pre>
        <?php
        echo Yii::$app->errorHandler->exception->getTraceAsString();
        ?>
        </pre>
            <p>
                Продолжайте работу через пару секунд.
            </p>
            <b>
                <a href="/">Вернуться на сайт </a>
            </b>

        </div>


    </div>
</div>




