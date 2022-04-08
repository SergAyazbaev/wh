<?php

namespace frontend\models;

use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @property integer id
 * @property integer parent_id
 * @property string name
 * @property string nomer_borta
 * @property string nomer_gos_registr
 * @property string nomer_traktor
 *
 * @property string tx
 *
 * @property date date_create
 * @property int create_user_id
 * @property int deactive
 * @property int f_first_bort
 * @property int final_destination
 * @property int dc_timestamp
 *
 **/
class Sprwhelement extends ActiveRecord
{
    public $find_parent_id;

    public $imageFile;
    public $find_name; //Поисковая фраза (во вьюшке)

    public $write_id; //
    public $all_from_txt; //

    const FLAG_DELETED = 1;
    const FLAG_ACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'sprwh_element',
        ];
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

            'sprwhtop.name',
            'fullName',
            'name',
            'all_from_txt',
            'nomer_borta',
            'nomer_gos_registr',
            'nomer_traktor',
            'nomer_vin',
            'tx',

            'flag',

            'final_destination',
            'f_first_bort',

            'create_user_id',
            'edit_user_id',
            'delete_sign',
            'delete_sign_user_id',

            'date_delete',

            'date_create',
            'dc_timestamp',

            'date_edit',
            'imageFile',
            'find_parent_id',

            'update_points',
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
                    'deactive',

                    'all_from_txt',

                    'name',
                    'nomer_borta',
                    'nomer_gos_registr',
                    'nomer_traktor',
                    'nomer_vin',
                    'tx',

                    'flag',
                    'final_destination',
                    // Все ли ПЕ этого АП являются СКЛАДАМИ
                    'f_first_bort',

                    'update_points',
                ], 'safe'],


            //
            [['id'], 'unique'],
            //
            [['tx'], 'string', 'min' => 0, 'max' => 255],
            //
            [[
                'id',
                'parent_id',
                'deactive',
                'f_first_bort',
                'dc_timestamp',
            ], 'integer'],


            //
            [['delete_sign'], 'default', 'value' => 0],
            [['f_first_bort'], 'default', 'value' => 0],

            [['delete_sign', 'f_first_bort'], 'in', 'range' => [0, 1]],

            [[
                'date_create'],
                'default',
                'value' => function () {
                    return date('d.m.Y H:i:s', strtotime('now'));
                },
            ],

            [[
                'dc_timestamp'
            ], 'default', 'value' => function () {
                return (int)strtotime('now');
            }
            ],


            //
            [['final_destination'], 'default', 'value' => 1],

            [[
                'create_user_id',
                'edit_user_id',
                'delete_sign_user_id',
            ], 'default', 'value' => 0],


            //   [['all_from_txt'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/',
            //      'message' => 'Your username can only contain alphanumeric characters, underscores and dashes.'],


            //TRIM
            [[
                'name',
                'tx',
                'nomer_borta',
                'nomer_gos_registr',
                'nomer_traktor',
                'nomer_vin',
            ], 'filter', 'filter' => 'trim'],

            ///UPPER
            [[
                'name',
                'nomer_borta',
                'nomer_gos_registr',
                'nomer_traktor',
                'nomer_vin',
            ], 'filter', 'filter' => 'strtoupper'],

            /// Уникальность полей
            //
            [['nomer_borta'], 'unique', 'targetAttribute' => ['nomer_borta', 'parent_id', 'f_first_bort'],
                'message' => 'Не возможно сохранить - Двойник АП+БОРТ+УчетБорт'],
            //
            [['nomer_gos_registr'], 'unique', 'targetAttribute' => ['nomer_gos_registr', 'parent_id', 'f_first_bort'],
                'message' => 'Не возможно сохранить - Двойник АП+ГОС+УчетБорт'],

            //
            [['nomer_traktor'], 'unique', 'targetAttribute' => ['nomer_traktor', 'parent_id', 'f_first_bort'],
                'message' => 'Не возможно сохранить - Двойник АП+ТРАКТОР+УчетБорт'],

            [['nomer_borta'], 'match',
                'pattern' => '/^[0-9]{1,10}$/',
                'message' => $this->nomer_borta . 'Не соотвествует маске nomer_borta. Исправить...'],

