<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;
use yii\validators\Validator;

class AlphaValidator extends Validator
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
        if (!ctype_alpha($model->$attribute)) {
            $this->addError($model, $attribute, $model->getAttributeLabel($attribute) .' must be alphabetical.');
        }
    }
}