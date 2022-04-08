<?php

use \kartik\file\FileInput;
use \yii\web\View;
use \yii\widgets\Pjax;
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;

$this->title = 'GuideJet';
?>

<style>
    /*//PHOTO GALERY/*/
    .file-thumbnail-footer {
        /*background-color: #00ff43;*/
        display: none;
    }

    .file-preview {
        padding: 0px;
        width: 100%;
        margin-bottom: 0px;
    }

    input[type="file"] {
        display: block;
        font-size: medium;
        width: 100%;
    }

    .wrap > .container22 {
        margin-top: 0px;
    }

    .wrap {
        width: 500px;
        margin-left: 10%;
        margin-top: 5%;
    }

    @media (max-width: 500px) {
        .wrap {
            width: auto;
            margin-left: 0;
            margin-top: 5%;
        }

    }


    /*//FORM///*/
    .form-control {
        padding: 3px;
        font-family: 'fira', monospace;
        font-size: 25px;
    }


    /*/ FUTER NEXT >>//*/
    div._futer {
        margin-top: 30px;
        display: inline-flex;
        position: inherit;
        padding: 5px 0px;
        border: 0.5px solid #a3a3a3;
        border-radius: 10px;
        width: 100%;
    }

    div.past_futer {
        float: left;
        width: calc((98% - 180px));
    }

    div.next_futer {
        float: right;
        width: 140px;
    }

    /*//ALL BUTTONS/*/
    div > .width_all_butt {
        width: 100%;
    }

    div.width_all_butt a {
        margin: 7px;
        width: 94%;
    }


    .mobile-body-content {
        background-color: #96ff0000;
        width: 100%;
        min-height: min-content;
        padding: 0px;
        margin: 0px;
        display: block;
        position: relative;
    }

    a.next_step {
        max-width: 120px;
        display: block;
        position: relative;
        /*float: right;*/
    }

    a.back_step {
        max-width: 120px;
        display: block;
        position: relative;
    }

    ._sklad {
        margin-bottom: 10px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 5px;
        font-size: 22px;
        font-weight: bolder;
        text-align: center;

        display: block;
        /*position: absolute;*/
        float: left;
        width: 100%;
    }

    .ap_pe {
        margin-bottom: 30px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 5px;
        font-size: 22px;
        font-weight: bolder;
        text-align: center;

        display: block;
        /*position: absolute;*/
        float: left;
        width: 100%;
    }

    .ap_pe:empty {
        background-color: #ff1c43;
        display: none;
    }

    .ap_pe > p {
        text-align: center;
        font-size: 23px;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: bolder;
        margin-bottom: 0px;

    }

    .jumbotron > h1 {
        font-size: xx-large;
        padding: 5px;
        margin-top: 10px;
        color: #245580;
    }

    .jumbotron > h2 {
        font-size: x-large;
        margin-bottom: 10px;
    }


    @media (max-width: 700px) {
        .ap_pe {
            top: 110px;
            left: 4px;
        }


        p > a {
            height: 50px;
        }

        .btn {
            margin: 3px 10px;
            font-size: 22px;
        }


    }

    /*//BLOCK/*/
    .help-block {
        display: none;
    }

    .block_bort {
        /*background-color: #dad55e;*/
        background-color: #dad55e5c;
        width: 100%;
        border: 7px solid #a7a35e29;
        border-radius: 10px;
        margin-bottom: 15px;

        display: inline-block;
        position: relative;
        /*float: left;*/
    }

    .block_1, .block7_1 {
        float: left;
        /*width: calc(100% - 85px);*/
        width: calc(100% - 50px);
        /* background-color: red; */
        padding: 2px 7px;
    }

    .block_2, .block7_2 {
        float: left;
        display: block;
        position: absolute;
        top: 64px;
        right: 54px;
        width: 65px;
        height: 34px;
        background-color: aquamarine;
        padding: 0px 5px;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
    }

    .block_3, .block7_3 {
        display: none;
        float: left;
        width: 100%;
        background-color: #5eda701f;
        padding: 10px 12px;
        margin-top: 30px;
    }

    /*//PHOTO*/
    div.block_3_button {
        display: inline-grid;
        position: relative;
        float: right;
    }

    .block_photo, .block_photo1, .block_photo2,
    .block_photo7, .block_photo71, .block_photo72 {
        border: 3px solid #d6d6ae;
        display: block;
        position: inherit;
        padding: 6px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);

    }

    .block_photo:hover, .block_photo1:hover, .block_photo2:hover {
        border: 3px solid red;
    }

    .block_photo:after {
        border: 3px solid #00ff43;
    }

    .block_photo:focus {
        border: 3px solid #00ff43;
    }

    .block_3_button img {
        background-color: #da955ef0;
        color: red;
        border-radius: 20px;
        float: left;
        line-height: 0.2;
        padding: 3px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
    }

    .file-drop-zone {
        border: 0;
    }

    .file-drop-zone-title {
        display: none;
    }

