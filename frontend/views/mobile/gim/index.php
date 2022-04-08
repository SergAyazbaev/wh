<?php

use \yii\helpers\Html;

$this->title = 'GuideJet';
?>

<style>
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
        width: calc((98% - 140px));
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
</style>


<h5> Ужатие ФОТО</h5>


<body>
<section id="wrapper">
    <article id='article'>

        <div class='holder' id="holder">

            <div id='holder_helper'>
                <h2 id="holder_helper_title">Drag & Drop your Image here!</h2>
            </div>

            <img id="source_image" class='img_container' src=""/>

        </div>

        <div class='holder' id='holder_result'>
            <img id="result_image" class='img_container' src=""/>
        </div>

        <div class='clear'></div>

        <div class='col' id='left-col'>
            <fieldset>

                <legend>Compressor settings</legend>

                <div id="slider-range-min"></div>

                <div id='controls-wrapper'>
                    <!--                    Compression ratio : <input id="jpeg_encode_quality" size='3' readonly='true' type="text"-->
                    value="30"/> %
                    <div id='buttons-wrapper'>
                        <a class='btn btn-large btn-primary' id="jpeg_encode_button">Compress</a>&nbsp;
                        <a class='btn btn-large btn-success' id="jpeg_upload_button">Upload</a>
                    </div>
                </div>

            </fieldset>
        </div>

        <div class='col' id='right-col'>
            <fieldset>
                <legend>Console output</legend>
                <div id='console_out'></div>
            </fieldset>
        </div>
        <div class='clear'></div>
    </article>
    <footer>
        Created by <a id="built" href="http://twitter.com/brunobar79">@brunobar79</a>
    </footer>
</section>
</body>







<div class="site-index">
    <div class="mobile-body-content">


        <div class="_futer">

            <div class="past_futer">
                <?php
                echo Html::a(
                    '<< Выход',
                    ['/mobile/index'],
                    [
                        'onclick' => 'window.opener.location.reload();window.close();',
                        'class' => ' back_step btn btn-warning',
                    ]);
                ?>
            </div>

            <div class="next_futer">
                <?= Html::a('Далее >>', ['mobile/index'], ['class' => ' next_step btn btn-success']) ?>

            </div>

        </div>


    </div>
</div>


<br>
<br>
<br>
<br>

<?php

$script = <<<JS



$(function() {

    //Console logging (html)
    if (!window.console)
        console = {};

    
    // alert(1111);

    
    let console_out = document.getElementById('console_out');
    let output_format = "jpg";

    console.log = function(message) {
        console_out.innerHTML += message + '<br />';
        console_out.scrollTop = console_out.scrollHeight;
    };

    
    
    //Slider init
    // $("#slider-range-min").slider({
    //     range: "min",
    //     value: 13,
    //     min: 1,
    //     max: 100,
    //     slide: function(event, ui) {
    //         $("#jpeg_encode_quality").val(ui.value);
    //
    //         $("#jpeg_encode_quality").val(20);
    //
    //     }
    // });

     $("#jpeg_encode_quality").val(12);
    

 
    
       

   /** DRAG AND DROP STUFF WITH FILE API **/
   var holder = document.getElementById('holder');
   
   holder.ondragover = function() {
       this.className = 'is_hover';
       return false;
   };
   holder.ondragend = function() {
       this.className = '';
       return false;
   };
   holder.ondrop = function(e) {
       this.className = '';
       e.preventDefault();
       
       document.getElementById("holder_helper").removeChild(document.getElementById("holder_helper_title"));
        
        let file = e.dataTransfer.files[0], 
        reader = new FileReader();
        reader.onload = function(event) {
            let i = document.getElementById("source_image");
           	 	i.src = event.target.result;
           	 	i.onload = function(){
           	 		let image_width=$(i).width();
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
   
       
   
   //alert(1111);
       
       
       
   var encodeButton = document.getElementById('jpeg_encode_button');
   var encodeQuality = document.getElementById('jpeg_encode_quality');

   //HANDLE COMPRESS BUTTON
   encodeButton.addEventListener('click', function(e) {
       
       var source_image = document.getElementById('source_image');
       var result_image = document.getElementById('result_image');
       if (source_image.src == "") {
           alert("You must load an image first!");
           return false;
       }

       //var quality = parseInt(encodeQuality.value);
       var quality = 12;
       console.log("Quality >>" + quality);

       console.log("process start...");
//        var time_start = new Date().getTime();
//        
       result_image.src = jic.compress(source_image,quality,output_format).src;
       
       result_image.onload = function(){
       	var image_width=$(result_image).width(),
           image_height=$(result_image).height();
      	        
	        if(image_width > image_height){
	        	result_image.style.width="320px";
	        }else{
	        	result_image.style.height="300px";
	        }
	       result_image.style.display = "block";


       };

       //        var duration = new Date().getTime() - time_start;
//        
//        
//
//
       console.log("process finished...");
       console.log('Processed in: ' + duration + 'ms');
   
   
   }, false);
   
   
//    
//    
//    //HANDLE UPLOAD BUTTON
//    var uploadButton = document.getElementById("jpeg_upload_button");
//    uploadButton.addEventListener('click', function(e) {
//        var result_image = document.getElementById('result_image');
//        if (result_image.src == "") {
//            alert("You must load an image and compress it first!");
//            return false;
//        }
//        var callback= function(response){
//        	console.log("image uploaded successfully! :)");
//        	console.log(response);        	
//        }
//        
//        jic.upload(result_image, 'upload.php', 'file', 'new.'+output_format,callback);
//        
//       
//    }, false);

   alert(2222);
   
// /*** END OF DRAG & DROP STUFF WITH FILE API **/

});




JS;

$this->registerJs($script);

?>



