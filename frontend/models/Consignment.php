<?php

namespace frontend\models;


use Yii;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\mongodb\ActiveRecord;


class Consignment extends ActiveRecord
{

		public $dt_one_day; // Используется в главной вьюшке как параметр одного дня
		public $dt_one_day_array; // Список дней


    /**
     * @return array|string
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'consignment',
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

            'group_id',
            'group_name',
            'element_id',
            'element_name',

            'name',
            'tx',

            'dt_create_timestamp',
            'dt_update_timestamp',

            'dt_create',
            'dt_one_day',
            'dt_one_day_array', //набор дней

            'cena',

            'array_update',
            'spr_globam.name',
            'spr_globam_element.name',
        ];
    }


    /**
     * Создаю ТаймШТАМП для всех операций Создания накладных и Редактирования
     *
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [ 'dt_create_timestamp', 'dt_update_timestamp' ],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [ 'dt_update_timestamp' ],
                ]
            ]

        ];

    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [ [     'id','group_id','element_id' ],'required'],

            [
                [
                    '_id',
                    'id',

                    'group_id',
                    'group_name',
                    'element_id',
                    'element_name',

                    'name',
                    'tx',

                    'dt_create_timestamp',
                    'dt_create',
                    'dt_one_day',
                    'dt_one_day_array',

                    'cena',

                    'array_update',
                    'spr_globam.name',
                    'spr_globam_element.name',
                ],
                'safe',
            ],

            [['dt_create'], 'date', 'format' => 'php:d.m.Y H:i:s'],

            //            [
            //                'dt_create_timestamp',
            //                'default',
            //                'value' => function () {
            //                    return strtotime('now');
            //                },
            //            ],


            [['id'], 'unique'],

            [
                [
                    'id',
                    'element_id',
                ],
                'integer',
            ],

            [['cena'], 'double',],
            [['cena'], 'default', 'value' => 0.0],


            ////////////
            [['name'], 'string', 'length' => [5, 200]],

            [['group_name'], 'string', 'length' => [5, 300]],
            [['element_name'], 'string', 'length' => [5, 300]],



            //				[
            //					[ 'name','tx' ],
            //					'match',
            //					'pattern' => '/^[а-яА-ЯЁёa-z\(\)a-zA-Z0-9_]+$/i',
            //					// 'pattern' => '/^[\(\)a-zA-Z0-9]\w*$/i',
            //					//'pattern' => '/^[0-9]{5,15}$/i',
            //					'message' => ' Недопустимые символы... ',
            //				],


        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№ ПАРТИИ',

            'group_id' => '№ Группы',
            'element_id' => 'Ид названий УСТРОЙСТВ',

            'group_name'  => 'Название группы',
            'element_name' => 'Название устройства',


            'name' => 'Наименование ПАРТИИ ',
            'tx' => 'Примечание',

            'dt_create' => 'Дата создания',
            'dt_create_timestamp' => 'Дата создания',
            'dt_update_timestamp' => 'Дата редакт.',
            'dt_one_day' => 'Один день',


            'cena'=> 'Цена',

            'array_update'=> 'Аттрибуты',
        ];
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
     * Получить максимальную Цену по данному устройству
     * =
     *
     * @param $id
     * @return int
     */
    static function max_summary_by_id($id)
    {
        return static::find()
            ->where(['element_id' => (int)$id])
            ->asArray()->max('cena');
    }

    /**
     * А теперь найти по двум признакам (ид + макс_сумма)= Полный массив
     * =
     *
     * @param $id
     * @param $max_summary
     * @return array|ActiveRecord|null
     */
    static function full_array_by_maxsummary($id, $max_summary)
    {
        return static::find()
            ->where(
                ['AND',
                    ['element_id' => (int)$id],
                    ['cena' => (double)$max_summary],
                ]
            )
            ->asArray()
            ->one();
    }


    /**
     * @return array
     */
    static function List_active_id()
    {
        return static::find()->distinct('dt_create_timestamp');
    }


    /**
     * Устройства АСУОП. Группа
     * =
     *
     * @return ActiveQueryInterface
     */
    public function getSpr_globam()
    {
        return $this->hasOne(Spr_globam::className(), ['id' => 'group_id']);
    }


    /**
     * Устройства АСУОП. Устройство
     * =
     *
     * @return ActiveQueryInterface
     */
    public function getSpr_globam_element()
    {
        return $this->hasOne(Spr_globam_element::className(), ['id' => 'element_id']);
    }


//    /**
//     * @param $id
//     *
//     * @return array|null|ActiveRecord
//     */
//    static function findModelDouble($id)
//    {
//        if ((
//            $model = static::find()
//                ->where(['id' => (int)$id])
//                ->one()
//
//            ) !== null) {
//            return $model;
//        }
//
//        return [];
//    }


    /**
     * @param $id
     *
     * @return Consignment
     * @throws ExitException
     */
    public static function findModel($id)
    {
        if (($model = static::findOne($id)) == null) {
            throw new ExitException('barcode_consignment Find(). ERROR');
        }
        else{
            return $model;
        }
    }

//    /**
//     * @param $id
//     *
//     * @return array|ActiveRecord
//     */
//    public static function getConsignment_one($id)
//    {
//
//        return static::find()->where(['=', 'id', $id])->asArray()->one();
//
//    }


}
