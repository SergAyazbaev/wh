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


$activeSheet->setCellValue('A2', 'Остатки. ' . $fill_name1 . ' - ' . $fill_name2);
$activeSheet->setCellValue('A3', $fill_name3);

//$activeSheet->setCellValue('G1', '1');
//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);

$activeSheet->getRowDimension('A2:H2')->setRowHeight(50);

$activeSheet->mergeCells('A1:H1');
$activeSheet->mergeCells('A2:H2');

$activeSheet->getStyle('A2')->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A2')->getFont()->setSize(18);
//$activeSheet->getStyle('A2')->getFont()->setBold(true);


$activeSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$activeSheet->getStyle('G1')->getFont()->setBold(true);

//    $activeSheet->setCellValue('A3', 'Отчет');

/// Объединить ячейки
$activeSheet->mergeCells('A3:H3');

$activeSheet->setCellValue('A4', '№');


$activeSheet->setCellValue('B4', 'Дата');
$activeSheet->setCellValue('C4', 'Накладная');

$activeSheet->setCellValue('D4', 'Группа');
$activeSheet->setCellValue('E4', 'Название');
$activeSheet->setCellValue('F4', 'Штрих-Код');

$activeSheet->setCellValue('G4', 'Кол.');
$activeSheet->setCellValue('H4', 'Ед.Изм');

/// Объединить ячейки
//$activeSheet->mergeCells('B4:C4');
//$activeSheet->mergeCells('D4:E4');


$yy = [];
$num = 0;

///  'wh_tk_amort' => 7
//    'wh_tk_element' => 1
//    'ed_izmer' => 1
//    'ed_izmer_num' => 1
//    'bar_code' => '041111'
//    'intelligent' => '1'
//    'name_wh_tk_amort' => 'АСУОП'
//    'name_wh_tk_element' => 'ПВ GJ-DA04'
//    'name_ed_izmer' => 'шт.'
//    't' => 1603421631
//    'id' => 30946
//]


foreach ($dataModels as $ii) {
    //ddd($ii);

    //$activeSheet->getStyle('I5:I'.($num + 5))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

    $activeSheet->setCellValue('A' . ($num + 5), $num + 1);
    //$activeSheet->setCellValue('A' . ($num + 5), $ii['id']);

    if (isset($ii['t']) && !empty($ii['t'])) {
        $activeSheet->setCellValue('B' . ($num + 5), date('d.m.Y H:i:s', $ii['t']));
    }
    $activeSheet->setCellValue('C' . ($num + 5), $ii['id']);

    $activeSheet->setCellValue('D' . ($num + 5), $ii['name_wh_tk_amort']);
    $activeSheet->setCellValue('E' . ($num + 5), $ii['name_wh_tk_element']);
    $activeSheet->setCellValue('F' . ($num + 5), $ii['bar_code']);

    $activeSheet->setCellValue('G' . ($num + 5), $ii['ed_izmer_num']);
    $activeSheet->setCellValue('H' . ($num + 5), $ii['name_ed_izmer']);


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
$activeSheet->setSharedStyle($sharedStyle2, "A4:H" . ($num + 4));


$activeSheet->getStyle('A3')->getFont()->setBold(true);


$activeSheet->getStyle('A4:H4')->getFont()->setName('Malgun Gothic');  /// 'Candara');
$activeSheet->getStyle('A4:H4')->getFont()->setBold(true);
$activeSheet->getStyle('A4:H4')->getFont()->setSize(12);

///
$activeSheet->getStyle('A4:H4')->getAlignment()->applyFromArray(
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
$activeSheet->getStyle('A5:D' . ($num + 4))->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('G5:H' . ($num + 4))->getAlignment()->setHorizontal('center');


//getAlignment()->setHorizontal('right');
$activeSheet->getRowDimension('B5:B' . ($num + 4))->setRowHeight(20);

$activeSheet->getColumnDimension('A')->setWidth(8);

$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setWidth(15);

$activeSheet->getColumnDimension('D')->setWidth(20);
$activeSheet->getColumnDimension('E')->setWidth(30);

$activeSheet->getColumnDimension('F')->setWidth(20);
$activeSheet->getColumnDimension('G')->setAutoSize(true);
//    $activeSheet->getColumnDimension('G')->setWidth(28);

$activeSheet->getColumnDimension('H')->setAutoSize();


/// ШАПКА ТАБЛИЦЫ
$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);


//$activeSheet->getStyle('F5:F' . ($num + 4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$activeSheet->getStyle('F5:F' . ($num + 4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
$activeSheet->getStyle('E5:E' . ($num + 4))->getAlignment()->setHorizontal('center');


$activeSheet->getStyle('A4:H4')->applyFromArray($styleThinBlackBorderOutline);
$activeSheet->getStyle('A5:H' . ($num + 4))->applyFromArray($styleThinBlackBorderOutline);

//Перенос текста по строкам внутри ячейки
$activeSheet->getStyle('G1:H' . ($num + 4))->getAlignment()->setWrapText(true);
// Выравниваем по центру
$activeSheet->getStyle('K5:H' . ($num + 4))->getAlignment()->setHorizontal('center');


$activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); /*ORIENTATION_PORTRAIT*/
$activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$num2_start = $num + 7;
$xx = 1;
///////////////////
/// SVOD
foreach ($svod as $gr_id => $n_id) {
    foreach ($n_id as $ii) {
        //ddd($ii);
        /// 'n' => 1620
        //    'gr_name' => 'АСУОП'
        //    'ch_name' => 'ПВ GJ-DA04'

        //$activeSheet->getStyle('I5:I'.($num + 5))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

        $activeSheet->setCellValue('A' . ($num + 7), $xx);
        //$activeSheet->setCellValue('A' . ($num + 5), $ii['id']);

        $activeSheet->setCellValue('B' . ($num + 7), $ii['gr_name']);
        $activeSheet->setCellValue('C' . ($num + 7), $ii['ch_name']);

        $activeSheet->setCellValue('E' . ($num + 7), $ii['n']);

        $activeSheet->mergeCells('C' . ($num + 7) . ':D' . ($num + 7));

        $num++;
        $xx++;
    }
}

$activeSheet->setSharedStyle($sharedStyle2, 'A' . ($num2_start) . ':E' . ($num + 6));

// Выравниваем по центру
$activeSheet->getStyle('A' . ($num2_start) . ':H' . ($num + 7))->getAlignment()->setHorizontal('center');



/////////////////

$activeSheet->setCellValue('F' . ($num + 6), 'Итого:');
$activeSheet->setCellValue("G" . ($num + 6), "=SUM(G" . ($sum_start + 5) . ":G" . ($num + 5) . ")");

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