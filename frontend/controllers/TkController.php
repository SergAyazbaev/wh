<?php
namespace frontend\controllers;

use frontend\models\post_spr_globam_element;
use frontend\models\posttk;
use frontend\models\Spr_glob_element;
use frontend\models\Spr_globam_element;
use frontend\models\Tk;

use Yii;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * PvController implements the CRUD actions for pv model.
 */
class TkController extends Controller
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


    public function beforeAction( $event )
    {
        if ( Yii::$app->getUser()->identity->group_id < 50 ) {
            throw new NotFoundHttpException( Yii::$app->controller->id.'. Доступ только группе главного инженера' );
        }

        return parent::beforeAction( $event );
    }




    /**
     * Lists all pv models.
     *
     * @return mixed
     */

    public function actionIndex()
    {
        $para = Yii::$app->request->queryParams;

        $searchModel = new posttk();
        $dataProvider = $searchModel->search( $para );


//            dd($dataProvider);

//            if ( isset($para['print']) && $para['print']==1)
//            {
//                $dataProvider ->pagination = ['pageSize' =>-1];
//
//                $str = $this->render('print_excel', [
//                            //'dataProvider' => pv::find()->asArray()->all(),
//                        'dataProvider' => $dataProvider,
//                        'dataModels' => $dataProvider->getModels()
//                    ]);
//
//                    echo $str;
//                    return;
//            }


        //if ( isset($para) && !empty($para)) $para=$para; else $para=[];


        return $this->render(
            'index', [
                       'searchModel' => $searchModel,
                       'dataProvider' => $dataProvider,
                   ]
        );
    }


    /**
     * Displays a single pv model.
     *
     * @param $id
     *
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
     * Creates a new pv model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $max_value = Tk::find()->max( "id" );
        $max_value++;

        $model = new Tk();
        $model->id = $max_value;

        $para = Yii::$app->request->queryParams;

        $searchModel = new posttk();
        $dataProvider = $searchModel->search( $para );

        $model_table_tk = Tk::find()->all();


        /////////// ПРЕД СОХРАНЕНИЕМ
        if ( $model->load( Yii::$app->request->post() ) ) {

            $model->id = (integer)$max_value;
            $model->dt_create = date( 'd.m.Y H:i:s', strtotime( 'now' ) );


            if ( $model->save( true ) )
                return $this->redirect( [ '/tk/update_tk?id='.$model->_id ] );
            else
                ddd( $model->errors );
        }


        return $this->render(
            '_form', [
                        'model' => $model,
                        'model_table_tk' => $model_table_tk,
                        'dataProvider' => $dataProvider,
                        'searchModel' => $searchModel,

                    ]
        );
    }



//    /**
//     * Updates an existing pv model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param $id
//     * @return mixed
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionUpdate($id)
//    {
//        $model = Tk::findModel($id);  /// this is  _id !!!!!
//
//        if ($model->load(Yii::$app->request->post())){
//            dd($model);
//
//            if($model->save())
////            ////////////
////            $collection = Yii::$app->mongodb->getCollection('tk');
////            $collection->update(['_id' => $model->_id ],
////                [
////                    'dt_create_mongo' => MyHelpers::to_isoDate($model->dt_create),
////                ]
////            );
//
//            return $this->redirect(['/tk/index?sort=-id']);
//
//
//        }
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//
//    }


    /**
     * ТИПОВОЙ КОМПЛЕКТ.
     * -
     *
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate_tk( $id )
    {
        $model = Tk::findModel( $id );

        /// Тут буду делать промежуточную-связующию таблицу
        if ( $model->load( Yii::$app->request->post() ) ) {
            $model->id = (integer)$model->id;
            $model->dt_edit = date( 'd.m.Y H:i:s', strtotime( 'now' ) );

            if ( $model->save( true ) )
                return $this->redirect( [ '/tk/index?sort=-id' ] );
        }

        $xx = posttk::find()->all();
        $type_words = ArrayHelper::getColumn( $xx, 'name_tk' );

        return $this->render(
            '_form_tk',
            [
                'model' => $model,
                'type_words' => $type_words,
            ]
        );
    }


    /**
     * Deletes an existing pv model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws StaleObjectException
     */
    public function actionDelete( $id )
    {
        $this->findModel( $id )->delete();

        return $this->redirect( [ 'index' ] );
    }

    /**
     * Finds the pv model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $id
     *
     * @return Tk|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id )
    {
        if ( ( $model = Tk::findOne( $id ) ) !== null ) {
            return $model;
        }

        throw new NotFoundHttpException( 'The requested page does not exist.' );
    }

    protected function findModel_act( $id )
    {
        if ( ( $model = Tk::find()->where( [ 'id' => $id ] )->all() ) !== null ) {
            return $model;
        }

        throw new NotFoundHttpException( 'The requested page does not exist.' );
    }


    /**
     * @param $id
     *
     * @return string
     */
    public function actionList( $id )
    {
        $model =
            html::dropdownList(
                'name_id',
                0,
                ArrayHelper::map(
                    Spr_glob_element::find()
                        ->where( [ 'parent_id' => (integer)$id ] )
                        ->all(),
                    'id', 'name'
                ),
                [ 'prompt' => 'Выбор ...' ]
            );

        return $model;
    }


    /**
     * @param $id
     *
     * @return string
     */
    public function actionListamort( $id )
    {
        $model =
            html::dropdownList(
                'name_id_amort',
                0,
                ArrayHelper::map(
                    spr_globam_element::find()
                        ->where( [ 'parent_id' => (integer)$id ] )
                        ->all(),
                    'id', 'name'
                ),
                [ 'prompt' => 'Выбор ...' ]
            );
        return $model;
    }


    /**
     * Подтягивает из таблицы  Element
     *  логическое поле ДА-НЕТ
     *  INTELLIGENT ( Штрихкод, интелектуально устройство )
     *
     * @param $id
     *
     * @return string
     */
    public function actionListamort_logic( $id )
    {
        $model = Spr_globam_element::find()
            ->asArray()
            ->where( [ 'id' => (integer)$id ] )
            ->one();

        //dd($model['intelligent']);
        return $model[ 'intelligent' ];
    }


    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
     *
     * @return string
     */
    public function actionList_ed_izm( $id = 0 )
    {
        $model =
            Spr_glob_element::find()
                ->where( [ 'id' => (integer)$id ] )
                ->one();

        return $model[ 'ed_izm' ];
    }


    /**
     * @param int $id
     *
     * @return mixed
     */
    public function actionList_parent_id_amort( $id = 0 )
    {
        $model =
            post_spr_globam_element::find()
                ->where( [ 'id' => (integer)$id ] )
                ->one();

        return $model[ 'parent_id' ];
    }

    /**
     * ЛистАморт Используется
     * Справочник Штуки, Метры, Литры
     *
     * @param $id
     *
     * @return string
     */
    public function actionList_parent_id( $id = 0 )
    {
        $model =
            Spr_glob_element::find()
                ->where( [ 'id' => (integer)$id ] )
                ->one();
        return $model[ 'parent_id' ];
    }


}