//            [['nomer_gos_registr'], 'match',
//                'pattern' => "/^[A-Z]{0,1}[0-9]{3,4}[A-Z]{2,3}[0-9]{0,2}$/",
//                'message' => $this->nomer_gos_registr . ' Не соотвествует маске nomer_gos_registr. Исправить...  '],

            [['nomer_traktor'], 'match',
                'pattern' => "/^[A-Z]{0,4}[0-9]{3,4}[A-Z]{1,4}[0-9]{0,2}$/",
                'message' => $this->nomer_traktor . ' Не соотвествует маске nomer_traktor. Исправить...'],


            /// Инициализация update_points
            /// START
            [
                'update_points',
                'default',
                'value' => function () {
                    $xx[] = [
                        'dt_update' => date('d.m.Y H:i:s', strtotime('now')),
                    ];
                    return $xx;
                },
            ],


            /// Запись ИСТОРИИ
            ///  Инициализация update_points
            /// JOI
            [
                ['update_points'], 'record_history'
            ],

            ///
            [
                [
                    'nomer_gos_registr',
                    'nomer_traktor'
                ],
                function ($attribute) {
                    //
                    //ddd($this->final_destination);

                    if ((int)$this->final_destination == 0) {
                        return true;
                    }

                    ///
                    if (!empty($this->nomer_gos_registr) && !empty($this->nomer_traktor)) {
                        $this->addError(
                            $attribute,
                            $this->id . ' == ' . $this->nomer_gos_registr . ' Либо ГОС, либо Трактор'
                        );
                        return false;
                    }

                    //
                    if ((int)$this->f_first_bort != 1) {
                        //
                        if (!empty($this->nomer_gos_registr)) {
                            $this->name = $this->nomer_gos_registr;
                        }
                        //
                        if (!empty($this->nomer_traktor)) {
                            $this->name = $this->nomer_traktor;
                        }
                    }
                    return true;
                }
            ],
            ///
            [
                ['nomer_borta'],
                function ($attribute) {
                    if ((int)$this->f_first_bort == 1) {
                        //
                        if (!empty($this->nomer_borta)) {
                            $this->name = $this->nomer_borta;
                        }
                    }
                }
            ],
        ];
    }

    /**
     * Отпечаток Пользовательских данных.
     * -
     * Исторические данные в накладных
     * -
     *
     * @return bool
     */
    public function record_history()
    {
        $xx = $this->update_points;

        $xx[] = [
            'dt_update' => date('d.m.Y H:i:s', strtotime('now')),
            'dt_update_timestamp' => strtotime('now'),
            'user_ip' => Yii::$app->request->getUserIP(),
            'user_id' => Yii::$app->user->identity->id,
            'user_name' => Yii::$app->user->identity->username,
            'user_group_id' => Yii::$app->user->identity->group_id,
            'user_role' => Yii::$app->user->identity->role,
        ];

        $this->update_points = $xx;
        return true;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№ Id',
            'parent_id' => 'АП',
            'deactive' => 'Деактивация',

            'delete_sign' => 'Del(1)',

            'delete_sign_user_id' => 'Id удалившего',
            'create_user_id' => 'Id создателя',
            'edit_user_id' => 'Id редактора',

            'date_delete' => 'Дата удаления',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата редактирования',

            'fullName' => 'АП',
            'name' => 'Наим. склада',

            'final_destination' => 'ЦС',
            'f_first_bort' => 'Учет ведется по БОРТу (1)',

            'nomer_borta' => 'Борт №',
            'nomer_gos_registr' => 'Гос. №',
            'nomer_traktor' => 'Трактор №',
            'nomer_vin' => 'VIN',

            'tx' => 'Примечание',
            'imageFile' => 'imageFile',
        ];
    }


    /**
     * setNext_max_id()
     * =
     * Вычисляем следующий новый ИД
     *
     * @return int
     */
    public static function setNext_max_id()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;
    }

    /**
     *
     */
    public function getSprwhelement_change()
    {
        return $this->hasMany(Sprwhelement_change::className(), ['parent_id' => 'id']);
        //->andOnCondition(['parent_id' => '']);

        // ->andOnCondition(Sprwhelement_change::tableName() . '.min >= ' . self::tableName() . '.min')
        // ->andOnCondition(Sprwhelement_change::tableName() . '.max <= ' . self::tableName() . '.max');
    }

    /**
     * saveImage TO SprWHElement
     * =
     *
     * @param $filename
     * @return bool
     */
    public function saveImage($filename)
    {
        ddd($this->imageFile);
        ddd(123);

        $this->imageFile = $filename;
        return $this->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function actionDelete()
    {
        $para = Yii::$app->request->get();

        $model = self::findModel($para['id']);

        $model->delete();

        // Возвтрат по РЕФЕРАЛУ
        $url_array = Yii::$app->request->headers;
        $url = $url_array['referer'];

        return $this->goBack($url);
    }


    /**
     *
     */
    public function getFullName()
    {
        return $this->sprwhtop->name;
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhtop()
    {
        return $this->hasOne(Sprwhtop::className(), ['id' => 'parent_id']);
    }


    /**
     * Связка с Таблицей  Sklad
     * -     *
     * @return ActiveQueryInterface
     */
    public function getSklad()
    {
        return $this->hasOne(Sklad::className(), ['wh_home_number' => 'id']);
    }

    /**
     * Связка с Таблицей  Sklad_inventory
     * -
     *
     * @return ActiveQueryInterface
     */
    public function getSklad_inventory()
    {
        return $this->hasOne(Sklad_inventory::className(), ['wh_destination_element' => 'id']);
    }

    /**
     * ПРОВЕРКА. Является ли группа (Автопарк) Целевым ПАРКОМ
     * =
     *
     * @param $parent_id
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
                ]
            )
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
     * Получить Список ВСЕХ элементов в группе
     *=
     * @param $child_id
     * @return int|mixed
     */
    public static function ParentId_from_ChildId($child_id)
    {
        $arr = static::find()
            ->select(['parent_id',])
            ->where(['id' => $child_id])
            ->one();

        if (isset($arr->parent_id)) {
            return $arr->parent_id;
        }
        return 0;
    }

    /**
     * Получить Список ВСЕХ элементов в группе
     *=
     *
     * @param $parent_id
     * @return array
     */
    static function Array_id_parent_id($parent_id)
    {
        return ArrayHelper::getColumn(
            static::find()
                ->select(['id',])
                ->orderBy('name')
                ->asArray()
                ->where(['parent_id' => $parent_id])
                ->all(),
            'id'
        );
    }

    /**
     * @param $id
     * @return array|null|ActiveRecord
     */
    static function findModelDouble($id)
    {
        return static::find()
            ->where(
                ['OR',
                    ['id' => $id],
                    ['id' => (int)$id]
                ]
            )
            ->one();
    }

    /**
     * Array PE
     *=
     * @param $id
     * @return array|ActiveRecord|null
     */
    static function array_Model_Pe($id)
    {
        return static::find()
            ->where(
                ['OR',
                    ['id' => $id],
                    ['id' => (int)$id]
                ]
            )
            ->asArray()
            ->one();
    }

    /**
     * Находим PARENT_ID через ID
     * -
     *
     * @param $id
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
     * @param $bus_name
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
     * @return array|ActiveRecord
     */
    static function findModelDoubleRange($array)
    {
        if (($model = static::find()
                ->select(['id', 'name'])
                ->where(['id' => $array])
                ->asArray()
                ->all()) !== null) {
            return $model;
        }
        return [];
    }

    /**
     * Поиск ИД ПАРК-ПАРК по ИД ПАРК
     * =
     * @param $park_id
     * @return array|ActiveRecord
     * @throws \Exception
     */
    static function id_park_by_PARK_NAME($park_id)
    {
        // 'BATU HOLDING ТОО'
        $str_top_name = ArrayHelper::getValue(Sprwhtop::find()
            ->select(['name'])
            ->where(['id' => (int)$park_id])
            ->one(), 'name');

        if (!empty($str_top_name)) {
            $id = ArrayHelper::getValue(static::find()
                ->select(['id'])
                ->where(['=', 'name', (string)$str_top_name])
                ->one(), 'id');
        }

        if (empty($id)) {
            return [
                'id' => 0,
                'name' => $str_top_name
            ];
        }
        ///
        return [
            'id' => $id,
            'name' => $str_top_name
        ];

    }

    /**
     * Получить список всех автобусов = ЦC
     * =
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
                    ]
                )
                ->where(
                    [
                        '>=',
                        'final_destination',
                        (int)1,
                    ]
                )
                ->asArray()
                ->all(), 'id', 'name'
        );
    }

    /**
     * Получить МАССИВ всех автобусов = ЦC-ИД
     * =
     *
     * @return array|ActiveRecord
     */
    static function getArray_cs_ids()
    {
        return
            ArrayHelper::getColumn(static::find()
                ->select(['id'])
                ->where(['>=', 'final_destination', (int)1])
                ->orderBy('id ASC')
                ->asArray()
                ->all(), 'id');
    }

    /**
     * Получить МАССИВ всех автобусов = ЦC-ИД
     * =
     *
     * @return array|ActiveRecord
     */
    static function getArray_cs_ids_names()
    {
        return
            ArrayHelper::map(static::find()
                ->select(['id', 'name'])
                ->where(['>=', 'final_destination', (int)1])
                ->orderBy('id ASC')
                ->asArray()
                ->all(), 'id', 'name');
    }

    /**
     * Получить Все АВТОБУСЫ ДАННОГО Автопарка (id - парка)
     *-
     *
     * @param $parent_id
     * @return array|ActiveRecord
     */
    static function findAll_Elements_Parent($parent_id)
    {
        return ArrayHelper::map(
            static::find()
                ->where(['parent_id' => (int)$parent_id])
                ->orderBy(['name DESC'])
                ->all(),
            'id', 'name'
        );
    }


    /**
     * Получить по списку АЙДИ-шников
     *-
     *
     * @param $array_ids
     * @return array|ActiveRecord
     */
    static function findAll_username_by_ids($array_ids)
    {
        return ArrayHelper::map(
            static::find()
                ->where(['IN', 'id', $array_ids])
                ->orderBy(['name ASC'])
                ->all(),
            'id', 'name'
        );
    }

    /**
     * Получить все ключи по всей ГРУППЕ СКЛАДА
     *-
     *
     * @param $id
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
     * @return array|ActiveRecord
     */
    static function findAll_Parent_as_Array($parent_id)
    {
        if (($model = static::find()
                ->where(['parent_id' => (int)$parent_id])
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
     * @return array
     */
    static function findChi_as_Array($parent_id)
    {
        return ArrayHelper::map(static::find()
            ->where(
                ['OR',
                    ['parent_id' => $parent_id],
                    ['parent_id' => (int)$parent_id],
                ]
            )
            ->all(), 'name', 'id');
    }

    /**
     * Получить Массив Всех НОМЕРОВ АВТОБУСОВ по их ИД
     * (по масиву ИД номеров)
     *
     * @param $array_id
     * @return array|ActiveRecord
     */
    static function findAll_Attrib_PE($array_id)
    {
        $array_id = array_map('intval', $array_id); // Приводим в массив Целых ЧИСЕЛ

        return ArrayHelper::map(
            static::find()
                ->where(['id' => $array_id])
                ->asArray()
                ->all(), 'id', 'name'
        );
    }

    /**
     * Возвращает массив с ПОЛНЫМИ ДАННЫМИ
     * =
     * Конечного Склада и его Компании
     *-
     *
     * @param $id
     * @return mixed
     */
    static function findFullArray($id)
    {
        $poisk_deb = self::findModelDouble($id);


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
     *  Full Pe
     *=
     * @param $id_element
     * @return mixed
     * @throws NotFoundHttpException
     */
    public static function arrayFull_Pe($id_element)
    {
        if (!isset($id_element)) {
            throw new NotFoundHttpException('SprWhElement. findFullArray. Не указан ИД склада ');
        }
        $model = self::findModelDouble($id_element);
        return $model;
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
                    ]
                )
                ->orderBy('name')
                ->asArray()
                ->where(
                    [
                        'AND',
                        ['parent_id' => $array_parent_ids],
                        ['id' => $array_ids],
                    ]
                )
                ->all(),
            'id', 'name'
        );

        //        ddd($array_parent_ids);
        //        ddd($xx);
        return $xx;
    }

    /**
     * Получить Список ТМЦ в группе
     *=
     * (по массиву ИД)
     * -
     *
     * @param $parent_id
     * @return array
     */
    static function ArrayOnParent_id($parent_id)
    {
        return ArrayHelper::map(
            static::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy('name')
                ->asArray()
                ->where(['parent_id' => (int)$parent_id])
                ->all(),
            'id', 'name'
        );
    }

    /**
     * Получить Номер Ид по всем полям.
     *-
     * Показать все возможные двойники
     *=
     *
     * @param $name
     * @param $parent_id
     * @return array
     * //static function All_id_from_name($name, $parent_id, $f_bort)
     */
    static function All_id_from_name($name, $parent_id)
    {
        return static::find()
            ->where(
                ['AND',
                    ['<>', 'deactive', 1],
                    ['parent_id' => $parent_id],

                    ['==', 'name', $name]
                ]
            )
            ->asArray()
            ->all();
    }


    /**
     * Получить Список Складов сгласно перечню
     *=
     * (по массиву ИД)
     * -
     *
     * @param $array_ids
     * @return array
     */
    static function ArrayNamesWithIds($array_ids)
    {
        if (is_numeric($array_ids)) {
            $array_ids = [$array_ids];
        }
        // Приводим массив к массиву чисел
        $array_ids = array_map('intval', $array_ids);

        return ArrayHelper::map(
            static::find()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->asArray()
                ->where(['id' => $array_ids])
                ->all(),
            'id', 'name'
        );
    }


    /**
     * @param $array_ids
     * @return array
     */
    static function ArrayGosWithIds($array_ids)
    {
        return ArrayHelper::map(

            static::find()
                ->select(
                    [
                        'id',
                        'nomer_gos_registr',
                    ]
                )
                ->orderBy('nomer_gos_registr')
                ->asArray()
                ->where(
                    ['AND',
                        ['id' => $array_ids],
                        ['!=', 'nomer_gos_registr', '']
                    ]
                )
                ->all()
            , 'id', 'nomer_gos_registr'
        );

    }

    /**
     * @param $array_ids
     * @return array
     */

    static function ArrayBortWithIds($array_ids)
    {
        return ArrayHelper::map(

            static::find()
                ->select(
                    [
                        'id',
                        'nomer_borta',
                    ]
                )
                ->orderBy('nomer_borta')
                ->asArray()
                ->where(
                    ['AND',
                        ['id' => $array_ids],
                        ['!=', 'nomer_borta', '']
                    ]
                )
                ->all()
            , 'id', 'nomer_borta'
        );

    }

    /**
     * @return array
     */
    static function ArrayNamesAllIds()
    {
        $array = ArrayHelper::map(
            static::find()
                ->select(
                    [
                        'id',
                        'name',
                    ]
                )
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'name'
        );


        // Пустышки удаляет из массива
        $array = array_filter($array);
        // Двойники удаляет из массива
        $array = array_unique($array);

        return $array;

    }

    /**
     * Массив Соответсвия ИД-склада == ИД-группы складов
     * =
     *
     * @return array
     */
    static function ArrayParentId_FromID()
    {
        return array_filter(ArrayHelper::map(
            static::find()
                ->select(
                    [
                        'id',
                        'parent_id',
                    ]
                )
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'parent_id'
        ));
    }

    /**
     * Применяем ко всем потомкам по признаку родителя
     *=
     *
     * @param $parent_id
     * @param $sign
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
        $result = Sprwhelement::updateAll(
            ['final_destination' => $sign],
            ['parent_id' => $parent_id],
            [
                'upsert' => false,
                'multi' => true
            ]
        );

        if ($result <= 0) {
            return false;
        }
        return true;
    }

    /**
     * Применяем ко всем потомкам по признаку родителя
     *=
     *
     * @param $parent_id
     * @param $sign
     * @return bool
     */
    static function setDeactive($parent_id, $sign)
    {
        // НЕ рАБОТАЕТ В ВЕРСИИ НОВОГО СЕРВЕРА!!!!!
        //			$collection = Yii::$app->mongodb->getCollection( 'sprwh_element' );
        //			$collection->update(
        //				[ 'parent_id' => $parent_id ],
        //				[ 'final_destination' => $sign ]
        //			);

        // OK Работает
        $result = Sprwhelement::updateAll(
            ['deactive' => $sign],
            ['parent_id' => $parent_id],
            [
                'upsert' => false,
                'multi' => true
            ]);

        if ($result <= 0) {
            return false;
        }
        return true;
    }

    /**
     * @param $parent_id
     * @param $sign
     * @return bool
     */
    static function setBort_is_first($parent_id, $sign)
    {
        $result = Sprwhelement::updateAll(
            ['f_first_bort' => $sign],
            ['parent_id' => $parent_id],
            [
                'upsert' => false,
                'multi' => true
            ]
        );

        if ($result <= 0) {
            return false;
        }
        return true;
    }

    /**
     * @param $id
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

        return (isset($one_item['final_destination']) && $one_item['final_destination'] === 1);
    }

    /**
     * @return array
     */
    static function get_ListFinalDestination()
    {
        return [];

//        return ArrayHelper::map(
//            self::find()
//                ->where(
//                    ['AND',
//                        ['!=', 'name', ''],
//                        [
//                            'OR',
//                            ['final_destination' => '1'],
//                            ['final_destination' => (int)1],
//                        ]
//                    ]
//                )
//                ->orderBy('name')
//                ->asArray()
//                ->all(), 'id', 'name', 'f_first_bort'
//        );
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
            'name', 'id'
        );
    }

    /**
     * ПАКЕТНОЕ УДАЛЕНИЕ из справочник WH по ИД
     * =
     * Используем Подготовленный массив
     * -
     *
     * @param $array_ids
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

    /**
     * Этот ИД является ЦС ?
     * =
     *
     * @param $id
     * @return bool
     */
    public static function is_cs($id)
    {
        $xx = static::find()
            ->where(['id' => (int)$id])
            ->asArray()
            ->one();


        if (isset($xx['final_destination']) && (int)$xx['final_destination'] === 1) {
            return true;
        }

        return false;
    }

    ///
    ///
    ///

    static function Array_cs($id)
    {
        return ArrayHelper::map(
            static::find()
                ->where(['parent_id' => (int)$id])
                ->orderBy('name')
                ->asArray()
                ->all(),
            'id', 'name'
        );
    }

    /**
     * Поиск по всей базе по трем полям (name, gos, bort)
     * =
     *
     * @param $name
     * @return array|ActiveRecord|null
     */
    static function findFirst_in_all_fields($name)
    {
        return self::find()->where(
            [
                'OR',
                ['name' => $name],
                ['nomer_borta' => $name],
                ['nomer_gos_registr' => $name],
            ]
        )->orderBy('name')->one();

    }

    /**
     * Поиск НЕ ПО ВСЕЙ базе, А только в разрезе ОДНОГО АВтоПАРКА по трем полям (name, gos, bort)
     * =
     *
     * @param $name
     * @param $park_id
     * @return array|ActiveRecord|null
     */
    static function findFirst_in_park($name, $park_id)
    {
        return self::find()->where(
            ['AND',
                ['parent_id' => (int)$park_id],
                [
                    'OR',
                    ['name' => $name],
                    ['nomer_borta' => $name],
                    ['nomer_gos_registr' => $name],
                ]
            ]
        )->orderBy('name')
            ->one();

    }

    /**
     * Теперь переход на то...Поиск по всей базе по трем полям (name, gos, bort)
     * =
     *
     * @param $name
     * @return array
     */
    static function getFirst_in_all_fields($name)
    {
        $xx1 = ArrayHelper::map(
            self::find()->where(
                ['name' => $name]
            )->orderBy('name')->asArray()->one(), 'id', 'name'
        );
        if (isset($xx1)) return $xx1;

        ////
        $xx2 = ArrayHelper::map(
            self::find()->where(
                ['nomer_borta' => $name]
            )->orderBy('nomer_borta')->asArray()->one(), 'id', 'nomer_borta'
        );
        if (isset($xx1)) return $xx2;

        ////
        $xx3 = ArrayHelper::map(
            self::find()->where(
                ['nomer_gos_registr' => $name]
            )->orderBy('nomer_gos_registr')->asArray()->one(), 'id', 'nomer_gos_registr'
        );
        if (isset($xx1)) return $xx3;

        return [];
    }

    /**
     * Деактивация любого случая, кроме нашего автопарка + гос_номер
     * =
     * @param $park_id
     * @param $gos_number
     * @return bool
     */
    static function Deactivate_by_id($park_id, $gos_number)
    {
        ///
        /// Находим Сочетание, которое надо Деактивировать
        ///
        $model = self::find()
            ->where(
                ['AND',
                    ['<>', 'parent_id', $park_id],
                    ['OR',
                        ['==', 'name', $gos_number],
                        ['==', 'nomer_gos_registr', $gos_number],
                    ]
                ])
            ->one();

        if (!isset($model)) {
            return false;
        }

        $model->deactive = 1; // Ставим признак "Деактивировать"

        If (!$model->save(true)) {
            //ddd($model->errors);
            return false;
        }

        return true;
    }

    /**
     * Создание нового элемента в спарвочник
     * =
     * @param $park_id
     * @param $gos_number
     * @return bool
     */
    static function Create_by_Park_and_Gos($park_id, $gos_number)
    {
        ///
        /// Находим Сочетание, которое надо Деактивировать
        ///
        $next_id = self::setNext_max_id(); // 5030
        $model = new  self();
        $model->id = (int)$next_id;

        $model->parent_id = (int)$park_id;
        $model->name = (string)$gos_number;
        $model->nomer_gos_registr = (string)$gos_number;
        $model->final_destination = (int)1;
        $model->deactive = (int)0;
        $model->f_first_bort = (int)0; // GOS-first
        $model->delete_sign = (int)0;
        $model->date_create = Date('d.m.Y H:i:s', strtotime("now"));
        $model->create_user_id = Yii::$app->user->identity->id;


        if (!$model->save(true)) {
            ddd($model->errors);
            return false;
        }

        return true;
    }


    /**
     * Переименование на новый ГОС+NAME
     * =
     * @param $id
     * @param $name_gos
     * @param $do_timestamp
     * @return array
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    static function Rename_Name_GOS($id, $name_gos, $do_timestamp)
    {
        /// Находим Сочетание, которое надо Деактивировать
        $model = static::find()
            ->where(['id' => (int)$id])
            ->one();
        //
        $old_name = trim($model->name);
        //
        $model->name = (string)$name_gos;
        $model->nomer_gos_registr = (string)$name_gos;

        //
        $str = $model->tx;
        $model->tx = $str . "\n" . 'ГОС снят: ' . $old_name . ', установлен: ' . $name_gos . ' ' . date('(d.m.Y)', $do_timestamp);

        //ddd($model);

        if (!$model->update(false)) {
            //ddd($model);
            return ($model->errors);
        }
        return ['OK'];
    }

    /**
     * Получить одно Название по Ид
     *-
     * @param $id
     * @return mixed|string
     */
    static function Name_from_id($id)
    {
        $model = static::find()
            ->select(['name'])
            ->where(['=', 'id', (int)$id])
            ->one();

        if (isset($model->name) && !empty($model->name)) {
            return $model->name;
        }
        return '';
    }

    /**
     * 1. Получить  ИдC по НАЗВАНИЮ БОРТА
     *-
     * 2. или по ИМЕНИ
     * 3. Надо после этого проверять: Ведется ли учет по ПО БОРТУ
     *-
     *
     * @param $id_ap
     * @param $bort_name
     * @return array|ActiveRecord
     */
    static function findAll_ids_by_AP_and_BORT($id_ap, $bort_name)
    {
        return ArrayHelper::getColumn(
            static::find()
                ->where(
                    ['AND',
                        ['OR',
                            ['parent_id' => $id_ap],
                            ['parent_id' => (int)$id_ap],
                        ],
                        ['OR',
                            ['name' => $bort_name],
                            ['nomer_borta' => $bort_name],
                        ]
                    ]
                )
                //->orderBy(['name ASC'])
                ->all(), 'id');
    }

    /**
     * 1. Получить  Ид-C по НАЗВАНИЮ ГОС
     *-
     * 2. или по ИМЕНИ
     * 3. Надо после этого проверять: Ведется ли учет по ПО ГОСУ
     *-
     *
     * @param $id_ap
     * @param $gos_name
     * @return array|ActiveRecord
     */
    static function findAll_ids_by_AP_and_GOS($id_ap, $gos_name)
    {
        return ArrayHelper::getColumn(
            static::find()
                ->where(
                    ['AND',
                        ['OR',
                            ['parent_id' => $id_ap],
                            ['parent_id' => (int)$id_ap],
                        ],
                        ['OR',
                            ['name' => $gos_name],
                            ['nomer_gos_registr' => $gos_name],
                        ],
                    ]
                )
                ->all(), 'id');
    }


    /**
     * Узнать. Учет ведется для этого ПЕ
     * -
     * ПО ГОСУ или ПО БОРТУ
     *-
     * @param $id_pe
     * @return array|ActiveRecord
     * @throws \Exception
     */
    static function is_f_first_bort_PE($id_pe)
    {
        return ArrayHelper::getValue(
            static::find()
                ->where(['id' => $id_pe])
                ->one(), 'f_first_bort');
    }

    /**
     * Получить Ид только по ДВУМ ИМЕНАМ ВНУТРИ ОДНОГО ПАРКА
     * -
     * @param $name_old
     * @param $name_new
     * @param $ideal_id_AP
     * @param $ideal_id_PE
     * @return array
     */
    static function find_Wrong_Id_by_Names($name_old, $name_new, $ideal_id_AP, $ideal_id_PE)
    {
        return ArrayHelper::getColumn(
            Sprwhelement::find()
                ->where(
                    ['AND',
                        ['OR',
                            ['name' => (string)$name_old],
                            ['name' => (string)$name_new]
                        ],
                        ['parent_id' => $ideal_id_AP], // Обязательно проверяем внутри одного АП
                        //['id' => $ideal_id_PE],
                    ]
                )
                ->all(), 'id');

    }

    /**
     * Получить Ид-C только по ИМЕНИ_N_GOS + ideal.PARENT_ID +ideal_id
     * -
     * @param $name
     * @param $ideal_parent_id
     * @param $ideal_id
     * @return array|ActiveRecord
     * @throws \Exception
     */
    static function find_Wrong_All_Id($name, $ideal_parent_id, $ideal_id)
    {
        return ArrayHelper::getColumn(static::find()
            ->where(
                ['AND',
                    ['name' => (string)$name],
                    ['parent_id' => (int)$ideal_parent_id], // Обязательно проверяем внутри одного АП
                    ['!=', 'id', (int)$ideal_id]
                ]
            )
            ->all(), 'id');
    }

    /**
     * Удалить все IdS из массива  deleteAll_ids
     * =
     * @param $array_ids
     * @return bool
     */
    public static function deleteAll_ids($array_ids)
    {
        $model = static::updateAll(
            ['delete_sign' => (int)1],
            ['IN', 'id', $array_ids],
            [
                'upsert' => false,
                'multi' => true
            ]
        );

        return $model;
    }


}
