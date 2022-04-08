<?php

namespace yii\db;

use Yii;
use yii\base\Model;


/**
 * Недостающий класс Yii2 -> MongoDB
 * UPSERT()
 *
 * Class Upsert
 * @package yii\db
 */
//abstract class AppBaseModel extends Model implements ActiveRecordInterface
abstract class AppBaseModel extends Model implements ActiveRecordInterface
{


    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws Exception
     * @throws StaleObjectException
     */
    public function saveClassic($runValidation = true, $attributeNames = null)
    {
        if ($this->getIsNewRecord()) {
            return $this->insert($runValidation, $attributeNames);
        }

        return $this->update($runValidation, $attributeNames) !== false;
    }




}