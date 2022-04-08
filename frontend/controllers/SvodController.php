<?php

namespace frontend\controllers;

use frontend\components\MyHelpers;
use frontend\models\postsprwhelement;
use frontend\models\Sklad;
use frontend\models\Sklad_wh_invent;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use frontend\models\Virtual;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style;
use PHPExcel_Style_Border;
use PHPExcel_Style_Color;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Worksheet_PageSetup;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;


/**
 *
 */
class SvodController extends Controller
{

    /**
     * Если ГРУППА ниже нормы STATISTIC, то нет доступа
     *
     * @param $event
     * @return bool
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function beforeAction($event)
    {
        if (!isset(Yii::$app->getUser()->identity->id)) {
            throw new HttpException(411, 'Необходима авторизация', 1);
        }
        return parent::beforeAction($event);
    }


    /**
     *
     */
    public function init()
    {
        parent::init();

        $session = Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
    }


    /**
     * Главняа страница в древе Складов
     * =
     * INDEX --TREE--
     */
    public function actionTree()
    {
        //echo memory_get_usage() . "\n"; // 36640

        ini_set('memory_limit', '128M');
        //global $model_tree;

        $model = new Virtual();
        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
        $model->dt_create_timestamp = strtotime($model->dt_create);

        //
        $model_tree = [];
        $model_tree[] = [
            'id' => -1,
            'parent_id' => 0,
            'name' => '',
            'text' => 'Для начала работы выберите дату'
        ];


        //
        //
        if ($model->load(Yii::$app->request->post())) {
            //
            $model->dt_create_timestamp = strtotime($model->dt_create);
            //$items = MyHelpers::WH_BinaryTree();

            ///
            if (isset(Yii::$app->user->identity->sklad) && is_array(Yii::$app->user->identity->sklad)) {
                $array_sklads = Yii::$app->user->identity->sklad;
            }
            if (isset(Yii::$app->user->identity->sklad) && is_numeric(Yii::$app->user->identity->sklad)) {
                $array_sklads = [Yii::$app->user->identity->sklad];
            }

            //
            $parent_ids = Sprwhelement::find()
                ->where(['$in', 'id', $array_sklads])
                ->distinct('parent_id');
            //ddd($parent_ids);

            //
            ///     $parent_ids = [41, 42, 44, 45, 46, 8, 7, 4, 3, 2, 1];
            ///     $parent_ids = [46, 7]; ///GROUP

            //
            $model_tree = MyHelpers::WH_BinaryTree_by_date_onlyWH((int)$model->dt_create_timestamp, $parent_ids);
        }


        //ddd($model_tree);

        ///
        $items = MyHelpers::WH_BinaryTree_Normal($model_tree);

        return $this->render('tree/index', [
            'model' => $model,
            'items' => $items
        ]);
    }


