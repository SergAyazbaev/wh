<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;


/**
 * Class Sprglobam
 *
 * @property int id
 * @package app\models
 */
class Spr_globam extends ActiveRecord
{
    const HAS_ONE = 1123;


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [ Yii::$app->params[ 'vars' ], 'spr_globam' ];
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
            'delete',
            'tx',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [ [ 'id', 'name', 'delete' ], 'safe', ],

            [ [ 'id', ], 'unique' ],

            [ [ 'tx' ], 'string', 'max' => 260 ],

            [ [ 'tx' ], 'trim' ],

            [ [ 'name' ], 'required', 'message' => 'Заполнить...' ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => '№ _Id',
            'id' => '№ Id',
            'name' => 'Группа (по типу установки)',
            'delete' => 'Признак Удаления',
        ];
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSpr_globam_element()
    {
        return $this->hasMany( Spr_globam_element::className(), [ 'parent_id' => 'id' ] );
    }


    /**
     * @return array
     */
//    public function relations()
//    {
//        return array(
//            'rel_group' => array(self::HAS_ONE, 'SprGroup', 'id'),
//        );
//    }

    /**
     * @return array
     */
    public static function name_am_parent()
    {
        return ArrayHelper::map( static::find()->all(), 'id', 'name' );
    }


}
