<?php

//use \yii\widgets\Pjax;
use \yii\web\JsExpression;
use \yii\jui\AutoComplete;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

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


<div id="tz1">
    <div class="pv_motion_create_right_center">

        <?php
        echo "<h4>Отчет о движении:  </h4>";
        echo "<h1>" . $element_name . " </h1>";
        ?>

    </div>

    <div class="pv_motion_create_right_center">

        <!--        --><?php //Pjax::begin(['id' => 'w0',]); ?>

        <?php $form = ActiveForm::begin(
            [
                'id' => 'project-form',
                'action' => ['stat_ostatki/barcode_to_naklad'],

                'method' => 'get',

                'options' => [
                    'data-pjax' => 'w0',
                    'autocomplete' => 'off',

                ],

                'validateOnChange' => true,

                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'validateOnSubmit' => false,
                'validateOnBlur' => false,

            ]);
        ?>


        <?php

        echo $form->field($model_text, 'find_name')
            ->widget(
                AutoComplete::className(), [
                'options' => [
                    'class' => 'form-control',
                    //'placeholder' => 'Enter locale code such as "en" or "en_US"',
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
            ->textInput()
            ->label(false);
        ?>


        <!--            --><? //= Html::input('text', 'bar',
        //                Yii::$app->request->post('bar'), ['class' => 'form-control'])
        //            ?>

        <?= Html::submitButton('Поиск', ['class' => 'btn btn-lg btn-primary', 'name' => 'hash-button']) ?>

        <?php ActiveForm::end(); ?>
        <!--        --><?php //Pjax::end(); ?>


    </div>


    <div class="pv_motion_create_ok_button">


        <?php
        echo Html::a('Выход', ['/stat_ostatki'], ['class' => 'btn btn-warning']);
        ?>


    </div>
</div>

<?php //ActiveForm::end(); ?>

<div id="stat_result">


    <div class="pv_motion_create_right">

        <?php
        $searchModel =
            [

                'nakladnaya' => null,
                'ed_izmer' => '',
                'bar_code' => '',
            ];

        echo GridView::widget(
            [
                'dataProvider' => $provider,
                'filterModel' => $searchModel,

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
                        'attribute' => 'nakladnaya',
                        'label' => 'Накл',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],
                    ],
                    [
                        'attribute' => 'ed_izmer',
                        'label' => 'Ед.изм',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],
                    ],
                    [
                        'attribute' => 'ed_izmer_num',
                        'label' => 'Кол-во',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],
                    ],


                    [
                        'label' => 'Штрих-код',
                        'attribute' => 'bar_code',

                        'contentOptions' => ['style' => 'overflow: hidden;'],
                        //'value' =>function($model, $key, $index){

                        'content' => function ($model) {


                            if (
                                Yii::$app->getUser()->identity->group_id >= 100 ||
                                Yii::$app->getUser()->identity->group_id == 61 ||
                                Yii::$app->getUser()->identity->group_id == 40) {

                                if (isset($model['bar_code']) && $model['bar_code'] > 0) {
                                    //$url = Url::to(['/stat_ostatki/barcode_to_naklad?id='.$model['wh_home_number'].'&bar='.$model['bar_code'] ]);
                                    $url = Url::to(['/stat_ostatki/barcode_to_naklad?bar=' . $model['bar_code']]);

                                    $text_ret = Html::a($model['bar_code'], $url, [
                                        'class' => 'btn btn-success btn-xs',
                                        'data-pjax' => 0,
                                    ]);

                                    return $text_ret;
                                }

                            } else {
                                if (isset($model['bar_code']) && $model['bar_code'] > 0) {

                                    return $model['bar_code'];
                                }
                            }

                            return 'Б/Н';

                        },

                    ],

                    [
                        'attribute' => 'wh_home_number',
                        'label' => 'База',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 50px;'],
                    ],

                    //.......
                    [
                        'attribute' => 'wh_debet_name',
                        'label' => 'Группа складов',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width: 40px;'],
                    ],
                    [
                        'attribute' => 'wh_debet_element_name',
                        'label' => 'Отпустил',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
                    ],
                    [
                        'attribute' => 'wh_destination_name',
                        'label' => 'Группа складов',
                        'contentOptions' => ['style' => 'overflow: hidden;max-width: 40px;'],
                    ],
                    [
                        'attribute' => 'wh_destination_element_name',
                        'label' => 'Получил',
                        'contentOptions' => ['style' => 'overflow: hidden;width: 110px;'],
                    ],


                ],

            ]);
        ?>

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



<?php //ActiveForm::end(); ?>
<?php //Pjax::end(); ?>



