<?php

namespace frontend\controllers;

use frontend\models\post_past_inventory;
use frontend\models\Sklad_past_inventory;
use frontend\models\Sklad;
use frontend\models\Spr_glob;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam;
use frontend\models\Spr_globam_element;
use frontend\models\Spr_things;
use frontend\models\Sprwhelement;
use frontend\components\MyHelpers;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;
use yii\base\ExitException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;


class Past_inventoryController extends Controller
{

    /**
     * $session
     */
    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $session->open();

        if (!isset(Yii::$app->getUser()->identity)) {
            /// Быстрая переадресация
            throw new HttpException(411, 'Необходима авторизация', 2);
        }

    }

    /**
     * Если ГРУППА ниже нормы STATISTIC, то нет доступа
     *
     * @param $event
     * @return bool
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function beforeAction($event)
    {
        if ((
            Yii::$app->getUser()->identity->group_id < 40 ||
            Yii::$app->getUser()->identity->group_id > 100)) {

            throw new NotFoundHttpException('Доступ только STATISTIC-группе');
        }
        return parent::beforeAction($event);
    }


    /**
     * Index
     * =
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        // if (Yii::$app->getUser()->identity->group_id < 60) {
        //     throw new NotFoundHttpException('Доступ только cотрудникам бухгалтерии');
        // }

        $para = Yii::$app->request->queryParams;

        $searchModel = new post_past_inventory();
        $dataProvider = $searchModel->search($para);

        //ddd($dataProvider->getModels());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Для Любого Кладовщика. ПРОМЕЖУТОЧНЫЕ Остатки
     *=
     * actionIndex_by_id
     * -
     * para: past_inventory/index_by_id?id=86
     *
     * @return string
     * @throws UnauthorizedHttpException
     */
    public function actionIndex_by_id()
    {
        $para = Yii::$app->request->queryParams;
        //ddd($para);

        if (!isset($para['id']) || empty($para['id']))
            throw new UnauthorizedHttpException('actionIndex_by_id. ID = NONE ');

//        $searchModel = new post_past_inventory();

        /** @noinspection PhpUndefinedClassInspection */
        $query = Sklad_past_inventory::find()->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['id' => SORT_DESC],]);

