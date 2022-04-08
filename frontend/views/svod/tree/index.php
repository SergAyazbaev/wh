<?php

use execut\widget\TreeView;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Свод сводов';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .tree_land_tab {
        padding: 20px 0px;
        overflow: unset;
    }

    .treeview span.icon {
        width: 27px;
        /*margin-right: 5px;*/
        /*height: 21px;*/
        /*line-height: 1.32;*/
    }

    .head_str {
        padding: 10px 30px;
        font-size: 24px;
    }

    .container22 {
        display: none;
    }

    .glyphicon {
        font-family: "Cabin", Arial, sans-serif;
        font-size: x-large;
        line-height: 1;
        width: 30px;
        margin: -7px auto;
        /*-webkit-font-smoothing: antialiased;*/
        /*-moz-osx-font-smoothing: grayscale;*/
    }

    .glyphicon:hover {
        background-color: #23ff90;
        width: 30px;
    }

    .close.glyphicon:hover {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        background-color: #ff7636;
        width: 25px;
    }

    .execut-tree-filter-input .close.glyphicon-search {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    #res_window {
        overflow: auto;
        padding: 10px;
        display: block;
        position: fixed;
        right: 20px;
        height: 87%;
        top: 5%;
        z-index: 9;
        background-color: #eee;
        min-width: calc((100% - 510px));
    }

    .tree_land, .tree_land_left, .tree_land_right {
        background-color: #fff8dc3d;
        float: left;
        max-height: 700px;
        padding: 0px 10px;
    }

    .tree_datetime {
        border: 1px solid #c3bfae;
        border-radius: 15px;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
        width: 30%;
        max-width: 450px;
        min-width: 450px;
        padding: 5px 20px;
        margin-bottom: 10px;
    }

    .tree_land_left, .tree_land_right {
        border: 1px solid #c3bfae;
        border-radius: 15px;
        box-shadow: -1px 3px 7px 0px rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.1);
        width: 30%;
        max-width: 450px;
        min-width: 450px;
    }

    .tree_land_right {
        min-width: 600px;
        width: initial;
        max-width: 95%;
        overflow: unset;
    }

    .treeview .list-group-item {
        cursor: pointer;
        line-height: 1;
    }

    .pv_motion_create_right_center {
        padding: 0px 25px;
        margin: 0px;
    }


    @media (max-width: 1100px) {
        .wrap {
            padding: 10px 0px;
            margin: 1px 0px;
            position: relative;
            height: max-content;
        }

        .tree_datetime {
            min-width: 95%;
        }

        .tree_land_tab {
            position: inherit;
            margin: 2px 0px;
            padding: 2px 0px;
            overflow: unset;
            height: max-content;
        }

        /*.tree_land, .tree_land_left, .tree_land_right {*/
        /*    overflow: hidden;*/
        /*    float: left;*/
        /*    height: auto;*/
        /*    padding: 10px 3px;*/
        /*    margin: 5px;*/
        /*}*/
        #res_window {
            position: inherit;
            width: 100%;
            min-width: 400px;
            padding: 20px 0px;
            margin: 20px 21px;
            overflow: auto;
        }

        .tree_land_left, .tree_land_right {
            min-width: 95%;
        }

        .tree_land_right {
            max-width: 100%;
        }


    }

</style>

<br>
<br>

<?php
Pjax::begin(['id' => 'pjax-container']);
?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'project-form',
        'method' => 'post',

        'options' => [
            'autocomplete' => 'off',
        ],

//            'enableAjaxValidation' => false, //!!!
        'enableClientValidation' => true, //!!!
//            'validateOnSubmit' => true,
    ]);
?>

<div class="tree_datetime">

    <?php
    echo $form->field($model, 'dt_create')
        ->widget(
            DateTimePicker::className(), [

                'name' => 'dp_1',
                'type' => DateTimePicker::TYPE_INPUT,   //TYPE_INLINE,
                'size' => 'lg',
                'language' => 'ru',
                'options' => ['placeholder' => 'Ввод даты/времени...'],
//                    'convertFormat' => true,
                'value' => $model->dt_create_timestamp,
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'pickerPosition' => 'bottom-left',
                    'format' => 'dd.mm.yyyy HH:ii:ss',
                    'autoclose' => true,
                    'weekStart' => 1, //неделя начинается с понедельника
                    //'startDate' => date('Y-m-d H:i:s', strtotime('first day of  -1 month')),
                    'todayBtn' => true, //снизу кнопка "сегодня"
                ]
            ]
        );

    ?>


    <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']) ?>

    <!--    <div id="rezult">Result:</div>-->

