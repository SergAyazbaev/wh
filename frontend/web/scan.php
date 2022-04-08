<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take pictures from your webcam</title>

<style>
    .booth {
        width: 400px;
        background: #ccc;
        border: 10px solid #ddd;
        margin: 0 auto;
    }

    .booth-capture-button {
        display: block;
        margin: 10px 0;
        padding: 10px 20px;
        background: cornflowerblue;
        color: #fff;
        text-align: center;
        text-decoration: none;
    }

    #canvas {
        display: none;
    }
</style>

    <script src="js/jquery.js"></script>

</head>
<body>
<div class="booth">
    <video id="video" width="400" height="300" autoplay></video>
    <a href="#" id="capture" class="booth-capture-button">Сфотографировать</a>
    <canvas id="canvas" width="400" height="300"></canvas>
    <img src="http://goo.gl/qgUfzX" id="photo" alt="Ваша фотография">
</div>

<br>
<br>
<br>


<input type="file" accept="image/*;capture=camera">
<input type="file" accept="video/*;capture=camcorder">
<input type="file" accept="audio/*;capture=microphone">

<br>
<br>
<br>

</body>
</html>






<script>
    $('#capture').click( function(){
        //alert(1332);
            context.drawImage(video, 0, 0, 400, 300);
            photo.setAttribute('src', canvas.toDataURL('image/png'));
    });


    (function() {
        var video = document.getElementById('video'),
            canvas = document.getElementById('canvas'),
            context = canvas.getContext('2d'),
            photo = document.getElementById('photo'),
            vendorUrl = window.URL || window.webkitURL;


        navigator.getUserMedia = navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia;

        if (navigator.getUserMedia) {

            //navigator.getUserMedia({ audio: true, video: { width: 1280, height: 720 } },
            navigator.getUserMedia({ audio: false, video: true },

                function(stream) {
                    var video = document.querySelector('video');
                    video.srcObject = stream;
                    video.onloadedmetadata = function(e) {
                        video.play();
                    };
                },

                function(err) {
                    console.log("The following error occurred: " + err.name);
                }

            );

        } else {
            console.log("getUserMedia not supported");
        }


        document.getElementById('capture').addEventListener('click', function() {
            context.drawImage(video, 0, 0, 400, 300);
            photo.setAttribute('src', canvas.toDataURL('image/png'));
        });
    })();




</script>



<?php

$inipath = php_ini_loaded_file();
if ($inipath) {
    echo 'Loaded php.ini: ' . $inipath;
} else {
    echo 'A php.ini file is not loaded';
}



