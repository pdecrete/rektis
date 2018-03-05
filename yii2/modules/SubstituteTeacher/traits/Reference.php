<?php
namespace app\modules\SubstituteTeacher\traits;

use yii\helpers\Json;

/**
 */
trait Reference
{

    /**
     * Make a reference value for specific fields of this model
     */
    public function buildSelfReference($fields = 'id')
    {
        if (!is_array($fields)) {
            $fields = [(string)$fields];
        }
        $refs = array_map(function ($field) {
            return $this->{$field};
        }, $fields);
        return $this->buildReference($refs);
    }

    /**
     * Generate a crypted reference string for the designated string or array.
     *
     * @param string|array $to_ref string to build crypted reference for
     */
    public function buildReference($to_ref)
    {
        return \Yii::$container->get('Crypt')->encrypt(Json::encode($to_ref));
    }
}
