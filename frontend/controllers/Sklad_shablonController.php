<?php

namespace frontend\controllers;

use frontend\models\postsklad_shablon;
use frontend\models\Shablon;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam_element;
use frontend\components\MyHelpers;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;


/**
 * PvController implements the CRUD actions for pv model.
 */
class Sklad_shablonController extends Controller
{

    /**
     * @param $event
     * @return bool|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($event)
    {
        if ( Yii::$app->getUser()->identity->group_id < 40) {
            //            return $this->redirect(['/']);
            throw new NotFoundHttpException('Доступ только группе SKLAD');
        }

        return parent::beforeAction($event);
    }


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
     * Lists all pv models.
     * @return mixed
     */

    public function actionIndex()
    {

        $para = Yii::$app->request->queryParams;

        $session = Yii::$app->session;
        $session->open();

        if (isset($para['otbor']) && !empty($para['otbor'])) {
            //$sklad= Yii::$app->params['sklad'] = $para['otbor'];
            $session->set('sklad_', $para['otbor']);
        }

        $sklad=$session->get('sklad_');
//        dd($sklad);


        if (!isset($para['posttz'])) {
            $para['posttz']['dt_deadline1'] = date('d.m.Y', strtotime('now -7 day'));
            $para['posttz']['dt_deadline2'] = date('d.m.Y', strtotime('now +7 day'));
        }

        $searchModel_shablon = new postsklad_shablon();
        $dataProvider_shablon = $searchModel_shablon->search($para);

        return $this->render('index', [
            'searchModel_shablon' => $searchModel_shablon,
            'dataProvider_shablon' => $dataProvider_shablon,
            'para' => $para,
            'sklad' => $sklad,
        ]);
    }



    /**
     * Displays a single pv model.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView( $id )
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    /**
     * Creates a new pv model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $max_value = Shablon::find()->max('id');
        $max_value++;

        $model = new Shablon();
        $model->id = $max_value;


        if ($model->load(Yii::$app->request->post())){
            $model->user_id = Yii::$app->user->identity->id;
            $model->user_name = Yii::$app->user->identity->username;
            $model->user_group_id = Yii::$app->user->identity->group_id;

//            dd($model);

            $model->id = (integer) $model->id;

            if ( $model->save(true))
                return $this->redirect(['/sklad_shablon']);
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Эта функция применяется для подстановки
     * Целого массива ТК (Типового комплекта) в
     * Массив ТЗ (Типового задания)
     *
     * @param $tz_id
     * @param $id_tk
     * @param $text
     * @return bool|string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionCreatenext( $id_tz, $id_tk )
    {
        if ( $id_tz )
        $model = $this->findModel($id_tz);
        else
            throw new NotFoundHttpException('Createnext.Не хватает параметра');

        $xx = Shablon::find()->where(['id' => (integer)$id_tk])->one();
        /// ДВА МАССИВА

        $model->array_tk = $xx['array_tk']; // Списание простое
        $model->array_tk_amort = $xx['array_tk_amort'];  // Амортизация

        $model->tk_top = $id_tk;  // Список-дропдаун
        $model->wh_cred_top = $_REQUEST['val12']['val_2_1'];
        $model->wh_cred_top_name = $_REQUEST['val12']['val_2_2'];


        if ($model->load(Yii::$app->request->post())){
            $model->id = (integer)$model->id;
            if ($model->save(true))
                if (MyHelpers::Mongo_save('tz', 'wh_cred_top', $model->_id, $model->wh_cred_top))
                    if (MyHelpers::Mongo_save('tz', 'wh_cred_top_name', $model->_id, $model->wh_cred_top_name))
                        return $this->redirect(['/tz/?sort=dt_deadline']);
         }


        return $this->renderAjax('update_tz', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing pv model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate( $id )
    {
        $model = $this->findModel($id);
        //  dd($model);

        if ($model->load(Yii::$app->request->post())) {
                $model->id = (integer)$model->id;

            if ($model->save())
                return $this->redirect('/sklad_shablon');
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Просто КОПИЯ этой накладной с новым номером
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionCopyfromshablon($id)
    {
        //dd(123);

//        $session = Yii::$app->session;
//        $sklad = $session->get('sklad_');

        $model = Shablon::findModelDouble($id) ;  /// this is  _id !!!!!
//        dd($model);

        $max_value = Shablon::find()->max('id');
        $max_value++;

        $new_doc = new Shablon();

        ///Сливаем в новую накладную старую копию и дописываем новый номер

        $new_doc->id = (integer)$max_value;

        $new_doc['shablon_name']  =$model['shablon_name']." (Копия)";

            $new_doc->user_id     = (integer) Yii::$app->getUser()->identity->id;;
            $new_doc->user_name   = Yii::$app->getUser()->identity->username;
            $new_doc->user_group_id= Yii::$app->getUser()->identity->group_id;

        $new_doc['array_tk_amort']   =$model['array_tk_amort'];
        $new_doc['array_tk']         =$model['array_tk'];




        //dd($new_doc);


        $new_doc->save(true);

        return $this->redirect('/sklad_shablon');
    }


    /**
     * Deletes an existing pv model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete( $id )
    {
        $this->findModel($id)->delete();
        return $this->redirect(['/sklad_shablon']);
    }


    /**
     * Finds the pv model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Shablon|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id )
    {
        if (($model = Shablon::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Не найдена страница.');
    }


    /**
     * Справочник элементов прямого списания
     * @param $id
     * @return string
     */
    public function actionList( $id=0 )
    {

        $model =
            html::dropdownList(
                'name_id',
                0,
                ArrayHelper::map(Spr_glob_element::find()
                    ->where(['parent_id' => (integer)$id])
                    ->all(), 'id', 'name'),
                ['prompt' => 'Выбор ...']
            );

        return $model;
    }