</style>


<h5> Идентификация ПЕ </h5>


<div id="res"></div>

<div class="site-index">
    <div class="mobile-body-content">


        <div class="ap_pe">
            <p><?= $name_ap ?></p>
            <p> <?= $name_pe ?>    <?= (!empty($name_cs) ? '(cs)' : "") ?></p>
        </div>


        <?php Pjax::begin(['id' => 'pjax_1']); ?>
        <?php $form = ActiveForm::begin(
            [
                'action' => ['/mobile/index_ident_pe'],
                'method' => 'POST',
//                'enctype' => 'multipart/form-data'
                'options' => [
                    'data-pjax' => 'pjax_1'
                ],

            ]
        );
        ?>

        <br>
        <br>
        <!--        --><? //= Html::submitButton('Печать',
        //            [
        //                'class' => 'btn btn-primary',
        //                'name' => 'contact-button',
        //                'value' => 'print_mtp210',
        //            ])
        //
        //        ?>

        <?= Html::submitButton('Подтверждаю',
            [
                //'class' => 'btn btn-primary',
                //'class' => ' back_step btn btn-warning',
                'class' => 'width_all btn btn-success',
                'name' => 'contact-button',
                'value' => 'print_mtp210',
            ])
        ?>

        <br>
        <br>
        <br>


        <div class="block_bort">
            <div class="block_1">
                <?php
                echo $form->field($model, 'bort')
                    ->input('text', ['pattern' => "[0-9]{3,15}", 'maxlength' => '20', 'placeholder' => 'Бортовой номер...']);
                ?>
            </div>
            <div class="block_2">
                <?php
                echo $form->field($model, 'check_bort')->checkbox(['class' => 'check_ok']);
                ?>
            </div>

            <div class="block_3_button">
                <div class="block_photo1">
                    <img src="/fonts/camera.png" id="bort_cam1" height="30px"/>
                </div>

                <div class="block_photo2">
                    <img src="/fonts/in.png" id="bort_catalog1" height="30px"/>
                </div>
            </div>


            <div class="block_3" id="block3_bort_cam1">


            </div>

        </div>

        <div class="block_bort">

            <div class="block_1">
                <?php
                echo $form->field($model, 'gos')
                    ->input('text', ['maxlength' => '20', 'placeholder' => 'Гос номер...']);
                ?>
            </div>
            <div class="block_2">
                <?php
                echo $form->field($model, 'check_gos')->checkbox(['class' => 'check_ok']);
                ?>
            </div>

            <div class="block_3_button">
                <div class="block_photo1">
                    <img src="/fonts/camera.png" id="bort_cam2" height="30px"/>
                </div>

                <div class="block_photo2">
                    <img src="/fonts/in.png" id="bort_catalog2" height="30px"/>
                </div>
            </div>

            <div class="block_3" id="block3_bort_cam2">
                <?php
                echo $form->field($model, 'imageFiles[2]')->widget(FileInput::classname(), [
                    'options' => [
                        'multiple' => true,
                        'accept' => 'image/*',
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' => 'Выбрать ФОТО'
                    ],
                    'pluginOptions' => [
                        'previewFileType' => 'image',  // 'any'
                        'maxFileSize' => 10240,
                        'maxFileCount' => 10,

                        'showPreview' => true,  // false,
                        'showCaption' => false, // true,
                        'showRemove' => false,  // true,
                        'showUpload' => false   // false
                    ]

                ]);
                ?>

            </div>

        </div>


        <?php
        foreach ($array_menu as $key_1 => $item_1) {
            foreach ($item_1 as $key_2 => $item_2) {

                echo '<div class="block_bort">';
                echo '<div class="block7_1">';

                //ddd($item_2['bar_code']); // '19600003866'

                echo $form->field($model, "aray_check_box[$key_1][$key_2]")
                    ->input('text', ['value' => $item_2['bar_code'], 'maxlength' => '20'])
                    ->label($item_2['short_name']);   /// short_name

                //])->label($item_2['name']);       /// name

                echo '</div>';

                echo '<div class="block7_2" id="bort_catalog" >';
                echo $form->field($model, "aray_check[$key_1][$key_2]")->checkbox();
                echo '</div>';

                echo '<div class="block_3_button">
                        <div class="block_photo71" >
                            <img src="/fonts/camera.png" id="bort_cam' . $key_1 . '_' . $key_2 . '" height="30px"/>
                        </div>
                        <div class="block_photo72">
                            <img src="/fonts/in.png" id="bort_catalog' . $key_1 . '_' . $key_2 . '" height="30px"/>
                        </div>
                    </div>';


                echo '<div class="block_3" id="block3_bort_cam' . $key_1 . '_' . $key_2 . '">';

                //echo $form->field($model, "aray_photo[$key_1][$key_2]")->widget(FileInput::classname(), [
                echo $form->field($model, "imageFiles[$key_1][$key_2]")->widget(FileInput::classname(), [
                    'options' => [
                        'multiple' => true,
                        'accept' => 'image/*',
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' => 'Выбрать ФОТО'
                    ],
                    'pluginOptions' => [
                        'previewFileType' => 'image',  // 'any'
                        'maxFileSize' => 10240,
                        'maxFileCount' => 10,

                        'showPreview' => true,  // false,
                        'showCaption' => false, // true,
                        'showRemove' => false,  // true,
                        'showUpload' => false   // false
                    ]


//                    'pluginOptions' => [
//                        'uploadUrl' => Url::to(['/site/file-upload']),
//                        'uploadExtraData' => [
//                            'album_id' => 20,
//                            'cat_id' => 'Nature'
//                        ],
//                        'maxFileCount' => 10
//                    ]
                ]);

                echo '</div>';


                echo '</div>';

            }
        }
        ?>


        <div class="_futer">
            <div class="past_futer">
                <?php
                echo Html::a(
                    'Выход',
                    ['/mobile/index_tree'],
                    [
//                        'onclick' => 'window.opener.location.reload();window.close();',
                        'class' => ' back_step btn btn-warning',
                    ]);
                ?>
            </div>
            <div class="next_futer">
                <?= Html::submitButton('Подтверждаю',
                    [
                        //'class' => 'btn btn-primary',
                        //'class' => ' back_step btn btn-warning',
                        'class' => 'width_all btn btn-success',
                        'name' => 'contact-button',
                        'value' => 'print_mtp210',
                    ])
                ?>
            </div>


        </div>

        <br>
        <br>
        <br>
        <br>


        <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>


    </div>
