<?php

namespace frontend\models;


//use frontend\components\Vars;
use Yii;
use yii\mongodb\ActiveRecord;


/**
 * Class Sprrestoreelement
 * @package app\models
 */
class Sprrestoreelement extends ActiveRecord
{
    /**
     * @param $action
     * @return mixed
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'spr_restore_element'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'parent_id',
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
            //[['id', 'parent_id'], 'number'],
            [['id', 'parent_id'], 'integer'],
            [['name','tx'], 'safe']
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
            'parent_id' => 'parent_id',
            'name' => 'Наименование элемента',
            'tx' => 'Примечание',
        ];
    }
}
