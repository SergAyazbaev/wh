<?php

namespace frontend\models;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 *   Копия  старой базы Гайджет EXCEL
 *=
 * @property integer $id
 *
 * @property date $dt_akta
 * @property date $dt_job,
 * @property integer $dt_akta_ts
 * @property integer $dt_job_ts
 *
 * @property string $name_doc
 * @property string $wh_name
 * @property string $wh_ap
 * @property string $wh_gos_bort
 * @property string $gos_bort_old
 * @property integer $wh_id
 *
 * @property string $asuop_name
 * @property integer $asuop_id
 *
 * @property string $bar_code ??????????????
 * @property integer $pull_id ??????????????
 *
 * @property integer $ed_num
 * @property double $ed_cena
 * @property double $ed_sum
 *
 * @property string $naprav
 * @property integer $str_len
 * @property string $akt_str
 */
class Xls_base extends ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return [
            Yii::$app->params['vars'],
            'xls_base',
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'id',

            'dt_akta',
            'dt_job',
            'dt_akta_ts', ///
            'dt_job_ts',  ///


            'name_doc',

            'wh_name',
            'wh_ap',
            'wh_gos_bort',
            'gos_bort_old',
            'wh_id',    ///

            'asuop_name',
            'asuop_id', ///
            'bar_code',
            'pull_id',  ///

            'ed_num',
            'ed_cena',
            'ed_sum',

            'naprav',
            'str_len',
            'akt_str',
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                '_id',
                'id',

                'dt_akta',
                'dt_job',
                'dt_akta_ts', ///
                'dt_job_ts',  ///


                'name_doc',

                'wh_name',
                'wh_ap',
                'wh_gos_bort',
                'gos_bort_old',
                'wh_id',    ///

                'asuop_name',
                'asuop_id', ///
                'bar_code',
                'pull_id',  ///

                'ed_num',
                'ed_cena',
                'ed_sum',

                'naprav',
                'str_len',
                'akt_str',
                ], 'safe'],

            [['id'], 'required'],

            [[
                'ed_cena',
                'ed_sum',
                ], 'double'],

            [[
                'ed_cena',
                'ed_sum',
                ], 'integer'],

            [[
                'name_doc',
                'wh_name',
                'wh_ap',
                'wh_gos_bort',
                'gos_bort_old',

                'asuop_name',
                'bar_code',

                'naprav',
                'akt_str',
                ], 'string'],

        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),

        ];
    }


    /**
     * Вычисляем следующий новый ИД
     *=
     * @return int
     */
    public static function setNextMaxId()
    {
        $xx = static::find()->asArray()->max('id');
        return ++$xx;
    }


            //    public function beforeSave($insert)
            //    {
            //        if (parent::beforeSave($insert)) {
            //            if ($insert) {
            //                $this->date_create = time();
            //            }
            //            return true;
            //        } else {
            //            return false;
            //        }
            //    }

}
