<?php

use app\assets\BootboxAsset;
use frontend\models\Sprwhelement;
use frontend\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;


AppAsset::register($this);
BootboxAsset::overrideSystemConfirm();
?>



<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Cache-Control" content="private">
    <meta name="Cache-Control" content="max-age=3600, must-revalidate">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<style>

    #status_chat {
        display: block;
        position: fixed;
        height: 25px;
        bottom: 35px;
        right: 20px;
        background-color: rgb(198, 219, 221);
        padding: 2px 20px;
        z-index: 999;
    }

    .chat {
        display: none;
        position: fixed;
        height: 25px;
        padding: 0px;
        bottom: 2px;
        right: 100px;
        width: 300px;
        margin: 0;
        background-color: #ffffff;
    }

    .pull-chat, .pull-chat-mess {
        display: block;
        padding: 5px 20px;
        height: 39px;
        right: 300px;
        width: 300px;
    }

    .pull-chat {
        background-color: #0b72b878;
        z-index: 999;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
        height: 27px;
        font-weight: bolder;
    }

    .pull-chat-mess {
        bottom: 69px;
        background-color: #2a85a040;
    }


</style>

<body>
<?php $this->beginBody() ?>


<div class="wrap">


    <?php
    NavBar::begin(
        [
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]
    );


    if (Yii::$app->getUser()->getIsGuest()) {
        //        $menuItems[] = ['label' => 'Регистация', 'url' => ['/site/signup']];
        $menuItems[] = [
            'label' => 'Вход',
            'url' => ['/site/login'],
        ];

    } else {


        ////
        $array_str = [];
        //
        if (is_array(Yii::$app->getUser()->identity->getUserSklad())) {
            // dd(Yii::$app->getUser()->identity->getUserSklad());

            $xx = Sprwhelement::findModelDoubleRange(Yii::$app->getUser()->identity->getUserSklad());

            foreach ($xx as $item) {
                $array_str[] = [
                    'label' => 'Подразделение ' . $item['name'],
                    'url' => ['/sklad/in?otbor=' . $item['id']],
                ];
            }
        } else {

            //dd(Yii::$app->getUser()->identity->getUserSklad());
            $item_id = Sprwhelement::findModelDouble(Yii::$app->getUser()->identity->getUserSklad());
            //dd($item_id['name']);

            $array_str[] = [
                'label' => ' ' . $item_id['name'],
                'url' => ['/sklad/in?otbor=' . $item_id['id']],
            ];
        }




        /****
         * АКПАЕВ МТС
         */
        if (Yii::$app->getUser()->identity->group_id == 1) {
            include('gr_1.php');
        }


        /****
         * AUKEN ERDOS,
         * Саша Федоров  +7013330129
         */
        if (Yii::$app->getUser()->identity->group_id == 10) {
            include "gr_10.php";
        }

        /****
         * Agent THA
         */
        if (Yii::$app->getUser()->identity->group_id == 30) {
            include "gr_30.php";
        }


        /****
         * Главный СКЛАД (Виктор) (40)
         */
        if (Yii::$app->getUser()->identity->group_id == 40) {
            include "gr_40.php";
        }


        /****
         * СКЛАД (Асемтай) (41)
         */

        if (Yii::$app->getUser()->identity->group_id == 41) {
            include "gr_41.php";
        }


        /***
         *
         * Жанночка . Старший Диспетчер 45
         *
         ***/
        if (Yii::$app->getUser()->identity->group_id == 45) { /// Досан, Нурлан (Гл.Инженер)
            include "gr_45.php";
        }


        /***
         * Главный ИНЖЕНЕР /// Досан, Нурлан (Гл.Инженер)
         ***/
        if (Yii::$app->getUser()->identity->group_id == 50) {
            include "gr_50.php";
        }


        /***
         * Бухгалтер Назира !!!!!!!!(верх) 61
         ***/
        if (Yii::$app->getUser()->identity->group_id == 61) {
            include "gr_61.php";
        }


        /***
         * Бухгалтер САНЖАР (низ) 65 (Замена оборудования на ЦС)
         ***/
        if (Yii::$app->getUser()->identity->group_id == 65) {
            include "gr_65.php";
        }


        /***
         * Бухгалтер Жанель
         ***/
        if (Yii::$app->getUser()->identity->group_id == 70) {
            include "gr_70.php";
        }


        /***
         * Модератор Талгат Татубаев
         ***/
        if (Yii::$app->getUser()->identity->group_id == 71) {
                    //Алматы
                    if(  Yii::$app->params['vars']==="wh_prod"){
                        include('gr_71.php');
                    }
                    //Караганда
                    if(  Yii::$app->params['vars']==="wh_kar"){
                        include('kar/gr_71.php');
                    }
        }


        /*************************************
         * SATISICA = 99
         *************************************/
        if (Yii::$app->getUser()->identity->group_id == 99) {
            include('gr_99.php');
        }


        /*************************************
         * Admin = 100
         *************************************/
        if (Yii::$app->getUser()->identity->group_id >= 100) {
            //Алматы
            if(  Yii::$app->params['vars']=="wh_prod"){
                include('gr_100.php');
            }
            //Караганда
            if(  Yii::$app->params['vars']=="wh_kar"){
                include('kar/gr_100.php');
            }
        }

        ////////////


        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton
            (
                'Logout ( ' . Yii::$app->user->identity->username . ' )',
                [
                    'class' => 'btn btn-link logout',
                    'label' => 'Начало',
                    'url' => ['/site/index'],
                ]
            ) . ''
            . Html::endForm()
            . '</li>';

    }


    try {

        echo Nav::widget(
            [
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]
        );

    } catch (Exception $e) {

    }


    NavBar::end();
    ?>


    <div class="container22">
    </div>

    <?= $content ?>


</div>


<!--<div class="chat">-->
<!--    <div class="pull-chat"></div>-->
<!--    <div class="pull-chat-mess"></div>-->
<!--</div>-->


<!--<div id="status_chat"></div>-->
<!--<div id="run_button"></div>-->

<div id="messages-field">
    <div class="leftmessage">
    </div>
</div>


<footer class="footer">

    <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?>, 2018-2020, <?= date('Y') ?></p>
    <p class="mess"></p>


    <p class="pull-right">Морозов С.Р. </p>
    <!--        < ? //= Yii::powered() ? >-->

</footer>


<?php
//        echo ( Yii::$app->controller->id); //имя текущего контроллера
//        ddd( Yii::$app->controller->action->id); //имя текущего экшена
//        ddd( Yii::$app->controller->module->id); //имя текущего модуля

//
// Точка запуска Виджета
//
if (Yii::$app->controller->action->id != 'client') {
    echo $this->render('/chat/only_read/index', []);
}

?>


<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
