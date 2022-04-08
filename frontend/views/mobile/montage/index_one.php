<?php

use \yii\grid\GridView;
//use \yii\widgets\Pjax;
use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'GuideJet';
?>
<style>
    .scroll_mobile {
        width: max-content;
        overflow: auto;
        /*overflow: scroll;*/
    }

    .mobile-body-content {
        background-color: #00ff432e;
        padding: 15px;
        width: 60%;
        /*max-width: 500px;*/
        /*min-height: 700px;*/
        /*max-height: 1000px;*/
        margin-left: 20%;
        overflow: auto;
    }


    .jumbotron {
        text-align: center;
        padding: 10px 1px;
    }

    .jumbotron td {
        /*text-align: center;*/
        text-align: justify;
        padding: 10px 1px;
    }


    @media (max-width: 500px) {
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

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
            font-size: x-large;
        }

        h1 {
            font-size: xx-large;
            padding: 5px;
            margin-top: 10px;
        }

        h2 {
            font-size: x-large;
            margin-bottom: 10px;
        }


    }
</style>


<?php //Pjax::begin(); ?>
<?php $form = ActiveForm::begin(
    [
        'action' => ['/mobile/montage_summary_one'],
        'method' => 'GET',
    ]
);


?>


<div class="site-index">


    <div class="jumbotron">
        <?php echo '<h1>' . 'Список ';
        echo '</h1>';
        echo 'на установку из накладной № <b>' . $sklad_id;
        echo '</b>';

        ?>
    </div>


    <?php
    echo $form->field($model, 'id')
        ->textInput(['type' => 'hidden'])
        ->label(false);
    ?>
    <?php
    echo $form->field($model, 'id_ap')
        ->textInput(['type' => 'hidden'])
        ->label(false);
    ?>
    <?php
    echo $form->field($model, 'id_pe')
        ->textInput(['type' => 'hidden'])
        ->label(false);
    ?>
    <?php
    echo $form->field($model, 'sklad_id')
        ->textInput(['type' => 'hidden'])
        ->label(false);
    ?>

    <div class="mobile-body-content">
        <div class="scroll_mobile">
            <?= GridView::widget(
                [
                    'dataProvider' => $dataProvider,
//                    'filterModel' => $searchModel,

                    'showFooter' => false,    // футер
                    'showHeader' => true,  // заголовок
                    //'summary' => false,     // всего из ..
                    //'summary' => 'Показано {count} из {totalCount}',
                    'summary' => 'Всего строк {totalCount}',

                    'rowOptions' => function ($model) {
                        // ddd($model);


                        if ((int)$model['take_it'] == 1) {
                            return ['class' => 'info'];
                        } else {
                            return ['class' => 'danger'];
                        }
                    },

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'bar_code',
                            'header' => 'ШтрихКод',
                            'contentOptions' => ['style' => 'width: 60px;'],

                            //                                'content' => function ($model_grid, $key, $index, $column) {
                            //
                            //                                    //ddd($model['bar_code']);
                            //                                    //ddd($model['take_it']);
                            //
                            //                                    if ($model_grid['take_it'] == 0) {
                            //                                        $str_btn = "btn-success";
                            //                                    } else {
                            //                                        $str_btn = "btn-warning";
                            //                                    }
                            //
                            //                                    //ddd($model_grid);
                            //
                            //                                    $url = Url::to(['montage_summary_one?bar_code_to_cange=' . $model_grid['bar_code'] . '&take_it=' . $model_grid['take_it']]);
                            //                                    return Html::a(
                            //                                        $model_grid['bar_code'], $url, [
                            //                                            'class' => 'btn ' . $str_btn,
                            //                                            'method' => 'get',
                            //                                            'data-pjax' => 0,
                            //                                        ]
                            //                                    );
                            //                                }
                        ],

//                                    [
//                                        'class' => 'yii\grid\CheckboxColumn',
//                                        'name' => 'take_it',
//                                        'checkboxOptions' => function ($model, $key, $index, $column) {
//                                            return ['value' => $model->take_it];
//                                        }
//
//                                    ],

                        [
                            'attribute' => 'name',
                            'header' => 'Название',
                            'contentOptions' => ['style' => 'width: 60px;'],
                        ],


                    ],
                ]
            );

            ?>

        </div>


        <?php
        //        echo $form->field($model, 'barcode_montage')->dropDownList($array_barcode, [
        //            //'prompt' => 'Штрих код...',
        //            //'multiple' => true
        //            'multiple' => 'multiple',
        //              'readonly' => 'readonly',
        //
        //        ]);

        ?>


        <div class="form-group">
            <?= Html::submitButton('Работа выполнена', ['class' => 'width_all btn btn-success']) ?>
        </div>
    </div>


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


</div>


<?php ActiveForm::end(); ?>
<?php //Pjax::end(); ?>
