<?php
	yii::setAlias( '@path_save', __DIR__ . '/frontend/web/assets/reports' );
	yii::setAlias( '@path2', __DIR__ . '/../../../../vendor/' );
	require_once Yii::getAlias( '@path2' ) . '/yexcel/Classes/PHPExcel.php';

	//ddd(__DIR__);


	//yii::setAlias('@path_save', 'C:/OSPanel/domains/prod/frontend/web/assets/reports');
	//yii::setAlias('@path2', 'C:/OSPanel/domains/wh/vendor/');
	//require_once Yii::getAlias('@path2').'/yexcel/Classes/PHPExcel.php';


	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator( "Guidejet TI" )
	            ->setLastModifiedBy( "Guidejet TI" )
	            ->setTitle( "Краткий отчет" )
	            ->setSubject( "Краткий отчет" )
	            ->setDescription( "Краткий отчет" )
	            ->setKeywords( "Краткий отчет" )
	            ->setCategory( "Краткий отчет" );


	$objPHPExcel->setActiveSheetIndex( 0 );
	$activeSheet = $objPHPExcel->getActiveSheet();


	$activeSheet->setCellValue( 'A2', 'Справочник. ПАРТИИ товаров ' );


	//$activeSheet->setCellValue('G1', '1');

	//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);
	$activeSheet->getRowDimension( 'A2:K2' )->setRowHeight( 50 );

	$activeSheet->mergeCells( 'A1:E1' );
	$activeSheet->mergeCells( 'A2:E2' );

	$activeSheet->getStyle( 'A2' )->getFont()->setName( 'Arial Black' ); ///setName('Courier New');
	$activeSheet->getStyle( 'A2' )->getFont()->setSize( 12 );
	//$activeSheet->getStyle('A2')->getFont()->setBold(true);


	$activeSheet->getStyle( 'E1' )->getAlignment()->setHorizontal( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$activeSheet->getStyle( 'E1' )->getFont()->setBold( true );

	//    $activeSheet->setCellValue('A3', 'Отчет');

	$activeSheet->mergeCells( 'A3:E3' );

	//        'id' => '2'
	//        'parent_id' => '5'
	//        'name' => ''
	//        'ed_izm' => ''
	//        'tx' => ''

	$activeSheet->setCellValue( 'A4', '№' );
	$activeSheet->setCellValue( 'B4', 'id' );
	$activeSheet->setCellValue( 'C4', 'Группа' );
	$activeSheet->setCellValue( 'D4', 'Наименование' );
	$activeSheet->setCellValue( 'E4', 'Наименование ПАРТИИ' );

	//$activeSheet->setCellValue('E4', 'Комент');


	//$activeSheet->mergeCells('C4:D4');
	//$activeSheet->mergeCells('E4:F4');
	//$activeSheet->mergeCells('G4:H4');


	$yy  = [];
	$num = 0;

	//ddd($dataModels);


	foreach( $dataModels as $ii )
	{

		//ddd($ii );
		//ddd($ii['spr_glob']['name'] );

		$activeSheet->setCellValue( 'A' . ( $num + 5 ), $num + 1 );
		$activeSheet->setCellValue( 'B' . ( $num + 5 ), $ii[ 'id' ] );
		$activeSheet->setCellValue( 'C' . ( $num + 5 ), $spr_globam[ (int) $ii[ 'spr_globam_element' ][ 'parent_id' ] ] );
		$activeSheet->setCellValue( 'D' . ( $num + 5 ), $spr_globam_element[ (int) $ii[ 'element_id' ] ] );
		$activeSheet->setCellValue( 'E' . ( $num + 5 ), $ii[ 'name' ] );





		$num ++;
	}


	$activeSheet->getColumnDimension( 'A' )->setWidth( 8 );
	$activeSheet->getColumnDimension( 'B' )->setAutoSize( true );
	$activeSheet->getColumnDimension( 'C' )->setAutoSize( true );
	$activeSheet->getColumnDimension( 'D' )->setAutoSize( true );
	$activeSheet->getColumnDimension( 'E' )->setWidth( 28 );


	$sharedStyle2 = new PHPExcel_Style();
	$sharedStyle2->applyFromArray(
		[
			'borders' => [
				'top'    => [ 'style' => PHPExcel_Style_Border::BORDER_THIN ],
				'bottom' => [ 'style' => PHPExcel_Style_Border::BORDER_THIN ],
				'right'  => [ 'style' => PHPExcel_Style_Border::BORDER_THIN ],
			],
		] );

	$activeSheet->getStyle( 'B5:B' . ( $num + 4 ) )->getAlignment()->setWrapText( true );
	$activeSheet->getRowDimension( 'B5:B' . ( $num + 4 ) )->setRowHeight( 20 );


	//$activeSheet->getRowDimension(1,$num+4);
	//$activeSheet->getStyle('B5:B'.($num+4))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);


	///// СЕТКА
	$activeSheet->setSharedStyle( $sharedStyle2, "A4:E" . ( $num + 4 ) );


	$activeSheet->getStyle( 'A3' )->getFont()->setBold( true );

	$activeSheet->getStyle( 'A4:E4' )->getFont()->setName( 'Malgun Gothic' );  /// 'Candara');
	$activeSheet->getStyle( 'A4:E4' )->getFont()->setBold( true );
	$activeSheet->getStyle( 'A4:E4' )->getFont()->setSize( 11 );


	//$activeSheet->getStyle('A'.($num+4).':J'.($num+4))->getFont()->setBold(true);


	//$activeSheet->getStyle('B4:E4')->getAlignment()->setHorizontal('left');
	//$activeSheet->getStyle('A4:A'.($num+4))->getAlignment()->setHorizontal('center');


	//getAlignment()->setHorizontal('right');
	$activeSheet->getRowDimension( 'B5:B' . ( $num + 4 ) )->setRowHeight( 20 );


	//$activeSheet->getColumnDimension('D')->setWidth(12);
	//$activeSheet->getColumnDimension('D')->setWidth(12);


	// $activeSheet->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$activeSheet->getStyle( 'A1' )->getFont()->getColor()->setARGB( PHPExcel_Style_Color::COLOR_BLACK );

	$activeSheet->getStyle( 'C1' )->getFont()->getColor()->setARGB( PHPExcel_Style_Color::COLOR_BLACK );
	$activeSheet->getStyle( 'E1' )->getFont()->getColor()->setARGB( PHPExcel_Style_Color::COLOR_BLACK );

	$styleThinBlackBorderOutline = [
		'borders' => [
			'outline' => [
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				'color' => [ 'argb' => 'FF000000' ],
			],
		],
	];


	$activeSheet->getStyle( 'A4:E4' )->getAlignment()->setHorizontal( 'center' );
	$activeSheet->getStyle( 'A5:B' . ( $num + 4 ) )->getAlignment()->setHorizontal( 'center' );

	$activeSheet->getStyle( 'C5:D' . ( $num + 4 ) )->getAlignment()->setHorizontal( 'left' );
	$activeSheet->getStyle( 'A5:D' . ( $num + 4 ) )->getAlignment()->setVertical( 'top' );


	$activeSheet->getStyle( 'E5:E' . ( $num + 4 ) )->getAlignment()->setWrapText( true );

	$activeSheet->getStyle( 'E5:E' . ( $num + 4 ) )->getAlignment()->setHorizontal( 'justify' );
	$activeSheet->getStyle( 'E5:E' . ( $num + 4 ) )->getAlignment()->setVertical( 'top' );


	///FORMAT NUMBER !!!!!
	$activeSheet->getStyle( 'E5:E' . ( $num + 4 ) )->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );

	/// Общая рамка таблицы (Шапка)
	$activeSheet->getStyle( 'A4:E4' )->applyFromArray( $styleThinBlackBorderOutline );
	/// Общая рамка таблицы (Тело)
	$activeSheet->getStyle( 'A5:E' . ( $num + 4 ) )->applyFromArray( $styleThinBlackBorderOutline );


	$activeSheet->getPageSetup()->setOrientation( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE ); /*ORIENTATION_PORTRAIT*/
	$activeSheet->getPageSetup()->setPaperSize( PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4 );


	/////////////////

	$objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );


	//$filename = md5(microtime()).'.xlsx';
	//$filename = md5(microtime()).'.xlsx';
	$filename = 'отчет.xlsx';

	$filename = 'svod_' . date( 'd.m.Y H-i-s' ) . '.xls';

	$file = $_SERVER[ 'DOCUMENT_ROOT' ] . '/assets/reports/' . $filename;


	$objWriter->save( $file );


	//echo '<script>var xlsxfile = "'.$filename.'.xlsx"; </script>';


	if(ob_get_level())
	{
		ob_end_clean();
	}

	ob_get_contents();

	//header('Content-type: application/vnd.ms-excel');
	header( 'Content-type: application/vnd.excel' );
	header( 'Content-Disposition: attachment; filename=' . basename( $filename ) . '' );
	header( "Pragma: no-cache" );
	header( "Expires: 0" );

	// Write file to the browser
	$objWriter->save( 'php://output' );

	$objPHPExcel->disconnectWorksheets();
	unset( $objPHPExcel );

	ob_flush();


?>

<h1> ОК.</h1>