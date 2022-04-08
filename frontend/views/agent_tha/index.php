<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

//if (empty($model->dt_create) || empty($model->dt_create_end) ){
//
//    $model->dt_create    = Date('Y-m-d', strtotime('now -3 day'));
//    $model->dt_create_end   = Date('Y-m-d', strtotime('now'));
//
//}
//$start_date=$model->dt_create;
//$end_date=$model->dt_create_end;


$this->title = 'ТехЗадания';
$this->params['breadcrumbs'][] = $this->title;
?>


<!--    --><?php //  echo $this->render('_search',
//        [
//            'model' => $searchModel,
//            'start_date' => $start_date,
//            'end_date' => $end_date
//        ]
//
//    );
//    ?>



<?php Pjax::begin();

//dd($sklad); // 3524

?>

<div class="table_with">


    <div class="table_with">
        <h1>Накладные по складу <?php echo $sklad ?>  </h1>


        <?
        $dataProvider->pagination->pageParam = 'in-page';
        $dataProvider->sort->sortParam = 'in-sort';

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],]);
        ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,


            'columns' => [
                ['class' => 'yii\grid\ActionColumn',
                    'header' => '',
                    'contentOptions' => ['style' => ' width: 50px;'],
                    'headerOptions' => ['width' => '10'],

                    'template' => '{delete}',

                    'buttons' => [
                        'delete' =>

                            function ($url, $model) {
//                                    dd($model);

                                if (Yii::$app->getUser()->identity->group_id == 100) {
                                    $url = Url::to(['/agent_tha/delete?id=' . $model->_id]);
                                    return Html::a(
                                        '<span class="glyphicon glyphicon-remove " style="color:red"></span>',
                                        $url);

                                }

                                //       if( $model['user_id']!=Yii::$app->getUser()->identity->id){
                                //           $url = Url::to(['/agent_tha/udate_tha?id='. $model->_id  ]);
                                //           return Html::a(
                                //               '<span class="glyphicon glyphicon-edit"></span>',
                                //               $url);

                                //       return Html::a(
                                //       '<span class="glyphicon " >NO</span>',
                                //       $url);


                                return '';
                            },
                    ],

                ],

                [
                    'header' => '',
                    'contentOptions' => function () {
                        if (Yii::$app->user->identity->group_id >= 30 &&
                            Yii::$app->user->identity->group_id < 40)
                            return ['style' => 'background-color: #4acc6061;'];
                        else
                            return ['style' => ' width: 72px;'];
                    },
                    'content' => function ($model) {  //dd(Yii::$app->user->identity->group_id);


                        $url = Url::to(['/agent_tha/udate_tha?id=' . $model->_id]);//

                        return Html::a('Вн', $url, [
                            'class' => 'btn btn-success btn-xs',
                            'data-pjax' => 0,
                            'data-id' => $model->_id,
                        ]);
                    }

                ],

                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => ' width: 72px;'],
                ],

                [
                    'attribute' => 'tz_id',
                    'contentOptions' => ['style' => ' width: 72px;'],
                ],
                [
                    'attribute' => 'sklad_vid_oper_name',
//                    'contentOptions' => ['style' => ' width: 10px;'],
                ],

                [
                    'attribute' => 'dt_create',
                    'format' => ['date', 'd.MM.Y H:i:s'],
                ],

                "wh_debet_name",
                "wh_debet_element_name",

                "wh_destination_name",
                "wh_destination_element_name",

                [
                    'attribute' => 'user_name',
                    'contentOptions' => ['style' => ' ;min-width: 15px;'],
                ],

            ],
        ]);


        /**
         * @param $d
         * @return false|string
         */
        function Data_format($d)
        {
            return date('d.m.Y h:i:s', strtotime($d));
        }


        ?>


        <?php Pjax::end(); ?>

    </div>


    <?php
    $script = <<<JS



JS;
    $this->registerJs($script);
    ?>



