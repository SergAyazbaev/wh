<?php
yii::setAlias('@path_save', 'C:/OSPanel/domains/wh/frontend/web/reports');
yii::setAlias('@path2', 'C:/OSPanel/domains/wh/vendor/');

require_once Yii::getAlias('@path2').'/yexcel/Classes/PHPExcel.php';


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


$activeSheet->setCellValue('J2', 'Приложение № ____');
$activeSheet->setCellValue('J3', 'К Договору о полной');
$activeSheet->setCellValue('J4', 'материальной ответственности');
$activeSheet->setCellValue('J5', '№__ от ___________ 20__ года');

$activeSheet->getStyle('J2:J5')->getAlignment()->setHorizontal('right');

$activeSheet->setCellValue('F7', 'АКТ _______');
$activeSheet->setCellValue('F8', 'демонтажа и возврата оборудования');

$activeSheet->getStyle('F7:F8')->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('F7:F8')->getFont()->setSize(20);

$activeSheet->setCellValue('A10', 'г. Алматы');
$activeSheet->setCellValue('J10', date("d.m.Y",strtotime("now")));
$activeSheet->getStyle('J10')->getAlignment()->setHorizontal('right');

////////
//$activeSheet->setCellValue('A12', 'ТОО «Транспортный холдинг города Алматы», в лице директора Абдураманова Р.Р., действующего на основании Устава, именуемое в дальнейшем «Собственник», и ТОО «Транспортная Карта» в лице Директора Кабиденова К.К. действующего на основании Устава, именуемый в дальнейшем «Исполнитель» с одной стороны, и ТОО «АВТОБАЗА ДОСТРАНС» в лице Директора Байсултанова А.У., действующего на основании Устава, именуемое в дальнейшем «Перевозчик», с другой стороны, далее совместно именуемые «Стороны», а по отдельности «Сторона», на основании договора о полной материальной ответственности № «4» от «21» июля 2015 года, составили настоящий акт о том что:');
$activeSheet->setCellValue('A12', 'ТОО «Транспортный холдинг города Алматы», именуемое в дальнейшем «Собственник», в лице Временно исполняющего обязанности директора Хамраева С.А., действующего на основании Устава и Протокола Общего собрания участников от «05» февраля 2016 года, с одной стороны, ТОО «Транспортная карта», именуемое в дальнейшем «Исполнитель», в лице Заместителя директора по производству Темиркулова Н.С., действующего на основании доверенности от 27 апреля 2016 года, и Заместителя директора по финансам Каршаловон А.А., действующего на основании Устава, со второй стороны, и ТОО «АВТОБАЗА ДОСТРАНС» именуемое в дальнейшем «Перевозчик», в лице Директора Байсултанова А.У., действующего на основании Устава, с третьей стороны, далее совместно именуемые «Стороны», а по отдельности «Сторона», на основании Договора о полной материальной ответственности № 04 от «21» июля 2015 года, составили настоящий Акт о том что:');

$activeSheet->mergeCells('A12:J12');
$activeSheet->getStyle('A12:J12')->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('12')->setRowHeight(140);


//$activeSheet->setCellValue('A13', '1. «Собственник» передал, «Исполнитель» установил, а «Перевозчик» принял ниже перечисленное оборудование на хранение, в том виде и количестве, в котором оно указано в настоящем акте:');
$activeSheet->setCellValue('A13', 'I. «Перевозчик» передал, «Исполнитель» демонтировал, а «Собственник» принял назад нижеперечисленное оборудование, в том виде и количестве, в котором оно указано в настоящем акте:');

$activeSheet->mergeCells('A13:J13');
$activeSheet->getStyle('A13:J13')->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('13')->setRowHeight(31);

$activeSheet->getStyle('A12:A13')->getAlignment()->setHorizontal('justify');
////
///
///
///
///

