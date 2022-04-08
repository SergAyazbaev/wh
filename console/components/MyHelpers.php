<?php

namespace console\components;


use app\models\Sprwhelement;
use app\models\Sprwhtop;
//use MongoDB\BSON\UTCDateTime;

use Picqer\Barcode\BarcodeGeneratorPNG;
//use UTCDateTime\DateTime;
use yii\helpers\VarDumper;
use yii\mongodb\Query;



/**
 * Class MyHelpers
 * @package frontend\components
 */
class MyHelpers
{

    public static function ddd( $var = null )
    {
        echo '<br>';
        echo '<br>';
        echo '<b>ddd()</b>';

        if ( !isset( $var ) ) {
            echo '<b>ddd()</b> - пустое значение на входе';
            echo '';
            echo '';
            die();
        }

        if ( is_array( $var ) ) {
            echo 'ddd(is_array()) - массив <br>';
        }
        if ( is_object( $var ) ) {
            echo 'ddd(is_object()) - объект <br>';
        }
        if ( is_bool( $var ) ) {
            echo 'ddd(is_bool()) <br>';
        }

        echo "<pre>";
        VarDumper::dump( $var, 10, 1 );
        echo "<pre>";
        die();
    }


    /**
     * @param $var
     */
    function dd( $var )
    {
        echo "<pre>";
        print_r( $var );
        echo "<pre>";
        die();
    }


    /**
     * @param $var
     *
     * @return string
     */
    function ss( $var )
    {
        return htmlspecialchars( $var, ENT_QUOTES, 'utf-8' );
    }

    /**
     * @param $var
     */
    function vd( $var )
    {
        if ( $var ) {
            echo '<pre><b style="color: green;padding: 15px;">';
            var_dump( $var );
            echo '</b></pre><br>';
        } else{
            echo '<pre><b style="color: red;padding: 15px;">';
            var_dump( $var );
            echo '</b></pre><br>';
        }
    }


    /**
     * @param $var
     */
    function vv( $var )
    {
        var_dump( $var );
        //die();
    }

    /**
     * @param $var
     */
    function pr( $var )
    {
        static $int = 0;
        echo '<pre><b style="background: red;padding: 1px 5px;">'.$int.'</b> ';
        print_r( $var );
        echo '</pre>';
        $int++;
    }

    /**
     * @param $var
     */
    function v1( $var )
    {
        static $int = 0;
        echo '<pre><b style="background: blue;padding: 1px 5px;">'.$int.'</b> ';
        var_dump( $var );
        echo '</pre>';
        $int++;
    }
    //protected $otbor;

    /**
     * GLOBAL. Приводит Все ШТРИХКОДЫ к нормальному ВИДУ
     * =
     * принимает: строку
     *-
     * возвращает: строку
     * -
     * @param $barcode_str
     * @return string
     */
    public static function barcode_normalise($barcode_str ) {

        if(empty($barcode_str)) return '';

        if ($barcode_str == '0') return '';

        // Очистка от букв. Оставляем только цифры
        //        $barcode_str = preg_replace('/^([\d]*)([A-Z]?)/', '$1', $barcode_str); // Удаляет ВСЕ, кроме цифр

        $barcode_str = strtoupper( preg_replace( '/([\D]*)([0-9]*)([A-Z]+)([0-9]*)([\D]*)/iu', '$2$3$4', $barcode_str ) );

        //ddd($barcode_str);



        if (substr($barcode_str, 0, 6) == '019600') { //19600004992
            //$barcode_str = substr($barcode_str,0,11);
            $barcode_str = substr($barcode_str, 1, 12);
        }


        //0196000
        if (substr($barcode_str, 0, 6) == '019600') {
            $barcode_str = substr($barcode_str, 1, 11);
        }
        //196000
        if (substr($barcode_str, 0, 6) == '19600') {
            $barcode_str = substr($barcode_str, 0, 10);
        }


        //S0-S1
        $barcode_str = preg_replace( '/(S0|S1)(.*)$/i', '$2', $barcode_str );

        //ddd($barcode_str);



        return $barcode_str;
    }

   /**
     * @param $name
     * @return string
     */
    public static function hello($name=' ====!!!! ') {
        return "Hello $name";
    }


