<?php

use frontend\models\post_spr_globam;
use frontend\models\post_spr_globam_element;
//use frontend\models\posttk;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_things;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;

?>

<style>
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

    table.multiple-input-list.table-renderer tbody tr > td {
        border: 0 !important;
        padding: 0px;
    }

    label.control-label {
        color: #b81900;
        margin: auto;
        margin-top: auto;
        margin-top: 10px;
        font-size: x-large;
        padding-left: 2%;
        font-style: italic;
    }

    select option {
        color: #5a5a4ae3;
        background-color: #28582817;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .pv_motion_create_left {
        display: block;
        position: relative;
        width: auto;
        min-width: 322px;
        padding: 0px 6px;
        margin: 5px 15px;
        margin-bottom: 5px;
        margin-bottom: 5px;
        margin-bottom: 10px;
    }

    @media (max-width: 710px) {

        h1 {
            font-size: 20px;
            padding: 5px 15px;
        }

        .pv_motion_create_left {
            min-width: 267px;
            display: block;
            padding: 25px;
            margin-bottom: 15px;
            margin-left: 7px;
        }

        .pv_motion_create_left_date {
            overflow: auto;
            padding: 10px;
            margin: 0px;
        }
    }

    @media (max-width: 600px) {
        .pv_motion_create_left {
            padding: 0px;
            margin: 0px;
        }

        .pv-action-form {
            display: block;
            background-color: #0043ff12;
            float: none;
        }
    }

</style>


<?php $form = ActiveForm::begin(); ?>

<div class="pv-action-form">
    <div class="pv_motion_create_left">
        <div class="pv_motion_create_left_date">
            <?php
            echo $form->field( $model, 'name_tk' )
                ->widget(
                    AutoComplete::className(), [
                                                 'clientOptions' => [
                                                     'source' => $type_words,
                                                 ],
                                             ]
                )
                ->textarea(
                    [ 'class' => 'form-control class-content-title_series',
                        'placeholder' => $model->getAttributeLabel( 'name_tk' ),
//                        'style' => "height: 58px; width: 624px; font-size: x-large; margin: 0px; max-width: 100%;min-width:225px; position: inherit; padding: 10px; max-height: 150px;min-height: 50px;"
                    ]
                )
                ->label( false );

            ?>

        </div>

        <div class="pv_motion_create_ok_button">
            <div class="form-group">

                <?php
                if ( (int)Yii::$app->getUser()->identity->group_id >= 50 )

                    if ( $model[ 'user_create_id' ] == Yii::$app->getUser()->identity->id ) {

                        echo Html::submitButton( 'Сохранить', [ 'class' => 'btn btn-success' ] );
                    }
                ?>

                <?= Html::Button(
                    '&#8593;',
                    [
                        'class' => 'btn btn-warning',
                        'onclick' => "window.history.back();"
                    ]
                )
                ?>

            </div>

        </div>

    </div>


    <div class="pv_motion_create_left_date">

        <?php
        echo $form->field( $model, 'array_tk_amort' )->widget(
            MultipleInput::className(), [

                                          'id' => 'my_id2',
                                          'theme' => 'default',
                                          'rowOptions' => [
                                              'id' => 'row{multiple_index_my_id2}',
                                          ],

                                          'min' => 0, // should be at least 2 rows
                                          'allowEmptyList' => true,//false,
                                          'enableGuessTitle' => true,//false,
                                          'sortable' => true,
                                          'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                                          'columns' => [

                                              [
                                                  'name' => 'wh_tk_amort',
                                                  'type' => 'dropDownList',
                                                  'title' => 'Группа',
                                                  'defaultValue' => [],
                                                  'items' =>
                                                      ArrayHelper::map(
                                                          post_spr_globam::find()->orderBy( 'name' )->all(),
                                                          'id', 'name'
                                                      ),

                                                  'options' => [
                                                      'style' => 'min-width:40px;overflow:auto;padding:0px;',
                                                      'prompt' => 'Выбор ...',
                                                      'id' => 'subcat211-{multiple_index_my_id2}',

                                                      'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
   $("select#subcat22-{multiple_index_my_id2}").html(data);
});
JS

                                                  ],
                                              ],

                                              [
                                                  'name' => 'wh_tk_element',
                                                  'type' => 'dropDownList',
                                                  'title' => 'Компонент',
                                                  'defaultValue' => [],
                                                  'items' =>
                                                      ArrayHelper::map(
                                                          post_spr_globam_element::find()
                                                              ->orderBy( 'name' )
                                                              ->all(),
                                                          'id', 'name'
                                                      ),


                                                  'options' => [
                                                      'style' => 'min-width:80px;overflow:auto;padding:0px;',
                                                      'prompt' => 'Выбор ...',
                                                      'id' => 'subcat22-{multiple_index_my_id2}',

                                                      'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});                     
                                
$.post("listamort_logic?id=" + $(this).val(), function(data){
   console.log(data);
   // $("select#subcat22-{multiple_index_my_id2}").html(data);
   $("select#tk-array_tk_amort-{multiple_index_my_id2}-intelligent").val(data);
   
});
JS

                                                  ],


                                              ],


                                              [
                                                  'name' => 'intelligent',
                                                  'title' => 'ШК',
                                                  'type' => 'dropDownList',
                                                  'defaultValue' => 0,
                                                  'enableError' => true,
                                                  'value' => (integer)$model->intelligent,
                                                  'options' => [
//                                'type'  => 'number',
//                                'class' => 'input-priority',
//                                'step'  => '1',
                                                      'style' => 'min-width:40px;max-width:60px;overflow:auto;padding:0px;',
                                                  ],
                                                  'items' => [
                                                      0 => 'Нет',
                                                      1 => 'Да',
                                                  ],
                                              ],


                                              [
                                                  'name' => 'ed_izmer',
                                                  'title' => 'Ед. Изм',
                                                  'type' => 'dropDownList',
                                                  'defaultValue' => [],
                                                  'options' => [
                                                      'style' => 'min-width:40px;max-width:70px;overflow:auto;padding:0px;',
                                                  ],

                                                  'items' => ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' ),


                                              ],


                                              [
                                                  'name' => 'ed_izmer_num',
                                                  'title' => 'К- во',
                                                  'defaultValue' => 1.00,
                                                  'value' => (integer)$model->ed_izmer_num,
                                                  'enableError' => true,
                                                  'options' => [
                                                      'type' => 'number',
                                                      'class' => 'input-priority',
                                                      'step' => '1',
                                                      'style' => 'min-width:40px;max-width:70px;overflow:auto;',
                                                  ]
                                              ],


