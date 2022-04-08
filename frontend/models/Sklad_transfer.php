<?php /** @noinspection PhpUndefinedClassInspection */

//namespace frontend\models;
namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;

//use DateTime;
//use frontend\components\Vars;
//use yii\mongodb\ActiveRecord;
//use yii\web\NotFoundHttpException;


/**
 * Class Sklad_transfer
 * @package app\models
 */
class Sklad_transfer extends Sklad
{
    const TRANSFERED_NULL = 0; // Не принято РЕШЕНИЕ о передаче

    const TRANSFERED_OK = 1;   // Принята
    const TRANSFERED_REFUSE = 2;   // не принята. Отказался принимать

    const TRANSFERED_ERROR_1 = -1;
    const TRANSFERED_ERROR_2 = -2;
    const TRANSFERED_ERROR_3 = -3;


    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return [Yii::$app->params['vars'], 'sklad_transfer'];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [];  /// Не трогать!! В моем случае
    }

    /**
     * @return array
     */
    public function attributes()
    {

        return [
            '_id',
            'id',

            'sklad_vid_oper',
            'sklad_vid_oper_name',

            'dt_create',
            'dt_create_timestamp', // Индекс по нему

            'wh_home_number',  // ид текущего склада
            'wh_cs_number',


            'wh_debet_top',
            'wh_debet_name',
            'wh_debet_element',
            'wh_debet_element_name',

            'wh_destination',
            'wh_destination_name',
            'wh_destination_element',
            'wh_destination_element_name',

            "wh_dalee",
            "wh_dalee_element",
            "wh_dalee_name",
            "wh_dalee_element_name",

            'user_id',
            'user_ip',
            "user_name",
            "user_group_id",

            "tz_id",
            "tz_name",
            "tz_date",

            'dt_transfer_start_timestamp',// 'начало передачи (from Sklad)',
            'dt_transfer_start',    // начало передачи (from Sklad)

            'dt_transfer_end',      // завершение передачи (from Sklad)
            'dt_transfered_ok',     // принято получателем (to Sklad)
            'dt_transfered_user_id',
            'dt_transfered_user_name',


            "update_user_id",
            "update_user_name",
            "dt_to_work_signal",
            "dt_deadline",
            'dt_transfered_date',


            'array_tk_amort',
            'array_tk',
            'array_casual',
            'array_bus',
            'array_count_all',

            'tx',

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
//            'sklad_vid_oper' => 'ВидОп',
            'sklad_vid_oper_name' => 'Вид опер.',
            'dt_create' => 'Дата накладной',
            'dt_create_timestamp' => 'Дата созд.',
            'dt_update_timestamp' => 'Дата ред.',

            /// DOP Elements
            'user_name' => 'Автор',
            //'Автор',
            'user_group_id' => 'user_group_id',
            "update_user_id" => '',

            'wh_home_number' => 'База',
            // 'Id cклад',
            'prev_doc_number' => 'ид накладной Отправителя',
            'wh_top' => '№ Склада #',
            'wh_top_name' => 'Название склада #',
            'wh_element' => 'Склад ID',

            'wh_debet_top' => 'Компания-отправитель',
            'wh_debet_name' => 'Компания-отпр ИМЯ',
            'wh_debet_element' => 'Склад-отправитель',
            'wh_debet_element_name' => 'Склад-отпр ИМЯ',
            'wh_destination' => 'Компания-получатель',
            'wh_destination_name' => 'Компания-получатель',
            'wh_destination_element' => 'Склад-получатель',
            'wh_destination_element_name' => 'Склад-получатель',

            'dt_transfered_date' => 'Дата передачи накладной',
            'dt_transfered_user_name' => 'Получил',


            'tx' => 'Прим.',
            'username' => 'Автор',

            'dt_transfer_start_timestamp' => 'Начало передачи',
        ];

    }



    public function rules()
    {
        return [


            [[
                'dt_create',
//                'dt_update',
                'dt_transfer_start',
                'dt_transfer_end',
            ], 'date', 'format' => 'php:d.m.Y H:i:s'],

            ['dt_transfer_start', 'default', 'value' => function () {
                return date('d.m.Y H:i:s', strtotime('now'));
            }
            ],


            [
                [
                    'id',

                    'wh_home_number', // ид текущего склада

                    'sklad_vid_oper',
                    'sklad_vid_oper_name',

                    'wh_debet_top',
                    'wh_debet_name',
                    'wh_debet_element',
                    'wh_debet_element_name',

                    'wh_destination',
                    'wh_destination_name',
                    'wh_destination_element',
                    'wh_destination_element_name',

                    "wh_dalee",
                    "wh_dalee_element",

                    'user_id',
                    "user_name",
                    "user_group_id",

                    'array_tk_amort',
                    'array_tk',
                    'array_casual',
                    'array_bus',

                ],
                'safe'
            ],

            [
                [
                    // ПРИ ПЕРЕДАЧЕ накладных ОБЯЗАТЕЛЬНО
                    'wh_home_number', // ид текущего склада
//                    'sklad_vid_oper', // вид операции

                    'wh_debet_top',
                    'wh_debet_element',
                    'wh_destination',
                    'wh_destination_element',
                ],
                'required'
            ],

            [['id'], 'unique'],

            [[
                'id',
                'wh_home_number',
                'wh_cs_number',
                'dt_transfered_ok'

            ], 'integer'],     // ид накладной источника


//            ['tz_id',   'default', 'value' => 0 ],
//            ['tz_name', 'default', 'value' => '' ],
//            ['tz_date', 'default', 'value' => 0 ],


//            ['wh_debet_element_cs', 'default', 'value' => 0 ],
//            ['wh_destination_element_cs', 'default', 'value' => 0 ],

            [
                'user_ip',
                'default',
                'value' => function () {
                    return Yii::$app->request->getUserIP();
                },
            ],
            [
                'user_id',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->id;
                },
            ],
            [
                'user_name',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->username;
                },
            ],
            [
                'user_group_id',
                'default',
                'value' => function () {
                    return Yii::$app->user->identity->group_id;
                },
            ],

            [
                [
                    'array_tk_amort',
                    'array_tk',
                    'array_casual',
                    'array_bus',
                ],
                'default',
                'value' => [],
            ],




        ];
    }