//        if( isset($para['id'] ))
//            $query->andFilterWhere(['=', 'wh_destination_element', $para['id'] ]);


        return $this->render('index_by_id', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создаем новую  накладную  (Инвентаризация)
     * -
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate_new()
    {
        //        $sklad = Sklad::getSkladIdActive();
        //        if (!isset($sklad) || empty($sklad))
        //            throw new UnauthorizedHttpException('Sklad=0');


        $max_value = Sklad_past_inventory::find()->max('id');
        $max_value++;

        $model = new Sklad_past_inventory();

        if (!is_object($model))
            throw new NotFoundHttpException('Склад ИНВЕНТАРИЗАЦИИ не работает');


        ////////
        $model->id = (integer)$max_value;

        $model->sklad_vid_oper = 1; // INVENTORY

        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
        /// То самое преобразование ПОЛЯ Милисукунд
        //$model->setDtCreateText( "NOW" );   ///Милисукунд


        //////////////
        if ($model->load(Yii::$app->request->post())) {
            //            ddd($model);

            $model->wh_home_number = (integer)$model->wh_destination_element;
            $model->sklad_vid_oper = 1; // INVENTORY

            $model->sklad_vid_oper = (integer)$model->sklad_vid_oper; // Приводим к числу
            if ($model['sklad_vid_oper'] == 2)
                $model['sklad_vid_oper_name'] = 'Приходная накладная';

            if ($model['sklad_vid_oper'] == 3)
                $model['sklad_vid_oper_name'] = 'Расходная накладная';


            ddd($model);

            if ($model->save(true)) {

                return $this->render('_form_create',
                    [
                        'new_doc' => $model,
                        //'model' => $model,
                        'sklad' => $sklad,
                        //'items_auto' =>    ['нет автобусов'],
                        'alert_mess' => 'Сохранение.Успешно.',
                    ]);
            } else
                ddd($model->errors);
        }


        return $this->render('_form_create', [
            'new_doc' => $model,
            'sklad' => $sklad,
            //  'alert_mess' => 'Сохранение. Попытка',

        ]);
    }

    /**
     * Редактирование Накладной
     * -
     * Url::to(['/sklad/update
     * ? id=_id
     * & otbor=86
     *
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     * @throws ExitException
     */
    public function actionUpdate($id)
    {
        if (!isset($id) || empty($id))
            throw new UnauthorizedHttpException('$id///  Не подключен.  Sklad UPDATE');

        $para_post = Yii::$app->request->post();

        ////////
        $model = Sklad_past_inventory::findModel($id);  // it is =  _id
        ///
//                ddd($model);


        /// add_button_am
        ///  КНОПКА - ЗАЛИВКА КОПИПАСТ - АСУОП
        ///
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button_am') {


            //  Получить справочник
            $spr_elem = Spr_globam_element::name_plus_id();
            $spr_elem_parent = Spr_globam_element::id_to_parent();
            $spr_elem_intelligent = Spr_globam_element::id_to_intelligent();


            //$spr_elem_edizm = Spr_globam_element::id_to_ed_izm(); ///ВСЕГДА-ШТУКИ!!!


            /////////
            $array = explode("\r\n", trim($para_post['Sklad_inventory']['add_text_to_inventory_am']));

            //ddd($para_post);
            //ddd($array);


            // Приводим к нормальному массиву
            foreach ($array as $item) {
                $array_sign[] = array_map('trim', explode("\t", $item));   /// "TAB"
            }

            foreach ($array_sign as $key => $item2) {
                $key_key = array_search($item2[0], $spr_elem);   // $item2[1];/// штуки

                if (isset($key_key)) {
                    //ddd($key_key);

//                    $array_reason[]  =  [
//                        $spr_elem_parent[$key_key],
//                        $key_key,
//                        $item2[0],
//                        $item2[1]
//                    ];

                    $array_tk[] = [
                        'wh_tk_amort' => $spr_elem_parent[$key_key],
                        'wh_tk_element' => $key_key,
                        'ed_izmer' => 1, // Всегда -ШТУКИ
                        'ed_izmer_num' => (isset($array_sign[$key][1]) ? $array_sign[$key][1] : 0),
                        'bar_code' => '123',
                        'intelligent' => $spr_elem_intelligent[$key_key]
                    ];
                }
            }


            $model->array_tk_amort = array_merge($model->array_tk_amort, $array_tk);
            //   ddd($array_reason);

        }


        ///
        ///  КНОПКА - ЗАЛИВКА КОПИПАСТ - СПИСАНИЕ (без АМ)
        ///
        ///add_button
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'add_button') {

            //  Получить справочник
            $spr_elem = Spr_glob_element::name_plus_id();
            $spr_elem_parent = Spr_glob_element::id_to_parent();
            $spr_elem_edizm = Spr_glob_element::id_to_ed_izm();


            /////////
            $array = explode("\r\n", trim($para_post['Sklad_inventory']['add_text_to_inventory']));


            // Приводим к нормальному массиву
            foreach ($array as $item) {
                $array_sign[] = array_map('trim', explode("\t", $item));   /// "TAB"
            }

            foreach ($array_sign as $key => $item2) {
                $key_key = array_search($item2[0], $spr_elem);   // $item2[1];/// штуки

                if (isset($key_key)) {
                    // $array_reason[]  =  [  $spr_elem_parent[$key_key], $key_key, $item2[0], $item2[1] ];

                    $array_tk[] = [
                        'wh_tk' => $spr_elem_parent[$key_key],
                        'wh_tk_element' => $key_key,
                        'ed_izmer' => $spr_elem_edizm[$key_key],
                        'ed_izmer_num' => (isset($array_sign[$key][1]) ? floatval(str_replace(",", ".", $array_sign[$key][1])) : 0),
                    ];
                }
            }

            //ddd( floatval( str_replace(",", ".", $array_sign[$key][1] )  ) );

            $model->array_tk = array_merge($model->array_tk, $array_tk);
        }


        //// Подсчет количества строк в массивах
        /// for VIEW
        ///

        $erase_array[0] = count($model->array_tk_amort);
        $erase_array[1] = count($model->array_tk);
        $erase_array[2] = count($model->array_casual);

        //ddd($erase_array );


        ///
        ///  КНОПКА УДАЛЕНИЕ СТРОК в массивах
        ///
        /// $para_post['contact-button']=='erase_aray'
        ///
        if (isset($para_post['contact-button']) && $para_post['contact-button'] == 'erase_button') {
            //ddd($para_post);

            if (is_array($model['array_tk_amort'])) {
                //////////array_tk_amort
                ///
                $start = (int)$para_post['Sklad_inventory']['erase_array'][0][0];
                $stop = (int)$para_post['Sklad_inventory']['erase_array'][0][1] - $start;

                $array = (array)$model['array_tk_amort'];
                array_splice($array, $start, $stop);
                $model['array_tk_amort'] = $array;

            }

            if (is_array($model['array_tk'])) {
                //////////array_tk
                ///
                $start = (int)$para_post['Sklad_inventory']['erase_array'][1][0];
                $stop = (int)$para_post['Sklad_inventory']['erase_array'][1][1] - $start;

                $array = (array)$model['array_tk'];
                array_splice($array, $start, $stop);

                $model['array_tk'] = $array;
            }

            if (is_array($model['array_casual'])) {
                //////////array_casual
                ///
                $start = (int)$para_post['Sklad_inventory']['erase_array'][2][0];
                $stop = (int)$para_post['Sklad_inventory']['erase_array'][2][1] - $start;

                $array = (array)$model['array_casual'];
                array_splice($array, $start, $stop);
                $model['array_casual'] = $array;
            }

            // ddd($model);

        }


        $xx1 = $xx2 = $xx3 = 0;

        ///||||||||||||||||||||||||||||||||||
        /// Подсчет СТРОК Всего
        ///
        if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort'])
            && is_array($model['array_tk_amort']))

            $xx1 = count($model['array_tk_amort']);

        if (isset($model['array_tk']) && !empty($model['array_tk'])
            && is_array($model['array_tk']))

            $xx2 = count($model['array_tk']);

        if (isset($model['array_casual']) && !empty($model['array_casual'])
            && is_array($model['array_casual']))

            $xx3 = count($model['array_casual']);

        $model['array_count_all'] = (int)$xx1 + $xx2 + $xx3;

