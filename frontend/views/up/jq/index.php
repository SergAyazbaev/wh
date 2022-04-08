<?php

use \yii\helpers\Html;
use \yii\widgets\ActiveForm;
use \yii\widgets\Pjax;

use \bupy7\cropbox\CropboxWidget;


$this->title = 'GuideJet';
?>

<style>
    .imgareaselect-selection {
        background-color: rgba(14, 144, 210, 0.41);
    }

    .imageBox {
        position: relative;
        height: 400px;
        width: 400px;
        border: 1px solid #aaa;
        background: #fff;
        overflow: hidden;
        background-repeat: no-repeat;
        cursor: move;
    }

    .imageBox .thumbBox {
        /*position: absolute;*/
        /*top: 50%;*/
        /*left: 50%;*/
        /*width: 200px;*/
        /*height: 200px;*/

        position: absolute;
        top: 60%;
        left: 27%;
        width: 96%;
        height: 25%;

        margin-top: -100px;
        margin-left: -100px;
        box-sizing: border-box;
        border: 1px solid rgb(102, 102, 102);
        box-shadow: 0 0 0 1000px rgba(0, 0, 0, 0.5);
        background: none repeat scroll 0% 0% transparent;
    }

    .imageBox .spinner {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        text-align: center;
        line-height: 400px;
        background: rgba(0, 0, 0, 0.7);
    }
</style>


<br>
<br>
<br>
<br>


<link rel="stylesheet" href="style.css" type="text/css"/>
<style>
    .container {
        position: absolute;
        top: 10%;
        left: 10%;
        right: 0;
        bottom: 0;
    }

    .action {
        width: 400px;
        height: 30px;
        margin: 10px 0;
    }

    .cropped > img {
        margin-right: 10px;
    }


    .membrane-cropbox {
        background-color: rgba(0, 0, 0, 0.5);
        height: 25%;
        width: 100%;
        position: absolute;
        left: 0;
        top: 0;
        cursor: move;
    }
</style>

<div class="container">
    <!--    <div class="imageBox">-->
    <!--        <div class="thumbBox"></div>-->
    <!--        <div class="spinner" style="display: none">Loading...</div>-->
    <!--    </div>-->
    <!--    <div class="action">-->
    <!--        <input type="file" id="file" style="float:left; width: 250px">-->
    <!--        <input type="button" id="btnCrop" value="Crop" style="float: right">-->
    <!--        <input type="button" id="btnZoomIn" value="+" style="float: right">-->
    <!--        <input type="button" id="btnZoomOut" value="-" style="float: right">-->
    <!--    </div>-->
    <!---->
    <!--    <div class="cropped"></div>-->


    <div class="block_3" id="block3_bort_cam1">
        <?php Pjax::begin(['id' => 'pjax_1']); ?>
        <?php $form = ActiveForm::begin(
            [
                'action' => ['/up/crop'],
                'method' => 'POST',
//                'enctype' => 'multipart/form-data'
                'options' => [
                    'data-pjax' => 'pjax_1'
                ],

            ]
        );
        ?>

        <?php


        echo $form->field($model, 'image')->widget(CropboxWidget::className(), [
            'croppedDataAttribute' => 'crop_info',
        ]);

        ?>


        <div class="_futer">
            <div class="past_futer">
                <?php
                echo Html::a(
                    'Выход',
                    ['/mobile/index_tree'],
                    [
                        'onclick' => 'window.opener.location.reload();window.close();',
                        'class' => ' back_step btn btn-warning',
                    ]);
                ?>
            </div>
            <div class="next_futer">
                <!--                --><? //= Html::a('Далее >>', ['mobile/index'], ['class' => ' next_step btn btn-success']) ?>
                <?= Html::submitButton('Подтверждаю', ['class' => 'width_all btn btn-success']) ?>
            </div>
        </div>


        <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>


    </div>

    <br>
    <br>
    <br>
    <br>

</div>


<script type="text/javascript">
    window.onload = function () {
        var options =
            {
                imageBox: '.imageBox',
                thumbBox: '.thumbBox',
                spinner: '.spinner',
                imgSrc: 'avatar.png'
            };
        var cropper = new cropbox(options);
        document.querySelector('#file').addEventListener('change', function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                options.imgSrc = e.target.result;
                cropper = new cropbox(options);
            };
            reader.readAsDataURL(this.files[0]);
            this.files = [];
        });
        document.querySelector('#btnCrop').addEventListener('click', function () {
            var img = cropper.getDataURL();
            document.querySelector('.cropped').innerHTML += '<img src="' + img + '">';

            ///
            ///
            ///
            //$('.file-drop-zone-title').css("display: none");
            $('div.pe_identification-imagefiles-1').html('<img src="' + img + '">');


        });
        document.querySelector('#btnZoomIn').addEventListener('click', function () {
            cropper.zoomIn();
        });
        document.querySelector('#btnZoomOut').addEventListener('click', function () {
            cropper.zoomOut();
        })
    };
</script>


<?php

$script = <<<JS


$(window).load(function() {
    var options =
    {
        thumbBox: '.thumbBox',
        spinner: '.spinner',
        imgSrc: 'avatar.png'
    };
    
    var cropper = $('.imageBox').cropbox(options);
    $('#file').on('change', function(){
        var reader = new FileReader();
        reader.onload = function(e) {
            options.imgSrc = e.target.result;
            cropper = $('.imageBox').cropbox(options);
        };
        reader.readAsDataURL(this.files[0]);
        this.files = [];
    });
    
    $('#btnCrop').on('click', function(){
        
        var img = cropper.getDataURL();
        
        $('.cropped').append('<img src="'+img+'">');
        
    });
    
    $('#btnZoomIn').on('click', function(){
        cropper.zoomIn();
    });
    
    $('#btnZoomOut').on('click', function(){
        cropper.zoomOut();
    })
});

// use with require js (new feature added on 9 Oct 2014)
// require.config({
//     baseUrl: "../",
//     paths: {
//         jquery: 'jquery-1.11.1.min',
//         cropbox: 'cropbox'
//     }
// });

require( ["jquery", "cropbox"], function($) {
    var options =
    {
        thumbBox: '.thumbBox',
        spinner: '.spinner',
        imgSrc: 'avatar.png'
    };
    var cropper = $('.imageBox').cropbox(options);
    $('#file').on('change', function(){
        var reader = new FileReader();
        reader.onload = function(e) {
            options.imgSrc = e.target.result;
            cropper = $('.imageBox').cropbox(options);
        };
        reader.readAsDataURL(this.files[0]);
        this.files = [];
    });
    $('#btnCrop').on('click', function(){
        var img = cropper.getDataURL();
        $('.cropped').append('<img src="'+img+'">');
    });
    $('#btnZoomIn').on('click', function(){
        cropper.zoomIn();
    });
    $('#btnZoomOut').on('click', function(){
        cropper.zoomOut();
    })
    }
);



// $(function() {
//     //Console logging (html)
//     if (!window.console)
//         console = {};    
//     alert(1111);
// });






   alert(5555);


JS;

$this->registerJs($script);

?>