/**
 * Связка с Таблицей  Sprwhelement
 * -
 *
 * @return ActiveQueryInterface
 */
public function getSprwhelement_wh_dalee_element()
{
    return $this->hasOne(Sprwhelement::className(), ['id' => 'wh_dalee_element']);

}

    /*
     * ПЕРЕДАТОЧНЫЙ БУФЕР
     * (передает накладные между складами)
     * SKLAD_TRANSFER
     */


    /**
     * БУФЕР ПЕРЕДАЧИ НАКЛАДНЫХ
     * =
     * ТОЛЬКО РАСХОДНЫЕ(!) НАКЛАДНЫЕ     * ПОМЕЩАЕМ В БУФЕРНУЮ ТАБЛИЦУ
     * -
     *
     * @param $array
     * @return array|bool
     * @throws NotFoundHttpException
     */
    static function setTransfer($array)
    {
        if (is_object($array) && !is_array($array)) {
            //
            // ТОЛЬКО!!! Расходная накладная
            //
            if ($array['sklad_vid_oper'] <> 3) {
                throw new NotFoundHttpException('Отправляем только РАСХОДНЫЕ НАКЛАДНЫЕ(!). ');
            }

            //
            // ТОЛЬКО!!! Cо своего склада
            //
            if ($array['wh_debet_element'] <> Sklad::getSkladIdActive()) {
                throw new NotFoundHttpException('Расходная накладная должна быть ТОЛЬКО со своего склада(!). ');
            }

            //
            // ТОЛЬКО!!! Расходная накладная НЕ МОЖЕТ быть на СВОЙ склад!
            //
            if ($array['wh_destination_element'] == Sklad::getSkladIdActive()) {
                throw new NotFoundHttpException('Расходная накладная НЕ МОЖЕТ быть на СВОЙ склад(!). ');
            }


            $sklad_transfer = new Sklad_transfer();


            $sklad_transfer->id = $array->id;
            $sklad_transfer->dt_transfer_start = Date("d.m.Y H:i:s", strtotime("now"));
            $sklad_transfer->dt_create = $array->dt_create;
            $sklad_transfer->dt_create_timestamp = $array->dt_create_timestamp;
//            $sklad_transfer->dt_update = $array->dt_update;

            $sklad_transfer->dt_transfer_start = Date('d.m.Y H:i:s', strtotime("now")); // 'начало передачи (from Sklad)',
            $sklad_transfer->dt_transfer_start_timestamp = strtotime("now"); // 'начало передачи (from Sklad)',


//            $sklad_transfer->prev_doc_number = $array->id;

            $sklad_transfer->user_id = $array->user_id;
            $sklad_transfer->user_name = $array->user_name;

            $sklad_transfer->sklad_vid_oper = $array->sklad_vid_oper;
            $sklad_transfer->sklad_vid_oper_name = $array->sklad_vid_oper_name;

            $sklad_transfer->wh_debet_top = (int)$array->wh_debet_top;
            $sklad_transfer->wh_debet_name = $array->wh_debet_name;
            $sklad_transfer->wh_debet_element = (int)$array->wh_debet_element;
            $sklad_transfer->wh_debet_element_name = $array->wh_debet_element_name;

            $sklad_transfer->wh_destination = (int)$array->wh_destination;
            $sklad_transfer->wh_destination_name = $array->wh_destination_name;
            $sklad_transfer->wh_destination_element = (int)$array->wh_destination_element;
            $sklad_transfer->wh_destination_element_name = $array->wh_destination_element_name;

            $sklad_transfer->wh_dalee = $array->wh_dalee;
            $sklad_transfer->wh_dalee_element = $array->wh_dalee_element;

            if (isset($array->tz_id) && $array->tz_id > 0) {
                $sklad_transfer->tz_id = $array->tz_id;
                $sklad_transfer->tz_name = $array->tz_name;
                $sklad_transfer->tz_date = $array->tz_date;
            }

            $sklad_transfer->array_tk_amort = $array->array_tk_amort;
            $sklad_transfer->array_tk = $array->array_tk;
            $sklad_transfer->array_casual = $array->array_casual;
            $sklad_transfer->array_bus = $array->array_bus;

            $sklad_transfer->wh_home_number = (int)$array->wh_destination_element; /// WH HOME NUMBER

            $sklad_transfer->dt_transfered_ok = (int)self::TRANSFERED_NULL;


            //            ///////////////
            //            /// СКЛАД - ПРИЕМНИК
            //            // Является ЛИ Целевым Складом (ЦС)
            //            // и накладная = РАСХОДНАЯ от Установщика -- СТАНОВИТСЯ ПРИХОДНОЙ на ЦС
            //            ///////////////
            //            if(
            //                Sprwhelement::is_FinalDestination( [$sklad_transfer->wh_destination_element] ) &&
            //                $sklad_transfer->sklad_vid_oper == Sklad::VID_NAKLADNOY_RASHOD
            //            ) {
            //	            //
            //	            //	            //            	ddd($sklad_transfer['id']);
            //	            //
            //	            //	            $new_doc = Sklad::find()
            //	            //	                            ->where( [ 'id' => ( $sklad_transfer[ 'id' ] ) ] )
            //	            //	                            ->one();
            //	            //
            //	            //
            //	            //	            //ddd($sklad_transfer['wh_destination_element']);
            //	            //
            //	            //	            $new_doc->wh_cs_number = (int) $sklad_transfer[ 'wh_destination_element' ]; // ЦС
            //	            //
            //	            //                $new_doc->setDtCreateText( "NOW" );// TIMESTAMP
            //	            //
            //	            //	            //                    $new_doc->sklad_vid_oper = Sklad::VID_NAKLADNOY_PRIHOD;            // ПРИХОД (приходная накладная)
            //	            //	            //                    $new_doc->sklad_vid_oper_name = Sklad::VID_NAKLADNOY_PRIHOD_STR;   // ПРИХОД (приходная накладная)
            //	            //	            //  $new_doc->user_id = (int)Yii::$app->getUser()->identity->id;
            //	            //	            //  $new_doc->user_name = Yii::$app->getUser()->identity->username;
            //	            //
            //	            //                //date_default_timezone_set ("Asia/Almaty");
            //	            //
            //	            //                $new_doc->dt_update = date('d.m.Y H:i:s', strtotime('now'));
            //	            //
            //	            //
            //	            //                // Является Целевым Складом (ЦС)
            //	            //                $new_doc->wh_destination_element_cs=1;
            //	            //                ///////////////
            //	            //
            //	            //
            //	            //                /// То самое преобразование ПОЛЯ Милисукунд
            //	            //	            //$new_doc->setDtCreateText( $new_doc->dt_create );
            //	            //
            //	            //
            //	            //                //ddd($new_doc);
            //	            //
            //	            //                if (!$new_doc->save(true)) {
            //	            //                    ddd($new_doc->errors);
            //	            //                }
            //	            //
            //	            //
            //	            //                // В БУФЕРЕ ОТМЕТКА. ЯВЛЯЕТСЯ Целевым складом
            //	            //                $sklad_transfer->wh_destination_element_cs = 1 ; // ЦС
            //	            //	            $sklad_transfer->wh_cs_number              = (int) $new_doc->wh_destination_element; // ЦС
            //	            //
            //	            //                ///В БУФЕРЕ ОТМЕТКА. ДОСТАВЛЕНО
            //	            //                $sklad_transfer->dt_transfered_ok = self::TRANSFERED_OK;  /// ??? может быть = ДВУХЭТАПНАЯ ФИНАЛОЧКА ????
            //	            //
            //
            //
            //            }


            // ddd($sklad_transfer);

            if ($sklad_transfer->save(true)) {
                return true;
            } else {
                return ($sklad_transfer->errors);
            }


        }

//        else
//            throw new NotFoundHttpException('setTransfer(). Нужна модель');

        return true;
    }


    /**
     * SKLAD-TRANSFER
     * -
     * Запись-отметка о ПРИНЯТИИ или НЕПРИНЯТИИ накладной
     * =
     *
     * @param $id
     * @param int $yes_or_no
     * @return array|bool
     * @throws NotFoundHttpException
     */
    static function setTransfer_delivered($id = 1, $yes_or_no = -1)
    {

        $sklad_transfer = static::findModel($id); // _id

        $sklad_transfer->dt_transfer_end = Date('d.m.Y H:i:s', strtotime('now' . ' +3 seconds'));
        // завершилась передача (from Sklad)
        $sklad_transfer->dt_transfered_user_id = (integer)Yii::$app->getUser()->identity->id;;
        $sklad_transfer->dt_transfered_user_name = Yii::$app->getUser()->identity->username;

        switch ($yes_or_no) {
            case self::TRANSFERED_OK :
                $sklad_transfer->dt_transfered_ok = self::TRANSFERED_OK;
                // принято получателем
                break;
            case self::TRANSFERED_REFUSE :
                $sklad_transfer->dt_transfered_ok = self::TRANSFERED_REFUSE;
                // НЕ принято получателем
                break;
            case self::TRANSFERED_ERROR_1 :
                $sklad_transfer->dt_transfered_ok = self::TRANSFERED_ERROR_1;
                // Ошибка
                break;
            default :
                $sklad_transfer->dt_transfered_ok = self::TRANSFERED_NULL;
                // принято получателем
                break;
        }


        //ddd($sklad_transfer);
        if ($sklad_transfer->save(true)) {
            //   dd($sklad_transfer);
            return $sklad_transfer;
        } else {
            ddd($sklad_transfer->errors);
            return ($sklad_transfer->errors);
        }

    }


    /**
     * @param $id
     * @return Sklad_transfer|array|null
     * @throws NotFoundHttpException
     */
    static function findModel($id)
    {
        if ((
            $model = Sklad_transfer::find()
                ->where(['_id' => $id])
                ->one()
            ) !== null) {

            return $model;
        }

        throw new NotFoundHttpException('Sklad_Transfer 1 "' . $id . '"-   Ответ на запрос. Этого id нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад');
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

        throw new NotFoundHttpException('Sklad_Transfer 2 -  findModelDouble. Этого нет БУФЕРЕ ПЕРЕДАЧИ со склада на склад');
    }

    /**
     * /// Проверим, есть или нет непринятые накладные Сколько и за какой период???
     *=
     * @param $sklad
     * @return array|null|ActiveRecord
     * @throws \yii\mongodb\Exception
     */
    static function findOld_transfers($sklad)
    {
        $array_res = [];
        $data_1days = Date('d.m.Y 00:00:00', strtotime('now -1 days'));
        $data_3days = Date('d.m.Y 00:00:00', strtotime('now -3 days'));
        $data_10days = Date('d.m.Y 00:00:00', strtotime('now -10 days'));

        ///
        ///
        $count_1 = self::find()
            ->select(['id', 'wh_home_number', 'dt_create', 'dt_transfered_ok'
                //        'dt_transfer_start'
            ])
            ->where(
                ['AND',
                    ['==', 'wh_home_number', $sklad],
                    ['>', 'dt_create', $data_1days],
                    ['==', 'dt_transfered_ok', self::TRANSFERED_NULL], // Не принято РЕШЕНИЕ о передаче
                ]
            )
            ->count();
        $array_res['count_1'] = $count_1;
        ///
        ///
        $count_3 = self::find()
            ->select(['id', 'wh_home_number', 'dt_create', 'dt_transfered_ok'
                //        'dt_transfer_start'
            ])
            ->where(
                ['AND',
                    ['==', 'wh_home_number', $sklad],
                    ['>', 'dt_create', $data_3days],
                    ['==', 'dt_transfered_ok', self::TRANSFERED_NULL], // Не принято РЕШЕНИЕ о передаче
                ]
            )
            ->count();
        $array_res['count_3'] = $count_3;

        ///
        ///
        $count_10 = self::find()
            ->select(['id', 'wh_home_number', 'dt_create', 'dt_transfered_ok'
                //        'dt_transfer_start'
            ])
            ->where(
                ['AND',
                    ['==', 'wh_home_number', $sklad],
                    ['>', 'dt_create', $data_10days],
                    ['==', 'dt_transfered_ok', self::TRANSFERED_NULL], // Не принято РЕШЕНИЕ о передаче
                ]
            )
            ->count();

        //ddd($count_10);
        $array_res['count_10'] = $count_10;

        return $array_res;
    }

}
