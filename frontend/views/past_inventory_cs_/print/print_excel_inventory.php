<?php
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


/// TABLE TOP
$sharedStyle1 = new PHPExcel_Style();
$sharedStyle1->applyFromArray(
    array(
        'font' => array(
            'name' => 'Arial',
            'size' => '11',
            'bold' => true,
            'color' => array(
                //'rgb' => 'FF0000'
                'rgb' => '555555'
            ),
        ),

        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('argb' => 'FFDCE6F1')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'wrap' => TRUE,
            'indent' => 5
        ),

        'borders' => array(
            'top' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
            'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
        )
    )
);


/// TABLE BODY
$sharedStyle2 = new PHPExcel_Style();
$sharedStyle2->applyFromArray(
    array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'wrap' => TRUE,
        ),
        'borders' => array(
            'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
        ),

    )
);

/// COLUMN "justyFY"
$sharedStyle2_just = new PHPExcel_Style();
$sharedStyle2_just->applyFromArray(
    array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'wrap' => TRUE,
            'indent' => 2
        ),
        'borders' => array(
            'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
        ),

    )
);

///COLUMN FOR NUMBER
$sharedStyle3_num = new PHPExcel_Style();
$sharedStyle3_num->applyFromArray(
    array(
        'numberformat ' => array(
            'code ' => PHPExcel_Style_NumberFormat:: FORMAT_NUMBER_00

        ),

        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'indent' => 1
        ),
//            'fill' => array(
//                'type' => PHPExcel_Style_Fill::FILL_SOLID,
//                'color' => array('argb' => 'FFDCE6F1')
//            ),

        'borders' => array(
            'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
        )


    )
);

$activeSheet->getColumnDimension('A')->setWidth(4);
$activeSheet->getColumnDimension('B')->setWidth(63);
$activeSheet->getColumnDimension('C')->setWidth(4);
$activeSheet->getColumnDimension('D')->setWidth(-1); //6


$activeSheet->setCellValue('A1', 'Инвентаризационная опись оборудования,');
$activeSheet->mergeCells('A1:D2');
$activeSheet->getStyle('A1')->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A1')->getFont()->setSize(12);
$activeSheet->getStyle('A1')->getFont()->setBold(true);
$activeSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A3', 'принятого на ответ хранение от ТОО "Транспортного холдинга города Алматы" согласно Договору на оказание услуг по монтажу и сервисно-техническому обслуживанию №____________    от "___"__________20___года. ');
$activeSheet->mergeCells('A3:D7');
$activeSheet->getStyle('A3:D7')->getAlignment()->setWrapText(true); //!!!
$activeSheet->getStyle('A3')->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A3')->getFont()->setSize(11);
$activeSheet->getStyle('A3')->getFont()->setBold(true);
$activeSheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$activeSheet->setCellValue('A8', 'На основании приказа(распоряжения) ТОО "Guidejet TI (Гайджет ТиАй)" от "___"_______20___года №_________ произведено снятие фактических остатков оборудования в _______________ ______________, числящихся на ______________________ по состоянию на __/________ 20____ года.       ');
$activeSheet->mergeCells('A8:D12');
$activeSheet->getStyle('A8:D12')->getAlignment()->setWrapText(true); //!!!
$activeSheet->getStyle('A8')->getFont()->setSize(11);
$activeSheet->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);


$activeSheet->setCellValue('A14', 'Инвентаризация начата    "____"_________2019 года.  Время _____');
$activeSheet->setCellValue('A15', 'Инвентаризация окончена  "____"_________2019 года.  Время _____');
$activeSheet->mergeCells('A13:B13');
$activeSheet->mergeCells('A14:B14');


$activeSheet->getRowDimension(14)->setRowHeight(22); /// ВЫСОТА !!!!


/////////////////////
$activeSheet->setCellValue('A17', '№');
//$activeSheet->setCellValue('B17', 'Группа');
$activeSheet->setCellValue('B17', 'Наименование');
$activeSheet->setCellValue('C17', 'Ед');
$activeSheet->setCellValue('D17', 'Кол-во');