    /**
     * ONE_AP_SVOD. Получить статистику по одному АП
     * =
     * @param $id
     * @param $date_str
     * @param $print
     * @return string
     * @throws \Exception
     */
    public function actionOne_ap_svod($id, $date_str, $print)
    {
        // set_time_limit(500);
        //ini_set('session.cookie_lifetime', 10000);// на моем не работает
        //ddd(1111);

        $id = Yii::$app->request->get('id');
        $date_str = Yii::$app->request->get('date_str');
        $print = Yii::$app->request->get('print');

        // Виксируем моментальную дату и время
        if (!empty($date_str)) {
            $last_time = strtotime($date_str);
        } else {
            $last_time = strtotime('last day of last month  23:59:59');
        }

        // * Получить Список ВСЕХ элементов в группе
        $array_ids = Sprwhelement::Array_id_parent_id((int)$id);
        ///   0 => 7188
        //    1 => 7189

        $array_res = [];
        $model_pillow = [];

        // 5028
        //        $model_pillow = $this->actionOne_pe_svod(5028, $last_time, '1');
        //        $array_res = self::summaryArraysPEtoArrayAP($array_res, $model_pillow['array_tk_amort']);
        //        ddd($array_res);

//                $model_pillow = $this->actionOne_pe_svod(4994, $last_time, '1');
//                ddd($model_pillow);
//
//                $array_res = self::summaryArraysPEtoArrayAP($array_res, $model_pillow['array_tk_amort']);
//                ddd($array_res);


//        $time = strtotime('now');
        ///
        ///
        foreach ($array_ids as $key => $item_id) {
            $model_pillow[$key] = $this->actionOne_pe_svod($item_id, $last_time, '1');
            ////
            if (isset($model_pillow[$key]['array_tk_amort'])) {
                $array_res = self::summaryArraysPEtoArrayAP($array_res, $model_pillow[$key]['array_tk_amort']);

            }
        }
//        ddd($model_pillow);
        unset($model_pillow);


        //////
        $xx = 0;
        $array_itogo = [];
        foreach ($array_res as $key_group => $item_group) {
            foreach ($item_group as $key_elem => $item_elem) {

                $array_itogo[$xx]['wh_tk_amort'] = $key_group;
                $array_itogo[$xx]['wh_tk_element'] = $key_elem;
                $array_itogo[$xx]['wh_tk_element_name'] = 'Fastener for CVB24 (Крепление для CVB24)';
                $array_itogo[$xx]['ed_izmer'] = 1;
                $array_itogo[$xx]['ed_izmer_num'] = $array_res[$key_group][$key_elem];
                $array_itogo[$xx]['bar_code'] = '';
                $array_itogo[$xx]['intelligent'] = 0;
                $array_itogo[$xx]['wh_tk_amort_name'] = 'Оборудование АСУОП';
                $array_itogo[$xx]['ed_izmer_name'] = 'шт';
                $xx++;

            }
        }
        unset($array_res);

        /// Содать Форму новой накладной. СТАТИСТИКА
        $model = new Sklad();
        $model->scenario = Sklad::SCENARIO_NEW_MODEL_FOR_STAT;

        $model->sklad_vid_oper = 1;
        $model->sklad_vid_oper_name = 'Статистика сводная';
        $model->wh_home_number = $id;
        $model->wh_debet_top = $id;
        $model->wh_debet_name = Sprwhtop::Name_from_id($id);
        $model->array_tk_amort = $array_itogo;
        unset($array_itogo);

        /// * Подготовка массива НАКЛАДНОЙ для быстрого использования. Ускорение работы накладной.
        //     * Добавляем Текстовые поля в расшифровку названий.
        $model->array_tk_amort = $this->actionArrayFullRepresent_for_sklad($model->array_tk_amort);

        //ddd($model->array_tk_amort);
        $counter_things = $this->actionCount_things($model->array_tk_amort);
        ///ddd($counter_things);

        //
        $last_time_str = Date('d.m.Y', $last_time);


//        $time=strtotime('now')-$time;
//        ddd($time);


        /// На печать. Возвращаем модель. Процедура имеет второй выход тут
        if (isset($print) && $print == '1') {
            return $model;            //ddd($print);
        }


        //
        return $this->render('tree/view_itog', [
            'model' => $model,
            'id' => $id,
            'counter_things' => $counter_things,
            'wh_name' => $model->wh_debet_name,
            'last_time_str' => $last_time_str,
            'last_time' => $last_time,
        ]);

    }


