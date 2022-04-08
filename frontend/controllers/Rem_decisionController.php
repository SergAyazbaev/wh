<?php

namespace frontend\controllers;


use frontend\models\post_rem_decision;

//use frontend\models\post_rem_nepoladki;
//use frontend\models\post_spr_globam;
use frontend\models\Rem_decision;
//use frontend\models\Rem_nepoladki;
use frontend\models\Spr_globam;
//use frontend\components\MyHelpers;
use Yii;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class Rem_decisionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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

        $searchModel = new post_rem_decision();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
//            'sort' => $sort,
        ]);
    }


    /**
     * Displays a single sprtype model.
     *
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the sprtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Spr_globam|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Spr_globam::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Rem_decision();
        $model->id = Rem_decision::setNext_max_id();


        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)$model->id;

            if (!$model->save()) {

                return $this->redirect(['/rem_decision']);
            }


        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new sprtype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate_opt()
    {

        $model = new Rem_decision();
        $model->id = Rem_decision::setNext_max_id();


        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)$model->id;


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


            foreach ($reshenia2 as $item) {
                $model = new Rem_decision();
                $model->id = Rem_decision::setNext_max_id();
                $model->tx = '';
                $model->name = $item;

                if (!$model->save()) {
                    ddd($model->errors);
                }

//                    return $this->redirect(['/rem_decision']);

            }

        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing sprtype model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {

            $model->id = (int)$model->id;
            $model->delete = (int)$model->delete;

            //ddd($model);


            //             if(
            $model->save(true);
            //             ){
            //                 //ddd($model);
            //             }

            return $this->redirect(['/globalam']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing sprtype model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
}
