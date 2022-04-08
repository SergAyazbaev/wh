<?php
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


$activeSheet->setCellValue('A2', 'Отчет по движению (подробный)');


//$activeSheet->setCellValue('G1', '1');

//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('A2:K2')->setRowHeight(50);

$activeSheet->mergeCells('A1:K1');
$activeSheet->mergeCells('A2:K2');

$activeSheet->getStyle('A2')->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A2')->getFont()->setSize(18);
//$activeSheet->getStyle('A2')->getFont()->setBold(true);


$activeSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$activeSheet->getStyle('K1')->getFont()->setBold(true);

//    $activeSheet->setCellValue('A3', 'Отчет');

$activeSheet->mergeCells('A3:K3');

$activeSheet->setCellValue('A4', '№');
$activeSheet->setCellValue('B4', 'Дата');
$activeSheet->setCellValue('C4', 'Склад-Отправитель ');
$activeSheet->setCellValue('D4', '');
$activeSheet->setCellValue('E4', 'Склад-Получатель');
$activeSheet->setCellValue('F4', '');

$activeSheet->setCellValue('G4', 'ТМЦ');
//$activeSheet->setCellValue('H4', 'Наименование');
$activeSheet->setCellValue('I4', 'Bar-code');
$activeSheet->setCellValue('J4', 'Ед.Изм');
$activeSheet->setCellValue('K4', 'Кол-во');


$activeSheet->mergeCells('C4:D4');
$activeSheet->mergeCells('E4:F4');
$activeSheet->mergeCells('G4:H4');


$yy = [];
$num = 0;


foreach ($dataModels as $ii) {
    //ddd($ii);

//    echo'<br>';
//    print_r($ii);
    $activeSheet->setCellValue('A' . ($num + 5), $ii['id']);
    $activeSheet->setCellValue('B' . ($num + 5), date('d.m.Y', strtotime($ii['dt_create'])));

    $activeSheet->setCellValue('C' . ($num + 5), $ii['wh_debet_name']);
    $activeSheet->setCellValue('D' . ($num + 5), $ii['wh_debet_element_name']);

    $activeSheet->setCellValue('E' . ($num + 5), $ii['wh_destination_name']);
    $activeSheet->setCellValue('F' . ($num + 5), $ii['wh_destination_element_name']);


    if (isset($ii['wh_tk_amort']))
        $activeSheet->setCellValue('G' . ($num + 5), $ii['wh_tk_amort']);

    if (isset($ii['wh_tk']))
        $activeSheet->setCellValue('G' . ($num + 5), $ii['wh_tk']);

    $activeSheet->setCellValue('H' . ($num + 5), $ii['wh_tk_element']);
    $activeSheet->setCellValue('I' . ($num + 5), (isset($ii['bar_code']) ? (int)$ii['bar_code'] : ''));


    if ($ii['ed_izmer'] == 1)
        $activeSheet->setCellValue('J' . ($num + 5), 'шт');
    else
        $activeSheet->setCellValue('J' . ($num + 5), $ii['ed_izmer']);

    $activeSheet->setCellValue('K' . ($num + 5), $ii['ed_izmer_num']);


    if ($ii['sklad_vid_oper'] == 2)
        $activeSheet->setCellValue('L' . ($num + 5), 'Приход в:');
    if ($ii['sklad_vid_oper'] == 3)
        $activeSheet->setCellValue('L' . ($num + 5), 'Расход из:');

    $activeSheet->setCellValue('M' . ($num + 5), $ii['wh_home_number']);

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


///// СЕТКА
$activeSheet->setSharedStyle($sharedStyle2, "A4:K" . ($num + 4));


$activeSheet->getStyle('A3')->getFont()->setBold(true);

$activeSheet->getStyle('A4:K4')->getFont()->setName('Malgun Gothic');  /// 'Candara');
$activeSheet->getStyle('A4:K4')->getFont()->setBold(true);
$activeSheet->getStyle('A4:K4')->getFont()->setSize(11);


//$activeSheet->getStyle('A'.($num+4).':J'.($num+4))->getFont()->setBold(true);


$activeSheet->getStyle('A4:K4')->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('A4:A' . ($num + 4))->getAlignment()->setHorizontal('center');


//getAlignment()->setHorizontal('right');
$activeSheet->getRowDimension('B5:B' . ($num + 4))->setRowHeight(20);

$activeSheet->getColumnDimension('A')->setWidth(8);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setWidth(12);
$activeSheet->getColumnDimension('E')->setAutoSize(true);
$activeSheet->getColumnDimension('F')->setWidth(12);

$activeSheet->getColumnDimension('G')->setAutoSize(true);
$activeSheet->getColumnDimension('H')->setWidth(12);
$activeSheet->getColumnDimension('I')->setAutoSize(true);
$activeSheet->getColumnDimension('J')->setAutoSize(true);
$activeSheet->getColumnDimension('K')->setAutoSize(true);


//$activeSheet->getColumnDimension('D')->setWidth(12);
//$activeSheet->getColumnDimension('D')->setWidth(12);


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

/// Общая рамка таблицы (Шапка)
$activeSheet->getStyle('A4:K4')->applyFromArray($styleThinBlackBorderOutline);
/// Общая рамка таблицы (Тело)
$activeSheet->getStyle('A5:K' . ($num + 4))->applyFromArray($styleThinBlackBorderOutline);


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