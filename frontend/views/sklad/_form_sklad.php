<?php

use frontend\models\post_spr_glob;
use frontend\models\post_spr_glob_element;
use frontend\models\post_spr_globam;
use frontend\models\post_spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\Alert;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

//use yii\widgets\Pjax;


?>
<style>
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


<?php
//Pjax::begin(['id' => "pjax-container",]);
//echo \yii::$app->request->get('page');

?>

<?php $form = ActiveForm::begin(
    [
        'options' => [
            'data-pjax' => true,
            //                'data-confirm'=>'normal_doc'
        ],
        'enableAjaxValidation' => false, //!!!!!! Важно
        'enableClientValidation' => true,//false,
        'validateOnChange' => true,//false,
        //            'validateOnSubmit'          => true,
        'validateOnBlur' => true,//false,false,


    ]
);
//createfromtz
?>


<div id="tz1">


    <div class="pv_motion_create_right_center">

        <div class="tree_land3">

            <?php


            if ( isset( $tz_head ) && !empty( $tz_head ) ) {

                Modal::begin(
                    [
                        'header' => '<h3>ТехЗадание № '.$tz_head[ 'id' ].'</h3> <h3>Тема:  '.$tz_head[ 'name_tz' ].' </h3>',
                        //                    'header' => false,
                        'options' => [
                            'id' => 'kartik-modal1',
                            //'tabindex' => true // important for Select2 to work properly
                        ],
                        'toggleButton' => [
                            'label' => 'ТехЗадание №'.$tz_head[ 'id' ],
                            'class' => 'btn btn-default'  // btn-primary'
                        ],
                    ]
                );


                ?>

                <table class="modal_left">
                    <thead>
                    <tr>
                        <td>
                            Сокращение
                        </td>
                        <td>
                            Значение
                        </td>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>
                            Конечный адресат установки
                        </td>
                        <td>
                            <?= $tz_head[ 'wh_cred_top_name' ] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Количество ПЕ
                        </td>
                        <td>
                            <?= $tz_head[ 'multi_tz' ] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Дорожная карта
                        </td>
                        <td>
                            <?= $tz_head[ 'street_map' ] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Статус ТЗ
                        </td>
                        <td>
                            <?
                            if ( isset( $tz_head[ 'status_state' ] ) )
                                echo $tz_head[ 'status_state' ] ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Дата создания ТЗ
                        </td>
                        <td>
                            <?= $tz_head[ 'dt_create' ] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Передано в работу
                        </td>
                        <td>
                            <?
                            if ( isset( $tz_head[ 'status_create_date' ] ) )
                                echo $tz_head[ 'status_create_date' ] ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            Крайний срок для выполнения ТЗ
                        </td>
                        <td>
                            <?= $tz_head[ 'dt_deadline' ] ?>
                        </td>
                    </tr>
                    </tbody>
                </table>


                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ок.Понятно</button>
                </div>

                <?
                Modal::end();
            }


            ?>

            <?php

            //ddd($tz_head);

            //Создано по ТехЗаданию
            echo "<h4>Накладная № ".$new_doc->id."</h4>";
            ?>

            <?php
            //   if( isset($new_doc['array_bus'])   ){

            ///////////
            if ( empty( $new_doc[ 'array_bus' ] ) )
                $sum_bus = 0;
            else
                $sum_bus = count( $new_doc[ 'array_bus' ] );

            //       dd($items_auto);
            ///////////
            ///

            // Using a select2 widget inside a modal dialog

            if ( isset( $items_auto ) && !empty( $items_auto ) ) {
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

                echo $form->field( $new_doc, 'array_bus' )->widget(
                    Select2::className()
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
                            'tokenSeparators' => [ ',', ' ' ],
                            'maximumInputLength' => 10
                        ],
                    ]
                );

                ?>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Прикрыть список</button>
                </div>

                <?
                echo Alert::widget(
                    [
                        'options' => [
                            'class' => 'alert-info'
                        ],
                        'body' => 'Кнопка "Прикрыть список" не удаляет и не отменяет изменения списка '
                    ]
                );


                Modal::end();
            }
            ?>


        </div>


        <!--    <div class="tree_land">-->


        <!--    </div>-->

        <div class="tree_land">
            <?php

            //////////
            echo $form->field( $new_doc, 'wh_debet_top' )->dropdownList(
                ArrayHelper::map(
                    Sprwhtop::find()
                        ->orderBy( 'name' )
                        ->all(),
                    'id', 'name'
                ),
                [
                    'prompt' => 'Выбор склада ...',
                    //'disabled' => 'disabled',
                ]
            );


            echo $form->field( $new_doc, 'wh_debet_element' )->dropdownList(
                ArrayHelper::map(
                    Sprwhelement::find()
                        ->where( [ 'parent_id' => (integer)$new_doc->wh_debet_top ] )
                        ->all(), 'id', 'name'
                ),
                [
                    'prompt' => 'Выбор склада ...',
                    // 'disabled' => 'disabled',
                ]
            );


            echo "<div class='close_hid'>";

            echo $form->field( $new_doc, 'tz_id' );

            echo $form->field( $new_doc, 'wh_debet_name' );
            //->hiddenInput()->label(false);
            echo $form->field( $new_doc, 'wh_debet_element_name' );
            //->hiddenInput()->label(false);
            echo "</div>";

            ?>
        </div>


        <div class="tree_land">
            <?php

            //////////
            echo $form->field( $new_doc, 'wh_destination' )->dropDownList(
                ArrayHelper::map(
                    Sprwhtop::find()
                        ->orderBy( 'name' )
                        ->all(),
                    'id', 'name'
                ),
                [
                    'prompt' => 'Выбор склада ...'
                ]
            );


            ///////////////////

            echo $form->field( $new_doc, 'wh_destination_element' )->widget(
                Select2::className()
                , [
                    'name' => 'st',
                    'data' => ArrayHelper::map(
                        Sprwhelement::find()
                            ->where( [ 'parent_id' => (integer)$new_doc->wh_destination ] )
                            ->all(), 'id', 'name'
                    ),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'size' => Select2::SMALL, //LARGE,
                ]
            );


            echo "<div class='close_hid'>";
            echo $form->field( $new_doc, 'wh_destination_name' )
                ->hiddenInput()->label( false );
            echo $form->field( $new_doc, 'wh_destination_element_name' )
                ->hiddenInput()->label( false );
            echo "</div>";

            ?>


        </div>

        <div class="tree_land">

            <?php
            /////////////////
            // echo '<p>Количество комплектов:</p>';
            echo $form->field( $model, 'multi_tz' )
                ->textInput(
                    [
                        'validationDelay' => 5,
                        //'readonly' => 'true',
                        'style' => "width: 100%;display: inline;" ]
                );


            //////////// echo '<p>DeadLine ДЕДЛАЙН:</p>';
            ///
            $date_now = date( "Y-m-d H:i:s", strtotime( 'now' ) );

            if ( empty( $model->dt_deadline ) )
                $model->dt_deadline = $date_now;

            echo $form->field( $model, 'dt_deadline' )
                ->widget(
                    DateTimePicker::className(), [

                    'name' => 'dp_1',
                    'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                    'size' => 'lg',
                    'options' => [
                        'placeholder' => 'Ввод даты/времени...' ],
                                                   //  'disabled' => 'disabled',


                                                   'value' => $date_now,
                    'pluginOptions' => [
                        'pickerPosition' => 'bottom-left',  //'top-left',
                        'format' => 'yyyy-mm-dd HH:ii:ss',
                        'autoclose' => true,
                        'weekStart' => 1, //неделя начинается с понедельника
                        'startDate' => $date_now,
                        'todayBtn' => true, //снизу кнопка "сегодня"
                    ]
                                               ]
                );

            ?>
        </div>


        <div class="tree_land">
            <?php
            echo $form->field( $new_doc, 'tx' )->textarea(
                [
                    'style' => "margin: 0px; height: 93px; font-size: 15px;"
                ]
            );
            ?>
        </div>

    </div>


    <div class="pv_motion_create_ok_button">


        <?php
        echo Html::a(
            'Выход',
            [ '/sklad/in?otbor='.$sklad ],
            [ 'class' => 'btn btn-warning' ]
        );
        ?>


        <?php
        echo " ";


        echo Html::submitButton(
            'Создать НАКЛАДНУЮ',
            [
                'class' => 'btn btn-success',
                'data-confirm' => Yii::t(
                    'yii',
                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'
                ),

                'name' => 'contact-button',
                'value' => 'create_new'
            ]
        );
        echo " ";

        //        echo Html::submitButton(
        //            'Демонтаж все Акты',
        //            [
        //                'class' => 'btn btn-info',
        //                'data-confirm' => Yii::t(
        //                    'yii',
        //                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'
        //                ),
        //
        //                'name' => 'contact-button',
        //                'value' => 'create_demontage'
        //            ]
        //        );
        //        echo " ";

        echo Html::submitButton(
            'Монтаж все Акты',
            [
                'class' => 'btn btn-info',
                'data-confirm' => Yii::t(
                    'yii',
                    'СОХРАНЯЕМ НАКЛАДНУЮ ?'
                ),

                'name' => 'contact-button',
                'value' => 'create_montage'
            ]
        );


        ?>


        <?php
        //        echo Html::a(
        //            'Демонтаж все Акты ', [ '/sklad/tz-to-many-new-acts-demontage?tz_id='.$model->id ], [ 'data-confirm' => '<b>Вы точно хотите: </b><br>
        //                        Создать МНОГО АКТОВ, по количеству автобусов в ТехЗадании ?',
        //
        //            //                    'data-method'=>"post",
        //
        //            'data-pjax' => 'normal_doc',
        //
        //            //                    'id' => "to_print",
        //
        //            'class' => 'btn  btn-info' //btn-primary'   //btn-success'
        //        ]
        //        );
        ?>

        <?php
        //            echo Html::a( 'Монтаж все Акты ', [ '/sklad/tz-to-many-new-acts-montage?tz_id='.$model->id ], [ 'data-confirm' => '<b>Вы точно хотите: </b><br>
        //                        Создать МНОГО АКТОВ, по количеству автобусов в ТехЗадании ?',
        //
        //                    //                    'data-method'=>"post",
        //
        //                    'data-pjax' => 'normal_doc',
        //
        //                    //                    'id' => "to_print",
        //
        //                    'class' => 'btn  btn-info' //btn-primary'   //btn-success'
        //                ] );
        ?>


        <?php
        if ( isset( $user[ 'username' ] ) && ( $user[ 'username' ] === 'VICTOR5' ) ) {
            ?>

            <?php
            echo Html::a(
                'Только для Штрихкодов', [ '/sklad/create_multi?tz_id='.$model->id.'&multi='.$model->multi_tz ], [ 'id' => "to_print", //'target' => "_blank",
                                           'class' => 'btn btn-default' ]
            );
            ?>

            <?php
            echo Html::a(
                'Только без Штрихкодов', [ '/sklad/create_multi_without_barcode?tz_id='.$model->id.'&multi='.$model->multi_tz ], [ 'id' => "to_print", //'target' => "_blank",
                                           'class' => 'btn btn-default' ]
            );
            ?>

            <?php
            //        if ( isset($user['username']) && ($user['username']==='VICTOR5'))
        }
        ?>


        <?php
        //dd($model->status_state);

        //          if( isset($model->status_state) && $model->status_state>0) {
        //              echo "<div>Передано в работу: "
        //                  .date('d.m.Y h:i:s', strtotime($model->status_create_date))
        //                  ."</div>";
        //          }
        //          else {
        //
        //              if (Yii::$app->user->identity->group_id == 50)
        //
        //                  echo Html::a('Передать в работу',
        //                      ['/tz_to_work/signal_to_work?id=' . $model->id],
        //                      [
        //                          'id' => "tz_to_work",
        //                          //'target' => "_blank",
        //                          'class' => 'btn btn-default'
        //                      ]);
        //          }
        ?>


    </div>

    <div class="pv_motion_create_right">

        <?php

        echo $form->field( $new_doc, 'array_tk_amort' )->widget(
            MultipleInput::className(), [
            'id' => 'my_id2',
                                          'theme' => 'default',
            'rowOptions' => [
                'id' => 'row{multiple_index_my_id2}',
            ],

            //'max'               => 25,
                                          'min' => 0, // should be at least 2 rows
                                          'allowEmptyList' => true,//false,
                                          'enableGuessTitle' => true,//false,
                                          'sortable' => true,
            'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

            'columns' => [
                [
                    'name' => 'wh_tk_amort',
                    'type' => 'dropDownList',
                    'title' => 'Груп па',
                    'defaultValue' => 0,
                    'items' => ArrayHelper::map(
                        post_spr_globam::find()->all(),
                        'id', 'name'
                    ),

                    'options' => [
                        'prompt' => 'Выбор ...',
                        'id' => 'subcat211-{multiple_index_my_id2}',
                        'style' => 'padding: 0px;min-width:10px;overflow: auto;',
                        'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
        // console.log(data);
    $("select#subcat22-{multiple_index_my_id2}").html(data);
    
});
JS

                    ],
                ],

                [
                    'name' => 'wh_tk_element',
                    'type' => 'dropDownList',
                    'title' => 'Ком понент',
                    'defaultValue' => [],

                    'options' => [
                        'id' => 'subcat22-{multiple_index_my_id2}',
                        'prompt' => 'Выбор ...',
                        'style' => 'min-width:40px;overflow: auto;',
                        'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat211-{multiple_index_my_id2}").val(data);
});                     
JS
                    ],

                    'items' =>
                        ArrayHelper::map(
                            post_spr_globam_element::find()->orderBy( 'parent_id, name' )->all(),
//                            postglobalamelement::find()->all(),
                            'id', 'name'
                        )
                    ,

                ],


                [
                    'name' => 'intelligent',
                    'title' => ' Ш.К',
                    'type' => 'dropDownList',
                    'defaultValue' => 0,
                    'enableError' => true,
                    'value' => (integer)$model->intelligent,
                    'options' => [
//                                'type'  => 'number',
//                                'class' => 'input-priority',
//                                'step'  => '1',
                        'style' => 'padding: 0px;width:auto;overflow: auto;',

                    ],
                    'items' => [
                        0 => 'Нет',
                        1 => 'Да',
                    ],
                ],


                [
                    'name' => 'ed_izmer',
                    'title' => 'Ед. изм',
                    'type' => 'dropDownList',
                    'defaultValue' => 1,
                    'options' => [
                        'style' => 'min-width: 66px;width:auto;padding: 0px;overflow: auto;',
                    ],

                    'items' => ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' ),

                ],

                [
                    'name' => 'ed_izmer_num',
                    'title' => 'К-во',
                    'defaultValue' => 0,
                    'enableError' => true,
                    'value' => (integer)$model->ed_izmer_num,
                    'options' => [

                        //'type' => 'number',
                        'type' => MaskedInput::className(), [ 'mask' => '9' ],
                        'class' => 'input-priority',
                        'style' => 'min-width: 3px;width:50px;padding: 0px;overflow: auto;text-align:center',
                    ]
                ],

                [
                    'name' => 'bar_code',
                    'title' => 'Код',
                    //'defaultValue' => 10101010101,
                    'enableError' => true,
                    //'value' =>(integer) $model->ed_izmer_num,
                    'headerOptions' => [ 'width' => '170' ],
                    'options' => [
                        'style' => 'min-width:3px;padding: 0px;overflow: auto;',

                        //'type' => 'number',
                        //'type' => \yii\widgets\MaskedInput::className(), ['mask' => '9'],
                        //'class' => 'input-priority',
//                                'style' => 'min-width:62px;max-width: 170px;overflow: auto;'
                    ]
                ],


            ]
                                      ]
        );

        //->label(false);

        ?>
    </div>

    <div class="pv_motion_create_right">

        <?php
        //dd($new_doc);
        //dd(empty($new_doc->array_tk));

        if ( isset( $new_doc->array_tk ) && !empty( $new_doc->array_tk ) )
            echo $form->field( $new_doc, 'array_tk' )->widget(
                MultipleInput::className(), [

                'id' => 'my_id',
                                              'theme' => 'default',
                // 'prepend'  => true, // Добавлять инсертом последнюю или первую строку

                'rowOptions' => [
                    'id' => 'row{multiple_index_my_id}',


                ],


//                'max'               => 25,
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
                                post_spr_glob::find()
                                    ->orderBy( 'name' )->all(),
                                'id',
                                'name'
                            ),

                        'options' => [
                            'prompt' => 'Выбор ...',
                            'id' => 'subcat11-{multiple_index_my_id}',
                            'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    //console.log(data);
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
                                post_spr_glob_element::find()
                                    ->orderBy( 'name' )->all(),
                                'id', 'name'
                            )
                        ,

                        'options' => [
                            'prompt' => '...',
                            'id' => 'subcat-{multiple_index_my_id}',

                            'onchange' => <<< JS
                        
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_id}").val(data);
});
                        
