<?php
yii::setAlias('@path_save',  __DIR__ .'/frontend/web/assets/reports');
yii::setAlias('@path2',  __DIR__ .'/../../../../vendor/');
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


$activeSheet->setCellValue('A2', 'Отчет. Остатки');



//$activeSheet->setCellValue('G1', '1');

//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);
$activeSheet->getRowDimension('A2:H2')->setRowHeight(50);

$activeSheet->mergeCells('A1:I1');
$activeSheet->mergeCells('A2:I2');

$activeSheet->getStyle('A2')->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A2')->getFont()->setSize(18);
//$activeSheet->getStyle('A2')->getFont()->setBold(true);



$activeSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$activeSheet->getStyle('G1')->getFont()->setBold(true);

//    $activeSheet->setCellValue('A3', 'Отчет');

$activeSheet->mergeCells('A3:I3');

$activeSheet->setCellValue('A4', '№');
$activeSheet->setCellValue('B4', 'Группа');
$activeSheet->setCellValue('C4', 'Наименование');
$activeSheet->setCellValue('D4', 'Ед');
$activeSheet->setCellValue('E4', 'Кол-во');
$activeSheet->setCellValue('F4', 'Приход');
$activeSheet->setCellValue('G4', 'Расход');
$activeSheet->setCellValue('H4', 'Итог');
$activeSheet->setCellValue('I4', '1C-id');




$yy=[];
$num=0;

//            'model' => $model,
//            'group_am_name' => $model1,
//            'element_am_name' => $model2,
//            'group_name'    => $model3,
//            'element_name'  => $model4,
//            'things'  => $model5,


foreach ($model['array_tk_amort'] as $key_group=>$item ) {

        $activeSheet->setCellValue('A' . ($num + 5),  $num+1  );
        $activeSheet->setCellValue('B' . ($num + 5),  "".$group_am_name[$item['wh_tk_amort']] );
        $activeSheet->setCellValue('C' . ($num + 5),  "".$element_am_name[$item['wh_tk_element']] );
        $activeSheet->setCellValue('D' . ($num + 5),  "".$things[$item['ed_izmer']] );
        $activeSheet->setCellValue('E' . ($num + 5),  "".$item['ed_izmer_num']  );
        $activeSheet->setCellValue('F' . ($num + 5),  "".$item['prihod_num']  );
        $activeSheet->setCellValue('G' . ($num + 5),  "".$item['rashod_num']  );
        $activeSheet->setCellValue('H' . ($num + 5),  "".$item['itog']  );

    $activeSheet->setCellValue('I' . ($num + 5), "" . $list_amcc[$item['wh_tk_element']]); //1C-id


    $num++;
}

//ddd($model);


foreach ($model['array_tk'] as $key_group=>$item ) {

        $activeSheet->setCellValue('A' . ($num + 5),  $num+1  );
        $activeSheet->setCellValue('B' . ($num + 5),  "".$group_name[$item['wh_tk']] );
    $activeSheet->setCellValue('C' . ($num + 5), "" . (isset($element_name[$item['wh_tk_element']]) ? $element_name[$item['wh_tk_element']] : ''));
        $activeSheet->setCellValue('D' . ($num + 5),  "".$things[$item['ed_izmer']] );
        $activeSheet->setCellValue('E' . ($num + 5),  "".$item['ed_izmer_num']  );
        $activeSheet->setCellValue('F' . ($num + 5),  "".$item['prihod_num']  );
        $activeSheet->setCellValue('G' . ($num + 5),  "".$item['rashod_num']  );
        $activeSheet->setCellValue('H' . ($num + 5),  "".$item['itog']  );

    $activeSheet->setCellValue('I' . ($num + 5), "" . (isset($list_cc[$item['wh_tk_element']]) ? $list_cc[$item['wh_tk_element']] : ''));// 1C-id


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


/// Ячейки внутри таблицы
$activeSheet->setSharedStyle($sharedStyle2, "A4:H".($num+4));


$activeSheet->getStyle('A3')->getFont()->setBold(true);

$activeSheet->getStyle('A4:I4')->getFont()->setName('Malgun');  /// 'Candara');
$activeSheet->getStyle('A4:I4')->getFont()->setBold(true);
$activeSheet->getStyle('A4:I4')->getFont()->setSize(11);




//$activeSheet->getStyle('A'.($num+4).':J'.($num+4))->getFont()->setBold(true);


$activeSheet->getStyle('A4:I4')->getAlignment()->setHorizontal('center');
$activeSheet->getStyle('A4:A'.($num+4))->getAlignment()->setHorizontal('center');



//getAlignment()->setHorizontal('right');
$activeSheet->getRowDimension('B5:B'.($num+4))->setRowHeight(20);

$activeSheet->getColumnDimension('A')->setWidth(8);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('E')->setAutoSize(true);
$activeSheet->getColumnDimension('F')->setAutoSize(true);
$activeSheet->getColumnDimension('G')->setAutoSize(true);
$activeSheet->getColumnDimension('H')->setAutoSize(true);
$activeSheet->getColumnDimension('I')->setAutoSize(true);





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


$activeSheet->getStyle('A4:I4')->applyFromArray($styleThinBlackBorderOutline);
$activeSheet->getStyle('A5:I' . ($num + 4))->applyFromArray($styleThinBlackBorderOutline);

$activeSheet->getStyle('D5:D' . ($num + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$activeSheet->getStyle('I5:I' . ($num + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); /*ORIENTATION_PORTRAIT*/
$activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);





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