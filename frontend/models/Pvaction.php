<?php

namespace frontend\models;


//use frontend\components\Vars;
//use MongoDB\BSON\ObjectID;
use Yii;


/**
 * Class Pvaction
 * @package app\models
 */
class Pvaction extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'pvaction'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'author',
            'content',
            'comments',
            'dt_create',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'author', 'content', 'comments', 'dt_create'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'name' => 'Name',
            'author' => 'Author',
            'content' => 'Content',
            'comments' => 'Comments',
            'dt_create' => 'Dt Create',
        ];
    }
}