    /**
     * ONE PE  SVOD
     * =
     * @param $id_pe
     * @param $date_str
     * @param $print
     * @return array|string
     */
    public function actionOne_pe_svod($id_pe, $date_str, $print = 0)
    {

//        dd($id_pe);
//        dd($date_str);
//        dd($print);
//        ddd(1111);

        $last_time = strtotime($date_str);

        // Виксируем моментальную дату и время
        if (empty($last_time)) {
            $last_time = strtotime('now');
        }

        // Invenory
        $model_pillow = $this->actionRead_pillow_one((int)$id_pe, $last_time);
//                ddd($model_pillow);


        ///
        if (empty($model_pillow)) {
            return $id_pe . ' Нет начальных остатков. Нет СТОЛБОВЫХ.';
        }

        //
        if (!isset($model_pillow->dt_create_timestamp)) {
            return 'model->dt_create_timestamp';
        }

        //
        $first_time = $model_pillow->dt_create_timestamp;

        /**
         *   ПРИХОД / РАСХОД    * Полный Массив
         */
        //$arrayPrihodRashod = Inventory_wh_generatorController::ArrayPrihodRashod_v2(

        //        $count = Inventory_wh_generatorController::ArrayPrihodRashod_count(
        //            $id_pe,
        //            (int)$first_time,
        //            (int)$last_time
        //
        //        );
        //        ddd($count);

        //        $arrayPrihod = Inventory_wh_generatorController::ArrayPrihodRashod_ver4(


        ///
        $arrayPrihodRashod = Inventory_wh_generatorController::ArrayPrihodRashod_v3(
            $id_pe,
            (int)$first_time,
            (int)$last_time
        );


//                ddd($model_pillow);
//                ddd($arrayPrihodRashod);


        /**
         * WH.  * Главная ЛОГИКА. WH.
         * Получает два массива:
         * 1.Первый - Остатки на начало.
         * 2.Второй - Массив Приход-расход.
         * На выходе - полный массив ИТОГО.
         */


        /// 1.
        /// WH !!!!!
        ///
        $array_inventary = Stat_balans_wh_Controller::Math_summary(
            $model_pillow->array_tk_amort,
            $arrayPrihodRashod
        );


//                ddd($array_inventary);



        ///////////////////////
        // Очистка от нулевых остатков
        $array_inventary = $this->actionClear_zerro($array_inventary);
//        ddd($last_time);


        /// * Подготовка массива НАКЛАДНОЙ для быстрого использования. Ускорение работы накладной.
        //     * Добавляем Текстовые поля в расшифровку названий.
        $model_pillow->array_tk_amort = $this->actionArrayFullRepresent_for_sklad($array_inventary);

//        ddd($last_time);

        //Количество
        $counter_things = $this->actionCount_things($model_pillow->array_tk_amort);

        //Дата
        $last_time_str = Date('d.m.Y', $last_time);

        //        $array = [];
        //        foreach ($model_pillow->array_tk_amort as $key => $item) {
        //            $array[$key] = $item;
        //            $array[$key]['ed_izmer_num'] = $item['itog'];
        //        }
        //        $model_pillow->array_tk_amort = $array; //!!!

        //        ddd($model_pillow);


//        ddd($last_time);


        //////////////
        ///  На печать.
        ///  Возвращаем модель. Процедура имеет второй выход тут
        ///
        if (isset($print) && $print == '1') {

//            ddd($model_pillow);
//            ddd(7777);

            return ArrayHelper::toArray($model_pillow);
        }


        //
        $wh_name = Sprwhelement::Name_from_id($id_pe);

//        dd(date('d.m.Y H:i:s', 1612744248)); ///1612744248  // 1612744248
//        ddd($last_time);


        return $this->render('tree/view_itog_pe_one', [
            'model' => $model_pillow,
            'counter_things' => $counter_things,
            'wh_name' => $wh_name,
            'last_time_str' => $last_time_str,
            'last_time' => $last_time,
            'id' => $id_pe,
        ]);
    }


    /**
     * PRINT PE ONE. Принт только одного PE
     * =
     * INDEX --TREE--
     */
    public function actionTree_print_pe_one()
    {
        $id = Yii::$app->request->get('id'); // '5004' id
        $last_date = Yii::$app->request->get('date'); //'1605204000'
        $last_date_str = date('d.m.Y H:i:s', $last_date); // !!!!!!!!!!!

        //ddd($last_date); //1612736421  //1612759821
        //ddd($last_date); //1612736421  //1612759821


        //ddd(date('d.m.Y H:i:s',1612759821));
        //ddd(date('d.m.Y H:i:s',$last_date));


        //        $para_print = Yii::$app->request->get('print'); //'1'

        //        ddd($para_print);
        //        ddd($last_date);
        //        ddd($id);

        //
        $name_str = Sprwhtop::Name_from_id($id);
        //ddd($array_full);

//        ddd($last_date);

        $array_PE = [];
        ///
        $array_PE[] = (array)$this->actionOne_pe_svod($id, $last_date_str, 1);

//        ddd($array_PE);
//        ddd(date('d.m.Y H:i:s', $last_date));


        ///
        ///   Суммв ИТОГО
        ///* Суммирование в ИТОГОВЫЙ СУМ-СУМ для СВОД-СВОДОВ
        ///
        $array_itogo = self::summaryItogo($array_PE[0]['array_tk_amort']);


        //
        $this->render('print/print_excel_pe_one', [
            'array_PE' => $array_PE,
            'name_str' => $name_str,
            'last_date' => $last_date,
            'array_itogo' => $array_itogo,
        ]);
    }


