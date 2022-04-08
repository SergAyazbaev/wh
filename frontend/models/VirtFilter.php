<?php

namespace frontend\models;

use yii\mongodb\ActiveRecord;


/**
 * Class Spr_glob
 *
 * @property int id
 * @property string name
 * @property string bar_code_str
 *
 * @property array id_sender
 * @property array id_dest
 *
 * @package app\models
 */
class VirtFilter extends ActiveRecord
{
    public $id, $name;
    public $array_decision;
//    public $find_name;
    public $bar_code_str;
    public $id_sender, $id_dest, $flag_only_betveen;


    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'name',
            'id_sender',
            'id_dest',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№',
            'name' => 'name',
            'id_sender' => 'Отправитель',
            'id_dest' => 'Получатель',
            'bar_code_str' => 'Штрихкод',
            'flag_only_betveen' => 'Только между...',

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
                'name',
            ], 'safe'],

            [['id'], 'unique'],

            [['flag_only_betveen'], 'integer'],
            [['flag_only_betveen'], 'default', 'value' => 1]


        ];
    }


}