$.post("list_ed_izm?id=" + $(this).val(), function(data){
//alert(data);
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
                            'id' => 'ed_izm-{multiple_index_my_id}',
                            'style' => 'min-width: 66px;',
                            'onchange' => <<< JS
if ( $(this).val()>1 ){
    //console.log( $("input#zero-{multiple_index_my_id}").val() );
    $("input#zero-{multiple_index_my_id}").attr('step','0.1');
}else{
    $("input#zero-{multiple_index_my_id}").attr('step','1');
};
JS
                            ,
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
                            'id' => 'zero-{multiple_index_my_id}',
                            'type' => 'number',
                            'class' => 'input-priority',
                            'step' => '1',

                            'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                        ],
                    ],


                ]
                                          ]
            );
        //  ->label(false);
        ?>


    </div>

    <div class="pv_motion_create_right">

        <?php

        if ( isset( $new_doc->array_casual ) && !empty( $new_doc->array_casual ) )
            echo $form->field( $new_doc, 'array_casual' )->widget(
                MultipleInput::className(), [

                'id' => 'my_id',
                                              'theme' => 'default',
                // 'prepend'  => true, // Добавлять инсертом последнюю или первую строку

                'rowOptions' => [
                    'id' => 'row{multiple_index_my_id}',


                ],


//                'max'               => 25,
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
                                post_spr_glob::find()
                                    ->orderBy( 'name' )->all(),
                                'id',
                                'name'
                            ),

                        'options' => [
                            'prompt' => 'Выбор ...',
                            'id' => 'subcat11-{multiple_index_my_id}',
                            'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    //console.log(data);
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
                                post_spr_glob_element::find()
                                    ->orderBy( 'name' )->all(),
                                'id', 'name'
                            )
                        ,

                        'options' => [
                            'prompt' => '...',
                            'id' => 'subcat-{multiple_index_my_id}',

                            'onchange' => <<< JS
                        
$.post("list_parent_id?id=" + $(this).val(), function(data){
    $("select#subcat11-{multiple_index_my_id}").val(data);
});
                        
$.post("list_ed_izm?id=" + $(this).val(), function(data){
//alert(data);
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
                            'id' => 'ed_izm-{multiple_index_my_id}',
                            'style' => 'min-width: 66px;',
                            'onchange' => <<< JS
if ( $(this).val()>1 ){
    //console.log( $("input#zero-{multiple_index_my_id}").val() );
    $("input#zero-{multiple_index_my_id}").attr('step','0.1');
}else{
    $("input#zero-{multiple_index_my_id}").attr('step','1');
};
JS
                            ,
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
                            'id' => 'zero-{multiple_index_my_id}',
                            'type' => 'number',
                            'class' => 'input-priority',
                            'step' => '1',

                            'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                        ],
                    ],


                ]
                                          ]
            );
        //  ->label(false);
        ?>


    </div>

