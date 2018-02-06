<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\SubstituteTeacher\models\PlacementPreference;

/**
 * PlacementPreferenceSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\PlacementPreference`.
 */
class PlacementPreferenceSearch extends PlacementPreference
{
    public $year; // filter by teacher year catalog

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'prefecture_id', 'school_type', 'order', 'year'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PlacementPreference::find()
            ->joinWith('teacher');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'teacher_id' => $this->teacher_id,
            'prefecture_id' => $this->prefecture_id,
            'school_type' => $this->school_type,
            'order' => $this->order,
            '{{%stteacher}}.year' => $this->year,
        ]);

        return $dataProvider;
    }
}
