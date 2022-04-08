<?php

namespace frontend\models;

use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use yii\mongodb\ActiveRecord;
use Yii;


/**
 * @property integer id
 * @property integer parent_id
 * @property string n_bort
 * @property string n_gos
 * @property string old_bort
 * @property string old_gos
 *
 * @property integer user_id
 * @property integer do_timestamp
 * @property integer dt_cr_timestamp
 * @property DatePicker dt_create
 *
 * @property integer doc_cr
 * @property string doc_num
 * @property string tx
 **/
class Sprwhelement_change extends ActiveRecord
{

    public $dt_create;
    public $doc_cr;
    public $wh_top, $wh_element;

    public $add_text;//


    const SCENARIO_FROM_EXCEL = 'fromexcel';

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'sprwh_el_change',
        ];
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_FROM_EXCEL] = [
            'parent_id',

            'n_bort',
            'n_gos',

            'old_bort',
            'old_gos',

            'user_id',
            'tx',

            'user_ip',
            'user_id',
            'user_name',
            'user_do_timestamp',

        ];
        return $scenarios;
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

            'n_bort',
            'n_gos',

            'old_bort',
            'old_gos',

            'do_timestamp',
            'dt_cr_timestamp',

            'doc_cr',
            'doc_num',

            'user_id',
            'tx',

            'user_ip', 'user_id', 'user_name',
            'user_do_timestamp',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[
                'id',
                'wh_top',
                'wh_element',
            ], 'required', 'message' => 'Заполнить...', 'on' => self::SCENARIO_FROM_EXCEL],

            [[
                'id',
                'wh_top',
                'wh_element',
                'tx',

            ], 'safe', 'on' => self::SCENARIO_FROM_EXCEL],


            [[
                '_id',
                'id',
                'parent_id',

                'old_bort',
                'old_gos',

                'user_id',

                'do_timestamp',
                'dt_create', ///
                ///
                'dt_cr_timestamp',
                'doc_cr',

                'doc_num',
                'tx',

                'wh_top',///
                'wh_element',///
                ///
                //
                'user_ip',
                'user_id',
                'user_name',
                'user_do_timestamp',

            ], 'safe'],

            ///
            [['id'], 'unique'],

            ///
            [[
                'id',
                'parent_id',
                'do_timestamp',
                'dt_cr_timestamp',
            ], 'integer'],


            //
            [['n_bort', 'n_gos'], 'default', 'value' => ''],


            /// Уникальность четырех полей относительно
            [['n_bort'], 'unique', 'targetAttribute' => [
                'n_bort',
                'parent_id',
                'do_timestamp'
            ], 'message' => 'Двойник: парк + номер + время '
            ],
            [['n_gos'], 'unique', 'targetAttribute' => [
                'n_gos',
                'parent_id',
                'do_timestamp'
            ], 'message' => 'Двойник: парк + номер + время '
            ],
            [['old_bort'], 'unique', 'targetAttribute' => [
                'old_bort',
                'parent_id',
                'do_timestamp'
            ], 'message' => 'Двойник: парк + номер + время '
            ],
            [['old_gos'], 'unique', 'targetAttribute' => [
                'old_gos',
                'parent_id',
                'do_timestamp'
            ], 'message' => 'Двойник: парк + номер + время '
            ],

            //
            [['n_bort', 'old_bort'],
                'integer',
                'integerOnly' => true,
                'min' => 1,
                'max' => 99999,
                'tooSmall' => 'Мало',
                'tooBig' => 'Много'],

            ///MASK
            [['n_bort', 'old_bort'], 'match',
                'pattern' => '/^([0-9]{1,7})$/i',
                'message' => 'Не соотвествует маске'],
            ///MASK
            [['n_gos', 'old_gos'], 'match',
                'pattern' => '/^([A-Z]{0,1}[0-9]{3,4}[A-Z]{2,3}[0-9]{0,2})$/i',
                'message' => 'Не соотвествует маске'],


            [[
                'wh_element',
                'dt_create',
                'doc_cr',
                'doc_num',
            ], 'required', 'message' => 'Заполнить...'],
            //                ,'on'=>self::SCENARIO_CLOSE_TICKET],

            ///
            [[
                'old_gos'
            ], 'my_required_gos_bort'], // проверка на заполнение одного из двух полей
            ///
