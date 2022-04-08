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


$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Guidejet TI")
    ->setLastModifiedBy("Guidejet TI")
    ->setTitle("Краткий отчет")
    ->setSubject("Краткий отчет")
    ->setDescription("Краткий отчет")
    ->setKeywords("Краткий отчет")
    ->setCategory("Краткий отчет");


$objPHPExcel->setActiveSheetIndex(0);
$activeSheet = $objPHPExcel -> getActiveSheet();


$activeSheet->setCellValue('A2', 'Справочник. Склады');



//$activeSheet->setCellValue('G1', '1');

//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('A2:K2')->setRowHeight(50);

$activeSheet->mergeCells('A1:E1');
$activeSheet->mergeCells('A2:E2');

$activeSheet->getStyle('A2')->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A2')->getFont()->setSize(12);
//$activeSheet->getStyle('A2')->getFont()->setBold(true);



$activeSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$activeSheet->getStyle('E1')->getFont()->setBold(true);

//    $activeSheet->setCellValue('A3', 'Отчет');

$activeSheet->mergeCells('A3:E3');

//        'id' => '2'
//        'parent_id' => '5'
//        'name' => ''
//        'ed_izm' => ''
//        'tx' => ''

$activeSheet->setCellValue('A4', '№');
$activeSheet->setCellValue('B4', 'Наименование');
$activeSheet->setCellValue('C4', 'Наименование');
$activeSheet->setCellValue('D4', 'Группа');
$activeSheet->setCellValue('E4', 'ЦС');
$activeSheet->setCellValue('F4', 'БОРТ');
$activeSheet->setCellValue('G4', 'ГОС или Трактор');
$activeSheet->setCellValue('H4', 'vin');
$activeSheet->setCellValue('I4', 'Delete');
$activeSheet->setCellValue('J4', 'Комментарии');


//$activeSheet->mergeCells('C4:D4');
//$activeSheet->mergeCells('E4:F4');
//$activeSheet->mergeCells('G4:H4');


$yy = [];
$num = 0;

foreach ($dataModels as $ii ) {

    // ddd($ii);
    //ddd($ii['sprwhtop']['name'] );

    $activeSheet->setCellValue('A' . ($num + 5), $ii['id']);
    //$activeSheet->setCellValue('B' . ($num + 5), $ii['parent_id']);

    $activeSheet->setCellValue('B' . ($num + 5), $spr_wh_top[$ii['parent_id']]);

    $activeSheet->setCellValue('C' . ($num + 5), $ii['name']);
    $activeSheet->setCellValue('D' . ($num + 5), $ii['parent_id']);

    $activeSheet->setCellValue('E' . ($num + 5), ($ii['final_destination']));


    $activeSheet->setCellValue('F' . ($num + 5), ($ii['nomer_borta']));
    $activeSheet->setCellValue('G' . ($num + 5), ($ii['nomer_gos_registr']));
    if (isset($ii['nomer_traktor']) && !empty($ii['nomer_traktor'])) {
        $activeSheet->setCellValue('G' . ($num + 5), ($ii['nomer_traktor']));
    }
    $activeSheet->setCellValue('H' . ($num + 5), ($ii['nomer_vin']));
    $activeSheet->setCellValue('I' . ($num + 5), ($ii['delete_sign']));
    $activeSheet->setCellValue('J' . ($num + 5), ($ii['tx']));


    $num++;
}


$sharedStyle2 = new PHPExcel_Style();
$sharedStyle2->applyFromArray(
    array('borders' => array(
        'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
    )
    ));

$activeSheet->getStyle('B5:B'.($num+4))->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('B5:B'.($num+4))->setRowHeight(20);


//$activeSheet->getRowDimension(1,$num+4);
//$activeSheet->getStyle('B5:B'.($num+4))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);


///// СЕТКА
$activeSheet->setSharedStyle($sharedStyle2, "A4:I" . ($num + 4));


$activeSheet->getStyle('A3')->getFont()->setBold(true);

$activeSheet->getStyle('A4:I4')->getFont()->setName('Malgun Gothic');  /// 'Candara');
$activeSheet->getStyle('A4:I4')->getFont()->setBold(true);
$activeSheet->getStyle('A4:I4')->getFont()->setSize(11);


//$activeSheet->getStyle('A'.($num+4).':J'.($num+4))->getFont()->setBold(true);


$activeSheet->getStyle('A4:E4')->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('A4:A' . ($num + 4))->getAlignment()->setHorizontal('center');



//getAlignment()->setHorizontal('right');
$activeSheet->getRowDimension('B5:B'.($num+4))->setRowHeight(20);





//$activeSheet->getColumnDimension('D')->setWidth(12);
//$activeSheet->getColumnDimension('D')->setWidth(12);




// $activeSheet->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$activeSheet->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

$activeSheet->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
$activeSheet->getStyle('I1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);

/// Общая рамка таблицы (Шапка)
$activeSheet->getStyle('A4:I4')->applyFromArray($styleThinBlackBorderOutline);
/// Общая рамка таблицы (Тело)
$activeSheet->getStyle('A5:I' . ($num + 4))->applyFromArray($styleThinBlackBorderOutline);



$activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); /*ORIENTATION_PORTRAIT*/
$activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


$activeSheet->getColumnDimension('A')->setAutoSize(true);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('E')->setAutoSize(true);
$activeSheet->getColumnDimension('F')->setAutoSize(true);
$activeSheet->getColumnDimension('G')->setAutoSize(true);
$activeSheet->getColumnDimension('H')->setAutoSize(true);
$activeSheet->getColumnDimension('I')->setAutoSize(true);


//ddd($activeSheet);



/////////////////

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


//$filename = md5(microtime()).'.xlsx';
//$filename = md5(microtime()).'.xlsx';
$filename = 'отчет.xlsx';

$filename = 'svod_'.date('d_m_Y H-i-s').'.xls';

$file=$_SERVER['DOCUMENT_ROOT'].'/assets/reports/'.$filename ;


$objWriter->save($file);




//echo '<script>var xlsxfile = "'.$filename.'.xlsx"; </script>';


if (ob_get_level()) {
    ob_end_clean();
}

ob_get_contents();

//header('Content-type: application/vnd.ms-excel');
header('Content-type: application/vnd.excel');
header('Content-Disposition: attachment; filename='.basename($filename).'');
header("Pragma: no-cache");
header("Expires: 0");

// Write file to the browser
$objWriter->save('php://output');

$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);

ob_flush ();


?>

<h1> ОК.</h1>