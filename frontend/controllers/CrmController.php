<?php

namespace frontend\controllers;

use frontend\models\Crm;
use frontend\models\mts_crm;
use frontend\models\post_mts_crm;
use frontend\models\Sklad;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhtop;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;


/**
 *
 */
class CrmController extends Controller
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
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // Принятие накладной из Createfrom_shablon
                    'index' => [
                        'POST',
                        'GET',
                    ],
                    //  'delete' => ['POST', 'DELETE'],
                ],
            ],
        ];
    }


    /**
     * ВАЖНО!
     * =
     * @param $event
     * @return bool
     * @throws HttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($event)
    {

        if (!Yii::$app->getUser()->identity) {
            throw new HttpException(411, 'Необходима авторизация', 5);
            //throw new HttpException('Необходима авторизация');
        }

        return parent::beforeAction($event);
    }


    /**
     * {@inheritdoc}
     */
    public function actionIndex()
    {
        $user_name = Yii::$app->getUser()->identity->username_for_signature;

        return $this->render('index', [
            "user_name" => $user_name,
        ]);
    }


    /**
     * NEW CAll Interface. Создание НОВОЙ ЗАЯВКИ в OTRS
     * =
     * {@inheritdoc}
     */
    public function actionNewCall()
    {

        ///
        $model = new Crm(['scenario' => Crm::SCENARIO_CREATE_NEW]);

        /// Получить полный список ЦС - GROUP //Список ЦС - групп
        $spr_top = Sprwhtop::get_ListFinalDestination();


        /// LOAD
        if ($model->load(Yii::$app->request->post())) {

            //ddd($model);

            $params = [
                'Ticket' => [
                    'Title' => 'Заявитель: ' . $model->opername,
                    'QueueID' => 31, //
                    'StateID' => 1, //
                    'PriorityID' => 3, // синий 1, //3
                    'CustomerUser' => "manager",
                    'CustomerID' => "manager", //1,
                    'OwnerID' => 22 // Техническое обслуживание (вн 0) //22  // Заявки ОТРС =26, //
                ],
                'Article' => [
                    'CommunicationChannel' => 'Email',
                    'ArticleTypeID' => 1,
                    'Subject' => '--',
                    'Body' => "comment",
                    'ContentType' => 'text/plain; charset=utf8'
                ],
                'DynamicField' => [
                    [
                        'Name' => 'comment',
                        'Value' => "."          //.$model->commentArea
                    ],
                    [
                        'Name' => 'phone',      // Телефон для связи
                        'Value' => "" . $model->caller_id_hide
                    ],
                    [
                        'Name' => 'recall',
                        'Value' => $model->inputPhone
                    ],
                    [
                        'Name' => 'needCallBack',
                        'Value' => $model->needCallBack //"0"
                    ],
                    [
                        'Name' => 'name',
                        'Value' => $model->inputName
                    ],
                    [
                        'Name' => 'operName',
                        'Value' => $model->opername
                    ],

                    // Доп поля  31
                    [
                        'Name' => 'routeNumber',  // Номер маршрута
                        'Value' => '' . $model->routeNumber
                    ],
                    [
                        'Name' => 'routeOwner',   // Владелец маршрута по старому
                        'Value' => '' . $spr_top[$model->companyName]
                    ],
                    [
                        'Name' => 'routeItem',    // Гос.номер
                        'Value' => '' . $model->stateNumber
                    ],
                    [
                        'Name' => 'problemIssue', // Классификация проблемы
                        'Value' => '' . $model->oezap
                    ]
                ]
            ];


            ///#############
            $model->id = Crm::setNextMaxId();
            // ddd($model);

            ///save
            if ($model->save(true)) {
                // Отправка команды в OTRS
                $str_json = $this->send_otrs('ticket/add', $params);

                //..
                if (stristr($str_json, 'Error')) {
                    return $this->render('new_call/index', [
                        'spr_top' => $spr_top,
                        'alert_mess' => 'Ошибка связи. Заявка НЕ СОЗДАНА.'
                    ]);
                }

                ///
                return $this->redirect('/crm/new-call');

            } else {
                ddd($model->errors);
            }


        }


        return $this->render('new_call/index', [
            'model' => $model,
            'spr_top' => $spr_top
        ]);
    }


    /**
     * TICKET STATUS SET
     * =
     */
    public function actionStatusSet()
    {

        ///
        $model = new Crm(['scenario' => Crm::SCENARIO_CREATE_NEW]);

        /// Получить полный список ЦС - GROUP //Список ЦС - групп
        $spr_top = Sprwhtop::get_ListFinalDestination();


        /// LOAD
        if ($model->load(Yii::$app->request->post())) {

            ddd($model);

            $params = [
                'Ticket' => [
                    'Title' => 'Заявитель: ' . $model->opername,
                    'QueueID' => 31, //
                    'StateID' => 1, //
                    'PriorityID' => 3, // синий 1, //3
                    'CustomerUser' => "manager",
                    'CustomerID' => "manager", //1,
                    'OwnerID' => 22 // Техническое обслуживание (вн 0) //22  // Заявки ОТРС =26, //
                ],
                'Article' => [
                    'CommunicationChannel' => 'Email',
                    'ArticleTypeID' => 1,
                    'Subject' => '--',
                    'Body' => "comment",
                    'ContentType' => 'text/plain; charset=utf8'
                ],
                'DynamicField' => [
                    [
                        'Name' => 'comment',
                        'Value' => "."          //.$model->commentArea
                    ],
                    [
                        'Name' => 'phone',      // Телефон для связи
                        'Value' => "" . $model->caller_id_hide
                    ],
                    [
                        'Name' => 'recall',
                        'Value' => $model->inputPhone
                    ],
                    [
                        'Name' => 'needCallBack',
                        'Value' => $model->needCallBack //"0"
                    ],
                    [
                        'Name' => 'name',
                        'Value' => $model->inputName
                    ],
                    [
                        'Name' => 'operName',
                        'Value' => $model->opername
                    ],

                    // Доп поля  31
                    [
                        'Name' => 'routeNumber',  // Номер маршрута
                        'Value' => '' . $model->routeNumber
                    ],
                    [
                        'Name' => 'routeOwner',   // Владелец маршрута по старому
                        'Value' => '' . $spr_top[$model->companyName]
                    ],
                    [
                        'Name' => 'routeItem',    // Гос.номер
                        'Value' => '' . $model->stateNumber
                    ],
                    [
                        'Name' => 'problemIssue', // Классификация проблемы
                        'Value' => '' . $model->oezap
                    ]
                ]
            ];


            ///#############
            $model->id = Crm::setNextMaxId();
            // ddd($model);

            ///save
            if ($model->save(true)) {

                // Отправка команды в OTRS
                $str_json = $this->send_otrs('ticket/add', $params);

                //..
                if (stristr($str_json, 'Error')) {
                    return $this->render('new_call/index', [
                        'spr_top' => $spr_top,
                        'alert_mess' => 'Ошибка связи. Заявка НЕ СОЗДАНА.'
                    ]);
                }

                ///
                return $this->redirect('/crm/status_set');

            } else {
                ddd($model->errors);
            }


        }


        return $this->render('status_set/index', [
            'model' => $model,
            'spr_top' => $spr_top
        ]);
    }


    /**
     * Закрытие ЗАЯВКИ в OTRS
     * =
     * {@inheritdoc}
     */
    public function actionTicketClose()
    {
//        ///
//        ///   https://manager-test.tha.kz/otrs/index.pl?ChallengeToken=8c4fLxuVzTZc2zthDertGXHRMWEPVblT&Action=AgentTicketCompose&TicketID=83472&ArticleID=169357&ReplyAll=&ResponseID=1
//        ///
//        ///
//        /// https://manager-test.tha.kz/otrs/index.pl?ChallengeToken=y5Ay8MbB5rDz6jXPVBQtw4UOATnjHGND&Action=AgentTicketCompose&TicketID=83491&ArticleID=169388&ReplyAll=&ResponseID=1
//
//        ///
//        $model = new Crm(['scenario' => Crm::SCENARIO_CLOSE_TICKET]);
//
//        /// Получить ПОСЛЕДНИЕ ЗАЯВКИ, 40 штук
//        $ticket_ids = ArrayHelper::map(Yii::$app->db_otrs->createCommand(
//            'SELECT  a.id, a.tn FROM ticket a where ticket_state_id != 3   ORDER BY id DESC  LIMIT 40'
//        )->queryAll(), 'id', 'tn');
//
//
//        /// Получить Справочник СТАТУСОВ
//        $ticket_state_ids = ArrayHelper::map(Yii::$app->db_otrs->createCommand(
//            'SELECT  a.id, a.name FROM ticket_state a   ORDER BY id ASC '
//        )->queryAll(), 'id', 'name');
//
//
//        ///
//        ///   LOAD
//        ///
//        if ($model->load(Yii::$app->request->post())) {
//            //ddd($model->ticket_state_id);
//
//            //ticket_id
////            $ticket_id = $model->id;
//
//            ///
//            ///  Получить Статус Фактический из таблицы соответсвия статусов
//            ///
////            $status_fact_id = Yii::$app->db_otrs->createCommand(
////                'SELECT  type_id FROM ticket_state where id = ' . $model->ticket_state_id . ';'
////            )->queryScalar(); //3
//            $status_fact_id = $model->ticket_state_id;
//
//
//            /// TICKET
//            ///  Изменить Статус в OTRS
//            Yii::$app->db_otrs->createCommand(
//                "UPDATE ticket SET  ticket_lock_id = 1 " .
//                ", ticket_priority_id = 3 " .
//                ", change_by = 22 " .
//                ", change_time ='" . date('Y-m-d H:i:s', strtotime('now -6 hours')) . "'" .
//                " WHERE id=" . $model->id . ";"
//            )->execute();
//
//            /// TICKET
//            ///  Изменить Статус в OTRS
//            Yii::$app->db_otrs->createCommand(
//                "UPDATE ticket SET ticket_state_id =" . $status_fact_id .
//                ", ticket_lock_id = 1 " .
//                ", change_by = 22 " .
//                ", change_time ='" . date('Y-m-d H:i:s', strtotime('now -6 hours')) . "'" .
//                " WHERE id=" . $model->id . ";"
//            )->execute();
//
//
////            ///
////            /// TICKET_HISTORY/ UNLOCK
////            /// Добавить СТРОКУ В ИСТОРИЮ
////            // выражение SQL с двумя именованными маркерами «:username» и «:email»
////            $sql =
////                "INSERT INTO ticket_history(name, history_type_id, ticket_id," .
////                "type_id, queue_id, owner_id,priority_id, state_id, " .
////                "create_time, change_time, " .
////                "create_by, change_by" .
////                ")" .
////                "VALUES( '%%Закрыта не успешно%%Проводится инкасация%%',27," . $model->id . "," .
////                "1, 31, 26, 3, " . $status_fact_id . "," .
////                "'" . date('Y-m-d H:i:s', strtotime('now -6 hours')) . "'," .
////                "'" . date('Y-m-d H:i:s', strtotime('now -6 hours')) .
////                "'," .
////                "26, 26" .
////                ")";
////            //
////            Yii::$app->db_otrs->createCommand($sql)->execute();
//
//            ///
//            /// TICKET_HISTORY
//            /// Добавить СТРОКУ В ИСТОРИЮ
//            // выражение SQL с двумя именованными маркерами «:username» и «:email»
//            $sql =
//                "INSERT INTO ticket_history(name, history_type_id, ticket_id," .
//                "type_id, queue_id, owner_id,priority_id, state_id, " .
//                "create_time, change_time, " .
//                "create_by, change_by" .
//                ")" .
//                "VALUES( '%%Закрыта не успешно%%Проводится инкасация%%',27," . $model->id . "," .
//                "1, 31, 22, 3, " . $status_fact_id . "," .
//                "'" . date('Y-m-d H:i:s', strtotime('now -6 hours')) . "'," .
//                "'" . date('Y-m-d H:i:s', strtotime('now -6 hours')) .
//                "'," .
//                "22, 22" .
//                ")";
//            //
//            Yii::$app->db_otrs->createCommand($sql)->execute();
//
//
//            // Отправка команды в OTRS
//            //$str_json = $this->send_otrs('ticket/add', $params);
//            //$str_json = $this->send_otrs('TicketUpdate', $params);
//
//            //$str_json = $this->send_otrs('ticket/get', $params); // '{\"Error\":{\"ErrorCode\":\"TicketGet.AccessDenied\",\"ErrorMessage\":\"TicketGet: User does not have access to the ticket!\"}}'
//            //$str_json = $this->send_otrs('ticket/search', $params); //
//            //$str_json = $this->send_otrs('ticket/zoom', $params); //
//            //                // Отправка команды в OTRS
//            //                $str_json = $this->send_otrs('ticket/add', $params);
//
//
//            ///#############
//            $model->id = Crm::setNextMaxId();
//
//            ///  save
//            if ($model->save(true)) {
//                ///
//                return $this->redirect('/');
//            } else {
//                ddd($model->errors);
//            }
//        }
//
//        ///
//        return $this->render('ticket_close/index', [
//            'model' => $model,
//            'ticket_ids' => $ticket_ids,
//            'ticket_state_ids' => $ticket_state_ids,
//        ]);
//
    }


    /**
     * Zakaz
     * =
     * {@inheritdoc}
     */
    public function actionZakaz()
    {
        $user_id = Yii::$app->getUser()->identity->id;

        if (!isset($user_id)) {
            return $this->render('/mobile/errors', ['err' => "Отсутсвует авторизация"]);
        }

        ///
        /// Получить лист - список мобильников (МТС) из справочника
        ///
        $list_mts1 = Sprwhelement::ArrayOnParent_id(1); // Guidejet TI. Склад сервисной службы
        $list_mts2 = Sprwhelement::ArrayOnParent_id(2); // Guidejet TI.

        $list_mts = $list_mts1 + $list_mts2;
        asort($list_mts);

        $list_mts = ['' => "Не выбран"] + $list_mts;

//        ddd($list_mts);
//        ddd($list_mts1);

        ///
        /// Получить лист - список АВТОПАРКОВ
        ///
        $list_ap = Sprwhtop::ArrayNamesAllIds();
        $list_ap = ['' => "Не выбран"] + $list_ap;

        //$list_pe = Sprwhelement::ArrayOnParent_id(14);
        ///
        /// Получить лист - список АВТО Бусов
        /// БОРТ
        ///
        $list_bort_pe = [];

        ///
        /// Получить лист - список АВТО Бусов
        /// ГОС
        ///
        $list_gos_pe = [];

        ///
        /// MTS
        ///
        $model = new mts_crm();
        $model->id = mts_crm::setNext_max_id();

        ///
        ///
        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)mts_crm::setNext_max_id();
            $model->mts_id = (int)$model->mts_id;
            $model->id_ap = (int)$model->id_ap;

            if (isset($model->id_bort_pe)) {
                $model->id_pe = (int)$model->id_bort_pe;
                unset($model->id_bort_pe);
            }
            if (isset($model->id_gos_pe)) {
                $model->id_pe = (int)$model->id_gos_pe;
                unset($model->id_gos_pe);
            }
            $model->id_pe = (int)$model->id_pe;


            $model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
            $model->dt_create_timestamp = strtotime('now');