</div>

<br>
<br>
<br>
<br>


<?php
$script = <<<JS
$(document).ready(function() {
    $('.alert_save').fadeOut(2500); // плавно скрываем окно временных сообщений
});


$('input,textarea').keypress(function(e){
    if(e.keyCode==13) {  //alert('Нажал - enter!');
        return false;
    }
});


//1 //2
$('div.block_photo1>img').on('click', function(e) {
   let id = e.target.id ; // OK id  
   
   // block3_bort_cam1
   let id_bl3= 'block3_'+id;            
   $('#'+id_bl3).toggle();   ////alert( $('#'+id_bl3).parent().html());
   
    
  // $('#'+id).toggle();   //
    
  //$('#bort_cam_block3').toggle();   //$('#bort_cam_block3').css('display','block');
    
  // alert(id);  
  // alert($(this).html()  ); 
   
  return false;
});

// 7_2
$('div.block_photo71>img').on('click', function(e) {
   let id = e.target.id ; // OK id
   //alert(id);
   
   
   // block3_bort_cam1
   let id_bl3= 'block3_'+id;            
   $('#'+id_bl3).toggle();   ////alert( $('#'+id_bl3).parent().html());
   
    
  // $('#'+id).toggle();   //
    
  //$('#bort_cam_block3').toggle();   //$('#bort_cam_block3').css('display','block');
    
  // alert(id);  
  // alert($(this).html()  ); 
   
  return false;
});



///
///
$('div.block_photo2').on('click', function(e) {           
    //let id = e.target.id ; // OK id;
    let number = e.target.id ; // OK id;
    //alert(id);
    
    //let number = $(this).val();    
    
    //let text = $('#sklad-wh_debet_top>option[value='+number+']').text();
    //$('#sklad-wh_debet_name').val(text) ;

    $.ajax( {
		url: '/mobile/ocr',
		data: {
		    id :number
		},
		//dataType: "json",		
			success: function(res) {
		    //console.log(res);		    
		            //$('#sklad-debet_element').html('');
		            $('div#res').html(res);		    
					},
			error: function( res) {
		    console.log(res);
						//alert('JS.sklad-wh_destination '+res );						
					}
    } );
    
 
   
  return false;
});

//
//////
//  jQuery(function($) {
//
//        var p = $("#previewimage");
//        $("body").on("change", "#image", function(){
//
//            var imageReader = new FileReader();
//            imageReader.readAsDataURL(document.getElementById("image").files[0]);
//
//            imageReader.onload = function (oFREvent) {
//                p.attr('src', oFREvent.target.result).fadeIn();
//            };
//        });
//
//        $('#previewimage').imgAreaSelect({
//            onSelectEnd: function (img, selection) {
//                $('input[name="x1"]').val(selection.x1);
//                $('input[name="y1"]').val(selection.y1);
//                $('input[name="w"]').val(selection.width);
//                $('input[name="h"]').val(selection.height);
//            }
//        });
//    });

JS;

$this->registerJs($script, View::POS_READY);
?>

