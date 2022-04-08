<style>
    .tree_land {
        width: 450px;
        min-width: 300px;
        background-color: cornsilk;
        padding: 15px;
    }
</style>

<?php


use frontend\models\Sprwhtop;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

//    use yii\widgets\ActiveForm;
    use yii\widgets\Pjax;


$this->title = 'Склады Автопарков (ЦС)';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin( [ 'id' => '1' ] );

?>
<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    //         echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>


<!--        --><?php//= Html::a('Создать новый Элемент склада', ['create'], ['class' => 'btn btn-success']) ?>
    </p>



<!--    --><?php
//        echo $this->render('_excel', [
//            'model' => $searchModel,
//            'para' => $para
//        ]);
//    ?>




    <?= GridView::widget(['dataProvider' => $dataProvider, 'filterModel' => $searchModel,

        'columns' => [
            [
             //     'title' => '123parent_id',

                    'attribute' => 'parent_id',
                'filter' => ArrayHelper::map(
                 Sprwhtop::find()
                     ->orderBy(['name'])
                     ->all(), 'id', 'name'),
                //'value' => 'parent_id',
                'value' => 'sprwhtop.name',
                'contentOptions' => ['style' => ' ;min-width: 15px;'],
                ],

            'id',
            'name',
            'nomer_borta',
            'nomer_gos_registr',
            'nomer_vin',
            'tx',

            [
                'attribute'=> 'delete_sign',
                'value'=>function($url, $model, $key) {

                        if (isset($url['delete_sign'])){
                            return  $url['delete_sign'];
                        }

                    return '';
                    },
                'contentOptions' => ['style' => ' width: 72px;'],
            ],


            [
                    'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {flags} {delete} {erase}',

                'contentOptions' => [
                        'class' => 'action-column',
                    'style' => ' width: 120px;'
                ],
                'buttons' => [
                    'update' => function($url, $model, $key) {

                        //Для Талгата
                        if ( Yii::$app->getUser()->identity->group_id == 71 ){

                            $options = [
                                'target' => '_blank',
                                    'title' => 'update',
                                'aria-label' => 'update',
                                'data-pjax' => 'w0',
                                //'data-confirm' => Yii::t('yii', 'Редактируем... '),
                                'data-method' => 'GET',

                            ];

                            return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, $options);
                        }
                    return '';
                    },

                    'flags' => function ($url,$model) {

                        if ( Yii::$app->getUser()->identity->group_id>=70 ){

                            $options = [
                                'title' => 'Редактирование',
                                'target' => '_blank',
                                'aria-label' => 'edit',
                                'data-pjax' => 'w0',
                                'data-method' => 'PUT'
                            ];

                            return Html::a(
                                '<span class="glyphicon glyphicon-alert"></span>',
                                $url, $options);
                        }
                        return '';
                    },


                    'delete' => function($url, $model, $key) {
                        //Для Талгата
                        if ( Yii::$app->getUser()->identity->group_id >= 71 ){

                            $options = ['title' => 'delete', 'aria-label' => 'delete', 'data-pjax' => 'w0',
                            'data-confirm' => Yii::t('yii', 'Точно? Удаляем? '),
                            'data-method' => 'POST',
                        ];

                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                    }

                    return '';
                },


                    'erase' => function($url, $model, $key) {
                        //        ddd($model->id);


                        if($model->delete_sign==1){
                            $url= '/sprwhelement/erase?id='.$model->id;

                            if (Yii::$app->getUser()->identity->group_id >= 100 ) {
                                $options = [
                                    'title' => 'XXXXX',
                                    'aria-label' => 'erase',
                                    //                                    'data-pjax' => 'w0',
                                    'data-confirm' => Yii::t('yii', 'Точно? Удаляем?'),
                                    'data-method' => 'POST',
                                ];

                                return Html::a('<span class="glyphicon glyphicon-thumbs-down"></span>',
                                    $url, $options);
                            }

                        }

                        return '';
                    },



                    ],



            ],

            [
                //                    final_destination
                'attribute'=> 'final_destination',
                'value'=>function($url, $model, $key) {

                    if (isset($url['final_destination'])  && (int)$url['final_destination']==1 ){
                        return  'ЦС';
                    }

                    return '';
                },

                'contentOptions' => ['style' => ' width: 72px;'],
            ],

        ],]);


    ?>


</div>


<?php
    Pjax::end();
?>