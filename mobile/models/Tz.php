<?php

namespace mobile\models;


use Yii;

//use yii\helpers\ArrayHelper;

use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;


/**
 * Class Tz
 *
 * @package app\models
 */
class Tz extends ActiveRecord
{

    public $three;

    const STATE_NULL = 0;    // Статус не определен
    const STATE_IN_WORK = 1;    // Статус "В работе"
    const STATE_WORKED = 2;    // Статус "Выполнено.ОК."
    const STATE_TO_RETURN = 10;   // Статус "Верните на базу"
    const STATE_RETURNED = 11;   // Статус "Возвращено на базу"


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['db_name'],
//            'wh_prod',
            'tz',
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

            'street_map',

            'dt_create',
            'dt_edit',
            'dt_deadline',
            'dt_deadline1',
            'dt_deadline2',

            'dt_create_timestamp',
            'dt_deadline_timestamp',

            'user_create_group_id',
            'user_create_id',
            'user_create_name',
            'user_edit_group_id',
            'user_edit_id',
            'user_edit_name',

            'user_id',
            'user_name',

            'name_tz',
            'name_tk',
            'multi_tz',

//            'tk_top'  ,
            'id_tk',
//            'id_tz'  ,

//            'tk_element'     ,
//            'tk_element_amort',

            'wh_deb_top',
            'wh_deb_top_name',
            'wh_deb_element',

            'wh_cred_top',
            'wh_cred_top_name',
            'wh_cred_element',

            'tx',
            'ed_izmer',
            'ed_izmer_num',

            'intelligent',

            'array_bus',
            'array_tk_amort',
            'array_tk',
            'array_casual',

            // 'array_bus_gosnum',
            // 'array_bus_boardnum',

            'captcha',
            'three',

            'status_state',
            'status_create_user',
            'status_create_date',

            'status_return',
            'status_return_create_user',
            'status_return_create_date',

            'array_bus',

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [

            [['id',], 'unique'],
            [['id', 'wh_cred_top'], 'required'],

//            [ [ 'array_bus' ], 'required', 'message' => 'Выбрать...' ],

            [['street_map'], 'required', 'message' => 'Выбрать...'],
            [['name_tz'], 'required', 'message' => 'Заполнить...'],
            [['name_tz'], 'string', 'max' => 300],

            [['id', 'multi_tz'], 'integer'],

            [['multi_tz'], 'integer', 'min' => 1, 'message' => 'Заполнить...'],
            [['multi_tz'], 'default', 'value' => 1],

            ['user_edit_group_id', 'default', 'value' => function () {
                return Yii::$app->getUser()->identity->group_id;
            }],
            ['user_edit_id', 'default', 'value' => function () {
                return (integer)Yii::$app->user->identity->id;
            }],
            ['user_edit_name', 'default', 'value' => function () {
                return Yii::$app->user->identity->username;
            }],


            [[
                'street_map',

                'dt_create',
                'dt_edit',
                'dt_deadline',
                'dt_deadline1',
                'dt_deadline2',

                'user_create_group_id',
                'user_create_id',
                'user_create_name',

                'user_edit_group_id',
                'user_edit_id',
                'user_edit_name',

                'user_id',
                'user_name',

                'name_tz',
                'name_tk',
                'multi_tz',

                'tk_top',
                'id_tk',
                'id_tz',

                'wh_deb_top',
                'wh_deb_top_name',
                'wh_deb_element',
                'wh_cred_top',
                'wh_cred_top_name',
                'wh_cred_element',

                'tx',
                'ed_izmer',
                'ed_izmer_num',

                'intelligent',

//                'array_bus',
//                'array_bus2',

                'array_tk_amort',
                'array_tk',
                'array_casual',

                'captcha',
                'three', // тройная кнопка


                // статус "В РАБОТЕ"
                // кто этот статус поставил
                // когда был принят статус

                'status_state',
                'status_create_user',
                'status_create_date',

                'status_return',
                'status_return_create_user',
                'status_return_create_date',

                'array_bus',

            ], 'safe'],


            [
                ['array_bus'],
                function ($attribute, $params) {

//                    [ [ 'array_bus' ], 'required', 'message' => 'Выбрать...' ],

                    if (!is_array($this['array_bus'])) {
                        $this->addError(
                            $attribute, 'Выбрать...'
                        );
                    }

                    if (is_array($this['array_bus']) && empty($this['array_bus'])) {
                        $this->addError(
                            $attribute, 'Мало автобусов'
                        );
                    }
                    return $this['array_bus'];
                },
            ],


        ];

    }


    /**
     * MODEL
     *
     * @param $id
     * @return array|null|ActiveRecord
     * @throws NotFoundHttpException
     */

    static function findModelDouble($id)
    {
        if (($model = static::find()
                ->where(['id' => (integer)$id])
                //->asArray()
                ->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('findModelDouble. Этого нет БУФЕРЕ ПЕРЕДАЧИ ');
    }

    /**
     * AsArray
     *
     * @param $id
     * @return array|null|ActiveRecord
     */
    static function findModelDoubleAsArray($id)
    {
        return static::find()
            ->where(['id' => (int)$id])
            ->asArray()
            ->one();
    }


    /**
     * setNext_max_id()
     * =
     * Вычисляем следующий новый ИД
     *
     * @return int
     */
    public static function setNext_max_id()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;
    }


    /**
     * MOBILE/Satart-List. Получить начальный список всех ТехЗаданий
     *=
     * @return array|null|ActiveRecord
     */
    static function find_array_all_list()
    {
        return static::find()
            ->select([
                'id',
                'name_tz',
                'dt_create',
                'dt_deadline',
                'wh_cred_top_name',
                'user_edit_name',
            ])
            ->orderBy('dt_create_timestamp')// dt_deadline_timestamp
            ->asArray()
            ->all();
    }

    /**
     * 2/
     * MOBILE/Satart-List. Получить начальный список всех ТехЗаданий
     *=
     *
     */
    static function find_list()
    {
        return ArrayHelper::map(
            static::find()
                ->select([
                    'id',
                    'name_tz',
                ])
                ->orderBy('id')// dt_deadline_timestamp
                ->asArray()
                ->all(), 'id', 'name_tz');

    }


    /**
     * 3/
     * MOBILE/Satart-List. Получить начальный список всех ТехЗаданий
     *=
     * @param $id
     * @return array|ActiveRecord|null
     */
    static function find_one($id)
    {
        return
            static::find()
                ->where(
                    ['OR',
                        ['id' => $id],
                        ['id' => (int)$id],
                    ]

                )
                ->asArray()
                ->one();

    }


}