//                        'type' => \yii\widgets\MaskedInput::className(),
//                            'options' => [
//                              'class' => 'input-priority',
//                              'mask' => '9[9][9].[9][9][9]',
//                          ],


                                              [
                                                  'name' => 'tx',
                                                  'title' => 'Комментарии',
                                                  'defaultValue' => '',
                                                  'enableError' => true,
                                                  'options' => [
                                                      'class' => 'input-priority',
                                                      //'style' => 'max-width: 170px;overflow: auto;'
                                                      'style' => 'overflow: auto;'

                                                  ]
                                              ]


                                          ]
                                      ]
        );
        //->label(false);


        //          'type' => \yii\widgets\MaskedInput::className(),
        //            'options' => [
        //              'class' => 'input-phone',
        //              'mask' => '999-999-99-99',
        //          ],

        ?>


    </div>


    <div class="pv_motion_create_left_date">

        <?php
        //array_tk

        echo $form->field( $model, 'array_tk' )->widget(
            MultipleInput::className(), [

                                          'id' => 'my_id',
                                          'theme' => 'default',
                                          'rowOptions' => [
                                              'id' => 'row{multiple_index_my_id}',

                                          ],

//                    'max'               => 50,
                                          'min' => 0, // should be at least 2 rows
                                          'allowEmptyList' => true,//false,
                                          'enableGuessTitle' => true,//false,
                                          'sortable' => true,
                                          'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                                          'columns' => [
                                              [
                                                  'name' => 'wh_tk',
                                                  'type' => 'dropDownList',
                                                  'title' => 'Группа',
                                                  'defaultValue' => [],
                                                  'items' =>
                                                      ArrayHelper::map(
                                                          Spr_glob::find()
                                                              ->orderBy( 'name' )
                                                              ->all(),
                                                          'id',
                                                          'name'
                                                      ),

                                                  'options' => [
                                                      'style' => 'min-width:40px;overflow:auto;padding:0px;',
                                                      'id' => 'subcat11-{multiple_index_my_id}',
                                                      'prompt' => 'Выбор ...',
                                                      'onchange' => <<< JS
                                
$.post("list?id=" + $(this).val(), function(data){    
    $("select#subcat-{multiple_index_my_id}").html(data);
});
JS
                                                  ],
                                              ],


                                              [
                                                  'name' => 'wh_tk_element',
                                                  'type' => 'dropDownList',
                                                  'title' => 'Компонент',
                                                  'defaultValue' => [],
                                                  'items' =>
                                                      ArrayHelper::map(
                                                          Spr_glob_element::find()
                                                              ->orderBy( 'name' )->all(),
                                                          'id', 'name'
                                                      )
                                                  ,
                                                  'options' => [
                                                      'style' => 'min-width:40px;overflow:auto;padding:0px;',

                                                      'prompt' => '...',
                                                      'id' => 'subcat-{multiple_index_my_id}',

                                                      'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_id}").val(data);
});
                            
                            
$.post("list_ed_izm?id=" + $(this).val(), function(data){
//    $("select#subcat-ed_izm-{multiple_index_my_id}").val(data);
    $("select#sklad-array_tk-{multiple_index_my_id}-ed_izmer").val(data);
});
JS
                                                      ,
                                                  ]
                                              ],


                                              [
                                                  'name' => 'ed_izmer',
                                                  'title' => 'Ед. изм',
                                                  'type' => 'dropDownList',
                                                  'defaultValue' => 1,
                                                  'options' => [
                                                      'style' => 'padding: 0px;min-width:75px;overflow: auto;',
                                                      'id' => "sklad-array_tk-{multiple_index_my_id}-ed_izmer",
                                                  ],

                                                  'items' => ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' ),


                                              ],


                                              [
                                                  'name' => 'ed_izmer_num',
                                                  'title' => 'Кол-во',
                                                  'defaultValue' => 1.00,
                                                  'value' => (integer)$model->ed_izmer_num,
                                                  'enableError' => true,
                                                  'options' => [
                                                      'type' => 'number',
                                                      'class' => 'input-priority',
                                                      'step' => '0.1',
                                                      'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                                                  ]
                                              ],


//                        'type' => \yii\widgets\MaskedInput::className(),
//                            'options' => [
//                              'class' => 'input-priority',
//                              'mask' => '9[9][9].[9][9][9]',
//                          ],


                                              [
                                                  'name' => 'tx',
                                                  'title' => 'Комментарии',
                                                  'defaultValue' => '',
                                                  'enableError' => true,
                                                  'options' => [
                                                      'class' => 'input-priority',
                                                      //'style' => 'max-width: 170px;overflow: auto;'
                                                      'style' => 'overflow: auto;'

                                                  ]
                                              ]
                                          ]
                                      ]
        );
        //->label(false);
        ?>


    </div>


    <div class="pv_motion_create_left_date">

        <?php

        echo $form->field( $model, 'array_casual' )->widget(
            MultipleInput::className(), [

                                          'id' => 'my_casual',
                                          'theme' => 'default',
                                          'rowOptions' => [
                                              'id' => 'row{multiple_index_my_casual}',

                                          ],

                                          'min' => 0, // should be at least 2 rows
                                          'allowEmptyList' => true,//false,
                                          'enableGuessTitle' => true,//false,
                                          'sortable' => true,
                                          'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                                          'columns' => [
                                              [
                                                  'name' => 'wh_tk',
                                                  'type' => 'dropDownList',
                                                  'title' => 'Группа',
                                                  'defaultValue' => [],
                                                  'items' =>
                                                      ArrayHelper::map(
                                                          Spr_glob::find()->all(),
                                                          'id',
                                                          'name'
                                                      ),

                                                  'options' => [
                                                      'style' => 'min-width:40px;overflow:auto;padding:0px;',
                                                      'id' => 'subcat11-{multiple_index_my_casual}',
                                                      'prompt' => 'Выбор ...',
                                                      'onchange' => <<< JS
                                
$.post("list?id=" + $(this).val(), function(data){    
    $("select#subcat-{multiple_index_my_casual}").html(data);
});
JS
                                                  ],
                                              ],


                                              [
                                                  'name' => 'wh_tk_element',
                                                  'type' => 'dropDownList',
                                                  'title' => 'Компонент',
                                                  'defaultValue' => [],
                                                  'items' =>
                                                      ArrayHelper::map(
                                                          Spr_glob_element::find()
                                                              ->orderBy( 'name' )
                                                              ->all(),
                                                          'id', 'name'
                                                      )
                                                  ,
                                                  'options' => [
                                                      'style' => 'min-width:40px;overflow:auto;padding:0px;',

                                                      'prompt' => '...',
                                                      'id' => 'subcat-{multiple_index_my_casual}',

                                                      'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_casual}").val(data);
});
                            
                            
$.post("list_ed_izm?id=" + $(this).val(), function(data){
//    $("select#subcat-ed_izm-{multiple_index_my_casual}").val(data);
    $("select#sklad-array_tk-{multiple_index_my_casual}-ed_izmer").val(data);
});
JS
                                                      ,
                                                  ]
                                              ],


                                              [
                                                  'name' => 'ed_izmer',
                                                  'title' => 'Ед. изм',
                                                  'type' => 'dropDownList',
                                                  'defaultValue' => 1,
                                                  'options' => [
                                                      'style' => 'padding: 0px;min-width:75px;overflow: auto;',
                                                      'id' => "sklad-array_tk-{multiple_index_my_casual}-ed_izmer",
                                                  ],

                                                  'items' => ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' ),


                                              ],


                                              [
                                                  'name' => 'ed_izmer_num',
                                                  'title' => 'Кол-во',
                                                  'defaultValue' => 1.00,
                                                  'value' => (integer)$model->ed_izmer_num,
                                                  'enableError' => true,
                                                  'options' => [
                                                      'type' => 'number',
                                                      'class' => 'input-priority',
                                                      'step' => '0.1',
                                                      'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                                                  ]
                                              ],


//                        'type' => \yii\widgets\MaskedInput::className(),
//                            'options' => [
//                              'class' => 'input-priority',
//                              'mask' => '9[9][9].[9][9][9]',
//                          ],


                                              [
                                                  'name' => 'tx',
                                                  'title' => 'Комментарии',
                                                  'defaultValue' => '',
                                                  'enableError' => true,
                                                  'options' => [
                                                      'class' => 'input-priority',
                                                      //'style' => 'max-width: 170px;overflow: auto;'
                                                      'style' => 'overflow: auto;'

                                                  ]
                                              ]
                                          ]
                                      ]
        );
        //->label(false);
        ?>


    </div>


    <div class="pv_motion_create_ok_button">
        <div class="form-group">

            <?php
            if ( (int)Yii::$app->getUser()->identity->group_id >= 50 )

                //if( $model['user_create_id']==Yii::$app->user->id ){

                if ( $model[ 'user_create_id' ] == Yii::$app->getUser()->identity->id ) {

                    echo Html::submitButton( 'Сохранить', [ 'class' => 'btn btn-success' ] );
                }
            ?>

            <?= Html::Button(
                '&#8593;',
                [
                    'class' => 'btn btn-warning',
                    'onclick' => "window.history.back();"
                ]
            )
            ?>

        </div>
    </div>


    <?php ActiveForm::end(); ?>




    <?php

    $script1 = <<<JS11
