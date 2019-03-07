<?php

namespace app\modules\eduinventory\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * TeacherSearch represents the model behind the search form about `app\models\Teacher`.
 */
class TeacherSearch extends Teacher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'school_id'], 'integer'],
            [['teacher_surname', 'teacher_name', 'teacher_fathername', 'teacher_mothername', 'teacher_registrynumber', 'teacher_afm', 'teacher_specialization'], 'safe'],
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
            'teacher_id' => $this->teacher_id,
            'school_id' => $this->school_id,
            'specialisation_id' => $this->specialisation_id,
        ]);

        $query->andFilterWhere(['like', 'teacher_surname', $this->teacher_surname])
            ->andFilterWhere(['like', 'teacher_name', $this->teacher_name])
            ->andFilterWhere(['like', 'teacher_fathername', $this->teacher_fathername])
            ->andFilterWhere(['like', 'teacher_mothername', $this->teacher_mothername])
            ->andFilterWhere(['like', 'teacher_registrynumber', $this->teacher_registrynumber])
            ->andFilterWhere(['like', 'teacher_afm', $this->teacher_afm]);

        return $dataProvider;
    }
}
