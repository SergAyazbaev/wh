<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>


<style>
    div > .has-error > .help-block {
        padding: 9px;
        font-size: 21px;
        background-color: #ffd57fd4;
        color: crimson;
        width: 80%;
        left: 10%;
        text-align: center;
    }


    .glyphicon-plus {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;
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
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        width: 100%;
    }

    td div input {
        background-color: rgba(35, 216, 57, 0.21);
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
    }


    .list-cell__button {
        text-align: center;
    }

    table.multiple-input-list.table-renderer tbody tr > td {
        border: 0 !important;
        padding: 0;
    }

    .pv_motion_create_right_center {
        min-width: 345px;
        background-color: #74bb2e33;
        display: block;
        position: relative;
    }


    .pv_motion_create_ok_button {
        width: 100%;
    }


    @media (max-width: 780px) {
        .scroll_window {

            width: 99%;
            display: grid;
            position: inherit;
            overflow: auto;
            margin-bottom: 100px;
        }

    }


    @media (max-width: 710px) {

        h1 {
            font-size: 20px;
            padding: 5px 15px;
        }

        .pv_motion_create_right_center {
            width: 100%;
            min-width: 270px;
            background-color: #74bb2e33;
            display: block;
            position: inherit;
            float: left;
            padding: 3px;
            margin-left: 5px;
            margin-bottom: 5px;
        }


        .pv_motion_create_ok_button {
            display: block;
            position: inherit;
            padding: 10px 18px;
            float: left;
            width: 100%;
            background-color: #48616121;
            /*margin: 5px;*/
            margin-top: 5px;
            background-color: #48616121;
        }

    }
</style>


<style>
    .pv_motion_create_right_center {
        width: 100%;
        min-width: 240px;
        margin: 0px;
        padding: 3px 3px;
    }

    @media (min-width: 605px) {
        .pv_motion_create_right_center {
            padding: 3px 3px;
        }
    }

    @media (min-width: 1214px) {
        .pv_motion_create_right_center {
            padding: 3px 10px;
        }
    }


    .tree_land3 {
        width: 285px;
    }
</style>


<?php $form = ActiveForm::begin(
    [
        'action' => [ 'tz/new_bus' ],
        'method' => 'POST',

        'options' => [
            'data-pjax' => 0,
            'autocomplete' => 'off',
        ],
    ]
);
?>


<div id="tz1">
    <div class="pv_motion_create_right_center">

        <div class="tree_land3">
            <?php
            echo "<br><h2> В справочнике </h2>";
            echo "<h2> под номером: ".$model->id." </h2>";
            ?>

        </div>


        <div class="tree_land">
            <?php

            echo $form->field( $model, 'name' )->input( 'text', [ 'readonly' => 'readonly', 'disabled' => 'disabled' ] );
            echo $form->field( $model, 'nomer_borta' )->input( 'text' );
            echo $form->field( $model, 'nomer_gos_registr' )->input( 'text' );
            echo $form->field( $model, 'nomer_vin' )->input( 'text' );
            ?>
        </div>


    </div>


    <div class="pv_motion_create_ok_button">


        <!--        --><?php
        //        ////////// ALERT
        //
        //        if ( isset( $alert_mess ) && !empty( $alert_mess ) ) {
        //            echo Alert::widget(
        //                [
        //                    'options' => [
        //                        'class' => 'alert_save',
        //                        'animation' => "slide-from-top",
        //                    ],
        //                    'body' => $alert_mess,
        //                ]
        //            );
        //        }
        //
        //        ////////// ALERT
        //        ?>


        <?php
        echo Html::a(
            'Ок. Создали. Возвращаемся ', [ '/tz' ], [
                                            'onclick'=>"window.history.back();",
                                            'class' => 'btn btn-warning',
                                        ]
        );
        ?>

        <?php
        echo Html::a(
            'Сохранить доп изменения', [ '/tz/new_bus_resave' ], [
                                         'onclick'=>"window.history.back();",
                                         'class' => 'btn btn-success',
                                         'data' => [
                                             'confirm' => 'Хотите ?',
                                             'method' => 'post',
                                         ],
                                     ]
        );
        ?>

    </div>


    <div class="pv_motion_create_right">

    </div>


    <div class="pv_motion_create_right">

    </div>


    <div class="pv_motion_create_right">

    </div>
</div>


<?php ActiveForm::end(); ?>


<?php
$script = <<<JS

//////////////////// sprwhtop-name
$('#sprwhtop-name').change(function() {        
    var  number = $(this).val();
    $.ajax( {
		url: '/tz/list_whelement',
		data: {
		    id :number
		},
		async: false, 
		//dataType: "json",		
			success: function(res) {
		            $('#array_bus_select').html('');
		            $('#array_bus_select').html(res);		            		            
					},
			error: function( res) {
						alert(' Сбой сети1 '+res );
						console.log(res);
					}
    } );
    
    
});




////////////
$('#go_home').click(function() {    
    window.history.back();  
})

JS;

$this->registerJs( $script, yii\web\View::POS_READY );
?>