function  change_tk( x ){   
    //alert(123);

        $.post("list?id=" + x, function(data){
            console.log(data);    
            //$("select#subcat-{multiple_index_my_id}").html(data);
        });  
    
}
JS11;

    $this->registerJs( $script1, 2 );


    $script = <<<JS

//$('#pvmotion-type_action').change(function() {
// Обработал в контроллере!!!     
//}    

//////////////////// Debitor
$('#tz-wh_deb_top').change(function() {
    	    
    var  number = $(this).val();
    var  text = $('#tz-wh_deb_top>option[value='+number+']').text();
    
            
    	    $('#tz-wh_deb_top_name').val(text);
    	    
            // console.log(number);   
            // console.log(text);   
    
    $.ajax( {
		url: '/pvmotion/whselect',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#tz-wh_deb_element').html('');
		    $('#tz-wh_deb_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
    
    
    
});

//////////////////// Creditor
$('#tz-wh_cred_top').change(function() {
    var  number = $(this).val();
    var  text = $('#tz-wh_cred_top>option[value='+number+']').text();
    
    	    $('#tz-wh_cred_top_name').val(text);
    	    
    	    
            // console.log(number);   
            // console.log(text);   
    
    $.ajax( {
		url: '/pvmotion/whselect',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#tz-wh_cred_element').html('');
		    $('#tz-wh_cred_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
    
    
    
});



JS;

    $this->registerJs( $script, yii\web\View::POS_READY );
    ?>