</div>


<?php
$script = <<<JS

//////////////////// VID - sklad-sklad_vid_oper
//$('#sklad-sklad_vid_oper').change(function() {    
//     var  number22 = $('#sklad-sklad_vid_oper').val();
//     var  text22   = $('#sklad-sklad_vid_oper>option[value='+number22+']').text();
//      $('#sklad-sklad_vid_oper_name').val(text22);
//});




//////////////////// Debitor -top
$('#sklad-wh_debet_top').change(function() {
        
    var  number = $(this).val();
    var  text = $('#sklad-wh_debet_top>option[value='+number+']').text();    
    $('#sklad-wh_debet_name').val(text) ;

     // var  number2 = $('#sklad-wh_debet_element').val();
     // var  text2   = $('#sklad-wh_debet_element>option[value='+number2+']').text();
     //  $('#sklad-wh_debet_element_name').val(text2);


    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		            $('#sklad-debet_element').html('');
		            $('#sklad-wh_debet_element').html(res);		    
					},
			error: function( res) {
						alert('нет данных ' );
						console.log(res);
					}
    } );
    
});

//////////////////// Debitor - element sklad-wh_debet_element
$('#sklad-wh_debet_element').change(function() {    
     var  number2 = $('#sklad-wh_debet_element').val();
     var  text2   = $('#sklad-wh_debet_element>option[value='+number2+']').text();
      $('#sklad-wh_debet_element_name').val(text2);
});


