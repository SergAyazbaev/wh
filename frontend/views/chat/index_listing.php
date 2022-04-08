<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

?>

<style>
    .cyrlce_letter, .cyrlce_letter_mine {
        display: block;
        position: inherit;
        width: 30px;
        height: 30px;
        margin: -3px -29px;

        font-size: 17px;
        font-weight: bold;
        color: #2d9b34;
        background-color: #a3e6a3;
        border: 5px solid #0b93d5;
        border-radius: 20px;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
    }


    .cyrlce_text {
        background-color: #d9ffcf;
        text-align: justify;
    }

    .cyrlce_text_mine {
        background-color: #f8fbe3d1;
        text-align: justify;
    }

    .close {
        display: block;
        position: fixed;
        bottom: 129px;
        right: 180px;
        background-color: rgb(236, 240, 243);
        padding: 20px;
        border: 2px solid #0e86ef;
    }

    .scroll_mobile {
        display: block;
        position: initial;
        overflow: auto;
        height: inherit;
    }

    .chat_t {
        font-weight: bolder;
        font-size: 14px;
    }
</style>


<div class="scroll_mobile">

    <?php Pjax::begin([
        'id' => 'pjax-container1',
        'timeout' => 1000
    ])
    ?>

    <?= GridView::widget(
        [
            'id' => 'w1',
            'dataProvider' => $provider,
            'showFooter' => false,    // футер
            'showHeader' => false,  // заголовок
            'summary' => false,     // всего из ..
            'columns' => [
//                [
//                    'attribute' => 'dialog_id',
//                    'contentOptions' => ['style' => 'width: 10px;'],
//                ],

                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 30px'],
                    'content' => function ($model) {
                        return '';
                    }
                ],
                [
                    'attribute' => 'num_busline',
                    'contentOptions' => function ($model) {
                        if ($model['user_id'] == Yii::$app->user->identity->id) {
                            return ['class' => 'cyrlce_letter_mine'];
                        }
                        return ['class' => 'cyrlce_letter'];
                    },
                    'content' => function ($model) {
                        if (isset($model['user_name']) && !empty($model['user_name'])) {
                            return mb_substr($model['user_name'], 0, 1);
                        }
                        return '';
                    }
                ],
                [
                    'attribute' => 'text',
                    'contentOptions' => function ($model) {
                        if ($model['user_id'] == Yii::$app->user->identity->id) {
                            return [
                                'class' => 'cyrlce_text_mine',
                                'style' => 'max-width: 11%;white-space: pre-wrap;padding:10px;text-align: justify;'
                            ];
                        }

                        return [
                            'class' => 'cyrlce_text',
                            'style' => 'max-width: 11%;white-space: pre-wrap;padding:10px'
                        ];
                    },

                    'content' => function ($model) {
                        if (isset($model['text']) && !empty($model['text'])) {
                            return date('H:i    ', $model['date_create']) . ' ' . $model['text'];
                        }
                        return '';
                    }
                ],


            ],
        ]
    );
    ?>
    <?php Pjax::end(); ?>
</div>


    <?php
    $script = <<<JS
/// Получаем всю переписку 
function close_listing() {
    $( ".scroll_mobile" ).css('display','none');    
    $( ".wrap_clients" ).css('display','block');          
}

JS;

    $this->registerJs($script, yii\web\View::POS_END);
    ?>