//
//$activeSheet->getStyle('A2')->getFont()->setName('Arial Black'); ///setName('Courier New');
//$activeSheet->getStyle('A2')->getFont()->setSize(30);
////$activeSheet->getStyle('A2')->getFont()->setBold(true);

$num=14;

$activeSheet->mergeCells('A'.$num.':J'.$num);

$activeSheet->setCellValue('A'.($num+1), '№');

//$activeSheet->setCellValue('B'.($num+1), 'Документ');
$activeSheet->setCellValue('C'.($num+1), 'Дата');

$activeSheet->setCellValue('D'.($num+1), 'Склад компании');
$activeSheet->setCellValue('E'.($num+1), 'Авто');

$activeSheet->setCellValue('F'.($num+1), 'Название прибора');
$activeSheet->setCellValue('G'.($num+1), 'Инв №');
$activeSheet->setCellValue('H'.($num+1), 'Контент');
$activeSheet->setCellValue('I'.($num+1), 'Коммент');

$activeSheet->setCellValue('J'.($num+1), 'Автор');

$activeSheet->getStyle('A'.($num+1).':G'.($num+1))->getAlignment()->setWrapText(true);

//$activeSheet->getRowDimension('A'.($num+1).':J'.($num+1))->setRowHeight(30);
//$activeSheet->getStyle($num+1)->getFont()->setBold(true);

////
///

$yy=[];
$num=16;
$num_start=$num;
$xx=1;


foreach ($dataModels as $ii ) {
    //$activeSheet->setCellValue('A' . ($num ), $ii['id']);
    $activeSheet->setCellValue('A' . ($num ), $xx);
    //$activeSheet->setCellValue('B' . ($num  ), $ii['type_action_tx']);
    //$activeSheet->setCellValue('B' . ($num  ), $ii['document']);
    $activeSheet->setCellValue('C' . ($num  ),
            date('d.m.Y',strtotime($ii['dt_create']))
        );

    $activeSheet->setCellValue('D' . ($num  ), $ii['wh_top_name']);
    $activeSheet->setCellValue('E' . ($num  ), $ii['wh_element']);

    $activeSheet->setCellValue('F' . ($num  ), $ii['pv']['type_pv_name']);
    $activeSheet->setCellValue('G' . ($num  ), $ii['pv_id']);

    $activeSheet->setCellValue('H' . ($num  ),  $ii['content']);
    $activeSheet->setCellValue('I' . ($num  ),  $ii['comments']);

    $activeSheet->setCellValue('J' . ($num  ),  $ii['name']);

    $num++;
    $xx++;
}


///// ЧЕРНАЯ СЕТКА
$sharedStyle2 = new PHPExcel_Style();
$sharedStyle2->applyFromArray(
    array('borders' => array(
        'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
        'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
    )
    ));

$activeSheet->duplicateStyle($sharedStyle2, 'A'.($num_start).':J'.($num-1) );
///////////////

$sharedStyle1 = new PHPExcel_Style();
$sharedStyle1->applyFromArray(
    array('borders' => array(
        'top'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
        'bottom' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
        'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
    )
    ));

//$activeSheet->getStyle('A15:G15')->getAlignment()->setWrapText(true);


$blueBold =$sharedStyle1->applyFromArray(
    array(
        "font" => array(
            "bold" => true,
            "size" => 12,
            'wrap' => true,
            "color" => array("rgb" => "0000ff"),
        ),
    ));

$style = array(
        'wraptext' => true,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
);


$activeSheet->duplicateStyle($blueBold, 'A'.($num_start-1).':J'.($num_start-1) );

//$objPHPExcel->getActiveSheet()->getStyle('A15')
//->applyFromArray($style);

//$activeSheet->getStyle('A'.($num_start-1).':J'.($num_start-1))->getAlignment()->setVertical('center');
//$activeSheet->getStyle('A'.($num_start-1).':J'.($num_start-1))->getAlignment()->setHorizontal('center');

$activeSheet->getStyle('A'.($num_start-1).':J'.($num_start-1))->getAlignment()->setWrapText(true);

