<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;


/**
 * Class Spr_glob
 * @property float id
 * @package app\models
 */
class Spr_glob extends ActiveRecord
{
//    private $id;

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [ Yii::$app->params['vars'], 'spr_glob' ];
        //        return [Yii::$app->params['vars'], 'spr_group_type'];
    }


    public function getSpr_glob_element()
    {
        return $this->hasMany(Spr_glob_element::className(), ['parent_id' => 'id']);
    }


    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'name',
            'tx',
            'user_id',
            'user_name',
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
                'tx',
                'user_id',
                'user_name',
                ],
                'safe'],

            [['id'], 'unique'],

            [[
                'id',
                'user_id'
            ],
                'integer'],

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
            'name'  => 'Название рекомендованной группы',
            'tx'    => 'Комментарии',
            'user_id'=>'',
            'user_name'=>'',
        ];
    }


}
