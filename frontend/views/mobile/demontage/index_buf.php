<?php


use \yii\helpers\Url;
use \yii\grid\GridView;
use \yii\widgets\Pjax;
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

    h1 {
        font-size: xx-large;
        padding: 5px;
        margin-top: 10px;
    }

    h2 {
        font-size: x-large;
        margin-bottom: 10px;
    }

    .mobile-body-content {
        background-color: #00ff432e;
        padding: 15px;
        width: 60%;
        margin-left: 20%;
        overflow: auto;
    }

    @media (max-width: 500px) {
        .mobile-body-content {
            /*background-color: #96ff0012;*/
            background-color: #96ff0000;
            padding: 0;
            width: 100%;
            max-width: none;
            min-height: min-content;
            max-height: none;
            margin-left: 0;
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
            font-size: 44px;
            padding: 15px;
        }


    }
</style>


<?php Pjax::begin(['id' => 'w0']); ?>
<?php $form = ActiveForm::begin(
    [
        'action' => ['/mobile/montage'],
        'method' => 'POST',
    ]
);
?>


<div class="site-index">

    <h2>Буфер СЕГОДНЯ</h2>

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
                            'attribute' => 'id',
                            'contentOptions' => ['style' => 'width: 90px;'],

                            'content' => function ($model) {

                                $url = Url::to(['montage_summary_one?id=' . $model->id]);
                                return Html::a(
                                    $model->id, $url, [
                                        'class' => 'btn btn-success btn-xs',
                                        'method' => 'get',
                                        'data-pjax' => 'w0',
                                    ]
                                );
                            }
                        ],

                        [
                            'attribute' => 'sprwhtop_wh_dalee.name',
                            //                            'contentOptions' => ['style' => 'width: 110px;'],
                        ],

                        [
                            'attribute' => 'sprwhelement_wh_dalee_element.name',
                            //                            'contentOptions' => ['style' => 'width: 60px;'],
                        ],

                        [
                            'attribute' => 'dt_create_timestamp',
                            'contentOptions' => ['style' => 'width: 60px;'],
                            'format' => ['datetime', 'php:d.m.Y H:i:s',],
                        ],

                        [
                            'attribute' => 'sprwhelement_wh_dalee_element.nomer_borta',
                            //                            'contentOptions' => ['style' => 'width: 40px;'],
                        ],
                        [
                            'attribute' => 'sprwhelement_wh_dalee_element.nomer_gos_registr',
                            //                            'contentOptions' => ['style' => 'width: 40px;'],
                        ],
                        [
                            'attribute' => 'sprwhelement_wh_dalee_element.nomer_vin',
                            //                                    'contentOptions' => ['style' => 'max-width: 90px;'],
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

<br>
<br>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>
