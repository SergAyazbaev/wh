<?php

namespace frontend\controllers;

use frontend\models\Sklad;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use frontend\models\postsklad;

//use MongoDB\Driver\Exception\Exception;


class Agent_thaController extends Controller
{
    public $sklad_tha;


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
            'verbs' => ['class' => VerbFilter::className(),
                'actions' => [
                    'update' => ['POST', 'GET', 'GET'],
                ],
            ],
        ];
    }

    /**
     * @param $event
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($event)
    {

        if (Yii::$app->getUser()->identity->group_id != 30)
            throw new NotFoundHttpException('Доступ только для - Агент ТХА');

        return parent::beforeAction($event);
    }


    /**
     * @return string
     * @throws HttpException
     */
    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;

        if (isset($para['otbor']))
            $sklad_tha = $para['otbor'];
        else
            $sklad_tha = 0;

        $searchModel = new postsklad();
        $dataProvider = $searchModel->search($para);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'para' => $para,
            'sklad' => $sklad_tha,
        ]);
    }


    /**
     * THA-Agent (Славик).
     * Подтрверждает отправку/согласование с ТХА
     *
     */

    public function actionUdate_tha($id)
    {

        $model = Sklad::findModel($id);  /// this is  _id !!!!!  SKLAD !


        if ($model->load(Yii::$app->request->post())) {
            $sklad = $model['wh_home_number'];

            ///Сливаем во едино базу с коментами Агента ТХА
            $old_xx = $model->oldAttributes;
            $new_xx = $model->attributes;

            $xx22_old = $old_xx['array_tk_amort'];
            $xx22_new = $new_xx['array_tk_amort'];

            $x = 0;
            while (isset($xx22_old[$x])) {
                $xx22_old[$x]['the_bird'] = $xx22_new[$x]['the_bird'];
                $xx22_old[$x]['tx'] = $xx22_new[$x]['tx'];
                $x++;
            }

            $model['array_tk_amort'] = $xx22_old;


//                dd($model);
            if (!$model->save(false)) {
                dd($model->errors);
            }

            return $this->redirect('/agent_tha?otbor=' . $sklad);
        }


        return $this->render('_form', [
            'model' => $model,
        ]);

    }


    public function actionMail_to_tha($id) // Sklad/
    {
        // $session = Yii::$app->session;
        // $sklad = $session->get('sklad_');

        $model = $this->findModel($id);  /// this is  _id !!!!!

        return $this->render('mail_to_tha/_form', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Sklad|null
     * @throws NotFoundHttpException
     */
    protected function findModel( $id)
    {
        if (($model = Sklad::findOne($id)) !== null) {
            return $model;
        }
         throw new NotFoundHttpException('Ответ на запрос. Этого нет в складе');
//        return false;
    }

    public function actionDelete($id, $adres_to_return = "")
    {
        $this->findModel($id)->delete();
        return $this->redirect(['/sklad/' . $adres_to_return]);
    }

    /**
     * @param mixed $otbor
     */
    public function setOtbor($otbor)
    {
        $this->otbor = $otbor;
    }


}