    /**
     * @param string $collection_name
     * @param string $stolb_name
     * @return mixed
     */
    public static function Min_id($collection_name='spr_type', $stolb_name='id') {
        $query = new Query;

        $query->select([$stolb_name])
            ->from($collection_name)
            ->orderBy( $stolb_name.' ASC')
            ->limit(1);
        $rows = $query->one();

        return $rows[$stolb];
    }


    /**
     * @param string $collection_name
     * @param string $stolb
     * @return
     */
    public static function Mongo_max_id($collection_name = 'spr_type', $stolb = 'id') {
//        $asc_name=($min_max==1?' ASC':' DESC');

        $query = new Query;

        $query->select([$stolb])
            ->from($collection_name)
            ->orderBy( $stolb.' DESC')
            ->limit(1);

        $rows = $query->one();

        //dd($rows);


        $max_value = $rows[$stolb];

        return $max_value;
    }

    /**
     * @param string $collection_name
     * @param string $pole_name
     * @param $model_id
     * @param string $value
     * @return bool
     */
    //public static function Mongo_save($collection_name='spr_nazvanie', $pole_name='id', $model_id, $value='' )


    /**
     * Вспомогательный Хелпер
     * -
     *
     * @param string $collection_name
     * @param string $pole_name
     * @param $model_id
     * @param string $value
     * @return bool
     */
    public static function Mongo_save($collection_name, $pole_name, $model_id, $value='' )
    {
//        try {
//            $collection = Yii::$app->mongodb->getCollection($collection_name);
//            $collection->update(
//                ['_id' => $model_id],
//                [$pole_name => $value,]
////            );
//        }catch (Exception $ex) {
//                echo $ex->getMessage();
//                return false;
//            }

        return true;
    }




