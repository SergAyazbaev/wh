<?php


use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = ' Запрос ЦС '; // Заголовок

?>
<style>
    /*.navbar-inverse {*/
    /*    height: 0px;*/
    /*}*/
    .glyphicon-plus {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;

        /*padding: 7px;*/
        /*margin: -8px;*/
        font-size: medium;
        font-weight: bold;
        border-radius: 25px;
    }

    .glyphicon-plus:hover {
        color: rgba(255, 255, 255, 0.53);
        background-color: #5cb85c;
        border-color: #4cae4c;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);

    }

    /*.help-block {*/

    /*}*/
    .help-block-error {
        display: none;
    }

    thead th {
        height: 22px;
        padding: 10px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    table tr, table td, {
        border: 0px;
        margin: 0px;
        width: 100%;
    }

    thead tr, thead td {
        background-color: rgba(11, 147, 213, 0.12);
        padding: 5px 10px;
        margin: 0px;
        padding: 10px;
    }

    tbody tr, tbody td {
        padding: 1px 3px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    td div select {
        /*background-color: rgba(65, 255, 61, 0.24);*/
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        width: 100%;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }

    td div input {
        background-color: rgba(35, 216, 57, 0.21);
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }
</style>


<div id="tz1">
    <?php $form = ActiveForm::begin([
        'id' => 'project-form',
        'action' => ['from_cs'],
        //        'action' => [ 'fork' ],
        'options' => [
            'data-pjax' => 1,
            'autocomplete' => 'off',
            'method' => 'PUT',
        ],
    ]);
    ?>

    <div class="pv_motion_create_right_center">

        <div class="tree_land2">

            <?= "<br>склад " . $model_sklad['id']; ?>

            <?= "<br><h2> Целевой склад (ЦС) " . (isset($model->id) ? $model->id : '') . "</h2>"; ?>

        </div>


        <div class="tree_land">


            <?php
            echo $form->field($model_sklad, 'id')->hiddenInput()->label(false); // тоже надо!
            ?>

            <?php

            echo $form->field($model_top, 'id')->dropDownList($list_group)->label('АП');

            ///////////////////

            ?>
            <?php

            echo $form->field($model_element, 'id')->widget(Select2::className()
                , [
                    'name' => 'st',
                    'data' => $list_element,

                    //                        'data' =>   ArrayHelper::map(
                    //                            Sprwhelement::find()
                    //                                ->where(['parent_id'=>(integer)$model_top->wh_destination])
                    //                                ->orderBy('name')
                    //                                ->all()
                    //                            ,'id','name'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'size' => Select2::SMALL, //LARGE,
                ])->label("ПЕ");

            ?>
        </div>


    </div>


    <div class="pv_motion_create_ok_button">

        <?= Html::Button(
            'Выход',
            [
                'class' => 'btn btn-warning',
                'onclick'=>"window.history.back();",
            ]);
        ?>


        <?= Html::submitButton('Запросить ОСТАТКИ. Простая накладная',
            [
                'class' => 'btn btn-success',
            ]);
        ?>

        <!--        --><?php //= Html::submitButton( 'Запросить ОСТАТКИ. Интерфейс Замены',
        //            [
        //                'class' => 'btn btn-success',
        //                'name' => 'contact-button',
        //                'value' => 'func-change',
        //            ] )
        //        ?>


        <!--        --><?php
        //        echo Html::a('Отменить накладную ',
        //            ['/sklad/transfer_dont/?id=' . (isset($model->_id) ? $model->_id : 0)],
        //            [
        //                'class' => 'btn btn-danger'
        //            ]);
        //        ?>


    </div>

</div>


<?php ActiveForm::end(); ?>


<?php
$script = <<<JS
//////////////////// CLOSE --ALERT--
// $(document).ready(function() {
//     $('.alert_save').fadeOut(2500); // плавно скрываем окно временных сообщений
// });





//////////////////// Debitor -top
$('#sprwhtop-id').change(function() {
    
        var  number = $(this).val();
        //alert(number);


    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},			
			success: function(res) {		 
		            // $('#sprwhelement-id').html('');
		            $('#sprwhelement-id').html(res);		    
					},
			error: function( res) {
						alert('JS.sprwhelement-id '+res );
						console.log(res);
					}
    } );
    
    
    
});



////////////
$('#go_home').click(function() {    
    window.history.back();  
})

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>




