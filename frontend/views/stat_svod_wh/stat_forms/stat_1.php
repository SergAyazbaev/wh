<?php

//use http\Url;

//use kartik\select2\Select2;
//use yii\bootstrap\Alert;
//use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


?>
<style>

    .tree_land > h3 {
        /*color: #00ff43;*/
        color: #8bb195;
        padding: 0px 10px;
        margin: 0px;
        font-size: large;
        font-weight: bold;
    }

    .tree_land, .tree_land3 {
        padding: 15px 30px;
        min-height: 120px;
    }

    .tree_land3 {
        min-width: 200px;
    }

</style>


<?php
//Pjax::begin(['id' => "pjax-container",]);
//echo \yii::$app->request->get('page');

?>

<?php ActiveForm::begin(); ?>

<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true,//                'data-confirm'=>'normal_doc'
], 'enableClientValidation' => true, //false,
    'validateOnChange' => true, //false,
    'validateOnSubmit' => false,//true,
    //    'enableAjaxValidation'      => true,
    //    'validateOnBlur'            => true,//false,false,

]);

?>


<div id="tz1">

    <div class="pv_motion_create_right_center">

        <div class="tree_land3">
            <?php


            //ddd($model_tz);

            //Создано по ТехЗаданию
            echo "ТехЗадание №" . $model_tz['id'] . "<h3>" . $model_tz['name_tz'] . " </h3>";
            echo "ПЕ:" . $model_tz['multi_tz'];
            ?>


        </div>


        <div class="tree_land">
            Для адресата:<h3><?= $model_tz['wh_cred_top_name']; ?> </h3>
            ДК: <?= $model_tz['street_map']; ?>
        </div>


        <div class="tree_land">

            Дата создания:<h3><?= $model_tz['dt_create']; ?> </h3>
            Дата окончания:<h3><?= $model_tz['dt_deadline']; ?> </h3>

        </div>

        <div class="tree_land">

            Создал:<h3><?= $model_tz['user_create_name']; ?> \ <?= $model_tz['user_edit_name']; ?> </h3>

        </div>
    </div>


    <div class="pv_motion_create_ok_button">


        <?php
        echo Html::a('Выход', ['/statistics'], ['class' => 'btn btn-warning']);
        ?>


        <?
        echo Html::submitButton('11', ['class' => 'btn btn-success']);
        ?>


        <?php
        echo Html::a('1', ['/sklad/copycard_from_origin?id=' . $model_tz['id']],

            ['data-confirm' => Yii::t('yii', '<b>Вы точно хотите: </b><br>
                        Создать КОПИЮ этой НАКЛАДНОЙ ?'),

                'id' => "to_print", //'target' => "_blank",
                'class' => 'btn  btn-info' //btn-primary'   //btn-success'
            ]);
        ?>


    </div>
</div>

<?php ActiveForm::end(); ?>

<div id="stat_result">


    <div class="pv_motion_create_right">

        <?

        //        $dataProvider_into->pagination->pageParam = 'into-page';
        //        $dataProvider_into->sort->sortParam = 'into-sort';
        //        $dataProvider_into->setSort([
        //        'defaultOrder' => ['dt_deadline'=>SORT_DESC],]);
        //        $dataProvider_into->pagination->pageSize=3;

        //        ddd($model);
        //        'id' => 5
        //        'wh_home_number' => 86
        //        'user_name' => 'VICTOR5'
        //        'tz_name' => '66666655555'
        //        'tz_date' => '02.05.2019 15:57:37'
        //        'dt_deadline' => '02.05.2019 00:00:00'
        //        'wh_debet_top' => '4'
        //        'wh_debet_name' => 'Guidejet TI. Основной склад'
        //        'wh_debet_element' => '86'
        //        'wh_debet_element_name' => 'АСУОП'
        //        'wh_destination' => '4'
        //        'wh_destination_name' => 'Guidejet TI. Основной склад'
        //        'wh_destination_element' => '86'
        //        'wh_destination_element_name' => 'АСУОП'


        echo GridView::widget(['dataProvider' => $provider, 'filterModel' => $model,

            'columns' => [

                [
                    'header' => '№',
                    'attribute' => 'sklad_vid_oper',
                    'contentOptions' => ['style' => 'width: 30px;'],
                    'value' => function ($model, $key, $index) {
                        return ++$key;
                    }
                    ,
                ],

                [
                    'header' => 'Вид',
                    'attribute' => 'sklad_vid_oper',
                    'contentOptions' => ['style' => 'width: 50px;'],
                ],
                [
                    'header' => 'Вид операции',
                    'attribute' => 'sklad_vid_oper_name',
                    'contentOptions' => ['style' => 'overflow: hidden;'],
                ],

                //                    [
                //                        'header'=>'Хозяин',
                //                        'attribute' => 'wh_home_number',
                //                        'contentOptions' => ['style' => 'overflow: hidden;'],
                //                    ],

                [
                    'header' => '№ Док',
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width: 70px;'],
                ],
                [
                    'header' => 'Источник',
                    'attribute' => 'wh_debet_element',
                    'contentOptions' => ['style' => 'max-width:40px;'],
                ],
                [
                    'header' => 'Источник',
                    'attribute' => 'wh_debet_name',
                    'contentOptions' => ['style' => 'width: 70px;overflow: hidden;'],
                ],
                [
                    'header' => 'Источник',
                    'attribute' => 'wh_debet_element_name',
                    'contentOptions' => ['style' => 'width: 70px;overflow: hidden;'],
                ],
                [
                    'header' => 'Приемник',
                    'attribute' => 'wh_destination_element',
                    'contentOptions' => ['style' => 'width: 70px;overflow: hidden;'],
                ],

                [
                    'header' => 'Приемник',
                    'attribute' => 'wh_destination_name',
                    'contentOptions' => ['style' => 'width: 70px;overflow: hidden;'],
                ],

                [
                    'header' => 'Приемник',
                    'attribute' => 'wh_destination_element_name',
                    'contentOptions' => ['style' => 'width: 70px;overflow: hidden;'],
                ],

                [
                    'header' => 'Всего строк',
                    'attribute' => 'array_count_all',
                    'contentOptions' => ['style' => 'width: 70px;overflow: hidden;'],
                ],

            ],

        ]);
        ?>

    </div>


</div>


<?
$script = <<<JS

$('#go_home').click(function() {    
    window.history.back();  
})

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>



<?php ActiveForm::end(); ?>
<?php //Pjax::end(); ?>



