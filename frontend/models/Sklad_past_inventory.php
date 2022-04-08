<?php

namespace frontend\models;

use common\models\User;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\mongodb\ActiveRecord;
use Yii;




class Sklad_past_inventory extends ActiveRecord
{

    public $add_text_to_inventory_am;
    public $add_text_to_inventory;

    public $erase_array;     //// Удаление строк ПАКЕТОМ

    /**
     * NAME COLLECTION
     * -
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'sklad_past_inventory'];
    }



    public function attributes()
    {

        return [
            '_id',
            'id',
            'wh_home_number',   // ид текущего склада

            'dt_create',
            'dt_update',

            'dt_start',
            'dt_start_timestamp',

            'dt_create_timestamp',
            'dt_update_timestamp',


            'group_inventory', // Группа ведомостей, причастных к ОДНОЙ Инвентаризации
            'group_inventory_name', //

            'wh_destination',
            'wh_destination_name',
            'wh_destination_element',
            'wh_destination_element_name',

            'array_tk_amort',
            'array_tk',
            'array_casual',

            'array_count_all',

            'prihod_num',
            'rashod_num',

        ];
    }


    /**
     * Создаю ТаймШТАМП для всех операций Создания накладных и Редактирования
     *
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [ 'dt_create_timestamp', 'dt_update_timestamp' ],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [ 'dt_update_timestamp' ],
                ]
            ]

        ];

    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [ ['wh_home_number'],
                'integer', 'min' => 1, 'message' => 'Id_Sklad ==0... ERROR!'],

            [[  'dt_create' ], 'date', 'format'=>'php:d.m.Y H:i:s'
            ],

            ['dt_create', 'default', 'value' => function()
            { return date('d.m.Y H:i:s', strtotime('now')); }
            ],



            [[
                'dt_create',

                'group_inventory',
                'group_inventory_name',

                'wh_home_number',  // ид текущего склада

                'wh_destination',
                'wh_destination_element',

                'array_tk_amort',
                'array_tk',
                'array_casual',
            ],
                'safe'
            ],


            [[
                'dt_create',
                'wh_destination',
                'wh_destination_element',
            ], 'required', 'message' => 'Заполнить...'
            ],

            [[
                'wh_destination',
                'wh_destination_element'
            ],  'integer'  ],


            [['_id'],  'unique'  ],
            [['id'],  'unique'  ],
            [['id'],  'integer' ],


            [['array_tk_amort'],    'default', 'value' => []  ],
            [['array_tk'],          'default', 'value' => []  ],
            [['array_casual'],      'default', 'value' => []  ],

        ];
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [

            '_id' => 'ID',
            'id' => '№',
            'group_inventory' => '№ группы',
            'group_inventory_name' => 'Название группы',


            'dt_create' => 'Дата создания',

            /// DOP Elements
            'dt_start' => 'Начало периода',
            'dt_create_timestamp' => 'Дата созд.',
            'dt_update_timestamp' => 'Дата ред.',

            'wh_home_number' => 'ID',

            'wh_destination' => 'Группа складов',
            'wh_destination_name' => 'Группа складов',
            'wh_destination_element' => 'Склад',
            'wh_destination_element_name' => 'Склад',


            'array_tk_amort' => 'Амортизация',
            'array_tk' => 'Списание',
            'array_casual' => 'Расходные материалы',

            'array_count_all' => 'Строк',  //'Всего строк',

            'comments' => 'Коменты',

            'array_tk_amort.prihod_num' => 'Приход',
            'array_tk_amort.rashod_num' => 'Расход',

            'array_tk.prihod_num' => 'Приход',
            'array_tk.rashod_num' => 'Расход',
        ];

    }


    /**
     * @param $id
     *
     * @return Sklad_past_inventory|null
     * @throws ExitException
     */
    public static function findModel( $id )
    {
        if ( ( $model = static::findOne( $id ) ) == null ) {
            throw new ExitException( 'Sklad_inventory. Find(). ERROR' );
        } else {
            return $model;
        }


    }

    /**
     * Поиск по заданной базе
     * where(['id'=>(integer) $id])->one()
     *
     * @param $id
     *
     * @return array|null|ActiveRecord
     * @throws ExitException
     */

    static function findModelDouble( $id )
    {
        if ( ( $model = static::find()->where( [ 'id' => (integer)$id ] )->one() ) !== null ) {
            return $model;
        }

        throw new ExitException('Sklad_past_inventory. findModelDouble(). Errror');
    }

    /**
     * Сортировка Массива по ГРУППАМ ТОВАРОВ
     * Для ОТЧЕТА В ЭКСЕЛ
     * -
     *
     * @param $array
     *
     * @return array
     */
    static function sort_array_pk( $array )
    {
        $sort_buf = [];
        foreach ( $array as $item ) {
            $sort_buf[ $item[ 'wh_tk' ] ][ $item[ 'wh_tk_element' ] ] = $item;
        }

        $array = [];
        foreach ( $sort_buf as $key => $item ) {
            foreach ( $item as $key2 => $item2 ) {
                $array[] = $item2;
            }
        }

        unset( $sort_buf );
        return $array;
    }

    /**
     * RELATIONS
     * -
     * @return ActiveQueryInterface
     */

    public function getUser()
    {
        return $this->hasOne(User::className(),
            ['group_id' => 'user_group_id']
        );
    }

    /**
     */
//    public function setDtCreateText()
//    {
//        $this->dt_create_timestamp = (int)strtotime($this->dt_create);
//    }


    public function DateToMinute($value = 0)
    {
        return (int)strtotime($value);
    }

    /**
     * Вернуть массив: ид, наименовние склада
     * @return array
     */
    static function ArraySklad_id_name( $sklad_id)
    {
            $item=Sprwhelement::find()
                ->select(['name'])
                ->asArray()
                ->where( ['id'=>(int) $sklad_id] )
                ->one();

            return $item['name'];

    }

    /**
     * Вернуть массив: ид, наименовние GROUP склада
     *
     * @param $group_sklad_id
     *
     * @return mixed
     */
    static function ArraySkladGroup_id_name($group_sklad_id)
    {
        $item=Sprwhtop::find()
            ->select(['name'])
            ->asArray()
            ->where( ['id'=>(int) $group_sklad_id] )
            ->one();

        return $item['name'];

    }


    /**
     * Получаем список-массив ИД-задействованых WH-TOP
     *=
     * в РЕЗУЛЬТАТАХ Инвентаризации
     * -
     * @return string
     */
    static function ArrayUniq_Wh_Ids()
    {
        $xx = self::find()->distinct('wh_destination');
        if (empty($xx)) {
            return '';
        }

        foreach ($xx as $item) {
            $array[(int)$item] = (int)$item;
        }
        return $array;
    }

    /**
     * Получаем список-массив ИД-задействованых WH-ELEMENT
     *=
     * в РЕЗУЛЬТАТАХ Инвентаризации
     * -
     * @return array
     */
    static function ArrayUniq_WhElements_Ids()
    {
        $xx=   self::find() ->distinct('wh_destination_element');

        //ddd($xx);

        foreach ($xx as $item){
           $array[(int)$item]=(int) $item;
        }
        return $array;
    }




}
