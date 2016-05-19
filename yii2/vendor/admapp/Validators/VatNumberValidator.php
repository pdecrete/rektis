<?php

namespace admapp\Validators;

use Yii;
use yii\validators\Validator;

/**
 * Description of VatNumberValidator
 *
 * @author Stavros spapad@gmail.com 
 */
class VatNumberValidator extends Validator
{

    public $allowEmpty = true;

    /**
     * Check validity of denoted attribute as a greek VAT number.
     * Raises error for attribute if check fails. 
     * 
     * @param yii\base\Model $model_object
     * @param string $attribute 
     * @return void
     */
    public function validateAttribute($model_object, $attribute)
    {
        $value = $model_object->$attribute;
        if ($this->allowEmpty && $this->isEmpty($value))
            return;

        if ($this->checkvat($value) === false) {
            $message = $this->message !== null ? $this->message : Yii::t('app', 'Value "{value}" for attribute {attribute} is not a valid VAT number.');
            $this->addError($model_object, $attribute, $message, array('{value}' => $value));
        }
    }

    /**
     * Check an AFM number validity
     * 
     * @param string|int $afm
     * @return boolean 
     */
    public static function checkvat($afm)
    {
        if (!is_string($afm) && is_numeric($afm)) {
            $afm = sprintf("%09d", $afm);
        }
        if (preg_match("/^([0-9]{9}$)/i", $afm) == 1) {
            $afm_num = intval($afm);
            $afm_sum = 0;
            $afm_last_digit = $afm_num % 10;

            $afm_num = (int) floor($afm_num / 10);
            for ($i = 8; $i > 0; $i--) {
                $afm_sum += ($afm_num % 10) * pow(2, (9 - $i));
                $afm_num = (int) floor($afm_num / 10);
            }

            $afm_mod = $afm_sum % 11;

            if (($afm_mod == $afm_last_digit) || (($afm_mod == 10) && ($afm_last_digit == 0))) {
                return true;
            }
        }
        return false;
    }

}