//////////////////// Creditor
$('#sklad-wh_destination').change(function() {
    
    var  number = $(this).val();
    var  text = $('#sklad-wh_destination>option[value='+number+']').text();
 	    $('#sklad-wh_destination_name').val(text);
 	    
            // console.log(number);   
            // console.log(text);   
   
    $.ajax( {
		url: '/sklad/list_element',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#sklad-wh_destination_element').html('');
		    $('#sklad-wh_destination_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
   
});

//////////////////// destination_element - element
/// sklad-wh_destination_element
 
$('#sklad-wh_destination_element').change(function() {    
     var  number2 = $('#sklad-wh_destination_element').val();     
     var  text2   = $('#sklad-wh_destination_element>option[value='+number2+']').text();
      $('#sklad-wh_destination_element_name').val(text2);
});



//
// //////////////////// Creditor
// $('#sklad-wh_dalee').change(function() {    
//     var  number = $(this).val();
//    
//   
//     $.ajax( {
// 		url: '/sklad/list_element',
// 		data: {
// 		    id :number
// 		},				
// 			success: function(res) {		 
// 		    $('#sklad-wh_dalee_element').html('');
// 		    $('#sklad-wh_dalee_element').html(res);
//		    
// 						//alert('OK. '+ res );
// 					},
// 			error: function( res) {
// 						alert('JS.sklad-wh_dalee '+ res );
// 					}
//     } );
//   
// });



// $( "form" ).submit(function( event ) {
 
// $( "form" ).submit(function(  ) {
//  
//    var  number1  = $('#sklad-wh_debet_top').val();
//    var  text1  = $('#sklad-wh_debet_top>option[value='+number1+']').text();    
//     $('#sklad-wh_debet_name').val(text1);
//     
//    var  number2  = $('#sklad-wh_debet_element').val();
//    var  text2  = $('#sklad-wh_debet_element>option[value='+number2+']').text();    
//     $('#sklad-wh_debet_element_name').val(text2);
//    //------------------
//    
//     var  number3  = $('#sklad-wh_destination').val();
//     var  text3  = $('#sklad-wh_destination>option[value='+number3+']').text();    
//      $('#sklad-wh_destination_name').val(text3);
//      
//
//    var  number4  = $('#sklad-wh_destination_element').val();
//    var  text4  = $('#sklad-wh_destination_element>option[value='+number4+']').text();    
//     $('#sklad-wh_destination_element_name').val(text4);
//    // ------------------
//     
//    // alert(text2);
//    
//  // event.preventDefault();
//});




$('#go_home').click(function() {    
    window.history.back();  
})


JS;

$this->registerJs( $script, yii\web\View::POS_READY );
?>



<?php ActiveForm::end(); ?>
<?php //Pjax::end(); ?>


