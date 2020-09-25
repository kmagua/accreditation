<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;
use yii\validators\Validator;

class AlNumValidator extends Validator
{
    /**
    * Validates the attribute of the object.
    * If there is any error, the error message is added to the object.
    * @param Model $model the object being validated
    * @param string $attribute the attribute being validated
    */
    public function validateAttribute($model, $attribute)
    {
        // check the strength parameter used in the validation rule of our model
        $value = str_replace(" ", "O", $model->$attribute);
        if (!ctype_alnum($value)) {
            $this->addError($model, $attribute, $model->getAttributeLabel($attribute) .' can only contain alpha numerical values.');
        }
    }
}