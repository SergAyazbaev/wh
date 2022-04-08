<?php

namespace frontend\controllers;

//use frontend\models\Barcode_consignment;
use frontend\models\Barcode_pool;
use frontend\components\MyHelpers;
use frontend\models\Sklad;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use Mpdf\Mpdf;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;


class Mtp2printerController extends Controller
{

    /**
     * $session
     *
     */
    public function init()
    {
        parent::init();
        ///
        if (!Yii::$app->getUser()->identity) {
            throw new HttpException(411, 'Необходима авторизация', 5);
        }

    }

//
//    /**
//     * {@inheritdoc}
//     */
//    public function behaviors()
//    {
//        return [
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'in' => [
//                        'GET'],
//                    // Главная страница
//                    'update' => [
//                        'GET',
//                        'POST',
//                    ],
//                    // Редактирование НАКЛАДНОЙ
//                    'create_new' => [
//                        'GET',
//                        'POST',
//                    ],
//                    // КНОПКА создать новую наклданую
//                    'prihod2' => [
//                        'GET',
//                        'POST',
//                    ],
//
//                    //  'delete' => ['POST', 'DELETE'],
//                ],
//            ],
//        ];
//
//    }


    /**
     *1. Печать на ПРИНТЕРЕ ШТРИХКОДОВ. Работает
     *=
     *
     */
    public function actionIndex()
    {
        // Список-массив. Поиск автопоиск
        $pool = Barcode_pool::Array_for_auttofinder();

        ///
        $model = new Barcode_pool();
        $model->find_name = '';

        // ddd($pool);


        return $this->render('/xprinter/index', [
            "model" => $model,
            "pool" => $pool,
        ]);
    }




    /**
     * Печать на Bluetooth-ПРИНТЕРЕ ШТРИХКОДОВ. Работает
     *=
     *
     */
    public function actionPdf_create_for_mtp2()
    {

        //
        $model = Sklad::find()->where(['id' => 33413])->one(); //Пример накладной
        //
        $spr_globam = ArrayHelper::map(Spr_globam::find()->all(), 'id', 'name');
        $spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(), 'id', 'name');

        //2
        $html = $this->getView()->render(
            '/mtp2printer/pdf_montage/_form', [
                'model' => $model,

                'spr_globam' => $spr_globam,
                'spr_globam_element' => $spr_globam_element,
            ]
        );


        //
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => [60, 200],
            'default_font_size' => 7,
            'autoPageBreak' => false,  // Разрывы страниц есть/нет
        ]);

        //
        $mpdf->SetAuthor('Guidejet TI, 2020');

        //CSS
        $stylesheet = $this->getView()->render('/mtp2printer/pdf_montage/_form_css.php');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        //
        $mpdf->AddPageByArray(array(
            'orientation' => 'P',
            'mgl' => '1',
            'mgr' => '1',
            'mgt' => '2',
            'mgb' => '0',
        ));

        //
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY, true, true);
        //
        $filename = 'MTPrinter_' . date('d_m_Y__H-i-s') . '.pdf';
        //
        $mpdf->Output($filename, 'I');

        return false;
    }

    /**
     *1. Печать на ПРИНТЕРЕ ШТРИХКОДОВ. Работает
     *=
     *
     */
    public function actionIndex_for_array()
    {
        ///
        $model = new Barcode_pool();
        $model->find_name = '';

        //
        return $this->render('/xprinter/index_for_array.php', [
            "model" => $model,
        ]);
    }

    /**
     *2. Печать на ПРИНТЕРЕ ШТРИХКОДОВ.
     *=
     *
     */
    public function actionPrint_from_array()
    {

        //
        if (Yii::$app->request->post('Barcode_pool')) {
            //
            $post = Yii::$app->request->post('Barcode_pool');
            //
            $array_bar_code = explode("\r\n", $post['bar_code']);
            //
            $array_bar_code = array_filter($array_bar_code);


            // Печатаем сразу весь МАССИВ ШТРИХКОДОВ
            if ($this->Pdf_create_by_barcode_array($array_bar_code)) {
                return 'OK';
            }

            ddd($array_bar_code);


        }


        return $this->render('/xprinter/index_for_array.php', [
            "model" => $model,
        ]);
    }

    /**
     * Печать на ПРИНТЕРЕ ШТРИХКОДОВ. Работает
     *=
     * @param $bar_code
     * @return bool
     * @throws \Mpdf\MpdfException
     * @throws \Picqer\Barcode\Exceptions\BarcodeException
     */
    function Pdf_create_by_barcode($bar_code)
    {
        ////////////////////
        ///// BAR-CODE
        $str_pos = str_pad($bar_code, 5, "0", STR_PAD_BOTH); /// длинная строка с номером генерируется в длинную
        $bar_code_html = MyHelpers::Xprinter_Barcode($str_pos);
        ///// BAR-CODE

        //
        $stylesheet = $this->getView()->render('/xprinter/html_to_pdf/_form_css.php');

        ///
        ///  mPDF()        /////        $mpdf->writeBarcode('978-1234-567-890');

        //
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => [60, 40],
            'default_font_size' => 11,
            'autoPageBreak' => true,  // Разрывы страниц есть/нет
        ]);

        //
        $mpdf->SetAuthor('Guidejet TI, 2020');
        //
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        //
        $mpdf->AddPageByArray(array(
            'orientation' => 'P',
            'mgl' => '3',//
            'mgr' => '2',//
            'mgt' => '6', //
            'mgb' => '4',//
        ));
        //
        $foot_str = $bar_code_html . '<br>' . $bar_code_html;
        //
        $mpdf->WriteHTML($foot_str, \Mpdf\HTMLParserMode::HTML_BODY, true, true);
        //
        $filename = 'Xprinter_' . date('d_m_Y__H-i-s') . '.pdf';
        //
        $mpdf->Output($filename, 'I');

        return false;
    }

    /**
     * Печать на ПРИНТЕРЕ ШТРИХКОДОВ. Список ШТРИХКОДОВ
     *=
     * @param $bar_code_array
     * @return bool
     * @throws \Mpdf\MpdfException
     * @throws \Picqer\Barcode\Exceptions\BarcodeException
     */
    function Pdf_create_by_barcode_array($bar_code_array)
    {
        //
        $stylesheet = $this->getView()->render('/xprinter/html_to_pdf/_form_css.php');

        ///
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => [60, 40],
            'default_font_size' => 11,
            'autoPageBreak' => true,  // Разрывы страниц есть/нет
        ]);

        //
        $mpdf->SetAuthor('Guidejet TI, 2020');
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

        $xx = 0;
        $xx_count = count($bar_code_array);
        $foot_str = '';
        foreach ($bar_code_array as $bar_code) {
            $xx++;

            ////////////////////
            ///// BAR-CODE
            $str_pos = str_pad($bar_code, 5, "0", STR_PAD_BOTH); /// длинная строка с номером генерируется в длинную
            $bar_code_html = MyHelpers::Xprinter_Barcode($str_pos);
            ///// BAR-CODE

            if ($xx % 2 == 0) {
                //ddd(count($bar_code_array));
                //
                $mpdf->AddPageByArray(array('orientation' => 'P', 'mgl' => '3', 'mgr' => '2', 'mgt' => '6', 'mgb' => '4'));
                //$foot_str = $bar_code_html;
                $foot_str = $foot_str . '<br>' . $bar_code_html;
                $mpdf->WriteHTML($foot_str, \Mpdf\HTMLParserMode::HTML_BODY, true, true);
            } else {
                // even
                $foot_str = $bar_code_html;
                /// EXIT 7
                if ($xx_count == $xx) {
                    //
                    $mpdf->AddPageByArray(array('orientation' => 'P', 'mgl' => '3', 'mgr' => '2', 'mgt' => '6', 'mgb' => '4'));
                    $mpdf->WriteHTML($foot_str, \Mpdf\HTMLParserMode::HTML_BODY, true, true);
                }
            }

        }

        //
        $filename = 'Xprinter_' . date('d_m_Y__H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');

        return false;
    }


}