<?php

namespace frontend\controllers;


use common\models\User;
use frontend\models\Dialog;
use frontend\models\Dialogmessage;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 *
 */
class ChatController extends Controller
{
    public $next_time;

    /**
     * $session
     */
//    public function init()
//    {
//        parent::init();
//        $session = Yii::$app->session;
//        $session->open();
//
//        if (!Yii::$app->getUser()->identity) {
//            /// Быстрая переадресация
//            throw new HttpException(411, 'Необходима авторизация', 2);
//        }
//
//    }


    /**
     * Доступ только В отладочном режиме
     * =
     * {@inheritdoc}
     */
//    public function beforeAction($event)
//    {
//        /// Только эта проверка. Больше тут не надо!!!!
//        //if (!isset(Yii::$app->getUser()->identity->id)) {
////        if (!Yii::$app->getUser()->identity) {
////            /// Быстрая переадресация
////            throw new HttpException(411, 'Необходима авторизация', 2);
////        }
//        return parent::beforeAction($event);
//    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // Главная страница
                    'update' => [
                        'GET',
                        'POST',],
                    // Редактирование НАКЛАДНОЙ
                    //
                    'index' => [
                        'GET',
                        'POST'
                    ],
                    'create' => [
                    ],
                    'delete' => [
                    ],
                    'view' => [
                    ],
                    //  'delete' => ['POST', 'DELETE'],
                ],
            ],
        ];

    }


    /**
     * @return string
     */
//    public function actionIndex()
//    {
//        ddd(1111);
//
//        $model = new Rem_history();
//        $model->id = Rem_history::setNext_max_id();
//
//        ///
//        /// LOAD
//        ///
//        if ($model->load(Yii::$app->request->post())) {
//            ddd($model);
//        }
//
//        return $this->render('index', [
//            'model' => $model
//        ]);
//    }


    /**
     * @return string
     * @throws \Exception
     */
    public function actionClient()
    {
        $model = new Dialogmessage();
        $model->dialog_id = '1';
        $model->user_id = Yii::$app->user->identity->id;
        $model->user_name = (
        isset(Yii::$app->user->identity->username_for_signature) ?
            Yii::$app->user->identity->username_for_signature :
            Yii::$app->user->identity->username
        );


        $array_dialog = ArrayHelper::map(Dialog::find()->all(), 'id', 'name');

        return $this->render('index', [
            'model' => $model,
            'array_dialog' => $array_dialog
        ]);

        //AJAX
        //        return $this->renderAjax('index_ajax', [
        //            'model' => $model,
        //            'array_dialog' => $array_dialog
        //        ]);
    }

    /**
     * Вход сюда по ссылке из экрана-оповещений
     * =
     * @return string
     * @throws \Exception
     */
    public function actionShow_listing()
    {
        ///
        $sender_id = yii::$app->request->get('sender_id');
        //
        $array_ids = Dialogmessage::array_ids_ByUserId_ByStatus($sender_id, Dialogmessage::STATUS_UNREAD);
        ///
        foreach ($array_ids as $item_id) {
            $model_mess = Dialogmessage::find_one_ByMessageId($item_id);
            $model_mess->scenario = Dialogmessage::SCENARIO_STATUS_CHANGE;
            $model_mess->status = Dialogmessage::STATUS_READ;
            ///
            if (!$model_mess->save(true)) {
                ddd($model_mess->errors);
            }
            //ddd($model_mess);
        }

//        ddd($array_ids);
//        $this->actionSave_new_status($user_id);


        // GRID
        //$model = Dialogmessage::ArrayAll_ByDialogNumber(1);
        $model = Dialogmessage::Array20_ByDialogNumber(1);


        $provider = new ArrayDataProvider([
            'allModels' => $model,
            'pagination' => ['pageSize' => -1]
        ]);


        return $this->renderAjax('index_listing', [
            'model' => $model,
            'provider' => $provider,
        ]);
    }


    /**
     * ФОРМА. JS-Форма.
     * =
     * @return string
     * @throws \Exception
     */
    public function actionJs_forma_by_id()
    {
//        $id = yii::$app->request->get('id');
        //ddd($id);

        /// FORM
        $dialog_id = '1';
        $user_id = Yii::$app->user->identity->id;
        $user_name = (isset(Yii::$app->user->identity->username_for_signature) ?
            Yii::$app->user->identity->username_for_signature :
            Yii::$app->user->identity->username);

        return $this->renderAjax('index_form', [
            'dialog_id' => $dialog_id,
            'user_id' => $user_id,
            'user_name' => $user_name,
        ]);
    }


    /**
     *  STATUS-LINE
     * =
     * @return string
     * @throws \Exception
     */
    public function actionOnlyRead()
    {
        return $this->render('only_read/index', []);
    }


    /**
     * Сохранение новой записи о сообщении из Скрипта НА Странице
     * -
     * @return Dialogmessage|string
     */
    //public function save_message( $msg, $user_id, $user_name)
    public function actionSave_message()
    {
        $text = yii::$app->request->get('text');
        $dialog = yii::$app->request->get('dialog');
        $name = yii::$app->request->get('name');
        $userid = yii::$app->request->get('');
        $username = yii::$app->request->get('username');


        $dialod_model = new Dialogmessage();
        $dialod_model->scenario = Dialogmessage::SCENARIO_CREATE;
        $dialod_model->id = Dialogmessage::setNextMaxId();


        //
        // Сервисное сообщение не пишем в базу.
        // А остальное - пишем.
        //

        $dialod_model->dialog_id = (int)$dialog;
        $dialod_model->text = $text;
        $dialod_model->sender_id = (int)$name;
        $dialod_model->user_id = (int)$userid;
        $dialod_model->user_name = $username;

        $dialod_model->date_create = strtotime('now');
        $dialod_model->status = Dialogmessage::STATUS_UNREAD;

        ///
        if (!$dialod_model->save(true)) {
            return $dialod_model;
        }

        return '';
    }

    /**
     * Сохранение нового СТАТУСА в сообщения данного дилога. От имени ПРОСМОТРЕВШЕГО сообщения
     * -
     * @param $message_id
     * @return Dialogmessage|string
     */
    public function actionSave_new_status($message_id)
