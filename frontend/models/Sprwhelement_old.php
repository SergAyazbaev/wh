<?php

namespace frontend\models;

use yii\mongodb\ActiveRecord;
use Yii;


/**
 * Class Sprwhelement
 * @package app\models
 */
class Sprwhelement_old extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'sprwh_element_old'];
    }


    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

//        if($this->isNewRecord){
//            // если мы создаем нового пользователя, тогда нам необходимо создать
//            // для него запись в таблице профиля с ссылкой на родительскую таблицу
//            $user_profile = new Sprwhtop_old();
//            $user_profile->id =     $this->id;
//            $user_profile->name =   $this->name;
//            $user_profile->save();
//        } else {
//            // иначе неободимо обновить данные в таблице профиля
//            Sprwhtop_old::model()
//                ->updateAll( [ 'user_id' =>$this->id,
//                    'name' => $this->name,
//                    'tx'=>$this->tx ],
//                    'user_id=:user_id', [':user_id'=> $this->id]
//                );
//        }
    }


    /**
     * Кидаем СЮДА ИСТОРИЮ
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
//
//
//            $model=new Sprwhtop_old();
//            $model->updateAll(
//                [
//                'id' =>$this->parent_id,
//                'name' => $this->name,
//                'tx'=>$this->tx
//                ],
//                [ 'id'=> $this->id ]
//            );
//
////            dd($this);
//
//
            return true;
        }
        return false;
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
            'parent_name',
            'parent_name_tx',

            'create_user_id',
            'edit_user_id',
            'delete_sign_user_id',
            'move_user_id',

            'delete_sign',

            'date_delete',
            'date_create'  ,
            'date_edit'    ,
            'date_move' ,

            'name',
            'all_from_txt',
            'nomer_borta',
            'nomer_gos_registr',
            'nomer_vin',
            'tx',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[
                'parent_name',
                'parent_name_tx',

                'name',
                'tx',
            ],
                'safe'],


            [['id'],  'unique'  ],

            [['id','parent_id'],     'integer'],
//            [['id','parent_id'],     'required' , 'message' => 'Заполнить...' ],

            [['delete_sign'],   'default',  'value' => 0 ],
            [['delete_sign'],   'in',      'range'  => [ 0, 1 ] ],

            [[ 'date_delete', 'date_create', 'date_edit','date_move' ]
                ,'date',
                'format'=>'php:d.m.Y H:i:s'
            ],


            [[ 'date_move' ]
                , 'default', 'value' => date('d.m.Y H:i:s', strtotime('now'))
            ],
            [[ 'move_user_id' ]
                , 'default', 'value' => Yii::$app->getUser()->identity->id
            ],




            [['create_user_id','edit_user_id', 'delete_sign_user_id'], 'default', 'value' => 0],
            [['create_user_id','edit_user_id', 'delete_sign_user_id'], 'integer', 'min' => 0],


            [['name','tx'], 'filter', 'filter' => 'trim'],

            [[
                'nomer_borta',
                'nomer_gos_registr',
                'nomer_vin'], 'filter', 'filter' => 'trim'],

            //[['nomer_borta','nomer_gos_registr','nomer_vin'],  'unique'],


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
            'parent_id' => 'parent',

            'parent_name'   => 'parent_name',
            'parent_name_tx' => 'parent_name_tx',


            'delete_sign' => 'Del',

            'delete_sign_user_id' => 'Id удалившего',
            'create_user_id'    => 'Id создателя',
            'edit_user_id'      => 'Id редактора',
            'move_user_id'      => 'Id mover\'s',

            'date_delete'  => 'Дата удаления',
            'date_create'  => 'Дата создания',
            'date_edit'    => 'Дата редактирования',
            'date_move'    => 'Дата кинули в Историю',

            'name' => 'Наименование склада',
            'nomer_borta'=> 'Борт №',
            'nomer_gos_registr'=> 'Гос.рег.номер',
            'nomer_vin'=> 'VIN',

            'tx' => 'Примечание',
        ];
    }



}
