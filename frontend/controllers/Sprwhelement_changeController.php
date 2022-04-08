<?php

namespace frontend\controllers;

use frontend\models\postsprwhelement_change;
use frontend\models\Sklad;
use frontend\models\Sklad_inventory_cs;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhelement_change;
use frontend\models\Sprwhtop;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

//use function GuzzleHttp\Psr7\str;


class Sprwhelement_changeController extends Controller
{
    /**
     * $session
     */
    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $session->open();
        if (!Yii::$app->getUser()->identity) {
            throw new HttpException(411, 'Необходима авторизация', 2);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET'],
                    'change_gos_bort' => ['GET', 'POST'],
                    'update' => ['POST'],
                    'delete' => ['GET'],
                ],
            ],
        ];

    }


    /**
     * INDEX. Главная страница с главной таблицей
     * =
     * @return string
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;

        //
        $model = new Sprwhelement_change();

        $searchModel = new postsprwhelement_change();
        $dataProvider = $searchModel->search($para);

        $dataProvider->pagination->pageSize = 10;

        /**
         * Настройка параметров сортировки
         * Важно: должна быть выполнена раньше $this->load($params)
         */
        $dataProvider->setSort(
            [
                'attributes' => [
                    'id',
                    'parent_id',
                    'do_timestamp',

                    'n_bort',
                    'n_gos',
                    'old_bort',
                    'old_gos',

                    'user_id',
                    'dt_cr_timestamp',
                    'doc_cr',
                    'doc_num',
                    'tx',

                    'user_name',
                    'user_do_timestamp',

                    'sprwhelement.sprwhtop.name',
                    'sprwhelement.name' => [
                        'asc' => [
                            'parent_id' => SORT_ASC],
                        'desc' => [
                            'parent_id' => SORT_DESC],
                        'default' => SORT_DESC,
                    ],


                ],
                'defaultOrder' => ['id' => SORT_ASC, 'n_bort' => SORT_ASC]
            ]
        );

        //ddd($dataProvider->getModels());

        /**
         * Запомнить РЕФЕР
         */
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);

        ///
        return $this->render(
            'index', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );

    }

    /**
     * Заливка новых ЗАМЕН НОМЕРОВ из EXCEL копипастом
     * -
     * FROM EXCEL!!!
     * =
     */
    public function actionInput_from_excel()
    {
        //
        $model = new Sprwhelement_change();
        //
        $list_whtop = ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');
        $list_whtop_element = ['' => 'Выбрать группу'];


        ///
        if ($model->load(Yii::$app->request->post())) {
            $para_button = Yii::$app->request->post('contact-button');
            ///
            if ($para_button == 'add_from_excel') {
                $err = [];
                $err_top = [];
                $err_bort = [];
//                $err_gos = [];
                $res_spr = [];


                $array_change = Yii::$app->request->post('Sprwhelement_change');
                //

                foreach ($array_change as $str_item) {
                    $str_item = preg_replace('/[\"]/i', '', $str_item);
                    $str_item = preg_replace('/[\r]+/iu', "\r", $str_item);
                    $str_item = preg_replace('/[\n]+/iu', "\n", $str_item);

                    //
                    $array_item = explode("\r\n", $str_item);
                    ///$array_item
                    foreach ($array_item as $one_str) {
                        // \t
                        $array_t = explode("\t", $one_str);

                        //    0 => '01.10.2017'
                        //    1 => 'Каскеленский автопарк'
                        //    2 => '018ABB05'
                        //    3 => '573ZNA05'
                        //    4 => ''
                        //    5 => ''

                        $model = new Sprwhelement_change();
                        $model->scenario = Sprwhelement_change::SCENARIO_FROM_EXCEL;
                        $model->id = Sprwhelement_change::setNext_max_id();
                        $model->do_timestamp = strtotime($array_t[0] . " +1 second ");
                        $model->dt_cr_timestamp = strtotime($array_t[0] . " +1 second ");
                        //

                        if (!isset($array_t[1])) {
                            continue;
                        }

                        //в названии АП
                        $array_wh_top = Sprwhtop::array_from_name((string)$array_t[1]);
                        if (!$array_wh_top) {
                            $err_top[] = $array_t[1];
                        }

                        $id_AP = (int)$array_wh_top['id']; //29


                        //////
                        // ИМЯ НОМЕРА (ГОС ИЛИ БОРТ)
                        // в обратную сторону через НОВЫЙ НОМЕР
                        $name_new = trim($array_t[2]);
                        $name_old = trim($array_t[3]);
                        ///
//                        if ($name == '(пусто)' || empty($name)) {
//                            continue;
//                        }

                        // Если ТОЛЬКО ЦИФРЫ БЕЗ БУКВ - это НОМЕР БОРТА
                        if (empty(preg_replace('/\d/i', '', $name_old))) {

                            // Поиск НОМЕРА БОРТА перед заменой
                            $id_PE = Sprwhelement::findAll_ids_by_AP_and_BORT($id_AP, $name_old);
                            if (!$id_PE) {
                                $id_PE = Sprwhelement::findAll_ids_by_AP_and_BORT($id_AP, $name_new);
                                if (!$id_PE) {
                                    $err_bort[$id_AP][] = $name_new;
                                }

                                // НЕ НАДО СОЗДАВАТЬ НОВЫЕ ЗАПИСИ В СПРАВОЧНИК WH_ELEMENT
//                                if (!$this->saveNewSprWhElement($id_AP, $name)) //BORT
//                                {
//                                    continue;
//                                }
                            }
                            $model->old_bort = (string)$array_t[3]; // old
                            $model->n_bort = (string)$array_t[2]; // new


                        } else {
                            // Поиск НОМЕРА ГОС перед заменой
                            $id_PE = Sprwhelement::findAll_ids_by_AP_and_GOS($id_AP, $name_old);
                            if ($id_PE) {
                                $good_gos[$id_AP]['ap'] = $array_t[1];
                                $good_gos[$id_AP][] = $name_old;
                            }
                            if (!$id_PE) {
//                                $err_gos[$id_AP]['ap'] = $array_t[1];
//                                $err_gos[$id_AP][] = $name;

                                $id_PE = Sprwhelement::findAll_ids_by_AP_and_GOS($id_AP, $name_new);
                                if (!$id_PE) {
                                    //ddd($id_AP);

                                    $err_gos[$id_AP]['ap'] = $array_t[1];
                                    $err_gos[$id_AP][] = $name_new;
                                }

                                // НЕ НАДО СОЗДАВАТЬ НОВЫЕ ЗАПИСИ В СПРАВОЧНИК WH_ELEMENT
//                                if (!$this->saveNewSprWhElement($id_AP, $name)) //GOS
//                                {
//                                    continue;
//                                }
                            }
                            $model->old_gos = (string)$array_t[3];
                            $model->n_gos = (string)$array_t[2];
                        }

                        if (isset($id_PE)) {
                            $god[] = [
                                'ap' => $array_t[1],
                                'name' => $name_new,
                                'id_AP' => $id_AP,
                                'id_pe' => $id_PE,
                            ];

                            if (empty($id_PE)) {
                                $err_id_PE[] = [
                                    'ap' => $array_t[1],
                                    'name' => $name_new,
                                    'id_AP' => $id_AP,
                                    'id_pe' => $id_PE,
                                ];
                                continue;
                            }
                        }
                        //ddd($id_PE);

                        $model->parent_id = (int)$id_PE[0];
                        $model->doc_num = (string)$array_t[4];
                        $model->tx = (string)$array_t[5];

                        //ddd($model);
                        ///
                        //    0 => '01.10.2017'
                        //    1 => 'Каскеленский автопарк'
                        //    2 => '018ABB05'
                        //    3 => '573ZNA05'
                        //    4 => ''
                        //    5 => ''

                        //ddd($model);

                        //
                        if (!$model->save(true)) {
                            //dd($model->errors);
                            $err[$model->id] = $model->errors;
                        } else {
                            //echo '== '.$model->n_gos.' <br>';
                            $res_spr[] = [
                                $array_t[1],
                                $model->parent_id,
                                $model->n_gos,
                                Sprwhelement::Rename_Name_GOS($model->parent_id, $model->n_gos, $model->do_timestamp)
                            ];
                        }

                    }


                }

                echo '========== <br>';


                if (isset($err_top) && !empty($err_top)) {
                    echo 'err_top <br>';
                    dd($err_top);
                }

//                if (isset($good_gos) && !empty($good_gos)) {
//                    echo 'good_gos <br>';
//                    dd($good_gos);
//                }

                if (isset($err_bort) && !empty($err_bort)) {
                    echo 'err_bort <br>';
                    dd($err_bort);
                }
                if (isset($err_gos) && !empty($err_gos)) {
                    echo 'err_gos <br>';
                    dd($err_gos);
                }
                ///
                if (isset($err) && !empty($err)) {
                    echo 'err <br>';
                    dd($err);
                }
                ///
//                if (isset($god) && !empty($god)) {
//                    echo 'Gut <br>';
//                    dd($god);
//                }
                ///
                if (isset($res_spr) && !empty($res_spr)) {
                    echo 'spr_wh_element <br>';
                    ddd($res_spr);
                }

            }

            ///
            return $this->redirect(['/sprwhelement_change/index']);
        }

        ///
        return $this->render(
            'copypast/_form', [
                'model' => $model,
                'list_whtop' => $list_whtop,
                'list_whtop_element' => $list_whtop_element,
            ]
        );

    }


    /**
     * Создать В Справочник новую запись
     * =
     * @param $ap_id
     * @param $name_pe
     * @return bool
     * @throws \Exception
     */
    function saveNewSprWhElement($ap_id, $name_pe)
    {
        //
        $bort_priznak = Sprwhtop::getPriznakBort($ap_id);
        if (!isset($bort_priznak)) {
            ddd(112222);
        }
        //
        $model = new Sprwhelement();
        $model->id = Sprwhelement::setNext_max_id();

        $model->parent_id = (int)$ap_id;
        $model->deactive = (int)0;
        $model->final_destination = (int)1;
        $model->f_first_bort = (int)$bort_priznak;
        $model->tx = 'AutoCreate'; //Примечание


        //if ((int)$uchet_bort == 1) {
        if ((int)$bort_priznak == 1) {
            // BORT
            $model->name = (string)$name_pe;
            $model->nomer_borta = (string)$name_pe;
        } else {
            //GOS
            $model->name = (string)$name_pe;
            $model->nomer_gos_registr = (string)$name_pe;
        }

        //ddd($model);

        ///
        if ($model->save(true)) {
            return true;
        } else {
            ddd($model->errors);
        }

        return false;
    }

    /**
     * ВОЗВРАТ ПО РЕФЕРАЛУ
     */
    public function actionReturn_to_refer()
    {
        //Возврат по рефералу REFER
        return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id, Yii::$app->request->url));
    }


    /**
     * @return mixed
     */
    public function actionChange_gos_bort()
    {
        $model = new Sprwhelement_change();
        $model->id = Sprwhelement_change::setNext_max_id();
        $model->dt_cr_timestamp = strtotime('now');
        $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));

        //
        $list_whtop = ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');
        $list_whtop_element = ['' => 'Выбрать группу'];

        ///
        $para_button = Yii::$app->request->post('contact-button');
        //ddd($model);

        ///
        if ($model->load(Yii::$app->request->post())) {
            ///
            if (isset($model->wh_element) && (int)$model->wh_element > 0) {
                $list_whtop_element = ['' => 'Выбрать группу'] +
                    ArrayHelper::map(Sprwhelement::find()->orderBy('name')->all(), 'id', 'name');
            }

            ///
            if ($para_button == 'save_button') {
                //
                $model->do_timestamp = strtotime($model->dt_create);
                $model->dt_cr_timestamp = strtotime($model->doc_cr);
                //
                $model->parent_id = (int)$model->wh_element;


                //
                if ($model->save(true)) {
                    return $this->redirect(['/sprwhelement_change/return_to_refer']);
                }
            }

        }

        return $this->render(
            '_form_create', [
                'model' => $model,
                'list_whtop' => $list_whtop,
                'list_whtop_element' => $list_whtop_element,
            ]
        );
    }


    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        ddd(111);

        $max_value = Sprwhelement::find()->max('id');
        $max_value++;

        $model = new Sprwhelement();
        $model->id = $max_value;

        if (empty($model->parent_id)) {
            $model->parent_id = 0;
        }


        if ($model->load(Yii::$app->request->post())) {

            ///
            /// ПРОВЕРКА. Является ли группа (Автопарк) Целевым ПАРКОМ
            ///
            if (Sprwhelement::is_cs_group($model->parent_id)) {

                $model->final_destination = 1;
            }

            //ddd($sprav);

            $model->id = (integer)$model->id;
            $model->parent_id = (integer)$model->parent_id;

            $model->create_user_id = Yii::$app->getUser()->identity->id; // 'Id создателя',
            $model->date_create = date('d.m.Y H:i:s', strtotime('now'));

            $model->delete_sign = (integer)0; // Типа NO DEL


            //  * Функция приведения записей по полям ГОС и БОРТ в норму.
            //$model = $this->Normalise_GOS_BORT($model);

            //ddd($model);
            //
            if ($model->save()) {
                return $this->redirect(['/sprwhelement_change/return_to_refer']);
            }
        }


        return $this->render(
            'create', [
                'model' => $model,
            ]
        );
    }


    /**
     * РЕДАКТИРОВАНИЕ сопровождается отметками от пользователе-редакторе и дате редактирования
     *-
     * @return string|Response
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->get('id');

        $model = Sprwhelement_change::findOne($id);
        $model->dt_create = date('d.m.Y H:i:s', $model->do_timestamp);
        $model->doc_cr = date('d.m.Y H:i:s', $model->dt_cr_timestamp);
        $model->wh_element = $model->parent_id;
        $model->wh_top = Sprwhelement::ParentId_from_ChildId($model->parent_id);
        //ddd($model);

        //
        $list_whtop = ArrayHelper::map(Sprwhtop::find()->orderBy('name')->all(), 'id', 'name');
        ///

        $list_whtop_element = ['' => 'Выбрать группу'] +
            ArrayHelper::map(Sprwhelement::find()->orderBy('name')->all(), 'id', 'name');

        ///
        $para_button = Yii::$app->request->post('contact-button');

        ///
        if ($model->load(Yii::$app->request->post())) {

            ///
            if ($para_button == 'save_button') {
                //ddd($model);

                //
                $model->do_timestamp = strtotime($model->dt_create);
                $model->dt_cr_timestamp = strtotime($model->doc_cr);
                //
                $model->parent_id = (int)$model->wh_element;


                //ddd($model);

                //
                if ($model->save(true)) {
                    if (!($res = Sprwhelement::Rename_Name_GOS($model->parent_id, $model->n_gos, $model->do_timestamp))) {
                        ddd($res);
                    }
                    return $this->redirect('/sprwhelement_change/index');
                }

            }


        }


        //ddd(111);
        //_form_update -- не нужна
        // все происходит в одной форме

        return $this->render(
            '_form_create', [
                'model' => $model,
                'list_whtop' => $list_whtop,
                'list_whtop_element' => $list_whtop_element,
            ]
        );

    }

    /**
     * Ремонт-слияние Номеров ИД складов согласно справочнику Замен_Номеров_ГОС.
     * Заменяем в Накладных Склада и СТОЛБОВЫХ накладных
     * =
     *
     * @return string|Response
     * @throws \Exception
     */
    public function actionRemont_sklad_and_stolb()
    {
        $arr_rez = [];
        $all_broken_ids = [];
        $arr_all = [];


        // Получаем данные для замены согласно СПРАВОЧНИКУ ЗАМЕН
        foreach (Sprwhelement_change::find()
                     ->select([
                         'parent_id', // Это парент на ИД Справочника
                         'do_timestamp',
                         'old_gos',
                         'old_bort',
                         'n_gos',
                         'n_bort',
                     ])
                     ->each() as $model_change) {

            // ПУСТО
            if (empty($model_change->n_gos)) {
                continue;
            }
            if (empty($model_change->old_gos)) {
                continue;
            }

            ///4440
            $ideal_id_PE = $model_change->parent_id;

            /// AP
            $ideal_parent_id = ArrayHelper::getValue(Sprwhelement::find()
                ->where(['id' => (int)$ideal_id_PE])
                ->one(), 'parent_id'); // parent_id =24
//            ddd($model_change);
//            ddd($ideal_parent_id);


            //Получить Ид только по ДВУМ ИМЕНАМ ВНУТРИ ОДНОГО ПАРКА
            $broken_ids = Sprwhelement::find_Wrong_Id_by_Names(
                $model_change->old_gos,
                $model_change->n_gos,
                $ideal_parent_id,
                $ideal_id_PE
            );
            if (count($broken_ids) <= 1) {
                continue;
            }

            if (count($broken_ids) > 1) {
                // ddd($broken_ids); // 4463

                // Удаляем оригинальный ИД из массива
                unset($broken_ids[array_search($ideal_id_PE, $broken_ids)]);

                ///
                $all_broken_ids = $all_broken_ids + $broken_ids;

                ddd($all_broken_ids); // 4578
                //0 => 5689
                //1 => 4578
            }


            foreach ($broken_ids as $id) {
                if ($id == $ideal_id_PE) {
                    continue;
                }

                // Ответ будет массивом ответов
                $arr_rez[] = Sklad::update_all_ids_to_id($id, $ideal_id_PE);
                $arr_all[$id][$ideal_id_PE] = $broken_ids;
            }
        }


        echo 'arr_all';
        dd($arr_all);

        echo '$arr_rez';
        dd($arr_rez);

        echo 'all_broken_ids';
        dd($all_broken_ids);
        //   [0] => 4060
        //   [1] => 4350


        ///Сразу УДАЛЯТЬ СТОЛБ!
        echo 'Сразу УДАЛЯТЬ СТОЛБ!';
        $arr_rez = Sklad_inventory_cs::deleteAll_ids($all_broken_ids);
        dd($arr_rez);


        // Удалить все ненужные ИД из справочника Sprwhelement
        echo 'Удалить все ненужные ИД из справочника Sprwhelement';
        dd(Sprwhelement::deleteAll_ids($all_broken_ids));

        ddd("OK");

        return "OK";
    }


    /**
     * @return string
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');
        Sprwhelement_change::findOne($id)->delete();

        // Возвтрат по РЕФЕРАЛУ
        $url_array = Yii::$app->request->headers;
        $url = $url_array['referer'];

        return $this->goBack($url);
    }


    /**
     * По вызову Аякс находит
     * -
     * Поле - ГОС
     * =
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    public function actionGos_old($id = 0)
    {
        return ArrayHelper::getValue(
            Sprwhelement::find()
                ->where(['id' => (int)$id])
                ->one(), 'nomer_gos_registr');
    }

    /**
     * По вызову Аякс находит
     * -
     * Поле - БОРТ
     * =
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    public function actionBort_old($id = 0)
    {
        return ArrayHelper::getValue(
            Sprwhelement::find()
                ->where(['id' => (int)$id])
                ->one(), 'nomer_borta');
    }


}
