<?php

namespace frontend\models;

//use frontend\components\Vars;
use Yii;


/**
 * Class Sprsklad
 * @package app\models
 */
class Sprsklad extends \yii\mongodb\ActiveRecord
{
    /**
     * @param $action
     * @return mixed
     */
    public function beforeAction($action)
    {
       // dd($action);
        return parent::beforeAction($action);
    }


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        //return ['wh', 'spr_sklad'];
        return [Yii::$app->params['vars'], 'sprwh_top'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
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
            'name' => 'Наименование (устройства, прибора, компонента)',
        ];
    }
}
