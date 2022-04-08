<?php

namespace common\validators;

//namespace yii\mongodb\validators;
//use yii\validators\Validator;
//use yii\validators\Validator;
//use yii\validators\Validator;

use yii\validators\Validator;

/** @noinspection PhpClassNamingConventionInspection */


/**
 * Class EmbedDocValidator
 * @package common\validators
 */
class EmbedDocValidator extends Validator
{
    public $scenario;
    public $model;

    public function validateAttribute($object, $attribute)
    {
        //$attr = $object->{$attribute};
        $attr = $object->$attribute;

        if (is_array($attr)) {
            $model = new $this->model;
            if ($this->scenario) {
                $model->scenario = $this->scenario;
            }
            $model->attributes = $attr;
            if (!$model->validate()) {
                foreach ($model->getErrors() as $errorAttr) {
                    foreach ($errorAttr as $value) {
                        $this->addError($object, $attribute, $value);
                    }
                }
            }
        } else {
            $this->addError($object, $attribute, 'should be an array');
        }
    }

}
