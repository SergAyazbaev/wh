<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;


class Spr_pd extends ActiveRecord
{

    public static function collectionName()
    {
        return [ Yii::$app->params['vars'], 'spr_pd' ];
    }


    public function getSpr_glob_element()
    {
        return $this->hasMany(Spr_glob_element::className(), ['pd_id' => 'id']);
    }


    public function attributes()
    {
        return [
            '_id',
            'id',
            'name',
            'tx',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'unique' ],
            [['id'], 'integer'],

            [[
                'id',
                'name',
                'tx',
                ],
                'safe'],
        ];

    }


    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№ Id',
            'name'  => 'Подгуппа товаров',
            'tx'    => 'Комментарии',
        ];
    }

}
