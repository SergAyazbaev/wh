<?php
error_reporting(0);
date_default_timezone_set("Asia/Almaty");
ini_set('max_input_vars','10000');
ini_set('upload_max_size','30M');
ini_set('post_max_size','30M');


yii::setAlias('@path_save', __DIR__ . '/frontend/web/assets/reports');
yii::setAlias('@path2', __DIR__ . '/../../../../vendor/');
require_once Yii::getAlias('@path2') . '/yexcel/Classes/PHPExcel.php';

//ddd(__DIR__);


//yii::setAlias('@path_save', 'C:/OSPanel/domains/prod/frontend/web/assets/reports');
//yii::setAlias('@path2', 'C:/OSPanel/domains/wh/vendor/');
//require_once Yii::getAlias('@path2').'/yexcel/Classes/PHPExcel.php';


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


$activeSheet->setCellValue('A2', 'Журнал ремонтов');

//$activeSheet->setCellValue('G1', '1');
//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);

$activeSheet->getRowDimension('A2:L2')->setRowHeight(50);

$activeSheet->mergeCells('A1:L1');
$activeSheet->mergeCells('A2:L2');

$activeSheet->getStyle('A2')->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A2')->getFont()->setSize(18);
//$activeSheet->getStyle('A2')->getFont()->setBold(true);


$activeSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$activeSheet->getStyle('G1')->getFont()->setBold(true);

//    $activeSheet->setCellValue('A3', 'Отчет');

/// Объединить ячейки
$activeSheet->mergeCells('A3:L3');

$activeSheet->setCellValue('A4', '№');

$activeSheet->setCellValue('B4', 'Заявка. Дата. Автор');
$activeSheet->setCellValue('C4', '');
$activeSheet->setCellValue('D4', 'Ремонт. Дата. Мастер');
$activeSheet->setCellValue('E4', '');

$activeSheet->setCellValue('F4', 'Код');
$activeSheet->setCellValue('G4', 'Название');
$activeSheet->setCellValue('H4', 'Диагноз');
$activeSheet->setCellValue('I4', 'Решение');
$activeSheet->setCellValue('J4', 'Замененные компоненты');
$activeSheet->setCellValue('K4', 'MTC');
$activeSheet->setCellValue('L4', 'Рем.');

/// Объединить ячейки
$activeSheet->mergeCells('B4:C4');
$activeSheet->mergeCells('D4:E4');


$yy = [];
$num = 0;


//'id' => 1
//            'bar_code' => '043188'
//            'short_name' => 'Помощник водителя GJ-DA04'
//            'diagnoz' => 'eertw'
//            'decision' => 'sdfgsdg231'
//            'list_details' => 'sdfgsd'
//            'user_name' => 'rr'
//            'user_group' => 10
//            'user_id' => 22
//            'user_ip' => '127.0.0.1'
//            'dt_create_timestamp' => 1595332129

//'rem_user_name' => 'admin'
//            'rem_user_group' => 100
//            'rem_user_id' => 1
//            'rem_user_ip' => '127.0.0.1'
//            'dt_rem_timestamp' => 1595396517

foreach ($dataModels as $ii) {
    //ddd($ii->barcode_pool->turnover);

    //$activeSheet->getStyle('I5:I'.($num + 5))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

    $activeSheet->setCellValue('A' . ($num + 5), $ii['id']);

    $activeSheet->setCellValue('B' . ($num + 5), date('d.m.Y H:i:s', $ii['dt_create_timestamp']));
    $activeSheet->setCellValue('C' . ($num + 5), (isset($user[$ii['user_id']]) ? $user[$ii['user_id']] : ''));   // $ii['user_name']);

    $activeSheet->setCellValue('D' . ($num + 5), (is_int($ii['dt_rem_timestamp']) ? date('d.m.Y H:i:s', $ii['dt_rem_timestamp']) : ''));
    $activeSheet->setCellValue('E' . ($num + 5), (isset($user[$ii['rem_user_id']]) ? $user[$ii['rem_user_id']] : ''));


    $activeSheet->setCellValue('F' . ($num + 5), $ii['bar_code']);
    $activeSheet->setCellValue('G' . ($num + 5), $ii['short_name']);
    $activeSheet->setCellValue('H' . ($num + 5), $ii['diagnoz']);
    $activeSheet->setCellValue('I' . ($num + 5), $ii['decision']);
    $activeSheet->setCellValue('J' . ($num + 5), $ii['list_details']);

    $activeSheet->setCellValue('K' . ($num + 5), (isset($spr_list[$ii['mts_user_id']]) ? $spr_list[$ii['mts_user_id']] : ''));

    $activeSheet->setCellValue('L' . ($num + 5), (isset($ii->barcode_pool->turnover) ? $ii->barcode_pool->turnover : ''));


    $num++;
}


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


// Борта ячеек на всей таблице
$activeSheet->setSharedStyle($sharedStyle2, "A4:L" . ($num + 4));


$activeSheet->getStyle('A3')->getFont()->setBold(true);


$activeSheet->getStyle('A4:L4')->getFont()->setName('Malgun Gothic');  /// 'Candara');
$activeSheet->getStyle('A4:L4')->getFont()->setBold(true);
$activeSheet->getStyle('A4:L4')->getFont()->setSize(12);

///
$activeSheet->getStyle('A4:L4')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'rotation' => 0,
        'wrap' => true,
        'heigh' => 10
    )
);

$activeSheet->getRowDimension(4)->setRowHeight(30); /// ВЫСОТА !!!!

// Выравниваем по центру
$activeSheet->getStyle('A5:F' . ($num + 4))->getAlignment()->setHorizontal('center');




//getAlignment()->setHorizontal('right');
$activeSheet->getRowDimension('B5:B' . ($num + 4))->setRowHeight(20);

$activeSheet->getColumnDimension('A')->setWidth(8);

$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);

$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('E')->setAutoSize(true);

$activeSheet->getColumnDimension('F')->setAutoSize(true);
$activeSheet->getColumnDimension('G')->setAutoSize(true);
//    $activeSheet->getColumnDimension('G')->setWidth(28);

$activeSheet->getColumnDimension('H')->setWidth(28);
$activeSheet->getColumnDimension('I')->setWidth(28);
$activeSheet->getColumnDimension('J')->setWidth(28);
$activeSheet->getColumnDimension('K')->setWidth(20);


/// ШАПКА ТАБЛИЦЫ
$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);


$activeSheet->getStyle('E5:E' . ($num + 4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$activeSheet->getStyle('E5:E' . ($num + 4))->getAlignment()->setHorizontal('center');


$activeSheet->getStyle('A4:L4')->applyFromArray($styleThinBlackBorderOutline);
$activeSheet->getStyle('A5:L' . ($num + 4))->applyFromArray($styleThinBlackBorderOutline);

//Перенос текста по строкам внутри ячейки
$activeSheet->getStyle('G1:L' . ($num + 4))->getAlignment()->setWrapText(true);
// Выравниваем по центру
$activeSheet->getStyle('K5:L' . ($num + 4))->getAlignment()->setHorizontal('center');


$activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); /*ORIENTATION_PORTRAIT*/
$activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


/////////////////

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


//$filename = md5(microtime()).'.xlsx';
//$filename = md5(microtime()).'.xlsx';
$filename = 'отчет.xlsx';

$filename = 'svod_' . date('d.m.Y H-i-s') . '.xls';

$file = $_SERVER['DOCUMENT_ROOT'] . '/assets/reports/' . $filename;

//ddd($objWriter);



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