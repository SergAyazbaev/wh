<?php

//use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
//use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\Html;
//use yii\web\View;
use yii\widgets\ActiveForm;

//use yii\widgets\Pjax;



?>
<style>

    .tree_land>h3 {
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
    .tree_land3{
        min-width: 200px;
    }

</style>



<?php
//Pjax::begin(['id' => "pjax-container",]);
//echo \yii::$app->request->get('page');

?>

<?php ActiveForm::begin();?>

<?php $form = ActiveForm::begin([
    'options'=>[
        'data-pjax'=>true,
        //                'data-confirm'=>'normal_doc'
    ],
    'enableClientValidation'    => true, //false,
    'validateOnChange'          => true, //false,
    'validateOnSubmit'          => false,//true,
    //    'enableAjaxValidation'      => true,
    //    'validateOnBlur'            => true,//false,false,

]);

?>



<div id="tz1">

    <div class="pv_motion_create_right_center">

        <div class="tree_land3">
            <?php
//            ddd($model);
//            'id' => 20
//        'user_create_id' => '13'
//        'user_edit_group_id' => '50'
//        'user_create_name' => 'user'
//        'dt_create' => '03.05.2019 11:55:03'
//        'name_tz' => '55555'
//        'street_map' => '1'
//        'multi_tz' => 51
//        'wh_cred_top' => '14'
//        'dt_deadline' => '30.06.2019 21:45:0'
//        'wh_cred_top_name' => 'City Bus ТОО'
//        'user_edit_id' => 13
//        'user_edit_name' => 'user'

//            'id_tk' => '3'
//        'status_state' => 1
//            status_create_date

//            'wh_debet_top' => '4'
//                'wh_debet_element' => '86'
//                'wh_destination' => '4'
//                'wh_destination_element' => '87'
//                'array_bus' => ''
//                'user_id' => 5
//                'user_name' => 'VICTOR5'
//                'user_group_id' => 40
//                'tz_id' => 20
//                'tz_name' => '55555'
//                'tz_date' => '03.05.2019 11:55:03'
//                'dt_create' => '03.05.2019 11:58:57'
//                'dt_deadline' => '30.06.2019 21:45:0'
//                'wh_debet_name' => 'Guidejet TI. Основной склад'
//                'wh_debet_element_name' => 'АСУОП'
//                'wh_destination_name' => 'Guidejet TI. Основной склад'
//                'wh_destination_element_name' => 'Сырье и материалы'
//                'user_ip' => '127.0.0.1'
//                'dt_update' => '04.05.2019 18:25:21'
//                'update_user_id' => 5
//                'update_user_name' => 'VICTOR5'
//                'dt_transfered_date' => '03.05.2019 18:14:31'
//                'dt_transfered_user_id' => 5
//                'dt_transfered_user_name' => 'VICTOR5'


            //ddd($model['name_tz']);

            //Создано по ТехЗаданию
            echo "ТехЗадание №".$model['id']."<h3>" .$model['name_tz']. " </h3>";
            echo "ПЕ:".$model['multi_tz'];
            ?>

            <?php
            //   if( isset($model['array_bus'])   ){

            ///////////
            if (empty($model['array_bus']))
                $sum_bus = 0;
            else
                $sum_bus = count ($model['array_bus']);

            //       dd($items_auto);
            ///////////
            ///

            // Using a select2 widget inside a modal dialog

            if(isset($items_auto) && !empty($items_auto)){
                Modal::begin([
                    //'header' => 'modal header',
                    'header' => false,
                    'options' => [
                        'id' => 'kartik-modal',
                        //'tabindex' => true // important for Select2 to work properly
                    ],
                    'toggleButton' => [
                        'label' => 'Автобусы ('.$sum_bus.')' ,
                        'class' => 'btn btn-default'  // btn-primary'
                    ],
                ]);

                echo $form->field($model, 'array_bus')->widget(Select2::className()
                    , [
                        ///echo Select2::widget([
                        'name' => 'st',
                        //'data' => $items_auto,
                        'data' => $items_auto,
                        'options' => [

                            'id' => 'array_bus_select',
                            'placeholder' => 'Список автобусов ...',
                            'allowClear' => true,
                            'multiple' => true

                        ],
                        'theme' => Select2::THEME_BOOTSTRAP, // THEME_DEFAULT, //THEME_CLASSIC, //THEME_BOOTSTRAP,
                        'size' => Select2::SMALL, //LARGE,
                        'pluginOptions' => [
                            'tags' => true,
                            'tokenSeparators' => [',', ' '],
                            'maximumInputLength' => 10
                        ],
                    ]);

                ?>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Прикрыть список</button>
                </div>

                <?
                echo Alert::widget([
                    'options' => [
                        'class' => 'alert-info'
                    ],
                    'body' => 'Кнопка "Прикрыть список" не удаляет и не отменяет изменения списка '
                ]);


                Modal::end();
            }
            ?>

        </div>


        <div class="tree_land">
            Для адресата:<h3><?=$model['wh_cred_top_name'];?> </h3>
            ДКарта:<h3><?=$model['street_map'];?> </h3>

        </div>


        <div class="tree_land">

            Дата создания:<h3><?=$model['dt_create'];?> </h3>
            Дата окончания:<h3><?=$model['dt_deadline'];?> </h3>

        </div>

        <div class="tree_land">

            Создал:<h3><?=$model['user_create_name'];?></h3>
            Радакт.:<h3><?=$model['user_edit_name'];?> </h3>

        </div>
    </div>




    <div class="pv_motion_create_ok_button">


        <?php
        echo Html::a('Выход', ['/statistics'] ,['class' => 'btn btn-warning'] );
        ?>

        <?
        echo Html::submitButton('11',
            ['class' => 'btn btn-success']
        );
        ?>


        <?php
        echo Html::a('1',
            ['/sklad/copycard_from_origin?id=' . $model['id'] ],

            [
                'data-confirm' => Yii::t('yii',
                    '<b>Вы точно хотите: </b><br>
                        Создать КОПИЮ этой НАКЛАДНОЙ ?'),

                'id' => "to_print",
                //'target' => "_blank",
                'class' => 'btn  btn-info' //btn-primary'   //btn-success'
            ]);
        ?>


        <?
        echo Html::a('actionStat_1',
            ['/statistics/stat_1?id=' . $model['id'] ],
            [
                'id' => "to_print",
                //'target' => "_blank",
                'class' => 'btn btn-default'
            ]);
        ?>


    </div>
</div>

<?php ActiveForm::end();?>

<div id="stat_result">
    123
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