    /**
     * Справочник списания по амортизации
     * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     *
     * @param $id
     * @return string
     */
    public function actionListamort( $id=0 )  //ok
    {
        $model =
            html::dropdownList(
                'name_id_amort',
                0, ArrayHelper::map(
                Spr_globam_element::find()
                        ->where(['parent_id' =>(integer) $id])
                        ->all()
              , 'id', 'name'),
                ['prompt' => 'Выбор ...']
            );

        return $model;
    }


    /**
     *  * Подтяивает из таблицы  Element
     *  логическое поле ДА-НЕТ
     *  INTELEGENT ( Штрихкод, интелектуально устройство )
     *
     * @param $id
     * @return mixed
     */
    public function actionListamort_logic($id)
    {
        $model = Spr_globam_element::find()
            ->asArray()
            ->where(['id'=>(integer) $id])
            ->one();

        //dd($model['intelligent']);
        return $model['intelligent'];    /// 1 - 0
    }


    /**
     * @param int $id
     * @return mixed
     */
    public function actionList_parent_id_amort($id = 0 )
    {
        $model = Spr_globam_element::find()
                ->where(['id' => (integer)$id])
                ->one();
        if (isset($model['parent_id']) && !empty($model['parent_id']))
            return $model['parent_id'];
        else
            return 0;
    }


    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     * @param $id
     * @return string
     */
    public function actionList_parent_id($id = 0 )
    {
        $model =
            Spr_glob_element::find()
                ->where(['id' => (integer)$id])
                ->one();

//        dd($model['ed_izm']);
        return $model['parent_id'];
    }


    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     * @param $id
     * @return string
     *
     * ТОЛЬКО НЕ!!!! НЕ АСУОП !!!
     */
    public function actionList_ed_izm($id = 0 )
    {
        $model =
            Spr_glob_element::find()
                ->where(['id' => (integer)$id])
                ->one();

//        dd($model['ed_izm']);


        return $model['ed_izm'];
    }



}
