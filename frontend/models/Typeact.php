<?php

namespace frontend\models;


//use frontend\components\Vars;
use Yii;

/**
 * Class Typeact
 * @package app\models
 */
class Typeact extends \yii\mongodb\ActiveRecord
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
        return [Yii::$app->params['vars'], 'spr_type_act'];
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getPvMotion()
    {
        return $this->hasMany(Pvmotion::className(), [ 'type_action' => 'id'] );
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','tx','id'], 'safe'],
            //[['id'], 'integer'],

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
            'name' => 'Вид перемещения',
            'tx' => 'Сокр ВП',
        ];
    }
}
