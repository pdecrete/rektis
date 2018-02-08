<?php

namespace app\modules\SubstituteTeacher\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TeacherSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\Teacher`.
 */
class TeacherSearch extends Teacher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'registry_id', 'year', 'status'], 'integer'],
            [['points'], 'number'],
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
        $query = Teacher::find();

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
            'registry_id' => $this->registry_id,
            'year' => $this->year,
            'status' => $this->status,
            'points' => $this->points,
        ]);

        return $dataProvider;
    }
}
