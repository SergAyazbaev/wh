<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;



?>

<style>
    .my_label{
        padding: 0 100px;
        margin-top: 26px;
        margin-bottom: -17px;
        font-size: 26px;
        font-weight: bold;
    }
</style>


<?php //Pjax::begin();


//        'DataProvider_pihod_spisanie'   => $DataProvider_pihod_spisanie,
//            'model_pihod_spisanie'   => $array_pihod_spisanie,

//ddd($DataProvider_pihod_spisanie);


?>

<div class="my_label" >Амортизация</div>
<div class="table_with">
    <?php
    /// AMORT

    $DataProvider_pihod_amprt->pagination->pageParam = 'into-page1';
    $DataProvider_pihod_amprt->sort->sortParam = 'into-sort1';
    $DataProvider_pihod_amprt->pagination->pageSize=-1;

//    ddd($array_pihod_amprt);

    //ddd($sklad_id);

    //        $dataProvider_into->pagination->pageParam = 'into-page';
    //        $dataProvider_into->sort->sortParam = 'into-sort';
    //        $dataProvider_into->setSort([
    //        'defaultOrder' => ['dt_deadline'=>SORT_DESC],]);
    //        $dataProvider_into->pagination->pageSize=3;

    echo GridView::widget([
        'dataProvider' => $DataProvider_pihod_amprt,
//        'filterModel' => $array_pihod_amprt,

            'columns' => [

                [
                    'attribute'=> 'group_id',
                    'header'=>'Группа',
                    'contentOptions' => [ 'style'=>'max-width:20px;overflow: hidden;padding:2px 10px' ],
                    //'contentOptions' => [ 'style'=>'width: max-content;overflow: hidden;padding:2px 10px' ],
                ],
                [
                    'attribute'=> 'item_id',
                    //'headerOptions' => ['style' => 'width:20px'],
                    'header'=>'Наименование',
                    'contentOptions' => [ 'style'=>'max-width:220px;overflow: hidden;padding:2px 10px' ],
                    //'contentOptions' => [ 'style'=>'width: max-content;overflow: hidden;padding:2px 10px' ],
                ],


//                [
//                    'attribute'=> 'group_nn',
//                    'contentOptions' => ['style' => 'width: 90px;'],
//                ],
//                [
//                    'attribute'=> 'item_nn',
//                    'contentOptions' => ['style' => 'width: 90px;'],
//                ],
                [
                    'header'=>'',

                    'contentOptions' => [ 'style'=>'width:40px;padding:2px 10px' ],
                    'content' => function($model) {
                         //dd($model);

                        $url = Url::to(['/stat_balans/enter-gr-elem_am?sklad_id='. $model['sklad_id'].'&gr='. $model['group_nn'].'&el='. $model['item_nn'] ]);
                        return Html::a('Вн', $url, [
                            'class' => 'btn btn-success btn-xs',
                            'data-pjax' => 0,
//                            'data-id' => $model->_id,
                            'target' => '_blank'
                        ]);
                    }
                ],

                [
                    'header'=>'',

                    'contentOptions' => [ 'style'=>'width:40px;padding:2px 10px' ],
                    'content' => function($model) {
                         //dd($model);

                        $url = Url::to(['/stat_balans/enter-gr-elem_am_barcode?sklad_id='. $model['sklad_id'].'&gr='. $model['group_nn'].'&el='. $model['item_nn'] ]);
                        return Html::a('BarCode', $url, [
                            'class' => 'btn btn-success btn-xs',
                            'data-pjax' => 0,
//                            'data-id' => $model->_id,
                            'target' => '_blank'
                        ]);
                    }
                ],


                [
                    'attribute'=> 'plus',
                    'header'=>'Приход',
                    'contentOptions' => ['style' => 'width: 90px;'],
                ],
                [
                    'attribute'=> 'minus',
                    'header'=>'Расход',
                    'contentOptions' => ['style' => 'width: 90px;'],
                ],


            ],

        ]);

     ?>
</div>


<div class="my_label" >Списание</div>

