<?php

use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'GuideJet';
?>


<style>
    .mobile-body-content {
        background-color: #00ff432e;
        padding: 15px;
        width: 60%;
        max-width: 500px;
        min-height: 700px;
        /*max-height: 1000px;*/
        margin-left: 20%;
    }

    .jumbotron {
        text-align: center;
        padding: 10px 1px;
    }

    .width_all {
        width: 100%;
        font-size: 24px;
        /* font-weight: 900; */
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    @media (max-width: 500px) {
        .mobile-body-content {
            /*background-color: #96ff0012;*/
            background-color: #96ff0000;
            padding: 0px;
            width: 100%;
            max-width: none;
            min-height: min-content;
            max-height: none;
            margin-left: 0px;
        }

        .width_all {
            margin: 5px 2px;
            width: 99%;
            font-size: 22px;
            /* font-weight: 900; */
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            line-height: 2.1;
        }

        .wrap > .container22 {
            padding: 0px;
            width: 99%;
            overflow: auto;
            margin-top: 0px;
            margin-bottom: 4px;
        }

        .jumbotron {
            padding-top: 4px;
            padding-bottom: 0px;
            margin-bottom: 4px;
            color: inherit;
            background-color: #fff;
        }

        p > a {
            height: 50px;
        }

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
            font-size: x-large;
        }

        h1 {
            font-size: 44px;
            /*background-color: #00ff434d;*/
            padding: 15px;
        }

        .form-group {
            margin-bottom: 30px;
            background-color: #ffb70017;
            padding: 3px 6px;
            border-radius: 10px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        }

        .form-control {
            font-size: 23px;
            height: auto;
        }

    }
</style>


<?php $form = ActiveForm::begin(
    [
//        'id' => 'project-form',
        'action' => ['crm/zakaz'],
        'method' => 'POST',
    ]
);
?>


<div class="site-index">

    <div class="mobile-body-content">
        <div class="jumbotron">
            <h2> Заявка № <?= $model->id ?></h2>
            <h2> от <?= $model->dt_create ?></h2>
        </div>


        <?php

        //        ddd($model);


        //        'id' => 1
        //        'mts_id' => 37
        //        'id_ap' => 14
        //        'id_pe' => 177
        //        'crm_txt' => 'ыыва ва ыва ыва ываывава вап вапывап ыыва ва ыва ыва ываывава вап вапывап ыыва ва ыва ыва ываывава вап вапывап ыыва ва ыва ыва ываывава вап вапывап ыыва ва ыва ыва ываывава вап вапывап ыыва ва ыва ыва ываывава вап вапывап ыыва ва ыва ыва ываывава вап вапывап '
        //        'dt_create' => '16.06.2020 16:21:08'
        //        'dt_create_timestamp' => 1592302868
        //        'job_fin' => 1
        //        'zakaz_fin' => 0
        //        'code_bag' => '0'
        //        'code_job' => '18'
        //        'code_rez' => '2'
        //        'dt_update' => '16.06.2020 16:21:48'
        //        'dt_update_timestamp' => 1592302908
        //        'job_fin_timestamp' => 1592302908


        /// MTS
        echo $form->field($model, 'mts_id')->dropDownList($list_mts, ['prompt' => 'Сотрудник МТС...']);

        /// АВТОПАРК
        echo $form->field($model, 'id_ap')->dropDownList($list_ap, ['prompt' => 'АвтоПарк...']);

        /// PE
        echo $form->field($model, 'id_pe')->dropDownList($list_pe, ['prompt' => 'Автобус...']);


        echo $form->field($model, 'crm_txt')
            ->textarea([
                'style' => 'height:auto;width:100%;max-width: 100%;min-width: 100%',
                'min-rows' => '7',
                'rows' => '10',
                'placeholder' => " Задание. Текст. Краткая вводная часть.",
            ]);


        ?>


        <br>
        <br>

        <?php
        echo Html::a(
            'Выход',
            //['/crm/index'],
            ['/crm/return_to_refer'],
            [
                //'onclick' => 'window.opener.location.reload();window.close();',
                'class' => 'btn btn-warning',
            ]);
        ?>



    </div>
</div>
<br>
<br>


<?php ActiveForm::end(); ?>
