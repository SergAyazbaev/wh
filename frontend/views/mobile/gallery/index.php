<?php

use \yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;

$this->title = 'GuideJet';
?>

<style>
    /*//PHOTO GALERY/*/
    .photo {
        margin: 10px;
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
    }

    .file-thumbnail-footer {
        /*background-color: #00ff43;*/
        display: none;
    }

    .file-preview {
        padding: 0px;
        width: 100%;
        margin-bottom: 0px;
    }

    input[type="file"] {
        display: block;
        font-size: medium;
        width: 100%;
    }

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


    /*//FORM///*/
    .form-control {
        font-size: 25px;
        font-family: monospace;
        padding: 3px;
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

    div.next_futer button {
        float: right;
        width: 180px;
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
        /*min-height: min-content;*/
        /*padding: 0px;*/
        /*margin: 0px;*/
        /*display: block;*/
        /*position: relative;*/
        font-size: 23px;
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

    /*//BLOCK/*/
    .help-block {
        display: none;
    }

    .block_bort {
        /*background-color: #dad55e;*/
        background-color: #dad55e5c;
        width: 100%;
        border: 7px solid #a7a35e29;
        border-radius: 10px;
        margin-bottom: 15px;

        display: inline-block;
        position: relative;
        /*float: left;*/
    }

    .block_1 {
        float: left;
        /*width: calc(100% - 85px);*/
        width: calc(100% - 50px);
        /* background-color: red; */
        padding: 2px 7px;
    }

    .block_2 {
        float: left;
        display: block;
        position: absolute;
        top: 64px;
        right: 54px;
        width: 65px;
        height: 34px;
        background-color: aquamarine;
        padding: 0px 5px;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
    }

    .block_3 {
        display: none;
        float: left;
        width: 100%;
        background-color: #5eda701f;
        padding: 10px 12px;
        margin-top: 30px;
    }

    /*//PHOTO*/
    div.block_3_button {
        display: inline-grid;
        position: relative;
        float: right;
    }

    .block_photo, .block_photo1, .block_photo2 {
        border: 3px solid #d6d6ae;
        display: block;
        position: inherit;
        padding: 6px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);

    }

    .block_photo:hover, .block_photo1:hover, .block_photo2:hover {
        border: 3px solid red;
    }

    .block_photo:after {
        border: 3px solid #00ff43;
    }

    .block_photo:focus {
        border: 3px solid #00ff43;
    }

    .block_3_button img {
        background-color: #da955ef0;
        color: red;
        border-radius: 20px;
        float: left;
        line-height: 0.2;
        padding: 3px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
    }

    .file-drop-zone {
        border: 0;
    }

    .file-drop-zone-title {
        display: none;
    }

</style>


<h5> Идентификация ПЕ </h5>


<div class="site-index">
    <div class="mobile-body-content">

        <div class="ap_pe"><?php
            if (strlen($name_pe) > 1) {
                echo $name_ap . ' - ' . $name_pe;
                echo(!empty($name_cs) ? ' (cs)' : "");
            }
            ?>
        </div>


        <?php Pjax::begin(['id' => 'w3']); ?>
        <?php $form = ActiveForm::begin(
            [
                'action' => ['/mobile/index_ident_pe'],
                'method' => 'POST',
//                'enctype' => 'multipart/form-data'
                'options' => [
                    'data-pjax' => 'w3'
                ],

            ]
        );
        ?>


        <div class="site-index">

            <h2><b> Фото галлерея </b></h2>


            <div class="mobile-body-content">
                <div class="scroll_mobile">
                    <?php
                    $dataProvider->pagination->pageParam = 'into-page';
                    $dataProvider->sort->sortParam = 'into-sort';

                    //                        $dataProvider->setSort(
                    //                            [
                    //                                'defaultOrder' => ['id' => SORT_DESC],
                    //                            ]);
                    $dataProvider->pagination->pageSize = 10;

                    echo GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,

                            'showFooter' => false,    // футер
                            'showHeader' => true,  // заголовок
                            'summary' => false,     // всего из ..

                            'columns' => [


                                [
                                    'attribute' => 'dt_create_timestamp',
                                    'contentOptions' => ['style' => 'width: 60px;'],
                                    'format' => ['datetime', 'php:d.m.Y H:i:s',],
                                ],
                                [
                                    'attribute' => 'sprwhelement_ap.name',
                                    'contentOptions' => ['style' => 'width: 90px;'],
                                ],
                                [
                                    'attribute' => 'sprwhelement_pe.name',
                                    'contentOptions' => ['style' => 'width: 120px;'],
                                ],

                                [
                                    'attribute' => 'aray_photo_gos',
                                    'contentOptions' => ['style' => 'width: 120px;'],
                                    'content' => function ($model) {

//                                        ddd($model);
                                        //ddd($model->aray_photo_gos);

                                        $path = "/photo/ident_pe/";


                                        $xx = '';
                                        $array_strs = (array)$model->aray_photo_gos;

                                        foreach ($array_strs as $item) {
                                            $str_images = $path . $model->path_hash . "/" . $item;
                                            $xx .= '<div class="photo">' . Html::img($str_images, [
                                                    'width' => '130px;',
                                                    'border' => '3px solid #3333334d',
                                                    'padding' => '5px'
                                                ]) . '</div>';

                                        }

                                        return $xx;
                                    }
                                ],

                                [
                                    'attribute' => 'aray_photo_bort',
                                    'contentOptions' => ['style' => 'width: 120px;'],
                                    'content' => function ($model) {

                                        //ddd($model);
                                        //ddd($model->aray_photo_gos);

                                        $path = "/photo/ident_pe/";


                                        $xx = '';
                                        $array_strs = (array)$model->aray_photo_bort;

                                        foreach ($array_strs as $item) {
                                            $str_images = $path . $model->path_hash . "/" . $item;
                                            $xx .= '<div class="photo">' . Html::img($str_images, [
                                                    'width' => '130px;',
                                                    'border' => '3px solid #3333334d',
                                                    'padding' => '5px'
                                                ]) . '</div>';

                                        }

                                        return $xx;
                                    }
                                ],

                                [
                                    'attribute' => 'aray_photo',
                                    'contentOptions' => ['style' => 'width: 120px;'],
                                    'content' => function ($model) {

//                            ddd($model);

                                        $path = "/photo/ident_pe/";
                                        $xx = '';
                                        $array_strs = (array)$model->aray_photo;

//                                        ddd($array_strs);

                                        foreach ($array_strs as $key1 => $item1) {
                                            foreach ($item1 as $key2 => $item) {
                                                $str_images = $path . $model->path_hash . "/" . $item;
                                                $xx .= '<div class="photo">' .
                                                    $key1 .
                                                    Html::img($str_images, [
                                                        'width' => '130px;',
                                                        'border' => '3px solid #3333334d',
                                                        'padding' => '5px',
                                                        'margin-left' => '5px',
                                                    ]) . '</div>';

                                            }
                                        }

                                        return $xx;
                                    }
                                ],



                            ],
                        ]
                    );

                    ?>

                </div>

            </div>
        </div>


        <br>
        <br>

        <?php
        echo Html::a(
            'Выход',
            ['/mobile/index'],
            [
                'onclick' => 'window.opener.location.reload();window.close();',
                'class' => 'btn btn-warning',
            ]);
        ?>

        <?php $form = ActiveForm::end(); ?>
        <?php Pjax::begin(['id' => 'w3']); ?>

    </div>
</div>
