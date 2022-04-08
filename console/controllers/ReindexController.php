<?php

namespace console\controllers;

use frontend\models\Sklad;
use frontend\models\Sklad_inventory;
use frontend\models\Sklad_past_inventory;
use yii\console\Controller;
use yii\helpers\Console;


/**
 * Reindex sklad statistics
 */

class ReindexController extends Controller
{

    /**
     * 1
     * Проверка actionIndex
     */
    public function actionIndex()
    {

        $name = $this->ansiFormat('Alex', Console::FG_YELLOW);
        echo "=== " . $name;

        echo "

Ok Yes, cron service is running...

";

        return 0;
    }


    /**
     * REGENERATE INVENORY
     *
     * Входящий остаток +Приход -Расход = Итог (по всем Складам)
     * КРАЙНЯЯ ИНВЕНТАРИЗАЦИЯ
     *
     * Cron подключен в эту точку входа. Каждые три часа начало тут.
     * -
     * временно : по номеру списку активных складов ИНВЕНТАРИЗАЦИИ
     *
     */
    public function actionPast_inventory_summary()
    {
				/// Список всех активных складов
				//$list_keys = Sklad::ArraySklad_Uniq_wh_numbers();

        //// Список всех активных ИНВЕНТАРИЗАЦИЙ складов
        $list_keys = Sklad_inventory::Array_inventory_ids();

				//        print_r($list_keys);
				//    [0] => 86
				//    [1] => 4229
		
        foreach ($list_keys as $item) {
            echo " | Base_inventory = " . $item;
            echo "";

            $this->actionPast_inventory_id($item);
        }

        return 0;
    }


    /**
     * 2
     *
     * Подсчет Итогов по Складу ($sklad_id)
     *
     * @param $sklad_id
     * @return bool|void
     */

