<?php

namespace frontend\models;


use Yii;
use yii\base\ExitException;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;


/**
 * Class Tz
 *
 * @package app\models
 */
class Tz extends ActiveRecord
{


    public $three;

    const STATE_NULL = 0;    // Статус не определен
    const STATE_IN_WORK = 1;    // Статус "В работе"
    const STATE_WORKED = 2;    // Статус "Выполнено.ОК."
    const STATE_TO_RETURN = 10;   // Статус "Верните на базу"
    const STATE_RETURNED = 11;   // Статус "Возвращено на базу"

    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [ Yii::$app->params[ 'vars' ], 'tz' ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [

            '_id',
            'id',

            'street_map',

            'dt_create',
            'dt_edit',
            'dt_deadline',
            'dt_deadline1',
            'dt_deadline2',

            'dt_create_timestamp',
            'dt_deadline_timestamp',

            'user_create_group_id',
            'user_create_id',
            'user_create_name',
            'user_edit_group_id',
            'user_edit_id',
            'user_edit_name',

            'user_id',
            'user_name',

            'name_tz',
            'name_tk',
            'multi_tz',

//            'tk_top'  ,
            'id_tk',
//            'id_tz'  ,

//            'tk_element'     ,
//            'tk_element_amort',

            'wh_deb_top',
            'wh_deb_top_name',
            'wh_deb_element',

            'wh_cred_top',
            'wh_cred_top_name',
            'wh_cred_element',

            'tx',
            'ed_izmer',
            'ed_izmer_num',

            'intelligent',

            'array_bus',
            'array_tk_amort',
            'array_tk',
            'array_casual',

            // 'array_bus_gosnum',
            // 'array_bus_boardnum',

            'captcha',
            'three',

            'status_state',
            'status_create_user',
            'status_create_date',

            'status_return',
            'status_return_create_user',
            'status_return_create_date',

            'array_bus',

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [

            [ [ 'id', ], 'unique' ],
            [ [ 'id', 'wh_cred_top' ], 'required' ],

//            [ [ 'array_bus' ], 'required', 'message' => 'Выбрать...' ],

            [ [ 'street_map' ], 'required', 'message' => 'Выбрать...' ],
            [ [ 'name_tz' ], 'required', 'message' => 'Заполнить...' ],
            [ [ 'name_tz' ], 'string', 'max' => 300 ],

            [ [ 'id', 'multi_tz' ], 'integer' ],

            [ [ 'multi_tz' ], 'integer', 'min' => 1, 'message' => 'Заполнить...' ],
            [ [ 'multi_tz' ], 'default', 'value' => 1 ],

            [ 'user_edit_group_id', 'default', 'value' => function () { return Yii::$app->getUser()->identity->group_id; } ],
            [ 'user_edit_id', 'default', 'value' => function () { return (integer)Yii::$app->user->identity->id; } ],
            [ 'user_edit_name', 'default', 'value' => function () { return Yii::$app->user->identity->username; } ],


            [ [
                'street_map',

                'dt_create',
                'dt_edit',
                'dt_deadline',
                'dt_deadline1',
                'dt_deadline2',

                'user_create_group_id',
                'user_create_id',
                'user_create_name',

                'user_edit_group_id',
                'user_edit_id',
                'user_edit_name',

                'user_id',
                'user_name',

                'name_tz',
                'name_tk',
                'multi_tz',

                'tk_top',
                'id_tk',
                'id_tz',

                'wh_deb_top',
                'wh_deb_top_name',
                'wh_deb_element',
                'wh_cred_top',
                'wh_cred_top_name',
                'wh_cred_element',

                'tx',
                'ed_izmer',
                'ed_izmer_num',

                'intelligent',

//                'array_bus',
//                'array_bus2',

                'array_tk_amort',
                'array_tk',
                'array_casual',

                'captcha',
                'three', // тройная кнопка


                // статус "В РАБОТЕ"
                // кто этот статус поставил
                // когда был принят статус

                'status_state',
                'status_create_user',
                'status_create_date',

                'status_return',
                'status_return_create_user',
                'status_return_create_date',

                'array_bus',

            ], 'safe' ],


            [
                [ 'array_bus' ],
                function ( $attribute, $params ) {

//                    [ [ 'array_bus' ], 'required', 'message' => 'Выбрать...' ],

                    if ( !is_array( $this[ 'array_bus' ] ) ) {
                        $this->addError(
                            $attribute, 'Выбрать...'
                        );
                    }

                    if ( is_array( $this[ 'array_bus' ] ) && empty( $this[ 'array_bus' ] ) ) {
                        $this->addError(
                            $attribute, 'Мало автобусов'
                        );
                    }
                    return $this[ 'array_bus' ];
                },
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
            'id' => '№ ТехЗадания',

            'street_map' => 'Дорожная карта',

            'dt_create' => 'Дата создания',
            'dt_deadline' => 'Крайняя Дата',
            'dt_deadline1' => 'DL1',
            'dt_deadline2' => 'DL2',

            'dt_create_timestamp' => 'Дата создания',
            'dt_deadline_timestamp' => 'Крайняя Дата',


            'dt_edit' => 'Дата правки',

            'user_create_id' => 'ID Создателя',
            'user_edit_id' => 'ID Редактора',
            'user_create_name' => 'Имя Создателя',
            'user_edit_name' => 'Имя Редактора',

            'user_create_group_id' => 'Group Create',
            'user_edit_group_id' => 'Group Edit',


            'user_id' => 'User Id',
            'user_name' => 'User Name',

            'name_tz' => 'Название ТехЗадания',
            'multi_tz' => 'Количество комплектов',
            'name_tk' => 'Типовой Комплект',


            'tk_top' => 'Типовой Комплект (ТК)', //Выбрано на странице ТехЗадания
            'id_tk' => 'Типовой Комплект', //Выбрано на странице ТехЗадания
            'id_tz' => 'Типовое задание (ТЗ)',  //Выбрано на странице ТехЗадания

//            'tk_element'        =>  'Простое списание',
//            'tk_element_amort'  =>  'Амортизация',


            'wh_deb_top' => 'Склад-источник',
            'wh_deb_top_name' => '',
            'wh_deb_element' => 'Элемент-источник',
            'wh_cred_top' => 'Склад-приемник',
            'wh_cred_top_name' => 'Автопарк',
            'wh_cred_element' => 'Элемент-приемник',

            'tx' => 'Tk комментарий',
            'ed_izmer' => 'Ед. изм',
            'ed_izmer_num' => 'Кол-во',

            'intelligent' => "Есть штрихкод",

            'array_bus' => 'Автобусы (список)',
            //'array_bus2'=>  'Автобусы (список)',
            'array_tk_amort' => 'Амортизация',
            'array_tk' => 'Списание',
            'array_casual' => 'Расходные материалы',

//            'array_bus_gosnum'  =>  'Гос.номера',
//            'array_bus_boardnum'  =>  'Борт.номера',

            'captcha' => 'Капча',
            'three' => 'тройная кнопка',


            'status_state' => 'Статус',
            'status_create_user' => 'В работу передал',
            'status_create_date' => 'Дата передачи в работу',    // Date()

            'status_return' => 'Команда ОТМЕНА',
            'status_return_create_user' => 'Автор ОТМЕНЫ',
            'status_return_create_date' => 'Команда ОТМЕНА',    // Date()


        ];
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getPvMotion()
    {
        return $this->hasMany( Pvmotion::className(), [ 'pv_id' => 'id' ] );
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getSklad()
    {
        return $this->hasMany( Sklad::className(), [ 'tz_id' => 'id' ] );
    }


    /**
     * @param $number_mongo
     * @return int
     */
    public static function Maxpv( $number_mongo )
    {
        global $number_mongo;
        $number_mongo = Pv::find()->max( 'number_mongo' );

        return $number_mongo;
    }


    /**
     * MODEL
     *
     * @param $id
     * @return array|null|ActiveRecord
     * @throws NotFoundHttpException
     */

    static function findModelDouble( $id )
    {
        if ( ( $model = static::find()
                ->where( [ 'id' => (integer)$id ] )
                ->one() ) !== null ) {
            return $model;
        }

        throw new NotFoundHttpException( 'findModelDouble. Этого нет БУФЕРЕ ПЕРЕДАЧИ ' );
    }

    /**
     * AsArray
     *
     * @param $id
     * @return array|null|ActiveRecord
     */
    static function findModelDoubleAsArray( $id )
    {
        return static::find()
            ->where( [ 'id' => (int)$id ] )
            ->asArray()
            ->one();
    }

    static function findOneModelWith( $id, $with_col, $array_select = [] )
    {

        if ( ( $model = static::find()
                ->select( $array_select )
                ->with( $with_col )
                ->asArray()
                ->where( [ 'id' => (int)$id ] )->one()

            ) !== null ) {
            return $model;
        }

        //        throw new NotFoundHttpException('findModelDoubleAsArray. Этого нет БУФЕРЕ ПЕРЕДАЧИ');
        return $model;
    }


    /**
     * @param int $id
     * @param array $array_select
     * @return array|ActiveRecord
     * @throws ExitException
     */
    static function findOneModelAsArrayToTz( $id = 0, $array_select = [] )
    {
        if ( ( $model = Tz::find()
                ->select( $array_select )
                ->where( [ 'id' => (int)$id ] )
                ->asArray()
                ->one()
            ) !== null ) {
            return $model;
        }

        throw new ExitException( 'Sklad 1 -  5555 с. Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад' );
    }


    /**
     * Добавить номмальный ИНДЕКС с понятной очередью
     * NORMALIZE ARRAY (Serg_M)
     *
     * @param $array_xx
     * @return array
     */
    static function setArrayToNormal( $array_xx )
    {
        if ( is_array( $array_xx ) ) {
            foreach ( $array_xx as $item ) {
                $as_normal[] = $item;
            }
            return $as_normal;
        } else
            return $array_xx;
    }


    /**
     * Вторая функция Сортировки
     * (немного меньше строк без ужатия)
     * АМОРТИЗАЦИЯ 1
     *
     * @param $array_xx
     * @return array
     */
    static function setArraySort1( $array_xx )
    {
        if ( isset( $array_xx ) && !empty( $array_xx ) ) {

            $sort_names = ArrayHelper::map(
                Spr_globam_element::find()
                    ->select( [ 'id', 'name' ] )
                    ->asArray()->all(), 'id', 'name'
            );
            //        dd($sort_names);

            if ( !empty( $sort_names ) ) {
                foreach ( $array_xx as $items ) {
                    if ( !empty( $items[ 'wh_tk_element' ] ) ) {
                        $items[ 'name' ] = $sort_names[ $items[ 'wh_tk_element' ] ];
                        $res_array[] = $items;
                    }
                }
            }
            //dd($res_array);

            ////////GOLDEN FUNC-twice
            if ( !isset( $res_array ) )
                return $array_xx;


            $keys = array_column( $res_array, 'name' );
            array_multisort( $keys, SORT_ASC, $res_array );
            //dd($res_array);

            return $res_array;
        } else
            return $array_xx;
    }

    /**
     * Первая функция Сортировки
     * ( УЖАЛ справочник перед работой )
     * СПИСАНИЕ 2
     *
     * @param $array_xx
     * @return array
     */

    static function setArraySort2( $array_xx )
    {
        if ( isset( $array_xx ) && !empty( $array_xx ) ) {

            $sort_names = ArrayHelper::map(
                Spr_glob_element::find()
                    ->select( [ 'id', 'name' ] )
                    ->asArray()->all(), 'id', 'name'
            );
            //        dd($sort_names);

            if ( !empty( $sort_names ) ) {
                foreach ( $array_xx as $items ) {
                    if ( !empty( $items[ 'wh_tk_element' ] ) ) {
                        $items[ 'name' ] = $sort_names[ $items[ 'wh_tk_element' ] ];
                        $res_array[] = $items;
                    }
                }
            }
            //dd($res_array);

            ////////GOLDEN FUNC-twice
            if ( !isset( $res_array ) )
                return $array_xx;


            $keys = array_column( $res_array, 'name' );
            array_multisort( $keys, SORT_ASC, $res_array );
            //dd($res_array);

            return $res_array;
        } else
            return $array_xx;
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
        $xx = static::find()->asArray()->max( 'id' );
        return ++$xx;
    }


}
