<?php

namespace app\modules\SubstituteTeacher\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TeacherSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\Teacher`.
 */
class TeacherSearch extends Teacher
{
    public $specialisation_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'registry_id', 'year', 'public_experience', 'smeae_keddy_experience', 'disabled_children', 'specialisation_id'], 'integer'],
            [['disability_percentage'], 'integer', 'min' => 0, 'max' => 100],
            [['three_children', 'many_children'], 'boolean']
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

        if (!empty($this->specialisation_id)) {
            $query = $query->joinWith([
                'registry.teacherRegistrySpecialisations'
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            self::tableName() . '.registry_id' => $this->registry_id,
            'year' => $this->year,
            'public_experience' => $this->public_experience, 
            'smeae_keddy_experience' => $this->smeae_keddy_experience,
            'disabled_children' => $this->disabled_children,
            'disability_percentage' => $this->disability_percentage,
            'three_children' => $this->three_children, 
            'many_children' => $this->many_children,
            TeacherRegistrySpecialisation::tableName() . '.specialisation_id' => $this->specialisation_id
        ]);

        return $dataProvider;
    }
}