    /**
     * Print AP. Вывод АП в EXCEL
     * =
     * INDEX --TREE--
     */
    public function actionTree_print_ap()
    {
        $id = Yii::$app->request->get('id'); // '14'
        $last_date = Yii::$app->request->get('date'); //'1605204000'

        //
        $model_AP = $this->actionOne_ap_svod($id, $last_date, '1');
        // ddd($model_AP);

        //
        $this->renderAjax(
            'print/print_excel_ap', [
            'model' => $model_AP,
        ]);
    }

    /**
     * Print AP. Вывод АП в EXCEL
     * =
     * @return string
     */
    public function actionPrint_ap()
    {
        $str_json = Yii::$app->request->post('str_json');
        $wh_name = Yii::$app->request->post('whname');

        $arr = json_decode($str_json);

        foreach ($arr as $item) {
            $arr_rez[$item[0]][$item[1]] = $item[2];
        }
        //ddd($arr_rez);

        ///
        //$filename = $wh_name . '__' . strtotime('now') . '.xls';
        $filename = strtotime('now') . '.xls';
        $filename = preg_replace('/[ ]/u', '_', $filename);
        $file = $_SERVER['DOCUMENT_ROOT'] . '/assets/reports/' . $filename;


        $this->render(
            'print_js/print_excel_ap', [
            'arr' => $arr_rez,
            'filename' => $filename,
            'file' => $file,
            'wh_name' => $wh_name,
        ]);

        return $filename;
//        return $this->Exc($arr_rez);
    }


    /**
     * @return mixed
     */
    public function actionOpen_xls()
    {
        $file_name = Yii::$app->request->get('str');

        //отключить профайлеры
        //        $this->disableProfilers();
        //        Yii::app()->request->sendFile(basename($file), file_get_contents($file));


        $filename = $_SERVER['DOCUMENT_ROOT'] . '/assets/reports/' . $file_name;

        $glob_string = realpath($_SERVER['DOCUMENT_ROOT'] . '/assets/reports/') . DIRECTORY_SEPARATOR . $file_name;

        //   header('Content-Type: application/excel');
        header('Content-type: application/vnd.excel');
        header('Content-Disposition: attachment; filename=' . basename($filename) . '');
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile($glob_string);

        return;
    }

