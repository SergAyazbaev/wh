<?php

namespace frontend\models;

use Yii;
//use frontend\components\Vars;


/**
 * Class Sprvid
 * @package app\models
 */
class Sprvid extends \yii\mongodb\ActiveRecord
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
        return [Yii::$app->params['vars'], 'spr_vid_rab'];
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
            [['id', 'name'], 'safe']
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
            'name' => 'Вид работы (классификация)',
        ];
    }
}
