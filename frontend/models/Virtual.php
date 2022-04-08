<?php
namespace frontend\models;

use yii\mongodb\ActiveRecord;


/**
 * @property integer $id
 * @property integer $tn
 * @property integer $ticket_state_id
 * @property integer dt_create_timestamp
 * @property date dt_create
 *
 */
class Virtual extends ActiveRecord
{

    const SCENARIO_NEW_CREATE = 'new_create';


    /**
     * {@inheritdoc}
     */
//    public static function collectionName()
//    {
//        return [
//            Yii::$app->params['vars'],
//            'sklad',
//        ];
//
//    }


    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios[self::SCENARIO_NEW_CREATE] = [
            'id',
            'dt_create',
            'dt_create_timestamp',
        ];
        $scenarios[self::SCENARIO_DEFAULT] = [
            '_id',
            'id',
            'dt_create',
            'dt_create_timestamp',

        ];

        return $scenarios;
    }


    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'dt_create',
            'dt_create_timestamp',
        ];

    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            '_id' => '_ID',
            'id' => '№',
            'dt_create' => 'На начало дня',
            'dt_create_timestamp' => 'На начало дня',
        ];

    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['id',], 'unique'],

            [
                [
                    'dt_create',
                ],
                'date',
                'format' => 'php:d.m.Y H:i:s',
            ],
            [
                'dt_create',
                'default',
                'value' => function () {
                    return date('d.m.Y H:i:s', strtotime('now'));
                },
            ],


            ///SAFE
            [
                [
                    'id',
                    "dt_create",
                    "dt_create_timestamp",
                ],
                'safe', 'on' => self::SCENARIO_DEFAULT
            ],


            /// ЕСЛИ ЕЩЕ НЕ ЗАПОЛНИЛИСЬ !!!!
            [
                [
                    'dt_create',
                ],
                'required',
                'message' => 'Заполнить...',
            ],


        ];
    }


}