$activeSheet->getStyle('A'.($num_start-1).':J'.($num_start-1))->getAlignment()->applyFromArray($style);





$activeSheet->getStyle('D'.($num_start).':F'.($num))->getAlignment()->setWrapText(true);

$activeSheet->getStyle('A'.($num_start).':B'.($num))->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('E'.($num_start).':E'.($num))->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('G'.($num_start).':G'.($num))->getAlignment()->setHorizontal('center');



$activeSheet->getColumnDimension('A')->setWidth(5);
$activeSheet->getColumnDimension('B')->setWidth(0);
$activeSheet->getColumnDimension('C')->setWidth(11);
$activeSheet->getColumnDimension('D')->setWidth(20);
$activeSheet->getColumnDimension('E')->setWidth(10);
$activeSheet->getColumnDimension('F')->setWidth(20);
$activeSheet->getColumnDimension('G')->setWidth(6);
$activeSheet->getColumnDimension('H')->setWidth(12);
$activeSheet->getColumnDimension('I')->setWidth(12);
$activeSheet->getColumnDimension('J')->setWidth(8);


///
///
///
///
$num=$num+2;

//$activeSheet->setCellValue('A'.$num, 'Вышеуказанное оборудование установлено и передано Перевозчику. Перевозчик несет полную материальную ответственность за принятое вышеуказанное оборудование.');
$activeSheet->setCellValue('A'.$num, '2. Общее количество демонтированного оборудования составляет 2 (Два) единицы.');
$activeSheet->setCellValue('A'.++$num, '2.1. Стоимость демонтированного оборудования составляет 335 710 (Триста тридцать пять тысяч семьсот десять) тенге с учетом НДС.');

$activeSheet->mergeCells('A'.$num.':J'.$num);
$activeSheet->getStyle('A'.$num.':J'.$num)->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension($num)->setRowHeight(31);

$activeSheet->setCellValue('A'.++$num, 'Настоящий акт является неотъемлемой частью Договора о полной материальной ответственности № 04 от «21» июля 2015 года.');

$activeSheet->mergeCells('A'.$num.':J'.$num);
$activeSheet->getStyle('A'.$num.':J'.$num)->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension($num)->setRowHeight(31);


$activeSheet->mergeCells('A'.$num.':J'.$num );
$activeSheet->getStyle('A'.$num.':J'.$num)->getAlignment()->setWrapText(true);
$activeSheet->getStyle('A'.$num.':J'.$num)->getAlignment()->setHorizontal('justify');
$activeSheet->getRowDimension( $num )->setRowHeight(31);

$num=$num+2;



$activeSheet->setCellValue('A'.$num, 'СОБСТВЕННИК: Временно исполняющий обязанности Директора ТОО «Транспортный холдинг города Алматы');
$activeSheet->getStyle('A'.$num)->getAlignment()->setWrapText(true);
$activeSheet->mergeCells('A'.$num.':E'.$num);
$activeSheet->getStyle('A'.$num.':E'.$num)->getAlignment()->setHorizontal('justify');
$activeSheet->getRowDimension($num)->setRowHeight(45);
$activeSheet->setCellValue('H'.$num, '__________________________');
$activeSheet->mergeCells('H'.$num.':J'.$num);

$num=$num+2;

$activeSheet->setCellValue('A'.$num, 'ИСПОЛНИТЕЛЬ: Заместитель директора по производству ТОО «Транспортная карга»');
$activeSheet->getStyle('A'.$num)->getAlignment()->setWrapText(true);
$activeSheet->mergeCells('A'.$num.':E'.$num);
$activeSheet->getStyle('A'.$num.':E'.$num)->getAlignment()->setHorizontal('justify');
$activeSheet->getRowDimension($num)->setRowHeight(41);
$activeSheet->setCellValue('H'.$num, '__________________________');
$activeSheet->mergeCells('H'.$num.':J'.$num);

