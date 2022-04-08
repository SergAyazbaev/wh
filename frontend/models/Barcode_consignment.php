<?php

namespace frontend\models;


use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\mongodb\ActiveRecord;
use Yii;


/**
 * @property int id
 * @property int element_id
 *
 * @property int dt_create_timestamp
 * @property date dt_create
 * @property date dt_update
 *
 **/
class Barcode_consignment extends ActiveRecord
{

    public $dt_one_day; // Используется в главной вьюшке как параметр одного дня


    /**
     * @return array|string
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'barcode_consignment',
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

            'name',
            'tx',

            'dt_create_timestamp',
            'dt_update_timestamp',

            'dt_create',
            'dt_update',

            'cena_input',
            'cena_formula',
            'cena_calc',
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt_update_timestamp'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dt_update_timestamp'],
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

            [[
                'id',
                'element_id',
            ], 'required'],

            [[
                '_id',
                'id',
                'element_id',
                'name',
                //						'name_consignment',
                'tx',
                'dt_create',
                'dt_update',
                'dt_create_timestamp',
                'dt_update_timestamp',

                'dt_one_day',

            ], 'safe'],

            [
                [
                    'dt_create',
                    'dt_update',
                ],
                'date',
                'format' => 'php:d.m.Y H:i:s',
            ],

            //				[
            //					'dt_create_timestamp',
            //					'default',
            //					'value' => function() {
            //						//return date( 'd.m.Y H:i:s', strtotime( 'now' ) );
            //						return strtotime( 'now' );
            //					},
            //				],


            //						return (int) strtotime( $this->dt_create );


            [[
                'id',
            ], 'unique',],

            [
                [
                    'id',
                    'element_id',
                    //						'name_consignment',
                ],
                'integer',
            ],

            [
                [
                    'cena_input',
                    'cena_formula',
                    'cena_calc',
                ],
                'double',
            ],

            [
                [
                    'cena_input',
                    'cena_formula',
                    'cena_calc',
                ],
                'default',
                'value' => 0.0,
            ],


            ////////////
            [
                ['name',],
                'string',
                'length' => [
                    5,
                    200,
                ],
            ],

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

            'id' => '№ Id ПРТИИ',
            'element_id' => 'Ид названий УСТРОЙСТВ',
            'name' => 'Наименование ПАРТИИ ',
            'tx' => 'Примечание',

            'dt_create' => 'Дата создания',
            'dt_create_timestamp' => 'Дата создания',
            'dt_one_day' => 'Дата один день ',

            'cena_input' => 'Цена входящая',
            'cena_calc' => 'Цена итоговая',
            'cena_formula' => 'Формула подсчета',

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
     * Устройства АСУОП
     * =
     *
     * @return ActiveQueryInterface
     */
    public function getSpr_globam_element()
    {
        return $this->hasOne(Spr_globam_element::className(), ['id' => 'element_id']);
    }


    /**
     * @param $id
     *
     * @return array|null|ActiveRecord
     */
    static function findModelDouble($id)
    {
        if ((
            $model = static::find()
                ->where(['id' => (int)$id])
                ->one()

            ) !== null) {
            return $model;
        }

        return [];
    }


    /**
     * @param $id
     *
     * @return Barcode_consignment|null
     * @throws ExitException
     */
    public static function findModel($id)
    {
        if (($model = static::findOne($id)) == null) {
            throw new ExitException('barcode_consignment Find(). ERROR');
        }


        return $model;
    }


    /**
     * @param $id
     *
     * @return array|ActiveRecord
     */
    public static function getConsignment_one($id)
    {

        return static::find()->where(['=', 'id', $id])->asArray()->one();

    }


}
