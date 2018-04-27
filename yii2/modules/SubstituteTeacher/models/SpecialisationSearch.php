<?php

namespace app\modules\SubstituteTeacher\models;

use yii\base\Model;

/**
 * @inheritdoc
 */
class SpecialisationSearch extends \app\models\SpecialisationSearch
{
    /**
     * @inheritdoc
     */
    public function search($params)
    {
        $query = Specialisation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