//        ddd($model);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk'] = $this->getTkNames($model['array_tk']);

//        ddd($model);


        $spr_things = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');


        ///
        /// //////// ПРЕД СОХРАНЕНИЕМ
        ///
        if (!isset($para_post['contact-button']) || empty($para_post['contact-button']))
            if ($model->load(Yii::$app->request->post())) {
                //  ddd($model);

                //$model->wh_home_number=(integer)$sklad;
                $model->wh_home_number = (int)$model->wh_destination_element;
                //$model->sklad_vid_oper=1; // INVENTORY

                $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));

//                $model->update_user_id      = Yii::$app->request->getUserIP();
//                $model->update_user_name    = Yii::$app->user->identity->username;
//                $model->update_user_id      = Yii::$app->user->identity->id;
//                $model->update_user_group_id= Yii::$app->user->identity->group_id ;


                ////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
                // $model->array_tk_amort  = Sklad::setArraySort1( $model->array_tk_amort );
                ///  ТАБ 1
                $model->array_tk_amort = Sklad::setArrayClear($model->array_tk_amort);
                ///  ТАБ 2
                $model->array_tk = Sklad::setArraySort2($model->array_tk);


                ////  Приводим ключи в прядок! УСПОКАИВАЕМ ОЧЕРЕДЬ
                $model->array_tk_amort = Sklad::setArrayToNormal($model->array_tk_amort);
                $model->array_tk = Sklad::setArrayToNormal($model->array_tk);
                $model->array_casual = Sklad::setArrayToNormal($model->array_casual);

                ///////
                /// ПРИЕМНИК
                $xx2 = Sprwhelement::findFullArray($model->wh_destination_element);

                $model->wh_destination_name = $xx2['top']['name'];
                $model->wh_destination_element_name = $xx2['child']['name'];
                /// То самое преобразование ПОЛЯ Милисукунд
                $model->dt_start_timestamp = $model->DateToMinute($model['dt_create']);


                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                //                $model['array_tk_amort'] = $this->getTkNames_am( $model['array_tk_amort'] );
                // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
                //                $model['array_tk'] = $this->getTkNames( $model['array_tk'] );

                //            ddd($model);


                if ($model->save(true)) {
                    return $this->render('_form_create',
                        [
                            'new_doc' => $model,
                            'spr_things' => $spr_things,
                            'alert_mess' => 'Сохранение.Успешно.',
                        ]);
                }
            }


