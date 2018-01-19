<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\SubstituteTeacher\models\TeacherRegistrySpecialisation;

/**
 * TeacherRegistrySpecialisationSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\TeacherRegistrySpecialisation`.
 */
class TeacherRegistrySpecialisationSearch extends TeacherRegistrySpecialisation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'registry_id', 'specialisation_id'], 'integer'],
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
        $query = TeacherRegistrySpecialisation::find();

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
            'specialisation_id' => $this->specialisation_id,
        ]);

        return $dataProvider;
    }
}
