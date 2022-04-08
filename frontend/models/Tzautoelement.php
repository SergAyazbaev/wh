<?php

namespace frontend\models;

use yii\mongodb\ActiveRecord;
use Yii;

/**
 * Class Tzautoelement
 * @package app\models
 */
class Tzautoelement extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'tz_auto_element'];
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getSprwhtop()
    {
        return $this->hasOne(Sprwhtop::className(), ['id'=>'tz_id']);
    }


    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'tz_id',
            'id',
            'parent_id',
            'name',
            'nomer_borta',
            'nomer_gos_registr',
            'nomer_vin',
            'tx',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[  'id', ],
                'required'],

            [[  'parent_id',],
                'required','message' => 'Надо выбрать'],


            //[['id', 'parent_id'], 'number'],
            [[
                'tz_id',
                'id',
                'parent_id'
            ],
                'integer', 'message' => 'Must be DOUBLE!'],

            [[
                'name',
                'tx'
            ],
                'filter', 'filter' => 'trim'],

            [[
                'nomer_borta',
                'nomer_gos_registr',
                'nomer_vin'
            ],
                'filter', 'filter' => 'trim'],

            [[
                'nomer_borta',
                'nomer_gos_registr',
                'nomer_vin'
            ],
                'unique'],


            [[
                'tz_id',
                'name',
                'nomer_borta',
                'nomer_gos_registr',
                'tx',
                'nomer_vin',
                ],
                'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'tz_id' => 'TZ_Id',
            'id' => '№ Id',
            'parent_id' => 'parent_id',
            'name' => 'Наименование склада',
            'nomer_borta'=> 'Борт №',
            'nomer_gos_registr'=> 'Гос.рег.номер',
            'nomer_vin'=> 'VIN',

            'tx' => 'Примечание',
        ];
    }
}
