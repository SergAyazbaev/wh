<?php

namespace frontend\models;


use Yii;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;


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
        return [Yii::$app->params['vars'], 'sprwh_top'];
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
            'deactive',

            'name',   /// Наше название взято из 1С

            'name_tha', /// Название взято из ТХА


            'final_destination', // Признак конечного склада
            // Показывает, что данный склад - Автобус


            'buses_variant', // Все ли ПЕ этого АП являются СКЛАДАМИ

            'tx',

            'create_user_id',
            'edit_user_id',
            'delete_sign',
            'delete_sign_user_id',

            'f_first_bort',

            'date_delete',
            'date_create',
            'date_edit',

            // "create_user_id" : 2,
            //  "date_create" : "25.06.2019 16:40:26",
            //  "delete_sign" : 0,
            //  "edit_user_id" : 0,
            //  "delete_sign_user_id" : 0,
            //  "all_from_txt" : "",
            //  "flag" : 0
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent_id'], 'integer'],


            [[
                'name',
                'name_tha',

                'tx',
                'final_destination',
                'buses_variant', // Все ли ПЕ этого АП являются СКЛАДАМИ
            ],
                'safe'],

            [[
                'id',
                'parent_id',
                'deactive',
                'final_destination',

            ], 'integer'],


            [[
                'buses_variant'
            ], 'integer', 'integerOnly' => true],


            [['delete_sign'], 'default', 'value' => 0],
            [['delete_sign'], 'in', 'range' => [0, 1]],


            [['date_delete', 'date_create', 'date_edit']
                , 'date', 'format' => 'php:d.m.Y H:i:s'],

            [['create_user_id', 'edit_user_id', 'delete_sign_user_id'], 'default', 'value' =>   Yii::$app->user->identity->id  ],
            [['create_user_id', 'edit_user_id', 'delete_sign_user_id'], 'integer', 'min' => 0],


            [['name', 'name_tha', 'tx'], 'filter', 'filter' => 'trim'],
            [['name', 'name_tha'], 'string', 'max' => 180],

            [['f_first_bort'], 'default', 'value' => 0],

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№ Id',
            'parent_id' => 'parent',
            'deactive' => 'Деактивация',

            'name' => 'Компания',
            'name_tha' => 'Компания (версия ТХА)',

            'tx' => 'Примечание',

            'final_destination' => 'ЦС',
            'buses_variant' => 'Все ли ПЕ этого АП являются СКЛАДАМИ',


            'delete_sign' => 'Del',
            'f_first_bort' => "Учет ведется по БОРТОВОМУ номеру", // "УпБ", //'Учет по борту',

            'delete_sign_user_id' => 'Id удалившего',
            'create_user_id' => 'Id создателя',
            'edit_user_id' => 'Id редактора',

            'date_delete' => 'Дата удаления',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата редактирования',
        ];
    }


    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhelement()
    {
        return $this->hasMany(Sprwhelement::className(), ['parent_id' => 'id']);
    }


    /**
     * @return ActiveQueryInterface
     */
    public function getTzautoelement()
    {
        return $this->hasMany(Tzautoelement::className(), ['tz_id' => 'id']);
    }

    /**
     * Finds the sprtype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
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
     * @return array|null|ActiveRecord
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
     * @return array|ActiveRecord
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
     * Получаем состояние флага f_first_bort
     * =
     * @param $id_AP
     * @return mixed
     * @throws \Exception
     */
    public static function getPriznakBort($id_AP)
    {
        return (int)ArrayHelper::getValue(static::find()
            ->where(['id' => (int)$id_AP])
            ->one(), 'f_first_bort');
    }


    /**
     * Самое ПОЛНОЕ ДЕРЕВО Складов
     *
     * @return array|ActiveRecord
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
     * @return array
     */
    static function ArrayNamesWithIds($array_ids)
    {
        return ArrayHelper::map(
            Sprwhtop::find()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->asArray()
                ->where(['id' => $array_ids])
                ->all(),
            'id', 'name'
        );
    }

    /**
     * @return array
     */
    static function ArrayNamesAllIds()
    {
        return ArrayHelper::map(
            static::find()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'name'
        );
    }

    /**
     * All CS
     * =
     * @return array
     */
    static function Array_cs()
    {
        return ArrayHelper::map(
            static::find()
                ->where(['final_destination' => (int)1])
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'name'
        );
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
            'name', 'id'
        );
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
        return ['' => 'Выбрать...'] + ArrayHelper::map(self::find()
                ->where([
                    'OR',
                    ['final_destination' => '1'],
                    ['final_destination' => (int)1]
                ])
                ->orderBy('name')
                ->all(), 'id', 'name');
    }

    /**
     * Получить полный список НЕ ЦЕЛЕВЫХ СКЛАДОВ
     * =
     * OK
     *-
     *
     * @return mixed
     */
    static function get_List_NON_FinalDestination()
    {
        return ['' => 'Выбрать...'] + ArrayHelper::map(self::find()
                ->where(
                    ['!=', 'final_destination', (int)1]
                )
                ->orderBy('name')
                ->all(), 'id', 'name');
    }


    /**
     * Получить Номер Ид по всем полям.
     *-
     * Показать все возможные двойники
     *=
     *
     * @param $name
     * @return array
     */
    static function One_array_from_name($name)
    {
//        $name='Алматинский городской автобусный парк №2 ТОО';
//        $name='Алматинский городской';

        $xx = static::find()
            ->where(
                ['AND',
                    ['!=', 'deactive', 1],
                    ['OR',
                        ['like', 'name', $name],
                        ['like', 'name_tha', $name],
                    ]
                ]

            )
            ->asArray()
            ->one();

        return $xx;

    }

    /**
     *=
     *
     * @param $name
     * @return array
     */
    static function array_from_name($name)
    {
        $name = (string)$name;
        $xx = static::find()
            ->where(
                ['OR',
                    ['like', 'name', $name],
                    ['like', 'name_tha', $name],
                ]
            )
            ->asArray()
            ->one();

        return $xx;

    }

    /**
     * Получить одно Название по Ид
     *-
     * @param $id
     * @return mixed|string
     * @throws \Exception
     */
    static function Name_from_id($id)
    {
        return ArrayHelper::getValue(
            static::find()
                ->where(['==', 'id', (int)$id])
                ->one(), 'name');
    }


}
