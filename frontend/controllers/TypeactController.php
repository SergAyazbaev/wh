<?php

namespace frontend\controllers;


use frontend\models\postpvtypeact;
use frontend\models\Typeact;
use frontend\components\MyHelpers;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * PvRestoreController implements the CRUD actions for pvrestore model.
 */
class TypeactController extends Controller
{
    /**
     * @throws NotFoundHttpException
     */
    public function init()
    {
        parent::init();

        //        if (! Yii::$app->user->id) {
        if ( !Yii::$app->user->identity ) {
            throw new NotFoundHttpException( 'Необходима авторизация' );
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
                    'delete' => [ 'POST' ],
                ],
            ],
        ];
    }

    /**
     * Lists all pvrestore models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        //dd($_REQUEST);

        $searchModel = new postpvtypeact();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );

        //dd($searchModel);

        return $this->render(
            'index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
                   ]
        );
    }

    /**
     * Displays a single pvrestore model.
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView( $id )
    {
        return $this->render(
            'view', [
                      'model' => $this->findModel( $id ),
                  ]
        );
    }

    /**
     * Creates a new pvrestore model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Typeact();

        $max_value = MyHelpers::Mongo_max_id( 'spr_type_act', 'id' );
        $max_value++;
        $model->id = $max_value;

        //dd($max_value);


        if ( $model->load( Yii::$app->request->post() ) && $model->save( true ) ) {

//            if (MyHelpers::Mongo_save_id('spr_type_motion', $model->_id, (integer) $max_value ) )
//            {
            return $this->redirect( [ '/typeact' ] );

//            }

            //            if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //            return $this->redirect(['view', 'id' => (string)$model->_id]);
        }


        return $this->render(
            'create', [
            'model' => $model,
                    ]
        );
    }

    /**
     * Updates an existing pvrestore model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate( $id )
    {
        $model = $this->findModel( $id );

        //dd($model->id);

        if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
            if ( MyHelpers::Mongo_save( 'spr_type_act', 'id', $model->_id, $model->id ) ) {
                return $this->redirect( [ '/typeact' ] );
            }
        }

        return $this->render(
            'update', [
            'model' => $model,
                    ]
        );
    }

    /**
     * Deletes an existing pvrestore model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete( $id )
    {
        $this->findModel( $id )->delete();

        return $this->redirect( [ 'index' ] );
    }

    /**
     * Finds the pvrestore model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $id
     * @return Typeact|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id )
    {
        if ( ( $model = Typeact::findOne( $id ) ) !== null ) {
            return $model;
        }

        throw new NotFoundHttpException( 'The requested page does not exist.' );
    }
}
