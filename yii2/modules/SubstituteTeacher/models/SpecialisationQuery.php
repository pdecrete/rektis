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

        $this->andWhere(['[[code]]' => \Yii::$app->controller->module->params['applicable-specialisation-codes']]);
        return $this;
    }
}
