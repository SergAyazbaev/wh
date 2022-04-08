<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;


class Spr_things extends ActiveRecord
{

    /**
     * @return array|string
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'spr_things'];
    }


    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'name',
            'type_num', // float || int
            'tx',
            'user_id',
            'user_name',
            'dt_create',
            'dt_update'
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'unique'],
            [['id', 'user_id'], 'integer'],

            [['type_num'], 'trim'],
            //[['type_num'], 'string', 'min' => 3, 'max' => 5],
//            [['type_num'], 'string', 'length' => [3, 5]],
            [['type_num'], 'in', 'range' => ['INT', 'FLOAT']],

            [[
                'name',
                'type_num'
            ], 'required', 'message' => 'Заполнить...'],

            [[
                'id',
                'name',
                'tx',
                'type_num',
                'user_id',
                'user_name',
                'dt_update'
            ],
                'safe'],

            [['dt_create', 'dt_update'],
                'date',
                'format' => 'php:d.m.Y H:i:s'
            ],

            ['dt_create', 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }
            ],
            ['dt_update', 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }
            ],


        ];

    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№ Id',
            'name' => 'Ед.изм',
            'type_num' => 'Тип поля',
            'tx' => 'Комментарии',
            'user_id' => 'ИД автора ',
            'user_name' => 'Автор',
            'dt_create' => 'Создано',
            'dt_update' => 'Обновлено',
        ];
    }


    /**
     * Relation with Spr_glob_element
     * -
     * @return \yii\db\ActiveQueryInterface
     */
    public function getSpr_glob_element()
    {
        return $this->hasMany(Spr_glob_element::className(), ['ed_izm' => 'id']);
    }

}
