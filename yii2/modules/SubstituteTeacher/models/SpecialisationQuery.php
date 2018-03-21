<?php

namespace app\modules\SubstituteTeacher\models;

/**
 * @inheritdoc
 */
class SpecialisationQuery extends \app\models\SpecialisationQuery
{
    public function init()
    {
        parent::init();

        $this->andWhere(['[[code]]' => \Yii::$app->controller->module->params['applicable_specialisation_codes']]);
        return $this;
    }
}
