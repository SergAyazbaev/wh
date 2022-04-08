<?php

namespace frontend\controllers;


use frontend\models\postsprwhtop;
use frontend\models\Sprwhelement;
use frontend\models\Sprwhelement_old;
use frontend\models\Sprwhtop;
use Yii;
use yii\mongodb\Exception;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SprTypeController implements the CRUD actions for sprtype model.
 */
class SprwhtopController extends Controller
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
                    'index' => [ 'GET' ],
                    'view' => [ 'GET' ],
                    'create' => [
                        'GET',
                        'POST',
                    ],
                    'update' => [
                        'GET',
                        'PUT',
                        'POST',
                    ],
                    'delete' => [
                        'POST',
                        'DELETE',
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all sprtype models.
     *
     * @return mixed
     * @throws Exception
     */
    public function actionIndex()
    {
        $searchModel = new postsprwhtop();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );

        $dataProvider->setSort([
            'defaultOrder' => ['name' => SORT_ASC],]);


        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
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
        $max_value = Sprwhtop::find()->max( 'id' );
        $max_value++;

        $model = new Sprwhtop();
        $model->id = $max_value;


        if ( $model->load( Yii::$app->request->post() ) ) {
            $model->id = (integer)$model->id;
            $model->parent_id = (integer)$model->parent_id;
            $model->final_destination = (int)$model->final_destination;

            if ( $model->save( true ) ) {
                return $this->redirect( [ '/sprwhtop' ] );
            }
        }

        return $this->render( 'create', [ 'model' => $model, ] );
    }

    /**
     * Редактируем TOP WH
     * =
     *
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate( $id )
    {
        $model = Sprwhtop::findModel( $id );


        if ( $model->load( Yii::$app->request->post() ) ) {
            //        'deactive' => '0'
            //        'f_first_bort' => '1'

            ///PARENT-id
            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->final_destination = (int)$model->final_destination;
            $model->deactive = (int)$model->deactive;
            $model->buses_variant = (int)$model->buses_variant;


            // Применяем 1. Ко всем детям ЭТОГО РОДИТЕЛЯ
            if ( (int)$model->buses_variant == 1 ) {
                //ddd($model);

                /// CS
                Sprwhelement::setFinal_Destination( $model->id, $model->final_destination );

                /// Deactive
                Sprwhelement::setDeactive( $model->id, $model->deactive );

                /// BORT code is first
                Sprwhelement::setBort_is_first( $model->id, $model->f_first_bort );


                ///    * Получить Список ВСЕХ элементов в группе
                $array_elements_id = Sprwhelement::Array_id_parent_id( (int)$model->id );


                //// Проходим с проверкой по всем элементам в группе
                if ( isset( $array_elements_id ) ) {
                    foreach ( $array_elements_id as $itemm_id ) {
                        $model_spr_element = Sprwhelement::findModelDouble( $itemm_id );
                        if ( isset( $model_spr_element ) ) {
                            //ddd( $model_spr_element );

                            //  * Функция приведения записей по полям ГОС и БОРТ в норму.
                            //$model_spr_element = SprwhelementController::Normalise_GOS_BORT( $model_spr_element );

                            ///SAVE
                            $model_spr_element->save( true );

                        }
                    }
                }

            }


            if ( $model->save( true ) ) {
                return $this->redirect( [ '/sprwhtop' ] );
            }


        }


        return $this->render(
            'update',
            [ 'model' => $model, ]
        );
    }


    /**
     * Редактируем TOP WH
     * =
     *
     * @param integer $_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate_ss( $id )
    {
        $model = Sprwhtop::findModel( $id );


        if ( $model->load( Yii::$app->request->post() ) ) {
            //        'deactive' => '0'
            //        'f_first_bort' => '1'

            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->final_destination = (int)$model->final_destination;
            $model->deactive = (int)$model->deactive;
            $model->buses_variant = (int)$model->buses_variant;


            if ( $model->save( true ) ) {
                return $this->redirect( [ '/sprwhtop' ] );
            }
        }


        return $this->render(
            '_form_ss',
            [ 'model' => $model, ]
        );
    }

    /**
     * FLAGS. Специально для Админов,
     *=
     * Жанель, Талагат
     * -
     *
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionFlags( $id )
    {
        $model = Sprwhtop::findModel( $id );


        if ( $model->load( Yii::$app->request->post() ) ) {

            $model->id = (int)$model->id;
            $model->parent_id = (int)$model->parent_id;
            $model->final_destination = (int)$model->final_destination;
            $model->buses_variant = (int)$model->buses_variant;


            // Применяем 1. Ко всем детям ЭТОГО РОДИТЕЛЯ
            if ( $model->buses_variant == 1 ) {
                Sprwhelement::setFinal_Destination( $model->id, $model->final_destination );
            }

            if ( $model->save( true ) ) {
                return $this->redirect( [ '/sprwhtop' ] );
            }


        }


        return $this->render(
            '_form_flags',
            [ 'model' => $model, ]
        );
    }


    /**
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete()
    {
        $para = Yii::$app->request->get();
        $model = Sprwhtop::findModel( $para[ 'id' ] );
        //ddd($model);


        if ( isset( $model ) ) {


            if ( isset( $model->delete_sign ) && $model->delete_sign == 1 ) {

                $model->delete_sign = 0;
                $model->delete_sign_user_id = Yii::$app->getUser()->identity->id;
                $model->date_delete = date( 'd.m.Y H:i:s', strtotime( 'now' ) );

                //     * Восстанавливаем признак УДАЛЕНИЯ в ноль "0".
                //     * Откат Удаления.
                if ( !$this->actionDel_ReverceWHelements( $model->id ) ) {
                    throw new NotFoundHttpException( 'No Delete.Error' );
                }

            } else{
                $model->delete_sign = 1; // Типа УДАЛЯЕМ
                $model->delete_sign_user_id = Yii::$app->getUser()->identity->id; // Who Deleted This
                $model->date_delete = date( 'd.m.Y H:i:s', strtotime( 'now' ) );

                // Устанавливаем ОТМЕТКИ  "Удалено" для всех участников данной подгуппы
                if ( !$this->actionDeleteWHelements( $model->id ) ) {
                    throw new NotFoundHttpException( 'Delete.Error' );
                }
            }

            //ddd($model);

            if ( !$model->save( true ) ) {
                ddd( $model->errors );
            }

        }


        // Возвтрат по РЕФЕРАЛУ
        $url_array = Yii::$app->request->headers;
        $url = $url_array[ 'referer' ];

        return $this->goBack( $url );

        //        $this->findModel($id)->delete();
        //        return $this->redirect(['index']);
    }

    /**
     * Устанавливаем ОТМЕТКИ  "Удалено" для всех участников данной подгуппы
     *
     * @param $parent_id
     * @return bool
     */
    public function actionDeleteWHelements( $parent_id )
    {
        if ( isset( $parent_id ) ) {
            $model = Sprwhelement::updateAll(
                [
                    'delete_sign' => 1,
                    'delete_sign_user_id' => Yii::$app->getUser()->identity->id,
                    // Who Deleted This
                    'date_delete' => date( 'd.m.Y H:i:s', strtotime( 'now' ) ),
                ],
                [ 'parent_id' => $parent_id ]
            );
        }

        if ( $model > 0 ) {
            return true;
        } else{
            return false;
        }
    }


    /**
     * Move TO History (ERASE)
     * Удаляем в КОРЗИНУ (в резервную базу. В историю )
     */
    public function actionErase()
    {
        $para = Yii::$app->request->get();

        // Одна ЗАПИСЬ
        $model_top = Sprwhtop::findModel( $para[ 'id' ] );
        if ( $model_top->delete_sign <> 1 || empty( $model_top->date_delete ) ) {
            throw new NotFoundHttpException( 'Erase_WH_element.Нет данных для передачи в ИСТОРИЮ' );
        }

        //AS ARRAY !!!!
        $model_element = Sprwhelement::findAll_Parent_as_Array( $model_top->id );

        ///
        ///  Level ELEMENT to SAVE
        ///

        $errors = 0;
        $xx = 0;
        while ( count( $model_element ) > $xx ) {
            /// NEW
            $model_old_element = new Sprwhelement_old();

            $model_old_element->id = (int)$model_element[ $xx ][ 'id' ];
            $model_old_element->parent_id = (int)$model_element[ $xx ][ 'parent_id' ];
            $model_old_element->name = $model_element[ $xx ][ 'name' ];
            $model_old_element->tx = $model_element[ $xx ][ 'tx' ];

            $model_old_element->parent_name = $model_top->name;
            $model_old_element->parent_name_tx = $model_top->tx;

            $model_old_element->nomer_borta = $model_element[ $xx ][ 'nomer_borta' ];
            $model_old_element->nomer_gos_registr = $model_element[ $xx ][ 'nomer_gos_registr' ];
            $model_old_element->nomer_vin = $model_element[ $xx ][ 'nomer_vin' ];

            $model_old_element->date_delete = $model_element[ $xx ][ 'date_delete' ];
            $model_old_element->delete_sign_user_id = $model_element[ $xx ][ 'delete_sign_user_id' ];


            if ( $model_old_element->validate( true ) ) {
                $model_old_element->save();
            } else{
                $errors++;
                ddd( $model_old_element );
                //throw new NotFoundHttpException('Erase_WH_element.Не завершилось копирование в ИСТОРИЮ');
            }

            $xx++;

        }


        $model_element2 = Sprwhelement::findAll_Elements_Parent2( $model_top->id );
        foreach ( $model_element2 as $item ) {
            $item->delete();
        }

        $model_top->delete();

        return true;
    }


    /**
     * Внимание! Замена!
     * Копия из Активной базы (sprwh_top + sprwh_element)
     * создается в одину коллекцию - SprWH_element_old
     * LEVEL TOP  SAVE (Erase)
     *
     * @param $id
     */
    public function Erase_WH_top( $id )
    {
        //        $model_top      = Sprwhtop::findModel( $id ); // Одна ЗАПИСЬ
        //        ///
        //        ///  Level TOP to SAVE
        //        ///
        //        $model_old_top      = new Sprwhtop_old();
        //        ///
        //        $model_old_top->attributes = $model_top->attributes;
        //
        //        $model_old_top->move_sign_user_id   = Yii::$app->getUser()->identity->id;
        //        $model_old_top->date_move           = date('d.m.Y H:i:s', strtotime('now'));
        //
        //
        //        //dd($model_old_top);
        //
        //        if ( !$model_old_top->save(true)  ) {
        //            throw new NotFoundHttpException('Erase_WH_top.Не завершилось копирование в ИСТОРИЮ');
        //        }
        //
        //        return $model_old_top;
    }


    /**
     * Восстанавливаем признак УДАЛЕНИЯ в ноль "0".
     * Откат Удаления.
     *
     * @param $parent_id
     * @return bool
     */
    public function actionDel_ReverceWHelements( $parent_id )
    {
        if ( isset( $parent_id ) ) {
            $model = Sprwhelement::updateAll(
                [
                    'status' => '',
                    'delete_sign' => 0,
                    'delete_sign_user_id' => Yii::$app->getUser()->identity->id,
                    // Who Deleted This
                    'date_delete' => date( 'd.m.Y H:i:s', strtotime( 'now' ) ),
                ],
                [ 'parent_id' => $parent_id ]
            );
        }

        if ( $model > 0 ) {
            return true;
        } else{
            return false;
        }
    }

    /**
     * Displays a single sprtype model.
     *
     * @param $id
     * @return mixed
     */
    public function actionView( $id )
    {
        return $this->render( 'view', [ 'model' => $this->findModel( $id ), ] );
    }


}
