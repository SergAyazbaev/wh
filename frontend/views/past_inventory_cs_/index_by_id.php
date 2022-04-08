<?php

use frontend\models\Sprwhtop;
use frontend\models\Sprwhelement;

use frontend\models\Sklad_past_inventory;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>




<?php //Pjax::begin(); ?>


<div class="table_with">
    <h1>Промежуточные итоги</h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,


        //'tableOptions' =>['autocomplete' => 'off'],

        //  'options'=>['class'=>'mynewclass'], // новый класс

        // 'layout'=>"{sorter} \n{items}\n{pager}\n{summary}\n",

        //    'rowOptions'=>function ($model, $key, $index, $grid){
        //			$class=$index%2?'odd':'even';  // стилизация четной и нечетной строки
        //			return array('key'=>$key,'index'=>$index,'class'=>$class);
        //		},


        'columns' => [

            [
                'attribute' => 'id',
                'contentOptions' => ['style' => ' width: 72px;'],
            ],


            [
                'header' => '',
                'contentOptions' => ['style' => ' width: 52px;'],

                'content' => function ($model) {

                    $url = Url::to(['read?id=' . $model->_id]);

                    return Html::a('Вн', $url, [
                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
                        'data-id' => $model->_id,
                    ]);
                }

            ],


            [
                'attribute' => 'wh_destination',
                'label' => 'КОНТРАГЕНТ',

                /// Уникальные ТОЛЬКО ТУТ ИМЕНА
                'filter' => Sprwhtop::ArrayNamesWithIds(
                    Sklad_past_inventory::ArrayUniq_Wh_Ids()
                ),

                //'filter' => Sklad_past_inventory::ArrayUniq_Wh_Ids() ,
                'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
            ],


            [
                'attribute' => 'wh_destination_element',     //'wh_home_number',

                /// Уникальные ТОЛЬКО ТУТ ИМЕНА
                'filter' => Sprwhelement::ArrayNamesWithIds(
                    Sklad_past_inventory::ArrayUniq_WhElements_Ids()
                ),

                'headerOptions' => ['autocomplete' => 0],
            ],
            [
                'attribute' => 'dt_start',
                'contentOptions' => ['style' => ' min-width: 92px;'],
            ],
            [
                'attribute' => 'dt_create',
                'contentOptions' => ['style' => ' min-width: 102px;'],
            ],

            [
                'attribute' => 'wh_destination_name',
            ],

            [
                'attribute' => 'wh_destination_element_name',
            ],


            ['class' => 'yii\grid\ActionColumn',
                'header' => '',
                'contentOptions' => ['style' => ' width: 50px;'],
                'headerOptions' => ['width' => '10'],

                'template' => '{delete}',

                'buttons' => [
                    'delete' =>
                        function ($url, $model) {
                            //    dd($url);

                            if (Yii::$app->getUser()->identity->group_id == 100) {
                                $url = Url::to(['/past_inventory/delete?id=' . $model->_id]);

                                return Html::a(
                                    '<span class="glyphicon glyphicon-remove " style="color:red"></span>',
                                    $url);
                            }

                            return Html::a('<span class="glyphicon " ></span>', $url);
                        },
                ],

            ],
        ],
    ]);


    ?>

    <?php Pjax::end(); ?>


</div>


<?php
$script = <<<JS



JS;
$this->registerJs($script);
?>



