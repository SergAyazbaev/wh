<?php

use \yii\widgets\Pjax;
use \kartik\file\FileInput;
use \yii\helpers\Html;
use \yii\widgets\ActiveForm;

$this->title = 'GuideJet';
?>

<style>
    .ap_pe > p {
        text-align: center;
        font-size: 23px;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: bolder;
        margin-bottom: 0px;
    }

    .ap_pe {
        margin-bottom: 30px;
        background-color: #a5dcaa;
        /*background-color: #ffb70017;*/
        border: 5px solid #33333314;
        border-radius: 10px;
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        overflow: hidden;
        padding: 15px;
    }

    .crm_txt {
        background-color: #b4d20e38;
        padding: 20px;
        white-space: pre-wrap;
        font-size: 22px;
        border: 3px solid #3333333d;
        border-radius: 10px;
        margin: 40px 0px;
    }

    .mobile-body-content {
        background-color: #00ff432e;
        padding: 15px;
        width: 60%;
        max-width: 500px;
        min-height: 700px;
        /*max-height: 1000px;*/
        margin-left: 20%;
    }

    .jumbotron {
        text-align: center;
        padding: 10px 1px;
    }

    .width_all {
        width: 100%;
        font-size: 24px;
        /* font-weight: 900; */
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    /*//BLOCK/*/
    .help-block {
        display: none;
    }

    .block_bort, .block_bort_new {
        /*background-color: #5eda5f52;*/
        background-color: #5e64da3d;
        width: 100%;
        border: 7px solid #a7a35e29;
        border-radius: 10px;
        margin-bottom: 15px;

        display: inline-block;
        position: relative;
        /*float: left;*/
    }

    .block_bort_new {
        background-color: aquamarine;
    }

    .block_1 {
        /*float: left;*/
        /*width: calc(100% - 85px);*/
        width: calc(100% - 50px);
        /* background-color: red; */
        padding: 2px 7px;
    }


    @media (max-width: 500px) {
        .mobile-body-content {
            /*background-color: #96ff0012;*/
            background-color: #96ff0000;
            padding: 0px;
            width: 100%;
            max-width: none;
            min-height: min-content;
            /*max-height: none;*/
            margin-left: 0px;
        }

        .width_all {
            margin: 5px 2px;
            width: 99%;
            font-size: 22px;
            /* font-weight: 900; */
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            line-height: 2.1;
        }

        .wrap > .container22 {
            padding: 0px;
            width: 99%;
            overflow: auto;
            margin-top: 0px;
            margin-bottom: 4px;
        }

        .jumbotron {
            padding-top: 4px;
            padding-bottom: 0px;
            margin-bottom: 4px;
            color: inherit;
            background-color: #fff;
        }

        p > a {
            height: 50px;
        }

        label {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 700;
            font-size: x-large;
        }

        h1 {
            font-size: xx-large;
            padding: 5px;
            margin-top: 10px;
        }

        h2 {
            font-size: x-large;
            margin-bottom: 10px;
        }

        .sqware {
            margin-bottom: 30px;
            background-color: #ffb70017;
            padding: 3px 6px;
            border-radius: 10px;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.11);
        }

        .form-control {
            font-size: 23px;
            height: auto;
        }

    }
</style>

<?php Pjax::begin(['id' => 'pjax_1']); ?>
<?php $form = ActiveForm::begin(
    [
        'action' => ['/mobile/change_things'],
        'method' => 'POST',
        'options' => [
            'data-pjax' => 'pjax_1'
        ],
    ]
);

?>


<div class="site-index">

    <div class="mobile-body-content">
        <h1>Экстренные Замены</h1>


        <div class="ap_pe">
            <p><?= $name_ap ?></p>
            <p> <?= $name_pe ?>    <?= (!empty($name_cs) ? '(cs)' : "") ?></p>
        </div>

        <?php
        echo $form->field($model, 'id')
            ->textInput(['type' => 'hidden'])
            ->label(false);
        ?>

        <div class="block_bort">
            <div class="block_1">
                <?php
                echo $form->field($model, 'barcode_bad')->dropDownList($array_from_cs, ['prompt' => 'Выбрать...']);
                ?>
            </div>

            <div class="block_3" id="block3_bort_cam2">

                <!--                <div class="ap_pe" id="image1">УЖАТЬ</div>-->

                <!--                <input type="file" class="upload" id="image11">-->

                <!--                <div id="image1111">-->
                <?php
                echo $form->field($model, 'imageFiles[1]')->widget(FileInput::classname(), [

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
                        'maxFileSize' => 130000,
                        'maxFileCount' => 10,

                        'showPreview' => true,  // false,
                        'showCaption' => false, // true,
                        'showRemove' => false,  // true,
                        'showUpload' => false   // false
                    ]

                ]);
                ?>

                <!--            </div>-->
                <!--                <div id="image12">1231-->
            </div>

        </div>


        <br>

        <div class="block_bort_new">
            <div class="block_1">

                <?php
                echo $form->field($model, 'barcode_god')->dropDownList($array_list_obmen_fond, ['prompt' => 'Выбрать...']);
                ?>
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
                        'maxFileSize' => 130000,
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
        <br>

        <div class="form-group">
            <?= Html::submitButton('Замену подтверждаю', ['class' => 'width_all btn btn-success']) ?>
        </div>


        <div class="_futer">
            <div class="past_futer">

                <?php
                echo Html::a(
                    '<< Выход',
                    ['/mobile/index'],
                    [
//                        'onclick' => 'window.opener.location.reload();window.close();',
                        'class' => ' back_step btn btn-warning',
                    ]);
                ?>
            </div>
        </div>

        <br>
        <br>

        <div class="_futer">
            <div class="past_futer">
                <p>Просмотр остатков. Закрытие накладных замены</p>

                <?php
                echo Html::a(
                    'Просмотр / закрытие накладных. ',
                    ['/mobile_close_day/exchanges_view'],
                    [
//                        'onclick' => 'window.opener.location.reload();window.close();',
                        'class' => 'btn btn-danger',
                    ]);
                ?>
            </div>
        </div>


    </div>
