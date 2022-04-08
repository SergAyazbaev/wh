<?php

use \app\models\Sprwhelement;

$this->title = 'GuideJet';
?>

<style>

    .row {
        margin-right: 0px;
        margin-left: 0px;
    }


    .col-lg-4 > p {
        /*background-color: blueviolet;*/
        display: block;
        position: initial;
        float: left;
    }

    p.lead {
        text-align: center;
        overflow: hidden;
        float: left;
        display: block;
        position: inherit;
        width: 100%;
        /*background-color: #0000ff54;*/
        overflow: hidden;

    }

    .row_futer {
        /*background-color: #90ed7d30;*/

        /*display: block;*/
        /*position: inherit;*/
        /*bottom: 60px;*/
    }

    /*.row_futer>div{*/
    /*background-color: #bbed7d20;*/
    /*margin: 10px;*/
    /*width: 29%;*/
    /*padding: 27px;*/
    /*!*display: contents;*!*/
    /*}*/

    @media (max-width: 600px) {
        p.lead {
            text-align: unset;
            margin: 8px -10px;
        }
    }

    @media (max-width: 1200px) {
        .row_futer {
            display: grid;
            padding: 40px;
            margin: 20px;
            /*background-color: #bd241f4f;*/
            color: #6b503096;
        }

        .row_futer > div {
            display: contents;
        }
    }

    .jumbotron {
        /*background-color: aqua;*/
        /* float: left; */
        background-color: #3e454500;
        display: inline;
        position: relative;
        width: 100%;
        float: left;
        clear: both;
    }

    h2 {
        font-size: 30px;
        /*background-color: aqua;*/
        height: 51px;
        display: block;
        float: left;
        width: 100%;
    }

    .wrapper {
        width: 100%;
        display: block;
        float: left;
        position: static;
        text-align: center;
        margin: 5% 0px;
    }

    /*@media(max-width:700px) {*/
    /*.wrapper {*/
    /*!*background-color: #cfd218b5;*!*/
    /*!*width: 100%;*!*/
    /*!*margin: 5%;*!*/

    /*display: contents;*/
    /*}*/
    /*}*/


    /*@media(max-width:900px) {*/
    /*.wrapper {*/
    /*!*padding: 0% 1%;*!*/
    /*display: contents;*/
    /*}*/
    /*}*/

</style>


<div class="site-index">

    <div class="body-content">
        <div class="jumbotron">
            <h1>Ware Houses & PVs</h1>
            <h3>Контроль наличия приборов учета на транспорте. УМЦ. </h3>
        </div>


        <div class="wrapper">

            <?php


            if (isset(Yii::$app->user) && Yii::$app->user->getIsGuest()) { ?>

                <a class="btn btn-lg btn-success" href="/site/login"> ВХОД </a>
                <!--            <a class="btn btn-lg btn-success" href="/site/signup">Регистрация</a>-->

            <?php } else { ?>


                <?php if (Yii::$app->getUser()->identity->group_id == 100) {
                    /// Super-Admin  ?>


                    <p class="lead">
                        <a class="btn btn-lg btn-success" href="/info.php">Версия ПО MongoDB</a>
                    </p>

                    <br>
                    <br>
                    <br>

                    <p class="lead">
                        <a class="btn btn-lg btn-success" href="/tk/index?sort=-id">Типовые Комплекты</a>
                    </p>
                    <p class="lead">
                        <a class="btn btn-lg btn-success" href="/tz">Техническое задание</a>
                    </p>


                    <p class="lead">
                        <a class="btn btn-lg btn-success" href="/pv">Таблица "Приборы учета"</a>
                    </p>
                    <p class="lead">
                        <a class="btn btn-lg btn-success" href="/pvmotion">Таблица действий</a>
                    </p>
                    <p class="lead">
                        <a class="btn btn-lg btn-success" href="/pvrestore">Таблица ремонтов</a>
                    </p>
                    <p class="lead">
                        <a class="btn btn-lg btn-success" href="/sprtype">Справочники</a>
                    </p>


                <?php } ?>

                <?php if (Yii::$app->getUser()->identity->group_id == 50) {
                    /// Досан, Нурлан (Гл.Инженер)  ?>
                    <p class="lead">

                        <a class="btn btn-lg btn-success" href="/tk/index?sort=-id">Типовые Комплекты</a>
                    </p>
                    <p class="lead">
                        <a class="btn btn-lg btn-success" href="/tz">Техническое задание</a>
                    </p>

                <?php } ?>


                <?php // ДЛЯ ВСЕХ ПОЛЬЗОВАТЕЛЕЙ. Если у пользователя есть СКЛАДЫ, ТО:


                if (Yii::$app->getUser()->identity->getUserSklad()) {

                    $array = Yii::$app->getUser()->identity->getUserSklad();
                    $xx = Sprwhelement::findModelDoubleRange($array);

                    $str = '';
                    foreach ($xx as $item) {
                        $str .= '<p class="lead"><a class="btn btn-lg btn-success" href="/sklad/in?otbor=' . $item['id'] . '"> ' . $item['name'] . ' (' . $item['id'] . ')' . '</a><br></p>';
                    }

                    echo $str;

                } ?>


                <p class="lead">
                    <a class="btn btn-lg btn-success" href='/site/logout'>Выход</a>
                </p>


            <?php }

            //'/site/logout' ], 'post' )
            ?>


        </div>

        <div class="row_futer">

            <!--            <div class="col-lg-4">-->
            <!--                    <h2>Заголовок</h2>-->
            <!--                    <p>Тут будет подробненько что-то написано для начального старта.-->
            <!--                        Тут будет подробненько что-то написано для начального старта.</p>-->
            <!--                    <p><a class="btn btn-default" href="/doc/">Наша документация &raquo;</a></p>-->
            <!--            </div>-->
            <!---->
            <!--            <div class="col-lg-4">-->
            <!--                <h2>Заголовок</h2>-->
            <!--                <p>Тут будет подробненько что-то написано для начального старта.-->
            <!--                    Тут будет подробненько что-то написано для начального старта.</p>-->
            <!--                <p><a class="btn btn-default" href="/forum/">Примем баги (bugs) &raquo;</a></p>-->
            <!--            </div>-->
            <!---->
            <!--            <div class="col-lg-4">-->
            <!--                <h2>Заголовок</h2>-->
            <!--                <p>Тут будет подробненько что-то написано для начального старта.-->
            <!--                Тут будет подробненько что-то написано для начального старта.</p>-->
            <!--                <p><a class="btn btn-default" href="/extensions/">Предложения по улучшению &raquo;</a></p>-->
            <!--            </div>-->

        </div>
    </div>
</div>