    /**
     * @param $arr
     * @return mixed
     * @throws \PHPExcel_Exception
     */
    public function Exc($arr)
    {
        //error_reporting(0);

        /** Include PHPExcel */
        //require_once yii::getAlias('@path_excel') . '/PHPExcel.php';


        require_once yii::getAlias('@path_excel_2') . '/../PHPExcel.php';
        require_once yii::getAlias('@path_excel_2') . '/Writer/Excel2007.php';
        require_once yii::getAlias('@path_excel_2') . '/IOFactory.php';


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


        $activeSheet->setCellValue('A1', 'Сводная ведомость АП ');
//        $activeSheet->setCellValue('A2', $model->wh_debet_name);


//$activeSheet->setCellValue('G1', '1');

//$activeSheet->getStyle('A1:J2')->getAlignment()->setWrapText(true);
        $activeSheet->getRowDimension('A1:E2')->setRowHeight(50);

        $activeSheet->mergeCells('A1:E1');
        $activeSheet->mergeCells('A2:E2');
        $activeSheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $activeSheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $activeSheet->getStyle('A1')->getFont()->setName('Courier New'); ///setName('Courier New');
        $activeSheet->getStyle('A1')->getFont()->setSize(16);

        $activeSheet->getStyle('A2')->getFont()->setName('Arial'); ///setName('Courier New');
        $activeSheet->getStyle('A2')->getFont()->setSize(18);
//$activeSheet->getStyle('A2')->getFont()->setBold(true);


//        $activeSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $activeSheet->getStyle('E1')->getFont()->setBold(true);

//    $activeSheet->setCellValue('A3', 'Отчет');

        $activeSheet->mergeCells('A3:E3');

        $activeSheet->setCellValue('A4', '№');
        $activeSheet->setCellValue('B4', 'Группа');
        $activeSheet->setCellValue('C4', 'Наименование товара');
        $activeSheet->setCellValue('D4', 'Ед.Изм');
        $activeSheet->setCellValue('E4', 'Количество');

        $activeSheet->getRowDimension('A4')->setRowHeight(30);

//$activeSheet->mergeCells('C4:D4');
//$activeSheet->mergeCells('E4:F4');


        $yy = [];
        $num = 0;

//ddd($arr);

        foreach ($arr as $ii) {
            //ddd($ii);
            ///   'wh_tk_amort_name' => 'Оборудование АСУОП'
            //    'wh_tk_element_name' => 'Fastener for CVB24 (Крепление для CVB24)'
            //    'ed_izmer_name' => 'шт'
            //    'ed_izmer_num' => '219'

            $activeSheet->setCellValue('A' . ($num + 5), $num + 1);
            $activeSheet->setCellValue('B' . ($num + 5), $ii['wh_tk_amort_name']);

            $activeSheet->setCellValue('C' . ($num + 5), $ii['wh_tk_element_name']);
            $activeSheet->setCellValue('D' . ($num + 5), $ii['ed_izmer_name']);
            $activeSheet->setCellValue('E' . ($num + 5), $ii['ed_izmer_num']);

            $num++;
        }

        $activeSheet->setCellValue("E" . ($num + 6), "=SUM(E5:E" . ($num + 5) . ")");

        $activeSheet->getStyle("E" . ($num + 6))->getFont()->setName('Malgun Gothic');  /// 'Candara');
        $activeSheet->getStyle("E" . ($num + 6))->getFont()->setBold(true);
        $activeSheet->getStyle("E" . ($num + 6))->getFont()->setSize(11);

        $activeSheet->setCellValue("D" . ($num + 6), 'Итого:');
//        $activeSheet->getStyle("D" . ($num + 6))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


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


//Сетка таблицы
        $activeSheet->setSharedStyle($sharedStyle2, "A4:E" . ($num + 4));


        $activeSheet->getStyle('A3')->getFont()->setBold(true);

        $activeSheet->getStyle('A4:E4')->getFont()->setName('Malgun Gothic');  /// 'Candara');
        $activeSheet->getStyle('A4:E4')->getFont()->setBold(true);
        $activeSheet->getStyle('A4:E4')->getFont()->setSize(11);


//$activeSheet->getStyle('A'.($num+4).':J'.($num+4))->getFont()->setBold(true);


        $activeSheet->getStyle('A4:E4')->getAlignment()->setHorizontal('center');
        $activeSheet->getStyle('A4:A' . ($num + 4))->getAlignment()->setHorizontal('center');
        $activeSheet->getStyle('D4:E' . ($num + 7))->getAlignment()->setHorizontal('center');


//getAlignment()->setHorizontal('right');
        $activeSheet->getRowDimension('B5:B' . ($num + 4))->setRowHeight(20);

        $activeSheet->getColumnDimension('A')->setWidth(6);
        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setWidth(12);
        $activeSheet->getColumnDimension('E')->setWidth(18);


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

// Голова таблицы
        $activeSheet->getStyle('E5:E' . ($num + 4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $activeSheet->getStyle('E5:E' . ($num + 4))->getAlignment()->setHorizontal('center');

// Тело таблицы
        $activeSheet->getStyle('A4:E4')->applyFromArray($styleThinBlackBorderOutline);
        $activeSheet->getStyle('A5:E' . ($num + 4))->applyFromArray($styleThinBlackBorderOutline);

//Перенос текста по строкам внутри ячейки
        $activeSheet->getStyle('C5:E' . ($num + 4))->getAlignment()->setWrapText(true);


        $activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE); /*ORIENTATION_PORTRAIT*/
        $activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);


/////////////////
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);


        $filename = 'отчет.xlsx';
        $filename = 'svod_' . date('d_m_Y__H-i-s') . '.xls';

        $file = $_SERVER['DOCUMENT_ROOT'] . '/assets/reports/' . $filename;
        // @path_reports

        //$file = yii::getAlias('@path_reports') .'/'. $filename;

        $objWriter->save($file);

//        if (ob_get_level()) {
//            ob_end_clean();
//        }
//        ob_get_contents();
//        header("Content-Type: application/vnd.ms-excel; charset=utf-8"); # Important
//        header("Content-Type: application/force-download");
//        header("Content-Type: application/octet-stream");
//        header("Content-Type: application/download");
////header('Content-type: application/vnd.excel');
//        header('Content-Disposition: attachment; filename=' . basename($filename) . '');
//        header("Pragma: no-cache");
//        header("Expires: 0");
//        $objWriter->save('php://output');
//        ob_flush();