</div>


<?php ActiveForm::end(); ?>
<?php Pjax::end() ?>


<?php

$script = <<<JS
function compress(source_img_obj, quality, maxWidth, output_format){
       let mime_type = "image/jpeg";
       if(typeof output_format !== "undefined" && output_format=="png"){
           mime_type = "image/png";
       }

       maxWidth = maxWidth || 1000;
       let natW = source_img_obj.naturalWidth;
       let natH = source_img_obj.naturalHeight;
       let ratio = natH / natW;
       if (natW > maxWidth) {
           natW = maxWidth;
           natH = ratio * maxWidth;
       }

       let cvs = document.createElement('canvas');
       cvs.width = natW;
       cvs.height = natH;

       let ctx = cvs.getContext("2d").drawImage(source_img_obj, 0, 0, natW, natH);
       let newImageData = cvs.toDataURL(mime_type, quality/100);
       let result_image_obj = new Image();
       result_image_obj.src = newImageData;
       return result_image_obj;
}


 /** DRAG AND DROP STUFF WITH FILE API **/
    var holder = document.getElementById('image11');
    holder.ondrop = function(e) {
        this.className = '';
        e.preventDefault();
        
         
        var file = e.dataTransfer.files[0], 
        reader = new FileReader();
        reader.onload = function(event) {
            var i = document.getElementById("source_image");
           	 	i.src = event.target.result;
           	 	i.onload = function(){
           	 		image_width=$(i).width();
	                image_height=$(i).height();
	
	                if(image_width > image_height){
	                	i.style.width="320px";
	                }else{
	                	i.style.height="300px";
	                }
	                i.style.display = "block";
	                console.log("Image loaded");

           	 	}
                
        };
        
        if(file.type=="image/png"){
            output_format = "png";
        }

        console.log("Filename:" + file.name);
        console.log("Filesize:" + (parseInt(file.size) / 1024) + " Kb");
        console.log("Type:" + file.type);
        

        reader.readAsDataURL(file);
        
        return false;
    };
    
$('div#image1').on('click', function(e) {
    
    alert(1111);       
        // var source_xx = document.getElementById('image11');
        //     console.log( $(source_xx).find('div.kv-file-content').html() ); ///img

        //var source_image = $(source_xx).find('div.kv-file-content src').html();
        var source_image = document.getElementById('image11');
        var result_image = document.getElementById('image12');
        
        // console.log(source_image);
        console.log(source_image);
        
         
        reader = new FileReader();        
        console.log(reader);
         
        
        reader.onload = function(event) {
            var i = document.getElementById("source_image");
           	 	i.src = event.target.result;
           	 	i.onload = function(){
           	 		image_width=$(i).width();
	                image_height=$(i).height();
	
	                if(image_width > image_height){
	                	i.style.width="320px";
	                }else{
	                	i.style.height="300px";
	                }
	                i.style.display = "block";
	                console.log("Image loaded");

           	 	}
                
        };
        
        
        if (source_image.src == "") {
            alert("You must load an image first!");
            return false;
        }

        
        
        
        // var quality = parseInt(encodeQuality.value);
        // console.log("Quality >>" + quality);
        
//        console.log("process start...");
//        var time_start = new Date().getTime();
//        
//        result_image.src = jic.compress(source_image,quality,output_format).src;
//        
//        result_image.onload = function(){
//        	var image_width=$(result_image).width(),
//            image_height=$(result_image).height();
//       	        
//	        if(image_width > image_height){
//	        	result_image.style.width="320px";
//	        }else{
//	        	result_image.style.height="300px";
//	        }
//	       result_image.style.display = "block";
//
//
//        }
//        var duration = new Date().getTime() - time_start;
//        
//        
//
//
//        console.log("process finished...");
//        console.log('Processed in: ' + duration + 'ms');
        
    
    
    
//Images Objects
//     var source_img = document.getElementById("source_img");
//     var target_img = document.getElementById("target_img");

//(NOTE: see the examples/js/demo.js file to understand how this object could be a local image 
//from your filesystem using the File API)

// var quality =  80,
// output_format = 'jpg', 
// target_img.src = jic.compress(source_img,quality,output_format).src;  
    
    
    
  // let id = e.target.id ; // OK id
  // let xx = $(this).parents().html() ; // OK id
  //
  // let yy = $(xx).html() ; // OK id

  // block3_bort_cam1
  // let id_bl3= 'block3_'+id;
  // $('#'+id_bl3).toggle();   ////alert( $('#'+id_bl3).parent().html());
  
  
  // console.log(yy);
  // console.log(xx);
  //   alert(xx);

 return false;
});

JS;

$this->registerJs($script);

?>



