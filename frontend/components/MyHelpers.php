<?php

namespace frontend\components;


use app\models\Sklad;

//use app\models\Sprwhelement;
//use app\models\Sprwhtop;

use frontend\models\Sprwhelement;
use frontend\models\Sprwhelement_change;
use frontend\models\Sprwhtop;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\Exceptions\BarcodeException;
use Yii;
use yii\helpers\ArrayHelper;
use yii\mongodb\Query;


/**
 * Class MyHelpers
 * @package frontend\components
 */
class MyHelpers
{

    //protected $otbor;

    /**
     * Приводит Все ШТРИХКОДЫ к нормальному ВИДУ
     * =
     * принимает: строку
     *-
     * возвращает: строку
     * -
     *
     * @param $barcode_str
     *
     * @return string
     */
    public static function barcode_normalise($barcode_str)
    {

        if (empty($barcode_str))
            return '';

        if ($barcode_str == '0')
            return '';

        $barcode_str = preg_replace('/[^\d*]/', '', $barcode_str); // Удаляет ВСЕ, кроме цифр

        if (substr($barcode_str, 0, 6) == '019600') { //19600004992
            //$barcode_str = substr($barcode_str,0,11);
            $barcode_str = substr($barcode_str, 1, 12);
        }

        return $barcode_str;
    }

    /**
     * @param $name
     *
     * @return string
     */
    public static function hello($name = ' ====!!!! ')
    {
        return "Hello $name";
    }


    /**
     * @param string $collection_name
     * @param string $stolb_name
     *
     * @return mixed
     */
    public static function Min_id($collection_name = 'spr_type', $stolb_name = 'id')
    {
        $query = new Query;

        $query->select([$stolb_name])
            ->from($collection_name)
            ->orderBy($stolb_name . ' ASC')
            ->limit(1);
        $rows = $query->one();

        return $rows[$stolb];
    }


    /**
     * @param string $collection_name
     * @param string $stolb
     *
     * @return
     */
    public static function Mongo_max_id($collection_name = 'spr_type', $stolb = 'id')
    {
        //        $asc_name=($min_max==1?' ASC':' DESC');

        $query = new Query;

        $query->select([$stolb])
            ->from($collection_name)
            ->orderBy($stolb . ' DESC')
            ->limit(1);

        $rows = $query->one();

        //dd($rows);


        $max_value = $rows[$stolb];

        return $max_value;
    }

    /**
     * @param string $collection_name
     * @param string $pole_name
     * @param        $model_id
     * @param string $value
     *
     * @return bool
     */
    //public static function Mongo_save($collection_name='spr_nazvanie', $pole_name='id', $model_id, $value='' )


    /**
     * Вспомогательный Хелпер
     * -
     *
     * @param string $collection_name
     * @param string $pole_name
     * @param        $model_id
     * @param string $value
     *
     * @return bool
     */
    public static function Mongo_save($collection_name, $pole_name, $model_id, $value = '')
    {
        try {
            $collection = Yii::$app->mongodb->getCollection($collection_name);
            $collection->update(
                ['_id' => $model_id],
                [$pole_name => $value,]
            );
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }

        return true;
    }