        return $file;
    }

    /**
     * Print PE
     * =
     * INDEX --TREE--
     */
    public function actionTree_print_pe()
    {
        $id = Yii::$app->request->get('id'); // '14' GROUP
        $last_date = Yii::$app->request->get('date'); //'1605204000'

        // * Получить Список ВСЕХ элементов в группе
        $array_ids = Sprwhelement::Array_id_parent_id((int)$id);

        //
        $name_str = Sprwhtop::Name_from_id($id);


        //        $array_PE= $this->actionOne_pe_svod(5028, $last_date, 1);
        //        ddd($array_PE);

        ///
        $array_PE = [];
        foreach ($array_ids as $key => $item_id) {
            $array_PE[$key] = (array)$this->actionOne_pe_svod($item_id, $last_date, 1);
        }

        //
        $this->render('print/print_excel_pe_all', [
            'array_PE' => $array_PE,
            'name_str' => $name_str,
            'last_date' => $last_date,
        ]);
    }


    /**
     * PRINT PE ONE. Принт только одного PE
     * =
     * INDEX --TREE--
     *
     * @param $id
     * @param $date
     */
    public function actionTree_print_pe_one_id_date($id, $date)
    {
        $id = Yii::$app->request->get('id'); // '5004' id
        $last_date = Yii::$app->request->get('date'); //'1605204000'

        //
        $name_str = Sprwhelement::Name_from_id($id); // 'АСУОП'

        ///
        $array_PE = [];
        $array_PE[] = (array)$this->actionOne_pe_svod($id, $last_date, 1);

        ddd($array_PE);

        //
        $this->render('print/print_excel_pe_one', [
            'array_PE' => $array_PE,
            'name_str' => $name_str,
            'last_date' => $last_date,
        ]);
    }


    /**
     * Суммирование в ИТОГОВЫЙ СУМ-СУМ для СВОД-СВОДОВ
     * =
     * @param $array
     * @return array
     */
    static function summaryItogo($array)
    {
        $array_res = [];

        foreach ($array as $item) {
            ///
            if (isset($array_res[$item['wh_tk_element']])) {
                $array_res[$item['wh_tk_element']] = [
                    'id' => $item['wh_tk_element'],
                    's' => $array_res[$item['wh_tk_element']]['s'] + $item['ed_izmer_num'],
                    'name_gr' => $item['wh_tk_amort_name'],
                    'name_el' => $item['wh_tk_element_name'],
                ];
            } else {
                $array_res[$item['wh_tk_element']] = [
                    'id' => $item['wh_tk_element'],
                    's' => $item['ed_izmer_num'],
                    'name_gr' => $item['wh_tk_amort_name'],
                    'name_el' => $item['wh_tk_element_name'],
                ];
            }
        }
        return $array_res;
    }

    /**
     * Суммирование всех записаей от каждого ПЕ в один АП
     * =
     * @param $array_res
     * @param $array_incoming
     * @return
     */
    static function summaryArraysPEtoArrayAP($array_res, $array_incoming)
    {
        foreach ($array_incoming as $item) {
            if (
                isset($array_res[$item['wh_tk_amort']][$item['wh_tk_element']]) &&
                (int)$array_res[$item['wh_tk_amort']][$item['wh_tk_element']] > 0
            ) {
                $array_res[$item['wh_tk_amort']][$item['wh_tk_element']] =
                    $array_res[$item['wh_tk_amort']][$item['wh_tk_element']] + $item['itog'];
            } else {
                $array_res[$item['wh_tk_amort']][$item['wh_tk_element']] = $item['itog'];
            }
        }
//        ddd($array_incoming);
//        ddd($array_res);

        return $array_res;
    }


    /**
     * Подготовка массива НАКЛАДНОЙ для быстрого использования. Ускорение работы накладной.
     * Добавляем Текстовые поля в расшифровку названий.
     * =
     * @param $array_asuop
     * @return string
     */
    public function actionArrayFullRepresent_for_sklad($array_asuop)
    {
        //GLOBAM
        $array_spr_globam = ArrayHelper::map(
            Spr_globam::find()
//                ->where(['!=', 'delete', (int)1])
                ->orderBy('name')->all(),
            'id', 'name');

        //GLOBAM_ELEMENT
        $array_spr_globam_element = ArrayHelper::map(
            Spr_globam_element::find()->orderBy('name')->all(),
            'id', 'name');

        //THINGS
        $array_things = ArrayHelper::map(
            Spr_things::find()->all(),
            'id', 'name');

//        ddd($array_asuop);

        ///
        foreach ($array_asuop as $key => $item) {
            //
            if (!empty($item['wh_tk_amort'])) {
                $array_asuop[$key]['wh_tk_amort_name'] = $array_spr_globam[(int)$item['wh_tk_amort']];
            }
            //
            if (!empty($item['wh_tk_element'])) {
                $array_asuop[$key]['wh_tk_element_name'] = $array_spr_globam_element[$item['wh_tk_element']];
            }
            //
            $array_asuop[$key]['name_ed_izmer'] = $array_things[$item['ed_izmer']];
        }

        return $array_asuop;
    }


    /**
     * Очистка от нулевых остатков
     * =
     * @param $array
     * @return array
     */
    public function actionClear_zerro($array)
    {
        $array_rez = [];
        foreach ($array as $item) {
            if ((int)$item['ed_izmer_num'] > 0) {
//            if ((int)$item['itog'] > 0) {
                $array_rez[] = $item;
            }
        }
        return $array_rez;
    }

    /**
     * Подсчет количества товара
     * =
     * @param $array_for_count
     * @return string
     */
    public function actionCount_things($array_for_count)
    {
        $xx = 0;
        foreach ($array_for_count as $item) {
            if (isset($item['itog'])) {
                $xx = $xx + $item['itog'];
            } else {
                $xx = $xx + $item['ed_izmer_num'];
            }
        }
        return $xx;
    }


    /**
     * DEMO
     * =
     * @param $id
     * @param $parent_id
     * @return string
     */
    public function actionOne_pe_demo($id, $parent_id)
    {
        return 'OK id=' . $id . ' parent_id=' . $parent_id;
    }


    /**
     * Читатель СТОЛБОВОЙ НАКЛАДНОЙ
     * =
     * @param $cs_id
     * @param $last_time
     * @return array|int|\yii\mongodb\ActiveRecord|null
     */
    public static function actionRead_pillow_one($cs_id, $last_time)
    {

        // array_tk_amort
        ///
        $array_items_inventory = Sklad_wh_invent::find()
            ->select([
                'id',
                'dt_create_timestamp',
                'dt_create_day',
                'dt_create',
                'sklad_vid_oper',
                'sklad_vid_oper_name',
                'wh_home_number',
                'wh_destination',
                'wh_destination_element',
                'wh_destination_name',
                'wh_destination_element_name',
                'user_id',
                'user_name',
                'count_str',
                'tx',
                'calc_errors',
                'empty_cs',
                'dt_update',
                'dt_update_timestamp',

                'array_tk_amort'
            ])
            ->where(
                ['AND',
                    ['<=', 'dt_create_timestamp', (int)$last_time],
                    ['OR',
                        ['wh_destination_element' => (int)$cs_id],
                        ['wh_destination_element' => (string)$cs_id]
                    ]
                ])
            ->orderBy('dt_create_timestamp DESC')
            //->asArray()
            ->one();

        return $array_items_inventory;

    }


    /**
     * VIEW
     * =
     * @param $model
     * @return string
     */
    public function actionOne_summary_view($model)
    {
        return $this->render('view_itog', [
            'model' => $model,
        ]);
    }

    /**
     * INDEX
     * @return string
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;
        ddd($para);
        $model = new Sprwhelement();

        $searchModel = new postsprwhelement();
        $dataProvider = $searchModel->search_with($para);


        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */

        $dataProvider->setSort(
            [
                'attributes' => [
                    'id',
                    'parent_id',
                    'name',
                    'nomer_borta',
                    'nomer_gos_registr',
                    'nomer_vin',
                    'tx',
                    'delete_sign',
                    'final_destination',
                    'deactive',
                    'f_first_bort',
                ],
                'defaultOrder' => ['parent_id' => SORT_ASC, 'id' => SORT_ASC]
            ]
        );

        //  ddd($dataProvider->getModels());

        //Запомнить РЕФЕР
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);

        return $this->render(
            'index', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }


    /**
     * ВОЗВРАТ ПО РЕФЕРАЛУ
     */
    public function actionReturn_to_refer()
    {
        //Возврат по рефералу REFER
        return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id, Yii::$app->request->url));
    }


}