    /**
     * @param string $collection_name
     * @param int $model_id
     * @param int $max_value
     * @return bool
     */
//    public static function Mongo_save_id( $collection_name='spr_nazvanie', $model_id, $max_value=1 )
//    public static function Mongo_save_id( $collection_name, $model_id, $max_value=1 )
//    {
//        try{
//            $collection = Yii::$app->mongodb->getCollection($collection_name);
//            $collection->update(['_id' => $model_id ],
//                [
//                    'id' => (integer) $max_value ,
//                ]
//            );
//        }catch (Exception $ex) {
//            echo $ex->getMessage();return false;
//        }
//        return true;
//    }



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
     * @param $model_id
     * @param int $value
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
    public static function Excel_uniq( )
    {
        $file='D:\txt\123_b.txt';

        if (file_exists($file)) {
            $mess = "OK";
        }

        $fp = fopen($file, "r");
        $x=0;
        $arr_sum=[];
//        $arr=[];
//        $arr_uniq_name=[];

       // $fsave = fopen($file, "w");


        while (($line = fgets($fp, 4096)) !== false) {
            //echo $x."| ".$line.'<br>';

                $arr=explode( ';',$line );
                $arr= array_map('trim', $arr);

//                if (MyHelpers::array_rus_one($arr_sum, trim($arr[7]) )){
//                    continue;
//                };


            //echo '<b>'.$x."| ".$line.' </b><br>';

//                $ass=[];

                //array_push( $ass, $arr[8]); // ID
                //array_push( $ass, $arr[7]); // NAME

                array_push($arr_sum, $arr[7]);

                $x++;

                if($x>62653) break;

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
     * @param string $collection_name
     * @return bool
     */
    public static function WH_BinaryTree($collection_name='spr_sklad' )
    {
        try{
            $model_tree=SprSklad::find()->orderBy('id ASC, parent_id ASC ')->asArray()->all();
            $model_res = \frontend\components\MyHelpers::buildTree($model_tree, 0);

        }catch (Exception $ex) {
            return null;
        }
        return $model_res;
    }


    /**
     * RECURSE Супер-функция производит дерево из любого массива (id, parent_id)
     *
     * @param array $elements
     * @param int $parentId
     * @return array
     */
    public function buildTree(array $elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $element['text']=$element['name']; ///////

                $children = MyHelpers::buildTree($elements, $element['id']);
                if ($children) {
                    //$element['nodes']=$element['name']; ///////


                    $buf=$children; ///////
                    $x=0;           ///////
                    foreach($buf as $nnn){ ///////
                        $children[$x]['text']=$nnn['name'];///////
                        $x++;       ///////
                    }           ///////

                    $element['nodes'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }



//$items = \frontend\components\MyHelpers::WH_BinaryTree();

//echo '<div class="tree_land">';
//
//echo TreeView::widget(['data' => $items,
//'size' => TreeView::SIZE_MIDDLE,   //SIZE_SMALL,
//'clientOptions' => ['onNodeSelected' => $onSelect,],]);
//
//echo '</div>';


//
//    /////////////////
//
//    /**
//     * WH_BinaryTree() + buildTree()
//     *
//     * @param string $collection_name
//     * @return bool
//     */
//    //public static function WH_BinaryTree($collection_name='sprwh_top' )
//    /**
//     * @return array|null
//     */
//    public static function WH_BinaryTree(  )
//    {
//        $model_tree=Sprwhtop::find()->orderBy('id ASC, parent_id ASC ')->asArray()->all();
//        $model_tree2=Sprwhelement::find()->where(['parent_id'=>[
//            2,3,4,5,6,7,8,9,10,
//            11,12,13,14,15,16,17,18,19,20,
//            21,22,23,24
//        ]])->orderBy('id ASC, parent_id ASC ')->asArray()->all();
//        //
//        $model_tree=array_merge($model_tree,$model_tree2);
//        //
//        $model_res =MyHelpers::buildTree($model_tree, 0);
//        return $model_res;
//    }
//
//
//    /**
//     * RECURSE Супер-функция производит дерево из любого массива (id, parent_id)
//     *
//     * @param array $elements
//     * @param int $parentId
//     * @return array
//     */
//    public static function buildTree(array $elements, $parentId = 0) {
//        $branch = array();
//
//        foreach ($elements as $element) {
//            if ($element['parent_id'] == $parentId) {
//                $element['text']=$element['name']; ///////
//
//                $children = MyHelpers::buildTree($elements, $element['id']);
//                if ($children) {
//                    //$element['nodes']=$element['name']; ///////
//
//
//                    $buf=$children; ///////
//                    $x=0;           ///////
//                    foreach($buf as $nnn){ ///////
//                        $children[$x]['text']=$nnn['name'];///////
//                        $x++;       ///////
//                        }           ///////
//
//                    $element['nodes'] = $children;
//                }
//                $branch[] = $element;
//            }
//        }
//
//        return $branch;
//    }


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
     * @param $id
     * @param $parent_id
     * @param $str
     * @param string $collection_name
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
     * @return bool
     */
    public static function Mongo_find_wh_element($str='' )
    {
        Sprwhelement::find()->where([ 'like','name', $str, true ])->one();

        if( Sprwhelement::find()->where([ 'like','name', $str, true ])->one() != null){
            return true;
        }

        return false;
    }

    /**
     * @param string $str
     * @return bool
     */
    public static function Mongo_find_wh_top($str )
    {
        Sprwhelement::find()->where([ 'like','name', $str, true ])->one();

        if( Sprwhelement::find()->where([ 'like','name', $str, true ])->one() != null){
            return true;
        }

        return false;
    }


    /**
     * START UPLOAD
     *
     * @param $id
     * @param $str
     * @param string $collection_name
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
     * @param $id
     * @param int $parent_id
     * @param $menu_name
     * @param $nomer_borta
     * @param $nomer_gos_registr
     * @param string $collection_name
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
     * @return string
     * @throws \Picqer\Barcode\Exceptions\BarcodeException
     */
    public static function Barcode_HTML ( $str_number='10101' ) {
            ////////////// PDF - Barcode
            $html='';
        $html.= '<div class="bar_code" style="position: absolute;top: 10px;right: 50px;">';

        $generator = new BarcodeGeneratorPNG();
        $html.= '<img src="data:image/png;base64,'.base64_encode(
                $generator->getBarcode($str_number, $generator::TYPE_CODE_128)).'">';

        $html.= '<div>'.$str_number;
        $html.= '</div>';
        $html.= '</div>';
        //////////////
        return $html;
    }


    /**
     * @param int $id
     * @param string $html_string
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