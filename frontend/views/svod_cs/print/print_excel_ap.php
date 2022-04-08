<?php
error_reporting(0);

yii::setAlias('@path_save', __DIR__ . '/frontend/web/assets/reports');
yii::setAlias('@path2', __DIR__ . '/../../../../vendor/');
require_once Yii::getAlias('@path2') . '/yexcel/Classes/PHPExcel.php';


$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Guidejet TI")
    ->setLastModifiedBy("Guidejet TI")
    ->setTitle("Краткий отчет")
    ->setSubject("Краткий отчет")
    ->setDescription("Краткий отчет")
    ->setKeywords("Краткий отчет")
    ->setCategory("Краткий отчет");


$objPHPExcel->setActiveSheetIndex(0);
$activeSheet = $objPHPExcel->getActiveSheet();


$activeSheet->setCellValue('A1', 'Сводная ведомость АП ');
$activeSheet->setCellValue('A2', $model->wh_debet_name);


//$activeSheet->setCellValue('G1', '1');

//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('A1:E2')->setRowHeight(50);

$activeSheet->mergeCells('A1:E1');
$activeSheet->mergeCells('A2:E2');
$activeSheet->getStyle('A1')->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('A2')->getAlignment()->setHorizontal('center');

$activeSheet->getStyle('A1')->getFont()->setName('Courier New'); ///setName('Courier New');
$activeSheet->getStyle('A1')->getFont()->setSize(16);

$activeSheet->getStyle('A2')->getFont()->setName('Arial'); ///setName('Courier New');
$activeSheet->getStyle('A2')->getFont()->setSize(18);
//$activeSheet->getStyle('A2')->getFont()->setBold(true);


$activeSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$activeSheet->getStyle('E1')->getFont()->setBold(true);

//    $activeSheet->setCellValue('A3', 'Отчет');

$activeSheet->mergeCells('A3:E3');

$activeSheet->setCellValue('A4', '№');
$activeSheet->setCellValue('B4', 'Группа');
$activeSheet->setCellValue('C4', 'Наименование товара');
$activeSheet->setCellValue('D4', 'Ед.Изм');
$activeSheet->setCellValue('E4', 'Количество');

$activeSheet->getRowDimension('A4')->setRowHeight(30);

//$activeSheet->mergeCells('C4:D4');
//$activeSheet->mergeCells('E4:F4');


//ddd($model);
//'wh_debet_name' => 'City Bus ТОО'
//        'array_tk_amort' => [
//    0 => [
//        'wh_tk_amort' => 7
//                'wh_tk_element' => 13
//                'wh_tk_element_name' => 'Fastener for CVB24 (Крепление для CVB24)'
//                'ed_izmer' => 1
//                'ed_izmer_num' => 219
//                'bar_code' => ''
//                'intelligent' => 0
//                'wh_tk_amort_name' => 'Оборудование АСУОП'
//                'ed_izmer_name' => 'шт'
//            ]


$yy = [];
$num = 0;

foreach ($model->array_tk_amort as $ii) {

    $activeSheet->setCellValue('A' . ($num + 5), $num + 1);
    $activeSheet->setCellValue('B' . ($num + 5), $ii['wh_tk_amort_name']);

    $activeSheet->setCellValue('C' . ($num + 5), $ii['wh_tk_element_name']);
    $activeSheet->setCellValue('D' . ($num + 5), $ii['ed_izmer_name']);
    $activeSheet->setCellValue('E' . ($num + 5), $ii['ed_izmer_num']);

    $num++;
}

$activeSheet->setCellValue("E" . ($num + 6), "=SUM(E5:E" . ($num + 5) . ")");

$activeSheet->getStyle("E" . ($num + 6))->getFont()->setName('Malgun Gothic');  /// 'Candara');
$activeSheet->getStyle("E" . ($num + 6))->getFont()->setBold(true);
$activeSheet->getStyle("E" . ($num + 6))->getFont()->setSize(11);

$activeSheet->setCellValue("D" . ($num + 6), 'Итого:');
$activeSheet->getStyle("D" . ($num + 6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


$sharedStyle2 = new PHPExcel_Style();
$sharedStyle2->applyFromArray(
    array('borders' => array(
        'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    )
    ));

$activeSheet->getStyle('B5:B' . ($num + 4))->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('B5:B' . ($num + 4))->setRowHeight(20);


//$activeSheet->getRowDimension(1,$num+4);
//$activeSheet->getStyle('B5:B'.($num+4))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);


//Сетка таблицы
$activeSheet->setSharedStyle($sharedStyle2, "A4:E" . ($num + 4));


$activeSheet->getStyle('A3')->getFont()->setBold(true);

$activeSheet->getStyle('A4:E4')->getFont()->setName('Malgun Gothic');  /// 'Candara');
$activeSheet->getStyle('A4:E4')->getFont()->setBold(true);
$activeSheet->getStyle('A4:E4')->getFont()->setSize(11);


//$activeSheet->getStyle('A'.($num+4).':J'.($num+4))->getFont()->setBold(true);


$activeSheet->getStyle('A4:E4')->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('A4:A' . ($num + 4))->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('D4:E' . ($num + 7))->getAlignment()->setHorizontal('center');


//getAlignment()->setHorizontal('right');
$activeSheet->getRowDimension('B5:B' . ($num + 4))->setRowHeight(20);

$activeSheet->getColumnDimension('A')->setWidth(6);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setWidth(12);
$activeSheet->getColumnDimension('E')->setWidth(18);


// $activeSheet->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$activeSheet->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

$activeSheet->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$activeSheet->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);

// Голова таблицы
$activeSheet->getStyle('E5:E' . ($num + 4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$activeSheet->getStyle('E5:E' . ($num + 4))->getAlignment()->setHorizontal('center');

// Тело таблицы
$activeSheet->getStyle('A4:E4')->applyFromArray($styleThinBlackBorderOutline);
$activeSheet->getStyle('A5:E' . ($num + 4))->applyFromArray($styleThinBlackBorderOutline);

//Перенос текста по строкам внутри ячейки
$activeSheet->getStyle('C5:E' . ($num + 4))->getAlignment()->setWrapText(true);


$activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); /*ORIENTATION_PORTRAIT*/
$activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


/////////////////

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


//$filename = md5(microtime()).'.xlsx';
//$filename = md5(microtime()).'.xlsx';
$filename = 'отчет.xlsx';

$filename = 'svod_' . date('d_m_Y H-i-s') . '.xls';

$file = $_SERVER['DOCUMENT_ROOT'] . '/assets/reports/' . $filename;


$objWriter->save($file);


//echo '<script>var xlsxfile = "'.$filename.'.xlsx"; </script>';


if (ob_get_level()) {
    ob_end_clean();
}

ob_get_contents();

//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/vnd.excel');
header('Content-Disposition: attachment; filename=' . basename($filename) . '');
header("Pragma: no-cache");
header("Expires: 0");

// Write file to the browser
$objWriter->save('php://output');

$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);

ob_flush();


?>

<h1> ОК.</h1>