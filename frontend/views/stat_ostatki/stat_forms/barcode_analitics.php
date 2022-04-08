<?php


use kartik\select2\Select2;
use \yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


$this->title = ' # ' . $virt_filter->bar_code_str . '  '; // Заголовок

?>
<style>
    .btn {
        float: left;
        margin-left: 20px;
        margin-right: 50px;
    }

    .info {
        background-color: #042f44;
        color: #0a0a0a;
    }

    .field-barcode_pool-find_name {
        width: max-content;
        float: left;
    }

    .field-array_bus_select {
        width: 510px;
        float: left;
    }


    .ui-autocomplete {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
        /*box-shadow: inset 0 4px 4px rgba(0, 1, 1, 0.75);*/
        box-shadow: 3px 2px 13px 8px rgba(0, 1, 0, 0.29);
        -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;

    }

    .input_code, .input_code1, .input_filtr {
        min-width: 300px;
        max-width: 400px;
        min-height: 80px;
        /*background-color: #99979c;*/
        float: left;
        display: block;
        margin: 2px;
        padding: 5px;
    }

    .input_code1 {
        min-width: 120px;
    }

    .input_filtr {
        min-width: 120px;
        width: 140px;
        padding-top: 15px;
    }

</style>


<div id="tz1">

    <div class="pv_motion_create_right_center">

        <?php
        echo "<h4>Отчет о движении товара со ШТРИХ-КОДОМ: " . $virt_filter->bar_code_str . " </h1>";
        ?>

    </div>
</div>


