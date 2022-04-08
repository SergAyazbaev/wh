<style>
    .tree_land {
        width: 450px;
        min-width: 300px;
        background-color: cornsilk;
        padding: 15px;
    }
</style>

<?php


use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

//    use yii\widgets\ActiveForm;


$this->title = 'Справочник проверочных кодов, штрихкодов';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(['id' => '1']);

?>
<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    //         echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>


        <?= Html::a('Создать новый Элемент склада', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php

    //	Pjax::begin();

    $form = ActiveForm::begin(
        [
            'id' => 'project-form',
            'method' => 'get',
            'class' => 'form-inline',
            'action' => ['/barcode_pool/index'],

            'options' => [
                //'data-pjax' => 1,
                'autocomplete' => 'off',
            ],

        ]);
    ?>





    <?= Html::submitButton(
        'Передать в EXCEL', [
        'class' => 'btn btn-default',
        'name' => 'print',
        'value' => 1,
    ]) ?>


    <?php
    ActiveForm::end();

    $dataProvider->pagination->pageSize = 10;
    ?>


    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

            'columns' => [

                [
                    'attribute' => 'id',
                    'value' => 'id',
                    'contentOptions' => ['style' => ' ;width: 75px;'],
                ],

                [
                    'value' => 'spr_globam_element.spr_globam.name',
                    'contentOptions' => ['style' => ' ;min-width: 15px;'],
                ],

                [
                    'attribute' => 'parent_name',
                    'filter' => $spr_globam_element,
                    'value' => 'spr_globam_element.name',
                    'contentOptions' => ['style' => ' ;min-width: 15px;'],
                ],

                'bar_code',

                [
                    'attribute' => 'turnover',
                    'contentOptions' => ['style' => 'overflow: hidden;width: 90px;'],
                    'content' => function ($model) {
                        if (isset($model['turnover']) && !empty($model['turnover'])) {
                            return $model['turnover'];
                        }
                        return '';
                    }
                ],


            ],
        ]);


    ?>


</div>


<?php
Pjax::end();
?>