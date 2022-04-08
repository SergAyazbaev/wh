<style>
    .nav > li > a:hover {
        text-decoration: none;
        /*box-shadow: -1px 3px 7px 0px rgba(0,0,0,0.2);*/
        background-color: #7cb5ec24;;
    }

    .nav > li > a:focus {
    }


    div#alert_save {
        /*box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2);*/
        box-shadow: 20px 7px 13px 6px rgba(0, 0, 0, 0.2);
        /*background-color: #ce117d;*/
        /*color: #00ff43;*/

        padding: 17px 4%;
        width: 38%;
        display: block;
        position: absolute;
        top: 32px;
        right: 1%;
        font-family: Helvetica, Arial, sans-serif;
        font-size: 19px;
        border-radius: 20px;
        /* border: 5px solid #3c3c3c; */
        z-index: 999;


        border: 5px solid #a51d1d45;
        background-color: #e6dfdf;
        color: #ff00488c;
    }

</style>

<?
if (isset($aray_res) && (
        (int)$aray_res['count_10'] > 0 ||
        (int)$aray_res['count_3'] > 0 ||
        (int)$aray_res['count_1'] > 0)) {

    echo '<div id="alert_save"><b>Внимание! Есть непринятые Накладные!</b>';

    if (isset($aray_res['count_10']) && $aray_res['count_10'] > 0) {
        echo "<br> За десять дней " . $aray_res['count_10'] . " накладных";
    }
    if (isset($aray_res['count_3']) && $aray_res['count_3'] > 0) {
        echo "<br> За три дня " . $aray_res['count_3'] . " накладных";
    }
    if (isset($aray_res['count_1']) && $aray_res['count_1'] > 0) {
        echo "<br> За один день " . $aray_res['count_1'] . " накладных";
    }

    echo '</div>';

}
?>

<?php

use yii\bootstrap\Tabs;


echo Tabs::widget([
        'encodeLabels' => false,
        'items' => [

            [
                'label' => 'Тех.задание',
                'content' => $this->render('/sklad/sklad_in/index_tz', [
                    'searchModel_tz' => $searchModel_tz,
                    'dataProvider_tz' => $dataProvider_tz,
                    'sklad' => $sklad
                ]),
            ],


            [
                'label' => 'Приход',
                'content' => $this->render('/sklad/sklad_in/index_deliver', [
                    'searchModel_into' => $searchModel_into,
                    'dataProvider_into' => $dataProvider_into,
                    'sklad' => $sklad
                ]),
            ],


            [
                'label' => ' Шаблоны документов ',
                'content' => $this->render('/sklad/sklad_in/index_shablon', [
                    'searchModel_shablon' => $searchModel_shablon,
                    'dataProvider_shablon' => $dataProvider_shablon,
                    'sklad' => $sklad
                ]),
            ],


            [
                'active' => true,
                'label' => 'Склад. (' . $sklad . ') Все накладные. ',
                'content' => $this->render('/sklad/sklad_in/index_sklad', [
                    'searchModel_sklad' => $searchModel_sklad,
                    'dataProvider_sklad' => $dataProvider_sklad,
                    'sklad' => $sklad,
                    'model' => $model,

                ]),
            ],


        ],
    ]
);


?>


<?php
$script = <<<JS
$(document).ready(function() {
    $('#alert_save').fadeOut(5000); // плавно скрываем окно временных сообщений
});


////////////
$('#go_home').click(function() {    
    window.history.back();  
})

JS;

$this->registerJs($script);
//$this->registerJs( $script, View::POS_READY );
?>