<div class="table_with">
    <?php
    /// SPISANIE

    $DataProvider_pihod_spisanie->pagination->pageParam = 'into-page2';
    $DataProvider_pihod_spisanie->sort->sortParam = 'into-sort2';
    $DataProvider_pihod_spisanie->pagination->pageSize=-1;

    //        $dataProvider_into->pagination->pageParam = 'into-page';
    //        $dataProvider_into->sort->sortParam = 'into-sort';
    //        $dataProvider_into->setSort([
    //        'defaultOrder' => ['dt_deadline'=>SORT_DESC],]);
    //        $dataProvider_into->pagination->pageSize=3;

    echo GridView::widget([
        'dataProvider' => $DataProvider_pihod_spisanie,
//        'filterModel' => $array_pihod_spisanie,

            'columns' => [

                [
                    'attribute'=> 'group_id',
                    'header'=>'Группа',
                    'contentOptions' => [ 'style'=>'max-width:20px;overflow: hidden;padding:2px 10px' ],
                    //'contentOptions' => [ 'style'=>'width: max-content;overflow: hidden;padding:2px 10px' ],
                ],
//                [
//                    'attribute'=> 'group_nn',
//                    'header'=>'Группа',
//                    'contentOptions' => [ 'style'=>'max-width:20px;overflow: hidden;padding:2px 10px' ],
//                    //'contentOptions' => [ 'style'=>'width: max-content;overflow: hidden;padding:2px 10px' ],
//                ],
                [
                    'attribute'=> 'item_id',
                    //'headerOptions' => ['style' => 'width:20px'],
                    'header'=>'Наименование',
                    'contentOptions' => [ 'style'=>'max-width:220px;overflow: hidden;padding:2px 10px' ],
                    //'contentOptions' => [ 'style'=>'width: max-content;overflow: hidden;padding:2px 10px' ],
                ],
//                [
//                    'attribute'=> 'item_nn',
//                    //'headerOptions' => ['style' => 'width:20px'],
//                    'header'=>'Наименование',
//                    'contentOptions' => [ 'style'=>'max-width:220px;overflow: hidden;padding:2px 10px' ],
//                    //'contentOptions' => [ 'style'=>'width: max-content;overflow: hidden;padding:2px 10px' ],
//                ],

                [
                    'header'=>'',

                    'contentOptions' => [ 'style'=>'width:40px;padding:2px 10px' ],
                    'content' => function($model) {
                        //dd($model);

                        //                        $url = Url::to(['/stat_balans/enter-gr-elem_am?gr='. $model['group_id'].'&el='. $model['item_id'] ]);
                        $url = Url::to(['/stat_balans/enter-gr-elem_sp?sklad_id='. $model['sklad_id'].'&gr='. $model['group_nn'].'&el='. $model['item_nn'] ]);
                        return Html::a('Вн', $url, [
                            'class' => 'btn btn-success btn-xs',
                            'data-pjax' => 0,
//                            'data-id' => $model->_id,
                            'target' => '_blank'
                        ]);
                    }
                ],

                [
                    'attribute'=> 'plus',
                    'header'=>'Приход',
                    'contentOptions' => ['style' => 'width: 90px;'],
                ],
                [
                    'attribute'=> 'minus',
                    'header'=>'Расход',
                    'contentOptions' => ['style' => 'width: 90px;'],
                ],


            ],

        ]);

     ?>
</div>


<div class="my_label" >Расходники</div>

<div class="table_with">
    <?php
    ///   CASUAL
    //    'DataProvider_pihod_rashod'     => $DataProvider_pihod_rashod,
    //            'model_pihod_rashod'     => $array_pihod_rashod,


    $DataProvider_pihod_rashod->pagination->pageParam = 'into-page3';
    $DataProvider_pihod_rashod->sort->sortParam = 'into-sort3';
    $DataProvider_pihod_rashod->pagination->pageSize=-1;

    //        $dataProvider_into->setSort([
    //        'defaultOrder' => ['dt_deadline'=>SORT_DESC],]);
    //        $dataProvider_into->pagination->pageSize=3;


//    'DataProvider_pihod_rashod'     => $DataProvider_pihod_rashod,
//            'model_pihod_rashod'     => $array_pihod_rashod,

    echo GridView::widget([
        'dataProvider' => $DataProvider_pihod_rashod,
//        'filterModel' => $array_pihod_rashod,

            'columns' => [


                [
                    'attribute'=> 'group_id',
                    'header'=>'Группа',
                    'contentOptions' => [ 'style'=>'max-width:20px;overflow: hidden;padding:2px 10px' ],
                    //'contentOptions' => [ 'style'=>'width: max-content;overflow: hidden;padding:2px 10px' ],
                ],
                [
                    'attribute'=> 'item_id',
                    //'headerOptions' => ['style' => 'width:20px'],
                    'header'=>'Наименование',
                    'contentOptions' => [ 'style'=>'max-width:220px;overflow: hidden;padding:2px 10px' ],
                    //'contentOptions' => [ 'style'=>'width: max-content;overflow: hidden;padding:2px 10px' ],
                ],



                [
                    'header'=>'',

                    'contentOptions' => [ 'style'=>'width:40px;padding:2px 10px' ],
                    'content' => function($model) {
                        //dd($model);

                        //                        $url = Url::to(['/stat_balans/enter-gr-elem_am?gr='. $model['group_id'].'&el='. $model['item_id'] ]);
                        $url = Url::to(['/stat_balans/enter-gr-elem_rash?sklad_id='. $model['sklad_id'].'&gr='. $model['group_nn'].'&el='. $model['item_nn'] ]);
                        return Html::a('Вн', $url, [
                            'class' => 'btn btn-success btn-xs',
                            'data-pjax' => 0,
//                            'data-id' => $model->_id,
                            'target' => '_blank'
                        ]);
                    }
                ],

                [
                    'attribute'=> 'plus',
                    'header'=>'Приход',
                    'contentOptions' => ['style' => 'width: 90px;'],
                ],
                [
                    'attribute'=> 'minus',
                    'header'=>'Расход',
                    'contentOptions' => ['style' => 'width: 90px;'],
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



