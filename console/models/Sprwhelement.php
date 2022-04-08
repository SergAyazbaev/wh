<?php

namespace yii\console;


use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use Yii;
use yii\web\NotFoundHttpException;


class Sprwhelement extends ActiveRecord
{

    const FLAG_DELETED = 1;
    const FLAG_ACTIVE = 0;


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [
//            Yii::$app->params['db_name'],
            'wh_prod',
            'sprwh_element',
        ];
    }


    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->sprwhtop->name;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getSprwhtop()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'parent_id']);
    }


    /**
     * Связка с Таблицей  Sklad
     * -
     *
     * @return \yii\db\ActiveQueryInterface
     */
    public function getSklad()
    {
        return $this->hasOne(Sklad::className(), ['wh_home_number' => 'id']);
    }


    /**
     * Связка с Таблицей  Sklad_inventory
     * -
     *
     * @return \yii\db\ActiveQueryInterface
     */
    public function getSklad_inventory()
    {
        return $this->hasOne(Sklad_inventory::className(), ['wh_destination_element' => 'id']);
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

            'sprwhtop.name',
            'fullName',
            'name',
            'all_from_txt',
            'nomer_borta',
            'nomer_gos_registr',
            'nomer_vin',
            'tx',
            'final_destination',
            // Признак конечного склада
            // Показывает, что данный склад - Автобус

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

                    'name',
                    'nomer_borta',
                    'nomer_gos_registr',
                    'nomer_vin',
                    'tx',

                    'final_destination',
                    'buses_variant',
                    // Все ли ПЕ этого АП являются СКЛАДАМИ

                    'fullName',
                ],
                'safe',
            ],


            [
                ['id'],
                'unique',
            ],
            [
                [
                    'id',
                    'parent_id',
                ],
                'integer',
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
                'date_create',
                'default',
                'value' => function () {
                    return date('d.m.Y H:i:s', strtotime('now'));
                },
            ],


            [
                [
                    'final_destination',
                ],
                'default',
                'value' => 1,
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

            //            [['all_from_txt'], 'filter', 'filter' => 'trim'],
            //            [['all_from_txt'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/',
            //                'message' => 'Your username can only contain alphanumeric characters, underscores and dashes.'],


            [
                [
                    'name',
                    'nomer_borta',
                    'nomer_gos_registr',
                    'nomer_vin',
                ],
                'filter',
                'filter' => 'trim',
            ],


            /// Уникальность четырех полей относительно
            ///  parent_ID+NAME
            [
                ['name'],
                'unique',
                'targetAttribute' => [
                    'parent_id',
                    'name',
                ],
            ],

            [
                ['nomer_borta'],
                'unique',
                'targetAttribute' => [
                    'parent_id',
                    'nomer_borta',
                ],
            ],

            [
                ['nomer_gos_registr'],
                'unique',
                'targetAttribute' => [
                    'parent_id',
                    'nomer_gos_registr',
                ],
            ],

            [
                ['nomer_vin'],
                'unique',
                'targetAttribute' => [
                    'parent_id',
                    'nomer_vin',
                ],
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

            'delete_sign' => 'Del',

            'delete_sign_user_id' => 'Id удалившего',
            'create_user_id' => 'Id создателя',
            'edit_user_id' => 'Id редактора',

            'date_delete' => 'Дата удаления',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата редактирования',

            'fullName' => 'АП',
            'name' => 'Наименование склада',

            'final_destination' => 'Является Целевым складом (ЦС)',

            'nomer_borta' => 'Борт №',
            'nomer_gos_registr' => 'Гос.рег.номер',
            'nomer_vin' => 'VIN',

            'tx' => 'Примечание',
        ];
    }


    /**
     * ПРОВЕРКА. Является ли группа (Автопарк) Целевым ПАРКОМ
     * =
     *
     * @param $parent_id
     *
     * @return bool
     */
    public static function is_cs_group($parent_id)
    {

        $cs = Sprwhtop::find()
            ->select(
                [
                    'id',
                    'parent_id',
                    'name',
                    'final_destination',
                ])
            ->where(
                [
                    'OR',
                    ['id' => $parent_id],
                    ['id' => (int)$parent_id],
                ]
            )
            ->one();

        return (isset($cs->final_destination) && (int)$cs->final_destination === 1);
    }

    /**
     * @param $id
     *
     * @return array|null|ActiveRecord
     */
    static function findModelDouble($id)
    {
        if ((
            $model = static::find()
                ->where(['id' => (integer)$id])
                ->one()

            ) !== null) {
            return $model;
        }

        return [];
    }

    /**
     * Находим PARENT_ID через ID
     * -
     *
     * @param $id
     *
     * @return array|mixed
     */
    static function find_parent_id($id)
    {
        if (($model = static::find()
                ->where(['id' => (integer)$id])->one()) !== null) {
            return $model['parent_id'];
        }

        return [];
    }

    /**
     * @param $bus_id
     *
     * @return array|ActiveRecord|null
     */
    static function show_me_AP_by_Name($bus_name)
    {
        return static::find()->where(['name' => trim($bus_name)])
            ->asArray()
            ->one();
    }


    /**
     * @param $array
     *
     * @return array|ActiveRecord
     */
    static function findModelDoubleRange($array)
    {
        if (($model = static::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->where(['id' => $array])
                ->asArray()->all()) !== null) {

            return $model;
        }

        return [];
    }


    /**
     * Получить список всех автобусов = ЦC
     * =
     *
     * @param $array
     *
     * @return array|ActiveRecord
     */
    static function getAll_cs()
    {
        return ArrayHelper::map(
            static::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->where(
                    [
                        '>=',
                        'final_destination',
                        (int)1,
                    ])
                ->asArray()
                ->all(), 'id', 'name');
    }


    /**
     * Получить Все АВТОБУСЫ ДАННОГО Автопарка (id - парка)
     *-
     *
     * @param $id
     *
     * @return array|ActiveRecord
     */
    static function findAll_Elements_Parent($parent_id)
    {
        return ArrayHelper::map(
            static::find()->where(['parent_id' => (int)$parent_id])->orderBy(['name DESC'])->all(),
            'id', 'name');
    }


    /**
     * Получить все ключи по всей ГРУППЕ СКЛАДА
     *-
     *
     * @param $id
     *
     * @return array
     */
    static function findAll_keys_from_parent($id)
    {
        if ((int)$id == 0) {
            if (($model = static::find()->select(['id'])->asArray()->all()) !== null) {
                return ArrayHelper::getColumn($model, 'id');
            }
        } else {

            if (($model = Sprwhelement::find()->where(['parent_id' => (int)$id])->select(['id'])->asArray()->all()) !== null) {
                return ArrayHelper::getColumn($model, 'id');
            }
        }

        return [];
    }


    /**
     * @param $parent_id
     *
     * @return array|ActiveRecord
     */
    static function findAll_Parent_as_Array($parent_id)
    {
        if (($model = static::find()->where(['parent_id' => (integer)$parent_id])
                ->asArray()
                ->all()) !== null) {
            return $model;
        }

        return [];
    }


    /**
     * Получить массив из двух полей для менюшки
     *=
     * на входе: номер группы
     *-
     * на выходе: список-название + Id
     *-
     *
     * @param $parent_id
     *
     * @return array
     */
    static function findChi_as_Array($parent_id)
    {
        return ArrayHelper::map(
            static::find()
                ->where(['parent_id' => (int)$parent_id])
                ->all(), 'name', 'id');
    }


    /**
     * Получить Массив Всех НОМЕРОВ АВТОБУСОВ по их ИД
     * (по масиву ИД номеров)
     *
     * @param $id
     *
     * @return array|ActiveRecord
     */
    static function findAll_Attrib_PE($array_id)
    {
        $array_id = array_map('intval', $array_id); // Приводим в массив Целых ЧИСЕЛ

        return ArrayHelper::map(
            static::find()
                ->where(['id' => $array_id])
                ->asArray()
                ->all(), 'id', 'name');
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
    static function findFullArray($id)
    {
        if (!isset($id)) {
            throw new NotFoundHttpException('SprWhElement. Не указан ИД склада ');
        }

        $poisk_deb = self::findModelDouble($id);

        //ddd( $poisk_deb );

        if (isset($poisk_deb) && !empty($poisk_deb['parent_id'])) {

            $poisk_deb_top = Sprwhtop::find()
                ->where(['id' => (integer)$poisk_deb['parent_id']])->one();

            //dd($poisk_deb['parent_id']);
            $array_request['top']['id'] = $poisk_deb_top['id'];
            $array_request['top']['name'] = $poisk_deb_top['name'];
            $array_request['top']['cs'] = $poisk_deb['final_destination'];

            $array_request['child']['id'] = $poisk_deb['id'];
            $array_request['child']['name'] = $poisk_deb['name'];

            $array_request['child']['nomer_borta'] = $poisk_deb['nomer_borta'];
            $array_request['child']['nomer_gos_registr'] = $poisk_deb['nomer_gos_registr'];
            $array_request['child']['nomer_vin'] = $poisk_deb['nomer_vin'];
            $array_request['child']['tx'] = $poisk_deb['tx'];

            if (isset($poisk_deb['final_destination']) && $poisk_deb['final_destination'] == 1) {
                $array_request['child']['cs'] = ($array_request['child']['id']);
            } else {
                $array_request['child']['cs'] = (0);
            }

            ///

        } else {
            $array_request['top']['name'] = '';//'Внимание! Не указан';
            $array_request['child']['name'] = '';//'Внимание! Не указан';
            $array_request['child']['cs'] = 0;

        }

        //            else
        //                throw new NotFoundHttpException('в модели-SprWhElement. Полные данные не найдены.');

        return $array_request;
    }


    /**
     * Получить Список Групп Складов
     * /// Уникальные ТОЛЬКО ТУТ ИМЕНА
     *=
     * по массиву их ИД
     * -
     *
     * @param $array_parent_ids
     * @param $array_ids
     *
     * @return array
     */
    static function ArrayNamesWithIds_andParents($array_parent_ids, $array_ids)
    {
        $xx = ArrayHelper::map(
            static::find()
                ->select(
                    [
                        'id',
                        'name',
                    ])
                ->orderBy('name')
                ->asArray()
                ->where(
                    [
                        'AND',
                        ['parent_id' => $array_parent_ids],
                        ['id' => $array_ids],
                    ])
                ->all(),
            'id', 'name');

        //        ddd($array_parent_ids);
        //        ddd($xx);
        return $xx;
    }

    /**
     * Получить Список Складов сгласно перечню
     *=
     * (по массиву ИД)
     * -
     *
     * @param $array_ids
     *
     * @return array
     */
    static function ArrayNamesWithIds($array_ids)
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
     * Массив Соответсвия ИД-склада == ИД-группы складов
     * =
     *
     * @return array
     */
    static function ArrayParentId_FromID()
    {
        return ArrayHelper::map(
            static::find()
                ->select(
                    [
                        'id',
                        'parent_id',
                    ])
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'parent_id');
    }


    /**
     * Применяем ко всем потомкам по признаку родителя
     *=
     *
     * @param $parent_id
     * @param $sign
     *
     * @return bool
     */
    static function setFinal_Destination($parent_id, $sign)
    {


        // НЕ рАБОТАЕТ В ВЕРСИИ НОВОГО СЕРВЕРА!!!!!
        //			$collection = Yii::$app->mongodb->getCollection( 'sprwh_element' );
        //			$collection->update(
        //				[ 'parent_id' => $parent_id ],
        //				[ 'final_destination' => $sign ]
        //			);


        // OK Работает
        $result = Sprwhelement::updateAll(['final_destination' => $sign], ['parent_id' => $parent_id]);

        if ($result <= 0) {
            return false;
            //ddd( $result );
        }

        // OK Работает

        return true;
    }


    /**
     * @param $id
     *
     * @return Sprwhelement|null
     * @throws NotFoundHttpException
     */
    static function findModel($id)
    {
        if (($model = static::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Not exist.');
    }


    /**
     * Принимает ИД
     * =
     * Возвращает Одну запись со всеми полями в МАССИВЕ
     * =
     *
     * @param $id
     *
     * @return array|ActiveRecord
     */
    static function findOne_model($id)
    {
        return static::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
    }


    /**
     * Является ЛИ Целевым Складом (ЦС)
     * =
     * final_destination === 1;
     * -
     *
     * @param $id
     *
     * @return bool
     * @throws NotFoundHttpException
     */
    static function is_FinalDestination($id)
    {
        if (!isset($id)) {
            throw new NotFoundHttpException('SprWhElement. is_FinalDestination(). Не указан ИД склада ');
        }

        $one_item = self::findOne_model($id);

        //ddd();

        return $one_item['final_destination'] === 1;
    }

    /**
     * @return array
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

    /**
     * Получить полный Список ИМЕН
     * =
     * и представить его в инверсии для поиска по ключу
     * -
     *
     * @return array
     */
    static function ArrayNames_inverce_id()
    {
        return ArrayHelper::map(
            static::find()->all(),
            'name', 'id');
    }

    /**
     * ПАКЕТНОЕ УДАЛЕНИЕ из справочник WH по ИД
     * =
     * Используем Подготовленный массив
     * -
     *
     * @param $array_park_bus
     *
     * @return array
     * @throws NotFoundHttpException
     */
    static function Delete_by_id($array_ids)
    {

        if (!isset($array_ids)) {
            throw new NotFoundHttpException('SprWhElement. Delete_by_id. Массив не получен');
        }

        ///////// ВОТ !!!!!!
        $xx = Sprwhelement::deleteAll(['id' => $array_ids]);
        //ddd($xx); // Возвращает количество


        if ($xx < 0) {
            throw new NotFoundHttpException('SprWhElement. Delete(). Не удалаил');
        }


        $array = [
            'how_math' => $xx,
            'find_math' => $xx,
        ];

        return $array;
    }


}
