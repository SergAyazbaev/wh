<?php

namespace frontend\models;

use Yii;
use yii\base\ExitException;
use yii\db\ActiveQueryInterface;
use yii\helpers\ArrayHelper;
use yii\mongodb\ActiveRecord;

/**
 * @property integer $id
 * @property integer $tn
 * @property integer $ticket_state_id
 *
 */
class Crm extends ActiveRecord
{
    public $count_xx; // Иногда использую счетчик в ЗАпросах SQL


    const SCENARIO_CREATE_NEW = 'create';
    const SCENARIO_CLOSE_TICKET = 'close';
    //const SCENARIO_UPDATE = self::SCENARIO_DEFAULT;
    //const SCENARIO_DEFAULT = self::SCENARIO_CREATE_NEW;


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'crm',
        ];

    }

    public function scenarios()
    {
        //        $scenarios = parent::scenarios();
        //        $scenarios[self::SCENARIO_DEFAULT] = [
        //        ];
        $scenarios[self::SCENARIO_CREATE_NEW] = [
            'caller_id_hide',//
            'opername',//
            'inputPhone',//
            'needCallBack',//
            'inputName',//
            'inputEmail',//
            'inputLogin',//
            'commentArea',//!!!!!!!!! comment-area
            'optionsRadios',//
            'owner',//

            'inputType',//
            'routeNumber',//
            'oezap',//
            'companyName',//
            'stateNumber',//
            'responsible',//

            'gos_bort',//
            'nameTechnics'
        ];

        $scenarios[self::SCENARIO_CLOSE_TICKET] = [
            'id',
            'tn',
            'ticket_state_id',
            'opername',

            'Action',//
            'SubjectError',//
            'SubjectServerError',//
            'RichTextError',//
            'RichTextServerError',//
            'StateID',//
        ];
        return $scenarios;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'id',
            'tn',

            'ticket_state_id',


            'dt_create',
            'dt_update',
            'dt_create_timestamp',
            'dt_update_timestamp',

            'user_id',
            'user_ip',
            "user_name",
            "user_group_id",

            "update_user_id",
            "update_user_name",

            'update_points',// массив всех людей, которые сохраняли эту накладную когда-либо

            'caller_id_hide',//
            'opername',//
            'inputPhone',//
            'needCallBack',//
            'inputName',//
            'inputEmail',//
            'inputLogin',//
            'commentArea',//!!!!!!!!! comment-area
            'optionsRadios',//
            'owner',//

            'inputType',//
            'routeNumber',//
            'oezap',//
            'companyName',//
            'stateNumber',//
            'responsible',//

            'gos_bort',//
            'nameTechnics',//

            'Action',//
            'Email',//
            'InReplyTo',//
            'References',//
            'From',//
            'ToCustomer',//

            'SubjectError',//
            'SubjectServerError',//
            'RichTextError',//
            'RichTextServerError',//
            'StateID',//
            'DynamicField_statePE',//
            'DynamicField_actions',//

            'Day',//
            'Month',//
            'Year',//
        ];

    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            '_id' => '_ID',
            'id' => '№',
            'tn' => 'TN№',

            'dt_create' => 'Дата накладной',
            'dt_update' => 'Дата исправления',
            'dt_create_timestamp' => 'Дата созд.',
            'dt_update_timestamp' => 'Дата ред.',

            'user_id' => 'user_id',
            'user_ip' => 'IP',
            'user_name' => 'Автор',

            'username' => 'Автор',
            'update_user_name' => 'Ред.',
            ///
            'caller_id_hide' => 'Абонент',
            'opername' => 'Диспетчер',
            'inputPhone' => 'Номер для отзвона',
            'inputName' => 'Ф.И.О.',
            'needCallBack' => 'Нужен отзвон?',
            'inputEmail' => 'Email-уведомления',
            'inputLogin' => 'Клиент',
            'commentArea' => 'Суть обращения',

            'optionsRadios' => 'Дальнейшие действия с заявкой',
            'owner' => 'Ответственный',

            'inputType' => 'Тип заявки',
            'routeNumber' => 'Номер маршрута',
            'oezap' => 'Класс заявки',
            'companyName' => 'Предприятие',
            'stateNumber' => 'Гос/борт номер',
            'responsible' => 'Ответственный',

            'gos_bort' => 'Номер Борт/Гос',

            'nameTechnics' => 'Техник',//???

            'Action'=> 'Те',//???

            'Email'=> 'Email',
            'InReplyTo'=> 'поле',
            'References'=> 'поле2',
            'From'=> 'От кого',
            'ToCustomer'=> 'Кому',

            'SubjectError'=> 'Те',//???
            'SubjectServerError'=> 'Те',//???
            'RichTextError'=> 'Те',//???
            'RichTextServerError'=> 'Те',//???
            'StateID'=> 'Те',//???

        ];

    }

    /**
     * Создаю ТаймШТАМП для всех операций Создания накладных и Редактирования
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['dt_update_timestamp'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['dt_update_timestamp'],
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

            [[
                'opername',
                'inputPhone',
                'inputName',

                'companyName',
                'routeNumber',
                'stateNumber',
                'oezap',

            ],'required','message' => 'Заполнить...'],

            [[
                'id',
                'ticket_state_id',
                'opername',
            ],'required','message' => 'Заполнить...','on'=>self::SCENARIO_CLOSE_TICKET],


            ///SAFE
            [[

                'tn',//
                'caller_id_hide',//
                'opername',//
                'inputPhone',//
                'needCallBack',//
                'inputName',//
                'inputEmail',//
                'inputLogin',//
                'commentArea',//!!!!!!!!! comment-area
                'optionsRadios',//
                'owner',//

                'inputType',//
                'routeNumber',//
                'oezap',//
                'companyName',//
                'stateNumber',//
                'responsible',//

                'nameTechnics',//

            ], 'safe'],                 // , 'on' => self::SCENARIO_DEFAULT],

            [[

                'tn',//
                'ticket_state_id',//

            ], 'safe', 'on' => self::SCENARIO_CLOSE_TICKET],


            [['id',], 'unique'],
            [[
                'id',
                'ticket_state_id',
                ], 'integer',
                'min' => 1,
                'message' => 'Id_Crm == 0   ... ERROR!'],

            [['dt_create', 'dt_update',],
                'date',
                'format' => 'php:d.m.Y H:i:s'],

            [['dt_create'], 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }],

            [['dt_update'], 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }],

            [['user_ip'], 'default', 'value' => function () {
                return Yii::$app->request->getUserIP();
            }],

            [['user_id'], 'default', 'value' => function () {
                return Yii::$app->user->identity->id;
            }],

            [['user_name'], 'default', 'value' => function () {
                return Yii::$app->user->identity->username;
            }],

            [['update_user_id'], function () {
                $this->update_user_id = Yii::$app->user->identity->id;
            }],

            [['update_user_name'], function () {
                $this->update_user_name = Yii::$app->user->identity->username;
            }],

            [['user_group_id'], function () {
                $this->user_group_id = Yii::$app->user->identity->group_id;
            }],


            ///
            ///  Инициализация update_points
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
        ];

    }



    /**
     * findModelAsArray
     * =
     * @param int $id
     * @return array|ActiveRecord|null
     */
    static function findModelAsArray($id = 0)
    {
        return Sklad::find()
            ->where(['id' => (int)$id])
            ->asArray()
            ->one();
    }


    /**
     * setNext_max_id()
     * =
     *
     * @return int
     */
    public static function setNextMaxId()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;

    }

    /**
     * Связка с Таблицей  Sprwhelement
     * -
     *
     * @return ActiveQueryInterface
     */
    public function getSprwhelement_home_number()
    {
        return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_home_number']);

    }


    public function getSprwhTopName()
    {
        return $this->hasOne(Sprwhtop::className(), ['parent_id' => 'id'])
            ->via('sprwhelement_wh_cs_number'); // Имя связи которая объявлена выше

    }


    /**
     * Возвращает массив номеров складов (БАЗ) по накладным
     * -
     *
     * @return array
     */
    static function ArraySklad_Uniq_wh_numbers()
    {
        return self::find()
            ->distinct('wh_home_number');

    }


    /**
     * Запрос в ВШЭ По заданному массиву ИД-шников
     * Для выпадаюшего списка картик Select2  (не мульти)
     * =
     *
     * @param $input_array
     * @return array
     */
    static function ListFromInput_array_sprwhelement($input_array)
    {

        return [0 => 'Выбрать все'] + ArrayHelper::map(
                Sprwhelement::find()
                    ->select(
                        [
                            'id',
                            'name',
                        ]
                    )
                    ->orderBy('name')
                    ->asArray()
                    ->where(['id' => $input_array])
                    ->all(),
                'id', 'name'
            );

    }


    /**
     * Модель одной накладной
     * =
     * where(['id'=>(integer) $id])->one()
     * -
     *
     * @param $id
     * @return array|null|ActiveRecord
     * @throws ExitException
     */
    static function findModelDouble($id)
    {
        if (($model = static::find()->where(['id' => (integer)$id])->one()) !== null) {
            return $model;
        }

        throw new ExitException('Sklad 2 -  Ответ на запрос. Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад');

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

}