//            ddd($model);


            if (!$model->save(true)) {
                ddd($model->errors);
            }

            return $this->redirect('/crm/index');
        }


//        ddd($list_gos_pe);

        return $this->render('zakaz/index', [
            "list_mts" => $list_mts,
            "list_ap" => $list_ap,

            "list_gos_pe" => $list_gos_pe,
            "list_bort_pe" => $list_bort_pe,

            "model" => $model,
        ]);
    }


    /**
     * Tabl
     * =
     * {@inheritdoc}
     */
    public function actionTake_day()
    {
        $dt_start = date('Y-m-d 00:00:00', strtotime('now -2 month'));
        $dt_stop = date('Y-m-d 23:59:59', strtotime('now -2 month'));


        $model = Yii::$app->db_otrs->createCommand(
        //' SELECT id,tn, title,user_id, ticket_state_id,timeout,create_time,change_by '.
        //  ' where timeout >='.strtotime($dt_start).' AND '. ' timeout <='.strtotime($dt_stop).

            ' SELECT  * ' .
            ' FROM ticket ' .
            ' where create_time >=\'' . ($dt_start) . '\' AND ' . ' create_time <=\'' . ($dt_stop) . '\' ' .
            ' AND queue_id=31 ' .
            ' ORDER BY tn DESC' .
            ' LIMIT 0,100'

        )->queryAll();

        return $model;
    }


    /**
     * Tabl
     * =
     * {@inheritdoc}
     */
    public function actionTake___()
    {
//
//        //$count = Yii::$app->db_otrs->createCommand('SELECT ()* FROM ticket LIMIT 10')->queryAll();
//        $count = Yii::$app->db_otrs->createCommand('SELECT
//                a.id,a.title,a.user_id,a.ticket_state_id,a.timeout,a.create_time,a.change_by
//             FROM ticket a LIMIT 10')->queryAll();
//
//        ddd($count);
    }


    /**
     * #2 SEND TO OTRS
     * =
     * @param $method
     * @param $params
     * @return bool|string
     */
    function send_otrs($method, $params)
    {
        ///
        $params['UserLogin'] = 'manager';
        $params['Password'] = 'manager';
        //$params['CustomerUserLogin'] = 'login';
        //$params['SessionID'] = 'id';

        $ch = curl_init('https://manager-test.tha.kz/otrs/nph-genericinterface.pl/Webservice/webform/' . $method);

        $data_string = json_encode($params);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        return $result;
    }


    /**
     * По вызову Аякс находит
     * ПОДЧИНЕННЫЕ СКЛАДЫ
     *
     * @param int $id
     * @return string
     */
    public function actionListElement($id = 0)
    {
        $model = Html::dropDownList(
            'name_id', 0,
            ArrayHelper::map(
                Sprwhelement::find()
                    ->where([
                        'parent_id' => (integer)$id])
                    ->orderBy('name')
                    ->all(), 'name', 'name'
            ),
            [
                'prompt' => 'Выбор ...']
        );

        if (empty($model)) {
            return "Запрос вернул пустой массив";
        }

        return $model;

    }


    /**
     *
     */
    public function actionListElementGos($id = 0)
    {
        $model = Html::dropDownList(
            'name_id', 0,
            ArrayHelper::map(
                Sprwhelement::find()
                    ->where([
                        'parent_id' => (integer)$id])
                    ->orderBy('nomer_gos_registr')
                    ->all(), 'nomer_gos_registr', 'nomer_gos_registr'
            ),
            [
                'prompt' => 'Выбор ...']
        );

        if (empty($model)) {
            return "Запрос вернул пустой массив";
        }

        return $model;

    }

    /**
     *
     */
    public function actionListElementBort($id = 0)
    {
        $model = Html::dropDownList(
            'name_id', 0,
            ArrayHelper::map(
                Sprwhelement::find()
                    ->where(['parent_id' => (integer)$id])
                    ->orderBy('nomer_borta')
                    ->all(), 'nomer_borta', 'nomer_borta'),
            ['prompt' => 'Выбор ...']
        );

        if (empty($model)) {
            return "Запрос вернул пустой массив";
        }
        return $model;
    }


    /**
     * Tabl
     * =
     * {@inheritdoc}
     */
    public function actionTake_next()
    {
//        //$count = Yii::$app->db_otrs->createCommand('SELECT ()* FROM ticket LIMIT 10')->queryAll();
//        $count = Yii::$app->db_otrs->createCommand(
//            'SELECT a.id,a.title,a.user_id,a.ticket_state_id,a.timeout,a.create_time,a.change_by FROM ticket a LIMIT 10')
//            ->queryAll();
//
//        ddd($count);
//
//        return $this->render('zakaz/tabl', [
//            "searchModel" => $searchModel,
//        ]);
    }


    /**
     * Tabl
     * =
     * {@inheritdoc}
     */
    public function actionTabl()
    {
        $para = Yii::$app->request->queryParams;

        $searchModel = new post_mts_crm();
        $dataProvider_open = $searchModel->search_open($para);
        $dataProvider_close = $searchModel->search_close($para);

        //ddd($dataProvider_open->getModels());
        //ddd($dataProvider_open->getModels());


        //Запомнить РЕФЕР
        Sklad::setPathRefer_ByName(Yii::$app->controller->module->id, Yii::$app->request->url);

        return $this->render('zakaz/tabl', [
            "searchModel" => $searchModel,
            "dataProvider_open" => $dataProvider_open,
            "dataProvider_close" => $dataProvider_close,
        ]);

    }

    /**
     * ВОЗВРАТ ПО РЕФЕРАЛУ
     */
    public function actionReturn_to_refer()
    {
        // Сбросить НАСТРОЙКИ ФИЛЬТРА
        //        Sklad::setUnivers('filter_bc', '');
        //        Sklad::setUnivers('filter_id', '');
        //        Sklad::setUnivers('filter_dt', '');

        //Возврат по рефералу REFER
        return $this->redirect(Sklad::getPathReferByName(Yii::$app->controller->module->id, Yii::$app->request->url));
    }


    /**
     * Содержимое РЕШЕННОЙ задачи
     * =
     * {@inheritdoc}
     */
    public function actionClosed_job()
    {
        ///
        $id = Yii::$app->request->get('id');
        $model = mts_crm::findModelDouble($id);

        ///
        /// Получить лист - список мобильников (МТС) из справочника
        $list_mts1 = Sprwhelement::ArrayOnParent_id(1); // Guidejet TI. Склад сервисной службы
        $list_mts2 = Sprwhelement::ArrayOnParent_id(2); // Guidejet TI.

        $list_mts = $list_mts1 + $list_mts2;
        asort($list_mts);

        ///
        /// Получить лист - список АВТОПАРКОВ
        $list_ap = Sprwhtop::ArrayNamesWithIds($model->id_ap);

        ///
        /// Получить лист - список АВТО Бусов
        $list_pe = Sprwhelement::ArrayNamesWithIds($model->id_pe);

        ///
        ///
        ///
        $nepoladki = [
            'Белый экран ', 'Черный экран ', 'Микрофон', 'Не работает сенсор', 'Привязка ПВ', 'Отсутствует связь',
            'Неправильная привязка МСАМ карты', 'Ошибка программного обеспечения', 'Сломан ролик', 'Сломан нож',
            'Не работает принтер', 'Неправильная привязка служебной карты', 'На экране "фатальная ошибка"',
            'Не принимает оплату', 'Умышленная порча оборудования', 'Отсутствует связь'

        ];

        $reshenia = [
            0 => "Нет решения",
            1 => "Перезагрузка",
            2 => "Переобжимка коннекторов",
            3 => "Пересборка контактов",
        ];

        $reshenia2 = [
            'Водитель не дождался мастера',
            'Водитель не отвечает',
            'Водитель отказался от ремонта',
            'Восстановление питания',
            'Монтаж АСУОП завершен',
            'Демонтаж АСУОП завершен',
            'Демонтаж МТТ',
            'Демонтаж стабилизатора МТТ',
            'Замена 1-го терминала',
            'Замена 2-го терминала',
            'Замена автомобильного стабилизатора от МТТ',
            'Замена антенны',
            'Замена колодки',
            'Замена МСАМ',
            'Замена МТТ',
            'Замена обоих терминалов',
            'Замена ПВ',
            'Замена поручня',
            'Замена предохранителя',
            'Замена разъёма',
            'Замена свитча',
            'Замена сим карты',
            'Замена стабилизатора VSP01',
            'Мастер проверил, всё оборудование работает',
            'Нет доступа к ПЕ',
            'Обмен сервера инкассации',
            'Перезапуск МТТ',
            'Перезапуск ПВ',
            'Перезапуск терминалов',
            'Переобжим коннектора RJ45',
            'Переобжим коннектора молекс на ПВ',
            'Переобжим коннектора молекс на терминале',
            'Переобжим коннектора на стабилизаторе VSP01',
            'Поломка самого ТС',
            'Привязка ПВ',
            'Привязка служебной карты МТТ',
            'Со слов водителя МТТ работает',
            'Со слов водителя ПВ работает',
            'Со слов водителя терминалы работают',
            'Телефон отключен',
            'Укрепление поручня в парке',
        ];
        $reshenia = $reshenia + $reshenia2;
        asort($reshenia);

        $itog = [
            '1' => 'Решено на месте',
            '2' => 'Замена из ОФ',
            '3' => 'Нет решения',
        ];


        return $this->render('closed', [
            'model' => $model,

            "list_mts" => $list_mts,
            "list_ap" => $list_ap,
            "list_pe" => $list_pe,

            "nepoladki" => $nepoladki,
            "reshenia" => $reshenia,
            "itog" => $itog,

        ]);

    }


    /**
     * Содержимое РЕШЕННОЙ задачи
     * =
     * {@inheritdoc}
     */
    public function actionZakaz_info()
    {
        $para = Yii::$app->request->queryParams;

        $model = mts_crm::findModelDouble($para['id']);
        ///
        /// Получить лист - список мобильников (МТС) из справочника
        $list_mts1 = Sprwhelement::ArrayOnParent_id(1); // Guidejet TI. Склад сервисной службы
        $list_mts2 = Sprwhelement::ArrayOnParent_id(2); // Guidejet TI.

        $list_mts = $list_mts1 + $list_mts2;
        asort($list_mts);

        ///
        /// Получить лист - список АВТОПАРКОВ
        $list_ap = Sprwhtop::ArrayNamesAllIds();

        ///
        /// Получить лист - список АВТО Бусов
        $list_pe = Sprwhelement::ArrayOnParent_id(14);

        return $this->render('zakaz_view', [
            'model' => $model,
            "list_mts" => $list_mts,
            "list_ap" => $list_ap,
            "list_pe" => $list_pe,
        ]);
    }


    /**
     * По вызову Аякс находит
     * ПОДЧИНЕННЫЕ СКЛАДЫ
     *
     * @param int $id
     * @return string
     */
    public function actionParentId_element($id)
    {
        $model = Html::dropDownList(
            'name_id', 0,
            ArrayHelper::map(
                Sprwhelement::find()
                    ->select(['id', 'name'])
                    ->where(['parent_id' => (int)$id])
                    ->orderBy('name')
                    ->all(), 'id', 'name'),
            ['prompt' => 'Выбор ...']
        );
        return $model;
    }


    /**
     * По вызову Аякс находит
     * ПОДЧИНЕННЫЕ СКЛАДЫ
     *
     * @param int $id
     * @return string
     */
    public function actionGos_element($id)
    {
        $model = Html::dropDownList(
            'gos_id', 0,
            ArrayHelper::map(
                Sprwhelement::find()
                    ->select([
                        'id',
                        'parent_id',
                        'nomer_gos_registr'
                    ])
                    ->where(['parent_id' => (int)$id])
                    ->orderBy('nomer_gos_registr')
                    ->all(), 'id', 'nomer_gos_registr'
            ),
            ['prompt' => 'Выбор ...']
        );

        return $model;
    }


    /**
     * actionBort_element
     * =
     *
     * @param int $id
     * @return string
     */
    public function actionBort_element($id)
    {
        $model = Html::dropDownList(
            'gos_id', 0,
//            ['0'=>'нет']+
            ArrayHelper::map(
                Sprwhelement::find()
                    ->select([
                        'id',
                        'parent_id',
                        'nomer_borta',
                    ])
                    ->where([
                        'parent_id' => (int)$id])
                    ->orderBy('nomer_borta')
                    ->all(), 'id', 'nomer_borta'
            ),
            [
                'prompt' => 'Выбор ...']
        );

        return $model;
    }


}