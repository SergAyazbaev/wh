<?php

namespace frontend\models;

use console\components\MyHelpers;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use Yii;
use yii\web\NotFoundHttpException;


/**
 * Class Sprglobamelement
 *
 * @package app\models
 */
class Spr_globam_element extends ActiveRecord
{

    /**
     * @return array|string
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'spr_globam_element']; ///sprglobam_element
        //mongoimport --db wh_prod  --collection spr_globam_element --type json --file sprglobam_element.json
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'cc_id',

            'parent_id',

            'intelligent',
            'name',
            'short_name',

            'tx',
            'user_id',
            'user_ip',
            'user_name',
            'dt_update',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№ Id',
            'cc_id' => '1С_Id',
            'parent_id' => 'parent_id',
            'intelligent' => 'Устройство имеет Штрих-код',
            'name' => 'Наименование устройства АСУОП',
            'short_name' => 'АСУОП',
            'tx' => 'Примечание',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'cc_id',
                'parent_id',
                'name',
                'short_name',
                'intelligent',
                'tx',
            ], 'safe'],

            [['parent_id', 'intelligent'], 'required', 'message' => 'Надо выбрать...'],

            [['name'], 'required', 'message' => 'Надо заполнить...'],


            ////........
            [['dt_update'], 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }],

            [['user_ip'], 'default', 'value' => function () {
                return Yii::$app->request->getUserIP();
            }],

            [
                'user_id',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->id;
                },
            ],
            [
                'user_name',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->username;
                },
            ],

            [['cc_id'], 'string'], //!!!

            [['id', 'parent_id'], 'integer'],

        ];
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSpr_globam()
    {
        return $this->hasOne(Spr_globam::className(), ['id' => 'parent_id']);
    }

    /**
     * Возвращает массив (ИД + Текст)
     *=
     * @return array|ActiveRecord
     */
    public static function name_plus_id()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'name');
    }

    /**
     * Возвращает массив (ИД + Текст)
     *=
     * @return array|ActiveRecord
     */
    public static function name_plus_id_tx()
    {
        $array = ArrayHelper::map(static::find()->all(), 'id', 'tx');
        return array_filter($array);
    }

    /**
     * Возвращает массив (ИД)
     *=
     * @return array|ActiveRecord
     */
    public static function array_ids()
    {
        return ArrayHelper::getColumn(static::find()->orderBy('id')->all(), 'id');
    }

    /**
     * Возвращает массив СТРОК (ИД)
     *=
     * @return array|ActiveRecord
     */
    public static function array_string_ids()
    {
        $xx = ArrayHelper::getColumn(static::find()->orderBy('id')->all(), 'id');
        foreach ($xx as $key => $item) {
            $xx[$key] = (string)$item;
        }
        return $xx;
    }

    /**
     * Возвращает массив (ИД + parent_id)/ Соответствие Ид парентуИД
     * -
     *
     * @return array
     */
    public static function id_to_parent()
    {
        return ArrayHelper::map(static::find()->orderBy('id')->all(), (string) 'id',(string) 'parent_id');
    }

    /**
     * Возвращает массив (ИД + intelligent)
     * -
     *
     * @return array
     */
    public static function id_to_intelligent()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'intelligent');
    }

    /**
     * Приводим Позиции Массива АМ и расшифровываем по признаку ИНТЕЛЕГЕНТ (Имеет ли Штрихкод)
     * =
     *
     * @param $array_am
     *
     * @return mixed
     */
    public static function array_am_to_intelligent($array_am)
    {
        /// Получаем массив ИД+Ителегент
        $spr_glob_intelligent = self::id_to_intelligent();

        $array = [];
        $x_key = 0;
        if (isset($array_am) && is_array($array_am)) {
            foreach ($array_am as $key => $item) {

                //ddd($item);
                if (isset($item['intelligent'])) {
                    $xx = (int)($item['intelligent'] == 0 ? 1 : (int)$item['ed_izmer_num']);
                } else {
                    $xx = (int)1;
                }


                do {
                    $array[$x_key] = $item;
                    $array[$x_key]['ed_izmer_num'] = (int)$xx;
                    $array[$x_key]['intelligent'] = (int)$spr_glob_intelligent[$item['wh_tk_element']];
                    $array[$x_key]['bar_code'] = $item['bar_code'];

                    // Оставить только ОДИН Штрихкод, при ИХ повторении
                    if ((int)$xx != 1) {
                        $array[$x_key]['bar_code'] = '';
                    }
                    $xx--;
                    $x_key++;
                } while ($xx > 0);
            }
        }

        return $array;
    }

    /**
     * ПОлучить Парент ИД через ИД элемента
     * =
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getParent_id($id)
    {
        return ArrayHelper::getValue(static::find()
            ->where(['id' => (int)$id])
            ->one(), 'parent_id');
    }

    /**
     * Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его ИД)
     * =
     * Конечного Склада и его Компании
     *-
     *
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function findFullArray($id)
    {

        if (!isset($id)) {
            throw new NotFoundHttpException('Spr_globam_element -> findFullArray. Не указан ИД элемента');
        }

        $poisk_cild = self::find()
            ->where(
                [
                    '=',
                    'id',
                    (int)$id,
                ])
            ->one();

        //        ddd($poisk_cild);

        $poisk_parent = Spr_globam::find()
            ->where(
                [
                    '=',
                    'id',
                    (int)$poisk_cild['parent_id'],
                ])
            ->one();

        if (isset($poisk_parent) && !empty($poisk_parent['id'])) {
            $array_request['top']['id'] = $poisk_parent['id'];
            $array_request['top']['name'] = $poisk_parent['name'];
        }

        if (isset($poisk_cild) && !empty($poisk_cild['id'])) {
            $array_request['child']['id'] = $poisk_cild['id'];
            $array_request['child']['name'] = $poisk_cild['name'];
            $array_request['child']['short_name'] = $poisk_cild['short_name'];
            $array_request['child']['intelligent'] = $poisk_cild['intelligent'];

        }

        return $array_request;
    }

    /**
     * @param string $name1
     * @param string $name2
     * @param string $name3
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function findFullArray_by_names3($name1 = '999999', $name2 = '999999', $name3 = '999999')
    {

        if (!isset($name1) || !isset($name2) || !isset($name3)) {
            throw new NotFoundHttpException('Spr_globam_element -> findFullArray_by_names3. Не указан фрагменты текста');
        }

        $poisk_cild = self::find()
            ->where(
                ['OR',
                    ['like', 'name', $name1],
                    ['like', 'name', $name2],
                    ['like', 'name', $name3],
                ]
            )
            ->one();

        //ddd($poisk_cild);

        $poisk_parent = Spr_globam::find()
            ->where(
                [
                    '=',
                    'id',
                    (int)$poisk_cild['parent_id'],
                ])
            ->one();

        if (isset($poisk_parent) && !empty($poisk_parent['id'])) {
            $array_request['top']['id'] = $poisk_parent['id'];
            $array_request['top']['name'] = $poisk_parent['name'];
        }

        if (isset($poisk_cild) && !empty($poisk_cild['id'])) {
            $array_request['child']['id'] = $poisk_cild['id'];
            $array_request['child']['name'] = $poisk_cild['name'];
            $array_request['child']['intelligent'] = $poisk_cild['intelligent'];

        }

        return $array_request;
    }

    /**
     * @param string $name1
     * @param string $name2
     * @param string $name3
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function findArray_by_names_and_goup($name1 = '999999')
    {

        if ( !isset($name1) ) {
            throw new NotFoundHttpException('Spr_globam_element -> findFullArray_by_names3. Не указан текст');
        }

        // spr_globam_element  ===  "intelligent" : "1",
        $poisk_cild = self::find()
            ->where(
              ['AND',
                    ['like', 'name', $name1],
                    ['<>','intelligent', '1']  //ПРОВЕРКА  не должен иметь штрих=код
                  ]
            )
            ->one();

if (empty($poisk_cild)){
  return[];
}

        //
        $poisk_parent = Spr_globam::find()
            ->where(
                [
                    '=',
                    'id',
                    (int)$poisk_cild['parent_id'],
                ])
            ->one();

        if (isset($poisk_parent) && !empty($poisk_parent['id'])) {
            $array_request['top']['id'] = $poisk_parent['id'];
            $array_request['top']['name'] = $poisk_parent['name'];
        }

        if (isset($poisk_cild) && !empty($poisk_cild['id'])) {
            $array_request['child']['id'] = $poisk_cild['id'];
            $array_request['child']['name'] = $poisk_cild['name'];
            $array_request['child']['intelligent'] = $poisk_cild['intelligent'];

        }

        return $array_request;
    }

    /**
     * findFullArray_by_name
     * Полное имя.
     * =
     * @param string $name1
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function findFullArray_by_name($name1 = '999999')
    {

        if (!isset($name1)) {
            throw new NotFoundHttpException('Spr_globam_element -> findFullArray_by_names. Не указан текст');
        }

        $poisk_cild = self::find()
            ->where(['like', 'name', $name1])
            ->one();

        //ddd($poisk_cild);

        $poisk_parent = Spr_globam::find()
            ->where(
                [
                    '=',
                    'id',
                    (int)$poisk_cild['parent_id'],
                ])
            ->one();

        if (isset($poisk_parent) && !empty($poisk_parent['id'])) {
            $array_request['top']['id'] = $poisk_parent['id'];
            $array_request['top']['name'] = $poisk_parent['name'];
        }

        if (isset($poisk_cild) && !empty($poisk_cild['id'])) {
            $array_request['child']['id'] = $poisk_cild['id'];
            $array_request['child']['name'] = $poisk_cild['name'];
            $array_request['child']['intelligent'] = $poisk_cild['intelligent'];

        }

        return $array_request;
    }

    /**
     * Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его ИД)
     * =
     * Конечного Склада и его Компании
     *-
     *
     * @param $barcode
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function findFullArray_BY_barcode($barcode)
    {

        if (!isset($barcode)) {
            //   throw new NotFoundHttpException('Spr_globam_element. Не указан BARCODE ');
            return null;
        }

        // * GLOBAL. Приводит Все ШТРИХКОДЫ к нормальному ВИДУ
        $barcode = MyHelpers::barcode_normalise($barcode);


        // Этап
        // 1      Ищем по Баркоду == Находим его ИД
        $poisk_id = Barcode_pool::getId_from_Barcode($barcode);

        if (empty($poisk_id)) {
            //throw new NotFoundHttpException('Spr_globam_element. findFullArray_BY_barcode. Не найден BARCODE ');
            return null;
        }


        //2  * Возвращает массив с ПОЛНЫМИ ДАННЫМИ (на входе его ИД)
        return self::findFullArray($poisk_id);
    }

    /**
     * Получить список по parent_id
     * =
     * @param $parent_id
     * @return array
     */
    static function List_elements($parent_id)
    {
        return [0 => 'Выбрать все'] + ArrayHelper::map(
                static::find()
                    ->select(['id', 'name'])
                    ->orderBy('name')
                    ->asArray()
                    ->where(['parent_id' => $parent_id])
                    ->all(),
                'id', 'name');
    }

}
