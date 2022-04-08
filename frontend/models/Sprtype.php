<?php

namespace frontend\models;


//use frontend\components\Vars;
//use Yii;

/**
 * Class Sprtype
 * @package app\models
 */
class Sprtype extends \yii\mongodb\ActiveRecord
{
    /**
     * @return array
     */
    public static function getTypeIdName()
    {
        return[
            3=>0,
            1=>'sdfgsdfsdf',
            2=>'sdfsdfsdf asdfa sdf'
            ];
    }

    /**
     * @param $action
     * @return mixed
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }


//    /**
//     * {@inheritdoc}
//     */
//    public static function collectionName()
//    {
//        return [Yii::$app->params['vars'], 'spr_type'];
//    }

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
            [['id', 'name'], 'safe'],
            [['id'], 'unique']
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
