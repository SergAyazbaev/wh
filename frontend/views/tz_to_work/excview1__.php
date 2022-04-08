<?php
//    dd($array_m);
//yii::setAlias('@path_save', 'C:/OSPanel/domains/wh/frontend/web/reports');
//yii::setAlias('@path2', 'C:/OSPanel/domains/wh/vendor/');

//require_once Yii::getAlias('@path2').'\yexcel\Classes\PHPExcel.php';



//\moonland\phpexcel\Excel::export([
//    'models' => $array_m,
//    'columns' => ['column1','column2','column3'], //without header working, because the header will be get label from attribute label.
//    'headers' => ['column1' => 'Header Column 1','column2' => 'Header Column 2', 'column3' => 'Header Column 3'],
//]);
//
//dd(123);



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

$activeSheet->setCellValue('A1', date('d.m.Y H:i') );
$activeSheet->getStyle('A2')->getAlignment()->setHorizontal('left');


$activeSheet->setCellValue('A2', 'ТехЗадание');
$activeSheet->getStyle('A2')->getAlignment()->setHorizontal('center');


//$activeSheet->setCellValue('G1', '1');

//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('A2:K2')->setRowHeight(50);

$activeSheet->mergeCells('A1:K1');
$activeSheet->mergeCells('A2:K2');

//$activeSheet->getStyle('A2')->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A2')->getFont()->setName('Arial'); ///setName('Courier New');
$activeSheet->getStyle('A2')->getFont()->setSize(30);
//$activeSheet->getStyle('A2')->getFont()->setBold(true);



$activeSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$activeSheet->getStyle('G1')->getFont()->setBold(true);

//    $activeSheet->setCellValue('A3', 'Отчет');

$activeSheet->mergeCells('A3:K3');

$activeSheet->setCellValue('A4', '№');
$activeSheet->setCellValue('B4', 'Т.З.');
$activeSheet->setCellValue('C4', 'Автопарк');
$activeSheet->setCellValue('D4', 'Срок');
$activeSheet->setCellValue('E4', 'Группа');
$activeSheet->setCellValue('F4', 'Наименование');
$activeSheet->setCellValue('G4', 'Ед изм');
$activeSheet->setCellValue('H4', 'шт');
$activeSheet->setCellValue('I4', 'Комплектов');
$activeSheet->setCellValue('J4', 'всего, штук ');
$activeSheet->setCellValue('K4', '');


$yy=[];
$num=0;

foreach (  $array_m  as $ii ) {

    $activeSheet->setCellValue('A' . ($num + 5), $num+1 );
    $activeSheet->setCellValue('B' . ($num + 5), $ii['id']);

    $activeSheet->setCellValue('C' . ($num + 5), $ii['wh_cred_top_name']);
    $activeSheet->setCellValue('D' . ($num + 5), date('d.m.Y H:i:s', strtotime($ii['dt_deadline'])));
                //$ii['dt_deadline']);

    //$activeSheet->setCellValue('B' . ($num + 5), $ii['dt_create']);
    //$activeSheet->setCellValue('K' . ($num + 5), date('d.m.Y', strtotime($ii['dt_create'])));

    $activeSheet->setCellValue('I' . ($num + 5), $ii['multi_tz']);    //multi_tz

    if (isset($ii['array_tk_amort']) && !empty($ii['array_tk_amort']))
        foreach (  $ii['array_tk_amort']  as $amort ) {
            $activeSheet->setCellValue('A' . ($num + 5), $num+1 );

            $activeSheet->setCellValue('E' . ($num + 5), $amort[1]);
            $activeSheet->setCellValue('F' . ($num + 5), $amort[2]);
            $activeSheet->setCellValue('G' . ($num + 5), $amort[3]);
            $activeSheet->setCellValue('H' . ($num + 5), $amort[4]);

            if ($amort[4]>0) {
                $activeSheet->setCellValue('I' . ($num + 5), $ii['multi_tz']);
                $activeSheet->setCellValue('J' . ($num + 5), $amort[4] * $ii['multi_tz']);
            }

            $num++;
        }
        //$num--;

    if (isset($ii['array_tk']) && !empty($ii['array_tk']))
        foreach (  $ii['array_tk']  as $tk ) {

            $activeSheet->setCellValue('A' . ($num + 5), $num+1 );

            $activeSheet->setCellValue('E' . ($num + 5), $tk[1]);
            $activeSheet->setCellValue('F' . ($num + 5), $tk[2]);
            $activeSheet->setCellValue('G' . ($num + 5), $tk[3]);
            $activeSheet->setCellValue('H' . ($num + 5), $tk[4]);

            if ($tk[4]>0) {
                $activeSheet->setCellValue('I' . ($num + 5), $ii['multi_tz']);
                $activeSheet->setCellValue('J' . ($num + 5), $tk[4] * $ii['multi_tz']);
            }

            $num++;
        }
        $num--;

    $num++;
}



//Итого:
//$activeSheet->setCellValue('E'.($num+6), " Итого, (кв.м):" );
//$activeSheet->setCellValue('F'.($num+6), $e_val );
//$activeSheet->setCellValue('G'.($num+6), $f_val );



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

