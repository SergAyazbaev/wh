<?php

use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


Pjax::begin([ 'id' => 'pjax-container',]);
    echo yii::$app->request->get('page');

?>

<style>
    .glyphicon-plus{
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;

        /*padding: 7px;*/
        /*margin: -8px;*/
        font-size: medium;
        font-weight: bold;
        border-radius: 25px;
    }
    .glyphicon-plus:hover{
        color: rgba(255, 255, 255, 0.53);
        background-color: #5cb85c;
        border-color: #4cae4c;
        box-shadow: -1px 3px 7px 0px rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.1);

    }

    /*.help-block {*/

    /*}*/
    .help-block-error{
        display: none;
    }

    thead th{
        height: 22px;
        padding: 10px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    table tr,table td,{
        border: 0px;
        margin: 0px;
        width: 100%;
    }
    thead tr,thead td{
        background-color: rgba(11, 147, 213, 0.12);
        padding: 5px 10px;
        margin: 0px;
        padding: 10px;
    }
    tbody tr,tbody td{
        padding: 1px 3px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }
    td div select{
        /*background-color: rgba(65, 255, 61, 0.24);*/
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        width: 100%;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }
    td div input{
        background-color: rgba(35, 216, 57, 0.21);
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }

</style>


    <?php $form = ActiveForm::begin(); ?>


    <div class="pv_motion_create_right">
            <?
            //dd($model);

            echo $form->field($model, 'shablon_name')
                ->textInput();
            ?>
    </div>


<div id="tz1">



      <div class="pv_motion_create_ok_button">
          <?= Html::Button('&#8593;',
              [
                  'class' => 'btn btn-warning',
                  'onclick'=>"window.history.back();"
              ]);
          ?>

          <?
//            if( $model['user_create_id']==Yii::$app->getUser()->identity->id ){
                echo Html::submitButton('Сохранить',
                    ['class' => 'btn btn-success']);
//            }
          ?>

          <?php
          echo Html::a('Копия с новым номером',
              ['sklad_shablon/copyfromshablon?id=' . $model->id ],
              [
                  'id' => "to_print",
                  //'target' => "_blank",
                  'class' => 'btn  btn-info' //btn-primary'   //btn-success'
              ]);
          ?>




          <?
          //dd($model->status_state);

          if( isset($model->status_state) && $model->status_state>0) {
              echo "<div>Передано в работу: "
                  .date('d.m.Y h:i:s', strtotime($model->status_create_date))
                  ."</div>";
          }
          else {

              if (Yii::$app->user->identity->group_id == 50)

                  echo Html::a('Передать в работу',
                      ['/tz_to_work/signal_to_work?id=' . $model->id],
                      [
                          'id' => "tz_to_work",
                        //  //'target' => "_blank",
                          'class' => 'btn btn-default'
                      ]);
          }
          ?>


          <?
        //  dd($model->status_state);

          if( isset($model->status_state) && $model->status_state===10) {
              echo "<div>Отмена ТехЗадания: "
                  .date('d.m.Y h:i:s', strtotime($model->status_create_date))
                  ."</div>";
          }
          else {
              if( isset($model->status_state) && $model->status_state < 10) {
                  if (Yii::$app->user->identity->group_id == 50)

                      echo Html::a('Отмена ТехЗадания',
                          ['/tz_to_work/signal_to_return?id=' . $model->id],
                          [
                              'id' => "tz_to_work",
                             // //'target' => "_blank",
                              'class' => 'btn btn-default'
                          ]);

              }
          }
          ?>





</div>





            <div class="pv_motion_create_all_new">


                <div class="pv_motion_create_right">

                <?

//dd($model);


                echo $form->field($model, 'array_tk_amort')->widget(MultipleInput::className(), [
                    'id' => 'my_id2',
                    'theme'  => 'default',
                    'rowOptions' => [
                        'id' => 'row{multiple_index_my_id2}',
                    ],
//                    'max'               => 25,
                    'min'               => 0, // should be at least 2 rows
                    'allowEmptyList'    => true ,//false,
                    'enableGuessTitle'  => true ,//false,
                    'sortable'  => true,
                    'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                    'columns' => [
                        [
                            'name' => 'calc',
                            'title' => '№',
                            'value' => function($data, $key) {
                                return ++$key['index'];
                            },

                            'options' => [
                                'prompt' => 'Выбор ...',
                                'style' => 'width: 40px;',
                                'readonly' => 'readonly',
                                'disabled' => 'disabled',
                            ],
                        ],

                        [
                            'name'  => 'wh_tk_amort',
                            'type'  => 'dropDownList',
                            'title' => 'Группа',
                            'defaultValue' => 0,
                            'items' => ArrayHelper::map(
                                Spr_globam::find()->orderBy('name')->all(),
                                'id','name'),

                            'options' => [
                                'prompt' => 'Выбор ...',
                                'id' => 'subcat2114-{multiple_index_my_id2}',
                                'onchange' => <<< JS
$.post("listamort?id=" + $(this).val(), function(data){
    console.log(data);
    $("select#subcat224-{multiple_index_my_id2}").html(data);
});
JS
                            ],
                        ],

                        [
                            'name'  => 'wh_tk_element',
                            'type'  => 'dropDownList',
                            'title' => 'Компонент',
                            'defaultValue'=> [],

                            'options' => [
                                'id' => 'subcat224-{multiple_index_my_id2}',
                                'style' => 'min-width: 136px;',
                                'prompt' => '...',
                                'onchange' => <<< JS
$.post("list_parent_id_amort?id=" + $(this).val(), function(data){
    $("select#subcat2114-{multiple_index_my_id2}").val(data);
});                     


$.post("listamort_logic?id=" + $(this).val(), function(data){
   $("select#shablon-array_tk_amort-{multiple_index_my_id2}-intelligent").val(data);
             
});
JS
                                ,
                            ],
                            'items' =>
                                ArrayHelper::map(
                                    Spr_globam_element::find()->orderBy('name')->all(),
                                    'id','name')
                            ,

                        ],

                        [
//                            'id' => 'subcat22-{multiple_index_my_id2}',
                            'name'  => 'intelligent',
                            'title' => 'Штрих',
                            'type'  => 'dropDownList',
                            'defaultValue' => 0,
                            'enableError' => true,

                            'value' =>(integer) $model->intelligent,
                            'options' => [
                                'style' => 'width:60px;padding: 0px;'

//                                'type'  => 'number',
//                                'class' => 'input-priority',
//                                'step'  => '1',
//                                'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                            ],
                            'items' => [
                                0 => 'Нет',
                                1 => 'Да',
                            ],

                        ],

                        [
                            'name'  => 'ed_izmer',
                            'title' => 'Ед. изм',
                            'type'  => 'dropDownList',
                            'defaultValue' => 1,
                            'options' => [
                                'style' => 'width:70px;padding: 0px;'
                            ],

                            'items' => ArrayHelper::map( Spr_things::find()->all(),'id','name'),


                        ],

                        [
                            'name'  => 'ed_izmer_num',
                            'title' => 'Кол-во',
                            'defaultValue' => 1,
                            'enableError' => true,
                            'value' =>(integer) $model->ed_izmer_num,
                            'options' => [
                                'type'  => 'number',
                                'class' => 'input-priority',
                                'step'  => '1',
                                'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                            ]
                        ],

                        [
                            'name'  => 'tx',
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
                ]);
                //->label(false);

                ?>

            </div>
            <div class="pv_motion_create_right">

                <?

                echo $form->field($model, 'array_tk')->widget(MultipleInput::className(), [
                    'id' => 'my_id',
                    'theme'  => 'default',
                    'rowOptions' => [
                        'id' => 'row{multiple_index_my_id}',
                    ],
//                    'max'               => 50,
                    'min'               => 0, // should be at least 2 rows
                    'allowEmptyList'    => true ,//false,
                    'enableGuessTitle'  => true ,//false,
                    'sortable'  => true,
                    'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header

                    'columns' => [
                        [
                            'name' => 'calc',
                            'title' => '№',
                            'value' => function($data, $key) {
                                return ++$key['index'];
                            },

                            'options' => [
                                'prompt' => 'Выбор ...',
                                'style' => 'width: 40px;',
                                'readonly' => 'readonly',
                                'disabled' => 'disabled',
                            ],
                        ],

                        [
                            'name' => 'wh_tk',
                            'type'  => 'dropDownList',
                            'title' => 'Группа',
                            'defaultValue' => 0,
                            'items' => ArrayHelper::map(
                                Spr_glob::find()->orderBy('name')->all(),
                                'id', 'name'),

                            'options' => [
                                'prompt' => 'Выбор ...',
                                'id' => 'subcat211-{multiple_index_my_id}',
                                'onchange' => <<< JS
 $.post("list?id=" + $(this).val(), function(data){    
     $("select#subcat22-{multiple_index_my_id}").html(data);
 });
    
JS
                            ],
                        ],

                        [
                            'name'  => 'wh_tk_element',
                            'type'  => 'dropDownList',
                            'title' => 'Компонент',
                            'defaultValue'=> [],

                            'options' => [
                                'id' => 'subcat22-{multiple_index_my_id}',
                                'style' => 'min-width: 110px;',
                                'prompt' => '...',
                                'onchange' => <<< JS
$.post("list_parent_id?id=" + $(this).val(), function(data){
        $("select#subcat211-{multiple_index_my_id}").val(data);
  });
    
       
$.post("list_ed_izm?id=" + $(this).val(), function(data){
    $("select#shablon-array_tk-{multiple_index_my_id}-ed_izmer").val(data);    
});
JS

                                ,
                            ],
                            'items' =>ArrayHelper::map(
                                    Spr_glob_element::find()->orderBy('name')->all(),
                                    'id', 'name')
                            ,

                        ],



                        [
                            'name'  => 'ed_izmer',
                            'title' => 'Ед. изм',
                            'type'  => 'dropDownList',
                            'defaultValue' => 1,
                            'options' => [
                                'style' => 'width:80px;padding: 0px;'
                            ],

                            'items' => ArrayHelper::map( Spr_things::find()->all(),'id','name'),


                        ],

                        [
                            'name'  => 'ed_izmer_num',
                            'title' => 'Кол-во',
                            'defaultValue' => 1,
                            'enableError' => true,
                            'value' =>(integer) $model->ed_izmer_num,
                            'options' => [
                                'type'  => 'number',
                                'class' => 'input-priority',
                                'step' => '1',
                                'style' => 'min-width:62px;max-width: 70px;overflow: auto;',
                            ]
                        ],

                        [
                            'name'  => 'tx',
                            'title' => 'Комментарии',
                            'defaultValue' => '',
                            'enableError' => true,
                            'options' => [
                                'class' => 'input-priority',
                                'style' => 'max-width: 70px;overflow: auto;'

                            ]
                        ]


                    ]
                ]);
                //->label(false);


                ?>

            </div>


        </div>

    </div>



<?php ActiveForm::end(); ?>


<?Pjax::end();?>