    public function actionPast_inventory_id( $sklad_id )
    {
		
		//   if(!isset($sklad_id)){
		//        $sklad_id=86;
		//   }

        /////////
        $array_items_inventory = Sklad_inventory::find()
            ->select(
                [
                    'id',

                    'dt_create',
                    'dt_create_timestamp',
					
                    //'dt_start',
                    //'dt_start_timestamp',

                    'wh_destination',
                    'wh_destination_element',

                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',

                    'array_tk.wh_tk',
                    'array_tk.wh_tk_element',
                    'array_tk.ed_izmer',
                    'array_tk.ed_izmer_num',
                    //'array_tk.name_ed_izmer',

                ])
            //->where( ['id'=>(int)$inventory_id] )

            ->where(
                [
                    'OR',
                    ['wh_destination_element' => (int)$sklad_id],
                    ['wh_destination_element' => (string)$sklad_id],
                ]
            )
            //->orderBy('dt_start_timestamp DESC')// Внимание! ОШИБКА НАЗВАНИЯ!!!
            ->orderBy('dt_create_timestamp DESC')
            ->asArray()
            ->one();

		
         // print_r($array_items_inventory);
		 // die();


        if (!isset($array_items_inventory) || empty($array_items_inventory)) {
            return 0;
        }


        /// ИНВЕНТАРИЗАЦИИ
        //                if( !isset($array_items_inventory) || empty($array_items_inventory) ){
        //                    throw new NotFoundHttpException('Нет ИНВЕНТАРИЗАЦИИ по заданному складу');
        //                }


//        print_r($array_items_inventory);
//        return 0;


        // Промежуточные результаты Инвентаризаций
        // каждые Три часа сохраняются тут:
        $model = new Sklad_past_inventory();


        $max_value = Sklad_past_inventory::find()->max('id');
        $max_value++;

        //////////////

        //$model->dt_start = $array_items_inventory['dt_start'];
        //$model->dt_start_timestamp = $array_items_inventory['dt_start_timestamp'];
  
		$model->dt_start = $array_items_inventory['dt_create'];
        $model->dt_start_timestamp = $array_items_inventory['dt_create_timestamp'];
		
		$model->dt_create = Date( 'd.m.Y H:i:s', strtotime( $array_items_inventory['dt_create'] ) );
		$model->dt_update = Date( 'd.m.Y H:i:s', strtotime( 'now' ) );
        $model->dt_create_timestamp = strtotime( 'now' );
        $model->dt_update_timestamp = strtotime( 'now' );


        // echo (123) ;
        // print_r ($model);


        $model->id = (int)$max_value;

        $model->wh_destination = $array_items_inventory['wh_destination'];
        $model->wh_destination_element = $array_items_inventory['wh_destination_element'];
        $model->wh_destination_name = Sklad_past_inventory::ArraySkladGroup_id_name($array_items_inventory['wh_destination']);
        $model->wh_destination_element_name = Sklad_past_inventory::ArraySklad_id_name($array_items_inventory['wh_destination_element']);


        //$arrayPrihodRashod =  $this->ArrayPrihodRashod($sklad_id, $dt_start);


        /// ???????
        $arrayPrihodRashod = $this->ArrayPrihodRashod(
            $array_items_inventory['wh_destination_element'],
            $array_items_inventory['dt_create_timestamp'] ///
                //$array_items_inventory['dt_start_timestamp'] /////????????
        );


        //        ddd($arrayPrihodRashod);


        // Неверный путь!!!!! ПОЛОВИНЧАТЫЙ
        // Надо еще новые приходы и расходы дописать к таблице СНИЗУ
        // Наша ЖАНЕЛЯ Убила гуппы в Амортизации!!!! Внимание!!! Теперь учет в одной группе!!!


        $array_itog_amort = [];
        $array_amort = $array_items_inventory['array_tk_amort'];

        foreach ($array_amort as $item) {
            $array_itog_amort[] = [
                'wh_tk_amort' => $item['wh_tk_amort'],
                'wh_tk_element' => $item['wh_tk_element'],
                'ed_izmer' => $item['ed_izmer'],
                'ed_izmer_num' => $item['ed_izmer_num'],
                //'name_ed_izmer' => "шт",

                'prihod_num' =>(isset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus'])?
                    $arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus']:0),

                'rashod_num' =>(isset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['minus'])?
                    $arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['minus']:0),

                'itog' => (
                    $item['ed_izmer_num']
                    +
                    (isset($arrayPrihodRashod['amort'][7][$item['wh_tk_element']]['plus']) ?
                        $arrayPrihodRashod['amort'][7][$item['wh_tk_element']]['plus'] : 0)

                    -
                    (isset($arrayPrihodRashod['amort'][7][$item['wh_tk_element']]['minus'])?
                        $arrayPrihodRashod['amort'][7][$item['wh_tk_element']]['minus']:0)

                ),

            ];
//            unset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['plus']);
//            unset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]['minus']);

            if (empty($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']])) {
                if(isset($arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']])){
                    unset( $arrayPrihodRashod['amort'][$item['wh_tk_amort']][$item['wh_tk_element']]);
                }
            }
            if (empty($arrayPrihodRashod['amort'][$item['wh_tk_amort']])) {

                if(isset($arrayPrihodRashod['amort'][$item['wh_tk_amort']])){
                    unset($arrayPrihodRashod['amort'][$item['wh_tk_amort']]);
                }
            }
        }

        //        ddd($array_itog_amort);


        $model->array_tk_amort = $array_itog_amort;


        $array_itog_amort = [];

        foreach ((array) $array_items_inventory['array_tk'] as $item) {

            $array_itog_amort[] = [
                'wh_tk' => $item['wh_tk'],
                'wh_tk_element' => $item['wh_tk_element'],
                'ed_izmer' => $item['ed_izmer'],
                'ed_izmer_num' => $item['ed_izmer_num'],
                //'name_ed_izmer' => $item['name_ed_izmer'],

                'prihod_num' =>
                    (isset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus']) ?
                        $arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus'] : 0),

                'rashod_num' =>
                    (isset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus']) ?
                        $arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus'] : 0),


                'itog' => (
                    $item['ed_izmer_num']
                    +
                    (isset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus']) ?
                        $arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus'] : 0)

                    -
                    (isset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus']) ?
                        $arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus']:0)

                ),

            ];

//            unset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['plus']);
//            unset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]['minus']);


            if (empty($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']])) {
                if(isset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']])){
                    unset($arrayPrihodRashod['tk'][$item['wh_tk']][$item['wh_tk_element']]);
                }
            }
            if (empty($arrayPrihodRashod['tk'][$item['wh_tk']])) {
                if(isset($arrayPrihodRashod['tk'][$item['wh_tk']])){
                    unset($arrayPrihodRashod['tk'][$item['wh_tk']]);
                }
            }

        }

        //$model->array_tk  = $array_itog_amort;
        //unset($array_itog_amort);

        //        ddd($arrayPrihodRashod['tk']);
        //// Вот теперь остались только(!) НОВЫЕ ПОЗИЦИИ в ПРИХОДЕ (и потом в расходе)

        $array_tk = $arrayPrihodRashod['tk'];
        if (isset($array_tk) && !empty($array_tk)) {
            foreach ($array_tk as $item_gr => $gr) {
                foreach ($gr as $item_id => $id) {
                    $array_itog_amort[] = [
                        'wh_tk' => $item_gr,
                        'wh_tk_element' => $item_id,
                        'ed_izmer' => 1,
                        'ed_izmer_num' => 0,

                        'prihod_num' =>
                            (isset($arrayPrihodRashod['tk'][$item_gr][$item_id]['plus'])?
                                $arrayPrihodRashod['tk'][$item_gr][$item_id]['plus']:0),

                        'rashod_num' =>
                            (isset($arrayPrihodRashod['tk'][$item_gr][$item_id]['minus'])?
                                $arrayPrihodRashod['tk'][$item_gr][$item_id]['minus']:0),

                        'itog' => (
                            (isset($id['ed_izmer_num']) ? $id['ed_izmer_num'] : 0)
                            +
                            (isset($arrayPrihodRashod['tk'][$item_gr][$item_id]['plus'])?
                                $arrayPrihodRashod['tk'][$item_gr][$item_id]['plus']:0)
                            -
                            (isset($arrayPrihodRashod['tk'][$item_gr][$item_id]['minus'])?
                                $arrayPrihodRashod['tk'][$item_gr][$item_id]['minus']:0)

                        ),

                    ];

                }
            }
        }


//echo (123);
//var_dump($array_itog_amort);

        $model->array_tk = $array_itog_amort;
		
        //$model->dt_start = ;



        if(!$model->save(true)){			
			 print_r($model->errors);	
			 die();
		}
		else{
			echo "\n";			
			print_r($model->id);	
			echo "\n";			
		}

        return 0;
    }


    /**
     * ДВИЖЕНИЕ ПОСЛЕ ИНВЕНТАРИЗАЦИИ
     *-
     * Возвращает массив с ПРРИХОДОМ и Расходом
     *
     * @param $sklad_id
     * @param $dt_create_timestamp
     *
     * @return mixed
     */


    private function ArrayPrihodRashod($sklad_id, $dt_create_timestamp)
    {

        //        ddd($dt_create_timestamp);
        //        ddd($sklad_id);

        /////////
        $array_items = Sklad::find()
            ->select(
                [
                    'id',
                    'sklad_vid_oper',
                    'dt_create',
                    'dt_create_timestamp',

                    'wh_debet_element',
                    'wh_destination_element',

                    'array_tk_amort.wh_tk_amort',
                    'array_tk_amort.wh_tk_element',
                    'array_tk_amort.ed_izmer',
                    'array_tk_amort.ed_izmer_num',

                    'array_tk.wh_tk',
                    'array_tk.wh_tk_element',
                    'array_tk.ed_izmer',
                    'array_tk.ed_izmer_num',

                    'array_casual.wh_tk',
                    'array_casual.wh_tk_element',
                    'array_casual.ed_izmer',
                    'array_casual.ed_izmer_num',

                ])
            ->where(
                [
                    'AND',
                    [
                        '>=',
                        'dt_create_timestamp',
                        $dt_create_timestamp,
                    ],

                    ['wh_home_number' => (int)$sklad_id],
                ]
            )
            ->asArray()
            ->all();

        //       ddd($array_items);


        //////////////
        //$array_itog_amort1=[];

        foreach ($array_items as $num_id) {    // $num_id - Это одна накладная целиком

            // ARRAY
            if (isset($num_id['array_tk_amort']) && !empty($num_id['array_tk_amort'])) {
                $array_bufer_amort = $num_id['array_tk_amort'];

                $x_plus = 0;
                $x_minus = 0;

                foreach ($array_bufer_amort as $item_pos) {

                    if ((int)$num_id['sklad_vid_oper'] == 2) {
                        $x_plus++;
                        $array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['plus'] =
                            (isset($array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['plus'])?
                                $array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['plus']:0)

                            + $item_pos['ed_izmer_num'];
                    }
                    if ((int)$num_id['sklad_vid_oper'] == 3) {
                        $x_minus++;
                        $array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['minus'] =
                            (isset($array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['minus']) ?
                                $array_itog_amort1[7][(int)$item_pos['wh_tk_element']]['minus'] : 0)
                            + $item_pos['ed_izmer_num'];
                    }

                }
            }
            // ddd( $array_itog_amort1 );


            // ARRAY array_tk
            if (isset($num_id['array_tk']) && !empty($num_id['array_tk'])) {
                $array_bufer_amort = $num_id['array_tk'];

                $x_plus = 0;
                $x_minus = 0;

                foreach ($array_bufer_amort as $item_pos) {

                    if ((int)$num_id['sklad_vid_oper'] == 2) {
                        $x_plus++;

                        //print_r( $item_pos);

                        $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] =
                            (isset($array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus']) ?
                                $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] : 0)
                            + $item_pos['ed_izmer_num'];
                    }
                    if ((int)$num_id['sklad_vid_oper'] == 3) {
                        $x_minus++;
                        $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] =
                            (isset($array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'])?
                                $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus']:0)

                            + $item_pos['ed_izmer_num'];
                    }

                }
            }
            //  ddd( $array_itog_array_tk );
            //  print_r( $array_itog_array_tk);


            // ARRAY array_casual
            if (isset($num_id['array_casual']) && !empty($num_id['array_casual'])) {
                $array_bufer_amort = $num_id['array_casual']; ///!!!!!!!!!!!  array_casual

                $x_plus = 0;
                $x_minus = 0;

                foreach ($array_bufer_amort as $item_pos) {

                    if ((int)$num_id['sklad_vid_oper'] == 2) {
                        $x_plus++;
                        $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] =
                            (isset($array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'])?
                                $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['plus'] :0)
                            + $item_pos['ed_izmer_num'];
                    }
                    if ((int)$num_id['sklad_vid_oper'] == 3) {
                        $x_minus++;
                        $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] =
                            (isset($array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus']) ?
                                $array_itog_array_tk[(int)$item_pos['wh_tk']][(int)$item_pos['wh_tk_element']]['minus'] : 0)
                            + $item_pos['ed_izmer_num'];
                    }

                }
            }
        }

        $array_itog_amort['amort'] = (isset($array_itog_amort1)?$array_itog_amort1:'');
        $array_itog_amort['tk'] = (isset($array_itog_array_tk)?$array_itog_array_tk:'');

        //$array_itog_amort['casual']=$array_itog_array_casual; /// Это - все в одном!!!

        //ddd( $array_itog_amort );

        return $array_itog_amort;
    }


}
