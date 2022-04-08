<?php

use \yii\widgets\ActiveForm;
use \yii\widgets\Pjax;

//use \yii\helpers\Html;

$this->title = 'GuideJet';
?>

<style>
    .imgareaselect-selection {
        background-color: rgba(14, 144, 210, 0.41);
    }

</style>


<?php Pjax::begin(['id' => 'pjax_1']); ?>
<?php $form = ActiveForm::begin(
    [
        'action' => ['/up/crop'],
        'method' => 'POST',
        'options' => [
            'enctype' => 'multipart/form-data',
            'data-pjax' => 'pjax_1'
        ],

    ]
);
?>


<!--<form action="/up/index" method="post" enctype="multipart/form-data">-->
    Upload Image: <input type="file" name="image" id="image"/>
    <input type="hidden" name="x1" value=""/>
    <input type="hidden" name="y1" value=""/>
    <input type="hidden" name="w" value=""/>
    <input type="hidden" name="h" value=""/><br><br>
    <input type="submit" name="submit" value="Submit"/>
<!--</form>-->

<p><img id="previewimage" style="display:none;"></p>


<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>



<br>
<br>
<br>
<br>


<?php

$script = <<<JS

    jQuery(function($) {
 
        var p = $("#previewimage");
        $("body").on("change", "#image", function(){
 
            var imageReader = new FileReader();
            imageReader.readAsDataURL(document.getElementById("image").files[0]);
     
            imageReader.onload = function (oFREvent) {
                p.attr('src', oFREvent.target.result).fadeIn();
            };
        });
 
        $('#previewimage').imgAreaSelect({
            onSelectEnd: function (img, selection) {
                $('input[name="x1"]').val(selection.x1);
                $('input[name="y1"]').val(selection.y1);
                $('input[name="w"]').val(selection.width);
                $('input[name="h"]').val(selection.height);            
            }
        });
    });



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



