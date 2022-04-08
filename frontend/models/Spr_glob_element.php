<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;


class Spr_glob_element extends ActiveRecord
{
    public $new_pull;

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'spr_glob_element'];
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

            'cc_id',  /// 1C-Id


            'pd_id',        // Подразделение/Раздел товаров
            'spr_pd.name',
            'name',
            'all_from_txt',
            'ed_izm',
            'intelligent',
            'tx',

            'user_id',
            'user_ip',
            'user_name',
            'dt_update',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id'], 'integer'],

            [['name', 'tx'], 'filter', 'filter' => 'trim'],
            [['all_from_txt'], 'filter', 'filter' => 'trim'],

            // [['cc_id'], 'match', 'pattern' =>'/^[A-ZА-Я]{0,3}[0-9]{5,15}$/u',
            [['cc_id'], 'match', 'pattern' =>'/^[A-ZА-Я]{0,3}[0-9]{5,15}$/u',
                'message' => 'Имя пользователя должно состоять только из латинких букв "БК", чисел'],


            [['all_from_txt'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/',
                'message' => 'Имя пользователя должно состоять только из латинких букв, чисел и подчеркиваний'],



            [[
                'parent_id',
                'ed_izm'
            ], 'required', 'message' => 'Надо выбрать...'],

            [['name',], 'required', 'message' => 'Надо заполнить...'],

            ['dt_update', 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }
            ],

            ['user_ip', 'default', 'value' => function () {
                return Yii::$app->request->getUserIP();
            }],

            ['user_id', 'default', 'value' => function () {
                return Yii::$app->user->identity->id;
            }],

            ['user_name', 'default', 'value' => function () {
                return Yii::$app->user->identity->username;
            }],


            [[
                'id',
                'parent_id',
                'cc_id',
                'pd_id',
                'name',
                'tx',
                'ed_izm'
            ]
                , 'safe'],

            [['parent_id', 'name', 'ed_izm'], 'required'],

            [['ed_izm'], 'default', 'value' => 1],
            [['ed_izm'], 'integer', 'min' => 1],
            [['ed_izm'], 'integer', 'max' => 15],


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

            'cc_id' => '1c_Id',

            'pd_id' => 'Подразделение/Раздел товаров',        // Подразделение/Раздел товаров
            'name' => 'Наименование содержимого в группе типовых элементов',
            'intelligent' => 'Штрих-код',
            'tx' => 'Примечание',
            'ed_izm' => 'Ед.изм',
        ];
    }


    /**
     * @return ActiveQueryInterface
     */
    public function getSpr_glob()
    {
        return $this->hasOne(Spr_glob::className(), ['id' => 'parent_id']);
    }


    /**
     * Relation with Spr_things
     * -
     * @return ActiveQueryInterface
     */
    public function getSpr_things()
    {
        return $this->hasOne(Spr_things::className(), ['id' => (integer)'ed_izm']);
    }


    /**
     * @return ActiveQueryInterface
     */
    public function getSpr_pd()
    {
        return $this->hasOne(Spr_pd::className(), ['id' => 'pd_id']);
    }


    /**
     * Связывает по ИД - парент_ид
     */
    public function getSpr_parent_id()
    {
        return $this->hasOne(Spr_glob_element::className(), ['parent_id' => 'id']);
    }


    /**
     * @param $id
     * @return Spr_glob_element|array|null
     * @throws NotFoundHttpException
     */
    static function findModel($id)
    {
        if (($model = static::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Sklad 1 -   777  Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад');
    }


    /**
     * @return array
     */
    public static function name_plus_id()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'name');
    }


    /**
     * @return array
     */
    public static function id_to_parent()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'parent_id');
    }


    /**
     * Возвращает массив (ИД + ed_izm)
     * -
     * @return array
     */
    public static function id_to_ed_izm()
    {
        return ArrayHelper::map(static::find()->all(), 'id', 'ed_izm');
    }


    /**
     * Выборка для Select2
     * =
     * частный случай
     *
     * @return array
     */
    public static function getOptions_select2()
    {
        return ['' => 'Выбор...'] + ArrayHelper::map(static::find()->all(), 'id', 'name');
    }


    /**
     * Возвращает массив с ПОЛНЫМИ ДАННЫМИ
     * =
     * Конечного Склада и его Компании
     *-
     *
     * @param $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function findFullArray($id)
    {

        if (!isset($id)) {
            throw new NotFoundHttpException('Spr_globam_element. Не указан ИД элемента ');
        }

        $poisk_cild = self::find()
            ->where(
                [
                    '=',
                    'id',
                    (int)$id,
                ])
            ->one();


        $poisk_parent = Spr_glob::find()
            ->where(
                [
                    '=',
                    'id',
                    (int)$poisk_cild['parent_id'],
                ])
            ->one();


        if (isset($poisk_parent) && !empty($poisk_parent['id'])) {
            $array_request['top']['id'] = $poisk_parent['id'];
            $array_request['top']['name'] = $poisk_parent['name'];
        }

        if (isset($poisk_cild) && !empty($poisk_cild['id'])) {
            $array_request['child']['id'] = $poisk_cild['id'];
            $array_request['child']['name'] = $poisk_cild['name'];
        }

        return $array_request;
    }


}
