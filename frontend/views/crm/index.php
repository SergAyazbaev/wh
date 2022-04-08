<?php

use \yii\helpers\Html;

$this->title = 'GuideJet';
?>

<style>
    .mobile-body-content {
        background-color: #00ff432e;
        padding: 15px;
        width: 60%;
        max-width: 500px;
        min-height: 700px;
        /*max-height: 1000px;*/
        margin-left: 20%;
    }

    .jumbotron {
        text-align: center;
        padding: 10px 1px;
    }

    .width_all {
        width: 100%;
        font-size: 24px;
        /* font-weight: 900; */
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        margin-bottom: 10px;
    }


    @media (max-width: 700px) {
        .mobile-body-content {
            /*background-color: #96ff0012;*/
            background-color: #96ff0000;
            padding: 0px;
            width: 100%;
            max-width: none;
            min-height: min-content;
            /*max-height: none;*/
            margin-left: 0px;
        }

        .width_all {
            margin: 5px;
            width: 99%;
            font-size: 22px;
            /* font-weight: 900; */
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            line-height: 2.1;
            margin-bottom: 20px;


        }

        .wrap > .container22 {
            padding: 0px;
            width: 99%;
            overflow: auto;
            margin-top: 0px;
            margin-bottom: 4px;
        }

        .jumbotron {
            padding-top: 4px;
            padding-bottom: 0px;
            margin-bottom: 4px;
            color: inherit;
            background-color: #fff;
        }

        p > a {
            height: 50px;
        }

    }
</style>


<div class="site-index">

    <div class="mobile-body-content">
        <div class="jumbotron">
            <h1> CRM </h1>
        </div>

        <?= Html::a('Создать ЗАЯВКУ ', ['crm/zakaz',
            //'id' => (string)$model->_id],
        ],
            ['class' => 'width_all btn btn-info']) ?>

        <?= Html::a('Таблица ЗАЯВОК ', ['crm/tabl'], ['class' => 'width_all btn btn-info']) ?>

    </div>
</div>

