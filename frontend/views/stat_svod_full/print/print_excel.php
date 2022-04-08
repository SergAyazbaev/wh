<?php
yii::setAlias('@path_save',  __DIR__ .'/frontend/web/assets/reports');
yii::setAlias('@path2',  __DIR__ .'/../../../../vendor/');
require_once Yii::getAlias('@path2').'/yexcel/Classes/PHPExcel.php';

//ddd(__DIR__);



ddd(123);


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
$activeSheet = $objPHPExcel -> getActiveSheet();


$activeSheet->setCellValue('A2', 'Отчет по движению');



//$activeSheet->setCellValue('G1', '1');

//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('A2:K2')->setRowHeight(50);

$activeSheet->mergeCells('A1:K1');
$activeSheet->mergeCells('A2:K2');

$activeSheet->getStyle('A2')->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A2')->getFont()->setSize(18);
//$activeSheet->getStyle('A2')->getFont()->setBold(true);



$activeSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$activeSheet->getStyle('G1')->getFont()->setBold(true);

//    $activeSheet->setCellValue('A3', 'Отчет');

$activeSheet->mergeCells('A3:K3');

$activeSheet->setCellValue('A4', '№');
$activeSheet->setCellValue('B4', 'Склад-Отправитель ');
$activeSheet->setCellValue('C4', '');
$activeSheet->setCellValue('D4', 'Склад-Получатель');
$activeSheet->setCellValue('E4', '');
$activeSheet->setCellValue('F4', 'ТМЦ');
$activeSheet->setCellValue('G4', '');
$activeSheet->setCellValue('H4', 'Ед.Изм');
$activeSheet->setCellValue('I4', 'Кол-во');
$activeSheet->setCellValue('J4', 'BarCode');
$activeSheet->setCellValue('K4', 'Дата');

$activeSheet->mergeCells('B4:C4');
$activeSheet->mergeCells('D4:E4');
$activeSheet->mergeCells('F4:G4');

$yy=[];
$num=0;

//ddd($dataModels);

//        'wh_home_number' => 86
        //        'wh_debet_element' => '531'
//        'wh_debet_name' => 'Алматинский городской автобусный парк №2'
//        'wh_debet_element_name' => 'A204FK'
            //        'wh_destination' => '4'
            //        'wh_destination_element' => '86'
//        'wh_destination_name' => 'Guidejet TI. Основной склад'
//        'wh_destination_element_name' => 'АСУОП'

//        'wh_tk_amort' => 'Крепления, кронштейны'
//        'wh_tk_element' => 'Fastener for CVB24 (Крепление для CVB24)'

//        'ed_izmer' => '1'
//        'ed_izmer_num' => '2'
//        'bar_code' => ''
//        'dt_create' => '03.05.2019 10:21:15'

//        'sklad_vid_oper' => '2'
//        'naklad' => 181
//        'id' => 181

foreach ($dataModels as $ii ) {
//    echo'<br>';
//    print_r($ii);
    $activeSheet->setCellValue('A' . ($num + 5), $ii['id']);
    $activeSheet->setCellValue('B' . ($num + 5), $ii['wh_debet_name']);
    $activeSheet->setCellValue('C' . ($num + 5), $ii['wh_debet_element_name']);

    $activeSheet->setCellValue('D' . ($num + 5), $ii['wh_destination_name']);
    $activeSheet->setCellValue('E' . ($num + 5), $ii['wh_destination_element_name']);

    if(isset($ii['wh_tk_amort']) && !empty($ii['wh_tk_amort']))
        $activeSheet->setCellValue('F' . ($num + 5), $ii['wh_tk_amort']);
    if(isset($ii['wh_tk_element']) && !empty($ii['wh_tk_element']))
        $activeSheet->setCellValue('G' . ($num + 5), $ii['wh_tk_element']);

    if(isset($ii['ed_izmer']) && !empty($ii['ed_izmer']))
        $activeSheet->setCellValue('H' . ($num + 5), $ii['ed_izmer'] );

    if(isset($ii['ed_izmer']) && !empty($ii['ed_izmer']))
        $activeSheet->setCellValue('I' . ($num + 5), $ii['ed_izmer_num'] );

    if(isset($ii['bar_code']) && !empty($ii['bar_code']))
        $activeSheet->setCellValue('J' . ($num + 5),  (int)$ii['bar_code']);
    $activeSheet->setCellValue('K' . ($num + 5), date('d.m.Y', strtotime($ii['dt_create'])) );
    $activeSheet->setCellValue('L' . ($num + 5), $ii['wh_home_number']);


    $num++;
}


//    0 => 86 Guidejet TI. Основной склад АСУОП
//    1 => 87 Guidejet TI. Основной склад Сырье и материалы
//    2 => 84 Guidejet TI. Склад инженеров эксплуатации Минжасаров М
//    3 => 3523 Guidejet TI. Склад ремонта	Токарев Владимир Николаевич
//    4 => 3991 Сергеев Евгений Александрович

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
$activeSheet->getStyle('A4:K4')->getFont()->setSize(11);




//$activeSheet->getStyle('A'.($num+4).':J'.($num+4))->getFont()->setBold(true);


$activeSheet->getStyle('A4:K4')->getAlignment()->setHorizontal('center');

$activeSheet->getStyle('A4:A'.($num+4))->getAlignment()->setHorizontal('center');
//$activeSheet->getStyle('F4:K'.($num+4))->getAlignment()->setHorizontal('center');


//getAlignment()->setHorizontal('right');
$activeSheet->getRowDimension('B5:B'.($num+4))->setRowHeight(20);

$activeSheet->getColumnDimension('A')->setWidth(8);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('E')->setAutoSize(true);
$activeSheet->getColumnDimension('F')->setAutoSize(true);
$activeSheet->getColumnDimension('G')->setWidth(15);
$activeSheet->getColumnDimension('H')->setWidth(10);
$activeSheet->getColumnDimension('I')->setWidth(10);
$activeSheet->getColumnDimension('J')->setWidth(19);
$activeSheet->getColumnDimension('K')->setWidth(12);




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