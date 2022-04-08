<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<style>
    .table_with{
        background-color: #00ff4314;
        margin: 50px calc(15% - 100px);
        width: calc(90% - 10px);
        max-width: 810px;
    }
    .test_yelow{
        color:#0006ff9c;
        background-color: #7cb5ec1f;
    }
    .test_red{
        color:red;
    }

    .test_green {
        color: green;
    }
</style>


<div class="table_with">

    <?php
    echo '<p>';
    echo ' Группа: <b>';
    if (isset($spr_full_name['top']['name'])) {
        echo $spr_full_name['top']['name'];
    }
    echo '</b><br> ТМЦ: <b>';
    if (isset($spr_full_name['child']['name'])) {
        echo $spr_full_name['child']['name'];
    }
    echo '</b>';
    echo '</p>';
    ?>

    <?php
    /// AMORT
    $DataProvider_pihod_amprt->pagination->pageParam = 'into-page11';
    $DataProvider_pihod_amprt->sort->sortParam = 'into-sort11';
    $DataProvider_pihod_amprt->pagination->pageSize = -1;


    echo GridView::widget([
        'dataProvider' => $DataProvider_pihod_amprt,

        'rowOptions' => function ($model, $key, $index, $grid) {
         if ($model['sklad_vid_oper'] == 2 ){
             return ['class' => 'test_yelow'];
         }
         if ($model['sklad_vid_oper'] == 3 ){
             return ['class' => 'test_green'];
         }
            return ['class' => 'test_red'];
        },

            'columns' => [

                [
                    'attribute'=> 'id',
                    'header'=>'Накладная, №',
                    'contentOptions' => ['style' => 'min-width: 75px;text-align: center;'],
                    'content' => function($model) {
                        $text_ret = '';

                        //ddd($model['sklad_id']);

                        $url = Url::to(['/sklad/update_id?id='.$model['id'].
                            '&otbor='.(isset($model['sklad_id'])?$model['sklad_id']:0)

//                            .'&gr='.$model['wh_tk'].
//                            '&el='.$model['wh_tk_element']
                        ]);

                        $text_ret .= Html::a(''.$model['id'], $url, [
                            'class' => 'btn btn-success btn-xs',
                            'data-pjax' => 0,
//                            'data-id' => $model->_id,
                            'target' => '_blank'

                        ]);
                        return $text_ret;
                    }
                ],


                [
                    'attribute'=> 'dt_create',
                    'header'=>'Создан',

                ],
                [
                    'attribute'=> 'dt_update',
                    'header'=>'Отредактир.',

                ],


                [
                    'attribute' => 'sklad_vid_oper',
                    'header'=>'Вид операции',
                    //'label' => 'Вид Опер',
                    'value' => 'sklad_vid_oper',
                    'filter' => [
                        '1' => "Инвентаризация",
                        '2' => "Приход",
                        '3' => "Расход",
                    ],
                    'content' => function ($searchModel) {
                        return ArrayHelper::getValue([
                            '' => "",
                            '1' => "Инвентаризация",
                            '2' => "Приход",
                            '3' => "Расход",
                        ], $searchModel['sklad_vid_oper']);
                    },
                    'contentOptions' => ['style' => 'width: 50px;'],
                ],


                [
                    'attribute'=> 'ed_izmer_nn',
                    'header'=>'Ед.изм',
                    'contentOptions' => ['style' => 'width: 40px;'],
                ],
                [
                    'attribute'=> 'ed_izmer_num',
                    'header'=>'Кол-во',
                    'contentOptions' => ['style' => 'width: 80px;'],
                ],


            ],

        ]);

     ?>
</div>




<?php
$script = <<<JS



JS;
$this->registerJs($script);
?>



