<?php

	namespace frontend\controllers;


	use Mpdf\Mpdf;
	use Mpdf\MpdfException;
    use frontend\models\Barcode_pool;
    use frontend\models\post_spr_glob_element;
    use frontend\models\post_spr_globam_element;
    use frontend\models\Sklad;
    use frontend\models\Spr_glob;
    use frontend\models\Spr_glob_element;
    use frontend\models\Spr_globam;
    use frontend\models\Spr_globam_element;
    use frontend\models\Spr_things;
    use frontend\models\Sprwhelement;
    use frontend\models\Tz;
	use frontend\components\MyHelpers;
	use Picqer\Barcode\Exceptions\BarcodeException;
	use Yii;
	use yii\base\ExitException;
	use yii\filters\VerbFilter;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\web\BadRequestHttpException;
	use yii\web\Controller;
    use yii\web\HttpException;
    use yii\web\NotFoundHttpException;
	use yii\web\Response;
	use yii\web\UnauthorizedHttpException;


	class Sklad_change_peController extends Controller {
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
						'update'     => [
							'GET',
							'POST',
						],
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
		 * Редактирование Накладной
		 * =
		 * ЦС внутри нашей накладной  по признаку
		 * $cs_number_id
		 * -
		 *
		 * @param $id
		 *
		 * @return string|Response
		 * @throws NotFoundHttpException
		 * @throws UnauthorizedHttpException
		 * @throws ExitException
		 */
		public function actionUpdate( $id ) {
			$para = Yii::$app->request->queryParams;
			$para_post = Yii::$app->request->post();

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
				$num_next_sklad = $para_post[ 'Sklad' ][ 'add_button' ];


				// Сложили ВСЕ МАССИВЫ ИЗ ДВУХ НАКЛАДНЫХ
				$different_nakl = Sklad::findArray_by_id_into_sklad( $sklad, $num_next_sklad );

				//ddd($different_nakl);

				if( !isset( $different_nakl ) || empty( $different_nakl ))
				{
					return $this->render(
						'_form',
						[
							'model'              => $model,
							'sklad'              => $sklad,
							'items_auto'         => $items_auto,
							'tz_head'            => $tz_head,
							'alert_mess'         => 'Не найдена накладная.',
							'spr_globam_element' => $spr_globam_element,

						] );
				}


				$model->array_tk_amort = Sklad::AddNaklad_to_Naklad( $model[ 'array_tk_amort' ], $different_nakl[ 'array_tk_amort' ] );
				$model->array_tk       = array_merge( $model[ 'array_tk' ], $different_nakl[ 'array_tk' ] );
				$model->array_casual   = array_merge( $model[ 'array_casual' ], $different_nakl[ 'array_casual' ] );


				if($model->save( true ))
				{

					//					/////////////////
					//					// Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
					//					$model[ 'array_tk_amort' ] = $this->getTkNames_am( $model[ 'array_tk_amort' ] );
					//
					//					// Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
					//					$model[ 'array_tk' ] = $this->getTkNames( $model[ 'array_tk' ] );

					//ddd($model);


					return $this->render(
						'_form',
						[
							'model'              => $model,
							'sklad'              => $sklad,
							'items_auto'         => $items_auto,
							'tz_head'            => $tz_head,
							'alert_mess'         => 'Сохранение.Успешно.',
							'spr_globam_element' => $spr_globam_element,
						] );
				}
				//				else
				//				{
				//					//ddd($model->errors);
				//				}


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

					//  ddd($model);

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

				if($model->save( true ))
				{

					/////////////////
					// Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
					$model[ 'array_tk_amort' ] = $this->getTkNames_am( $model[ 'array_tk_amort' ] );

					// Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
					$model[ 'array_tk' ] = $this->getTkNames( $model[ 'array_tk' ] );

					//ddd($model);


					return $this->render(
						'_form',
						[
							'model'              => $model,
							'sklad'              => $sklad,
							'items_auto'         => $items_auto,
							'tz_head'            => $tz_head,
							'alert_mess'         => 'Сохранение.Успешно.',
							'spr_globam_element' => $spr_globam_element,
						] );
				} else
				{
					ddd( $model->errors );
				}

			}

			//$spr_globam_element = ArrayHelper::map(Spr_globam_element::find()->all(),'id','name');

			$spr_globam_element = Spr_globam_element::name_plus_id();


			/////////////////
			// Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
			$model[ 'array_tk_amort' ] = $this->getTkNames_am( $model[ 'array_tk_amort' ] );

			// Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
			$model[ 'array_tk' ] = $this->getTkNames( $model[ 'array_tk' ] );


			//ddd(Yii::$app->request->post());
			//ddd(Yii::$app->getResponse());


			//ddd($model);

			/////////// ПРЕД СОХРАНЕНИЕМ
			///
			if($model->load( Yii::$app->request->post() ))
			{
				//ddd($model);
				//ddd(Yii::$app->request->post());


				//ddd(123);
				$model->wh_home_number = (int) $sklad;


				////  Долгая и нудная СОРТИРОВКА по Названию Компонентов
				///  ТАБ 1
				$model->array_tk_amort = Sklad::setArraySort1( $model->array_tk_amort );
				//					$model->array_tk_amort = Sklad::setArrayClear( $model->array_tk_amort );
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

				///
				///  Проверяем. Если
				///  Конечный склад В РАСХОДНОЙ НАКЛАДНОЙ является ЦС!!!
				/// то....
				///
				//	            $new_doc->wh_cs_number = (int) $sklad_transfer[ 'wh_destination_element' ]; // ЦС

				//					if(isset( $model->wh_cs_number ) && $model->wh_cs_number > 0)
				//					{
				//						if(Sprwhelement::is_FinalDestination( [ $model->wh_destination_element ] ) &&
				//						   $model->sklad_vid_oper == Sklad::VID_NAKLADNOY_RASHOD)
				//						{
				//							$model->wh_destination_element_cs = 1;
				//						}
				//					}


				//				if(
				//					isset( $para_post[ 'contact-button' ] ) &&
				//				   ( $para_post[ 'contact-button' ] == 'save_button')
				//				)
				//				{
				if($model->save( true ))
				{

					//ddd($model);

					/////////////////
					// Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
					$model[ 'array_tk_amort' ] = $this->getTkNames_am( $model[ 'array_tk_amort' ] );

					// Приводим МАссив в Читаемый ВИД с Помощью СПРАВОЧНИКОВ
					$model[ 'array_tk' ] = $this->getTkNames( $model[ 'array_tk' ] );

					//ddd($model);


					return $this->render(
						'_form',
						[
							'model'              => $model,
							'sklad'              => $sklad,
							'items_auto'         => $items_auto,
							'tz_head'            => $tz_head,
							'alert_mess'         => 'Сохранение.Успешно.',
							'spr_globam_element' => $spr_globam_element,
						] );
				} else
				{

					//ddd($model->errors);

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
									'model'              => $model,
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
			//			}


			//        ddd($model);


			//ddd( $model );

			return $this->render(
				'_form', [
				'model'              => $model,
				'sklad'              => $sklad,
				'items_auto'         => $items_auto,
				'tz_head'            => $tz_head,
				'erase_array'        => $erase_array,
				'alert_mess'         => '',
				'spr_globam_element' => $spr_globam_element,


			] );
		}

		/**
		 * Приводим Массив В читаемый вид
		 * С ПОМОЩЬЮ СПРАВОЧНИКОВ
		 *-
		 *
		 * @param $array_tk
		 *
		 * @return mixed
		 */
		public function getTkNames_am( $array_tk ) {
			$spr_globam_model         = ArrayHelper::map( Spr_globam::find()->orderBy( 'name' )->all(), 'id', 'name' );
			$spr_globam_element_model = ArrayHelper::map( Spr_globam_element::find()->orderBy( 'name' )->all(), 'id', 'name' );

			$spr_globam_element_model_intelligent = ArrayHelper::map( Spr_globam_element::find()->orderBy( 'name' )->all(), 'id', 'intelligent' );
			//ddd($spr_globam_element_model_intelligent);


			$buff = [];
			if(isset( $array_tk ) && !empty( $array_tk ))
			{
				foreach( $array_tk as $key => $item )
				{

					$buff[ $key ][ 'name_wh_tk_amort' ]   = $spr_globam_model[ $item[ 'wh_tk_amort' ] ];
					$buff[ $key ][ 'name_wh_tk_element' ] = $spr_globam_element_model[ $item[ 'wh_tk_element' ] ];
					//$buff[$key]['name_ed_izmer']=$spr_things_model[$item['ed_izmer']];

					$buff[ $key ][ 'name_ed_izmer' ] = 'шт';
					$buff[ $key ][ 'ed_izmer' ]      = '1';


					$buff[ $key ][ 'bar_code' ]    = ( $item[ 'bar_code' ] > 0 ? $item[ 'bar_code' ] : '' );
					$buff[ $key ][ 'intelligent' ] = ( (int) $spr_globam_element_model_intelligent[ $item[ 'wh_tk_element' ] ] );

					$buff[ $key ][ 'wh_tk_amort' ]   = $item[ 'wh_tk_amort' ];
					$buff[ $key ][ 'wh_tk_element' ] = $item[ 'wh_tk_element' ];
                    $buff[$key]['take_it'] = (isset($item['take_it']) ? $item['take_it'] : 0);
					$buff[ $key ][ 'ed_izmer_num' ]  = $item[ 'ed_izmer_num' ];

				}
			}

			return $buff;
		}

		/**
		 * Приводим Массив В читаемый вид
		 * С ПОМОЩЬЮ СПРАВОЧНИКОВ
		 *-
		 *
		 * @param $array_tk
		 *
		 * @return mixed
		 */
		public function getTkNames( $array_tk ) {
			$spr_glob_model         = ArrayHelper::map( Spr_glob::find()->orderBy( 'name' )->all(), 'id', 'name' );
			$spr_glob_element_model = ArrayHelper::map( Spr_glob_element::find()->orderBy( 'name' )->all(), 'id', 'name' );
			$spr_things_model       = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );


			$buff = [];
			if(isset( $array_tk ) && !empty( $array_tk ))
			{
				foreach( $array_tk as $key => $item )
				{

                    $buff[ $key ][ 'name_tk' ] = ( isset( $spr_glob_model[ $item[ 'wh_tk' ] ] ) ? $spr_glob_model[ $item[ 'wh_tk' ] ] : '' );
					$buff[ $key ][ 'name_tk_element' ] = $spr_glob_element_model[ $item[ 'wh_tk_element' ] ];
					$buff[ $key ][ 'name_ed_izmer' ]   = $spr_things_model[ $item[ 'ed_izmer' ] ];


					$buff[ $key ][ 'wh_tk' ]         = $item[ 'wh_tk' ];
					$buff[ $key ][ 'wh_tk_element' ] = $item[ 'wh_tk_element' ];
					$buff[ $key ][ 'ed_izmer' ]      = $item[ 'ed_izmer' ];
                    $buff[ $key ][ 'ed_izmer_num' ] = $item[ 'ed_izmer_num' ];

                    if ( isset( $item[ 'take_it' ] ) ) {
                        $buff[ $key ][ 'take_it' ] = $item[ 'take_it' ];
                    }

					//$buff[$key]['name']=$item['name'];

				}
			}

			//        ddd($array_tk);
			//        ddd($buff);

			return $buff;
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
			$html_css = $this->getView()->render( '/sklad_update/print/html_to_pdf/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad_update/print/html_to_pdf/_form_green', [
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
			$html_css = $this->getView()->render( '/sklad_update/print/html_to_pdf/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad_update/print/html_to_pdf/_form_inner_janel_demontage', [
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
			$html_css = $this->getView()->render( '/sklad_update/print/html_to_pdf/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad_update/print/html_to_pdf/_form_inner_janel', [
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
			$html_css = $this->getView()->render( '/sklad_update/print/html_akt_mont/_form_css.php' );

			//2
			$html = $this->getView()->render(
				'/sklad_update/print/html_akt_mont/_form', [
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

			$filename = 'Montage_' . date( 'd.m.Y_H-i-s' ) . '.pdf';

			//header('Content-type: application/pdf');
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
//			$model1 = ArrayHelper::map(
//				Spr_globam::find()
//				          ->all(), 'id', 'name' );

			$model2 = ArrayHelper::map(
				Spr_globam_element::find()
				                  ->orderBy( 'id' )
				                  ->all(), 'id', 'name' );


			///// NOT AMORT
//			$model3 = ArrayHelper::map(
//				Spr_glob::find()
//				        ->all(), 'id', 'name' );

			$model4 = ArrayHelper::map(
				Spr_glob_element::find()
				                ->orderBy( 'id' )
				                ->all(), 'id', 'name' );


			$model5 = ArrayHelper::map( Spr_things::find()->all(), 'id', 'name' );
			////////////////////


			//			ddd($model1);
			//			ddd($model5);


			//ddd( 123 );


			//1
			$html_css = $this->getView()->render( '/sklad_update/print/html_akt_demont/_form_css.php' );


			//2
			$html = $this->getView()->render(
				'/sklad_update/print/html_akt_demont/_form', [
				'model'  => $model,
				//            'model1' => $model1,
				'model2' => $model2,
				//            'model3' => $model3,
				'model4' => $model4,
				'model5' => $model5,
			] );

			//ddd($html);

			//  Тут можно подсмореть
			//$html = ss( $html );


			//ddd($html_css);

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

			$filename = 'DeMontage_' . date( 'd.m.Y_H-i-s' ) . '.pdf';

			//header('Content-type: application/pdf');
			$mpdf->Output( $filename, 'I' );


			return false;
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
		 * Полный список АМ. По запросу в MultipleInput(е)
		 *=
		 *
		 * @param $parent_id
		 *
		 * @return array
		 */
        //public function actionList_full_amort( $parent_id ) {
        public function actionList_full_amort()
        {
			return ArrayHelper::map(
				Spr_globam::find()
				          ->orderBy( "name" )
				          ->all(),
				'id', 'name' );
		}

		/**
		 * Добыает  Парент ИД для таблицы АСУОП в редактировании накладной
		 * =
		 *
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
		 * Добыает  Парент ИД для таблицы АСУОП в редактировании накладной
		 * =
		 * Возвращает ИД Аморта
		 * -
		 *
		 * @param $bar_code
		 *
		 * @return mixed
		 */
		public function actionId_amort_from_barcode( $bar_code ) {

			//    "id" : 14204,
			//    "element_id" : 2,
			//    "bar_code" : "19600005913"

			$model =
				Barcode_pool::find()
				            ->where( [ 'bar_code' => $bar_code ] )
				            ->one();

			return ( $model[ 'element_id' ] );

		}

		/**
		 * Добыает  Парент ИД для таблицы АСУОП в редактировании накладной
		 * =
		 *  Возвращает ИД Группы-Аморта
		 *
		 * @param $id
		 *
		 * @return mixed
		 */
		public function actionId_group_amort_from_id( $id ) {

			$model =
				Spr_globam_element::find()
				                  ->where( [ 'id' => (int) $id ] )
				                  ->one();

			return ( $model[ 'parent_id' ] );

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


	}