<?php


use \yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
//use yii\web\JsExpression;
use yii\widgets\ActiveForm;


$this->title = '# ' . $bar_code . '  '; // Заголовок

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

    #barcode_pool-find_name, #barcode_pool-name {
        width: 260px;
        float: left;
        margin-right: 20px;
    }

    .wrap {
        padding: 0px 7px;
    }

    .wrap > .container22 {
        display: none;
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
</style>



<br>
<br>
<br>


<div id="tz1">

    <div class="pv_motion_create_right_center">

        <?php
        echo "<h4>Отчет о движении товара со ШТРИХ-КОДОМ: " . $bar_code . " </h1>";
        ?>

    </div>
</div>

<div class="pv_motion_create_right_center">



    <?php $form = ActiveForm::begin(
        [
//            'id' => 'project-form',
            'action' => [ 'stat_ostatki/barcode_to_naklad' ],
            'method' => 'get',

            'options' => [
//                'data-pjax' => 'w7',
                'autocomplete' => 'off',

            ],

//            'validateOnChange' => true,
//            'enableAjaxValidation' => false,
//            'enableClientValidation' => false,
//            'validateOnSubmit' => false,
//            'validateOnBlur' => false,

        ] );
    ?>


    <?php

    echo $form->field( $model_text, 'find_name' )
        ->widget(
            AutoComplete::className(), [
            'options' => [
                'class' => 'form-control',
                //'placeholder' => 'Enter locale code such as "en" or "en_US"',

                'autofocus' => true,
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
//                'select' => new JsExpression( "function( event, ui ) {
//                $('#user-company').val(ui.item.id);
//            }"
//                )

            ],
        ] )
        ->textInput()
        ->label( false );
    ?>

    <?= Html::submitButton( 'Поиск', [ 'class' => 'btn btn-lg btn-primary' ] ) ?>

    <?php ActiveForm::end(); ?>




</div>

<div class="pv_motion_create_ok_button">


    <?php
    echo Html::a( 'Выход', [ '/stat_ostatki' ], [ 'class' => 'btn btn-warning' ] );
    ?>


</div>



<div id="stat_result">
    <div class="pv_motion_create_right">

        <?php
        //        $provider->pagination->pageSize = 10;

        //        $provider->sort=['id'=>[SORT_ASC]];

        //        $provider->setSort([
        //            'attributes'=>[
        //                'nakladnaya'=>[
        //                    'asc'=>['nakladnaya'=>SORT_ASC],
        //                ],
        //                'bar_code'=>[SORT_ASC],
        //            ]
        //        ]);

        Pjax::begin();
        echo GridView::widget(
            [
                'dataProvider' => $provider,
                'filterModel' => $model,

                'columns' => [

                    [
                        //'header'=>'№',
                        'attribute' => '##',
                        'contentOptions' => [ 'style' => 'width: 30px;' ],
                        'value' => function ( $model, $key, $index ) {
                            return ++$key;
                        },
                    ],


                    [
                        'label' => '№ Накл',
                        'attribute' => 'id',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 70px;'],

                        //'value' =>function($model, $key, $index){

                        'content' => function ($model) {

                            if (
                                Yii::$app->getUser()->identity->group_id >= 40) {

                                $url = Url::to(['/sklad/update_id?id=' . $model['id'] . '&otbor=' . $model['wh_home_number']]);

                                $text_ret = Html::a( $model[ 'id' ], $url, [
                                    'target' => '_blank',
                                    'class' => 'btn btn-success btn-xs', 'data-pjax' => 0,
                                ] );
                                return $text_ret;
                            } else {
                                return $model[ 'id' ];
                            }


                        },

                    ],

                    [
                        'attribute' => 'wh_home_number',
                        'label' => 'База',
                        'contentOptions' => [ 'style' => 'overflow: hidden;width: 50px;' ],
                    ],


                    [
                        'attribute' => 'sklad_vid_oper_name',
                        'label' => 'Вид операции',
                        'contentOptions' => ['style' => 'overflow: hidden;min-width: 20px;max-width: 60px;'],
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
                        'contentOptions' => [ 'style' => 'overflow: hidden;width: 110px;' ],
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

                        'contentOptions' => ['style' => 'width: 280px;max-width: 10%;min-width: 80px;padding:0px 10px;white-space: pre-wrap;']
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


            ] );
        Pjax::end();
        ?>

    </div>


</div>


<?php
$script = <<<JS

$('#go_home').click(function() {    
    window.history.back();  
})

JS;

$this->registerJs( $script, yii\web\View::POS_READY );
?>



<?php //ActiveForm::end(); ?>
<?php //Pjax::end(); ?>