</div>


<?php $form = ActiveForm::end()
?>



<?php
Pjax::end();

$onSelect = new JsExpression(<<<JS

function _ (undefined, item) {
       var date_str = $('input#virtual-dt_create').val();
       var date_idstr = date_str.replace( /[\.;: ]/gi, '_');
       
       // console.log(date_str);
       // console.log(date_idstr);
       
       
        // AP
        if(item.parent_id==0){
            $.ajax({
                    type: "GET",
                    async: true,
                    contentType: "application/json; charset=utf-8", 
                    cache: true,                                            
                    global: true,
                    url: '/svod/one_ap_svod',
                    data: {
                        id : item.id,
                        date_str : date_str,
                        print: ''
                    },
                            
                        beforeSend: function (jqXHR, settings) {
                            $('div#res_window').append('<div class="next_window" id="next_'+item.id+'_'+date_idstr+'"><br><br><h1><b>Результат SVOD уже в пути...</b></h1><img id="loadImg" src="/css/wait.gif"></div>');
                            $('div#res_window').scrollTop($('div#res_window').height()*2000);
                        },
                        
                        success: function(res) {                        
                                // $('div#res_window').append('<div class="next_window" id="next_'+item.id+'">Результат уже в пути...</div>');
                                $('div#next_'+item.id+'_'+date_idstr).html(res);                                
                                $('#res_window').scrollTop($('#res_window').height()*2000);                                
                                return true;
                                },
                                
                        error: function (xhr, status, error) {                                              
                                $('div#next_'+item.id+'_'+date_idstr).html('<img class="loadImg" src="/css/nobody.gif"><br><h1> SVOD. Сервер не вернул ответ</h1>');     
                                 // $('img.loadImg').show().animate({height: "300px"}, 500).delay(3000).animate({height: "0px"}, 1000);
                                $('img.loadImg').hide(5000).removeClass('loadImg');
              
                                $('#res_window').scrollTop($('#res_window').height()*2000);                                
                                },
                                
                });            
        
            
        }  else {
            
            //alert(111);
             console.log(item);
             console.log(date_str);
            
            // PE
            $.ajax({
                    async: true, 
                    url: '/svod/one_pe_svod',
                    data: {
                        id_pe : item.id,
                        date_str : date_str,
                        print: 0
                    },		
                        success: function(res) {
                                //
                                $('div#res_window').append('<div class="next_window" id="next_'+item.id+'_'+date_idstr+'">SVOD.  Результат уже в пути...</div>');
                                $('div#next_'+item.id+'_'+date_idstr).html(res);
                                                                
                                $('#res_window').scrollTop($('#res_window').height()*2000);
                                return true;
                                },
                        error: function( res) {
                                    alert('SVOD. ERROR one_pe_svod=> '.res );
                                    console.log(res);
                                }
                });
            
        }
        
}
JS
);

//
// Костыль - обновляет страницу при снятии выделения
//
$nodeUnselected = new JsExpression(<<<JS
    function a (undefined, item) {    
    console.log(item);
   
       // if (item.href !== location.pathname) {
           // $.pjax({
           //     container: '#pjax-container',
           //     url: '/svod/one_ap_svod',
           //     timeout: null
           // });
       // }
              //console.log(item);
   }
JS
);


////
$groupsContent = TreeView::widget([
    'data' => $items,
    'header' => '<div class="tree_land_title" >Склады</div>',
    'searchOptions' => [
        'inputOptions' => [
            'placeholder' => 'Поиск объекта...']
    ],
    'clientOptions' => [
        'onNodeSelected' => $onSelect,
//        'onNodeUnselected' => $nodeUnselected,
        'levels' => 1
    ]
]);

echo '<div class="tree_land_left">' . $groupsContent;
echo '</div>';
?>


<br>
<br>


<div class="tree_land_right" id="res_window">
    <div class="tree_land_tab">

        <div class="tree_land_title">
            <div class="head_str">Результат выборки</div>
        </div>


    </div>
</div>


<br>
<br>
<br>


