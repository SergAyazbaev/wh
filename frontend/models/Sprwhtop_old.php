<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;


/**
 * В н и м а н и е !
 * Это не просто ОЛД. Это история удалоения из справочника Старых/удаленных Складов
 * !!!!!!!!! Ни когда не удалять этот ОЛД
 *
 * Class Sprwhtop
 * @package app\models
 *
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!
 *
*/
class Sprwhtop_old extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'sprwh_top_old'];
    }

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
    public function attributes()
    {
        return [
            'id',
            'parent_id',
            'name',
            'tx',

            'create_user_id',
            'edit_user_id',
            'delete_sign_user_id',

            'delete_sign',

            'date_create'  ,
            'date_edit'    ,
            'date_delete',

            'move_sign_user_id'    ,
            'date_move'    ,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['id'],  'unique'  ],
            [['id'],  'integer'  ],
            [['id'],  'safe'  ],


            [[ 'date_delete', 'date_create', 'date_edit' ]
                ,'date', 'format'=>'d.m.Y H:i:s'],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id'   => 'ID',
            'id'    => '№ Id',
            'parent_id' => 'parent_id',

            'name'  => 'Компания',
            'tx'    => 'Примечание',

            'date_create'   => 'Дата создания',
            'date_edit'     => 'Дата редактирования',
            'date_delete'   => 'Дата удаления',

            'move_sign_user_id' => 'Кто скинул в ИСТОРИЮ',
            'date_move'         => 'Дата броска в ИСТОРИЮ',

        ];
    }



}
