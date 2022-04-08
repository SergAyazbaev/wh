<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for collection "Installset".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $id
 * @property mixed $install_group
 * @property mixed $ids_pv
 * @property mixed $name
 */
class Installset extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['wh', 'Installset'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'install_group',
            'ids_pv',
            'name',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'install_group', 'ids_pv', 'name'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => 'Id',
            'install_group' => 'Install Group',
            'ids_pv' => 'Ids pv',
            'name' => 'Name',
        ];
    }
}
