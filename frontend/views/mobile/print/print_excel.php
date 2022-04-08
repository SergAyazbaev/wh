<?php
error_reporting(0);
date_default_timezone_set("Asia/Almaty");
ini_set('max_input_vars', '10000');
ini_set('upload_max_size', '30M');
ini_set('post_max_size', '30M');


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


//    ddd($model);
//   'id_ap' => 14
//        'id_pe' => 187
//        'bort' => '5011'
//        'gos' => '993DR02'
//        'vin' => ''
//        'dt_create' => '21.12.2020 15:56:37'
//        'dt_create_timestamp' => 1608544597
//        'mts_id' => 1


$activeSheet->setCellValue('A' . ($num + 5), $model->id_ap);
$activeSheet->setCellValue('A' . ($num + 5), $model->id_pe);


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