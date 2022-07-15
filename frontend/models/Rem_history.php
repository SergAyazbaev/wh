<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;


/**
 * Class Spr_glob
 * @property float id
 * @package app\models
 */
class Rem_history extends ActiveRecord
{
    public $array_decision;

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'rem_history'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'bar_code',
            'short_name',

            'diagnoz',
            'decision',

            'list_details',

            'user_name',
            'user_id',
            'user_group',
            'user_ip',

            'rem_user_name',
            'rem_user_group',
            'rem_user_id',
            'rem_user_ip',

            'dt_create',
            'dt_create_timestamp',

            'dt_rem_timestamp',

            'update_points',

            'mts_user_id',
            'mts_user_name',

            'array_decision',
            'num_busline',

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'id' => '№',

            'bar_code' => 'Код',

            'short_name' => 'Название',

            'diagnoz' => 'Диагноз',
            'decision' => 'Решение',

            'list_details' => 'Замена компонентов',

            'user_name' => 'Автор',
            'user_group' => 'Доступ',
            'user_id' => 'userid',
            'user_ip' => 'user_ip',

            'rem_user_name' => 'Мастер',
            'rem_user_group' => '',
            'rem_user_id' => '',
            'rem_user_ip' => '',


            'dt_create' => 'Дата',
            'dt_create_timestamp' => 'Дата заявки',

            'dt_rem_timestamp' => 'Дата ремонта',

            'mts_user_id' => 'MTC',
            'mts_user_name' => 'MTC',

            'sprwhelement.name' => 'MTC',
            'barcode_pool_turnover' => '?!',
            'barcode_pool_turnover_alltime' => '!!!',

            'array_decision' => 'Решение',
            'num_busline' => 'Марш',

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

                'bar_code',
                'short_name',
                'diagnoz',

                'decision',

                'list_details',

                'update_points',

                'mts_user_id',
                'mts_user_name',

                'array_decision', //OK

