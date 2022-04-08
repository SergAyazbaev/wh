<?php
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

Pjax::begin(['id' => 'pjax-container',]);
echo \yii::$app->request->get('page');
Pjax::end();
?>


<div id="tz1">
    <?php $form = ActiveForm::begin(); ?>

    <div class="pv-action-form" style=" width: 100%;display: inline-table;">


        <?= GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
//                ['class' => 'yii\grid\SerialColumn'],

//                [
//                    'attribute'=> 'parent_id',
//                    'filter'=>\yii\helpers\ArrayHelper::map(frontend\models\globalam::find()->all(),'id','name'),
//                    'value'=> 'parent_id',
//                    //'contentOptions' => ['style' => 'max-width: 50px;overflow: auto;'],
//                    //'filter'=> frontend\models\Sprwhtop::find()->select(['name'])->indexBy('name')->column(),
//                ],

//            [
//                'attribute'=>'parent_id',
//                'value'=> 'parent_id',
//                 'header'=>'',
//                ],

                'parent_id',
                'name_parent',
                'id',
                'name_child',
                'ed_izmer_name',
                'sum',
                'attansion',



//        [
//            'attribute'=> 'id_group',
//            //'contentOptions' => ['style' => 'max-width: 125px;overflow: auto;line-height: 4px;'],
//            'filter'=>
//                \yii\helpers\ArrayHelper::map(frontend\models\Spr_glob::find()->all(),
//                'id','name'),
//
//            'value'=> 'id_group',
//        ],

//        [
//            'attribute'=> 'dt_create',
//            'contentOptions' => ['style' => 'max-width: 125px;overflow: auto;line-height: 4px;'],
//            'filter'=>  DateTimePicker::widget([
//                'name' => 'datetime_10',
//                'options' => ['placeholder' => 'Select operating time ...'],
//                'convertFormat' => true,
//                'pluginOptions' => [
//                    'format' => 'd-M-Y g:i A',
//                    'startDate' => '01-Mar-2014 12:00 AM',
//                    'todayHighlight' => true
//                ]
//            ]),
//
//            'value'=> 'dt_create',
//        ],

//                ['class' => 'yii\grid\ActionColumn',
//                    'contentOptions' => ['style' => 'width:84px; font-size:18px;']],

            ],
        ]);

        ?>




        <?
//        if(empty($model->dt_create))
//            $model->dt_create    = Date('Y-m-d H:i:s', strtotime('now'));

        echo '<div class="tree_land_horizont_m">';
        ?>


        <?

//        if(isset($id))   // Tz - id
//            $model->id = $id ;
//
//        echo $form->field($model, 'id')
//            ->textInput(['class' => 'form-control',
//                'style' => 'width: 50px; margin-right: 5px;',
//                'readonly' => 'readonly']);
//        //->hiddenInput()->label(false);
//
//        echo '</div>';
        ?>


        <?
//        dd($model);
//        echo '<div class="tree_land_horizont">';
//
//        //echo $form->field($model, 'dt_create')
//        echo $form->field($model, 'dt_create')
//            ->textInput(['class' => 'form-control class-content-title_series',
//                'style' => 'width: 165px; margin-right: 5px;',
//                'readonly' => 'readonly']);
//
//
//        if (empty($model->user_create_id) ) {
//            $model->user_create_id = Yii::$app->user->identity->id;
//        };
//
//        echo $form->field($model, 'user_create_id')
//            ->textInput([
//                'class' => 'form-control class-content-title_series',
//                'style' => 'width: 65px; margin-right: 5px;',
//                'readonly' => 'readonly'])
//            ->label('Id1 ');
//
//        if (empty($model->user_create_name) ) {
//            $model->user_create_name = Yii::$app->user->identity->username;
//        };
//
//        echo $form->field($model, 'user_create_name')
//            ->textInput(['class' => 'form-control class-content-title_series',
//                'style' => 'width: 100px; margin-right: 5px;',
//                'readonly' => 'readonly'])
//            ->label('Создал');
//
//        echo '</div>';
        ?>




    </div>





  <div class="pv_motion_create_all">
            <div class="pv_motion_create_right_center">


            </div>

            <div class="pv_motion_create_right_center">
                <?
                echo '<div class="tree_land">';



                    ////
