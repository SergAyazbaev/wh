<?php


use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;

$this->title = '# ' . $bar_code . ' Исторрия '; // Заголовок
?>

<style>

    #button_poisk {
        float: left;
        margin: 20px;
    }

    @media (max-width: 780px) {
        #button_poisk {
            float: right;
            margin: 20px;
        }
    }

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

<?php
//Pjax::begin(['id' => "pjax-container",]);
//echo \yii::$app->request->get('page');
?>

<br>

<div id="tz1">

    <div class="pv_motion_create_right_center">
        <?php
        echo "<h4>Склад: " . $sklad . " </h1>";
        echo "<h4>Поиск по штрих-коду: " . $bar_code . " </h1>";
        ?>
    </div>

</div>

<div class="pv_motion_create_right_center">

    <?php $form = ActiveForm::begin(
        [
            'id' => 'project-form',
            //'action' => [ 'stat_ostatki/barcode_to_naklad' ],
            'action' => [ '/sklad_cs/from_cs_one_barcode' ],
            'method' => 'get',

            'options' => [
                //'data-pjax' => 1,
                'autocomplete' => 'off',

            ],

            'validateOnChange' => true,

            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
            'validateOnSubmit' => false,
            'validateOnBlur' => false,

        ] );
    ?>

    <?php
    echo $form->field( $model_text, 'find_name' )
        ->widget(
            AutoComplete::className(), [

            'options' => [
                'class' => 'form-control',
                'style' => 'width:60%;overflow: hidden;max-height: 100px;'
            ],

            'clientOptions' => [
                'source' => $pool,
                'autoFill' => true,
                'minLength' => '3',
                'showAnim' => 'fold',

                //                'select' => new JsExpression( "function( event, ui ) {
                //                $('#user-company').val(ui.item.id);
                //            }" )

            ],
        ])
        ->textInput(['placeholder' => 'Штрих-код', 'autofocus' => true,])
        ->label(false);
    ?>

    <?= Html::submitButton('Поиск', ['id' => 'button_poisk', 'class' => 'btn btn-lg btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>


<div class="pv_motion_create_ok_button">

    <?php
    echo Html::a('Выход', ['/'], [
        //'onclick'=>"window.history.back();",
        'onclick'=>"window.history.back();",
        'class' => 'btn btn-warning']);


    ?>
</div>


<?php

if (isset($alert_str) && !empty($alert_str)) {
    echo '<div class="alert_str" style="display:block;background-color:#ff71003d;font-size:26px;padding: 41px 10%;width:76%;margin: 0px 10%;position: absolute;top: 278px;">' .
        $alert_str
        . '</div>';
}

?>










