<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\mongodb\ActiveRecord;


/**
 * @property int id
 * @property int home_id
 * @property int gr
 * @property int el
 * @property int s
 *
 * @property int nnn_id
 *
 * @property int bc
 * @property int t
 * @property int t_cr
 * @property int t_in
 * @property int t_out
 *
 */
class Reflection extends ActiveRecord
{
    const SCENARIO_NEW_CREATE = 'new_create';


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'reflection'];
    }


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
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',

            'home_id',

            'nnn_id',
            'bc',

            'gr',
            'el',
            's',

            't',
            't_cr',
            't_in',
            't_out',
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

            'home_id' => 'Склад',

            'nnn_id' => '№ накладной',


            'gr' => 'Гр.',
            'el' => 'Эл.',
            'bc' => 'Штрихкод',
            's' => 'количество в движении',

            't' => 'День',              // Округленный
            't_cr' => 'Создано.',       // Дата и время'
            't_in' => 'Принято.',       // Дата и время'
            't_out' => 'Передано.',     // Дата и время'

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                '_id',
                'id',
                'home_id',

                'nnn_id',
                'bc',

                'gr',
                'el',
                's',

                't',
                't_cr',
                't_in',
                't_out',

            ], 'safe'],

            [['id'], 'unique'],

            [['bc',], 'string'],

            [['id', 'home_id', 'gr', 'el', 's', 't', 't_cr', 't_in', 't_out', 'nnn_id',], 'integer'],

        ];
    }


    /**
     * @return ActiveQueryInterface
     */
    public function getSpr_globam()
    {
        return $this->hasOne(Spr_globam::className(), ['id' => 'gr']);
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSpr_globam_element()
    {
        return $this->hasOne(Spr_globam_element::className(), ['id' => 'el']);
    }


    /**
     * setNext_max_id()
     * =
     * Вычисляем следующий новый ИД
     *
     * @return int
     */
    public static function setNextMaxId()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;
    }


}