$num=$num+2;

$activeSheet->setCellValue('A'.$num, 'ИСПОЛНИТЕЛЬ: Заместитель директора по финансам ТОО «Транспортная карта»');
$activeSheet->getStyle('A'.$num)->getAlignment()->setWrapText(true);
$activeSheet->mergeCells('A'.$num.':E'.$num);
$activeSheet->getStyle('A'.$num.':E'.$num)->getAlignment()->setHorizontal('justify');
$activeSheet->getRowDimension($num)->setRowHeight(41);
$activeSheet->setCellValue('H'.$num, '__________________________');
$activeSheet->mergeCells('H'.$num.':J'.$num);

$num=$num+2;

$activeSheet->setCellValue('A'.$num, 'ПЕРЕВОЗЧИК: Директор ТОО «АВТОБАЗА ДОСТРАНС»');
$activeSheet->getStyle('A'.$num)->getAlignment()->setWrapText(true);
$activeSheet->mergeCells('A'.$num.':E'.$num);
$activeSheet->getStyle('A'.$num.':E'.$num)->getAlignment()->setHorizontal('justify');
$activeSheet->getRowDimension($num)->setRowHeight(41);
$activeSheet->setCellValue('H'.$num, '__________________________');
$activeSheet->mergeCells('H'.$num.':J'.$num);



////$activeSheet->getRowDimension(1,$num+4);
////$activeSheet->getStyle('B5:B'.($num+4))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

//$activeSheet->getStyle('A4:A'.($num+4))->getAlignment()->setHorizontal('center');
//$activeSheet->getStyle('E5:L'.($num+4))->getAlignment()->setHorizontal('center');

////getAlignment()->setHorizontal('right');
//$activeSheet->getRowDimension('B5:B'.($num+4))->setRowHeight(20);
//
//$activeSheet->getColumnDimension('A')->setWidth(8);
//$activeSheet->getColumnDimension('B')->setWidth(12);
//$activeSheet->getColumnDimension('C')->setAutoSize(true);
//$activeSheet->getColumnDimension('D')->setAutoSize(true);
//$activeSheet->getColumnDimension('E')->setAutoSize(true);
//$activeSheet->getColumnDimension('F')->setAutoSize(true);
//$activeSheet->getColumnDimension('G')->setWidth(8);
//$activeSheet->getColumnDimension('H')->setAutoSize(true);
//$activeSheet->getColumnDimension('I')->setAutoSize(true);
//$activeSheet->getColumnDimension('J')->setAutoSize(true);
//$activeSheet->getColumnDimension('K')->setAutoSize(true);
//$activeSheet->getColumnDimension('L')->setAutoSize(true);
//
//
//
//
//$activeSheet->getColumnDimension('D')->setWidth(12);
//
//
//
//
//// $activeSheet->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
//$activeSheet->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
//
//    $activeSheet->getStyle('C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
//    $activeSheet->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
//
//$styleThinBlackBorderOutline = array(
//    'borders' => array(
//        'outline' => array(
//            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
//            'color' => array('argb' => 'FF000000'),
//        ),
//    ),
//);
//
//
//
//
////$activeSheet->getStyle('A3:I3')->applyFromArray($styleThinBlackBorderOutline);
//$activeSheet->getStyle('A4:L4')->applyFromArray($styleThinBlackBorderOutline);
//$activeSheet->getStyle('A5:L'.($num+4))->applyFromArray($styleThinBlackBorderOutline);
////$activeSheet->getStyle('A'.($num+4).':I'.($num+4))->applyFromArray($styleThinBlackBorderOutline);
//
//
//$activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); /*ORIENTATION_PORTRAIT*/
//$activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);



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

        $filename = 'отчет'.date('d_m_Y H-i-s').'.xls';

        $file=$_SERVER['DOCUMENT_ROOT'].'/reports/pvmotion/adv/'.$filename ;


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