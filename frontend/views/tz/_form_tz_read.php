<?php

use \yii\widgets\Pjax;
use frontend\models\post_spr_globam;
use frontend\models\post_spr_globam_element;
use frontend\models\posttz;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhtop;
use frontend\models\Tk;

//use kartik\datetime\DateTimePicker;
//use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
//use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
//use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;

?>

<div class="message_load">
    Запрос отправлен. Сервер занят другими запросами. Ждите ответа ....
</div>

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


    .my_label {
        color: #b81900;
        margin: auto;
        margin-top: auto;
        margin-top: 10px;
        font-size: x-large;
        padding-left: 2%;
        font-style: italic;

    }

    .modal-dialog, .modal-content {
        max-height: 100%;
        height: 80%;
        overflow: auto;
        width: 880px;
        max-width: 100%;
    }

    .modal-content {
        height: 500px;
        overflow: auto;
    }

    .modal-body > .form-group {

        /*overflow: auto;*/
    }

    .modal-footer {
        display: block;
        position: absolute;
        bottom: auto;
        left: auto;
    }


    .pv_motion_create_ok_button {
        display: block;
        position: inherit;
        padding: 10px 18px;
        /*float: left;*/
        width: 98%;
        background-color: #48616121;
        /*margin: 5px;*/
        margin-top: 5px;
        background-color: #48616121;

    }


</style>


<?php $form = ActiveForm::begin(
    [
        //'action' => [ 'tz/open_tz_new_tk' ]
    ]
);

?>

<?php

/// По этому полю строится переменная _id
echo $form->field( $model, '_id' )->hiddenInput()->label( false );


if ( empty( $model->dt_create ) )
    $model->dt_create = Date( 'd.m.Y H:i:s', strtotime( 'now' ) );
?>