//    public function Save_new_status($user_id)
    {
        $model_message = Dialogmessage::find_one_ByMessageId($message_id);

        ddd($model_message);


        $dialod_model->scenario = Dialogmessage::SCENARIO_CREATE;


        //
        // Сервисное сообщение не пишем в базу.
        // А остальное - пишем.
        //

        $dialod_model->dialog_id = (int)$dialog;
        $dialod_model->text = $text;
        $dialod_model->sender_id = (int)$name;
        $dialod_model->user_id = (int)$userid;
        $dialod_model->user_name = $username;

        $dialod_model->date_create = strtotime('now');
        $dialod_model->status = Dialogmessage::STATUS_UNREAD;

        ///
        if (!$dialod_model->save(true)) {
            return $dialod_model;
        }

        return '';
    }

    /**
     *
     * -
     * @return Dialogmessage|string
     * @throws \yii\mongodb\Exception
     */
    public function actionShow_all_users()
    {
        //
        // Array. Листинг ИДС сендеров по признаку Статуса
        // UNREAD
        $array_ids = Dialogmessage::ArrayAllUniqUsers_ForStatus_InMessages(
            Dialogmessage::STATUS_UNREAD
        );

        ///Далее
        foreach ($array_ids as $user_id) {
            $arr_count[$user_id] = Dialogmessage::CountMessages_forUserId($user_id);
        }

        //ddd($arr_count);


        $array_1 = User::find()
            ->select(['id', 'username_for_signature'])
            ->where(['in', 'id', $array_ids])
            ->orderBy('username_for_signature')
            ->asArray()
            ->all();
        $array_2 = User::find()
            ->select(['id', 'username_for_signature'])
            ->where(
                ['AND',
                    ['not in', 'id', $array_ids],
                    ['chat' => 1]
                ]
            )
            ->orderBy('username_for_signature')
            ->asArray()
            ->all();

        //
        foreach ($array_1 as $key => $item_model) {
            $array_1[$key]['status_unread_count'] = $arr_count[$item_model['id']];
        }
        //ddd($array_1);

        ///
        $array = $array_1 + $array_2;

        //ddd($array);

        $provider = new ArrayDataProvider([
            'allModels' => $array,
            'pagination' => ['pageSize' => -1]
        ]);


        return $this->renderAjax('users_listing', [
            'provider' => $provider,
        ]);

    }

}

