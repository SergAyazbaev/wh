<?php

namespace frontend\models;


use Yii;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;

/**
 * @property int id
 * @property int element_id
 * @property int barcode_consignment_id
 * @property string bar_code
 *
 * @property int dt_create_timestamp
 * @property date dt_create
 * @property date dt_update
 *
 **/
class Barcode_pool extends ActiveRecord
{

    public $parent_name;
    public $name;
    public $find_name;      //Поисковая фраза (во вьюшке)
    public $find_array;     //Поиск по списку номеров


    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'barcode_pool',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',

            'id',
            'element_id',
            'bar_code',
            'barcode_consignment_id',

            'write_off',
            'write_off_doc',
            'write_off_note',

            'turnover',
            'turnover_alltime',

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // required
            [[
                'id',
                'element_id',
                'bar_code',
            ], 'required'],

            [[
                'id',
                'element_id',
                'bar_code',
                'barcode_consignment_id',

                'write_off',
                'write_off_doc',
                'write_off_note',
            ],
                'safe'],


            [[
                'id',
                'bar_code',
            ], 'unique'],

            [[
                'id',
                'element_id',
                'barcode_consignment_id',
                'turnover',
                'turnover_alltime',
            ], 'integer'],

            ////////////

            [['bar_code'], 'string', 'length' => [5, 15]],

            [
                ['bar_code',],
                'match',
                //'pattern' => '/^[a-z]\w*$/i',
                //'pattern' => '/^[0-9]{5,15}$/i',

                //
                // 1.Российские Коммутаторы NIS-3200. У них есть буква "А" шестая справа.
                //
                // 2.Китяйские Коммутаторы 5,8,16. У них будет НАМИ ДОПИСАН признак -5, -8 -16.

                //                 'pattern' => '/^[0-9]{5,10}[A-Z]?[0-9]{1,5}$/i',
                //                'pattern' => '/^[0-9]{5,10}[A-Z]?[0-9]{1,5}-?[\5\8\16]{,3}$/i',

                'pattern' => '/^[0-9]{5,10}[A-Z]?[0-9]{1,5}(-5)?(-8)?(-16)?$/i',
                'message' => ' спаравочник bar_code -- pattern   ... ERROR!',
            ],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№ Id',
            'element_id' => 'element_id',
            'bar_code' => 'Штрих-код устройства',
            'barcode_consignment_id' => 'Ид Партии',

            'write_off' => 'Списано',
            'write_off_doc' => 'На основании документа',
            'write_off_note' => 'Причина списания',

            'turnover' => 'Возвраты',
            'turnover_alltime' => 'Возвраты за все время',

        ];
    }

    /**
     * StringName
     */
    public function getStringName()
    {
        return $this->bar_code . ' ';
    }


    /**
     * @return int
     */
    public static function setNext_max_id()
    {
        $xx = static::find()->asArray()->max('id');

        return ++$xx;
    }

    /**
     * @param $id
     *
     * @return Barcode_pool|null
     * @throws ExitException
     */
    static function findModel($id)
    {
        if (($model = static::findOne($id)) !== null) {
            if (is_object($model)) {
                return $model;
            }
        }

        throw new ExitException('Sklad 1 -   11110000 с. Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад');
    }

    /**
     * Характеристики.
     * -
     * Находим описание ИЗДЕЛИЯ в справочнике
     *=
     * @param $bar_code
     * @return array|ActiveRecord|null
     */
    static function findFull_array($bar_code)
    {
        return static::find()
            ->with('spr_globam_element')
            ->where(['==', 'bar_code', (string)$bar_code])
            ->asArray()
            ->one();
    }

    /**
     *  Ищем по ИД
     * -
     * Находим barcode_consignment_id
     * =
     *
     * @param $bar_code
     *
     * @return array|ActiveRecord
     */
    static function getBarcode_consignment_id($bar_code)
    {
        $xx = $model = static::find()
            ->where(['bar_code' => $bar_code])
            ->asArray()
            ->one();
        //ddd($xx);
        return $xx['barcode_consignment_id'];
    }

    /**
     * ???????????????????????????????
     * Ищем по Баркоду
     *=
     * Находим его ИД
     *=
     * @param $bar_code
     *
     * @return array|ActiveRecord
     */
    static function getId_from_Barcode($bar_code)
    {
        $xx = $model = static::find()
            ->where(['bar_code' => $bar_code])
            ->asArray()
            ->one();
        if (isset($xx['element_id'])) {
            return $xx['element_id'];
        }

        return null;
    }

    /**
     * Ищем по Баркоду
     *=
     * Находим его ИД
     *=
     * @param $bar_code
     *
     * @return array|ActiveRecord
     */
    static function getId_Barcode($bar_code)
    {
        $bar_code = trim($bar_code);

        if (strlen($bar_code) >= 11 || strlen($bar_code) == 5) {
            $model = static::find()
                ->where(['like', 'bar_code', (string)$bar_code])
                ->asArray()
                ->one();
        } else {
            $model = static::find()
                ->where(['=', 'bar_code', (string)$bar_code])
                ->asArray()
                ->one();
        }

        if (isset($model['id'])) {
            //ddd($bar_code);
            return $model['id'];
        }
        return [];

    }


    /**
     * Перезапись строки-ПОЗИЦИИ
     *=
     *
     * @param $id
     * @param $write_off_doc
     * @param $write_off_note
     * @return array|int
     */
    //static function updatePosition($id, $write_off, $write_off_doc, $write_off_note)
    static function updatePosition($id, $write_off_doc, $write_off_note)
    {
        $model = static::find()->where(['==', 'id', $id])->one();
        if (!isset($model)) {
            return [];
        }

        $model->write_off = (int)1;
        $model->write_off_doc = $write_off_doc;
        $model->write_off_note = $write_off_note;

        if (!$model->save(true)) {
            return -1;
        }
        return 1;
    }

    /**
     * Находим PARENT_ID через ID
     * -
     *
     * @param $id
     *
     * @return array|mixed
     */
    static function find_parent_id($id)
    {
        if (($model = static::find()
                ->where(['id' => (integer)$id])->one()) !== null) {
            return $model['parent_id'];
        }

        return [];
    }

    /**
     * Получить МАССИВ для проверки в ПУЛе ШТРИХКОДОВ
     *=
     *
     * @param $bar_code
     *
     * @return array|ActiveRecord|null
     */
    public static function getAray_number_pool($bar_code)
    {
        return Barcode_pool::find()
            ->where(
                [
                    '=',
                    'bar_code',
                    $bar_code,
                ])
            ->asArray()
            ->one();
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSpr_globam_element()
    {
        return $this->hasOne(Spr_globam_element::className(), ['id' => 'element_id']);
    }

    /**
     * Связь со списком партий.
     * =
     *
     * @return ActiveQueryInterface
     */
    public function getBarcode_consignment()
    {
        return $this->hasOne(Barcode_consignment::className(), ['id' => 'barcode_consignment_id']);
    }

    /**
     */
    public function getBarcode_consignment2()
    {
        return $this->hasOne(Barcode_consignment::className(), ['barcode_consignment_id' => 'id']);
    }

    /**
     * // Поиск автопоиск
     * */
    public static function Array_for_auttofinder()
    {
        // Поиск автопоиск
        return ArrayHelper::getColumn(static::find()
            ->select(['bar_code'])
            ->asArray()
            ->all(),
            'bar_code');
    }


}
