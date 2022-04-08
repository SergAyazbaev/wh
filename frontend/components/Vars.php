<?php

namespace frontend\components;


use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;


/**
 * Class Vars
 * @package frontend\components
 */
class Vars
{
    //const DB_COLLECTION_NAME = "prod";
    const DB_COLLECTION_NAME = "wh_prod";


    /**
     * @param string $str_number
     * @return string
     * @throws BarcodeException
     */
    public static function Barcode_HTML($str_number = '10101')
    {
        ////////////// PDF - Barcode
        $html = '';
        $html .= '<div class="bar_code" style="position: absolute;top: 20px;right: 50px;">';

        $generator = new BarcodeGeneratorPNG();
        $html .= '<img src="data:image/png;base64,' . base64_encode(
                $generator->getBarcode($str_number, $generator::TYPE_CODE_128)) . '">';

        $html .= '<div>' . $str_number;
        $html .= '</div>';
        $html .= '</div>';
        //////////////
        return $html;
    }

    /**
     * @return mixed
     */
    public static function db_name()
    {
        return Yii::$app->params['vars'];
    }


}