<div class="pv_motion_create_right_center">
    <div class="input_code1">
        <?php $form = ActiveForm::begin(
            [
                'id' => 'project-form',
                'action' => ['stat_ostatki/barcode_to_naklad_analitics'],
                'method' => 'GET',
                'options' => [
                    'data-pjax' => 0,
                    'autocomplete' => 'off',
                ],
            ]);
        ?>


        <?php
        echo $form->field($virt_filter, 'bar_code_str')
            ->widget(
                AutoComplete::className(), [
                'options' => [
                    'class' => 'form-control',
                    //                    'autofocus' => true,
                    'style' => 'width:60%;overflow: hidden;max-height: 100px;'
                ],

                'clientOptions' => [
                    'source' => $pool,

                    //                'source' => "autocompletefind?str=5555",
                    //                'source' => "autocompletefind",
                    //                'enableAjax' => false,
                    //                'delay' => 100


                    'autoFill' => true,
                    'minLength' => '3',
                    'showAnim' => 'fold',
                    'select' => new JsExpression("function( event, ui ) {
                $('#user-company').val(ui.item.id);
            }")

                ],
            ])
            ->textInput(['placeholder' => 'Сначала Штрихкод']);
        ?>

    </div>


    <div class="input_code">


        <?php

        echo $form->field($virt_filter, 'id_sender')->widget(Select2::className()
            , [

                'theme' => Select2::THEME_BOOTSTRAP, // THEME_DEFAULT, //THEME_CLASSIC, //THEME_BOOTSTRAP,
                'size' => Select2::SMALL, //LARGE,

                'name' => 'state_401',
                'data' => $filter_dest,
                'options' => [
                    'id' => 'array_bus_select1',
                    'placeholder' => 'Список ...',
                    'multiple' => true,
                    'tabindex' => false,
                    'hideSearch' => true,
                    'tags' => true,
                ],

                'pluginOptions' => [
                    'allowClear' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 10
                ],


            ]);


        ?>


    </div>
    <div class="input_code">


        <?php

        echo $form->field($virt_filter, 'id_dest')->widget(Select2::className()
            , [

                'theme' => Select2::THEME_BOOTSTRAP, // THEME_DEFAULT, //THEME_CLASSIC, //THEME_BOOTSTRAP,
                'size' => Select2::SMALL, //LARGE,

                'name' => 'state_402',
                'data' => $filter_dest,
                'options' => [
                    'id' => 'array_bus_select2',
                    'placeholder' => 'Список ...',
                    'multiple' => true,
                    'tabindex' => false,
                    'hideSearch' => true,
                    'tags' => true,
                ],

                'pluginOptions' => [
                    'allowClear' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 10
                ],


            ]);


        ?>
    </div>

    <!--    <div class="input_code">-->
    <!--        --><? //= $form->field($virt_filter, 'flag_only_betveen')->checkbox()
    //        ?>
    <!--    </div>-->
    <div class="input_filtr">
        <?= Html::submitButton('Фильтр', ['class' => 'btn btn-lg btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>

<div class="pv_motion_create_ok_button">


    <?php
    echo Html::a('Выход', ['/stat_ostatki/barcode_to_naklad_analitics'], ['class' => 'btn btn-warning']);
    ?>


</div>


<?php //ActiveForm::end(); ?>


<?php

//ddd($this);

//echo $this->render('_search', ['model' => $model]);
?>

<div id="stat_result">
    <div class="pv_motion_create_right">

        <?php
        Pjax::begin([
                'id' => "pjax-container",
                'options' => ['autocomplete' => 'off']
            ]
        );
        ?>
        <?php
        $provider->pagination->pageSize = 10;

        //        $provider->sort=['id'=>[SORT_ASC]];

        //        $provider->setSort([
        //            'attributes'=>[
        //                'nakladnaya'=>[
        //                    'asc'=>['nakladnaya'=>SORT_ASC],
        //                ],
        //                'bar_code'=>[SORT_ASC],
        //            ]
        //        ]);


        echo GridView::widget(
            [
                'dataProvider' => $provider,
                'filterModel' => $model,
                'rowOptions' => function ($model) {
//                     ddd($model);
                    //ddd($model['user_name']);

                    if ($model['sklad_vid_oper'] == '1') {
                        return ['class' => "info"];
                    }


                    if ($model['user_name'] == '!!!') {
                        return ['class' => 'danger'];
                    }
                    if (isset($model['update_user_name']) && $model['update_user_name'] == 'errors') {
                        return ['class' => 'danger'];
                    }
                    if (isset($model['update_user_name']) && $model['update_user_name'] == 'errors2') {
                        return ['class' => 'warning'];
                    }
                    return [];
                },

                'columns' => [

                    [
                        //'header'=>'№',
                        'attribute' => '##',
                        'contentOptions' => ['style' => 'width: 30px;'],
                        'value' => function ($model, $key, $index) {
                            return ++$key;
                        },
                    ],


                    [
                        'label' => '№ Накл',
                        'attribute' => 'id',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 70px;'],

                        //'value' =>function($model, $key, $index){

                        'content' => function ($model) {
                            if ($model['sklad_vid_oper'] <> '1') { // не иннвентаризация

                                if (
                                    Yii::$app->getUser()->identity->group_id >= 40) {

                                    $url = Url::to(['/sklad/update_id?id=' . $model['id'] . '&otbor=' . $model['wh_home_number']]);

                                    $text_ret = Html::a($model['id'], $url, [
                                        'target' => '_blank',
                                        'class' => 'btn btn-success btn-xs',
                                        'data-pjax' => 0,
                                    ]);
                                    return $text_ret;
                                } else {
                                    return $model['id'];
                                }
                            }


                            return '';
                        },

                    ],

                    [
                        'attribute' => 'wh_home_number',
                        'label' => 'База',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],
                    ],


                    [
                        'attribute' => 'sklad_vid_oper_name',
                        'label' => 'Вид операции',
                        'contentOptions' => ['style' => 'overflow: hidden;min-width: 20px;max-width: 60px;'],
                        'filter' => [
                            '1' => "Инвентаризация",
                            '2' => "Приход",
                            '3' => "Расход",
                        ],
                        'content' => function ($model) {
                            return ArrayHelper::getValue([
                                '1' => "Инвентаризация",
                                '2' => "Приходная накладная",
                                '3' => "Расходная накладная",
                            ], $model['sklad_vid_oper']);
                        },

//                        'content' => function ($model) {
//                            //ddd($model);
//                                return (string)$model['sklad_vid_oper'];
//                        },
                    ],

//                    [
//                        'attribute' => 'dt_create',
//                        'label' => 'Дата документа',
//                        'contentOptions' => [ 'style' => 'overflow: hidden;width: 70px;' ],
//                    ],


                    [

                        'label' => 'Дата документа',
                        'attribute' => 'dt_create_timestamp',
                        'format' => [
                            'datetime',
                            'php:d.m.Y H:i:s',
                        ],

                        'contentOptions' => ['style' => 'overflow: hidden;width: 70px;'],
                    ],


                    //.......
                    [
                        'attribute' => 'wh_debet_name',
                        'label' => 'Группа складов',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width: 60px;'],
                    ],
                    [
                        'attribute' => 'wh_debet_element_name',
                        'label' => 'Отпустил',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
                    ],
                    [
                        'attribute' => 'wh_destination_name',
                        'label' => 'Группа складов',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width: 60px;'],
                    ],
                    [
                        'attribute' => 'wh_destination_element_name',
                        'label' => 'Получил',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
                    ],
                    [
                        'attribute' => 'tx',

                        'contentOptions' => ['style' => 'width: 180px;max-width: 10%;min-width: 300px;padding:0px 10px;white-space: pre-wrap;']
                    ],


                    [
                        'attribute' => 'user_name',
                        'label' => 'Автор',
//                        'contentOptions' => [ 'style' => 'overflow: hidden;max-width: 60px;' ],
                    ],

                    //'dt_create_timestamp',

                    //                    'wh_destination',
                    //                    'wh_destination_element',
                    //                    'user_ip',

                    //                    'user_group_id',
                    //                    'tz_id',
                    //                    'update_user_id',
                    //                    'update_user_name',
                ],


            ]);
        ?>


        <?php Pjax::end(); ?>


    </div>


</div>


<?php
$script = <<<JS

$('#go_home').click(function() {    
    window.history.back();  
})

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>





