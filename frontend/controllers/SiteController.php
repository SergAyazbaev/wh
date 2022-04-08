<?php

namespace frontend\controllers;


use frontend\models\MailerForm;
use common\models\LoginForm;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;


/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * $session
     */
    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $session->open();
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {


        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],

                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['@'],
//                        'roles' => ['?'], // не авторизован
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],

            ],

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            //            'captcha' => [
            //                'class' => 'yii\captcha\CaptchaAction',
            //                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            //            ],
        ];
    }



    /**
     * {@inheritdoc}
     */
//    public function beforeAction($action)
//    {
//        if ($action->controller->id=='site' && $action->id == 'error') {
//            $this->layout = 'error_site';
//        }
//
//        ddd(Yii::$app->response->statusCode);  ///411
//
//
////        ddd($action->controller->id); // 'site'
//        //            ddd($action->controller);
//
//        return parent::beforeAction($action);
//    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        return $this->render('index');
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        ///
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
                //            return Yii::$app->request->url
            return $this->goBack();
        } else {
            $model->password = '';
        }


        return $this->render('login', [
            'model' => $model,
        ]);

    }


    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {//do we have any
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);//get all cookies
            foreach ($cookies as $cookie) {//loop
                $parts = explode('=', $cookie);//get the bits we need
                $name = trim($parts[0]);
                setcookie($name, '', time() - 1000);//kill it
                setcookie($name, '', time() - 1000, '/');//kill it more
            }
        }

        Yii::$app->user->logout();

        return $this->goHome();
    }


    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    /**
     * Signs user up. Прописка нового пользователя
     *
     * @return mixed
     * @throws Exception
     */
    public function actionSignup()
    {
        $model = new SignupForm();


        ///
        if ($model->load(Yii::$app->request->post())) {

            // ddd($model);

            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }


    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте Ваш e-mail.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }


    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    /**
     * @return string|Response
     */
    public function actionMailer()
    {
        $model = new MailerForm();
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {
            Yii::$app->session->setFlash('mailerFormSubmitted');
            return $this->refresh();
        }
        return $this->render('mailer', [
            'model' => $model,
        ]);
    }


    public function actionError()
    {
        ddd(1111111);
    }

}