    /**
     * Вспомогательный Хелпер. Ставит ФЛАГ в значение поднят, 1
     * -
     * Mongo. Монго. Флаг прописан по всей коллекции Склад одной командой
     * =
     *
     * @return bool
     */
    public static function Mongo_sklad_flag_on()
    {
        try {
            $collection = Yii::$app->mongodb->getCollection('sklad');
            $collection->update([],
                ['$set' =>
                    [
                        'flag' => 1,
                    ]
                ],
                ['multi' => true, 'timestamps' => true]
            );
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
        return true;
    }

    public static function Mongo_sklad_flag_on_oldver()
    {
        $all_ids = ArrayHelper::getColumn(Sklad::find()->all(), 'id');

        foreach ($all_ids as $one_id) {
            $model = Sklad::find()->where(['==', 'id', $one_id])->one();
            $model->flag = (int)1;

            if (!$model->save(true)) {
                ddd($model);
                return false;
            }
        }
        return true;
    }

    /**
     *
     * *
     * @return bool
     */
    public static function Mongo_exist_false()
    {

        $collection = Yii::$app->mongodb->getCollection('sklad');
        $xx = $collection->count(
            ["array_tk_amort" => ["wh_tk_amort" => ['$exist' => false]]],
            ['multi' => true]
        );

        //ddd($xx);

        return $xx;
    }

    public static function Mongo_sklad_flag_off()
    {
        try {
            $collection = Yii::$app->mongodb->getCollection('sklad');
            $collection->update([],
                //[ '$set' =>
                [
                    'flag' => 0,
                ]
            // ]
            //, [ 'multi' => true ]
            );
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
        return true;
    }



    /**
     * @param string $collection_name
     * @param int $model_id
     * @param int $max_value
     *
     * @return bool
     */
    //    public static function Mongo_save_id( $collection_name='spr_nazvanie', $model_id, $max_value=1 )
    public static function Mongo_save_id($collection_name, $model_id, $max_value = 1)
    {
        try {
            $collection = Yii::$app->mongodb->getCollection($collection_name);
            $collection->update(['_id' => $model_id],
                [
                    'id' => (integer)$max_value,
                ]
            );
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return false;
        }
        return true;
    }



    //    public static function Mongo_save_pvid( $collection_name='spr_nazvanie', $model_id, $value=88 )
    //    {
    //        try{
    //            $collection = Yii::$app->mongodb->getCollection($collection_name);
    //            $collection->update(['_id' => $model_id ],
    //                [
    //                    'pv_id' => (integer) $value ,
    //                ]
    //            );
    //        }catch (Exception $ex) {
    //            echo $ex->getMessage();return false;
    //        }
    //        return true;
    //    }


    /**
     * @param string $collection_name
     * @param        $model_id
     * @param int $value
     *
     * @return bool
     */
    //public static function Sklad_save_parent($collection_name='spr_nazvanie', $model_id, $value=1 )
    //    public static function Sklad_save_parent($collection_name,  $model_id, $value=1 )
    //    {
    //        $collection = Yii::$app->mongodb->getCollection($collection_name);
    //        $collection->update(['_id' => $model_id ],
    //            [
    //                'parent_id' => (integer) $value ,
    //            ]
    //        );
    //        return true;
    //    }


    //    /**
    //     * @param string $collection_name
    //     * @param int $model_id
    //     * @param string $date_value
    //     * @return bool
    //     */
    //    public static function Mongo_save_date($collection_name='pv', $model_id=0, $date_value='1972-01-01 00:00:00' )
    //    {
    //        try{
    //            $collection = Yii::$app->mongodb->getCollection($collection_name);
    //            $collection->update(['_id' => $model_id ],
    //                [
    //                    'date_mongo' => MyHelpers::to_isoDate($date_value) ,
    //                    'dt_create' => (string) $date_value ,
    //                ]
    //            );
    //        }catch (Exception $ex) {
    //            echo $ex->getMessage();   return false;
    //        }
    //
    //        return true;
    //    }


    /**
     * @param $timestr
     *
     * @return false|string
     */
    //    public static function to_isoDate($timestr){
    //
    //        //        // $orig_date = new DateTime('2016-06-27 13:03:33');
    //
    //        $orig_date = new DateTime($timestr);
    //        $orig_date=$orig_date->getTimestamp();
    //
    //        return new UTCDateTime($orig_date*1000);
    //    }

    //    /**
    //     * Вспомогательный Хелпер
    //     *-
    //     * @param $timestamp
    //     * @return UTCDateTime
    //     */
    //    public static function to_date_UTC($timestamp)
    //    {
    //        return new UTCDateTime($timestamp*1000); //  к виду: 1538381757000
    //
    //    }


    /**
     * @return string
     */
    public static function Excel_uniq()
    {
        $file = 'D:\txt\123_b.txt';

        if (file_exists($file)) {
            $mess = "OK";
        }

        $fp = fopen($file, "r");
        $x = 0;
        $arr_sum = [];
        //        $arr=[];
        //        $arr_uniq_name=[];

        // $fsave = fopen($file, "w");


        while (($line = fgets($fp, 4096)) !== false) {
            //echo $x."| ".$line.'<br>';

            $arr = explode(';', $line);
            $arr = array_map('trim', $arr);

            //                if (MyHelpers::array_rus_one($arr_sum, trim($arr[7]) )){
            //                    continue;
            //                };


            //echo '<b>'.$x."| ".$line.' </b><br>';

            //                $ass=[];

            //array_push( $ass, $arr[8]); // ID
            //array_push( $ass, $arr[7]); // NAME

            array_push($arr_sum, $arr[7]);

            $x++;

            if ($x > 62653)
                break;

        }

        dd($arr_sum);


        return $mess;
    }


    //    /**
    //     * @param array $arr
    //     * @param string $string
    //     * @return bool
    //     */
    //    public static function array_rus($arr=[], $string='' )
    //    {
    //        foreach ( $arr as $key => $str) {
    //            if ($str[1] === $string) {
    //                return true;
    //            };
    //        }
    //        return false;
    //    }

    //    /**
    //     * @param array $arr
    //     * @param string $string
    //     * @return bool
    //     */
    //    public static function array_rus_one($arr=[], $string='' )
    //    {
    //        foreach ( $arr as $str) {
    //            if ($str === $string) {
    //                return true;
    //            };
    //        }
    //        return false;
    //    }


    /**
     * @param $index
     *
     * @return string
     */
    public static function columnName($index)
    {
        $i = $index - 1;
        if ($i >= 0 && $i < 26) {
            return chr(ord('A') + $i);
        }
        if ($i > 25) {
            return (static::columnName($i / 26)) . (static::columnName($i % 26 + 1));
        }
        return 'A';
    }


    /////////////////

    /**
     * WH_BinaryTree() + buildTree()
     *
     * @param string $collection_name
     *
     * @return bool
     */
    //public static function WH_BinaryTree($collection_name='sprwh_top' )
    /**
     * @return array|null
     */
//    public static function Tree_()
//    {
//        $model_tree = Sprwhtop::find()->orderBy('id ASC, parent_id ASC ')->asArray()->all();
//        $model_tree2 = Sprwhelement::find()->where(['parent_id' => [
//            2, 3, 4, 5, 6, 7, 8, 9, 10,
//            11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
//            21, 22, 23, 24
//        ]])->orderBy('id ASC, parent_id ASC ')->asArray()->all();
//        $model_tree = array_merge($model_tree, $model_tree2);
//        ///
//        $model_res = self::buildTree($model_tree, 0);
//
//       // ddd($model_res);
//
//        return $model_res;
//    }

    /**
     * WH_BinaryTree() + buildTree()
     *
     * @param string $collection_name
     *
     * @return bool
     */
    //public static function WH_BinaryTree($collection_name='sprwh_top' )
    /**
     * @return array|null
     */
    public static function WH_BinaryTree()
    {
        //
        $model_tree = Sprwhtop::find()
            ->select(['id', 'parent_id', 'name'])
            ->where(['final_destination' => 1])
            ->orderBy('name ASC')
            ->asArray()
            ->all();
        //
        $model_tree2 = Sprwhelement::find()
            ->select(['id', 'parent_id', 'name'])
            ->where(['final_destination' => 1])
            ->orderBy('name ASC, parent_id ASC ')
            ->asArray()
            ->all();
        //
        $model_tree = array_merge($model_tree, $model_tree2);
        ///
        $model_res = self::buildTree($model_tree, 0);

        // ddd($model_res);

        return $model_res;
    }

    /**
     * WH_BinaryTree() + buildTree() + by_date
     *=
     * @param $date_timestamp
     * @param int $final_destination
     * @return array
     */
    public static function WH_BinaryTree_by_date($date_timestamp, $final_destination = 1)
    {
        //ini_set('memory_limit', '64M');

        //CS
        if ((int)$final_destination === 1) {
            $str = '$eq';

            // MЕНЬШЕ-OLD, Больше-NEW.
            //Теперь они сливаются вместе и не конфликтуют
            $array_ids_actual = Sprwhelement_change::array_Actual_Ids_and_GOS($date_timestamp);
            $array_ids_defective = Sprwhelement_change::array_Defective_Ids($date_timestamp);

        } else {
            $str = '$ne';

            $array_ids_actual = ArrayHelper::map(Sprwhelement::find()
                ->select(['id', 'parent_id', 'name'])
                ->all(), 'id', 'name');
            $array_ids_defective = [];
        }

        //
        $model_tree_top = ArrayHelper::map(Sprwhtop::find()
            ->select(['id', 'parent_id', 'name'])
            ->where([$str, 'final_destination', 1])
            ->orderBy('name ASC')
            ->all(), 'id', 'name');
        foreach ($model_tree_top as $key => $item) {
            $model_top[$key]['id'] = $key;
            $model_top[$key]['parent_id'] = 0;
            $model_top[$key]['name'] = $item;
        }

        //ddd($model_top);


        //
//        $model_tree2 = Sprwhelement::find()
//            ->select(['id', 'parent_id', 'name'])
//            ->where([$str, 'final_destination', 1])
//            ->orderBy('name ASC')
//            ->asArray()
//            ->all();
//
//        ddd($model_tree2);


        $model_tree2 = ArrayHelper::map(Sprwhelement::find()
            ->select(['id', 'parent_id'])
            ->where([$str, 'final_destination', 1])
            ->orderBy('name ASC')
            ->all(), 'id', 'parent_id');

        $model_tree2_name = ArrayHelper::map(Sprwhelement::find()
            ->select(['id', 'name'])
            ->where([$str, 'final_destination', 1])
            ->orderBy('name ASC')
            ->all(), 'id', 'name');


        $array_rez = [];


        /// Замена новых ГОС номеров старыми по справочнику замен
        foreach ($model_tree2 as $key_id => $key_parent_id) {


            ///
            if ((int)$final_destination === 1) {

                // Перезаписываем на новые значения
                if (isset($array_ids_actual[(int)$key_parent_id])) {
                    // Переподстановка Старого номера по актуальности на дату
                    $model_tree2[$key] = $array_ids_actual[(int)$key_parent_id];
                    //$item['name'] = $array_ids_actual[(int)$item['id']];
                }

                // Удаляем ненужные старые значения
                if (!isset($array_ids_defective[(int)$key_parent_id])) {
                    // Создаем массив
                    $array_rez[] = [
                        'parent_id' => (int)$key_parent_id,
                        'id' => $key_id,
                        'name' => $model_tree2_name[$key_id],
//                    'text' => $item['name'],
                    ];
                }
            } else {
                $array_rez[] = [
                    'parent_id' => (int)$key_parent_id,
                    'id' => $key_id,
                    'name' => $model_tree2_name[$key_id],
//                    'text' => $item['name'],
                ];

            }
        }


//        ddd($model_tree2);
//        ddd($array_rez);

        //AP + PE
        $model_tree = array_merge($model_top, $array_rez);

        //ddd($model_tree);

        ///
        //        $model_res = self::buildTree($model_tree, 0);

        return $model_tree;
    }

    /**
     * W
     *=
     * @param $array
     * @return array
     */
    public static function WH_BinaryTree_Normal($array)
    {
        foreach ($array as $item) {
            $array_ids[] = $item['parent_id'];
        }

        // Двойники удаляет из массива
        $array_ids = array_unique($array_ids);
        //ddd($array_ids);

        ///  OK
        ///
        $array_tree = [];
        $array_zzz = [];
        foreach ($array_ids as $item_zerro) {
            //0
            foreach ($array as $key_big => $item_big) {
                //ddd($array);

                /// TEXT
                $item_big = array_merge($item_big, ['text' => $item_big['name']]);
                //$item_big['name']='';
                //unset($item_big['name']);

                //ddd($item_big['parent_id']);
                if ((int)$item_big['parent_id'] === (int)$item_zerro) {
                    $arr_ch[$item_big['id']] = $item_big;
                }
            }

            ///level 0
            if ($item_zerro == 0) {
                $array_zzz = $arr_ch;
            }
            unset($arr_ch);
        }


        // ddd($array_zzz);

        ///2222
        foreach ($array as $key_big => $item_big) {
            /// TEXT
            $item_big = array_merge($item_big, ['text' => $item_big['name']]);

            //if ($item_big['parent_id'] != 0 && (int)$item_big['parent_id'] != (int)$item_zerro) {
            if ($item_big['parent_id'] != 0) {

                //$array_zzz[(int)$item_big['parent_id']]['nodes'][] = $item_big;
                if (isset($item_big['parent_id']) && !empty($item_big['parent_id'])) {
                    if (isset($item_big)) {

                        $array_zzz[$item_big['parent_id']]['nodes'][] = [
                            'id' => $item_big['id'],
                            'parent_id' => $item_big['parent_id'],
                            'name' => '',
                            'text' => $item_big['name']
                        ];

                    }
                }
            }
        }
        //ddd($array);
        //ddd($array_zzz);


        foreach ($array_zzz as $item) {
            $arr_out[] = $item;
        }

        return $arr_out;
    }

    /**
     * WH_BinaryTree() + buildTree() + by_date
     *=
     * @param $date_timestamp
     * @param array $parent_ids
     * @return array
     */
    public static function WH_BinaryTree_by_date_onlyWH($date_timestamp, $parent_ids = [])
    {
        //ini_set('memory_limit', '64M');

        //
        $model_tree_top = ArrayHelper::map(Sprwhtop::find()
            ->select(['id', 'parent_id', 'name'])
            ->where(['$in', 'id', $parent_ids])
            ->orderBy('name ASC')
            ->all(), 'id', 'name');

        $model_top = [];
        foreach ($model_tree_top as $key => $item) {
            $model_top[$key]['id'] = $key;
            $model_top[$key]['parent_id'] = 0;
            $model_top[$key]['name'] = $item;
        }

        $model_tree2 = ArrayHelper::map(Sprwhelement::find()
            ->select(['id', 'parent_id'])
            ->where(['$in', 'parent_id', $parent_ids])
            ->orderBy('name ASC')
            ->all(), 'id', 'parent_id');


        $model_tree2_name = ArrayHelper::map(Sprwhelement::find()
            ->where(['$in', 'parent_id', $parent_ids])
            ->orderBy('name')
            ->all(), 'id', 'name');

        $array_rez = [];


        /// Замена новых ГОС номеров старыми по справочнику замен
        foreach ($model_tree2 as $key_id => $key_parent_id) {
            $array_rez[] = [
                'parent_id' => (int)$key_parent_id,
                'id' => $key_id,
                'name' => $model_tree2_name[$key_id],
//                    'text' => $item['name'],
            ];
        }


        //AP + PE
        $model_top = array_merge($model_top, $array_rez);

        return $model_top;
    }


    /**
     * RECURSE Супер-функция производит дерево из любого массива (id, parent_id)
     *
     * @param array $elements
     * @param int $parentId
     *
     * @return array
     */
    public static function buildTree(array $elements, $parentId = 0)
    {
        $branch = [];
        //
        foreach ($elements as $key => $element) {
            if ($element['parent_id'] == $parentId) {

                if (!isset($element['name']) || empty($element['name'])) {
                    return $branch;
                }


                $element['text'] = $element['name']; ///////

                $children = MyHelpers::buildTree($elements, $element['id']);
                if ($children) {
                    $element['nodes'] = $element['name']; ///////

                    $buf = $children; ///////
                    $x = 0;           ///////
                    foreach ($buf as $nnn) { ///////
                        $children[$x]['text'] = $nnn['name'];///////
                        $x++;       ///////
                    }           ///////

                    $element['nodes'] = $children;
                }

                $branch[] = $element;
            }
            if (isset($elements[$key]['name'])) {
                unset($elements[$key]['name']);
            }
        }

        return $branch;
    }


    /**
     * RECURSE Супер-функция производит дерево из любого массива (id, parent_id)
     *
     * @param array $elements
     * @param int $parentId
     *
     * @return array
     */
    public static function buildTree_byArray(array $elements, $parentId = 0)
    {
        $branch = [];
        //
        ddd($elements);
        foreach ($elements as $key => $element) {


            if ($element['parent_id'] != (int)$parentId) {
                continue;
            }

            if ($element['parent_id'] == $parentId) {
                //

                $branch = $element; ///////
                $branch['text'] = $element['name']; ///////

//                dd($branch);

//                dd($element);
//                ddd($elements);

                $children = MyHelpers::buildTree_byArray((array)$elements, (int)$element['id']);

                if (isset($children) && !empty($children)) {
                    //
                    $element['nodes'] = $element['name'];

                    //
                    $x = 0;
                    foreach ($children as $nnn) {
                        $children[$x]['text'] = (isset($nnn['name']) ? $nnn['name'] : $x);
                        $x++;
                    }

                    $element['nodes'] = $children;

                    dd($children);
                    dd($branch);

                }


//                $branch[] = $element;
                unset($elements[$key]);
            }
//            if (isset($elements[$key]['name'])) {
//                unset($elements[$key]['name']);
//            }

//            echo memory_get_usage() . "\n"; // 36640

//            dd($parentId);

        }

//        unset($elements);


        return $branch;
    }


    /**
     * /////////////////////
     * // Заполняю таблицу СКЛАДЫ-TREE
     *
     * @return string
     */
    //    public static function excel_wh_add_tree()
    //    {
    //        $file='D:\txt2\123.csv';
    //
    //        if (file_exists($file)) {
    //            $mess = "OK";
    //        }
    //        $fp = fopen($file, "r");
    //
    //
    //        $buf='';
    //        $start_num_0 = $start_num_1 = 1;
    //
    //        $x=$start_num_1;
    //        while (($line = fgets($fp, 4096)) !== false) {
    //
    //            $arr=explode( ';',$line );
    //            $arr= array_map('trim', $arr);
    //
    //                /*            if (MyHelpers::array_rus_one($arr_sum, trim($arr[7]) )){
    //                                continue;
    //                            };*/
    //
    //            if ( $buf == $arr[0])
    //            {
    //                if ( !MyHelpers::Mongo_ex_add_tree( $x, $start_num_0, $arr[1], "spr_sklad_tree" ) )
    //                    return false ;
    //
    //            }
    //            else{
    //                $buf = $arr[0]; // пропишем для сравнения
    //                $start_num_0=$x;
    //                if ( !MyHelpers::Mongo_ex_add_tree( $x, 0 , $arr[0], "spr_sklad_tree" ) )
    //
    //
    //                    return false ;
    //
    //            }
    //
    //
    //
    //
    //            echo $x."| ".$start_num_0." | ".$line.'<br>';
    //
    //
    //
    //
    //            $x++;
    //            if($x>62653) break;
    //        }
    //
    //
    //        return $mess;
    //    }

    /**
     * /////////////////////
     * // Заполняю таблицу СКЛАДЫ
     *
     * отдельно склады - предприятия,
     * отдельно автобусы
     *
     * @return string
     */
    //    public static function excel_to_wh()
    //    {
    //        $file='D:\txt2\123.csv';
    //
    //        if (file_exists($file)) {
    //            $mess = "OK";
    //        }
    //        $fp = fopen($file, "r");
    //
    //
    //        $buf='';
    //        $x_top=0;
    //        $x=0;
    //
    //        while (($line = fgets($fp, 4096)) !== false) {
    //
    //            $arr=explode( ';',$line );
    ////            $arr= array_map('trim', $arr);
    //
    //
    //            if ( $buf == $arr[0])
    //            {
    //                if ( !MyHelpers::Mongo_ex_add( $x, $x_top, $arr[1], "sprwh_element" ) )
    //                    return false ;
    //
    //            }
    //            else{
    //                $buf = $arr[0]; // пропишем для сравнения
    //                $start_num_0=$x;
    //                $x_top++;
    //                if ( !MyHelpers::Mongo_ex_add( $x_top, 0, $arr[0], "sprwh_top" ) )
    //                    return false ;
    //
    //            }
    //
    //
    //
    //
    //            echo $x."| ".$start_num_0." | ".$line.'<br>';
    //
    //
    //
    //
    //            $x++;
    //            if($x>62653) break;
    //        }
    //
    //
    //        return $mess;
    //    }

    /**
     * @param        $id
     * @param        $parent_id
     * @param        $str
     * @param string $collection_name
     *
     * @return bool
     */
    //    public static function Mongo_ex_add_tree($id, $parent_id, $str, $collection_name='spr_sklad' )
    //    {
    //        $collection = Yii::$app->mongodb->getCollection($collection_name);
    //
    //        $collection->insert([
    //            'id' => (integer) $id,
    //            'parent_id' => (integer) $parent_id,
    //            'name' => $str ]);
    //        return true;
    //    }


    /**
     * /////////////////////
     * // Заполняю таблицу СКЛАДЫ
     *
     * отдельно склады - предприятия,
     * отдельно автобусы
     *
     * @return string
     */
    //    public static function excel_to_2()
    //    {
    ////        $file='D:\txt2\park_auto\1.csv';
    ////        $file='D:\txt2\park_auto\2.csv';
    //        $file='D:\txt2\park_auto\4444.csv';
    //
    //        if (file_exists($file)) {
    //            $mess = "OK";
    //        }
    //        $fp = fopen($file, "r");
    //
    //
    ////        $buf='';
    //        $x_top=0;
    //        $x=1;
    //
    ////        $park='';
    ////        $auto='';
    ////        $buf_1='';
    //
    //        while (($line = fgets($fp, 4096)) !== false) {
    //
    //            $arr=explode( ';',$line );
    //
    //            $arr= array_map('trim', $arr);
    //
    //
    //
    //            if (empty($arr[0]) && empty($arr[1]))
    //                continue;
    //
    //
    ////            if ( !empty($arr[0]) )
    ////            {
    ////                $park=$arr[0];
    ////            }
    ////
    ////            if ( !empty($arr[1]) )
    ////            {
    ////                $auto=$arr[1];
    ////            }
    //
    //            //vv($arr);
    //
    //            $arr[0]=preg_replace('{\t*\n*\r*}','',$arr[0]);
    //            $arr[1]=preg_replace('{\t*\n*\r*}','',$arr[1]);
    //
    //            $arr[0]=trim($arr[0]);
    //            $arr[1]=trim($arr[1]);
    //
    //            //dd( MyHelpers::Mongo_find(  'sprwh_element', $arr[1]) );
    //
    //            if (  empty($arr[0]) )
    //            {
    //                    if( !MyHelpers::Mongo_find_wh_element( $arr[1])  )
    //                    {
    ////                        $buf_1 = $arr[1];
    //                        MyHelpers::Mongo_ex_add( $x, $x_top, $arr[1], "sprwh_element" );
    //                     }
    //
    //            }
    //            else{
    //                $buf = $arr[0]; // пропишем для сравнения
    //                $start_num_0=$x;
    //                $x_top++;
    //                if( !MyHelpers::Mongo_find_wh_top( $arr[0])  ) {
    //                    MyHelpers::Mongo_ex_add($x_top, 0, $arr[0], "sprwh_top");
    //                }
    //
    //            }
    //
    //
    //            echo $x."| ".$start_num_0." | ".$line.'<br>';
    //
    //            $x++;
    //            if($x>62653) break;
    //        }
    //
    //
    //        return $mess;
    //    }


    //    public static function excel_to_3()
    //    {
    //        $file='D:\txt2\park_auto\4444.csv';
    //
    //        if (file_exists($file)) {
    //            $mess = "OK";
    //        }
    //        $fp = fopen($file, "r");
    //
    //
    ////        $buf='';
    //        $x_top=0;
    //        $x=1;
    //
    ////        $park='';
    ////        $auto='';
    //        $buf_1='';
    //
    //        while (($line = fgets($fp, 4096)) !== false) {
    //
    //            $arr=explode( ';',$line );
    //            $arr= array_map('trim', $arr);
    //
    //            if (empty($arr[0]) && empty($arr[1]) && empty($arr[2]))
    //                continue;
    //
    ////            if ( !empty($arr[0]) )   $id=$arr[0];
    ////            if ( !empty($arr[1]) )   $auto=$arr[1];
    ////            if ( !empty($arr[2]) )   $park=$arr[1];
    //
    //
    //
    //            $str_super= str_replace(')', ') ', $arr[1]);
    //
    //            $str1= str_replace('(', '', $arr[1]);
    //            $str1= str_replace('#', '', $str1);
    //
    //            if ( strpos($str1,')')>0 )
    //            {
    //                $bort=explode( ')',$str1 );
    //            }
    //            else{
    //                $bort[0]='';        // номер борта
    //                $bort[1]=$str1;     // гос.рег.номер
    //            }
    //
    ////            vv($arr);
    ////            vv($bort);
    //
    //
    //            if ( $buf_1!=$arr[2] ){
    //                $x_top++;
    //                if( !MyHelpers::Mongo_find_wh_top( $arr[2])  )
    //                {
    //                    MyHelpers::Mongo_add_to_wh($x_top,(integer) 0, $arr[2],
    //                        $bort[0], $bort[1],
    //                        (string) "sprwh_top");
    //                }
    //                if( !MyHelpers::Mongo_find_wh_element( $arr[1])  )
    //                {
    //                    MyHelpers::Mongo_add_to_wh( $x, $x_top,
    //                        $str_super,
    //                        $bort[0], $bort[1],
    //                        (string) "sprwh_element" );
    //                }
    //            }
    //
    //            if ( $buf_1==$arr[2] )
    //            {
    //               if( !MyHelpers::Mongo_find_wh_element( $arr[1])  )
    //                {
    //                     MyHelpers::Mongo_add_to_wh( $x, $x_top,
    //                         $str_super,
    //                         $bort[0], $bort[1],
    //                         (string) "sprwh_element" );
    //                }
    //            }
    //
    //
    //
    //            echo $x."| ".$x_top." | ".$line.'<br>';
    //
    //            $x++;
    //            $buf_1 = $arr[2];
    //
    //            if($x>62653) break;
    //        }
    //
    //
    //        return $mess;
    //    }

    /**
     * @param string $str
     *
     * @return bool
     */
    public static function Mongo_find_wh_element($str = '')
    {
        Sprwhelement::find()->where(['like', 'name', $str, true])->one();

        if (Sprwhelement::find()->where(['like', 'name', $str, true])->one() != null) {
            return true;
        }

        return false;
    }

    /**
     * @param string $str
     *
     * @return bool
     */
    public static function Mongo_find_wh_top($str)
    {
        Sprwhelement::find()->where(['like', 'name', $str, true])->one();

        if (Sprwhelement::find()->where(['like', 'name', $str, true])->one() != null) {
            return true;
        }

        return false;
    }


    /**
     * START UPLOAD
     *
     * @param        $id
     * @param        $str
     * @param string $collection_name
     *
     * @return bool
     */
    //    public static function Mongo_ex_add($id, $parent_id, $str, $collection_name='spr_BAD____' )
    //    {
    //        $str_ = explode ('   ',$str);
    //
    //        if($parent_id==0)
    //        {
    //            $str_[0]=$str_[0]." ".$str_[1]." ".$str_[2];
    //            $str_[1]=$str_[2]='';
    //        }
    //
    //
    //        $collection = Yii::$app->mongodb->getCollection($collection_name);
    //
    //        $collection->insert([
    //            'id' => (integer) $id,
    //            'parent_id' => (integer) $parent_id,
    //            'name' => $str_[0],
    //            'tx' => (isset($str_[1])?$str_[1]:'')
    //        ]);
    //        return true;
    //    }


    /**
     * @param        $id
     * @param int $parent_id
     * @param        $menu_name
     * @param        $nomer_borta
     * @param        $nomer_gos_registr
     * @param string $collection_name
     *
     * @return bool
     */
    //    public static function Mongo_add_to_wh ( $id, $parent_id=0, $menu_name,
    //                                             $nomer_borta, $nomer_gos_registr,
    //                                             $collection_name='spr_BAD_WH!' )
    //    {
    //
    //        try{
    //            $collection = Yii::$app->mongodb->getCollection($collection_name);
    //            $collection->insert([
    //                'id' => (integer) $id,
    //                'parent_id' => (integer) $parent_id,
    //
    //                'name' => $menu_name,
    //                'nomer_borta' => $nomer_borta,
    //                'nomer_gos_registr' => $nomer_gos_registr,
    //                'tx'=>'',
    //            ]);
    //
    //        }catch (Exception $ex) {
    //            echo $ex->getMessage();return false;
    //        }
    //        return true;
    //    }
    //

    /**
     * @param string $str_number
     *
     * @return string
     * @throws BarcodeException
     */
    public static function Barcode_HTML($str_number = '10101')
    {
        ////////////// PDF - Barcode
        $html = '';
        $html .= '<div class="bar_code" style="position: absolute;top: 10px;right: 50px;">';

        $generator = new BarcodeGeneratorPNG();
        $html .= '<img src="data:image/png;base64,' . base64_encode(
                $generator->getBarcode($str_number, $generator::TYPE_CODE_128)) . '">';

        $html .= '<div>' . $str_number;
        $html .= '</div>';
        $html .= '</div>';
        //////////////
        return $html;
    }

    /**
     * ДЛЯ ПРИНТЕРА ЭТИКЕТОК
     *=
     * @param string $str_number
     * @return string
     * @throws BarcodeException
     */
    public static function Xprinter_Barcode($str_number = '10101')
    {
        $html = '';
        $generator = new BarcodeGeneratorPNG();
        $html .= '<img style="height:18px;width: 188px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($str_number, $generator::TYPE_CODE_128)) . '">';
        $html .= '<div class="str_number">' . $str_number . '</div>';
        return $html;
    }


    /**
     * @param int $id
     * @param string $html_string
     *
     * @return bool
     */
    //    public static function  FixCross( $id_AA='AA', $id=33333, $html_string='текст для распечатки')
    //    {
    //        if (isset($model_old))   unset($model_old);
    //
    //        $id_AA=strtoupper($id_AA);
    //
    //        // Ищем такой ИД
    //        $model_old = Cross::find()
    //            ->orderBy(['dt_print'])
    //            ->andWhere(['bar_code_int'=>(integer) $id ])
    //            ->andWhere(['bar_code_aa'=> $id_AA ])
    //            ->andWhere(['parent_id'=>(integer) 0 ])
    //            ->one();
    //
    //
    //
    //        ///////////////////
    //            // $max_value  = MyHelpers::Mongo_max_id('cross','id');
    //        $max_value  = Cross::find() ->max('id');
    //        $max_value++;
    //        ///////////////////
    //
    //
    //        ////Создаем новую строку
    //        $model = new Cross();
    //        $model->load(Yii::$app->request->post());
    //
    //        $model->id         = (integer)$max_value;
    //        $model->dt_print   = date("d.m.Y H:i:s", strtotime('now'));
    //
    //            $str_pos = $id_AA." ".str_pad($id, 10, "0", STR_PAD_LEFT);
    //            $model->bar_code_aa = $id_AA;
    //            $model->bar_code_int = $id;
    //            $model->bar_code_cross = $str_pos;
    //            $model->user_id =(integer) Yii::$app->getUser()->identity->id ;
    //            $model->user_group_id =(integer) Yii::$app->getUser()->identity->group_id;
    //
    //
    //        /////////////
    //        if( $model_old ){
    ////            dd($model_old);
    //
    //            $model->parent_id  = (integer)$model_old->id;
    //            $model->dt_create  = date("d.m.Y H:i:s", strtotime($model_old->dt_create));
    //            //$model->html_text  = $model_old->html_text;
    //            $model->html_text  = "Копия";
    //        }
    //        else{
    //            $model->parent_id  = (integer) 0;
    //            $model->dt_create  = date("d.m.Y H:i:s", strtotime("now"));
    //            $model->html_text  = $html_string;
    //        }
    //        /////////////
    //
    //
    //        unset($model_old);
    //        $model->save(true);
    //
    //    }


}