<div id="tz1">

    <div class="pv_motion_create_right_center">

        <div class="tree_land2">
            <?php
            $xx = posttz::find()->all();
            $type_words = ArrayHelper::getColumn( $xx, 'name_tz' );

            if ( isset( $val12 ) )
                $model->name_tz = $val12[ 'val_0_1' ];


            echo $form->field( $model, 'name_tz' )
                ->textarea(
                    [
                        'class' => 'form-control class-content-title_series',
                        'disabled' => 'disabled',
                    ]
                );
            ?>
            ТЗ № <?= $model->id ?>
        </div>

        <div class="tree_land">

            <?php
            $type_words = [
                '1' => 'Первичная полная установка',
                '2' => 'Первичная частичная установка',
                '3' => 'Демонтаж ',
                '4' => 'Монтаж',
                '5' => 'Выдача расходных материалов',

            ];


            echo $form->field( $model, 'street_map' )
                ->dropDownList(
                    $type_words,
                    [
                        'prompt' => 'Без карты',
                        'disabled' => 'disabled',
                    ]
                )
                ->label( "Дорожная карта" )//->hint("Дорожная карта")
            ;


            ///////////

            echo $form->field( $model, 'id_tk' )->dropdownList(
                ArrayHelper::map( Tk::find()->all(), 'id', 'name_tk' ),
                [
                    'prompt' => 'Без ТК... ',
                    'disabled' => 'disabled',
                ]
            );


            ?>
        </div>

        <div class="tree_land">
            <?php
            echo $form->field( $model, 'wh_cred_top' )->dropdownList(
                ArrayHelper::map( Sprwhtop::find()->all(), 'id', 'name' ),
                [
                    'prompt' => 'Выбор склада ...',
                    'disabled' => 'disabled',
                ]
            )->label( "Автопарк" );
            ?>


            <div id="div_bus">
                <?php

                if ( empty( $model[ 'array_bus' ] ) )
                    $sum_bus = 0;
                else
                    $sum_bus = count( $model[ 'array_bus' ] );


                // Using a select2 widget inside a modal dialog
                Modal::begin(
                    [
                        //'header' => 'modal header',
                        'header' => false,
                        'options' => [
                            'id' => 'kartik-modal',
                            //'tabindex' => true // important for Select2 to work properly
                        ],
                        'toggleButton' => [
                            'label' => 'Автобусы ('.$sum_bus.')',
                            'class' => 'btn btn-default'  // btn-primary'
                        ],
                    ]
                );

                //                ddd($model);

                echo $form->field( $model, 'array_bus' )
                    ->textInput(
                        [
                            //'validationDelay'=>5,
                            'style' => "width: 100%;display: inline;",
                            'disabled' => 'disabled',

                        ]
                    );


                ?>


                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Прикрыть список</button>
                </div>


                <?php

                Modal::end();
                ?>


            </div>
        </div>

        <div class="tree_land">
            <?php
            //  Всего АВТОБУСОВ
            // echo '<p>Количество комплектов:</p>';
            echo $form->field( $model, 'multi_tz' )
                ->textInput(
                    [
                        //'validationDelay'=>5,
                        'style' => "width: 100%;display: inline;",
                        'disabled' => 'disabled',

                    ]
                );


            /////////
            $date_now = date( "d.m.Y HH:ii:ss", strtotime( 'now' ) );

            if ( empty( $model->dt_deadline ) )
                $model->dt_deadline = $date_now;


            echo $form->field( $model, 'dt_deadline' )
                ->textInput(
                    [
                        //'validationDelay'=>5,
                        'style' => "width: 100%;display: inline;",
                        'disabled' => 'disabled',

                    ]
                );


            ?>
        </div>

    </div>


    <div class="pv_motion_create_ok_button">
        <?= Html::Button(
            ' Выход ',
            [
                'class' => 'btn btn-warning',
                'onclick' => "window.history.back();location.close()"
            ]
        );
        ?>


        <?php
        echo Html::a(
            'Печать',
            [ '/tz/html_pdf?id='.$model->id ],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]
        );
        ?>

        <?php
        echo Html::a(
            'ТЗ Норма Расхода',
            [ '/tz/html_pdf_norma?id='.$model->id ],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]
        );
        ?>

        <?php
        echo Html::a(
            'ТЗ Норма Расхода Версия 3.0',
            [ '/tz/html_pdf_norma_3?id='.$model->id ],
            [
                'id' => "to_print",
                'target' => "_blank",
                'class' => 'btn btn-default'
            ]
        );
        ?>
    </div>

    <?php Pjax::begin(); ?>

    <div class="pv_motion_create_all_new">
        <div class="pv_motion_create_right">

            <?php
            echo $form->field( $model, 'array_tk_amort' )
                ->widget(
                    MultipleInput::className(), [

                                                  'id' => 'my_id2',
                                                  'theme' => 'default',
                                                  'rowOptions' => [
                                                      'id' => 'row{multiple_index_my_id2}',
                                                  ],
                                                  //                    'max'               => 25,
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
                                                          'defaultValue' => 0,
                                                          'items' => ArrayHelper::map(
                                                              post_spr_globam::find()
                                                                  ->orderBy( 'name' )
                                                                  ->all(),
                                                              'id', 'name'
                                                          ),

                                                          'options' => [
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

                                                          'options' => [
                                                              'id' => 'subcat22-{multiple_index_my_id2}',
                                                              'style' => 'min-width: 336px;',
                                                              'prompt' => '...',

                                                              'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});                             

$.post("listamort_logic?id=" + $(this).val(), function(data){
   //console.log(data);   
   $("select#tz-array_tk_amort-{multiple_index_my_id2}-intelligent").val(data);
});
JS
                                                              ,
                                                          ],
                                                          'items' =>
                                                              ArrayHelper::map(
                                                                  post_spr_globam_element::find()
                                                                      ->orderBy( 'name' )
                                                                      ->all(),
                                                                  'id', 'name'
                                                              )
                                                          ,

                                                      ],

                                                      [
//                            'id' => 'subcat22-{multiple_index_my_id2}',
                                                          'name' => 'intelligent',
                                                          'title' => 'Штрих',
                                                          'type' => 'dropDownList',
                                                          'defaultValue' => 0,
                                                          'enableError' => true,

                                                          'value' => (integer)$model->intelligent,
                                                          'options' => [
                                                              'style' => 'width:60px;padding: 0px;'

                                                          ],
                                                          'items' => [
                                                              0 => 'Нет',
                                                              1 => 'Да',
                                                          ],

                                                      ],


                                                      [
                                                          'name' => 'ed_izmer_num',
                                                          'title' => 'Кол-во',
                                                          'defaultValue' => 1,
                                                          'enableError' => true,
                                                          'value' => (integer)$model->ed_izmer_num,
                                                          'options' => [
                                                              'type' => 'number',
                                                              'class' => 'input-priority',
                                                              'step' => '1',
                                                              'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                                                          ]
                                                      ],

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
                )
                ->label( 'Амортизация', [ 'class' => 'my_label' ] );

            ?>

        </div>


        <div class="pv_motion_create_right">
            <?php
            echo $form->field( $model, 'array_tk' )->widget(
                MultipleInput::className(), [

                                              'id' => 'my_id',
                                              'theme' => 'default',
                                              'rowOptions' => [
                                                  'id' => 'row{multiple_index_my_id}',
                                              ],

//                    'max'               => 25,
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
                                                      //'items' => Spr_glob::find()->select(['name'])->column(),
                                                      'items' =>
                                                          ArrayHelper::map(
                                                              Spr_glob::find()
                                                                  ->orderBy( [ 'name' ] )
                                                                  ->all(),
                                                              'id',
                                                              'name'
                                                          ),

                                                      'options' => [
                                                          'prompt' => 'Выбор ...',
                                                          'id' => 'subcat11-{multiple_index_my_id}',

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
                                                                  ->orderBy( 'name' )
                                                                  ->all(),
                                                              'id', 'name'
                                                          ),

                                                      'options' => [
                                                          'prompt' => '...',
                                                          'id' => 'subcat-{multiple_index_my_id}',
                                                          'style' => 'min-width: 336px;',
                                                          'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_id}").val(data);
});
                            
                            
$.post("list_ed_izm?id=" + $(this).val(), function(data){
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
                                                          'style' => 'width:60px;padding: 0px;',
                                                          'id' => "sklad-array_tk-{multiple_index_my_id}-ed_izmer",
                                                      ],

                                                      'items' => ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' ),


                                                  ],

                                                  [
                                                      'name' => 'ed_izmer_num',
                                                      'title' => 'Кол-во',
                                                      'defaultValue' => 1,
                                                      'enableError' => true,
                                                      'value' => (integer)$model->ed_izmer_num,
                                                      'options' => [
                                                          'type' => 'number',
                                                          'class' => 'input-priority',
                                                          'step' => '0.1',
                                                          'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                                                      ]
                                                  ],

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
            )
                ->label( 'Списание', [ 'class' => 'my_label' ] );
            ?>

        </div>


        <div class="pv_motion_create_right">

            <?php
            //dd($model);

            echo $form->field( $model, 'array_casual' )->widget(
                MultipleInput::className(), [

                                              'id' => 'my_casual',
                                              'theme' => 'default',
                                              'rowOptions' => [
                                                  'id' => 'row{multiple_index_my_casual}',
                                              ],

//                    'max'               => 25,
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
                                                      //'items' => Spr_glob::find()->select(['name'])->column(),
                                                      'items' =>
                                                          ArrayHelper::map(
                                                              Spr_glob::find()
                                                                  ->orderBy( 'name' )
                                                                  ->all(),
                                                              'id',
                                                              'name'
                                                          ),

                                                      'options' => [
                                                          'prompt' => 'Выбор ...',
                                                          'id' => 'subcat11_casual-{multiple_index_my_casual}',

                                                          'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    $("select#subcat_casual-{multiple_index_my_casual}").html(data);
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
                                                          'prompt' => '...',
                                                          'id' => 'subcat_casual-{multiple_index_my_casual}',
                                                          'style' => 'min-width: 336px;',
                                                          'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11_casual-{multiple_index_my_casual}").val(data);
});
                            
                            
$.post("list_ed_izm?id=" + $(this).val(), function(data){
    $("select#sklad-array_casual-{multiple_index_my_casual}-ed_izmer").val(data);
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
                                                          'style' => 'width:60px;padding: 0px;',
                                                          'id' => "sklad-array_casual-{multiple_index_my_casual}-ed_izmer",
                                                      ],

                                                      'items' => ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' ),


                                                  ],

                                                  [
                                                      'name' => 'ed_izmer_num',
                                                      'title' => 'Кол-во',
                                                      'defaultValue' => 1,
                                                      'enableError' => true,
                                                      'value' => (integer)$model->ed_izmer_num,
                                                      'options' => [
                                                          'type' => 'number',
                                                          'class' => 'input-priority',
                                                          'step' => '0.1',
                                                          'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                                                      ]
                                                  ],

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
            )
                ->label( 'Расходные материалы', [ 'class' => 'my_label' ] );

            ?>


        </div>


    </div>

    <?php Pjax::end(); ?>

</div>

<?php ActiveForm::end(); ?>




<?php
$script = <<<JS
///////////////
// функция принимает элемент, который необходимо центрировать
function alignCenter(elem) {
  elem.css({ // назначение координат left и top
    left: ($(window).width() - elem.width()) / 2 + 'px',
    top: ($(window).height() - elem.height()) / 2 + 'px'
  })
}
////


/////////
$('#tz-id_tk').change(function() {    

    var  number_tz_id = $('#tz-_id').val();
    var  number_tk = $(this).val();    
            if (number_tk==0) {
                   return false;  // Защита от Нулевого ВЫБОРА  
            }          
        
    var  number_array_bus_select =  $('#array_bus_select').val() ;    
//      console.log('number_array_bus_select='+number_array_bus_select);

    
    
//alert(number_array_bus_select<1 );
    
//            if ( !number_array_bus_select || number_array_bus_select<1) {            
//                    $(".message_load").fadeIn(300).text('Заполните, пожалуйста, список автобусов');
//                    $('.message_load').fadeOut(3000); // плавно скрываем окно/фон                    
//                    return false;  // Защита от ОТСУТСВИЯ АВТОБУСОВ
//             } 
    
                
    if ( !number_array_bus_select || number_array_bus_select<1) {
        number_array_bus_select=0;
    }
    
    var  number_name_tz = $('#tz-name_tz').val()  ;
    //alert(text);
    
    var  number_multi_tz = $('#tz-multi_tz').val()  ;
    var  number_dt_deadline = $('#tz-dt_deadline').val()  ;

    var var_id_cred=$('#tz-wh_cred_top').val();    
    var  number_wh_cred_name = $('#tz-wh_cred_top>option[value='+var_id_cred+']').text();

       
    var  val_0_1 = $('#tz-name_tz').text();           //// ..ТехЗадание

    
    
    

    $.ajax( {
		       //url: '/tz/createnext',		
		 url:  '/tz/open_tz_new_tk',
		type:  "POST",	
		async: false, // Чтобы не отваливалась и не пугала ЛЮДЕЙ
		
		data: {
		    id_tz       : number_tz_id,		    		    
		    id_tk       : number_tk,		        		    
		    array_bus_select : number_array_bus_select,		        		    
		    name_tz     : number_name_tz,		        		    
		    multi_tz    : number_multi_tz,		        		    
		    dt_deadline : number_dt_deadline,		        		    
		    wh_cred_top_name : number_wh_cred_name,		        		    
		},
				
	    beforeSend: function(){
	    
                    $(".message_load").text('Запрос отправлен. Сервер занят...').fadeIn( 80, function() {
                        $('.message_load').fadeOut(5000); // плавно скрываем окно/фон
                    });        		       
		    },
		
			success: function(res) {
			        $(".message_load").hide();
        		    $('#tz1').html('');
        		    $('#tz1').html(res);
		    },
		    
			error: function( res ) {													    
							    $('#tz1').html('Запрос не вернул значение. '+res);
							    console.log(res);
					}					
    } );
    
    
    
});




//////////////////// Debitor
$('#tz-wh_deb_top').change(function() {
    var  number = $(this).val();
    var  text = $('#tz-wh_deb_top>option[value='+number+']').text();
    
    $('#tz-wh_deb_top_name').val(text);

            console.log(number);   
            console.log(text);   
    
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
    	    
    	    
            console.log(number);   
            console.log(text);   
    
            alert('/pvmotion/whselect');
    
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


/////////////
$('#go_home').click(function() {    
    window.history.back();  
})
JS;

$this->registerJs( $script, yii\web\View::POS_READY );
?>

