<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

class BootboxAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootbox';
    public $js = [
        '/js/bootbox.js',
    ];


    //    /// ИЗМЕНЕНЫЙ КОНФИРМ
    public static function overrideSystemConfirm()
    {
        Yii::$app->view->registerJs('
            yii.confirm = function(message, ok, cancel) {
                bootbox.confirm(message, function(result) {
                    if (result) { !ok || ok(); } else { !cancel || cancel(); }
                });
            }
        ');
    }
}


// ok СТАНДАРТНЫЙ
//    public static function overrideSystemConfirm()
//    {
//        Yii::$app->view->registerJs('
//            yii.confirm = function (message, ok, cancel) {
//                    if (window.confirm(message)) {
//                        !ok || ok();
//                    } else {
//                        !cancel || cancel();
//                    }
//
//            }
//        ');
//    }


///**
// * Displays a confirmation dialog.
// * The default implementation simply displays a js confirmation dialog.
// * You may override this by setting `yii.confirm`.
// * @param message the confirmation message.
// * @param ok a callback to be called when the user confirms the message
// * @param cancel a callback to be called when the user cancels the confirmation
// */
//confirm: function (message, ok, cancel) {
//    if (window.confirm(message)) {
//        !ok || ok();
//    } else {
//        !cancel || cancel();
//    }
//},
