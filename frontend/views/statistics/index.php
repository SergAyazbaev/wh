<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\ActiveForm;
//use yii\widgets\Pjax;

//if (empty($model->dt_create) || empty($model->dt_create_end) ){
//
//    $model->dt_create    = Date('d.m.Y', strtotime('now -3 day'));
//    $model->dt_create_end   = Date('d.m.Y', strtotime('now'));
//
//}
//$start_date=$model->dt_create;
//$end_date=$model->dt_create_end;


$this->title = 'ТехЗадания';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
//  echo $this->render('_search',
//        [
//            'model' => $searchModel,
//            'start_date' => $start_date,
//            'end_date' => $end_date
//        ]
//
//    );
//
?>



<?php //Pjax::begin(); ?>


<?php

    //$form = ActiveForm::begin([
    //    'action'  => ['tz/open_tz_new_tk'],
    //    'options' => ['data' => ['pjax' => true]],
    //
    //
    //    //'data-pjax'=>true,
    //    //                'data-confirm'=>'normal_doc'
    //    'enableClientValidation'    => true, //false,
    //    'validateOnChange'          => true, //false,
    //    'validateOnSubmit'          => false,//true,
    //    //    'enableAjaxValidation'      => true,
    //    //    'validateOnBlur'            => true,//false,false,
    //
    //]);

?>



<div class="table_with">
    <h1>Поступили следующие ТехЗадания</h1>

<!--    --><?//
//    echo  Html::a('Создать новую накладную', ['create_new'], ['class' => 'btn btn-success']);
//    ?>

    <?
    //= Html::a('Создать новую накладную', ['create?sklad='.$sklad], ['class' => 'btn btn-success']);
    ?>

    <!--        --><?//= Html::a('Отчет "ОСТАТКИ по складу" ',
    //            ['total_stock'], ['class' => 'btn btn-info']);
    //        ?>
    <!--        --><?//= Html::a('Отчет "ОСТАТКИ по всем складам пользователя" ',
    //            ['total_stock_for_user'], ['class' => 'btn btn-info']);
    //        ?>

    <?
    //        = Html::a('Печать в Excel', ['/pv?print=1'.$para_str],
    //            ['target' => '_blank','class' => 'btn btn-success']);
    ?>

    <?
    //        = Html::button('Период с '.($start_date?$start_date:' ...')." по ".($end_date?$end_date:' ...'),
    //            ['class' => 'btn btn-success ','id' => 'period' ])

    //        dd($para['otbor']);
    ?>


    <?
        //    $dataProvider->pagination->pageParam = 'in-page';
        //    $dataProvider->sort->sortParam = 'in-sort';

        //    $dataProvider_into->setSort([
        //        'defaultOrder' => ['id'=>SORT_DESC],]);


        //    ddd($dataProvider_into);

    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider_into,
        'filterModel' => $searchModel_into,

            //  'options'=>['class'=>'mynewclass'], // новый класс

            // 'layout'=>"{sorter} \n{items}\n{pager}\n{summary}\n",

            //    'rowOptions'=>function ($model, $key, $index, $grid){
            //			$class=$index%2?'odd':'even';  // стилизация четной и нечетной строки
            //			return array('key'=>$key,'index'=>$index,'class'=>$class);
            //		},


        'columns' => [


//                ['class' => 'yii\grid\ActionColumn',
//                    'header'=>'',
//                'contentOptions' => ['style' => ' width: 50px;'],
//                'headerOptions' => ['width' => '10'],
//
//                    //                    'content' => function() {
//                    //                            dd(Yii::$app->user);
//                    //                      },
//
//                'template' =>  '{delete}',
//
//                'buttons' => [
//                    'delete' =>
//                        function ($url, $model) {
//
//                            if (Yii::$app->getUser()->identity->group_id == 100) {
//                                $url = Url::to(['/statistics/delete?id=' . $model->_id]);
//
//                                return Html::a('<span class="glyphicon glyphicon-remove " style="color:red"></span>', $url);
//                            }
//                            if ($model['user_id'] != Yii::$app->getUser()->identity->id) {
//                                $url = Url::to(['/statistics/?id=' . $model->_id]);
//
//                                return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url);
//                            }
//
//                            return Html::a('<span class="glyphicon " ></span>', $url);
//                        },
//
//                    ],
//
//                    ],


            [
                //'header'=>'id',
                'contentOptions' => ['style' => ' width: 60px;'],

                //                'contentOptions' => function() {
                //
                //                    if(Yii::$app->user->identity->group_id>=70 &&
                //                        Yii::$app->user->identity->group_id<=100)
                //                        return ['style' => 'background-color: #4acc6061; width: 72px;'];
                //                    else
                //                        return ['style' => ' width: 72px;'];
                //
                //                },

                'content' => function($model) {

                    $url = Url::to(['/statistics/stat?id='.$model->id ]);

                    return Html::a( $model->id, $url, [
                        // 'data-method' => 'GET',
                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
                        'data-id' => $model->id,
                        'style' => ' width: 42px;'
                    ]);

                }

            ],


//            [
//                'attribute'=> 'tz_id',
//                'contentOptions' => ['style' => ' width: 72px;'],
//            ],
//            [
//                'attribute'=> 'sklad_vid_oper_name',
//                //                    'contentOptions' => ['style' => ' width: 10px;'],
//            ],

            [
                'attribute'=> 'dt_deadline',
                'format' =>  ['date', 'php:d.m.Y H:i:s'],
            ],
            [
                'attribute'=> 'dt_create',
                'format' =>  ['date', 'php:d.m.Y H:i:s'],
            ],
            [
                'attribute'=> 'status_create_date',
                'format' =>  ['date', 'php:d.m.Y H:i:s'],
            ],
//            [
//                'attribute'=> 'status_state',
//            ],

            [
                'attribute'=> 'street_map',
                'contentOptions' => ['style' => ' min-width: 70px;width: 80px;'],
            ],
            [
                'attribute'=> 'name_tz',
            ],
            [
                'attribute'=> 'multi_tz',
                'contentOptions' => ['style' => ' width: 72px;'],
            ],
            [
                'attribute'=> 'wh_cred_top_name',
            ],


//            [
//                'attribute'=> 'user_create_id',
//                'contentOptions' => ['style' => ' ;min-width: 15px;'],
//            ],
            [
                'attribute'=> 'user_create_name',
                'contentOptions' => ['style' => ' ;min-width: 20px;'],
            ],
//            [
//                'attribute'=> 'user_edit_id',
//                'contentOptions' => ['style' => ' ;min-width: 15px;'],
//            ],
            [
                'attribute'=> 'user_edit_name',
                'contentOptions' => ['style' => ' ;min-width: 20px;'],
            ],

        ],
    ]);

//    ddd($searchModel_into);



    /**
     * @param $d
     * @return false|string
     */
    function Data_format($d)
    {
        return date('d.m.Y h:i:s', strtotime($d));
    }


    ?>




</div>


<?php //ActiveForm::end(); ?>

<?php //Pjax::end(); ?>


<?php
$script = <<<JS
JS;
$this->registerJs($script);
?>