                'num_busline',
            ],
                'safe'
            ],


            [['id'], 'unique'],

            [
                [
                    'id',
                    'bar_code',
                    'diagnoz',
                ],
                'required',
                'message' => 'Заполнить...',
            ],

            [[
                'id',
                'user_id',

                'mts_user_id',
                "rem_user_group",
                "rem_user_id",

            ],
                'integer'
            ],

            [['num_busline'], 'string', 'max' => 5],


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


            [[
                'decision',
                'list_details'], 'default', 'value' => ''
            ]

        ];
    }

    /**
     * setNext_max_id()
     * =
     *
     * @return int
     */
    public static function setNext_max_id()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;

    }

    ///
    public function getSpr_glob_element()
    {
        return $this->hasMany(Spr_glob_element::className(), ['parent_id' => 'id']);
    }

    ///
    public function getSprwhelement()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'mts_user_id']);
    }

    ///
    public function getBarcode_pool()
    {
        return $this->hasOne(Barcode_pool::className(), ['bar_code' => 'bar_code']);
    }

    /**
     * Модель одной накладной
     * =
     * where(['id'=>(integer) $id])->one()
     * -
     *
     * @param $id
     * @return array|null|ActiveRecord
     */
    static function findModelDouble($id)
    {
        return static::find()
            ->where([
                'id' => (int)$id
            ])
            ->one();
    }

    /**
     * Возвращает массив Ids = МТС
     * -
     *
     * @param $dt_create
     * @return array
     */
    static function ArrayUniq_mts_user_id($dt_create)
    {
        $dt_start = strtotime(date('d.m.Y 00:00:00', strtotime($dt_create . ' -30 days')));
        $dt_stop = strtotime(date('d.m.Y 23:59:59', strtotime($dt_create)));

        $arr = self::find()
            ->where(['AND',
                ['>=', 'dt_create_timestamp', $dt_start],
                ['<=', 'dt_create_timestamp', $dt_stop],
                ['!=', 'rem_user_id', '']
            ])
            ->distinct('mts_user_id');


        $arr2 = [];
        foreach ($arr as $item) {
            $arr2[] = (int)$item;
        }

        $arr2 = array_unique($arr2);

        return $arr2;
    }


    /**
     * Возвращает массив Ids = REM
     * -
     *
     * @param $dt_create
     * @return array
     */
    static function ArrayUniq_rem($dt_create)
    {
        $dt_start = strtotime(date('d.m.Y 00:00:00', strtotime($dt_create . ' -30 days')));
        $dt_stop = strtotime(date('d.m.Y 23:59:59', strtotime($dt_create)));

        $arr = self::find()
            ->where(['AND',
                ['>=', 'dt_rem_timestamp', $dt_start],
                ['<=', 'dt_rem_timestamp', $dt_stop],
                ['!=', 'rem_user_id', '']
            ])
            ->distinct('rem_user_id');

        return $arr;
    }


    /**
     * Возвращает массив Ids = REM
     * -
     *
     * @return array
     */
    static function ArrayUniq_cvb()
    {
        $arr = ArrayHelper::map(self::find()
            ->select(['short_name'])
            ->where(['AND',
                ['<>', 'short_name', ''],
                ['!=', 'short_name', null]
            ])
            ->orderBy(['short_name'])
            ->all(), 'short_name', 'short_name');

        $arr = array_filter($arr);
        return $arr;
    }


    /**
     * Возвращает массив Ids = REM
     * -
     *
     * @param $dt_between
     * @return array
     */
    static function ArrayUniq_rem_name($dt_between = '')
    {
        $arr = ArrayHelper::map(self::find()
            ->select(['rem_user_name'])
            ->where(['AND',
                ['<>', 'rem_user_name', ''],
                ['!=', 'mts_user_name', null]
            ])
            ->orderBy(['rem_user_name'])
            ->all(), 'rem_user_name', 'rem_user_name');
        $arr = array_filter($arr);
        return $arr;
    }

    /**
     * Возвращает массив Ids = REM
     * -
     *
     * @param $dt_between
     * @return array
     */
    static function ArrayUniq_rem_mtsname($dt_between = '')
    {
        $arr = ArrayHelper::map(self::find()
            ->select(['mts_user_name'])
            ->where(['AND',
                ['<>', 'mts_user_name', ''],
                ['!=', 'mts_user_name', null]
            ])
            ->orderBy(['mts_user_name'])
            ->all(), 'mts_user_name', 'mts_user_name');

        $arr = array_filter($arr);
        return $arr;
    }


    /**
     * Возвращает массив с неисправностями
     * -
     *
     * @param $dt_create
     * @return int
     * @throws \yii\mongodb\Exception
     */
    static function countDecision_all($dt_create)
    {
        $dt_start = strtotime(date('d.m.Y 00:00:00', strtotime($dt_create . ' -6 days')));
        $dt_stop = strtotime(date('d.m.Y 23:59:59', strtotime($dt_create)));

        $arr = self::find()
            ->where(['AND',
                ['>=', 'dt_create_timestamp', $dt_start],
                ['<=', 'dt_create_timestamp', $dt_stop],
                ['!=', 'decision', '']
            ])
            ->count('decision');

        return $arr;
    }

    /**
     * Возвращает массив с неисправностями
     * -
     *
     * @param $dt_create
     * @param string $period_str
     * @return array
     */
    public static function ArrayUniq_decision($dt_create, $period_str = ' -6 days')
    {
        $dt_start = strtotime(date('d.m.Y 00:00:00', strtotime($dt_create . $period_str)));
        $dt_stop = strtotime(date('d.m.Y 23:59:59', strtotime($dt_create)));
        //ddd(date('d.m.Y',$dt_stop));

        return self::find()
            ->where(['AND',
                ['>=', 'dt_create_timestamp', $dt_start],
                ['<=', 'dt_create_timestamp', $dt_stop],
                ['!=', 'decision', '']
            ])
            ->distinct('decision');
    }

    /**
     * Возвращает массив с БОЛЕЕ подробными неисправностями. Алгоритм дыделения Части стоки в массив
     * -
     *
     * @param $array
     * @return array
     */
    static function ArrayTranslator($array)
    {
        $array2 = [];
        foreach ($array as $item_arr) {
            $res = explode('.', $item_arr);
            foreach ($res as $re2) {
                $array2[] = trim($re2);
            }
        }
        //ddd($array2);

        // Пустышки удаляет из массива
        $array2 = array_filter($array2);
        // Двойники удаляет из массива
        $array2 = array_unique($array2);

        return $array2;
    }


    /**
     * Возвращает количество снятых УСРОЙСТВ за день, одним МТС
     * -
     *
     * @param $id_mts
     * @param $array_dt_timestamp
     * @return int
     * @throws \yii\mongodb\Exception
     */
    static function countMts_by_days($id_mts, $array_dt_timestamp)
    {
        $dt_start = strtotime(date('d.m.Y 00:00:00', strtotime($array_dt_timestamp)));
        $dt_stop = strtotime(date('d.m.Y 23:59:59', strtotime($array_dt_timestamp)));

        return self::find()
            ->where(['AND',
                ['OR',
                    ['==', 'mts_user_id', (string)$id_mts],
                    ['==', 'mts_user_id', (int)$id_mts],
                ],

                ['>=', 'dt_create_timestamp', $dt_start],
                ['<=', 'dt_create_timestamp', $dt_stop]
            ])
            ->count();
    }

    /**
     * Возвращает количество снятых УСРОЙСТВ за день, одним МТС
     * -
     *
     * @param $id_rem
     * @param $array_dt_timestamp
     * @return int
     * @throws \yii\mongodb\Exception
     */
    static function countRem_by_days($id_rem, $array_dt_timestamp)
    {
        $dt_start = strtotime(date('d.m.Y 00:00:00', strtotime($array_dt_timestamp)));
        $dt_stop = strtotime(date('d.m.Y 23:59:59', strtotime($array_dt_timestamp)));

        return self::find()
            ->where(['AND',
                ['==', 'rem_user_id', $id_rem],

                ['>=', 'dt_rem_timestamp', $dt_start],
                ['<=', 'dt_rem_timestamp', $dt_stop]
            ])
            ->count();
    }


    /**
     * Возвращает количество снятых УСРОЙСТВ за день, одним МТС
     * -
     *
     * @param $id_decision
     * @param $dt_create
     * @param string $period_str
     * @return int
     * @throws \yii\mongodb\Exception
     */
    static function countDecision_by_six_days($id_decision, $dt_create, $period_str = ' -6 days')
    {
        $dt_start = strtotime(date('d.m.Y 00:00:00', strtotime($dt_create . $period_str)));
        $dt_stop = strtotime(date('d.m.Y 23:59:59', strtotime($dt_create)));

        return self::find()
            ->where(['AND',
                ['>=', 'dt_create_timestamp', $dt_start],
                ['<=', 'dt_create_timestamp', $dt_stop],
                ['like', 'decision', $id_decision]
            ])
            ->count('decision');
    }

    /**
     * Модель одной накладной BAR_CODE
     * =
     * -
     *
     * @param $bar_code
     * @return array|null|ActiveRecord
     */
    static function findModel_barcode($bar_code)
    {
        return static::find()
            ->where([
                '==', 'bar_code', (string)$bar_code
            ])
            ->one();
    }


    /**
     * ПАРВОЧНИК decision - РЕШЕНИЕ
     * =
     *
     * @return array|null|ActiveRecord
     */
    static function findDecision_all()
    {
      $one_month = strtotime('now -15 days');
      $all_month = strtotime('now -50 days');
      $arr = ArrayHelper::map(static::find()
            ->select(['id', 'decision'])
            ->where(['>=', 'dt_create_timestamp', $all_month])
            ->orderBy('decision asc')
            ->all(), 'id', 'decision');

        // Пустышки удаляет из массива
        $arr = array_filter($arr);
        // Двойники удаляет из массива
        $arr = array_unique($arr);

        //ddd($arr);
        return $arr;
    }

    /**
     *  Поиск фактического Массива Штрихкодов  по Массиву штрихкодов на входе
     * =
     * @param $array
     * @return mixed
     */
    public static function findArray_by_Array($array)
    {
        ///
        $array1 = self::find()
            ->select(['bar_code'])
            ->where(['in', 'bar_code', $array])
            ->asArray()
            ->all();

        $array_result = [];
        ///
        foreach ($array1 as $item) {
            $array_result[(string)$item['bar_code']] = $item['bar_code'];
        }

        return $array_result;
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
        //ddd($this);

        $xx = $this->update_points;
        //        ddd(Yii::$app->user->identity);


        $xx[] = [
            'dt_update' => date('d.m.Y H:i:s', strtotime('now')),
            'dt_update_timestamp' => strtotime('now'),
            'user_ip' => Yii::$app->request->getUserIP(),
            'user_id' => Yii::$app->user->identity->id,
            'user_name' => Yii::$app->user->identity->username,
            'user_group_id' => Yii::$app->user->identity->group_id,
            'user_role' => Yii::$app->user->identity->role,
        ];


        //ddd($xx);

        $this->update_points = $xx;

        return true;
        //return $xx;

    }


}