//            [[
//                'n_gos', 'n_bort',
//            ], 'my_required_gos_bort_new'], // проверка на заполнение одного из двух полей


            ///UPPER
            [['n_gos', 'old_gos', 'n_bort', 'old_bort'], 'filter', 'filter' => 'strtoupper'],
            [['n_gos', 'n_bort', 'dt_create', 'doc_cr'], 'filter', 'filter' => 'trim'],


            //
            [['user_ip'], 'default', 'value' => function () {
                //return Yii::$app->request->getUserIP();
                return Yii::$app->request->userIP;
            }],
            [['user_id'], 'default', 'value' => function () {
                return Yii::$app->user->identity->id;
            }],
            [['user_name'], 'default', 'value' => function () {
                return Yii::$app->user->identity->username;
            }],
            [['user_do_timestamp'], 'default', 'value' => function () {
                return strtotime('now');
            }],


        ];
    }

    /**
     * Проверка на ОБЯЗАТЕЛЬНОЕ заплнение идного из двух полей
     * -
     * @param $attribute_name
     * @param $params
     * @return bool
     */
    public function my_required_gos_bort($attribute_name, $params)
    {
        if (empty($this->old_bort) && empty($this->old_gos)) {
            //$this->addError($attribute_name, 'Обязательно заполнить ГОС или БОРТ');
            $this->addError('old_bort', 'Обязательно заполнить ГОС или БОРТ');
            $this->addError('old_gos', 'Обязательно заполнить ГОС или БОРТ');
            return false;
        }
        return true;
    }

    public function my_required_gos_bort_new($attribute_name, $params)
    {

        if (empty($this->n_bort) && empty($this->n_gos)) {
            //$this->addError($attribute_name, 'Обязательно заполнить ГОС или БОРТ');
            $this->addError('n_gos', 'Обязательно заполнить ГОС или БОРТ');
            $this->addError('n_bort', 'Обязательно заполнить ГОС или БОРТ');
            return false;
        }
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
            'parent_id' => 'parent',

            'wh_top' => 'АП',
            'wh_element' => 'ПЕ',

            'n_bort' => 'Нов.Борт',
            'n_gos' => 'Нов.Гос',
            'old_bort' => 'Старый.Борт',
            'old_gos' => 'Старый.Гос',

            'tx' => 'Примечание',

            'dt_create' => 'Дата операции',
            'do_timestamp' => 'Дата операции',

            'doc_cr' => 'Дата док',
            'dt_cr_timestamp' => 'Дата документа',

            'doc_num' => 'Номер документа',

            'user_id' => 'Автор',
            'user_name' => 'Автор',
            'user_do_timestamp' => 'Дата занесения',
        ];
    }


    /**
     * @return
     */
    public function getSprwhelement_many_many()
    {
        return $this->hasProperty(Sprwhelement::className(), ['id' => 'parent_id'], '');

//            Sprwhelement::className(), [ 'id' => 'parent_id' ] ) ;
    }

    /**
     * @param $time_point
     * @return
     */
    public function getSprwhelement_timepoint($time_point)
    {
        return $this->hasMany(Sprwhelement::className(), ['id' => 'parent_id'])
            ->andOnCondition(['>', 'do_timestamp', $time_point]);
        //;
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_one()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'parent_id']);
    }

    /**
     * @return ActiveQueryInterface
     */
    public function getSprwhelement()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'parent_id']);
    }


    /**
     * setNext_max_id()
     * =
     * @return int
     */
    public static function setNext_max_id()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;
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
     * @param $id
     * @return array|null|ActiveRecord
     */
    static function findModelDouble($id)
    {
        return static::find()
            ->where(['id' => (int)$id])
            ->one();
    }


    /**
     * Находим PARENT_ID через ID
     * -
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    static function find_parent_id($id)
    {
        return ArrayHelper::getValue(static::find()
            ->where(['id' => (int)$id])->one(), 'parent_id');
    }


    /**
     * RECURCIA. ГОС
     * -
     * Конечный результат передается в глобальную переменныую ->  global $xx_str
     * =
     * @param $id_AP
     * @param $name_gos
     * @param $timestamp_deadline
     *
     * @return bool
     * @throws \Exception
     */
    public static function recurcia_findElemGOS($id_AP, $name_gos, $timestamp_deadline)
    {
        global $xx_str; // Важно указывать и в источнике и тут!

        ///
        $old = ArrayHelper::getValue(static::find()
            ->where(
                ['AND',
                    ['parent_id' => (int)$id_AP], // Только! Для одного АвтоПарка
                    ['>', 'do_timestamp', (int)$timestamp_deadline], // ищем назад по времени. Берем кусок с крайней даты до настоящего времени
                    ['n_gos' => (string)$name_gos], // находим новый номер, а по нему находим СТАРЫЙ НОМЕР ДО ЗАМЕНЫ
                ]
            )
            ->one(), 'old_gos');

        //'old_gos' => '083YTA02'
        if (isset($old) && !empty($old)) {
            $xx_str = $old;
            //echo '<br>'.' GOS = '.$model->n_gos.' time = '.date('d.m.Y', $model->do_timestamp ). ' OLD_GOS = '.$model->old_gos;
            return Sprwhelement_change::recurcia_findElemGOS($id_AP, (string)$old, $timestamp_deadline);
        }

        return false;
    }


    /**
     * RECURCIA. БОРТ
     * -
     * Конечный результат передается в глобальную переменныую ->  global $xx_str
     * =
     * @param $id_AP
     * @param $name_bort
     * @param $timestamp_deadline
     *
     * @return bool
     * @throws \Exception
     */
    public static function recurcia_findElemBORT($id_AP, $name_bort, $timestamp_deadline)
    {
        global $xx_str; // Важно указывать и в источнике и тут!

        ///
        $model = static::find()
            ->where(
                ['AND',
                    ['parent_id' => (int)$id_AP], // Только! Для одного АвтоПарка
                    ['>', 'do_timestamp', (int)$timestamp_deadline], // ищем назад по времени. Берем кусок с крайней даты до настоящего времени
                    ['n_bort' => (string)$name_bort], // находим новый номер, а по нему находим СТАРЫЙ НОМЕР ДО ЗАМЕНЫ
                ]
            )
            ->one();
        //
        if (isset($model->old_bort) && !empty($model->old_bort)) {
            $xx_str = $model->old_bort;
            //echo '<br>'.' n_bort = '.$model->n_bort.' time = '.date('d.m.Y', $model->do_timestamp ). ' OLD_BORT = '.$model->old_bort;
            return Sprwhelement_change::recurcia_findElemBORT($id_AP, (string)$model->old_bort, $timestamp_deadline);
        }

        return true;
    }


    /**
     * Обработчик входящего массива. На выходе массив с корректными названиями НОМЕРОВ ГОС и БОРТ на дату
     * RECURCIA.
     * -
     * Конечный результат передается в глобальную переменныую-массив ->  global $ids_changes
     * =
     * @param $id_AP
     * @param $name_gos
     * @return bool
     * @throws \Exception
     */
    public static function recurcia_find_Ids_array($id_AP, $name_gos)
    {
        global $ids_changes; // Важно указывать и в источнике и тут!
        $ids_changes = [];

        ///Name Element
        $arr_rez1 = ArrayHelper::getColumn(Sprwhelement::find()
            ->where(
                ['AND',
                    ['parent_id' => (int)$id_AP], // Только! Для одного АвтоПарка
                    ['name' => (string)$name_gos], // находим новый номер, а по нему находим СТАРЫЙ НОМЕР ДО ЗАМЕНЫ
                ]
            )
            ->all(), 'id');
//        dd($arr_rez1);

        ///N_GOS Change
        $arr_rez2 = ArrayHelper::getColumn(Sprwhelement_change::find()
            ->where(['n_gos' => (string)$name_gos])->all(), 'parent_id');
        ///dd($arr_rez2);

        ///
        $ids_changes = array_merge($ids_changes, $arr_rez1);
        $ids_changes = array_merge($ids_changes, $arr_rez2);
        array_filter($ids_changes); // Удалить пустышки
        $ids_changes = array_unique($ids_changes);// Удалить двойники

        //ddd($ids_changes);


        //'old_gos' => '083YTA02'
        if (isset($old) && !empty($old)) {
            $ids_changes[] = $old;
            //echo '<br>'.' GOS = '.$model->n_gos.' time = '.date('d.m.Y', $model->do_timestamp ). ' OLD_GOS = '.$model->old_gos;
            //return Sprwhelement_change::recurcia_find_Ids_array($id_AP, (string)$old, $timestamp_deadline);
            return Sprwhelement_change::recurcia_find_Ids_array($id_AP, (string)$old);
        }

        return $ids_changes;
    }

