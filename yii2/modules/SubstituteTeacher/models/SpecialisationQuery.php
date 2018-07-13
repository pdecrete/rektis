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

        // $this->andWhere(['[[code]]' => \Yii::$app->controller->module->params['applicable-specialisation-codes']]);
        // $this->andWhere(['[[code]]' => \Yii::$app->getModule('SubstituteTeacher')->params['applicable-specialisation-codes']]);
        $this->andWhere(['OR',
            ['[[code]]' => \Yii::$app->getModule('SubstituteTeacher')->params['applicable-specialisation-codes']],
            ['[[code]]' => null]
        ]);
        return $this;
    }
}
