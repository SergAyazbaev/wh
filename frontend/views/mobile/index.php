<?php

use \yii\helpers\Html;

$this->title = 'GuideJet';
?>

<style>
    .wrap > .container22 {
        margin-top: 0px;
    }

    .wrap {
        width: 500px;
        margin-left: 10%;
        margin-top: 5%;
    }

    @media (max-width: 500px) {
        .wrap {
            width: auto;
            margin-left: 0;
            margin-top: 5%;
        }
    }




    /*/ FUTER NEXT >>//*/
    div._futer {
        margin-top: 30px;
        display: inline-flex;
        position: inherit;
        padding: 5px 0px;
        border: 0.5px solid #a3a3a3;
        border-radius: 10px;
        width: 100%;
    }

    div.past_futer {
        float: left;
        width: calc((98% - 140px));
    }

    div.next_futer {
        float: right;
        width: 140px;
    }
    /*//ALL BUTTONS/*/
    div > .width_all_butt {
        width: 100%;
    }

    div.width_all_butt a {
        margin: 7px;
        width: 94%;
    }


    .mobile-body-content {
        background-color: #96ff0000;
        width: 100%;
        min-height: min-content;
        padding: 0px;
        margin: 0px;
        display: block;
        position: relative;
    }

    a.next_step {
        max-width: 120px;
        display: block;
        position: relative;
        /*float: right;*/
    }

    a.back_step {
        max-width: 120px;
        display: block;
        position: relative;
    }

    ._sklad {
        margin-bottom: 10px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 5px;
        font-size: 22px;
        font-weight: bolder;
        text-align: center;

        display: block;
        /*position: absolute;*/
        float: left;
        width: 100%;
    }

    .ap_pe {
        margin-bottom: 30px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 5px;
        font-size: 22px;
        font-weight: bolder;
        text-align: center;

        display: block;
        /*position: absolute;*/
        float: left;
        width: 100%;
    }

    .ap_pe:empty {
        background-color: #ff1c43;
        display: none;
    }

    .ap_pe > p {
        text-align: center;
        font-size: 23px;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: bolder;
        margin-bottom: 0px;

    }

    .jumbotron > h1 {
        font-size: xx-large;
        padding: 5px;
        margin-top: 10px;
        color: #245580;
    }

    .jumbotron > h2 {
        font-size: x-large;
        margin-bottom: 10px;
    }



    @media (max-width: 700px) {
        .ap_pe {
            top: 110px;
            left: 4px;
        }


        p > a {
            height: 50px;
        }

        .btn {
            margin: 3px 10px;
            font-size: 22px;
        }

    }
</style>

<h5> Выбор действий МТС </h5>


<div class="site-index">
    <div class="mobile-body-content">

        <div class="_sklad"><?php
            if (isset($sklad) && !empty($sklad)) {
                echo "Sklad " . $sklad;
            }
            ?></div>

        <div class="ap_pe">
            <p><?= $name_ap ?></p>
            <p> <?= $name_pe ?>    <?= (!empty($name_cs) ? '(cs)' : "") ?></p>
        </div>


        <div class="width_all_butt">

            <?php
            if (is_array($array_sklad_list) && !empty($array_sklad_list)) {
                echo Html::a('Вод Склада(' . (isset($sklad) ? $sklad : '') . ')', ['init_sklad'], ['class' => 'width_all btn btn-warning']);
            }
            ?>

            <?= Html::a('Устранение неполадок', ['seekbus'], ['class' => 'width_all btn btn-success']) ?>
            <?= Html::a('Экстренная Замена ', ['change_things'], ['class' => 'width_all btn btn-success']) ?>
            <?= Html::a('Монтаж по ТЗ', ['montage'], ['class' => 'width_all btn btn-info']) ?>
            <?= Html::a('Демонтаж по ТЗ', ['demontage'], ['class' => 'width_all btn btn-info']) ?>

        </div>


        <div class="_futer">
            <div class="past_futer">
                <?php
                echo Html::a(
                    '<< Выход',
                    ['/mobile/index_tree'],
                    [
                        'onclick' => 'window.opener.location.reload();window.close();',
                        'class' => ' back_step btn btn-warning',
                    ]);
                ?>
            </div>
            <div class="next_futer">

                <?= Html::a('Далее >>', ['mobile/index_list'], ['class' => ' next_step btn btn-success']) ?>

            </div>
        </div>


    </div>
</div>

<br>
<br>
<br>
<br>

<?= Html::a('Галерея', ['gallery'], ['class' => 'width_all btn btn-secondary']) ?>
<?= Html::a('Обнаружение штрихкодов', ['ocr'], ['class' => 'width_all btn btn-secondary']) ?>
<?= Html::a('Сжатие фото', ['gim'], ['class' => 'width_all btn btn-secondary']) ?>


