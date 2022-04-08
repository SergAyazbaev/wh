<?php

namespace frontend\models;


//use frontend\components\Vars;
use Yii;


/**
 * Class Sprrestore
 * @package app\models
 */
class Sprrestore extends \yii\mongodb\ActiveRecord
{
    /**
     * @param $action
     * @return mixed
     */
    public function beforeAction($action)
    {
//        dd(parent::collectionName());
        return parent::beforeAction($action);
    }


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'spr_restore'];
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['id'], 'unique'],
            [['name'], 'safe']
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
            'name' => 'Выполненная работа',
        ];
    }
}