$activeSheet->setSharedStyle($sharedStyle2, "A4:K".($num+4));


$activeSheet->getStyle('A3')->getFont()->setBold(true);

$activeSheet->getStyle('A4:K4')->getFont()->setName( 'Malgun Gothic');  /// 'Candara');
$activeSheet->getStyle('A4:K4')->getFont()->setBold(true);
$activeSheet->getStyle('A4:K4')->getFont()->setSize(14);




//$activeSheet->getStyle('A'.($num+4).':J'.($num+4))->getFont()->setBold(true);

$activeSheet->getStyle('A4:B'.($num+4))->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('D4:D'.($num+4))->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('G4:K'.($num+4))->getAlignment()->setHorizontal('center');
//$activeSheet->getStyle('F4:K'.($num+4))->getAlignment()->setHorizontal('center');


//getAlignment()->setHorizontal('right');
$activeSheet->getRowDimension('B5:B'.($num+4))->setRowHeight(20);

$activeSheet->getColumnDimension('A')->setWidth(6);
$activeSheet->getColumnDimension('B')->setWidth(6);
//$activeSheet->getColumnDimension('C')->setAutoSize(true); //setWidth(40);
//$activeSheet->getColumnDimension('D')->setAutoSize(true);
//$activeSheet->getColumnDimension('E')->setAutoSize(true);
//$activeSheet->getColumnDimension('F')->setAutoSize(true);
//$activeSheet->getColumnDimension('G')->setAutoSize(true);
$activeSheet->getColumnDimension('H')->setAutoSize(true);
//$activeSheet->getColumnDimension('I')->setAutoSize(true);
//$activeSheet->getColumnDimension('J')->setAutoSize(true);
$activeSheet->getColumnDimension('K')->setAutoSize(true);
$activeSheet->getColumnDimension('L')->setAutoSize(true);


$activeSheet->getStyle('A4:K4')->getAlignment()->setWrapText(true);
$activeSheet->getStyle('A4:K4')->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('A4:K4')->getAlignment()->setVertical('center');
$activeSheet->getRowDimension('A4:K4')->setRowHeight(50);


$activeSheet->getStyle('C5:C'.($num+4))->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('C5:C'.($num+4))->setRowHeight(50);
$activeSheet->getColumnDimension('C')->setWidth(20);

$activeSheet->getStyle('D5:D'.($num+4))->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('D5:D'.($num+4))->setRowHeight(50);
$activeSheet->getColumnDimension('D')->setWidth(10);

$activeSheet->getStyle('E5:E'.($num+4))->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('E5:E'.($num+4))->setRowHeight(50);
$activeSheet->getColumnDimension('E')->setWidth(20);

$activeSheet->getStyle('F5:F'.($num+4))->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('F5:F'.($num+4))->setRowHeight(50);
$activeSheet->getColumnDimension('F')->setWidth(35);

$activeSheet->getStyle('G5:G'.($num+4))->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('G5:G'.($num+4))->setRowHeight(50);
$activeSheet->getColumnDimension('G')->setWidth(8);

$activeSheet->getStyle('I5:I'.($num+4))->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('I5:I'.($num+4))->setRowHeight(50);
$activeSheet->getColumnDimension('I')->setWidth(10);

$activeSheet->getStyle('J5:J'.($num+4))->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('J5:J'.($num+4))->setRowHeight(50);
$activeSheet->getColumnDimension('J')->setWidth(10);


$activeSheet->getColumnDimension('D')->setWidth(12);




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




//$activeSheet->getStyle('A3:I3')->applyFromArray($styleThinBlackBorderOutline);
$activeSheet->getStyle('A4:K4')->applyFromArray($styleThinBlackBorderOutline);
$activeSheet->getStyle('A5:K'.($num+4))->applyFromArray($styleThinBlackBorderOutline);
//$activeSheet->getStyle('A'.($num+4).':I'.($num+4))->applyFromArray($styleThinBlackBorderOutline);


$activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); /*ORIENTATION_PORTRAIT*/
$activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);



//    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

//    $file = "excel_".md5(microtime());
//    $full_filename= $_SERVER['DOCUMENT_ROOT'].'/reports/'.$file.'.xlsx';
//    $objWriter->save($full_filename);
//
//    $objWriter->save('php://output');
/////////////////


        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


        //$filename = md5(microtime()).'.xlsx';
        //$filename = md5(microtime()).'.xlsx';
        //$filename = 'отчет.xlsx';

        $filename = 'Tz '.date('d_m_Y H-i-s').'.xls';

        $file=$_SERVER['DOCUMENT_ROOT'].'/reports/tz/'.$filename ;


        $objWriter->save($file);




        //echo '<script>var xlsxfile = "'.$filename.'.xlsx"; </script>';


if (ob_get_level()) {
    ob_end_clean();
}

ob_get_contents();

        //header('Content-type: application/vnd.ms-excel');
        header('Content-type: application/vnd.excel');
        header('Content-Disposition: attachment; filename='.basename($filename).'');

        // Write file to the browser
        $objWriter->save('php://output');

        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);

ob_flush ();


?>

<h1> ОК.</h1>

