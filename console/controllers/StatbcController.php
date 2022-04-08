<?php

namespace console\controllers;


use frontend\models\Barcode_pool;
use frontend\models\Sklad;
use yii\helpers\ArrayHelper;



/**
 * -----Every Evening
 *
 * OLD = Stat_balans_cs_Controller
 */
class StatbcController extends Stat_balans_cs_Controller
{


    /**
     * 1. Start Function. CALC FAST - TURNOFER
     *
     *  Пересчет ОБОРАЧИВАЕМОСТИ АСУОП НА РЕМОНТ
     *=
     */
    public function actionCalc_turnover()
    {
        // $barcode_pool = ArrayHelper::getColumn(
        //     Barcode_pool::find()
        //         //->limit(500)
        //         ->asArray()
        //         ->all(), 'bar_code');



  //ОТЛОЖЕННАЯ загрузка частями
  foreach (Barcode_pool::find()->each() as $barcode) {

              // $barcode = '19600012089';   // '19600012132'; //19600012089

            //За период в 1 месяц.
            //Запись результата в справочник по полю Turnover
            $count_one = self::TurnoverCount_by_barcode($barcode['bar_code']);

            //За период всю его жизнь.
            //Запись результата в справочник по полю Turnover
            $count_alltime = self::TurnoverCount_by_barcode_alltime($barcode['bar_code']);

            // Пишем все это в базу
            self::TurnoverWrite_poolbarcode($barcode['bar_code'], $count_one, $count_alltime);

                    // print_r($count_one);
                    // if ($count_one > 1){
                    //   die($barcode['bar_code'] . '  -- '. $count_one);
                    // }



        }

        ///
        //self::dddd($array_result);

        echo "ALL is OK.... Расчет окончен. Ок.";
        return 0;
    }

    /**
     * Запрос ао одному БАР-КОДУ за ОДИН месяц
     * =
     * @param $bar_code
     * @return int
     * @throws \yii\mongodb\Exception
     */
    static function TurnoverCount_by_barcode($bar_code)
    {
        $date_start = strtotime('now -1 month');

        $count = Sklad::find()
            ->select([
                'id', 'array_tk_amort', "dt_create", "dt_create_timestamp"
            ])
            ->where(['AND',
             // "wh_home_number" : 7188,
             // ['==', 'wh_debet_top', 7],   // Guidejet TI. Ремонтный отдел. Подотчет
             ['==', 'wh_home_number', 7188],   // Ремонтный отдел.
             ['==', 'sklad_vid_oper', '2'],

                ['>=', 'dt_create_timestamp', $date_start],
                ['==', 'array_tk_amort.bar_code', $bar_code],
            ])
            ->count();

        //self::dddd($cursor);

        return $count;
    }

 /**
     * Запрос ао одному БАР-КОДУ за ВСЮ ЖИЗНЬ
     * =
     * @param $bar_code
     * @return int
     * @throws \yii\mongodb\Exception
     */
    static function TurnoverCount_by_barcode_alltime($bar_code)
    {

        $count = Sklad::find()
            ->select([
                'id', 'array_tk_amort', "dt_create", "dt_create_timestamp"
            ])
            ->where(['AND',
                 ['==', 'wh_home_number', 7188],   // Ремонтный отдел.
                // ['==', 'wh_debet_top', 7],
                ['==', 'sklad_vid_oper', '2'],
                ['==', 'array_tk_amort.bar_code', $bar_code],
            ])
            ->count();

        //self::dddd($cursor);

        return $count;
    }


    /**
     * Запись результата в справочник по полю Turnover
     * =
     * @param $bar_code
     * @param $xx_onemont
     * @param $xx_alltime
     * @return int
     */
    static protected function TurnoverWrite_poolbarcode($bar_code, $xx_onemont=0,  $xx_alltime=0)
    {
        $pool = Barcode_pool::find()
            ->where(['==', 'bar_code', $bar_code])
            ->one();

        $pool->turnover = $xx_onemont;
        $pool->turnover_alltime = $xx_alltime;


        if (!$pool->save(true)) {
          return 1;
            // self::dddd($cursor);
        }

        return 0;
    }




    ///
    /// dd d d d
    ///
    static function dddd($var = null)
    {
        echo '<br>';
        echo '<br>';
        echo '<b>ddd()</b>';

        if (!isset($var)) {
            echo '<b>ddd()</b> - empty';
            echo '';
            echo '';
            die();
        }

        if (is_array($var)) {
            echo 'ddd(is_array()) - massiv <br>';
        }
        if (is_object($var)) {
            echo 'ddd(is_object()) - object <br>';
        }
        if (is_bool($var)) {
            echo 'ddd(is_bool()) <br>';
        }

        echo "<pre>";
        print_r($var);
        echo "<pre>";
        die();
    }


}
