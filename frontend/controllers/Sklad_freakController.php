<?php

	namespace frontend\controllers;


    use frontend\models\MailerForm;
    use frontend\models\post_spr_globam;
    use frontend\models\post_spr_globam_element;
    use frontend\models\postsklad;
    use frontend\models\postsklad_shablon;
    use frontend\models\postsklad_transfer;
    use frontend\models\posttz;
    use frontend\models\post_spr_glob_element;
    use frontend\models\Shablon;
    use frontend\models\Sklad;
    use frontend\models\Sklad_transfer;
    use frontend\models\Spr_glob;
    use frontend\models\Spr_glob_element;
    use frontend\models\Spr_globam;
    use frontend\models\Spr_globam_element;
    use frontend\models\Spr_things;
    use frontend\models\Sprwhelement;
    use frontend\models\Tz;
	use frontend\components\MyHelpers;
	use Mpdf\Mpdf;
	use Mpdf\MpdfException;
	use Picqer\Barcode\Exceptions\BarcodeException;
	use Yii;
	use yii\base\Controller;
	use yii\base\ExitException;
	use yii\db\StaleObjectException;
	use yii\filters\VerbFilter;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\web\BadRequestHttpException;
    use yii\web\HttpException;
    use yii\web\NotFoundHttpException;
	use yii\web\Response;
	use yii\web\UnauthorizedHttpException;


	class Sklad_freakController extends Controller {

		public $sklad;


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
		public function behaviors() {
			return [
				'verbs' => [
					'class' => VerbFilter::className(),

					'actions' => [
						'in'         => [ 'GET' ],
						// Главная страница
						//						'update'     => [
						//							'GET',
						//							'POST',
						//						],
						// Редактирование НАКЛАДНОЙ
						'create_new' => [
							'GET',
							'POST',
						],
						// КНОПКА создать новую наклданую
						'prihod2'    => [
							'GET',
							'POST',
						],
						// Принятие накладной из ПРИХОДА

						'createfromtz'       => [
							'GET',
							'POST',
						],
						// Принятие накладной из Createfromtz
						'createfrom_shablon' => [
							'GET',
							'POST',
						],
						// Принятие накладной из Createfrom_shablon

						'create_from_cs' => [ 'GET' ],
						// Принятие накладной из Createfrom_shablon
						'from_cs'        => [
							'POST',
							'GET',
						],
						// Принятие накладной из ЦС


						'copy-to-transfer' => [ 'GET' ],
						// ПЕРЕДАЧА В БУФЕР ОБМЕНА CopyToTransfer


						'index'  => [],
						'create' => [],
						'delete' => [],
						'view'   => [],

						//  'delete' => ['POST', 'DELETE'],
					],
				],
			];
		}


		/**
		 * @param $event
		 *
		 * @return bool
		 * @throws BadRequestHttpException
		 */
		public function beforeAction( $event ) {
			//        if ((Yii::$app->getUser()->identity->group_id >= 50 ||
			//            Yii::$app->getUser()->identity->group_id < 40)) {
			//            if ( Yii::$app->getUser()->identity->group_id !=30 )
			//                throw new NotFoundHttpException('Доступ только отрудникам SKLAD');
			//        }
			return parent::beforeAction( $event );
		}


		/**
		 * @return string
		 */
		public function actionIndex() {

			ddd( 123 );

			return $this->redirect( '/' );
		}


		/**
		 * ВКЛАДКИ для Склада
		 * =
		 *
		 * @return string
		 */
		public function actionIn() {

			$para = Yii::$app->request->queryParams;
			//			$print_comand = Yii::$app->request->get();

			////////////
			/// ПЕРЕДАЧА ДАННЫХ ФИЛЬТРА ДЛЯ ПЕЧАТИ ЧЕРЕЗ СЕССИЮ
			///
			$session = Yii::$app->session;
			if( !$session->isActive)
			{
				$session->open();
			}


			///...............................
			///  Работает мой фильтр!!!!!!!!!!!!!
			///

			//			if( isset($para['postsklad']['user_name']) && !empty($para['postsklad']['user_name'])  )
			//			{
			//				$session[ 'user_name' ] = $para['postsklad']['user_name'];
			//
			//				//ddd($para);
			//
			//
			//				$is_save_filter = 0;
			//			}
			//			else{
			//				$session[ 'user_name' ] ='';
			//				$is_save_filter = 1;
			//			}
			//
			//
			//			if( isset( $session[ 'user_name' ] ) && (int)$para[ 'save_filter' ]== 1 ){
			//				$para['postsklad']['user_name']=$session[ 'user_name' ];
			//				if(!empty($para['postsklad']['user_name']))
			//				{
			//					$is_save_filter = 0;
			//				}
			//			}
			//			else{
			//				$is_save_filter = 1;
			//			}
			///
			///  Работает мой фильтр!!!!!!!!!!!!!
			/// ...........................
			////////////


			if(isset( $para[ 'otbor' ] ) && !empty( $para[ 'otbor' ] ))
			{
				//$sklad= Yii::$app->params['sklad'] = $para['otbor'];
				$session->set( 'sklad_', $para[ 'otbor' ] );
			}

			$sklad = $session->get( 'sklad_' );

			if( !isset( $para[ 'otbor' ] ))
			{
				$para[ 'otbor' ] = $sklad;
			}


			$searchModel_tz  = new posttz();
			$dataProvider_tz = $searchModel_tz->search_into( $para );

			$searchModel_into  = new postsklad_transfer();
			$dataProvider_into = $searchModel_into->search_into_wh( $para );

			$searchModel_shablon  = new postsklad_shablon();
			$dataProvider_shablon = $searchModel_shablon->search( $para );

			$searchModel_sklad  = new postsklad();
			$dataProvider_sklad = $searchModel_sklad->search( $para );


			$dataProvider_sklad->setSort(
				[
					'attributes' => [
						'tx' => [
							'asc'     => [ 'tx' => SORT_ASC ],
							'desc'    => [ 'tx' => SORT_DESC ],
							'default' => SORT_DESC,
						],


						'id'        => [
							'asc'     => [ 'id' => SORT_ASC ],
							'desc'    => [ 'id' => SORT_DESC ],
							'default' => SORT_DESC,
						],
						'dt_create' => [
							'asc'     => [ 'dt_create' => SORT_ASC ],
							'desc'    => [ 'dt_create' => SORT_DESC ],
							'default' => SORT_DESC,
						],

						'dt_one_day' => [
							'asc'     => [ 'dt_create_timestamp' => SORT_ASC ],
							'desc'    => [ 'dt_create_timestamp' => SORT_DESC ],
							'default' => SORT_DESC,
						],
						'dt_start'   => [
							'asc'     => [ 'dt_start' => SORT_ASC ],
							'desc'    => [ 'dt_start' => SORT_DESC ],
							'default' => SORT_DESC,
						],

						'wh_dalee_element' => [
							'asc'     => [ 'wh_dalee_element' => SORT_ASC ],
							'desc'    => [ 'wh_dalee_element' => SORT_DESC ],
							'default' => SORT_DESC,
						],

						'user_name' => [
							'asc'     => [ 'user_name' => SORT_ASC ],
							'desc'    => [ 'user_name' => SORT_DESC ],
							'default' => SORT_DESC,
						],


						'sklad_vid_oper',
						'wh_home_number',

						'tz_id',
						'array_count_all',
						'wh_debet_name',
						'wh_debet_element_name',
						'wh_destination_name',
						'wh_destination_element_name',
					],


					'defaultOrder' => [ 'id' => SORT_DESC ],

				] );


			/// Работает  ОДНОДНЕВНАЯ ВЫБОРКА !!!!  через модель поиска
			/// Место только ТУТ!
			if(isset( $para[ 'postsklad' ][ 'dt_start' ] ) && !empty( $para[ 'postsklad' ][ 'dt_start' ] ))
			{
				$searchModel_sklad[ 'dt_start' ] = date( 'd.m.Y', strtotime( $para[ 'postsklad' ][ 'dt_start' ] ) );

			}


			return $this->render(
				'accordion/_form_accordion', [
				'searchModel_tz'  => $searchModel_tz,
				'dataProvider_tz' => $dataProvider_tz,

				'searchModel_into'  => $searchModel_into,
				'dataProvider_into' => $dataProvider_into,

				'searchModel_shablon'  => $searchModel_shablon,
				'dataProvider_shablon' => $dataProvider_shablon,

				'searchModel_sklad'  => $searchModel_sklad,
				'dataProvider_sklad' => $dataProvider_sklad,

				'sklad' => $sklad,

				'is_save_filter' => $is_save_filter,
			] );

		}


		/**
		 * @param int $id
		 * @param int $sklad
		 *
		 * @return string
		 * @throws NotFoundHttpException
		 */
//		public function actionTransfer_delivered( $id = 0, $sklad = 0 ) {
        public function actionTransfer_delivered($id = 0)
        {
			if(Sklad_transfer::setTransfer_delivered( $id, Sklad_transfer::TRANSFERED_OK )) // Получил
			{
				return $this->actionRewrite();
			} else           //return $this->goHome();
			{
				throw new NotFoundHttpException( 'Обратитесь к разработчику' );
			}
		}

		/**
		 * Sklad ASEMTAI
		 * Тут будет функционал перепрошивки CVB-24 по накладным
		 *
		 * @return string
		 */
		public function actionRewrite() // Sklad/
		{
			$para = Yii::$app->request->queryParams;

			if( !isset( $para[ 'otbor' ] ))
			{

				//#############
				$session         = Yii::$app->session;
				$para[ 'otbor' ] = $sklad = $session->get( 'sklad_' );
				//#############

			} else
			{
				$session = Yii::$app->session;
				$session->set( 'sklad_', $para[ 'otbor' ] );
			}

			//        dd($para);

			return $this->redirect( 'in?otbor=' . $sklad );
		}

		/**
		 * @param int $id
		 *
		 * @return string
		 * @throws NotFoundHttpException
		 */
		public function actionTransfer_dont( $id = 0 ) {
			// Отказ получать
			if( !Sklad_transfer::setTransfer_delivered(
				$id, Sklad_transfer::TRANSFERED_REFUSE )) // Отказ получать
			{
				throw new NotFoundHttpException( 'Не установлена отметка об отказе' );
			}

			//return $this->goHome();
			//return $this->redirect('/sklad/in?otbor=' . $sklad);

			return $this->redirect( [ 'sklad/in' ] );

		}

		/**
		 * ВНУТРИ НАКЛАДНОЙ (Производство) ASEMTAI
		 * SKLAD FOR Asemtai
		 *-
		 *
		 * @param $id
		 *
		 * @return string|Response
		 * @throws NotFoundHttpException
		 * @throws UnauthorizedHttpException
		 * @throws ExitException
		 */
		public function actionRewrite_update( $id ) {
			$para = Yii::$app->request->queryParams;

			//$para_post = Yii::$app->request->post();

			//        if($para_post['add_button']){
			//            ddd($para_post);
			//            //            ddd($para_post['add_button']);
			//        }


			if(isset( $para[ 'otbor' ] ) && !empty( $para[ 'otbor' ] ))
			{
				if( !Sklad::setSkladIdActive( $para[ 'otbor' ] ))
				{
					throw new UnauthorizedHttpException( '$_SESSION1 Не подключен.  Sklad=0' );
				}
			}

			if(isset( $para[ 'sklad' ] ) && !empty( $para[ 'sklad' ] ))
			{
				if( !Sklad::setSkladIdActive( $para[ 'sklad' ] ))
				{
					throw new UnauthorizedHttpException( '$_SESSION2 Не подключен.  Sklad=0' );
				}
			}


			////////
			$sklad        = Sklad::getSkladIdActive();         // Активный склад
			$parett_sklad = Sprwhelement::find_parent_id( $sklad ); // Парент айди этого СКЛАДА


			if( !isset( $sklad ) || empty( $sklad ))
			{
				throw new UnauthorizedHttpException( 'REWRITE. Sklad=0' );
			}


			////////
			$model = Sklad::findModel( $id );  /// this is  _id !!!!! //$model->getDtCreateText()

			if( !is_object( $model ))
			{
				throw new NotFoundHttpException( 'Нет такой накладной' );
			}


			/// Автобусы ЕСТЬ?
			if(isset( $model[ 'array_bus' ] ) && !empty( $model[ 'array_bus' ] ))
			{
				$items_auto = Sprwhelement::findAll_Attrib_PE(
					array_map( 'intval', $model[ 'array_bus' ] )
				);
			} else
			{
				$items_auto = [];
			} // ['нет автобусов'];


			/// Получаем ТехЗадание. ШАПКА
			if($model->tz_id)
			{
				$tz_head = Tz::findModelDoubleAsArray( (int) $model->tz_id );
			} else
			{
				$tz_head = [];
			}


			//   ddd($tz_head);


			if((int) $model[ 'sklad_vid_oper' ] == 2)
			{
				$model[ 'sklad_vid_oper_name' ] = 'Приходная накладная';
				$model->wh_destination          = $parett_sklad;  // Мой склад ОТПРАВИТЕЛЬ
				$model->wh_destination_element  = $sklad; // Мой склад ОТПРАВИТЕЛЬ

				//ddd($model);
			}

			if((int) $model[ 'sklad_vid_oper' ] == 3)
			{
				$model[ 'sklad_vid_oper_name' ] = 'Расходная накладная';
				$model->wh_debet_top            = $parett_sklad;       // Мой склад ПОЛУЧАТЕЛЬ
				$model->wh_debet_element        = $sklad;      // Мой склад ПОЛУЧАТЕЛЬ
			}


			/////////// ПРЕД СОХРАНЕНИЕМ
			if($model->load( Yii::$app->request->post() ))
			{

				$model->wh_home_number = (integer) $sklad;


				////  Прописать во все массивы ЕД.Изм и Кол-во
				///
				$model->array_tk_amort = Sklad::setArrayEdIzm_Kolvo( $model->array_tk_amort );


				////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
				///  ТАБ 1
				// $model->array_tk_amort  = Sklad::setArraySort1( $model->array_tk_amort );
				$model->array_tk_amort = Sklad::setArrayClear( $model->array_tk_amort );
				///  ТАБ 2
				$model->array_tk = Sklad::setArraySort2( $model->array_tk );

				////  Приводим ключи в прядок! УСПОКАИВАЕМ ОЧЕРЕДЬ
				$model->array_tk_amort = Sklad::setArrayToNormal( $model->array_tk_amort );
				$model->array_tk       = Sklad::setArrayToNormal( $model->array_tk );
				$model->array_casual   = Sklad::setArrayToNormal( $model->array_casual );


				//                    $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
				//                    $model->update_user_id = (integer)Yii::$app->getUser()->identity->id;;
				//                    $model->update_user_name = Yii::$app->getUser()->identity->username;

				////////////
				//            ddd($model);


				if((int) $model[ 'sklad_vid_oper' ] == 2)
				{
					$model[ 'sklad_vid_oper_name' ] = 'Приходная накладная';
				}
				if((int) $model[ 'sklad_vid_oper' ] == 3)
				{
					$model[ 'sklad_vid_oper_name' ] = 'Расходная накладная';
				}


				///////
				/// ИСТОЧНИК
				$xx1 = Sprwhelement::findFullArray( $model->wh_debet_element );
				/// ПРИЕМНИК
				$xx2 = Sprwhelement::findFullArray( $model->wh_destination_element );

				$model->wh_debet_name         = $xx1[ 'top' ][ 'name' ];
				$model->wh_debet_element_name = $xx1[ 'child' ][ 'name' ];

				$model->wh_destination_name         = $xx2[ 'top' ][ 'name' ];
				$model->wh_destination_element_name = $xx2[ 'child' ][ 'name' ];

				//  ddd($model);


				////////......ПОДСЧЕТ СТРОК
				$xx1 = $xx2 = $xx3 = 0;
				if(isset( $model[ 'array_tk_amort' ] ) && !empty( $model[ 'array_tk_amort' ] )
				   && is_array( $model[ 'array_tk_amort' ] ))
				{
					$xx1 = count( $model[ 'array_tk_amort' ] );
				}

				if(isset( $model[ 'array_tk' ] ) && !empty( $model[ 'array_tk' ] )
				   && is_array( $model[ 'array_tk' ] ))
				{
					$xx2 = count( $model[ 'array_tk' ] );
				}

				if(isset( $model[ 'array_casual' ] ) && !empty( $model[ 'array_casual' ] )
				   && is_array( $model[ 'array_casual' ] ))
				{
					$xx3 = count( $model[ 'array_casual' ] );
				}

				$model[ 'array_count_all' ] = (int) $xx1 + $xx2 + $xx3;


				//            ddd($model);


				if($model->save( true ))
				{
					if(isset( $model[ 'wh_home_number' ] ))
					{
						$sklad = $model[ 'wh_home_number' ];

						return $this->redirect( '/sklad/in?otbor=' . $sklad );
					}

					return $this->actionRewrite();
				}

			}


			return $this->render(
				'sklad_rewrite/_form', [
				'model'      => $model,
				'sklad'      => $sklad,
				'items_auto' => $items_auto,
				'tz_head'    => $tz_head,
			] );

		}


		/**
		 * Просто КОПИЯ этой накладной с новым номером ПО НАЖАТИЮ КНОПКИ "Копия с новым номером"
		 *
		 * @param $id
		 *
		 * @return Response
		 * @throws ExitException
		 * @throws NotFoundHttpException
		 */
		public function actionCopycard_from_origin( $id ) {
			$session = Yii::$app->session;
			$sklad   = $session->get( 'sklad_' );

			$model = Sklad::findModelDouble( $id );  /// this is  _id !!!!!
			//        dd($model);


			$max_value = Sklad::find()->max( 'id' );
			$max_value ++;

			$new_doc = new Sklad();

			///Сливаем в новую накладную старую копию и дописываем новый номер
			//            unset($model->_id);
			//            $new_doc=$model;

			$new_doc->id                 = (integer) $max_value;
			$new_doc[ 'sklad_vid_oper' ] = $model[ 'sklad_vid_oper' ];

			$new_doc[ 'dt_create_timestamp' ] = (int) strtotime( "NOW" );


			$new_doc[ 'wh_home_number' ] = $model[ 'wh_home_number' ];

			$new_doc[ 'wh_debet_top' ]          = $model[ 'wh_debet_top' ];
			$new_doc[ 'wh_debet_name' ]         = $model[ 'wh_debet_name' ];
			$new_doc[ 'wh_debet_element' ]      = $model[ 'wh_debet_element' ];
			$new_doc[ 'wh_debet_element_name' ] = $model[ 'wh_debet_element_name' ];

			$new_doc[ 'wh_destination' ]              = $model[ 'wh_destination' ];
			$new_doc[ 'wh_destination_name' ]         = $model[ 'wh_destination_name' ];
			$new_doc[ 'wh_destination_element' ]      = $model[ 'wh_destination_element' ];
			$new_doc[ 'wh_destination_element_name' ] = $model[ 'wh_destination_element_name' ];

			$new_doc[ 'wh_dalee' ]         = $model[ 'wh_dalee' ];
			$new_doc[ 'wh_dalee_element' ] = $model[ 'wh_dalee_element' ];


			$new_doc[ 'sklad_vid_oper_name' ] = $model[ 'sklad_vid_oper_name' ];
			$new_doc[ 'tz_id' ]               = $model[ 'tz_id' ];
			$new_doc[ 'tz_name' ]             = $model[ 'tz_name' ];
			$new_doc[ 'tz_date' ]             = $model[ 'tz_date' ];
			$new_doc[ 'dt_deadline' ]         = $model[ 'dt_deadline' ];


			$new_doc[ 'array_tk_amort' ] = $model[ 'array_tk_amort' ];
			$new_doc[ 'array_tk' ]       = $model[ 'array_tk' ];
			$new_doc[ 'array_casual' ]   = $model[ 'array_casual' ];
			$new_doc[ 'array_bus' ]      = $model[ 'array_bus' ];


			//ddd($model);

			$new_doc[ 'dt_create' ]     = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
			$new_doc[ 'user_id' ]       = Yii::$app->getUser()->identity->id;
			$new_doc[ 'user_name' ]     = Yii::$app->getUser()->identity->username;
			$new_doc[ 'user_group_id' ] = Yii::$app->getUser()->identity->group_id;


			///////////////
			// ЕСЛИ Склад-Приемник является Целевым Складом (ЦС)
			// и накладная = РАСХОДНАЯ
			///////////////

			if(
				Sprwhelement::is_FinalDestination( $new_doc->wh_destination_element ) &&
				$new_doc->sklad_vid_oper == Sklad::VID_NAKLADNOY_RASHOD
			)
			{
				$new_doc [ 'wh_cs_number' ]              = $new_doc[ 'wh_destination_element' ];
				$new_doc [ 'wh_destination_element_cs' ] = 1;
			}


			$new_doc->save( true );


			return $this->redirect( '/sklad/in?otbor=' . $sklad );
		}


		/**
		 *
		 * Просто КОПИЯ этой накладной с новым номером ПО НАЖАТИЮ КНОПКИ "Расходная накладная"
		 *
		 * actionCopycard_rashod
		 *
		 * @param $id
		 *
		 * @return Response
		 * @throws NotFoundHttpException
		 * @throws ExitException
		 */
		public function actionCopycard_rashod( $id ) {
			$session = Yii::$app->session;
			$sklad   = $session->get( 'sklad_' );

			$model = Sklad::findModelDouble( $id );  /// this is  _id !!!!!
			//  dd($model);

			$max_value = Sklad::find()->max( 'id' );
			$max_value ++;


			$new_doc = new Sklad();


			///Сливаем в новую накладную старую копию и дописываем новый номер
			//    unset($model->_id);
			//    $new_doc=$model;

			$new_doc->id                      = (integer) $max_value;
			$new_doc[ 'sklad_vid_oper' ]      = (int) 3; //РАСХОДНАЯ накладная
			$new_doc[ 'sklad_vid_oper_name' ] = 'Расходная накладная';

			// Получить полный Массив-знаний по ОТПРАВИТЕЛЮ
			$array_request = Sprwhelement::findFullArray( $sklad );
			// dd($array_request);

			//FROM
			$new_doc[ 'wh_home_number' ]        = (integer) $sklad;
			$new_doc[ 'wh_debet_top' ]          = $array_request[ 'top' ][ 'id' ];
			$new_doc[ 'wh_debet_name' ]         = $array_request[ 'top' ][ 'name' ];
			$new_doc[ 'wh_debet_element' ]      = $array_request[ 'child' ][ 'id' ];
			$new_doc[ 'wh_debet_element_name' ] = $array_request[ 'child' ][ 'name' ];

			//TO
			$new_doc[ 'wh_destination' ]              = $model[ 'wh_destination' ];
			$new_doc[ 'wh_destination_name' ]         = $model[ 'wh_destination_name' ];
			$new_doc[ 'wh_destination_element' ]      = $model[ 'wh_destination_element' ];
			$new_doc[ 'wh_destination_element_name' ] = $model[ 'wh_destination_element_name' ];


			$new_doc[ 'tz_id' ]       = $model[ 'tz_id' ];
			$new_doc[ 'tz_name' ]     = $model[ 'tz_name' ];
			$new_doc[ 'tz_date' ]     = $model[ 'tz_date' ];
			$new_doc[ 'dt_deadline' ] = $model[ 'dt_deadline' ];


			$new_doc[ 'array_tk_amort' ] = $model[ 'array_tk_amort' ];
			$new_doc[ 'array_tk' ]       = $model[ 'array_tk' ];
			$new_doc[ 'array_casual' ]   = $model[ 'array_casual' ];
			$new_doc[ 'array_bus' ]      = $model[ 'array_bus' ];


			//       ddd($new_doc);

			if( !$new_doc->save( true ))
			{
				throw new NotFoundHttpException( 'Сохранить оказалось невозможно' );
			}


			return $this->redirect( '/sklad/in?otbor=' . $sklad );
		}


		/**
		 * Sklad ASEMTAI
		 *
		 * Подготовка и отправка ПИСЬМА в ТХА
		 * с отчетом о ПРОШИВКЕ и ПРИВЯЗКЕ МСАМ карт к устройствам,
		 * а так же привязке устройств к Маршрутам и Автобусам
		 *
		 * @param $id
		 *
		 * @return string
		 * @throws NotFoundHttpException
		 */
		public function actionMail_to_tha( $id ) // Sklad/
		{
			// $session = Yii::$app->session;
			// $sklad = $session->get('sklad_');

			$model = $this->findModel( $id );  /// this is  _id !!!!!

			return $this->render(
				'mail_to_tha/_form', [
				'model' => $model,
			] );
		}

		/**
		 * @param $id
		 *
		 * @return Sklad|array|null
		 * @throws NotFoundHttpException
		 */
		public static function findModel( $id ) {
			if(( $model = Sklad::findOne( $id ) ) !== null)
			{
				return $model;
			}

			throw new NotFoundHttpException( 'Ответ на запрос. Этого нет в складе' );
		}

		/**
		 * THA-Agent (Славик).
		 * Подтрверждает отправку/согласование с ТХА
		 *
		 * @param $id
		 *
		 * @return string
		 * @throws NotFoundHttpException
		 */
		public function actionTha_agent( $id ) {
			$session = Yii::$app->session;
			$sklad   = $session->get( 'sklad_' );

			$model = $this->findModel( $id );  /// this is  _id !!!!!


			if($model->load( Yii::$app->request->post() ))
			{

				//            dd($model);

				///Сливаем во едино базу с коментами Агента ТХА
				$old_xx = $model->oldAttributes;
				$new_xx = $model->attributes;

				$xx22_old = $old_xx[ 'array_tk_amort' ];
				$xx22_new = $new_xx[ 'array_tk_amort' ];

				$x = 0;
				while( isset( $xx22_old[ $x ] ) )
				{
					$xx22_old[ $x ][ 'the_bird' ] = $xx22_new[ $x ][ 'the_bird' ];
					$xx22_old[ $x ][ 'tx' ]       = $xx22_new[ $x ][ 'tx' ];
					$x ++;
				}

				$model[ 'array_tk_amort' ] = $xx22_old;
				//dd($model);

				if($model->save())
				{
				} else
				{
					dd( $model->errors );
				}

				return $this->redirect( '/sklad?otbor=' . $sklad );
			}


			return $this->render(
				'sklad_rewrite_tha/_form', [
				'model' => $model,
				'sklad' => $sklad,
			] );

		}

		/**
		 * @return string|Response
		 * @throws NotFoundHttpException
		 */
		public function actionPrihod() {

			dd( 123 );


			$id_array = Yii::$app->request->get();

			$sklad_transfer = Sklad_transfer::findModel( $id_array[ 'id' ] );

			//        dd($sklad_transfer);

			if( !isset( $sklad_transfer ))
			{
				throw new NotFoundHttpException( 'Обратитесь к разработчику' );
			}


			if($sklad_transfer->load( Yii::$app->request->post() ))
			{


				//#############
				$session = Yii::$app->session;
				$sklad   = $session->get( 'sklad_' );
				//#############

				//            'wh_home_number', // ид текущего склада
				//            'sklad_vid_oper',
				//            'dt_create',

				$new_doc = new Sklad();     // Новыая накладная


				$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_PRIHOD;
				$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

				$new_doc->wh_home_number = (int) $sklad; // мой текущий склад

				$new_doc->dt_create = $sklad_transfer->dt_create;

				$new_doc->wh_debet_top          = $sklad_transfer->wh_debet_top;
				$new_doc->wh_debet_name         = $sklad_transfer->wh_debet_name;
				$new_doc->wh_debet_element      = $sklad_transfer->wh_debet_element;
				$new_doc->wh_debet_element_name = $sklad_transfer->wh_debet_element_name;

				$new_doc->wh_destination              = $sklad_transfer->wh_destination;
				$new_doc->wh_destination_name         = $sklad_transfer->wh_destination_name;
				$new_doc->wh_destination_element      = $sklad_transfer->wh_destination_element;
				$new_doc->wh_destination_element_name = $sklad_transfer->wh_destination_element_name;

				$new_doc->user_group_id = (integer) $sklad_transfer->id;
				$new_doc->user_id       = (int) Yii::$app->getUser()->identity->id;
				$new_doc->user_name     = Yii::$app->getUser()->identity->username;

				date_default_timezone_set( "Asia/Almaty" );
				$new_doc->dt_update = date( 'd.m.Y H:i:s', strtotime( 'now' ) );

				$new_doc->array_tk_amort = $sklad_transfer->array_tk_amort;
				$new_doc->array_tk       = $sklad_transfer->array_tk;


				//        vd($sklad_transfer);


				$max_value = Sklad::find()->max( 'id' );
				$max_value ++;
				$new_doc->id = (integer) $max_value;


				//        dd($new_doc);

				unset( $sklad_transfer [ 'dt_transfer_start' ] );

				//         vd($new_doc);

				if(isset( $new_doc ) && $new_doc->save( true ))
				{


					dd( $new_doc );


					if(MyHelpers::Mongo_save( 'sklad', 'id', $new_doc->_id, (integer) $max_value ))

					{
						return $this->redirect( 'sklad_in/in?otbor=' . $sklad );
					}

				}
			}


			return $this->render(
				'sklad_in/_form_sklad', [
				//                'model' => $tz_body,        //'multi_tz' => $tz_body->multi_tz,
				//                'new_doc' => $new_doc,
			] );


		}

		/**
		 * @return string|Response
		 * @throws NotFoundHttpException
		 * @throws UnauthorizedHttpException
		 */
		public function actionCreate_new() {
			//TODO:SKLAD = Create_new

			$para     = Yii::$app->request->post();
//			$para_get = Yii::$app->request->get(); // передал
			//ddd($para_get);

			//ddd($para);


			$session = Yii::$app->session;
			if( !$session->isActive)
			{
				$session->open();
			}

			$sklad = $session->get( 'sklad_' );

			//ddd($sklad);


			$max_value = Sklad::find()->max( 'id' );
			$max_value ++;


			if( !isset( $sklad ) || empty( $sklad ))
			{
				throw new UnauthorizedHttpException( 'Sklad=0' );
			}


			$model = new Sklad();

			if( !is_object( $model ))
			{
				throw new NotFoundHttpException( 'Склад не работает' );
			}

			////////
			$model->id             = (integer) $max_value;
			$model->wh_home_number = (int) $sklad;
			$model->dt_create      = date( 'd.m.Y H:i:s', strtotime( 'now' ) );


			if( !isset( $model->sklad_vid_oper ) || empty( $model->sklad_vid_oper ))
			{
				$model->sklad_vid_oper = Sklad::VID_NAKLADNOY_RASHOD;
			}


			//            ddd($model);


			/////////// ПРЕД СОХРАНЕНИЕМ
			if($model->load( Yii::$app->request->post() ))
			{

				$model->wh_home_number = (int) $sklad;

				$model->user_id   = (int) Yii::$app->getUser()->identity->id;
				$model->user_name = Yii::$app->getUser()->identity->username;

//				$model->sklad_vid_oper = (integer) $model->sklad_vid_oper; // Приводим к числу


                if ((int)$model['sklad_vid_oper'] == 1)
				{
					$model[ 'sklad_vid_oper_name' ] = 'Инвентаризация';

					$array = Sprwhelement::findFullArray( $sklad );
					//'top' => ['id' => 14,'name' => 'City Bus ТОО']
					//'child' => [ 'id' => 177, 'name' => '5001' ]

					$model->wh_debet_element       = $array[ 'child' ][ 'id' ];
					$model->wh_destination_element = $array[ 'child' ][ 'id' ];

					$model->wh_debet_name         = $array[ 'top' ][ 'name' ];
					$model->wh_debet_element_name = $array[ 'child' ][ 'name' ];

					$model->wh_destination_name         = $array[ 'top' ][ 'name' ];
					$model->wh_destination_element_name = $array[ 'child' ][ 'name' ];

				}

                if ((int)$model['sklad_vid_oper'] == 2)
				{
					$model[ 'sklad_vid_oper_name' ] = 'Приходная накладная';

					$array = Sprwhelement::findFullArray( $sklad );
					//'top' => ['id' => 14,'name' => 'City Bus ТОО']
					//'child' => [ 'id' => 177, 'name' => '5001' ]

					$model->wh_destination         = $array[ 'top' ][ 'id' ];
					$model->wh_destination_element = $array[ 'child' ][ 'id' ];

				}

                if ((int)$model['sklad_vid_oper'] == 3)
				{
					$model[ 'sklad_vid_oper_name' ] = 'Расходная накладная';


					$array = Sprwhelement::findFullArray( $sklad );
					//'top' => ['id' => 14,'name' => 'City Bus ТОО']
					//'child' => [ 'id' => 177, 'name' => '5001' ]

					$model->wh_debet_top     = $array[ 'top' ][ 'id' ];
					$model->wh_debet_element = $array[ 'child' ][ 'id' ];

				}

				/// То самое преобразование ПОЛЯ Милисукунд
				$model->setDtCreateText( $model->dt_create );

				// TODO: create_new SIT

				//ddd($para);


				//            ddd($model);


				///
				///  Приводим INTELLIGENT в прядок!
				///  Прописываем каждому элементу
				$model->array_tk_amort = Spr_globam_element::array_am_to_intelligent( $model->array_tk_amort );

				//            ddd($model);


				/////////// ПРЕД СОХРАНЕНИЕМ
				/// Проверим ИМЕННО Нашу кнопку "Создать"

				if(isset( $para[ 'contact-button' ] ) && $para[ 'contact-button' ] == 'create_new')
				{

					//Перебивка номера накладной
					if((int) Sklad::setNext_max_id() > (int) $model->id)
					{
						$model->id = (int) Sklad::setNext_max_id();
						$tx        = $model->tx;
						$model->tx = $tx . "от админа (аварийно изм.номер накладной)";
					}
					//ddd($new_doc);


					if($model->save( true ))
					{

						return $this->render(
							'_form_create',
							[
								'model'      => $model,
								'sklad'      => $sklad,
								'alert_mess' => 'Сохранение.Успешно.',
							]
						);
					}
				}


			}


			return $this->render(
				'_form_create', [
				'model'      => $model,
				'sklad'      => $sklad,
				'alert_mess' => '',

			] );
		}

		/**
		 * Создаем накладную
		 * вариант со Штрихкодами
		 * по умножению
		 * (ТехЗадание * Мультипликатор)
		 *
		 * @param int $tz_id
		 * @param int $multi
		 *
		 * @return string|Response
		 * @throws NotFoundHttpException
		 */
		public function actionCreate_multi( $tz_id = 0, $multi = 0 ) {
			$buff2 = [];

			// TZ find()
			$tz_body = Tz::find()
			             ->where( [ 'id' => (integer) $tz_id ] )
			             ->one();       // Tz


			#########
			$session = Yii::$app->session;
			$sklad   = $session->get( 'sklad_' );
			//dd($sklad);


			$new_doc = new Sklad();     // Новыая накладная

			$max_value = Sklad::find()->max( 'id' );;
			$max_value ++;
			$new_doc->id             = (integer) $max_value;
			$new_doc->wh_home_number = (int) $sklad;

			$new_doc->user_id   = Yii::$app->getUser()->getId();
			$new_doc->user_name = Yii::$app->user->identity->username;

			$new_doc->tz_id   = (integer) $tz_body->id;
			$new_doc->tz_name = $tz_body->name_tz;

			$new_doc->tz_date     = $tz_body->dt_create;
			$new_doc->dt_deadline = $tz_body->dt_deadline;


			// Получить полный Массив-знаний по ОТПРАВИТЕЛЮ
			$array_request = Sprwhelement::findFullArray( 86 );
			// dd($array_request);

			//FROM
			$new_doc[ 'wh_home_number' ]        = (integer) $sklad;
			$new_doc[ 'wh_debet_top' ]          = $array_request[ 'top' ][ 'id' ];
			$new_doc[ 'wh_debet_name' ]         = $array_request[ 'top' ][ 'name' ];
			$new_doc[ 'wh_debet_element' ]      = $array_request[ 'child' ][ 'id' ];
			$new_doc[ 'wh_debet_element_name' ] = $array_request[ 'child' ][ 'name' ];


			// Получить полный Массив-знаний по ОТПРАВИТЕЛЮ
			$array_request = Sprwhelement::findFullArray( $sklad );
			//TO
			$new_doc[ 'wh_destination' ]              = $array_request[ 'top' ][ 'id' ];
			$new_doc[ 'wh_destination_name' ]         = $array_request[ 'top' ][ 'name' ];
			$new_doc[ 'wh_destination_element' ]      = $array_request[ 'child' ][ 'id' ];
			$new_doc[ 'wh_destination_element_name' ] = $array_request[ 'child' ][ 'name' ];

			///

			//create_multi


			$new_doc->array_tk_amort = $tz_body->array_tk_amort;
			//$new_doc->array_tk = $tz_body->array_tk ;
			//        $buff_amort = $new_doc->array_tk_amort;

			//        dd($new_doc);

			$all_multi = 0;
			if(isset( $new_doc->array_tk_amort ) && !empty( $new_doc->array_tk_amort ))
			{
				foreach( $new_doc->array_tk_amort as $string )
				{

					if($string[ 'intelligent' ] > 0)
					{
						//                $x_multi = 0;
						for( $x_multi = 0;$x_multi < $multi;$x_multi ++ )
						{
							$buff2[ $all_multi ] = $string;
							//$buff2[$all_multi]['bar_code']='';

							$next_while                            = (int) $buff2[ $all_multi ][ 'ed_izmer_num' ];
							$buff2[ $all_multi ][ 'ed_izmer_num' ] = 1;

							while( $next_while > 1 )
							{
								$all_multi ++;
								$next_while --;
								$buff2[ $all_multi ]                   = $string;
								$buff2[ $all_multi ][ 'ed_izmer_num' ] = 1;

							}

							$all_multi ++;
						}
					}

				}
			}

			$new_doc->array_tk_amort = $buff2;
			unset( $buff2 );

			$new_doc->array_bus    = $tz_body->array_bus;
			$new_doc->array_casual = $tz_body->array_casual;


			if($new_doc->load( Yii::$app->request->post() ))
			{

				$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_PRIHOD;
				$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

				/// То самое преобразование ПОЛЯ Милисукунд
				$new_doc->setDtCreateText( "NOW" );


				//dd(123);
				if($new_doc->save( true ))
				{

					//return $this->redirect('/sklad/in?otbor=' . $new_doc->wh_destination_element);
					return $this->redirect( '/sklad/in' );
				} else
				{
					dd( $new_doc->errors );
				}


			}


			return $this->render(
				'_form_sklad', [
				'model'   => $tz_body,
				'new_doc' => $new_doc,
				'sklad'   => $sklad,

			] );

		}

		/**
		 * Создаем накладную
		 * Вариант без Штрихкодов
		 *
		 * @param int $tz_id
		 * @param int $multi
		 *
		 * @return string|Response
		 * @throws NotFoundHttpException
		 */
		public function actionCreate_multi_without_barcode( $tz_id = 0, $multi = 0 ) {

			$buff2 = [];

			$tz_body = Tz::find()
			             ->where( [ 'id' => (integer) $tz_id ] )
			             ->one();;       // Tz

			#########
			$session = Yii::$app->session;
			$sklad   = $session->get( 'sklad_' );
			//dd($sklad);

			#########
			$new_doc = new Sklad();     // Новыая накладная

			$max_value = Sklad::find()->max( 'id' );;
			$max_value ++;

			$new_doc->id = (integer) $max_value;

			$new_doc->wh_home_number = (int) $sklad;

			$new_doc->user_id   = Yii::$app->getUser()->getId();
			$new_doc->user_name = Yii::$app->user->identity->username;

			$new_doc->tz_id       = (integer) $tz_id;
			$new_doc->tz_name     = $tz_body->name_tz;
			$new_doc->tz_date     = $tz_body->dt_create;
			$new_doc->dt_deadline = $tz_body->dt_deadline;

			$new_doc->array_tk_amort = $tz_body->array_tk_amort;
			$new_doc->array_tk       = $tz_body->array_tk;
			$new_doc->array_casual   = $tz_body->array_casual;
			$new_doc->array_bus      = $tz_body->array_bus;


			$full_sklad = Sprwhelement::findFullArray( $sklad );
			//dd($full_sklad);
			/// ИСТОЧНИК
			$new_doc->wh_debet_top          = (int) $full_sklad[ "top" ][ 'id' ];
			$new_doc->wh_debet_element      = (int) $sklad;
			$new_doc->wh_debet_name         = $full_sklad[ "top" ][ 'name' ];
			$new_doc->wh_debet_element_name = $full_sklad[ "child" ][ 'name' ];
			/// ПРИЕМНИК
			$new_doc->wh_destination              = (int) $full_sklad[ "top" ][ 'id' ];
			$new_doc->wh_destination_element      = (int) $sklad;
			$new_doc->wh_destination_name         = $full_sklad[ "top" ][ 'name' ];
			$new_doc->wh_destination_element_name = $full_sklad[ "child" ][ 'name' ];


			$all_multi = 0;
			if(isset( $new_doc->array_tk_amort ) && !empty( $new_doc->array_tk_amort ))
			{
				foreach( $new_doc->array_tk_amort as $string )
				{

					if($string[ 'intelligent' ] > 0)
					{
						//                $x_multi = 0;
						for( $x_multi = 0;$x_multi < $multi;$x_multi ++ )
						{
							//                    $buff2[$all_multi] = $string;
							//                        //                print_r($buff_amort[$x_multi]);
							//                        //                echo "<br>";
							//                    $buff2[$all_multi]['bar_code']='101010101';
							$all_multi ++;
						}
					} else
					{

						//dd($string);

						$buff2[ $all_multi ]                   = $string;
						$buff2[ $all_multi ][ 'ed_izmer_num' ] = $string[ 'ed_izmer_num' ] * $multi;

						$buff2[ $all_multi ][ 'bar_code' ] = 'нет';
						$all_multi ++;

					}

				}
			}

			$new_doc->array_tk_amort = $buff2;
			unset( $buff2 );


			//.....
			$all_multi = 0;
			if(isset( $new_doc->array_tk ) && !empty( $new_doc->array_tk ))
			{

				foreach( $new_doc->array_tk as $string )
				{

					$buff2[ $all_multi ]                   = $string;
					$buff2[ $all_multi ][ 'ed_izmer_num' ] = $string[ 'ed_izmer_num' ] * $multi;

					$buff2[ $all_multi ][ 'bar_code' ] = 'нет';
					$all_multi ++;

				}
				//dd($buff2);
				$new_doc->array_tk = $buff2;
			}

			// Получить полный Массив-знаний по ОТПРАВИТЕЛЮ
			$array_request = Sprwhelement::findFullArray( $sklad );
			//TO
			$new_doc[ 'wh_destination' ]              = $array_request[ 'top' ][ 'id' ];
			$new_doc[ 'wh_destination_name' ]         = $array_request[ 'top' ][ 'name' ];
			$new_doc[ 'wh_destination_element' ]      = $array_request[ 'child' ][ 'id' ];
			$new_doc[ 'wh_destination_element_name' ] = $array_request[ 'child' ][ 'name' ];


			//ddd($new_doc);

			if($new_doc->load( Yii::$app->request->post() ))
			{

				$new_doc->user_id   = (integer) Yii::$app->getUser()->identity->id;
				$new_doc->user_name = Yii::$app->getUser()->identity->username;
				$new_doc->dt_update = date( 'd.m.Y H:i:s', strtotime( 'now' ) );

				$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_RASHOD;
				$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;

				//            ddd($new_doc);

				if($new_doc->save( true ))
				{
					//return $this->redirect('/sklad/in?otbor=' . $new_doc->wh_destination_element);
					return $this->redirect( '/sklad/in' );
				}

			}


			return $this->render(
				'_form_sklad', [
				'model'   => $tz_body,
				//'multi_tz' => $tz_body->multi_tz,
				'new_doc' => $new_doc,
				'sklad'   => $sklad,
			] );

		}

		/**
		 * Создаем новую  накладную из Шаблона
		 *
		 * @param int $shablon_id
		 *
		 * @return string|Response
		 */
		public function actionCreatefrom_shablon( $shablon_id = 0 ) {
			$para = Yii::$app->request->queryParams;        //dd($para);

			$session = Yii::$app->session;
			$session->open();

			if(isset( $para[ 'otbor' ] ) && !empty( $para[ 'otbor' ] ))
			{
				//$sklad= Yii::$app->params['sklad'] = $para['otbor'];
				$session->set( 'sklad_', $para[ 'otbor' ] );
			}

			$sklad = $session->get( 'sklad_' );
			//        dd($sklad);

			#############
			$shablon_body = Shablon::find()
			                       ->where( [ 'id' => (integer) $shablon_id ] )
			                       ->one();
			#############


			#############
			$new_doc = new Sklad();     // Новая накладная

			$max_value = Sklad::find()->max( 'id' );
			$max_value ++;
			$new_doc->id = (integer) $max_value;

			$new_doc->wh_home_number = (int) $sklad;

			//        $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
			//        $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

			$new_doc->wh_debet_top     = 1;
			$new_doc->wh_debet_element = 1;
			//        $new_doc->wh_debet_name = "City111-Bus";
			//        $new_doc->wh_debet_element_name = "4645645";

			$new_doc->wh_destination         = 2;
			$new_doc->wh_destination_element = "1917";
			//        $new_doc->wh_destination_name           = "Guidejet TI";
			//        $new_doc->wh_destination_element_name   = "Склад №1";

			$new_doc->array_tk_amort = $shablon_body->array_tk_amort;
			$new_doc->array_tk       = $shablon_body->array_tk;
			//        $new_doc->array_casual      = $shablon_body->array_casual;


			if($new_doc->load( Yii::$app->request->post() ))
			{

				$new_doc->user_id       = Yii::$app->user->identity->id;
				$new_doc->user_name     = Yii::$app->user->identity->username;
				$new_doc->user_group_id = Yii::$app->user->identity->group_id;

				$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_PRIHOD;
				$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

				$new_doc->tz_id   = 0;
				$new_doc->tz_name = '';
				$new_doc->tz_date = '';
				date_default_timezone_set( "Asia/Almaty" );
				$new_doc->dt_create = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
				/// То самое преобразование ПОЛЯ Милисукунд
				$new_doc->setDtCreateText( "NOW" );


				$new_doc->dt_deadline = '';

				if($new_doc->save())
				{

					return $this->redirect( '/sklad/in?otbor=' . $sklad );
				}

			}


			return $this->render(
				'_form_sklad_shablon', [
				'model'   => $shablon_body,
				'new_doc' => $new_doc,
				'sklad'   => $sklad,
			] );

		}

		/**
		 * Создаем новую  накладную
		 * (Приход, Расход, Инвентаризация)
		 *
		 * @return string|Response
		 */
		public function actionCreate() {
			$session = Yii::$app->session;
			$sklad   = $session->get( 'sklad_' );
			//dd($sklad);


			$max_value = Sklad::find()->max( 'id' );;
			$max_value ++;
			//        $model->id = $max_value;

			$new_doc = new Sklad();     // Новая накладная

			$new_doc->id = (integer) $max_value;
			//            $new_doc->tz_id = (integer) ;

			$new_doc->wh_home_number      = (int) $sklad;
			$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
			$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

			//        $new_doc->dt_create         = $model->dt_create;
			//        $new_doc->wh_debet_top      = $model->wh_debet_top;
			//        $new_doc->wh_debet_name     = $model->wh_debet_name;
			//        $new_doc->wh_debet_element  = $model->wh_debet_element;
			//        $new_doc->wh_debet_element_name = $model->wh_debet_element_name;

			//        $new_doc->wh_destination            = $model->wh_destination;
			//        $new_doc->wh_destination_name       = $model->wh_destination_name;
			//        $new_doc->wh_destination_element    = $model->wh_destination_element;
			//        $new_doc->wh_destination_element_name = $model->wh_destination_element_name;


			$new_doc->user_id   = (int) Yii::$app->getUser()->identity->id;
			$new_doc->user_name = Yii::$app->getUser()->identity->username;
			date_default_timezone_set( "Asia/Almaty" );
			$new_doc->dt_update = date( 'd.m.Y H:i:s', strtotime( 'now' ) );

			$new_doc->array_tk_amort = [];
			$new_doc->array_tk       = [];
			$new_doc->array_casual   = [];


			if($new_doc->load( Yii::$app->request->post() ))
			{
				/// То самое преобразование ПОЛЯ Милисукунд
				$new_doc->setDtCreateText( "NOW" );


				//Перебивка номера накладной
				if((int) Sklad::setNext_max_id() > (int) $new_doc->id)
				{
					$new_doc->id = (int) Sklad::setNext_max_id();
					$tx          = $new_doc->tx;
					$new_doc->tx = $tx . "от админа (аварийно изм.номер накладной)";
				}
				//ddd($new_doc);
				//

				if($new_doc->save( true ))
				{


					return $this->redirect( '/sklad/index?sort=-id&sklad=' . $sklad );

				} else
				{
					//dd($model);
					return $this->redirect( '/' );
				}
			}


			return $this->render(
				'sklad_in/_form', [
				//            'model' => $model,
				'new_doc' => $new_doc,
				'sklad'   => $sklad,
			] );


		}

		/**
		 * ПЕРЕДАЧА накладной в БУФЕР
		 *=
		 * по нажатию ЗЕЛЕНОЙ КНОПКИ-ОТПРАВКИ
		 * -
		 *
		 * @return string|Response
		 * @throws NotFoundHttpException
		 * @throws ExitException
		 */
		public function actionCopyToTransfer() {
			//TODO: actionCopyToTransfer  КНОПКА - ОТПРАВИТЬ НАКЛАДНУЮ через буфер ПЕредачи
			$para = Yii::$app->request->queryParams;

			$id = $para[ 'id' ]; // _id
			//ddd($id);

			$model = Sklad::findModel( $id );

			// CS
			//$model->wh_cs_number = (int)0;

			///////
			$model->dt_transfered_date    = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
			$model->dt_transfered_user_id = (integer) Yii::$app->getUser()->identity->id;;
			$model->dt_transfered_user_name = Yii::$app->getUser()->identity->username;


			if($err = Sklad_transfer::setTransfer( $model ) != null)
			{
				if( !$model->save( true ))
				{
					ddd( $model->errors );
				}
			} else
			{
				ddd( $err );
			}


			return $this->redirect( '/sklad/in' );

		}

		/**
		 * ИЗ БУФЕРА ПЕРЕДАЧИ. С ПРОСМОТРОМ.
		 * =
		 * Принятие накладной из Трансфера,
		 * =
		 * из буферной базы передачи накладных
		 * -
		 *
		 * @return string|Response
		 * @throws NotFoundHttpException
		 */
		public function actionPrihod2() {
			$para  = Yii::$app->request->queryParams;        //dd($para);
			$sklad = Yii::$app->params[ 'sklad' ] = $para[ 'otbor' ];
			$id    = $id_before_transfered = $para[ 'id' ];    // OID

			$model = Sklad_transfer::findModel( $id );

			if($model->dt_transfered_ok != 0)
			{
				throw new NotFoundHttpException( 'Накладная уже передавалась' );
			}

			//dd($model);
			//#############
			//        $session = Yii::$app->session;
			//        $sklad = $session->get('sklad_');
			//#############


			$max_value = Sklad::find()->max( 'id' );
			$max_value ++;

			$new_doc = new Sklad();     // Новая накладная

			//        $new_doc =$model;  // ПЕРЕГОНКА

			$new_doc->id             = (integer) $max_value;
			$new_doc->wh_home_number = (int) $sklad;


			$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
			$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

            $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
            $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

			$new_doc->wh_debet_top = $model->wh_debet_top;

			$new_doc->wh_debet_name               = $model->wh_debet_name;
			$new_doc->wh_debet_element            = $model->wh_debet_element;
			$new_doc->wh_debet_element_name       = $model->wh_debet_element_name;
			$new_doc->wh_destination              = $model->wh_destination;
			$new_doc->wh_destination_name         = $model->wh_destination_name;
			$new_doc->wh_destination_element      = $model->wh_destination_element;
			$new_doc->wh_destination_element_name = $model->wh_destination_element_name;

			$new_doc->wh_dalee         = $model->wh_dalee;
			$new_doc->wh_dalee_element = $model->wh_dalee_element;


			$new_doc->tz_id   = $model->tz_id;
			$new_doc->tz_name = $model->tz_name;
			$new_doc->tz_date = $model->tz_date;
			//        dd($new_doc);


			/// Автобусы ЕСТЬ?
			if(isset( $model[ 'array_bus' ] ) && !empty( $model[ 'array_bus' ] ))
			{
				$items_auto = Sprwhelement::findAll_Attrib_PE(
					array_map( 'intval', $model[ 'array_bus' ] )
				);
			} else
			{
				$items_auto = [];
			} // ['нет автобусов'];


			if($new_doc->load( Yii::$app->request->post() ))
			{

				//$new_doc =$model;  // ПЕРЕГОНКА Noo !!!!

				$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
				$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

				$new_doc->user_id   = (int) Yii::$app->getUser()->identity->id;
				$new_doc->user_name = Yii::$app->getUser()->identity->username;

				date_default_timezone_set( "Asia/Almaty" );
				$new_doc->dt_update = date( 'd.m.Y H:i:s', strtotime( 'now' ) );

				$new_doc->array_tk_amort = $model->array_tk_amort;
				$new_doc->array_tk       = $model->array_tk;
				$new_doc->array_casual   = $model->array_casual;
				$new_doc->array_bus      = $model->array_bus;


				//Перебивка номера накладной
				if((int) Sklad::setNext_max_id() > (int) $new_doc->id)
				{
					$new_doc->id = (int) Sklad::setNext_max_id();
					$tx          = $new_doc->tx;
					$new_doc->tx = $tx . "от админа (аварийно изм.номер накладной)";
				}
				//ddd($new_doc);

				if($new_doc->save( true ))
				{
					// ОТметка о получении НАКЛАДНОЙ
					Sklad_transfer::setTransfer_delivered( $id, Sklad_transfer::TRANSFERED_OK );

					return $this->redirect( '/sklad/in?otbor=' . $sklad );
				} else
				{
					$new_doc->errors;
				}
			}


			//						return $this->redirect('/sklad/in');

			return $this->render(
				'sklad_in/_form', [
				'model'      => $model,
				'new_doc'    => $new_doc,
				'sklad'      => $sklad,
				'items_auto' => $items_auto,
			] );

		}

		/**
		 * ИЗ БУФЕРА ПЕРЕДАЧИ. БЕЗ просмотра. FAST (!)
		 * =
		 * Принятие накладной из Трансфера,
		 * =
		 * из буферной базы передачи накладных
		 * -
		 *
		 * @return string|Response
		 * @throws NotFoundHttpException
		 */
		public function actionPrihod2_fast() {
			$para  = Yii::$app->request->queryParams;        //dd($para);
			$sklad = Yii::$app->params[ 'sklad' ] = $para[ 'otbor' ];
			$id    = $id_before_transfered = $para[ 'id' ];    // OID

			$model = Sklad_transfer::findModel( $id );

			if($model->dt_transfered_ok != 0)
			{
				throw new NotFoundHttpException( 'Накладная уже передавалась' );
			}

			//dd($model);
			//#############
			//        $session = Yii::$app->session;
			//        $sklad = $session->get('sklad_');
			//#############


			$max_value = Sklad::find()->max( 'id' );
			$max_value ++;

			$new_doc = new Sklad();     // Новая накладная

			//        $new_doc =$model;  // ПЕРЕГОНКА

			$new_doc->id             = (integer) $max_value;
			$new_doc->wh_home_number = (int) $sklad;


			$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
			$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)


            $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;
            $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;

			$new_doc->wh_debet_top = $model->wh_debet_top;

			$new_doc->wh_debet_name               = $model->wh_debet_name;
			$new_doc->wh_debet_element            = $model->wh_debet_element;
			$new_doc->wh_debet_element_name       = $model->wh_debet_element_name;
			$new_doc->wh_destination              = $model->wh_destination;
			$new_doc->wh_destination_name         = $model->wh_destination_name;
			$new_doc->wh_destination_element      = $model->wh_destination_element;
			$new_doc->wh_destination_element_name = $model->wh_destination_element_name;

			$new_doc->wh_dalee         = $model->wh_dalee;
			$new_doc->wh_dalee_element = $model->wh_dalee_element;


			$new_doc->tz_id   = $model->tz_id;
			$new_doc->tz_name = $model->tz_name;
			$new_doc->tz_date = $model->tz_date;
			//        dd($new_doc);


			/// Автобусы ЕСТЬ?
//			if(isset( $model[ 'array_bus' ] ) && !empty( $model[ 'array_bus' ] ))
//			{
//				$items_auto = Sprwhelement::findAll_Attrib_PE(
//					array_map( 'intval', $model[ 'array_bus' ] )
//				);
//			} else
//			{
//				$items_auto = [];
//			} // ['нет автобусов'];


			//			if($new_doc->load( Yii::$app->request->post() ))
			//			{

			//$new_doc =$model;  // ПЕРЕГОНКА Noo !!!!

			$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
			$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

			$new_doc->user_id   = (int) Yii::$app->getUser()->identity->id;
			$new_doc->user_name = Yii::$app->getUser()->identity->username;

			date_default_timezone_set( "Asia/Almaty" );
			$new_doc->dt_update = date( 'd.m.Y H:i:s', strtotime( 'now' ) );

			$new_doc->array_tk_amort = $model->array_tk_amort;
			$new_doc->array_tk       = $model->array_tk;
			$new_doc->array_casual   = $model->array_casual;
			$new_doc->array_bus      = $model->array_bus;


			//ddd($new_doc);

			if($new_doc->save( true ))
			{
				// ОТметка о получении НАКЛАДНОЙ
				Sklad_transfer::setTransfer_delivered( $id, Sklad_transfer::TRANSFERED_OK );

				return $this->redirect( '/sklad/in?otbor=' . $sklad );
			} else
			{
				$new_doc->errors;
			}

			//			}


			return $this->redirect( '/sklad/in' );

			//			return $this->render(
			//				'sklad_in/_form', [
			//				'model'      => $model,
			//				'new_doc'    => $new_doc,
			//				'sklad'      => $sklad,
			//				'items_auto' => $items_auto,
			//			] );

		}

		/**
		 * @param        $id
		 * @param string $adres_to_return
		 *
		 * @return Response
		 * @throws NotFoundHttpException
		 * @throws StaleObjectException
		 */
		public function actionDelete( $id, $adres_to_return = "" ) {
			$this->findModel( $id )->delete();

			return $this->redirect( [ '/sklad/' . $adres_to_return ] );
		}

		/**
		 * @param mixed $otbor
		 */
		public function setOtbor( $otbor ) {
			$this->otbor = $otbor;
		}

		/**
		 * Лист Используется в Основной таблице без Амортизации
		 * Справочник элементов прямого списания
		 *
		 * @param $id
		 *
		 * @return string
		 */
		public function actionList( $id = 0 ) {

			$model =
				Html::dropDownList(
					'name_id',
					0,
					ArrayHelper::map(
						post_spr_glob_element::find()
						                     ->where( [ 'parent_id' => (integer) $id ] )
						                     ->orderBy( "name" )
						                     ->all(), 'id', 'name' ),
					[ 'prompt' => 'Выбор ...' ]
				);

			return $model;
		}

		/**
		 * ЛистАморт Используется в таблице Амортизации
		 * Справочник списания по амортизации
		 *
		 * @param $id
		 *
		 * @return string
		 */
		public function actionListamort( $id = 0 ) {
			$model =
				Html::dropDownList(
					'name_id_amort',
					0,
					ArrayHelper::map(
						post_spr_globam_element::find()
						                       ->where( [ 'parent_id' => (integer) $id ] )
						                       ->orderBy( "name" )
						                       ->all(), 'id', 'name' ),

					[ 'prompt' => 'Выбор ...' ]
				);

			return $model;
		}


		/**
		 * ЛистАморт Используется
		 * Справочник Штуки, Метры, Литры
		 *
		 * @param $id
		 *
		 * @return string
		 *
		 * ТОЛЬКО НЕ!!!! НЕ АСУОП !!!
		 */
		public function actionList_ed_izm( $id = 0 ) {
			$model =
				post_spr_glob_element::find()
				                     ->where( [ 'id' => (integer) $id ] )
				                     ->one();

			return $model[ 'ed_izm' ];
		}


		/**
		 * @return string
		 * @throws ExitException
		 */
		public function actionMail_to() {
			dd( 'Почтовое отправление в разработке' );

			$xx    = Yii::$app->request->get();
			$model = Sklad::findModelDouble( $xx[ 'id' ] );
			//        dd($model);

			if($model->load( Yii::$app->request->post() ))
			{


				dd( $model );

				//            [id] => 30
				//            [wh_destination] => 2
				//            [wh_destination_name] => Guidejet TI
				//            [wh_destination_element] => 1925
				//            [wh_destination_element_name] => Склад Прошивки модулей
				//            [tz_id] => 13
				//            [tz_name] => 12311123
				//            [tz_date] => 2019-02-26 06:42:47
				//            [user_id] => 9
				//            [user_name] => asemtai
				//            [dt_update] => 2019-02-27 14:52:49
				//            [array_tk_amort] => Array

				//return $this->redirect(['/rewrite_update?id=' . $id]);
				return $this->redirect( [ '/rewrite_update' ] );
			}


			//        return $this->render('mail_to 1111111/_form', [
			//            'model' => $model,
			//            //'new_doc' => $new_doc,
			//            //'sklad' => $sklad,
			//        ]);


			//        return $this->redirect(['mailer?id'.$id]);
			return true;
		}

		/**
		 * @param $id
		 *
		 * @return string|Response
		 */
		public function actionMailer( $id ) {
			dd( $id );


			$model = new MailerForm();
			if($model->load( Yii::$app->request->post() ) && $model->sendEmail())
			{
				Yii::$app->session->setFlash( 'mailerFormSubmitted' );

				return $this->refresh();
			}

			return $this->render(
				'mailer', [
				'model' => $model,
			] );
		}

		/**
		 *
		 * ПРЕДВАРИТЕЛЬНЫЙ ПРОСМТР СТРАНИЦЫ НАКЛАДНОЙ
		 * ПЕРЕД ВЫВОДОМ в PDF
		 *
		 *
		 * @return string
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionHtml_pdf_green() {
			$para  = Yii::$app->request->queryParams;
			$model = Sklad::findModelDouble( $para[ 'id' ] );

			////////////////////
			///// AMORT!!
			$model1 = ArrayHelper::map(
				Spr_globam::find()
				          ->all(), 'id', 'name' );

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			$model3 = ArrayHelper::map(
				Spr_glob::find()
				        ->all(), 'id', 'name' );

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );

			////////////////////

			///// BAR-CODE
			$str_pos       = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			$bar_code_html = MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			///// BAR-CODE

			//1
			$html_css = $this->getView()->render( '/sklad/html_to_pdf/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad/html_to_pdf/_form_green', [
				//            'bar_code_html' => $bar_code_html,
				'model'  => $model,
				'model1' => $model1,
				'model2' => $model2,
				'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );


			//        dd($model);

			// Тут можно подсмореть
			//         $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';


			$mpdf->SetAuthor( 'Guidejet TI, 2019' );
			$mpdf->SetHeader( $bar_code_html );
			$mpdf->WriteHTML( $html_css, 1 );

			//        $foot_str= '{PAGENO}';

			$foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>           
        ';

			//$mpdf->SetFooter($foot_str );
			$mpdf->SetHTMLFooter( $foot_str, 'O' );


			///////
			$mpdf->AddPage(
				'', '', '', '', '',
				10, 10, 25, 42, '', 25, '', '', '',
				'', '', '', '', '', '', '' );

			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'Sk ' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}

		/**
		 *
		 * ПРЕДВАРИТЕЛЬНЫЙ ПРОСМТР СТРАНИЦЫ НАКЛАДНОЙ
		 * ПЕРЕД ВЫВОДОМ в PDF
		 * (BARCODE)
		 *
		 *
		 * @return string
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionHtml_pdf_green_barcode() {
			$para  = Yii::$app->request->queryParams;
			$model = Sklad::findModelDouble( $para[ 'id' ] );

			////////////////////
			///// AMORT!!
			$model1 = ArrayHelper::map(
				post_spr_globam::find()
				               ->all(), 'id', 'name' );

			$model2 = ArrayHelper::map(
				post_spr_globam_element::find()
				                       ->orderBy( 'id' )
				                       ->all(), 'id', 'name' );


			///// NOT AMORT
			$model3 = ArrayHelper::map(
				Spr_glob::find()
				        ->all(), 'id', 'name' );

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////


			///// BAR-CODE
			$str_pos       = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			$bar_code_html = MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			///// BAR-CODE

			//1
			$html_css = $this->getView()->render( '/sklad/html_to_pdf/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad/html_to_pdf/_form_green_barcode', [
				//            'bar_code_html' => $bar_code_html,
				'model'  => $model,
				'model1' => $model1,
				'model2' => $model2,
				'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );


			//        dd($model);

			// Тут можно подсмореть
			//         $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';

			$mpdf->SetAuthor( 'Guidejet TI, 2019' );
			$mpdf->SetHeader( $bar_code_html );
			$mpdf->WriteHTML( $html_css, 1 );

			//        $foot_str= '{PAGENO}';

			$foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>           
        ';

			//$mpdf->SetFooter($foot_str );
			$mpdf->SetHTMLFooter( $foot_str, 'O' );


			///////
			$mpdf->AddPage(
				'', '', '', '', '',
				10, 10, 25, 42, '', 25, '', '', '',
				'', '', '', '', '', '', '' );

			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'Tz ' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}


		/**
		 * Asemtai
		 *
		 * @return string
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionProshivka_to_pdf() {
			$para  = Yii::$app->request->queryParams;
			$model = Sklad::findModelDouble( $para[ 'id' ] );

			////////////////////
			///// AMORT!!
			$model1 = ArrayHelper::map(
				Spr_globam::find()
				          ->all(), 'id', 'name' );

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			$model3 = ArrayHelper::map(
				Spr_glob::find()
				        ->all(), 'id', 'name' );

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////


			//1
			$html_css = $this->getView()->render( '/sklad/proshivka_to_pdf/_form_css.php' );

			//        dd($model);

			//2
			$html = $this->getView()->render(
				'/sklad/proshivka_to_pdf/_form_asemtai', [
				'model'  => $model,
				'model1' => $model1,
				'model2' => $model2,
				'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );


			//        dd($model);
			// Тут можно подсмореть
			//         $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';
			$mpdf->WriteHTML( $html_css, 1 );

			///////
			$mpdf->AddPage(
				0, 0, 0,
				0, 0,
				10, 10, 10, 20 );
			//$html = '';
			$str_pos = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			//       dd($model) ;
			$html .= MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'Tz ' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}


		/**
		 * Накладная Внутреннее Перемещение Виктор
		 *
		 * @return bool
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionHtml_pdf_inner() {
			$para  = Yii::$app->request->queryParams;
			$model = Sklad::findModelDouble( $para[ 'id' ] );

			////////////////////
			///// AMORT!!
			$model1 = ArrayHelper::map(
				Spr_globam::find()
				          ->all(), 'id', 'name' );

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			$model3 = ArrayHelper::map(
				Spr_glob::find()
				        ->all(), 'id', 'name' );

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////


			//1
			$html_css = $this->getView()->render( '/sklad/html_to_pdf/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad/html_to_pdf/_form_inner', [
				'model'  => $model,
				'model1' => $model1,
				'model2' => $model2,
				'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );


			//        dd($model);


			// Тут можно подсмореть
			//         $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';
			$mpdf->WriteHTML( $html_css, 1 );

			///////
			$mpdf->AddPage();

			//$html = '';
			$str_pos = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			//       dd($model) ;
			$html .= MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'Tz ' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}


		/**
		 * Накладная Внутреннее Перемещение 2 Жанель
		 *
		 * @return bool
		 * @throws NotFoundHttpException
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionHtml_pdf_janel_montage() {
			$para  = Yii::$app->request->queryParams;
			$model = Sklad::findModelDouble( $para[ 'id' ] );

			////////////////////
			///// AMORT!!
			$model1 = ArrayHelper::map(
				Spr_globam::find()
				          ->all(), 'id', 'name' );

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			$model3 = ArrayHelper::map(
				Spr_glob::find()
				        ->all(), 'id', 'name' );

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////

			$model6 = Sprwhelement::findFullArray( $model[ 'wh_destination_element' ] );
			//			ddd($model6);
			$model7 = Sprwhelement::findFullArray( $model[ 'wh_dalee_element' ] );
			//			ddd($model7);
			$wh_debet_name         = $model[ 'wh_debet_name' ];
			$wh_debet_element_name = $model[ 'wh_debet_element_name' ];


			//ddd($model);

			///// BAR-CODE
			$str_pos       = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			$bar_code_html = MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			///// BAR-CODE


			//1
			$html_css = $this->getView()->render( '/sklad/html_to_pdf/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad/html_to_pdf/_form_inner_janel', [
				//            'bar_code_html' => $bar_code_html,
				'model'                 => $model,
				'model1'                => $model1,
				'model2'                => $model2,
				'model3'                => $model3,
				'model4'                => $model4,
				'model5'                => $model5,
				'model6'                => $model6,
				'model7'                => $model7,
				'wh_debet_name'         => $wh_debet_name,
				'wh_debet_element_name' => $wh_debet_element_name,
			] );


			//        dd($model);


			// Тут можно подсмореть
			//         $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';


			$mpdf->SetAuthor( 'Guidejet TI, 2019' );
			$mpdf->SetHeader( $bar_code_html );
			$mpdf->WriteHTML( $html_css, 1 );

			//        $foot_str= '{PAGENO}';

			$foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>           
        ';

			//$mpdf->SetFooter($foot_str );
			$mpdf->SetHTMLFooter( $foot_str, 'O' );


			///////
			$mpdf->AddPage(
				'', '', '', '', '',
				10, 10, 25, 42, '', 25, '', '', '',
				'', '', '', '', '', '', '' );

			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'Montage_' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}

		/**
		 * Накладная Внутреннее Перемещение 2 Жанель
		 *
		 * @return bool
		 * @throws NotFoundHttpException
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionHtml_pdf_janel_demontage() {
			$para  = Yii::$app->request->queryParams;
			$model = Sklad::findModelDouble( $para[ 'id' ] );

			////////////////////
			///// AMORT!!
			$model1 = ArrayHelper::map(
				Spr_globam::find()
				          ->all(), 'id', 'name' );

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			$model3 = ArrayHelper::map(
				Spr_glob::find()
				        ->all(), 'id', 'name' );

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////

			$model6 = Sprwhelement::findFullArray( $model[ 'wh_debet_element' ] );

			///// BAR-CODE
			$str_pos       = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			$bar_code_html = MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			///// BAR-CODE


			//1
			$html_css = $this->getView()->render( '/sklad/html_to_pdf/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad/html_to_pdf/_form_inner_janel_demontage', [
				//            'bar_code_html' => $bar_code_html,
				'model'  => $model,
				'model1' => $model1,
				'model2' => $model2,
				'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
				'model6' => $model6,
			] );


			//        dd($model);


			// Тут можно подсмореть
			//         $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';


			$mpdf->SetAuthor( 'Guidejet TI, 2019' );
			$mpdf->SetHeader( $bar_code_html );
			$mpdf->WriteHTML( $html_css, 1 );

			//        $foot_str= '{PAGENO}';

			$foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>           
        ';

			//$mpdf->SetFooter($foot_str );
			$mpdf->SetHTMLFooter( $foot_str, 'O' );


			///////
			$mpdf->AddPage(
				'', '', '', '', '',
				10, 10, 25, 42, '', 25, '', '', '',
				'', '', '', '', '', '', '' );

			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'Montage_' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}

		/**
		 * @return bool
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionHtml_pdf() {
			$para  = Yii::$app->request->queryParams;
			$model = Sklad::findModelDouble( $para[ 'id' ] );

			////////////////////
			///// AMORT!!
			$model1 = ArrayHelper::map(
				Spr_globam::find()
				          ->all(), 'id', 'name' );

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			$model3 = ArrayHelper::map(
				Spr_glob::find()
				        ->all(), 'id', 'name' );

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////


			//1
			$html_css = $this->getView()->render( '/sklad/html_to_pdf/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad/html_to_pdf/_form', [
				'model'  => $model,
				'model1' => $model1,
				'model2' => $model2,
				'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );


			//        dd($model);


			// Тут можно подсмореть
			//         $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';
			$mpdf->WriteHTML( $html_css, 1 );

			///////
			$mpdf->AddPage();

			//$html = '';
			$str_pos = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			//       dd($model) ;
			$html .= MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'Sk_' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}


		/**
		 * Акт МОНТАЖА
		 *
		 * @return bool
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionHtml_akt_mont() {
			$para  = Yii::$app->request->queryParams;
			$model = Sklad::findModelDouble( $para[ 'id' ] );

			////////////////////
			///// AMORT!!
			$model1 = ArrayHelper::map(
				Spr_globam::find()
				          ->all(), 'id', 'name' );

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			$model3 = ArrayHelper::map(
				Spr_glob::find()
				        ->all(), 'id', 'name' );

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////


			//1
			$html_css = $this->getView()->render( '/sklad/html_akt_mont/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad/html_akt_mont/_form', [
				'model'  => $model,
				'model1' => $model1,
				'model2' => $model2,
				'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );


			//        dd($model);


			// Тут можно подсмореть
			//         $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';
			$mpdf->WriteHTML( $html_css, 1 );

			///////
			$mpdf->AddPage(
				'', '', '', '', '',
				10, 10, 20, '', '', 1 );

			//$html = '';
			$str_pos = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			//       dd($model) ;
			$html .= MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'Montage_' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}

		/**
		 * Акт ДЕ-МОНТАЖА
		 *
		 * @return bool
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionHtml_akt_demont() {
			$para = Yii::$app->request->queryParams;

			$model = Sklad::findModelDouble( $para[ 'id' ] );

			////////////////////
			///// AMORT!!
			//        $model1 = ArrayHelper::map(Spr_globam::find()
			//            ->all(), 'id', 'name');

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			//        $model3 = ArrayHelper::map(Spr_glob::find()
			//            ->all(), 'id', 'name');

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////


			//1
			$html_css = $this->getView()->render( '/sklad/html_akt_demont/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad/html_akt_demont/_form', [
				'model'  => $model,
				//            'model1' => $model1,
				'model2' => $model2,
				//            'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );


			//  Тут можно подсмореть
			//  $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';
			$mpdf->WriteHTML( $html_css, 1 );

			///////
			$mpdf->AddPage( '', '', '', '', '', 10, 10, 20, '', '', 1 );

			//$html = '';
			$str_pos = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			//       dd($model) ;
			$html .= MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'DeMontage_' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}

		/**
		 * Накладная Резервный ФОНД (ПДФ)
		 *
		 * @return bool
		 * @throws MpdfException
		 * @throws BarcodeException
		 * @throws ExitException
		 */
		public function actionHtml_reserv_fond() {

			$para = Yii::$app->request->queryParams;

			$model = Sklad::findModelDouble( $para[ 'id' ] );


			////////////////////
			///// AMORT!!
			//        $model1 = ArrayHelper::map(Spr_globam::find()
			//            ->all(), 'id', 'name');

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			//        $model3 = ArrayHelper::map(Spr_glob::find()
			//            ->all(), 'id', 'name');

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );

			////////////////////

			///// BAR-CODE
			$str_pos       = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			$bar_code_html = MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			///// BAR-CODE


			//1
			$html_css = $this->getView()->render( '/sklad/html_reserv_fond/_form_css.php' );

			//ddd($model);

			//2
			$html = $this->getView()->render(
				'/sklad/html_reserv_fond/_form', [
				//            'bar_code_html' => $bar_code_html,
				'model'  => $model,
				//            'model1' => $model1,
				'model2' => $model2,
				//            'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );


			//  Тут можно подсмореть
			//  $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';

			$mpdf->SetAuthor( 'Guidejet TI, 2019' );
			$mpdf->SetHeader( $bar_code_html );
			$mpdf->WriteHTML( $html_css, 1 );

			//        $foot_str= '{PAGENO}';

			$foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>           
        ';

			//$mpdf->SetFooter($foot_str );
			$mpdf->SetHTMLFooter( $foot_str, 'O' );


			///////
			$mpdf->AddPage(
				'', '', '', '', '',
				10, 10, 25, 42, '', 25, '', '', '',
				'', '', '', '', '', '', '' );

			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'DeMontage_' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}


		public function actionHtmlAsemtai() {

			$para = Yii::$app->request->queryParams;

			$model = Sklad::findModelDouble( $para[ 'id' ] );


			////////////////////
			///// AMORT!!
			//        $model1 = ArrayHelper::map(Spr_globam::find()
			//            ->all(), 'id', 'name');

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
			//        $model3 = ArrayHelper::map(Spr_glob::find()
			//            ->all(), 'id', 'name');

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////

			///// BAR-CODE
			$str_pos       = str_pad( $model->id, 10, "0", STR_PAD_LEFT ); /// длинная строка с номером генерируется в длинную
			$bar_code_html = MyHelpers::Barcode_HTML( 'sk' . $model->wh_home_number . '-' . $str_pos );
			///// BAR-CODE


			//1
			$html_css = $this->getView()->render( '/sklad/html_reserv_fond/_form_css.php' );

			//ddd($model);

			//2
			$html = $this->getView()->render(
				'/sklad/html_reserv_fond/_form', [
				//            'bar_code_html' => $bar_code_html,
				'model'  => $model,
				//            'model1' => $model1,
				'model2' => $model2,
				//            'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );


			//  Тут можно подсмореть
			//  $html = ss($html);

			///
			///  mPDF()
			///

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';

			$mpdf->SetAuthor( 'Guidejet TI, 2019' );
			$mpdf->SetHeader( $bar_code_html );
			$mpdf->WriteHTML( $html_css, 1 );

			//        $foot_str= '{PAGENO}';

			$foot_str = '
           <div class="print_row">
                <div class="footer_left" >
                       <div class="man_sign">Отпустил</div>
                 </div>
                 <div class="footer_right" >
                       <div class="man_sign">Получил</div>
                 </div>
           </div>           
        ';

			//$mpdf->SetFooter($foot_str );
			$mpdf->SetHTMLFooter( $foot_str, 'O' );


			///////
			$mpdf->AddPage(
				'', '', '', '', '',
				10, 10, 25, 42, '', 25, '', '', '',
				'', '', '', '', '', '', '' );

			//////////


			$mpdf->WriteHTML( $html, 2 );
			$html = '';

			unset( $html );

			$filename = 'DeMontage_' . date( 'd.m.Y H-i-s' ) . '.pdf';
			$mpdf->Output( $filename, 'I' );


			return false;
		}

		/**
		 * Простая
		 *
		 * @throws UnauthorizedHttpException
		 */
		public function actionTo_pdf() {
			throw new UnauthorizedHttpException( ' Форма удалена ' );
		}

		/**
		 * @param int $sklad_id
		 *
		 * @throws MpdfException
		 * @throws BarcodeException
		 */
		public function actionPdfreport( $sklad_id = 11 ) {

			////////////// PDF - Barcode
			$html = '';
			$html .= MyHelpers::Barcode_HTML( "sklad " . $sklad_id );
			////////////// PDF - Barcode


			$html_css = '';
			$html_css .= '
        table{            
               border: 1px solid #ddd;
               /*background-color: azure;*/                
               margin: 0px;
               padding: 0px;    
        }
        th{
               font-size: 11px;
               border: 0px solid ;
               background-color: #ddd;
               padding: 10px 10px;
        }
        td,tr{
               font-size: 10px;
               border: 1px solid #ddd;       
               padding: 5px 10px;      
        }
        .bar_code{
              position: absolute;
              top: 20px;
              right: 50px;                            
        }
        ';

			$mpdf             = new mPDF();
			$mpdf->charset_in = 'utf-8';
			$mpdf->WriteHTML( $html_css, 1 );

			$html .= '<h3>Техническое задание №' . $sklad_id . '</h3>';

			//        $para = [];
			//        $para = Yii::$app->request->queryParams;

			//        [start] => 2018-11-27
			//        [end] => 2018-11-29

			//d33($para);


			//        $model = Sklad::find()
			//            ->andFilterWhere(['>=', 'dt_deadline', $para['start']])
			//            ->andFilterWhere(['<', 'dt_deadline', $para['end']])
			//            ->asArray()->orderBy('id DESC')->all();
			//        }

			//        dd($model);


			//////// BUS АвтоБУсы в PDF
			//        $html .= $this->action_bus_report_pdf($sklad_id); ///BUS АвтоБУсы в PDF


			$mpdf->WriteHTML( $html, 2 );
			$mpdf->AddPage();

			//////////////////////
			$mpdf->Output( 'mpdf.pdf', 'I' );

		}

		/**
		 * По вызову Аякс находит
		 * ПОДЧИНЕННЫЕ СКЛАДЫ
		 *
		 * @param int $id
		 *
		 * @return string
		 */
		public function actionList_element( $id = 0 ) {
			//dd($id);

			$model = Html::dropDownList(
				'name_id', 0,
				ArrayHelper::map(
					Sprwhelement::find()
					            ->where( [ 'parent_id' => (integer) $id ] )
					            ->all(), 'id', 'name' ),
				[ 'prompt' => 'Выбор ...' ]
			);

			if(empty( $model ))
			{
				return "Запрос вернул пустой массив";
			}

			return $model;
		}


		/**
		 *  Подтяивает из таблицы  Element
		 *  логическое поле ДА-НЕТ
		 *  INTELLIGENT ( Штрихкод, интелектуально устройство )
		 *
		 * @param $id
		 *
		 * @return mixed`
		 */
		public function actionListamort_logic( $id ) {
			$model = Spr_globam_element::find()
			                           ->asArray()
			                           ->where( [ 'id' => (integer) $id ] )
			                           ->one();

			//dd($model['intelligent']);
			return $model[ 'intelligent' ];    /// 1 - 0
		}


		/**
		 * @param int $id
		 *
		 * @return mixed
		 */
		public function actionList_parent_id_amort( $id = 0 ) {
			$model =
				post_spr_globam_element::find()
				                       ->where( [ 'id' => (integer) $id ] )
				                       ->one();

			//        dd($model['ed_izm']);
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
		public function actionList_parent_id( $id ) {
			$model =
				Spr_glob_element::find()
				                ->where( [ 'id' => (int) $id ] )
				                ->one();

			if( !isset( $model ))
			{
				return 0;
			}

			return $model[ 'parent_id' ];
		}


		/**
		 * Сколько СТРОК в Массиве
		 *
		 * @param $array
		 *
		 * @return array
		 */
		public function Count_rows( $array ) {
			return count( $array );
		}

		/**
		 * СИНЯЯ КНОПКА ВНУТРИ НАКЛАДНОЙ
		 * Создаем МНОГО НОВЫХ НАКЛАДНЫХ-АКТОВ
		 * (Монтажа)
		 * По колитчествву Автобусов (ПЕ)
		 *
		 * @return Response
		 * @throws NotFoundHttpException
		 */
		public function actionTzToManyNewActsMontage() {

			$load_model = new Sklad();     // Новая накладная

			if($load_model->load( Yii::$app->request->post() ))
			{


				$para = Yii::$app->request->get();

				//Читаем Т.З.
				$model_tz = Tz::findModelDouble( $para[ 'tz_id' ] );
				// TZ

				//ddd($model_tz);


				$session = Yii::$app->session;
				$sklad   = $session->get( 'sklad_' ); //64
				//
				$max_value = Sklad::find()->max( 'id' );


				// Хозяин МОЕГО склада
				$array_full = Sprwhelement::findFullArray( $sklad );
				//dd($array_full);

				//            ddd( $array_full );
				//            ddd( $load_model[ 'array_bus' ] );
				//            ddd( $load_model );

				//        ddd($model_tz);


				// ЦИКЛ
				if( !isset( $load_model[ 'array_bus' ] ) || empty( $load_model[ 'array_bus' ] ))
				{
//					$x_casual = 0;
					throw new NotFoundHttpException( 'load_model[array_bus] Список Автобусов. Нет данных' );
				} else
				{
					foreach( $model_tz[ 'array_bus' ] as $key )
					{

						//
						// РАСШИФРОВКА для Инженера
						//
						$array_full_xx2 = Sprwhelement::findFullArray( $load_model->wh_destination_element );

						//
						// РАСШИФРОВКА для  АВТОБУСа
						//
						$array_full_bus = Sprwhelement::findFullArray( $key );


						//Это номер группы складаов
						//''wh_cred_top

						// Это номер склада
						//4425 ddd($key);

						//            dd($key); // $key=177   //$item=5001

						$max_value ++;

						$new_model = new Sklad();     // Новая накладная

						$new_model->id             = (integer) $max_value;
						$new_model->wh_home_number = (int) $sklad;
						$new_model->tz_id          = $model_tz[ 'id' ];

						$new_model->sklad_vid_oper      = Sklad::VID_NAKLADNOY_RASHOD;            // РАСХОД
						$new_model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;   // РАСХОД


						$new_model->wh_debet_top          = $array_full[ 'top' ][ 'id' ];
						$new_model->wh_debet_name         = $array_full[ 'top' ][ 'name' ];
						$new_model->wh_debet_element      = $array_full[ 'child' ][ 'id' ];
						$new_model->wh_debet_element_name = $array_full[ 'child' ][ 'name' ];

						$new_model->wh_destination              = $array_full_xx2[ 'top' ][ 'id' ];
						$new_model->wh_destination_name         = $array_full_xx2[ 'top' ][ 'name' ];
						$new_model->wh_destination_element      = $array_full_xx2[ 'child' ][ 'id' ];
						$new_model->wh_destination_element_name = $array_full_xx2[ 'child' ][ 'name' ];


						/// DaLEE
						$new_model->wh_dalee         = $array_full_bus[ 'top' ][ 'id' ];
						$new_model->wh_dalee_element = $array_full_bus[ 'child' ][ 'id' ];


						$new_model->user_id   = (int) Yii::$app->getUser()->identity->id;
						$new_model->user_name = Yii::$app->getUser()->identity->username;
						$new_model->dt_create = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
						$new_model->dt_update = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
						/// То самое преобразование ПОЛЯ Милисукунд
						$new_model->setDtCreateText( "NOW" );


						$new_model->array_tk  = $model_tz[ 'array_tk' ];
						$new_model->array_bus = $model_tz[ 'array_bus' ];

						//$new_model->array_tk_amort = $model_tz[ 'array_tk_amort' ];
						//ddd( $new_model );
						//ddd($model_tz);

						$new_model->array_tk_amort = Sklad::setAmArrayIntelegentToStrings( $model_tz[ 'array_tk_amort' ] );

						//$new_model->array_tk_amort = Sklad::setAmArrayIntelegentAll( $model_tz[ 'array_tk_amort' ], count( $new_model->array_bus ) );
						//ddd( $new_model );

						if($x_casual == 0)
						{
							$new_model->array_casual = $model_tz[ 'array_casual' ];
							$x_casual ++;
						}


						//ddd($new_model);

						if( !$new_model->save( true ))
						{
							dd( $new_model->errors );
						}
						//            else
						//                dd($new_model);

						//unset($new_model);
					}
				}


			}

			return $this->redirect( '/sklad/in' );
		}


		/**
		 * Прием накладных ИЗ Буфера Обмена
		 *
		 * DEmontage
		 * Создаем МНОГО НОВЫХ НАКЛАДНЫХ-АКТОВ
		 * (ДеМонтажа)
		 * По колитчествву Автобусов (ПЕ)
		 *
		 * @return Response
		 * @throws NotFoundHttpException
		 */
		public function actionTzToManyNewActsDemontage() {
			$para = Yii::$app->request->get();
			// $para[tz_id] => 8        dd($para);

			//Читаем Т.З.
			$model_tz = Tz::findModelDouble( $para[ 'tz_id' ] );
			// TZ TZ TZ

			$session = Yii::$app->session;
			$sklad   = $session->get( 'sklad_' ); //64
			//
			$max_value = Sklad::find()->max( 'id' );

			// Хозяин МОЕГО склада
			$array_full = Sprwhelement::findFullArray( $sklad );

			if(isset( $model_tz[ 'array_bus' ] ) && !empty( $model_tz[ 'array_bus' ] ))
			{

				$x_casual = 0;
				foreach( $model_tz[ 'array_bus' ] as $key )
				{

					//dd($key); // $key=177   //$item=5001

					$max_value ++;

					$new_model = new Sklad();     // Новая накладная

					$new_model->id             = (integer) $max_value;
					$new_model->wh_home_number = (int) $sklad;
					$new_model->tz_id          = $model_tz[ 'id' ];

					$new_model->sklad_vid_oper      = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
					$new_model->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)

					// Хозяин АВТОБУСА
					$array_full_bus = Sprwhelement::findFullArray( $key );

					/// Уход (Демонтаж) из АВТОБУСА и ПАРКА
					$new_model->wh_debet_top          = $array_full_bus[ 'top' ][ 'id' ];
					$new_model->wh_debet_name         = $array_full_bus[ 'top' ][ 'name' ];
					$new_model->wh_debet_element      = $array_full_bus[ 'child' ][ 'id' ];
					$new_model->wh_debet_element_name = $array_full_bus[ 'child' ][ 'name' ];

					/// Приход СЕБЕ, на свой ЛИЧНЫЙ склад
					$new_model->wh_destination              = $array_full[ 'top' ][ 'id' ];
					$new_model->wh_destination_name         = $array_full[ 'top' ][ 'name' ];
					$new_model->wh_destination_element      = $array_full[ 'child' ][ 'id' ];
					$new_model->wh_destination_element_name = $array_full[ 'child' ][ 'name' ];

					/// DaLEE
					$new_model->wh_dalee = $array_full[ 'top' ][ 'id' ];
					//$new_model->wh_dalee_name = $array_full['top']['name'];
					$new_model->wh_dalee_element = $array_full[ 'child' ][ 'id' ];
					//$new_model->wh_dalee_element_name = $array_full['child']['name'];


					$new_model->user_id   = (int) Yii::$app->getUser()->identity->id;
					$new_model->user_name = Yii::$app->getUser()->identity->username;
					//            $new_model->dt_create = date('d.m.Y H:i:s', strtotime('now'));
					//            $new_model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
					$new_model->dt_create = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
					$new_model->dt_update = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
					/// То самое преобразование ПОЛЯ Милисукунд
					$new_model->setDtCreateText( "NOW" );


					$new_model->array_tk       = $model_tz[ 'array_tk' ];
					$new_model->array_tk_amort = $model_tz[ 'array_tk_amort' ];
					$new_model->array_bus      = $model_tz[ 'array_bus' ];

					if($x_casual == 0)
					{
						$new_model->array_casual = $model_tz[ 'array_casual' ];
						$x_casual ++;
					}

					//                ddd($model_tz);

					//ddd($new_model);


					if( !$new_model->save( true ))
					{
						dd( $new_model->errors );
					}

					unset( $new_model );
				}
			} else
			{
				throw new NotFoundHttpException( 'Список Автобусов. Нет данных' );
			}


			return $this->redirect( '/sklad/in' );
		}


		/**
		 * Принятие/создание НОВОЙ накладной по ТЕХ Заданию
		 *
		 * @param int $tz_id - на входе
		 *
		 * @return string|Response
		 * @throws NotFoundHttpException
		 * @throws UnauthorizedHttpException
		 */
		public function actionCreatefromtz( $tz_id ) {

			$tz_body = Tz::find()
			             ->where( [ 'id' => (integer) $tz_id ] )
			             ->one();

			$session = Yii::$app->session;
			$sklad   = $session->get( 'sklad_' );

			if( !isset( $sklad ) || empty( $sklad ))
			{
				throw new UnauthorizedHttpException( 'Createfromtz. Sklad=0' );
			}

			//ddd($sklad);


			$new_doc = new Sklad();     // Новая накладная

			$max_value = Sklad::find()->max( 'id' );
			$max_value ++;
			$new_doc->id = (integer) $max_value;

			$new_doc->wh_home_number = (int) $sklad;

			$new_doc->sklad_vid_oper      = Sklad::VID_NAKLADNOY_RASHOD;
			$new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_RASHOD_STR;


			$new_doc->array_tk_amort = $tz_body->array_tk_amort;
			$new_doc->array_tk       = $tz_body->array_tk;
			$new_doc->array_bus      = $tz_body->array_bus;
			$new_doc->array_casual   = $tz_body->array_casual;


			// Полное звание этого Элемента
			$full_sklad = Sprwhelement::findFullArray( $sklad );

			/// ИСТОЧНИК
			$new_doc->wh_debet_top     = (int) $full_sklad[ "top" ][ 'id' ];
			$new_doc->wh_debet_element = (int) $sklad;
			/// ПРИЕМНИК
			//        $new_doc->wh_destination = (int)$full_sklad["top"]['id'];
			//        $new_doc->wh_destination_element = (int)$sklad;


			/// Автобусы ЕСТЬ?
			if(isset( $new_doc[ 'array_bus' ] ) && !empty( $new_doc[ 'array_bus' ] ))
			{
				$items_auto = Sprwhelement::findAll_Attrib_PE(
					array_map( 'intval', $new_doc[ 'array_bus' ] )
				);
			} else
			{
				$items_auto = [ 'нет автобусов' ];
			}


			/// Получаем ТехЗадание. ШАПКА
			if($tz_body->id)
			{
				$tz_head = Tz::findModelDoubleAsArray( (int) $tz_body->id );
			} else
			{
				$tz_head = [];
			}


			//dd($items_auto);

			//        ddd($new_doc);

			if($new_doc->load( Yii::$app->request->post() ))
			{

				$new_doc->user_id       = Yii::$app->user->identity->id;
				$new_doc->user_name     = Yii::$app->user->identity->username;
				$new_doc->user_group_id = Yii::$app->user->identity->group_id;

				$new_doc->tz_id   = (int) $tz_body->id;
				$new_doc->tz_name = $tz_body->name_tz;
				$new_doc->tz_date = $tz_body->dt_create;

				$new_doc->dt_create = date( 'd.m.Y H:i:s', strtotime( 'now' ) );
				/// То самое преобразование ПОЛЯ Милисукунд
				$new_doc->setDtCreateText( "NOW" );
				$new_doc->dt_deadline = $tz_body->dt_deadline;


				$new_doc->user_id   = (int) Yii::$app->getUser()->identity->id;
				$new_doc->user_name = Yii::$app->getUser()->identity->username;

				$new_doc->wh_debet_top           = (int) $new_doc->wh_debet_top;
				$new_doc->wh_debet_element       = (int) $new_doc->wh_debet_element;
				$new_doc->wh_destination         = (int) $new_doc->wh_destination;
				$new_doc->wh_destination_element = (int) $new_doc->wh_destination_element;

				$new_doc->wh_dalee         = (int) $new_doc->wh_dalee;
				$new_doc->wh_dalee_element = (int) $new_doc->wh_dalee_element;

				$new_doc->tx = 'Накладная создана по запросу ТехЗадания';


				//            ddd( $new_doc->array_bus );
				//            ddd(count( $new_doc->array_bus ));


				//
				//   * Приводим только первую табицу (АМОРТИЗАЦИЯ/АСУОП)
				//   * к виду : Каждая запись(интелегент) записана в своей, новой строке
				// При этом умножено на количество АВТОБУСОВ (ПЕ)
				//


				$new_doc->array_tk_amort = Sklad::setAmArrayIntelegentAll( $new_doc->array_tk_amort, count( $new_doc->array_bus ) );
				$new_doc->array_tk       = Sklad::setAmArrayIntelegentAll( $new_doc->array_tk, count( $new_doc->array_bus ) );


				//ddd($new_doc);


				if($new_doc->save( true ))
				{
					return $this->redirect( '/sklad/in' );
				}
			}


			return $this->render(
				'_form_sklad', [
				'model'      => $tz_body,
				'new_doc'    => $new_doc,
				'sklad'      => $sklad,
				'items_auto' => $items_auto,
				'tz_head'    => $tz_head,
			] );
		}

		/**
		 * Редактирование Накладной
		 * =
		 * ЦС внутри нашей накладной  по признаку
		 * $cs_number_id
		 * -
		 *
		 * @return string|Response
		 * @throws ExitException
		 * @throws NotFoundHttpException
		 * @throws UnauthorizedHttpException
		 */
		public function actionUpdate() {

			//TODO:SKLAD = Update
			$para = Yii::$app->request->queryParams;
			//ddd( $para );

			$para_post = Yii::$app->request->post(); // Для печати
			//ddd( $para_post );


			if(isset( $para[ 'otbor' ] ) && !empty( $para[ 'otbor' ] ))
			{
				if( !Sklad::setSkladIdActive( $para[ 'otbor' ] ))
				{
					throw new UnauthorizedHttpException( '$_SESSION1 Не подключен.  Sklad=0' );
				}
			}

			if(isset( $para[ 'sklad' ] ) && !empty( $para[ 'sklad' ] ))
			{
				if( !Sklad::setSkladIdActive( $para[ 'sklad' ] ))
				{
					throw new UnauthorizedHttpException( '$_SESSION2 Не подключен.  Sklad=0' );
				}
			}

			$sklad = Sklad::getSkladIdActive();    // Активный склад (_SESSION)
			//dd($sklad);


			if( !isset( $sklad ) || empty( $sklad ))
			{
				throw new UnauthorizedHttpException( '$_SESSION3 Не подключен.  Sklad=0' );
			}


			////////
			$model = Sklad::findModel( $id );  /// this is  _id !!!!! //$model->getDtCreateText()
			///


			if( !is_object( $model ))
			{
				throw new NotFoundHttpException( 'Нет такой накладной' );
			}


			/// Автобусы ЕСТЬ?
			if(isset( $model[ 'array_bus' ] ) && !empty( $model[ 'array_bus' ] ))
			{

				$items_auto = Sprwhelement::findAll_Attrib_PE(
					array_map( 'intval', $model[ 'array_bus' ] )
				);

				//            ddd( $items_auto );
				//            ddd( $model['array_bus'] );
			} else
			{
				$items_auto = []; // ['нет автобусов'];
			}


			/// Получаем ТехЗадание. ШАПКА
			if($model->tz_id)
			{
				$tz_head = Tz::findModelDoubleAsArray( (int) $model->tz_id );
			} else
			{
				$tz_head = [];
			}

			//   ddd($tz_head);


			///

			$parett_sklad = Sprwhelement::find_parent_id( $sklad ); // Парент айди этого СКЛАДА

			//		if((int)$model['sklad_vid_oper']==1){
			//			$model['sklad_vid_oper_name']='Инвентаризация';
			//
			//			$model->wh_debet_top=$parett_sklad;       // Мой склад ПОЛУЧАТЕЛЬ
			//			$model->wh_debet_element=$sklad;      // Мой склад ПОЛУЧАТЕЛЬ
			//			$model->wh_destination=$parett_sklad;  // Мой склад ОТПРАВИТЕЛЬ
			//			$model->wh_destination_element=$sklad; // Мой склад ОТПРАВИТЕЛЬ
			//
			//			//ddd($model);
			//		}


			if((int) $model[ 'sklad_vid_oper' ] == 2)
			{
				$model[ 'sklad_vid_oper_name' ] = 'Приходная накладная';
				$model->wh_destination          = $parett_sklad;  // Мой склад ОТПРАВИТЕЛЬ
				$model->wh_destination_element  = $sklad; // Мой склад ОТПРАВИТЕЛЬ

				//ddd($model);
			}


			if((int) $model[ 'sklad_vid_oper' ] == 3)
			{
				$model[ 'sklad_vid_oper_name' ] = 'Расходная накладная';
				$model->wh_debet_top            = $parett_sklad;       // Мой склад ПОЛУЧАТЕЛЬ
				$model->wh_debet_element        = $sklad;      // Мой склад ПОЛУЧАТЕЛЬ
			}


			//// Подсчет количества строк в массивах
			/// for VIEW
			///

			$erase_array[ 0 ] = count( $model->array_tk_amort );
			$erase_array[ 1 ] = count( $model->array_tk );
			$erase_array[ 2 ] = count( $model->array_casual );

			//ddd($erase_array );


			///////////////////////////////////////////////////////
			///
			///  КНОПКА ПЛЮС. ДОБАВКА ЕЩЕ ОДНОЙ НАКЛАДНОЙ К ЭТОЙ
			///
			/// contact-button
			/// 1. add_aray
			/// 2. erase_aray
			///
			if(isset( $para_post[ 'contact-button' ] ) && $para_post[ 'contact-button' ] == 'add_button')
			{

				//            ddd($para_post['contact-button']);
				////            ddd($para_post['Sklad']['add_button']);

				$num_next_sklad = $para_post[ 'Sklad' ][ 'add_button' ];
				//            $next_model = Sklad::find()
				//                ->where(['id'=>(int)$num_next_sklad])
				//                ->one();

				//            if (!isset($next_model) || empty($next_model))
				//                throw new NotFoundHttpException('Нет такой накладной');


				// Сложили ВСЕ МАССИВЫ ИЗ ДВУХ НАКЛАДНЫХ
				$different_nakl = Sklad::findArray_by_id_into_sklad( $sklad, $num_next_sklad );
				if( !isset( $different_nakl ) || empty( $different_nakl ))
				{
					return $this->render(
						'_form',
						[
							'new_doc'            => $model,
							'sklad'              => $sklad,
							'items_auto'         => $items_auto,
							'tz_head'            => $tz_head,
							'alert_mess'         => 'Не найдена накладная.',
							'spr_globam_element' => $spr_globam_element,


						] );
				}


				$model->array_tk_amort = Sklad::AddNaklad_to_Naklad( $model[ 'array_tk_amort' ], $different_nakl[ 'array_tk_amort' ] );

				//            $model->array_tk      = Sklad::AddNaklad_to_Naklad( $model['array_tk'], $different_nakl['array_tk']);
				//ddd($model);

				//            $model->array_casual  = Sklad::AddNaklad_to_Naklad( $model['array_tk_amort'], $different_nakl['array_tk_amort']);
				//            $model->array_tk_amort= array_merge( $model['array_tk_amort'],$next_model['array_tk_amort']);
				$model->array_tk     = array_merge( $model[ 'array_tk' ], $different_nakl[ 'array_tk' ] );
				$model->array_casual = array_merge( $model[ 'array_casual' ], $different_nakl[ 'array_casual' ] );

			}

			///
			///  КНОПКА УДАЛЕНИЕ СТРОК в массивах
			///
			/// $para_post['contact-button']=='erase_aray'
			///

			if(isset( $para_post[ 'contact-button' ] ) && $para_post[ 'contact-button' ] == 'erase_button')
			{
				//ddd($para_post);

				if(is_array( $model[ 'array_tk_amort' ] ))
				{
					//////////array_tk_amort
					///
					$start = (int) $para_post[ 'Sklad' ][ 'erase_array' ][ 0 ][ 0 ] - 1;
					$stop  = (int) $para_post[ 'Sklad' ][ 'erase_array' ][ 0 ][ 1 ] - $start;

					$array = (array) $model[ 'array_tk_amort' ];
					array_splice( $array, $start, $stop );
					$model[ 'array_tk_amort' ] = $array;

					//                ddd($model);

					//                $start=$stop=0;
				}

				if(is_array( $model[ 'array_tk' ] ))
				{
					//////////array_tk
					///
					$start = (int) $para_post[ 'Sklad' ][ 'erase_array' ][ 1 ][ 0 ] - 1;
					$stop  = (int) $para_post[ 'Sklad' ][ 'erase_array' ][ 1 ][ 1 ] - $start;

					$array = (array) $model[ 'array_tk' ];
					array_splice( $array, $start, $stop );
					$model[ 'array_tk' ] = $array;

					//                $start=$stop=0;
				}

				if(is_array( $model[ 'array_casual' ] ))
				{
					//////////array_casual
					///
					$start = (int) $para_post[ 'Sklad' ][ 'erase_array' ][ 2 ][ 0 ] - 1;
					$stop  = (int) $para_post[ 'Sklad' ][ 'erase_array' ][ 2 ][ 1 ] - $start;

					$array = (array) $model[ 'array_casual' ];
					array_splice( $array, $start, $stop );
					$model[ 'array_casual' ] = $array;

					//                $start=$stop=0;
				}

				//ddd($model);

			}

			//$spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(),'id','name');
			$spr_globam_element = Spr_globam_element::name_plus_id();


			///
			///  МОДАЛЬНОЕ ОКНО. Добавляем в ПУЛ ШТРИХКОДОВ для АСУОП
			///

			if(isset( $para_post[ 'contact-button' ] ) && $para_post[ 'contact-button' ] == 'add_new_pool_copypast_fufer')
			{

				$id        = $para_post[ 'Sklad' ][ 'pool_copypast_id' ];
				$parent_id = Spr_globam_element::getParent_id( $id );

				//ddd( $parent_id );


				////////
				$array = explode( "\r\n", $para_post[ 'Sklad' ][ 'pool_copypast_fufer' ] );

				foreach( $array as $item )
				{
					if( !empty( $item ))
					{
						$barcode_str = (string) preg_replace( '/[^\d*]/i', '', $item );

						//19600004992
						if(substr( $barcode_str, 0, 6 ) == '019600')
						{
							$barcode_str = substr( $barcode_str, 1, 12 );
						}

						$array_result[] = $barcode_str;
					}
				}

				if( !isset( $array_result ))
				{
					return $this->render(
						'_form',
						[
							'new_doc'            => $model,
							'sklad'              => $sklad,
							'items_auto'         => $items_auto,
							'tz_head'            => $tz_head,
							'alert_mess'         => 'Нет данных для заливки в базу.',
							'spr_globam_element' => $spr_globam_element,


						] );
					//throw new NotFoundHttpException( 'Нет данных для заливки в базу.' );
				}


				$array_model = $model[ 'array_tk_amort' ];

				foreach( $array_result as $item )
				{

					$array_model[] = [

						'wh_tk_amort'   => $parent_id,
						'wh_tk_element' => (int) $id,
						'ed_izmer'      => 1,
						'ed_izmer_num'  => 1,
						'intelligent'   => 1,

						'bar_code' => $item,
					];
				}


				$model[ 'array_tk_amort' ] = $array_model;

				//				ddd( $model );
				//				ddd( $array_model );
				//				ddd( $array_result );


			}


			//ddd($model);
			/////////// ПРЕД СОХРАНЕНИЕМ
			///
			if( !isset( $para_post[ 'contact-button' ] ) || empty( $para_post[ 'contact-button' ] ))

			{
				if($model->load( Yii::$app->request->post() ))
				{

					//ddd(123);
					$model->wh_home_number = (int) $sklad;


					////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
					///  ТАБ 1
					// $model->array_tk_amort  = Sklad::setArraySort1( $model->array_tk_amort );
					$model->array_tk_amort = Sklad::setArrayClear( $model->array_tk_amort );
					///  ТАБ 2
					$model->array_tk = Sklad::setArraySort2( $model->array_tk );


					////  Приводим ключи в прядок! УСПОКАИВАЕМ ОЧЕРЕДЬ
					$model->array_tk_amort = Sklad::setArrayToNormal( $model->array_tk_amort );
					$model->array_tk       = Sklad::setArrayToNormal( $model->array_tk );
					$model->array_casual   = Sklad::setArrayToNormal( $model->array_casual );


					////  Приводим INTELLIGENT в прядок! Прописываем каждому элементу
					$model->array_tk_amort = Spr_globam_element::array_am_to_intelligent( $model->array_tk_amort );


					//                    $model->dt_update = date('d.m.Y H:i:s', strtotime('now'));
					//                    $model->update_user_id = (integer)Yii::$app->getUser()->identity->id;;
					//                    $model->update_user_name = Yii::$app->getUser()->identity->username;

					////////////

					//ddd($model);


					if((int) $model[ 'sklad_vid_oper' ] == 2)
					{
						$model[ 'sklad_vid_oper_name' ] = 'Приходная накладная';
					}
					if((int) $model[ 'sklad_vid_oper' ] == 3)
					{
						$model[ 'sklad_vid_oper_name' ] = 'Расходная накладная';
					}

					$model->wh_debet_top           = (int) $model->wh_debet_top;
					$model->wh_debet_element       = (int) $model->wh_debet_element;
					$model->wh_destination         = (int) $model->wh_destination;
					$model->wh_destination_element = (int) $model->wh_destination_element;
					$model->wh_dalee               = (int) $model->wh_dalee;
					$model->wh_dalee_element       = (int) $model->wh_dalee_element;


					$model->wh_cs_number              = (int) 0;
					$model->wh_destination_element_cs = (int) 0;


					if(isset( $model->wh_debet_element ) && !empty( $model->wh_debet_element ))
					{
						///////
						/// ИСТОЧНИК
						$xx1                          = Sprwhelement::findFullArray( $model->wh_debet_element );
						$model->wh_debet_name         = $xx1[ 'top' ][ 'name' ];
						$model->wh_debet_element_name = $xx1[ 'child' ][ 'name' ];

						////  Приводим INTELLIGENT в прядок! Прописываем каждому элементу
						$model->wh_cs_number = $xx1[ 'child' ][ 'cs' ];

						//ddd($xx1);

					}


					if(isset( $model->wh_destination_element ) && !empty( $model->wh_destination_element ))
					{
						/// ПРИЕМНИК
						$xx2                                = Sprwhelement::findFullArray( $model->wh_destination_element );
						$model->wh_destination_name         = $xx2[ 'top' ][ 'name' ];
						$model->wh_destination_element_name = $xx2[ 'child' ][ 'name' ];

						////  Приводим INTELLIGENT в прядок! Прописываем каждому элементу
						if($model->wh_cs_number == 0 || empty( $model->wh_cs_number ))
						{
							//ddd($xx2);

							$model->wh_cs_number = $xx2[ 'child' ][ 'cs' ];
						}
					}

					///ddd($model);

					if(isset( $model->wh_dalee_element ) && !empty( $model->wh_dalee_element ))
					{
						/// ПРИЕМНИК
						$xx2                          = Sprwhelement::findFullArray( $model->wh_dalee_element );
						$model->wh_dalee_name         = $xx2[ 'top' ][ 'name' ];
						$model->wh_dalee_element_name = $xx2[ 'child' ][ 'name' ];
					}
					// ddd($model);

					$xx1 = $xx2 = $xx3 = 0;


					/// То самое преобразование ПОЛЯ Милисукунд
					//            $model->setDtCreateText( "NOW" );
					$model->setDtCreateText( $model[ 'dt_create' ] );


					///||||||||||||||||||||||||||||||||||
					/// Подсчет СТРОК Всего
					///
					if(isset( $model[ 'array_tk_amort' ] ) && !empty( $model[ 'array_tk_amort' ] )
					   && is_array( $model[ 'array_tk_amort' ] ))
					{
						$xx1 = count( $model[ 'array_tk_amort' ] );
					}

					if(isset( $model[ 'array_tk' ] ) && !empty( $model[ 'array_tk' ] )
					   && is_array( $model[ 'array_tk' ] ))
					{
						$xx2 = count( $model[ 'array_tk' ] );
					}

					if(isset( $model[ 'array_casual' ] ) && !empty( $model[ 'array_casual' ] )
					   && is_array( $model[ 'array_casual' ] ))
					{
						$xx3 = count( $model[ 'array_casual' ] );
					}

					$model[ 'array_count_all' ] = (int) $xx1 + $xx2 + $xx3;


					///
					/// INTELLIGENT
					///
					///  Приводим INTELLIGENT в прядок!
					///  Прописываем каждому элементу
					$model->array_tk_amort = Spr_globam_element::array_am_to_intelligent( $model->array_tk_amort );
					///
					///  INTELLIGENT


					//ddd($model);

					///
					///  Проверяем. Если
					///  Конечный склад В РАСХОДНОЙ НАКЛАДНОЙ является ЦС!!!
					/// то....
					///
					//	            $new_doc->wh_cs_number = (int) $sklad_transfer[ 'wh_destination_element' ]; // ЦС

					if(isset( $model->wh_cs_number ) && $model->wh_cs_number > 0)
					{
						if(Sprwhelement::is_FinalDestination( [ $model->wh_destination_element ] ) &&
						   $model->sklad_vid_oper == Sklad::VID_NAKLADNOY_RASHOD)
						{
							$model->wh_destination_element_cs = 1;
						}
					}


					//ddd($model);

					if($model->save( true ))
					{
						return $this->render(
							'_form',
							[
								'new_doc'            => $model,
								'sklad'              => $sklad,
								'items_auto'         => $items_auto,
								'tz_head'            => $tz_head,
								'alert_mess'         => 'Сохранение.Успешно.',
								'spr_globam_element' => $spr_globam_element,


							] );
					} else
					{

						if(isset( $model->errors ))
						{
							$err = $model->errors;

							if(isset( $err[ 'array_tk_amort' ] ))
							{
								$err_str = implode( ', ', $err[ 'array_tk_amort' ] );

								//ddd($err_str);

								return $this->render(
									'_form',
									[
										'new_doc'            => $model,
										'sklad'              => $sklad,
										'items_auto'         => $items_auto,
										'tz_head'            => $tz_head,
										'alert_mess'         => 'Ошибка.' . $err_str,
										'spr_globam_element' => $spr_globam_element,

									] );
							}

						}
					}

				}
			}


			//        ddd($model);


			//ddd( $model );

			return $this->render(
				'_form', [
				'new_doc'            => $model,
				'sklad'              => $sklad,
				'items_auto'         => $items_auto,
				'tz_head'            => $tz_head,
				'erase_array'        => $erase_array,
				'alert_mess'         => '',
				'spr_globam_element' => $spr_globam_element,

			] );
		}


		/**
		 * Вход только по номеру склада и номеру накладной
		 * -
		 *
		 * @param $id
		 *
		 * @return string
		 * @throws UnauthorizedHttpException
		 */
        //public function actionUpdate_id( $id ) {
        public function actionUpdate_id()
        {
			$para = Yii::$app->request->queryParams;

			//ddd($para);

			if(isset( $para[ 'otbor' ] ) && !empty( $para[ 'otbor' ] ))
			{
				if( !Sklad::setSkladIdActive( $para[ 'otbor' ] ))
				{
					throw new UnauthorizedHttpException( '$_SESSION1 Не подключен.  Sklad=0' );
				}
			}

			//  ddd($para);
			$sklad = $para[ 'otbor' ];
			$id    = $para[ 'id' ];
			$el    = ( isset( $para[ 'el' ] ) ? $para[ 'el' ] : 0 );


			$model = Sklad::find()
				//            ->select(['oid'])
				//            ->where(['id'=>(int)$id])
				          ->where(
					[
						'AND',
						[ 'id' => (int) $id ],
						[ 'wh_home_number' => (int) $sklad ],
					] )
			              ->one();


			$model->tk_element = $el; /// Элемент для выделения крассным цветом


			////////////////// Автобусы ЕСТЬ?
//			if(isset( $model[ 'array_bus' ] ) && !empty( $model[ 'array_bus' ] ))
//			{
//				$items_auto = Sprwhelement::findAll_Attrib_PE(
//					array_map( 'intval', $model[ 'array_bus' ] )
//				);
//			} else
//			{
//				$items_auto = [];
//			} // ['нет автобусов'];


			/////////////////// Получаем ТехЗадание. ШАПКА
//			if($model->tz_id)
//			{
//				$tz_head = Tz::findModelDoubleAsArray( (int) $model->tz_id );
//			} else
//			{
//				$tz_head = [];
//			}


			return $this->render(
				'_form_read_only', [
				'new_doc' => $model,
				'sklad'   => $sklad,

				'items_auto'  => [],
				'tz_head'     => [],
				'erase_array' => [],
				'alert_mess'  => '',
			] );
		}


	}