//        ddd($model);


        return $this->render('_form_create', [
            'new_doc' => $model,
            'spr_things' => $spr_things,
            'alert_mess' => '',
        ]);

    }

    /**
     * actionRead
     * -
     * @param $id
     * @return string
     * @throws ExitException
     * @throws UnauthorizedHttpException
     */
    public function actionRead($id)
    {
        if (!isset($id) || empty($id))
            throw new UnauthorizedHttpException('$id///  Не подключен.  Sklad UPDATE');

//        $para_post = Yii::$app->request->post();

        ////////
        $model = Sklad_past_inventory::findModel($id);  // it is =  _id
        ///
        //                ddd($model);


        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk_amort'] = $this->getTkNames_am($model['array_tk_amort']);

        // Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
        $model['array_tk'] = $this->getTkNames($model['array_tk']);

        //        ddd($model);


        $spr_things = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');


        return $this->render('_form_by_id', [
            'new_doc' => $model,
            'spr_things' => $spr_things,
            'alert_mess' => '',
        ]);

    }

    /**
     * Распечатка. Выходная Форма.
     * Накладная Резервный ФОНД (ПДФ)
     *-
     * @return bool
     * @throws MpdfException
     * @throws BarcodeException
     * @throws ExitException
     */
    public function actionPdf_form()
    {

        $para = Yii::$app->request->queryParams;

        $model = Sklad_past_inventory::findModelDouble($para['id']);

        //ddd($model);


        ////////////////////
        ///// AMORT!!
        //        $model1 = ArrayHelper::map(Spr_globam::find()
        //            ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(Spr_globam_element::find()
            ->orderBy('id')
            ->all(), 'id', 'name');


        ///// NOT AMORT
        //        $model3 = ArrayHelper::map(Spr_glob::find()
        //            ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(Spr_glob_element::find()
            ->orderBy('id')
            ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');

        ////////////////////

        ///// BAR-CODE
        $str_pos = str_pad($model->id, 10, "0", STR_PAD_LEFT); /// длинная строка с номером генерируется в длинную
        $bar_code_html = MyHelpers::Barcode_HTML('sk' . $model->wh_home_number . '-' . $str_pos);
        ///// BAR-CODE


        //1
        $html_css = $this->getView()->render('/past_inventory/pdf_form/_form_css.php');

        //ddd($model);

        //2
        $html = $this->getView()->render('/past_inventory/pdf_form/_form', [
            //            'bar_code_html' => $bar_code_html,
            'model' => $model,
            //            'model1' => $model1,
            'model2' => $model2,
            //            'model3' => $model3,
            'model4' => $model4,
            'model5' => $model5,
        ]);


        //  Тут можно подсмореть
        //$html = dd($html);

        ///
        ///  mPDF()
        ///

        $mpdf = new mPDF();
        $mpdf->charset_in = 'utf-8';

        $mpdf->SetAuthor('Guidejet TI, 2019');
        $mpdf->SetHeader($bar_code_html);
        $mpdf->WriteHTML($html_css, 1);

        //        $foot_str= '{PAGENO}';

        $foot_str = '


        ';

        //$mpdf->SetFooter($foot_str );
        $mpdf->SetHTMLFooter($foot_str, 'O');


        ///////
        $mpdf->AddPage('', '', '', '', '',
            10, 10, 25, 42, '', 25, '', '', '',
            '', '', '', '', '', '', '');

        //////////


        $mpdf->WriteHTML($html, 2);
        $html = '';

        unset($html);

        $filename = 'PastInventory_' . date('d.m.Y H-i-s') . '.pdf';
        $mpdf->Output($filename, 'I');


        return false;
    }

    /**
     * EXCEL FORM
     * =
     * @return bool
     * @throws ExitException
     */
    public function actionExcel_form()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad_past_inventory::findModelDouble($para['id']);

        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(Spr_globam::find()
            ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(Spr_globam_element::find()
            ->orderBy('id')
            ->all(), 'id', 'name');

        $model_amcc = ArrayHelper::map(Spr_globam_element::find()
            ->all(), 'id', 'cc_id');


        ///// NOT AMORT
        $model3 = ArrayHelper::map(Spr_glob::find()
            ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(Spr_glob_element::find()
            ->orderBy('id')
            ->all(), 'id', 'name');

        $model_cc = ArrayHelper::map(Spr_glob_element::find()
            ->all(), 'id', 'cc_id');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////


//        ddd($model_cc);
//        ddd($model);

        $this->render('print/print_excel', [
            'model' => $model,

            'group_am_name' => $model1,
            'element_am_name' => $model2,
            'list_amcc' => $model_amcc,// 1C-id


            'group_name' => $model3,
            'element_name' => $model4,
            'list_cc' => $model_cc,// 1C-id

            'things' => $model5,
        ]);


        return false;
    }

    /**
     * Инвентаризационная ведомость оборудования
     * -
     * @return bool
     * @throws ExitException
     */
    public function actionExcel_form_inventory()
    {
        $para = Yii::$app->request->queryParams;
        $model = Sklad_past_inventory::findModelDouble($para['id']);

        $model_array_tk = Sklad_past_inventory::sort_array_pk($model['array_tk']);
        //ddd($model_array_tk);


        ////////////////////
        ///// AMORT!!
        $model1 = ArrayHelper::map(Spr_globam::find()
            ->all(), 'id', 'name');

        $model2 = ArrayHelper::map(Spr_globam_element::find()
            ->orderBy('id')
            ->all(), 'id', 'name');


        ///// NOT AMORT
        $model3 = ArrayHelper::map(Spr_glob::find()
            ->all(), 'id', 'name');

        $model4 = ArrayHelper::map(Spr_glob_element::find()
            ->orderBy('id')
            ->all(), 'id', 'name');


        $model5 = ArrayHelper::map(Spr_things::find()->all(), 'id', 'name');
        ////////////////////


        $model_amcc = ArrayHelper::map(Spr_globam_element::find()
            ->all(), 'id', 'cc_id');

        $model_cc = ArrayHelper::map(Spr_glob_element::find()
            ->all(), 'id', 'cc_id');


//        ddd($model);


        $this->render('print/print_excel_inventory', [
            'model' => $model,
            'model_array_tk' => $model_array_tk,

            'group_am_name' => $model1,
            'element_am_name' => $model2,
            'model_amcc' => $model_amcc,

            'group_name' => $model3,
            'element_name' => $model4,
            'model_cc' => $model_cc,

            'things' => $model5,
        ]);


        return false;
    }

    /**
     * @param $id
     * @param string $adres_to_return
     * @return Response
     * @throws ExitException
     * @throws StaleObjectException
     */
    public function actionDelete($id, $adres_to_return = "")
    {
        Sklad_past_inventory::findModel($id)->delete();
        return $this->redirect(['/past_inventory/' . $adres_to_return]);
    }

    //    /**
    //     * id = системный длинный Ид
    //     * -
    //     */
    //    protected function findModel($id)
    //    {
    //        return Sklad_past_inventory::findOne($id);
    //    }


    /**
     * Приводим Массив (AM) В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     *-
     * @param $array_tk
     * @return mixed
     */
    public function getTkNames_am($array_tk)
    {
        $spr_globam_model = ArrayHelper::map(
            Spr_globam::find()->orderBy('name')->all(),
            'id',
            'name');

        $spr_globam_element_model = ArrayHelper::map(
            Spr_globam_element::find()->orderBy('name')->all(),
            'id',
            'name');

        $spr_globam_element_cc = ArrayHelper::map(
            Spr_globam_element::find()
                ->where(['!=', 'cc_id', null])
                ->all(),
            'id',
            'cc_id');

        $spr_elem_intelligent = Spr_globam_element::id_to_intelligent();

        $buff = [];
        if (isset($array_tk) && !empty($array_tk))
            foreach ($array_tk as $key => $item) {

                $buff[$key]['name_wh_tk_amort'] = $spr_globam_model[$item['wh_tk_amort']];
                $buff[$key]['name_wh_tk_element'] = $spr_globam_element_model[$item['wh_tk_element']];

                $buff[$key]['cc_id'] = $spr_globam_element_cc[$item['wh_tk_element']]; //1C-id

                //$buff[$key]['name_ed_izmer']=$spr_things_model[$item['ed_izmer']];

                $buff[$key]['name_ed_izmer'] = 'шт';
                $buff[$key]['ed_izmer'] = '1';


                $buff[$key]['wh_tk_amort'] = $item['wh_tk_amort'];
                $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                $buff[$key]['take_it'] = (isset($item['take_it']) ? $item['take_it'] : 0);
                $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];

                $buff[$key]['prihod_num'] = $item['prihod_num'];
                $buff[$key]['rashod_num'] = $item['rashod_num'];
                $buff[$key]['itog'] = $item['itog'];

                $buff[$key]['bar_code'] = (isset($item['bar_code']) ? $item['bar_code'] : '');
                $buff[$key]['intelligent'] = $spr_elem_intelligent[$item['wh_tk_element']];


            }

        return $buff;
    }

    /**
     * Приводим Массив В читаемый вид
     * С ПОМОЩЬЮ СПРАВОЧНИКОВ
     *-
     * @param $array_tk
     * @return mixed
     */
    public function getTkNames($array_tk)
    {
        $spr_glob_model = ArrayHelper::map(
            Spr_glob::find()->orderBy('name')->all(),
            'id',
            'name');

        $spr_glob_element_model = ArrayHelper::map(
            Spr_glob_element::find()->orderBy('name')->all(),
            'id',
            'name');

        $spr_glob_element_cc = ArrayHelper::map(
            Spr_glob_element::find()
                ->where(['!=', 'cc_id', null])
                ->all(),
            'id',
            'cc_id');

        $spr_things_model = ArrayHelper::map(
            Spr_things::find()->all(), 'id', 'name');


        $buff = [];
        if (isset($array_tk) && !empty($array_tk))
            foreach ($array_tk as $key => $item) {

                $buff[$key]['name_tk'] = isset($spr_glob_model[$item['wh_tk']]) ? $spr_glob_model[$item['wh_tk']] : '';
                $buff[$key]['name_tk_element'] = (isset($spr_glob_element_model[$item['wh_tk_element']]) ? $spr_glob_element_model[$item['wh_tk_element']] : '');

                $buff[$key]['cc_id'] = (isset($spr_glob_element_cc[$item['wh_tk_element']]) ? $spr_glob_element_cc[$item['wh_tk_element']] : ''); // 1C

                $buff[$key]['name_ed_izmer'] = $spr_things_model[$item['ed_izmer']];

                $buff[$key]['wh_tk'] = $item['wh_tk'];
                $buff[$key]['wh_tk_element'] = $item['wh_tk_element'];
                $buff[$key]['ed_izmer'] = $item['ed_izmer'];
                $buff[$key]['take_it'] = (isset($item['take_it']) ? $item['take_it'] : 0);
                $buff[$key]['ed_izmer_num'] = $item['ed_izmer_num'];

                //$buff[$key]['name']=$item['name'];
                $buff[$key]['prihod_num'] = $item['prihod_num'];
                $buff[$key]['rashod_num'] = $item['rashod_num'];
                $buff[$key]['itog'] = $item['itog'];
            }

        //        ddd($array_tk);
        // ddd($buff);

        return $buff;
    }

}