$activeSheet->getRowDimension(17)->setRowHeight(40); /// ВЫСОТА !!!!


$activeSheet->setSharedStyle($sharedStyle1, "A17:D17");
$activeSheet->getStyle('A17:D17')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$yy = [];

$num = 18;
$num_str = 1;
foreach ($model['array_tk_amort'] as $key_group => $item) {

    if ( isset( $item[ 'itog' ] ) && $item[ 'itog' ] > 0 ) {
        $activeSheet->setCellValue( 'A'.( $num ), $num_str );
        //        $activeSheet->setCellValue('B' . ($num + 18),  "".$group_am_name[$item['wh_tk_amort']] );
        $activeSheet->setCellValue( 'B'.( $num ), "".$element_am_name[ $item[ 'wh_tk_element' ] ] );
        $activeSheet->setCellValue( 'C'.( $num ), "".$things[ $item[ 'ed_izmer' ] ] );
        //$activeSheet->setCellValue('D' . ($num), "" . $item['ed_izmer_num']);
        $activeSheet->setCellValue( 'D'.( $num ), "".$item[ 'itog' ] );


        $num++;
        $num_str++;
    }
}
$num--;
$activeSheet->setSharedStyle($sharedStyle1, "A18:D18");
$activeSheet->setSharedStyle($sharedStyle2, "A18:D" . ($num));
$activeSheet->getStyle('B18:B' . $num)->getAlignment()->setHorizontal('justyfi');
$activeSheet->setSharedStyle($sharedStyle3_num, 'D18:D' . $num);

//////////////////////// SUM
$num++;
$objPHPExcel->getActiveSheet()
    ->setCellValue(
        'D33',
        '=SUM(D18:D32)'
    );

////////////////////////

$num_str = 1;
$num++;

$num_start = $num;

$key_group_fub = 0;
foreach ($model_array_tk as $key_group => $item) {
    if ($key_group_fub != (int)$item['wh_tk']) {
        $key_group_fub = (int)$item['wh_tk'];

        $activeSheet->setCellValue('A' . $num, "" . $group_name[$item['wh_tk']]);
        $activeSheet->setSharedStyle($sharedStyle1, "A" . $num . ":D" . ++$num);
        $activeSheet->mergeCells('A' . --$num . ':D' . ++$num);

        $num++;

        $activeSheet->setSharedStyle($sharedStyle2, "A" . ($num_start) . ":D" . ($num - 3));
        $activeSheet->setSharedStyle($sharedStyle3_num, 'D' . ($num_start) . ':D' . ($num - 3));
        $activeSheet->getStyle('B' . ($num_start) . ':B' . ($num - 3))->getAlignment()->setHorizontal('justyfi');

        $num_start = $num;
    }

    $activeSheet->setCellValue('A' . ($num), $num_str);
    //$activeSheet->setCellValue('A' . ($num ),  "".$group_name[$item['wh_tk']] );
    $activeSheet->setCellValue('B' . ($num), "" . (isset($element_name[$item['wh_tk_element']]) ? $element_name[$item['wh_tk_element']] : ''));
    $activeSheet->setCellValue('C' . ($num), "" . $things[$item['ed_izmer']]);
    $activeSheet->setCellValue('D' . ($num), "" . $item['ed_izmer_num']);


    $num++;
    $num_str++;

}


$activeSheet->setSharedStyle($sharedStyle2, "A" . ($num_start) . ":D" . ($num - 1));
$activeSheet->setSharedStyle($sharedStyle3_num, 'D' . ($num_start) . ':D' . ($num - 1));
$activeSheet->getStyle('B' . ($num_start) . ':B' . ($num - 1))->getAlignment()->setHorizontal('justyfi');


//Высота ячеек = автомат
//foreach( $activeSheet->getRowDimension('A17:D32') as $rd) {
//    $rd->setRowHeight(-1);
//}


///////////////////////////////////////
/// ПОДПИСИ СТОРОН
///

$num++;
$num++;
$activeSheet->setCellValue('A' . ($num), 'Председатель комиссии:');
$activeSheet->getStyle('A' . ($num))->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A' . ($num))->getFont()->setSize(11);
$activeSheet->getStyle('A' . ($num))->getFont()->setBold(true);