//                if ($model->street_map>0 )
//                    $new_doc->sklad_vid_oper=3; // Отгрузка со склада



//            echo $form->field($new_doc, 'sklad_vid_oper')->dropdownList(
//                    [
//                        '1'=>'Инвентаризация',
//                        '2'=>'Приходная накладная',
//                        '3'=>'Расходная накладная',
//                    ],
//                    [
//                        'prompt' => 'Выбор ...',
//                        'options' => [ 3 => ['selected'=>'selected']],
//                    ]
//                )->label("Вид операции");

                /////////
                echo "</div><br>";
                echo '<div class="tree_land">';
                //////////

                //////////
//                echo $form->field($new_doc, 'wh_debet_top')->dropdownList(
                //                    \yii\helpers\ArrayHelper::map(frontend\models\Sprwhtop::find()->all(),'id','name'),
//                    [
//                        'prompt' => 'Выбор склада ...'
//                    ]
//                )->label("Склад-источник");
//
//
//                echo $form->field($new_doc, 'wh_debet_element')->dropdownList(
                //                    \yii\helpers\ArrayHelper::map(frontend\models\Sprwhelement::find()
//                        ->where(['parent_id'=>(integer)$new_doc->wh_debet_top])
//                        ->all(),'id','name'),
//                    [
//                        'prompt' => 'Выбор склада ...'
//                    ]
//                );
//                    echo $form->field($new_doc, 'wh_debet_name')
//                        ->hiddenInput()->label(false);

                /////////
           echo "</div><br>";
           echo '<div class="tree_land">';
                //////////






             echo '</div>';
                ?>

            </div>


      <div class="pv_motion_create_ok_button">
<!--          --><?//= Html::Button('&#8593;',
//              [
//                  'class' => 'btn btn-warning',
//                  'onclick'=>"window.history.back();"
//              ]);
//          ?>
<!---->
<!--          --><?//
//                echo Html::submitButton('Создать накладную(ые)',
//                    ['class' => 'btn btn-success']
//                );
//          ?>




<!--          --><?//
//               if( Yii::$app->user->id == 50 )
//                   echo Html::a('Печать',
//                       ['/sklad/to_pdf/?id=' . $model->id],
//                       [
//                           'id' => "to_print",
//                           //'target' => "_blank",
//                           'class' => 'btn btn-default'
//                       ]);
//          ?>




      </div>
  </div>
</div>



        <div class="pv_motion_create_all_new">

            <div class="pv_motion_create_right">

                <?
//                dd($model);

//                    [id] => 10
//                    [tz_id] => 55
//                    [tz_name] => 1231231231
//                    [tz_date] => 2019-01-09 18:13:14
//                    [dt_deadline] => 2019-01-09 00:00:00

//                [sklad_vid_oper] => 3
//                    [wh_debet_top] => 6
//                    [wh_debet_element] => 181
//                    [wh_debet_name] => "ТОО "" АЛМАТЫЭЛЕКТРОТРАНС"""
//                [wh_destination] => 2
//                    [wh_destination_element] => 1919
//                    [wh_destination_name] => Guidejet TI
//                [dt_create] => 2019-01-11 09:44:19
//                    [user_id] => 13
//                    [user_name] => viktor
//                [sklad_vid_oper_name] => Расходная накладная
//                [wh_debet_element_name] => (2071) 699DA02
//                [wh_destination_element_name] => Склад №3



//                'searchModel'   => $searchModel,
//                'dataProvider'  => $dataProvider,

                ?>



<!--                --><?//= GridView::widget([
//                    'dataProvider' => $dataProvider,
//                    'filterModel' => $searchModel,

                        //  'options'=>['class'=>'mynewclass'], // новый класс

                        // 'layout'=>"{sorter} \n{items}\n{pager}\n{summary}\n",

                        //    'rowOptions'=>function ($model, $key, $index, $grid){
                        //			$class=$index%2?'odd':'even';  // стилизация четной и нечетной строки
                        //			return array('key'=>$key,'index'=>$index,'class'=>$class);
                        //		},

                        //'summary'=>'', // скрыть
                        //'showFooter'=>true, // показать
                        //'showHeader' => false, // показать=true

