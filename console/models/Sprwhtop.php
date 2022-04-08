<?php

namespace yii\console;


use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use \yii\mongodb\ActiveRecord;

/**
 * Class Sprwhtop
 * @package yii\console
 */
class Sprwhtop extends ActiveRecord
{
    const BUSES_VARIANT_NO = 0;
    const BUSES_VARIANT_ALL = 1;
    const BUSES_VARIANT_DIFERENT = 2;


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [
//            Yii::$app->params['db_name'],
            'wh_prod',
            'sprwh_top'];
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

            'final_destination',
            // Признак конечного склада
            // Показывает, что данный склад - Автобус


            'buses_variant',
            // Все ли ПЕ этого АП являются СКЛАДАМИ

            'tx',

            'create_user_id',
            'edit_user_id',
            'delete_sign',
            'delete_sign_user_id',

            'date_delete',
            'date_create',
            'date_edit',

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'parent_id',
                ],
                'integer',
            ],


            [
                [
                    'name',
                    'tx',
                    'final_destination',
                    'buses_variant',
                    // Все ли ПЕ этого АП являются СКЛАДАМИ
                ],
                'safe',
            ],

            [
                [
                    'id',
                    'parent_id',
                ],
                'integer',
            ],


            [
                [
                    'final_destination',
                    'buses_variant',
                ],
                'integer',
                'integerOnly' => true,
            ],


            [
                ['delete_sign'],
                'default',
                'value' => 0,
            ],
            [
                ['delete_sign'],
                'in',
                'range' => [
                    0,
                    1,
                ],
            ],


            [
                [
                    'date_delete',
                    'date_create',
                    'date_edit',
                ]
                ,
                'date',
                'format' => 'php:d.m.Y H:i:s',
            ],

            [
                [
                    'create_user_id',
                    'edit_user_id',
                    'delete_sign_user_id',
                ],
                'default',
                'value' => 0,
            ],
            [
                [
                    'create_user_id',
                    'edit_user_id',
                    'delete_sign_user_id',
                ],
                'integer',
                'min' => 0,
            ],


            [
                [
                    'name',
                    'tx',
                ],
                'filter',
                'filter' => 'trim',
            ],


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

            'name' => 'Компания',
            'tx' => 'Примечание',

            'final_destination' => 'ПЕ ЦС',
            'buses_variant' => 'Все ли ПЕ этого АП являются СКЛАДАМИ',


            'delete_sign' => 'Del',

            'delete_sign_user_id' => 'Id удалившего',
            'create_user_id' => 'Id создателя',
            'edit_user_id' => 'Id редактора',

            'date_delete' => 'Дата удаления',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата редактирования',
        ];
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getSprwhelement()
    {
        return $this->hasMany(Sprwhelement::className(), ['parent_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getTzautoelement()
    {
        return $this->hasMany(Tzautoelement::className(), ['tz_id' => 'id']);
    }


    /**
     * Finds the sprtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Sprwhtop|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    static function findModel($id)
    {
        if (($model = static::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not exist.');
    }

    /**
     * @param $id
     * @return array|null|\yii\mongodb\ActiveRecord
     * @throws NotFoundHttpException
     */
    static function findModelDouble($id)
    {
        if (($model = static::find()->where(['id' => (integer)$id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not exist.');
    }


    /**
     * @return array|\yii\mongodb\ActiveRecord
     * @throws NotFoundHttpException
     */
    static function Fullwith()
    {
        if (($model = static::find()->with('sprwhelement')->asArray()->all()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not exist.');
    }


    /**
     * Самое ПОЛНОЕ ДЕРЕВО Складов
     *
     * @return array|\yii\mongodb\ActiveRecord
     */
    static function TreeNodesFull()
    {
        $array_xx = [];

        if (($model = static::find()->with('sprwhelement')->asArray()->all()) !== null) {
            foreach ($model as $item) {

                if (isset($item['sprwhelement']) && !empty($item['sprwhelement'])) {
                    $array_xx2 = [];
                    foreach ($item['sprwhelement'] as $ii) {
                        $array_xx2[] = [
                            'text' => $ii['name'],
                            'selectable' => true,
                            'id' => $ii['id'],
                            'expanded' => true,
                            'dataAttr' => [
                                'target' => $ii['id'],
                            ],
                        ];
                    }
                }

                $array_str = [
                    'text' => $item['name'],
                    'state' => [
                        //'checked' => true,
                        //'disabled'=> true,
                        'expanded' => true,
                        //'selected'=> true
                    ],
                    'nodes' => $array_xx2
                ]; // TOP

                $array_xx[] = $array_str; // TOP
            }


            return $array_xx;
        }

        return [['text' => 'Ни чего нет']];
    }


    /**
     * Получить Список Групп Складов
     * /// Уникальные ТОЛЬКО ТУТ ИМЕНА
     *=
     * по массиву их ИД
     * -
     *
     * @param $array_ids
     *
     * @return array
     */
    static function ArrayNamesWithIds($array_ids)
    {
        return ArrayHelper::map(
            Sprwhtop::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->orderBy('name')
                ->asArray()
                ->where(['id' => $array_ids])
                ->all(),
            'id', 'name');
    }

    /**
     * @return array
     */
    static function ArrayNamesAllIds()
    {
        return ArrayHelper::map(
            static::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'name');
    }


    /**
     * Получить полный Список Групп Складов
     * =
     * и представить его в инверсии для поиска по ключу
     * -
     *
     * @return array
     */
    static function ArrayNames_inverce_id()
    {
        return ArrayHelper::map(
            Sprwhtop::find()->all(),
            'name', 'id');
    }


    /**
     * Получить полный список ЦС - GROUP //Список ЦС - групп
     * =
     * OK
     *-
     *
     * @return mixed
     */
    static function get_ListFinalDestination()
    {
        return ArrayHelper::map(
            self::find()
                ->where(
                    [
                        'OR',
                        ['final_destination' => '1'],
                        ['final_destination' => (int)1],
                    ])
                ->orderBy('name')
                ->asArray()
                ->all(), 'id', 'name');
    }

}