//    /**
//     * Обработчик входящего массива. На выходе массив с корректными названиями НОМЕРОВ ГОС и БОРТ на дату
//     * RECURCIA.
//     * -
//     * @param $timestamp_deadline
//     * @return array
//     */
//    public static function array_cahges_by_timestamp($timestamp_deadline)
//    {
//        // ищем назад по времени. Берем кусок с крайней даты до настоящего времени
//        $model = static::find()
//            ->where(['>', 'do_timestamp', (int)$timestamp_deadline])
//            ->all();
//
//        $arr = [];
//        foreach ($model as $key => $item) {
//            //   'do_timestamp' => 1556820001
//            //                'old_gos' => '768BO02'
//            //                'n_gos' => '559LH02'
//            //                'parent_id' => 4440
//
//            $arr[$item['parent_id']][] = [
//                'datetime' => $item['do_timestamp'],
//                'parent_id' => $item['parent_id'],
//                'old_gos' => $item['old_gos'],
//                'n_gos' => $item['n_gos'],
//            ];
//        }
//        unset($model);
//
//        return $arr;
//    }

    /**
     * Свежая
     * Находим ВСЕ АКТУАЛЬНЫЕ Значения на заданную дату!!!
     * -
     *    MЕНЬШЕ-OLD, Больше-NEW.
     *    они сливаются вместе и не конфликтуют
     *
     * @param $time_point
     * @return mixed
     */
    public static function array_Actual_Ids_and_GOS($time_point)
    {
        $array_ids_oldgos = [];
        $array_ids_oldgos_min = [];
        $array_ids_oldgos_max = [];

//        dd(date('d.m.Y H:i:s', $time_point));

        ///
        /// Все что
        ///  MЕНЬШЕ-OLD, Больше-NEW.
        ///  Разные поля в разные направления!!!
        $array_ids_oldgos_min = ArrayHelper::map(Sprwhelement_change::find()
            ->select(['parent_id', 'old_gos', 'do_timestamp'])
            ->where(
            ///  MЕНЬШЕ-OLD
                ['<', 'do_timestamp', (int)$time_point]
            )
            ->orderBy(['old_gos'])
            ->all(), 'parent_id', 'old_gos');

//        dd($time_point); ///1598896800
//        dd($array_ids_oldgos_min);
//        dd('----------------');
//        dd('----------------');
//        dd('----------------');
        //
        $array_ids_oldgos_max = ArrayHelper::map(Sprwhelement_change::find()
            ->select(['id', 'parent_id', 'n_gos', 'do_timestamp'])
            ->where(
            ///  Больше-NEW.
                ['>', 'do_timestamp', $time_point]
            )
            ->orderBy(['n_gos'])
            ->all(), 'parent_id', 'n_gos');
        //

//        dd($time_point); ///1598896800
//        ddd($array_ids_oldgos_max);

        //Теперь они сливаются вместе и не конфликтуют
        $array_ids = ($array_ids_oldgos_min + $array_ids_oldgos_max); ///!!!!!
        ///
//        dd(strtotime('now'));
//        dd($time_point);
//        dd(date('d.m.Y H:i:s', $time_point));
//        ddd(count($array_ids));
//        ddd($array_ids);

        unset($array_ids_oldgos_min);
        unset($array_ids_oldgos_max);

        return $array_ids;
    }

    /**
     * Свежая
     * Находим ВСЕ АКТУАЛЬНЫЕ Значения на заданную дату!!!
     * -
     *    MЕНЬШЕ-OLD, Больше-NEW.
     *    они сливаются вместе и не конфликтуют
     *
     * @param $time_point
     * @return mixed
     */
    public static function array_Defective_Ids($time_point)
    {
        $array_ids_oldgos = [];
        $array_ids_oldgos_min = [];
        $array_ids_oldgos_max = [];

//        dd(date('d.m.Y H:i:s', $time_point));

        ///
        /// Все что
        ///  MЕНЬШЕ-OLD, Больше-NEW.
        ///  Разные поля в разные направления!!!
        $array_ids_oldgos_min = ArrayHelper::map(Sprwhelement_change::find()
            ->select(['parent_id', 'n_gos', 'do_timestamp'])
            ->where(
            ///  MЕНЬШЕ-OLD
                ['<', 'do_timestamp', (int)$time_point]
            )
            ->orderBy(['old_gos'])
            ->all(), 'parent_id', 'n_gos'); //old_gos<>

//        dd($time_point); ///1598896800
//        dd($array_ids_oldgos_min);
//        dd('----------------');
//        dd('----------------');
//        dd('----------------');
        //
        $array_ids_oldgos_max = ArrayHelper::map(Sprwhelement_change::find()
            ->select(['id', 'parent_id', 'old_gos', 'do_timestamp'])
            ->where(
            ///  Больше-NEW.
                ['>', 'do_timestamp', $time_point]
            )
            ->orderBy(['n_gos'])
            ->all(), 'parent_id', 'old_gos'); //n_gos<>
        //

//        dd($time_point); ///1598896800
//        ddd($array_ids_oldgos_max);

        //Теперь они сливаются вместе и не конфликтуют
        $array_ids = ($array_ids_oldgos_min + $array_ids_oldgos_max); ///!!!!!
        ///
//        dd($time_point);
//        dd(date('d.m.Y H:i:s', $time_point));
//        dd(count($array_ids));
//        ddd($array_ids);

        unset($array_ids_oldgos_min);
        unset($array_ids_oldgos_max);

        return $array_ids;
    }


    /**
     * Находим ВСЕ Значения на заданную дату. For_TIME_POINT
     * -
     * @param $time_point
     * @param $list_ids
     * @return mixed
     */
    static function array_ids_changes_for_TIME_POINT($time_point, $list_ids)
    {
        //  Справочник Ids на эту дату
        //ОТЛОЖЕННАЯ загрузка частями
        foreach (static::find()->where(["AND",
                ['in', 'parent_id', $list_ids],
                ['<', 'do_timestamp', $time_point],
            ]
        )->orderBy(['do_timestamp ASC'])->each(50) as $customer) {
            //
            $arr_rez[$customer->parent_id][$customer->old_gos] = $customer->n_gos;
            //
            if (!empty($customer->n_bort)) {
                $arr_rez[$customer->parent_id][$customer->old_bort] = $customer->n_bort;
            }
        }

        // На выходе массив для каждого ИД ПЕ
        //.....
        // 1240 => [
        //        '158CD02' => '158DC02'
        //    ]

        return $arr_rez;
    }

    /**
     * Находим Единственное правильное Значение для данного АП+ПЕ + Дата(секунды)
     * -
     * @param $time_point
     * @param $PE_id
     * @return mixed
     */
    public static function Actual_Name_One($time_point, $PE_id)
    {
        //        dd(date('d.m.Y H:i:s',1506794401));
        //        dd(date('d.m.Y H:i:s',$time_point));
        ///
        $array_bolee = ArrayHelper::map(
            Sprwhelement_change::find()
                ->select(['id', 'parent_id', 'old_gos', 'do_timestamp'])
                ->where(
                    ["AND",
                        ['parent_id' => (int)$PE_id],
                        ['>', 'do_timestamp', $time_point]
                    ]
                )
                ->orderBy('do_timestamp DESC') ///!!!
                ->all(), 'parent_id', 'old_gos');
        ///
        $array_menee = ArrayHelper::map(
            Sprwhelement_change::find()
                ->select(['id', 'parent_id', 'n_gos', 'do_timestamp'])
                ->where(
                    ["AND",
                        ['parent_id' => (int)$PE_id],
                        ['<', 'do_timestamp', $time_point]
                    ]
                )
                ->orderBy('do_timestamp ASC') ///!!!
                ->all(), 'parent_id', 'n_gos');

        if (!empty($array_bolee)) {
            return $array_bolee;
        }
        if (!empty($array_menee)) {
            return $array_menee;
        }
        return $array_bolee + $array_menee;
    }

}