//                    'columns' => [
//                        [
//                            'header'=>'',
//                            'content' => function($model) {
//                                //dd($model);
//
//                                $url = Url::to(['/sklad/update?id='. $model->_id ]);
//                                return Html::a('Вн', $url, [
//                                    'class' => 'btn btn-success btn-xs',
//                                    'data-pjax' => 0,
//                                    'data-id' => $model->_id,
//                                ]);
//                            }
//                        ],


//                        'sklad_vid_oper_name',

//                        [
//                            'attribute'=> 'dt_create',
//                            //'value'=> 'dt_create',
//                            'contentOptions' => ['style' => ' width: 150px;'],
//                            'format' => ['datetime', 'php:d.m.Y (h:i:s)'],
//                        ],

//                        "wh_debet_name",
//                        "wh_debet_element_name",
//                        "wh_destination_name",
//                        "wh_destination_element_name",
//
//                        'user_name'

//                    ],
//                ]);

                ?>



            </div>


        </div>


<?php ActiveForm::end(); ?>


<?

$script = <<<JS
///////////////
// $('#tz-tk_top').change(function() {
//    	    
//    
//     var  number_tz_id = $('#tz-_id').val();           
//     var  number_tk = $(this).val();
//    
//     //var  text = $('#tz-tk_top>option[value='+number_tk+']').text();
//
//    
//
//     var  val_0_1 = $('#tz-name_tz').text();           //// ..ТехЗадание     
//     // var  val_1_1 = $('#tz-wh_deb_top').val();
//     // var  val_1_2 = $('#tz-wh_deb_element').val();
//    
//     if(!$('#tz-wh_cred_top').val()){
//         alert("Выберите Автопарк" );        
//         return false;
//     }
//    
//     var  val_2_1 = $('#tz-wh_cred_top').val();
//     var  val_2_2 = $('#tz-wh_cred_top>option[value='+val_2_1+']').text();
//    
//    
//     // var  val_2_2 = $('#tz-wh_cred_element').val();
//    
//     //id="tz-wh_cred_top"
//    	    
//              // console.log(number_tz_id);   
//              // console.log(number_tk);
//              // console.log(text);            
//
//     $.ajax( {
// 		url: '/tz/createnext',
// 		data: {
// 		    id_tz : number_tz_id,		    		    
// 		    id_tk : number_tk,		    
// 		        //text: text,		    
// 		    val12: {
//                 val_0_1: val_0_1, //name_tz
//                     // val_1_1: val_1_1,
//                     // val_1_2: val_1_2,
//                 val_2_1: val_2_1, // wh_cred_top  id
//                 val_2_2: val_2_2, // wh_cred_top_name
//                     // val_2_2: val_2_2,
// 		    }
// 		},		
// 			success: function(res) {		 
//		    
// 		    //alert('OK. '+ res );
//		    
// 		    $('#tz1').html('');
// 		    $('#tz1').html(res);
//		    
// 						//alert('OK. '+ res );
// 					},
// 			error: function( res) {
// 						alert('не пошло. '+ res );
// 							    $('#tz1').html('');
// 							    $('#tz1').html('не пошло. '+ res);
// 					}
//     } );
//    
//    
//    
// });




//////////////////// Debitor
$('#sklad-wh_debet_top').change(function() {
    var  number = $(this).val();
    var  text = $('#sklad-wh_debet_top>option[value='+number+']').text();
    
    $('#sklad-wh_debet_name').val(text) ;
    //alert(text);
    
    // wh_debet_name    
    //$('#sklad-wh_debet_top_name').val(text) ;
    	    
    	    
            // console.log(number);   
            // console.log(text);   
    
    $.ajax( {
		url: '/pvmotion/whselect',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {		 
		    $('#sklad-wh_debet_element').html('');
		    $('#sklad-wh_debet_element').html(res);
		    
						//alert('OK. '+ res );
					},
			error: function( res) {
						alert('не пошло. '+ res );
					}
    } );
    
});



//////////////////// Creditor
$('#sklad-wh_destination').change(function() {
    
    var  number = $(this).val();
    var  text = $('#sklad-wh_destination>option[value='+number+']').text();
 	    
 	    $('#sklad-wh_destination_name').val(text);
 	    
    	    //$('#tz-wh_cred_top_name').val(text);
   	    
   	    
            // console.log(number);   
            // console.log(text);   
   
    $.ajax( {
		url: '/pvmotion/whselect',
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



$('#go_home').click(function() {    
    window.history.back();  
})


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>

