<?php

namespace frontend\controllers;


//use common\models\User;
//use frontend\models\Dialog;
//use frontend\models\Dialogmessage;
use frontend\models\post_dialogmessage;
use Yii;
use yii\filters\VerbFilter;
//use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;


class Chat_historyController extends Controller
{
    public $next_time;

    /**
     * $session
     */
    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $session->open();

        if (!Yii::$app->getUser()->identity) {
            /// Быстрая переадресация
            throw new HttpException(411, 'Необходима авторизация', 2);
        }

    }


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
     * Lists all sprtype models.
     * @return mixed
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;
        //ddd($para);

        ///
        $searchModel = new post_dialogmessage();
        $dataProvider = $searchModel->search($para);

        ///
        $dataProvider->setSort(
            [
                'attributes' => [

                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC]
                    ],

                    'dialog_id',
                    'sender_id',
                    'user_id',
                    'text'
                ],

                'defaultOrder' => ['id' => SORT_DESC]
            ]);


        //ddd($dataProvider->getModels());

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * @return string
     * @throws \Exception
     */
//    public function actionClient()
//    {
//        //User
//        $array_users = ArrayHelper::map(User::find()->all(), 'id', 'username_for_signature');
//
//        $model = new Dialogmessage();
//        $model->dialog_id = '1';
//        $model->user_id = Yii::$app->user->identity->id;
//
//
//
////        $model->user_name = $array_users[Yii::$app->user->identity->id];
//
//        //
//        $array_dialog = ArrayHelper::map(Dialog::find()->all(), 'id', 'name');
//
////        ddd($model);
//
//        return $this->render('index', [
//            'model' => $model,
//            'array_dialog' => $array_dialog
//        ]);
//    }


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
}


//class MessageEvent extends Event
//{
//    public $message;
//}