$num++;
$activeSheet->setCellValue('A' . ($num), 'Зам.директора по финансам  _____________Жанарбаев Р.Б.');
$num++;

$num++;
$activeSheet->setCellValue('A' . ($num), 'Представитель ТОО "Транспортный холдинг города Алматы" ');
$activeSheet->getStyle('A' . ($num))->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A' . ($num))->getFont()->setSize(11);
$activeSheet->getStyle('A' . ($num))->getFont()->setBold(true);

$num++;
$activeSheet->setCellValue('A' . ($num), '________________ Канибетов Ж.Р.');
$num++;
$activeSheet->setCellValue('A' . ($num), '________________ Злавдинов А.А.');
$num++;
$activeSheet->setCellValue('A' . ($num), '________________ Камзанов Е.С.');
$num++;
$activeSheet->setCellValue('A' . ($num), '________________ Таипов П.А');
$num++;

$num++;
$activeSheet->setCellValue('A' . ($num), 'Члены комиссии:');
$activeSheet->getStyle('A' . ($num))->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A' . ($num))->getFont()->setSize(11);
$activeSheet->getStyle('A' . ($num))->getFont()->setBold(true);

$num++;
$activeSheet->setCellValue('A' . ($num), 'ТОО "Guidejet TI" (Гайджет ТиАй)');
$activeSheet->getStyle('A' . ($num))->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A' . ($num))->getFont()->setSize(11);
$activeSheet->getStyle('A' . ($num))->getFont()->setBold(true);

$num++;
$activeSheet->setCellValue('A' . ($num), 'Главный инженер по эксплуатации __________Карбаев Н.К.');
$num++;
$activeSheet->setCellValue('A' . ($num), 'Экономист    __________ Татубаев Т.С.');
$num++;
$activeSheet->setCellValue('A' . ($num), 'Бухгалтер    __________ Жакиянова Ж.С.');
$num++;

$num++;
$activeSheet->setCellValue('A' . ($num), 'Материально-ответственное лицо:');
$activeSheet->getStyle('A' . ($num))->getFont()->setName('Arial Black'); ///setName('Courier New');
$activeSheet->getStyle('A' . ($num))->getFont()->setSize(11);
$activeSheet->getStyle('A' . ($num))->getFont()->setBold(true);

$num++;
$activeSheet->setCellValue('A' . ($num), 'Руководитель сервисно-технического отдела');
$activeSheet->setCellValue('A' . ($num), '____________Нуспаев Д.Б.');

////////....................


//$activeSheet->getActiveSheet()->getRowDimension('A17:E17')->setRowHeight(40);
//$activeSheet->getStyle('A17:E17')->getFont()->setName( 'Malgun' );  /// 'Candara');
//$activeSheet->getStyle('A4:H4')->getAlignment()->setHorizontal('center');
//$activeSheet->getStyle('A4:A'.($num+4))->getAlignment()->setHorizontal('center');


$styleThinBlackBorderOutline = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);


//$activeSheet->getStyle('A4:H4')->applyFromArray($styleThinBlackBorderOutline);
//$activeSheet->getStyle('A5:H'.($num+4))->applyFromArray($styleThinBlackBorderOutline);


$activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT); /*ORIENTATION_PORTRAIT*/
$activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


/////////////////

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


//$filename = md5(microtime()).'.xlsx';
//$filename = md5(microtime()).'.xlsx';
$filename = 'отчет.xlsx';

$filename = 'invent_op_' . date('d_m_Y H-i-s') . '.xls';

$file = $_SERVER['DOCUMENT_ROOT'] . '/assets/reports/' . $filename;


$objWriter->save($file);


//echo '<script>var xlsxfile = "'.$filename.'.xlsx"; </script>';


if (ob_get_level()) {
    ob_end_clean();
}


ob_get_contents();
header("Content-Type: application/vnd.ms-excel; charset=utf-8"); # Important
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");


//header('Content-type: application/vnd.excel');
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