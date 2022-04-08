<?php

?>


<div id="tz1">

    <?php use yii\widgets\ActiveForm;

    $form = ActiveForm::begin(); ?>


    <div class="scroll_window">
        <div class="pv_motion_create_all_new">

            <div class="pv_motion_create_right">
            </div>

            <pre>
Добрый день!
Прошу зарегистрировать МСАМ от ТТ на след. Гос номера:

Алматыэлектротранс (___) маршрут

                <?php
                //dd($model);

                //  [wh_tk_amort] => 1
                //  [wh_tk_element] => 14
                //  [bar_code] => 123123123123123
                //  [msam_code] => 345234523452345
                //  [bus_number_bort] => 47
                //  [bus_number_gos] => 48
                //  [bus_number_vin] => 48
                //  [tx] => ewrwerw


                //dd($model['array_tk_amort']);

                if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort'])) {
                    $array_msam = $model['array_tk_amort'];

                    foreach ($array_msam as $item) {

                        $var_msam_code = $var_bus_number_bort = $var_bus_number_gos = '';
                        if (isset($item['msam_code'])) $var_msam_code = $item['msam_code'];
                        if (isset($item['bus_number_bort'])) $var_bus_number_bort = $item['bus_number_bort'];
                        if (isset($item['bus_number_gos'])) $var_bus_number_gos = $item['bus_number_gos'];

                        echo "<br>" . $var_msam_code . " --- (" . $var_bus_number_bort . ')' . $var_bus_number_gos;
                    }

                } else
                    echo "<br> Не чего передавать ... ";
                ?>


                С уважением Асемтай Ибрагимов,

Специалист по ремонту ТОО "Guidejet TI" (Гайджет ТиАй)
тел.  +77010357435 +77272663363 вн. 112

</pre>


            <div class="pv_motion_create_right">
            </div>

        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>


<?
//Modal::begin([
//    'header' => '<h2>Вот это модальное окно!</h2>',
//    'toggleButton' => [
//        'tag' => 'button',
//        'class' => 'btn btn-lg btn-block btn-info',
//        'label' => 'Нажмите здесь, забавная штука!',
//    ]
//]);
//
//echo 'Надо взять на вооружение.';
//
//Modal::end();


//$this->registerJs($script, yii\web\View::POS_READY);
?>

